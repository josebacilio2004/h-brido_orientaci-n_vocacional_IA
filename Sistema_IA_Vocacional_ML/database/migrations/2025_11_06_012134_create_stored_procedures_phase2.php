<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // STORED PROCEDURES - TEST DE INTERESES
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_interest_tests');
        DB::unprepared('
            CREATE PROCEDURE sp_get_active_interest_tests()
            BEGIN
                SELECT * FROM test_interests WHERE is_active = 1 ORDER BY created_at DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_interest_test_with_questions');
        DB::unprepared('
            CREATE PROCEDURE sp_get_interest_test_with_questions(IN p_test_id BIGINT)
            BEGIN
                SELECT * FROM test_interests WHERE id = p_test_id LIMIT 1;
                SELECT * FROM interest_questions WHERE test_interest_id = p_test_id ORDER BY question_number ASC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_interest_response');
        DB::unprepared('
            CREATE PROCEDURE sp_save_interest_response(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_question_id BIGINT,
                IN p_answer TEXT,
                IN p_score INT
            )
            BEGIN
                INSERT INTO interest_responses (user_id, test_interest_id, interest_question_id, answer, score, created_at, updated_at)
                VALUES (p_user_id, p_test_id, p_question_id, p_answer, p_score, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    answer = p_answer,
                    score = p_score,
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_interest_scores');
        DB::unprepared('
            CREATE PROCEDURE sp_calculate_interest_scores(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT 
                    iq.category,
                    SUM(ir.score) as total_score,
                    COUNT(*) as question_count,
                    AVG(ir.score) as avg_score
                FROM interest_responses ir
                INNER JOIN interest_questions iq ON ir.interest_question_id = iq.id
                WHERE ir.user_id = p_user_id 
                AND ir.test_interest_id = p_test_id
                GROUP BY iq.category
                ORDER BY total_score DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_interest_result');
        DB::unprepared('
            CREATE PROCEDURE sp_save_interest_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_scores JSON,
                IN p_recommended_careers JSON,
                IN p_analysis TEXT,
                IN p_total_score INT
            )
            BEGIN
                INSERT INTO interest_results (
                    user_id, test_interest_id, scores, recommended_careers, analysis, total_score, completed_at, created_at, updated_at
                )
                VALUES (p_user_id, p_test_id, p_scores, p_recommended_careers, p_analysis, p_total_score, NOW(), NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    scores = p_scores,
                    recommended_careers = p_recommended_careers,
                    analysis = p_analysis,
                    total_score = p_total_score,
                    completed_at = NOW(),
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_interest_result');
        DB::unprepared('
            CREATE PROCEDURE sp_get_interest_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT ir.*, ti.name as test_name, ti.description as test_description
                FROM interest_results ir
                INNER JOIN test_interests ti ON ir.test_interest_id = ti.id
                WHERE ir.user_id = p_user_id 
                AND ir.test_interest_id = p_test_id
                ORDER BY ir.completed_at DESC
                LIMIT 1;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - TEST DE HABILIDADES
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_skill_tests');
        DB::unprepared('
            CREATE PROCEDURE sp_get_active_skill_tests()
            BEGIN
                SELECT * FROM test_skills WHERE is_active = 1 ORDER BY created_at DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_skill_test_with_questions');
        DB::unprepared('
            CREATE PROCEDURE sp_get_skill_test_with_questions(IN p_test_id BIGINT)
            BEGIN
                SELECT * FROM test_skills WHERE id = p_test_id LIMIT 1;
                SELECT * FROM skill_questions WHERE test_skill_id = p_test_id ORDER BY question_number ASC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_skill_response');
        DB::unprepared('
            CREATE PROCEDURE sp_save_skill_response(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_question_id BIGINT,
                IN p_answer TEXT,
                IN p_score INT
            )
            BEGIN
                INSERT INTO skill_responses (user_id, test_skill_id, skill_question_id, answer, score, created_at, updated_at)
                VALUES (p_user_id, p_test_id, p_question_id, p_answer, p_score, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    answer = p_answer,
                    score = p_score,
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_skill_scores');
        DB::unprepared('
            CREATE PROCEDURE sp_calculate_skill_scores(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT 
                    sq.skill_category,
                    SUM(sr.score) as total_score,
                    COUNT(*) as question_count,
                    AVG(sr.score) as avg_score
                FROM skill_responses sr
                INNER JOIN skill_questions sq ON sr.skill_question_id = sq.id
                WHERE sr.user_id = p_user_id 
                AND sr.test_skill_id = p_test_id
                GROUP BY sq.skill_category
                ORDER BY total_score DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_skill_result');
        DB::unprepared('
            CREATE PROCEDURE sp_save_skill_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_scores JSON,
                IN p_recommended_careers JSON,
                IN p_analysis TEXT,
                IN p_total_score INT
            )
            BEGIN
                INSERT INTO skill_results (
                    user_id, test_skill_id, scores, recommended_careers, analysis, total_score, completed_at, created_at, updated_at
                )
                VALUES (p_user_id, p_test_id, p_scores, p_recommended_careers, p_analysis, p_total_score, NOW(), NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    scores = p_scores,
                    recommended_careers = p_recommended_careers,
                    analysis = p_analysis,
                    total_score = p_total_score,
                    completed_at = NOW(),
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_skill_result');
        DB::unprepared('
            CREATE PROCEDURE sp_get_skill_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT sr.*, ts.name as test_name, ts.description as test_description
                FROM skill_results sr
                INNER JOIN test_skills ts ON sr.test_skill_id = ts.id
                WHERE sr.user_id = p_user_id 
                AND sr.test_skill_id = p_test_id
                ORDER BY sr.completed_at DESC
                LIMIT 1;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - TEST DE PERSONALIDAD
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_personality_tests');
        DB::unprepared('
            CREATE PROCEDURE sp_get_active_personality_tests()
            BEGIN
                SELECT * FROM test_personality WHERE is_active = 1 ORDER BY created_at DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_personality_test_with_questions');
        DB::unprepared('
            CREATE PROCEDURE sp_get_personality_test_with_questions(IN p_test_id BIGINT)
            BEGIN
                SELECT * FROM test_personality WHERE id = p_test_id LIMIT 1;
                SELECT * FROM personality_questions WHERE test_personality_id = p_test_id ORDER BY question_number ASC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_personality_response');
        DB::unprepared('
            CREATE PROCEDURE sp_save_personality_response(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_question_id BIGINT,
                IN p_answer TEXT,
                IN p_score INT
            )
            BEGIN
                INSERT INTO personality_responses (user_id, test_personality_id, personality_question_id, answer, score, created_at, updated_at)
                VALUES (p_user_id, p_test_id, p_question_id, p_answer, p_score, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    answer = p_answer,
                    score = p_score,
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_personality_scores');
        DB::unprepared('
            CREATE PROCEDURE sp_calculate_personality_scores(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT 
                    pq.trait,
                    SUM(pr.score) as total_score,
                    COUNT(*) as question_count,
                    AVG(pr.score) as avg_score
                FROM personality_responses pr
                INNER JOIN personality_questions pq ON pr.personality_question_id = pq.id
                WHERE pr.user_id = p_user_id 
                AND pr.test_personality_id = p_test_id
                GROUP BY pq.trait
                ORDER BY total_score DESC;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_personality_result');
        DB::unprepared('
            CREATE PROCEDURE sp_save_personality_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_scores JSON,
                IN p_recommended_careers JSON,
                IN p_analysis TEXT,
                IN p_total_score INT
            )
            BEGIN
                INSERT INTO personality_results (
                    user_id, test_personality_id, scores, recommended_careers, analysis, total_score, completed_at, created_at, updated_at
                )
                VALUES (p_user_id, p_test_id, p_scores, p_recommended_careers, p_analysis, p_total_score, NOW(), NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    scores = p_scores,
                    recommended_careers = p_recommended_careers,
                    analysis = p_analysis,
                    total_score = p_total_score,
                    completed_at = NOW(),
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_personality_result');
        DB::unprepared('
            CREATE PROCEDURE sp_get_personality_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT pr.*, tp.name as test_name, tp.description as test_description
                FROM personality_results pr
                INNER JOIN test_personality tp ON pr.test_personality_id = tp.id
                WHERE pr.user_id = p_user_id 
                AND pr.test_personality_id = p_test_id
                ORDER BY pr.completed_at DESC
                LIMIT 1;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - NOTAS ACADÉMICAS
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_academic_grades');
        DB::unprepared('
            CREATE PROCEDURE sp_save_academic_grades(
                IN p_user_id BIGINT,
                IN p_nota_matematica INT,
                IN p_nota_comunicacion INT,
                IN p_nota_ciencias_sociales INT,
                IN p_nota_ciencia_tecnologia INT,
                IN p_nota_desarrollo_personal INT,
                IN p_nota_ciudadania_civica INT,
                IN p_nota_educacion_fisica INT,
                IN p_nota_ingles INT,
                IN p_nota_educacion_trabajo INT,
                IN p_academic_year INT
            )
            BEGIN
                INSERT INTO student_grades (
                    user_id, nota_matematica, nota_comunicacion, nota_ciencias_sociales,
                    nota_ciencia_tecnologia, nota_desarrollo_personal, nota_ciudadania_civica,
                    nota_educacion_fisica, nota_ingles, nota_educacion_trabajo, academic_year,
                    created_at, updated_at
                )
                VALUES (
                    p_user_id, p_nota_matematica, p_nota_comunicacion, p_nota_ciencias_sociales,
                    p_nota_ciencia_tecnologia, p_nota_desarrollo_personal, p_nota_ciudadania_civica,
                    p_nota_educacion_fisica, p_nota_ingles, p_nota_educacion_trabajo, p_academic_year,
                    NOW(), NOW()
                )
                ON DUPLICATE KEY UPDATE
                    nota_matematica = p_nota_matematica,
                    nota_comunicacion = p_nota_comunicacion,
                    nota_ciencias_sociales = p_nota_ciencias_sociales,
                    nota_ciencia_tecnologia = p_nota_ciencia_tecnologia,
                    nota_desarrollo_personal = p_nota_desarrollo_personal,
                    nota_ciudadania_civica = p_nota_ciudadania_civica,
                    nota_educacion_fisica = p_nota_educacion_fisica,
                    nota_ingles = p_nota_ingles,
                    nota_educacion_trabajo = p_nota_educacion_trabajo,
                    academic_year = p_academic_year,
                    updated_at = NOW();
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_academic_grades');
        DB::unprepared('
            CREATE PROCEDURE sp_get_academic_grades(IN p_user_id BIGINT)
            BEGIN
                SELECT * FROM student_grades WHERE user_id = p_user_id ORDER BY academic_year DESC LIMIT 1;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - PREDICCIONES ML
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_ml_prediction');
        DB::unprepared('
            CREATE PROCEDURE sp_save_ml_prediction(
                IN p_user_id BIGINT,
                IN p_prediction_type VARCHAR(50),
                IN p_features JSON,
                IN p_predicted_careers JSON,
                IN p_confidence_score FLOAT,
                IN p_model_metadata JSON
            )
            BEGIN
                INSERT INTO ml_predictions (
                    user_id, prediction_type, features, predicted_careers, confidence_score, model_metadata, created_at, updated_at
                )
                VALUES (p_user_id, p_prediction_type, p_features, p_predicted_careers, p_confidence_score, p_model_metadata, NOW(), NOW());
                
                SELECT LAST_INSERT_ID() as prediction_id;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_ml_prediction');
        DB::unprepared('
            CREATE PROCEDURE sp_get_ml_prediction(
                IN p_user_id BIGINT,
                IN p_prediction_type VARCHAR(50)
            )
            BEGIN
                SELECT * FROM ml_predictions 
                WHERE user_id = p_user_id 
                AND prediction_type = p_prediction_type
                ORDER BY created_at DESC
                LIMIT 1;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_user_predictions');
        DB::unprepared('
            CREATE PROCEDURE sp_get_all_user_predictions(IN p_user_id BIGINT)
            BEGIN
                SELECT * FROM ml_predictions 
                WHERE user_id = p_user_id
                ORDER BY created_at DESC;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - ESTADÍSTICAS Y REPORTES
        // ==========================================

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_student_report');
        DB::unprepared('
            CREATE PROCEDURE sp_get_student_report(IN p_user_id BIGINT)
            BEGIN
                SELECT 
                    u.id,
                    u.name,
                    u.email,
                    u.grade,
                    u.school,
                    COUNT(DISTINCT tr.id) as total_tests_completed,
                    AVG(tr.total_score) as avg_test_score,
                    GROUP_CONCAT(DISTINCT c.name) as recommended_careers
                FROM users u
                LEFT JOIN test_results tr ON u.id = tr.user_id AND tr.completed_at IS NOT NULL
                LEFT JOIN careers c ON JSON_CONTAINS(tr.recommended_careers, JSON_QUOTE(c.name))
                WHERE u.id = p_user_id
                GROUP BY u.id;
            END
        ');

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_aggregate_statistics');
        DB::unprepared('
            CREATE PROCEDURE sp_get_aggregate_statistics()
            BEGIN
                SELECT 
                    COUNT(DISTINCT u.id) as total_students,
                    COUNT(DISTINCT tr.id) as total_tests_completed,
                    ROUND(AVG(tr.total_score), 2) as avg_score,
                    MAX(tr.total_score) as max_score,
                    MIN(tr.total_score) as min_score,
                    COUNT(DISTINCT u.school) as schools_count,
                    COUNT(DISTINCT u.grade) as grades_count
                FROM users u
                LEFT JOIN test_results tr ON u.id = tr.user_id AND tr.completed_at IS NOT NULL;
            END
        ');
    }

    public function down(): void
    {
        // Eliminar todos los stored procedures de la fase 2
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_interest_tests');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_interest_test_with_questions');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_interest_response');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_interest_scores');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_interest_result');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_interest_result');
        
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_skill_tests');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_skill_test_with_questions');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_skill_response');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_skill_scores');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_skill_result');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_skill_result');
        
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_personality_tests');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_personality_test_with_questions');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_personality_response');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_personality_scores');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_personality_result');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_personality_result');
        
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_academic_grades');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_academic_grades');
        
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_ml_prediction');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_ml_prediction');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_user_predictions');
        
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_student_report');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_aggregate_statistics');
    }
};
