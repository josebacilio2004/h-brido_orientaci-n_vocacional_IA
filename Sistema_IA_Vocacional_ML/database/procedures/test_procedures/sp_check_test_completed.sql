CREATE PROCEDURE sp_check_test_completed(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT COUNT(*) as is_completed
    FROM test_results 
    WHERE user_id = p_user_id 
    AND test_id = p_test_id 
    AND completed_at IS NOT NULL;
END$$