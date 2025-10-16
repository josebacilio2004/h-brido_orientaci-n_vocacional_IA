CREATE PROCEDURE sp_get_clustering_data()
BEGIN
    SELECT 
        tr.id,
        tr.user_id,
        u.name as user_name,
        tr.scores,
        tr.recommended_careers,
        tr.completed_at
    FROM test_results tr
    INNER JOIN users u ON tr.user_id = u.id
    WHERE tr.completed_at IS NOT NULL
    ORDER BY tr.completed_at DESC;
END$$