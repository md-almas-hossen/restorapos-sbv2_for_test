CREATE TABLE IF NOT EXISTS `tbl_qrpayments` (
  `qrpid` int(11) NOT NULL AUTO_INCREMENT,
  `paymentsid` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`qrpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_qrsetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` int(11) NOT NULL DEFAULT 0,
  `theme` varchar(255) DEFAULT NULL,
  `backgroundcolorqr` text DEFAULT NULL,
  `qrheaderfontcolor` text DEFAULT NULL,
  `review_code` text DEFAULT NULL,
  `isactivereview` int NOT NULL DEFAULT '0' COMMENT '0=inactive,1=active', 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

