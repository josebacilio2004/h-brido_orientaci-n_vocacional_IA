<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vocational_test_id',
        'test_question_id',
        'answer',
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(VocationalTest::class, 'vocational_test_id');
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Guardar respuesta de test
     */
    public static function guardarRespuesta($userId, $testId, $questionId, $answer, $score = 0)
    {
        $result = DB::select('CALL sp_save_test_response(?, ?, ?, ?, ?)', [
            $userId,
            $testId,
            $questionId,
            $answer,
            $score
        ]);
        
        return $result ? $result[0] : null;
    }

    /**
     * Obtener respuesta previa del usuario
     */
    public static function obtenerRespuestaPrevia($userId, $testId, $questionId)
    {
        $result = DB::select('CALL sp_get_previous_answer(?, ?, ?)', [
            $userId,
            $testId,
            $questionId
        ]);
        
        return $result ? $result[0] : null;
    }
}
