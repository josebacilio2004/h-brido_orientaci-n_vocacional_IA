CREATE PROCEDURE sp_delete_user_result(IN p_user_id INT, IN p_test_id INT)
BEGIN
    DELETE FROM test_results 
    WHERE user_id = p_user_id 
    AND test_id = p_test_id;
END$$