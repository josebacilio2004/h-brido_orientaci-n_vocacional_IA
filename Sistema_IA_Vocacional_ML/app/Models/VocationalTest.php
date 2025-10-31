<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VocationalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'type',
        'duration_minutes',
        'total_questions',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_questions' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(TestQuestion::class)->orderBy('question_number');
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Obtener todos los tests activos
     */
    public static function obtenerActivos()
    {
        return DB::select('CALL sp_get_active_tests()');
    }

    /**
     * Obtener test con sus preguntas
     */
    public static function obtenerConPreguntas($testId)
    {
        $results = DB::select('CALL sp_get_test_with_questions(?)', [$testId]);
        
        if (empty($results)) {
            return null;
        }

        // El primer resultado es el test, el resto son las preguntas
        $test = $results[0];
        $preguntas = array_slice($results, 1);
        
        return [
            'test' => $test,
            'preguntas' => $preguntas
        ];
    }

    /**
     * Obtener una pregunta específica
     */
    public static function obtenerPregunta($testId, $numeroPreguntas)
    {
        $result = DB::select('CALL sp_get_question(?, ?)', [$testId, $numeroPreguntas]);
        return $result ? $result[0] : null;
    }

    /**
     * Obtener pregunta siguiente
     */
    public static function obtenerSiguientePregunta($testId, $numeroPreguntaActual)
    {
        $siguienteNumero = $numeroPreguntaActual + 1;
        return self::obtenerPregunta($testId, $siguienteNumero);
    }

    /**
     * Obtener pregunta anterior
     */
    public static function obtenerPreguntaAnterior($testId, $numeroPreguntaActual)
    {
        $numeroAnterior = $numeroPreguntaActual - 1;
        if ($numeroAnterior < 1) {
            return null;
        }
        return self::obtenerPregunta($testId, $numeroAnterior);
    }

    /**
     * Verificar si el test está completado por un usuario
     */
    public static function verificarTestCompletado($userId, $testId)
    {
        $result = DB::select('CALL sp_check_test_completed(?, ?)', [$userId, $testId]);
        return $result ? ($result[0]->is_completed > 0) : false;
    }

    /**
     * Contar respuestas del usuario
     */
    public static function contarRespuestasUsuario($userId, $testId)
    {
        $result = DB::select('CALL sp_count_user_responses(?, ?)', [$userId, $testId]);
        return $result ? $result[0]->total_responses : 0;
    }

    /**
     * Calcular puntajes por categoría
     */
    public static function calcularPuntajesCategorias($userId, $testId)
    {
        return DB::select('CALL sp_calculate_category_scores(?, ?)', [$userId, $testId]);
    }

    /**
     * Obtener tests completados por un usuario
     */
    public static function obtenerTestsCompletados($userId)
    {
        $results = DB::select('CALL sp_get_user_completed_tests(?)', [$userId]);
        
        if (empty($results)) {
            return [];
        }

        // Retornar solo los IDs para usar con in_array() en las vistas
        return collect($results)->pluck('vocational_test_id')->toArray();
    }
}
