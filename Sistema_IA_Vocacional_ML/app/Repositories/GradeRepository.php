<?php

namespace App\Repositories;

use App\DAO\Interfaces\GradeDAOInterface;

class GradeRepository
{
    private GradeDAOInterface $gradeDAO;

    public function __construct(GradeDAOInterface $gradeDAO)
    {
        $this->gradeDAO = $gradeDAO;
    }

    /**
     * Guardar notas académicas
     */
    public function saveGrades(int $userId, array $grades, int $academicYear)
    {
        // Guardar cada nota individual
        foreach ($grades as $subject => $grade) {
            if ($subject !== 'academic_year') {
                $this->gradeDAO->saveGrade($userId, $subject, $grade);
            }
        }
        
        return $this->gradeDAO->getStudentGrades($userId);
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
        return $this->gradeDAO->getStudentGrades($userId)->first();
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
        return $this->gradeDAO->getStudentGrades($userId);
    }

    /**
     * Obtener promedio de notas
     */
    public function getAverageGrade(int $userId)
    {
        return $this->gradeDAO->getAverageGrade($userId);
    }

    /**
     * Actualizar nota
     */
    public function updateGrade(int $id, float $grade)
    {
        return $this->gradeDAO->updateGrade($id, $grade);
    }

    /**
     * Eliminar nota
     */
    public function deleteGrade(int $id)
    {
        return $this->gradeDAO->deleteGrade($id);
    }

    /**
     * Obtener nota por ID
     */
    public function findById(int $id)
    {
        return $this->gradeDAO->findById($id);
    }
}
