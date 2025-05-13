INSERT INTO `payment_method` (`payment_method_id`, `payment_method`, `is_active`,`modulename`) VALUES ('19', 'Tappayment', '1','tappayment');
INSERT INTO `paymentsetup` (`setupid`, `paymentid`, `marchantid`, `password`, `email`, `currency`, `Islive`, `status`,`edit_url`) VALUES ('19', '19', 'sk_test_FE0sLjy28COSP57vibmMBAYa','sk_test_FE0sLjy28COSP57vibmMBAYa', NULL, 'AED', '0', '1','tappayment/tappayment/editpayment');

INSERT INTO `tbl_ledger` (`code`, `Name`, `NatureID`, `GroupID`, `Groupsubid`, `acctypeid`, `blanceID`, `destinationid`, `subType`, `IsActive`, `isstock`, `isfixedassetsch`, `noteNo`, `AssetsCode`, `depCode`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `iscashnature`, `isbanknature`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES('10101010121', 'Tap payment', 1, 3, 1, 1, 1, 2, 1, 1,0,0,NULL,NULL,NULL,0,0, 0.00,0, 1,'John Doe', NOW(), 'John Doe', '0000-00-00 00:00:00');


