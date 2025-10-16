CREATE PROCEDURE sp_get_test_result(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT 
        tr.id,
        tr.user_id,
        tr.test_id,
        t.name as test_name,
        tr.scores,
        tr.recommended_careers,
        tr.analysis,
        tr.total_score,
        tr.completed_at
    FROM test_results tr
    INNER JOIN tests t ON tr.test_id = t.id
    WHERE tr.user_id = p_user_id 
    AND tr.test_id = p_test_id;
END$$