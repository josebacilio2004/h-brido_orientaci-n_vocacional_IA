<?php

namespace App\Http\Controllers;

use App\Repositories\TestRepository;
use App\Repositories\GradeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController
{
    protected $testRepository;
    protected $gradeRepository;

    public function __construct(TestRepository $testRepository, GradeRepository $gradeRepository)
    {
        $this->middleware('auth');
        $this->testRepository = $testRepository;
        $this->gradeRepository = $gradeRepository;
    }

    public function index()
    {
        $tests = $this->testRepository->getActiveTests();
        $completedTests = $this->testRepository->getUserCompletedTests(Auth::id());

        return view('tests.index', compact('tests', 'completedTests'));
    }

    public function show($id)
    {
        $test = $this->testRepository->getTestWithQuestions($id);

        if (!$test) {
            abort(404, 'Test no encontrado');
        }

        $hasCompleted = $this->testRepository->hasUserCompletedTest(Auth::id(), $id);

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test. Aquí están tus resultados.');
        }

        return view('tests.show', compact('test'));
    }

    public function result($id)
    {
        $result = $this->testRepository->getUserTestResult(Auth::id(), $id);

        if (!$result) {
            return redirect()->route('tests.index')
                ->with('error', 'No se encontró el resultado del test.');
        }

        return view('tests.result', compact('result'));
    }

    public function gradesForm()
    {
        $grades = $this->gradeRepository->getUserGrades(Auth::id());
        return view('tests.grades', compact('grades'));
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

        $grades = $this->gradeRepository->saveOrUpdateGrades(
            Auth::id(),
            array_merge($validated, ['academic_year' => date('Y')])
        );

        try {
            $prediction = $this->getPredictionFromAI($grades);

            return redirect()->route('tests.ai-result')
                ->with('success', 'Predicción generada exitosamente')
                ->with('prediction', $prediction);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al obtener predicción: ' . $e->getMessage());
        }
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
            $result = $response->json();

            $this->testRepository->savePrediction(
                Auth::id(),
                $data,
                $result['carrera_recomendada'] ?? 'No definida',
                $result['confidence'] ?? 0,
                $result['top_careers'] ?? [],
                $result['model_version'] ?? '1.0'
            );

            return $result;
        }

        throw new \Exception('Error al conectar con el servicio de IA');
    }

    public function aiResult()
    {
        $prediction = $this->testRepository->getLatestPrediction(Auth::id());

        if (!$prediction) {
            return redirect()->route('tests.grades')
                ->with('info', 'Primero debes ingresar tus notas académicas.');
        }

        return view('tests.ai-result', compact('prediction'));
    }

    public function start($id)
    {
        $test = $this->testRepository->getTestById($id);

        if (!$test) {
            abort(404, 'Test no encontrado');
        }

        $hasCompleted = $this->testRepository->hasUserCompletedTest(Auth::id(), $id);

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test.');
        }

        $lastQuestionNumber = $this->testRepository->getLastAnsweredQuestion(Auth::id(), $id);
        $nextQuestion = $lastQuestionNumber ? min($lastQuestionNumber + 1, $test->total_questions) : 1;

        return redirect()->route('tests.question', [
            'id' => $id,
            'question' => $nextQuestion
        ]);
    }

    public function restart($id)
    {
        $this->testRepository->deleteUserResponses(Auth::id(), $id);
        $this->testRepository->deleteUserResult(Auth::id(), $id);

        return redirect()->route('tests.start', $id)
            ->with('success', 'Test reiniciado correctamente.');
    }

    public function question($id, $questionNumber)
    {
        $test = $this->testRepository->getTestWithQuestions($id);

        if (!$test) {
            abort(404, 'Test no encontrado');
        }

        if ($questionNumber < 1 || $questionNumber > $test->total_questions) {
            return redirect()->route('tests.start', $id);
        }

        $question = $this->testRepository->getQuestionByNumber($id, $questionNumber);

        if (!$question) {
            abort(404, 'Pregunta no encontrada');
        }

        $previousAnswer = $this->testRepository->getUserAnswer(Auth::id(), $id, $question->id);

        $progress = ($questionNumber / $test->total_questions) * 100;
        $answeredCount = $this->testRepository->countUserAnswers(Auth::id(), $id);

        return view('tests.question', compact('test', 'question', 'questionNumber', 'progress', 'answeredCount', 'previousAnswer'));
    }

    public function saveAnswer(Request $request, $id, $questionNumber)
    {
        Log::info("=== GUARDANDO RESPUESTA ===");
        Log::info("Test: {$id}, Pregunta: {$questionNumber}");

        try {
            $validated = $request->validate([
                'answer' => 'required'
            ]);

            $test = $this->testRepository->getTestById($id);
            $question = $this->testRepository->getQuestionByNumber($id, $questionNumber);

            if (!$test || !$question) {
                throw new \Exception('Test o pregunta no encontrada');
            }

            $score = $this->calculateScore($question, $validated['answer']);

            $this->testRepository->saveAnswer(
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
            $test = $this->testRepository->getTestById($id);
            $user = Auth::user();

            $totalResponses = $this->testRepository->countUserAnswers($user->id, $id);

            Log::info("Respuestas encontradas: {$totalResponses}/{$test->total_questions}");

            if ($totalResponses < $test->total_questions) {
                Log::warning("Faltan respuestas");

                $firstUnanswered = $this->testRepository->getFirstUnansweredQuestion($user->id, $id);

                if ($firstUnanswered) {
                    return redirect()->route('tests.question', [
                        'id' => $id,
                        'question' => $firstUnanswered->question_number
                    ])->with('warning', 'Por favor completa todas las preguntas.');
                }
            }

            $scores = $this->testRepository->calculateScoresByCategory($user->id, $id);

            Log::info("Puntajes calculados:", $scores);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            $recommendedCareers = $this->getRecommendedCareersRIASEC($scores);
            $analysis = $this->generateRIASECAnalysis($scores, $recommendedCareers);

            $result = $this->testRepository->saveResult(
                $user->id,
                $id,
                $scores,
                $recommendedCareers,
                $analysis,
                array_sum($scores)
            );

            Log::info("Resultado guardado ID: {$result->id}");

            return redirect()->route('tests.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            Log::error("Error procesando test: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => 1
            ])->with('error', 'Error al procesar el test. Por favor intenta de nuevo.');
        }
    }

    public function process($id)
    {
        return $this->processTest($id);
    }

    public function finalizeFromLastQuestion(Request $request, $id)
    {
        Log::info("=== FINALIZAR DESDE ÚLTIMA PREGUNTA ===");
        Log::info("Test ID: {$id}, Usuario: " . Auth::id());

        try {
            $test = $this->testRepository->getTestById($id);
            $lastQuestionNumber = $test->total_questions;

            Log::info("Buscando última pregunta: número {$lastQuestionNumber}");

            $question = $this->testRepository->getQuestionByNumber($id, $lastQuestionNumber);

            if (!$question) {
                throw new \Exception('Última pregunta no encontrada');
            }

            Log::info("Última pregunta encontrada: ID {$question->id}");

            $validated = $request->validate([
                'answer' => 'required'
            ]);

            $score = $this->calculateScore($question, $validated['answer']);

            Log::info("Guardando respuesta: {$validated['answer']}, score: {$score}");

            $this->testRepository->saveAnswer(
                Auth::id(),
                $id,
                $question->id,
                $validated['answer'],
                $score
            );

            Log::info("Última respuesta guardada exitosamente");

            return $this->processTest($id);
        } catch (\Exception $e) {
            Log::error("Error al finalizar desde última pregunta: " . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => $lastQuestionNumber ?? 1
            ])->with('error', 'Error al finalizar el test: ' . $e->getMessage());
        }
    }

    private function calculateScore($question, $answer)
    {
        switch ($question->type) {
            case 'scale':
                return (int) $answer;
            case 'yes_no':
                return $answer === 'yes' ? 5 : 0;
            case 'multiple_choice':
                return 3;
            default:
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
                'score' => $scores[$category],
                'percentage' => round(($scores[$category] / 50) * 100, 1),
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
        $topScore = $scores[$topCategory];
        $topPercentage = round(($topScore / 50) * 100, 1);

        $categoryName = $this->getCategoryName($topCategory);

        $analysis = "Según el test RIASEC, tu perfil vocacional dominante es <strong>{$categoryName}</strong> con un {$topPercentage}% de afinidad. ";
        $analysis .= $this->getCategoryDescription($topCategory) . " ";

        if (count($scores) >= 2) {
            $secondCategory = array_keys($scores)[1];
            $secondCategoryName = $this->getCategoryName($secondCategory);
            $analysis .= "También muestras características del tipo <strong>{$secondCategoryName}</strong>, ";
            $analysis .= "lo que indica un perfil versátil que puede adaptarse a diferentes áreas profesionales.";
        }

        return $analysis;
    }
}
