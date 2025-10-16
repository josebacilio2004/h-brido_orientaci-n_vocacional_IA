<?php

namespace App\Repositories;

use App\Models\TestResult;
use Illuminate\Support\Facades\DB;

class ClusteringRepository
{
    
    /**
     * Obtener datos para clustering
     */
    public function getClusteringData()
    {
        return TestResult::with(['user', 'test'])
            ->whereNotNull('completed_at')
            ->get()
            ->map(function($result) {
                return [
                    'id' => $result->id,
                    'user_id' => $result->user_id,
                    'scores' => $result->scores,
                    'recommended_careers' => $result->recommended_careers,
                    'total_score' => $result->total_score,
                    'completed_at' => $result->completed_at
                ];
            })
            ->toArray();
    }

    /**
     * Obtener todos los tests completados
     */
    public function getAllCompletedTests()
    {
        return TestResult::whereNotNull('completed_at')
            ->get()
            ->map(function($result) {
                return [
                    'id' => $result->id,
                    'user_id' => $result->user_id,
                    'scores' => $result->scores,
                    'recommended_careers' => $result->recommended_careers,
                    'total_score' => $result->total_score,
                    'completed_at' => $result->completed_at
                ];
            })
            ->toArray();
    }

    /**
     * Obtener estadísticas generales
     */
    public function getGeneralStatistics()
    {
        $totalTests = TestResult::whereNotNull('completed_at')->count();
        $totalUsers = TestResult::whereNotNull('completed_at')->distinct('user_id')->count();
        $avgScore = TestResult::whereNotNull('completed_at')->avg('total_score');
        
        return [
            'total_tests' => $totalTests,
            'total_users' => $totalUsers,
            'avg_score' => round($avgScore, 2),
            'completion_rate' => 100
        ];
    }

    /**
     * Obtener distribución de perfiles RIASEC
     */
    public function getRiasecDistribution()
    {
        $results = TestResult::whereNotNull('completed_at')->get();
        
        $distribution = [
            'realista' => 0,
            'investigador' => 0,
            'artistico' => 0,
            'social' => 0,
            'emprendedor' => 0,
            'convencional' => 0
        ];
        
        foreach ($results as $result) {
            $scores = $result->scores;
            if ($scores) {
                $maxCategory = array_keys($scores, max($scores))[0];
                $distribution[$maxCategory]++;
            }
        }
        
        return $distribution;
    }
}
