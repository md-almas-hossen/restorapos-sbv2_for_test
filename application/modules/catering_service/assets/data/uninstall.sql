DELETE FROM `sec_menu_item` WHERE `sec_menu_item`.`module` = 'catering_service';
DELETE FROM `language` WHERE `language`.`phrase` = 'info';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_service';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_order_list';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_dashboard';
DELETE FROM `language` WHERE `language`.`phrase` = 'create_package';
DELETE FROM `language` WHERE `language`.`phrase` = 'package_name';
DELETE FROM `language` WHERE `language`.`phrase` = 'max_item';
DELETE FROM `language` WHERE `language`.`phrase` = 'package_price';
DELETE FROM `language` WHERE `language`.`phrase` = 'update_package';
DELETE FROM `language` WHERE `language`.`phrase` = 'add_catering_package';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_package_list';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_sell_report';
DELETE FROM `language` WHERE `language`.`phrase` = 'catering_report';
ALTER TABLE `item_foods` DROP `is_packagestatus`;

