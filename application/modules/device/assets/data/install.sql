INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'device', 'Attendance Device');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'device_name', 'Device Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'device_ip', 'Device IP');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'assigned_employee_no', 'Assigned Employee No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'assign', 'Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'delete_device', 'Employees Assigned to this device will no longer to any device after deleting this device. Are you sure?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'device_ip_form', 'Device IP Form');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'add_new_device_ip', 'Add New Device IP');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'employees_under_device', 'Employees Under Device');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'no_employee_remains', 'No Employee Remains');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'no_employee_remains', 'No Employee Remains');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'attendance_data_message', 'Attendance Data Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'get_zkt_attendance_data', 'Get Zkt Attendance Data');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'port', 'Port');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'set_your_device', 'Set Your Device');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'machine_id', 'Machine ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'wastage', 'Wastage');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'actual_stay', 'Actual Stay');

INSERT INTO `real_ip_setting` (`id`, `status`) VALUES (null, 1);

INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) VALUES ('device', 'create_device_ip', 'device', '0', '0', '3', '2023-09-25 00:00:00');

ALTER TABLE `employee_history` ADD `device_ip_id` INT NULL  AFTER `pos_id`;
