<?php

namespace App\Http\Controllers;

use App\Models\TestPersonality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PersonalityTestController extends Controller
{
    public function index()
    {
        try {
            $tests = TestPersonality::obtenerActivos();
            $completedTests = TestPersonality::obtenerTestsCompletados(Auth::id());

            return view('tests.personality.index', compact('tests', 'completedTests'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in PersonalityTestController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar tests de personalidad');
        }
    }

    public function show($testId)
    {
        try {
            $test = TestPersonality::obtenerConPreguntas($testId);
            if (!$test) {
                return redirect()->route('tests.personality.index')->with('error', 'Test no encontrado');
            }

            return view('tests.personality.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in PersonalityTestController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar test');
        }
    }

    public function question($testId, $questionNumber)
    {
        try {
            $test = TestPersonality::find($testId);
            if (!$test) {
                return redirect()->route('tests.personality.index')->with('error', 'Test no encontrado');
            }

            $question = TestPersonality::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return redirect()->route('tests.personality.show', $testId)->with('error', 'Pregunta no encontrada');
            }

            $previousAnswer = TestPersonality::obtenerRespuestaPrevia(Auth::id(), $testId, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;

            return view('tests.personality.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in PersonalityTestController@question: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar pregunta');
        }
    }

    public function processAnswer(Request $request, $testId, $questionNumber)
    {
        try {
            $validated = $request->validate([
                'answer' => 'required|integer|min:0|max:4'
            ]);

            $question = TestPersonality::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return response()->json(['error' => 'Pregunta no encontrada'], 404);
            }

            TestPersonality::guardarRespuesta(Auth::id(), $testId, $question->id, $validated['answer'], $validated['answer']);

            return response()->json([
                'success' => true,
                'message' => 'Respuesta guardada',
                'nextQuestion' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in PersonalityTestController@processAnswer: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar respuesta'], 500);
        }
    }

    public function result($testId)
    {
        try {
            $test = TestPersonality::find($testId);
            if (!$test) {
                return redirect()->route('tests.personality.index')->with('error', 'Test no encontrado');
            }

            $result = TestPersonality::obtenerResultado(Auth::id(), $testId);
            if (!$result) {
                $scores = TestPersonality::calcularPuntajesPorCategoria(Auth::id(), $testId);
                $analysis = $this->generatePersonalityAnalysis($scores);
                $recommendedCareers = $this->getRecommendedCareers($scores);
                $totalScore = $scores->sum('total_score');

                TestPersonality::guardarResultado(Auth::id(), $testId, $scores, $recommendedCareers, $analysis, $totalScore);

                return view('tests.personality.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
            }

            return view('tests.personality.result', [
                'test' => $test,
                'scores' => json_decode($result->scores, true),
                'analysis' => $result->analysis,
                'recommendedCareers' => json_decode($result->recommended_careers, true)
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in PersonalityTestController@result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar resultados');
        }
    }

    private function generatePersonalityAnalysis($scores)
    {
        $topTrait = $scores->first();
        return "Tu rasgo de personalidad predominante es: {$topTrait->trait}. Este tipo de personalidad generalmente se desempeña bien en ambientes que valoren tus preferencias naturales.";
    }

    private function getRecommendedCareers($scores)
    {
        $topTraits = $scores->take(2)->pluck('trait')->toArray();
        return $topTraits;
    }
}
