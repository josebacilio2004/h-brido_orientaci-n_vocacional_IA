<?php

namespace App\Repositories;

use App\DAO\Interfaces\ClusteringDAOInterface;

class ClusteringRepository
{
    private ClusteringDAOInterface $clusteringDAO;

    public function __construct(ClusteringDAOInterface $clusteringDAO)
    {
        $this->clusteringDAO = $clusteringDAO;
    }

    /**
     * Obtener datos para clustering
     */
    public function getClusteringData()
    {
        $results = $this->clusteringDAO->getAllTestResults();

        return $results->map(function ($result) {
            return [
                'id' => $result->id,
                'user_id' => $result->user_id,
                'scores' => json_decode($result->scores, true),
                'recommended_careers' => json_decode($result->recommended_careers, true),
                'total_score' => $result->total_score,
                'completed_at' => $result->completed_at
            ];
        })->toArray();
    }

    /**
     * Obtener todos los tests completados
     */
    public function getAllCompletedTests()
    {
        return $this->getClusteringData();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getGeneralStatistics()
    {
        $stats = $this->clusteringDAO->getClusteringStats();

        $totalTests = $stats['total_clusters'] ?? 0;
        $totalUsers = $stats['total_users'] ?? 0;

        return [
            'total_tests' => $totalTests,
            'total_users' => $totalUsers,
            'avg_score' => 0,
            'completion_rate' => 100
        ];
    }

    /**
     * Obtener distribución de perfiles RIASEC
     */
    public function getRiasecDistribution()
    {
        $results = $this->clusteringDAO->getAllTestResults();

        $distribution = [
            'realista' => 0,
            'investigador' => 0,
            'artistico' => 0,
            'social' => 0,
            'emprendedor' => 0,
            'convencional' => 0
        ];

        foreach ($results as $result) {
            $scores = json_decode($result->scores, true);
            if ($scores && is_array($scores)) {
                $maxCategory = array_key_first($scores);
                if (isset($distribution[$maxCategory])) {
                    $distribution[$maxCategory]++;
                }
            }
        }

        return $distribution;
    }

    /**
     * Guardar cluster
     */
    public function saveCluster(int $clusterId, array $userIds, array $clusterData)
    {
        return $this->clusteringDAO->saveCluster($clusterId, $userIds, $clusterData);
    }

    /**
     * Obtener clusters
     */
    public function getClusters()
    {
        return $this->clusteringDAO->getClusters();
    }

    /**
     * Obtener usuarios en cluster
     */
    public function getUsersInCluster(int $clusterId)
    {
        return $this->clusteringDAO->getUsersInCluster($clusterId);
    }

    /**
     * Eliminar clusters
     */
    public function deleteClusters()
    {
        return $this->clusteringDAO->deleteClusters();
    }

    /**
     * Obtener resultados con filtros
     */
    public function getTestResultsWithFilters(array $filters)
    {
        $results = $this->clusteringDAO->getTestResultsWithFilters($filters);

        return $results->map(function ($result) {
            return [
                'id' => $result->id,
                'user_id' => $result->user_id,
                'scores' => json_decode($result->scores, true),
                'recommended_careers' => json_decode($result->recommended_careers, true),
                'total_score' => $result->total_score,
                'completed_at' => $result->completed_at
            ];
        })->toArray();
    }

    /**
     * Guardar resultados del clustering
     */
    public function saveClusteringResults(array $data)
    {
        // Convertir los datos a un formato compatible con tu DAO
        $clusterData = [
            'cluster_stats' => $data['cluster_stats'],
            'trends' => $data['trends'],
            'analyzed_at' => $data['analyzed_at'],
            'total_samples' => $data['total_samples']
        ];

        // Si necesitas un clusterId específico, puedes generarlo o usar uno por defecto
        $clusterId = 1; // o generar un ID único

        // Obtener los user_ids de los resultados si están disponibles
        $userIds = [];
        if (isset($data['results'])) {
            foreach ($data['results'] as $result) {
                if (isset($result['user_id'])) {
                    $userIds[] = $result['user_id'];
                }
            }
        }

        return $this->clusteringDAO->saveCluster($clusterId, $userIds, $clusterData);
    }
}
