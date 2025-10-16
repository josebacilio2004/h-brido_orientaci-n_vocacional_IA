CREATE PROCEDURE sp_get_test_by_id(IN p_test_id INT)
BEGIN
    SELECT 
        id,
        name,
        description,
        total_questions,
        time_limit,
        is_active
    FROM tests 
    WHERE id = p_test_id 
    AND is_active = 1;
END$$