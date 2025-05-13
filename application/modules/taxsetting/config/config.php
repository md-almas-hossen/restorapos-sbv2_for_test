<?php

// module directory name
$HmvcConfig['taxsetting']["_title"]       = "Tax setting for country wise";
$HmvcConfig['taxsetting']["_description"] = "Tax setting for country wise";
$HmvcConfig['taxsetting']["_version"]     = 1.1;



// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['taxsetting']['_database'] = true;
$HmvcConfig['taxsetting']["_tables"] = array(
	'tax_settings','tax_collection','tbl_tax'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['taxsetting']["_extra_query"] = true;


