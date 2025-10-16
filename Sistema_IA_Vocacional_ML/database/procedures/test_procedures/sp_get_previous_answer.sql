CREATE PROCEDURE sp_get_previous_answer(
    IN p_user_id INT,
    IN p_test_id INT,
    IN p_question_id INT
)
BEGIN
    SELECT 
        id,
        user_id,
        test_id,
        question_id,
        answer,
        score
    FROM test_responses 
    WHERE user_id = p_user_id 
    AND test_id = p_test_id 
    AND question_id = p_question_id;
END$$