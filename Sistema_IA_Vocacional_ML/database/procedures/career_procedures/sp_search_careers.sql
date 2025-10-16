CREATE PROCEDURE sp_search_careers(IN p_search_term VARCHAR(255))
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
    WHERE c.name LIKE CONCAT('%', p_search_term, '%')
    OR c.description LIKE CONCAT('%', p_search_term, '%')
    OR f.name LIKE CONCAT('%', p_search_term, '%')
    AND c.is_active = 1
    ORDER BY c.name;
END$$