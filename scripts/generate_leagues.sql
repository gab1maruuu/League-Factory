DELIMITER //

DROP PROCEDURE IF EXISTS GenerarLigasMasivas //

CREATE PROCEDURE GenerarLigasMasivas(IN semanas_pasadas INT, IN semanas_futuras INT)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE fecha_iteracion DATE;
    DECLARE admin_id INT;
    
    -- Obtener ID de un usuario para asignar como creador (si no hay, usar 1 o salir)
    SELECT id INTO admin_id FROM usuarios LIMIT 1;
    IF admin_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No hay usuarios en la tabla usuarios';
    END IF;

    -- Generar hacia el PASADO (empezando desde el domingo anterior más lejano)
    -- Calculamos el domingo de hace 'semanas_pasadas' semanas.
    -- WEEKDAY(NOW()): 0=Monday... 6=Sunday. 
    -- Queremos alinearnos al domingo.
    SET i = -semanas_pasadas;
    
    WHILE i <= semanas_futuras DO
        -- Calcular fecha base del domingo de la semana 'i' relativa a hoy
        -- DATE_ADD(NOW(), INTERVAL i WEEK) -> nos mueve en semanas
        -- Ajuste para caer en domingo si hoy no lo es puede ser complejo, simplificamos:
        -- Asignamos que la fecha de creación sea DATE(NOW() + INTERVAL i WEEK).
        -- Si el usuario quiere ESTRICTAMENTE domingos, deberíamos ajustar la fecha base.
        -- Vamos a asumir que "se crean cada 7 días" partiendo de una fecha base (ej: hoy o el último domingo).
        
        -- Vamos a alinear al ÚLTIMO DOMINGO
        SET fecha_iteracion = DATE_SUB(DATE(NOW()), INTERVAL WEEKDAY(NOW()) + 1 DAY); -- Esto da el Sábado pasado. +1 si queremos Domingo?
        -- WEEKDAY: Mon=0, Sun=6. 
        -- DATE_SUB(NOW, INTERVAL (WEEKDAY(NOW()) + 1) % 7 DAY) -> Si es domingo(6), resta 0 (6+1=7%7=0). Correcto.
        
        -- Ajustemos a Domingo:
        -- Si hoy es Jueves (3). Domingo fue hace 4 días.
        
        SET fecha_iteracion = DATE_ADD(DATE(NOW()), INTERVAL (6 - WEEKDAY(NOW())) DAY); -- Esto nos lleva al PRÓXIMO Domingo (o hoy si es domingo).
        -- Mejor partamos de HOY y sumemos semanas. "Aparezcan cada domingo".
        -- Vamos a forzar que la fecha base sea el Domingo de la semana 'i'.
        
        SET fecha_iteracion = DATE_ADD(DATE(NOW()), INTERVAL i WEEK); 
        -- Ajustar al domingo de esa semana
        SET fecha_iteracion = DATE_ADD(fecha_iteracion, INTERVAL (6 - WEEKDAY(fecha_iteracion)) DAY);
        
        -- -----------------------
        -- Insertar Ligas
        -- -----------------------
        
        -- Estado: Si la fecha ya pasó hace más de 7 días -> Finalizada
        -- Si está en los últimos 7 días -> En curso (abierta a inscripción o jugando)
        -- Si es futuro -> Abierta
        
        INSERT INTO ligas (nombre, descripcion, deporte, temporada, estado, creado_por, fecha_creacion)
        VALUES 
        ('Liga Senior', CONCAT('Edición semana ', DATE_FORMAT(fecha_iteracion, '%d/%m/%Y')), 'Fútbol 11', YEAR(fecha_iteracion), 
            CASE 
                WHEN fecha_iteracion < DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'finalizada'
                WHEN fecha_iteracion <= NOW() AND fecha_iteracion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'en_curso'
                ELSE 'abierta'
            END, 
            admin_id, fecha_iteracion),
            
        ('Liga Mahou', CONCAT('Torneo Mahou ', DATE_FORMAT(fecha_iteracion, '%d/%m/%Y')), 'Fútbol 7', YEAR(fecha_iteracion), 
            CASE 
                WHEN fecha_iteracion < DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'finalizada'
                WHEN fecha_iteracion <= NOW() AND fecha_iteracion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'en_curso'
                ELSE 'abierta'
            END, 
            admin_id, fecha_iteracion),
            
        ('Liga Hypermotion', CONCAT('Segunda División ', DATE_FORMAT(fecha_iteracion, '%d/%m/%Y')), 'Fútbol 11', YEAR(fecha_iteracion), 
            CASE 
                WHEN fecha_iteracion < DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'finalizada'
                WHEN fecha_iteracion <= NOW() AND fecha_iteracion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'en_curso'
                ELSE 'abierta'
            END, 
            admin_id, fecha_iteracion),
            
        ('LaLiga', CONCAT('Primera División ', DATE_FORMAT(fecha_iteracion, '%d/%m/%Y')), 'Fútbol 11', YEAR(fecha_iteracion), 
            CASE 
                WHEN fecha_iteracion < DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'finalizada'
                WHEN fecha_iteracion <= NOW() AND fecha_iteracion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'en_curso'
                ELSE 'abierta'
            END, 
            admin_id, fecha_iteracion);

        SET i = i + 1;
    END WHILE;
END //

DELIMITER ;

-- Ejecutar para generar historial y futuro
-- Generar 10 semanas hacia atrás y 10 hacia adelante
CALL GenerarLigasMasivas(10, 10);
