<?php

// module directory name
$HmvcConfig['shiftmangment']["_title"]       = "Shift Management System";
$HmvcConfig['shiftmangment']["_description"] = "Shift Management for employee";
$HmvcConfig['shiftmangment']["_version"]     = 1.1;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['shiftmangment']['_database'] = true;
$HmvcConfig['shiftmangment']["_tables"] = array(
	'shifts','shift_user'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['shiftmangment']["_extra_query"] = true;


