CREATE TABLE IF NOT EXISTS `tbl_customerpoint` (
  `cpid` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `points` decimal(10,3) NOT NULL DEFAULT 0.000,
  PRIMARY KEY (`cpid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tbl_pointsetting` (
  `psid` int(11) NOT NULL AUTO_INCREMENT,
  `amountrangestpoint` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amountrangeedpoint` decimal(10,2) NOT NULL DEFAULT 0.00,
  `earnpoint` decimal(10,3) NOT NULL DEFAULT 0.000,
  PRIMARY KEY (`psid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



