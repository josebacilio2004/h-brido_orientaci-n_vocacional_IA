<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'grade',
        'school',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function testResponses()
    {
        return $this->hasMany(TestResponse::class);
    }
    
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
    
    public function grades()
    {
        return $this->hasOne(StudentGrade::class);
    }
    
    public function aiPredictions()
    {
        return $this->hasMany(AIPrediction::class);
    }
    
    public function getLatestPrediction()
    {
        return $this->aiPredictions()->latest()->first();
    }
    
    public function getCompletedTestsCount()
    {
        return $this->testResults()->count();
    }

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Crear un nuevo usuario - puede ser via DAO o Eloquent directo
     */
    public static function crear($data)
    {
        try {
            return self::create($data);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener usuario por email con manejo de errores
     */
    public static function obtenerPorEmail($email)
    {
        try {
            return self::where('email', $email)->first();
        } catch (\Exception $e) {
            Log::error('Error al obtener usuario por email: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener usuario por ID
     */
    public static function obtenerPorId($id)
    {
        try {
            return self::find($id);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuario por ID: ' . $e->getMessage());
            return null;
        }
    }
}
