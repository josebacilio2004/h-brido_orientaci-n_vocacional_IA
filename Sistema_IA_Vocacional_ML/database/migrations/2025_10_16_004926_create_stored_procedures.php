<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==========================================
        // STORED PROCEDURES - USUARIOS
        // ==========================================
        
        // SP: Crear usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_create_user');
        DB::unprepared('
            CREATE PROCEDURE sp_create_user(
                IN p_name VARCHAR(255),
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_grade VARCHAR(50),
                IN p_school VARCHAR(255),
                IN p_role VARCHAR(50)
            )
            BEGIN
                INSERT INTO users (name, email, password, grade, school, role, created_at, updated_at)
                VALUES (p_name, p_email, p_password, p_grade, p_school, p_role, NOW(), NOW());
                
                SELECT LAST_INSERT_ID() as user_id;
            END
        ');

        // SP: Obtener usuario por email
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_email');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_by_email(
                IN p_email VARCHAR(255)
            )
            BEGIN
                SELECT * FROM users WHERE email = p_email LIMIT 1;
            END
        ');

        // SP: Obtener usuario por ID
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_id');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_by_id(
                IN p_user_id BIGINT
            )
            BEGIN
                SELECT * FROM users WHERE id = p_user_id LIMIT 1;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - TESTS VOCACIONALES
        // ==========================================
        
        // SP: Obtener tests activos
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_tests');
        DB::unprepared('
            CREATE PROCEDURE sp_get_active_tests()
            BEGIN
                SELECT * FROM vocational_tests WHERE is_active = 1 ORDER BY created_at DESC;
            END
        ');

        // SP: Obtener test por ID con preguntas
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_test_with_questions');
        DB::unprepared('
            CREATE PROCEDURE sp_get_test_with_questions(
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT * FROM vocational_tests WHERE id = p_test_id LIMIT 1;
                
                SELECT * FROM test_questions 
                WHERE vocational_test_id = p_test_id 
                ORDER BY question_number ASC;
            END
        ');

        // SP: Obtener pregunta específica
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_question');
        DB::unprepared('
            CREATE PROCEDURE sp_get_question(
                IN p_test_id BIGINT,
                IN p_question_number INT
            )
            BEGIN
                SELECT * FROM test_questions 
                WHERE vocational_test_id = p_test_id 
                AND question_number = p_question_number 
                LIMIT 1;
            END
        ');

        // SP: Guardar respuesta de test
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_test_response');
        DB::unprepared('
            CREATE PROCEDURE sp_save_test_response(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_question_id BIGINT,
                IN p_answer TEXT,
                IN p_score INT
            )
            BEGIN
                INSERT INTO test_responses (user_id, vocational_test_id, test_question_id, answer, score, created_at, updated_at)
                VALUES (p_user_id, p_test_id, p_question_id, p_answer, p_score, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    answer = p_answer,
                    score = p_score,
                    updated_at = NOW();
                    
                SELECT ROW_COUNT() as affected_rows;
            END
        ');

        // SP: Obtener respuesta previa
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_previous_answer');
        DB::unprepared('
            CREATE PROCEDURE sp_get_previous_answer(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_question_id BIGINT
            )
            BEGIN
                SELECT * FROM test_responses 
                WHERE user_id = p_user_id 
                AND vocational_test_id = p_test_id 
                AND test_question_id = p_question_id 
                LIMIT 1;
            END
        ');

        // SP: Contar respuestas del usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_count_user_responses');
        DB::unprepared('
            CREATE PROCEDURE sp_count_user_responses(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT COUNT(*) as total_responses 
                FROM test_responses 
                WHERE user_id = p_user_id 
                AND vocational_test_id = p_test_id;
            END
        ');

        // SP: Calcular puntajes por categoría
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_category_scores');
        DB::unprepared('
            CREATE PROCEDURE sp_calculate_category_scores(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT 
                    tq.category,
                    SUM(tr.score) as total_score,
                    COUNT(*) as question_count,
                    AVG(tr.score) as avg_score
                FROM test_responses tr
                INNER JOIN test_questions tq ON tr.test_question_id = tq.id
                WHERE tr.user_id = p_user_id 
                AND tr.vocational_test_id = p_test_id
                GROUP BY tq.category
                ORDER BY total_score DESC;
            END
        ');

        // SP: Guardar resultado de test
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_test_result');
        DB::unprepared('
            CREATE PROCEDURE sp_save_test_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT,
                IN p_scores JSON,
                IN p_recommended_careers JSON,
                IN p_analysis TEXT,
                IN p_total_score INT
            )
            BEGIN
                INSERT INTO test_results (
                    user_id, 
                    vocational_test_id, 
                    scores, 
                    recommended_careers, 
                    analysis, 
                    total_score, 
                    completed_at,
                    created_at, 
                    updated_at
                )
                VALUES (
                    p_user_id, 
                    p_test_id, 
                    p_scores, 
                    p_recommended_careers, 
                    p_analysis, 
                    p_total_score, 
                    NOW(),
                    NOW(), 
                    NOW()
                )
                ON DUPLICATE KEY UPDATE 
                    scores = p_scores,
                    recommended_careers = p_recommended_careers,
                    analysis = p_analysis,
                    total_score = p_total_score,
                    completed_at = NOW(),
                    updated_at = NOW();
                    
                SELECT LAST_INSERT_ID() as result_id;
            END
        ');

        // SP: Obtener resultado de test
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_test_result');
        DB::unprepared('
            CREATE PROCEDURE sp_get_test_result(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT tr.*, vt.name as test_name, vt.description as test_description
                FROM test_results tr
                INNER JOIN vocational_tests vt ON tr.vocational_test_id = vt.id
                WHERE tr.user_id = p_user_id 
                AND tr.vocational_test_id = p_test_id
                ORDER BY tr.completed_at DESC
                LIMIT 1;
            END
        ');

        // SP: Obtener tests completados por usuario
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_completed_tests');
        DB::unprepared('
            CREATE PROCEDURE sp_get_user_completed_tests(
                IN p_user_id BIGINT
            )
            BEGIN
                SELECT DISTINCT vocational_test_id
                FROM test_results 
                WHERE user_id = p_user_id 
                AND completed_at IS NOT NULL
                ORDER BY completed_at DESC;
            END
        ');

        // SP: Verificar si test está completado
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_test_completed');
        DB::unprepared('
            CREATE PROCEDURE sp_check_test_completed(
                IN p_user_id BIGINT,
                IN p_test_id BIGINT
            )
            BEGIN
                SELECT COUNT(*) as is_completed
                FROM test_results 
                WHERE user_id = p_user_id 
                AND vocational_test_id = p_test_id
                AND completed_at IS NOT NULL;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - CARRERAS
        // ==========================================
        
        // SP: Obtener todas las carreras
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_careers');
        DB::unprepared('
            CREATE PROCEDURE sp_get_all_careers()
            BEGIN
                SELECT * FROM careers ORDER BY faculty, name;
            END
        ');

        // SP: Obtener carreras por facultad
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_careers_by_faculty');
        DB::unprepared('
            CREATE PROCEDURE sp_get_careers_by_faculty(
                IN p_faculty VARCHAR(255)
            )
            BEGIN
                SELECT * FROM careers 
                WHERE faculty = p_faculty 
                ORDER BY name;
            END
        ');

        // SP: Obtener carreras por perfil RIASEC
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_careers_by_riasec');
        DB::unprepared('
            CREATE PROCEDURE sp_get_careers_by_riasec(
                IN p_riasec_profile VARCHAR(50)
            )
            BEGIN
                SELECT * FROM careers 
                WHERE riasec_profile = p_riasec_profile 
                ORDER BY name;
            END
        ');

        // SP: Buscar carreras
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_search_careers');
        DB::unprepared('
            CREATE PROCEDURE sp_search_careers(
                IN p_search_term VARCHAR(255)
            )
            BEGIN
                SELECT * FROM careers 
                WHERE name LIKE CONCAT("%", p_search_term, "%")
                OR description LIKE CONCAT("%", p_search_term, "%")
                OR faculty LIKE CONCAT("%", p_search_term, "%")
                ORDER BY name;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - CLUSTERING
        // ==========================================
        
        // SP: Obtener datos para clustering
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_clustering_data');
        DB::unprepared('
            CREATE PROCEDURE sp_get_clustering_data()
            BEGIN
                SELECT 
                    tr.id,
                    tr.user_id,
                    tr.vocational_test_id,
                    tr.scores,
                    tr.recommended_careers,
                    tr.total_score,
                    tr.completed_at,
                    u.name as user_name,
                    u.grade,
                    u.school
                FROM test_results tr
                INNER JOIN users u ON tr.user_id = u.id
                WHERE tr.completed_at IS NOT NULL
                ORDER BY tr.completed_at DESC;
            END
        ');

        // SP: Obtener estadísticas generales
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_general_statistics');
        DB::unprepared('
            CREATE PROCEDURE sp_get_general_statistics()
            BEGIN
                SELECT 
                    COUNT(DISTINCT user_id) as total_users,
                    COUNT(*) as total_tests_completed,
                    AVG(total_score) as avg_total_score,
                    MAX(total_score) as max_score,
                    MIN(total_score) as min_score
                FROM test_results
                WHERE completed_at IS NOT NULL;
            END
        ');

        // ==========================================
        // STORED PROCEDURES - NOTAS ACADÉMICAS
        // ==========================================
        
        // SP: Guardar notas académicas
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_student_grades');
        DB::unprepared('
            CREATE PROCEDURE sp_save_student_grades(
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
                    user_id,
                    nota_matematica,
                    nota_comunicacion,
                    nota_ciencias_sociales,
                    nota_ciencia_tecnologia,
                    nota_desarrollo_personal,
                    nota_ciudadania_civica,
                    nota_educacion_fisica,
                    nota_ingles,
                    nota_educacion_trabajo,
                    academic_year,
                    created_at,
                    updated_at
                )
                VALUES (
                    p_user_id,
                    p_nota_matematica,
                    p_nota_comunicacion,
                    p_nota_ciencias_sociales,
                    p_nota_ciencia_tecnologia,
                    p_nota_desarrollo_personal,
                    p_nota_ciudadania_civica,
                    p_nota_educacion_fisica,
                    p_nota_ingles,
                    p_nota_educacion_trabajo,
                    p_academic_year,
                    NOW(),
                    NOW()
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
                    
                SELECT LAST_INSERT_ID() as grade_id;
            END
        ');

        // SP: Obtener notas académicas
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_student_grades');
        DB::unprepared('
            CREATE PROCEDURE sp_get_student_grades(
                IN p_user_id BIGINT
            )
            BEGIN
                SELECT * FROM student_grades 
                WHERE user_id = p_user_id 
                ORDER BY academic_year DESC 
                LIMIT 1;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar todos los stored procedures
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_create_user');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_email');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_by_id');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_active_tests');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_test_with_questions');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_question');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_test_response');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_previous_answer');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_count_user_responses');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_calculate_category_scores');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_test_result');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_test_result');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_user_completed_tests');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_check_test_completed');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_all_careers');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_careers_by_faculty');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_careers_by_riasec');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_search_careers');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_clustering_data');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_general_statistics');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_save_student_grades');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_student_grades');
    }
};
