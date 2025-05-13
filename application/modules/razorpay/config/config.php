<?php

// module directory name
$HmvcConfig['razorpay']["_title"]       = "Razor Payment Gateway";
$HmvcConfig['razorpay']["_description"] = "Razor Payment Gateway";
$HmvcConfig['razorpay']["_version"]   = 1.1;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['razorpay']['_database'] = false;
$HmvcConfig['razorpay']["_tables"] = array();
//Table sql Data insert into existing tables to run module
$HmvcConfig['razorpay']["_extra_query"] = true;


