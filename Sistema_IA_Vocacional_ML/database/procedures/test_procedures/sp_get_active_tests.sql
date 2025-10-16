CREATE PROCEDURE sp_get_active_tests()
BEGIN
    SELECT 
        id,
        name,
        description,
        total_questions,
        time_limit,
        is_active,
        created_at
    FROM tests 
    WHERE is_active = 1
    ORDER BY name;
END$$