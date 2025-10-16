CREATE PROCEDURE sp_save_test_response(
    IN p_user_id INT,
    IN p_test_id INT,
    IN p_question_id INT,
    IN p_answer TEXT,
    IN p_score INT
)
BEGIN
    INSERT INTO test_responses (
        user_id,
        test_id,
        question_id,
        answer,
        score,
        answered_at
    ) VALUES (
        p_user_id,
        p_test_id,
        p_question_id,
        p_answer,
        p_score,
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        answer = VALUES(answer),
        score = VALUES(score),
        answered_at = VALUES(answered_at);
    
    SELECT ROW_COUNT() as affected_rows;
END$$