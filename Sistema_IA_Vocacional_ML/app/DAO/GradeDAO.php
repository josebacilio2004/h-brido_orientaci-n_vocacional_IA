<?php

namespace App\DAO;

use App\DAO\Interfaces\GradeDAOInterface;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeDAO implements GradeDAOInterface
{
    /**
     * Obtener notas del estudiante
     */
    public function getStudentGrades(int $userId)
    {
        try {
            return StudentGrade::where('user_id', $userId)
                ->orderBy('subject', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting student grades: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Guardar nota
     */
    public function saveGrade(int $userId, string $subject, float $grade)
    {
        try {
            return StudentGrade::updateOrCreate(
                [
                    'user_id' => $userId,
                    'subject' => $subject
                ],
                [
                    'grade' => $grade
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error saving grade: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener promedio de notas
     */
    public function getAverageGrade(int $userId)
    {
        try {
            $average = StudentGrade::where('user_id', $userId)
                ->avg('grade');
            
            return $average ?? 0;
        } catch (\Exception $e) {
            Log::error('Error getting average grade: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Actualizar nota
     */
    public function updateGrade(int $id, float $grade)
    {
        try {
            $studentGrade = StudentGrade::find($id);
            if ($studentGrade) {
                $studentGrade->update(['grade' => $grade]);
                return $studentGrade->fresh();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating grade: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Eliminar nota
     */
    public function deleteGrade(int $id)
    {
        try {
            $studentGrade = StudentGrade::find($id);
            if ($studentGrade) {
                return $studentGrade->delete();
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting grade: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener nota por ID
     */
    public function findById(int $id)
    {
        try {
            return StudentGrade::find($id);
        } catch (\Exception $e) {
            Log::error('Error finding grade by ID: ' . $e->getMessage());
            return null;
        }
    }
}
