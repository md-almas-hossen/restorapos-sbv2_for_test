<?php
// module directory name
$HmvcConfig['device']["_title"]       = "Device for attendance like ZKTeco";
$HmvcConfig['device']["_description"] = "Device create edit update, assign employees to devices, remove employees from devices";
$HmvcConfig['device']["_version"]    = 1.1;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['device']['_database'] = true;
$HmvcConfig['device']['_extra_query'] = true;
$HmvcConfig['device']["_tables"] = array( 
	'tbl_device_ip',
	'real_ip_setting',
	'attendance_history',
	'schdule_purchse_info'
);
