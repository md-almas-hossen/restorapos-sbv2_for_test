CREATE TABLE IF NOT EXISTS `customer_catering_order` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` bigint DEFAULT NULL,
  `saleinvoice` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `marge_order_id` varchar(30) DEFAULT NULL,
  `customer_id` int NOT NULL,
  `cutomertype` int NOT NULL,
  `isthirdparty` int NOT NULL DEFAULT '0' COMMENT '0=normal,1>all Third Party',
  `thirdpartyinvoiceid` int DEFAULT NULL,
  `waiter_id` int DEFAULT NULL,
  `kitchen` int DEFAULT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `cookedtime` time NOT NULL DEFAULT '00:15:00',
  `table_no` int DEFAULT NULL,
  `tokenno` varchar(30) DEFAULT NULL,
  `totalamount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `customerpaid` decimal(10,2) DEFAULT '0.00',
  `customer_note` text,
  `anyreason` text,
  `order_status` tinyint(1) NOT NULL COMMENT '1=Pending, 2=Processing, 3=Ready, 4=Served,5=Cancel',
  `nofification` int NOT NULL DEFAULT '0' COMMENT '0=unseen,1=seen',
  `orderacceptreject` int DEFAULT NULL,
  `splitpay_status` tinyint NOT NULL DEFAULT '0' COMMENT '0=no split,1=split',
  `isupdate` int DEFAULT NULL,
  `shipping_date` datetime DEFAULT '1790-01-01 01:01:01',
  `delivaryaddress` varchar(255) DEFAULT NULL,
  `person` int DEFAULT NULL,
  `tokenprint` int NOT NULL DEFAULT '0' COMMENT '1=print done,0=not done',
  `invoiceprint` int DEFAULT NULL,
  `is_duepayment` int DEFAULT NULL COMMENT '1=due payment',
  `offlineid` bigint DEFAULT '0',
  `offlinesync` int DEFAULT '0',
  `onlinesync` int DEFAULT '0',
  `ordered_by` int NOT NULL DEFAULT '0',
  `isdelete` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `cutomertype` (`cutomertype`),
  KEY `waiter_id` (`waiter_id`),
  KEY `kitchen` (`kitchen`),
  KEY `thirdpartyinvoiceid` (`thirdpartyinvoiceid`),
  KEY `table_no` (`table_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `tbl_catering_package` (
  `id` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `person` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `food_item_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `tbl_package_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `package_id` int NOT NULL,
  `category_id` int NOT NULL,
  `max_item` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `catering_bill_card_payment` (
  `row_id` bigint NOT NULL AUTO_INCREMENT,
  `bill_id` bigint NOT NULL,
  `multipay_id` int DEFAULT NULL,
  `card_no` varchar(200) DEFAULT NULL,
  `terminal_name` int NOT NULL,
  `bank_name` int DEFAULT NULL,
  PRIMARY KEY (`row_id`),
  KEY `bill_id` (`bill_id`),
  KEY `multipay_id` (`multipay_id`),
  KEY `bank_name` (`bank_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `catering_mobiletransaction` (
  `trid` int NOT NULL AUTO_INCREMENT,
  `bill_id` bigint NOT NULL,
  `multipay_id` int NOT NULL,
  `mobilemethod` int DEFAULT NULL,
  `mobilenumber` varchar(100) DEFAULT NULL,
  `transactionnumber` varchar(255) DEFAULT NULL,
  `pdate` date DEFAULT '1970-01-01',
  PRIMARY KEY (`trid`),
  KEY `bill_id` (`bill_id`),
  KEY `multipay_id` (`multipay_id`),
  KEY `mobilemethod` (`mobilemethod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `catering_multipay_bill` (
  `multipay_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `multipayid` varchar(30) DEFAULT NULL,
  `payment_type_id` int NOT NULL,
  `amount` float NOT NULL,
  `suborderid` int DEFAULT NULL,
  `adflag` varchar(30) DEFAULT 'ad',
  `pdate` date DEFAULT '1970-01-01',
  PRIMARY KEY (`multipay_id`),
  KEY `order_id` (`order_id`),
  KEY `multipayid` (`multipayid`),
  KEY `payment_type_id` (`payment_type_id`),
  KEY `suborderid` (`suborderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `catering_package_bill` (
  `bill_id` bigint NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `order_id` bigint NOT NULL,
  `total_amount` float NOT NULL,
  `discount` float NOT NULL,
  `discountnote` text,
  `allitemdiscount` decimal(19,3) NOT NULL DEFAULT '0.000',
  `discountType` int NOT NULL DEFAULT '1',
  `service_charge` float NOT NULL,
  `deliverycharge` decimal(10,2) DEFAULT NULL,
  `shipping_type` int DEFAULT NULL COMMENT '1=home,2=pickup,3=none',
  `delivarydate` datetime DEFAULT NULL,
  `VAT` float NOT NULL,
  `bill_amount` float NOT NULL,
  `bill_date` date NOT NULL,
  `bill_time` time NOT NULL,
  `create_at` datetime DEFAULT '1970-01-01 01:01:01',
  `bill_status` tinyint(1) NOT NULL COMMENT '0=unpaid, 1=paid',
  `return_order_id` bigint DEFAULT NULL,
  `return_amount` decimal(19,3) DEFAULT NULL,
  `payment_method_id` tinyint NOT NULL,
  `create_by` int NOT NULL,
  `create_date` date NOT NULL,
  `update_by` int NOT NULL,
  `update_date` date NOT NULL,
  `isdelete` int DEFAULT '0',
  PRIMARY KEY (`bill_id`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `payment_method_id` (`payment_method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE IF NOT EXISTS `catering_tax_collection` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `customer_id` varchar(30) NOT NULL,
  `relation_id` varchar(30) NOT NULL,
  `tax0` text,
  `tax1` text,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


CREATE TABLE IF NOT EXISTS `order_menu_catering` (
  `row_id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` bigint NOT NULL,
  `menu_id` int NOT NULL COMMENT 'package id and non package id',
  `price` decimal(19,3) DEFAULT '0.000',
  `notes` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `is_package` int NOT NULL,
  PRIMARY KEY (`row_id`),
  KEY `order_id` (`order_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_menu_catering_item` (
  `row_id` bigint NOT NULL AUTO_INCREMENT,
  `package_id` int DEFAULT NULL,
  `is_package` int NOT NULL,
  `order_id` bigint NOT NULL,
  `menu_id` int NOT NULL,
  `price` decimal(19,3) DEFAULT '0.000',
  `itemdiscount` decimal(19,3) DEFAULT '0.000',
  `itemvat` decimal(10,2) DEFAULT NULL,
  `groupmid` int DEFAULT '0',
  `notes` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `menuqty` decimal(19,3) NOT NULL DEFAULT '0.000',
  `add_on_id` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `addonsqty` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tpassignid` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tpid` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tpposition` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tpprice` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `varientid` int NOT NULL,
  `groupvarient` int DEFAULT NULL,
  `addonsuid` int DEFAULT NULL,
  `qroupqty` decimal(19,3) DEFAULT '0.000',
  `isgroup` int DEFAULT '0',
  `food_status` int DEFAULT '0',
  `allfoodready` int DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `isupdate` int DEFAULT NULL,
  PRIMARY KEY (`row_id`),
  KEY `order_id` (`order_id`),
  KEY `menu_id` (`menu_id`),
  KEY `groupmid` (`groupmid`),
  KEY `varientid` (`varientid`),
  KEY `groupvarient` (`groupvarient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE IF NOT EXISTS `tbl_catering_rate_conversion` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `mergeOrderid` varchar(50) CHARACTER SET utf32 COLLATE utf32_unicode_ci DEFAULT NULL,
  `conv_amount` decimal(10,2) NOT NULL,
  `payrate` decimal(10,2) NOT NULL,
  `currency_name` varchar(25) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;