<?php

// module directory name
$HmvcConfig['meta']["_title"]     = "Meta Program";
$HmvcConfig['meta']["_description"] = "Manage meta";
$HmvcConfig['meta']["_version"]   = 1.1;

// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['meta']['_database'] = true;
$HmvcConfig['meta']["_tables"] = array('messenger_settings');
$HmvcConfig['meta']['_extra_query'] = true;
