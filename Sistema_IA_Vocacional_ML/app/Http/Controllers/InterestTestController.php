<?php

namespace App\Http\Controllers;

use App\Models\TestInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InterestTestController extends Controller
{
    public function index()
    {
        try {
            $tests = TestInterest::obtenerActivos();
            $completedTests = TestInterest::obtenerTestsCompletados(Auth::id());

            return view('tests.interests.index', compact('tests', 'completedTests'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in InterestTestController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar tests de intereses');
        }
    }

    public function show($testId)
    {
        try {
            $test = TestInterest::obtenerConPreguntas($testId);
            if (!$test) {
                return redirect()->route('tests.interests.index')->with('error', 'Test no encontrado');
            }

            return view('tests.interests.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in InterestTestController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar test');
        }
    }

    public function question($testId, $questionNumber)
    {
        try {
            $test = TestInterest::find($testId);
            if (!$test) {
                return redirect()->route('tests.interests.index')->with('error', 'Test no encontrado');
            }

            $question = TestInterest::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return redirect()->route('tests.interests.show', $testId)->with('error', 'Pregunta no encontrada');
            }

            $previousAnswer = TestInterest::obtenerRespuestaPrevia(Auth::id(), $testId, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;

            return view('tests.interests.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in InterestTestController@question: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar pregunta');
        }
    }

    public function processAnswer(Request $request, $testId, $questionNumber)
    {
        try {
            $validated = $request->validate([
                'answer' => 'required|integer|min:0|max:4'
            ]);

            $question = TestInterest::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return response()->json(['error' => 'Pregunta no encontrada'], 404);
            }

            TestInterest::guardarRespuesta(Auth::id(), $testId, $question->id, $validated['answer'], $validated['answer']);

            return response()->json([
                'success' => true,
                'message' => 'Respuesta guardada',
                'nextQuestion' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in InterestTestController@processAnswer: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar respuesta'], 500);
        }
    }

    public function result($testId)
    {
        try {
            $test = TestInterest::find($testId);
            if (!$test) {
                return redirect()->route('tests.interests.index')->with('error', 'Test no encontrado');
            }

            $result = TestInterest::obtenerResultado(Auth::id(), $testId);
            if (!$result) {
                $scores = TestInterest::calcularPuntajesPorCategoria(Auth::id(), $testId);
                $analysis = $this->generateInterestAnalysis($scores);
                $recommendedCareers = $this->getRecommendedCareers($scores);
                $totalScore = $scores->sum('total_score');

                TestInterest::guardarResultado(Auth::id(), $testId, $scores, $recommendedCareers, $analysis, $totalScore);

                return view('tests.interests.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
            }

            return view('tests.interests.result', [
                'test' => $test,
                'scores' => json_decode($result->scores, true),
                'analysis' => $result->analysis,
                'recommendedCareers' => json_decode($result->recommended_careers, true)
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in InterestTestController@result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar resultados');
        }
    }

    private function generateInterestAnalysis($scores)
    {
        $topCategory = $scores->first();
        return "Basado en tus respuestas, tu área de interés principal es: {$topCategory->category}. Esto sugiere que tienes una fuerte inclinación hacia actividades relacionadas con {$topCategory->category}.";
    }

    private function getRecommendedCareers($scores)
    {
        $topCategories = $scores->take(3)->pluck('category')->toArray();
        // Aquí se integraría con el modelo ML para obtener carreras recomendadas
        return $topCategories;
    }
}
