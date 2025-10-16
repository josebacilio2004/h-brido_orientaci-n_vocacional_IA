
CREATE PROCEDURE sp_get_general_statistics()
BEGIN
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM test_results WHERE completed_at IS NOT NULL) as completed_tests,
        (SELECT COUNT(*) FROM careers WHERE is_active = 1) as total_careers,
        (SELECT COUNT(DISTINCT faculty_id) FROM careers) as total_faculties;
END$$