<?php

// module directory name
$HmvcConfig['catering_service']["_title"]       = "Catering Services";
$HmvcConfig['catering_service']["_description"] = "Manage Catering Services";
$HmvcConfig['catering_service']["_version"]   = 1.0;


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['catering_service']['_database'] = true;
$HmvcConfig['catering_service']["_tables"] = array(
    'customer_catering_order',
    'tbl_catering_package',
    'tbl_package_details',
    'catering_bill_card_payment',
    'catering_mobiletransaction',
    'catering_multipay_bill',
    'catering_package_bill',
    'catering_tax_collection',
    'order_menu_catering',
    'order_menu_catering_item',
    'tbl_catering_rate_conversion'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['catering_service']["_extra_query"] = true;


