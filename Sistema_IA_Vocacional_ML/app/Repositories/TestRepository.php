<?php

namespace App\Repositories;

use App\DAO\Interfaces\TestDAOInterface;

class TestRepository
{
    private $testDAO;

    public function __construct($testDAO)
    {
        $this->testDAO = $testDAO;
    }

    /**
     * Obtener tests activos
     */
    public function getActiveTests()
    {
        return $this->testDAO->getActiveTests();
    }

    /**
     * Obtener test por ID
     */
    public function getTestById(int $testId)
    {
        return $this->testDAO->findById($testId);
    }

    /**
     * Obtener test con preguntas
     */
    public function getTestWithQuestions(int $testId)
    {
        return $this->testDAO->getTestWithQuestions($testId);
    }

    /**
     * Obtener pregunta por número
     */
    public function getQuestionByNumber(int $testId, int $questionNumber)
    {
        return $this->testDAO->getQuestionByNumber($testId, $questionNumber);
    }

    /**
     * Verificar si usuario completó el test
     */
    public function hasUserCompletedTest(int $userId, int $testId)
    {
        return $this->testDAO->hasUserCompletedTest($userId, $testId);
    }

    /**
     * Obtener tests completados por usuario
     */
    public function getUserCompletedTests(int $userId)
    {
        return $this->testDAO->getUserCompletedTests($userId);
    }

    /**
     * Obtener resultado de test del usuario
     */
    public function getUserTestResult(int $userId, int $testId)
    {
        return $this->testDAO->getUserTestResult($userId, $testId);
    }

    /**
     * Obtener última pregunta respondida
     */
    public function getLastAnsweredQuestion(int $userId, int $testId)
    {
        return $this->testDAO->getLastAnsweredQuestion($userId, $testId);
    }

    /**
     * Eliminar respuestas del usuario
     */
    public function deleteUserResponses(int $userId, int $testId)
    {
        return $this->testDAO->deleteUserResponses($userId, $testId);
    }

    /**
     * Eliminar resultado del usuario
     */
    public function deleteUserResult(int $userId, int $testId)
    {
        return $this->testDAO->deleteUserResponses($userId, $testId);
    }

    /**
     * Obtener respuesta previa del usuario
     */
    public function getUserAnswer(int $userId, int $testId, int $questionId)
    {
        return $this->testDAO->getUserAnswer($userId, $testId, $questionId);
    }

    /**
     * Contar respuestas del usuario
     */
    public function countUserAnswers(int $userId, int $testId)
    {
        return $this->testDAO->countUserAnswers($userId, $testId);
    }

    /**
     * Guardar respuesta
     */
    public function saveAnswer(int $userId, int $testId, int $questionId, string $answer, int $score)
    {
        return $this->testDAO->saveAnswer($userId, $testId, $questionId, $answer, $score);
    }

    /**
     * Obtener primera pregunta sin responder
     */
    public function getFirstUnansweredQuestion(int $userId, int $testId)
    {
        return $this->testDAO->getFirstUnansweredQuestion($userId, $testId);
    }

    /**
     * Calcular puntajes por categoría
     */
    public function calculateScoresByCategory(int $userId, int $testId)
    {
        return $this->testDAO->calculateScoresByCategory($userId, $testId);
    }

    /**
     * Guardar resultado del test
     */
    public function saveResult(int $userId, int $testId, array $scores, array $recommendedCareers, string $analysis, int $totalScore)
    {
        return $this->testDAO->saveResult($userId, $testId, $scores, $recommendedCareers, $analysis, $totalScore);
    }

    /**
     * Obtener último resultado del usuario
     */
    public function getUserLatestResult(int $userId)
    {
        return $this->testDAO->getUserTestResult($userId, 1);
    }

    /**
     * Obtener tests completados con detalles
     */
    public function getUserCompletedTestsWithDetails(int $userId)
    {
        $completedTests = $this->testDAO->getUserCompletedTests($userId);
        $results = [];
        
        foreach ($completedTests as $testId) {
            $result = $this->testDAO->getUserTestResult($userId, $testId);
            if ($result) {
                $results[] = $result;
            }
        }
        
        return $results;
    }
    public function savePrediction(int $userId, array $data, string $recommendedCareer, float $confidence, array $topCareers, string $modelVersion)
    {
        return $this->testDAO->savePrediction($userId, $data, $recommendedCareer, $confidence, $topCareers, $modelVersion);
    }
    public function getLatestPrediction(int $userId)
    {
        return $this->testDAO->getLatestPrediction($userId);
    }
}
