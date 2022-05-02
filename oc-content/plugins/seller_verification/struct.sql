CREATE TABLE /*TABLE_PREFIX*/t_seller_verification (
    pk_i_id INT  NOT NULL AUTO_INCREMENT,
    fk_i_user_id INT UNSIGNED NOT NULL,
	
	b_seller_verification BOOLEAN NULL,
	s_seller_description VARCHAR(255) NOT NULL DEFAULT '',
	
	
           PRIMARY KEY (pk_i_id),
        FOREIGN KEY (fk_i_user_id) REFERENCES /*TABLE_PREFIX*/t_user (pk_i_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';




