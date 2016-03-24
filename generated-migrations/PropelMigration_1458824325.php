<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1458824325.
 * Generated on 2016-03-24 13:58:45 
 */
class PropelMigration_1458824325
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `account`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `account_id` INTEGER NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `roles` TEXT NOT NULL,
    `salt` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `recovery_token` VARCHAR(32),
    `recovery_date` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `user_u_afde13` (`email`, `username`),
    INDEX `user_fi_474870` (`account_id`),
    CONSTRAINT `user_fk_474870`
        FOREIGN KEY (`account_id`)
        REFERENCES `account` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `domain`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `account_id` INTEGER NOT NULL,
    `uri` VARCHAR(255) NOT NULL,
    `status` TINYINT DEFAULT 0 NOT NULL,
    `is_enabled` TINYINT(1) DEFAULT 0 NOT NULL,
    `last_checked` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `domain_u_2ffc13` (`account_id`, `uri`),
    CONSTRAINT `domain_fk_474870`
        FOREIGN KEY (`account_id`)
        REFERENCES `account` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `users_domain`
(
    `user_id` INTEGER NOT NULL,
    `domain_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`domain_id`),
    INDEX `users_domain_fi_056961` (`domain_id`),
    CONSTRAINT `users_domain_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `users_domain_fk_056961`
        FOREIGN KEY (`domain_id`)
        REFERENCES `domain` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `test`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `domain_id` INTEGER NOT NULL,
    `test_type` TINYINT NOT NULL,
    `status` TINYINT(1) NOT NULL,
    `last_checked` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `test_fi_056961` (`domain_id`),
    CONSTRAINT `test_fk_056961`
        FOREIGN KEY (`domain_id`)
        REFERENCES `domain` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `account`;

DROP TABLE IF EXISTS `user`;

DROP TABLE IF EXISTS `domain`;

DROP TABLE IF EXISTS `users_domain`;

DROP TABLE IF EXISTS `test`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}