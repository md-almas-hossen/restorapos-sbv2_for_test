-- Queries on 06-10-2024 --

ALTER TABLE `supplier` ADD `acc_subcode_id` INT NOT NULL COMMENT 'acc_subcode primary_key' AFTER `supAddress`;

-- Queries on 07-10-2024 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'coa', 'COA', '', '', NULL);
















-- Queries on 07-08-2024 --

ALTER TABLE `acc_transaction` ADD `voucherID` INT(11) NOT NULL AFTER `ID`;
ALTER TABLE `acc_transaction` ADD CONSTRAINT `Voucher ID` FOREIGN KEY (`voucherID`) REFERENCES `tbl_vouchar`(`id`) ON DELETE RESTRICT ON UPDATE NO ACTION;
ALTER TABLE `acc_transaction` CHANGE `StoreID` `StoreID` INT(11) NULL DEFAULT NULL;

-- Queries on 08-08-2024 --

ALTER TABLE `tbl_voucharhead` ADD `purchaseID` INT NULL DEFAULT NULL COMMENT 'when purchase occurs' AFTER `refno`, ADD `saleID` INT NULL DEFAULT NULL COMMENT 'when sale occurs' AFTER `purchaseID`, ADD `serviceID` INT NULL DEFAULT NULL COMMENT 'For delivery charge' AFTER `saleID`;
ALTER TABLE `acc_transaction` ADD `purchaseID` INT NULL DEFAULT NULL COMMENT 'when purchase occurs' AFTER `refno`, ADD `saleID` INT NULL DEFAULT NULL COMMENT 'when sale occurs' AFTER `purchaseID`, ADD `serviceID` INT NULL DEFAULT NULL COMMENT 'For delivery charge' AFTER `saleID`;

-- Queries on 10-08-2024 --

INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'send_to_purchase', 'Send to purchase'); -- Updated to restorapos staging server --

-- Updated till above to staying server --also my local machine DB




-- Queries on 13-08-2024 --

-- From Mridul ---- In my local computer updated database --

INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'stock_in', 'Stock In', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'main_inventory_with_kitchen', 'Main Inventory (with Kitchen)', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'appears_in_date_range', 'appears in date range', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'stock_out', 'Stock Out', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'Total_Amount_of_stock_out_in_date_range', 'Total Amount of stock out in date range', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'used_wastage_expired', 'Used, Wastage, Expired', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'total_amount_of_stock_decreased_from_all_kitchens_in_date_range', 'Total amount of stock decreased from all Kitchens in date range', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'list_of_kitchens_and_their_stocks_in_date_range', 'List of Kitchens and their stocks in date range', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'the_green_marked', 'The green marked', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'current_stock', 'Current Stock', NULL);
INSERT INTO language (`id`, phrase, english, `arabic`) VALUES (NULL, 'shows_the_actual_remaining_stock_in_your_system', 'shows the actual remaining stock in your system', NULL);

-- By Misor ---- In my local computer updated database --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`) VALUES (NULL, 'add_opening_stock_multiple', 'Add opening stock multiple', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`) VALUES (NULL, 'rate_open_stock', 'Rate', NULL);

ALTER TABLE `language` ADD `arabic` TEXT NULL DEFAULT NULL AFTER `spanish`;

-- From ainal vai -- -- In my local computer updated database --

ALTER TABLE supplier_po_details ADD conversationrate INT NULL DEFAULT NULL AFTER indredientid; -- In my local computer updated database --

-- Updated till above to staying server on 14th August 2024 --also my local machine DB



-- Queries on 24-08-2024 --

ALTER TABLE `language` ADD `turkish` TEXT NULL DEFAULT NULL AFTER `arabic`;

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `turkish`, `arabic`) VALUES (NULL, 'financial_report', 'Financial Report', 'Informe financiero', 'Mali Rapor', 'التقرير المالي');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `turkish`, `arabic`) VALUES (NULL, 'rev_acc_name', 'Reverse Account', 'Nombre de cuenta inversa', 'Ters Hesap Adı', 'عكس اسم الحساب');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `turkish`, `arabic`) VALUES (NULL, 'remarks', 'Remarks', 'Observaciones', 'Notlar', 'ملاحظات');

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'rate_', 'Rate', NULL, NULL, NULL);

-- Updated till above to staying server on 24th August 2024 --also my local machine DB




