CREATE PROCEDURE sp_save_or_update_grades(
    IN p_user_id INT,
    IN p_nota_matematica INT,
    IN p_nota_comunicacion INT,
    IN p_nota_ciencias_sociales INT,
    IN p_nota_ciencia_tecnologia INT,
    IN p_nota_desarrollo_personal INT,
    IN p_nota_ciudadania_civica INT,
    IN p_nota_educacion_fisica INT,
    IN p_nota_ingles INT,
    IN p_nota_educacion_trabajo INT
)
BEGIN
    INSERT INTO user_grades (
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
    ) VALUES (
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
        YEAR(NOW()),
        NOW(),
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        nota_matematica = VALUES(nota_matematica),
        nota_comunicacion = VALUES(nota_comunicacion),
        nota_ciencias_sociales = VALUES(nota_ciencias_sociales),
        nota_ciencia_tecnologia = VALUES(nota_ciencia_tecnologia),
        nota_desarrollo_personal = VALUES(nota_desarrollo_personal),
        nota_ciudadania_civica = VALUES(nota_ciudadania_civica),
        nota_educacion_fisica = VALUES(nota_educacion_fisica),
        nota_ingles = VALUES(nota_ingles),
        nota_educacion_trabajo = VALUES(nota_educacion_trabajo),
        updated_at = VALUES(updated_at);
    
    SELECT * FROM user_grades WHERE user_id = p_user_id;
END$$