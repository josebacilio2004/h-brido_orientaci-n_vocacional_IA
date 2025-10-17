<?php

namespace App\Repositories;

use App\DAO\Interfaces\UserDAOInterface;

class UserRepository
{
    private UserDAOInterface $userDAO;

    public function __construct(UserDAOInterface $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    /**
     * Crear un nuevo usuario
     */
    public function create(array $data)
    {
        return $this->userDAO->create($data);
    }

    /**
     * Obtener usuario por email
     */
    public function findByEmail(string $email)
    {
        return $this->userDAO->findByEmail($email);
    }

    /**
     * Obtener usuario por ID
     */
    public function findById(int $userId)
    {
        return $this->userDAO->findById($userId);
    }

    /**
     * Actualizar usuario
     */
    public function update(int $userId, array $data)
    {
        return $this->userDAO->update($userId, $data);
    }

    /**
     * Obtener estadísticas del usuario
     */
    public function getUserStats(int $userId)
    {
        return $this->userDAO->getUserStats($userId);
    }

    /**
     * Verificar si existe usuario
     */
    public function exists(int $id)
    {
        return $this->userDAO->exists($id);
    }

    /**
     * Obtener todos los usuarios
     */
    public function getAll()
    {
        return $this->userDAO->getAll();
    }

    /**
     * Eliminar usuario
     */
    public function delete(int $id)
    {
        return $this->userDAO->delete($id);
    }
}
