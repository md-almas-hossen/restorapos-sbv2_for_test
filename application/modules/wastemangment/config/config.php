<?php

// module directory name
$HmvcConfig['wastemangment']["_title"]       = "Waste Management System";
$HmvcConfig['wastemangment']["_description"] = "Waste Management for employee";
$HmvcConfig['wastemangment']["_version"]     = 1.1;



// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['wastemangment']['_database'] = true;
$HmvcConfig['wastemangment']["_tables"] = array(
	'packaging_food_waste','ingradient_food_waste','items_food_waste'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['wastemangment']["_extra_query"] = true;


