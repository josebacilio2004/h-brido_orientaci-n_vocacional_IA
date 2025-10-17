<?php

namespace App\DAO\Interfaces;

interface GradeDAOInterface
{
    /**
     * Obtener notas del estudiante
     */
    public function getStudentGrades(int $userId);

    /**
     * Guardar nota
     */
    public function saveGrade(int $userId, string $subject, float $grade);

    /**
     * Obtener promedio de notas
     */
    public function getAverageGrade(int $userId);

    /**
     * Actualizar nota
     */
    public function updateGrade(int $id, float $grade);

    /**
     * Eliminar nota
     */
    public function deleteGrade(int $id);

    /**
     * Obtener nota por ID
     */
    public function findById(int $id);
}
