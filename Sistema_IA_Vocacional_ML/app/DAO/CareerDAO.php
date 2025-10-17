<?php

namespace App\DAO;

use App\DAO\Interfaces\CareerDAOInterface;
use App\Models\Career;
use Illuminate\Support\Facades\Log;

class CareerDAO implements CareerDAOInterface
{
    /**
     * Obtener carrera por ID
     */
    public function findById(int $id)
    {
        try {
            return Career::find($id);
        } catch (\Exception $e) {
            Log::error('Error finding career by ID: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener todas las carreras
     */
    public function getAll()
    {
        try {
            return Career::orderBy('faculty', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting all careers: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener carreras por facultad
     */
    public function getByFaculty(string $faculty)
    {
        try {
            return Career::where('faculty', $faculty)
                ->orderBy('name', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting careers by faculty: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener carreras por perfil RIASEC
     */
    public function getByRiasecProfile(string $profile)
    {
        try {
            return Career::where('riasec_profile', $profile)
                ->orderBy('name', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting careers by RIASEC profile: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Buscar carreras
     */
    public function search(string $query)
    {
        try {
            return Career::where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orderBy('name', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error searching careers: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener carreras recomendadas
     */
    public function getRecommendedCareers(array $scores)
    {
        try {
            // Obtener el perfil dominante
            $dominantProfile = array_key_first($scores);
            
            return Career::where('riasec_profile', $dominantProfile)
                ->orderBy('name', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting recommended careers: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Crear carrera
     */
    public function create(array $data)
    {
        try {
            return Career::create($data);
        } catch (\Exception $e) {
            Log::error('Error creating career: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Actualizar carrera
     */
    public function update(int $id, array $data)
    {
        try {
            $career = Career::find($id);
            if ($career) {
                $career->update($data);
                return $career->fresh();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error updating career: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Eliminar carrera
     */
    public function delete(int $id)
    {
        try {
            $career = Career::find($id);
            if ($career) {
                return $career->delete();
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting career: ' . $e->getMessage());
            throw $e;
        }
    }
}
