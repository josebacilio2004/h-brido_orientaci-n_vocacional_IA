<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckProcedures extends Command
{
    protected $signature = 'procedures:check';
    protected $description = 'Verificar que todos los procedures estén instalados';

    public function handle()
    {
        $procedures = [
            // Test procedures
            'sp_get_active_tests', 'sp_get_user_completed_tests', 'sp_get_test_by_id',
            'sp_get_test_with_questions', 'sp_get_question_by_number', 'sp_save_test_response',
            'sp_get_user_answer', 'sp_count_user_responses', 'sp_get_last_answered_question',
            'sp_get_first_unanswered_question', 'sp_get_previous_answer', 'sp_check_test_completed',
            'sp_calculate_category_scores', 'sp_save_test_result', 'sp_get_test_result',
            'sp_get_user_latest_result', 'sp_get_user_test_history', 'sp_delete_user_responses',
            'sp_delete_user_result', 'sp_save_prediction', 'sp_get_latest_prediction',
            
            // Career procedures
            'sp_get_all_careers', 'sp_get_all_faculties', 'sp_get_career_details',
            'sp_get_careers_by_faculty', 'sp_get_careers_by_riasec', 'sp_search_careers',
            'sp_get_recommended_careers', 'sp_get_popular_careers', 'sp_get_career_statistics',
            
            // Clustering procedures
            'sp_get_clustering_data', 'sp_get_completed_tests', 'sp_save_clustering_results',
            'sp_get_clustering_history', 'sp_get_general_statistics',
            
            // Grade procedures
            'sp_get_user_grades', 'sp_save_or_update_grades', 'sp_get_user_average', 'sp_get_grade_statistics'
        ];
        
        $total = count($procedures);
        $this->info("Verificando {$total} procedures...");
        
        $installed = 0;
        $missing = 0;
        
        foreach ($procedures as $procedure) {
            try {
                $result = DB::select("SHOW CREATE PROCEDURE {$procedure}");
                $this->info("✅ {$procedure} - INSTALADO");
                $installed++;
            } catch (\Exception $e) {
                $this->error("❌ {$procedure} - NO INSTALADO");
                $missing++;
            }
        }
        
        $this->info("\nResumen:");
        $this->info("✅ {$installed} procedures instalados");
        $this->error("❌ {$missing} procedures faltantes");
        
        return Command::SUCCESS;
    }
}