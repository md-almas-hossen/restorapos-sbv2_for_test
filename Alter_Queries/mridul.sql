-- 07 OCT, 2024
ALTER TABLE `order_payment_tbl` 
ADD `total_amount` FLOAT(10.2) NULL AFTER `status`,
ADD `discount_type` TINYINT NULL COMMENT '1: Percentage, 2: Flat' AFTER `total_amount`,
ADD `discount_amount` FLOAT(10.2) NULL AFTER `discount_type`;

-- 05 October, 2024
ALTER TABLE `table_details` CHANGE `total_people` `total_people` INT NULL;

-- 19 September, 2024
UPDATE `language` SET `english` = 'Items Name' WHERE `language`.`phrase` = 'ItemName';

