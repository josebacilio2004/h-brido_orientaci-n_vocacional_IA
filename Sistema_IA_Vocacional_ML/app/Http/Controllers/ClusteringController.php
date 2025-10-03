<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClusteringController extends Controller
{
    /**
     * Mostrar dashboard de análisis de clustering
     */
    public function dashboard()
    {
        // Obtener todos los resultados de tests completados
        $results = TestResult::with('user')
            ->whereNotNull('completed_at')
            ->get();

        if ($results->count() < 10) {
            return view('clustering.dashboard')->with('error', 'Se necesitan al menos 10 tests completados para realizar el análisis de clustering.');
        }

        // Preparar datos para clustering
        $data = $this->prepareDataForClustering($results);

        // Aplicar K-Means clustering
        $clusters = $this->kMeansClustering($data, 6); // 6 clusters para RIASEC

        // Calcular estadísticas por cluster
        $clusterStats = $this->calculateClusterStatistics($clusters, $results);

        // Obtener tendencias y patrones
        $trends = $this->analyzeTrends($results);

        return view('clustering.dashboard', compact('clusterStats', 'trends', 'results'));
    }

    /**
     * Preparar datos para clustering
     */
    private function prepareDataForClustering($results)
    {
        $data = [];

        foreach ($results as $result) {
            $scores = $result->scores;
            
            // Normalizar puntajes (0-1)
            $normalized = [];
            foreach (['realista', 'investigador', 'artistico', 'social', 'emprendedor', 'convencional'] as $category) {
                $normalized[] = isset($scores[$category]) ? $scores[$category] / 50 : 0;
            }

            $data[] = [
                'id' => $result->id,
                'user_id' => $result->user_id,
                'features' => $normalized,
                'scores' => $scores
            ];
        }

        return $data;
    }

    /**
     * Implementación de K-Means clustering
     */
    private function kMeansClustering($data, $k = 6, $maxIterations = 100)
    {
        $n = count($data);
        $dimensions = count($data[0]['features']);

        // Inicializar centroides aleatoriamente
        $centroids = [];
        $randomIndices = array_rand($data, min($k, $n));
        if (!is_array($randomIndices)) {
            $randomIndices = [$randomIndices];
        }

        foreach ($randomIndices as $index) {
            $centroids[] = $data[$index]['features'];
        }

        $assignments = array_fill(0, $n, 0);

        // Iterar hasta convergencia o máximo de iteraciones
        for ($iteration = 0; $iteration < $maxIterations; $iteration++) {
            $changed = false;

            // Asignar cada punto al centroide más cercano
            foreach ($data as $i => $point) {
                $minDistance = PHP_FLOAT_MAX;
                $closestCentroid = 0;

                foreach ($centroids as $j => $centroid) {
                    $distance = $this->euclideanDistance($point['features'], $centroid);
                    if ($distance < $minDistance) {
                        $minDistance = $distance;
                        $closestCentroid = $j;
                    }
                }

                if ($assignments[$i] !== $closestCentroid) {
                    $assignments[$i] = $closestCentroid;
                    $changed = true;
                }
            }

            // Si no hubo cambios, convergió
            if (!$changed) {
                break;
            }

            // Recalcular centroides
            $newCentroids = array_fill(0, $k, array_fill(0, $dimensions, 0));
            $counts = array_fill(0, $k, 0);

            foreach ($data as $i => $point) {
                $cluster = $assignments[$i];
                $counts[$cluster]++;
                for ($d = 0; $d < $dimensions; $d++) {
                    $newCentroids[$cluster][$d] += $point['features'][$d];
                }
            }

            for ($j = 0; $j < $k; $j++) {
                if ($counts[$j] > 0) {
                    for ($d = 0; $d < $dimensions; $d++) {
                        $newCentroids[$j][$d] /= $counts[$j];
                    }
                    $centroids[$j] = $newCentroids[$j];
                }
            }
        }

        // Organizar resultados por cluster
        $clusters = array_fill(0, $k, []);
        foreach ($data as $i => $point) {
            $clusters[$assignments[$i]][] = $point;
        }

        return [
            'clusters' => $clusters,
            'centroids' => $centroids,
            'assignments' => $assignments
        ];
    }

    /**
     * Calcular distancia euclidiana entre dos puntos
     */
    private function euclideanDistance($point1, $point2)
    {
        $sum = 0;
        for ($i = 0; $i < count($point1); $i++) {
            $sum += pow($point1[$i] - $point2[$i], 2);
        }
        return sqrt($sum);
    }

    /**
     * Calcular estadísticas por cluster
     */
    private function calculateClusterStatistics($clusteringResult, $results)
    {
        $clusters = $clusteringResult['clusters'];
        $centroids = $clusteringResult['centroids'];
        $categories = ['realista', 'investigador', 'artistico', 'social', 'emprendedor', 'convencional'];

        $stats = [];

        foreach ($clusters as $clusterIndex => $clusterData) {
            if (empty($clusterData)) {
                continue;
            }

            // Calcular perfil dominante del cluster
            $centroid = $centroids[$clusterIndex];
            $maxValue = max($centroid);
            $dominantIndex = array_search($maxValue, $centroid);
            $dominantProfile = $categories[$dominantIndex];

            // Calcular carreras más comunes en este cluster
            $careers = [];
            foreach ($clusterData as $point) {
                $result = $results->firstWhere('id', $point['id']);
                if ($result && isset($result->recommended_careers[0]['careers'])) {
                    foreach ($result->recommended_careers[0]['careers'] as $career) {
                        $careers[] = $career;
                    }
                }
            }

            $careerCounts = array_count_values($careers);
            arsort($careerCounts);
            $topCareers = array_slice(array_keys($careerCounts), 0, 5);

            // Calcular promedios de puntajes
            $avgScores = [];
            foreach ($categories as $i => $category) {
                $avgScores[$category] = round($centroid[$i] * 50, 1);
            }

            $stats[] = [
                'cluster_id' => $clusterIndex,
                'size' => count($clusterData),
                'percentage' => round((count($clusterData) / count($results)) * 100, 1),
                'dominant_profile' => $dominantProfile,
                'avg_scores' => $avgScores,
                'top_careers' => $topCareers,
                'centroid' => $centroid
            ];
        }

        // Ordenar por tamaño de cluster
        usort($stats, function($a, $b) {
            return $b['size'] - $a['size'];
        });

        return $stats;
    }

    /**
     * Analizar tendencias generales
     */
    private function analyzeTrends($results)
    {
        $categories = ['realista', 'investigador', 'artistico', 'social', 'emprendedor', 'convencional'];
        
        // Calcular promedios generales
        $avgScores = [];
        foreach ($categories as $category) {
            $scores = $results->pluck('scores')->map(function($scores) use ($category) {
                return $scores[$category] ?? 0;
            });
            $avgScores[$category] = round($scores->avg(), 1);
        }

        // Encontrar categoría más popular
        arsort($avgScores);
        $mostPopular = array_key_first($avgScores);

        // Calcular distribución de género (si está disponible)
        $genderDistribution = $results->groupBy('user.gender')->map->count();

        // Carreras más recomendadas
        $allCareers = [];
        foreach ($results as $result) {
            if (isset($result->recommended_careers[0]['careers'])) {
                foreach ($result->recommended_careers[0]['careers'] as $career) {
                    $allCareers[] = $career;
                }
            }
        }
        $careerCounts = array_count_values($allCareers);
        arsort($careerCounts);
        $topCareers = array_slice($careerCounts, 0, 10, true);

        return [
            'total_tests' => $results->count(),
            'avg_scores' => $avgScores,
            'most_popular_profile' => $mostPopular,
            'gender_distribution' => $genderDistribution,
            'top_careers' => $topCareers,
            'completion_rate' => 100 // Todos los resultados están completados
        ];
    }

    /**
     * API endpoint para obtener datos de clustering en JSON
     */
    public function getClusteringData()
    {
        $results = TestResult::with('user')
            ->whereNotNull('completed_at')
            ->get();

        if ($results->count() < 10) {
            return response()->json(['error' => 'Datos insuficientes'], 400);
        }

        $data = $this->prepareDataForClustering($results);
        $clusters = $this->kMeansClustering($data, 6);
        $clusterStats = $this->calculateClusterStatistics($clusters, $results);

        return response()->json([
            'clusters' => $clusterStats,
            'total_results' => $results->count()
        ]);
    }
}
