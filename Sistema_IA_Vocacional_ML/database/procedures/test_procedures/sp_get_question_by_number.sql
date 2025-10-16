CREATE PROCEDURE sp_get_question_by_number(IN p_test_id INT, IN p_question_number INT)
BEGIN
    SELECT 
        id,
        test_id,
        question_number,
        question_text,
        type,
        options,
        category
    FROM questions 
    WHERE test_id = p_test_id 
    AND question_number = p_question_number;
END$$