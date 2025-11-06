<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestPersonality extends Model
{
    use HasFactory;

    protected $table = 'test_personality';

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
     * Obtener todos los tests de personalidad activos
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

        $test->preguntas = PersonalityQuestion::where('test_personality_id', $testId)
            ->orderBy('question_number')
            ->get();

        return $test;
    }

    /**
     * Obtener pregunta específica
     */
    public static function obtenerPregunta($testId, $questionNumber)
    {
        return PersonalityQuestion::where('test_personality_id', $testId)
            ->where('question_number', $questionNumber)
            ->first();
    }

    /**
     * Guardar respuesta del usuario
     */
    public static function guardarRespuesta($userId, $testId, $questionId, $answer, $score)
    {
        return PersonalityResponse::updateOrCreate(
            [
                'user_id' => $userId,
                'test_personality_id' => $testId,
                'personality_question_id' => $questionId,
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
        return PersonalityResponse::where('user_id', $userId)
            ->where('test_personality_id', $testId)
            ->where('personality_question_id', $questionId)
            ->first();
    }

    /**
     * Contar respuestas completadas
     */
    public static function contarRespuestas($userId, $testId)
    {
        return PersonalityResponse::where('user_id', $userId)
            ->where('test_personality_id', $testId)
            ->count();
    }

    /**
     * Calcular puntajes por categoría
     */
    public static function calcularPuntajesPorCategoria($userId, $testId)
    {
        return DB::table('personality_responses')
            ->join('personality_questions', 'personality_responses.personality_question_id', '=', 'personality_questions.id')
            ->where('personality_responses.user_id', $userId)
            ->where('personality_responses.test_personality_id', $testId)
            ->groupBy('personality_questions.trait')
            ->selectRaw('personality_questions.trait, SUM(personality_responses.score) as total_score, COUNT(*) as question_count, AVG(personality_responses.score) as avg_score')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Guardar resultado del test
     */
    public static function guardarResultado($userId, $testId, $scores, $recommendedCareers, $analysis, $totalScore)
    {
        return PersonalityResult::updateOrCreate(
            [
                'user_id' => $userId,
                'test_personality_id' => $testId,
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
        return PersonalityResult::where('user_id', $userId)
            ->where('test_personality_id', $testId)
            ->first();
    }

    /**
     * Obtener tests completados por usuario
     */
    public static function obtenerTestsCompletados($userId)
    {
        return PersonalityResult::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('test_personality_id')
            ->toArray();
    }

    /**
     * Verificar si test está completado
     */
    public static function estaCompletado($userId, $testId)
    {
        return PersonalityResult::where('user_id', $userId)
            ->where('test_personality_id', $testId)
            ->whereNotNull('completed_at')
            ->exists();
    }
}
