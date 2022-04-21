
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- cart_update
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cart_update`;

CREATE TABLE `cart_update`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `code_promo_changed` TINYINT(1) DEFAULT 0 NOT NULL,
    `price_changed` TINYINT(1) DEFAULT 0 NOT NULL,
    `cart_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fi_cart_id` (`cart_id`),
    CONSTRAINT `fk_cart_id`
        FOREIGN KEY (`cart_id`)
        REFERENCES `cart` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
