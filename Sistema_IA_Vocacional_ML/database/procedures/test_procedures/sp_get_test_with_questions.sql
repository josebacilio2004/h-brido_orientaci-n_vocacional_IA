CREATE PROCEDURE sp_get_test_with_questions(IN p_test_id INT)
BEGIN
    -- Primero obtener información del test
    SELECT 
        t.id,
        t.name,
        t.description,
        t.total_questions,
        t.time_limit,
        COUNT(q.id) as actual_questions
    FROM tests t
    LEFT JOIN questions q ON t.id = q.test_id
    WHERE t.id = p_test_id 
    AND t.is_active = 1
    GROUP BY t.id, t.name, t.description, t.total_questions, t.time_limit;
    
    -- Luego obtener todas las preguntas
    SELECT 
        id,
        test_id,
        question_number,
        question_text,
        type,
        options,
        category
    FROM questions 
    WHERE test_id = p_test_id 
    ORDER BY question_number;
END$$