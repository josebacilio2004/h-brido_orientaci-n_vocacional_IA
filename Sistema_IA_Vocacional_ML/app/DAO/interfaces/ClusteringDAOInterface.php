<?php

namespace App\DAO\Interfaces;

interface ClusteringDAOInterface
{
    /**
     * Obtener todos los resultados de tests
     */
    public function getAllTestResults();

    /**
     * Obtener resultados de tests con filtros
     */
    public function getTestResultsWithFilters(array $filters);

    /**
     * Guardar cluster
     */
    public function saveCluster(int $clusterId, array $userIds, array $clusterData);

    /**
     * Obtener clusters
     */
    public function getClusters();

    /**
     * Obtener usuarios en cluster
     */
    public function getUsersInCluster(int $clusterId);

    /**
     * Obtener estadísticas de clustering
     */
    public function getClusteringStats();

    /**
     * Eliminar clusters
     */
    public function deleteClusters();
}
