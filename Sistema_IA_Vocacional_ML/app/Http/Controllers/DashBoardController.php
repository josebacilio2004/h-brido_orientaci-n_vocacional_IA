<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TestResult;
use App\Models\Career;
use App\Models\VocationalTest;
use App\Models\User;
use App\Models\AIPrediction;
use App\Models\PersonalityResult;

class DashboardController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    private function isCounselorOrAdmin()
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'counselor']);
    }

    private function isPsychologistOrAdmin()
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'psychologist']);
    }

    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            abort(403, 'No tienes permisos de administrador para acceder a esta sección.');
        }
    }

    private function requireCounselorAccess()
    {
        if (!$this->isCounselorOrAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    private function requirePsychologistAccess()
    {
        if (!$this->isPsychologistOrAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            $completedTests = TestResult::where('user_id', $user->id)
                ->pluck('vocational_test_id')
                ->toArray();
            
            $totalTests = VocationalTest::obtenerActivos();
            
            return view('dashboard.index', compact('user', 'completedTests', 'totalTests'));
        } catch (\Exception $e) {
            Log::error('Error en dashboard index: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar el dashboard.');
        }
    }

    // Nuevos métodos para el dashboard de orientadores
    public function counselorDashboard()
    {
        $this->requireCounselorAccess();

        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            $totalStudents = User::where('role', 'student')->count();
            $completedTests = TestResult::whereNotNull('completed_at')->count();
            $averageScore = TestResult::whereNotNull('total_score')->avg('total_score');
            $totalPredictions = AIPrediction::count();
            
            $recentStudents = User::where('role', 'student')
                ->with(['testResults' => function($query) {
                    $query->latest('completed_at')->first();
                }])
                ->latest()
                ->take(10)
                ->get()
                ->map(function($student) {
                    $lastTest = $student->testResults->first();
                    return (object)[
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'last_test_name' => $lastTest ? ($lastTest->vocational_test->name ?? 'N/A') : 'N/A',
                        'last_test_date' => $lastTest ? $lastTest->completed_at : null,
                        'status' => $lastTest ? true : false
                    ];
                });

            return view('counselor.dashboard', compact(
                'user', // ← AGREGAR ESTA VARIABLE
                'totalStudents',
                'completedTests',
                'averageScore',
                'totalPredictions',
                'recentStudents'
            ));
        } catch (\Exception $e) {
            Log::error('Error en counselor dashboard: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar el dashboard de orientadores.');
        }
    }

    public function counselorStudents()
    {
        $this->requireCounselorAccess();

        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            $students = User::where('role', 'student')
                ->with(['testResults', 'aiPredictions'])
                ->paginate(20);
            
            return view('counselor.students', compact('user', 'students')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en counselor students: ' . $e->getMessage());
            return redirect()->route('counselor.dashboard')->with('error', 'Error al cargar la lista de estudiantes.');
        }
    }

    public function studentPrediction($id)
    {
        $this->requireCounselorAccess();

        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            $student = User::where('role', 'student')->findOrFail($id);
            $latestResult = TestResult::where('user_id', $id)
                ->orderBy('completed_at', 'desc')
                ->first();
            
            if (!$latestResult) {
                return redirect()->route('counselor.students')->with('error', 'El estudiante aún no ha completado ningún test.');
            }

            $interests = $student->interestResponses()
                ->with('question')
                ->get()
                ->groupBy(function($response) {
                    return $response->question->category;
                });

            $skills = $student->skillResponses()
                ->with('question')
                ->get()
                ->groupBy(function($response) {
                    return $response->question->category;
                });

            return view('counselor.student-prediction', compact('user', 'student', 'latestResult', 'interests', 'skills')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en student prediction: ' . $e->getMessage());
            return redirect()->route('counselor.students')->with('error', 'Error al cargar la predicción del estudiante.');
        }
    }

    // Nuevos métodos para el dashboard de psicólogos
    public function psychologistDashboard()
    {
        $this->requirePsychologistAccess();

        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA (SOLUCIÓN PRINCIPAL)

            // Estadísticas de tests
            $testStats = [
                'total' => TestResult::count(),
                'completed' => TestResult::whereNotNull('completed_at')->count(),
                'inProgress' => TestResult::whereNull('completed_at')->count()
            ];

            // Estadísticas de perfiles
            $totalProfiles = PersonalityResult::count();
            $profileStats = [
                'total' => $totalProfiles,
                'highPerformance' => PersonalityResult::where('total_score', '>=', 75)->count(),
                'developing' => PersonalityResult::where('total_score', '<', 75)->count()
            ];

            // Estadísticas de predicciones
            $predictionStats = [
                'total' => AIPrediction::count(),
                'highConfidence' => AIPrediction::where('confidence', '>=', 0.8)->count(),
                'needsReview' => AIPrediction::where('confidence', '<', 0.8)->count()
            ];

            // Carreras más recomendadas
            $topCareers = [];
            if ($predictionStats['total'] > 0) {
                $topCareers = AIPrediction::select('predicted_career')
                    ->selectRaw('COUNT(*) as count')
                    ->groupBy('predicted_career')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get()
                    ->map(function($career) use($predictionStats) {
                        $percentage = $predictionStats['total'] > 0 ? round(($career->count / $predictionStats['total']) * 100) : 0;
                        return [
                            'name' => $career->predicted_career,
                            'count' => $career->count,
                            'percentage' => $percentage
                        ];
                    })->toArray();
            }

            // Tipos de personalidad más comunes
            $personalityTypes = [];
            if ($totalProfiles > 0) {
                $personalityTypes = PersonalityResult::select('primary_type')
                    ->selectRaw('COUNT(*) as count')
                    ->groupBy('primary_type')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get()
                    ->map(function($type) use($totalProfiles) {
                        $percentage = $totalProfiles > 0 ? round(($type->count / $totalProfiles) * 100) : 0;
                        return [
                            'name' => $type->primary_type,
                            'count' => $type->count,
                            'percentage' => $percentage
                        ];
                    })->toArray();
            }

            return view('psychologist.dashboard', compact(
                'user', // ← AGREGAR ESTA VARIABLE (SOLUCIÓN PRINCIPAL)
                'testStats',
                'profileStats',
                'predictionStats',
                'topCareers',
                'personalityTypes'
            ));
        } catch (\Exception $e) {
            Log::error('Error en psychologist dashboard: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Error al cargar el dashboard de psicólogos.');
        }
    }

    public function psychologistAnalytics()
    {
        $this->requirePsychologistAccess();

        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            // Implementar análisis detallados adicionales según sea necesario
            return view('psychologist.analytics', compact('user')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en psychologist analytics: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'Error al cargar los análisis.');
        }
    }

    public function tests()
    {
        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            return view('dashboard.tests', compact('user')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en dashboard tests: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar tests.');
        }
    }

    public function careers()
    {
        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            $careers = Career::all();
            $faculties = Career::distinct()
                ->pluck('faculty')
                ->filter()
                ->values()
                ->toArray();
            
            return view('dashboard.careers', compact('user', 'careers', 'faculties')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en dashboard careers: ' . $e->getMessage());
            return view('dashboard.careers', ['user' => Auth::user(), 'careers' => [], 'faculties' => []]); // ← AGREGAR $user
        }
    }

    public function recommendations()
    {
        try {
            $user = Auth::user(); // ← AGREGAR ESTA LÍNEA
            $latestResult = TestResult::where('user_id', Auth::id())
                ->orderBy('completed_at', 'desc')
                ->first();
            
            if ($latestResult) {
                if (is_string($latestResult->scores)) {
                    $latestResult->scores = json_decode($latestResult->scores, true);
                }
                if (is_string($latestResult->recommended_careers)) {
                    $latestResult->recommended_careers = json_decode($latestResult->recommended_careers, true);
                }
            }
            
            return view('dashboard.recommendations', compact('user', 'latestResult')); // ← AGREGAR $user
        } catch (\Exception $e) {
            Log::error('Error en dashboard recommendations: ' . $e->getMessage());
            return view('dashboard.recommendations', ['user' => Auth::user(), 'latestResult' => null]); // ← AGREGAR $user
        }
    }

    public function profile()
    {
        try {
            $user = Auth::user();
            return view('dashboard.profile', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error en dashboard profile: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar el perfil.');
        }
    }
}