CREATE PROCEDURE sp_count_user_responses(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT COUNT(*) as total_responses
    FROM test_responses 
    WHERE user_id = p_user_id 
    AND test_id = p_test_id;
END$$