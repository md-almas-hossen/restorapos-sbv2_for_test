CREATE TABLE `messenger_settings` (
    id INT auto_increment NOT NULL,
    conf_key varchar(255) NOT NULL,
    conf_value text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;