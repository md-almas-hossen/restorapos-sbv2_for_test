<?php

// module directory name
$HmvcConfig['androidpos']["_title"]       = "AndroidPOS Module";
$HmvcConfig['androidpos']["_description"] = "AndroidPOS is a Pos System for manage order from Android POS Add";
$HmvcConfig['androidpos']["_version"]   = 1.1;

// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['androidpos']['_database'] = false;
$HmvcConfig['printershare']["_tables"] = array();
//Table sql Data insert into existing tables to run module
$HmvcConfig['androidpos']["_extra_query"] = false;
$HmvcConfig['androidpos']["_androidapp"] = true;
