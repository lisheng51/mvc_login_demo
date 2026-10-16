-- Database schema for mvc_login_demo (MySQL / MariaDB)

CREATE TABLE IF NOT EXISTS `user_login` (
    `uid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `hash` CHAR(64) NULL DEFAULT NULL,
    PRIMARY KEY (`uid`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- group: 1 = Member, 2 = Admin
CREATE TABLE IF NOT EXISTS `users` (
    `uid` INT UNSIGNED NOT NULL,
    `firstname` VARCHAR(100) NOT NULL DEFAULT '',
    `surname` VARCHAR(100) NOT NULL DEFAULT '',
    `email` VARCHAR(255) NOT NULL DEFAULT '',
    `gender` VARCHAR(10) NOT NULL DEFAULT '',
    `group` TINYINT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`uid`),
    CONSTRAINT `fk_users_login` FOREIGN KEY (`uid`) REFERENCES `user_login` (`uid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
