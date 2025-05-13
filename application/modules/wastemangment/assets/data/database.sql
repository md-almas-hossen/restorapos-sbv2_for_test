CREATE TABLE `packaging_food_waste` (
  `id` int(10)  NOT NULL AUTO_INCREMENT,
  `order_id` int(10)  NOT NULL,
  `ingradient_id` INT(6) NOT NULL,
  `qnty` INT(6) NOT NULL,
  `l_price` INT(11) NOT NULL,
  `note` VARCHAR(255) NULL DEFAULT NULL,
  `createdby` INT(7) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `ingradient_food_waste` (
  `id` int(10)  NOT NULL AUTO_INCREMENT,
  `ingradient_id` INT(6) NOT NULL,
  `check_by` INT(6) NOT NULL,
  `qnty` DECIMAL(10,3) NOT NULL,
  `l_price` DECIMAL(10,4) NOT NULL,
  `note` VARCHAR(255) NULL DEFAULT NULL,
  `createdby` INT(7) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `items_food_waste` (
  `id` int(10)  NOT NULL AUTO_INCREMENT,
  `itms_id` INT(6) NOT NULL,
  `wvarientid` int(11) NOT NULL,
  `check_by` INT(6) NOT NULL,
  `qnty` DECIMAL(10,3) NOT NULL,
  `l_price` DECIMAL(10,4) NOT NULL,
  `note` VARCHAR(255) NULL DEFAULT NULL,
  `createdby` INT(7) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

