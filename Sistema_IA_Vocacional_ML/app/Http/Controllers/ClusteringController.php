<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClusteringController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->only('dashboard', 'getClusteringData');
    }

    public function dashboard()
    {
        try {
            $results = TestResult::obtenerTodosLosResultados();

            if (count($results) < 10) {
                return view('clustering.dashboard')
                    ->with('error', 'Se necesitan al menos 10 tests completados para realizar el análisis de clustering.');
            }

            // Preparar datos para clustering
            $data = $this->prepareDataForClustering($results);

            // Aplicar K-Means clustering
            $clusters = $this->kMeansClustering($data, 6);

            // Calcular estadísticas por cluster
            $clusterStats = $this->calculateClusterStatistics($clusters, $results);

            // Obtener tendencias y patrones
            $trends = $this->analyzeTrends($results);

            return view('clustering.dashboard', compact('clusterStats', 'trends', 'results'));
        } catch (\Exception $e) {
            Log::error('Error en clustering dashboard: ' . $e->getMessage());
            return view('clustering.dashboard')
                ->with('error', 'Error al procesar clustering: ' . $e->getMessage());
        }
    }

    private function prepareDataForClustering($results)
    {
        $data = [];

        foreach ($results as $result) {
            try {
                $scores = is_string($result->scores) ? json_decode($result->scores, true) : $result->scores;
                
                if (!is_array($scores)) {
                    $scores = [];
                }
                
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
            } catch (\Exception $e) {
                Log::warning('Error preparando datos para clustering: ' . $e->getMessage());
                continue;
            }
        }

        return $data;
    }

    private function kMeansClustering($data, $k = 6, $maxIterations = 100)
    {
        $n = count($data);
        if ($n === 0) {
            return ['clusters' => [], 'centroids' => [], 'assignments' => []];
        }

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

    private function euclideanDistance($point1, $point2)
    {
        $sum = 0;
        for ($i = 0; $i < count($point1); $i++) {
            $sum += pow($point1[$i] - $point2[$i], 2);
        }
        return sqrt($sum);
    }

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

            try {
                $centroid = $centroids[$clusterIndex];
                $maxValue = max($centroid);
                $dominantIndex = array_search($maxValue, $centroid);
                $dominantProfile = $categories[$dominantIndex] ?? 'desconocido';

                $careers = [];
                foreach ($clusterData as $point) {
                    $result = collect($results)->firstWhere('id', $point['id']);
                    if ($result) {
                        $recommendedCareers = is_string($result->recommended_careers) 
                            ? json_decode($result->recommended_careers, true) 
                            : $result->recommended_careers;
                        
                        if (isset($recommendedCareers[0]['careers']) && is_array($recommendedCareers[0]['careers'])) {
                            foreach ($recommendedCareers[0]['careers'] as $career) {
                                $careers[] = $career;
                            }
                        }
                    }
                }

                $careerCounts = array_count_values($careers);
                arsort($careerCounts);
                $topCareers = array_slice(array_keys($careerCounts), 0, 5);

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
            } catch (\Exception $e) {
                Log::warning('Error calculando estadísticas del cluster: ' . $e->getMessage());
                continue;
            }
        }

        usort($stats, function($a, $b) {
            return $b['size'] - $a['size'];
        });

        return $stats;
    }

    private function analyzeTrends($results)
    {
        try {
            $categories = ['realista', 'investigador', 'artistico', 'social', 'emprendedor', 'convencional'];
            
            $avgScores = [];
            foreach ($categories as $category) {
                $scores = collect($results)->map(function($result) use ($category) {
                    $scores = is_string($result->scores) ? json_decode($result->scores, true) : $result->scores;
                    return $scores[$category] ?? 0;
                });
                $avgScores[$category] = round($scores->avg(), 1);
            }

            arsort($avgScores);
            $mostPopular = array_key_first($avgScores) ?? 'realista';

            $allCareers = [];
            foreach ($results as $result) {
                $recommendedCareers = is_string($result->recommended_careers) 
                    ? json_decode($result->recommended_careers, true) 
                    : $result->recommended_careers;
                
                if (isset($recommendedCareers[0]['careers']) && is_array($recommendedCareers[0]['careers'])) {
                    foreach ($recommendedCareers[0]['careers'] as $career) {
                        $allCareers[] = $career;
                    }
                }
            }
            
            $careerCounts = array_count_values($allCareers);
            arsort($careerCounts);
            $topCareers = array_slice($careerCounts, 0, 10, true);

            return [
                'total_tests' => count($results),
                'avg_scores' => $avgScores,
                'most_popular_profile' => $mostPopular,
                'top_careers' => $topCareers,
                'completion_rate' => 100
            ];
        } catch (\Exception $e) {
            Log::error('Error analizando tendencias: ' . $e->getMessage());
            return [
                'total_tests' => count($results),
                'avg_scores' => [],
                'most_popular_profile' => 'desconocido',
                'top_careers' => [],
                'completion_rate' => 0
            ];
        }
    }

    public function getClusteringData()
    {
        try {
            $results = TestResult::obtenerTodosLosResultados();

            if (count($results) < 10) {
                return response()->json(['error' => 'Datos insuficientes'], 400);
            }

            $data = $this->prepareDataForClustering($results);
            $clusters = $this->kMeansClustering($data, 6);
            $clusterStats = $this->calculateClusterStatistics($clusters, $results);

            return response()->json([
                'clusters' => $clusterStats,
                'total_results' => count($results)
            ]);
        } catch (\Exception $e) {
            Log::error('Error en getClusteringData: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar datos de clustering'], 500);
        }
    }
}
