CREATE PROCEDURE sp_get_latest_prediction(IN p_user_id INT)
BEGIN
    SELECT 
        id,
        user_id,
        grades,
        recommended_career,
        confidence,
        top_careers,
        model_version,
        created_at
    FROM predictions 
    WHERE user_id = p_user_id 
    ORDER BY created_at DESC 
    LIMIT 1;
END$$