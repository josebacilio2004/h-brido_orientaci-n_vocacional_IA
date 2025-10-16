CREATE PROCEDURE sp_get_all_faculties()
BEGIN
    SELECT 
        id,
        name,
        description,
        dean_name,
        contact_email,
        building_location
    FROM faculties
    WHERE is_active = 1
    ORDER BY name;
END$$