-- Queries on 25-08-2024 --

ALTER TABLE `setting` ADD `client_id` INT(11) NOT NULL COMMENT 'for crm API call and alert message show' AFTER `is_auto_approve_acc`;

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'client_id', 'Client ID', NULL, NULL, NULL);

-- Updated till above to staging new folder based server on 25th August 2024 --also my local machine DB




-- **************88Till above updated in UAE and one client server also....*************************... ------------

-- Queries on 18-11-2024 --

ALTER TABLE `setting` ADD `pos_order_mode` INT NOT NULL DEFAULT '1' COMMENT 'Quick or Regular Mode' AFTER `alert_password`;

INSERT INTO `language` (`phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES
('order_mode_change_successfully', 'Order Mode Changed Successfully', '', '', NULL),
('order_mode_change', 'Order Mode Change', '', '', NULL),
('quick_mode', 'Quick Mode', '', '', NULL),
('regular_mode', 'Regular Mode', '', '', NULL),
('order_mode', 'Order Mode', '', '', NULL),
('order_mode_setting', 'Order Mode Setting', '', '', NULL);

-- Queries on 20-11-2024 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'card', 'Card', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'cash', 'Cash', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'order_created_payment_failed', 'Order Created Successfully But Payment Failed !', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'payment_failed', 'Payment Failed', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'card_payment', 'Card Payment', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'select_bank', 'Select Bank', '', '', NULL);


-- Above all Queries are updated in all client servers ***********************--

-- Queries on 20-11-2024 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'your_account_is_inactive_please_contact_support', 'Your account is inactive. Please contact support.', '', '', NULL);


-- Queries on 15-01-2025 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'logs_reset', 'Logs Reset', NULL, NULL, NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'logs_reset_note', 'Note: All Logs data will be cleared after running the logs reset.', '', '', NULL);

-- Queries on 16-01-2025 --

ALTER TABLE `tbl_bank` ADD `is_active` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 = active , 0 = inactive' AFTER `acc_coa_id`;

-- Queries on 20-01-2025 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'point_of_sale', 'Point Of Sales (POS)', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'kitchen_dislpay', 'Kitchen Display (KDS).', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'report_dashboard', 'Report Dashboard', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'purchases_report', 'Purchases Report', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'human_resources', 'Human Resources', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'productions', 'Productions', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'settings', 'Settings', '', '', NULL);

-- Queries on 22-01-2025 --

ALTER TABLE `customer_order` CHANGE `cutomertype` `cutomertype` INT NOT NULL COMMENT '3 = third party';
ALTER TABLE `customer_order` ADD `customer_id_for_third_party` INT NULL DEFAULT NULL COMMENT 'customer_id id of customer_info table' AFTER `isthirdparty`;



-- ***************** Work started for single server along with sub branch on same source code ***************** --


-- Queries on 17-04-2025 --

ALTER TABLE `setting` ADD `app_type` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = single server, 1 = sub branch' AFTER `login_auto_posting`;


INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'application_type', 'Application Type', 'Tipo de aplicación', 'نوع التطبيق', 'Başvuru Türü');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'sub_branch', 'Sub Branch', 'Subrama', 'فرع فرعي', 'Alt Şube');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'single_server', 'Single Server', 'Servidor único', 'خادم واحد', 'Tek Sunucu');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'user_real_ip', 'User Real IP', 'IP real del usuario', 'IP الحقيقي للمستخدم', 'Kullan Gerçek IP');


-- Queries on 20-04-2025 --

ALTER TABLE `customer_info` ADD `ref_code` INT NOT NULL COMMENT 'Main Branch customer ref code' AFTER `customer_id`, ADD UNIQUE (`ref_code`);

ALTER TABLE `employee_history` ADD `emp_ref_code` BIGINT NOT NULL COMMENT 'Employee REF CODE which is same as Main Branch' AFTER `emp_his_id`;
 
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'mapping', 'Mapping', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'employee_mapping', 'Employee Mapping', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'machine_id', 'Machine ID', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'employee_mapping_form', 'Employee Mapping Form', '', '', NULL);
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'employee_ref_code', 'Employee Ref Code', '', '', NULL);


INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES (NULL, 'mapping', 'Employee_map', 'hrm', '0', '0', '2', '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES (NULL, 'employee_mapping', 'employee_mapping', 'hrm', '1782', '0', '2', '2018-12-18 00:00:00');

CREATE TABLE `employee_map` (`id` INT NOT NULL AUTO_INCREMENT , `emp_ref_code` BIGINT NOT NULL COMMENT 'From employee_history table' , `machine_id` BIGINT NOT NULL COMMENT 'Ex: ZKT machine ID' , `status` TINYINT(1) NOT NULL DEFAULT '1' , `created_by` INT NULL DEFAULT NULL , `created_at` DATE NULL DEFAULT NULL , `updated_by` INT NULL DEFAULT NULL , `updated_at` DATE NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


ALTER TABLE `setting` ADD `secret_login_key` TEXT NULL DEFAULT NULL AFTER `inventory_system`;
ALTER TABLE `setting` CHANGE `secret_login_key` `secret_login_key` VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;
ALTER TABLE `setting` CHANGE `secret_login_key` `secret_login_key` VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL;

ALTER TABLE `supplier` CHANGE `acc_subcode_id` `acc_subcode_id` INT NULL DEFAULT NULL COMMENT 'acc_subcode primary_key';
ALTER TABLE `supplier` ADD `ref_code` BIGINT NOT NULL COMMENT 'Main branch supplier ID' AFTER `supid`, ADD UNIQUE (`ref_code`);

ALTER TABLE `employee_map` ADD UNIQUE(`emp_ref_code`);
ALTER TABLE `employee_map` ADD UNIQUE(`machine_id`);


-- Queries on 21-04-2025 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'can_not_sync_data_with_main_branch', 'Can not sync data with main branch', '', '', NULL);
 
 ALTER TABLE `customer_info` ADD `synced_main_branch` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'If sync = 1 , otherwise = 0' AFTER `thirdparty_id`;
 ALTER TABLE `customer_info` ADD `updated_main_branch` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'updated = 1 , fail = 0' AFTER `synced_main_branch`;
 ALTER TABLE `supplier` ADD `synced_main_branch` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 == synced , 0 = fail' AFTER `acc_subcode_id`;
 ALTER TABLE `supplier` ADD `updated_main_branch` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 = updated, 0 = not updated' AFTER `synced_main_branch`;

 
-- Queries on 29-04-2025 --

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'order_number_format', 'Order Number Format', 'Formato del número de pedido', 'تنسيق رقم الطلب', 'Sipariş Numarası Formatı');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'regular_number', 'Regular Number', 'Número normal', 'الرقم العادي', 'Normal Numara');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'random_numbers', 'Random Numbers', 'Números aleatorios', 'أرقام عشوائية', 'Rastgele Sayılar');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'order_number_format_change_successfully', 'Order Number Format Change Successfully', 'El formato del número de pedido se cambió correctamente', 'تم تغيير تنسيق رقم الطلب بنجاح', 'Sipariş Numarası Formatı Başarıyla Değiştirildi');

ALTER TABLE `tbl_invoicesetting` ADD `order_number_format` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Regular, 1 = Random' AFTER `sumnienable`;
ALTER TABLE `customer_order` ADD `random_order_number` VARCHAR(20) NULL DEFAULT NULL COMMENT 'random_order_number based on POS order number' AFTER `saleinvoice`;
ALTER TABLE `customer_order` ADD INDEX(`random_order_number`);

-- Queries on 11-05-2025 --

ALTER TABLE `tbl_posetting` ADD `items_sorting` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = Item Position, 1 = Alphabetical Order' AFTER `isautoapproved`;

INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'items_sorting', 'Items Sorting', 'Clasificación de artículos', 'فرز العناصر', 'Öğe Sıralama');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'item_position', 'Items Position', 'Posición de los artículos', 'موقف العناصر', 'Öğelerin Konumu');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'alphabetical_order', 'Alphabetical Order', 'Orden alfabético', 'الترتيب الأبجدي', 'Alfabetik Sıra');
INSERT INTO `language` (`id`, `phrase`, `english`, `spanish`, `arabic`, `turkish`) VALUES (NULL, 'items_sorting_change_successfully', 'Items Sorting Changed Successfully', 'La clasificación de elementos se modificó correctamente', 'تم تغيير فرز العناصر بنجاح', 'Öğelerin Sıralaması Başarıyla Değiştirildi');
