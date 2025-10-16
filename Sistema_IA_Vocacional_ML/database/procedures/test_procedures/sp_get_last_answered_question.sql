CREATE PROCEDURE sp_get_last_answered_question(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT COALESCE(MAX(q.question_number), 0) as last_question_number
    FROM test_responses tr
    INNER JOIN questions q ON tr.question_id = q.id
    WHERE tr.user_id = p_user_id 
    AND tr.test_id = p_test_id;
END$$