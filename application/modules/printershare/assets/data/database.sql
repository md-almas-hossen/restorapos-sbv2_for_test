CREATE TABLE IF NOT EXISTS `tbl_printersetting` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(30) NOT NULL,
  `port` varchar(12) DEFAULT NULL,
  `counterno` int(11) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;