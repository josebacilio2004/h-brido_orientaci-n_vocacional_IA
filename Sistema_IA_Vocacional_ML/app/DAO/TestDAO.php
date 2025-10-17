<?php

namespace App\DAO;

use App\DAO\Interfaces\TestDAOInterface;
use App\Models\VocationalTest;
use App\Models\TestQuestion;
use App\Models\TestResponse;
use App\Models\TestResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestDAO implements TestDAOInterface
{
    /**
     * Obtener test por ID
     */
    public function findById(int $id)
    {
        try {
            return VocationalTest::find($id);
        } catch (\Exception $e) {
            Log::error('Error finding test by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener todos los tests activos
     */
    public function getActiveTests()
    {
        try {
            return VocationalTest::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting active tests: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener test con preguntas
     */
    public function getTestWithQuestions(int $testId)
    {
        try {
            return VocationalTest::with('questions')->find($testId);
        } catch (\Exception $e) {
            Log::error('Error getting test with questions: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener pregunta por número
     */
    public function getQuestionByNumber(int $testId, int $questionNumber)
    {
        try {
            return TestQuestion::where('vocational_test_id', $testId)
                ->where('question_number', $questionNumber)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error getting question by number: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Guardar respuesta
     */
    public function saveAnswer(int $userId, int $testId, int $questionId, string $answer, int $score)
    {
        try {
            return TestResponse::updateOrCreate(
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
        } catch (\Exception $e) {
            Log::error('Error saving answer: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener respuesta del usuario
     */
    public function getUserAnswer(int $userId, int $testId, int $questionId)
    {
        try {
            return TestResponse::where('user_id', $userId)
                ->where('vocational_test_id', $testId)
                ->where('test_question_id', $questionId)
                ->first();
        } catch (\Exception $e) {
            Log::error('Error getting user answer: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Guardar resultado del test
     */
    public function saveResult(int $userId, int $testId, array $scores, array $recommendedCareers, string $analysis, int $totalScore)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error saving test result: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener resultado del usuario
     */
    public function getUserTestResult(int $userId, int $testId)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error getting user test result: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener tests completados por usuario
     */
    public function getUserCompletedTests(int $userId)
    {
        try {
            return TestResult::where('user_id', $userId)
                ->whereNotNull('completed_at')
                ->pluck('vocational_test_id')
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting user completed tests: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar si usuario completó el test
     */
    public function hasUserCompletedTest(int $userId, int $testId)
    {
        try {
            return TestResult::where('user_id', $userId)
                ->where('vocational_test_id', $testId)
                ->whereNotNull('completed_at')
                ->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if user completed test: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcular puntajes por categoría
     */
    public function calculateScoresByCategory(int $userId, int $testId)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error calculating scores by category: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Eliminar respuestas del usuario
     */
    public function deleteUserResponses(int $userId, int $testId)
    {
        try {
            return TestResponse::where('user_id', $userId)
                ->where('vocational_test_id', $testId)
                ->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting user responses: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener última pregunta respondida
     */
    public function getLastAnsweredQuestion(int $userId, int $testId)
    {
        try {
            $response = TestResponse::join('test_questions', 'test_responses.test_question_id', '=', 'test_questions.id')
                ->where('test_responses.user_id', $userId)
                ->where('test_responses.vocational_test_id', $testId)
                ->orderBy('test_questions.question_number', 'desc')
                ->first();
            
            return $response ? $response->question_number : 0;
        } catch (\Exception $e) {
            Log::error('Error getting last answered question: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtener primera pregunta sin responder
     */
    public function getFirstUnansweredQuestion(int $userId, int $testId)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error getting first unanswered question: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Contar respuestas del usuario
     */
    public function countUserAnswers(int $userId, int $testId)
    {
        try {
            return TestResponse::where('user_id', $userId)
                ->where('vocational_test_id', $testId)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error counting user answers: ' . $e->getMessage());
            return 0;
        }
    }
    public function savePrediction(int $userId, array $data, string $recommendedCareer, float $confidence, array $topCareers, string $modelVersion)
    {
        try {
            return TestResult::updateOrCreate(
                [
                    'user_id' => $userId,
                    'vocational_test_id' => $data['id'],
                ],
                [
                    'scores' => json_encode($data['scores']),
                    'recommended_careers' => json_encode($data['recommended_careers']),
                    'analysis' => $data['analysis'],
                    'total_score' => $data['total_score'],
                    'completed_at' => now(),
                    'model_version' => $modelVersion,
                    'confidence' => $confidence,
                    'top_careers' => json_encode($topCareers),
                    'recommended_career' => $recommendedCareer
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error saving test result: ' . $e->getMessage());
            throw $e;
        }
    }
}
