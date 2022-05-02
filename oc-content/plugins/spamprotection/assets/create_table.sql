CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_items` (
  `pk_i_id` int(10) NOT NULL AUTO_INCREMENT,
  `fk_i_item_id` int(10) DEFAULT NULL,
  `fk_i_user_id` int(10) DEFAULT NULL,
  `s_reason` text,
  `s_user_mail` varchar(100) DEFAULT NULL,
  `dt_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_comments` (
  `pk_i_id` int(10) NOT NULL AUTO_INCREMENT,
  `fk_i_comment_id` int(10) DEFAULT NULL,
  `fk_i_item_id` int(10) DEFAULT NULL,
  `fk_i_user_id` int(10) DEFAULT NULL,
  `s_reason` text,
  `s_user_mail` varchar(100) DEFAULT NULL,
  `dt_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_contacts` (
  `pk_i_id` int(10) NOT NULL AUTO_INCREMENT,
  `fk_i_item_id` int(10) DEFAULT NULL,
  `s_user` varchar(100) DEFAULT NULL,
  `fk_i_user_id` INT(10) NULL DEFAULT NULL,
  `s_user_mail` varchar(100) DEFAULT NULL,
  `s_user_phone` varchar(100) DEFAULT NULL,
  `s_user_message` text DEFAULT NULL,
  `s_reason` text DEFAULT NULL,
  `s_token` varchar(13) DEFAULT NULL,
  `dt_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_logins` (
  `pk_i_id` int(10) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(100) DEFAULT NULL,
  `s_email` varchar(100) DEFAULT NULL,
  `s_ip` varchar(30) DEFAULT NULL,
  `s_type` varchar(5) NOT NULL DEFAULT 'user',
  `dt_date_login` int(11) DEFAULT NULL,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_ban_log` ( 
  `pk_i_id` INT(10) NOT NULL AUTO_INCREMENT,
  `i_user_id` int(10) DEFAULT NULL,
  `s_user_email` varchar(100) DEFAULT NULL,
  `s_user_ip` varchar(50) DEFAULT NULL,
  `s_reason` text DEFAULT NULL,
  `dt_date_banned` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_users` ( 
  `pk_i_id` int(10) NOT NULL,
  `i_reputation` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `s_reputation` text, 
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `/*TABLE_PREFIX*/t_spam_protection_global_log` (
  `pk_i_id` INT NOT NULL AUTO_INCREMENT,
  `s_reason` TEXT NULL DEFAULT NULL,
  `s_account` VARCHAR(255) NULL DEFAULT NULL,
  `s_done` VARCHAR(255) NULL DEFAULT NULL,
  `dt_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_i_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;