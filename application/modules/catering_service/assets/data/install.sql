
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'info', 'Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'catering_service', 'Catering Service');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'catering_order_list', 'Catering Order List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'catering_dashboard', 'Catering Dashboard');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'submit_&_print', 'Submit & Print');

INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES ('catering_service', 'cateringservice', 'catering_service', '0', '0', '2', '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) SELECT 'catering_dashboard', 'catering_dashboard', 'catering_service', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'catering_service';
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) SELECT 'catering_order_list', 'catering_orderlist', 'catering_service', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'catering_service';
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) SELECT 'add_catering_package', 'add_catering_package', 'catering_service', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'catering_service';
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) SELECT 'catering_package_list', 'catering_package_list', 'catering_service', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'catering_service';
INSERT INTO `sec_menu_item` (`menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) SELECT 'catering_report', 'Report', 'catering_service', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'catering_service';



INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'create_package', 'Create Package');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'package_name', 'Package Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'max_item', 'Max Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'package_price', 'Package Price');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'update_package', 'Edit Package');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'add_catering_package', 'Add Catering Package');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'catering_package_list', 'Catering Package List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL,'catering_report','Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL,'catering_sell_report','Catering Sales Report');

ALTER TABLE `item_foods` ADD `is_packagestatus` INT NULL DEFAULT '0' COMMENT '0 = is_packagestatus (food) 1 = is_packagestatus (package) ' AFTER `ismainbr`;


/* first time insert this alert query in you database */
-- INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES (NULL, 'Catering Service', '0');

-- INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES (NULL, 'Categring Service', 'categring_service', 'categring_service', '0', '0', '1', '2023-08-20 09:51:58.000000');
-- INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES (NULL, 'Catering Order list ', 'catering_orderlist', 'categring_service', '1606', '0', '1', '2023-08-20 10:04:16.000000');

-- ALTER TABLE order_menu ADD category_id INT NULL DEFAULT NULL AFTER allfoodready;

-- ALTER TABLE bill ADD deliverycharge DECIMAL(10,2) NULL DEFAULT NULL AFTER service_charge;
-- ALTER TABLE customer_order ADD delivaryaddress VARCHAR(255) NULL DEFAULT NULL AFTER shipping_date;
-- ALTER TABLE customer_order ADD person INT NULL DEFAULT NULL AFTER delivaryaddress;