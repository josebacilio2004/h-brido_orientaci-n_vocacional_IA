CREATE PROCEDURE sp_get_completed_tests()
BEGIN
    SELECT 
        tr.id,
        tr.user_id,
        tr.scores,
        tr.recommended_careers,
        tr.completed_at
    FROM test_results tr
    WHERE tr.completed_at IS NOT NULL
    ORDER BY tr.completed_at DESC;
END$$