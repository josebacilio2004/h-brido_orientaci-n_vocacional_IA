CREATE PROCEDURE sp_get_user_latest_result(IN p_user_id INT)
BEGIN
    SELECT 
        tr.id,
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
    AND tr.completed_at IS NOT NULL
    ORDER BY tr.completed_at DESC
    LIMIT 1;
END$$