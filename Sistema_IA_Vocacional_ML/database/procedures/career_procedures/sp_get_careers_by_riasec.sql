CREATE PROCEDURE sp_get_careers_by_riasec(IN p_riasec_profile VARCHAR(50))
BEGIN
    SELECT 
        c.id,
        c.name,
        c.description,
        c.faculty_id,
        f.name as faculty_name,
        c.duration,
        c.modality,
        c.riasec_profile
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    WHERE c.riasec_profile = p_riasec_profile
    AND c.is_active = 1
    ORDER BY c.name;
END$$