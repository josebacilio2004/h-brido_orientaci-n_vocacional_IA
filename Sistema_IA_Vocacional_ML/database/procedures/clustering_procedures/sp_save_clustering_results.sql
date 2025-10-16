CREATE PROCEDURE sp_save_clustering_results(IN p_cluster_data JSON)
BEGIN
    INSERT INTO clustering_analysis (cluster_data, analyzed_at, created_at)
    VALUES (p_cluster_data, NOW(), NOW());
END$$