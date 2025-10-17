<?php

namespace App\DAO\Interfaces;

interface TestDAOInterface
{
    /**
     * Obtener test por ID
     */
    public function findById(int $id);

    /**
     * Obtener todos los tests activos
     */
    public function getActiveTests();

    /**
     * Obtener test con preguntas
     */
    public function getTestWithQuestions(int $testId);

    /**
     * Obtener pregunta por número
     */
    public function getQuestionByNumber(int $testId, int $questionNumber);

    /**
     * Guardar respuesta
     */
    public function saveAnswer(int $userId, int $testId, int $questionId, string $answer, int $score);

    /**
     * Obtener respuesta del usuario
     */
    public function getUserAnswer(int $userId, int $testId, int $questionId);

    /**
     * Guardar resultado del test
     */
    public function saveResult(int $userId, int $testId, array $scores, array $recommendedCareers, string $analysis, int $totalScore);

    /**
     * Obtener resultado del usuario
     */
    public function getUserTestResult(int $userId, int $testId);

    /**
     * Obtener tests completados por usuario
     */
    public function getUserCompletedTests(int $userId);

    /**
     * Verificar si usuario completó el test
     */
    public function hasUserCompletedTest(int $userId, int $testId);

    /**
     * Calcular puntajes por categoría
     */
    public function calculateScoresByCategory(int $userId, int $testId);

    /**
     * Eliminar respuestas del usuario
     */
    public function deleteUserResponses(int $userId, int $testId);

    /**
     * Obtener última pregunta respondida
     */
    public function getLastAnsweredQuestion(int $userId, int $testId);

    /**
     * Obtener primera pregunta sin responder
     */
    public function getFirstUnansweredQuestion(int $userId, int $testId);

    /**
     * Contar respuestas del usuario
     */
    public function countUserAnswers(int $userId, int $testId);
}
