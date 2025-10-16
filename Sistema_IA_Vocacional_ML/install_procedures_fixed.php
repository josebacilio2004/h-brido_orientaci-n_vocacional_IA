<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "=== INSTALADOR DE PROCEDURES CORREGIDO ===\n\n";

function installProceduresFromFolder($folder, $procedures) {
    $basePath = __DIR__ . "/database/procedures/{$folder}/";
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($procedures as $procedure) {
        $filePath = $basePath . $procedure . '.sql';
        
        if (!file_exists($filePath)) {
            echo "⚠️  Archivo no encontrado: {$procedure}.sql\n";
            $errorCount++;
            continue;
        }
        
        $sql = file_get_contents($filePath);
        
        try {
            // Eliminar DELIMITER commands si existen
            $sql = preg_replace('/DELIMITER \$\$|DELIMITER ;/', '', $sql);
            
            // Eliminar procedure si existe
            DB::statement("DROP PROCEDURE IF EXISTS {$procedure}");
            
            // Crear nuevo procedure
            DB::unprepared($sql);
            
            echo "✅ {$procedure} instalado correctamente\n";
            $successCount++;
            
        } catch (Exception $e) {
            echo "❌ Error instalando {$procedure}: " . $e->getMessage() . "\n";
            
            // Mostrar solo el primer error detallado
            if ($errorCount === 0) {
                echo "   SQL: " . substr($sql, 0, 200) . "...\n";
            }
            $errorCount++;
        }
    }
    
    return [$successCount, $errorCount];
}

// Instalar procedures
$totalSuccess = 0;
$totalError = 0;

echo "📂 TEST PROCEDURES:\n";
$testProcedures = [
    'sp_get_active_tests', 'sp_get_user_completed_tests', 'sp_get_test_by_id',
    'sp_get_test_with_questions', 'sp_get_question_by_number', 'sp_save_test_response',
    'sp_get_user_answer', 'sp_count_user_responses', 'sp_get_last_answered_question',
    'sp_get_first_unanswered_question', 'sp_get_previous_answer', 'sp_check_test_completed',
    'sp_calculate_category_scores', 'sp_save_test_result', 'sp_get_test_result',
    'sp_get_user_latest_result', 'sp_get_user_test_history', 'sp_delete_user_responses',
    'sp_delete_user_result', 'sp_save_prediction', 'sp_get_latest_prediction'
];
list($success, $error) = installProceduresFromFolder('test_procedures', $testProcedures);
$totalSuccess += $success;
$totalError += $error;

echo "\n📂 CAREER PROCEDURES:\n";
$careerProcedures = [
    'sp_get_all_careers', 'sp_get_all_faculties', 'sp_get_career_details',
    'sp_get_careers_by_faculty', 'sp_get_careers_by_riasec', 'sp_search_careers',
    'sp_get_recommended_careers', 'sp_get_popular_careers', 'sp_get_career_statistics'
];
list($success, $error) = installProceduresFromFolder('career_procedures', $careerProcedures);
$totalSuccess += $success;
$totalError += $error;

echo "\n📂 CLUSTERING PROCEDURES:\n";
$clusteringProcedures = [
    'sp_get_clustering_data', 'sp_get_completed_tests', 'sp_save_clustering_results',
    'sp_get_clustering_history', 'sp_get_general_statistics'
];
list($success, $error) = installProceduresFromFolder('clustering_procedures', $clusteringProcedures);
$totalSuccess += $success;
$totalError += $error;

echo "\n📂 GRADE PROCEDURES:\n";
$gradeProcedures = [
    'sp_get_user_grades', 'sp_save_or_update_grades', 'sp_get_user_average', 'sp_get_grade_statistics'
];
list($success, $error) = installProceduresFromFolder('grade_procedures', $gradeProcedures);
$totalSuccess += $success;
$totalError += $error;

echo "\n==========================================\n";
echo "RESUMEN:\n";
echo "✅ {$totalSuccess} procedures instalados\n";
echo "❌ {$totalError} procedures con errores\n";

if ($totalError > 0) {
    echo "\n💡 Si hay errores, puede ser porque:\n";
    echo "   - Las tablas no existen todavía\n";
    echo "   - Hay problemas de permisos\n";
    echo "   - Algunos procedures tienen dependencias\n";
}