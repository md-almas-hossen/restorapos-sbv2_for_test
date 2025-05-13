set sql_mode='';

--
-- Table structure for table `accesslog`
--

DROP TABLE IF EXISTS `accesslog`;
CREATE TABLE IF NOT EXISTS `accesslog` (
  `sl_no` bigint(20) NOT NULL AUTO_INCREMENT,
  `action_page` varchar(50) DEFAULT NULL,
  `action_done` text DEFAULT NULL,
  `remarks` text NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `entry_date` datetime DEFAULT NULL,
  PRIMARY KEY (`sl_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_account_name`
--

DROP TABLE IF EXISTS `acc_account_name`;
CREATE TABLE IF NOT EXISTS `acc_account_name` (
  `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_name` varchar(255) NOT NULL,
  `account_type` int(11) NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `acc_account_name`
--

INSERT INTO `acc_account_name` (`account_id`, `account_name`, `account_type`) VALUES(1, 'Employee Salary', 0);
INSERT INTO `acc_account_name` (`account_id`, `account_name`, `account_type`) VALUES(3, 'Example', 1);
INSERT INTO `acc_account_name` (`account_id`, `account_name`, `account_type`) VALUES(4, 'Loan Expense', 0);
INSERT INTO `acc_account_name` (`account_id`, `account_name`, `account_type`) VALUES(5, 'Loan Income', 1);

-- --------------------------------------------------------

--
-- Table structure for table `acc_coa`
--

DROP TABLE IF EXISTS `acc_coa`;
CREATE TABLE IF NOT EXISTS `acc_coa` (
  `HeadCode` varchar(50) NOT NULL,
  `HeadName` varchar(100) NOT NULL,
  `PHeadName` varchar(50) NOT NULL,
  `HeadLevel` int(11) NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsTransaction` tinyint(1) NOT NULL,
  `IsGL` tinyint(1) NOT NULL,
  `HeadType` char(1) NOT NULL,
  `IsBudget` tinyint(1) NOT NULL,
  `IsDepreciation` tinyint(1) NOT NULL,
  `DepreciationRate` decimal(18,2) NOT NULL,
  `CreateBy` varchar(50) NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(50) NOT NULL,
  `UpdateDate` datetime NOT NULL,
  PRIMARY KEY (`HeadName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `acc_coa`
--

INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000001', '145454-HmIsahaq', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2018-12-17 15:10:19', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021403', 'AC', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:33:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('50202', 'Account Payable', 'Current Liabilities', 2, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2015-10-15 19:50:43', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10203', 'Account Receivable', 'Current Asset', 2, 1, 0, 0, 'A', 0, 0, 0.00, '', '0000-00-00 00:00:00', 'admin', '2013-09-18 15:29:35');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020201', 'Advance', 'Advance, Deposit And Pre-payments', 3, 1, 0, 1, 'A', 0, 0, 0.00, 'Zoherul', '2015-05-31 13:29:12', 'admin', '2015-12-31 16:46:32');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020103', 'Advance House Rent', 'Advance', 4, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-10-02 16:55:38', 'admin', '2016-10-02 16:57:32');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10202', 'Advance, Deposit And Pre-payments', 'Current Asset', 2, 1, 0, 0, 'A', 0, 0, 0.00, '', '0000-00-00 00:00:00', 'admin', '2015-12-31 16:46:24');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020602', 'Advertisement and Publicity', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:51:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010410', 'Air Cooler', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-05-23 12:13:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020603', 'AIT Against Advertisement', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:52:09', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1', 'Assets', 'COA', 0, 1, 0, 0, 'A', 0, 0, 0.00, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010204', 'Attendance Machine', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:49:31', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40216', 'Audit Fee', 'Other Expenses', 2, 1, 1, 1, 'E', 0, 0, 0.00, 'admin', '2017-07-18 12:54:30', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021002', 'Bank Charge', 'Financial Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:21:03', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30203', 'Bank Interest', 'Other Income', 2, 1, 1, 1, 'I', 0, 0, 0.00, 'Obaidul', '2015-01-03 14:49:54', 'admin', '2016-09-25 11:04:19');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010312', 'Bkash', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-12-01 13:14:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010104', 'Book Shelf', 'Furniture & Fixturers', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:46:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010407', 'Books and Journal', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:45:37', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010207', 'Brac Bank', 'Cash At Bank', 4, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-01-18 10:10:31', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020604', 'Business Development Expenses', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:52:29', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020606', 'Campaign Expenses', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:52:57', 'admin', '2016-09-19 14:52:48');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020502', 'Campus Rent', 'House Rent', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:46:53', 'admin', '2017-04-27 17:02:39');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40212', 'Car Running Expenses', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:28:43', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10201', 'Cash & Cash Equivalent', 'Current Asset', 2, 1, 0, 1, 'A', 0, 0, 0.00, '', '0000-00-00 00:00:00', 'admin', '2015-10-15 15:57:55');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020102', 'Cash At Bank', 'Cash & Cash Equivalent', 3, 1, 0, 1, 'A', 0, 0, 0.00, '2', '2018-07-19 13:43:59', 'admin', '2015-10-15 15:32:42');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020101', 'Cash In Hand', 'Cash & Cash Equivalent', 3, 1, 1, 1, 'A', 0, 0, 0.00, '2', '2018-07-31 12:56:28', 'admin', '2016-05-23 12:05:43');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30101', 'Cash Sale', 'Store Income', 1, 1, 1, 1, 'I', 0, 0, 0.00, '2', '2018-07-08 07:51:26', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010207', 'CCTV', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:51:24', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020102', 'CEO Current A/C', 'Advance', 4, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-09-25 11:54:54', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010206', 'City Bank', 'Cash At Bank', 4, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-01-18 10:09:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010101', 'Class Room Chair', 'Furniture & Fixturers', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:45:29', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021407', 'Close Circuit Cemera', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:35:35', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020601', 'Commision on Admission', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:51:21', 'admin', '2016-09-19 14:42:54');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010206', 'Computer', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:51:09', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021410', 'Computer (R)', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-03-24 12:38:52', 'Zoherul', '2016-03-24 12:41:40');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010102', 'Computer Table', 'Furniture & Fixturers', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:45:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('301020401', 'Continuing Registration fee - UoL (Income)', 'Registration Fee (UOL) Income', 4, 1, 1, 0, 'I', 0, 0, 0.00, 'admin', '2015-10-15 17:40:40', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020904', 'Contratuall Staff Salary', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:12:34', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('403', 'Cost of Sale', 'Expence', 0, 1, 1, 0, 'E', 0, 0, 0.00, '2', '2018-07-08 10:37:16', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30102', 'Credit Sale', 'Store Income', 1, 1, 1, 1, 'I', 0, 0, 0.00, '2', '2018-07-08 07:51:34', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020709', 'Cultural Expense', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'nasmud', '2017-04-29 12:45:10', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102', 'Current Asset', 'Assets', 1, 1, 0, 0, 'A', 0, 0, 0.00, '2', '2018-12-06 13:54:42', 'admin', '2018-07-07 11:23:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502', 'Current Liabilities', 'Liabilities', 1, 1, 0, 0, 'L', 0, 0, 0.00, 'anwarul', '2014-08-30 13:18:20', 'admin', '2015-10-15 19:49:21');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030101', 'cusL-0001-Walkin', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2019-01-08 09:14:48', '', '2022-01-20 12:48:19');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030108', 'cusL-0002-Jamil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-08-25 14:12:02', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030109', 'cusL-0004-Kabir khan', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '36', '2021-08-31 14:03:18', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030110', 'cusL-0007-jamil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '39', '2021-09-05 19:38:26', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030111', 'cusL-0008-kamal', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '40', '2021-09-19 11:53:13', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030112', 'cusL-0009-shakil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '41', '2021-10-26 10:20:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030113', 'cusL-0010-shakil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '42', '2021-10-26 10:23:52', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030104', 'cusL-0018-jamildasd', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '20', '2021-01-05 14:14:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030114', 'cusL-0019- ', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '0', '2021-11-10 14:06:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030115', 'cusL-0019-jamildasd', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '53', '2021-12-07 16:55:24', '', '2022-01-10 13:08:35');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030105', 'cusL-0021-jamil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '25', '2021-01-31 14:17:07', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030116', 'cusL-0022-ainal ', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '56', '2022-02-06 11:41:25', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030106', 'cusL-0022-Saiful Hassan', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '26', '2021-01-31 18:18:33', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102030107', 'cusL-0023-jamil', 'Customer Receivable', 4, 1, 1, 0, 'A', 0, 0, 0.00, '27', '2021-02-03 10:12:50', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020301', 'Customer Receivable', 'Account Receivable', 3, 1, 0, 1, 'A', 0, 0, 0.00, '2', '2019-01-08 09:15:08', 'admin', '2018-07-07 12:31:42');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40100002', 'cw-Chichawatni', 'Store Expenses', 2, 1, 1, 0, 'E', 0, 0, 0.00, '2', '2018-08-02 16:30:41', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020202', 'Deposit', 'Advance, Deposit And Pre-payments', 3, 1, 0, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:40:42', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020605', 'Design & Printing Expense', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:55:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020404', 'Dish Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:58:21', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40215', 'Dividend', 'Other Expenses', 2, 1, 1, 1, 'E', 0, 0, 0.00, 'admin', '2016-09-25 14:07:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020403', 'Drinking Water Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:58:10', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010211', 'DSLR Camera', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:53:17', 'admin', '2016-01-02 16:23:25');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010205', 'Dutch-Bangla Bank', 'Cash At Bank', 4, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-01-18 09:49:13', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000007', 'E3Y1WJMB-John Maria', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-27 05:55:58', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000010', 'E4Y91CAX-onlineorder', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-02-03 11:20:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000004', 'E97E9SJT-Manik Hassan', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-09 14:32:22', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020908', 'Earned Leave', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:13:38', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000006', 'EBK2UPRA-John Carlos', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-27 05:51:09', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020607', 'Education Fair Expenses', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:53:42', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000011', 'EK9BYZVY-test sdafdssdfds', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-02-24 14:07:53', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010602', 'Electric Equipment', 'Electrical Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:44:51', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010203', 'Electric Kettle', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:49:07', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10106', 'Electrical Equipment', 'Non Current Assets', 2, 1, 0, 1, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:43:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020407', 'Electricity Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:59:31', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10202010501', 'employ', 'Salary', 5, 1, 0, 0, 'A', 0, 0, 0.00, 'admin', '2018-07-05 11:47:10', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000013', 'EMW7SH4L-Kitchen1', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2022-02-10 17:48:28', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('405', 'Entertainment', 'Expense', 1, 1, 1, 0, 'E', 1, 1, 1.00, '2', '2020-01-18 07:49:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000012', 'ENVBUZKE-kabirkhan', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2020-10-12 10:57:33', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000002', 'EQLAJFUW-AinalHaque', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2018-12-17 15:08:43', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('2', 'Equity', 'COA', 0, 1, 0, 0, 'L', 0, 0, 0.00, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000009', 'EU3APTYY-JohnDoe', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-27 06:02:46', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000005', 'EW9PM201-test emp', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-09 14:38:15', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000008', 'EXL9WSCL-Mitchel Santner', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2019-01-27 05:58:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4', 'Expense', 'COA', 0, 1, 0, 0, 'E', 0, 0, 0.00, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020000003', 'EY2T1OWA-jahangirAhmad', 'Account Payable', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'John Doe', '2018-12-17 15:11:13', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020903', 'Faculty,Staff Salary & Allowances', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:12:21', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021404', 'Fax Machine', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:34:15', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020905', 'Festival & Incentive Bonus', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:12:48', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010103', 'File Cabinet', 'Furniture & Fixturers', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:46:02', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40210', 'Financial Expenses', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2013-08-20 12:24:31', 'admin', '2015-10-15 19:20:36');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010403', 'Fire Extingushier', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:39:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021408', 'Furniture', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:35:47', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10101', 'Furniture & Fixturers', 'Non Current Assets', 2, 1, 0, 1, 'A', 0, 0, 0.00, 'anwarul', '2013-08-20 16:18:15', 'anwarul', '2013-08-21 13:35:40');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020406', 'Gas Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:59:20', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('20201', 'General Reserve', 'Reserve & Surplus', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'admin', '2016-09-25 14:07:12', 'admin', '2016-10-02 17:48:49');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10105', 'Generator', 'Non Current Assets', 2, 1, 1, 1, 'A', 0, 0, 0.00, 'Zoherul', '2016-02-27 16:02:35', 'admin', '2016-05-23 12:05:18');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021414', 'Generator Repair', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-06-16 10:21:05', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40213', 'Generator Running Expenses', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:29:29', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10103', 'Groceries and Cutleries', 'Non Current Assets', 2, 1, 1, 1, 'A', 0, 0, 0.00, '2', '2018-07-12 10:02:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010408', 'Gym Equipment', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:46:03', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020907', 'Honorarium', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:13:26', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40205', 'House Rent', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2013-08-24 10:26:56', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40100001', 'HP-Hasilpur', 'Store Expenses', 2, 1, 1, 0, 'E', 0, 0, 0.00, '2', '2018-07-29 03:44:23', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020702', 'HR Recruitment Expenses', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-09-25 12:55:49', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020703', 'Incentive on Admission', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-09-25 12:56:09', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('3', 'Income', 'COA', 0, 1, 0, 0, 'I', 0, 0, 0.00, '', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('5020302', 'Income Tax Payable', 'Liabilities for Expenses', 3, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2016-09-19 11:18:17', 'admin', '2016-09-28 13:18:35');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020302', 'Insurance Premium', 'Prepayment', 4, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-09-19 13:10:57', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021001', 'Interest on Loan', 'Financial Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:20:53', 'admin', '2016-09-19 14:53:34');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020401', 'Internet Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:56:55', 'admin', '2015-10-15 18:57:32');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10107', 'Inventory', 'Non Current Assets', 1, 1, 0, 0, 'A', 0, 0, 0.00, '2', '2018-07-07 15:21:58', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010309', 'iyzico', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:32:35', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10205010101', 'Jahangir', 'Hasan', 1, 1, 0, 0, 'A', 0, 0, 0.00, '2', '2018-07-07 10:40:56', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010210', 'LCD TV', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:52:27', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30103', 'Lease Sale', 'Store Income', 1, 1, 1, 1, 'I', 0, 0, 0.00, '2', '2018-07-08 07:51:52', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('5', 'Liabilities', 'COA', 0, 1, 0, 0, 'L', 0, 0, 0.00, 'admin', '2013-07-04 12:32:07', 'admin', '2015-10-15 19:46:54');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('50203', 'Liabilities for Expenses', 'Current Liabilities', 2, 1, 0, 0, 'L', 0, 0, 0.00, 'admin', '2015-10-15 19:50:59', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020707', 'Library Expenses', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2017-01-10 15:34:54', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021409', 'Lift', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:36:12', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('50101', 'Long Term Borrowing', 'Non Current Liabilities', 2, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2013-07-04 12:32:26', 'admin', '2015-10-15 19:47:40');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010315', 'M-Cash', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-12-01 13:16:25', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020608', 'Marketing & Promotion Exp.', 'Promonational Expence', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:53:59', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020901', 'Medical Allowance', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:11:33', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010411', 'Metal Ditector', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'Zoherul', '2016-08-22 10:55:22', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021413', 'Micro Oven', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-05-12 14:53:51', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30202', 'Miscellaneous (Income)', 'Other Income', 2, 1, 1, 1, 'I', 0, 0, 0.00, 'anwarul', '2014-02-06 15:26:31', 'admin', '2016-09-25 11:04:35');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020909', 'Miscellaneous Benifit', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:13:53', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020701', 'Miscellaneous Exp', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-09-25 12:54:39', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40207', 'Miscellaneous Expenses', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2014-04-26 16:49:56', 'admin', '2016-09-25 12:54:19');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010401', 'Mobile Phone', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-01-29 10:43:30', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020101', 'Mr Ashiqur Rahman', 'Advance', 4, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-12-31 16:47:23', 'admin', '2016-09-25 11:55:13');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010212', 'Network Accessories', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-01-02 16:23:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020106', 'new head dfhgfh', 'Advance', 3, 1, 0, 0, 'A', 0, 0, 0.00, '2', '2020-01-16 06:25:10', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020408', 'News Paper Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-01-02 15:55:57', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010314', 'Nogodh', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-12-01 13:16:01', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('101', 'Non Current Assets', 'Assets', 1, 1, 0, 0, 'A', 0, 0, 0.00, '', '0000-00-00 00:00:00', 'admin', '2015-10-15 15:29:11');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('501', 'Non Current Liabilities', 'Liabilities', 1, 1, 0, 0, 'L', 0, 0, 0.00, 'anwarul', '2014-08-30 13:18:20', 'admin', '2015-10-15 19:49:21');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('406', 'Office Accessories', 'Expense', 1, 1, 1, 0, 'E', 1, 1, 1.00, '2', '2020-01-18 07:51:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010404', 'Office Decoration', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:40:02', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10102', 'Office Equipment', 'Non Current Assets', 2, 1, 0, 1, 'A', 0, 0, 0.00, 'anwarul', '2013-12-06 18:08:00', 'admin', '2015-10-15 15:48:21');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021401', 'Office Repair & Maintenance', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:33:15', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30201', 'Office Stationary (Income)', 'Other Income', 2, 1, 1, 1, 'I', 0, 0, 0.00, 'anwarul', '2013-07-17 15:21:06', 'admin', '2016-09-25 11:04:50');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020103', 'Online Payment', 'Cash & Cash Equivalent', 2, 1, 0, 1, 'A', 0, 0, 0.00, '2', '2020-10-18 14:26:41', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010308', 'Orange Money payment', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:32:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('402', 'Other Expenses', 'Expense', 1, 1, 0, 0, 'E', 0, 0, 0.00, '2', '2018-07-07 14:00:16', 'admin', '2015-10-15 18:37:42');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('302', 'Other Income', 'Income', 1, 1, 0, 0, 'I', 0, 0, 0.00, '2', '2018-07-07 13:40:57', 'admin', '2016-09-25 11:04:09');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40211', 'Others (Non Academic Expenses)', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'Obaidul', '2014-12-03 16:05:42', 'admin', '2015-10-15 19:22:09');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30205', 'Others (Non-Academic Income)', 'Other Income', 2, 1, 1, 1, 'I', 0, 0, 0.00, 'admin', '2015-10-15 17:23:49', 'admin', '2015-10-15 17:57:52');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10104', 'Others Assets', 'Non Current Assets', 2, 1, 0, 1, 'A', 0, 0, 0.00, 'admin', '2016-01-29 10:43:16', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020910', 'Outstanding Salary', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-04-24 11:56:50', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021405', 'Oven', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:34:31', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021412', 'PABX-Repair', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-04-24 14:40:18', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020902', 'Part-time Staff Salary', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:12:06', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010301', 'Paypal', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:27:41', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010306', 'Paystack Payments', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:30:55', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010307', 'Paytm Payments', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:31:23', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010202', 'Photocopy & Fax Machine', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:47:27', 'admin', '2016-05-23 12:14:40');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021411', 'Photocopy Machine Repair', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'Zoherul', '2016-04-24 12:40:02', 'admin', '2017-04-27 17:03:17');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('3020503', 'Practical Fee', 'Others (Non-Academic Income)', 3, 1, 1, 1, 'I', 0, 0, 0.00, 'admin', '2017-07-22 18:00:37', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1020203', 'Prepayment', 'Advance, Deposit And Pre-payments', 3, 1, 0, 1, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:40:51', 'admin', '2015-12-31 16:49:58');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010201', 'Printer', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:47:15', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('407', 'Product Purchase', 'Expense', 0, 1, 1, 0, 'E', 0, 0, 0.00, '2', '2020-01-23 07:09:10', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30207', 'Professional Training Course(Oracal)', 'Other Income', 2, 1, 1, 1, 'I', 0, 0, 0.00, 'nasim', '2017-06-22 13:24:16', 'nasim', '2017-06-22 13:25:56');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('3020502', 'Professional Training Course(Oracal-1)', 'Others (Non-Academic Income)', 3, 1, 1, 0, 'I', 0, 0, 0.00, 'nasim', '2017-06-22 13:28:05', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010208', 'Projector', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:51:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40206', 'Promonational Expense', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2013-07-11 13:48:57', 'anwarul', '2013-07-17 14:23:03');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30208', 'Purchase Discount', 'Other Income', 2, 1, 1, 0, 'I', 0, 0, 0.00, 'nasim', '2021-12-27 15:25:56', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40214', 'Repair and Maintenance', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:32:46', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('202', 'Reserve & Surplus', 'Equity', 1, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2016-09-25 14:06:34', 'admin', '2016-10-02 17:48:57');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('20102', 'Retained Earnings', 'Share Holders Equity', 2, 1, 1, 1, 'L', 0, 0, 0.00, 'admin', '2016-05-23 11:20:40', 'admin', '2016-09-25 14:05:06');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020708', 'River Cruse', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2017-04-24 15:35:25', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010311', 'RMA PAYMENT GATEWAY', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:33:12', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010313', 'Rocket', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-12-01 13:15:45', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020105', 'Salary', 'Advance', 4, 1, 0, 0, 'A', 0, 0, 0.00, 'admin', '2018-07-05 11:46:44', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40209', 'Salary & Allowances', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2013-12-12 11:22:58', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('404', 'Sale Discount', 'Expense', 1, 1, 1, 0, 'E', 0, 0, 0.00, '2', '2018-07-19 10:15:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('303', 'Sale Income', 'Income', 0, 1, 1, 1, 'I', 0, 0, 0.00, '2', '2020-01-23 06:58:20', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010406', 'Security Equipment', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:41:30', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('30104', 'Service Charge Income', 'Store Income', 1, 1, 1, 0, 'I', 0, 0, 0.00, '2', '2020-12-30 11:23:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('20101', 'Share Capital', 'Share Holders Equity', 2, 1, 1, 0, 'L', 0, 0, 0.00, 'anwarul', '2013-12-08 19:37:32', 'admin', '2015-10-15 19:45:35');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('201', 'Share Holders Equity', 'Equity', 1, 1, 0, 0, 'L', 0, 0, 0.00, '', '0000-00-00 00:00:00', 'admin', '2015-10-15 19:43:51');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('50201', 'Short Term Borrowing', 'Current Liabilities', 2, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2015-10-15 19:50:30', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010310', 'SIPS Office', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:32:54', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020906', 'Special Allowances', 'Salary & Allowances', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:13:13', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('50102', 'Sponsors Loan', 'Non Current Liabilities', 2, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2015-10-15 19:48:02', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020706', 'Sports Expense', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'nasmud', '2016-11-09 13:16:53', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010304', 'Square Payments', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:29:32', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010302', 'SSLCommerz', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:28:06', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('401', 'Store Expenses', 'Expense', 1, 1, 0, 0, 'E', 0, 0, 0.00, '2', '2018-07-07 13:38:59', 'admin', '2015-10-15 17:58:46');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('301', 'Store Income', 'Income', 1, 1, 0, 0, 'I', 0, 0, 0.00, '2', '2018-07-07 13:40:37', 'admin', '2015-09-17 17:00:02');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010305', 'Stripe Payment', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:29:59', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('3020501', 'Students Info. Correction Fee', 'Others (Non-Academic Income)', 3, 1, 1, 0, 'I', 0, 0, 0.00, 'admin', '2015-10-15 17:24:45', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010601', 'Sub Station', 'Electrical Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:44:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('5020205', 'Suppliers', 'Account Payable', 3, 1, 0, 1, 'L', 0, 0, 0.00, '2', '2018-12-15 06:50:12', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020501', 'sup_002-Kamal Hossain', 'Suppliers', 4, 1, 1, 0, 'L', 0, 0, 0.00, '2', '2020-01-18 10:49:49', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020504', 'sup_002-Supplier_1', 'Suppliers', 4, 1, 1, 0, 'L', 0, 0, 0.00, '2', '2020-09-08 14:26:40', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020502', 'sup_003-Maruf', 'Suppliers', 4, 1, 1, 0, 'L', 0, 0, 0.00, '2', '2020-01-18 10:56:31', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502020503', 'sup_004-Saiful', 'Suppliers', 4, 1, 1, 0, 'L', 0, 0, 0.00, '2', '2020-01-18 10:57:04', '2', '2020-01-21 13:10:59');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010316', 'SureCash', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2021-12-01 14:09:57', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020704', 'TB Care Expenses', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-10-08 13:03:04', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020501', 'TDS on House Rent', 'House Rent', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:44:07', 'admin', '2016-09-19 14:40:16');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502030201', 'TDS Payable House Rent', 'Income Tax Payable', 4, 1, 1, 0, 'L', 0, 0, 0.00, 'admin', '2016-09-19 11:19:42', 'admin', '2016-09-28 13:19:37');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502030203', 'TDS Payable on Advertisement Bill', 'Income Tax Payable', 4, 1, 1, 0, 'L', 0, 0, 0.00, 'admin', '2016-09-28 13:20:51', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502030202', 'TDS Payable on Salary', 'Income Tax Payable', 4, 1, 1, 0, 'L', 0, 0, 0.00, 'admin', '2016-09-28 13:20:17', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021402', 'Tea Kettle', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:33:45', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020402', 'Telephone Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:57:59', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010209', 'Telephone Set & PABX', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:51:57', 'admin', '2016-10-02 17:10:40');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102020104', 'Test', 'Advance', 4, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2018-07-05 11:42:48', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('505', 'tex', 'Liabilities', 1, 1, 1, 0, 'L', 0, 0, 0.00, '3', '2020-11-24 14:21:58', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40203', 'Travelling & Conveyance', 'Other Expenses', 2, 1, 1, 1, 'E', 0, 0, 0.00, 'admin', '2013-07-08 16:22:06', 'admin', '2015-10-15 18:45:13');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4021406', 'TV', 'Repair and Maintenance', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 19:35:07', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('102010303', 'Two Checkout', 'Online Payment', 2, 1, 1, 0, 'A', 0, 0, 0.00, '2', '2020-10-18 14:28:29', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010205', 'UPS', 'Office Equipment', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:50:38', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('40204', 'Utility Expenses', 'Other Expenses', 2, 1, 0, 1, 'E', 0, 0, 0.00, 'anwarul', '2013-07-11 16:20:24', 'admin', '2016-01-02 15:55:22');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020503', 'VAT on House Rent Exp', 'House Rent', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:49:22', 'admin', '2016-09-25 14:00:52');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('5020301', 'VAT Payable', 'Liabilities for Expenses', 3, 1, 0, 1, 'L', 0, 0, 0.00, 'admin', '2015-10-15 19:51:11', 'admin', '2016-09-28 13:23:53');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('502030101', 'VAT- TAX', 'VAT Payable', 3, 1, 1, 0, 'L', 0, 0, 0.00, '2', '2020-12-30 10:58:49', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010409', 'Vehicle A/C', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'Zoherul', '2016-05-12 12:13:21', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010405', 'Voltage Stablizer', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-03-27 10:40:59', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010105', 'Waiting Sofa - Steel', 'Furniture & Fixturers', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2015-10-15 15:46:29', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020405', 'WASA Bill', 'Utility Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2015-10-15 18:58:51', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('1010402', 'Water Purifier', 'Others Assets', 3, 1, 1, 0, 'A', 0, 0, 0.00, 'admin', '2016-01-29 11:14:11', '', '0000-00-00 00:00:00');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('4020705', 'Website Development Expenses', 'Miscellaneous Expenses', 3, 1, 1, 0, 'E', 0, 0, 0.00, 'admin', '2016-10-15 12:42:47', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `acc_customer_income`
--

DROP TABLE IF EXISTS `acc_customer_income`;
CREATE TABLE IF NOT EXISTS `acc_customer_income` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Customer_Id` varchar(50) NOT NULL,
  `VNo` varchar(50) NOT NULL,
  `Date` date NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_glsummarybalance`
--

DROP TABLE IF EXISTS `acc_glsummarybalance`;
CREATE TABLE IF NOT EXISTS `acc_glsummarybalance` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COAID` varchar(50) DEFAULT NULL,
  `Debit` decimal(18,2) DEFAULT NULL,
  `Credit` decimal(18,2) DEFAULT NULL,
  `FYear` int(11) DEFAULT NULL,
  `CreateBy` varchar(50) DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_income_expence`
--

DROP TABLE IF EXISTS `acc_income_expence`;
CREATE TABLE IF NOT EXISTS `acc_income_expence` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `VNo` varchar(50) NOT NULL,
  `Student_Id` varchar(50) NOT NULL,
  `Date` date NOT NULL,
  `Paymode` varchar(50) NOT NULL,
  `Perpose` varchar(50) NOT NULL,
  `Narration` text NOT NULL,
  `StoreID` int(11) NOT NULL,
  `COAID` varchar(50) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `IsApprove` tinyint(4) NOT NULL,
  `CreateBy` varchar(50) NOT NULL,
  `CreateDate` datetime NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_temp`
--

DROP TABLE IF EXISTS `acc_temp`;
CREATE TABLE IF NOT EXISTS `acc_temp` (
  `COAID` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Debit` decimal(18,2) NOT NULL,
  `Credit` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acc_transaction`
--

DROP TABLE IF EXISTS `acc_transaction`;
CREATE TABLE IF NOT EXISTS `acc_transaction` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `VNo` varchar(50) DEFAULT NULL,
  `Vtype` varchar(50) DEFAULT NULL,
  `VDate` date DEFAULT NULL,
  `COAID` varchar(50) NOT NULL,
  `Narration` text DEFAULT NULL,
  `Debit` decimal(18,2) DEFAULT NULL,
  `Credit` decimal(18,2) DEFAULT NULL,
  `reversecode` int(11) NOT NULL,
  `StoreID` int(11) NOT NULL,
  `IsPosted` char(10) DEFAULT NULL,
  `CreateBy` varchar(50) DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `UpdateBy` varchar(50) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `IsAppove` char(10) DEFAULT NULL,
  `subtype` int(11) DEFAULT NULL,
  `subcode` int(11) DEFAULT NULL,
  `ledgercomments` text DEFAULT NULL,
  `refno` varchar(100) DEFAULT NULL,
  `chequeno` varchar(50) DEFAULT NULL,
  `chequeDate` date DEFAULT NULL,
  `ishonour` int(11) DEFAULT NULL,
  `fin_yearid` int(11) NOT NULL,
  UNIQUE KEY `ID` (`ID`),
  KEY `COAID` (`COAID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acn_account_transaction`
--

DROP TABLE IF EXISTS `acn_account_transaction`;
CREATE TABLE IF NOT EXISTS `acn_account_transaction` (
  `account_tran_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` int(11) NOT NULL,
  `transaction_description` varchar(255) NOT NULL,
  `amount` varchar(25) NOT NULL,
  `tran_date` date NOT NULL,
  `payment_id` int(11) NOT NULL,
  `create_by_id` varchar(25) NOT NULL,
  PRIMARY KEY (`account_tran_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(11) NOT NULL COMMENT 'for employee , it will save employee_id',
  `type` varchar(30) NOT NULL COMMENT 'ticket, employee, attendnace etc.',
  `action` varchar(15) NOT NULL COMMENT 'create, update, delete',
  `action_id` varchar(15) NOT NULL,
  `table_name` varchar(30) DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `form_data` text DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=created, 2=updated, 3=deleted  ',
  `countfail` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addjustmentitem`
--

DROP TABLE IF EXISTS `addjustmentitem`;
CREATE TABLE IF NOT EXISTS `addjustmentitem` (
  `addjustid` int(11) NOT NULL AUTO_INCREMENT,
  `refarenceno` varchar(50) DEFAULT NULL,
  `adjustment_no` bigint(20) NOT NULL,
  `adjustdate` date NOT NULL,
  `savedby` varchar(200) NOT NULL,
  `note` text DEFAULT NULL,
  `addjustentrydate` datetime NOT NULL DEFAULT '1790-01-01 00:00:00',
  PRIMARY KEY (`addjustid`),
  KEY `invoiceid` (`refarenceno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addonsproduction`
--

DROP TABLE IF EXISTS `addonsproduction`;
CREATE TABLE IF NOT EXISTS `addonsproduction` (
  `addonsproductionid` int(11) NOT NULL AUTO_INCREMENT,
  `addonsid` int(11) NOT NULL,
  `itemquantity` int(11) NOT NULL,
  `savedby` int(11) NOT NULL,
  `saveddate` date NOT NULL,
  `addonsproductionexpiredate` date NOT NULL,
  PRIMARY KEY (`addonsproductionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addonsproduction_details`
--

DROP TABLE IF EXISTS `addonsproduction_details`;
CREATE TABLE IF NOT EXISTS `addonsproduction_details` (
  `addonspro_detailsid` int(11) NOT NULL AUTO_INCREMENT,
  `addonsid` int(11) NOT NULL,
  `ingredientid` int(11) NOT NULL,
  `qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unitname` varchar(100) NOT NULL,
  `createdby` int(11) NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`addonspro_detailsid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_ons`
--

DROP TABLE IF EXISTS `add_ons`;
CREATE TABLE IF NOT EXISTS `add_ons` (
  `add_on_id` int(11) NOT NULL AUTO_INCREMENT,
  `add_on_name` varchar(200) NOT NULL,
  `addonCode` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `unit` int(11) DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  `istopping` int(11) NOT NULL DEFAULT 0,
  `tax0` text DEFAULT NULL,
  `tax1` text DEFAULT NULL,
  `tax2` text DEFAULT NULL,
  PRIMARY KEY (`add_on_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adjustment_details`
--

DROP TABLE IF EXISTS `adjustment_details`;
CREATE TABLE IF NOT EXISTS `adjustment_details` (
  `detailsid` int(11) NOT NULL AUTO_INCREMENT,
  `adjustid` int(11) NOT NULL,
  `typeid` int(11) DEFAULT NULL,
  `indredientid` int(11) NOT NULL,
  `adjustquantity` decimal(19,3) NOT NULL DEFAULT 0.000,
  `finalquantity` decimal(19,3) DEFAULT NULL,
  `adjust_type` varchar(80) NOT NULL,
  `adjusteby` varchar(200) NOT NULL,
  `adjusteddate` date NOT NULL,
  PRIMARY KEY (`detailsid`),
  KEY `purchaseid` (`adjustid`),
  KEY `indredientid` (`indredientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assign_inventory`
--

DROP TABLE IF EXISTS `assign_inventory`;
CREATE TABLE IF NOT EXISTS `assign_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assign_inventory_main_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` float(10,2) NOT NULL,
  `assigned_date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `assigned_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assign_inventory_main`
--

DROP TABLE IF EXISTS `assign_inventory_main`;
CREATE TABLE IF NOT EXISTS `assign_inventory_main` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) NOT NULL,
  `kitchen_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `kitchennote` longtext DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = editable, 1 = not editable',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

DROP TABLE IF EXISTS `award`;
CREATE TABLE IF NOT EXISTS `award` (
  `award_id` int(11) NOT NULL AUTO_INCREMENT,
  `award_name` varchar(50) NOT NULL,
  `aw_description` varchar(200) NOT NULL,
  `awr_gift_item` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `awarded_by` varchar(30) NOT NULL,
  PRIMARY KEY (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_summary`
--

DROP TABLE IF EXISTS `bank_summary`;
CREATE TABLE IF NOT EXISTS `bank_summary` (
  `bank_id` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deposite_id` varchar(250) DEFAULT NULL,
  `date` varchar(250) DEFAULT NULL,
  `ac_type` varchar(50) DEFAULT NULL,
  `dr` float DEFAULT NULL,
  `cr` float DEFAULT NULL,
  `ammount` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `bill_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_id` bigint(20) NOT NULL,
  `total_amount` float NOT NULL,
  `discount` float NOT NULL,
  `discountnote` text DEFAULT NULL,
  `allitemdiscount` decimal(19,3) NOT NULL DEFAULT 0.000,
  `discountType` int(11) NOT NULL DEFAULT 1,
  `service_charge` float NOT NULL,
  `deliverycharge` decimal(10,2) DEFAULT NULL,
  `shipping_type` int(11) DEFAULT NULL COMMENT '1=home,2=pickup,3=none',
  `delivarydate` datetime DEFAULT NULL,
  `VAT` float NOT NULL,
  `bill_amount` float NOT NULL,
  `bill_date` date NOT NULL,
  `bill_time` time NOT NULL,
  `create_at` datetime DEFAULT '1970-01-01 01:01:01',
  `bill_status` tinyint(1) NOT NULL COMMENT '0=unpaid, 1=paid',
  `is_duepayment` int(11) DEFAULT NULL,
  `return_order_id` bigint(20) DEFAULT NULL,
  `return_amount` decimal(19,3) DEFAULT NULL,
  `payment_method_id` tinyint(4) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_date` date NOT NULL,
  `isdelete` int(11) DEFAULT 0,
  PRIMARY KEY (`bill_id`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `payment_method_id` (`payment_method_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_card_payment`
--

DROP TABLE IF EXISTS `bill_card_payment`;
CREATE TABLE IF NOT EXISTS `bill_card_payment` (
  `row_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bill_id` bigint(20) NOT NULL,
  `multipay_id` int(11) DEFAULT NULL,
  `card_no` varchar(200) DEFAULT NULL,
  `terminal_name` int(11) NOT NULL,
  `bank_name` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_id`),
  KEY `bill_id` (`bill_id`),
  KEY `multipay_id` (`multipay_id`),
  KEY `bank_name` (`bank_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_basic_info`
--

DROP TABLE IF EXISTS `candidate_basic_info`;
CREATE TABLE IF NOT EXISTS `candidate_basic_info` (
  `can_id` varchar(20) NOT NULL,
  `first_name` varchar(11) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `alter_phone` varchar(20) NOT NULL,
  `present_address` varchar(100) NOT NULL,
  `parmanent_address` varchar(100) NOT NULL,
  `picture` text DEFAULT NULL,
  `ssn` varchar(50) NOT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `zip` int(11) NOT NULL,
  PRIMARY KEY (`can_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `candidate_basic_info`
--

INSERT INTO `candidate_basic_info` (`can_id`, `first_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `ssn`, `state`, `city`, `zip`) VALUES('16304688503269L', 'Hassan', 'Kabir', 'kabir@gmail.com', '1732432434', '', 'DDD sgfsrgrg', 'DDD sgfsrgrg', NULL, '', '', 'Select City', 259);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_education_info`
--

DROP TABLE IF EXISTS `candidate_education_info`;
CREATE TABLE IF NOT EXISTS `candidate_education_info` (
  `can_edu_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_id` varchar(30) NOT NULL,
  `degree_name` varchar(30) NOT NULL,
  `university_name` varchar(50) NOT NULL,
  `cgp` varchar(30) NOT NULL,
  `comments` varchar(50) DEFAULT NULL,
  `sequencee` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`can_edu_id`),
  KEY `can_id` (`can_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `candidate_education_info`
--

INSERT INTO `candidate_education_info` (`can_edu_id`, `can_id`, `degree_name`, `university_name`, `cgp`, `comments`, `sequencee`) VALUES(1, '16304688503269L', 'sf', 'sdf', 'd', 's', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_interview`
--

DROP TABLE IF EXISTS `candidate_interview`;
CREATE TABLE IF NOT EXISTS `candidate_interview` (
  `can_int_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_id` varchar(30) NOT NULL,
  `job_adv_id` varchar(50) NOT NULL,
  `interview_date` varchar(30) NOT NULL,
  `interviewer_id` varchar(50) NOT NULL,
  `interview_marks` varchar(50) NOT NULL,
  `written_total_marks` varchar(50) NOT NULL,
  `mcq_total_marks` varchar(50) NOT NULL,
  `total_marks` varchar(30) NOT NULL,
  `recommandation` varchar(50) NOT NULL,
  `selection` varchar(50) NOT NULL,
  `details` varchar(50) NOT NULL,
  PRIMARY KEY (`can_int_id`),
  KEY `can_id` (`can_id`),
  KEY `job_adv_id` (`job_adv_id`),
  KEY `interviewer_id` (`interviewer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_selection`
--

DROP TABLE IF EXISTS `candidate_selection`;
CREATE TABLE IF NOT EXISTS `candidate_selection` (
  `can_sel_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_id` varchar(30) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `pos_id` varchar(30) NOT NULL,
  `selection_terms` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`can_sel_id`),
  KEY `can_id` (`can_id`),
  KEY `employee_id` (`employee_id`),
  KEY `pos_id` (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_shortlist`
--

DROP TABLE IF EXISTS `candidate_shortlist`;
CREATE TABLE IF NOT EXISTS `candidate_shortlist` (
  `can_short_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_id` varchar(30) NOT NULL,
  `job_adv_id` int(11) NOT NULL,
  `date_of_shortlist` varchar(50) NOT NULL,
  `interview_date` varchar(30) NOT NULL,
  PRIMARY KEY (`can_short_id`),
  KEY `can_id` (`can_id`),
  KEY `job_adv_id` (`job_adv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_workexperience`
--

DROP TABLE IF EXISTS `candidate_workexperience`;
CREATE TABLE IF NOT EXISTS `candidate_workexperience` (
  `can_workexp_id` int(11) NOT NULL AUTO_INCREMENT,
  `can_id` varchar(30) NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `working_period` varchar(50) NOT NULL,
  `duties` varchar(30) NOT NULL,
  `supervisor` varchar(50) NOT NULL,
  `sequencee` varchar(10) NOT NULL,
  PRIMARY KEY (`can_workexp_id`),
  KEY `can_id` (`can_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `candidate_workexperience`
--

INSERT INTO `candidate_workexperience` (`can_workexp_id`, `can_id`, `company_name`, `working_period`, `duties`, `supervisor`, `sequencee`) VALUES(1, '16304688503269L', 'bdtask', '2', 'df', 'fd', '');

-- --------------------------------------------------------

--
-- Table structure for table `check_addones`
--

DROP TABLE IF EXISTS `check_addones`;
CREATE TABLE IF NOT EXISTS `check_addones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_menuid` int(11) NOT NULL,
  `sub_order_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1=insert, 0=notinserted',
  PRIMARY KEY (`id`),
  KEY `order_menuid` (`order_menuid`),
  KEY `sub_order_id` (`sub_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `common_setting`
--

DROP TABLE IF EXISTS `common_setting`;
CREATE TABLE IF NOT EXISTS `common_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address` text DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `phone_optional` varchar(30) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `logo_footer` varchar(255) DEFAULT NULL,
  `ismembership` int(11) NOT NULL DEFAULT 0 COMMENT '1=enable,0=disable',
  `powerbytxt` text DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `web_onoff` int(11) DEFAULT 1 COMMENT '1=enable,0=disable',
  `backgroundcolorweb` varchar(30) DEFAULT NULL,
  `webheaderfontcolor` varchar(20) DEFAULT NULL,
  `backgroundcolorqr` varchar(30) DEFAULT NULL,
  `qrheaderfontcolor` varchar(20) DEFAULT NULL,
  `ismapenable` int(11) DEFAULT 0,
  `mapapikey` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO common_setting (id, address, email, phone, phone_optional, logo, logo_footer, ismembership, powerbytxt, country, web_onoff, backgroundcolorweb, webheaderfontcolor, backgroundcolorqr, qrheaderfontcolor, ismapenable, mapapikey) VALUES(1, '<p>123 Suspendis matti, <br> Visaosang Building VST District, <br> NY Accums, North American</p>', 'support@bdtask.com', '0123456789', '+88 01715 222 333', 'assets/img/2021-01-02/b.png', 'assets/img/2021-01-02/b1.png', 1, '© 2019 Hungry All Right Reserved. Developed by BDTASK.\r\n', 'BD', 1, '#0a0a0a', '#fff', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

DROP TABLE IF EXISTS `currency`;
CREATE TABLE IF NOT EXISTS `currency` (
  `currencyid` int(11) NOT NULL AUTO_INCREMENT,
  `currencyname` varchar(50) NOT NULL,
  `curr_icon` varchar(50) NOT NULL,
  `position` int(11) NOT NULL DEFAULT 1 COMMENT '1=left.2=right',
  `curr_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`currencyid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`currencyid`, `currencyname`, `curr_icon`, `position`, `curr_rate`) VALUES(1, 'BDT', '৳', 2, 83.00);
INSERT INTO `currency` (`currencyid`, `currencyname`, `curr_icon`, `position`, `curr_rate`) VALUES(2, 'USD', '$', 1, 1.00);
INSERT INTO `currency` (`currencyid`, `currencyname`, `curr_icon`, `position`, `curr_rate`) VALUES(3, 'INR', 'R', 1, 0.50);

-- --------------------------------------------------------

--
-- Table structure for table `currencynotes_tbl`
--

DROP TABLE IF EXISTS `currencynotes_tbl`;
CREATE TABLE IF NOT EXISTS `currencynotes_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `orderpos` int(11) NOT NULL DEFAULT 0,
  `created_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `currencynotes_tbl`
--

INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(1, 'One Thousand Taka', 1000.00, 1, '2023-02-18 12:27:40');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(2, 'Five Hundred Taka', 500.00, 2, '2023-02-18 12:27:54');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(4, 'Two Hundred Taka', 200.00, 3, '2023-02-18 12:30:45');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(5, 'One Hundred Taka', 100.00, 4, '2023-02-18 12:30:54');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(6, 'Fifty Taka', 50.00, 5, '2023-02-18 12:31:00');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(7, 'Twenty Taka', 20.00, 6, '2023-02-18 12:31:06');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(8, 'Ten Taka', 10.00, 7, '2023-02-18 12:31:12');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(9, 'Five Taka', 5.00, 8, '2023-02-18 12:31:18');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(10, 'Two Taka', 2.00, 9, '2023-02-18 12:31:24');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(11, 'One Taka', 1.00, 10, '2023-02-18 12:31:31');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(12, 'One Hundred Point', 0.10, 11, '2023-02-18 12:31:36');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(13, 'Ten Point', 0.10, 12, '2023-02-18 12:31:42');
INSERT INTO `currencynotes_tbl` (`id`, `title`, `amount`, `orderpos`, `created_date`) VALUES(14, 'One Point', 0.01, 13, '2023-02-18 12:31:53');

-- --------------------------------------------------------

--
-- Table structure for table `customer_info`
--

DROP TABLE IF EXISTS `customer_info`;
CREATE TABLE IF NOT EXISTS `customer_info` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `cuntomer_no` varchar(120) NOT NULL,
  `membership_type` int(11) DEFAULT NULL COMMENT '1=bronze,2=silver,3=gold,4=platinum,5vip',
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `tax_number` varchar(200) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `date_of_birth` date NOT NULL DEFAULT '1970-01-01',
  `password` varchar(255) DEFAULT NULL,
  `customer_token` text DEFAULT NULL,
  `customer_address` varchar(250) DEFAULT NULL,
  `customer_phone` varchar(200) NOT NULL,
  `customer_picture` varchar(255) DEFAULT NULL,
  `favorite_delivery_address` varchar(200) DEFAULT NULL,
  `otpcode` varchar(10) DEFAULT NULL,
  `crdate` date DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `createfrom` int(11) DEFAULT 0,
  `cusbrncecode` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_info`
--

INSERT INTO `customer_info` (`customer_id`, `cuntomer_no`, `membership_type`, `customer_name`, `customer_email`, `tax_number`, `max_discount`, `date_of_birth`, `password`, `customer_token`, `customer_address`, `customer_phone`, `customer_picture`, `favorite_delivery_address`, `otpcode`, `crdate`, `is_active`, `createfrom`, `cusbrncecode`) VALUES(1, 'cusL-0001', NULL, 'Walkin', 'kamal23@gmail.com', '', 100.00, '2023-04-09', 'e10adc3949ba59abbe56e057f20f883e', 'cO_Sa2fwscE:APA91bEFDD0sbV52pZPwJEl8MEUCcHBg2wIGjKfelfbiytAj4nJlPsKf8sSupfElBq-nm36DCkjYDEoUcA7qvtzKu4vDqjutF23f6Y_0uw4L_PlJIrtl61y4s-t5OKFAmdaU9OUQTtYS', 'Dhaka', '0275893478', NULL, 'ddd', NULL, '2023-11-29', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_membership_map`
--

DROP TABLE IF EXISTS `customer_membership_map`;
CREATE TABLE IF NOT EXISTS `customer_membership_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `membership_id` int(11) NOT NULL,
  `active_date` date NOT NULL,
  `active_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_order`
--

DROP TABLE IF EXISTS `customer_order`;
CREATE TABLE IF NOT EXISTS `customer_order` (
  `order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `saleinvoice` varchar(100) NOT NULL,
  `marge_order_id` varchar(30) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `cutomertype` int(11) NOT NULL,
  `isthirdparty` int(11) NOT NULL DEFAULT 0 COMMENT '0=normal,1>all Third Party',
  `thirdpartyinvoiceid` int(11) DEFAULT NULL,
  `waiter_id` int(11) DEFAULT NULL,
  `kitchen` int(11) DEFAULT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `cookedtime` time NOT NULL DEFAULT '00:15:00',
  `table_no` int(11) DEFAULT NULL,
  `tokenno` varchar(30) DEFAULT NULL,
  `totalamount` decimal(19,3) NOT NULL DEFAULT 0.000,
  `customerpaid` decimal(19,3) DEFAULT 0.000,
  `customer_note` text DEFAULT NULL,
  `anyreason` text DEFAULT NULL,
  `order_status` tinyint(1) NOT NULL COMMENT '1=Pending, 2=Processing, 3=Ready, 4=Served,5=Cancel',
  `nofification` int(11) NOT NULL DEFAULT 0 COMMENT '0=unseen,1=seen',
  `orderacceptreject` int(11) DEFAULT NULL,
  `splitpay_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=no split,1=split',
  `isupdate` int(11) DEFAULT NULL,
  `isquickorder` int(11) DEFAULT 0,
  `isquickorderpay` int(11) DEFAULT 0,
  `shipping_date` datetime DEFAULT '1790-01-01 01:01:01',
  `delivaryaddress` text DEFAULT NULL,
  `person` int(11) DEFAULT NULL,
  `tokenprint` int(11) NOT NULL DEFAULT 0 COMMENT '1=print done,0=not done',
  `invoiceprint` int(11) DEFAULT NULL,
  `is_duepayment` int(11) DEFAULT NULL COMMENT '1=due payment',
  `offlineid` int(11) DEFAULT 0,
  `offlinesync` int(11) DEFAULT 0,
  `onlinesync` int(11) DEFAULT 0,
  `masterbrorderid` varchar(200) DEFAULT NULL,
  `ordered_by` int(11) NOT NULL DEFAULT 0,
  `isdelete` int(11) DEFAULT 0,
  PRIMARY KEY (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `cutomertype` (`cutomertype`),
  KEY `waiter_id` (`waiter_id`),
  KEY `kitchen` (`kitchen`),
  KEY `thirdpartyinvoiceid` (`thirdpartyinvoiceid`),
  KEY `table_no` (`table_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_type`
--

DROP TABLE IF EXISTS `customer_type`;
CREATE TABLE IF NOT EXISTS `customer_type` (
  `customer_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_type` varchar(100) NOT NULL,
  `ordering` int(11) DEFAULT 0,
  PRIMARY KEY (`customer_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `customer_type`
--

INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(1, 'Dine-in', 0);
INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(2, 'Online Customer', 0);
INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(3, 'Third Party', 0);
INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(4, 'Take Way', 0);
INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(99, 'QR Customer', 0);
INSERT INTO `customer_type` (`customer_type_id`, `customer_type`, `ordering`) VALUES(100, 'Catering Service', 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_table`
--

DROP TABLE IF EXISTS `custom_table`;
CREATE TABLE IF NOT EXISTS `custom_table` (
  `custom_id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_field` varchar(100) NOT NULL,
  `custom_data_type` int(11) NOT NULL,
  `custom_data` mediumtext NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  PRIMARY KEY (`custom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `custom_table`
--

INSERT INTO `custom_table` (`custom_id`, `custom_field`, `custom_data_type`, `custom_data`, `employee_id`) VALUES(52, 'Chinese Cuisine', 1, 'coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides ', 'EU3APTYY');
INSERT INTO `custom_table` (`custom_id`, `custom_field`, `custom_data_type`, `custom_data`, `employee_id`) VALUES(54, 'French Suicine', 1, 'coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides ', 'EXL9WSCL');
INSERT INTO `custom_table` (`custom_id`, `custom_field`, `custom_data_type`, `custom_data`, `employee_id`) VALUES(55, 'Chinese Cuisine', 1, 'coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides ', 'E3Y1WJMB');
INSERT INTO `custom_table` (`custom_id`, `custom_field`, `custom_data_type`, `custom_data`, `employee_id`) VALUES(56, 'Kitchen Lead', 1, 'coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides ', 'EBK2UPRA');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `dept_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`dept_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(8, 'ACCOUNTING', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(9, 'Human Resource', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(10, 'Delivery department', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(11, 'Garage and Parking', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(12, 'Manager', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(13, 'Restaurant', 0);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(14, 'Waiter', 13);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(15, 'Senior Accountant', 8);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(16, 'Kitchen Manager', 12);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(17, 'Chef', 13);
INSERT INTO `department` (`dept_id`, `department_name`, `parent_id`) VALUES(18, 'Sales Manager', 12);

-- --------------------------------------------------------

--
-- Table structure for table `duty_type`
--

DROP TABLE IF EXISTS `duty_type`;
CREATE TABLE IF NOT EXISTS `duty_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `duty_type`
--

INSERT INTO `duty_type` (`id`, `type_name`) VALUES(1, 'Full Time');
INSERT INTO `duty_type` (`id`, `type_name`) VALUES(2, 'Part Time');
INSERT INTO `duty_type` (`id`, `type_name`) VALUES(3, 'Contructual');
INSERT INTO `duty_type` (`id`, `type_name`) VALUES(4, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

DROP TABLE IF EXISTS `email_config`;
CREATE TABLE IF NOT EXISTS `email_config` (
  `email_config_id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_port` varchar(200) DEFAULT NULL,
  `smtp_password` varchar(200) DEFAULT NULL,
  `protocol` text NOT NULL,
  `mailpath` text NOT NULL,
  `mailtype` text NOT NULL,
  `sender` text NOT NULL,
  `api_key` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`email_config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`email_config_id`, `smtp_host`, `smtp_port`, `smtp_password`, `protocol`, `mailpath`, `mailtype`, `sender`, `api_key`, `status`) VALUES(1, 'smtp.gmail.com', '587', 'qbbhgsvqgcpugmzm', 'SMTP', '/usr/sbin/sendmail', 'html', 'edotcovms@gmail.com', '22c4c92a-e5a8-4293-b64c-befc9248521e', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_benifit`
--

DROP TABLE IF EXISTS `employee_benifit`;
CREATE TABLE IF NOT EXISTS `employee_benifit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bnf_cl_code` varchar(100) NOT NULL,
  `bnf_cl_code_des` varchar(250) NOT NULL,
  `bnff_acural_date` date NOT NULL,
  `bnf_status` tinyint(4) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_history`
--

DROP TABLE IF EXISTS `employee_history`;
CREATE TABLE IF NOT EXISTS `employee_history` (
  `emp_his_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(30) NOT NULL,
  `pos_id` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `alter_phone` varchar(30) DEFAULT NULL,
  `present_address` varchar(100) DEFAULT NULL,
  `parmanent_address` varchar(100) DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `degree_name` varchar(30) DEFAULT NULL,
  `university_name` varchar(50) DEFAULT NULL,
  `cgp` varchar(30) DEFAULT NULL,
  `passing_year` varchar(30) DEFAULT NULL,
  `company_name` varchar(30) DEFAULT NULL,
  `working_period` varchar(30) DEFAULT NULL,
  `duties` varchar(30) DEFAULT NULL,
  `supervisor` varchar(30) DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `is_admin` int(11) NOT NULL DEFAULT 0,
  `dept_id` int(11) DEFAULT NULL,
  `division_id` int(11) NOT NULL,
  `maiden_name` varchar(50) DEFAULT NULL,
  `state` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `zip` int(11) NOT NULL,
  `citizenship` int(11) NOT NULL,
  `duty_type` int(11) NOT NULL,
  `hire_date` date NOT NULL,
  `original_hire_date` date NOT NULL,
  `termination_date` date NOT NULL,
  `termination_reason` text NOT NULL,
  `voluntary_termination` int(11) NOT NULL,
  `rehire_date` date NOT NULL,
  `rate_type` int(11) NOT NULL,
  `rate` float NOT NULL,
  `pay_frequency` int(11) NOT NULL,
  `pay_frequency_txt` varchar(50) NOT NULL,
  `hourly_rate2` float NOT NULL,
  `hourly_rate3` float NOT NULL,
  `home_department` varchar(100) NOT NULL,
  `department_text` varchar(100) NOT NULL,
  `class_code` varchar(50) DEFAULT NULL,
  `class_code_desc` varchar(100) DEFAULT NULL,
  `class_acc_date` date DEFAULT NULL,
  `class_status` tinyint(4) DEFAULT NULL,
  `is_super_visor` int(11) DEFAULT NULL,
  `super_visor_id` varchar(30) NOT NULL,
  `supervisor_report` text NOT NULL,
  `dob` date NOT NULL,
  `gender` int(11) NOT NULL,
  `country` varchar(120) DEFAULT NULL,
  `marital_status` int(11) NOT NULL,
  `ethnic_group` varchar(100) NOT NULL,
  `eeo_class_gp` varchar(100) NOT NULL,
  `ssn` varchar(50) DEFAULT NULL,
  `work_in_state` int(11) NOT NULL,
  `live_in_state` int(11) NOT NULL,
  `home_email` varchar(50) DEFAULT NULL,
  `business_email` varchar(50) DEFAULT NULL,
  `home_phone` varchar(30) DEFAULT NULL,
  `business_phone` varchar(30) DEFAULT NULL,
  `cell_phone` varchar(30) DEFAULT NULL,
  `emerg_contct` varchar(30) NOT NULL,
  `emrg_h_phone` varchar(30) DEFAULT NULL,
  `emrg_w_phone` varchar(30) DEFAULT NULL,
  `emgr_contct_relation` varchar(50) NOT NULL,
  `alt_em_contct` varchar(30) NOT NULL,
  `alt_emg_h_phone` varchar(30) NOT NULL,
  `alt_emg_w_phone` varchar(30) NOT NULL,
  PRIMARY KEY (`emp_his_id`),
  KEY `employee_id` (`employee_id`),
  KEY `pos_id` (`pos_id`),
  KEY `dept_id` (`dept_id`),
  KEY `division_id` (`division_id`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employee_history`
--

INSERT INTO `employee_history` (`emp_his_id`, `employee_id`, `pos_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `degree_name`, `university_name`, `cgp`, `passing_year`, `company_name`, `working_period`, `duties`, `supervisor`, `signature`, `is_admin`, `dept_id`, `division_id`, `maiden_name`, `state`, `city`, `zip`, `citizenship`, `duty_type`, `hire_date`, `original_hire_date`, `termination_date`, `termination_reason`, `voluntary_termination`, `rehire_date`, `rate_type`, `rate`, `pay_frequency`, `pay_frequency_txt`, `hourly_rate2`, `hourly_rate3`, `home_department`, `department_text`, `class_code`, `class_code_desc`, `class_acc_date`, `class_status`, `is_super_visor`, `super_visor_id`, `supervisor_report`, `dob`, `gender`, `country`, `marital_status`, `ethnic_group`, `eeo_class_gp`, `ssn`, `work_in_state`, `live_in_state`, `home_email`, `business_email`, `home_phone`, `business_phone`, `cell_phone`, `emerg_contct`, `emrg_h_phone`, `emrg_w_phone`, `emgr_contct_relation`, `alt_em_contct`, `alt_emg_h_phone`, `alt_emg_w_phone`) VALUES(162, 'EY2T1OWA', '4', 'jahangir', NULL, 'Ahmad', 'jahangir@gmail.com', '0195511016', NULL, NULL, NULL, './application/modules/employee/assets/images/2018-09-20/pra.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 15, 3, NULL, 'New York', 'New', 234234, 0, 1, '2018-09-19', '2018-09-19', '2018-09-19', 'sdfasdf', 2, '2018-09-26', 1, 323, 2, '234', 324234, 2523, '234', '234532', '', '', '1970-01-01', 1, NULL, '0', 'dfasdfsdf', '2018-09-19', 1, 'Bangladesh', 2, 'sunni', '234324', '23423', 1, 1, 'u@gmail.com', 'b@gmail.com', 'dsfsdf', 'dsfdsf', 'sdfsdf', '42342323', '234234', '234234', '2343', '234', '324234', '324324');
INSERT INTO `employee_history` (`emp_his_id`, `employee_id`, `pos_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `degree_name`, `university_name`, `cgp`, `passing_year`, `company_name`, `working_period`, `duties`, `supervisor`, `signature`, `is_admin`, `dept_id`, `division_id`, `maiden_name`, `state`, `city`, `zip`, `citizenship`, `duty_type`, `hire_date`, `original_hire_date`, `termination_date`, `termination_reason`, `voluntary_termination`, `rehire_date`, `rate_type`, `rate`, `pay_frequency`, `pay_frequency_txt`, `hourly_rate2`, `hourly_rate3`, `home_department`, `department_text`, `class_code`, `class_code_desc`, `class_acc_date`, `class_status`, `is_super_visor`, `super_visor_id`, `supervisor_report`, `dob`, `gender`, `country`, `marital_status`, `ethnic_group`, `eeo_class_gp`, `ssn`, `work_in_state`, `live_in_state`, `home_email`, `business_email`, `home_phone`, `business_phone`, `cell_phone`, `emerg_contct`, `emrg_h_phone`, `emrg_w_phone`, `emgr_contct_relation`, `alt_em_contct`, `alt_emg_h_phone`, `alt_emg_w_phone`) VALUES(165, '145454', '6', 'Hm', NULL, 'Isahaq', 'hmisahaq@gmail.com', '2344098234', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 14, 6, NULL, 'Alabama', 'deom', 3243, 0, 1, '2018-09-20', '2018-09-20', '2018-09-29', 'fsdf', 1, '2018-09-29', 1, 234, 3, '234', 0, 0, '', '', '', '', '1970-01-01', 1, NULL, '0', '324', '2018-09-29', 1, 'Bangladesh', 1, 'sdfsdf', '', '23423', 1, 1, '234', 'sd', '82309423', '', '234', '324234', '34242', '546456', '', '', '', '');
INSERT INTO `employee_history` (`emp_his_id`, `employee_id`, `pos_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `degree_name`, `university_name`, `cgp`, `passing_year`, `company_name`, `working_period`, `duties`, `supervisor`, `signature`, `is_admin`, `dept_id`, `division_id`, `maiden_name`, `state`, `city`, `zip`, `citizenship`, `duty_type`, `hire_date`, `original_hire_date`, `termination_date`, `termination_reason`, `voluntary_termination`, `rehire_date`, `rate_type`, `rate`, `pay_frequency`, `pay_frequency_txt`, `hourly_rate2`, `hourly_rate3`, `home_department`, `department_text`, `class_code`, `class_code_desc`, `class_acc_date`, `class_status`, `is_super_visor`, `super_visor_id`, `supervisor_report`, `dob`, `gender`, `country`, `marital_status`, `ethnic_group`, `eeo_class_gp`, `ssn`, `work_in_state`, `live_in_state`, `home_email`, `business_email`, `home_phone`, `business_phone`, `cell_phone`, `emerg_contct`, `emrg_h_phone`, `emrg_w_phone`, `emgr_contct_relation`, `alt_em_contct`, `alt_emg_h_phone`, `alt_emg_w_phone`) VALUES(166, 'EQLAJFUW', '6', 'Ainal', '', 'Haque', 'ainal@gmail.com', '01715234991', NULL, NULL, NULL, './application/modules/hrm/assets/images/2019-01-22/u.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 14, 0, NULL, 'Alabama', '', 0, 1, 1, '2018-11-12', '2018-11-12', '2018-11-12', '', 1, '2018-11-12', 1, 100, 1, '', 0, 0, '', '', '', '', '2018-11-12', 1, NULL, '0', '', '2018-11-12', 1, 'Bangladesh', 1, '', '', '3425', 1, 1, '', '', '017123657332', '', '017123657332', '017123657332', '017123657332', '017123657332', '', '', '', '');
INSERT INTO `employee_history` (`emp_his_id`, `employee_id`, `pos_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `degree_name`, `university_name`, `cgp`, `passing_year`, `company_name`, `working_period`, `duties`, `supervisor`, `signature`, `is_admin`, `dept_id`, `division_id`, `maiden_name`, `state`, `city`, `zip`, `citizenship`, `duty_type`, `hire_date`, `original_hire_date`, `termination_date`, `termination_reason`, `voluntary_termination`, `rehire_date`, `rate_type`, `rate`, `pay_frequency`, `pay_frequency_txt`, `hourly_rate2`, `hourly_rate3`, `home_department`, `department_text`, `class_code`, `class_code_desc`, `class_acc_date`, `class_status`, `is_super_visor`, `super_visor_id`, `supervisor_report`, `dob`, `gender`, `country`, `marital_status`, `ethnic_group`, `eeo_class_gp`, `ssn`, `work_in_state`, `live_in_state`, `home_email`, `business_email`, `home_phone`, `business_phone`, `cell_phone`, `emerg_contct`, `emrg_h_phone`, `emrg_w_phone`, `emgr_contct_relation`, `alt_em_contct`, `alt_emg_h_phone`, `alt_emg_w_phone`) VALUES(168, 'E97E9SJT', '6', 'Manik ', '', 'Hassan', 'manik@gmail.com', '01913251229', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 14, 0, NULL, 'Alabama', 'Dhaka', 143325, 1, 1, '2019-01-01', '2019-01-01', '2021-01-31', 'sdfs', 1, '2022-01-09', 2, 15000, 1, '', 0, 0, '', '', '', '', '2019-01-09', 1, NULL, '0', '', '1970-01-01', 1, 'Bangladesh', 1, '', '', 'e4dfg', 1, 1, '', '', '34353636', '', '3636', '345345', '3453', '3453', '', '', '', '');
INSERT INTO `employee_history` (`emp_his_id`, `employee_id`, `pos_id`, `first_name`, `middle_name`, `last_name`, `email`, `phone`, `alter_phone`, `present_address`, `parmanent_address`, `picture`, `degree_name`, `university_name`, `cgp`, `passing_year`, `company_name`, `working_period`, `duties`, `supervisor`, `signature`, `is_admin`, `dept_id`, `division_id`, `maiden_name`, `state`, `city`, `zip`, `citizenship`, `duty_type`, `hire_date`, `original_hire_date`, `termination_date`, `termination_reason`, `voluntary_termination`, `rehire_date`, `rate_type`, `rate`, `pay_frequency`, `pay_frequency_txt`, `hourly_rate2`, `hourly_rate3`, `home_department`, `department_text`, `class_code`, `class_code_desc`, `class_acc_date`, `class_status`, `is_super_visor`, `super_visor_id`, `supervisor_report`, `dob`, `gender`, `country`, `marital_status`, `ethnic_group`, `eeo_class_gp`, `ssn`, `work_in_state`, `live_in_state`, `home_email`, `business_email`, `home_phone`, `business_phone`, `cell_phone`, `emerg_contct`, `emrg_h_phone`, `emrg_w_phone`, `emgr_contct_relation`, `alt_em_contct`, `alt_emg_h_phone`, `alt_emg_w_phone`) VALUES(177, 'EMW7SH4L', '1', 'Kitchen1', NULL, '', 'kitchen@gmail.com', '01732432434', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 17, 0, NULL, 'Dhaka', 'D', 0, 1, 1, '2022-02-01', '2022-02-03', '2022-02-10', '', 1, '2022-02-10', 1, 200, 1, '', 0, 0, '', '', NULL, NULL, NULL, NULL, NULL, '0', '', '2001-02-02', 1, 'Bangladesh', 1, '', '', '', 1, 1, '', '', '65475675', '', '5676588', '678679790780', '5675686969', '7808089', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee_performance`
--

DROP TABLE IF EXISTS `employee_performance`;
CREATE TABLE IF NOT EXISTS `employee_performance` (
  `emp_per_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `note` varchar(50) NOT NULL,
  `date` varchar(50) NOT NULL,
  `note_by` varchar(50) NOT NULL,
  `number_of_star` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `updated_by` varchar(50) NOT NULL,
  PRIMARY KEY (`emp_per_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_payment`
--

DROP TABLE IF EXISTS `employee_salary_payment`;
CREATE TABLE IF NOT EXISTS `employee_salary_payment` (
  `emp_sal_pay_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `total_salary` varchar(50) NOT NULL,
  `total_working_minutes` varchar(50) NOT NULL,
  `working_period` varchar(50) NOT NULL,
  `payment_due` varchar(50) NOT NULL,
  `payment_date` varchar(50) NOT NULL,
  `paid_by` varchar(50) NOT NULL,
  `paymentgeneratedate` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`emp_sal_pay_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_setup`
--

DROP TABLE IF EXISTS `employee_salary_setup`;
CREATE TABLE IF NOT EXISTS `employee_salary_setup` (
  `e_s_s_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(30) NOT NULL,
  `sal_type` varchar(30) NOT NULL,
  `salary_type_id` varchar(30) NOT NULL,
  `amount` varchar(30) NOT NULL,
  `create_date` date DEFAULT NULL,
  `update_date` datetime(6) DEFAULT NULL,
  `update_id` varchar(30) NOT NULL,
  `gross_salary` float NOT NULL,
  PRIMARY KEY (`e_s_s_id`),
  KEY `employee_id` (`employee_id`),
  KEY `salary_type_id` (`salary_type_id`),
  KEY `update_id` (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `employee_salary_setup`
--

INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(44, 'EQLAJFUW', '1', '1', '35', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(45, 'EQLAJFUW', '1', '2', '10', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(46, 'EQLAJFUW', '1', '3', '5', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(47, 'EQLAJFUW', '1', '4', '0', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(48, 'EQLAJFUW', '1', '5', '0', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(49, 'EQLAJFUW', '1', '6', '0', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(50, 'EQLAJFUW', '1', '7', '0', '2022-12-12', NULL, '', 150);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(51, '145454', '1', '1', '35', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(52, '145454', '1', '2', '10', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(53, '145454', '1', '3', '5', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(54, '145454', '1', '4', '0', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(55, '145454', '1', '5', '0', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(56, '145454', '1', '6', '0', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(57, '145454', '1', '7', '0', '2022-12-12', NULL, '', 351);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(58, 'EY2T1OWA', '1', '1', '35', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(59, 'EY2T1OWA', '1', '2', '10', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(60, 'EY2T1OWA', '1', '3', '20', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(61, 'EY2T1OWA', '1', '4', '0', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(62, 'EY2T1OWA', '1', '5', '0', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(63, 'EY2T1OWA', '1', '6', '0', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(64, 'EY2T1OWA', '1', '7', '0', '2022-12-12', NULL, '', 532.95);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(73, 'E97E9SJT', '2', '1', '35', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(74, 'E97E9SJT', '2', '2', '15', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(75, 'E97E9SJT', '2', '3', '10', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(76, 'E97E9SJT', '2', '4', '0', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(77, 'E97E9SJT', '2', '5', '0', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(78, 'E97E9SJT', '2', '6', '0', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(79, 'E97E9SJT', '2', '7', '0', '2022-12-14', NULL, '', 24000);
INSERT INTO `employee_salary_setup` (`e_s_s_id`, `employee_id`, `sal_type`, `salary_type_id`, `amount`, `create_date`, `update_date`, `update_id`, `gross_salary`) VALUES(80, 'E97E9SJT', '2', '8', '0', '2022-12-14', NULL, '', 24000);

-- --------------------------------------------------------

--
-- Table structure for table `employee_sal_pay_type`
--

DROP TABLE IF EXISTS `employee_sal_pay_type`;
CREATE TABLE IF NOT EXISTS `employee_sal_pay_type` (
  `emp_sal_pay_type_id` int(10) UNSIGNED NOT NULL,
  `payment_period` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emp_attendance`
--

DROP TABLE IF EXISTS `emp_attendance`;
CREATE TABLE IF NOT EXISTS `emp_attendance` (
  `att_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `date` varchar(30) NOT NULL,
  `sign_in` varchar(30) DEFAULT NULL,
  `sign_out` varchar(30) DEFAULT NULL,
  `staytime` time DEFAULT NULL,
  PRIMARY KEY (`att_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

DROP TABLE IF EXISTS `expense`;
CREATE TABLE IF NOT EXISTS `expense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `type` varchar(100) NOT NULL,
  `voucher_no` varchar(50) NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_item`
--

DROP TABLE IF EXISTS `expense_item`;
CREATE TABLE IF NOT EXISTS `expense_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense_item_name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodvariable`
--

DROP TABLE IF EXISTS `foodvariable`;
CREATE TABLE IF NOT EXISTS `foodvariable` (
  `availableID` int(11) NOT NULL AUTO_INCREMENT,
  `availableCode` varchar(20) NOT NULL,
  `foodid` int(11) NOT NULL,
  `availtime` varchar(50) NOT NULL,
  `availday` varchar(30) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`availableID`),
  KEY `foodid` (`foodid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

DROP TABLE IF EXISTS `gender`;
CREATE TABLE IF NOT EXISTS `gender` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `gender_name`) VALUES(1, 'Male');
INSERT INTO `gender` (`id`, `gender_name`) VALUES(2, 'Female');
INSERT INTO `gender` (`id`, `gender_name`) VALUES(3, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `grand_loan`
--

DROP TABLE IF EXISTS `grand_loan`;
CREATE TABLE IF NOT EXISTS `grand_loan` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `permission_by` varchar(30) NOT NULL,
  `loan_details` varchar(30) NOT NULL,
  `amount` varchar(30) NOT NULL,
  `interest_rate` varchar(30) NOT NULL,
  `installment` varchar(30) NOT NULL,
  `installment_period` varchar(30) NOT NULL,
  `repayment_amount` varchar(30) NOT NULL,
  `date_of_approve` varchar(30) DEFAULT NULL,
  `repayment_start_date` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `updated_by` varchar(30) NOT NULL,
  `loan_status` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`loan_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ingredient_name` varchar(250) NOT NULL,
  `ingCode` varchar(20) DEFAULT NULL,
  `uom_id` int(11) NOT NULL,
  `stock_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_stock` decimal(10,2) NOT NULL DEFAULT 1.00,
  `storageunit` varchar(100) DEFAULT NULL,
  `conversion_unit` decimal(19,3) DEFAULT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=kitchenitems,1=otheritems',
  `type` int(11) DEFAULT 1 COMMENT '1=Row,2=finishgood',
  `is_addons` int(11) DEFAULT 0 COMMENT '1=Addons,0=all',
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  `itemid` int(11) DEFAULT NULL,
  `isIngedient` int(11) DEFAULT 1 COMMENT '1=show in list',
  `isMasterBranch` int(11) NOT NULL DEFAULT 0 COMMENT '1=from master branch and 0=default sub branch',
  PRIMARY KEY (`id`),
  KEY `uom_id` (`uom_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_settings_tbl`
--

DROP TABLE IF EXISTS `invoice_settings_tbl`;
CREATE TABLE IF NOT EXISTS `invoice_settings_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_temp_id` int(11) DEFAULT NULL,
  `normal_temp_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invoice_settings_tbl`
--

INSERT INTO `invoice_settings_tbl` (`id`, `pos_temp_id`, `normal_temp_id`) VALUES(1, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

DROP TABLE IF EXISTS `item_category`;
CREATE TABLE IF NOT EXISTS `item_category` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CatCode` varchar(200) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `CategoryImage` varchar(255) DEFAULT NULL,
  `Position` int(11) DEFAULT NULL,
  `CategoryIsActive` int(11) DEFAULT NULL,
  `offerstartdate` date DEFAULT '0000-00-00',
  `offerendate` date NOT NULL DEFAULT '0000-00-00',
  `isoffer` int(11) DEFAULT 0,
  `catcolor` varchar(50) DEFAULT NULL,
  `caticon` varchar(255) DEFAULT NULL,
  `parentid` int(11) DEFAULT 0,
  `showonweb` int(11) DEFAULT 0,
  `ordered_pos` int(11) DEFAULT 0,
  `UserIDInserted` int(11) NOT NULL DEFAULT 0,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  `UserIDUpdated` int(11) NOT NULL DEFAULT 0,
  `UserIDLocked` int(11) NOT NULL DEFAULT 0,
  `DateInserted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateUpdated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateLocked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isowncategory` int(11) NOT NULL,
  PRIMARY KEY (`CategoryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_foods`
--

DROP TABLE IF EXISTS `item_foods`;
CREATE TABLE IF NOT EXISTS `item_foods` (
  `ProductsID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryID` int(11) NOT NULL,
  `ProductName` varchar(255) DEFAULT NULL,
  `foodcode` varchar(255) DEFAULT NULL,
  `ProductImage` varchar(200) DEFAULT NULL,
  `bigthumb` varchar(255) NOT NULL,
  `medium_thumb` varchar(255) NOT NULL,
  `small_thumb` varchar(255) NOT NULL,
  `component` text DEFAULT NULL,
  `descrip` text DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `itemnotes` varchar(255) DEFAULT NULL,
  `menutype` varchar(25) DEFAULT NULL,
  `productvat` decimal(10,3) DEFAULT 0.000,
  `special` int(11) NOT NULL DEFAULT 0,
  `OffersRate` int(11) NOT NULL DEFAULT 0 COMMENT '1=offer rate',
  `offerIsavailable` int(11) NOT NULL DEFAULT 0 COMMENT '1=offer available,0=No Offer',
  `offerstartdate` date DEFAULT '0000-00-00',
  `offerendate` date DEFAULT '0000-00-00',
  `Position` int(11) DEFAULT NULL,
  `kitchenid` int(11) NOT NULL,
  `isgroup` int(11) DEFAULT NULL,
  `is_customqty` int(11) DEFAULT 0,
  `price_editable` int(11) DEFAULT 0,
  `withoutproduction` int(11) NOT NULL DEFAULT 0,
  `isIngredient` int(11) DEFAULT 0,
  `isfoodshowonweb` int(11) DEFAULT 0,
  `isstockvalidity` int(11) DEFAULT 0 COMMENT '0=no validity,1=validate',
  `cookedtime` time NOT NULL DEFAULT '00:00:00',
  `itemordering` int(11) DEFAULT 0,
  `ProductsIsActive` int(11) DEFAULT NULL,
  `vattypeid` int(11) DEFAULT NULL,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  `UserIDInserted` int(11) NOT NULL DEFAULT 0,
  `UserIDUpdated` int(11) NOT NULL DEFAULT 0,
  `UserIDLocked` int(11) NOT NULL DEFAULT 0,
  `DateInserted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateUpdated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `DateLocked` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ismainbr` int(11) DEFAULT 0,
  `tax0` text DEFAULT NULL,
  `tax1` text DEFAULT NULL,
  `tax2` text DEFAULT NULL,
  PRIMARY KEY (`ProductsID`),
  KEY `CategoryID` (`CategoryID`),
  KEY `kitchenid` (`kitchenid`),
  KEY `unit` (`unit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_advertisement`
--

DROP TABLE IF EXISTS `job_advertisement`;
CREATE TABLE IF NOT EXISTS `job_advertisement` (
  `job_adv_id` int(10) UNSIGNED NOT NULL,
  `pos_id` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `adv_circular_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `circular_dadeline` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `adv_file` tinytext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `adv_details` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  KEY `pos_id` (`pos_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_stock`
--

DROP TABLE IF EXISTS `kitchen_stock`;
CREATE TABLE IF NOT EXISTS `kitchen_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `stock_qty` float(10,2) NOT NULL,
  `kitchen_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kitchen_stock_new`
--

DROP TABLE IF EXISTS `kitchen_stock_new`;
CREATE TABLE IF NOT EXISTS `kitchen_stock_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assign_inventory_main_id` int(11) DEFAULT NULL,
  `reedem_id` int(11) DEFAULT NULL,
  `so_request_id` int(11) DEFAULT NULL,
  `kitchen_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock` double(10,2) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0 = StockIn, 1 = StockOut',
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` varchar(100) NOT NULL,
  `english` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3440 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2, 'login', 'Login');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3, 'email', 'Email Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(4, 'password', 'Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(5, 'reset', 'Reset');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(6, 'dashboard', 'Dashboard');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(7, 'home', 'Home');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(8, 'profile', 'Profile');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(9, 'profile_setting', 'Profile Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(10, 'firstname', 'First Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(11, 'lastname', 'Last Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(12, 'about', 'About');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(13, 'preview', 'Preview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(14, 'image', 'Image');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(15, 'save', 'Save');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(16, 'upload_successfully', 'Upload Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(17, 'user_added_successfully', 'User Added Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(18, 'please_try_again', 'Please Try Again...');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(19, 'inbox_message', 'Inbox Messages');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(20, 'sent_message', 'Sent Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(21, 'message_details', 'Message Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(22, 'new_message', 'New Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(23, 'receiver_name', 'Receiver Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(24, 'sender_name', 'Sender Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(25, 'subject', 'Subject');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(26, 'message', 'Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(27, 'message_sent', 'Message Sent!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(28, 'ip_address', 'IP Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(29, 'last_login', 'Last Login');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(30, 'last_logout', 'Last Logout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(31, 'status', 'Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(32, 'delete_successfully', 'Delete Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(33, 'send', 'Send');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(34, 'date', 'Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(35, 'action', 'Action');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(36, 'sl_no', 'SL No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(37, 'are_you_sure', 'Are You Sure ? ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(38, 'application_setting', 'Application Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(39, 'application_title', 'Application Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(40, 'address', 'Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(41, 'phone', 'Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(42, 'favicon', 'Favicon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(43, 'logo', 'Logo');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(44, 'language', 'Language');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(45, 'left_to_right', 'Left To Right');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(46, 'right_to_left', 'Right To Left');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(47, 'footer_text', 'Footer Text');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(48, 'site_align', 'Application Alignment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(49, 'welcome_back', 'Welcome Back!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(50, 'please_contact_with_admin', 'Please Contact With Admin');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(51, 'incorrect_email_or_password', 'Incorrect Email/Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(52, 'select_option', 'Select Option');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(53, 'ftp_setting', 'Data Synchronize [FTP Setting]');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(54, 'hostname', 'Host Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(55, 'username', 'User Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(56, 'ftp_port', 'FTP Port');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(57, 'ftp_debug', 'FTP Debug');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(58, 'project_root', 'Project Root');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(59, 'update_successfully', 'Update Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(60, 'save_successfully', 'Save Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(61, 'delete_successfully', 'Delete Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(62, 'internet_connection', 'Internet Connection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(63, 'ok', 'Okay');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(64, 'not_available', 'Not Available');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(65, 'available', 'Available');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(66, 'outgoing_file', 'Outgoing File');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(67, 'incoming_file', 'Incoming File');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(68, 'data_synchronize', 'Data Synchronize');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(69, 'unable_to_upload_file_please_check_configuration', 'Unable to upload file! please check configuration');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(70, 'please_configure_synchronizer_settings', 'Please configure synchronizer settings');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(71, 'download_successfully', 'Download Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(72, 'unable_to_download_file_please_check_configuration', 'Unable to download file! please check configuration');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(73, 'data_import_first', 'Data Import First');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(74, 'data_import_successfully', 'Data Import Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(75, 'unable_to_import_data_please_check_config_or_sql_file', 'Unable to Import Data! Please Check Configuration / SQL File.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(76, 'download_data_from_server', 'Download Data from Server');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(77, 'data_import_to_database', 'Data Import To Database');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(79, 'data_upload_to_server', 'Data Upload to Server');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(80, 'please_wait', 'Please Wait');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(81, 'ooops_something_went_wrong', ' Ops Something Went Wrong...');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(82, 'module_permission_list', 'Module Permission List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(83, 'user_permission', 'User Permission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(84, 'add_module_permission', 'Add Module Permission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(85, 'module_permission_added_successfully', 'Module Permission Added Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(86, 'update_module_permission', 'Update Module Permission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(87, 'download', 'Download');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(88, 'module_name', 'Module Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(89, 'create', 'Create');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(90, 'read', 'Read');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(91, 'update', 'Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(92, 'delete', 'Delete');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(93, 'module_list', 'Module List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(94, 'add_module', 'Add Module');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(95, 'directory', 'Module Directory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(96, 'description', 'Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(97, 'image_upload_successfully', 'Image Upload Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(98, 'module_added_successfully', 'Module Added Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(99, 'inactive', 'Inactive');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(100, 'active', 'Active');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(101, 'user_list', 'User List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(102, 'see_all_message', 'See All Messages');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(103, 'setting', 'Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(104, 'logout', 'Logout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(105, 'admin', 'Admin');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(106, 'add_user', 'Add User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(107, 'user', 'User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(108, 'module', 'Module');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(109, 'new', 'New');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(110, 'inbox', 'Inbox');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(111, 'sent', 'Sent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(112, 'synchronize', 'Synchronize');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(113, 'data_synchronizer', 'Data Synchronizer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(114, 'module_permission', 'Module Permission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(115, 'backup_now', 'Backup Now!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(116, 'restore_now', 'Restore Now!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(117, 'backup_and_restore', 'Backup and Restore');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(118, 'captcha', 'Captcha Word');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(119, 'database_backup', 'Database Backup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(120, 'restore_successfully', 'Restore Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(121, 'backup_successfully', 'Backup Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(122, 'filename', 'File Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(123, 'file_information', 'File Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(124, 'size', 'Size');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(125, 'backup_date', 'Backup Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(126, 'overwrite', 'Overwrite');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(127, 'invalid_file', 'Invalid File!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(128, 'invalid_module', 'Invalid Module');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(129, 'remove_successfully', 'Remove Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(130, 'install', 'Install');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(131, 'uninstall', 'Uninstall');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(132, 'tables_are_not_available_in_database', 'Tables are not available in database.sql');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(133, 'no_tables_are_registered_in_config', 'No tables are registered in config.php');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(134, 'enquiry', 'Enquiry');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(135, 'read_unread', 'Read/Unread');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(136, 'enquiry_information', 'Enquiry Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(137, 'user_agent', 'User Agent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(138, 'checked_by', 'Checked By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(139, 'new_enquiry', 'New Enquiry');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(140, 'crud', 'Crud');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(141, 'view', 'View');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(142, 'name', 'Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(143, 'add', 'Add');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(144, 'ph', 'Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(145, 'cid', 'SL No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(146, 'view_atn', 'Attendance View');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(147, 'mang', 'Employee Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(148, 'designation', 'Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(149, 'test', 'Test');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(150, 'sl', 'SL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(151, 'bdtask', 'BDTASK');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(152, 'practice', 'Practice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(153, 'branch_name', 'Branch Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(154, 'chairman_name', 'Chairman');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(155, 'b_photo', 'Photo');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(156, 'b_address', 'Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(157, 'position', 'Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(158, 'advertisement', 'Advertisement');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(159, 'position_name', 'Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(160, 'position_details', 'Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(161, 'circularprocess', 'Recruitment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(162, 'pos_id', 'Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(163, 'adv_circular_date', 'Publish Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(164, 'circular_dadeline', 'Deadline');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(165, 'adv_file', 'Documents');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(166, 'adv_details', 'Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(167, 'attendance', 'Attendance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(168, 'employee', 'Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(169, 'emp_id', 'Employee Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(170, 'sign_in', 'Sign In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(171, 'sign_out', 'Sign Out');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(172, 'staytime', 'Stay Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(173, 'abc', 'abc');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(174, 'first_name', 'First Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(175, 'last_name', 'Last Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(176, 'alter_phone', 'Alternative Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(177, 'present_address', 'Present Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(178, 'parmanent_address', 'Permanent Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(179, 'candidateinfo', 'Candidate Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(180, 'add_advertisement', 'Add Advertisement');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(181, 'advertisement_list', 'Manage Advertisement ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(182, 'candidate_basic_info', 'Candidate Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(183, 'can_basicinfo_list', 'Manage Candidate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(184, 'add_canbasic_info', 'Add New Candidate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(185, 'candidate_education_info', 'Candidate Educational Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(186, 'can_educationinfo_list', 'Candidate Edu Info List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(187, 'add_edu_info', 'Add Educational Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(188, 'can_id', 'Candidate Id');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(189, 'degree_name', 'Obtained Degree');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(190, 'university_name', 'University');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(191, 'cgp', 'CGPA');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(192, 'comments', 'Comments');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(193, 'signature', 'Signature');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(194, 'candidate_workexperience', 'Candidate Work Experience');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(195, 'can_workexperience_list', 'Work Experience List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(196, 'add_can_experience', 'Add Work Experience');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(197, 'company_name', 'Company Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(198, 'working_period', 'Working Period');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(199, 'duties', 'Duties');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(200, 'supervisor', 'Supervisor');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(201, 'candidate_workexpe', 'Candidate Work Experience');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(202, 'candidate_shortlist', 'Candidate Shortlist');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(203, 'shortlist_view', 'Manage Shortlist');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(204, 'add_shortlist', 'Add Shortlist');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(205, 'date_of_shortlist', 'Shortlist Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(206, 'interview_date', 'Interview Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(207, 'submit', 'Submit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(208, 'candidate_id', 'Your ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(209, 'job_adv_id', 'Job Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(210, 'sequence', 'Sequence');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(211, 'candidate_interview', 'Interview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(212, 'interview_list', 'Interview list');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(213, 'add_interview', 'Add interview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(214, 'interviewer_id', 'Interviewer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(215, 'interview_marks', 'Viva Marks');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(216, 'written_total_marks', 'Written Total Marks');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(217, 'mcq_total_marks', 'MCQ Total Marks');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(218, 'recommandation', 'Recommendation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(219, 'selection', 'Selection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(220, 'details', 'Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(221, 'candidate_selection', 'Candidate Selection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(222, 'selection_list', 'Selection List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(223, 'add_selection', 'Add Selection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(224, 'employee_id', 'Employee Id');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(225, 'position_id', '1');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(226, 'selection_terms', 'Selection Terms');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(227, 'total_marks', 'Total Marks');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(228, 'photo', 'Picture');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(229, 'your_id', 'Your ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(230, 'change_image', 'Change Photo');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(231, 'picture', 'Photograph');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(232, 'ad', 'Add');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(233, 'write_y_p_info', 'Write Your Personal Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(234, 'emp_position', 'Employee Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(235, 'add_pos', 'Add Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(236, 'list_pos', 'List of Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(237, 'emp_salary_stup', 'Employee Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(238, 'add_salary_stup', 'Add Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(239, 'list_salarystup', 'List of Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(240, 'emp_sal_name', 'Salary Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(241, 'emp_sal_type', 'Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(242, 'emp_performance', 'Employee Performance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(243, 'add_performance', 'Add Performance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(244, 'list_performance', 'List of Performance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(245, 'note', 'Note');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(246, 'note_by', 'Note By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(247, 'number_of_star', 'Number of Star');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(248, 'updated_by', 'Updated By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(249, 'emp_sal_payment', 'Manage Employee Salary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(250, 'add_payment', 'Add Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(251, 'list_payment', 'List of payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(252, 'total_salary', 'Total Salary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(253, 'total_working_minutes', 'Working Hour');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(254, 'payment_due', 'Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(255, 'payment_date', 'Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(256, 'paid_by', 'Paid By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(257, 'view_employee_payment', 'Employee Payment List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(258, 'sal_payment_type', 'Salary Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(259, 'add_payment_type', 'Add Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(260, 'list_payment_type', 'List of Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(261, 'payment_period', 'Payment Period');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(262, 'payment_type', 'Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(263, 'time', 'Punch Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(264, 'shift', 'Shift');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(265, 'location', 'Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(266, 'logtype', 'Log Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(267, 'branch', 'Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(268, 'student', 'Students');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(269, 'csv', 'CSV');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(270, 'save_successfull', 'Your Data Save Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(271, 'successfully_updated', 'Your Data Successfully Updated');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(272, 'atn_form', 'Attendance Form');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(273, 'atn_report', 'Attendance Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(274, 'end_date', 'To');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(275, 'start_date', 'From');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(276, 'done', 'Done');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(277, 'employee_id_se', 'Write Employee Id or name here ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(278, 'attendance_repor', 'Attendance Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(279, 'e_time', 'End Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(280, 's_time', 'Start Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(281, 'atn_datewiserer', 'Date Wise Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(282, 'atn_report_id', 'Date And Id base Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(283, 'atn_report_time', 'Date And Time report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(284, 'payroll', 'Payroll');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(285, 'loan', 'Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(286, 'loan_grand', 'Grant Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(287, 'add_loan', 'Add Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(288, 'loan_list', 'List of Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(289, 'loan_details', 'Loan Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(290, 'amount', 'Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(291, 'interest_rate', 'Interest Percentage');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(292, 'installment_period', 'Installment Period');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(293, 'repayment_amount', 'Repayment Total');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(294, 'date_of_approve', 'Approved Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(295, 'repayment_start_date', 'Repayment From');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(296, 'permission_by', 'Permitted By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(297, 'grand', 'Grand');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(298, 'installment', 'Installment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(299, 'loan_status', 'Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(300, 'installment_period_m', 'Installment Period in Month');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(301, 'successfully_inserted', 'Your loan Successfully Granted');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(302, 'loan_installment', 'Loan Installment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(303, 'add_installment', 'Add Installment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(304, 'installment_list', 'List of Installment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(305, 'loan_id', 'Loan No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(306, 'installment_amount', 'Installment Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(307, 'payment', 'Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(308, 'received_by', 'Receiver');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(309, 'installment_no', 'Install No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(310, 'notes', 'Notes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(311, 'paid', 'Paid');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(312, 'loan_report', 'Loan Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(313, 'e_r_id', 'Enter Your Employee ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(314, 'leave', 'Leave');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(315, 'add_leave', 'Add Leave');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(316, 'list_leave', 'List of Leave');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(317, 'dayname', 'Weekly Leave Day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(318, 'holiday', 'Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(319, 'list_holiday', 'List of Holidays');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(320, 'no_of_days', 'Number of Days');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(321, 'holiday_name', 'Holiday Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(322, 'set', 'Set');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(323, 'tax', 'Tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(324, 'tax_setup', 'Tax Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(325, 'add_tax_setup', 'Add Tax Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(326, 'list_tax_setup', 'List of Tax setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(327, 'tax_collection', 'Tax collection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(328, 'start_amount', 'Start Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(329, 'end_amount', 'End Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(330, 'rate', 'Tax Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(331, 'date_start', 'Date Start');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(332, 'amount_tax', 'Tax Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(333, 'collection_by', 'Collection By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(334, 'date_end', 'Date End');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(335, 'income_net_period', 'Income  Net period');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(336, 'default_amount', 'Default Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(337, 'add_sal_type', 'Add Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(338, 'list_sal_type', 'Salary Type List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(339, 'salary_type_setup', 'Salary Type Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(340, 'salary_setup', 'Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(341, 'add_sal_setup', 'Add Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(342, 'list_sal_setup', 'Salary Setup List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(343, 'salary_type_id', 'Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(344, 'salary_generate', 'Salary Generate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(345, 'add_sal_generate', 'Generate Now');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(346, 'list_sal_generate', 'Generated Salary List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(347, 'gdate', 'Generate Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(348, 'start_dates', 'Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(349, 'generate', 'Generate ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(350, 'successfully_saved_saletup', ' Set up Successful');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(351, 's_date', 'Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(352, 'e_date', 'End Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(353, 'salary_payable', 'Payable Salary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(354, 'tax_manager', 'Tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(355, 'generate_by', 'Generated By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(356, 'successfully_paid', 'Successfully Paid');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(357, 'direct_empl', ' Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(358, 'add_emp_info', 'Add New Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(359, 'new_empl_pos', 'Add New Employee Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(360, 'manage', 'Manage Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(361, 'ad_advertisement', 'ADD POSITION');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(362, 'moduless', 'Modules');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(363, 'next', 'Next');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(364, 'finish', 'Finish');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(365, 'request', 'Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(366, 'successfully_saved', 'Your Data Successfully Saved');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(367, 'sal_type', 'Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(368, 'sal_name', 'Salary Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(369, 'leave_application', 'Leave Application');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(370, 'apply_strt_date', 'Application Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(371, 'apply_end_date', 'Application End date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(372, 'leave_aprv_strt_date', 'Approved Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(373, 'leave_aprv_end_date', 'Approved End Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(374, 'num_aprv_day', 'Approved Day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(375, 'reason', 'Reason');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(376, 'approve_date', 'Approved Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(377, 'leave_type', 'Leave Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(378, 'apply_hard_copy', 'Application Hard Copy');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(379, 'approved_by', 'Approved By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(380, 'notice', 'Notice Board');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(381, 'noticeboard', 'Notice Board');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(382, 'notice_descriptiion', 'Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(383, 'notice_date', 'Notice Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(384, 'notice_type', 'Notice Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(385, 'notice_by', 'Notice By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(386, 'notice_attachment', 'Attachment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(387, 'account_name', 'Account Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(388, 'account_type', 'Account Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(389, 'account_id', 'Account Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(390, 'transaction_description', 'Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(391, 'payment_id', 'Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(392, 'create_by_id', 'Created By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(393, 'account', 'Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(394, 'account_add', 'Add Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(395, 'account_transaction', 'Transaction');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(396, 'award', 'Award');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(397, 'new_award', 'New Award');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(398, 'award_name', 'Award Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(399, 'aw_description', 'Award Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(400, 'awr_gift_item', 'Gift Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(401, 'awarded_by', 'Award By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(402, 'employee_name', 'Employee Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(403, 'employee_list', 'Atn List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(404, 'department', 'Department');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(405, 'department_name', 'Department Name ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(406, 'clockout', 'Clock Out');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(407, 'se_account_id', 'Select Account Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(408, 'division', 'Division');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(409, 'add_division', 'Add Division');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(410, 'update_division', 'Update Division');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(411, 'division_name', 'Division Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(412, 'division_list', 'Manage Division ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(413, 'designation_list', 'Designation List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(414, 'manage_designation', 'Manage Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(415, 'add_designation', 'Add Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(416, 'select_division', 'Select Division');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(417, 'select_designation', 'Select Designation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(418, 'asset', 'Asset');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(419, 'asset_type', 'Asset Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(420, 'add_type', 'Add Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(421, 'type_list', 'Type List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(422, 'type_name', 'Type Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(423, 'select_type', 'Select Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(424, 'equipment_name', 'Equipment Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(425, 'model', 'Model');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(426, 'serial_no', 'Serial No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(427, 'equipment', 'Equipment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(428, 'add_equipment', 'Add Equipment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(429, 'equipment_list', 'Equipment List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(430, 'type', 'Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(431, 'equipment_maping', 'Equipment Mapping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(432, 'add_maping', 'Add Mapping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(433, 'maping_list', 'Mapping List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(434, 'update_equipment', 'Update Equipment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(435, 'select_employee', 'Select Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(436, 'select_equipment', 'Select Equipment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(437, 'basic_info', 'Basic Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(438, 'middle_name', 'Middle Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(439, 'state', 'State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(440, 'city', 'City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(441, 'zip_code', 'Zip Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(442, 'maiden_name', 'Maiden Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(443, 'add_employee', 'Add Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(444, 'manage_employee', 'Manage Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(445, 'employee_update_form', 'Employee Update Form');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(446, 'what_you_search', 'What You Search');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(447, 'search', 'Search');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(448, 'duty_type', 'Duty Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(449, 'hire_date', 'Hire Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(450, 'original_h_date', 'Original Hire Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(451, 'voluntary_termination', 'Voluntary Termination');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(452, 'termination_reason', 'Termination Reason');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(453, 'termination_date', 'Termination Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(454, 're_hire_date', 'Re Hire Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(455, 'rate_type', 'Rate Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(456, 'pay_frequency', 'Pay Frequency');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(457, 'pay_frequency_txt', 'Pay Frequency Text');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(458, 'hourly_rate2', 'Hourly Rate2');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(459, 'hourly_rate3', 'Hourly Rate3');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(460, 'home_department', 'Home Department');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(461, 'department_text', 'Department Text');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(462, 'benifit_class_code', 'Benefit Class code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(463, 'benifit_desc', 'Benefit Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(464, 'benifit_acc_date', 'Benefit Accrual Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(465, 'benifit_sta', 'Benefit Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(466, 'super_visor_name', 'Supervisor Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(467, 'is_super_visor', 'Is Supervisor');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(468, 'supervisor_report', 'Supervisor Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(469, 'dob', 'Date of Birth');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(470, 'gender', 'Gender');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(471, 'marital_stats', 'Marital Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(472, 'ethnic_group', 'Ethnic Group');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(473, 'eeo_class_gp', 'EEO Class');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(474, 'ssn', 'SSN');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(475, 'work_in_state', 'Work in State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(476, 'live_in_state', 'Live in State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(477, 'home_email', 'Home Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(478, 'business_email', 'Business Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(479, 'home_phone', 'Home Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(480, 'business_phone', 'Business Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(481, 'cell_phone', 'Cell Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(482, 'emerg_contct', 'Emergency Contact');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(483, 'emerg_home_phone', 'Emergency Home Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(484, 'emrg_w_phone', 'Emergency Work Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(485, 'emer_con_rela', 'Emergency Contact Relation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(486, 'alt_em_contct', 'Alter Emergency Contact');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(487, 'alt_emg_h_phone', 'Alt Emergency Home Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(488, 'alt_emg_w_phone', 'Alt Emergency  Work Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(489, 'reports', 'Reports');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(490, 'employee_reports', 'Employee Reports');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(491, 'demographic_report', 'Demographic Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(492, 'posting_report', 'Positional Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(493, 'custom_report', 'Custom Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(494, 'benifit_report', 'Benefit Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(495, 'demographic_info', 'Demographical Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(496, 'positional_info', 'Positional Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(497, 'assets_info', 'Assets Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(498, 'custom_field', 'Custom Field');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(499, 'custom_value', 'Custom Data');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(500, 'adhoc_report', 'Adhoc Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(501, 'asset_assignment', 'Asset Assignment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(502, 'assign_asset', 'Assign Assets');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(503, 'assign_list', 'Assign List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(504, 'update_assign', 'Update Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(505, 'citizenship', 'Citizenship');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(506, 'class_sta', 'Class status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(507, 'class_acc_date', 'Class Accrual date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(508, 'class_descript', 'Class Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(509, 'class_code', 'Class Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(510, 'return_asset', 'Return Assets');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(511, 'dept_id', 'Department ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(512, 'parent_id', 'Parent ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(513, 'equipment_id', 'Equipment ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(514, 'issue_date', 'Issue Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(515, 'damarage_desc', 'Damarage Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(516, 'return_date', 'Return Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(517, 'is_assign', 'Is Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(518, 'emp_his_id', 'Employee History ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(519, 'damarage_descript', 'Damage Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(520, 'return', 'Return');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(521, 'return_successfull', 'Return Successful');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(522, 'return_list', 'Return List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(523, 'custom_data', 'Custom Data');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(524, 'passing_year', 'Passing Year');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(525, 'is_admin', 'Is Admin');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(526, 'zip', 'Zip Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(527, 'original_hire_date', 'Original Hire Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(528, 'rehire_date', 'Rehire Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(529, 'class_code_desc', 'Class Code Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(530, 'class_status', 'Class Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(531, 'super_visor_id', 'Supervisor ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(532, 'marital_status', 'Marital Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(533, 'emrg_h_phone', 'Emergency Home Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(534, 'emgr_contct_relation', 'Emergency Contact Relation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(535, 'id', 'ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(536, 'type_id', 'Equipment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(537, 'custom_id', 'Custom ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(538, 'custom_data_type', 'Custom Data Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(539, 'role_permission', 'Role Permission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(540, 'permission_setup', 'Permission Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(541, 'add_role', 'Add Role');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(542, 'role_list', 'Role List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(543, 'user_access_role', 'User Access Role');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(544, 'menu_item_list', 'Menu Item List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(545, 'ins_menu_for_application', 'Ins Menu  For Application');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(546, 'menu_title', 'Menu Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(547, 'page_url', 'Page URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(548, 'parent_menu', 'Parent Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(549, 'role', 'Role');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(550, 'role_name', 'Role Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(551, 'single_checkin', 'Single Check In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(552, 'bulk_checkin', 'Bulk Check In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(553, 'manage_attendance', 'Manage Attendance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(554, 'attendance_list', 'Attendance List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(555, 'checkin', 'Check In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(556, 'checkout', 'Check Out');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(557, 'stay', 'Stay');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(558, 'attendance_report', 'Attendance Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(559, 'work_hour', 'Work Hour');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(560, 'cancel', 'Cancel');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(561, 'confirm_clock', 'Confirm Checkout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(562, 'add_attendance', 'Add Attendance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(563, 'upload_csv', 'Upload CSV');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(564, 'import_attendance', 'Import Attendance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(565, 'manage_account', 'Manage Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(566, 'add_account', 'Add Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(567, 'add_new_account', 'Add New Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(568, 'account_details', 'Account Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(569, 'manage_transaction', 'Manage Transaction');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(570, 'add_expence', 'Add Experience');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(571, 'add_income', 'Add Income');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(572, 'return_now', 'Return Now !!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(573, 'manage_award', 'Manage Award');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(574, 'add_new_award', 'Add New Award');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(575, 'personal_information', 'Personal Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(576, 'educational_information', 'Educational Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(577, 'past_experience', 'Past Experience');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(578, 'basic_information', 'Basic Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(579, 'result', 'Result');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(580, 'institute_name', 'Institute Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(581, 'education', 'Education');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(582, 'manage_shortlist', 'Manage Short List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(583, 'manage_interview', 'Manage Interview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(584, 'manage_selection', 'Manage Selection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(585, 'add_new_dept', 'Add New Department');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(586, 'manage_dept', 'Manage Department');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(587, 'successfully_checkout', 'Checkout Successful !');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(588, 'grant_loan', 'Grant Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(589, 'successfully_installed', 'Successfully Installed');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(590, 'total_loan', 'Total Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(591, 'total_amount', 'Total Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(592, 'filter', 'Filter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(593, 'weekly_holiday', 'Weekly Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(594, 'manage_application', 'Manage Application');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(595, 'add_application', 'Add Application');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(596, 'manage_holiday', 'Manage Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(597, 'add_more_holiday', 'Add More Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(598, 'manage_weekly_holiday', 'Manage Weekly Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(599, 'add_weekly_holiday', 'Add Weekly Holiday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(600, 'manage_granted_loan', 'Manage Granted Loan');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(601, 'manage_installment', 'Manage Installment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(602, 'add_new_notice', 'Add New Notice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(603, 'manage_notice', 'Manage Notice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(604, 'salary_type', 'Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(605, 'manage_salary_generate', 'Manage Salary Generate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(606, 'generate_now', 'Generate Now');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(607, 'add_salary_setup', 'Add Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(608, 'manage_salary_setup', 'Manage Salary Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(609, 'add_salary_type', 'Add Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(610, 'manage_salary_type', 'Manage Salary Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(611, 'manage_tax_setup', 'Manage Tax Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(612, 'setup_tax', 'Setup Tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(613, 'add_more', 'Add More');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(614, 'tax_rate', 'Tax Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(615, 'no', 'No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(616, 'setup', 'Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(617, 'biographicalinfo', 'Bio-Graphical Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(618, 'positional_information', 'Positional Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(620, 'benifits', 'Benefits');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(621, 'others_leave_application', 'Others Leave');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(622, 'add_leave_type', 'Add Leave Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(623, 'others_leave', 'Apply Leave');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(624, 'number_of_leave_days', 'Number of Leave Days');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(625, 'itemmanage', 'Food Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(626, 'manage_category', 'Manage Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(627, 'add_category', 'Add Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(628, 'category_list', 'Category List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(629, 'manage_food', 'Manage Food');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(630, 'add_food', 'Add Food');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(631, 'food_list', 'Food List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(632, 'category_name', 'Category Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(633, 'food_name', 'Food Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(634, 'category_subtitle', 'Category Subtitle');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(635, 'update_category', 'Update Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(636, 'update_fooditem', 'Update Food Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(713, 'food_list', 'Food List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(714, 'food_name', 'Food Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(715, 'add_category', 'Add Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(716, 'add_food', 'Add Food');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(717, 'category_name', 'Category Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(718, 'category_list', 'Category List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(719, 'itemmanage', 'Food Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(720, 'manage_category', 'Manage Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(721, 'manage_food', 'Manage Food');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(722, 'offerdate', 'Offer Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(723, 'manage_addons', 'Manage Add-ons');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(724, 'add_adons', 'Add Add-ons');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(725, 'menu_addons', 'Add-ons Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(726, 'addons_list', 'Add-ons List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(727, 'assign_adons', 'Add-ons Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(728, 'assign_adons_list', 'Add-ons Assign List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(729, 'update_adons', 'Update Add-ons');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(730, 'item_name', 'Food Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(731, 'price', 'Price');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(732, 'offerenddate', 'Offer End Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(733, 'units', 'Unit and Ingredients');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(734, 'manage_unitmeasurement', 'Unit Measurement');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(735, 'unit_list', 'Unit Measurement List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(736, 'unit_add', 'Add Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(737, 'unit_update', 'Unit Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(738, 'unit_name', 'Unit Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(739, 'manage_ingradient', 'Manage Ingredients');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(740, 'ingradient_list', 'Ingredient List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(741, 'add_ingredient', 'Add Ingredient');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(742, 'ingredient_name', 'Ingredient Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(743, 'unit_short_name', 'Short Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(744, 'update_ingredient', 'Update Ingredient');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(745, 'component', 'Components');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(746, 'vat_tax', 'Vat');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(747, 'addons_name', 'Add-ons Name ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(748, 'food_varient', 'Food Variant');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(749, 'food_availablity', 'Food Availability');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(750, 'add_varient', 'Add Variant');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(751, 'varient_name', 'Variant Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(752, 'variant_list', 'Variant List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(753, 'variant_edit', 'Update Variant');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(754, 'food_availablelist', 'Food Available List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(755, 'add_availablity', 'Add Available Day & Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(756, 'edit_availablity', 'Update Available Day & Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(757, 'available_day', 'Available Day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(758, 'available_time', 'Available Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(759, 'membership_management', 'Membership Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(760, 'membership_list', 'Membership List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(761, 'membership_name', 'Membership Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(762, 'discount', 'Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(763, 'other_facilities', 'Other Facilities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(764, 'membership_add', 'Add Membership');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(765, 'membership_edit', 'Update Membership');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(766, 'payment_setting', 'Payment Method Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(767, 'paymentmethod_list', 'Payment Method List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(768, 'payment_add', 'Add Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(769, 'payment_edit', 'Update Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(770, 'payment_name', 'Payment Method Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(771, 'shipping_setting', 'Shipping Method Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(772, 'shipping_list', 'Shipping Method List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(773, 'shipping_name', 'Shipping Method Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(774, 'shipping_add', 'Add Shipping Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(775, 'shipping_edit', 'Update Shipping Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(776, 'shippingrate', 'Shipping Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(777, 'supplier_manage', 'Supplier Manage');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(778, 'supplier_name', 'Supplier Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(779, 'supplier_list', 'Supplier List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(780, 'mobile', 'Mobile');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(781, 'address', 'Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(782, 'supplier_add', 'Add Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(783, 'supplier_edit', 'Update Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(784, 'purchase_item', 'Manage Purchases');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(785, 'purchase', 'Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(786, 'purchase_list', 'Purchase List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(787, 'purchase_add', 'Add Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(788, 'purchase_edit', 'Update Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(789, 'quantity', 'Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(790, 'supplier_information', 'Supplier Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(791, 'add_new_order', 'Add New Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(792, 'pending_order', 'Pending Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(793, 'processing_order', 'Processing Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(794, 'cancel_order', 'Cancel Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(795, 'complete_order', 'Complete Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(796, 'pos_invoice', 'POS Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(797, 'ordermanage', 'Manage Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(798, 'table_manage', 'Manage Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(799, 'table_edit', 'Update Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(800, 'table_list', 'Table List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(801, 'table_name', 'Table Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(802, 'customer_type', 'Customer Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(803, 'customertype_list', 'Customer Type List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(804, 'production', 'Production');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(805, 'add_table', 'Table Add');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(806, 'table_add', 'Add Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(807, 'add_new_table', 'Add Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(808, 'order_list', 'Order List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(809, 'currency', 'Currency');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(810, 'currency_list', 'Currency List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(811, 'currency_name', 'Currency Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(812, 'currency_add', 'Add Currency');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(813, 'currency_edit', 'Update Currency');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(814, 'currency_icon', 'Currency Icon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(815, 'currency_rate', 'Conversion Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(816, 'report', 'Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(817, 'purchase_report', 'Purchases Report(Invoice)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(818, 'purchase_report_ingredient', 'Stock Report(Ingredients)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(819, 'stock_report', 'Stock Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(820, 'sell_report', 'Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(821, 'stock_report_product_wise', 'Stock Report(Production)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(822, 'accounts', 'Accounts');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(823, 'c_o_a', 'Chart of Accounts');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(824, 'debit_voucher', 'Debit Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(825, 'credit_voucher', 'Credit Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(826, 'contra_voucher', 'Contra Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(827, 'journal_voucher', 'Journal Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(828, 'voucher_approval', 'Voucher Approval');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(829, 'account_report', 'Accounts Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(830, 'voucher_report', 'Voucher Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(831, 'cash_book', 'Cash Book');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(832, 'bank_book', 'Bank Book');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(833, 'general_ledger', 'General Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(834, 'trial_balance', 'Trial Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(835, 'profit_loss', 'Profit Loss');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(836, 'cash_flow', 'Cash Flow');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(837, 'coa_print', 'COA Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(838, 'in_quantity', 'In Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(839, 'out_quantity', 'Out Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(840, 'stock', 'Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(841, 'find', 'Find');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(842, 'from_date', 'From');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(843, 'to_date', 'To');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(844, 'approved', 'Approved');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(845, 'total_ammount', 'Total Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(846, 'total_purchase', 'Total Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(847, 'total_sale', 'Total Sale');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(848, 'csv_file_informaion', 'CSV File Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(849, 'import_product_csv', 'Import product (CSV)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(850, 'set_productionunit', 'Set Production Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(851, 'production_set_list', 'Production Set List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(852, 'production_add', 'Add Production');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(853, 'production_list', 'Production List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(854, 'billing_from', 'Billing From');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(855, 'invoice', 'Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(856, 'invoice_no', 'Invoice No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(857, 'billing_date', 'Billing Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(858, 'billing_to', 'Billing To');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(859, 'reservation', 'Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(860, 'take_reservation', 'Take A Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(861, 'update_table', 'Table Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(862, 'reserve_time', 'Reservation Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(863, 'reservation_table', 'Add Booking');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(864, 'table_setting', 'Table Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(865, 'capacity', 'Capacity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(866, 'icon', 'Icon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(867, 'purchase_return', 'Purchases Return');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(868, 'purchase_qty', 'Purchase Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(869, 'return_qty', 'Return Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(870, 'total', 'Total');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(871, 'select', 'Select');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(872, 'return_invoice', 'Return Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(873, 'invoice_view', 'View Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(874, 'grand_total', 'Grand Total');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(875, 'supplier', 'Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(876, 'po_no', 'Invoice No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(877, 'grant', 'Grant');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(878, 'hrm', 'Human Resource');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(879, 'departmentfrm', 'Add Department');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(880, 'benefits', 'Benefits');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(881, 'class', 'Class');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(882, 'biographical_info', 'Biographical Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(883, 'additional_address', 'Additional Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(884, 'custom', 'Custom');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(885, 'pay_now', 'Pay Now ??');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(886, 'paymentmethod_setup', 'Payment Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(887, 'add_paymentsetup', 'Add New Payment Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(888, 'edit_setup', 'Update Setup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(889, 'marchantid', 'Marchant ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(890, 'order_successfully', 'Your Payment was Completed!!!.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(891, 'order_fail', 'Payment Incomplete!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(892, 'voucher_no', 'Voucher No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(893, 'remark', 'Remark');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(894, 'code', 'Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(895, 'debit', 'Debit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(896, 'credit', 'Credit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(897, 'template_name', 'Template Name ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(898, 'sms_template', 'SMS Template');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(899, 'sms_template_warning', 'Please Use \\\"{id}\\\" format without quotation to set dynamic value inside template');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(900, 'userid', 'User ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(901, 'from', 'From');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(902, 'opening_cash_and_equivalent', 'Opening Cash & Equivalent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(903, 'amount_in_Dollar', 'Amount In Dollar');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(904, 'pre_balance', 'Pre Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(905, 'current_balance', 'Current Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(906, 'with_details', 'With Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(907, 'credit_account_head', 'Credit Account Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(908, 'gl_head', 'GL Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(909, 'transaction_head', 'Transaction Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(910, 'confirm', 'Confirm');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(911, 's_rate', 'Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(912, 'web_setting', 'Web Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(913, 'banner_setting', 'Banner Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(914, 'menu_setting', 'Menu Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(915, 'widget_setting', 'Widget Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(916, 'add_banner', 'Add Banner');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(917, 'bannertype', 'Add Banner Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(918, 'banner_list', 'Banner List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(919, 'title', 'Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(920, 'subtitle', 'Sub Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(921, 'banner_type', 'Banner Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(922, 'link_url', 'Link URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(923, 'banner_edit', 'Banner Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(924, 'menu_name', 'Menu Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(925, 'menu_url', 'Menu Slug');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(926, 'sub_menu', 'Sub Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(927, 'add_menu', 'Add Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(928, 'parent_menu', 'Parent Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(929, 'widget_name', 'Widget Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(930, 'widget_title', 'Widget Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(931, 'widget_desc', 'Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(932, 'add_widget', 'Add New');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(933, 'common_setting', 'Common Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(934, 'bannersize', 'Banner Size');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(935, 'width', 'Width');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(936, 'height', 'Height');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(937, 'exclusive', 'Exclusive');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(938, 'best_Offers', 'Best Offer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(939, 'invalid_size', 'Invalid Size');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(940, 'confirm_reservation', 'Confirm Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(941, 'food_details', 'Food Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(942, 'email_setting', 'Email Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(943, 'contact_email_list', 'Contact List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(944, 'subscribelist', 'Subscribe List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(945, 'contact_send', 'Your Contact Information Send Successfully.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(946, 'couponlist', 'Coupon List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(947, 'add_coupon', 'Add Coupon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(948, 'coupon_Code', 'Coupon Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(949, 'coupon_rate', 'Coupon Value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(950, 'coupon_startdate', 'Start Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(951, 'coupon_enddate', 'End Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(952, 'coupon_edit', 'Update Coupon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(953, 'rating', 'Rating ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(954, 'add_rating', 'Add Rating');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(955, 'reviewtxt', 'Review Text');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(956, 'rating_edit', 'Rating Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(957, 'customer_rating', 'Customer Rating');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(958, 'country_list', 'Country List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(959, 'countryname', 'Country Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(960, 'add_country', 'Add Country');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(961, 'edit_country', 'Update Country');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(962, 'add_state', 'Add State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(963, 'edit_state', 'State Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(964, 'state', 'State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(965, 'city', 'City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(966, 'add_city', 'Add City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(967, 'edit_city', 'City Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(968, 'country', 'Country');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(969, 'state_list', 'State List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(970, 'city_list', 'All City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(971, 'server_setting', 'App Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(972, 'netip', 'Your Local Host Full URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(973, 'ip_port', 'Your Online URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(974, 'onlinebdname', 'Online Database Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(975, 'dbuser', 'Database User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(976, 'dbpassword', 'Database Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(977, 'dbhost', 'Database Host Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(978, 'social_setting', 'Social Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(979, 'url_link', 'URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(980, 'sicon', 'Select Icon');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(981, 'ord_failed', 'Order Failed!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(982, 'failed_msg', 'Order not placed due to some reason. Please Try Again!!!. Thank You !!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(983, 'ord_succ', 'Order Placed Successfully!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(984, 'succ_smg', 'Are you Sure to Print This Invoice????');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(985, 'no_order_run', 'No Order Running');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(986, 'thirdpartycustomer_list', 'Third-Party Customers');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(987, '3rd_customer_list', 'Third-Party Customers List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(988, '3rdcompany_name', 'Company Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(989, 'add_3rdparty_comapny', 'Add New Company');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(990, 'update_3rdparty', 'Update Company');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(991, 'commision', 'Commission Rate (%)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(992, 'list_of_card_terminal', 'Card Terminal List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(993, 'add_new_terminal', 'Add New Terminal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(994, 'update_terminal', 'Update Terminal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(995, 'card_terminal_name', 'Card Terminal Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(996, 'list_of_bank', 'Bank List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(997, 'add_bank', 'Add New Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(998, 'update_bank', 'Update Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(999, 'bank_name', 'Bank Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1000, 'sell_report_filter', 'Sale Report Filtering');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1001, 'sms_setting', 'SMS Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1002, 'sms_configuration', 'SMS Configuration');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1003, 'sms_temp', 'SMS Template');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1004, 'candidate_name', 'Candidate Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1005, 'assign1_role', 'Assign Role');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1006, 'customer_list', 'Customer List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1007, 'customer_name', 'Customer Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1008, 'update_ord', 'Update Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1009, 'final_report', 'Final Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1010, 'ehrm', 'HRM');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1011, 'add_expense_item', 'Add Expense Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1012, 'manage_expense_item', 'Manage Expense Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1013, 'add_expense', 'Add Expense');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1014, 'manage_expense', 'Manage Expense');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1015, 'expense_statement', 'Expense Statement');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1016, 'expense_type', 'Expense Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1017, 'expense_item_name', 'Expense Item Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1018, 'expense', 'Expense');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1020, 'signature_pic', 'Signature Picture');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1021, 'branch1', 'Branch');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1022, 'ac_no', 'A/C Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1023, 'ac_name', 'A/C Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1024, 'bank_transaction', 'Bank Transaction');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1025, 'bank', 'Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1026, 'withdraw_deposite_id', 'Withdraw / Deposit ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1027, 'bank_ledger', 'Bank Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1028, 'note_name', 'Note Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1029, 'balance', 'Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1030, 'previous_balance', 'Previous Credit Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1031, 'manage_supplier_ledger', 'Manage Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1032, 'supplier_ledger', 'Supplier Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1033, 'print', 'Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1034, 'select_supplier', 'Select Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1035, 'deposite_id', 'Deposit ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1036, 'print_date', 'Print Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1037, 'manage_bank', 'Manage Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1038, 'add_new_bank', 'Add New Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1039, 'bank_list', 'Bank List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1040, 'bank_edit', 'Bank Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1041, 'debit_plus', 'Debit (+)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1042, 'credit_minus', 'Credit (-)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1043, 'withdraw_deposite_id', 'Withdraw / Deposit ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1044, 'cash_adjustment', 'Cash Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1045, 'adjustment_type', 'Adjustment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1046, 'supplier_payment', 'Supplier Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1047, 'prepared_by', 'Prepared By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1048, 'authorized_signature', 'Authorized Signature');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1049, 'chairman', 'Chairman');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1050, 'kitchen_dashboard', 'Kitchen Dashboard');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1051, 'counter_dashboard', 'Counter Dashboard');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1052, 'nw_order', 'New Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1053, 'ongoingorder', 'On Going Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1054, 'tdayorder', 'Today Orders');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1055, 'onlineord', 'Online Order ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1056, 'table', 'Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1057, 'waiter', 'Waiter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1058, 'del_company', 'Delivery Company');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1059, 'cookedtime', 'Cooking Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1060, 'ord_num', 'Order Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1061, 'cmplt', 'Complete');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1062, 'sl_payment', 'Select Your Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1063, 'paymd', 'Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1064, 'crd_terminal', 'Card Terminal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1065, 'sl_bank', 'Select Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1066, 'lstdigit', 'Last 4 Digit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1067, 'cuspayment', 'Customer Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1068, 'cng_amount', 'Changes Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1069, 'pay_print', 'Pay Now & Print Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1070, 'payn', 'Pay Now');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1071, 'ordid', 'Order ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1072, 'can_reason', 'Cancel Reason');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1073, 'can_ord', 'Cancel Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1074, 'close', 'Close');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1075, 'add_customer', 'Add Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1076, 'fav_addesrr', 'Favorite Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1077, 'tabltno', 'Table No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1078, 'ordate', 'Order Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1079, 'payment_status', 'Payment Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1080, 'ordtcoun', 'Counter Waiting Display');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1081, 'remtime', 'Remaining Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1082, 'ordtime', 'Order Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1083, 'ord', 'Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1084, 'tok', 'Token');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1085, 'view_ord', 'View Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1086, 'fdready', 'Food Ready');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1087, 'fdnready', 'Food Not Ready');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1088, 'foodrfs', 'Food is Ready for Served!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1089, 'foodnrfs', 'Food Not Ready for Served');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1090, 'ordready', 'Order Ready');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1091, 'sele_by_date', 'Sale By Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1092, 'withdetails', 'With Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1093, 'topeneqv', 'Total Opening Cash & Cash Equivalent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1094, 'cashopen', 'Cashflow from Operating Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1095, 'payact', 'Payment for Other Operating Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1096, 'cash_gand_lie', 'Cash generated from Operating Activities before Changing in Operating Assets & Liabilities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1097, 'casfactive', 'Cashflow from Non Operating Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1098, 'cashnonlia', 'Cash generated from Non Operating Activities before Changing in Operating Assets & Liabilities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1099, 'incdre', 'Increase/Decrease in Operating Assets & Liabilities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1100, 'Tincdre', 'Total Increase/Decrease');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1101, 'netopenactv', 'Net Cash From Operating/Non Operating Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1102, 'cfact', 'Cash Flow from Investing Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1103, 'ncuia', 'Net Cash Used Investing Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1104, 'cfffa', 'Cash Flow from Financing Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1105, 'netcufa', 'Net  Cash Used Financing Activities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1106, 'ncio', 'Net Cash Inflow/Outflow');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1107, 'pflos', 'Profit Loss');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1108, 'clcEq', 'Closing Cash & Cash Equivalent:');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1109, 'TcccE', 'Total Closing Cash & Cash Equivalent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1110, 'pp_by', 'Prepared By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1111, 'act', 'Accounts');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1112, 'ausig', 'Authorized Signature');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1113, 'particls', 'Particulars');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1114, 'back', 'Back');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1115, 'bk_vouchar', 'Bank Book Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1116, 'errorajdata', 'Error get data from ajax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1117, 'reach_limit', 'You have reached the limit of adding');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1118, 'inpt', 'inputs');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1119, 'cantdel', 'There only one row you can\'t delete.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1120, 'slsuplier', 'Select Supplier');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1121, 'ptype', 'Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1122, 'casp', 'Cash Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1123, 'bnkp', 'Bank Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1124, 'slbank', 'Select Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1125, 'cscrv', 'Cash Credit Voucher');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1126, 'ac_code', 'Account Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1127, 'ac_head', 'Account Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1128, 'iword', 'In word');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1129, 'ac_office', 'Accounts Officer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1130, 'latestv', 'Latest version');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1131, 'after19', 'Auto Update Feature working On  after 1.9');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1132, 'crver', 'Current version');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1133, 'notesupdate', 'note: strongly recommended to backup your <b>SOURCE FILE</b> and <b>DATABASE</b> before update.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1134, 'noupdates', 'No Update available');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1135, 'lic_pur_key', 'License/Purchase key');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1136, 'lifeord', 'Total Orders');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1137, 'tdaysell', 'Today Sales Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1138, 'tcustomer', 'Total Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1139, 'tdeliv', 'Total Delivered');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1140, 'treserv', 'Total Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1141, 'latestord', 'Latest Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1142, 'latest_reser', 'Latest Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1143, 'ord_number', 'Order No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1144, 'latestolorder', 'Latest Online Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1145, 'monsalamntorder', 'Monthly Sales Amount and Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1146, 'onlineofline', 'Online Vs Offline Order & Sales');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1147, 'pending_ord', 'Pending Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1148, 'onlinesamnt', 'Online Sale Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1149, 'onlineordnum', 'Online Order Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1150, 'offsalamnt', 'Offline Sale Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1151, 'offlordnum', 'Offline Order Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1152, 'saleamnt', 'Sale Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1153, 'ordnumb', 'Order Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1154, 'store_name', 'Store Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1155, 'opent', 'Available On');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1156, 'closeTime', 'Closing Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1157, 'sldistype', 'Select Discount Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1158, 'distype', 'Discount Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1159, 'percent', 'Percent');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1160, 'sl_se_ch_ty', 'Select Service Charge Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1161, 'vatset', 'VAT Setting(%)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1162, 'mindeltime', 'Min. Delivery Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1163, 'dateformat', 'Date Format');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1164, 'sedateformat', 'Seletet Date Format');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1165, 'add_menu_item', 'Add Menu Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1166, 'menu_title', 'Menu Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1167, 'can_create', 'Can Create');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1168, 'can_read', 'Can Read');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1169, 'can_edit', 'Can Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1170, 'can_delete', 'Can Delete');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1171, 'smsrankgateway', 'To get <b>50</b> free SMS from smsrank.com click');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1172, 'ranktext', ' and register in registration section click Already Envato user and put your envato purchase key and product id  after registration put your username and password into the password and user name field this form.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1173, 'managementsection', 'This Section is Use Only for Store Management.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1174, 'width', 'Width');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1175, 'protocal', 'Protocol');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1176, 'mailpath', 'Mail Path');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1177, 'Mail_type', 'Mail Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1178, 'smtp_host', 'SMTP Host');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1179, 'smtp_post', 'SMTP Port');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1180, 'sender_email', 'Sender Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1181, 'smtp_password', 'SMTP Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1183, 'powered_by', 'Powered By Text');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1184, 'item_information', 'Item Information');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1185, 'size', 'Size');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1186, 'qty', 'Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1187, 'addons_name', 'Add-ons Name ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1188, 'addons_qty', 'Add-ons Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1189, 'add_to_cart', 'Add To Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1190, 'item', 'Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1191, 'unit_price', 'Unit Price');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1192, 'total_price', 'Total Price');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1193, 'order_status', 'Order Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1194, 'served', 'Served');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1195, 'cancel_reason', 'Cancel Reason');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1196, 'customer_order', 'Customer Notes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1197, 'customerpicktime', 'Customer Pick-up Date and time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1198, 'subtotal', 'Subtotal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1199, 'service_chrg', 'Service Charge');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1200, 'customer_paid_amount', 'Customer Paid Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1201, 'change_due', 'Change Due');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1202, 'total_due', 'Total Due');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1203, 'powerbybdtask', 'Powered  By: BDTASK, www.bdtask.com');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1204, 'recept', 'Receipt  No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1205, 'orderno', 'Order No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1206, 'ref_page', 'Refresh Page');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1207, 'orderid', 'Order ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1208, 'all', 'All');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1209, 'vat_tax1', 'Vat/Tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1210, 'ord_uodate_success', 'Order Update Successfully!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1211, 'do_print_token', 'Do you Want to Print Token No.???');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1212, 'req_failed', 'Request Failed, Please check your code and try again!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1213, 'ord_places', 'Order Placed Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1214, 'do_print_in', 'Do you Want to Print Invoice???');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1215, 'ord_complte', 'Order Completed');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1216, 'ord_com_sucs', 'Order Completed Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1217, 'token_no', 'Token NO');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1218, 'qr-order', 'QR Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1219, 'cuschange', 'Customer Change');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1220, 'order_successfully_placed', 'Order Has Been Placed Successfully!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1221, 'kitchen_setting', 'Kitchen Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1222, 'kitchen_name', 'Kitchen Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1223, 'kitchen_user_assign', 'Assign Kitchen User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1224, 'kitchen_list', 'Kitchen List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1225, 'add_kitchen', 'Add Kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1226, 'kitchen_assign', 'Kitchen Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1227, 'kitchen_edit', 'Kitchen Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1228, 'please_try_again_userassign', 'This user is already assign in this kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1229, 'select_kitchen', 'Select Kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1230, 'memberid', 'Member ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1231, 'member_name', 'Member Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1232, 'add_member', 'Add New Member');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1233, 'update_member', 'Update Member');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1234, 'member_list', 'Member List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1236, 'meminfo', 'Member Manage');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1237, 'blocked', 'Blocked');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1238, 'memberid_exist', 'Member ID Already Exists. Please Try Another.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1239, 'add_new_payment_type', 'Add New Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1240, 'sell_report_items', 'Item Wise Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1241, 'sell_report_waiters', 'Waiters Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1242, 'sell_report_delvirytype', ' Type-Wise Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1243, 'sell_report_casher', 'Details Sales(Cashier)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1244, 'ready_all_ietm', 'All Item Ready');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1245, 'unpaid_sell', 'Unpaid Sale');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1246, 'kitchen_sell', 'Kitchen Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1247, 'order_total', 'Total Order ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1248, 'scharge_report', 'Service Charge Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1249, 'seo_setting', 'SEO Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1250, 'seo_title', 'Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1251, 'seo_keyword', 'Keyword');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1252, 'seo_description', 'Description');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1253, 'pos_setting', 'POS Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1257, 'buy_now', 'Buy Now');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1264, 'purchase_key', 'Purchase Key');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1271, 'kitchen_status', 'Kitchen Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1278, 'habittrack', 'Customer Habit List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1279, 'review_rating', 'Review & Rating');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1280, 'pos_setting', 'POS Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1285, 'please_wait', 'Please Wait');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1286, 'month', 'Month');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1287, 'sl_option', 'Select Option');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1288, 'sl_product', 'Search Product');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1289, 'quickorder', 'Quick Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1290, 'placeorder', 'Place Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1291, 'type_slorder', 'Type and Select Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1292, 'mergeord', 'Merge Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1293, 'Processingod', 'Processing...');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1294, 'sLengthMenu', 'Display _MENU_ records per page');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1295, 'sInfo', 'Showing _START_ to _END_ of _TOTAL_ entries');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1296, 'sInfoEmpty', 'Showing 0 to 0 of 0 entries');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1297, 'sInfoFiltered', '(Filtered from _MAX_ Total Records)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1298, 'sLoadingRecords', 'Loading...');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1299, 'sZeroRecords', 'Nothing found - sorry');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1300, 'sEmptyTable', 'No Data Available in Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1301, 'sFirst', 'First');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1302, 'sLast', 'Last');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1303, 'sPrevious', 'Previous');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1304, 'sNext', 'Next');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1305, 'sSortAscending', 'Activate to sort column ascending');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1306, 'sSortDescending', 'Activate to Sort Column Descending');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1307, '_sign', 'Show %d Rows');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1308, '_0sign', 'No Row Selected');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1309, '_1sign', '1 Line Selected');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1310, 'copy', 'Copy');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1312, 'excel', 'Excel');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1313, 'pdf', 'Pdf');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1314, 'colvis', 'Column Visibility');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1316, 'no_orderfound', 'No Order Found!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1317, 'prepared', 'Prepared');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1318, 'accept', 'Accept');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1319, 'reject', 'Reject');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1320, 'ready', 'Ready');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1321, 'proccessing', 'Processing');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1322, 'kitnotacpt', 'Kitchen Not Accept');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1425, 'person', 'Person');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1426, 'before_time', 'Running Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1427, 'select_this_table', 'Select This Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1428, 'seat', 'Seat');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1429, 'seat_time', 'Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1430, '+', 'Add');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1431, 'clear', 'Clear');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1432, 'no_customer', 'No Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1433, 'table_map', 'Table Map');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1434, 'add', 'Add');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1435, 'itemsincart', 'Item(s) in Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1436, 'view_cart', 'View Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1437, 'morderlist', 'My Order List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1438, 'edit', 'Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1439, 'foodde', 'Food Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1440, 'cartlist', 'Cart List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1441, 'subtotal', 'Subtotal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1442, 'ordnote', 'Order Notes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1443, 'upsummery', 'Update Summery');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1444, 'upsumlist', 'Update Summery List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1445, 'mkpayment', 'Make Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1446, 'foodnote', 'Food Note');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1447, 'addnotesi', 'Add Note');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1448, 'thirdparty_orderid', 'Third-party Order ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1456, 'themes', 'Themes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1457, 'menu_type', 'Menu Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1458, 'add_menu_type', 'Add Menu Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1459, 'menutype_edit', 'Menu Type Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1460, 'menu_type_name', 'Menu Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1461, 'storetime', 'Manage Store Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1462, 'day_name', 'Day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1463, 'saturday', 'Saturday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1464, 'sunday', 'Sunday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1465, 'monday', 'Monday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1466, 'tuesday', 'Tuesday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1467, 'wednesday', 'Wednesday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1468, 'thursday', 'Thursday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1469, 'friday', 'Friday');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1470, 'footer_logo', 'Footer Logo');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1471, 'contact_us', 'Contact Us');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1472, 'opening_time', 'Available On');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1473, 'ourstore', 'Our Store');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1474, 'call_reservation', 'Call for Reservations');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1475, 'item_available', 'Items Available');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1479, 'membership_card', 'Bar Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1480, 'barcode_start', 'From Barcode');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1481, 'barcode_end', 'To Barcode');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1494, 'commission', 'Sales Commission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1495, 'sale_by_table', 'Tables Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1496, 'stock_limit', 'Re-Stock Level');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1497, 'ingredients', 'Ingredients');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1498, 'stock_out_ingredients', 'Stock Out Ingredients');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1499, 'office_addres1', 'Office Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1500, 'call_us', 'Call Us');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1501, 'email_us', 'Email Us');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1502, 'upload_theme', 'Upload Theme');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1503, 'discount_type', 'Discount Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1504, 'confirm_password', 'Confirm Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1559, 'wastemangment', 'Waste Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1590, 'add_group_item', 'Add Group Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1591, 'update_group_item', 'Update Group Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1592, 'production_setting', 'Production Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1593, 'select_auto', 'Select auto Production');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1594, 'split', 'Split');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1595, 'tinvat', 'TIN OR VAT NUM.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1596, 'bill', 'Bill');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1597, 'checkin', 'Check In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1598, 'checkout', 'Check Out');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1599, 'totalpayment', 'Total payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1600, 'thanssuport', 'Thank You for Your Support');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1601, 'thanks_you', 'Thank you very much');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1602, 'opening_balance', 'Opening Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1603, 'transaction_date', 'Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1604, 'voucher_type', 'Voucher Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1605, 'particulars', 'Head Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1606, 'total_empolyee', 'Total Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1607, 'apply_day', 'Days');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1608, 'loan_no', 'Loan No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1609, 'add_floor', 'Add Floor');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1610, 'floor_name', 'Floor Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1611, 'edit_floor', 'Edit Floor');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1612, 'floor_list', 'Floor List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1613, 'floor_select', 'Floor Select');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1614, 'add_to_cart_more', 'Cart & More');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1615, 'kitchen_printers', 'Kitchen printer Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1616, 'printer_list', 'Printer List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1617, 'add_printer', 'Add Printer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1618, 'ip_port', 'Your Online URL');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1625, 'counter_list', 'Counter List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1626, 'add_counter', 'Add Counter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1627, 'edit_counter', 'Edit Counter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1628, 'counter_no', 'Counter Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1629, 'add_opening_balance', 'Add Opening Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1630, 'add_closing_balance', 'Add Closing Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1632, 'sell_report_cashregister', 'Cash Register Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1633, 'closing_balance', 'Closing Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1634, 'factory_reset', 'Factory Reset');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1635, 'fresettext', 'Note: Strongly recommended to backup your SOURCE file and DATABASE before resetting because all transactional data will be cleared after running the factory reset.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1636, 'bill_by', 'Bill By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1640, 'type_table', 'Type and Select Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1643, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1645, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1646, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1648, 'sound_setting', 'Sound Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1649, 'is_sound', 'Is Sound Enable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1650, 'upload_notify', 'Upload Notification Sound');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1651, 'upload_order', 'Upload order Add Sound');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1655, 'room_list', 'Room List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1656, 'add_room', 'Add Room');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1657, 'room_no', 'Room No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1658, 'room_qr', 'All Room QR');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1659, 'restaurant_closed', 'Restaurant is Closed!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1660, 'closed_msg', 'You order Only when restaurant is open. Our opening and closing Time is:');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1661, 'privactp', 'Privacy Policy');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1662, 'terms_condition', 'Terms & conditions');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1663, 'refundp', 'Refund Policies');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1664, 'reservation_on_off', 'Reservation On Off');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1665, 'unavailable_day', 'Unavailable Day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1666, 'unavaildate', 'Unavailable Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1667, 'add_unavailablity', 'Add Unavailability');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1668, 'edit_unavailablity', 'Edit Unavailability');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1669, 'unavailable_time', 'Unavailable Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1670, 'max_reserveperson', 'Max Reserve Person');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1671, 'reservasetting', 'Reservation Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1672, 'webon', 'Website ON');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1673, 'weboff', 'Website Off');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1674, 'webdisable', 'Web site ON/Off');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1675, 'placr_setting', 'Place order Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1676, 'quick_ord', 'Quick Order Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1677, 'shippingtime', 'Shipping Date & Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1678, 'search_food_item', 'Search Food Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1679, 'search_category', 'Search Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1680, 'check_availablity', 'Check Availability');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1681, 'subscribe_paragraph', 'Subscribe to Receive Our Weekly Promotion');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1682, 'shipping_method', 'Shipping Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1683, 'please_select_shipping_method', 'Please Select Shipping Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1684, 'autoupdate', 'Auto Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1685, 'coa_head', 'COA Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1686, 'apps_addons', 'Apps Add-ons');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1687, 'download_apps_playstore', 'Please Download Apps on Playstore');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1688, 'kitchen_app', 'Kitchen App');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1689, 'waiter_app', 'Waiter App');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1690, 'customer_app', 'Customer App');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1691, 'if_you_need_the_above_all_apps', 'If you need the above all apps, please feel free to contact us.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1692, 'do_you_want_proceed', 'Do You Want to Proceed?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1693, 'is_offer', 'Offer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1694, 'is_special', 'Special');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1695, 'is_custome_quantity', 'Custom Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1696, 'kitchenitemsell', 'Kitchen Sell');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1697, 'due_marge', 'Due Merge');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1698, 'book_table', 'Book Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1699, 'reserve_table', 'Reserve Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1700, 'see_more', 'See More');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1701, 'food_name', 'Food Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1702, 'category', 'Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1703, 'search', 'Search');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1704, 'read_more', 'Read more');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1705, 'item_has_been_successfully_added', 'Item has been successfully added');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1706, 'add_to_cart', 'Add To Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1707, 'view_full_menu', 'View Full Menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1708, 'home', 'Home');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1709, 'subscribe_to_newsletter', 'Subscribe to Newsletter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1710, 'subscribe', 'subscribe');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1711, 'get_directions', 'Get Directions');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1712, 'teams_of_use', 'Teams of use');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1713, 'privacy_policy', 'Privacy Policy');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1714, 'contact', 'Contact');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1715, 'please_enter_your_email', 'Please Enter Your email !!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1716, 'please_enter_valid_email', 'Please enter a valid Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1717, 'thanks_for_subscription', 'Thanks for Subscription');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1718, 'note_added', 'Note Added');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1719, 'posting_failed', 'Posting failed');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1720, 'our_service_is_closed_on_this_date_and_time', 'Our service is Closed on this date and time !!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1721, 'reservation_time_closed_try_later', 'Reservation Time is closed!! Please try later.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1722, 'select_date', 'Please select date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1723, 'select_time', 'Please select Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1724, 'enter_number_of_people', 'Please enter the number of people');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1725, 'select_after_hour_current_time', 'Please select after 1 hour to Current time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1726, 'no_free_seat_to_the_reservation', 'Currently, there is no free seat to the reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1727, 'search_topics_or_keywords', 'Search topics or keywords');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1728, 'no_data_found', 'No Data Found');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1729, 'please_wait', 'Please Wait');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1730, 'reservation_contact', 'Contact No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1731, 'reservation_time', 'Expected Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1732, 'reservation_date', 'Expected Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1733, 'reservation_person', 'Total Person');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1734, 'deal_of_the_day', 'Deal of the day');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1735, 'cart', 'Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1736, 'unavailable', 'Unavailable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1737, 'write_comments', 'Write Your Comments');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1738, 'get_in_touch', 'Get In Touch');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1739, 'forgot_password', 'Forgot Password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1740, 'shopping_details_information_msg', 'If you have shopped with us before, please enter your details in the boxes below.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1741, 'remember_me', 'Remember Me');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1742, 'or', 'OR');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1743, 'register', 'Register');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1744, 'enter_your_phone_or_email', 'Please enter your Phone or Email.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1745, 'password_not_empty', 'Password Not Empty.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1746, 'failed_login_msg', 'Failed Login: Check your Email and password!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1747, 'email_not_registered_msg', 'Failed: Email has not been registered yet.!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1748, 'have_been_sent_email', 'Success: We have been sent an email to this');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1749, 'check_your_new_password', 'Email Address. Please check your New Password..!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1750, 'profile_picture', 'Profile Picture');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1751, 'my_profile', 'My Profile');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1752, 'my_reservation', 'My Reservation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1753, 'profile_update', 'Profile Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1754, 'name', 'Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1755, 'returning_customer', 'Returning customer?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1756, 'click_login', 'Click here to login');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1757, 'checkout_msg', 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer, please proceed to the Billing & Shipping section.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1758, 'username_or_email', 'Username or Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1759, 'billing_address', 'Billing Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1760, 'select_country', 'Select Country');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1761, 'select_state', 'Select State');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1762, 'town_city', 'Town / City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1763, 'select_city', 'Select City');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1764, 'street_address', 'Street Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1765, 'postcode_zip', 'Postcode / ZIP');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1766, 'create_account', 'Create an Account?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1767, 'create_account_password', 'Create account password');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1768, 'shipping_different_address', 'Ship to a Different Address?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1769, 'your_order', 'Your Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1770, 'product', 'Product');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1771, 'total_vat', 'Total VAT');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1772, 'coupon_discount', 'Coupon Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1773, 'service', 'Service');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1774, 'tag', 'Tag');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1775, 'review', 'Review');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1776, 'average_user_rating', 'Average User Rating');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1777, 'rating_breakdown', 'Rating Breakdown');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1778, 'complete_success', '100% Complete (success)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1779, '80_complete_primary', '80% Complete (primary)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1780, '60_complete_info', '60% Complete (info)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1781, '40_complete_warning', '40% Complete (warning)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1782, '20_complete_danger', '20% Complete (danger)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1783, 'rate_it', 'Rate It');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1784, 'french_chicken_burger_tomato_sauce', 'French Chicken Burger With Hot tomato Sauce');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1785, 'review_submit', 'Review Submit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1786, 'related_items', 'Related Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1787, 'pickup', 'Pickup');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1788, 'dine_in', 'Dine-in');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1789, 'enter_coupon_code', 'Enter coupon code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1790, '00_15_min', '00:15 MIN');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1791, 'go_to_checkout', 'Go to Checkout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1798, 'timezone', 'Time Zome');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1799, 'discountrate', 'Discount Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1800, 'vat', 'Vat');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1801, 'loan_issue_id', 'Loan Issue ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1802, 'repayment', 'Re-payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1803, 'loan_report_details', 'Loan Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1804, 'balance_sheet', 'Balance Sheet');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1805, 'purdate', 'Purchase Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1806, 'expdate', 'Expiry Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1807, 'parent_cat', 'Parent Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1808, 'set_productioncost', 'Set Production Cost Per Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1809, 'set_productionunit', 'Set Production Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1810, 'production_set', 'Production Set');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1811, 'production_set_for', 'Production Set For');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1812, 'serving_unit', 'Serving Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1813, 'purdate', 'Purchase Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1814, 'expdate', 'Expiry Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1815, 'parent_cat', 'Parent Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1816, 'set_productioncost', 'Set Production Cost Per Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1817, 'set_productionunit', 'Set Production Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1818, 'production_set', 'Production Set');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1819, 'production_set_for', 'Production Set For');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1820, 'serving_unit', 'Serving Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1821, 'kit_dashoard_setting', 'Kitchen Dashboard Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1822, 'kot_reftime', 'Kitchen Refresh time In Second');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1823, 'bulk_upload', 'Bulk Upload');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(1824, 'upload_food_csv', 'Upload Food Item csv');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2202, 'appcartempty', 'Your Cart is empty!!!.Please add some food.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2203, 'apporderempty', 'You Order List is empty!!! Please Place A Order First!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2239, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2241, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2242, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2244, 'topselleingitem', 'Top selling Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2247, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2249, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2250, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2252, 'logininfo', 'Login Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2253, 'newuser', 'New User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2254, 'orloginwith', 'or login with');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2255, 'registerinfo', 'Registration Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2256, 'register_txt', 'If you have shopped with us before, please enter your details in the boxes below.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2257, 'customerinfo', 'Customer Info');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2258, 'delvtype', 'Delivery Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2259, 'delv_date', 'Delivery Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2260, 'delvtime', 'Delivery Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2261, 'yourcart', 'Your Cart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2262, 'items', 'items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2263, 'delivarycrg', 'Delivery charge');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2264, 'offercodegift', 'Offer code / gift card code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2265, 'apply', 'Apply');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2266, 'proceedtocart', 'Proceed to Checkout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2267, 'delv_address', 'Delivary address List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2268, 'create_address', 'Create Address');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2269, 'seeallmenu', 'See all menu');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2270, 'sendymsg', 'Send Your Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2271, 'send_us', 'Send Us Message');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2297, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2299, 'tax_name', 'Reg No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2300, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2302, 'sell_report_addons', 'Add-ons Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2303, 'mobilep-method', 'Mobile Payment Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2310, 'mpmethodlist', 'Mobile Financial Services(MFS) List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2311, 'mobilemethodName', 'Financial Service Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2312, 'trans_no', 'Transaction No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2313, 'thankinv', 'Thank you');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2314, 'powerinv', 'Powered By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2333, 'financial_year', 'Financial Year');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2334, 'financial_year_end', 'Financial Year Ending');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2335, 'opening_balance', 'Opening Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2336, 'endyearnote', 'You can end Financial Year at the end of Financial Year. If you end Financial year Your all closing balance will be added in opening Balance for new Financial year');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2337, 'endyouryear', 'End Your Financial Year');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2338, 'head_of_account', 'Head of Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2339, 'trial_balance_with_opening_as_on', 'Trial Balance With Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2340, 'transdate', 'Transaction Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2349, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2351, 'tax_name', 'Reg No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2352, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2354, 'showhidevattin', 'Show/Hide(VAT/TIN)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2355, 'foodcode', 'Food Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2356, 'openfood', 'Open Food');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2357, 'openfoodentry', 'Open Food Entry');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2358, 'itemassign_to_user', 'Item Assign to User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2359, 'Userwiseitem', 'User wise Food Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2360, 'is_access', 'Is access');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2361, 'opening_balance_list', 'Opening Balance List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2362, 'add_opening_balance', 'Add Opening Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2363, 'sub_ledgder', 'Cost Center Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2364, 'bank_reconciliation', 'Bank Reconciliation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2365, 'statementofexpen', 'Statement of Expenditure');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2366, 'fixed_asset_schedule', 'Fixed Asset Schedule');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2367, 'receipt_and_payment', 'Receipt and Payment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2368, 'bank_reconciliation_report', 'Bank Reconciliation Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2369, 'income_statement', 'Income Statement');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2370, 'income_statement_form', 'Income Statement Form');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2371, 'fixed_assets_report', 'Fixed Assets Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2372, 'opening_balance_fixed_assets', 'Opening Balance Fixed Assets');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2373, 'additions', 'Additions');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2374, 'adjustment', 'Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2375, 'closing_balance_fixed_assets', 'Closing Balance Fixed Assets');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2376, 'depreciation_rate', 'Depreciation Rate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2377, 'depreciation_value', 'Depreciation Value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2378, 'opening_balance_accumulated_depreciation', 'Opening Balance Accumulated Depreciation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2379, 'closing_balance_accumulated_depreciation', 'Closing Balance Accumulated Depreciation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2380, 'written_down_value', 'Written Down Value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2381, 'authorised_by', 'Authorised By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2382, 'accrual_basis', 'Accrual Basis');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2383, 'cash_basis', 'Cash Basis');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2384, 'receipt', 'Receipt');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2385, 'cashinhand', 'Cash In Hand');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2386, 'cash_bank', 'Cash At Bank');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2387, 'advance', 'Advance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2388, 'payments', 'Payments');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2389, 'other_cost', 'Other Cost');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2390, 'transpostcost', 'Transports Cost');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2391, 'labourcost', 'Labour Costs');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2392, 'predefined_accounts', 'Predefined Accounts');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2441, 'nit_profit', 'Net Profit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2442, 'nit_loss', 'Nit Loss');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2443, 'total_assets', 'Total Assets');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2444, 'total_liabilities', 'Total Liabilities');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2445, 'total_equity', 'Total Equity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2446, 'total_liabilities_equity', 'Total Liabilities & Equity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2447, 'vat_report', 'VAT Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2452, 'pos_access_role', 'POS Access Role');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2453, 'not_showingweb', 'Not Visible on Web ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2454, 'odering', 'Listing Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2459, 'ftposition', 'Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2461, 'yestdaysell', 'Yesterday Sale Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2462, 'totalsaleamnt', 'Total Sales Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2463, 'manage_topping', 'Manage Topping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2464, 'topping_list', 'Topping List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2465, 'add_topping', 'ADD Topping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2466, 'toppingname', 'Topping Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2467, 'update_topping', 'Update Topping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2468, 'assign_topping_list', 'Assign Topping List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2469, 'topping_head', 'Topping Title');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2470, 'max_sl_topping', 'Max Selection');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2471, 'is_position', 'Is Position');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2472, 'assign_topping', 'Assign Topping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2473, 'update_assign', 'Update Topping Assign');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2474, 'is_paid', 'Is Paid');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2475, 'activity_log', 'Activity Log');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2496, 'testdfghftr', '');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2497, 'testdfghftry', '');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2498, 'cancel_item', 'Cancel Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2499, 'isinclusivetax', 'Is Tax Inclusive?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2500, 'isqrshowinvoice', 'Is QR Show On Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2501, 'istaxwarning', 'If You Randomly Change This Option Then Your Accounts And Total Sales And Purchase Are Not Showing Properly.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2506, 'restaurant_table', 'Restaurant Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2507, 'shift_module', 'Shift Module');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2508, 'qr_module', 'QR module');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2509, 'facebook_login', 'Facebook Login');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2510, 'server_setting', 'Server Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2511, 'commission_setting', 'Commission Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2512, 'toggle_navigation', 'Toggle navigation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2513, 'welcome', 'Welcome');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2514, 'pos', 'POS');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2515, 'kitchenkds', 'KITCHEN (KDS)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2516, 'waitingdisplay', 'WAITING DISPLAY');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2517, 'salesGraph', 'Sales Graph');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2518, 'OrderTypeBasedOverview', 'Order Type Based Overview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2519, 'TodayOverview', 'Today Overview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2520, 'OrderValue', 'Order Value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2521, 'Purchaset', 'Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2522, 'OthersExpenses', 'Others Expenses');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2523, 'WeeklyOverview', 'Weekly Overview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2524, 'MonthlyOverview', 'Monthly Overview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2525, 'YearlyOverview', 'Yearly Overview');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2526, 'IncomevsExpenseGrowth', 'Income vs Expense Growth');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2527, 'PurchaseGraph', 'Purchase Graph');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2528, 'TopSellingItems', 'Top Selling Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2529, 'SlowSellingItems', 'Slow Selling Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2530, 'HourlyOrderFlow', 'Hourly Order Flow');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2531, 'WaiterSales', 'Waiter Sales');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2532, 'ItemName', 'Item Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2533, 'today', 'Today');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2534, 'weekly', 'Weekly');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2535, 'AllTime', 'All Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2536, 'Dine_In', 'Dine In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2537, 'online', 'Online');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2538, 'TakeWay', 'Take Way');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2539, 'ThirdParty', 'Third Party');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2540, 'qr', 'QR');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2541, 'PurchaseAmount', 'Purchase Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2542, 'TotalExpense', 'Total Expense');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2543, 'TotalIncome', 'Total Income');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2544, 'OrderValueLine', 'Order Value Line');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2545, 'OrderNumberLine', 'Order Number Line');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2546, 'TotalOrderValue', 'Total Order Value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2547, 'invalid', 'Invalid');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2548, 'Inv_mergmessage', 'Merge Order and Split order can not Merge Again!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2549, 'warning', 'Warning');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2550, 'cashcloseing_msg', 'Something Wrong On Cash Closing!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2551, 'counter_worning', 'Something Wrong!!! .Please Select Counter Number!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2552, 'succ_note', 'Note Added Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2553, 'success', 'Success');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2554, 'payment_taken_successfully', 'payment taken successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2555, 'sl_mpay', 'Please select Your Mobile Payment Method!!!\\n');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2556, 'bnak_terminal', 'Please select Bank Terminal!!!\\n');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2557, 'bank_select', 'Please select Your Bank!!!\\n');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2558, 'error', 'Error');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2559, 'pay_full', 'Pay full amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2560, 'empty_not_pay', 'Empty item is not pay & print invoice!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2561, 'food_not_avail_warning', 'Food Not available at that moment!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2562, 'type_no_person', 'Please type Number of person');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2563, 'table_capacity_overflow', 'table capacity overflow');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2564, 'Please_Select_Waiter', 'Please Select Waiter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2565, 'Paid_amount_exceed', 'Paid amount is exceed to Total amount.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2566, 'Please_Select_Card_Terminal', 'Please Select Card Terminal!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2567, 'Please_Insert_Paid_amount', 'Please Insert Paid Amount!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2568, 'Please_Select_Table', 'Please Select Table');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2569, 'Please_Select_Customer_Name', 'Please Select Customer Name!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2570, 'Please_Select_Customer_Type', 'Please Select Customer Type!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2571, 'Please_add_Some_Food', 'Please add Some Food!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2572, 'Please_Select_Delivar_Company', 'Please Select Delivar Company!!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2573, 'Product_not_found', 'Product not found !');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2574, 'Yes_Cancel', 'Yes, Cancel!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2575, 'yes', 'Yes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2576, 'Enable_This_Option_to_show_on_Pos_Invoice', 'Enable This Option to show on Pos Invoice');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2577, 'Make_This_Field_Is_Optional_On_Pos_Page', 'Make This Field Is Optional On Pos Page.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2578, 'disable', 'Disable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2579, 'enable', 'Enable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2580, 'Mode_Change', 'Mode Change');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2581, 'Color_Mode_change_Successfully', 'Color Mode Change Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2582, 'Table_Mapping_Layout_change_Successfully', 'Table Mapping Layout Change Successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2583, 'Are_you_sure_you_want_to_delete', 'Are you sure you want to delete?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2584, 'Please_check_at_least_one_item', 'Please check at least one item!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2585, 'Check_Item', 'Check Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2586, 'Please_enter_From_Date', 'Please enter From Date!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2587, 'Please_enter_To_Date', 'Please enter To Date!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2588, 'Successfully_Deleted', 'Successfully Deleted!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2589, 'Something_Wrong', 'Something Wrong!!');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2590, 'Delete_Order', 'Delete Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2591, 'unit', 'Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2592, 'Ava_nty', 'Ava. Qnty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2593, 'Save_Changes', 'Save Changes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2594, 'Dis_Pcs', 'Dis/ Pcs');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2595, 'All_Items', 'All Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2596, 'Pos_Theme_Color', 'Pos Theme Color');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2597, 'Invoice_Layout', 'Invoice Layout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2598, 'TableMap_Layout', 'TableMap Layout');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2599, 'Invoice_Option_Show_Hide', 'Invoice Option Show/Hide');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2600, 'Day_Closing_Report_setting', 'Day Closing Report setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2601, 'Show_Item_summery', 'Show Item summery');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2602, 'Walk_In_Customer', 'Walk In Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2603, 'Online_Customer', 'Online Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2604, 'Third_Party', 'Third Party');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2605, 'Take_Way', 'Take Way');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2606, 'QR_Customer', 'QR Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2607, 'How_To_Display_Amount', 'How To Display Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2608, 'Download_CSV_Format', 'Download CSV Format');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2609, 'You_can_export_example_csv_file_Example', 'You can export example.csv file Example');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2614, 'Order_Summary', 'Order Summary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2615, 'topping', 'Topping');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2616, 'Select_Payment_Type', 'Select Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2617, 'Payable_Amount', 'Payable Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2618, 'addons_pay', 'Add-Ons');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2619, 'Take-Away', 'Take-Away');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2620, 'qrapp', 'QR App');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2621, 'qr_order', 'QR Order List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2622, 'tableqr_code', 'All Table QR');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2623, 'qr_payment', 'QR Payment Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2624, 'salary_advance', 'Salary Advance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2625, 'Add_Salary_Advance', 'Add Salary Advance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2626, 'Manage_Salary_Advance', 'Manage Salary Advance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2627, 'salar_month', 'Salary Month');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2628, 'update_salary_advance', 'Update Salary Advance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2629, 'create_date', 'Create Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2630, 'release_amount', 'Release Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2631, 'amount_type', 'Amount Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2632, 'Basic', 'Basic');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2633, 'salary_name', 'Salary Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2634, 'not_approved', 'Not Approved');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2635, 'employee_salary_chart', 'Employee Salary Chart');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2636, 'employee_salary_approval', 'Employee Salary Approval');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2637, 'post', 'POST');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2638, 'monthly_deduction', 'Monthly Deduct');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2639, 'deduct_head', 'Deduct Head');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2640, 'add_deduct', 'Add Deduct');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2641, 'update_deduct', 'Deduct Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2642, 'expence_month', 'Expence Month');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2643, 'payslip', 'Payslip');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2644, 'standard_working_hours', 'Standard Working Hours');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2645, 'damage_expire', 'ExpireOrDamage List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2646, 'expire_damageentry', 'Expire and Damage Entry');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2647, 'edit_damage', 'Edit Expire and Damage items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2648, 'expireqty', 'Expire Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2649, 'damageqty', 'Damage Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2650, 'expiredamagedate', 'Expire and Damage Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2651, 'openingstock', 'Opening Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2652, 'addopeningstock', 'Add Opening Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2653, 'openstockqty', 'Opening Stock Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2654, 'update_stock', 'Stock Update');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2655, 'open_qty', 'Open Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2656, 'closingqty', 'Closing Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2657, 'valuation', 'Valuation');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2658, 'stock_valuation_method', 'Stock Valuation Method');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2659, 'qr_themesetting', 'QR Theme Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2660, 'qr_setting', 'QR Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2661, 'item_image', 'Food Image is Hide ');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2662, 'change_theme', 'Change Thame');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2663, 'itemsalesbycashier', 'Details Sales (Cashier)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2664, 'sale_date', 'Sale Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2674, 'invoice_template', 'Invoice Template');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2675, 'invoice_templateList', 'Invoice Template List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2676, 'invoice_settings', 'Invoice Settings');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2677, 'layout_name', 'Layout name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2678, 'design_for', 'Design For');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2679, 'add_invoice_template', 'Add Invoice Template');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2680, 'currency_notes', 'Currency Notes');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2681, 'add_currency_note', 'Add Currency Note');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2682, 'currency_note_edit', 'Currency Note Edit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2683, 'note_name', 'Note Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2684, 'desktopauthkey', 'Desktop Key');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2685, 'tex_setting', 'Tax Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2686, 'tex_enable', 'Tax setting Enable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2687, 'number_of_tax', 'Number of tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2688, 'default_value', 'Default value');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2689, 'tax_name', 'Reg No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2690, 'tax_name', 'Tax Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2691, 'update_tax_settings', 'If you Update tax settings ,All of your previous tax record will be destroy.You Will Need to set tax product wise and Adones wise');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2692, 'withoutproducttion', 'Without Production');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2693, 'stock_report_product_wise_ready_item', 'Stock Report(Finish Goods)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2694, 'isstock_validate', 'Is Stock Validate?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2695, 'vat_type', 'Vat Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2696, 'subtype', 'Cost center Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2697, 'subcode', 'Cost Center');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2698, 'tax_number', 'Tax Number');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2699, 'max_discount', 'Max Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2700, 'date_of_birth', 'Date of Birth');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2702, 'contact_person_email', 'Contact Person Email');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2703, 'contact_person_name', 'Contact Person Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2704, 'contact_person_phone', 'Contact Person Phone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2705, 'catcode', 'CatCode');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2706, 'VariantCode', 'Variant Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2707, 'um_code', 'UM Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2708, 'ing_code', 'ING Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2709, 'challan_no', 'Challan No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2710, 'purchase_no', 'Purchase No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2711, 'prefix_setting', 'Prefix Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2712, 'sales', 'Sales');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2713, 'purchase', 'Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2714, 'sales_return', 'Sales Return');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2715, 'updated_successfully', 'Updated successfully');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2716, 'refcode', 'RefCode');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2717, 'pos_invoice_return', 'Order Return');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2718, 'thirdparty_sale_comm', 'Third-Party Commission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2719, 'paymentgatewaycomm', 'Payment Gateway Commission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2720, 'creditsalereport', 'Credit Sale Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2721, 'commission_report', 'Commission Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2722, 'control_ledger', 'Control Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2723, 'note_ledger', 'Note Ledger');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2724, 'reconciliation_statuslist', 'Reconciliation Status List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2725, 'add_status', 'Add Status');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2726, 'status_name', 'Status Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2727, 'sale_return_report', 'Return Sales (Setteled)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2728, 'CashCode', 'Cash Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2729, 'BankCode', 'Bank Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2730, 'purchaseAcc', 'Product Purchase');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2731, 'PurchaseDiscount', 'Purchase Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2732, 'SalesAcc', 'Sales Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2733, 'ServiceIncome', 'Service Change Income');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2734, 'CustomerAcc', 'Customer Receivable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2735, 'SupplierAcc', 'Account Payable');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2736, 'COGS', 'Sale Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2737, 'inventoryCode', 'inventory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2738, 'CPLcode', 'Current year Profit & Loss');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2739, 'LPLcode', 'Last year Profit & Loss');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2740, 'salary_code', 'Staff Salary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2741, 'emp_npf_contribution', 'Employee Contribution');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2742, 'empr_npf_contribution', 'Employee Profit Contribution');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2743, 'emp_icf_contribution', 'Employee ICF Contribution');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2744, 'empr_icf_contribution', 'Employee RICF Contribution');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2745, 'advance_employee', 'Advance Against Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2746, 'load_to_employee', 'Loan to Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2747, 'customer_due_amount', 'Customer Due Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2748, 'return_amount', 'Return Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2749, 'due_sale_report', 'Due Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2750, 'dragency', 'Delivery Agency');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2751, 'cashintransit', 'Cash In Transit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2752, 'customer', 'Customer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2753, 'delivery', 'Delivery');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2754, 'return_reportlist', 'Return List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2755, 'add_po_request', 'Add PO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2756, 'po_request_list', 'PO Request List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2757, 'varient_name', 'Varient Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2758, 'varient', 'Varient');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2759, 'po_id', 'PO ID');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2760, 'edit_po_request', 'Edit PO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2761, 'refund_amount', 'Refund Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2762, 'sale_qty', 'Sale Qty');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2763, 'sale_return_report', 'Return Sales (Setteled)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2764, 'sale_return_report', 'Return Sales (Setteled)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2765, 'auto_approve_account', 'Auto Approve Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2766, 'datebydatesale', ' Date wise Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2767, 'category_code', 'Category Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2768, 'auto_approve_account', 'Auto Approve Account');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2769, 'cardterminal', 'Card Terminal');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2770, 'pay_thirdparty_commision', 'Pay Delivery Commission');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2771, 'purchase_return_list', 'Purchase Return List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2772, 'vat_report_generate', 'Vat Report Generate');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2773, 'tips_managements', 'Tips Management');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2774, 'purchase_vat_report', 'Purchases VAT Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2775, 'currency_converter', 'Use Currency Converter ON POS');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2776, 'return_amount', 'Return Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2777, 'handshakebranchkey', 'Branch Handshaking Key');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2778, 'cash_in_hand', 'Cash In Hand');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2779, 'inventory_assign', 'Inventory Assign to Kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2781, 'taxsetting', 'TAX Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2791, 'websiteorder', 'Website Order');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2792, 'date_time', 'Date Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2793, 'order_type', 'Order Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2794, 'order_item', 'Order Item');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2795, 'payment_type', 'Payment Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2796, 'pending', 'Pending');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2797, 'shipped', 'Shipped');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2798, 'deliverred', 'Delivered');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2803, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2804, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2805, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2810, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2811, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2812, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2817, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2818, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2819, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2824, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2825, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2826, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2831, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2832, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2833, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2838, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2839, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2840, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2845, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2846, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2847, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2862, 'location_zone', 'Location Zone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2863, 'location_zone', 'Location Zone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2864, 'deliveryzone', 'Delivery Zone');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2865, 'isreviewenable', 'Active Review');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2866, 'add_review_code', 'Add Your Review Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2885, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2886, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2887, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2892, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2893, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2894, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2899, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2900, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2901, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2906, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2907, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2908, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2913, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2914, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2915, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2920, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2921, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2922, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2923, 'sell_reportchild', 'Sales Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2924, 'return_invoice_rept', 'Return Sales(Pending)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2929, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2930, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2931, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2958, 'reservation_dashboard', 'Reservation Dashboard');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2971, 'select_all', 'Select All');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2974, 'set_devices', 'Set Devices');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2975, 'to', 'To');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2976, 'edit_employee', 'Edit Employee');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2977, 'time_data', '');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2978, 'in', 'In');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2979, 'out', 'Out');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2980, 'punch', 'Punch');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2981, 'attendance_data_of_date', 'Attendance Data of Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2982, 'reload', 'Reload');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2983, 'assign_employee_to_device', 'Assign Employee To Device');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2984, 'get_back', 'Get Back');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(2999, 'food_delivery_order_id', 'Food Felivery Order Id');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3000, 'rider_name', 'Rider Name');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3001, 'thirdparty_report', 'Third-Party Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3002, 'foods_prereference', 'Foods Prereference');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3007, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3008, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3009, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3014, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3015, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3016, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3040, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3055, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3072, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3073, 'add_supplier_po_request', 'Add Supplier Po Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3074, 'quotation_date', 'Quotation Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3075, 'supplier_po_request_list', 'Supplier Po RequestList');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3076, 'mahafuz', 'Md. mahafuz');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3077, 'app_ongoing', 'Ongoing');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3078, 'app_kitchen', 'Kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3079, 'app_pull_data', 'Pull Data');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3080, 'app_print_set', 'Print Set');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3081, 'app_merge', 'Merge');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3082, 'app_no', 'No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3083, 'all_category', 'All Category');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3084, 'cart_more', 'Cart & More');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3085, 'app_qr_code', 'Qr Code');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3086, 'app_total_items', 'Total items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3087, 'update_token', 'Update Token');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3088, 'app_items_discount', 'Items Discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3089, 'app_customer_paid', 'Customer Paid');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3090, 'app_billto', 'Bill To');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3091, 'start_register', 'Start Register');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3092, 'counterno', 'Counter No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3093, 'add_balance', 'Add Balance');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3094, 'app_counter_register', 'Counter Register');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3095, 'app_total_change_amount', 'Total Change Amount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3096, 'app_day_closing_report', 'Day Closing Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3097, 'app_open_date', 'Open Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3098, 'app_close_date', 'Close Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3099, 'app_counter', 'Counter');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3100, 'app_sales_summary', 'Sales Summary');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3101, 'app_total_net_sales', 'Total net sales');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3102, 'app_total_tax', 'Total tax');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3103, 'app_total_sd', 'Total SD');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3104, 'app_total_sales', 'Total sales');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3105, 'app_total_discount', 'Total discount');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3106, 'app_payment_details', 'Payment Details');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3107, 'app_cash_drawer', 'Cash Drawer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3108, 'app_day_opening', 'Day opening');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3109, 'app_day_closing', 'Day closing');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3110, 'physicalstock', 'Physical Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3111, 'Day_Closing', 'Day Closing');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3112, 'purchasereportbyitem', 'Purchases Report(Item)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3113, 'assigned_kitchen', 'Assigned Inventory List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3114, 'assign_kitchen', 'Assign Inventory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3115, 'kitchen_user_stock_report', 'Kitchen Stock Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3116, 'admin_user_kitchen_stock_report', 'All Kitchen Stock Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3117, 'assigned_kitchen', 'Assigned Inventory List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3118, 'assign_kitchen', 'Assign Inventory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3119, 'kitchen_user_stock_report', 'Kitchen Stock Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3120, 'admin_user_kitchen_stock_report', 'All Kitchen Stock Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3121, 'so_request_list', 'SO Request List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3122, 'so_request_add', 'Add SO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3123, 'update_assigned_inventory', 'Edit Assigned Inventory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3124, 'edit_reedem', 'Edit Consumption');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3125, 'assign_so_request', 'Assign SO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3126, 'edit_so_request', 'Edit SO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3127, 'kitchen_user', 'Kitchen User');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3128, 'assign_date', 'Assign Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3129, 'product_type', 'Product Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3130, 'raw_ingredients', 'Raw Ingredients');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3131, 'finish_goods', 'Finish Goods');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3132, 'note_to_kitchen', 'Note to Kitchen');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3133, 'new_consumption', 'New Consumption');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3134, 'consumption_by', 'Consumption By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3135, 'consumption_date', 'Consumption Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3136, 'used', 'Used');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3138, 'expired', 'Expired');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3139, 'kitchen_stock', 'Kitchen Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3140, 'remaining', 'Remaining');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3141, 'note_to_admin', 'Note to Admin');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3142, 'new_so_request', 'New SO Request');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3143, 'request_date', 'Request Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3144, 'assign_so_items', 'Assign SO Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3145, 'edit_assigned_so_items', 'Edit Assigned SO Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3146, 'requested_qty', 'Requested Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3147, 'given_qty', 'Given Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3148, 'add_reedem', 'Add Consumption');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3149, 'reedem_list', 'Consumption List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3165, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3170, 'delivery_location', 'Delivery Location');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3171, 'add_items', 'Add Items');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3172, 'submit_&_print', 'Submit & Print');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3182, 'selling_price', 'Selling Price');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3198, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3217, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3236, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3240, 'yearlyReport', 'Monthly Report');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3241, 'all_over_summery', 'All Over Summery');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3242, 'subtotal_without_vat', 'Subtotal(without VAT)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3304, 'product_received_from_ho', 'Product Received From Head Office');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3305, 'storageunit', 'Storage Unit');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3306, 'conversion_unit', 'Conversion Quantity');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3307, 'sku', 'SKU');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3308, 'barcode', 'Barcode');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3309, 'availavail_storate', 'Available(Storage)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3310, 'availavail_ing', 'Available(Ingredient)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3311, 'qtn_storage', 'Qtn(Storage)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3312, 'qtn_ingredient', 'Qtn(Ingredient)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3314, 'ismapenable', 'Map Enable ?');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3315, 'mapapikey', 'Map Api Key');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3344, 'wastage1', 'Wastage Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3345, 'actual_stay1', 'Actual Stay Time');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3361, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3365, 'production_cost', 'Production Cost');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3366, 'openinginventory', 'Opening Inventory');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3367, 'manage_purchase', 'Manage Purchases');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3368, 'inventory_report_allkds', 'Inventory Report(All Kitchen)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3369, 'inventory_report_singlekds', 'Inventory Report(Single Kitchen)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3370, 'inventory_adj', 'Inventory Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3371, 'stockout_list', 'Stock Out List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3372, 'kds_inventory_transfer', 'Kitchen Inventory Transfer');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3373, 'manage_consumption', 'Manage Consumption');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3374, 'manage_po_req_cb', 'Manage PO Request(CB)');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3395, 'addadjustment', 'Add Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3396, 'manage_adjustment', 'Manage Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3397, 'adjustment_list', 'Adjustment List');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3398, 'edit_adjustment', 'Edit Adjustment');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3399, 'referenceno', 'Reference No');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3400, 'adjusted_by', 'Adjusted By');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3401, 'current_stock', 'Current Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3402, 'adjusted_stock', 'Adjusted Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3403, 'adjusted_type', 'Adjusted Type');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3404, 'final_stock', 'Final Stock');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3405, 'adjustment_no', 'Adjustment No.');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3406, 'adjustdate', 'Adjustment Date');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3435, 'real_ip_setting', 'Real IP Setting');
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES(3439, 'wastage', 'Wastage');

-- --------------------------------------------------------

--
-- Table structure for table `leave_apply`
--

DROP TABLE IF EXISTS `leave_apply`;
CREATE TABLE IF NOT EXISTS `leave_apply` (
  `leave_appl_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(20) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `apply_strt_date` varchar(20) NOT NULL,
  `apply_end_date` varchar(20) NOT NULL,
  `apply_day` int(11) NOT NULL,
  `leave_aprv_strt_date` varchar(20) NOT NULL,
  `leave_aprv_end_date` varchar(20) NOT NULL,
  `num_aprv_day` varchar(15) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `apply_hard_copy` text DEFAULT NULL,
  `apply_date` varchar(20) NOT NULL,
  `approve_date` varchar(20) NOT NULL,
  `approved_by` varchar(30) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  PRIMARY KEY (`leave_appl_id`),
  KEY `employee_id` (`employee_id`),
  KEY `leave_type_id` (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_type`
--

DROP TABLE IF EXISTS `leave_type`;
CREATE TABLE IF NOT EXISTS `leave_type` (
  `leave_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `leave_type` varchar(50) NOT NULL,
  `leave_days` int(11) NOT NULL,
  PRIMARY KEY (`leave_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_installment`
--

DROP TABLE IF EXISTS `loan_installment`;
CREATE TABLE IF NOT EXISTS `loan_installment` (
  `loan_inst_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(21) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `loan_id` varchar(21) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `installment_amount` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `payment` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `date` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `received_by` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `installment_no` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `notes` varchar(80) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`loan_inst_id`),
  KEY `employee_id` (`employee_id`),
  KEY `loan_id` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marital_info`
--

DROP TABLE IF EXISTS `marital_info`;
CREATE TABLE IF NOT EXISTS `marital_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marital_sta` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `marital_info`
--

INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES(1, 'Single');
INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES(2, 'Married');
INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES(3, 'Divorced');
INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES(4, 'Widowed');
INSERT INTO `marital_info` (`id`, `marital_sta`) VALUES(5, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

DROP TABLE IF EXISTS `membership`;
CREATE TABLE IF NOT EXISTS `membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membership_name` varchar(250) NOT NULL,
  `discount` float NOT NULL,
  `other_facilities` varchar(255) NOT NULL,
  `create_by` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `update_by` int(11) NOT NULL,
  `update_date` date NOT NULL,
  `startpoint` int(11) NOT NULL,
  `endpoint` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`, `startpoint`, `endpoint`) VALUES(1, 'Normal User', 0, '', 2, '2018-11-07', 2, '2018-11-07', 0, 0);
INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`, `startpoint`, `endpoint`) VALUES(2, 'Premium Member', 0, '', 1, '2020-11-04', 0, '0000-00-00', 250, 999);
INSERT INTO `membership` (`id`, `membership_name`, `discount`, `other_facilities`, `create_by`, `create_date`, `update_by`, `update_date`, `startpoint`, `endpoint`) VALUES(3, 'VIP', 0, '', 1, '2020-11-04', 0, '0000-00-00', 1001, 5000000);

-- --------------------------------------------------------

--
-- Table structure for table `menu_add_on`
--

DROP TABLE IF EXISTS `menu_add_on`;
CREATE TABLE IF NOT EXISTS `menu_add_on` (
  `row_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `assignCode` varchar(30) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `add_on_id` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `is_deleted` int(11) DEFAULT 0,
  PRIMARY KEY (`row_id`),
  KEY `menu_id` (`menu_id`),
  KEY `add_on_id` (`add_on_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `datetime` datetime NOT NULL,
  `sender_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=unseen, 1=seen, 2=delete',
  `receiver_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=unseen, 1=seen, 2=delete',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `name`, `description`, `image`, `directory`, `status`) VALUES(99, 'Tax setting for country wise', 'Tax setting for country wise', 'application/modules/taxsetting/assets/images/thumbnail.jpg', 'taxsetting', 1);

-- --------------------------------------------------------

--
-- Table structure for table `module_permission`
--

DROP TABLE IF EXISTS `module_permission`;
CREATE TABLE IF NOT EXISTS `module_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_module_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `create` tinyint(1) DEFAULT NULL,
  `read` tinyint(1) DEFAULT NULL,
  `update` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_id` (`fk_module_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `module_purchase_key`
--

DROP TABLE IF EXISTS `module_purchase_key`;
CREATE TABLE IF NOT EXISTS `module_purchase_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identity` varchar(250) DEFAULT NULL,
  `purchase_key` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `multipay_bill`
--

DROP TABLE IF EXISTS `multipay_bill`;
CREATE TABLE IF NOT EXISTS `multipay_bill` (
  `multipay_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `multipayid` varchar(30) DEFAULT NULL,
  `payment_type_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `suborderid` int(11) DEFAULT NULL,
  `adflag` varchar(30) DEFAULT 'ad',
  `pdate` date DEFAULT '1970-01-01',
  PRIMARY KEY (`multipay_id`),
  KEY `order_id` (`order_id`),
  KEY `multipayid` (`multipayid`),
  KEY `payment_type_id` (`payment_type_id`),
  KEY `suborderid` (`suborderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordertoken_tbl`
--

DROP TABLE IF EXISTS `ordertoken_tbl`;
CREATE TABLE IF NOT EXISTS `ordertoken_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `variantid` int(11) NOT NULL,
  `addonid` int(11) NOT NULL,
  `prevQty` decimal(10,2) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `note` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_menu`
--

DROP TABLE IF EXISTS `order_menu`;
CREATE TABLE IF NOT EXISTS `order_menu` (
  `row_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `price` decimal(19,3) DEFAULT 0.000,
  `itemdiscount` decimal(19,3) DEFAULT 0.000,
  `itemvat` decimal(10,2) DEFAULT NULL,
  `groupmid` int(11) DEFAULT 0,
  `notes` varchar(255) DEFAULT NULL,
  `menuqty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `add_on_id` varchar(100) NOT NULL,
  `addonsqty` varchar(100) NOT NULL,
  `tpassignid` varchar(100) DEFAULT NULL,
  `tpid` varchar(100) DEFAULT NULL,
  `tpposition` varchar(100) DEFAULT NULL,
  `tpprice` varchar(100) DEFAULT NULL,
  `varientid` int(11) NOT NULL,
  `groupvarient` int(11) DEFAULT NULL,
  `addonsuid` int(11) DEFAULT NULL,
  `qroupqty` decimal(19,3) DEFAULT 0.000,
  `isgroup` int(11) DEFAULT 0,
  `food_status` int(11) DEFAULT 0,
  `allfoodready` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `isupdate` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_id`),
  KEY `order_id` (`order_id`),
  KEY `menu_id` (`menu_id`),
  KEY `groupmid` (`groupmid`),
  KEY `varientid` (`varientid`),
  KEY `groupvarient` (`groupvarient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_payment_tbl`
--

DROP TABLE IF EXISTS `order_payment_tbl`;
CREATE TABLE IF NOT EXISTS `order_payment_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `pay_amount` decimal(10,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_pickup`
--

DROP TABLE IF EXISTS `order_pickup`;
CREATE TABLE IF NOT EXISTS `order_pickup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `delivery_time` time NOT NULL,
  `ridername` varchar(55) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1=picup,2=customer delivery,3=Ac',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentsetup`
--

DROP TABLE IF EXISTS `paymentsetup`;
CREATE TABLE IF NOT EXISTS `paymentsetup` (
  `setupid` int(11) NOT NULL AUTO_INCREMENT,
  `paymentid` int(11) NOT NULL,
  `marchantid` varchar(255) DEFAULT NULL,
  `password` varchar(120) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `currency` varchar(20) NOT NULL,
  `Islive` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `edit_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`setupid`),
  KEY `paymentid` (`paymentid`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `paymentsetup`
--

INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(1, 5, 'bdtas5e772deb8ff87', 'bdtas5e772deb8ff87@ssl', 'ainalcse@gmail.com', 'BDT', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(2, 3, '', '', 'tareq7500personal2@gmail.com', 'USD', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(3, 2, '901400787', '', 'ainalcse@gmail.com', 'USD', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(4, 6, '002020000000001', '002020000000001_KEY1', '1', '', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(5, 7, 'BE10000072', 'BE10000072', 'karmadorji@gmail.com', 'BTN', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(6, 8, 'sandbox-sq0idb-ShIOgPUIHSXxsjCPG4oh_A', 'EAAAEE3gxSvOVaHIq-5A5P_yFkUbkAfUM2-JiQju2FTxQ4n7epxmvKpaOhECxHcN', '5SNY8GNKAZM00', 'AUD', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(7, 9, 'sk_test_ol4WUcbGsqxNJItpeOi1ecDT00k5mDyC2G', 'pk_test_TrVFpmZBkgasCE6WTPkZgMPr00UzVVOqgp', 'ainalcse@gmail.com', 'USD', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(8, 10, 'sk_test_71353c2613675acb967ea532f4c4c8105ea175b8', 'pk_test_328da55755b88b1aaed96c5cda215b2fd887edb9', 'ainalcse@gmail.com', 'NGN', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(9, 11, NULL, '', '', '', 0, 0, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(10, 12, '7BUkXCbuHDcx1ZyQqmcKVtsLnFxF0r3f', 'vmUIfeoHXpZSKc20Wt50d6hqeIY5FcWtFR6prg0Ubak8IvmmZEFDDpQr5ZMEdnoS', '', 'XAF', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(12, 13, 'sandbox-5rd4uUC2yAz7LWDaalyJAOEsH2rxrqVB', 'sandbox-FsKRCZpk0BpdUss3wVsNLhvs5Ty5PSpi', '', 'BDT', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(18, 14, 'mobile', '', NULL, 'BDT', 0, 1, NULL);
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`, `edit_url`) VALUES(20, 20, '18e3673886e278ad', 'mer_033d82cde89aa5d14992ab33eb84affdb014ec4b0562c60f1ae34d8dcefc10c2', 'ChefIrina@gmail.com', 'AED', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE IF NOT EXISTS `payment_method` (
  `payment_method_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `paymentmethod_code` bigint(20) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `commission` float(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `modulename` varchar(50) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL DEFAULT '',
  `displayorder` int(11) DEFAULT 99,
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(1, 0, 'Card Payment', NULL, 1, '', 2);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(2, 12, 'Two Checkout', 0.00, 0, '', 12);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(3, 11, 'Paypal', 0.00, 0, '', 4);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(4, 0, 'Cash Payment', NULL, 1, '', 1);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(5, 10, 'SSLCommerz', 0.00, 0, '', 5);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(6, 9, 'SIPS Office', 0.00, 0, '', 14);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(7, 8, 'RMA PAYMENT GATEWAY', 0.00, 0, '', 13);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(8, 7, 'Square Payments', 0.00, 0, '', 6);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(9, 22, 'Stripe Payment', 0.00, 1, '', 8);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(10, 5, 'Paystack Payments', 0.00, 0, '', 7);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(11, 4, 'Paytm Payments', 0.00, 0, '', 9);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(12, 3, 'Orange Money payment', 0.00, 0, '', 10);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(13, 2, 'iyzico', 0.00, 0, '', 11);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(14, 23, 'Mobile Payment', 0.00, 1, '', 3);
INSERT INTO `payment_method` (`payment_method_id`, `paymentmethod_code`, `payment_method`, `commission`, `is_active`, `modulename`, `displayorder`) VALUES(20, 21, 'Paymennt', 0.00, 1, '', 20);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_commission_setting`
--

DROP TABLE IF EXISTS `payroll_commission_setting`;
CREATE TABLE IF NOT EXISTS `payroll_commission_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `create_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_holiday`
--

DROP TABLE IF EXISTS `payroll_holiday`;
CREATE TABLE IF NOT EXISTS `payroll_holiday` (
  `payrl_holi_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `holiday_name` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `start_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `no_of_days` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_by` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updated_by` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`payrl_holi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tax_setup`
--

DROP TABLE IF EXISTS `payroll_tax_setup`;
CREATE TABLE IF NOT EXISTS `payroll_tax_setup` (
  `tax_setup_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `start_amount` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end_amount` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `rate` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`tax_setup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pay_frequency`
--

DROP TABLE IF EXISTS `pay_frequency`;
CREATE TABLE IF NOT EXISTS `pay_frequency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frequency_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pay_frequency`
--

INSERT INTO `pay_frequency` (`id`, `frequency_name`) VALUES(1, 'Weekly');
INSERT INTO `pay_frequency` (`id`, `frequency_name`) VALUES(2, 'Biweekly');
INSERT INTO `pay_frequency` (`id`, `frequency_name`) VALUES(3, 'Annual');
INSERT INTO `pay_frequency` (`id`, `frequency_name`) VALUES(4, 'Monthly');

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

DROP TABLE IF EXISTS `position`;
CREATE TABLE IF NOT EXISTS `position` (
  `pos_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `position_details` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`pos_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(1, 'chef', 'Responsible for the pastry shop in a foodservice establishment. Ensures that the products produced in the pastry shop meet the quality standards in conjunction with the executive chef.');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(2, 'HRM', 'Recruits and hires qualified employees, creates in-house job-training programs, and assists employees with their career needs.');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(3, 'Kitchen manager', 'Supervises and coordinates activities concerning all back-of-the-house operations and personnel, including food preparation, kitchen and storeroom areas.');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(4, 'Counter server', 'Responsible for providing quick and efficient service to customers. Greets customers, takes their food and beverage orders, rings orders into register, and prepares and serves hot and cold drinks.');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(6, 'Waiter', 'Most waiters and waitresses, also called servers, work in full-service restaurants. They greet customers, take food orders, bring food and drinks to the tables and take payment and make change.');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(7, 'Accounts', 'Play a key role in every restaurant. ');
INSERT INTO `position` (`pos_id`, `position_name`, `position_details`) VALUES(8, 'Salesman', 'A salesman is someone who works in sales, with the main function of selling products or services to others either by visiting locations');

-- --------------------------------------------------------

--
-- Table structure for table `po_details_tbl`
--

DROP TABLE IF EXISTS `po_details_tbl`;
CREATE TABLE IF NOT EXISTS `po_details_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_id` int(11) NOT NULL,
  `producttype` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `ingredient_code` varchar(20) DEFAULT NULL,
  `variant_code` varchar(255) DEFAULT NULL,
  `quantity` decimal(19,3) NOT NULL DEFAULT 0.000,
  `delivered_quantity` int(11) DEFAULT NULL,
  `received_quantity` float(10,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `price` decimal(19,4) DEFAULT 0.0000,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `po_tbl`
--

DROP TABLE IF EXISTS `po_tbl`;
CREATE TABLE IF NOT EXISTS `po_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(50) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `termscondition` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `status` int(11) NOT NULL COMMENT '1 = pending, 2 = delivered, 3 = gate passed, 4 = canceled, 5 = received',
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefix_setting`
--

DROP TABLE IF EXISTS `prefix_setting`;
CREATE TABLE IF NOT EXISTS `prefix_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales` varchar(20) NOT NULL,
  `purchase` varchar(20) NOT NULL,
  `sales_return` varchar(20) NOT NULL,
  `purchase_return` varchar(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `prefix_setting`
--

INSERT INTO `prefix_setting` (`id`, `sales`, `purchase`, `sales_return`, `purchase_return`, `created_date`) VALUES(1, 'SA', 'PUR', 'SR', 'PR', '2023-04-10 01:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE IF NOT EXISTS `production` (
  `productionid` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `itemvid` int(11) DEFAULT NULL,
  `itemquantity` int(11) NOT NULL,
  `receipe_code` varchar(100) DEFAULT NULL,
  `receipe_nprice` decimal(19,3) DEFAULT 0.000,
  `savedby` int(11) NOT NULL,
  `saveddate` date NOT NULL,
  `productionexpiredate` date NOT NULL,
  PRIMARY KEY (`productionid`),
  KEY `itemid` (`itemid`),
  KEY `itemvid` (`itemvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_details`
--

DROP TABLE IF EXISTS `production_details`;
CREATE TABLE IF NOT EXISTS `production_details` (
  `pro_detailsid` int(11) NOT NULL AUTO_INCREMENT,
  `foodid` int(11) NOT NULL,
  `pvarientid` int(11) DEFAULT NULL,
  `ingredientid` int(11) NOT NULL,
  `qty` decimal(29,11) NOT NULL DEFAULT 0.00000000000,
  `receipe_code` varchar(100) DEFAULT NULL,
  `unitname` varchar(100) DEFAULT NULL,
  `receipe_price` decimal(19,3) DEFAULT 0.000,
  `createdby` int(11) NOT NULL,
  `created_date` date NOT NULL,
  PRIMARY KEY (`pro_detailsid`),
  KEY `foodid` (`foodid`),
  KEY `pvarientid` (`pvarientid`),
  KEY `ingredientid` (`ingredientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchaseitem`
--

DROP TABLE IF EXISTS `purchaseitem`;
CREATE TABLE IF NOT EXISTS `purchaseitem` (
  `purID` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` varchar(50) DEFAULT NULL,
  `purchase_no` bigint(20) NOT NULL,
  `suplierID` int(11) NOT NULL,
  `paymenttype` int(11) DEFAULT NULL,
  `bankid` int(11) DEFAULT NULL,
  `total_price` decimal(19,3) NOT NULL DEFAULT 0.000,
  `paid_amount` decimal(19,3) DEFAULT 0.000,
  `details` text DEFAULT NULL,
  `purchasedate` date NOT NULL,
  `purchaseexpiredate` date NOT NULL,
  `vat` decimal(19,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `transpostcost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `labourcost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `othercost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `savedby` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `terms_cond` text DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  PRIMARY KEY (`purID`),
  KEY `invoiceid` (`invoiceid`),
  KEY `suplierID` (`suplierID`),
  KEY `bankid` (`bankid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

DROP TABLE IF EXISTS `purchase_details`;
CREATE TABLE IF NOT EXISTS `purchase_details` (
  `detailsid` int(11) NOT NULL AUTO_INCREMENT,
  `purchaseid` int(11) NOT NULL,
  `typeid` int(11) DEFAULT NULL,
  `indredientid` int(11) NOT NULL,
  `quantity` decimal(19,3) NOT NULL DEFAULT 0.000,
  `conversionvalue` decimal(19,3) DEFAULT NULL,
  `unitname` varchar(80) NOT NULL,
  `price` decimal(19,3) NOT NULL DEFAULT 0.000,
  `itemvat` decimal(19,2) NOT NULL DEFAULT 0.00,
  `vattype` int(11) NOT NULL DEFAULT 0 COMMENT '0=amount,1=percent',
  `totalprice` decimal(19,3) NOT NULL DEFAULT 0.000,
  `purchaseby` int(11) NOT NULL,
  `purchasedate` date NOT NULL,
  `purchaseexpiredate` date NOT NULL,
  PRIMARY KEY (`detailsid`),
  KEY `purchaseid` (`purchaseid`),
  KEY `indredientid` (`indredientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

DROP TABLE IF EXISTS `purchase_return`;
CREATE TABLE IF NOT EXISTS `purchase_return` (
  `preturn_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `po_no` varchar(120) NOT NULL,
  `return_date` date NOT NULL,
  `totalamount` float NOT NULL,
  `return_reason` varchar(250) NOT NULL,
  `total_vat` decimal(10,2) DEFAULT NULL,
  `total_discount` decimal(10,2) DEFAULT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  `updateby` int(11) NOT NULL,
  `updatedate` datetime NOT NULL,
  PRIMARY KEY (`preturn_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `updateby` (`updateby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_details`
--

DROP TABLE IF EXISTS `purchase_return_details`;
CREATE TABLE IF NOT EXISTS `purchase_return_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `preturn_id` int(11) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `po_no` varchar(120) DEFAULT NULL,
  `qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `product_rate` float NOT NULL,
  `store_id` int(11) NOT NULL,
  `discount` float DEFAULT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `vattype` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rate_type`
--

DROP TABLE IF EXISTS `rate_type`;
CREATE TABLE IF NOT EXISTS `rate_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `r_type_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rate_type`
--

INSERT INTO `rate_type` (`id`, `r_type_name`) VALUES(1, 'Hourly');
INSERT INTO `rate_type` (`id`, `r_type_name`) VALUES(2, 'Salary');

-- --------------------------------------------------------

--
-- Table structure for table `recalculate_vat_log`
--

DROP TABLE IF EXISTS `recalculate_vat_log`;
CREATE TABLE IF NOT EXISTS `recalculate_vat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remark` text NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `recalculate_vat_log`
--

INSERT INTO `recalculate_vat_log` (`id`, `remark`, `status`, `user_id`, `created_at`) VALUES(1, 'recalculate vat setting enabled', 1, 2, '2024-02-14 18:00:42');
INSERT INTO `recalculate_vat_log` (`id`, `remark`, `status`, `user_id`, `created_at`) VALUES(2, 'recalculate vat setting disabled', 0, 2, '2024-02-14 18:00:45');

-- --------------------------------------------------------

--
-- Table structure for table `reservationofday`
--

DROP TABLE IF EXISTS `reservationofday`;
CREATE TABLE IF NOT EXISTS `reservationofday` (
  `offdayid` int(11) NOT NULL AUTO_INCREMENT,
  `offdaydate` date NOT NULL,
  `availtime` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`offdayid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reservationofday`
--

INSERT INTO `reservationofday` (`offdayid`, `offdaydate`, `availtime`, `is_active`) VALUES(1, '2024-06-27', '13:30:30-17:35:30', 1);
INSERT INTO `reservationofday` (`offdayid`, `offdaydate`, `availtime`, `is_active`) VALUES(2, '2024-06-29', '12:30:30-18:50:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rest_table`
--

DROP TABLE IF EXISTS `rest_table`;
CREATE TABLE IF NOT EXISTS `rest_table` (
  `tableid` int(11) NOT NULL AUTO_INCREMENT,
  `tablename` varchar(50) NOT NULL,
  `person_capicity` int(11) NOT NULL,
  `table_icon` varchar(255) NOT NULL,
  `floor` int(11) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=booked,0=free',
  PRIMARY KEY (`tableid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rest_table`
--

INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(1, '1', 2, '1', 3, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(2, '2', 4, '4', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(3, '3', 2, '2', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(6, '6', 4, '4', 1, 1);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(7, '7', 8, '3', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(8, '8', 6, '1', 3, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(9, '9', 3, '1', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(10, 'VIP', 8, '3', 2, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(11, 'Round', 6, '3', 2, 1);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(12, 'No 1', 2, '2', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(13, 'Bilas', 3, '4', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(14, 'New', 3, '1', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(15, 'T3', 4, '4', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(16, 'T8', 6, '3', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(17, 'T5', 4, '1', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(18, 'T-11', 5, '3', 1, 0);
INSERT INTO `rest_table` (`tableid`, `tablename`, `person_capicity`, `table_icon`, `floor`, `status`) VALUES(19, 'T-12', 6, '3', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE IF NOT EXISTS `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `create` tinyint(1) DEFAULT NULL,
  `read` tinyint(1) DEFAULT NULL,
  `update` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_module_id` (`fk_module_id`),
  KEY `fk_user_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_setup_header`
--

DROP TABLE IF EXISTS `salary_setup_header`;
CREATE TABLE IF NOT EXISTS `salary_setup_header` (
  `s_s_h_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `salary_payable` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `absent_deduct` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `tax_manager` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`s_s_h_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_sheet_generate`
--

DROP TABLE IF EXISTS `salary_sheet_generate`;
CREATE TABLE IF NOT EXISTS `salary_sheet_generate` (
  `ssg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(20) NOT NULL,
  `name` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gdate` varchar(20) DEFAULT NULL,
  `start_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end_date` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `generate_by` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int(11) DEFAULT 0,
  `approved_by` varchar(100) DEFAULT NULL,
  `approve_date` date DEFAULT '1970-01-01',
  PRIMARY KEY (`ssg_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `salary_type`
--

DROP TABLE IF EXISTS `salary_type`;
CREATE TABLE IF NOT EXISTS `salary_type` (
  `salary_type_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sal_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `emp_sal_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `default_amount` varchar(30) NOT NULL,
  `amount_type` int(11) DEFAULT 1,
  `status` varchar(50) NOT NULL,
  `acchead` int(11) DEFAULT NULL,
  PRIMARY KEY (`salary_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `salary_type`
--

INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(1, 'House Rent', '1', '', 1, '', NULL);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(2, 'Medical', '1', '', 1, '', NULL);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(3, 'Transport', '1', '', 1, '', 10);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(4, 'D', '1', '', 1, '', NULL);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(5, 'M', '0', '', 1, '', NULL);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(6, 'Food Allowance', '0', '', 0, '', 129);
INSERT INTO `salary_type` (`salary_type_id`, `sal_name`, `emp_sal_type`, `default_amount`, `amount_type`, `status`, `acchead`) VALUES(7, 'Conveyance', '0', '', 0, '', 24);

-- --------------------------------------------------------

--
-- Table structure for table `sale_return`
--

DROP TABLE IF EXISTS `sale_return`;
CREATE TABLE IF NOT EXISTS `sale_return` (
  `oreturn_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `return_date` date NOT NULL,
  `totalamount` decimal(19,3) NOT NULL,
  `totaldiscount` decimal(19,3) NOT NULL,
  `total_vat` decimal(19,3) DEFAULT NULL,
  `service_charge` decimal(19,3) DEFAULT NULL,
  `return_reason` varchar(250) NOT NULL,
  `adjustment_status` tinyint(4) DEFAULT 0 COMMENT '0 = not,1=yes [adjustment]',
  `pay_status` tinyint(4) DEFAULT 0 COMMENT '1=payment complete,\r\n0=not payment\r\n\r\n,This payment was made from the return list.',
  `pay_amount` decimal(19,3) NOT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  `updateby` int(11) NOT NULL,
  `updatedate` datetime NOT NULL,
  `full_invoice_return` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`oreturn_id`),
  KEY `supplier_id` (`customer_id`),
  KEY `updateby` (`updateby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_details`
--

DROP TABLE IF EXISTS `sale_return_details`;
CREATE TABLE IF NOT EXISTS `sale_return_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oreturn_id` int(11) NOT NULL,
  `order_id` bigint(20) DEFAULT NULL,
  `product_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `product_rate` float NOT NULL,
  `store_id` int(11) NOT NULL,
  `discount` decimal(19,3) DEFAULT NULL,
  `itemvat` decimal(19,3) DEFAULT NULL,
  `tamount` decimal(19,3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sec_menu_item`
--

DROP TABLE IF EXISTS `sec_menu_item`;
CREATE TABLE IF NOT EXISTS `sec_menu_item` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_title` varchar(200) DEFAULT NULL,
  `page_url` varchar(250) DEFAULT NULL,
  `module` varchar(200) DEFAULT NULL,
  `parent_menu` int(11) DEFAULT NULL,
  `is_report` tinyint(1) DEFAULT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1779 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sec_menu_item`
--

INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1, 'manage_category', '', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(2, 'category_list', 'item_category', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(3, 'add_category', 'create', 'itemmanage', 2, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(4, 'manage_food', '', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(5, 'food_list', 'item_food', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(6, 'add_food', 'index', 'itemmanage', 5, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(7, 'food_varient', 'foodvarientlist', 'itemmanage', 5, 0, 2, '2018-11-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(8, 'add_varient', 'addvariant', 'itemmanage', 5, 0, 2, '2018-11-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(9, 'food_availablity', 'availablelist', 'itemmanage', 5, 0, 2, '2018-11-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(10, 'add_availablity', 'addavailable', 'itemmanage', 5, 0, 2, '2018-11-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(11, 'manage_addons', '', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(12, 'addons_list', 'menu_addons', 'itemmanage', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(13, 'add_adons', 'create', 'itemmanage', 8, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(14, 'manage_unitmeasurement', '', 'setting', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(15, 'unit_list', 'unitmeasurement', 'setting', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(16, 'unit_add', 'create', 'setting', 12, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(17, 'manage_ingradient', '', 'setting', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(18, 'ingradient_list', 'ingradient', 'setting', 0, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(19, 'add_ingredient', 'create', 'setting', 15, 0, 2, '2018-11-05 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(20, 'assign_adons_list', 'assignaddons', 'itemmanage', 8, 0, 2, '2018-11-06 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(21, 'assign_adons', 'assignaddonscreate', 'itemmanage', 8, 0, 2, '2018-11-06 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(28, 'membership_management', '', 'setting', 0, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(29, 'membership_list', 'index', 'setting', 28, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(30, 'membership_add', 'create', 'setting', 29, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(31, 'payment_setting', '', 'setting', 0, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(32, 'paymentmethod_list', 'index', 'setting', 31, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(33, 'payment_add', 'create', 'setting', 32, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(34, 'shipping_setting', '', 'setting', 0, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(35, 'shipping_list', 'index', 'setting', 34, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(36, 'shipping_add', 'create', 'setting', 35, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(37, 'supplier_manage', 'supplierlist', 'purchase', 40, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(38, 'supplier_list', 'index', 'setting', 37, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(39, 'supplier_add', 'create', 'setting', 38, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(40, 'purchase_item', 'index', 'purchase', 0, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(41, 'purchase_add', 'create', 'purchase', 40, 0, 2, '2018-11-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(42, 'table_manage', '', 'setting', 0, 0, 2, '2018-11-13 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(43, 'add_new_table', 'create', 'setting', 44, 0, 2, '2018-11-13 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(44, 'table_list', 'restauranttable', 'setting', 42, 0, 2, '2018-11-13 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(45, 'ordermanage', 'index', 'ordermanage', 0, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(46, 'add_new_order', 'neworder', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(47, 'order_list', 'orderlist', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(48, 'pending_order', 'pendingorder', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(49, 'processing_order', 'processing', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(50, 'complete_order', 'completelist', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(51, 'cancel_order', 'cancellist', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(52, 'pos_invoice', 'pos_invoice', 'ordermanage', 45, 0, 2, '2018-11-22 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(53, 'c_o_a', 'treeview', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(54, 'debit_voucher', 'debit_voucher', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(55, 'credit_voucher', 'credit_voucher', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(56, 'contra_voucher', 'contra_voucher', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(57, 'journal_voucher', 'journal_voucher', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(58, 'voucher_approval', 'voucher_approval', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(59, 'account_report', '', 'accounts', 0, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(60, 'voucher_report', 'coa', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(61, 'cash_book', 'cash_book', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(62, 'bank_book', 'bank_book', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(63, 'general_ledger', 'general_ledger', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(64, 'trial_balance', 'trial_balance', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(65, 'profit_loss', 'profit_loss_report', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(66, 'cash_flow', 'cash_flow_report', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(67, 'coa_print', 'coa_print', 'accounts', 59, 0, 2, '2018-12-17 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(68, 'hrm', '', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(69, 'attendance', 'Home', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(70, 'atn_form', 'atnview', 'hrm', 69, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(71, 'atn_report', 'attendance_list', 'hrm', 69, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(72, 'award', 'Award_controller', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(73, 'new_award', 'create_award', 'hrm', 72, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(74, 'circularprocess', 'Candidate', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(75, 'add_canbasic_info', 'caninfo_create', 'hrm', 74, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(76, 'can_basicinfo_list', 'canInfoview', 'hrm', 74, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(77, 'candidate_basic_info', 'Candidate_select', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(78, 'candidate_shortlist', 'shortlist_form', 'hrm', 77, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(79, 'candidate_interview', 'interview_form', 'hrm', 77, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(80, 'candidate_selection', 'selection_form', 'hrm', 77, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(81, 'department', 'Department_controller', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(82, 'departmentfrm', 'create_dept', 'hrm', 81, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(83, 'division', 'Division_controller', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(84, 'add_division', 'division_form', 'hrm', 83, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(85, 'ehrm', 'Employees', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(86, 'division_list', 'position_view', 'hrm', 87, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(87, 'designation', 'create_position', 'hrm', 87, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(88, 'add_employee', 'viewEmhistory', 'hrm', 87, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(89, 'manage_employee', 'manageemployee', 'hrm', 87, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(91, 'emp_sal_payment', 'emp_payment_view', 'hrm', 87, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(92, 'leave', 'leave', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(93, 'weekly_holiday', 'weeklyform', 'hrm', 92, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(94, 'holiday', 'holiday_form', 'hrm', 92, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(95, 'others_leave_application', 'others_leave', 'hrm', 92, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(96, 'add_leave_type', 'leave_type_form', 'hrm', 92, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(97, 'leave_application', 'others_leave', 'hrm', 92, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(98, 'loan', 'loan', 'hrm', 0, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(99, 'loan_grand', 'create_grandloan', 'hrm', 98, 0, 2, '2018-12-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(100, 'loan_installment', 'create_installment', 'hrm', 98, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(101, 'manage_installment', 'installmentView', 'hrm', 98, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(102, 'manage_granted_loan', 'loan_view', 'hrm', 98, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(103, 'loan_report', 'loan_report', 'hrm', 98, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(104, 'payroll', 'Payroll', 'hrm', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(105, 'salary_type_setup', 'create_salary_setup', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(106, 'manage_salary_setup', 'emp_salary_setup_view', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(107, 'salary_setup', 'create_s_setup', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(108, 'manage_salary_type', 'salary_setup_view', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(109, 'salary_generate', 'create_salary_generate', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(110, 'manage_salary_generate', 'salary_generate_view', 'hrm', 104, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(111, 'purchase_return', 'return_form', 'purchase', 40, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(112, 'return_invoice', 'return_invoice', 'purchase', 40, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(118, 'table_setting', 'tablesetting', 'setting', 44, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(119, 'customer_type', '', 'setting', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(120, 'customertype_list', 'customertype', 'setting', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(121, 'add_type', 'create', 'setting', 120, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(122, 'currency', '', 'setting', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(123, 'currency_list', 'currency', 'setting', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(124, 'currency_add', 'create', 'setting', 123, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(125, 'production', '', 'production', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(126, 'production_set_list', 'production', 'production', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(127, 'set_productionunit', 'productionunit', 'production', 126, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(128, 'production_add', 'create', 'production', 126, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(129, 'production_list', 'addproduction', 'production', 126, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(130, 'reservation', '', 'reservation', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(131, 'reservation_table', 'tablebooking', 'reservation', 130, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(132, 'update_ord', 'updateorder', 'ordermanage', 45, 0, 2, '2019-12-11 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(133, 'kitchen_dashboard', 'kitchen', 'ordermanage', 45, 0, 2, '2020-02-13 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(134, 'counter_dashboard', 'counterboard', 'ordermanage', 45, 0, 2, '2020-02-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(191, 'counter_list', 'counterlist', 'ordermanage', 45, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(192, 'pos_setting', 'possetting', 'ordermanage', 45, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(193, 'sound_setting', 'soundsetting', 'ordermanage', 45, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(194, 'supplier_ledger', 'supplier_ledger_report', 'purchase', 38, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(195, 'stock_out_ingredients', 'stock_out_ingredients', 'purchase', 40, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(205, 'production_setting', 'possetting', 'production', 125, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(206, 'kitchen_setting', 'kitchensetting', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(207, 'kitchen_assign', 'assignkitchen', 'setting', 206, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(208, 'sms_setting', 'smsetting', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(209, 'sms_configuration', 'sms_configuration', 'setting', 208, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(210, 'sms_temp', 'sms_template', 'setting', 208, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(211, 'bank', 'bank_list', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(212, 'list_of_bank', 'index', 'setting', 211, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(213, 'language', 'language', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(214, 'application_setting', 'setting', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(215, 'server_setting', 'serversetting', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(216, 'factory_reset', 'factoryreset', 'setting', 214, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(217, 'country', 'country_city_list', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(218, 'state', 'statelist', 'setting', 217, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(219, 'city', 'citylist', 'setting', 217, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(220, 'commission', 'Commissionsetting/payroll_commission', 'setting', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(221, 'supplier_payment', 'supplier_payments', 'accounts', 59, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(222, 'cash_adjustment', 'cash_adjustment', 'accounts', 59, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(223, 'balance_sheet', 'balance_sheet', 'accounts', 59, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(224, 'expense', 'Cexpense', 'hrm', 0, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(225, 'unavailable_day', 'unavailablelist', 'reservation', 130, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(226, 'reservasetting', 'setting', 'reservation', 130, 0, 2, '2021-03-28 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1388, 'dashboard', 'home', 'dashboard', 0, 0, 2, '2021-09-02 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1530, 'tdayorder', 'todayallorder', 'ordermanage', 45, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1531, 'ongoingorder', 'getongoingorder', 'ordermanage', 45, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1532, 'qr-order', 'allqrorder', 'ordermanage', 45, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1533, 'onlineord', 'onlinellorder', 'ordermanage', 45, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1534, 'financial_year', 'fin_yearlist', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1535, 'financial_year_end', 'fin_yearend', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1536, 'predefined_accounts', 'predefined_accounts', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1537, 'opening_balance', 'opening_balanceform', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1538, 'opening_balance_list', 'opening_balancelist', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1539, 'bank_reconciliation', 'bank_reconciliation', 'accounts', 0, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1540, 'sub_ledgder', 'sub_ledger', 'accounts', 59, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1541, 'statementofexpen', 'expenditure_statement', 'accounts', 59, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1542, 'fixed_asset_schedule', 'fixedasset_schedule', 'accounts', 59, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1543, 'bank_reconciliation_report', 'bank_reconciliation_report', 'accounts', 59, 0, 2, '2022-06-12 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1586, 'qrapp', 'qrmodule', 'qrapp', 0, 0, 3, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1587, 'qr_order', 'index', 'qrapp', 1586, 0, 3, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1588, 'tableqr_code', 'tableqrcode', 'qrapp', 1586, 0, 3, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1589, 'qr_payment', 'qrpaymentsetting', 'qrapp', 1586, 0, 3, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1590, 'qr_themesetting', 'qrthemesetting', 'qrapp', 1586, 0, 3, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1597, 'pos_access_role', 'posrole', 'ordermanage', 45, NULL, 0, '0000-00-00 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1598, 'manage_topping', NULL, 'itemmanage', 4, NULL, 0, '0000-00-00 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1599, 'topping_list', 'menu_topping', 'itemmanage', 1598, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1600, 'assign_topping_list', 'assigntopping', 'itemmanage', 1598, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1601, 'Userwiseitem', NULL, 'itemmanage', NULL, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1602, 'itemassign_to_user', 'itemassign', 'itemmanage', 1601, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1603, 'openingstock', 'stock_out_ingredients', 'purchase', 40, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1604, 'damage_expire', 'damagelist', 'production', 126, NULL, 2, '2023-01-23 14:46:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1618, 'taxsetting', 'taxsettingback', 'taxsetting', 0, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1619, 'tex_setting', 'showtaxsetting', 'taxsetting', 1618, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1620, 'tex_enable', 'taxsetting', 'taxsetting', 1618, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1621, 'pay_thirdparty_commision', 'paydelivarycommision', 'ordermanage', 45, 0, 2, '2023-07-11 11:58:56');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1622, 'pos_invoice_return', 'orderreturn', 'ordermanage', 45, 0, 2, '2023-07-11 11:58:56');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1623, 'return_reportlist', 'returntbllist', 'ordermanage', 1622, 0, 2, '2023-07-11 12:04:41');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1624, 'creditsalereport', 'credit_sale_reportfrom', 'ordermanage', 45, 0, 2, '2023-07-11 12:04:41');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1662, 'report', 'reports', 'report', 0, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1663, 'purchase_report', 'index', 'report', 113, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1664, 'stock_report_product_wise', 'productwise', 'report', 113, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1665, 'purchase_report_ingredient', 'ingredientwise', 'report', 113, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1666, 'sell_report', 'report', 'report', 113, 0, 2, '2018-12-19 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1667, 'sell_report_items', 'sellrptItems', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1668, 'scharge_report', 'servicerpt', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1669, 'sell_report_waiters', 'sellrptwaiter', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1670, 'kitchen_sell', 'kichansrpt', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1671, 'sell_report_delvirytype', 'sellrptdelvirytype', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1672, 'sell_report_casher', 'sellrptCasher', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1673, 'unpaid_sell', 'unpaid_sell', 'report', 117, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1674, 'sell_report_filter', 'sellrpt2', 'report', 113, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1675, 'sele_by_date', 'sellrptbydate', 'report', 113, 0, 2, '2021-01-21 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1676, 'sell_report_cashregister', 'cashregister', 'report', 113, 0, 2, '2023-01-15 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1677, 'commission', 'payroll_commission', 'report', 113, 0, 2, '2023-01-15 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1678, 'sale_by_table', 'table_sale', 'report', 113, 0, 2, '2023-01-15 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1679, 'itemsalesbycashier', 'itemreportcashier', 'report', 113, 0, 2, '2023-01-15 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1680, 'datebydatesale', 'datewisereport', 'report', 111, 0, 2, '2023-05-27 16:36:42');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1681, 'sell_reportchild', 'sellrpt', 'report', 117, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1682, 'sell_report_addons', 'sellrptaddons', 'report', 117, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1683, 'vat_report', 'vatrpt', 'report', 117, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1684, 'return_invoice_rept', 'return_invoice', 'report', 117, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1685, 'sale_return_report', 'returnreportlist', 'report', 117, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1686, 'commission_report', '', 'report', 113, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1687, 'thirdparty_sale_comm', 'third_party_sale_commission', 'report', 1626, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1688, 'paymentgatewaycomm', 'payment_gateway_reportfrom', 'report', 1626, 0, 2, '2023-09-16 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1698, 'reservation_dashboard', 'reservation_dashboard', 'reservation', 130, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1699, 'reservation_dashboard', 'reservation_dashboard', 'reservation', 130, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1712, 'websiteorder', 'Websitorder', 'ordermanage', 45, 0, 2, '2020-12-03 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1713, 'assigned_kitchen', 'assignInventoryList', 'purchase', 40, 0, 2, '2023-12-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1714, 'assign_kitchen', 'assigninventory', 'purchase', 40, 0, 2, '2023-12-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1715, 'reedem_list', 'reedemList', 'purchase', 40, 0, 2, '2023-12-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1716, 'kitchen_user_stock_report', 'kitchenUserStockReport', 'purchase', 40, 0, 2, '2023-12-07 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1743, 'purchase_return_list', 'purchase_return_list', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1744, 'add_reedem', 'addReedem', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1745, 'so_request_list', 'soRequestList', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1746, 'so_request_add', 'soRequestAdd', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1747, 'admin_user_kitchen_stock_report', 'AdminUserStockReport', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1748, 'physicalstock', 'physical_stock', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1749, 'add_po_request', 'add_po_request', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1750, 'po_request_list', 'po_request_list', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1751, 'add_supplier_po_request', 'add_supplier_po_request', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1752, 'supplier_po_request_list', 'supplier_po_request_list', 'purchase', 40, 0, 2, '2024-03-18 00:00:00');
INSERT INTO `sec_menu_item` (`menu_id`, `menu_title`, `page_url`, `module`, `parent_menu`, `is_report`, `createby`, `createdate`) VALUES(1774, 'update_ingredient', 'updateintfrm', 'setting', 18, 0, 2, '2024-05-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sec_role_permission`
--

DROP TABLE IF EXISTS `sec_role_permission`;
CREATE TABLE IF NOT EXISTS `sec_role_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `can_access` tinyint(1) NOT NULL,
  `can_create` tinyint(1) NOT NULL,
  `can_edit` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3347 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sec_role_permission`
--

INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2289, 3, 53, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2290, 3, 54, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2291, 3, 55, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2292, 3, 56, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2293, 3, 57, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2294, 3, 58, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2295, 3, 59, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2296, 3, 60, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2297, 3, 61, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2298, 3, 62, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2299, 3, 63, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2300, 3, 64, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2301, 3, 65, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2302, 3, 66, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2303, 3, 67, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2304, 3, 221, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2305, 3, 222, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2306, 3, 223, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2307, 3, 1534, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2308, 3, 1535, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2309, 3, 1536, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2310, 3, 1537, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2311, 3, 1538, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2312, 3, 1539, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2313, 3, 1540, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2314, 3, 1541, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2315, 3, 1542, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2316, 3, 1543, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2317, 3, 1388, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2318, 3, 68, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2319, 3, 69, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2320, 3, 70, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2321, 3, 71, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2322, 3, 72, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2323, 3, 73, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2324, 3, 74, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2325, 3, 75, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2326, 3, 76, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2327, 3, 77, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2328, 3, 78, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2329, 3, 79, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2330, 3, 80, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2331, 3, 81, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2332, 3, 82, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2333, 3, 83, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2334, 3, 84, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2335, 3, 85, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2336, 3, 86, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2337, 3, 87, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2338, 3, 88, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2339, 3, 89, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2340, 3, 91, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2341, 3, 92, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2342, 3, 93, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2343, 3, 94, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2344, 3, 95, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2345, 3, 96, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2346, 3, 97, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2347, 3, 98, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2348, 3, 99, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2349, 3, 100, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2350, 3, 101, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2351, 3, 102, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2352, 3, 103, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2353, 3, 104, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2354, 3, 105, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2355, 3, 106, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2356, 3, 107, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2357, 3, 108, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2358, 3, 109, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2359, 3, 110, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2360, 3, 224, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2361, 3, 1, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2362, 3, 2, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2363, 3, 3, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2364, 3, 4, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2365, 3, 5, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2366, 3, 6, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2367, 3, 7, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2368, 3, 8, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2369, 3, 9, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2370, 3, 10, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2371, 3, 11, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2372, 3, 12, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2373, 3, 13, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2374, 3, 20, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2375, 3, 21, 1, 1, 1, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2376, 3, 45, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2377, 3, 46, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2378, 3, 47, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2379, 3, 48, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2380, 3, 49, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2381, 3, 50, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2382, 3, 51, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2383, 3, 52, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2384, 3, 132, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2385, 3, 133, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2386, 3, 134, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2387, 3, 191, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2388, 3, 192, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2389, 3, 193, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2390, 3, 1530, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2391, 3, 1531, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2392, 3, 1532, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2393, 3, 1533, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2394, 3, 125, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2395, 3, 126, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2396, 3, 127, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2397, 3, 128, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2398, 3, 129, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2399, 3, 205, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2400, 3, 40, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2401, 3, 41, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2402, 3, 111, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2403, 3, 112, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2404, 3, 194, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2405, 3, 195, 1, 1, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2406, 3, 1586, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2407, 3, 1587, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2408, 3, 1588, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2409, 3, 1589, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2410, 3, 1590, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2411, 3, 113, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2412, 3, 114, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2413, 3, 115, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2414, 3, 116, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2415, 3, 117, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2416, 3, 196, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2417, 3, 197, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2418, 3, 198, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2419, 3, 199, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2420, 3, 200, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2421, 3, 201, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2422, 3, 202, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2423, 3, 203, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2424, 3, 204, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2425, 3, 1591, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2426, 3, 1592, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2427, 3, 1593, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2428, 3, 1594, 1, 1, 1, 1, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2429, 3, 130, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2430, 3, 131, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2431, 3, 225, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2432, 3, 226, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2433, 3, 28, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2434, 3, 29, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2435, 3, 30, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2436, 3, 31, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2437, 3, 32, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2438, 3, 33, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2439, 3, 34, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2440, 3, 35, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2441, 3, 36, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2442, 3, 37, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2443, 3, 38, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2444, 3, 39, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2445, 3, 42, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2446, 3, 43, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2447, 3, 44, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2448, 3, 118, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2449, 3, 119, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2450, 3, 120, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2451, 3, 121, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2452, 3, 122, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2453, 3, 123, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2454, 3, 124, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2455, 3, 206, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2456, 3, 207, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2457, 3, 208, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2458, 3, 209, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2459, 3, 210, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2460, 3, 211, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2461, 3, 212, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2462, 3, 213, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2463, 3, 214, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2464, 3, 215, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2465, 3, 216, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2466, 3, 217, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2467, 3, 218, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2468, 3, 219, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2469, 3, 220, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2470, 3, 14, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2471, 3, 15, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2472, 3, 16, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2473, 3, 17, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2474, 3, 18, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(2475, 3, 19, 0, 0, 0, 0, 2, '2023-01-15 03:34:48');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3117, 1, 1, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3118, 1, 2, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3119, 1, 3, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3120, 1, 4, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3121, 1, 5, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3122, 1, 6, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3123, 1, 7, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3124, 1, 8, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3125, 1, 9, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3126, 1, 10, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3127, 1, 11, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3128, 1, 12, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3129, 1, 13, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3130, 1, 20, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3131, 1, 21, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3132, 1, 1598, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3133, 1, 1599, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3134, 1, 1600, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3135, 1, 1601, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3136, 1, 1602, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3137, 1, 14, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3138, 1, 15, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3139, 1, 16, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3140, 1, 17, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3141, 1, 18, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3142, 1, 19, 0, 0, 0, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3143, 1, 28, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3144, 1, 29, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3145, 1, 30, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3146, 1, 31, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3147, 1, 32, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3148, 1, 33, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3149, 1, 34, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3150, 1, 35, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3151, 1, 36, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3152, 1, 37, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3153, 1, 38, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3154, 1, 39, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3155, 1, 42, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3156, 1, 43, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3157, 1, 44, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3158, 1, 118, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3159, 1, 119, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3160, 1, 120, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3161, 1, 121, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3162, 1, 122, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3163, 1, 123, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3164, 1, 124, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3165, 1, 206, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3166, 1, 207, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3167, 1, 208, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3168, 1, 209, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3169, 1, 210, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3170, 1, 211, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3171, 1, 212, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3172, 1, 213, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3173, 1, 214, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3174, 1, 215, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3175, 1, 216, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3176, 1, 217, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3177, 1, 218, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3178, 1, 219, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3179, 1, 220, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3180, 1, 40, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3181, 1, 41, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3182, 1, 111, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3183, 1, 112, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3184, 1, 194, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3185, 1, 195, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3186, 1, 1603, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3187, 1, 1713, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3188, 1, 1714, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3189, 1, 1715, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3190, 1, 1716, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3191, 1, 45, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3192, 1, 46, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3193, 1, 47, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3194, 1, 48, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3195, 1, 49, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3196, 1, 50, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3197, 1, 51, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3198, 1, 52, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3199, 1, 132, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3200, 1, 133, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3201, 1, 134, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3202, 1, 191, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3203, 1, 192, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3204, 1, 193, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3205, 1, 1530, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3206, 1, 1531, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3207, 1, 1532, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3208, 1, 1533, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3209, 1, 1597, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3210, 1, 1621, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3211, 1, 1622, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3212, 1, 1623, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3213, 1, 1624, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3214, 1, 1712, 1, 1, 1, 1, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3215, 1, 53, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3216, 1, 54, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3217, 1, 55, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3218, 1, 56, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3219, 1, 57, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3220, 1, 58, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3221, 1, 59, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3222, 1, 60, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3223, 1, 61, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3224, 1, 62, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3225, 1, 63, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3226, 1, 64, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3227, 1, 65, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3228, 1, 66, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3229, 1, 67, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3230, 1, 221, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3231, 1, 222, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3232, 1, 223, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3233, 1, 1534, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3234, 1, 1535, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3235, 1, 1536, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3236, 1, 1537, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3237, 1, 1538, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3238, 1, 1539, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3239, 1, 1540, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3240, 1, 1541, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3241, 1, 1542, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3242, 1, 1543, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3243, 1, 68, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3244, 1, 69, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3245, 1, 70, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3246, 1, 71, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3247, 1, 72, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3248, 1, 73, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3249, 1, 74, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3250, 1, 75, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3251, 1, 76, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3252, 1, 77, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3253, 1, 78, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3254, 1, 79, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3255, 1, 80, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3256, 1, 81, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3257, 1, 82, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3258, 1, 83, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3259, 1, 84, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3260, 1, 85, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3261, 1, 86, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3262, 1, 87, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3263, 1, 88, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3264, 1, 89, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3265, 1, 91, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3266, 1, 92, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3267, 1, 93, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3268, 1, 94, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3269, 1, 95, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3270, 1, 96, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3271, 1, 97, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3272, 1, 98, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3273, 1, 99, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3274, 1, 100, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3275, 1, 101, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3276, 1, 102, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3277, 1, 103, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3278, 1, 104, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3279, 1, 105, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3280, 1, 106, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3281, 1, 107, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3282, 1, 108, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3283, 1, 109, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3284, 1, 110, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3285, 1, 224, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3286, 1, 125, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3287, 1, 126, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3288, 1, 127, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3289, 1, 128, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3290, 1, 129, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3291, 1, 205, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3292, 1, 1604, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3293, 1, 130, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3294, 1, 131, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3295, 1, 225, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3296, 1, 226, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3297, 1, 1698, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3298, 1, 1699, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3299, 1, 1388, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3300, 1, 1586, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3301, 1, 1587, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3302, 1, 1588, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3303, 1, 1589, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3304, 1, 1590, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3305, 1, 1618, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3306, 1, 1619, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3307, 1, 1620, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3308, 1, 1662, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3309, 1, 1663, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3310, 1, 1664, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3311, 1, 1665, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3312, 1, 1666, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3313, 1, 1667, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3314, 1, 1668, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3315, 1, 1669, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3316, 1, 1670, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3317, 1, 1671, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3318, 1, 1672, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3319, 1, 1673, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3320, 1, 1674, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3321, 1, 1675, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3322, 1, 1676, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3323, 1, 1677, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3324, 1, 1678, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3325, 1, 1679, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3326, 1, 1680, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3327, 1, 1681, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3328, 1, 1682, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3329, 1, 1683, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3330, 1, 1684, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3331, 1, 1685, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3332, 1, 1686, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3333, 1, 1687, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3334, 1, 1688, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3335, 1, 1692, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3336, 1, 1693, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3337, 1, 1694, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3338, 1, 1695, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3339, 1, 1696, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3340, 1, 1697, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3341, 1, 1703, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3342, 1, 1704, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3343, 1, 1705, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3344, 1, 1706, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3345, 1, 1707, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');
INSERT INTO `sec_role_permission` (`id`, `role_id`, `menu_id`, `can_access`, `can_create`, `can_edit`, `can_delete`, `createby`, `createdate`) VALUES(3346, 1, 1708, 0, 0, 0, 0, 2, '2024-01-04 02:12:35');

-- --------------------------------------------------------

--
-- Table structure for table `sec_role_tbl`
--

DROP TABLE IF EXISTS `sec_role_tbl`;
CREATE TABLE IF NOT EXISTS `sec_role_tbl` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` text NOT NULL,
  `role_description` text NOT NULL,
  `create_by` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `role_status` int(11) NOT NULL DEFAULT 1,
  `app_posaccess` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sec_role_tbl`
--

INSERT INTO `sec_role_tbl` (`role_id`, `role_name`, `role_description`, `create_by`, `date_time`, `role_status`, `app_posaccess`) VALUES(1, 'kitchen', 'manage kitchen', 2, '2020-10-12 10:27:03', 1, 0);
INSERT INTO `sec_role_tbl` (`role_id`, `role_name`, `role_description`, `create_by`, `date_time`, `role_status`, `app_posaccess`) VALUES(2, 'Counter', 'Display Order timing', 2, '2020-10-12 10:27:45', 1, 0);
INSERT INTO `sec_role_tbl` (`role_id`, `role_name`, `role_description`, `create_by`, `date_time`, `role_status`, `app_posaccess`) VALUES(3, 'Waiter', 'Order Taken and served food', 2, '2020-10-12 10:29:13', 1, 0);
INSERT INTO `sec_role_tbl` (`role_id`, `role_name`, `role_description`, `create_by`, `date_time`, `role_status`, `app_posaccess`) VALUES(4, 'App sales', 'Manage order fron Android app', 2, '2023-07-06 14:49:39', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sec_user_access_tbl`
--

DROP TABLE IF EXISTS `sec_user_access_tbl`;
CREATE TABLE IF NOT EXISTS `sec_user_access_tbl` (
  `role_acc_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_role_id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  PRIMARY KEY (`role_acc_id`),
  KEY `fk_role_id` (`fk_role_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sec_user_access_tbl`
--

INSERT INTO `sec_user_access_tbl` (`role_acc_id`, `fk_role_id`, `fk_user_id`) VALUES(1, 3, 172);
INSERT INTO `sec_user_access_tbl` (`role_acc_id`, `fk_role_id`, `fk_user_id`) VALUES(4, 1, 166);
INSERT INTO `sec_user_access_tbl` (`role_acc_id`, `fk_role_id`, `fk_user_id`) VALUES(5, 2, 166);
INSERT INTO `sec_user_access_tbl` (`role_acc_id`, `fk_role_id`, `fk_user_id`) VALUES(6, 1, 177);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `storename` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `logoweb` varchar(255) DEFAULT NULL,
  `favicon` varchar(100) DEFAULT NULL,
  `opentime` varchar(255) DEFAULT NULL,
  `closetime` varchar(255) DEFAULT NULL,
  `vat` decimal(10,2) NOT NULL DEFAULT 0.00,
  `isvatnumshow` int(11) DEFAULT 0,
  `vattinno` varchar(30) DEFAULT NULL,
  `standard_hours` float NOT NULL DEFAULT 8,
  `discount_type` int(11) NOT NULL DEFAULT 0 COMMENT '0=amount,1=percent',
  `discountrate` decimal(19,3) DEFAULT 0.000,
  `servicecharge` decimal(10,0) NOT NULL DEFAULT 0,
  `service_chargeType` int(11) NOT NULL DEFAULT 0 COMMENT '0=amount,1=percent',
  `currency` int(11) DEFAULT 0,
  `currencyconverter` int(11) NOT NULL DEFAULT 0,
  `showdecimal` int(11) NOT NULL DEFAULT 2,
  `min_prepare_time` varchar(50) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `timezone` varchar(150) NOT NULL,
  `dateformat` text NOT NULL,
  `site_align` varchar(50) DEFAULT NULL,
  `kitchenrefreshtime` int(11) DEFAULT 5,
  `powerbytxt` text DEFAULT NULL,
  `footer_text` varchar(255) DEFAULT NULL,
  `stockvaluationmethod` int(11) NOT NULL DEFAULT 2 COMMENT '1=FIFO,2=LIFO,3=Avg',
  `reservation_open` varchar(30) DEFAULT NULL,
  `reservation_close` varchar(30) DEFAULT NULL,
  `maxreserveperson` int(11) DEFAULT NULL,
  `printtype` int(11) DEFAULT 0,
  `posepagecolor` varchar(100) DEFAULT 'light-theme',
  `tablemaping` int(11) NOT NULL DEFAULT 1,
  `issmsenable` int(11) NOT NULL DEFAULT 0 COMMENT '1=enable,0=disable',
  `deliveryzone` int(11) NOT NULL DEFAULT 0,
  `socketenable` int(11) DEFAULT 0,
  `desktopinstallationkey` text DEFAULT NULL,
  `handshakebranch_key` varchar(50) DEFAULT NULL,
  `is_auto_approve_acc` int(11) DEFAULT NULL COMMENT ' 1 =Auto,2=Manual',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO setting (id, title, storename, address, email, phone, logo, logoweb, favicon, opentime, closetime, vat, isvatnumshow, vattinno, standard_hours, discount_type, discountrate, servicecharge, service_chargeType, currency, currencyconverter, showdecimal, min_prepare_time, language, timezone, dateformat, site_align, kitchenrefreshtime, powerbytxt, footer_text, stockvaluationmethod, reservation_open, reservation_close, maxreserveperson, printtype, posepagecolor, tablemaping, issmsenable, deliveryzone, socketenable, desktopinstallationkey, handshakebranch_key, is_auto_approve_acc) VALUES(2, 'RESTORAPOS Restaurant', 'Dhaka Restaurant', '98 Green Road, Farmgate, Dhaka-1215.', 'bdtask@gmail.com', '0123456789', 'assets/img/icons/2019-10-29/h.png', NULL, 'assets/img/icons/m.png', '9:00AM', '10:00PM', 0.00, 1, '23457586', 8, 1, 3.000, 0, 1, 2, 0, 2, '1:00 Hour', 'english', 'Asia/Dhaka', 'd/m/Y', 'LTR', 30, 'Powered By: BDTASK, www.bdtask.com\r\n', '2020©Copyright', 1, '09:00:00', '22:00:00', 20, 2, 'light-theme', 2, 1, 0, 0, '', 'T34BDBRanch1DER', 1);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_method`
--

DROP TABLE IF EXISTS `shipping_method`;
CREATE TABLE IF NOT EXISTS `shipping_method` (
  `ship_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_method` varchar(150) NOT NULL,
  `shippingrate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) DEFAULT NULL,
  `is_active` int(11) NOT NULL DEFAULT 0,
  `shiptype` int(11) DEFAULT NULL COMMENT '1=dinein,2=pickup,3=home',
  PRIMARY KEY (`ship_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `shipping_method`
--

INSERT INTO `shipping_method` (`ship_id`, `shipping_method`, `shippingrate`, `payment_method`, `is_active`, `shiptype`) VALUES(1, 'Home Delivary', 60.00, '20, 14, 9, 4, 1', 1, 3);
INSERT INTO `shipping_method` (`ship_id`, `shipping_method`, `shippingrate`, `payment_method`, `is_active`, `shiptype`) VALUES(2, 'Pickup', 0.00, '20, 4, 1', 1, 2);
INSERT INTO `shipping_method` (`ship_id`, `shipping_method`, `shippingrate`, `payment_method`, `is_active`, `shiptype`) VALUES(3, 'Dine-in', 0.00, '17, 9, 8, 5, 4, 3', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_configuration`
--

DROP TABLE IF EXISTS `sms_configuration`;
CREATE TABLE IF NOT EXISTS `sms_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `gateway` varchar(200) NOT NULL,
  `user_name` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `sms_from` varchar(200) NOT NULL,
  `userid` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_configuration`
--

INSERT INTO `sms_configuration` (`id`, `link`, `gateway`, `user_name`, `password`, `sms_from`, `userid`, `status`) VALUES(1, 'http://smsrank.com/', 'SMS Rank', 'rabbani', '123456', 'smsrank', '', 0);
INSERT INTO `sms_configuration` (`id`, `link`, `gateway`, `user_name`, `password`, `sms_from`, `userid`, `status`) VALUES(2, 'https://www.nexmo.com/', 'nexmo', '50489b88', 'z1cBmtrDeQrOaqhg', 'restaurant', '', 0);
INSERT INTO `sms_configuration` (`id`, `link`, `gateway`, `user_name`, `password`, `sms_from`, `userid`, `status`) VALUES(3, 'https://www.budgetsms.net/', 'budgetsms', 'user1', '1e753da74', 'budgetsms', '21547', 0);
INSERT INTO `sms_configuration` (`id`, `link`, `gateway`, `user_name`, `password`, `sms_from`, `userid`, `status`) VALUES(4, 'http://sms.bdtask.com', 'BDtask SMS', 'C20030055c583f4dc67332.06183368', '000', 'BDtask', '8801847169884', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_template`
--

DROP TABLE IF EXISTS `sms_template`;
CREATE TABLE IF NOT EXISTS `sms_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `default_status` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sms_template`
--

INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(1, 'one', 'your Order {id} is cancel for some reason.', 'Cancel', 0, 1, '2022-08-03 08:15:36', '0000-00-00 00:00:00');
INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(2, 'two', 'your order {id} is completed', 'CompleteOrder', 0, 1, '2018-12-31 07:58:19', '0000-00-00 00:00:00');
INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(3, 'three', 'your order {id} is processing', 'Processing', 0, 1, '2018-11-07 06:00:46', '0000-00-00 00:00:00');
INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(8, 'four', 'Your Order- {id} Has been Placed Successfully.', 'Neworder', 1, 1, '2022-08-03 08:43:28', '0000-00-00 00:00:00');
INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(9, 'Five', '{id} এইটা আপনার ভেরিফিকেশন কোড ', 'otp', 1, 1, '2022-08-04 04:12:00', '0000-00-00 00:00:00');
INSERT INTO `sms_template` (`id`, `template_name`, `message`, `type`, `status`, `default_status`, `created_at`, `updated_at`) VALUES(10, 'Six', 'your order {id} is Accepted', 'orderaccpet', 1, 1, '2022-08-03 08:45:23', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `so_request`
--

DROP TABLE IF EXISTS `so_request`;
CREATE TABLE IF NOT EXISTS `so_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `kitchen_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `kitchennote` text NOT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0 = pending, 1 = approved',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `so_request_details`
--

DROP TABLE IF EXISTS `so_request_details`;
CREATE TABLE IF NOT EXISTS `so_request_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `so_request_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_qty` float(10,2) NOT NULL COMMENT 'requested quantity',
  `given_qty` float(10,2) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `assigned_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `request_by` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribe_emaillist`
--

DROP TABLE IF EXISTS `subscribe_emaillist`;
CREATE TABLE IF NOT EXISTS `subscribe_emaillist` (
  `emailid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `dateinsert` datetime NOT NULL,
  PRIMARY KEY (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_order`
--

DROP TABLE IF EXISTS `sub_order`;
CREATE TABLE IF NOT EXISTS `sub_order` (
  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `vat` float DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `discountnote` text DEFAULT NULL,
  `s_charge` float DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0=unpaid,1=paid',
  `order_menu_id` text DEFAULT NULL,
  `adons_id` varchar(20) DEFAULT NULL,
  `adons_qty` varchar(20) DEFAULT NULL,
  `topid` varchar(255) DEFAULT NULL,
  `invoiceprint` int(11) DEFAULT NULL,
  `offline_suborderid` bigint(20) DEFAULT 0,
  `dueinvoice` int(11) DEFAULT 0,
  PRIMARY KEY (`sub_id`),
  KEY `order_id` (`order_id`),
  KEY `customer_id` (`customer_id`),
  KEY `adons_id` (`adons_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE IF NOT EXISTS `supplier` (
  `supid` int(11) NOT NULL AUTO_INCREMENT,
  `suplier_code` varchar(255) NOT NULL,
  `supName` varchar(100) NOT NULL,
  `supEmail` varchar(100) NOT NULL,
  `contact_person_name` varchar(200) DEFAULT NULL,
  `contact_person_email` varchar(200) DEFAULT NULL,
  `contact_person_phone` varchar(200) DEFAULT NULL,
  `tax_number` varchar(200) DEFAULT NULL,
  `supMobile` varchar(50) NOT NULL,
  `supAddress` text NOT NULL,
  PRIMARY KEY (`supid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supid`, `suplier_code`, `supName`, `supEmail`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `tax_number`, `supMobile`, `supAddress`) VALUES(1, 'sup_009', 'Supplier_1', 'suplier1@gmail.com', '', '', '', '', '3456436457', '');
INSERT INTO `supplier` (`supid`, `suplier_code`, `supName`, `supEmail`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `tax_number`, `supMobile`, `supAddress`) VALUES(2, 'sup_003', 'Lokman', 'lokman@gmail.com', NULL, NULL, NULL, NULL, '0187425732', 'Dhaka');
INSERT INTO `supplier` (`supid`, `suplier_code`, `supName`, `supEmail`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `tax_number`, `supMobile`, `supAddress`) VALUES(3, 'sup_004', 'Shakil', 'shakil@gmail.com', NULL, NULL, NULL, NULL, '02356783', 'Dhaka');
INSERT INTO `supplier` (`supid`, `suplier_code`, `supName`, `supEmail`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `tax_number`, `supMobile`, `supAddress`) VALUES(4, 'sup_005', 'Jhon', 'sup_005Jhon@gmail.com', NULL, NULL, NULL, NULL, '0163545665', 'Bulk Upload');
INSERT INTO `supplier` (`supid`, `suplier_code`, `supName`, `supEmail`, `contact_person_name`, `contact_person_email`, `contact_person_phone`, `tax_number`, `supMobile`, `supAddress`) VALUES(6, 'sup_007', 'Al-amin', 'testainal@gmail.com', 'New Customer 35 Hassan', 'ainal1haque@gmail.com', '01732432434', '6235346', '17324324341', 'DDD sgfsrgrg');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledger`
--

DROP TABLE IF EXISTS `supplier_ledger`;
CREATE TABLE IF NOT EXISTS `supplier_ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(100) NOT NULL,
  `supplier_id` bigint(20) DEFAULT NULL,
  `chalan_no` varchar(100) DEFAULT NULL,
  `deposit_no` varchar(50) DEFAULT NULL,
  `amount` decimal(19,3) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `d_c` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_po_details`
--

DROP TABLE IF EXISTS `supplier_po_details`;
CREATE TABLE IF NOT EXISTS `supplier_po_details` (
  `detailsid` int(11) NOT NULL AUTO_INCREMENT,
  `purchaseid` int(11) NOT NULL,
  `typeid` int(11) DEFAULT NULL,
  `indredientid` int(11) NOT NULL,
  `quantity` decimal(19,3) NOT NULL DEFAULT 0.000,
  `unitname` varchar(80) DEFAULT NULL,
  `price` decimal(19,3) NOT NULL DEFAULT 0.000,
  `itemvat` decimal(19,2) NOT NULL DEFAULT 0.00,
  `vattype` int(11) NOT NULL DEFAULT 0 COMMENT '0=amount,1=percent',
  `totalprice` decimal(19,3) NOT NULL DEFAULT 0.000,
  `purchaseby` int(11) NOT NULL,
  `purchasedate` date NOT NULL,
  PRIMARY KEY (`detailsid`),
  KEY `purchaseid` (`purchaseid`),
  KEY `indredientid` (`indredientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_po_request`
--

DROP TABLE IF EXISTS `supplier_po_request`;
CREATE TABLE IF NOT EXISTS `supplier_po_request` (
  `purID` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` varchar(50) DEFAULT NULL,
  `suplierID` int(11) NOT NULL,
  `paymenttype` int(11) DEFAULT NULL,
  `bankid` int(11) DEFAULT NULL,
  `total_price` decimal(19,3) NOT NULL DEFAULT 0.000,
  `paid_amount` decimal(19,3) DEFAULT 0.000,
  `details` text DEFAULT NULL,
  `purchasedate` date DEFAULT NULL,
  `purchaseexpiredate` date DEFAULT NULL,
  `vat` decimal(19,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `transpostcost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `labourcost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `othercost` decimal(19,2) NOT NULL DEFAULT 0.00,
  `savedby` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `terms_cond` text DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `purchase_status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`purID`),
  KEY `invoiceid` (`invoiceid`),
  KEY `suplierID` (`suplierID`),
  KEY `bankid` (`bankid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `synchronizer_setting`
--

DROP TABLE IF EXISTS `synchronizer_setting`;
CREATE TABLE IF NOT EXISTS `synchronizer_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `port` varchar(10) NOT NULL,
  `debug` varchar(10) NOT NULL,
  `project_root` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `synchronizer_setting`
--

INSERT INTO `synchronizer_setting` (`id`, `hostname`, `username`, `password`, `port`, `debug`, `project_root`) VALUES(8, '70.35.198.244', 'softest3bdtask', 'Ux5O~MBJ#odK', '21', 'true', './public_html/');

-- --------------------------------------------------------

--
-- Table structure for table `table_details`
--

DROP TABLE IF EXISTS `table_details`;
CREATE TABLE IF NOT EXISTS `table_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `time_enter` time NOT NULL,
  `total_people` int(11) NOT NULL,
  `delete_at` int(11) NOT NULL DEFAULT 0,
  `created_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `table_id` (`table_id`),
  KEY `customer_id` (`customer_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_setting`
--

DROP TABLE IF EXISTS `table_setting`;
CREATE TABLE IF NOT EXISTS `table_setting` (
  `settingid` int(11) NOT NULL AUTO_INCREMENT,
  `tableid` int(11) NOT NULL,
  `iconpos` text NOT NULL,
  PRIMARY KEY (`settingid`),
  KEY `tableid` (`tableid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `table_setting`
--

INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(1, 2, 'position: absolute; left: 146.601px; top: 0.0855865px; width: 112px; height: 104px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(2, 4, 'position: absolute; left: 87px; top: 17px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(3, 3, 'position: absolute; left: -13px; top: -12px; width: 96px; height: 72px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(4, 1, 'position: absolute; left: -15px; top: 10px; width: 119px; height: 123px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(5, 8, 'position: absolute; left: 150px; top: -12px; width: 171px; height: 175px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(6, 6, 'position: absolute; left: 603.304px; top: 95.2415px; width: 114px; height: 96px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(7, 5, 'position: absolute; left: -153px; top: 85px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(8, 7, 'position: absolute; left: 459px; top: -38.5625px; height: 63px; width: 88px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(9, 9, 'position: absolute; left: 367px; top: 87px; height: 115px; width: 116px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(10, 10, 'position: absolute; left: -15px; top: 5px; width: 151px; height: 313px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(11, 11, 'position: absolute; left: 167px; top: 7px; width: 226px; height: 130px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(15, 12, 'position: absolute; left: 145px; top: 412.438px; width: 241px; height: 93px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(16, 13, 'position: absolute; left: -15px; top: 386.068px; height: 72px; width: 68px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(17, 14, 'position: absolute; left: 257px; top: 276px; width: 102px; height: 111px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(18, 15, 'position: absolute; left: 151.526px; top: 167.526px; height: 87px; width: 85px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(19, 16, 'position: absolute; width: 93px; height: 82px; left: -15px; top: 100px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(20, 17, 'position: absolute; left: -13px; top: 222px; width: 81px; height: 87px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(21, 18, 'position: absolute; left: 482px; top: 260.438px; width: 152px; height: 93px;');
INSERT INTO `table_setting` (`settingid`, `tableid`, `iconpos`) VALUES(22, 19, 'position: absolute; left: 476px; top: 412.438px; width: 147px; height: 82px;');

-- --------------------------------------------------------

--
-- Table structure for table `tax_collection`
--

DROP TABLE IF EXISTS `tax_collection`;
CREATE TABLE IF NOT EXISTS `tax_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `customer_id` varchar(30) NOT NULL,
  `relation_id` varchar(30) NOT NULL,
  `tax0` text DEFAULT NULL,
  `tax1` text DEFAULT NULL,
  `tax2` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tax_settings`
--

DROP TABLE IF EXISTS `tax_settings`;
CREATE TABLE IF NOT EXISTS `tax_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `default_value` float NOT NULL,
  `tax_name` varchar(250) NOT NULL,
  `nt` int(11) NOT NULL,
  `reg_no` varchar(100) NOT NULL,
  `is_show` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tax_settings`
--

INSERT INTO `tax_settings` (`id`, `default_value`, `tax_name`, `nt`, `reg_no`, `is_show`) VALUES(25, 15, 'Standard Rated VAT', 3, '', 1);
INSERT INTO `tax_settings` (`id`, `default_value`, `tax_name`, `nt`, `reg_no`, `is_show`) VALUES(26, 0, 'Zero Rated VAT', 3, '', 1);
INSERT INTO `tax_settings` (`id`, `default_value`, `tax_name`, `nt`, `reg_no`, `is_show`) VALUES(27, 0, 'Exempt Rated', 3, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblreservation`
--

DROP TABLE IF EXISTS `tblreservation`;
CREATE TABLE IF NOT EXISTS `tblreservation` (
  `reserveid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `tableid` int(11) NOT NULL,
  `person_capicity` int(11) NOT NULL,
  `formtime` time NOT NULL,
  `totime` time NOT NULL,
  `reserveday` date NOT NULL,
  `customer_notes` text DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1=free,2=booked',
  `foods_prereference` text DEFAULT NULL,
  `notif` int(11) NOT NULL DEFAULT 0 COMMENT '0=unseen,1=seen',
  PRIMARY KEY (`reserveid`),
  KEY `cid` (`cid`),
  KEY `tableid` (`tableid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblserver`
--

DROP TABLE IF EXISTS `tblserver`;
CREATE TABLE IF NOT EXISTS `tblserver` (
  `serverid` int(11) NOT NULL AUTO_INCREMENT,
  `localhost_url` varchar(255) NOT NULL,
  `online_url` varchar(255) NOT NULL,
  PRIMARY KEY (`serverid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblserver`
--

INSERT INTO `tblserver` (`serverid`, `localhost_url`, `online_url`) VALUES(1, 'http://127.0.0.1/bhojonsaas_1/', 'http://127.0.0.1/bhojonsaas_1/');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_accnature`
--

DROP TABLE IF EXISTS `tbl_accnature`;
CREATE TABLE IF NOT EXISTS `tbl_accnature` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `typeid` int(11) NOT NULL,
  `balanceid` int(11) NOT NULL,
  `destinationid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `typeid` (`typeid`),
  KEY `balanceid` (`balanceid`),
  KEY `destinationid` (`destinationid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_accnature`
--

INSERT INTO `tbl_accnature` (`id`, `code`, `name`, `typeid`, `balanceid`, `destinationid`) VALUES(1, '10101', 'Assets', 1, 1, 2);
INSERT INTO `tbl_accnature` (`id`, `code`, `name`, `typeid`, `balanceid`, `destinationid`) VALUES(2, '10102', 'Liabilities', 2, 2, 2);
INSERT INTO `tbl_accnature` (`id`, `code`, `name`, `typeid`, `balanceid`, `destinationid`) VALUES(3, '10103', 'Income', 3, 2, 1);
INSERT INTO `tbl_accnature` (`id`, `code`, `name`, `typeid`, `balanceid`, `destinationid`) VALUES(4, '10104', 'Expense', 4, 1, 1);
INSERT INTO `tbl_accnature` (`id`, `code`, `name`, `typeid`, `balanceid`, `destinationid`) VALUES(5, '10105', 'Equity', 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_acctype`
--

DROP TABLE IF EXISTS `tbl_acctype`;
CREATE TABLE IF NOT EXISTS `tbl_acctype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_acctype`
--

INSERT INTO `tbl_acctype` (`id`, `name`) VALUES(1, 'Assets');
INSERT INTO `tbl_acctype` (`id`, `name`) VALUES(2, 'Liability');
INSERT INTO `tbl_acctype` (`id`, `name`) VALUES(3, 'Income');
INSERT INTO `tbl_acctype` (`id`, `name`) VALUES(4, 'Expense');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apptokenupdate`
--

DROP TABLE IF EXISTS `tbl_apptokenupdate`;
CREATE TABLE IF NOT EXISTS `tbl_apptokenupdate` (
  `updateid` int(11) NOT NULL AUTO_INCREMENT,
  `ordid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `itemnotes` text DEFAULT NULL,
  `qty` decimal(10,3) NOT NULL,
  `adonsqty` varchar(50) DEFAULT NULL,
  `addonsid` varchar(50) DEFAULT NULL,
  `addonsuid` varchar(50) DEFAULT NULL,
  `varientid` int(11) NOT NULL,
  `previousqty` int(11) NOT NULL,
  `isdel` varchar(30) DEFAULT NULL,
  `printer_token_id` varchar(100) DEFAULT NULL,
  `printer_status_log` text DEFAULT NULL,
  `isprint` int(11) DEFAULT 0 COMMENT '1=print,0=notprint',
  `add_qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `del_qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `delstaus` int(11) DEFAULT 0,
  `foodstatus` int(11) DEFAULT 0,
  `addedtime` datetime DEFAULT NULL,
  PRIMARY KEY (`updateid`),
  KEY `ordid` (`ordid`),
  KEY `menuid` (`menuid`),
  KEY `varientid` (`varientid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_assign_kitchen`
--

DROP TABLE IF EXISTS `tbl_assign_kitchen`;
CREATE TABLE IF NOT EXISTS `tbl_assign_kitchen` (
  `assignid` int(11) NOT NULL AUTO_INCREMENT,
  `kitchen_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`assignid`),
  KEY `kitchen_id` (`kitchen_id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_balancetype`
--

DROP TABLE IF EXISTS `tbl_balancetype`;
CREATE TABLE IF NOT EXISTS `tbl_balancetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_balancetype`
--

INSERT INTO `tbl_balancetype` (`id`, `name`) VALUES(1, 'Debit Balance');
INSERT INTO `tbl_balancetype` (`id`, `name`) VALUES(2, 'Credit Balance');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank`
--

DROP TABLE IF EXISTS `tbl_bank`;
CREATE TABLE IF NOT EXISTS `tbl_bank` (
  `bankid` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(255) NOT NULL,
  `ac_name` varchar(200) DEFAULT NULL,
  `ac_number` varchar(200) DEFAULT NULL,
  `branch` varchar(200) DEFAULT NULL,
  `signature_pic` varchar(255) DEFAULT NULL,
  `setdefault` int(11) DEFAULT 0,
  PRIMARY KEY (`bankid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_bank`
--

INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(1, 'Dutch-Bangla Bank', 'Ainal Haque', '110535764655', 'Mirpur 10', './application/modules/hrm/assets/images/2020-01-18/c.jpg', 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(2, 'City Bank', 'Kamal Hassan', '3869583', 'Uttara', './application/modules/hrm/assets/images/2020-01-18/e.jpg', 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(3, 'Brac Bank', 'Robiul Islam', '9356346', 'Motijeel', './application/modules/hrm/assets/images/2020-01-18/f.jpg', 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(4, 'VISA', '298749823648', '57832334', 'Online', NULL, 1);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(5, 'Master', '236452354', '072234254', 'Online', NULL, 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(6, 'Discover', '5478700325423', '723001', 'Online', NULL, 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(7, 'Amex', '801243562', '90242354', 'Online', NULL, 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(8, 'JCB', '112400123', '1104528', 'Online', NULL, 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(9, 'China Union Pay', 'online', '0147812356', 'online', NULL, 0);
INSERT INTO `tbl_bank` (`bankid`, `bank_name`, `ac_name`, `ac_number`, `branch`, `signature_pic`, `setdefault`) VALUES(10, 'Kamal', 'Jamal', '456457586', 'Dhaka', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bankchequestatus`
--

DROP TABLE IF EXISTS `tbl_bankchequestatus`;
CREATE TABLE IF NOT EXISTS `tbl_bankchequestatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vno` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `insertdate` datetime DEFAULT NULL,
  `createby` int(11) DEFAULT NULL,
  `IsActive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_billingaddress`
--

DROP TABLE IF EXISTS `tbl_billingaddress`;
CREATE TABLE IF NOT EXISTS `tbl_billingaddress` (
  `billaddressid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `companyname` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `city` varchar(70) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `zip` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `DateInserted` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`billaddressid`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cancelitem`
--

DROP TABLE IF EXISTS `tbl_cancelitem`;
CREATE TABLE IF NOT EXISTS `tbl_cancelitem` (
  `cancelid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `foodid` int(11) NOT NULL,
  `varientid` int(11) NOT NULL,
  `quantity` decimal(19,3) NOT NULL DEFAULT 0.000,
  `itemprice` decimal(19,3) DEFAULT NULL,
  `itencancelreason` text DEFAULT NULL,
  `cancel_by` int(11) DEFAULT 0,
  `canceldate` date DEFAULT '1790-01-01',
  PRIMARY KEY (`cancelid`),
  KEY `orderid` (`orderid`),
  KEY `foodid` (`foodid`),
  KEY `varientid` (`varientid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_card_terminal`
--

DROP TABLE IF EXISTS `tbl_card_terminal`;
CREATE TABLE IF NOT EXISTS `tbl_card_terminal` (
  `card_terminalid` int(11) NOT NULL AUTO_INCREMENT,
  `terminal_name` varchar(255) NOT NULL,
  `comissionrate` int(11) DEFAULT 0,
  PRIMARY KEY (`card_terminalid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_card_terminal`
--

INSERT INTO `tbl_card_terminal` (`card_terminalid`, `terminal_name`, `comissionrate`) VALUES(1, 'Nexus Terminal', 5);
INSERT INTO `tbl_card_terminal` (`card_terminalid`, `terminal_name`, `comissionrate`) VALUES(2, 'Brac Bank Terminal', 2);
INSERT INTO `tbl_card_terminal` (`card_terminalid`, `terminal_name`, `comissionrate`) VALUES(3, 'Visa-Master Terminal', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cashcounter`
--

DROP TABLE IF EXISTS `tbl_cashcounter`;
CREATE TABLE IF NOT EXISTS `tbl_cashcounter` (
  `ccid` int(11) NOT NULL AUTO_INCREMENT,
  `counterno` int(11) NOT NULL,
  PRIMARY KEY (`ccid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_cashcounter`
--

INSERT INTO `tbl_cashcounter` (`ccid`, `counterno`) VALUES(1, 1);
INSERT INTO `tbl_cashcounter` (`ccid`, `counterno`) VALUES(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cashregister`
--

DROP TABLE IF EXISTS `tbl_cashregister`;
CREATE TABLE IF NOT EXISTS `tbl_cashregister` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `counter_no` int(11) NOT NULL,
  `opening_balance` decimal(19,3) NOT NULL DEFAULT 0.000,
  `closing_balance` decimal(19,3) NOT NULL DEFAULT 0.000,
  `openclosedate` date NOT NULL,
  `opendate` datetime DEFAULT '1970-01-01 01:01:01',
  `closedate` datetime DEFAULT '1970-01-01 01:01:01',
  `status` int(11) NOT NULL DEFAULT 0,
  `openingnote` text DEFAULT NULL,
  `closing_note` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `counter_no` (`counter_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cashregister_details`
--

DROP TABLE IF EXISTS `tbl_cashregister_details`;
CREATE TABLE IF NOT EXISTS `tbl_cashregister_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cashregister_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city`
--

DROP TABLE IF EXISTS `tbl_city`;
CREATE TABLE IF NOT EXISTS `tbl_city` (
  `cityid` int(11) NOT NULL AUTO_INCREMENT,
  `countryid` int(11) NOT NULL,
  `stateid` int(11) NOT NULL,
  `cityname` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cityid`),
  KEY `countryid` (`countryid`),
  KEY `stateid` (`stateid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_city`
--

INSERT INTO `tbl_city` (`cityid`, `countryid`, `stateid`, `cityname`, `status`) VALUES(3, 1, 12, 'Savar', 1);
INSERT INTO `tbl_city` (`cityid`, `countryid`, `stateid`, `cityname`, `status`) VALUES(4, 1, 12, 'Gajipur', 1);
INSERT INTO `tbl_city` (`cityid`, `countryid`, `stateid`, `cityname`, `status`) VALUES(5, 1, 12, 'Mirpur', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_commisionpay`
--

DROP TABLE IF EXISTS `tbl_commisionpay`;
CREATE TABLE IF NOT EXISTS `tbl_commisionpay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thirdpartyid` int(11) NOT NULL,
  `paymethod` int(11) NOT NULL,
  `payamount` decimal(19,3) NOT NULL DEFAULT 0.000,
  `paydate` datetime NOT NULL DEFAULT '1790-01-01 01:01:01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_commisionpay`
--

INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(1, 4, 1, 5.000, '2023-05-31 00:00:00');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(2, 4, 1, 5.000, '2023-05-31 12:13:21');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(3, 4, 3, 5.000, '2023-05-31 12:16:13');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(4, 4, 3, 5.000, '2023-05-31 12:16:14');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(5, 4, 1, 1.000, '2023-05-31 12:17:05');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(6, 2, 1, 100.000, '2024-05-26 14:32:00');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(7, 2, 1, 100.000, '2024-05-26 14:32:00');
INSERT INTO `tbl_commisionpay` (`id`, `thirdpartyid`, `paymethod`, `payamount`, `paydate`) VALUES(8, 2, 1, 5.000, '2024-05-26 14:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country`
--

DROP TABLE IF EXISTS `tbl_country`;
CREATE TABLE IF NOT EXISTS `tbl_country` (
  `countryid` int(11) NOT NULL AUTO_INCREMENT,
  `countryname` varchar(70) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`countryid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_country`
--

INSERT INTO `tbl_country` (`countryid`, `countryname`, `status`) VALUES(1, 'Bangladesh', 1);
INSERT INTO `tbl_country` (`countryid`, `countryname`, `status`) VALUES(2, 'United State', 1);
INSERT INTO `tbl_country` (`countryid`, `countryname`, `status`) VALUES(3, 'United Kingdom', 1);
INSERT INTO `tbl_country` (`countryid`, `countryname`, `status`) VALUES(4, 'India', 1);
INSERT INTO `tbl_country` (`countryid`, `countryname`, `status`) VALUES(5, 'Vietnam', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_delivaritime`
--

DROP TABLE IF EXISTS `tbl_delivaritime`;
CREATE TABLE IF NOT EXISTS `tbl_delivaritime` (
  `dtimeid` int(11) NOT NULL AUTO_INCREMENT,
  `deltime` varchar(255) NOT NULL,
  PRIMARY KEY (`dtimeid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_delivaritime`
--

INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(1, '10:00-10:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(2, '10:30-11:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(3, '11:00-11:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(4, '11:30-12:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(5, '12:00-12:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(6, '12:30-13:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(7, '13:00-13:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(8, '13:30-14:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(9, '14:00-14:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(10, '14:30-15:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(11, '15:00-15:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(12, '15:30-16:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(13, '16:00-16:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(14, '16:30-17:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(15, '17:00-17:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(16, '17:30-18:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(17, '18:00-18:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(18, '18:30-19:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(19, '19:00-19:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(20, '19:30-20:00');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(21, '20:00-20:30');
INSERT INTO `tbl_delivaritime` (`dtimeid`, `deltime`) VALUES(22, '20:30-21:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_delivaryaddress`
--

DROP TABLE IF EXISTS `tbl_delivaryaddress`;
CREATE TABLE IF NOT EXISTS `tbl_delivaryaddress` (
  `delivaryid` int(11) NOT NULL AUTO_INCREMENT,
  `zone_id` int(11) DEFAULT NULL,
  `deladdress` text NOT NULL,
  PRIMARY KEY (`delivaryid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_delivaryaddress`
--

INSERT INTO `tbl_delivaryaddress` (`delivaryid`, `zone_id`, `deladdress`) VALUES(1, 1, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_destination`
--

DROP TABLE IF EXISTS `tbl_destination`;
CREATE TABLE IF NOT EXISTS `tbl_destination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_destination`
--

INSERT INTO `tbl_destination` (`id`, `name`) VALUES(1, 'Incomeststement');
INSERT INTO `tbl_destination` (`id`, `name`) VALUES(2, 'Balancesheet');
INSERT INTO `tbl_destination` (`id`, `name`) VALUES(3, 'Trading');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_expire_or_damagefoodentry`
--

DROP TABLE IF EXISTS `tbl_expire_or_damagefoodentry`;
CREATE TABLE IF NOT EXISTS `tbl_expire_or_damagefoodentry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `vid` int(11) DEFAULT 0,
  `expire_qty` decimal(19,2) NOT NULL DEFAULT 0.00,
  `damage_qty` decimal(19,2) NOT NULL DEFAULT 0.00,
  `expireordamage` date NOT NULL DEFAULT '1970-01-01',
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `dtype` int(11) NOT NULL COMMENT '1=ready food,2=Raw',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_financialyear`
--

DROP TABLE IF EXISTS `tbl_financialyear`;
CREATE TABLE IF NOT EXISTS `tbl_financialyear` (
  `fiyear_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `is_active` varchar(3) DEFAULT NULL COMMENT '1=ended,0=inactive,2=active',
  `create_by` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`fiyear_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_financialyear`
--

INSERT INTO `tbl_financialyear` (`fiyear_id`, `title`, `start_date`, `end_date`, `date_time`, `is_active`, `create_by`) VALUES(28, '2024-2024', '2024-01-01', '2024-03-10', '2024-03-11 15:22:24', '1', '2');
INSERT INTO `tbl_financialyear` (`fiyear_id`, `title`, `start_date`, `end_date`, `date_time`, `is_active`, `create_by`) VALUES(29, '2024-2024', '2024-03-11', '2024-12-31', '2024-03-11 15:22:42', '2', '2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_generatedreport`
--

DROP TABLE IF EXISTS `tbl_generatedreport`;
CREATE TABLE IF NOT EXISTS `tbl_generatedreport` (
  `generateid` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `saleinvoice` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `cutomertype` int(11) NOT NULL,
  `isthirdparty` int(11) NOT NULL DEFAULT 0,
  `waiter_id` int(11) DEFAULT NULL,
  `kitchen` int(11) DEFAULT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `table_no` int(11) DEFAULT NULL,
  `tokenno` varchar(30) DEFAULT NULL,
  `totalamount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customerpaid` decimal(10,2) DEFAULT 0.00,
  `customer_note` text DEFAULT NULL,
  `anyreason` text DEFAULT NULL,
  `order_status` tinyint(4) NOT NULL,
  `nofification` int(11) NOT NULL,
  `orderacceptreject` int(11) DEFAULT NULL,
  `reportDate` date NOT NULL,
  PRIMARY KEY (`generateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groupacc`
--

DROP TABLE IF EXISTS `tbl_groupacc`;
CREATE TABLE IF NOT EXISTS `tbl_groupacc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `accNatureid` int(11) NOT NULL,
  `acctypeid` int(11) NOT NULL,
  `balanceid` int(11) NOT NULL,
  `destinationid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `accNatureid` (`accNatureid`),
  KEY `acctypeid` (`acctypeid`),
  KEY `balanceid` (`balanceid`),
  KEY `destinationid` (`destinationid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_groupacc`
--

INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(1, '1010102', 'Non Current Assets', 1, 1, 1, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(2, '1010103', 'Inventory', 1, 1, 1, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(3, '1010101', 'Current Asset', 1, 1, 1, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(4, '1010503', 'Share Holders Equity', 5, 2, 2, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(5, '1010502', 'Reserve & Surplus', 5, 2, 2, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(11, '1010301', 'Other Income', 3, 3, 2, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(12, '1010302', 'Sale Income', 3, 3, 2, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(13, '1010401', 'Overhead Expenses', 4, 4, 1, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(14, '1010402', 'Administrative Expenses', 4, 4, 1, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(15, '1010403', 'Cost of Good Sold', 4, 4, 1, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(19, '1010404', 'Product Purchase', 4, 4, 1, 1);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(20, '1010202', 'Non Current Liabilities', 2, 2, 2, 2);
INSERT INTO `tbl_groupacc` (`id`, `code`, `name`, `accNatureid`, `acctypeid`, `balanceid`, `destinationid`) VALUES(21, '1010201', 'Current Liabilities', 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groupaccsub`
--

DROP TABLE IF EXISTS `tbl_groupaccsub`;
CREATE TABLE IF NOT EXISTS `tbl_groupaccsub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `GroupID` int(11) NOT NULL,
  `AccNatureID` int(11) NOT NULL,
  `acctypeid` int(11) NOT NULL,
  `blanceID` int(11) NOT NULL,
  `destinationid` int(11) NOT NULL,
  `code` varchar(25) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `IsActive` int(11) NOT NULL DEFAULT 0,
  `noteNo` varchar(30) NOT NULL,
  `isstock` bit(1) NOT NULL DEFAULT b'0',
  `isfixedAssetsSch` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `acctypeid` (`acctypeid`),
  KEY `blanceID` (`blanceID`),
  KEY `destinationid` (`destinationid`),
  KEY `AccNatureID` (`AccNatureID`),
  KEY `GroupID` (`GroupID`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_groupaccsub`
--

INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(1, 3, 1, 1, 1, 2, '101010101', 'Cash & Cash Equivalent', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(2, 3, 1, 1, 1, 2, '101010102', 'Advance, Deposit And Pre-payments', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(3, 3, 1, 1, 1, 2, '101010103', 'Account Receivable', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(4, 1, 1, 1, 1, 2, '101010204', 'Furniture & Fixturers', 1, 'Tr', b'0', b'1');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(5, 1, 1, 1, 1, 2, '101010205', 'Office Equipment', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(6, 1, 1, 1, 1, 2, '101010206', 'Groceries and Cutleries', 1, '', b'0', b'1');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(7, 1, 1, 1, 1, 2, '101010207', 'Generator', 1, '', b'0', b'1');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(8, 1, 1, 1, 1, 2, '101010208', 'Electrical Equipment', 1, '', b'0', b'1');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(9, 4, 5, 2, 2, 2, '101050101', 'Share Capital', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(10, 4, 5, 2, 2, 2, '101050302', 'Current year Profit & Loss', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(11, 5, 5, 2, 2, 2, '101050201', 'General Reserve', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(12, 12, 3, 3, 2, 1, '101030202', 'Sale Income', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(13, 2, 1, 1, 1, 2, '101010305', 'Inventory', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(14, 11, 3, 3, 2, 1, '101030101', 'Other Income', 1, 'ot', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(15, 13, 4, 4, 1, 1, '101040127', 'Overhead Expense', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(16, 14, 4, 4, 1, 1, '101040204', 'Salary', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(17, 14, 4, 4, 1, 1, '101040214', 'Travelling & Conveyance', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(18, 14, 4, 4, 1, 1, '101040221', 'Entertainment', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(19, 14, 4, 4, 1, 1, '101040228', 'Office Accessories', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(20, 13, 4, 4, 1, 1, '101040131', 'Utility Expenses', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(21, 13, 4, 4, 1, 1, '101040121', 'House Rent', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(22, 13, 4, 4, 1, 1, '101040101', 'Repair and Maintenance', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(23, 14, 4, 4, 1, 1, '101040220', 'Dividend', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(24, 14, 4, 4, 1, 1, '101040227', 'Audit Fee', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(25, 14, 4, 4, 1, 1, '101040202', 'Car Running Expenses', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(26, 14, 4, 4, 1, 1, '101040209', 'Others (Non Academic Expenses)', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(27, 14, 4, 4, 1, 1, '101040224', 'Financial Expenses', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(28, 14, 4, 4, 1, 1, '101040205', 'Promonational Expense', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(29, 14, 4, 4, 1, 1, '101040203', 'Miscellaneous Expenses', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(30, 14, 4, 4, 1, 1, '101040232', 'Allowances', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(31, 20, 2, 2, 2, 2, '101020202', 'Long Term Borrowing', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(32, 21, 2, 2, 2, 2, '101020103', 'Short Term Borrowing', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(33, 21, 2, 2, 2, 2, '101020104', 'Account Payable', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(34, 21, 2, 2, 2, 2, '101020101', 'Liabilities for Expenses', 1, 'lb', b'0', b'1');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(35, 15, 4, 4, 1, 1, '101040301', 'Discount', 1, 'ds', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(36, 15, 4, 4, 1, 1, '101040302', 'Vat', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(37, 19, 4, 4, 1, 1, '101040401', 'Product Purchase', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(38, 14, 4, 4, 1, 1, NULL, 'House Rent', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(39, 14, 4, 4, 1, 1, '101040201', 'Employee Salary Other Expences', 1, '', b'0', b'0');
INSERT INTO `tbl_groupaccsub` (`id`, `GroupID`, `AccNatureID`, `acctypeid`, `blanceID`, `destinationid`, `code`, `name`, `IsActive`, `noteNo`, `isstock`, `isfixedAssetsSch`) VALUES(40, 4, 5, 2, 2, 2, '101050301', 'Last year Profit & Loss', 1, '', b'0', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_groupitems`
--

DROP TABLE IF EXISTS `tbl_groupitems`;
CREATE TABLE IF NOT EXISTS `tbl_groupitems` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `gitemid` int(11) NOT NULL,
  `items` int(11) NOT NULL,
  `item_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `varientid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`groupid`),
  KEY `gitemid` (`gitemid`),
  KEY `items` (`items`),
  KEY `varientid` (`varientid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_groupitems`
--

INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(1, 17, 1, 1.00, 1, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(2, 17, 12, 1.00, 16, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(3, 17, 13, 1.00, 17, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(4, 17, 9, 1.00, 13, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(5, 18, 2, 2.00, 2, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(6, 18, 8, 1.00, 11, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(7, 39, 1, 3.00, 1, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(8, 39, 10, 2.00, 14, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(9, 46, 4, 1.00, 7, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(10, 46, 5, 1.00, 8, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(11, 46, 3, 1.00, 5, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(12, 47, 5, 1.00, 8, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(13, 47, 4, 1.00, 7, 1);
INSERT INTO `tbl_groupitems` (`groupid`, `gitemid`, `items`, `item_qty`, `varientid`, `status`) VALUES(14, 47, 3, 1.00, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoicesetting`
--

DROP TABLE IF EXISTS `tbl_invoicesetting`;
CREATE TABLE IF NOT EXISTS `tbl_invoicesetting` (
  `invstid` int(11) NOT NULL AUTO_INCREMENT,
  `invlayout` int(11) NOT NULL DEFAULT 1,
  `invlogo` int(11) NOT NULL DEFAULT 1,
  `invtitle` int(11) NOT NULL DEFAULT 1,
  `invaddress` int(11) NOT NULL DEFAULT 1,
  `invtin` int(11) NOT NULL DEFAULT 1,
  `invtable` int(11) NOT NULL DEFAULT 1,
  `invorder` int(11) NOT NULL DEFAULT 1,
  `invthank` int(11) NOT NULL DEFAULT 1,
  `invpower` int(11) NOT NULL DEFAULT 1,
  `invvat` int(11) NOT NULL DEFAULT 1,
  `invservice` int(11) NOT NULL DEFAULT 1,
  `invdiscount` int(11) NOT NULL DEFAULT 1,
  `invchangedue` int(11) NOT NULL DEFAULT 1,
  `invbillto` int(11) NOT NULL DEFAULT 1,
  `invbillby` int(11) NOT NULL DEFAULT 1,
  `isvatinclusive` int(11) NOT NULL DEFAULT 0,
  `qroninvoice` int(11) NOT NULL DEFAULT 0,
  `isitemsummery` int(11) NOT NULL DEFAULT 0,
  `mobile` int(11) NOT NULL DEFAULT 1,
  `website` int(11) NOT NULL DEFAULT 1,
  `mushok` int(11) NOT NULL DEFAULT 1,
  `token_history` int(11) NOT NULL DEFAULT 1,
  `recalculate_vat` tinyint(4) DEFAULT 0 COMMENT '0 => not recalculated, 1 => recalculated',
  `sumnienable` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`invstid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_invoicesetting`
--

INSERT INTO `tbl_invoicesetting` (`invstid`, `invlayout`, `invlogo`, `invtitle`, `invaddress`, `invtin`, `invtable`, `invorder`, `invthank`, `invpower`, `invvat`, `invservice`, `invdiscount`, `invchangedue`, `invbillto`, `invbillby`, `isvatinclusive`, `qroninvoice`, `isitemsummery`, `mobile`, `website`, `mushok`, `token_history`, `recalculate_vat`, `sumnienable`) VALUES(1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_template`
--

DROP TABLE IF EXISTS `tbl_invoice_template`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_name` text DEFAULT NULL,
  `design_type` tinyint(1) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `invoice_logo_show` tinyint(1) DEFAULT NULL,
  `store_name` varchar(100) DEFAULT NULL,
  `mushak` tinyint(4) DEFAULT NULL,
  `mushaktext` varchar(255) DEFAULT NULL,
  `website` tinyint(4) DEFAULT NULL,
  `websitetext` text DEFAULT NULL,
  `bin_pos_show` tinyint(4) DEFAULT NULL,
  `invoice_level` varchar(100) DEFAULT NULL,
  `invoice_level_show` tinyint(4) DEFAULT NULL,
  `company_name_show` tinyint(4) DEFAULT NULL,
  `company_address` tinyint(4) DEFAULT NULL,
  `mobile_num` tinyint(4) DEFAULT NULL,
  `email` tinyint(4) DEFAULT NULL,
  `customer_address` int(11) DEFAULT NULL,
  `customer_email` tinyint(4) DEFAULT NULL,
  `customer_mobile` tinyint(4) DEFAULT NULL,
  `date_level` varchar(50) DEFAULT NULL,
  `date_show` tinyint(4) DEFAULT NULL,
  `date_time_formate` text DEFAULT NULL,
  `time_show` tinyint(4) DEFAULT NULL,
  `show_tex` tinyint(4) DEFAULT NULL,
  `subtotal_level` varchar(50) DEFAULT NULL,
  `subtotal_level_show` tinyint(4) DEFAULT NULL,
  `service_charge` varchar(50) DEFAULT NULL,
  `servicechargeshow` tinyint(4) DEFAULT NULL,
  `vatshow` tinyint(4) DEFAULT NULL,
  `vat_level` varchar(50) DEFAULT NULL,
  `discount_level` varchar(50) DEFAULT NULL,
  `discountshow` tinyint(4) DEFAULT NULL,
  `grand_total` varchar(50) DEFAULT NULL,
  `grandtotalshow` tinyint(4) DEFAULT NULL,
  `customer_paid_show` tinyint(4) DEFAULT NULL,
  `cutomer_paid_amount` varchar(50) DEFAULT NULL,
  `total_due_show` tinyint(4) DEFAULT NULL,
  `total_due` varchar(50) DEFAULT NULL,
  `change_due_level` varchar(50) DEFAULT NULL,
  `change_due_show` tinyint(4) DEFAULT NULL,
  `total_payment_show` tinyint(4) DEFAULT NULL,
  `total_payment` varchar(50) DEFAULT NULL,
  `billing_to` varchar(50) DEFAULT NULL,
  `billing_to_show` tinyint(4) DEFAULT NULL,
  `bill_by` text DEFAULT NULL,
  `bill_by_show` tinyint(4) DEFAULT NULL,
  `waiter` varchar(225) DEFAULT NULL,
  `waitershow` int(11) DEFAULT 1,
  `bin_level` varchar(50) DEFAULT NULL,
  `table_level` varchar(50) DEFAULT NULL,
  `table_level_show` tinyint(4) DEFAULT NULL,
  `order_no_show` tinyint(4) DEFAULT NULL,
  `order_no` varchar(50) DEFAULT NULL,
  `payment_status_show` tinyint(4) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `footer_text` varchar(100) DEFAULT NULL,
  `footertextshow` tinyint(4) DEFAULT NULL,
  `lineHeight` varchar(50) NOT NULL,
  `fontsize` varchar(100) DEFAULT NULL,
  `custom_fonts` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_invoice_template`
--

INSERT INTO `tbl_invoice_template` (`id`, `layout_name`, `design_type`, `logo`, `invoice_logo_show`, `store_name`, `mushak`, `mushaktext`, `website`, `websitetext`, `bin_pos_show`, `invoice_level`, `invoice_level_show`, `company_name_show`, `company_address`, `mobile_num`, `email`, `customer_address`, `customer_email`, `customer_mobile`, `date_level`, `date_show`, `date_time_formate`, `time_show`, `show_tex`, `subtotal_level`, `subtotal_level_show`, `service_charge`, `servicechargeshow`, `vatshow`, `vat_level`, `discount_level`, `discountshow`, `grand_total`, `grandtotalshow`, `customer_paid_show`, `cutomer_paid_amount`, `total_due_show`, `total_due`, `change_due_level`, `change_due_show`, `total_payment_show`, `total_payment`, `billing_to`, `billing_to_show`, `bill_by`, `bill_by_show`, `waiter`, `waitershow`, `bin_level`, `table_level`, `table_level_show`, `order_no_show`, `order_no`, `payment_status_show`, `payment_status`, `footer_text`, `footertextshow`, `lineHeight`, `fontsize`, `custom_fonts`) VALUES(1, 'Layout Name', 2, 'assets/img/logo/2023-01-30/f2.png', 1, 'Store Name', 1, 'Mushak-6.3', 1, '', 1, 'nvoice Level', NULL, 1, 1, 1, NULL, 1, NULL, 1, 'Date', 1, 'd-m-Y', 1, 1, 'Subtotal', 1, 'Service Charge', 1, 1, 'Vat ', 'Discount ', 1, 'Grand Total', 1, 1, 'Customer Paid Amount', 1, 'Total Due ', 'Change Due ', 1, 1, 'sdfa', 'Billing To ', 1, 'Bill By', 1, '', 1, 'BINasdfasdf', 'Test', 1, 1, 'Order No', 1, 'Payment Status', 'Test Test', 1, '16', NULL, NULL);
INSERT INTO `tbl_invoice_template` (`id`, `layout_name`, `design_type`, `logo`, `invoice_logo_show`, `store_name`, `mushak`, `mushaktext`, `website`, `websitetext`, `bin_pos_show`, `invoice_level`, `invoice_level_show`, `company_name_show`, `company_address`, `mobile_num`, `email`, `customer_address`, `customer_email`, `customer_mobile`, `date_level`, `date_show`, `date_time_formate`, `time_show`, `show_tex`, `subtotal_level`, `subtotal_level_show`, `service_charge`, `servicechargeshow`, `vatshow`, `vat_level`, `discount_level`, `discountshow`, `grand_total`, `grandtotalshow`, `customer_paid_show`, `cutomer_paid_amount`, `total_due_show`, `total_due`, `change_due_level`, `change_due_show`, `total_payment_show`, `total_payment`, `billing_to`, `billing_to_show`, `bill_by`, `bill_by_show`, `waiter`, `waitershow`, `bin_level`, `table_level`, `table_level_show`, `order_no_show`, `order_no`, `payment_status_show`, `payment_status`, `footer_text`, `footertextshow`, `lineHeight`, `fontsize`, `custom_fonts`) VALUES(3, 'Layout Two', 3, 'assets/img/logo/2023-01-30/f1.png', 1, 'Bdtask', 1, '', 1, 'Website:www.restorapos.com', 1, '', NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 'Date', 1, 'd-m-Y', NULL, 1, 'Subtotal Level', 1, 'SD', 1, 1, 'Vat', 'Discount', 1, 'Grand Total', 1, 1, 'Customer Paid', 1, 'Total Due', 'Change Due', 1, 1, 'Payment', 'Bill To', 1, 'Bill By', 1, 'Waiter:Bahar', 1, 'TIN', 'Table', 1, 1, 'Order No.', 1, 'Payment Status', 'dfghdfgh', 1, '1', NULL, NULL);
INSERT INTO `tbl_invoice_template` (`id`, `layout_name`, `design_type`, `logo`, `invoice_logo_show`, `store_name`, `mushak`, `mushaktext`, `website`, `websitetext`, `bin_pos_show`, `invoice_level`, `invoice_level_show`, `company_name_show`, `company_address`, `mobile_num`, `email`, `customer_address`, `customer_email`, `customer_mobile`, `date_level`, `date_show`, `date_time_formate`, `time_show`, `show_tex`, `subtotal_level`, `subtotal_level_show`, `service_charge`, `servicechargeshow`, `vatshow`, `vat_level`, `discount_level`, `discountshow`, `grand_total`, `grandtotalshow`, `customer_paid_show`, `cutomer_paid_amount`, `total_due_show`, `total_due`, `change_due_level`, `change_due_show`, `total_payment_show`, `total_payment`, `billing_to`, `billing_to_show`, `bill_by`, `bill_by_show`, `waiter`, `waitershow`, `bin_level`, `table_level`, `table_level_show`, `order_no_show`, `order_no`, `payment_status_show`, `payment_status`, `footer_text`, `footertextshow`, `lineHeight`, `fontsize`, `custom_fonts`) VALUES(4, 'A4', 1, 'assets/img/logo/2023-01-31/f.png', 1, 'Bdtask', 1, 'Mushak', 1, '', NULL, 'invoice', 1, 1, 1, 1, 1, 1, 1, 1, 'Date', 1, 'd-m-Y', 1, 1, 'Subtotal Level', 1, 'Service Charge', 1, 1, 'Vat Level', 'Discount Level', 1, 'Grand Total Level', 1, 1, 'Customer Paid Amount Level', 1, 'Total Due Level', 'Change Due Level', 1, NULL, '', 'Billing To Level', 1, 'Billing From', 1, '', 1, '', '', NULL, 1, 'Order No Level', 1, 'Payment Status Level', '', NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_itemaccepted`
--

DROP TABLE IF EXISTS `tbl_itemaccepted`;
CREATE TABLE IF NOT EXISTS `tbl_itemaccepted` (
  `acid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `varient` int(11) NOT NULL,
  `accepttime` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`acid`),
  KEY `orderid` (`orderid`),
  KEY `menuid` (`menuid`),
  KEY `varient` (`varient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_itemwiseuser`
--

DROP TABLE IF EXISTS `tbl_itemwiseuser`;
CREATE TABLE IF NOT EXISTS `tbl_itemwiseuser` (
  `accessid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `isacccess` int(11) NOT NULL DEFAULT 0,
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`accessid`),
  KEY `userid` (`userid`),
  KEY `catid` (`catid`),
  KEY `menuid` (`menuid`),
  KEY `createby` (`createby`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kitchen`
--

DROP TABLE IF EXISTS `tbl_kitchen`;
CREATE TABLE IF NOT EXISTS `tbl_kitchen` (
  `kitchenid` int(11) NOT NULL AUTO_INCREMENT,
  `kitchencode` varchar(50) DEFAULT NULL,
  `kitchen_name` varchar(100) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `port` varchar(10) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `is_deleted` int(11) DEFAULT 0,
  PRIMARY KEY (`kitchenid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_kitchen`
--

INSERT INTO `tbl_kitchen` (`kitchenid`, `kitchencode`, `kitchen_name`, `ip`, `port`, `status`, `is_deleted`) VALUES(1, 'KIT0032', 'Kitchen One - Primary', NULL, NULL, 1, 0);
INSERT INTO `tbl_kitchen` (`kitchenid`, `kitchencode`, `kitchen_name`, `ip`, `port`, `status`, `is_deleted`) VALUES(2, 'KIT0033', 'Primary KItchen', NULL, NULL, 1, 0);
INSERT INTO `tbl_kitchen` (`kitchenid`, `kitchencode`, `kitchen_name`, `ip`, `port`, `status`, `is_deleted`) VALUES(3, 'KIT0034', 'Kitchen One', NULL, NULL, 1, 0);
INSERT INTO `tbl_kitchen` (`kitchenid`, `kitchencode`, `kitchen_name`, `ip`, `port`, `status`, `is_deleted`) VALUES(4, 'KIT0035', 'Heidi Duncan', NULL, NULL, 1, 0);
INSERT INTO `tbl_kitchen` (`kitchenid`, `kitchencode`, `kitchen_name`, `ip`, `port`, `status`, `is_deleted`) VALUES(6, 'KIT0037', 'Kitchen 13', NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kitchen_log`
--

DROP TABLE IF EXISTS `tbl_kitchen_log`;
CREATE TABLE IF NOT EXISTS `tbl_kitchen_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `orderinfo` text NOT NULL,
  `logtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kitchen_order`
--

DROP TABLE IF EXISTS `tbl_kitchen_order`;
CREATE TABLE IF NOT EXISTS `tbl_kitchen_order` (
  `ktid` int(11) NOT NULL AUTO_INCREMENT,
  `kitchenid` int(11) NOT NULL,
  `orderid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `varient` int(11) DEFAULT NULL,
  `addonsuid` int(11) DEFAULT NULL,
  `isaccepted` int(11) DEFAULT 0,
  `acceptedtime` datetime DEFAULT NULL,
  PRIMARY KEY (`ktid`),
  KEY `kitchenid` (`kitchenid`),
  KEY `orderid` (`orderid`),
  KEY `itemid` (`itemid`),
  KEY `varient` (`varient`),
  KEY `addonsuid` (`addonsuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ledger`
--

DROP TABLE IF EXISTS `tbl_ledger`;
CREATE TABLE IF NOT EXISTS `tbl_ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) DEFAULT NULL,
  `Name` varchar(50) NOT NULL,
  `NatureID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `Groupsubid` int(11) NOT NULL,
  `acctypeid` int(11) NOT NULL,
  `blanceID` int(11) NOT NULL,
  `destinationid` int(11) NOT NULL,
  `subType` int(11) NOT NULL DEFAULT 1,
  `IsActive` bit(1) NOT NULL DEFAULT b'0',
  `isstock` tinyint(1) NOT NULL DEFAULT 0,
  `isfixedassetsch` tinyint(1) NOT NULL DEFAULT 0,
  `noteNo` varchar(10) DEFAULT NULL,
  `AssetsCode` varchar(20) DEFAULT NULL,
  `depCode` varchar(20) DEFAULT NULL,
  `IsBudget` bit(1) NOT NULL DEFAULT b'0',
  `IsDepreciation` bit(1) NOT NULL DEFAULT b'0',
  `DepreciationRate` decimal(18,2) NOT NULL DEFAULT 0.00,
  `iscashnature` int(11) NOT NULL DEFAULT 0,
  `isbanknature` int(11) NOT NULL DEFAULT 0,
  `CreateBy` varchar(50) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  `UpdateBy` varchar(50) NOT NULL,
  `UpdateDate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`id`),
  KEY `NatureID` (`NatureID`),
  KEY `GroupID` (`GroupID`),
  KEY `Groupsubid` (`Groupsubid`),
  KEY `acctypeid` (`acctypeid`),
  KEY `destinationid` (`destinationid`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_ledger`
--

INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(1, '10101010107', 'Cash In Hand', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 1, 0, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(2, '10101010109', 'Dutch-Bangla Bank', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:12', 'John Doe', '2023-05-08 11:18:12');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(3, '10101010112', 'City Bank', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:18', 'John Doe', '2023-05-08 11:18:18');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(4, '10101010115', 'Paypal', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:22', 'John Doe', '2023-05-08 11:18:22');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(5, '10101010102', 'SSLCommerz', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:25', 'John Doe', '2023-05-08 11:18:25');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(6, '10101010301', 'Customer Receivable', 1, 3, 3, 1, 1, 2, 3, b'1', 0, 0, '4', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(7, '10101020401', 'Class Room Chair', 1, 1, 4, 1, 1, 2, 1, b'1', 0, 1, 'sch-1', 'sch-1-1', '', b'0', b'0', 20.00, 0, 0, 'John Doe', '2023-04-27 14:06:40', 'John Doe', '2023-04-27 14:06:40');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(8, '10101020402', 'Computer Table', 1, 1, 4, 1, 1, 2, 1, b'1', 0, 1, 'sch-1', 'sch-1-2', '', b'0', b'0', 15.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(9, '10101020403', 'File Cabinet', 1, 1, 4, 1, 1, 2, 1, b'1', 0, 1, 'sch-1', 'sch-1-3', '', b'0', b'0', 12.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(11, '10105010101', 'Share Capital', 5, 4, 9, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(12, '10105020101', 'General Reserve', 5, 5, 11, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(13, '10103020201', 'Sale Income', 3, 12, 12, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(14, '10101030501', 'Inventory', 1, 2, 13, 1, 1, 2, 1, b'1', 1, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(15, '10101010203', 'Advance Against Customer', 1, 3, 2, 1, 1, 2, 3, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(16, '10101010206', 'Advance Against Employee', 1, 3, 2, 1, 1, 2, 2, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(17, '10101010201', 'Deposit', 1, 3, 2, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(18, '10101010202', 'Prepayment 	Insurance Premium', 1, 3, 2, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(19, '10103010106', 'Service Charge Income', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(20, '10104010304', 'HP-Hasilpur', 4, 13, 15, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(21, '10104020402', 'Staff Salary', 4, 14, 16, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(22, '10104020401', 'Employee Salary', 4, 14, 16, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(23, '10104021101', 'Travelling', 4, 14, 17, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(24, '10104021102', 'Conveyance', 4, 14, 17, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(25, '10104022101', 'Entertainment', 4, 14, 18, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(26, '10104022801', 'Office Accessories', 4, 14, 19, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(27, '10101010101', 'Two Checkout', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:29', 'John Doe', '2023-05-08 11:18:29');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(28, '10101010105', 'Square Payments', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:32', 'John Doe', '2023-05-08 11:18:32');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(29, '10101010108', 'Stripe Payment', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:36', 'John Doe', '2023-05-08 11:18:36');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(30, '10101010111', 'Paystack Payments', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-08 11:18:38', 'John Doe', '2023-05-08 11:18:38');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(31, '10101010114', 'Paytm Payments', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(32, '10101010118', 'Orange Money payment', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(33, '10101010104', 'iyzico', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(34, '10101010106', 'Bkash', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(35, '10101010110', 'Rocket', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(36, '10101010113', 'Nogodh', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(37, '10101010117', 'M-Cash', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(38, '10101010103', 'SureCash', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:01', '2', '2023-04-17 05:41:01');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(40, '10103010103', 'Office Stationary (Income)', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(41, '10103010105', 'Miscellaneous (Income)', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(42, '10103010108', 'Bank Interest', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(43, '10103010109', 'Students Info. Correction Fee', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(44, '10103010101', 'Professional Training Course(Oracal-1)', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(45, '10103010102', 'Practical Fee', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(46, '10103010104', 'Professional Training Course(Oracal)', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(47, '10103010107', 'Purchase Discount', 3, 11, 14, 3, 2, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(49, '10104010206', 'Internet Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(50, '10104010208', 'Telephone Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(51, '10104010202', 'Drinking Water Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(52, '10104010203', 'Dish Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(53, '10104010204', 'WASA Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(54, '10104010205', 'Gas Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(55, '10104010207', 'Electricity Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(56, '10104010201', 'News Paper Bill', 4, 13, 20, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(57, '10104010801', 'TDS on House Rent', 4, 13, 21, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(58, '10104010802', 'Campus Rent', 4, 13, 21, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(59, '10104010803', 'VAT on House Rent Exp', 4, 13, 21, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(60, '10104010305', 'Generator Running Expenses', 4, 13, 15, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(61, '10104010101', 'Office Repair & Maintenance', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(62, '10104010104', 'Tea Kettle', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(63, '10104010107', 'AC', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(64, '10104010110', 'Fax Machine', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(65, '10104010112', 'Oven', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(66, '10104010114', 'TV', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(67, '10104010103', 'Close Circuit Camera', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(68, '10104010106', 'Furniture', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(69, '10104010109', 'Lift', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(70, '10104010111', 'Computer (R)', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(71, '10104010113', 'Photocopy Machine Repair', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(72, '10104010102', 'PABX-Repair', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(73, '10104010105', 'Micro Oven', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(74, '10104010108', 'Generator Repair', 4, 13, 22, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(77, '10104022001', 'Dividend', 4, 14, 23, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(78, '10104022701', 'Audit Fee', 4, 14, 24, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(79, '10104020201', 'Car Running Expenses', 4, 14, 25, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(80, '10104020901', 'Others (Non Academic Expenses)', 4, 14, 26, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(81, '10104020603', 'Medical Allowance', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(82, '10104020604', 'Faculty Allowances', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(83, '10104020605', 'Special Allowances', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(84, '10104020606', 'Festival & Incentive Bonus', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(85, '10104020601', 'Honorarium', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(86, '10104020503', 'Commision on Admission', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(87, '10104020505', 'Advertisement and Publicity', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(88, '10104020507', 'AIT Against Advertisement', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(89, '10104020508', 'Business Development Expenses', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(90, '10104020501', 'Design & Printing Expense', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(91, '10104020502', 'Campaign Expenses', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(92, '10104020504', 'Education Fair Expenses', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(93, '10104020506', 'Marketing & Promotion Exp.', 4, 14, 28, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(94, '10104020308', 'Miscellaneous Exp', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(95, '10104020301', 'HR Recruitment Expenses', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(96, '10104020303', 'Incentive on Admission', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(97, '10104020305', 'TB Care Expenses', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(98, '10104020306', 'Website Development Expenses', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(99, '10104020307', 'Sports Expense', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(100, '10104020309', 'Library Expenses', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(101, '10104020302', 'River Cruse', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(102, '10104020304', 'Cultural Expense', 4, 14, 29, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(103, '10104021701', 'Interest on Loan', 4, 14, 27, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(104, '10104021702', 'Bank Charge', 4, 14, 27, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(105, '10102020203', 'Long Term Borrowing', 2, 20, 31, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(106, '10102020201', 'Sponsors Loan', 2, 20, 31, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(107, '10102010301', 'Short Term Borrowing', 2, 21, 32, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(108, '10102010301', 'Short Term Loan from Bank', 2, 21, 32, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2022-04-11 12:19:27', 'John Doe', '2022-04-11 12:19:27');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(109, '10102010402', 'Account Payable', 2, 21, 33, 2, 2, 2, 4, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(110, '10101010204', 'Advance House Rent', 1, 3, 2, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(111, '10102020101', 'VAT Payable', 2, 21, 34, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(112, '10102020102', 'Income Tax(TDS Payable House Rent)', 2, 21, 34, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(113, '10102020103', 'Income Tax(TDS Payable on Salary)', 2, 21, 34, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(114, '10102020104', 'Income Tax(TDS Payable on Advertisement Bill)', 2, 21, 34, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(116, '10105030101', 'Last year Profit & Loss', 5, 4, 40, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(117, '10105030201', 'Current year Profit & Loss', 5, 4, 10, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:06', '2', '2023-04-17 05:41:06');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(118, '10104030101', 'Sale Discount', 4, 15, 35, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(119, '10104030201', 'Vat', 4, 15, 36, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(120, '10102010401', 'Employee Payable', 2, 21, 33, 2, 2, 2, 2, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(121, '10104040101', 'Product Purchase', 4, 19, 37, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2023-05-10 15:54:10', 'John Doe', '2023-05-10 15:54:10');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(122, '10101010116', 'Brac Bank', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(123, '10104010301', 'Other Cost', 4, 13, 15, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(124, '10104010302', 'Transport Cost', 4, 13, 15, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(125, '10104010303', 'Labour Cost', 4, 13, 15, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(126, '10104010804', 'House Rent Expence', 4, 13, 21, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:05', '2', '2023-04-17 05:41:05');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(127, '10101010205', 'Loan to Employee', 1, 3, 2, 1, 1, 2, 2, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:02', '2', '2023-04-17 05:41:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(128, '10104020101', 'Newspaper', 4, 14, 39, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:03', '2', '2023-04-17 05:41:03');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(129, '10104020602', 'Food Allowances', 4, 14, 30, 4, 1, 1, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, '2', '2023-04-17 05:41:04', '2', '2023-04-17 05:41:04');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(138, '10101010207', 'Advance to Supplier', 1, 3, 2, 1, 1, 2, 4, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2023-05-08 13:03:53', 'John Doe', '2023-05-08 13:03:53');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(139, '10101010119', 'Cash In Transit', 1, 3, 1, 1, 1, 2, 6, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 1, 0, 'John Doe', '2023-05-11 16:40:23', 'John Doe', '2023-05-11 16:40:23');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(140, '10101010302', 'Delivery Agency', 1, 3, 3, 1, 1, 2, 6, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2023-05-11 16:41:49', 'John Doe', '2023-05-11 16:41:49');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(141, '10101010120', 'Card Terminals', 1, 3, 1, 1, 1, 2, 8, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-05-30 10:01:26', 'John Doe', '2023-05-30 10:01:26');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(145, '10101010121', 'VISA', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:40:23', 'John Doe', '2023-06-22 14:40:23');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(146, '10101010122', 'Master', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:40:42', 'John Doe', '2023-06-22 14:40:42');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(147, '10101010123', 'Discover', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:41:15', 'John Doe', '2023-06-22 14:41:15');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(148, '10101010124', 'Amex', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:41:44', 'John Doe', '2023-06-22 14:41:44');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(149, '10101010125', 'JCB', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:42:07', 'John Doe', '2023-06-22 14:42:07');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(150, '10101010126', 'China Union Pay', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-06-22 14:42:40', 'John Doe', '2023-06-22 14:42:40');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(152, '10101010127', 'Saiful pay', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2023-10-02 09:54:34', 'John Doe', '2023-10-02 09:54:34');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(153, '10101010128', 'Kamal', 1, 3, 1, 1, 1, 2, 1, b'1', 0, 0, '3', '', '', b'0', b'0', 0.00, 0, 1, 'John Doe', '2023-10-02 09:59:02', 'John Doe', '2023-10-02 09:59:02');
INSERT INTO `tbl_ledger` (`id`, `code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES(154, '10102010403', 'Product Transfer Received from Head office', 2, 21, 33, 2, 2, 2, 1, b'1', 0, 0, '', '', '', b'0', b'0', 0.00, 0, 0, 'John Doe', '2024-03-19 14:19:01', 'John Doe', '2024-03-19 14:19:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_load_shedule`
--

DROP TABLE IF EXISTS `tbl_load_shedule`;
CREATE TABLE IF NOT EXISTS `tbl_load_shedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `employeeid` varchar(100) NOT NULL,
  `total_amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `installmentamount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `installmentdate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_location_zone`
--

DROP TABLE IF EXISTS `tbl_location_zone`;
CREATE TABLE IF NOT EXISTS `tbl_location_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_location_zone`
--

INSERT INTO `tbl_location_zone` (`id`, `zone_name`, `price`, `status`) VALUES(1, 'Uttara', 200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mainbranchinfo`
--

DROP TABLE IF EXISTS `tbl_mainbranchinfo`;
CREATE TABLE IF NOT EXISTS `tbl_mainbranchinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branchid` int(11) NOT NULL,
  `branchip` text NOT NULL,
  `authkey` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menutoping`
--

DROP TABLE IF EXISTS `tbl_menutoping`;
CREATE TABLE IF NOT EXISTS `tbl_menutoping` (
  `tpmid` int(11) NOT NULL AUTO_INCREMENT,
  `assignid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `tid` varchar(50) NOT NULL,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  PRIMARY KEY (`tpmid`),
  KEY `assignid` (`assignid`),
  KEY `menuid` (`menuid`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menutype`
--

DROP TABLE IF EXISTS `tbl_menutype`;
CREATE TABLE IF NOT EXISTS `tbl_menutype` (
  `menutypeid` int(11) NOT NULL AUTO_INCREMENT,
  `menucode` varchar(50) DEFAULT NULL,
  `menutype` varchar(120) NOT NULL,
  `menu_icon` varchar(150) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  PRIMARY KEY (`menutypeid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mobilepmethod`
--

DROP TABLE IF EXISTS `tbl_mobilepmethod`;
CREATE TABLE IF NOT EXISTS `tbl_mobilepmethod` (
  `mpid` int(11) NOT NULL AUTO_INCREMENT,
  `mobilePaymentname` varchar(255) NOT NULL,
  `comissionrate` int(11) DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '1=active,0=inactive',
  PRIMARY KEY (`mpid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_mobilepmethod`
--

INSERT INTO `tbl_mobilepmethod` (`mpid`, `mobilePaymentname`, `comissionrate`, `status`) VALUES(1, 'Bkash', 3, 1);
INSERT INTO `tbl_mobilepmethod` (`mpid`, `mobilePaymentname`, `comissionrate`, `status`) VALUES(2, 'Rocket', 2, 1);
INSERT INTO `tbl_mobilepmethod` (`mpid`, `mobilePaymentname`, `comissionrate`, `status`) VALUES(3, 'Nogodh', 2, 1);
INSERT INTO `tbl_mobilepmethod` (`mpid`, `mobilePaymentname`, `comissionrate`, `status`) VALUES(4, 'M-Cash', 3, 1);
INSERT INTO `tbl_mobilepmethod` (`mpid`, `mobilePaymentname`, `comissionrate`, `status`) VALUES(5, 'SureCash', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mobiletransaction`
--

DROP TABLE IF EXISTS `tbl_mobiletransaction`;
CREATE TABLE IF NOT EXISTS `tbl_mobiletransaction` (
  `trid` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` bigint(20) NOT NULL,
  `multipay_id` int(11) NOT NULL,
  `mobilemethod` int(11) DEFAULT NULL,
  `mobilenumber` varchar(100) DEFAULT NULL,
  `transactionnumber` varchar(255) DEFAULT NULL,
  `pdate` date DEFAULT '1970-01-01',
  PRIMARY KEY (`trid`),
  KEY `bill_id` (`bill_id`),
  KEY `multipay_id` (`multipay_id`),
  KEY `mobilemethod` (`mobilemethod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_module_purchasekey`
--

DROP TABLE IF EXISTS `tbl_module_purchasekey`;
CREATE TABLE IF NOT EXISTS `tbl_module_purchasekey` (
  `mpid` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(25) DEFAULT NULL,
  `purchasekey` varchar(55) DEFAULT NULL,
  `downloaddate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  `updatedate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`mpid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_module_purchasekey`
--

INSERT INTO `tbl_module_purchasekey` (`mpid`, `module`, `purchasekey`, `downloaddate`, `updatedate`) VALUES(3, 'whatsapp', 'BDT-9D7656-F83D-A4-4D967F901A773', '2021-08-31 14:05:42', '2021-08-31 14:05:42');
INSERT INTO `tbl_module_purchasekey` (`mpid`, `module`, `purchasekey`, `downloaddate`, `updatedate`) VALUES(11, 'loyalty', 'BDT-2E107A8-FF7ACB-1346F14-4CA04', '2021-09-01 11:47:02', '2021-09-01 11:47:02');
INSERT INTO `tbl_module_purchasekey` (`mpid`, `module`, `purchasekey`, `downloaddate`, `updatedate`) VALUES(16, 'printershare', 'BDT-060466A-50-5BE7F15259E44-00D', '2021-09-16 16:24:24', '2021-09-16 16:24:24');
INSERT INTO `tbl_module_purchasekey` (`mpid`, `module`, `purchasekey`, `downloaddate`, `updatedate`) VALUES(17, 'androidpos', 'BDT-288-DC80E1-085F348-326D05999', '2021-11-07 16:06:06', '2021-11-07 16:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monthly_deduct`
--

DROP TABLE IF EXISTS `tbl_monthly_deduct`;
CREATE TABLE IF NOT EXISTS `tbl_monthly_deduct` (
  `deductid` int(11) NOT NULL AUTO_INCREMENT,
  `duductheadid` int(11) NOT NULL,
  `accheadid` int(11) NOT NULL,
  `employee_id` varchar(200) NOT NULL,
  `month_year` varchar(100) NOT NULL,
  `amount` decimal(19,2) NOT NULL DEFAULT 0.00,
  `amounttypeid` int(11) NOT NULL,
  `is_approved` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`deductid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notificationsetting`
--

DROP TABLE IF EXISTS `tbl_notificationsetting`;
CREATE TABLE IF NOT EXISTS `tbl_notificationsetting` (
  `notifid` int(11) NOT NULL AUTO_INCREMENT,
  `firebasewaiterkitchen` text DEFAULT NULL,
  `onesignalcustomer` text NOT NULL,
  `onesignal_ioswaiter` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`notifid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_notificationsetting`
--

INSERT INTO `tbl_notificationsetting` (`notifid`, `firebasewaiterkitchen`, `onesignalcustomer`, `onesignal_ioswaiter`, `status`) VALUES(1, 'AAAAqG0NVRM:APA91bExey2V18zIHoQmCkMX08SN-McqUvI4c3CG3AnvkRHQp8S9wKn-K4Vb9G79Rfca8bQJY9pn-tTcWiXYJiqe2s63K6QHRFqIx4Oaj9MoB1uVqB7U_gNT9fiqckeWge8eVB9P5-rX', '208455d9-baca-4ed2-b6be-12b466a2efbd', '4e1150f3-03c8-4de3-ab57-79ca27da1b8e', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_openclose`
--

DROP TABLE IF EXISTS `tbl_openclose`;
CREATE TABLE IF NOT EXISTS `tbl_openclose` (
  `stid` int(11) NOT NULL AUTO_INCREMENT,
  `dayname` varchar(20) NOT NULL,
  `opentime` varchar(15) NOT NULL,
  `closetime` varchar(15) NOT NULL,
  PRIMARY KEY (`stid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_openclose`
--

INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(1, 'Saturday', '08:00', '21:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(2, 'Sunday', '08:00', '20:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(3, 'Monday', '08:00', '20:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(4, 'Tuesday', '08:00', '20:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(5, 'Wednesday', '08:00', '20:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(6, 'Thursday', '08:00', '20:00');
INSERT INTO `tbl_openclose` (`stid`, `dayname`, `opentime`, `closetime`) VALUES(7, 'Friday', 'Closed', 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_openfood`
--

DROP TABLE IF EXISTS `tbl_openfood`;
CREATE TABLE IF NOT EXISTS `tbl_openfood` (
  `openfid` int(11) NOT NULL AUTO_INCREMENT,
  `foodname` varchar(255) NOT NULL,
  `foodprice` decimal(19,3) NOT NULL DEFAULT 0.000,
  `quantity` float NOT NULL,
  `op_orderid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`openfid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_openingbalance`
--

DROP TABLE IF EXISTS `tbl_openingbalance`;
CREATE TABLE IF NOT EXISTS `tbl_openingbalance` (
  `opbalance_id` int(11) NOT NULL AUTO_INCREMENT,
  `ho_id` int(11) DEFAULT NULL COMMENT 'Head Office Id',
  `fiyear_id` int(11) NOT NULL,
  `headcode` text DEFAULT NULL,
  `opening_debit` decimal(18,3) DEFAULT NULL,
  `opening_credit` decimal(19,3) NOT NULL DEFAULT 0.000,
  `openingDate` date NOT NULL DEFAULT '1970-01-01',
  `subtypeid` int(11) DEFAULT NULL,
  `subcode` int(11) DEFAULT NULL,
  `CreateBy` varchar(100) DEFAULT NULL,
  `CreateDate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  `Updateby` varchar(100) DEFAULT NULL,
  `UpdateDate` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`opbalance_id`),
  KEY `fiyear_id` (`fiyear_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_openingstock`
--

DROP TABLE IF EXISTS `tbl_openingstock`;
CREATE TABLE IF NOT EXISTS `tbl_openingstock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itemid` int(11) NOT NULL,
  `itemtype` int(11) NOT NULL DEFAULT 0 COMMENT '0=raw,1=finishgood',
  `openstock` decimal(19,3) NOT NULL DEFAULT 0.000,
  `storageqty` decimal(19,3) DEFAULT NULL,
  `unitprice` decimal(19,4) NOT NULL DEFAULT 0.0000,
  `entrydate` date NOT NULL DEFAULT '1970-01-01',
  `createby` int(11) NOT NULL,
  `createdate` datetime NOT NULL DEFAULT '1970-01-01 00:01:01',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderduediscount`
--

DROP TABLE IF EXISTS `tbl_orderduediscount`;
CREATE TABLE IF NOT EXISTS `tbl_orderduediscount` (
  `duedisid` int(11) NOT NULL AUTO_INCREMENT,
  `duetotal` float NOT NULL,
  `dueorderid` int(11) NOT NULL,
  PRIMARY KEY (`duedisid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderlog`
--

DROP TABLE IF EXISTS `tbl_orderlog`;
CREATE TABLE IF NOT EXISTS `tbl_orderlog` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `orderid` int(11) NOT NULL,
  `details` longtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `logdate` datetime NOT NULL DEFAULT '1790-01-01 01:01:01',
  PRIMARY KEY (`logid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orderprepare`
--

DROP TABLE IF EXISTS `tbl_orderprepare`;
CREATE TABLE IF NOT EXISTS `tbl_orderprepare` (
  `opid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `varient` int(11) NOT NULL,
  `preparetime` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`opid`),
  KEY `orderid` (`orderid`),
  KEY `menuid` (`menuid`),
  KEY `varient` (`varient`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_physical_stock`
--

DROP TABLE IF EXISTS `tbl_physical_stock`;
CREATE TABLE IF NOT EXISTS `tbl_physical_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ingredient_id` int(11) NOT NULL,
  `unit_price` double(10,2) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `total_price` double(10,2) NOT NULL,
  `entry_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posbillsatelpermission`
--

DROP TABLE IF EXISTS `tbl_posbillsatelpermission`;
CREATE TABLE IF NOT EXISTS `tbl_posbillsatelpermission` (
  `billstid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `ordercancel` int(11) NOT NULL DEFAULT 0,
  `ordcomplete` int(11) NOT NULL DEFAULT 0,
  `ordedit` int(11) NOT NULL DEFAULT 0,
  `ordersplit` int(11) NOT NULL DEFAULT 0,
  `ordmerge` int(11) NOT NULL DEFAULT 0,
  `ordkot` int(11) NOT NULL DEFAULT 0,
  `orddue` int(11) NOT NULL DEFAULT 0,
  `kitchen_status` int(11) NOT NULL DEFAULT 0,
  `todayord` int(11) NOT NULL DEFAULT 0,
  `onlineord` int(11) NOT NULL DEFAULT 0,
  `qrord` int(11) NOT NULL DEFAULT 0,
  `thirdord` int(11) NOT NULL DEFAULT 0,
  `priceshowhide` int(11) DEFAULT 1,
  `printsummerybtn` int(11) DEFAULT 1,
  PRIMARY KEY (`billstid`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_posbillsatelpermission`
--

INSERT INTO `tbl_posbillsatelpermission` (`billstid`, `userid`, `ordercancel`, `ordcomplete`, `ordedit`, `ordersplit`, `ordmerge`, `ordkot`, `orddue`, `kitchen_status`, `todayord`, `onlineord`, `qrord`, `thirdord`, `priceshowhide`, `printsummerybtn`) VALUES(16, 177, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1);
INSERT INTO `tbl_posbillsatelpermission` (`billstid`, `userid`, `ordercancel`, `ordcomplete`, `ordedit`, `ordersplit`, `ordmerge`, `ordkot`, `orddue`, `kitchen_status`, `todayord`, `onlineord`, `qrord`, `thirdord`, `priceshowhide`, `printsummerybtn`) VALUES(23, 166, 0, 1, 1, 0, 0, 0, 1, 0, 1, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posetting`
--

DROP TABLE IF EXISTS `tbl_posetting`;
CREATE TABLE IF NOT EXISTS `tbl_posetting` (
  `possettingid` int(11) NOT NULL AUTO_INCREMENT,
  `waiter` int(11) NOT NULL DEFAULT 0 COMMENT '1=show,0=hide',
  `tableid` int(11) NOT NULL DEFAULT 0 COMMENT '1=show,0=hide',
  `cooktime` int(11) NOT NULL DEFAULT 0 COMMENT '1=enable,0=disable',
  `productionsetting` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=manual,1=auto',
  `tablemaping` int(11) NOT NULL DEFAULT 0 COMMENT '1=enable,0=disable',
  `soundenable` int(11) DEFAULT NULL COMMENT '1=enable,0=disable',
  `closingbutton` int(11) NOT NULL DEFAULT 1 COMMENT '1 =enable,0=disable',
  `isautoapproved` int(11) NOT NULL DEFAULT 0 COMMENT '1=Paid,2=Paid And Free',
  PRIMARY KEY (`possettingid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_posetting`
--

INSERT INTO `tbl_posetting` (`possettingid`, `waiter`, `tableid`, `cooktime`, `productionsetting`, `tablemaping`, `soundenable`, `closingbutton`, `isautoapproved`) VALUES(1, 0, 1, 1, 0, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_predefined`
--

DROP TABLE IF EXISTS `tbl_predefined`;
CREATE TABLE IF NOT EXISTS `tbl_predefined` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CashCode` int(11) NOT NULL,
  `BankCode` int(11) DEFAULT NULL,
  `advance` int(11) DEFAULT NULL,
  `purchaseAcc` int(11) DEFAULT NULL,
  `PurchaseDiscount` int(11) DEFAULT 0,
  `SalesAcc` int(11) DEFAULT NULL,
  `ServiceIncome` int(11) DEFAULT NULL,
  `CustomerAcc` int(11) DEFAULT NULL,
  `SupplierAcc` int(11) DEFAULT NULL,
  `COGS` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `tax` int(11) DEFAULT NULL,
  `inventoryCode` int(11) DEFAULT NULL,
  `CPLcode` int(11) DEFAULT NULL,
  `LPLcode` int(11) DEFAULT NULL,
  `salary_code` int(11) DEFAULT NULL,
  `emp_npf_contribution` int(11) DEFAULT NULL,
  `empr_npf_contribution` int(11) DEFAULT NULL,
  `emp_icf_contribution` int(11) DEFAULT NULL,
  `empr_icf_contribution` int(11) DEFAULT NULL,
  `advance_employee` varchar(50) NOT NULL,
  `load_to_employee` varchar(50) NOT NULL,
  `dragency` int(11) DEFAULT NULL,
  `cashintransit` int(11) DEFAULT NULL,
  `cardterminal` int(11) DEFAULT NULL,
  `product_received_from_ho` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_predefined`
--

INSERT INTO `tbl_predefined` (`id`, `CashCode`, `BankCode`, `advance`, `purchaseAcc`, `PurchaseDiscount`, `SalesAcc`, `ServiceIncome`, `CustomerAcc`, `SupplierAcc`, `COGS`, `vat`, `tax`, `inventoryCode`, `CPLcode`, `LPLcode`, `salary_code`, `emp_npf_contribution`, `empr_npf_contribution`, `emp_icf_contribution`, `empr_icf_contribution`, `advance_employee`, `load_to_employee`, `dragency`, `cashintransit`, `cardterminal`, `product_received_from_ho`) VALUES(3, 1, 2, 15, 121, 47, 13, 19, 6, 109, 118, 119, 111, 14, 117, 116, 21, 0, 0, 0, 0, '16', '127', 140, 139, 141, 154);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qrpayments`
--

DROP TABLE IF EXISTS `tbl_qrpayments`;
CREATE TABLE IF NOT EXISTS `tbl_qrpayments` (
  `qrpid` int(11) NOT NULL AUTO_INCREMENT,
  `paymentsid` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`qrpid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_qrpayments`
--

INSERT INTO `tbl_qrpayments` (`qrpid`, `paymentsid`) VALUES(1, '20,9,4,1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_qrsetting`
--

DROP TABLE IF EXISTS `tbl_qrsetting`;
CREATE TABLE IF NOT EXISTS `tbl_qrsetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` int(11) NOT NULL DEFAULT 0,
  `cartbtn` int(11) NOT NULL DEFAULT 0,
  `theme` varchar(255) DEFAULT NULL,
  `backgroundcolorqr` text DEFAULT NULL,
  `qrheaderfontcolor` text DEFAULT NULL,
  `review_code` text DEFAULT NULL,
  `isactivereview` int(11) NOT NULL DEFAULT 0 COMMENT '0=inactive,1=active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_qrsetting`
--

INSERT INTO `tbl_qrsetting` (`id`, `image`, `cartbtn`, `theme`, `backgroundcolorqr`, `qrheaderfontcolor`, `review_code`, `isactivereview`) VALUES(1, 0, 0, 'bg-light', '#000000', '#ffffff', '<script src=\"https://static.elfsight.com/platform/platform.js\" data-use-service-core defer></script>\r\n		<div class=\"elfsight-app-b4669c73-049b-47ab-ac41-72763a2aceba\"></div>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quickordersetting`
--

DROP TABLE IF EXISTS `tbl_quickordersetting`;
CREATE TABLE IF NOT EXISTS `tbl_quickordersetting` (
  `quickordid` int(11) NOT NULL AUTO_INCREMENT,
  `waiter` int(11) NOT NULL DEFAULT 1 COMMENT '1=show,0=hide',
  `tableid` int(11) NOT NULL DEFAULT 1 COMMENT '1=show,0=hide',
  `cooktime` int(11) NOT NULL DEFAULT 1 COMMENT '1=show,0=hide',
  `soundenable` int(11) NOT NULL DEFAULT 1 COMMENT '1=enable,0=disable	',
  `tablemaping` int(11) NOT NULL DEFAULT 1 COMMENT '1=enable,0=disable',
  PRIMARY KEY (`quickordid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_quickordersetting`
--

INSERT INTO `tbl_quickordersetting` (`quickordid`, `waiter`, `tableid`, `cooktime`, `soundenable`, `tablemaping`) VALUES(1, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rate_conversion`
--

DROP TABLE IF EXISTS `tbl_rate_conversion`;
CREATE TABLE IF NOT EXISTS `tbl_rate_conversion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(50) NOT NULL,
  `mergeOrderid` varchar(50) DEFAULT NULL,
  `conv_amount` decimal(10,2) NOT NULL,
  `payrate` decimal(10,2) NOT NULL,
  `currency_name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=283 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

--
-- Dumping data for table `tbl_rate_conversion`
--

INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(1, '24', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(2, '25', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(3, '26', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(4, '27', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(5, '28', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(6, '29', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(7, '30', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(8, '31', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(9, '32', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(10, '33', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(11, '34', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(12, '35', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(13, '36', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(14, '37', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(15, '42', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(16, '46', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(17, '58', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(18, '72', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(19, '71', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(20, '66,66,67,67,68,68,69,69,', '2023-06-19_3802', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(21, '66,66,67,67,68,68,69,69,', '2023-06-19_1077', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(22, '65', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(23, '64', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(24, '63', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(25, '62', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(26, '61', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(27, '73', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(28, '74', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(29, '75', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(30, '76', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(31, '77', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(32, '78', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(33, '80', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(34, '81', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(35, '82', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(36, '83', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(37, '84', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(38, '85', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(39, '86', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(40, '89', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(41, '90', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(42, '98,98,99,99,99,', '2023-07-09_2330', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(43, '98,98,99,99,99,', '2023-07-09_1419', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(44, '97,97,100,100,', '2023-07-09_2523', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(45, '101,101,101,102,102,102,', '2023-07-09_4377', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(46, '104', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(47, '103', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(48, '106', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(49, '107', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(50, '108', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(51, '109', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(52, '110', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(53, '111', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(54, '112', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(55, '114', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(56, '113', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(57, '115', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(58, '116', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(59, '117', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(60, '118', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(61, '119', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(62, '121', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(63, '122', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(64, '23', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(65, '24', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(66, '25', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(67, '125,125,126,126,127,127,127,', '2023-07-24_9553', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(68, '26', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(69, '129', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(70, '136', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(71, '137', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(72, '138', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(73, '139', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(74, '140', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(75, '141', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(76, '142', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(77, '143', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(78, '144', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(79, '145', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(80, '146', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(81, '147', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(82, '148', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(83, '149', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(84, '150', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(85, '151', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(86, '152', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(87, '153', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(88, '155', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(89, '156', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(90, '157', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(91, '158', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(92, '160', NULL, 125631.19, 108.07, 'BDT');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(93, '161', NULL, 63220.86, 108.07, 'BDT');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(94, '162', NULL, 128332.94, 108.07, 'BDT');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(95, '163', NULL, 64166.47, 108.07, 'BDT');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(96, '164', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(97, '165', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(98, '166', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(99, '167', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(100, '168', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(101, '169', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(102, '170', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(103, '171', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(104, '172', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(105, '173', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(106, '174', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(107, '175', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(108, '176', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(109, '177', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(110, '178', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(111, '179', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(112, '180', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(113, '181', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(114, '183', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(115, '182', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(116, '184', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(117, '185', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(118, '186', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(119, '187', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(120, '188', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(121, '27', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(122, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(123, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(124, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(125, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(126, '7', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(127, '8', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(128, '9', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(129, '10', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(130, '11', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(131, '12', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(132, '13,14,', '2023-08-21_4782', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(133, '15,16,', '2023-08-21_0142', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(134, '17,18,', '2023-08-22_5278', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(135, '6', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(136, '19,20,', '2023-08-22_0015', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(137, '21,22,', '2023-08-22_0643', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(138, '32,33,', '2023-08-22_0022', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(139, '25,26,27,28,29,30,31,', '2023-08-22_2622', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(140, '23,24,', '2023-08-22_7336', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(141, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(142, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(143, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(144, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(145, '6', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(146, '37', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(147, '38', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(148, '39', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(149, '40', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(150, '41', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(151, '42', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(152, '43', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(153, '44', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(154, '45', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(155, '46', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(156, '47,47,48,48,48,', '2023-08-31_2809', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(157, '47,47,48,48,48,', '2023-08-31_8743', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(158, '49', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(159, '50', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(160, '52', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(161, '54', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(162, '55', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(163, '7', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(164, '8', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(165, '9', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(166, '10', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(167, '12', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(168, '11', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(169, '59,59,60,60,', '2023-08-31_4344', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(170, '51,53,', '2023-08-31_4241', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(171, '61,61,62,62,', '2023-08-31_4930', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(172, '63,63,64,', '2023-08-31_2372', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(173, '65,66,66,66,', '2023-08-31_7420', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(174, '67,67,68,68,', '2023-08-31_4703', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(175, '69,69,70,70,', '2023-08-31_3062', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(176, '71', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(177, '72', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(178, '73,73,74,74,', '2023-09-04_5251', 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(179, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(180, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(181, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(182, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(183, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(184, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(185, '5', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(186, '6', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(187, '8', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(188, '19', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(189, '20', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(190, '21', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(191, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(192, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(193, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(194, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(195, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(196, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(197, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(198, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(199, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(200, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(201, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(202, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(203, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(204, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(205, '7', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(206, '36', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(207, '34', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(208, '109', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(209, '108', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(210, '110', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(211, '111', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(212, '112', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(213, '113', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(214, '114', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(215, '115', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(216, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(217, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(218, '11', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(219, '12', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(220, '16', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(221, '17', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(222, '18', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(223, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(224, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(225, '9', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(226, '8', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(227, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(228, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(229, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(230, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(231, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(232, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(233, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(234, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(235, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(236, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(237, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(238, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(239, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(240, '5', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(241, '6', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(242, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(243, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(244, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(245, '12', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(246, '13', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(247, '14', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(248, '15', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(249, '16', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(250, '17', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(251, '18', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(252, '19', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(253, '20', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(254, '22', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(255, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(256, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(257, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(258, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(259, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(260, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(261, '5', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(262, '13', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(263, '14', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(264, '15', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(265, '20', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(266, '23', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(267, '24', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(268, '29', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(269, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(270, '4', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(271, '5', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(272, '1', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(273, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(274, '3', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(275, '2', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(276, '13', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(277, '14', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(278, '15', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(279, '17', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(280, '20', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(281, '23', NULL, 0.00, 0.00, '');
INSERT INTO `tbl_rate_conversion` (`id`, `order_id`, `mergeOrderid`, `conv_amount`, `payrate`, `currency_name`) VALUES(282, '24', NULL, 0.00, 0.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

DROP TABLE IF EXISTS `tbl_rating`;
CREATE TABLE IF NOT EXISTS `tbl_rating` (
  `ratingid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `reviewtxt` text DEFAULT NULL,
  `proid` int(11) NOT NULL,
  `rating` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `ratetime` datetime NOT NULL,
  PRIMARY KEY (`ratingid`),
  KEY `proid` (`proid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reconcelstatus`
--

DROP TABLE IF EXISTS `tbl_reconcelstatus`;
CREATE TABLE IF NOT EXISTS `tbl_reconcelstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_reconcelstatus`
--

INSERT INTO `tbl_reconcelstatus` (`id`, `name`) VALUES(1, 'Honours');
INSERT INTO `tbl_reconcelstatus` (`id`, `name`) VALUES(2, 'Dis Honours');
INSERT INTO `tbl_reconcelstatus` (`id`, `name`) VALUES(3, 'Cheque Refund');
INSERT INTO `tbl_reconcelstatus` (`id`, `name`) VALUES(5, 'Placed');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reedem`
--

DROP TABLE IF EXISTS `tbl_reedem`;
CREATE TABLE IF NOT EXISTS `tbl_reedem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `kitchen_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `kitchennote` longtext NOT NULL,
  `reedem_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reedem_details`
--

DROP TABLE IF EXISTS `tbl_reedem_details`;
CREATE TABLE IF NOT EXISTS `tbl_reedem_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reedem_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `used_qty` double(10,2) DEFAULT 0.00,
  `wastage_qty` double(10,2) DEFAULT 0.00,
  `expired_qty` double(10,2) DEFAULT 0.00,
  `remaining_qty` double(10,2) DEFAULT 0.00,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_return_payment`
--

DROP TABLE IF EXISTS `tbl_return_payment`;
CREATE TABLE IF NOT EXISTS `tbl_return_payment` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `pay_amount` decimal(19,3) DEFAULT NULL,
  `due_amount` decimal(19,3) DEFAULT NULL,
  `payment_method_id` bigint(20) NOT NULL,
  `oreturn_id` int(11) NOT NULL,
  `createddate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_room`
--

DROP TABLE IF EXISTS `tbl_room`;
CREATE TABLE IF NOT EXISTS `tbl_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roomno` varchar(100) NOT NULL,
  `floorno` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1=active,0=inactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_salary_advance`
--

DROP TABLE IF EXISTS `tbl_salary_advance`;
CREATE TABLE IF NOT EXISTS `tbl_salary_advance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `salary_month` varchar(50) NOT NULL COMMENT 'for the month advance will be deducted',
  `amount` decimal(11,0) NOT NULL,
  `release_amount` decimal(11,0) DEFAULT 0,
  `paid` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'paid_to_employee',
  `CreateDate` date NOT NULL,
  `CreateBy` int(11) NOT NULL,
  `UpdateDate` date DEFAULT NULL,
  `UpdateBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_seoption`
--

DROP TABLE IF EXISTS `tbl_seoption`;
CREATE TABLE IF NOT EXISTS `tbl_seoption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `title_slug` varchar(255) NOT NULL,
  `keywords` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_seoption`
--

INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(1, 'Bhojon Home page', 'home', 'restaurant,food,reservation', 'Best Restautant Management Software');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(3, 'Menu', 'menu', 'Desert,Meet,fish,meet,bevarage', 'Menu Page');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(4, 'Food Details', 'food_details', 'Meet,solt', 'Details foodÃƒÆ’Ã¢â‚¬Å¡Ãƒâ€šÃ‚Â  information');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(5, 'Reservation', 'reservation', 'Table,booking,reservation', 'Table Reservation');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(6, 'Cart Page', 'cart_page', 'food,menu', 'Cart Page');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(7, 'Checkout', 'checkout', 'Checkout', 'Checkout');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(8, 'Login', 'login', 'Login', 'Login');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(9, 'Registration', 'registration', 'Registration', 'Registration');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(10, 'Payment information', 'payment_information', 'Online Payment information', 'Payment information');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(11, 'Stripe Payment information', 'stripe_payment_information', 'Stripe Payment', 'Stripe Payment information');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(12, 'About us', 'about_us', 'About restaurant', 'About us');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(13, 'Contact Us', 'contact_us', 'Contact Us', 'Contact Us');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(14, 'Privacy Policy', 'privacy_policy', 'privacy', 'Privacy Policy');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(15, 'Our Terms', 'our_terms', 'Our Terms', 'Our Terms');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(16, 'My Profile', 'my_profile', 'My Profile', 'My Profile');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(17, 'My Order List', 'my_order_list', 'My Order List', 'My Order List');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(18, 'View Order', 'view_order', 'View Order', 'View Order');
INSERT INTO `tbl_seoption` (`id`, `title`, `title_slug`, `keywords`, `description`) VALUES(19, 'My Reservation', 'my_reservation', 'My Reservation', 'My Reservation');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shippingaddress`
--

DROP TABLE IF EXISTS `tbl_shippingaddress`;
CREATE TABLE IF NOT EXISTS `tbl_shippingaddress` (
  `shipaddressid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `companyname` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `city` varchar(70) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `zip` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `address2` text DEFAULT NULL,
  `DateInserted` datetime NOT NULL DEFAULT '1970-01-01 01:01:01',
  PRIMARY KEY (`shipaddressid`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slider`
--

DROP TABLE IF EXISTS `tbl_slider`;
CREATE TABLE IF NOT EXISTS `tbl_slider` (
  `slid` int(11) NOT NULL AUTO_INCREMENT,
  `Sltypeid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `slink` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `delation_status` int(11) NOT NULL DEFAULT 0,
  `width` int(11) NOT NULL DEFAULT 0,
  `height` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`slid`),
  KEY `Sltypeid` (`Sltypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_slider`
--

INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(1, 1, 'Welcome To', 'Book <span>Your</span> Table', 'assets/img/banner/2023-01-04/1.jpg', '#', 1, 0, 1920, 902);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(2, 1, 'Find Your', 'Best <span>Cafe</span> Deals', 'assets/img/banner/2023-01-04/11.jpg', '#', 1, 0, 1920, 902);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(3, 1, 'Exclusive', 'Coffee <span>Shop</span>', 'assets/img/banner/2023-01-04/12.jpg', '#', 1, 0, 1920, 902);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(4, 2, 'Discover', 'OUR STORY', '', '#', 1, 0, 263, 332);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(5, 2, 'Discover', 'OUR STORY', '', '#', 1, 0, 263, 332);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(6, 3, 'Discover', 'OUR MENU', '', '#', 1, 0, 263, 332);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(7, 3, 'Discover', 'OUR MENU', '', '#', 1, 0, 263, 177);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(8, 3, 'Discover', 'OUR MENU', '', '#', 1, 0, 263, 177);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(9, 4, 'right', 'ads', '', '#', 1, 0, 252, 621);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(10, 5, 'OUR AWESOME STREET', 'FOOD HISTORY', '', '#', 1, 0, 541, 516);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(11, 6, 'Reservation', 'BOOK YOUR TABLE', 'assets/img/banner/2021-09-01/r.jpg', '#', 1, 0, 470, 548);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(12, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(13, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(14, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(15, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(16, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(17, 7, 'Our Gallery', 'CHEF SELECTION', '', '#', 1, 0, 340, 277);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(18, 8, 'Offer', 'item offer', '', '#', 1, 0, 250, 533);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(19, 9, 'Offer', 'food offer', '', '#', 1, 0, 250, 553);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(20, 10, 'contact us', 'contact', '', '#', 1, 0, 475, 633);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(21, 11, 'offer', 'sub', 'assets/img/banner/2021-10-18/b.jpg', '#', 1, 0, 941, 256);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(22, 13, 'RESTORA POS HISTORY', 'HISTORY', 'assets/img/banner/2021-10-27/1.jpg', '#', 1, 0, 405, 535);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(23, 13, 'VISIT OUR', 'VISIT OUR', 'assets/img/banner/2021-10-27/2.jpg', '#', 1, 0, 405, 535);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(24, 15, 'We Have Excellent Of', 'Quality Pizza', 'assets/img/banner/2021-10-27/l.png', 'http://localhost/bhojon_2.7', 1, 0, 315, 226);
INSERT INTO `tbl_slider` (`slid`, `Sltypeid`, `title`, `subtitle`, `image`, `slink`, `status`, `delation_status`, `width`, `height`) VALUES(25, 14, 'reservation', 'reservation', 'assets/img/banner/2021-10-27/r.jpg', '#', 1, 0, 536, 535);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slider_type`
--

DROP TABLE IF EXISTS `tbl_slider_type`;
CREATE TABLE IF NOT EXISTS `tbl_slider_type` (
  `stype_id` int(11) NOT NULL AUTO_INCREMENT,
  `STypeName` varchar(255) DEFAULT NULL,
  `delation_status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_slider_type`
--

INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(1, 'Home Top Slider', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(2, 'Home our story', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(3, 'Home our menu', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(4, 'Menu Page right Banner', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(5, 'Classic theme Home story', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(6, 'Classic theme Home reservation', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(7, 'Classic theme Home gallery', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(8, 'Menu Page Offer Banner Right', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(9, 'Cart Page Offer Banner Right', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(10, 'Contact Us', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(11, 'Home Modern Top Offer', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(13, 'Modern theme About', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(14, 'Modern theme reservation', 0);
INSERT INTO `tbl_slider_type` (`stype_id`, `STypeName`, `delation_status`) VALUES(15, 'Modern Theme about Middle', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sociallink`
--

DROP TABLE IF EXISTS `tbl_sociallink`;
CREATE TABLE IF NOT EXISTS `tbl_sociallink` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `socialurl` text DEFAULT NULL,
  `icon` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_sociallink`
--

INSERT INTO `tbl_sociallink` (`sid`, `title`, `socialurl`, `icon`, `status`) VALUES(1, 'Facebook', 'https://www.facebook.com', 'fab fa-facebook', 1);
INSERT INTO `tbl_sociallink` (`sid`, `title`, `socialurl`, `icon`, `status`) VALUES(2, 'Twitter', 'https://www.twitter.com', 'fab fa-twitter', 1);
INSERT INTO `tbl_sociallink` (`sid`, `title`, `socialurl`, `icon`, `status`) VALUES(3, 'Google Plus', 'https://plus.google.com', 'fab fa-google-plus', 1);
INSERT INTO `tbl_sociallink` (`sid`, `title`, `socialurl`, `icon`, `status`) VALUES(4, 'Pinterest', 'https://www.pinterest.com/', 'fab fa-pinterest', 1);
INSERT INTO `tbl_sociallink` (`sid`, `title`, `socialurl`, `icon`, `status`) VALUES(6, 'Linkedin', 'https://linkedin.com', 'fab fa-linkedin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_soundsetting`
--

DROP TABLE IF EXISTS `tbl_soundsetting`;
CREATE TABLE IF NOT EXISTS `tbl_soundsetting` (
  `soundid` int(11) NOT NULL AUTO_INCREMENT,
  `nofitysound` text DEFAULT NULL,
  `addtocartsound` text DEFAULT NULL,
  PRIMARY KEY (`soundid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_soundsetting`
--

INSERT INTO `tbl_soundsetting` (`soundid`, `nofitysound`, `addtocartsound`) VALUES(1, 'assets/2022-09-07/b.mp3', 'h');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_state`
--

DROP TABLE IF EXISTS `tbl_state`;
CREATE TABLE IF NOT EXISTS `tbl_state` (
  `stateid` int(11) NOT NULL AUTO_INCREMENT,
  `countryid` int(11) NOT NULL,
  `statename` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`stateid`),
  KEY `countryid` (`countryid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_state`
--

INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(1, 2, 'Alabama', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(2, 2, 'Alaska', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(3, 2, 'Arizona', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(4, 2, 'Arkansas', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(5, 2, 'California', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(6, 2, 'Florida', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(7, 2, 'New Mexico', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(8, 2, 'New York', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(9, 2, 'Oklahoma', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(10, 2, 'Texas', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(11, 2, 'Virginia', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(12, 1, 'Dhaka', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(13, 1, 'Chittagong', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(14, 1, 'Rajshahi', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(15, 1, 'Khulna', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(16, 1, 'Sylhet', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(17, 1, 'Barishal', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(18, 1, 'Rangpur', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(19, 1, 'Mymensingh', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(20, 4, 'West Bengal', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(21, 4, 'Uttar Pradesh', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(22, 4, 'Tripura', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(23, 4, 'Telangana', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(24, 4, 'Tamil Nadu', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(25, 4, 'Sikkim', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(26, 4, 'Rajasthan', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(27, 4, 'Punjab', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(28, 4, 'Odisha', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(29, 4, 'Nagaland', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(30, 4, 'Kerala', 1);
INSERT INTO `tbl_state` (`stateid`, `countryid`, `statename`, `status`) VALUES(31, 4, 'Haryana', 1);

-- --------------------------------------------------------

--
-- Table structure for table `acc_subcode`
--

DROP TABLE IF EXISTS `acc_subcode`;
CREATE TABLE IF NOT EXISTS `acc_subcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `subTypeID` varchar(20) NOT NULL,
  `refCode` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `subTypeID` (`subTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `acc_subcode`
--

INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(1, 'Walkin', '3', '1');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(2, 'Jamil', '3', '34');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(3, 'Kabir khan', '3', '36');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(4, 'jamilr', '3', '38');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(5, 'shakil', '3', '43');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(6, 'ainal ', '3', '56');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(7, 'kamal Hassan', '3', '59');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(8, 'kamal Hassan', '3', '60');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(9, 'kamal Hassan', '3', '61');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(10, 'kamal Hassan', '3', '62');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(11, 'kamal Hassan', '3', '63');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(12, 'kamal hassan', '3', '64');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(13, 'Kamal  hassan', '3', '65');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(14, 'kamal Hassan', '3', '66');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(15, 'kamal Hassan', '3', '67');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(16, 'ainal2', '3', '96');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(17, 'shakil', '3', '110');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(18, 'shakilf', '3', '112');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(19, 'chairman sir', '3', '113');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(20, 'chairman sir', '3', '114');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(21, 'chairman sir', '3', '115');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(22, 'chairman sir', '3', '116');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(23, 'chairman sir', '3', '117');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(24, 'chairman sir', '3', '118');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(25, 'Ainal', '3', '120');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(26, 'jamildasd', '3', '121');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(27, 'jamildasd gt', '3', '122');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(28, 'Jamil', '3', '123');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(29, 'jamildasd', '3', '124');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(30, 'Saiful', '3', '125');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(31, 'ainal1haque@gmail.com', '3', '126');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(32, 'Test Third party', '3', '127');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(33, 'Test123', '3', '128');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(34, 'test345', '3', '129');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(35, 'Newtest', '3', '130');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(36, 'Test Third', '3', '131');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(37, 'Third pP', '3', '132');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(38, 'ABC', '3', '133');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(39, 'Test Main', '3', '134');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(40, 'Name Terminal', '3', '135');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(41, 'Harriet Wells', '3', '136');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(42, 'Risa Mcguire', '3', '137');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(43, 'Samson Wilcox', '3', '138');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(44, 'Acton Tate', '3', '139');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(45, 'Walk-In-Customer', '3', '140');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(46, 'Mozahidul Islam', '3', '141');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(47, 'Faridul Islam', '3', '142');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(48, 'Joshua Winters', '3', '143');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(49, 'Saifullah', '3', '144');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(50, 'Rakib Hossain', '3', '145');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(51, 'jsdfjsdfj', '3', '146');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(52, 'Dieter Frost', '3', '147');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(53, 'Quentin Lloyd', '3', '148');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(54, 'Rooney Gilmore', '3', '149');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(55, 'Lynn Ayala', '3', '150');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(56, 'Byron Briggs', '3', '151');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(57, 'Kimberley Weaver', '3', '152');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(58, 'Test Customer', '3', '153');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(59, 'Margaret Jimenez', '3', '154');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(60, 'Kerry Kline', '3', '155');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(61, 'Glenna Walton', '3', '156');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(62, 'Abcd', '3', '157');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(63, 'Alex Bayla', '3', '158');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(64, 'Zian Hesn', '3', '159');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(65, 'Mkt s', '3', '209');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(66, 'Maruf', '3', '210');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(67, 'sohag', '3', '211');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(68, 'mihor', '3', '212');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(69, 'Milton vai', '3', '213');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(70, 'Ainal', '3', '214');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(71, 'Kamal', '3', '215');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(72, 'Jamil', '3', '216');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(73, 'Jamil', '3', '217');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(74, 'Bdtask test', '3', '218');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(75, 'Test re', '3', '219');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(76, 'Test 2 res', '3', '220');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(77, 'ffsdf', '3', '221');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(78, 'test bbok', '3', '222');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(79, 'Test', '3', '223');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(80, 'Babul', '3', '224');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(81, 'Supplier_1', '4', '1');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(82, 'Lokman', '4', '2');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(83, 'Shakil', '4', '3');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(84, 'Jhon', '4', '4');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(85, 'Al-amin', '4', '6');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(86, 'jahangir Ahmad', '2', '162');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(87, 'Hm Isahaq', '2', '165');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(88, 'Ainal Haque', '2', '166');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(89, 'Manik  Hassan', '2', '168');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(90, 'Kitchen1 ', '2', '177');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(91, 'Food Panda', 'da', '1');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(92, 'pathao', 'da', '2');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(93, 'Hungrynaki', 'da', '3');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(94, 'Foodmart', 'da', '4');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(95, 'FoodDelivary2', 'da', '6');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(96, 'FoodDelivary3', 'da', '7');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(97, 'FoodDelivery', 'da', '8');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(98, 'New Delivery', 'da', '9');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(99, 'Nexus Terminal', 'CTA', '1');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(100, 'Brac Bank Terminal', 'CTA', '2');
INSERT INTO `acc_subcode` (`id`, `name`, `subTypeID`, `refCode`) VALUES(101, 'Visa-Master Terminal', 'CTA', '3');

-- --------------------------------------------------------

--
-- Table structure for table `acc_subtype`
--

DROP TABLE IF EXISTS `acc_subtype`;
CREATE TABLE IF NOT EXISTS `acc_subtype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `isSystem` int(11) NOT NULL DEFAULT 1,
  `code` varchar(50) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) NOT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `acc_subtype`
--

INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(1, 'None', 1, '1', 2, '2023-05-30 03:57:39', 2, '2023-04-10 04:41:36');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(2, 'Employee', 1, '2', 2, '2023-05-30 03:57:43', 2, '2023-04-10 04:41:28');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(3, 'Customer', 1, '3', 2, '2023-05-30 03:57:46', 2, '2023-04-10 04:41:21');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(4, 'Supplier', 1, '4', 2, '2023-05-30 03:57:49', 2, '2023-04-10 04:41:13');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(5, 'CARS', 0, '5', 2, '2023-05-30 03:57:53', 2, '2023-04-10 04:44:20');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(6, 'Delivery Agent', 1, 'da', 2, '2023-05-08 23:31:45', 2, '2023-05-08 23:31:45');
INSERT INTO `acc_subtype` (`id`, `name`, `isSystem`, `code`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(8, 'Card Terminal Agent', 1, 'CTA', 2, '2023-05-30 03:44:46', 2, '2023-05-30 03:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tablefloor`
--

DROP TABLE IF EXISTS `tbl_tablefloor`;
CREATE TABLE IF NOT EXISTS `tbl_tablefloor` (
  `tbfloorid` int(11) NOT NULL AUTO_INCREMENT,
  `floorName` varchar(100) NOT NULL,
  PRIMARY KEY (`tbfloorid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_tablefloor`
--

INSERT INTO `tbl_tablefloor` (`tbfloorid`, `floorName`) VALUES(1, 'First Floor');
INSERT INTO `tbl_tablefloor` (`tbfloorid`, `floorName`) VALUES(2, 'VIP Floor');
INSERT INTO `tbl_tablefloor` (`tbfloorid`, `floorName`) VALUES(3, 'Second Floor');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tax`
--

DROP TABLE IF EXISTS `tbl_tax`;
CREATE TABLE IF NOT EXISTS `tbl_tax` (
  `taxsettings` int(11) NOT NULL AUTO_INCREMENT,
  `tax` int(11) NOT NULL DEFAULT 0 COMMENT '1=show,0=hide',
  PRIMARY KEY (`taxsettings`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_tax`
--

INSERT INTO `tbl_tax` (`taxsettings`, `tax`) VALUES(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_thirdparty_customer`
--

DROP TABLE IF EXISTS `tbl_thirdparty_customer`;
CREATE TABLE IF NOT EXISTS `tbl_thirdparty_customer` (
  `companyId` int(11) NOT NULL AUTO_INCREMENT,
  `companycode` bigint(20) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `address` text DEFAULT NULL,
  `commision` decimal(10,2) DEFAULT 0.00,
  `mainbrcode` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`companyId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_thirdparty_customer`
--

INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(1, 18, 'Food Panda', 'Dhaka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(2, 17, 'pathao', 'Dhaka', 8.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(3, 16, 'Hungrynaki', 'Dhaka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(4, 15, 'Foodmart', 'Dhaka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(6, 15, 'FoodDelivary2', 'Dhka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(7, 15, 'FoodDelivary3', 'Dhka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(8, 15, 'FoodDelivery', 'Dhaka', 5.00, NULL);
INSERT INTO `tbl_thirdparty_customer` (`companyId`, `companycode`, `company_name`, `address`, `commision`, `mainbrcode`) VALUES(9, 15, 'New Delivery', 'Adfdsfwerv', 34.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tips_management`
--

DROP TABLE IF EXISTS `tbl_tips_management`;
CREATE TABLE IF NOT EXISTS `tbl_tips_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waiter_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_token`
--

DROP TABLE IF EXISTS `tbl_token`;
CREATE TABLE IF NOT EXISTS `tbl_token` (
  `tokenid` int(11) NOT NULL AUTO_INCREMENT,
  `tokencode` varchar(50) NOT NULL,
  `tokenrate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tokenstartdate` date NOT NULL,
  `tokenendate` date NOT NULL,
  `tokenstatus` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`tokenid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tokenprintmanage`
--

DROP TABLE IF EXISTS `tbl_tokenprintmanage`;
CREATE TABLE IF NOT EXISTS `tbl_tokenprintmanage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `itemid` int(11) NOT NULL,
  `varientid` int(11) NOT NULL,
  `addonsid` varchar(50) DEFAULT NULL,
  `addonsqty` varchar(50) DEFAULT NULL,
  `qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `prevqty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `status` int(11) NOT NULL DEFAULT 0,
  `isdelete` int(11) NOT NULL DEFAULT 0,
  `add_qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `del_qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `print_qty` decimal(19,3) NOT NULL DEFAULT 0.000,
  `notes` text DEFAULT NULL,
  `printer_token_id` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_toppingassign`
--

DROP TABLE IF EXISTS `tbl_toppingassign`;
CREATE TABLE IF NOT EXISTS `tbl_toppingassign` (
  `tpassignid` int(11) NOT NULL AUTO_INCREMENT,
  `tpassignCode` varchar(50) DEFAULT NULL,
  `menuid` int(11) NOT NULL,
  `tptitle` varchar(200) NOT NULL,
  `maxoption` int(11) NOT NULL DEFAULT 0,
  `isposition` int(11) NOT NULL DEFAULT 0,
  `is_paid` int(11) NOT NULL DEFAULT 0 COMMENT '1=paid,0=non paid',
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  PRIMARY KEY (`tpassignid`),
  KEY `menuid` (`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trams_condition`
--

DROP TABLE IF EXISTS `tbl_trams_condition`;
CREATE TABLE IF NOT EXISTS `tbl_trams_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `terms_cond` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_updateitems`
--

DROP TABLE IF EXISTS `tbl_updateitems`;
CREATE TABLE IF NOT EXISTS `tbl_updateitems` (
  `updateid` int(11) NOT NULL AUTO_INCREMENT,
  `ordid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `qty` decimal(10,3) NOT NULL DEFAULT 0.000,
  `adonsqty` varchar(50) DEFAULT NULL,
  `addonsid` varchar(50) DEFAULT NULL,
  `varientid` int(11) NOT NULL,
  `addonsuid` int(11) DEFAULT NULL,
  `isupdate` varchar(5) DEFAULT NULL,
  `insertdate` date DEFAULT '0000-00-00',
  PRIMARY KEY (`updateid`),
  KEY `ordid` (`ordid`),
  KEY `menuid` (`menuid`),
  KEY `varientid` (`varientid`),
  KEY `addonsuid` (`addonsuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_updateitemssunmi`
--

DROP TABLE IF EXISTS `tbl_updateitemssunmi`;
CREATE TABLE IF NOT EXISTS `tbl_updateitemssunmi` (
  `updateid` int(11) NOT NULL AUTO_INCREMENT,
  `ordid` int(11) NOT NULL,
  `menuid` int(11) NOT NULL,
  `qty` decimal(10,3) NOT NULL DEFAULT 0.000,
  `adonsqty` varchar(50) DEFAULT NULL,
  `addonsid` varchar(50) DEFAULT NULL,
  `varientid` int(11) NOT NULL,
  `addonsuid` int(11) DEFAULT NULL,
  `isupdate` varchar(5) DEFAULT NULL,
  `insertdate` date DEFAULT '0000-00-00',
  PRIMARY KEY (`updateid`),
  KEY `ordid` (`ordid`),
  KEY `menuid` (`menuid`),
  KEY `varientid` (`varientid`),
  KEY `addonsuid` (`addonsuid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_version_checker`
--

DROP TABLE IF EXISTS `tbl_version_checker`;
CREATE TABLE IF NOT EXISTS `tbl_version_checker` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(10) DEFAULT NULL,
  `disable` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_version_checker`
--

INSERT INTO `tbl_version_checker` (`vid`, `version`, `disable`) VALUES(1, '2.8', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vouchar`
--

DROP TABLE IF EXISTS `tbl_vouchar`;
CREATE TABLE IF NOT EXISTS `tbl_vouchar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucherheadid` int(11) NOT NULL,
  `HeadCode` int(11) NOT NULL,
  `Debit` decimal(19,3) NOT NULL DEFAULT 0.000,
  `Creadit` decimal(19,3) NOT NULL DEFAULT 0.000,
  `RevarseCode` int(11) NOT NULL,
  `subtypeID` int(11) DEFAULT NULL,
  `subCode` int(11) DEFAULT NULL,
  `LaserComments` varchar(255) NOT NULL,
  `chequeno` varchar(50) DEFAULT NULL,
  `chequeDate` date DEFAULT NULL,
  `ishonour` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subtypeID` (`subtypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_voucharhead`
--

DROP TABLE IF EXISTS `tbl_voucharhead`;
CREATE TABLE IF NOT EXISTS `tbl_voucharhead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Vno` int(11) NOT NULL,
  `vNoMainB` varchar(255) DEFAULT NULL,
  `Vdate` date NOT NULL DEFAULT '1970-01-01',
  `companyid` int(11) DEFAULT 0,
  `BranchId` int(11) DEFAULT 0,
  `Remarks` text NOT NULL,
  `createdby` varchar(100) NOT NULL,
  `CreatedDate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `updatedBy` varchar(100) NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `isapprove` bit(1) NOT NULL DEFAULT b'0',
  `Approvedby` varchar(100) DEFAULT NULL,
  `approvedate` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `refno` varchar(100) DEFAULT NULL,
  `voucharType` int(11) NOT NULL,
  `fin_yearid` int(11) NOT NULL,
  `isyearClosed` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `companyid` (`companyid`),
  KEY `BranchId` (`BranchId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vouchartype`
--

DROP TABLE IF EXISTS `tbl_vouchartype`;
CREATE TABLE IF NOT EXISTS `tbl_vouchartype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `PrefixCode` varchar(5) NOT NULL,
  `isauto` bit(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_vouchartype`
--

INSERT INTO `tbl_vouchartype` (`id`, `name`, `PrefixCode`, `isauto`) VALUES(1, 'Debit Vouchar', 'DV_', b'1');
INSERT INTO `tbl_vouchartype` (`id`, `name`, `PrefixCode`, `isauto`) VALUES(2, 'Credit Vouchar', 'CV_', b'1');
INSERT INTO `tbl_vouchartype` (`id`, `name`, `PrefixCode`, `isauto`) VALUES(3, 'Journal Vouchar', 'JV_', b'1');
INSERT INTO `tbl_vouchartype` (`id`, `name`, `PrefixCode`, `isauto`) VALUES(4, 'Contra Vouchar', 'TV_', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_waiterappcart`
--

DROP TABLE IF EXISTS `tbl_waiterappcart`;
CREATE TABLE IF NOT EXISTS `tbl_waiterappcart` (
  `weaterappid` int(11) NOT NULL AUTO_INCREMENT,
  `waiterid` int(11) NOT NULL,
  `alladdOnsName` varchar(255) DEFAULT NULL,
  `total_addonsprice` decimal(10,2) DEFAULT 0.00,
  `totaladdons` int(11) DEFAULT NULL,
  `addons_name` varchar(255) DEFAULT NULL,
  `addons_id` int(11) DEFAULT NULL,
  `addons_price` double(10,2) DEFAULT 0.00,
  `addonsQty` int(11) DEFAULT NULL,
  `component` varchar(255) DEFAULT NULL,
  `destcription` text DEFAULT NULL,
  `itemnotes` varchar(255) DEFAULT NULL,
  `offerIsavailable` int(11) DEFAULT 0,
  `offerstartdate` date DEFAULT '0000-00-00',
  `OffersRate` int(11) DEFAULT NULL,
  `offerendate` date DEFAULT '0000-00-00',
  `price` decimal(10,2) DEFAULT 0.00,
  `ProductsID` int(11) NOT NULL,
  `ProductImage` varchar(255) DEFAULT NULL,
  `ProductName` varchar(255) NOT NULL,
  `productvat` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `variantName` varchar(255) DEFAULT NULL,
  `variantid` int(11) NOT NULL,
  `orderid` int(11) DEFAULT NULL,
  PRIMARY KEY (`weaterappid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_widget`
--

DROP TABLE IF EXISTS `tbl_widget`;
CREATE TABLE IF NOT EXISTS `tbl_widget` (
  `widgetid` int(11) NOT NULL AUTO_INCREMENT,
  `widget_name` varchar(100) NOT NULL,
  `widget_title` varchar(150) DEFAULT NULL,
  `widget_desc` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`widgetid`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_widget`
--

INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(1, 'Footer left', '', '<p class=\"text-justify\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(2, 'footermiddle', 'Available On', '<p><strong>Monday - Wednesday</strong> 10:00 AM - 7:00 PM</p>\r\n<p><strong>Thursday - Friday</strong> 10:00 AM - 11:00 PM</p>\r\n<p><strong>Saturday</strong> 12:00 PM - 6:00 PM</p>\r\n<p><strong>Sunday</strong> Off</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(3, 'Footer right', 'Contact Us', '<p>356, Mannan Plaza ( 4th Floar ) Khilkhet Dhaka</p>\r\n<p><a href=\"../../hungry\">support@bdtask.com</a></p>\r\n<p><a href=\"../../hungry\">+88 01715 222 333</a></p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(4, 'Our Store', 'Our Store', '<address>123 Suspendis matti,&nbsp;<br />Visaosang Building VST District,&nbsp;<br />NY Accums, North American</address>\r\n<p><a class=\"d-block\" href=\"http://soft9.bdtask.com/hungry-v1/\">0123-456-78910</a><a class=\"d-block\" href=\"http://soft9.bdtask.com/hungry-v1/\">support@domain.com</a></p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(6, 'Reservation', 'BOOK YOUR TABLE', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(7, 'Our Menu text', 'Our Menu ', '<p>Rosa is a restaurant, bar and coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides of the building, overlooking the market and a bustling London inteon.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(8, 'Specials', 'FOOD MENU', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(9, 'story text', 'OUR STORY', '<p>Rosa is a restaurant, bar and coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides of the building, overlooking the market and a bustling London inteon.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(10, 'Professional', 'OUR EXPERT CHEFS', '', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(11, 'Need Help Booking?', 'Need Help Booking?', '<p class=\"mb-2\">Just call our customer services at any time, we are waiting 24 hours to recieve your calls.</p>\r\n<p><a href=\"#\">+880 1712 123 123</a></p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(12, 'Privacy', 'Privacy Policy', '<h2>What is Lorem Ipsum</h2>\r\n<p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n<h3>Contacting us :</h3>\r\n<p>If you have any questions about this Privacy Policy, the practices of this site, or your dealings with this site, please contact us.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(13, 'termscondition', 'Terms of Use', '<h3>Web browser cookies</h3>\r\n<p>Our Site may use cacheÃƒâ€šÃ‚Â and \"cookies\" to enhance User experience. User\'s web browser places cookies on their hard drive for record-keeping purposes and sometimes to track information about them. User may choose to set their web browser to refuse cookies, or to alert you when cookies are being sent. If they do so, note that some parts of the Site may not function properly.</p>\r\n<h3>How we use collected information bdtask may collect and use Users personal information for the following purposes:</h3>\r\n<p>To run and operate our Site We may need your information display content on the Site correctly. To improve customer service Information you provide helps us respond to your customer service requests and support needs more efficiently. To personalize user experience We may use information in the aggregate to understand how our Users as a group use the services and resources provided on our Site. To improve our Site We may use feedback you provide to improve our products and services. To run a promotion, contest, survey or other Site feature To send Users information they agreed to receive about topics we think will be of interest to them. To send periodic emails We may use the email address to send User information and updates pertaining to their order. It may also be used to respond to their inquiries, questions, and/or other requests.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(14, 'map', 'Google Map', '<p>&lt;iframe style=\"border: 0;\" src=\"https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14599.578237069936!2d90.3654215!3d23.8223482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1583411739085!5m2!1sen!2sbd\" width=\"300\" height=\"150\" frameborder=\"0\" allowfullscreen=\"allowfullscreen\"&gt;&lt;/iframe&gt;</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(15, 'carousal1', 'carousal', '<p>show</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(16, 'TASTY MENU TODAY', 'CHEF SELECTION', '', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(17, 'FOOD HISTORY', 'OUR AWESOME STREET', '<p class=\"mb-4\">Thing lesser replenish evening called void a sea blessed meat fourth called moveth place behold night own night third in they abundant and don\'t you\'re the upon fruit. Divided open divided appear also saw may fill. whales seed creepeth. Open lessegether he also morning land i saw Man</p>\r\n<p><a class=\"simple_btn\" href=\"#\">Our Story</a></p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(21, 'Our Gallery', 'Restaurant Photo Gallery', '', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(22, 'subfooter', '', '', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(23, 'Get In Touch', 'contact', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(24, 'Refund Policies', 'Refund Policies', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(25, 'RESTORA POS HISTORY', 'Serving pizzas by the-slice since 2021', '<p>Restorapos is a restaurant, bar and coffee roastery located on a busy corner site in Farringdon’s Exmouth Market. With glazed frontage on two sides of the building, overlooking the market and a bustling London inteon.</p>', 1);
INSERT INTO `tbl_widget` (`widgetid`, `widget_name`, `widget_title`, `widget_desc`, `status`) VALUES(26, 'VISIT OUR', 'Restaurant', '<p class=\"mb-0\">Restorapos is a restaurant, bar and coffee roastery located on a busy corner site in Farringdon’s Exmouth Market. With glazed frontage on two sides of the building, overlooking the market and a bustling London inteon.</p>\r\n<p><a class=\"d-block fw-600 mt-4 text-green\" href=\"#\">Get Locations</a></p>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `themeid` int(11) NOT NULL AUTO_INCREMENT,
  `themename` varchar(100) NOT NULL,
  `theme_thumb` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '0=inactive,1=active',
  `activedate` date DEFAULT NULL,
  PRIMARY KEY (`themeid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`themeid`, `themename`, `theme_thumb`, `status`, `activedate`) VALUES(1, 'defaults', NULL, 0, '2020-11-19');
INSERT INTO `themes` (`themeid`, `themename`, `theme_thumb`, `status`, `activedate`) VALUES(3, 'classic', NULL, 0, NULL);
INSERT INTO `themes` (`themeid`, `themename`, `theme_thumb`, `status`, `activedate`) VALUES(4, 'modern', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `top_menu`
--

DROP TABLE IF EXISTS `top_menu`;
CREATE TABLE IF NOT EXISTS `top_menu` (
  `menuid` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) NOT NULL,
  `menu_slug` varchar(70) NOT NULL,
  `parentid` int(11) NOT NULL,
  `entrydate` date NOT NULL,
  `isactive` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`menuid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `top_menu`
--

INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(1, 'Home', 'home', 0, '2021-09-19', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(2, 'Reservation', 'reservation', 0, '2019-02-20', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(3, 'Menu', 'menu', 0, '2021-10-18', 0);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(4, 'About Us', 'about', 0, '2019-11-25', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(5, 'Contact Us', 'contact', 0, '2019-01-26', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(6, 'Pages', 'pages', 0, '2019-11-28', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(7, 'Cart', 'cart', 6, '2019-01-26', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(8, 'Details', 'details', 7, '2020-01-15', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(9, 'Logout', 'hungry/logout', 6, '2019-02-03', 1);
INSERT INTO `top_menu` (`menuid`, `menu_name`, `menu_slug`, `parentid`, `entrydate`, `isactive`) VALUES(10, 'My Profile', 'myprofile', 0, '2019-10-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measurement`
--

DROP TABLE IF EXISTS `unit_of_measurement`;
CREATE TABLE IF NOT EXISTS `unit_of_measurement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uom_name` varchar(200) NOT NULL,
  `um_code` varchar(200) DEFAULT NULL,
  `uom_short_code` varchar(10) NOT NULL,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  `is_active` tinyint(1) NOT NULL,
  `ismainbrunit` int(11) NOT NULL DEFAULT 0 COMMENT '1=from master branch and 0=default sub branch',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usedcoupon`
--

DROP TABLE IF EXISTS `usedcoupon`;
CREATE TABLE IF NOT EXISTS `usedcoupon` (
  `cusedid` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `couponcode` varchar(100) NOT NULL,
  `couponrate` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`cusedid`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `waiter_kitchenToken` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `password_reset_token` varchar(20) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `ip_address` varchar(14) DEFAULT NULL,
  `counter` int(11) DEFAULT NULL,
  `skeys` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `is_admin` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `about`, `waiter_kitchenToken`, `email`, `password`, `password_reset_token`, `image`, `last_login`, `last_logout`, `ip_address`, `counter`, `skeys`, `status`, `is_admin`) VALUES(1, 'Jhon', 'Doe', NULL, NULL, 'super@admin.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, NULL, NULL, NULL, NULL, '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `variant`
--

DROP TABLE IF EXISTS `variant`;
CREATE TABLE IF NOT EXISTS `variant` (
  `variantid` int(11) NOT NULL AUTO_INCREMENT,
  `VariantCode` varchar(200) DEFAULT NULL,
  `menuid` int(11) NOT NULL,
  `variantName` varchar(120) NOT NULL,
  `price` decimal(10,3) NOT NULL DEFAULT 0.000,
  `is_deleted` int(11) DEFAULT 0 COMMENT '0=nodelete,1=softdelete',
  PRIMARY KEY (`variantid`),
  KEY `menuid` (`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vattype_tbl`
--

DROP TABLE IF EXISTS `vattype_tbl`;
CREATE TABLE IF NOT EXISTS `vattype_tbl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vattype_tbl`
--

INSERT INTO `vattype_tbl` (`id`, `name`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(2, 'Amount', 2, '2023-04-10 04:21:23', 2, '2023-04-10 04:21:23');
INSERT INTO `vattype_tbl` (`id`, `name`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(3, 'Standard rated', 2, '2023-04-10 04:21:17', 2, '2023-04-10 04:21:17');
INSERT INTO `vattype_tbl` (`id`, `name`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(5, 'Zero rated', 2, '2023-04-10 04:21:46', 2, '2023-04-10 04:21:46');
INSERT INTO `vattype_tbl` (`id`, `name`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(6, 'Exempted', 2, '2023-04-10 04:21:39', 2, '2023-04-10 04:21:39');
INSERT INTO `vattype_tbl` (`id`, `name`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES(8, 'Imports subject to VAT paid at customer', 2, '2023-04-10 04:18:19', 2, '2023-04-10 04:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_holiday`
--

DROP TABLE IF EXISTS `weekly_holiday`;
CREATE TABLE IF NOT EXISTS `weekly_holiday` (
  `wk_id` int(11) NOT NULL AUTO_INCREMENT,
  `dayname` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`wk_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `weekly_holiday`
--

INSERT INTO `weekly_holiday` (`wk_id`, `dayname`) VALUES(1, 'Satarday,Sunday');
