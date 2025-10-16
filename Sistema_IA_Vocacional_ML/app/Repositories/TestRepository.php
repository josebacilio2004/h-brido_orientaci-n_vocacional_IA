<?php

namespace App\Repositories;

use App\Models\VocationalTest;
use App\Models\TestQuestion;
use App\Models\TestResponse;
use App\Models\TestResult;
use App\Models\AIPrediction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestRepository
{
    
    /**
     * Obtener tests activos
     */
    public function getActiveTests()
    {
        return VocationalTest::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener test por ID
     * Devolver objeto en lugar de array
     */
    public function getTestById(int $testId)
    {
        return VocationalTest::find($testId);
    }

    /**
     * Obtener test con preguntas
     * Devolver objeto en lugar de array
     */
    public function getTestWithQuestions(int $testId)
    {
        return VocationalTest::with('questions')->find($testId);
    }

    /**
     * Obtener pregunta por número
     * Devolver objeto en lugar de array
     */
    public function getQuestionByNumber(int $testId, int $questionNumber)
    {
        return TestQuestion::where('vocational_test_id', $testId)
            ->where('question_number', $questionNumber)
            ->first();
    }

    /**
     * Verificar si usuario completó el test
     */
    public function hasUserCompletedTest(int $userId, int $testId)
    {
        return TestResult::where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->whereNotNull('completed_at')
            ->exists();
    }

    /**
     * Obtener tests completados por usuario
     * Devolver array de IDs para compatibilidad con in_array()
     */
    public function getUserCompletedTests(int $userId)
    {
        return TestResult::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('vocational_test_id')
            ->toArray();
    }
    
    /**
     * Obtener tests completados con detalles
     */
    public function getUserCompletedTestsWithDetails(int $userId)
    {
        return TestResult::with('test')
            ->where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get();
    }

    /**
     * Obtener resultado de test del usuario
     */
    public function getUserTestResult(int $userId, int $testId)
    {
        $result = TestResult::with('test')
            ->where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->orderBy('completed_at', 'desc')
            ->first();
        
        if ($result) {
            $result->scores = json_decode($result->scores, true);
            $result->recommended_careers = json_decode($result->recommended_careers, true);
        }
        
        return $result;
    }

    /**
     * Obtener última pregunta respondida
     */
    public function getLastAnsweredQuestion(int $userId, int $testId)
    {
        $response = TestResponse::join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
            ->where('test_responses.user_id', $userId)
            ->where('test_responses.vocational_test_id', $testId)
            ->orderBy('test_questions.question_number', 'desc')
            ->first();
        
        return $response ? $response->question_number : 0;
    }

    /**
     * Eliminar respuestas del usuario
     */
    public function deleteUserResponses(int $userId, int $testId)
    {
        TestResponse::where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->delete();
    }

    /**
     * Eliminar resultado del usuario
     */
    public function deleteUserResult(int $userId, int $testId)
    {
        TestResult::where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->delete();
    }

    /**
     * Obtener respuesta previa del usuario
     */
    public function getUserAnswer(int $userId, int $testId, int $questionId)
    {
        return TestResponse::where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->where('test_question_id', $questionId)
            ->first();
    }

    /**
     * Contar respuestas del usuario
     */
    public function countUserAnswers(int $userId, int $testId)
    {
        return TestResponse::where('user_id', $userId)
            ->where('vocational_test_id', $testId)
            ->count();
    }

    /**
     * Guardar respuesta
     */
    public function saveAnswer(int $userId, int $testId, int $questionId, string $answer, int $score)
    {
        TestResponse::updateOrCreate(
            [
                'user_id' => $userId,
                'vocational_test_id' => $testId,
                'test_question_id' => $questionId
            ],
            [
                'answer' => $answer,
                'score' => $score
            ]
        );
    }

    /**
     * Obtener primera pregunta sin responder
     * Devolver objeto en lugar de array
     */
    public function getFirstUnansweredQuestion(int $userId, int $testId)
    {
        return TestQuestion::leftJoin('test_responses', function($join) use ($userId, $testId) {
                $join->on('test_questions.id', '=', 'test_responses.test_question_id')
                     ->where('test_responses.user_id', $userId)
                     ->where('test_responses.vocational_test_id', $testId);
            })
            ->where('test_questions.vocational_test_id', $testId)
            ->whereNull('test_responses.id')
            ->orderBy('test_questions.question_number')
            ->select('test_questions.*')
            ->first();
    }

    /**
     * Calcular puntajes por categoría
     */
    public function calculateScoresByCategory(int $userId, int $testId)
    {
        $results = TestResponse::join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
            ->where('test_responses.user_id', $userId)
            ->where('test_responses.vocational_test_id', $testId)
            ->select('test_questions.category', DB::raw('SUM(test_responses.score) as total_score'))
            ->groupBy('test_questions.category')
            ->get();
        
        $scores = [];
        foreach ($results as $row) {
            $scores[$row->category] = (int) $row->total_score;
        }
        
        return $scores;
    }

    /**
     * Guardar resultado del test
     * Devolver objeto en lugar de array
     */
    public function saveResult(int $userId, int $testId, array $scores, array $recommendedCareers, string $analysis, int $totalScore)
    {
        return TestResult::updateOrCreate(
            [
                'user_id' => $userId,
                'vocational_test_id' => $testId
            ],
            [
                'scores' => json_encode($scores),
                'recommended_careers' => json_encode($recommendedCareers),
                'analysis' => $analysis,
                'total_score' => $totalScore,
                'completed_at' => now()
            ]
        );
    }

    /**
     * Guardar predicción de IA
     */
    public function savePrediction(int $userId, array $inputData, string $recommendedCareer, float $confidence, array $topCareers, string $modelVersion)
    {
        return AIPrediction::create([
            'user_id' => $userId,
            'input_data' => json_encode($inputData),
            'recommended_career' => $recommendedCareer,
            'confidence' => $confidence,
            'top_careers' => json_encode($topCareers),
            'model_version' => $modelVersion
        ]);
    }

    /**
     * Obtener última predicción
     */
    public function getLatestPrediction(int $userId)
    {
        $prediction = AIPrediction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($prediction) {
            $prediction->input_data = json_decode($prediction->input_data, true);
            $prediction->top_careers = json_decode($prediction->top_careers, true);
        }
        
        return $prediction;
    }

    /**
     * Obtener último resultado del usuario
     */
    public function getUserLatestResult(int $userId)
    {
        $result = TestResult::with('test')
            ->where('user_id', $userId)
            ->orderBy('completed_at', 'desc')
            ->first();
        
        if ($result) {
            $result->scores = json_decode($result->scores, true);
            $result->recommended_careers = json_decode($result->recommended_careers, true);
        }
        
        return $result;
    }
}
