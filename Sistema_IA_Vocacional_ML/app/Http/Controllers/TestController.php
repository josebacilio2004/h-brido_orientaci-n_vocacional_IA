<?php

namespace App\Http\Controllers;

use App\Models\VocationalTest;
use App\Models\TestResponse;
use App\Models\TestResult;
use App\Models\StudentGrade;
use App\Models\TestInterest;
use App\Models\TestSkill;
use App\Models\TestPersonality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class TestController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $tests = VocationalTest::obtenerActivos();
            $interestTests = TestInterest::obtenerActivos();
            $skillTests = TestSkill::obtenerActivos();
            $personalityTests = TestPersonality::obtenerActivos();

            $completedTests = VocationalTest::obtenerTestsCompletados(Auth::id());
            $completedInterestTests = TestInterest::obtenerTestsCompletados(Auth::id());
            $completedSkillTests = TestSkill::obtenerTestsCompletados(Auth::id());
            $completedPersonalityTests = TestPersonality::obtenerTestsCompletados(Auth::id());

            return view('tests.index', compact('tests', 'completedTests', 'interestTests', 'skillTests', 'personalityTests', 'completedInterestTests', 'completedSkillTests', 'completedPersonalityTests'));
        } catch (\Exception $e) {
            Log::error('Error en index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error al cargar los tests.');
        }
    }

    public function show($id)
    {
        try {
            $testData = VocationalTest::obtenerConPreguntas($id);

            if (!$testData || empty($testData['test'])) {
                abort(404, 'Test no encontrado');
            }

            $test = $testData['test'];
            $hasCompleted = VocationalTest::verificarTestCompletado(Auth::id(), $id);

            if ($hasCompleted) {
                return redirect()->route('tests.result', $id)
                    ->with('info', 'Ya has completado este test. Aquí están tus resultados.');
            }

            return view('tests.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('Error en show: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar el test.');
        }
    }

    public function result($id)
    {
        try {
            $test = VocationalTest::find($id);
            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $resultData = TestResult::obtenerResultado(Auth::id(), $id);

            if (!$resultData) {
                Log::warning("No hay resultados para user: " . Auth::id() . ", test: " . $id);
                return redirect()->route('tests.index')
                    ->with('error', 'Aún no has completado este test. Por favor complétalo primero.');
            }

            $result = new \stdClass();
            $result->vocational_test_id = $resultData->vocational_test_id ?? $id;
            $result->user_id = $resultData->user_id ?? Auth::id();
            $result->analysis = $resultData->analysis ?? 'No hay análisis disponible';
            $result->total_score = $resultData->total_score ?? 0;

            if (is_string($resultData->scores)) {
                $result->scores = json_decode($resultData->scores, true) ?? [];
            } else {
                $result->scores = (array)$resultData->scores ?? [];
            }

            if (is_string($resultData->recommended_careers)) {
                $result->recommended_careers = json_decode($resultData->recommended_careers, true) ?? [];
            } else {
                $result->recommended_careers = (array)$resultData->recommended_careers ?? [];
            }

            Log::info("Resultado cargado correctamente para resultado");

            return view('tests.result', compact('result'));
        } catch (\Exception $e) {
            Log::error('Error en result: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('tests.index')->with('error', 'Error al cargar resultados: ' . $e->getMessage());
        }
    }

    public function gradesForm()
    {
        try {
            $grades = StudentGrade::obtenerNotas(Auth::id());
            return view('tests.grades', compact('grades'));
        } catch (\Exception $e) {
            Log::error('Error en gradesForm: ' . $e->getMessage());
            return view('tests.grades', ['grades' => null]);
        }
    }

    public function submitGrades(Request $request)
    {
        $validated = $request->validate([
            'nota_matematica' => 'required|integer|min:0|max:20',
            'nota_comunicacion' => 'required|integer|min:0|max:20',
            'nota_ciencias_sociales' => 'required|integer|min:0|max:20',
            'nota_ciencia_tecnologia' => 'required|integer|min:0|max:20',
            'nota_desarrollo_personal' => 'required|integer|min:0|max:20',
            'nota_ciudadania_civica' => 'required|integer|min:0|max:20',
            'nota_educacion_fisica' => 'required|integer|min:0|max:20',
            'nota_ingles' => 'required|integer|min:0|max:20',
            'nota_educacion_trabajo' => 'required|integer|min:0|max:20',
        ]);

        try {
            StudentGrade::guardarNotas(
                Auth::id(),
                $validated['nota_matematica'],
                $validated['nota_comunicacion'],
                $validated['nota_ciencias_sociales'],
                $validated['nota_ciencia_tecnologia'],
                $validated['nota_desarrollo_personal'],
                $validated['nota_ciudadania_civica'],
                $validated['nota_educacion_fisica'],
                $validated['nota_ingles'],
                $validated['nota_educacion_trabajo'],
                date('Y')
            );

            try {
                $prediction = $this->getPredictionFromAI($validated);
                return redirect()->route('tests.ai-result')
                    ->with('success', 'Predicción generada exitosamente')
                    ->with('prediction', $prediction);
            } catch (\Exception $e) {
                Log::warning('AI prediction failed, showing grades saved message: ' . $e->getMessage());
                return redirect()->route('tests.ai-result')
                    ->with('success', 'Notas guardadas. No pudimos conectar con el servicio de IA.');
            }
        } catch (\Exception $e) {
            Log::error('Error en submitGrades: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar las notas: ' . $e->getMessage());
        }
    }

    public function start($id)
    {
        try {
            $test = VocationalTest::find($id);

            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $hasCompleted = VocationalTest::verificarTestCompletado(Auth::id(), $id);

            if ($hasCompleted) {
                return redirect()->route('tests.result', $id)
                    ->with('info', 'Ya has completado este test.');
            }

            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => 1
            ]);
        } catch (\Exception $e) {
            Log::error('Error en start: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al iniciar el test.');
        }
    }

    public function restart($id)
    {
        try {
            $test = VocationalTest::find($id);

            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            TestResponse::where('user_id', Auth::id())
                ->where('vocational_test_id', $id)
                ->delete();

            TestResult::where('user_id', Auth::id())
                ->where('vocational_test_id', $id)
                ->delete();

            return redirect()->route('tests.start', $id)
                ->with('success', 'Test reiniciado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error en restart: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al reiniciar el test.');
        }
    }

    public function question($id, $questionNumber)
    {
        try {
            $testData = VocationalTest::obtenerConPreguntas($id);

            if (!$testData || empty($testData['test'])) {
                abort(404, 'Test no encontrado');
            }

            $test = $testData['test'];

            if ($questionNumber < 1 || $questionNumber > $test->total_questions) {
                return redirect()->route('tests.start', $id);
            }

            $question = VocationalTest::obtenerPregunta($id, $questionNumber);

            if (!$question) {
                abort(404, 'Pregunta no encontrada');
            }

            $previousAnswer = TestResponse::obtenerRespuestaPrevia(Auth::id(), $id, $question->id);
            $progress = ($questionNumber / $test->total_questions) * 100;
            $answeredCount = VocationalTest::contarRespuestasUsuario(Auth::id(), $id);

            return view('tests.question', compact('test', 'question', 'questionNumber', 'progress', 'answeredCount', 'previousAnswer'));
        } catch (\Exception $e) {
            Log::error('Error en question: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar la pregunta.');
        }
    }

    public function saveAnswer(Request $request, $id, $questionNumber)
    {
        Log::info("=== GUARDANDO RESPUESTA ===");
        Log::info("Test: {$id}, Pregunta: {$questionNumber}");

        try {
            $validated = $request->validate([
                'answer' => 'required'
            ]);

            $test = VocationalTest::find($id);
            $question = VocationalTest::obtenerPregunta($id, $questionNumber);

            if (!$test || !$question) {
                throw new \Exception('Test o pregunta no encontrada');
            }

            $score = $this->calculateScore($question, $validated['answer']);

            TestResponse::guardarRespuesta(
                Auth::id(),
                $id,
                $question->id,
                $validated['answer'],
                $score
            );

            Log::info("Respuesta guardada correctamente");

            if ($questionNumber == $test->total_questions) {
                Log::info("Última pregunta alcanzada - procesando test");
                return $this->processTest($id);
            }

            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error("Error guardando respuesta: " . $e->getMessage());
            return back()->with('error', 'Error al guardar respuesta. Por favor intenta de nuevo.');
        }
    }

    private function processTest($id)
    {
        Log::info("=== PROCESANDO TEST ===");

        try {
            $test = VocationalTest::find($id);
            $user = Auth::user();

            if (!$test || !$user) {
                throw new \Exception("Test o usuario no encontrado");
            }

            $totalResponses = VocationalTest::contarRespuestasUsuario($user->id, $id);
            Log::info("Respuestas encontradas: {$totalResponses}/{$test->total_questions}");

            if ($totalResponses < $test->total_questions) {
                throw new \Exception("No todas las preguntas han sido respondidas");
            }

            $scores = VocationalTest::calcularPuntajesCategorias($user->id, $id);
            Log::info("Puntajes calculados:", (array)$scores);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            $scoresArray = [];
            foreach ($scores as $score) {
                $scoresArray[$score->category] = $score->total_score;
            }

            $recommendedCareers = $this->getRecommendedCareersRIASEC($scoresArray);
            $analysis = $this->generateRIASECAnalysis($scoresArray, $recommendedCareers);

            // VERIFICAR QUE SE GUARDE CORRECTAMENTE
            $result = TestResult::guardarResultado(
                $user->id,
                $id,
                $scoresArray,
                $recommendedCareers,
                $analysis,
                array_sum($scoresArray)
            );

            if (!$result) {
                throw new \Exception("No se pudo guardar el resultado en la base de datos");
            }

            Log::info("Resultado guardado exitosamente - ID: " . ($result->id ?? 'N/A'));

            return redirect()->route('tests.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error procesando test: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => 1
            ])->with('error', 'Error al procesar el test: ' . $e->getMessage());
        }
    }

    public function aiResult()
    {
        try {
            $prediction = session('prediction');
            $grades = StudentGrade::obtenerNotas(Auth::id());

            if (!$grades) {
                return redirect()->route('tests.grades')
                    ->with('error', 'Por favor completa primero el formulario de notas.');
            }

            return view('tests.ai-result', compact('prediction', 'grades'));
        } catch (\Exception $e) {
            Log::error('Error en aiResult: ' . $e->getMessage());
            return redirect()->route('tests.grades')->with('error', 'Error al cargar resultados de IA.');
        }
    }

    private function calculateScore($question, $answer)
    {
        try {
            $type = $question->type ?? 'scale';

            switch ($type) {
                case 'scale':
                    return (int) $answer;
                case 'yes_no':
                    return $answer === 'yes' ? 5 : 0;
                case 'multiple_choice':
                    return 3;
                default:
                    return 0;
            }
        } catch (\Exception $e) {
            Log::warning('Error calculating score: ' . $e->getMessage());
            return 0;
        }
    }

    private function getRecommendedCareersRIASEC($scores)
    {
        arsort($scores);

        $careerMap = [
            'realista' => [
                'Ingeniería Civil',
                'Ingeniería Mecánica',
                'Arquitectura',
                'Agronomía',
                'Veterinaria',
                'Ingeniería Industrial',
                'Tecnología de Alimentos',
                'Ingeniería Ambiental'
            ],
            'investigador' => [
                'Medicina',
                'Ingeniería de Sistemas',
                'Biología',
                'Química',
                'Física',
                'Matemáticas',
                'Ingeniería Biomédica',
                'Biotecnología',
                'Astronomía'
            ],
            'artistico' => [
                'Diseño Gráfico',
                'Arquitectura',
                'Comunicación Audiovisual',
                'Bellas Artes',
                'Diseño de Moda',
                'Publicidad',
                'Música',
                'Literatura',
                'Diseño de Interiores'
            ],
            'social' => [
                'Psicología',
                'Educación',
                'Trabajo Social',
                'Enfermería',
                'Terapia Física',
                'Nutrición',
                'Ciencias de la Comunicación',
                'Sociología',
                'Orientación Vocacional'
            ],
            'emprendedor' => [
                'Administración de Empresas',
                'Marketing',
                'Derecho',
                'Economía',
                'Relaciones Internacionales',
                'Ciencias Políticas',
                'Gestión de Negocios',
                'Finanzas',
                'Comercio Internacional'
            ],
            'convencional' => [
                'Contabilidad',
                'Administración',
                'Ingeniería Industrial',
                'Gestión Pública',
                'Auditoría',
                'Banca y Finanzas',
                'Logística',
                'Recursos Humanos'
            ]
        ];

        $recommendations = [];
        $topCategories = array_slice(array_keys($scores), 0, 3);

        foreach ($topCategories as $category) {
            $recommendations[] = [
                'category' => $category,
                'category_name' => $this->getCategoryName($category),
                'score' => $scores[$category] ?? 0,
                'percentage' => round((($scores[$category] ?? 0) / 50) * 100, 1),
                'careers' => $careerMap[$category] ?? [],
                'description' => $this->getCategoryDescription($category)
            ];
        }

        return $recommendations;
    }

    private function getCategoryName($category)
    {
        $names = [
            'realista' => 'Realista',
            'investigador' => 'Investigador',
            'artistico' => 'Artístico',
            'social' => 'Social',
            'emprendedor' => 'Emprendedor',
            'convencional' => 'Convencional'
        ];

        return $names[$category] ?? $category;
    }

    private function getCategoryDescription($category)
    {
        $descriptions = [
            'realista' => 'Prefieres trabajar con objetos, herramientas y maquinaria. Te gustan las actividades prácticas y concretas.',
            'investigador' => 'Te gusta observar, aprender, investigar y resolver problemas. Prefieres el trabajo intelectual y analítico.',
            'artistico' => 'Disfrutas de actividades creativas y expresivas. Valoras la originalidad y la innovación.',
            'social' => 'Te gusta ayudar, enseñar y cuidar a otros. Prefieres trabajar con personas y para el beneficio de la comunidad.',
            'emprendedor' => 'Te gusta liderar, persuadir y tomar decisiones. Disfrutas de los desafíos y la competencia.',
            'convencional' => 'Prefieres trabajar con datos, números y procedimientos establecidos. Valoras el orden y la precisión.'
        ];

        return $descriptions[$category] ?? '';
    }

    private function generateRIASECAnalysis($scores, $recommendations)
    {
        arsort($scores);
        $topCategory = array_key_first($scores);
        $topScore = $scores[$topCategory] ?? 0;
        $topPercentage = round(($topScore / 50) * 100, 1);

        $categoryName = $this->getCategoryName($topCategory);

        $analysis = "Según el test RIASEC, tu perfil vocacional dominante es <strong>{$categoryName}</strong> con un {$topPercentage}% de afinidad. ";
        $analysis .= $this->getCategoryDescription($topCategory) . " ";

        if (count($scores) >= 2) {
            $secondCategory = array_keys($scores)[1] ?? null;
            if ($secondCategory) {
                $secondCategoryName = $this->getCategoryName($secondCategory);
                $analysis .= "También muestras características del tipo <strong>{$secondCategoryName}</strong>, ";
                $analysis .= "lo que indica un perfil versátil que puede adaptarse a diferentes áreas profesionales.";
            }
        }

        return $analysis;
    }

    private function getPredictionFromAI($grades)
    {
        $apiUrl = env('ML_API_URL', 'http://localhost:8000/predict');

        $data = [
            'Nota_Matematica' => $grades['nota_matematica'],
            'Nota_Comunicacion' => $grades['nota_comunicacion'],
            'Nota_Ciencias_Sociales' => $grades['nota_ciencias_sociales'],
            'Nota_Ciencia_Tecnologia' => $grades['nota_ciencia_tecnologia'],
            'Nota_Desarrollo_Personal' => $grades['nota_desarrollo_personal'],
            'Nota_Ciudadania_Civica' => $grades['nota_ciudadania_civica'],
            'Nota_Educacion_Fisica' => $grades['nota_educacion_fisica'],
            'Nota_Ingles' => $grades['nota_ingles'],
            'Nota_Educacion_Trabajo' => $grades['nota_educacion_trabajo'],
        ];

        try {
            $response = Http::timeout(10)->post($apiUrl, $data);

            if ($response->successful()) {
                $responseData = $response->json();

                // Normalize response format
                return [
                    'predictions' => $responseData['predictions'] ?? $responseData['top_careers'] ?? [],
                    'confidence' => $responseData['confidence'] ?? $responseData['accuracy'] ?? $responseData['score'] ?? 85,
                    'analysis' => $responseData['analysis'] ?? $responseData['explanation'] ?? 'Análisis basado en tu desempeño académico',
                    'model_info' => $responseData['model_info'] ?? null
                ];
            }

            Log::warning('ML API returned error: ' . $response->status());
            throw new \Exception('Respuesta inválida del servicio de IA');
        } catch (\Exception $e) {
            Log::error('ML API Error: ' . $e->getMessage());

            Log::info('Generating fallback prediction using RIASEC system');
            return $this->generateFallbackPrediction($grades);
        }
    }

    private function generateFallbackPrediction($grades)
    {
        // Calculate simple scores based on subject performance
        $riasecScores = [
            'realista' => (($grades['nota_matematica'] + $grades['nota_ciencia_tecnologia']) / 2),
            'investigador' => (($grades['nota_matematica'] + $grades['nota_ciencia_tecnologia']) / 2),
            'artistico' => (($grades['nota_comunicacion'] + $grades['nota_desarrollo_personal']) / 2),
            'social' => (($grades['nota_desarrollo_personal'] + $grades['nota_ciudadania_civica']) / 2),
            'emprendedor' => (($grades['nota_ciudadania_civica'] + $grades['nota_desarrollo_personal']) / 2),
            'convencional' => (($grades['nota_matematica'] + $grades['nota_comunicacion']) / 2),
        ];

        // Get career recommendations based on RIASEC scores
        $careersRecommended = $this->getRecommendedCareersRIASEC($riasecScores);

        // Format as career predictions
        $predictions = [];
        foreach ($careersRecommended as $category) {
            foreach ($category['careers'] as $career) {
                $predictions[] = [
                    'name' => $career,
                    'probability' => $category['percentage']
                ];
            }
        }

        // Return in normalized format
        return [
            'predictions' => array_slice($predictions, 0, 8),
            'confidence' => 75,
            'analysis' => 'Esta predicción se generó utilizando el análisis RIASEC basado en tus calificaciones académicas.',
            'model_info' => 'Fallback - Sistema RIASEC'
        ];
    }

    // Métodos para Interest Test
    public function showInterestTest($id)
    {
        try {
            $test = TestInterest::obtenerConPreguntas($id);

            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $hasCompleted = TestInterest::estaCompletado(Auth::id(), $id);

            if ($hasCompleted) {
                return redirect()->route('tests.interest.result', $id)
                    ->with('info', 'Ya has completado este test.');
            }

            return view('tests.interests.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('Error en showInterestTest: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar el test.');
        }
    }

    public function interestQuestion($id, $questionNumber)
    {
        try {
            $test = TestInterest::find($id);
            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $question = TestInterest::obtenerPregunta($id, $questionNumber);
            if (!$question) {
                abort(404, 'Pregunta no encontrada');
            }

            $previousAnswer = TestInterest::obtenerRespuestaPrevia(Auth::id(), $id, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;
            $progress = ($questionNumber / $totalQuestions) * 100;

            return view('tests.interests.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion', 'progress'));
        } catch (\Exception $e) {
            Log::error('Error en interestQuestion: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar la pregunta.');
        }
    }

    public function saveInterestAnswer(Request $request, $id, $questionNumber)
    {
        try {
            $validated = $request->validate(['answer' => 'required']);

            $test = TestInterest::find($id);
            $question = TestInterest::obtenerPregunta($id, $questionNumber);

            if (!$test || !$question) {
                throw new \Exception('Test o pregunta no encontrada');
            }

            $score = (int)$validated['answer'];
            TestInterest::guardarRespuesta(Auth::id(), $id, $question->id, $validated['answer'], $score);

            if ($questionNumber == $test->total_questions) {
                return $this->processInterestTest($id);
            }

            return redirect()->route('tests.interest.question', [
                'id' => $id,
                'question' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error("Error guardando respuesta interest: " . $e->getMessage());
            return back()->with('error', 'Error al guardar respuesta.');
        }
    }

    private function processInterestTest($id)
    {
        try {
            $test = TestInterest::find($id);
            $user = Auth::user();

            $scores = TestInterest::calcularPuntajesPorCategoria($user->id, $id);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            $scoresArray = [];
            foreach ($scores as $score) {
                $scoresArray[$score->category] = $score->total_score;
            }

            $analysis = $this->generateInterestAnalysis($scoresArray);
            $recommendedCareers = $this->getInterestRecommendedCareers($scoresArray);

            TestInterest::guardarResultado($user->id, $id, $scoresArray, $recommendedCareers, $analysis, array_sum($scoresArray));

            return redirect()->route('tests.interest.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error procesando test interest: " . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al procesar el test.');
        }
    }

    public function interestResult($id)
    {
        try {
            $result = TestInterest::obtenerResultado(Auth::id(), $id);

            if (!$result) {
                return redirect()->route('tests.index')
                    ->with('error', 'Aún no has completado este test.');
            }

            $test = TestInterest::find($id);
            $scores = json_decode($result->scores, true) ?? [];
            $analysis = $result->analysis;
            $recommendedCareers = json_decode($result->recommended_careers, true) ?? [];

            return view('tests.interests.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
        } catch (\Exception $e) {
            Log::error('Error en interestResult: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar resultados.');
        }
    }

    // Métodos para Skill Test
    public function showSkillTest($id)
    {
        try {
            $test = TestSkill::obtenerConPreguntas($id);

            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $hasCompleted = TestSkill::estaCompletado(Auth::id(), $id);

            if ($hasCompleted) {
                return redirect()->route('tests.skill.result', $id)
                    ->with('info', 'Ya has completado este test.');
            }

            return view('tests.skills.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('Error en showSkillTest: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar el test.');
        }
    }

    public function skillQuestion($id, $questionNumber)
    {
        try {
            $test = TestSkill::find($id);
            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $question = TestSkill::obtenerPregunta($id, $questionNumber);
            if (!$question) {
                abort(404, 'Pregunta no encontrada');
            }

            $previousAnswer = TestSkill::obtenerRespuestaPrevia(Auth::id(), $id, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;
            $progress = ($questionNumber / $totalQuestions) * 100;

            return view('tests.skills.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion', 'progress'));
        } catch (\Exception $e) {
            Log::error('Error en skillQuestion: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar la pregunta.');
        }
    }

    public function saveSkillAnswer(Request $request, $id, $questionNumber)
    {
        try {
            Log::info("=== GUARDANDO RESPUESTA SKILL ===");
            Log::info("Test: {$id}, Pregunta: {$questionNumber}");

            $validated = $request->validate(['answer' => 'required']);

            $test = TestSkill::find($id);
            $question = TestSkill::obtenerPregunta($id, $questionNumber);

            if (!$test || !$question) {
                throw new \Exception('Test o pregunta no encontrada');
            }

            Log::info("Total preguntas: {$test->total_questions}, Pregunta actual: {$questionNumber}");

            $score = (int)$validated['answer'];
            TestSkill::guardarRespuesta(Auth::id(), $id, $question->id, $validated['answer'], $score);

            // ✅ VERIFICAR SI ES LA ÚLTIMA PREGUNTA
            if ($questionNumber == $test->total_questions) {
                Log::info("ÚLTIMA PREGUNTA - PROCESANDO TEST");
                return $this->processSkillTest($id);
            }

            return redirect()->route('tests.skill.question', [
                'id' => $id,
                'question' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error("Error guardando respuesta skill: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->with('error', 'Error al guardar respuesta.');
        }
    }

    private function processSkillTest($id)
    {
        try {
            $test = TestSkill::find($id);
            $user = Auth::user();

            $scores = TestSkill::calcularPuntajesPorCategoria($user->id, $id);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            $scoresArray = [];
            foreach ($scores as $score) {
                $scoresArray[$score->category] = $score->total_score;
            }

            $analysis = $this->generateSkillAnalysis($scoresArray);
            $recommendedCareers = $this->getSkillRecommendedCareers($scoresArray);

            // ✅ VERIFICAR QUE SE GUARDE CORRECTAMENTE
            $result = TestSkill::guardarResultado($user->id, $id, $scoresArray, $recommendedCareers, $analysis, array_sum($scoresArray));

            if (!$result) {
                throw new \Exception("No se pudo guardar el resultado en la base de datos");
            }

            Log::info("Test de habilidades completado exitosamente - ID: " . ($result->id ?? 'N/A'));

            return redirect()->route('tests.skill.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error procesando test skill: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('tests.index')->with('error', 'Error al procesar el test: ' . $e->getMessage());
        }
    }

    public function skillResult($id)
    {
        try {
            $result = TestSkill::obtenerResultado(Auth::id(), $id);

            if (!$result) {
                return redirect()->route('tests.index')
                    ->with('error', 'Aún no has completado este test.');
            }

            $test = TestSkill::find($id);
            $scores = json_decode($result->scores, true) ?? [];
            $analysis = $result->analysis;
            $recommendedCareers = json_decode($result->recommended_careers, true) ?? [];

            return view('tests.skills.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
        } catch (\Exception $e) {
            Log::error('Error en skillResult: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar resultados.');
        }
    }

    public function showPersonalityTest($id)
    {
        try {
            $test = TestPersonality::obtenerConPreguntas($id);

            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $hasCompleted = TestPersonality::estaCompletado(Auth::id(), $id);

            if ($hasCompleted) {
                return redirect()->route('tests.personality.result', $id)
                    ->with('info', 'Ya has completado este test.');
            }

            return view('tests.personality.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('Error en showPersonalityTest: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar el test.');
        }
    }

    public function personalityQuestion($id, $questionNumber)
    {
        try {
            $test = TestPersonality::find($id);
            if (!$test) {
                abort(404, 'Test no encontrado');
            }

            $question = TestPersonality::obtenerPregunta($id, $questionNumber);
            if (!$question) {
                abort(404, 'Pregunta no encontrada');
            }

            $previousAnswer = TestPersonality::obtenerRespuestaPrevia(Auth::id(), $id, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;
            $progress = ($questionNumber / $totalQuestions) * 100;

            return view('tests.personality.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion', 'progress'));
        } catch (\Exception $e) {
            Log::error('Error en personalityQuestion: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar la pregunta.');
        }
    }

    public function savePersonalityAnswer(Request $request, $id, $questionNumber)
    {
        try {
            $validated = $request->validate(['answer' => 'required']);

            $test = TestPersonality::find($id);
            $question = TestPersonality::obtenerPregunta($id, $questionNumber);

            if (!$test || !$question) {
                throw new \Exception('Test o pregunta no encontrada');
            }

            $score = (int)$validated['answer'];
            TestPersonality::guardarRespuesta(Auth::id(), $id, $question->id, $validated['answer'], $score);

            if ($questionNumber == $test->total_questions) {
                return $this->processPersonalityTest($id);
            }

            return redirect()->route('tests.personality.question', [
                'id' => $id,
                'question' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error("Error guardando respuesta personality: " . $e->getMessage());
            return back()->with('error', 'Error al guardar respuesta.');
        }
    }

    private function processPersonalityTest($id)
    {
        try {
            $test = TestPersonality::find($id);
            $user = Auth::user();

            $scores = TestPersonality::calcularPuntajesPorCategoria($user->id, $id);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            $scoresArray = [];
            foreach ($scores as $score) {
                $scoresArray[$score->category] = $score->total_score;
            }

            $analysis = $this->generatePersonalityAnalysis($scoresArray);
            $recommendedCareers = $this->getPersonalityRecommendedCareers($scoresArray);

            TestPersonality::guardarResultado($user->id, $id, $scoresArray, $recommendedCareers, $analysis, array_sum($scoresArray));

            return redirect()->route('tests.personality.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error procesando test personality: " . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al procesar el test.');
        }
    }

    public function personalityResult($id)
    {
        try {
            $result = TestPersonality::obtenerResultado(Auth::id(), $id);

            if (!$result) {
                return redirect()->route('tests.index')
                    ->with('error', 'Aún no has completado este test.');
            }

            $test = TestPersonality::find($id);
            $scores = json_decode($result->scores, true) ?? [];
            $analysis = $result->analysis;
            $recommendedCareers = json_decode($result->recommended_careers, true) ?? [];

            return view('tests.personality.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
        } catch (\Exception $e) {
            Log::error('Error en personalityResult: ' . $e->getMessage());
            return redirect()->route('tests.index')->with('error', 'Error al cargar resultados.');
        }
    }

    private function generateInterestAnalysis($scores)
    {
        arsort($scores);
        $topCategory = array_key_first($scores);
        $topScore = $scores[$topCategory] ?? 0;

        return "Tus principales áreas de interés son " . implode(", ", array_slice(array_keys($scores), 0, 3)) .
            ". Estos intereses sugieren que deberías explorar carreras relacionadas con estas áreas.";
    }

    private function getInterestRecommendedCareers($scores)
    {
        arsort($scores);
        return array_slice(array_keys($scores), 0, 3);
    }

    private function generateSkillAnalysis($scores)
    {
        arsort($scores);
        $topCategory = array_key_first($scores);

        return "Tus habilidades más desarrolladas son en " . implode(", ", array_slice(array_keys($scores), 0, 3)) .
            ". Estas fortalezas te abrirán muchas puertas en el mundo laboral.";
    }

    private function getSkillRecommendedCareers($scores)
    {
        arsort($scores);
        return array_slice(array_keys($scores), 0, 3);
    }

    private function generatePersonalityAnalysis($scores)
    {
        arsort($scores);
        $topCategory = array_key_first($scores);

        return "Tu tipo de personalidad predominante es " . $topCategory .
            ". Esto significa que prefieres ambientes y trabajos que se alineen con estas características.";
    }

    private function getPersonalityRecommendedCareers($scores)
    {
        arsort($scores);
        return array_slice(array_keys($scores), 0, 3);
    }
}
