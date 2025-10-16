CREATE PROCEDURE sp_get_user_grades(IN p_user_id INT)
BEGIN
    SELECT 
        id,
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
    FROM user_grades 
    WHERE user_id = p_user_id
    ORDER BY academic_year DESC
    LIMIT 1;
END$$