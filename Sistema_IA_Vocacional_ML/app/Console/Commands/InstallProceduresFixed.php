<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallProceduresFixed extends Command
{
    protected $signature = 'procedures:install-fixed';
    protected $description = 'Instalar procedures sin DELIMITER';

    public function handle()
    {
        $this->info('Instalando procedures (versión corregida)...');
        
        $this->installProceduresFromFolder('test_procedures', [
            'sp_get_active_tests', 'sp_get_user_completed_tests', 'sp_get_test_by_id',
            'sp_get_test_with_questions', 'sp_get_question_by_number', 'sp_save_test_response',
            'sp_get_user_answer', 'sp_count_user_responses', 'sp_get_last_answered_question',
            'sp_get_first_unanswered_question', 'sp_get_previous_answer', 'sp_check_test_completed',
            'sp_calculate_category_scores', 'sp_save_test_result', 'sp_get_test_result',
            'sp_get_user_latest_result', 'sp_get_user_test_history', 'sp_delete_user_responses',
            'sp_delete_user_result', 'sp_save_prediction', 'sp_get_latest_prediction'
        ]);
        
        return Command::SUCCESS;
    }
    
    private function installProceduresFromFolder($folder, $procedures)
    {
        $basePath = database_path("procedures/{$folder}/");
        
        foreach ($procedures as $procedure) {
            $filePath = $basePath . $procedure . '.sql';
            
            if (!file_exists($filePath)) {
                $this->warn("Archivo no encontrado: {$procedure}.sql");
                continue;
            }
            
            $sql = file_get_contents($filePath);
            
            // Remover DELIMITER
            $sql = preg_replace('/DELIMITER \$\$|DELIMITER ;/', '', $sql);
            
            try {
                DB::statement("DROP PROCEDURE IF EXISTS {$procedure}");
                DB::unprepared($sql);
                $this->info("✅ {$procedure}");
            } catch (\Exception $e) {
                $this->error("❌ {$procedure}: " . $e->getMessage());
            }
        }
    }
}