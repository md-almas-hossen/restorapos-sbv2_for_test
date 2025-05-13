INSERT INTO `tbl_tax` (`taxsettings`, `tax`) VALUES(1, 1);
INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'tex_setting', 'Tax Setting'),(NULL, 'tex_enable', 'Tax setting Enable'),(NULL, 'number_of_tax', 'Number of tax'),(NULL, 'default_value', 'Default value'),(NULL, 'tax_name', 'Reg No'),(NULL, 'tax_name', 'Tax Name'),(NULL, 'update_tax_settings', 'If you Update tax settings ,All of your previous tax record will be destroy.You Will Need to set tax product wise and Adones wise');
INSERT INTO `acc_coa` (`HeadCode`, `HeadName`, `PHeadName`, `HeadLevel`, `IsActive`, `IsTransaction`, `IsGL`, `HeadType`, `IsBudget`, `IsDepreciation`, `DepreciationRate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`) VALUES ('505', 'tex', 'Liabilities', '1', '1', '1', '0', 'L', '0', '0', '0.00', '3', '2020-11-24 14:21:58', '', '0000-00-00 00:00:00');

INSERT INTO `language` (`id`, `phrase`, `english`) VALUES (NULL, 'taxsetting', 'TAX Setting');
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) VALUES ('taxsetting', 'taxsettingback', 'taxsetting', '0', '0', '2', '2020-12-03 00:00:00');
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'tex_setting', 'showtaxsetting', 'taxsetting', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'taxsetting';
INSERT INTO sec_menu_item (menu_title, page_url, module, parent_menu, is_report, createby, createdate) SELECT 'tex_enable', 'taxsetting', 'taxsetting', sec_menu_item.menu_id, '0', '2', '2020-12-03 00:00:00' FROM sec_menu_item WHERE sec_menu_item.menu_title = 'taxsetting';


