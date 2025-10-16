CREATE PROCEDURE sp_get_career_details(IN p_career_id INT)
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
        c.demand_level,
        c.required_skills,
        c.curriculum,
        c.contact_info
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    WHERE c.id = p_career_id;
END$$