<?php

namespace App\DAO\Interfaces;

interface CareerDAOInterface
{
    /**
     * Obtener carrera por ID
     */
    public function findById(int $id);

    /**
     * Obtener todas las carreras
     */
    public function getAll();

    /**
     * Obtener carreras por facultad
     */
    public function getByFaculty(string $faculty);

    /**
     * Obtener carreras por perfil RIASEC
     */
    public function getByRiasecProfile(string $profile);

    /**
     * Buscar carreras
     */
    public function search(string $query);

    /**
     * Obtener carreras recomendadas
     */
    public function getRecommendedCareers(array $scores);

    /**
     * Crear carrera
     */
    public function create(array $data);

    /**
     * Actualizar carrera
     */
    public function update(int $id, array $data);

    /**
     * Eliminar carrera
     */
    public function delete(int $id);
}
