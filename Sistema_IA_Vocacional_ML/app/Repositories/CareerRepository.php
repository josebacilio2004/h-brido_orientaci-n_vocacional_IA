<?php

namespace App\Repositories;

use App\DAO\Interfaces\CareerDAOInterface;

class CareerRepository
{
    private CareerDAOInterface $careerDAO;

    public function __construct(CareerDAOInterface $careerDAO)
    {
        $this->careerDAO = $careerDAO;
    }

    /**
     * Obtener todas las carreras
     */
    public function getAll()
    {
        return $this->careerDAO->getAll();
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
        $careers = $this->getAll();
        $faculties = [];
        
        foreach ($careers as $career) {
            if (!in_array($career->faculty, $faculties)) {
                $faculties[] = $career->faculty;
            }
        }
        
        sort($faculties);
        return $faculties;
    }

    /**
     * Obtener carreras por facultad
     */
    public function getByFaculty(string $faculty)
    {
        return $this->careerDAO->getByFaculty($faculty);
    }

    /**
     * Obtener carreras por perfil RIASEC
     */
    public function getByRiasecProfile(string $profile)
    {
        return $this->careerDAO->getByRiasecProfile($profile);
    }

    /**
     * Buscar carreras
     */
    public function search(string $searchTerm)
    {
        return $this->careerDAO->search($searchTerm);
    }

    /**
     * Obtener carrera por ID
     */
    public function findById(int $careerId)
    {
        return $this->careerDAO->findById($careerId);
    }

    /**
     * Obtener carreras recomendadas por categorías RIASEC
     */
    public function getRecommendedByCategories(array $categories)
    {
        $careers = collect();
        
        foreach ($categories as $category) {
            $careersByCategory = $this->careerDAO->getByRiasecProfile($category);
            $careers = $careers->merge($careersByCategory);
        }
        
        return $careers->unique('id');
    }

    /**
     * Obtener carreras recomendadas
     */
    public function getRecommendedCareers(array $scores)
    {
        return $this->careerDAO->getRecommendedCareers($scores);
    }

    /**
     * Crear carrera
     */
    public function create(array $data)
    {
        return $this->careerDAO->create($data);
    }

    /**
     * Actualizar carrera
     */
    public function update(int $id, array $data)
    {
        return $this->careerDAO->update($id, $data);
    }

    /**
     * Eliminar carrera
     */
    public function delete(int $id)
    {
        return $this->careerDAO->delete($id);
    }
}
