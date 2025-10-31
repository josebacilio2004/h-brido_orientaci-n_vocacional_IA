<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vocational_test_id',
        'scores',
        'recommended_careers',
        'analysis',
        'total_score',
        'completed_at'
    ];

    protected $casts = [
        'scores' => 'array',
        'recommended_careers' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(VocationalTest::class, 'vocational_test_id');
    }

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Guardar resultado de test
     */
    public static function guardarResultado($userId, $testId, $scores, $recommendedCareers, $analysis, $totalScore)
    {
        try {
            $result = DB::select('CALL sp_save_test_result(?, ?, ?, ?, ?, ?)', [
                $userId,
                $testId,
                json_encode($scores),
                json_encode($recommendedCareers),
                $analysis,
                $totalScore
            ]);

            return $result ? $result[0] : null;
        } catch (\Exception $e) {
            Log::error('Error guardando resultado: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener resultado de test para un usuario
     */
    public static function obtenerResultado($userId, $testId)
    {
        try {
            // Primero intenta con el stored procedure
            $result = DB::select('CALL sp_get_test_result(?, ?)', [$userId, $testId]);

            if (!$result || empty($result)) {
                Log::warning("No se encontraron resultados en SP para user: {$userId}, test: {$testId}");

                // Fallback: buscar directamente en la tabla
                $directResult = self::where('user_id', $userId)
                    ->where('vocational_test_id', $testId)
                    ->first();

                if ($directResult) {
                    Log::info("Resultado encontrado directamente en la tabla");
                    return $directResult;
                }

                Log::warning("No hay resultados en la tabla para user: {$userId}, test: {$testId}");
                return null;
            }

            $resultData = $result[0];

            // Crear una instancia del modelo con los datos
            $instance = new self();
            foreach ($resultData as $key => $value) {
                $instance->$key = $value;
            }

            return $instance;
        } catch (\Exception $e) {
            Log::error('Error obteniendo resultado: ' . $e->getMessage());

            // Fallback: buscar directamente en la tabla
            try {
                $directResult = self::where('user_id', $userId)
                    ->where('vocational_test_id', $testId)
                    ->first();

                if ($directResult) {
                    Log::info("Resultado encontrado en fallback");
                    return $directResult;
                }
            } catch (\Exception $fallbackError) {
                Log::error('Error en fallback: ' . $fallbackError->getMessage());
            }

            return null;
        }
    }

    /**
     * Obtener todos los resultados de un usuario
     */
    public static function obtenerResultadosUsuario($userId)
    {
        try {
            return self::where('user_id', $userId)
                ->orderBy('completed_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error obteniendo resultados del usuario: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener último resultado de un usuario
     */
    public static function obtenerUltimoResultado($userId)
    {
        try {
            return self::where('user_id', $userId)
                ->latest('completed_at')
                ->first();
        } catch (\Exception $e) {
            Log::error('Error obteniendo último resultado: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener todos los resultados para análisis de clustering
     */
    public static function obtenerTodosLosResultados()
    {
        try {
            return self::with('user')
                ->orderBy('completed_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error obteniendo todos los resultados: ' . $e->getMessage());
            return collect();
        }
    }
}
