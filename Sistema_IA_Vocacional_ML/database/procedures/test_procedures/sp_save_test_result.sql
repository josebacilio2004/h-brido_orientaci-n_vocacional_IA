CREATE PROCEDURE sp_save_test_result(
    IN p_user_id INT,
    IN p_test_id INT,
    IN p_scores JSON,
    IN p_recommended_careers JSON,
    IN p_analysis TEXT,
    IN p_total_score INT
)
BEGIN
    INSERT INTO test_results (
        user_id,
        test_id,
        scores,
        recommended_careers,
        analysis,
        total_score,
        completed_at,
        created_at
    ) VALUES (
        p_user_id,
        p_test_id,
        p_scores,
        p_recommended_careers,
        p_analysis,
        p_total_score,
        NOW(),
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        scores = VALUES(scores),
        recommended_careers = VALUES(recommended_careers),
        analysis = VALUES(analysis),
        total_score = VALUES(total_score),
        completed_at = VALUES(completed_at);
    
    SELECT LAST_INSERT_ID() as result_id;
END$$