<?php

namespace App\DAO;

use App\DAO\Interfaces\ClusteringDAOInterface;
use App\Models\TestResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClusteringDAO implements ClusteringDAOInterface
{
    /**
     * Obtener todos los resultados de tests
     */
    public function getAllTestResults()
    {
        try {
            return TestResult::with('user')
                ->whereNotNull('completed_at')
                ->orderBy('completed_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting all test results: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener resultados de tests con filtros
     */
    public function getTestResultsWithFilters(array $filters)
    {
        try {
            $query = TestResult::with('user')
                ->whereNotNull('completed_at');
            
            if (isset($filters['test_id'])) {
                $query->where('vocational_test_id', $filters['test_id']);
            }
            
            if (isset($filters['date_from'])) {
                $query->whereDate('completed_at', '>=', $filters['date_from']);
            }
            
            if (isset($filters['date_to'])) {
                $query->whereDate('completed_at', '<=', $filters['date_to']);
            }
            
            return $query->orderBy('completed_at', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error getting test results with filters: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Guardar cluster
     */
    public function saveCluster(int $clusterId, array $userIds, array $clusterData)
    {
        try {
            // Guardar información del cluster en la tabla de clustering
            return DB::table('clustering_results')->updateOrCreate(
                ['cluster_id' => $clusterId],
                [
                    'user_ids' => json_encode($userIds),
                    'cluster_data' => json_encode($clusterData),
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error saving cluster: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener clusters
     */
    public function getClusters()
    {
        try {
            return DB::table('clustering_results')
                ->orderBy('cluster_id', 'asc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting clusters: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener usuarios en cluster
     */
    public function getUsersInCluster(int $clusterId)
    {
        try {
            $cluster = DB::table('clustering_results')
                ->where('cluster_id', $clusterId)
                ->first();
            
            if ($cluster) {
                $userIds = json_decode($cluster->user_ids, true);
                return DB::table('users')
                    ->whereIn('id', $userIds)
                    ->get();
            }
            
            return collect();
        } catch (\Exception $e) {
            Log::error('Error getting users in cluster: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Obtener estadísticas de clustering
     */
    public function getClusteringStats()
    {
        try {
            $clusters = DB::table('clustering_results')->get();
            
            $stats = [
                'total_clusters' => $clusters->count(),
                'total_users' => 0,
                'clusters_data' => []
            ];
            
            foreach ($clusters as $cluster) {
                $userIds = json_decode($cluster->user_ids, true);
                $stats['total_users'] += count($userIds);
                $stats['clusters_data'][] = [
                    'cluster_id' => $cluster->cluster_id,
                    'user_count' => count($userIds),
                    'data' => json_decode($cluster->cluster_data, true)
                ];
            }
            
            return $stats;
        } catch (\Exception $e) {
            Log::error('Error getting clustering stats: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Eliminar clusters
     */
    public function deleteClusters()
    {
        try {
            return DB::table('clustering_results')->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting clusters: ' . $e->getMessage());
            throw $e;
        }
    }
}
