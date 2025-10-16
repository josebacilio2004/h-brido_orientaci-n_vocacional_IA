<?php

namespace App\Services;

use App\Repositories\ClusteringRepository;

class ClusteringService
{
    protected $clusteringRepository;

    public function __construct(ClusteringRepository $clusteringRepository)
    {
        $this->clusteringRepository = $clusteringRepository;
    }

    /**
     * Ejecutar análisis de clustering completo
     */
    public function performClusteringAnalysis()
    {
        // 1. Obtener datos mediante procedure
        $results = $this->clusteringRepository->getAllCompletedTests();

        if (count($results) < 10) {
            throw new \Exception('Se necesitan al menos 10 tests completados para realizar el análisis de clustering.');
        }

        // 2. Preparar datos
        $preparedData = $this->prepareDataForClustering($results);

        // 3. Ejecutar K-Means
        $clusters = $this->kMeansClustering($preparedData, 6);

        // 4. Calcular estadísticas
        $clusterStats = $this->calculateClusterStatistics($clusters, $results);

        // 5. Analizar tendencias
        $trends = $this->analyzeTrends($results);

        // 6. Guardar resultados mediante procedure
        $this->clusteringRepository->saveClusteringResults([
            'cluster_stats' => $clusterStats,
            'trends' => $trends,
            'analyzed_at' => now()->toDateTimeString(),
            'total_samples' => count($results)
        ]);

        return [
            'clusterStats' => $clusterStats,
            'trends' => $trends,
            'results' => $results
        ];
    }

    /**
     * Obtener dashboard data completa
     */
    public function getDashboardData()
    {
        $clusteringData = $this->performClusteringAnalysis();
        $generalStats = $this->clusteringRepository->getGeneralStatistics();

        return array_merge($clusteringData, ['generalStats' => $generalStats]);
    }

    // ... [Métodos prepareDataForClustering, kMeansClustering, calculateClusterStatistics, analyzeTrends]
    // Estos se mantienen igual que en el controller original pero se mueven aquí

    /**
     * Preparar datos para clustering: convertir cada fila en un vector numérico simple.
     * Acepta arrays u objetos y extrae valores numéricos.
     *
     * @param array $results
     * @return array
     */
    public function prepareDataForClustering($results)
    {
        $prepared = [];

        foreach ($results as $row) {
            $values = [];

            // Soporta tanto arrays como objetos
            $rowArray = is_array($row) ? $row : (is_object($row) ? (array) $row : []);

            foreach ($rowArray as $key => $val) {
                if (is_numeric($val)) {
                    $values[] = (float) $val;
                }
            }

            if (!empty($values)) {
                $prepared[] = $values;
            }
        }

        return $prepared;
    }

    /**
     * Implementación sencilla de K-Means placeholder que asigna elementos a clusters por índice.
     * Sustituir por una implementación completa si se necesita clustering real.
     *
     * @param array $data
     * @param int $k
     * @return array  // devuelve un array de asignaciones index => clusterId
     */
    public function kMeansClustering($data, $k = 6)
    {
        $assignments = [];

        if ($k <= 0) {
            $k = 1;
        }

        foreach ($data as $i => $row) {
            $assignments[$i] = $i % $k;
        }

        return $assignments;
    }

    /**
     * Calcular estadísticas básicas por cluster a partir de las asignaciones.
     *
     * @param array $clusters  // asignaciones index => clusterId
     * @param array $results
     * @return array
     */
    public function calculateClusterStatistics($clusters, $results)
    {
        $stats = [];

        foreach ($clusters as $index => $clusterId) {
            if (!isset($stats[$clusterId])) {
                $stats[$clusterId] = [
                    'count' => 0,
                    'sample_indices' => [],
                ];
            }

            $stats[$clusterId]['count']++;
            $stats[$clusterId]['sample_indices'][] = $index;
        }

        // Añadir información opcional por cluster
        foreach ($stats as $clusterId => $data) {
            $stats[$clusterId]['percentage'] = count($results) > 0 ? ($data['count'] / count($results)) * 100 : 0;
        }

        return $stats;
    }

    /**
     * Analizar tendencias simples en los resultados (placeholder).
     *
     * @param array $results
     * @return array
     */
    public function analyzeTrends($results)
    {
        // Implementación sencilla: devolver conteo total y ejemplos de primer/último registro
        return [
            'total_samples' => count($results),
            'first_sample' => count($results) ? $results[0] : null,
            'last_sample' => count($results) ? $results[count($results) - 1] : null,
        ];
    }
}