CREATE PROCEDURE sp_get_grade_statistics(IN p_user_id INT)
BEGIN
    SELECT 
        MAX(nota_matematica) as max_matematica,
        MAX(nota_comunicacion) as max_comunicacion,
        AVG(nota_matematica) as avg_matematica,
        AVG(nota_comunicacion) as avg_comunicacion,
        COUNT(*) as total_records
    FROM user_grades 
    WHERE user_id = p_user_id;
END$$