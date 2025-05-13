TRUNCATE TABLE `membership`;

INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`,`startpoint`,`endpoint`) VALUES(1, 'Normal User', 0, '', 2, '2018-11-07', 2, '2018-11-07',0,0);
INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`,`startpoint`,`endpoint`) VALUES(2, 'Premium Member', 0, '', 1, '2020-11-04', 0, '0000-00-00', 250, 999);
INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`,`startpoint`,`endpoint`) VALUES(3, 'VIP', 0, '', 1, '2020-11-04', 0, '0000-00-00', 1001, 5000000);

INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) VALUES ('loyalty', 'loyalty', 'loyalty', '0', '0', '3', '2020-12-03 00:00:00');
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'pointstting', 'index', 'loyalty', sec_menu_item.menu_id, '0', '3', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'loyalty';
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'membership_list', 'membershiplist', 'loyalty', sec_menu_item.menu_id, '0', '3', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'loyalty';
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'membership_card', 'customerbarcode', 'loyalty', sec_menu_item.menu_id, '0', '3', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'loyalty';
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'customerpointlist', 'customerpointlist', 'loyalty', sec_menu_item.menu_id, '0', '3', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'loyalty';
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'review_rating', 'review_rating', 'loyalty', sec_menu_item.menu_id, '0', '3', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'loyalty';


INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'loyalty', 'Loyalty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'pointstting', 'Point Setting'), (NULL, 'user_points', 'User Point List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'point_range_list', 'Point Range'), (NULL, 'startamount', 'Start ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'endamount', 'End Range'), (NULL, 'earn_point', 'Earn Point');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'add_range', 'Add Range'), (NULL, 'editpoint', 'Point Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'isvip', 'Is offer ?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'customerpointlist', 'Customer Point list');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'spendamount', 'Spend Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'totalp', 'Total Points');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'membershipenable', 'Membership Enable');


