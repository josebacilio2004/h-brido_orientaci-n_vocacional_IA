<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Crear un nuevo usuario
     */
    public function create(array $data)
    {
        // Usar Eloquent para crear el usuario y devolver el objeto completo
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Ya viene hasheado desde el controlador
            'grade' => $data['grade'],
            'school' => $data['school'],
            'role' => $data['role'] ?? 'student'
        ]);
    }

    /**
     * Obtener usuario por email
     */
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Obtener usuario por ID
     */
    public function findById(int $userId)
    {
        return User::find($userId);
    }

    /**
     * Actualizar usuario
     */
    public function update(int $userId, array $data)
    {
        $user = User::find($userId);
        
        if ($user) {
            $user->update($data);
            return $user->fresh();
        }
        
        return null;
    }

    /**
     * Obtener estadísticas del usuario (usando stored procedure para consultas complejas)
     */
    public function getUserStats(int $userId)
    {
        // Aquí sí usamos stored procedure para consultas complejas
        $result = DB::select('CALL sp_get_user_stats(?)', [$userId]);
        return !empty($result) ? $result[0] : null;
    }
}
