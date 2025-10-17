<?php

namespace App\DAO\Interfaces;

interface UserDAOInterface
{
    /**
     * Crear un nuevo usuario
     */
    public function create(array $data);

    /**
     * Obtener usuario por ID
     */
    public function findById(int $id);

    /**
     * Obtener usuario por email
     */
    public function findByEmail(string $email);

    /**
     * Actualizar usuario
     */
    public function update(int $id, array $data);

    /**
     * Eliminar usuario
     */
    public function delete(int $id);

    /**
     * Obtener todos los usuarios
     */
    public function getAll();

    /**
     * Obtener estadísticas del usuario
     */
    public function getUserStats(int $userId);

    /**
     * Verificar si existe usuario
     */
    public function exists(int $id);
}
