<?php

namespace App\DAO;

use App\DAO\Interfaces\UserDAOInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserDAO implements UserDAOInterface
{
    /**
     * Crear un nuevo usuario
     */
    public function create(array $data)
    {
        try {
            return User::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener usuario por ID
     */
    public function findById(int $id)
    {
        try {
            return User::find($id);
        } catch (\Exception $e) {
            Log::error('Error finding user by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener usuario por email
     */
    public function findByEmail(string $email)
    {
        try {
            return User::where('email', $email)->first();
        } catch (\Exception $e) {
            Log::error('Error finding user by email: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualizar usuario
     */
    public function update(int $id, array $data)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->update($data);
                return $user->fresh();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Eliminar usuario
     */
    public function delete(int $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                return $user->delete();
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener todos los usuarios
     */
    public function getAll()
    {
        try {
            return User::all();
        } catch (\Exception $e) {
            Log::error('Error getting all users: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener estadísticas del usuario
     */
    public function getUserStats(int $userId)
    {
        try {
            $result = DB::select('CALL sp_get_user_stats(?)', [$userId]);
            return !empty($result) ? $result[0] : null;
        } catch (\Exception $e) {
            Log::error('Error getting user stats: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verificar si existe usuario
     */
    public function exists(int $id)
    {
        try {
            return User::where('id', $id)->exists();
        } catch (\Exception $e) {
            Log::error('Error checking if user exists: ' . $e->getMessage());
            return false;
        }
    }
}
