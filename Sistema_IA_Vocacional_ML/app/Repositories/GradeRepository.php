<?php

namespace App\Repositories;

use App\Models\StudentGrade;
use Illuminate\Support\Facades\DB;

class GradeRepository
{
    
    /**
     * Guardar notas académicas
     */
    public function saveGrades(int $userId, array $grades, int $academicYear)
    {
        $grade = StudentGrade::updateOrCreate(
            [
                'user_id' => $userId,
                'academic_year' => $academicYear
            ],
            [
                'nota_matematica' => $grades['nota_matematica'],
                'nota_comunicacion' => $grades['nota_comunicacion'],
                'nota_ciencias_sociales' => $grades['nota_ciencias_sociales'],
                'nota_ciencia_tecnologia' => $grades['nota_ciencia_tecnologia'],
                'nota_desarrollo_personal' => $grades['nota_desarrollo_personal'],
                'nota_ciudadania_civica' => $grades['nota_ciudadania_civica'],
                'nota_educacion_fisica' => $grades['nota_educacion_fisica'],
                'nota_ingles' => $grades['nota_ingles'],
                'nota_educacion_trabajo' => $grades['nota_educacion_trabajo']
            ]
        );

        return $grade;
    }

    /**
     * Guardar o actualizar notas (alias para compatibilidad)
     */
    public function saveOrUpdateGrades(int $userId, array $grades)
    {
        $academicYear = $grades['academic_year'] ?? date('Y');
        return $this->saveGrades($userId, $grades, $academicYear);
    }

    /**
     * Obtener notas académicas
     */
    public function getGrades(int $userId)
    {
        return StudentGrade::where('user_id', $userId)
            ->orderBy('academic_year', 'desc')
            ->first();
    }

    /**
     * Obtener notas del usuario (alias)
     */
    public function getUserGrades(int $userId)
    {
        return $this->getGrades($userId);
    }

    /**
     * Obtener todas las notas del usuario por año
     */
    public function getAllGradesByYear(int $userId)
    {
        return StudentGrade::where('user_id', $userId)
            ->orderBy('academic_year', 'desc')
            ->get();
    }
}
