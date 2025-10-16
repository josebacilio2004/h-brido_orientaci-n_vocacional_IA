<?php

namespace App\Repositories;

use App\Models\Career;
use Illuminate\Support\Facades\DB;

class CareerRepository
{
    
    /**
     * Obtener todas las carreras
     */
    public function getAll()
    {
        return Career::orderBy('faculty')->orderBy('name')->get();
    }

    /**
     * Obtener todas las carreras (alias)
     */
    public function getAllCareers()
    {
        return $this->getAll();
    }

    /**
     * Obtener todas las facultades únicas
     */
    public function getAllFaculties()
    {
        return Career::select('faculty')
            ->distinct()
            ->orderBy('faculty')
            ->pluck('faculty')
            ->toArray();
    }

    /**
     * Obtener carreras por facultad
     */
    public function getByFaculty(string $faculty)
    {
        return Career::where('faculty', $faculty)
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener carreras por perfil RIASEC
     */
    public function getByRiasecProfile(string $profile)
    {
        return Career::where('riasec_profile', 'LIKE', '%' . $profile . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar carreras
     */
    public function search(string $searchTerm)
    {
        return Career::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('faculty', 'LIKE', '%' . $searchTerm . '%')
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener carrera por ID
     */
    public function findById(int $careerId)
    {
        return Career::find($careerId);
    }

    /**
     * Obtener carreras recomendadas por categorías RIASEC
     */
    public function getRecommendedByCategories(array $categories)
    {
        $query = Career::query();
        
        foreach ($categories as $category) {
            $query->orWhere('riasec_profile', 'LIKE', '%' . $category . '%');
        }
        
        return $query->orderBy('name')->get();
    }
}
