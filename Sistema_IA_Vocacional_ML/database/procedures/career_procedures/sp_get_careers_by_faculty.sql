CREATE PROCEDURE sp_get_careers_by_faculty(IN p_faculty_name VARCHAR(255))
BEGIN
    SELECT 
        c.id,
        c.name,
        c.description,
        c.faculty_id,
        f.name as faculty_name,
        c.duration,
        c.modality
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    WHERE f.name LIKE CONCAT('%', p_faculty_name, '%')
    AND c.is_active = 1
    ORDER BY c.name;
END$$