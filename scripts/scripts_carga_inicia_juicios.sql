CREATE TABLE `servicobranza`.`juicios_dato_inicial` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_juicio` INT NULL,
  `tipo_juicio` VARCHAR(45) NULL,
  `rut` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_juicio_UNIQUE` (`id_juicio` ASC),
  INDEX `IX_id_juicio_tipo_juicio` (`id_juicio` ASC, `tipo_juicio` ASC));
)

USE `servicobranza`;
DROP procedure IF EXISTS `actualizar_juicios_dato_inicial`;

DELIMITER $$
USE `servicobranza`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_juicios_dato_inicial`(
var_id_juicio INT,
var_tipo_juicio VARCHAR(45),
var_rut VARCHAR(45)
)
BEGIN

DECLARE var_id INT;

SELECT var_id=id FROM juicios_dato_inicial WHERE id_juicio = var_id_juicio AND tipo_juicio = var_tipo_juicio;
	
IF (var_id = 0) THEN
	BEGIN
		INSERT INTO juicios_dato_inicial (id_juicio, tipo_juicio, rut) VALUES (var_id_juicio, var_tipo_juicio, var_rut);
	END;
	ELSE
	BEGIN
		UPDATE juicios_dato_inicial 
        SET tipo_juicio = var_tipo_juicio, 
        rut = var_rut
        WHERE id = var_id;
	END;
END IF;
END
END$$

DELIMITER ;

