<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\TestResult;
use App\Models\Career;
use App\Models\VocationalTest;

class DashboardController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function tests()
    {
        try {
            return view('dashboard.tests');
        } catch (\Exception $e) {
            Log::error('Error en dashboard tests: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar tests.');
        }
    }

    public function careers()
    {
        try {
            $careers = Career::all();
            $faculties = Career::distinct()
                ->pluck('faculty')
                ->filter()
                ->values()
                ->toArray();
            
            return view('dashboard.careers', compact('careers', 'faculties'));
        } catch (\Exception $e) {
            Log::error('Error en dashboard careers: ' . $e->getMessage());
            return view('dashboard.careers', ['careers' => [], 'faculties' => []]);
        }
    }

    public function recommendations()
    {
        try {
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
            
            return view('dashboard.recommendations', compact('latestResult'));
        } catch (\Exception $e) {
            Log::error('Error en dashboard recommendations: ' . $e->getMessage());
            return view('dashboard.recommendations', ['latestResult' => null]);
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
