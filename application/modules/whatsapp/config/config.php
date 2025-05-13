<?php

// module directory name
$HmvcConfig['whatsapp']["_title"]       = "WhatsApp Chat & Ordering";
$HmvcConfig['whatsapp']["_description"] = "WhatsApp Chat & Ordering";
$HmvcConfig['whatsapp']["_version"]     = 1.1;



// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['whatsapp']['_database'] = true;
$HmvcConfig['whatsapp']["_tables"] = array(
	'whatsapp_settings'
);
//Table sql Data insert into existing tables to run module
$HmvcConfig['whatsapp']["_extra_query"] = true;


