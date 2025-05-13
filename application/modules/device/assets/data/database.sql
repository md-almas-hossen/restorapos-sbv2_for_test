CREATE TABLE IF NOT EXISTS `tbl_device_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_name` varchar(50) NULL,
  `device_ip` varchar(50) NOT NULL,
  `port` varchar(50) NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `real_ip_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `attendance_history` (
  `atten_his_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `id` int(11) NOT NULL DEFAULT 0,
  `state` text NOT NULL,
  `time` time,
  `date` date NULL,
  `date_time` datetime NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`atten_his_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `schdule_purchse_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchase_key` varchar(100) NULL,
  `domain` varchar(200) NULL,
  `ip_address` varchar(100) NULL,
  `port` varchar(11) NULL,
  `created_at` DATETIME,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



