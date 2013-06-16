
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- household
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `household`;

CREATE TABLE `household`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- capteur
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `capteur`;

CREATE TABLE `capteur`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `capteur_name` VARCHAR(128) NOT NULL,
    `household_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `capteur_FI_1` (`household_id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- mesure
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `mesure`;

CREATE TABLE `mesure`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `date` DATE NOT NULL,
    `state` INTEGER NOT NULL,
    `energy` INTEGER NOT NULL,
    `capteur_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `mesure_FI_1` (`capteur_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
