insert into jo_settings_blocks values (16,'LBL_LOGS', 0);
update jo_tab_sequence set sequence = 16 where table_name = 'jo_settings_blocks_seq' ;

insert into jo_settings_field values (46,16,'LBL_PBXMANAGER', 'joicon-pbxmanager', 'LBL_PBXManager_DESCRIPTION','PBXManager/view/List',1,0,1);
insert into jo_settings_field values (47,16,'LBL_RECYCLEBIN', 'joicon-recyclebin', 'LBL_RecycleBin_DESCRIPTION','RecycleBin/view/List',1,0,1);
update jo_tab_sequence set sequence = 47 where table_name = 'jo_settings_field_seq';
update jo_settings_field set blockid=16 where name = 'LBL_LOGIN_HISTORY_DETAILS';


drop table jo_customerportal_fields;
drop table jo_customerportal_prefs;
drop table jo_customerportal_settings;
drop table jo_customerportal_tabs;
drop table jo_portal;

delete from jo_tab where name = 'Portal';
delete from jo_tab where name = 'CustomerPortal';

CREATE TABLE `jo_user_menu_arrangement` (
  `um_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `default_sections` text COLLATE utf8_unicode_ci,
  `main_menu` text COLLATE utf8_unicode_ci,
  `module_apps` text COLLATE utf8_unicode_ci,
  `notifications` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`um_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
insert into jo_tab_sequence values (120, 'jo_user_menu_arrangement_seq', 0);
