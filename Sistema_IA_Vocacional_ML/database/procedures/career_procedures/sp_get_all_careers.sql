CREATE PROCEDURE sp_get_all_careers()
BEGIN
    SELECT 
        c.id,
        c.name,
        c.description,
        c.faculty_id,
        f.name as faculty_name,
        c.duration,
        c.modality,
        c.job_opportunities,
        c.riasec_profile,
        c.salary_range,
        c.demand_level
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    WHERE c.is_active = 1
    ORDER BY c.name;
END$$