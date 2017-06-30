<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';

class EmailPlus extends Vtiger_CRMEntity {

	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') {
			// TODO Handle actions after this module is installed.
			global $adb;
			$moduleInstance = Vtiger_Module::getInstance('EmailPlus');
			$moduleInstance->addLink('HEADERSCRIPT', 'Check Server Details', 'layouts/v7/modules/EmailPlus/resources/checkServerInfo.js');
			$adb->pquery("CREATE TABLE `rc_server_details` (
						`user_id` int(20) NOT NULL,
						`name` varchar(255) DEFAULT NULL,
						`email` varchar(50) DEFAULT NULL,
						`password` varchar(100) DEFAULT NULL,
						`account_type` varchar(100) DEFAULT NULL,
						`port` int(10) DEFAULT NULL
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `users` (
						`user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`username` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						`mail_host` varchar(128) NOT NULL,
						`created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`last_login` datetime DEFAULT NULL,
						`failed_login` datetime DEFAULT NULL,
						`failed_login_counter` int(10) unsigned DEFAULT NULL,
						`language` varchar(5) DEFAULT NULL,
						`preferences` longtext,
						`vtiger_user_id` int(10) DEFAULT NULL,
						PRIMARY KEY (`user_id`),
						UNIQUE KEY `username` (`username`,`mail_host`)
						) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `contacts` (
						`contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`del` tinyint(1) NOT NULL DEFAULT '0',
						`name` varchar(128) NOT NULL DEFAULT '',
						`email` text NOT NULL,
						`firstname` varchar(128) NOT NULL DEFAULT '',
						`surname` varchar(128) NOT NULL DEFAULT '',
						`vcard` longtext,
						`words` text,
						`user_id` int(10) unsigned NOT NULL,
						PRIMARY KEY (`contact_id`),
						KEY `user_contacts_index` (`user_id`,`del`),
						CONSTRAINT `user_id_fk_contacts` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `contactgroups` (
						`contactgroup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`user_id` int(10) unsigned NOT NULL,
						`changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`del` tinyint(1) NOT NULL DEFAULT '0',
						`name` varchar(128) NOT NULL DEFAULT '',
						PRIMARY KEY (`contactgroup_id`),
						KEY `contactgroups_user_index` (`user_id`,`del`),
						CONSTRAINT `user_id_fk_contactgroups` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `contactgroupmembers` (
						`contactgroup_id` int(10) unsigned NOT NULL,
						`contact_id` int(10) unsigned NOT NULL,
						`created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						PRIMARY KEY (`contactgroup_id`,`contact_id`),
						KEY `contactgroupmembers_contact_index` (`contact_id`),
						CONSTRAINT `contactgroup_id_fk_contactgroups` FOREIGN KEY (`contactgroup_id`) REFERENCES `contactgroups` (`contactgroup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
						CONSTRAINT `contact_id_fk_contacts` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`contact_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=latin1",array());
			$adb->pquery("CREATE TABLE `identities` (
						`identity_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`user_id` int(10) unsigned NOT NULL,
						`changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`del` tinyint(1) NOT NULL DEFAULT '0',
						`standard` tinyint(1) NOT NULL DEFAULT '0',
						`name` varchar(128) NOT NULL,
						`organization` varchar(128) NOT NULL DEFAULT '',
						`email` varchar(128) NOT NULL,
						`reply-to` varchar(128) NOT NULL DEFAULT '',
						`bcc` varchar(128) NOT NULL DEFAULT '',
						`signature` longtext,
						`html_signature` tinyint(1) NOT NULL DEFAULT '0',
						PRIMARY KEY (`identity_id`),
						KEY `user_identities_index` (`user_id`,`del`),
						KEY `email_identities_index` (`email`,`del`),
						CONSTRAINT `user_id_fk_identities` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8",array());
			$adb->pquery('CREATE TABLE `dictionary` (
						`user_id` int(10) unsigned DEFAULT NULL,
						`language` varchar(5) NOT NULL,
						`data` longtext NOT NULL,
						UNIQUE KEY `uniqueness` (`user_id`,`language`),
						CONSTRAINT `user_id_fk_dictionary` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8',array());
			$adb->pquery("CREATE TABLE `searches` (
						`search_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`user_id` int(10) unsigned NOT NULL,
						`type` int(3) NOT NULL DEFAULT '0',
						`name` varchar(128) NOT NULL,
						`data` text,
						PRIMARY KEY (`search_id`),
						UNIQUE KEY `uniqueness` (`user_id`,`type`,`name`),
						CONSTRAINT `user_id_fk_searches` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `session` (
						`sess_id` varchar(128) NOT NULL,
						`created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`ip` varchar(40) NOT NULL,
						`vars` mediumtext NOT NULL,
						PRIMARY KEY (`sess_id`),
						KEY `changed_index` (`changed`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `system` (
						`name` varchar(64) NOT NULL,
						`value` mediumtext,
						PRIMARY KEY (`name`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8",array());
			$adb->pquery("CREATE TABLE `cache` (
						`user_id` int(10) unsigned NOT NULL,
						`cache_key` varchar(128) CHARACTER SET ascii NOT NULL,
						`created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`expires` datetime DEFAULT NULL,
						`data` longtext NOT NULL,
						KEY `expires_index` (`expires`),
						KEY `user_cache_index` (`user_id`,`cache_key`),
						CONSTRAINT `user_id_fk_cache` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
			$adb->pquery("CREATE TABLE `cache_index` (
						`user_id` int(10) unsigned NOT NULL,
						`mailbox` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						`expires` datetime DEFAULT NULL,
						`valid` tinyint(1) NOT NULL DEFAULT '0',
						`data` longtext NOT NULL,
						PRIMARY KEY (`user_id`,`mailbox`),
						KEY `expires_index` (`expires`),
						CONSTRAINT `user_id_fk_cache_index` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
			$adb->pquery("CREATE TABLE `cache_messages` (
						`user_id` int(10) unsigned NOT NULL,
						`mailbox` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						`uid` int(11) unsigned NOT NULL DEFAULT '0',
						`expires` datetime DEFAULT NULL,
						`data` longtext NOT NULL,
						`flags` int(11) NOT NULL DEFAULT '0',
						PRIMARY KEY (`user_id`,`mailbox`,`uid`),
						KEY `expires_index` (`expires`),
						CONSTRAINT `user_id_fk_cache_messages` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
			$adb->pquery("CREATE TABLE `cache_shared` (
						`cache_key` varchar(255) CHARACTER SET ascii NOT NULL,
						`created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
						`expires` datetime DEFAULT NULL,
						`data` longtext NOT NULL,
						KEY `expires_index` (`expires`),
						KEY `cache_key_index` (`cache_key`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
			$adb->pquery("CREATE TABLE `cache_thread` (
						`user_id` int(10) unsigned NOT NULL,
						`mailbox` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
						`expires` datetime DEFAULT NULL,
						`data` longtext NOT NULL,
						PRIMARY KEY (`user_id`,`mailbox`),
						KEY `expires_index` (`expires`),
						CONSTRAINT `user_id_fk_cache_thread` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
						) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
			$adb->pquery("CREATE TABLE `rc_settings` (
						`id` int(10) NOT NULL AUTO_INCREMENT,
						`meta_key` varchar(255) DEFAULT NULL,
						`meta_value` longtext,
						PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=latin1",array());
			$adb->pquery("CREATE TABLE `collected_contacts` (
				`contact_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
				`del` tinyint(1) NOT NULL DEFAULT '0',
				`name` varchar(128) NOT NULL DEFAULT '',
				`email` text NOT NULL,
				`firstname` varchar(128) NOT NULL DEFAULT '',
				`surname` varchar(128) NOT NULL DEFAULT '',
				`vcard` longtext,
				`words` text,
				`user_id` int(10) unsigned NOT NULL,
				PRIMARY KEY (`contact_id`),
				KEY `user_collected_contacts_index` (`user_id`,`del`),
				CONSTRAINT `user_id_fk_collected_contacts` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1", array());
			$adb->pquery('insert into rc_settings (meta_key, meta_value) values (?, ?)', array('module_version', '1.0'));
		} else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
                        $adb->pquery('delete from vtiger_links where linklabel = ?', array('Check Server Details'));
		} else if($eventType == 'module.enabled') {
                        $moduleInstance = Vtiger_Module::getInstance('EmailPlus');
                        $moduleInstance->addLink('HEADERSCRIPT', 'Check Server Details', 'layouts/v7/modules/EmailPlus/resources/checkServerInfo.js');
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
 	}
}
