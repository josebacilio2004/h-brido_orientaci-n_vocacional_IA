<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nota_matematica',
        'nota_comunicacion',
        'nota_ciencias_sociales',
        'nota_ciencia_tecnologia',
        'nota_desarrollo_personal',
        'nota_ciudadania_civica',
        'nota_educacion_fisica',
        'nota_ingles',
        'nota_educacion_trabajo',
        'academic_year'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toArray()
    {
        return [
            'Nota_Matematica' => $this->nota_matematica,
            'Nota_Comunicacion' => $this->nota_comunicacion,
            'Nota_Ciencias_Sociales' => $this->nota_ciencias_sociales,
            'Nota_Ciencia_Tecnologia' => $this->nota_ciencia_tecnologia,
            'Nota_Desarrollo_Personal' => $this->nota_desarrollo_personal,
            'Nota_Ciudadania_Civica' => $this->nota_ciudadania_civica,
            'Nota_Educacion_Fisica' => $this->nota_educacion_fisica,
            'Nota_Ingles' => $this->nota_ingles,
            'Nota_Educacion_Trabajo' => $this->nota_educacion_trabajo,
        ];
    }

    // ============================================
    // MÉTODOS DAO CON STORED PROCEDURES
    // ============================================

    /**
     * Guardar notas académicas de estudiante
     */
    public static function guardarNotas(
        $userId,
        $notaMatematica,
        $notaComunicacion,
        $notaCienciasSociales,
        $notaCienciaTecnologia,
        $notaDesarrolloPersonal,
        $notaCiudadaniaCivica,
        $notaEducacionFisica,
        $notaIngles,
        $notaEducacionTrabajo,
        $academicYear = 2024
    ) {
        $result = DB::select('CALL sp_save_student_grades(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            $notaMatematica,
            $notaComunicacion,
            $notaCienciasSociales,
            $notaCienciaTecnologia,
            $notaDesarrolloPersonal,
            $notaCiudadaniaCivica,
            $notaEducacionFisica,
            $notaIngles,
            $notaEducacionTrabajo,
            $academicYear
        ]);
        
        return $result ? $result[0] : null;
    }

    /**
     * Obtener notas académicas de un estudiante
     */
    public static function obtenerNotas($userId)
    {
        $result = DB::select('CALL sp_get_student_grades(?)', [$userId]);
        return $result ? $result[0] : null;
    }

    /**
     * Calcular promedio general de notas
     */
    public function calcularPromedio()
    {
        $notas = [
            $this->nota_matematica,
            $this->nota_comunicacion,
            $this->nota_ciencias_sociales,
            $this->nota_ciencia_tecnologia,
            $this->nota_desarrollo_personal,
            $this->nota_ciudadania_civica,
            $this->nota_educacion_fisica,
            $this->nota_ingles,
            $this->nota_educacion_trabajo
        ];
        
        return array_sum($notas) / count($notas);
    }
}
