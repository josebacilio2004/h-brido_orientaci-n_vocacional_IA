CREATE PROCEDURE sp_save_prediction(
    IN p_user_id INT,
    IN p_grades JSON,
    IN p_recommended_career VARCHAR(255),
    IN p_confidence DECIMAL(5,2),
    IN p_top_careers JSON,
    IN p_model_version VARCHAR(50)
)
BEGIN
    INSERT INTO predictions (
        user_id,
        grades,
        recommended_career,
        confidence,
        top_careers,
        model_version,
        created_at
    ) VALUES (
        p_user_id,
        p_grades,
        p_recommended_career,
        p_confidence,
        p_top_careers,
        p_model_version,
        NOW()
    );
    
    SELECT LAST_INSERT_ID() as prediction_id;
END$$