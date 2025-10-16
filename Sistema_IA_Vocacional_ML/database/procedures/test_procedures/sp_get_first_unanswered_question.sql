CREATE PROCEDURE sp_get_first_unanswered_question(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT q.question_number, q.id as question_id
    FROM questions q
    LEFT JOIN test_responses tr ON q.id = tr.question_id AND tr.user_id = p_user_id
    WHERE q.test_id = p_test_id
    AND tr.id IS NULL
    ORDER BY q.question_number
    LIMIT 1;
END$$