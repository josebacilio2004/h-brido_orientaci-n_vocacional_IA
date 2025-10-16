CREATE PROCEDURE sp_get_popular_careers(IN p_limit INT)
BEGIN
    SELECT 
        c.id,
        c.name,
        c.description,
        c.faculty_id,
        f.name as faculty_name,
        COUNT(tr.id) as recommendation_count
    FROM careers c
    INNER JOIN faculties f ON c.faculty_id = f.id
    LEFT JOIN test_results tr ON JSON_CONTAINS(tr.recommended_careers, JSON_OBJECT('career_id', c.id))
    WHERE c.is_active = 1
    GROUP BY c.id, c.name, c.description, c.faculty_id, f.name
    ORDER BY recommendation_count DESC
    LIMIT p_limit;
END$$