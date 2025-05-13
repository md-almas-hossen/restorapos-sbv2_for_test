<?php
// module directory name
$HmvcConfig['qrapp']["_title"]     = "Qr Order";
$HmvcConfig['qrapp']["_description"] = "Manage QR Order item";
$HmvcConfig['qrapp']["_version"]   = 1.2;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['qrapp']['_database'] = true;
$HmvcConfig['qrapp']["_tables"] = array('tbl_qrpayments');
$HmvcConfig['qrapp']['_extra_query'] = true;
