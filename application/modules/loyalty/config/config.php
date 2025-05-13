<?php
// module directory name
$HmvcConfig['loyalty']["_title"]     = "Loyalty Program";
$HmvcConfig['loyalty']["_description"] = "Manage Loyalty";
$HmvcConfig['loyalty']["_version"]   = 1.1;

// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['loyalty']['_database'] = true;
$HmvcConfig['loyalty']['_extra_query'] = true;
$HmvcConfig['loyalty']["_tables"] = array(
'tbl_customerpoint',
'tbl_pointsetting');
