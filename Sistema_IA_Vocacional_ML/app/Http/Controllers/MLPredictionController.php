<?php

namespace App\Http\Controllers;

use App\Services\MLPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MLPredictionController extends Controller
{
    protected $mlService;

    public function __construct(MLPredictionService $mlService)
    {
        $this->mlService = $mlService;
        $this->middleware('auth');
    }

    /**
     * Obtener predicción de carreras para el usuario actual
     */
    public function predict(Request $request)
    {
        try {
            $includeGrades = $request->get('include_grades', true);
            $prediction = $this->mlService->predictCareers(Auth::id(), $includeGrades);

            return response()->json([
                'success' => true,
                'prediction' => $prediction,
                'recommended_careers' => $prediction->predicted_careers,
                'confidence' => $prediction->confidence_score
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in MLPredictionController@predict: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al generar predicción'
            ], 500);
        }
    }

    /**
     * Obtener última predicción del usuario
     */
    public function latest()
    {
        try {
            $prediction = $this->mlService->getLastPrediction(Auth::id());

            if (!$prediction) {
                return response()->json([
                    'success' => false,
                    'error' => 'No hay predicciones disponibles'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'prediction' => $prediction
            ]);
        } catch (\Exception $e) {
            Log::error('[v0] Error in MLPredictionController@latest: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener predicción'
            ], 500);
        }
    }

    /**
     * Vista para mostrar predicciones (solo admin)
     */
    public function index()
    {
        $this->authorize('admin');

        try {
            $predictions = DB::table('ml_predictions')
                ->select('user_id', DB::raw('COUNT(*) as prediction_count'), DB::raw('MAX(created_at) as last_prediction'))
                ->groupBy('user_id')
                ->paginate(50);

            return view('ml.predictions.index', compact('predictions'));
        } catch (\Exception $e) {
            Log::error('[v0] Error in MLPredictionController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar predicciones');
        }
    }
}