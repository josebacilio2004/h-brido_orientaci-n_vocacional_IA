CREATE PROCEDURE sp_get_recommended_careers(IN p_scores JSON, IN p_limit INT)
BEGIN
    -- Este procedure puede ser más complejo según tu lógica de recomendación
    -- Por ahora devuelve carreras basadas en el perfil RIASEC dominante
    SELECT 
        c.id,
        c.name,
        c.description,
        c.faculty_id,
        f.name as faculty_name,
        c.duration,
        c.modality,
        c.riasec_profile,
        c.demand_level
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    WHERE c.is_active = 1
    ORDER BY RAND() -- Temporal, implementa tu lógica específica
    LIMIT p_limit;
END$$