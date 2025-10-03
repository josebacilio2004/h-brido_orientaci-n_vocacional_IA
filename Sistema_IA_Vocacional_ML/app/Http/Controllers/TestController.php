<?php

namespace App\Http\Controllers;

use App\Models\VocationalTest;
use App\Models\TestQuestion;
use App\Models\TestResponse;
use App\Models\TestResult;
use App\Models\StudentGrade;
use App\Models\AIPrediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    protected $middleware = ['auth'];

    public function index()
    {
        $tests = VocationalTest::where('is_active', true)->get();
        $completedTests = TestResult::where('user_id', Auth::id())
            ->pluck('vocational_test_id')
            ->toArray();

        return view('tests.index', compact('tests', 'completedTests'));
    }

    public function show($id)
    {
        $test = VocationalTest::with('questions')->findOrFail($id);

        $hasCompleted = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->exists();

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test. Aquí están tus resultados.');
        }

        return view('tests.show', compact('test'));
    }

    public function result($id)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->with('test')
            ->latest()
            ->firstOrFail();

        return view('tests.result', compact('result'));
    }

    public function gradesForm()
    {
        $grades = StudentGrade::where('user_id', Auth::id())->first();
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

        $user = Auth::user();

        $grades = StudentGrade::updateOrCreate(
            ['user_id' => $user->id],
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

    private function getPredictionFromAI(StudentGrade $grades)
    {
        $apiUrl = env('ML_API_URL', 'http://localhost:8000/predict');

        $data = [
            'Nota_Matematica' => $grades->nota_matematica,
            'Nota_Comunicacion' => $grades->nota_comunicacion,
            'Nota_Ciencias_Sociales' => $grades->nota_ciencias_sociales,
            'Nota_Ciencia_Tecnologia' => $grades->nota_ciencia_tecnologia,
            'Nota_Desarrollo_Personal' => $grades->nota_desarrollo_personal,
            'Nota_Ciudadania_Civica' => $grades->nota_ciudadania_civica,
            'Nota_Educacion_Fisica' => $grades->nota_educacion_fisica,
            'Nota_Ingles' => $grades->nota_ingles,
            'Nota_Educacion_Trabajo' => $grades->nota_educacion_trabajo,
        ];

        $response = Http::timeout(10)->post($apiUrl, $data);

        if ($response->successful()) {
            $result = $response->json();

            AIPrediction::create([
                'user_id' => Auth::id(),
                'input_data' => $data,
                'predicted_career' => $result['carrera_recomendada'] ?? 'No definida',
                'confidence' => $result['confidence'] ?? 0,
                'top_careers' => $result['top_careers'] ?? [],
                'model_version' => $result['model_version'] ?? '1.0'
            ]);

            return $result;
        }

        throw new \Exception('Error al conectar con el servicio de IA');
    }

    public function aiResult()
    {
        $prediction = AIPrediction::where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$prediction) {
            return redirect()->route('tests.grades')
                ->with('info', 'Primero debes ingresar tus notas académicas.');
        }

        return view('tests.ai-result', compact('prediction'));
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

    private function getCareersForCategory($category)
    {
        $careerMap = [
            'tecnologia' => ['Ingeniería de Sistemas', 'Ingeniería Electrónica', 'Ciencias de la Computación'],
            'ciencias' => ['Medicina', 'Biología', 'Química', 'Física'],
            'humanidades' => ['Psicología', 'Derecho', 'Educación', 'Trabajo Social'],
            'negocios' => ['Administración', 'Economía', 'Contabilidad', 'Marketing'],
            'artes' => ['Diseño Gráfico', 'Arquitectura', 'Comunicación Audiovisual'],
        ];

        return $careerMap[$category] ?? ['Carrera General'];
    }

    public function start($id)
    {
        $test = VocationalTest::findOrFail($id);

        $hasCompleted = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->exists();

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test.');
        }

        // CORREGIDO: Especificar tabla en el where para evitar ambigüedad
        $lastResponse = TestResponse::where('user_id', Auth::id())
            ->where('test_responses.vocational_test_id', $id) // ← ESPECIFICAR TABLA
            ->join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
            ->orderByDesc('test_questions.question_number')
            ->select('test_questions.question_number')
            ->first();

        $nextQuestion = $lastResponse ? min($lastResponse->question_number + 1, $test->total_questions) : 1;

        return redirect()->route('tests.question', [
            'id' => $id,
            'question' => $nextQuestion
        ]);
    }

    public function restart($id)
    {
        TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->delete();

        TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->delete();

        return redirect()->route('tests.start', $id)
            ->with('success', 'Test reiniciado correctamente.');
    }

    public function question($id, $questionNumber)
    {
        $test = VocationalTest::with('questions')->findOrFail($id);

        if ($questionNumber < 1 || $questionNumber > $test->total_questions) {
            return redirect()->route('tests.start', $id);
        }

        $question = $test->questions()
            ->where('question_number', $questionNumber)
            ->firstOrFail();

        $previousAnswer = TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->where('test_question_id', $question->id)
            ->first();

        $progress = ($questionNumber / $test->total_questions) * 100;

        $answeredCount = TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->count();

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

            $test = VocationalTest::findOrFail($id);
            $question = TestQuestion::where('vocational_test_id', $id)
                ->where('question_number', $questionNumber)
                ->firstOrFail();

            $score = $this->calculateScore($question, $validated['answer']);

            // ✅ CORREGIDO: Sintaxis correcta del array
            TestResponse::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'vocational_test_id' => $id,  // ← CORREGIDO: 'clave' => valor
                    'test_question_id' => $question->id
                ],
                [
                    'answer' => $validated['answer'],
                    'score' => $score,
                ]
            );

            Log::info("Respuesta guardada correctamente");

            // Si es la última pregunta, procesar el test
            if ($questionNumber == $test->total_questions) {
                Log::info("Última pregunta alcanzada - procesando test");
                return $this->processTest($id);
            }

            // Si no es la última, ir a la siguiente pregunta
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

        DB::beginTransaction();
        try {
            $test = VocationalTest::findOrFail($id);
            $user = Auth::user();

            // Verificar que tengamos todas las respuestas
            $totalResponses = TestResponse::where('user_id', $user->id)
                ->where('vocational_test_id', $id)
                ->count();

            Log::info("Respuestas encontradas: {$totalResponses}/{$test->total_questions}");

            if ($totalResponses < $test->total_questions) {
                DB::rollBack();
                Log::warning("Faltan respuestas");

                // Encontrar primera pregunta sin responder
                $answeredQuestions = TestResponse::where('user_id', $user->id)
                    ->where('vocational_test_id', $id)
                    ->pluck('test_question_id')
                    ->toArray();

                $firstUnanswered = TestQuestion::where('vocational_test_id', $id)
                    ->whereNotIn('id', $answeredQuestions)
                    ->orderBy('question_number')
                    ->first();

                if ($firstUnanswered) {
                    return redirect()->route('tests.question', [
                        'id' => $id,
                        'question' => $firstUnanswered->question_number
                    ])->with('warning', 'Por favor completa todas las preguntas.');
                }
            }

            // CORREGIDO: Especificar tabla en el JOIN para evitar ambigüedad
            $scores = TestResponse::where('user_id', $user->id)
                ->where('test_responses.vocational_test_id', $id) // ← ESPECIFICAR TABLA
                ->join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
                ->select('test_questions.category', DB::raw('SUM(test_responses.score) as total'))
                ->groupBy('test_questions.category')
                ->pluck('total', 'category')
                ->toArray();

            Log::info("Puntajes calculados:", $scores);

            if (empty($scores)) {
                throw new \Exception("No se pudieron calcular los puntajes");
            }

            // Generar recomendaciones
            $recommendedCareers = $this->getRecommendedCareersRIASEC($scores);
            $analysis = $this->generateRIASECAnalysis($scores, $recommendedCareers);

            // Guardar resultado
            $result = TestResult::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'vocational_test_id' => $id
                ],
                [
                    'scores' => $scores,
                    'recommended_careers' => $recommendedCareers,
                    'analysis' => $analysis,
                    'total_score' => array_sum($scores),
                    'completed_at' => now()
                ]
            );

            Log::info("Resultado guardado ID: {$result->id}");

            DB::commit();

            return redirect()->route('tests.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
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

    // CORREGIDO: Agregar método para finalizar desde última pregunta
    public function finalizeFromLastQuestion(Request $request, $id)
    {
        Log::info("=== FINALIZAR DESDE ÚLTIMA PREGUNTA ===");
        Log::info("Test ID: {$id}, Usuario: " . Auth::id());

        try {
            $test = VocationalTest::findOrFail($id);
            $lastQuestionNumber = $test->total_questions;

            Log::info("Buscando última pregunta: número {$lastQuestionNumber}");

            // Buscar la última pregunta
            $question = TestQuestion::where('vocational_test_id', $id)
                ->where('question_number', $lastQuestionNumber)
                ->firstOrFail();

            Log::info("Última pregunta encontrada: ID {$question->id}");

            // Validar y guardar respuesta
            $validated = $request->validate([
                'answer' => 'required'
            ]);

            $score = $this->calculateScore($question, $validated['answer']);

            Log::info("Guardando respuesta: {$validated['answer']}, score: {$score}");

            // Guardar respuesta de la última pregunta
            TestResponse::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'vocational_test_id' => $id,
                    'test_question_id' => $question->id
                ],
                [
                    'answer' => $validated['answer'],
                    'score' => $score,
                ]
            );

            Log::info("Última respuesta guardada exitosamente");

            // Llamar al método de finalización
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
