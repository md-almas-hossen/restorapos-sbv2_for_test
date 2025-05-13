<?php

// module directory name
$HmvcConfig['printershare']["_title"]       = "Printer Module";
$HmvcConfig['printershare']["_description"] = "Share printer for Multiple print on one click";
$HmvcConfig['printershare']["_version"]   = 1.1;

// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['printershare']['_database'] = true;
$HmvcConfig['printershare']["_tables"] = array(
'tbl_printersetting'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['printershare']["_extra_query"] = true;
$HmvcConfig['printershare']["_zip_download"] = true;
