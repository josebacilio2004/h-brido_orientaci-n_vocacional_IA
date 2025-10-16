CREATE PROCEDURE sp_get_clustering_history()
BEGIN
    SELECT 
        id,
        cluster_data,
        analyzed_at,
        created_at
    FROM clustering_analysis 
    ORDER BY analyzed_at DESC;
END$$