CREATE PROCEDURE sp_calculate_category_scores(IN p_user_id INT, IN p_test_id INT)
BEGIN
    SELECT 
        q.category,
        SUM(tr.score) as total_score
    FROM test_responses tr
    INNER JOIN questions q ON tr.question_id = q.id
    WHERE tr.user_id = p_user_id 
    AND tr.test_id = p_test_id
    GROUP BY q.category
    ORDER BY total_score DESC;
END$$