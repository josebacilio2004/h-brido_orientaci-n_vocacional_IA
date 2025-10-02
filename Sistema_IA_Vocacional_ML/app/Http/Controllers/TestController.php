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

class TestController extends Controller
{
    /**
     * Apply authentication middleware to all controller methods.
     */
    protected $middleware = ['auth'];

    // Mostrar lista de tests disponibles
    public function index()
    {
        $tests = VocationalTest::where('is_active', true)->get();
        $completedTests = TestResult::where('user_id', Auth::id())
            ->pluck('vocational_test_id')
            ->toArray();

        return view('tests.index', compact('tests', 'completedTests'));
    }

    // Mostrar test específico
    public function show($id)
    {
        $test = VocationalTest::with('questions')->findOrFail($id);

        // Verificar si ya completó el test
        $hasCompleted = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->exists();

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test. Aquí están tus resultados.');
        }

        return view('tests.show', compact('test'));
    }

    // Guardar respuestas del test
    public function submit(Request $request, $id)
    {
        $test = VocationalTest::findOrFail($id);
        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Guardar respuestas
            $scores = [];
            foreach ($request->answers as $questionId => $answer) {
                $question = TestQuestion::findOrFail($questionId);

                // Calcular puntaje según tipo de pregunta
                $score = $this->calculateScore($question, $answer);

                TestResponse::create([
                    'user_id' => $user->id,
                    'vocational_test_id' => $id,
                    'test_question_id' => $questionId,
                    'answer' => is_array($answer) ? json_encode($answer) : $answer,
                    'score' => $score
                ]);

                // Agrupar puntajes por categoría
                $category = $question->category ?? 'general';
                if (!isset($scores[$category])) {
                    $scores[$category] = 0;
                }
                $scores[$category] += $score;
            }

            // Calcular carreras recomendadas
            $recommendedCareers = $this->getRecommendedCareers($scores);

            // Guardar resultado
            $result = TestResult::create([
                'user_id' => $user->id,
                'vocational_test_id' => $id,
                'scores' => $scores,
                'recommended_careers' => $recommendedCareers,
                'analysis' => $this->generateAnalysis($scores, $recommendedCareers),
                'total_score' => array_sum($scores),
                'completed_at' => now()
            ]);

            DB::commit();

            return redirect()->route('tests.result', $id)
                ->with('success', '¡Test completado exitosamente!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar el test: ' . $e->getMessage());
        }
    }

    // Mostrar resultado del test
    public function result($id)
    {
        $result = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->with('test')
            ->latest()
            ->firstOrFail();

        return view('tests.result', compact('result'));
    }

    // Formulario de notas académicas
    public function gradesForm()
    {
        $grades = StudentGrade::where('user_id', Auth::id())->first();
        return view('tests.grades', compact('grades'));
    }

    // Guardar notas y obtener predicción de IA
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

        // Guardar o actualizar notas
        $grades = StudentGrade::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, ['academic_year' => date('Y')])
        );

        // Llamar al API de Python para predicción
        try {
            $prediction = $this->getPredictionFromAI($grades);

            return redirect()->route('tests.ai-result')
                ->with('success', 'Predicción generada exitosamente')
                ->with('prediction', $prediction);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al obtener predicción: ' . $e->getMessage());
        }
    }

    // Obtener predicción del modelo de IA
    private function getPredictionFromAI(StudentGrade $grades)
    {
        // URL del API de Python (ajustar según tu configuración)
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

            // Guardar predicción en la base de datos
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

    // Mostrar resultado de predicción IA
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

    // Calcular puntaje de una respuesta
    private function calculateScore($question, $answer)
    {
        switch ($question->type) {
            case 'scale':
                return (int) $answer;
            case 'yes_no':
                return $answer === 'yes' ? 5 : 0;
            case 'multiple_choice':
                // Aquí podrías tener lógica más compleja
                return 3;
            default:
                return 0;
        }
    }

    // Obtener carreras recomendadas basadas en puntajes
    private function getRecommendedCareers($scores)
    {
        // Lógica simple de recomendación
        // En producción, esto debería ser más sofisticado
        $recommendations = [];

        arsort($scores);
        $topCategories = array_slice(array_keys($scores), 0, 3);

        foreach ($topCategories as $category) {
            $recommendations[] = [
                'category' => $category,
                'score' => $scores[$category],
                'careers' => $this->getCareersForCategory($category)
            ];
        }

        return $recommendations;
    }

    // Obtener carreras por categoría
    private function getCareersForCategory($category)
    {
        // Mapeo simple de categorías a carreras
        $careerMap = [
            'tecnologia' => ['Ingeniería de Sistemas', 'Ingeniería Electrónica', 'Ciencias de la Computación'],
            'ciencias' => ['Medicina', 'Biología', 'Química', 'Física'],
            'humanidades' => ['Psicología', 'Derecho', 'Educación', 'Trabajo Social'],
            'negocios' => ['Administración', 'Economía', 'Contabilidad', 'Marketing'],
            'artes' => ['Diseño Gráfico', 'Arquitectura', 'Comunicación Audiovisual'],
        ];

        return $careerMap[$category] ?? ['Carrera General'];
    }

    // Generar análisis del resultado
    private function generateAnalysis($scores, $recommendations)
    {
        $topCategory = array_key_first($scores);
        $topScore = $scores[$topCategory];

        return "Basado en tus respuestas, muestras una fuerte inclinación hacia el área de {$topCategory} " .
            "con un puntaje de {$topScore}. Esto sugiere que tienes habilidades y preferencias " .
            "que se alinean bien con carreras en este campo.";
    }

    public function start($id)
    {
        $test = VocationalTest::findOrFail($id);

        // Verificar si ya completó el test
        $hasCompleted = TestResult::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->exists();

        if ($hasCompleted) {
            return redirect()->route('tests.result', $id)
                ->with('info', 'Ya has completado este test. Aquí están tus resultados.');
        }

        // Limpiar respuestas previas si las hay (test incompleto)
        TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->whereNull('completed_at')
            ->delete();

        // Redirigir a la primera pregunta
        return redirect()->route('tests.question', ['id' => $id, 'question' => 1]);
    }

    public function question($id, $questionNumber)
    {
        $test = VocationalTest::with('questions')->findOrFail($id);

        // Verificar que el número de pregunta sea válido
        if ($questionNumber < 1 || $questionNumber > $test->total_questions) {
            return redirect()->route('tests.start', $id);
        }

        // Obtener la pregunta actual
        $question = $test->questions()
            ->where('question_number', $questionNumber)
            ->firstOrFail();

        // Obtener respuesta previa si existe
        $previousAnswer = TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->where('test_question_id', $question->id)
            ->first();

        // Calcular progreso
        $progress = ($questionNumber / $test->total_questions) * 100;

        // Contar respuestas completadas
        $answeredCount = TestResponse::where('user_id', Auth::id())
            ->where('vocational_test_id', $id)
            ->count();

        return view('tests.question', compact('test', 'question', 'questionNumber', 'progress', 'answeredCount', 'previousAnswer'));
    }

    public function saveAnswer(Request $request, $id, $questionNumber)
    {
        $validated = $request->validate([
            'answer' => 'required'
        ]);

        $test = VocationalTest::findOrFail($id);
        $question = TestQuestion::where('vocational_test_id', $id)
            ->where('question_number', $questionNumber)
            ->firstOrFail();

        // Guardar o actualizar respuesta
        TestResponse::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'vocational_test_id' => $id,
                'test_question_id' => $question->id
            ],
            [
                'answer' => $validated['answer'],
                'score' => is_numeric($validated['answer']) ? (int) $validated['answer'] : 0,
                'completed_at' => null
            ]
        );

        // Siguiente o finalizar
        if ($questionNumber < $test->total_questions) {
            return redirect()->route('tests.question', [
                'id' => $id,
                'question' => $questionNumber + 1
            ]);
        } else {
            return redirect()->route('tests.process', $id);
        }
    }


    public function process($id)
    {
        $test = VocationalTest::findOrFail($id);
        $user = Auth::user();

        // Verificar que todas las preguntas estén respondidas
        $answeredCount = TestResponse::where('user_id', $user->id)
            ->where('vocational_test_id', $id)
            ->count();

        if ($answeredCount < $test->total_questions) {
            return redirect()->route('tests.start', $id)
                ->with('error', 'Debes completar todas las preguntas del test.');
        }


        DB::beginTransaction();
        try {
            // Calcular puntajes por categoría
            $scores = TestResponse::where('user_id', $user->id)
                ->where('vocational_test_id', $id)
                ->join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
                ->select('test_questions.category', DB::raw('SUM(test_responses.score) as total'))
                ->groupBy('test_questions.category')
                ->pluck('total', 'category')
                ->toArray();

            // Obtener carreras recomendadas
            $recommendedCareers = $this->getRecommendedCareersRIASEC($scores);

            // Generar análisis personalizado
            $analysis = $this->generateRIASECAnalysis($scores, $recommendedCareers);

            // Guardar resultado
            $result = TestResult::create([
                'user_id' => $user->id,
                'vocational_test_id' => $id,
                'scores' => $scores,
                'recommended_careers' => $recommendedCareers,
                'analysis' => $analysis,
                'total_score' => array_sum($scores),
                'completed_at' => now()
            ]);

            // Marcar respuestas como completadas
            TestResponse::where('user_id', $user->id)
                ->where('vocational_test_id', $id)
                ->update(['completed_at' => now()]);

            DB::commit();

            return redirect()->route('tests.result', $id)
                ->with('success', '¡Test completado exitosamente! Aquí están tus resultados.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tests.start', $id)
                ->with('error', 'Error al procesar el test: ' . $e->getMessage());
        }
    }

    private function getRecommendedCareersRIASEC($scores)
    {
        // Ordenar puntajes de mayor a menor
        arsort($scores);

        // Mapeo de categorías RIASEC a carreras
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
