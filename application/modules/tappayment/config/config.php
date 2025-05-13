<?php

// module directory name
$HmvcConfig['tappayment']["_title"]       = "Tap Payment Gateway";
$HmvcConfig['tappayment']["_description"] = "Tap Payment Gateway";
$HmvcConfig['tappayment']["_version"]   = 1.0;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['tappayment']['_database'] = false;
$HmvcConfig['tappayment']["_tables"] = array();
//Table sql Data insert into existing tables to run module
$HmvcConfig['tappayment']["_extra_query"] = true;


