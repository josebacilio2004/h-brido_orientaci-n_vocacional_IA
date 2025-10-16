CREATE PROCEDURE sp_get_user_average(IN p_user_id INT)
BEGIN
    SELECT 
        AVG((nota_matematica + nota_comunicacion + nota_ciencias_sociales + 
             nota_ciencia_tecnologia + nota_desarrollo_personal + nota_ciudadania_civica +
             nota_educacion_fisica + nota_ingles + nota_educacion_trabajo) / 9) as average_grade
    FROM user_grades 
    WHERE user_id = p_user_id;
END$$