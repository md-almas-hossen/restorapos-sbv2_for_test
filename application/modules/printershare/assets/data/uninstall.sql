DELETE FROM sec_menu_item WHERE sec_menu_item.module = 'printershare';
DELETE FROM `language` WHERE `language`.`phrase` = 'printerport';
DELETE FROM `language` WHERE `language`.`phrase` = 'pintersetting';
DELETE FROM `language` WHERE `language`.`phrase` = 'invoiceprinter';
DELETE FROM `language` WHERE `language`.`phrase` = 'autop';
DELETE FROM `language` WHERE `language`.`phrase` = 'manual';
DELETE FROM `language` WHERE `language`.`phrase` = 'automanual';
DELETE FROM `sec_menu_item` WHERE `sec_menu_item`.`module` = 'printershare';
UPDATE `setting` SET `socketenable` = '0' WHERE `setting`.`id` = 2;


