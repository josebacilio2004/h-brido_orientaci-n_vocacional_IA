CREATE PROCEDURE sp_get_career_statistics()
BEGIN
    SELECT 
        COUNT(*) as total_careers,
        COUNT(DISTINCT faculty_id) as total_faculties,
        AVG(duration) as avg_duration,
        MAX(demand_level) as max_demand
    FROM careers 
    WHERE is_active = 1;
END$$