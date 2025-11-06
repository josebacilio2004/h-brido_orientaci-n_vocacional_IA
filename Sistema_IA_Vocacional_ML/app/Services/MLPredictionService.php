<?php

namespace App\Services;

use App\Models\MLPrediction;
use App\Models\ModelPerformance;
use App\Models\User;
use App\Models\Career;
use App\Models\StudentGrade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MLPredictionService
{
    protected $pythonScriptPath;
    protected $modelsPath;

    public function __construct()
    {
        $this->pythonScriptPath = base_path('python/ml_model.py');
        $this->modelsPath = storage_path('app/ml-models');
    }

    /**
     * Generar predicción enriquecida incluyendo notas de cursos específicos
     */
    public function predictCareers($userId, $includeGrades = true)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }

            $features = $this->extractEnhancedFeatures($userId, $includeGrades);
            Log::info('[v0] ML Enhanced Features extracted for user: ' . $userId, $features);

            $prediction = $this->callPythonModel($features);
            Log::info('[v0] ML Prediction received for user: ' . $userId);

            $careerPredictions = $this->processPredictionResults($prediction);

            $mlPrediction = MLPrediction::create([
                'user_id' => $userId,
                'prediction_type' => 'career_recommendation',
                'features' => $features,
                'predicted_careers' => $careerPredictions,
                'confidence_score' => $this->calculateConfidenceScore($prediction),
                'model_metadata' => [
                    'model_version' => config('ml.model_version', '2.0_enhanced'),
                    'timestamp' => now()->toDateTimeString(),
                    'training_date' => $this->getModelTrainingDate(),
                    'features_count' => count($features),
                ]
            ]);

            return $mlPrediction;
        } catch (Exception $e) {
            Log::error('[v0] Error in ML prediction for user ' . $userId . ': ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extraer características enriquecidas del estudiante
     */
    private function extractEnhancedFeatures($userId, $includeGrades = true)
    {
        $user = User::find($userId);
        $grades = [];
        $riasecScores = [];
        $testScores = [];

        if ($includeGrades) {
            $grades = $this->extractStudentGrades($userId);
            $riasecScores = $this->extractRiasecScores($userId);
        }

        $testScores = $this->extractEnhancedTestScores($userId);

        return [
            'user_id' => $userId,
            'age' => $user->age ?? null,
            'gender' => $user->gender ?? null,
            'nota_matematica' => $grades['nota_matematica'] ?? 0,
            'nota_comunicacion' => $grades['nota_comunicacion'] ?? 0,
            'nota_ciencias_sociales' => $grades['nota_ciencias_sociales'] ?? 0,
            'nota_ciencia_tecnologia' => $grades['nota_ciencia_tecnologia'] ?? 0,
            'nota_desarrollo_personal' => $grades['nota_desarrollo_personal'] ?? 0,
            'nota_ciudadania_civica' => $grades['nota_ciudadania_civica'] ?? 0,
            'nota_educacion_fisica' => $grades['nota_educacion_fisica'] ?? 0,
            'nota_ingles' => $grades['nota_ingles'] ?? 0,
            'nota_educacion_trabajo' => $grades['nota_educacion_trabajo'] ?? 0,
            'riasec_realista' => $riasecScores['realista'] ?? 0,
            'riasec_investigador' => $riasecScores['investigador'] ?? 0,
            'riasec_artistico' => $riasecScores['artistico'] ?? 0,
            'riasec_social' => $riasecScores['social'] ?? 0,
            'riasec_emprendedor' => $riasecScores['emprendedor'] ?? 0,
            'riasec_convencional' => $riasecScores['convencional'] ?? 0,
            'gpa' => $grades['gpa'] ?? 0,
            'academic_performance' => $grades['performance_level'] ?? 'average',
            'interest_scores' => $testScores['interests'] ?? [],
            'skill_scores' => $testScores['skills'] ?? [],
            'personality_traits' => $testScores['personality'] ?? [],
            'social_economic_status' => $user->socioeconomic_status ?? 'middle',
            'completed_tests_count' => count($testScores),
            'engagement_level' => $this->calculateEngagementLevel($userId),
        ];
    }

    /**
     * Extraer notas académicas por materia
     */
    private function extractStudentGrades($userId)
    {
        try {
            $grades = StudentGrade::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$grades) {
                return $this->getDefaultGrades();
            }

            $notas = [
                $grades->nota_matematica,
                $grades->nota_comunicacion,
                $grades->nota_ciencias_sociales,
                $grades->nota_ciencia_tecnologia,
                $grades->nota_desarrollo_personal,
                $grades->nota_ciudadania_civica,
                $grades->nota_educacion_fisica,
                $grades->nota_ingles,
                $grades->nota_educacion_trabajo,
            ];

            $gpa = array_sum($notas) / count($notas);
            $performanceLevel = match(true) {
                $gpa >= 18 => 'excellent',
                $gpa >= 15 => 'very_good',
                $gpa >= 13 => 'good',
                $gpa >= 11 => 'average',
                default => 'below_average'
            };

            return [
                'nota_matematica' => $grades->nota_matematica,
                'nota_comunicacion' => $grades->nota_comunicacion,
                'nota_ciencias_sociales' => $grades->nota_ciencias_sociales,
                'nota_ciencia_tecnologia' => $grades->nota_ciencia_tecnologia,
                'nota_desarrollo_personal' => $grades->nota_desarrollo_personal,
                'nota_ciudadania_civica' => $grades->nota_ciudadania_civica,
                'nota_educacion_fisica' => $grades->nota_educacion_fisica,
                'nota_ingles' => $grades->nota_ingles,
                'nota_educacion_trabajo' => $grades->nota_educacion_trabajo,
                'gpa' => $gpa,
                'performance_level' => $performanceLevel,
            ];
        } catch (Exception $e) {
            Log::warning('[v0] Could not extract grades for user ' . $userId . ': ' . $e->getMessage());
            return $this->getDefaultGrades();
        }
    }

    /**
     * Get default grades structure
     */
    private function getDefaultGrades()
    {
        return [
            'nota_matematica' => 0,
            'nota_comunicacion' => 0,
            'nota_ciencias_sociales' => 0,
            'nota_ciencia_tecnologia' => 0,
            'nota_desarrollo_personal' => 0,
            'nota_ciudadania_civica' => 0,
            'nota_educacion_fisica' => 0,
            'nota_ingles' => 0,
            'nota_educacion_trabajo' => 0,
            'gpa' => 0,
            'performance_level' => 'unknown'
        ];
    }

    /**
     * Extract RIASEC scores from test results
     */
    private function extractRiasecScores($userId)
    {
        try {
            $result = DB::table('test_results')
                ->where('user_id', $userId)
                ->where('test_type', 'riasec')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($result && $result->scores) {
                $scores = json_decode($result->scores, true);
                return [
                    'realista' => $scores['realista'] ?? 0,
                    'investigador' => $scores['investigador'] ?? 0,
                    'artistico' => $scores['artistico'] ?? 0,
                    'social' => $scores['social'] ?? 0,
                    'emprendedor' => $scores['emprendedor'] ?? 0,
                    'convencional' => $scores['convencional'] ?? 0,
                ];
            }

            return $this->getDefaultRiasecScores();
        } catch (Exception $e) {
            Log::warning('[v0] Could not extract RIASEC scores: ' . $e->getMessage());
            return $this->getDefaultRiasecScores();
        }
    }

    private function getDefaultRiasecScores()
    {
        return [
            'realista' => 0,
            'investigador' => 0,
            'artistico' => 0,
            'social' => 0,
            'emprendedor' => 0,
            'convencional' => 0,
        ];
    }

    /**
     * Enhanced test score extraction from multiple tests
     */
    private function extractEnhancedTestScores($userId)
    {
        try {
            $interestScores = DB::table('student_test_interest_answers')
                ->where('user_id', $userId)
                ->select(DB::raw('AVG(answer) as avg_score'))
                ->groupBy(DB::raw('SUBSTRING(question_id, 1, 2)'))
                ->pluck('avg_score')
                ->toArray();

            $skillScores = DB::table('student_test_skill_answers')
                ->where('user_id', $userId)
                ->select(DB::raw('AVG(answer) as avg_score'))
                ->groupBy(DB::raw('SUBSTRING(question_id, 1, 2)'))
                ->pluck('avg_score')
                ->toArray();

            $personalityScores = DB::table('student_test_personality_answers')
                ->where('user_id', $userId)
                ->select(DB::raw('AVG(answer) as avg_score'))
                ->groupBy(DB::raw('SUBSTRING(question_id, 1, 2)'))
                ->pluck('avg_score')
                ->toArray();

            return [
                'interests' => $interestScores,
                'skills' => $skillScores,
                'personality' => $personalityScores,
            ];
        } catch (Exception $e) {
            Log::warning('[v0] Could not extract test scores for user ' . $userId . ': ' . $e->getMessage());
            return ['interests' => [], 'skills' => [], 'personality' => []];
        }
    }

    /**
     * Calcular nivel de compromiso del estudiante
     */
    private function calculateEngagementLevel($userId)
    {
        try {
            $testCount = DB::table('student_test_interest_answers')
                ->where('user_id', $userId)
                ->distinct('test_id')
                ->count('test_id');

            return match(true) {
                $testCount >= 5 => 'high',
                $testCount >= 3 => 'medium',
                $testCount >= 1 => 'low',
                default => 'none'
            };
        } catch (Exception $e) {
            return 'unknown';
        }
    }

    private function callPythonModel($features)
    {
        try {
            $featuresJson = json_encode($features);
            $command = sprintf(
                'python3 %s \'%s\' 2>&1',
                escapeshellarg($this->pythonScriptPath),
                escapeshellarg($featuresJson)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                Log::error('[v0] Python ML script failed: ' . implode('\n', $output));
                throw new Exception('ML model prediction failed');
            }

            $result = json_decode(implode('\n', $output), true);
            return $result;
        } catch (Exception $e) {
            Log::error('[v0] Error calling Python ML model: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processPredictionResults($prediction)
    {
        try {
            $careers = Career::all();
            $careerPredictions = [];

            if (isset($prediction['predictions']) && is_array($prediction['predictions'])) {
                foreach ($prediction['predictions'] as $index => $score) {
                    if ($index < count($careers)) {
                        $career = $careers[$index];
                        $careerPredictions[] = [
                            'career_id' => $career->id,
                            'career_name' => $career->name,
                            'probability' => min($score * 100, 100),
                            'rank' => count($careerPredictions) + 1,
                        ];
                    }
                }

                usort($careerPredictions, function ($a, $b) {
                    return $b['probability'] <=> $a['probability'];
                });

                return array_slice($careerPredictions, 0, 5);
            }

            return [];
        } catch (Exception $e) {
            Log::error('[v0] Error processing ML prediction results: ' . $e->getMessage());
            return [];
        }
    }

    private function calculateConfidenceScore($prediction)
    {
        if (isset($prediction['confidence'])) {
            return min($prediction['confidence'], 1.0);
        }

        if (isset($prediction['predictions']) && is_array($prediction['predictions'])) {
            $topScore = max($prediction['predictions']);
            return min($topScore, 1.0);
        }

        return 0.5;
    }

    private function getModelTrainingDate()
    {
        try {
            $performance = ModelPerformance::orderBy('trained_at', 'desc')->first();
            return $performance?->trained_at?->toDateTimeString() ?? 'unknown';
        } catch (Exception $e) {
            return 'unknown';
        }
    }

    public function trainModel($sampleSize = 1000)
    {
        try {
            Log::info('[v0] Starting ML model training');

            $trainingData = $this->prepareTrainingData($sampleSize);
            $command = sprintf(
                'python3 %s \'%s\' --train 2>&1',
                escapeshellarg($this->pythonScriptPath),
                escapeshellarg(json_encode($trainingData))
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                throw new Exception('Training failed: ' . implode('\n', $output));
            }

            $result = json_decode(implode('\n', $output), true);

            ModelPerformance::create([
                'model_name' => 'RandomForestCareerPredictor',
                'accuracy' => $result['accuracy'] ?? 0,
                'precision' => $result['precision'] ?? 0,
                'recall' => $result['recall'] ?? 0,
                'f1_score' => $result['f1_score'] ?? 0,
                'confusion_matrix' => $result['confusion_matrix'] ?? [],
                'trained_at' => now(),
                'notes' => 'Training completed with ' . $sampleSize . ' samples',
            ]);

            Log::info('[v0] ML model trained successfully with accuracy: ' . ($result['accuracy'] ?? 0));
            return $result;
        } catch (Exception $e) {
            Log::error('[v0] Error training ML model: ' . $e->getMessage());
            throw $e;
        }
    }

    private function prepareTrainingData($sampleSize)
    {
        try {
            $users = User::limit($sampleSize)->get();
            $trainingData = [];

            foreach ($users as $user) {
                $features = $this->extractEnhancedFeatures($user->id, true);
                $preferredCareerId = $user->preferred_career_id ?? null;

                $trainingData[] = [
                    'features' => $features,
                    'label' => $preferredCareerId,
                ];
            }

            return $trainingData;
        } catch (Exception $e) {
            Log::error('[v0] Error preparing training data: ' . $e->getMessage());
            return [];
        }
    }

    public function getLastPrediction($userId)
    {
        try {
            return MLPrediction::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->first();
        } catch (Exception $e) {
            Log::error('[v0] Error getting last prediction for user ' . $userId . ': ' . $e->getMessage());
            return null;
        }
    }

    public function cleanOldPredictions($daysOld = 30)
    {
        try {
            $deleted = MLPrediction::where('created_at', '<', now()->subDays($daysOld))
                ->delete();

            Log::info('[v0] Deleted ' . $deleted . ' old ML predictions');
            return $deleted;
        } catch (Exception $e) {
            Log::error('[v0] Error cleaning old predictions: ' . $e->getMessage());
            return 0;
        }
    }
}
