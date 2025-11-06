<?php

namespace App\Http\Controllers;

use App\Models\TestSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkillTestController extends Controller
{
    public function index()
    {
        try {
            $tests = TestSkill::obtenerActivos();
            $completedTests = TestSkill::obtenerTestsCompletados(Auth::id());

            return view('tests.skills.index', compact('tests', 'completedTests'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in SkillTestController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar tests de habilidades');
        }
    }

    public function show($testId)
    {
        try {
            $test = TestSkill::obtenerConPreguntas($testId);
            if (!$test) {
                return redirect()->route('tests.skills.index')->with('error', 'Test no encontrado');
            }

            return view('tests.skills.show', compact('test'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in SkillTestController@show: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar test');
        }
    }

    public function question($testId, $questionNumber)
    {
        try {
            $test = TestSkill::find($testId);
            if (!$test) {
                return redirect()->route('tests.skills.index')->with('error', 'Test no encontrado');
            }

            $question = TestSkill::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return redirect()->route('tests.skills.show', $testId)->with('error', 'Pregunta no encontrada');
            }

            $previousAnswer = TestSkill::obtenerRespuestaPrevia(Auth::id(), $testId, $question->id);
            $totalQuestions = $test->total_questions;
            $currentQuestion = $questionNumber;

            return view('tests.skills.question', compact('test', 'question', 'previousAnswer', 'totalQuestions', 'currentQuestion'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in SkillTestController@question: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar pregunta');
        }
    }

    public function processAnswer(Request $request, $testId, $questionNumber)
    {
        try {
            $validated = $request->validate([
                'answer' => 'required|integer|min:0|max:4'
            ]);

            $question = TestSkill::obtenerPregunta($testId, $questionNumber);
            if (!$question) {
                return response()->json(['error' => 'Pregunta no encontrada'], 404);
            }

            TestSkill::guardarRespuesta(Auth::id(), $testId, $question->id, $validated['answer'], $validated['answer']);

            return response()->json([
                'success' => true,
                'message' => 'Respuesta guardada',
                'nextQuestion' => $questionNumber + 1
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in SkillTestController@processAnswer: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar respuesta'], 500);
        }
    }

    public function result($testId)
    {
        try {
            $test = TestSkill::find($testId);
            if (!$test) {
                return redirect()->route('tests.skills.index')->with('error', 'Test no encontrado');
            }

            $result = TestSkill::obtenerResultado(Auth::id(), $testId);
            if (!$result) {
                $scores = TestSkill::calcularPuntajesPorCategoria(Auth::id(), $testId);
                $analysis = $this->generateSkillAnalysis($scores);
                $recommendedCareers = $this->getRecommendedCareers($scores);
                $totalScore = $scores->sum('total_score');

                TestSkill::guardarResultado(Auth::id(), $testId, $scores, $recommendedCareers, $analysis, $totalScore);

                return view('tests.skills.result', compact('test', 'scores', 'analysis', 'recommendedCareers'));
            }

            return view('tests.skills.result', [
                'test' => $test,
                'scores' => json_decode($result->scores, true),
                'analysis' => $result->analysis,
                'recommendedCareers' => json_decode($result->recommended_careers, true)
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in SkillTestController@result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar resultados');
        }
    }

    private function generateSkillAnalysis($scores)
    {
        $topSkill = $scores->first();
        return "Según tus respuestas, tu habilidad más desarrollada es: {$topSkill->skill_category}. Esto indica que tienes un fuerte potencial en actividades que requieran estas competencias.";
    }

    private function getRecommendedCareers($scores)
    {
        $topSkills = $scores->take(3)->pluck('skill_category')->toArray();
        return $topSkills;
    }
}
