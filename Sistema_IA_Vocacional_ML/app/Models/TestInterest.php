<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestInterest extends Model
{
    use HasFactory;

    protected $table = 'test_interests';

    protected $fillable = [
        'name',
        'description',
        'total_questions',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================
    // MÉTODOS DAO
    // ============================================

    /**
     * Obtener todos los tests de intereses activos
     */
    public static function obtenerActivos()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Obtener test con sus preguntas
     */
    public static function obtenerConPreguntas($testId)
    {
        $test = self::find($testId);
        if (!$test) {
            return null;
        }

        $test->preguntas = InterestQuestion::where('test_interest_id', $testId)
            ->orderBy('question_number')
            ->get();

        return $test;
    }

    /**
     * Obtener pregunta específica
     */
    public static function obtenerPregunta($testId, $questionNumber)
    {
        return InterestQuestion::where('test_interest_id', $testId)
            ->where('question_number', $questionNumber)
            ->first();
    }

    /**
     * Guardar respuesta del usuario
     */
    public static function guardarRespuesta($userId, $testId, $questionId, $answer, $score)
    {
        return InterestResponse::updateOrCreate(
            [
                'user_id' => $userId,
                'test_interest_id' => $testId,
                'interest_question_id' => $questionId,
            ],
            [
                'answer' => $answer,
                'score' => $score,
            ]
        );
    }

    /**
     * Obtener respuesta anterior
     */
    public static function obtenerRespuestaPrevia($userId, $testId, $questionId)
    {
        return InterestResponse::where('user_id', $userId)
            ->where('test_interest_id', $testId)
            ->where('interest_question_id', $questionId)
            ->first();
    }

    /**
     * Contar respuestas completadas
     */
    public static function contarRespuestas($userId, $testId)
    {
        return InterestResponse::where('user_id', $userId)
            ->where('test_interest_id', $testId)
            ->count();
    }

    /**
     * Calcular puntajes por categoría
     */
    public static function calcularPuntajesPorCategoria($userId, $testId)
    {
        return DB::table('interest_responses')
            ->join('interest_questions', 'interest_responses.interest_question_id', '=', 'interest_questions.id')
            ->where('interest_responses.user_id', $userId)
            ->where('interest_responses.test_interest_id', $testId)
            ->groupBy('interest_questions.category')
            ->selectRaw('interest_questions.category, SUM(interest_responses.score) as total_score, COUNT(*) as question_count, AVG(interest_responses.score) as avg_score')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Guardar resultado del test
     */
    public static function guardarResultado($userId, $testId, $scores, $recommendedCareers, $analysis, $totalScore)
    {
        return InterestResult::updateOrCreate(
            [
                'user_id' => $userId,
                'test_interest_id' => $testId,
            ],
            [
                'scores' => json_encode($scores),
                'recommended_careers' => json_encode($recommendedCareers),
                'analysis' => $analysis,
                'total_score' => $totalScore,
                'completed_at' => now(),
            ]
        );
    }

    /**
     * Obtener resultado del test
     */
    public static function obtenerResultado($userId, $testId)
    {
        return InterestResult::where('user_id', $userId)
            ->where('test_interest_id', $testId)
            ->first();
    }

    /**
     * Obtener tests completados por usuario
     */
    public static function obtenerTestsCompletados($userId)
    {
        return InterestResult::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('test_interest_id')
            ->toArray();
    }

    /**
     * Verificar si test está completado
     */
    public static function estaCompletado($userId, $testId)
    {
        return InterestResult::where('user_id', $userId)
            ->where('test_interest_id', $testId)
            ->whereNotNull('completed_at')
            ->exists();
    }
}
