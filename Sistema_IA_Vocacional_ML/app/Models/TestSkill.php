<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestSkill extends Model
{
    use HasFactory;

    protected $table = 'test_skills';

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
     * Obtener todos los tests de habilidades activos
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

        $test->preguntas = SkillQuestion::where('test_skill_id', $testId)
            ->orderBy('question_number')
            ->get();

        return $test;
    }

    /**
     * Obtener pregunta específica
     */
    public static function obtenerPregunta($testId, $questionNumber)
    {
        return SkillQuestion::where('test_skill_id', $testId)
            ->where('question_number', $questionNumber)
            ->first();
    }

    /**
     * Guardar respuesta del usuario
     */
    public static function guardarRespuesta($userId, $testId, $questionId, $answer, $score)
    {
        return SkillResponse::updateOrCreate(
            [
                'user_id' => $userId,
                'test_skill_id' => $testId,
                'skill_question_id' => $questionId,
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
        return SkillResponse::where('user_id', $userId)
            ->where('test_skill_id', $testId)
            ->where('skill_question_id', $questionId)
            ->first();
    }

    /**
     * Contar respuestas completadas
     */
    public static function contarRespuestas($userId, $testId)
    {
        return SkillResponse::where('user_id', $userId)
            ->where('test_skill_id', $testId)
            ->count();
    }

    /**
     * Calcular puntajes por categoría
     */
    public static function calcularPuntajesPorCategoria($userId, $testId)
    {
        return DB::table('skill_responses')
            ->join('skill_questions', 'skill_responses.skill_question_id', '=', 'skill_questions.id')
            ->where('skill_responses.user_id', $userId)
            ->where('skill_responses.test_skill_id', $testId)
            ->groupBy('skill_questions.skill_category')
            ->selectRaw('skill_questions.skill_category, SUM(skill_responses.score) as total_score, COUNT(*) as question_count, AVG(skill_responses.score) as avg_score')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Guardar resultado del test
     */
    public static function guardarResultado($userId, $testId, $scores, $recommendedCareers, $analysis, $totalScore)
    {
        return SkillResult::updateOrCreate(
            [
                'user_id' => $userId,
                'test_skill_id' => $testId,
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
        return SkillResult::where('user_id', $userId)
            ->where('test_skill_id', $testId)
            ->first();
    }

    /**
     * Obtener tests completados por usuario
     */
    public static function obtenerTestsCompletados($userId)
    {
        return SkillResult::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->pluck('test_skill_id')
            ->toArray();
    }

    /**
     * Verificar si test está completado
     */
    public static function estaCompletado($userId, $testId)
    {
        return SkillResult::where('user_id', $userId)
            ->where('test_skill_id', $testId)
            ->whereNotNull('completed_at')
            ->exists();
    }
}
