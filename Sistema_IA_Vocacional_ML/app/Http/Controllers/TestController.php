<?php

namespace App\Http\Controllers;

use App\Models\VocationalTest;
use App\Models\TestResponse;
use App\Models\TestResult;
use App\Models\StudentGrade;
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
            $completedTests = VocationalTest::obtenerTestsCompletados(Auth::id());

            return view('tests.index', compact('tests', 'completedTests'));
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

        $response = Http::timeout(10)->post($apiUrl, $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Error al conectar con el servicio de IA');
    }
}
