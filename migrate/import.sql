-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: localhost    Database: joforce_sep23
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `user_id` int(19) NOT NULL,
  `cache_key` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `expires` datetime DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`user_id`),
  KEY `cache_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_cache` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_index`
--

DROP TABLE IF EXISTS `cache_index`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_index` (
  `user_id` int(19) NOT NULL,
  `mailbox` varchar(255) NOT NULL,
  `expires` datetime DEFAULT NULL,
  `valid` int(1) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`user_id`,`mailbox`),
  KEY `cache_index_expires_idx` (`expires`),
  CONSTRAINT `user_id_fk_cache_index` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_index`
--

LOCK TABLES `cache_index` WRITE;
/*!40000 ALTER TABLE `cache_index` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_index` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_messages`
--

DROP TABLE IF EXISTS `cache_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_messages` (
  `user_id` int(19) NOT NULL,
  `mailbox` varchar(255) NOT NULL,
  `uid` int(1) NOT NULL DEFAULT '0',
  `expires` datetime DEFAULT NULL,
  `data` text,
  `flags` int(11) DEFAULT '0',
  PRIMARY KEY (`user_id`,`mailbox`,`uid`),
  KEY `cache_messages_expires_idx` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_messages`
--

LOCK TABLES `cache_messages` WRITE;
/*!40000 ALTER TABLE `cache_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_thread`
--

DROP TABLE IF EXISTS `cache_thread`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_thread` (
  `user_id` int(19) NOT NULL,
  `mailbox` varchar(255) NOT NULL,
  `expires` datetime DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`user_id`,`mailbox`),
  KEY `cache_thread_expires_idx` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_thread`
--

LOCK TABLES `cache_thread` WRITE;
/*!40000 ALTER TABLE `cache_thread` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_thread` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collected_contacts`
--

DROP TABLE IF EXISTS `collected_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collected_contacts` (
  `contact_id` int(19) NOT NULL AUTO_INCREMENT,
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` int(1) DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `email` text,
  `firstname` varchar(200) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `vcard` text,
  `words` text,
  `user_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `collected_contacts_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_collected_contacts` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collected_contacts`
--

LOCK TABLES `collected_contacts` WRITE;
/*!40000 ALTER TABLE `collected_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `collected_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_privileges`
--
DROP TABLE IF EXISTS `jo_privileges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_privileges` (
  `privilegesid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11)  NOT NULL ,
  `user_privilege` text NULL,
  `sharing_privilege` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`privilegesid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `workflow_activatedonce`
--

LOCK TABLES `jo_privileges` WRITE;
/*!40000 ALTER TABLE `workflow_activatedonce` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflow_activatedonce` ENABLE KEYS */;
INSERT INTO `jo_privileges` VALUES (1,1,'{"is_admin":"1","current_user_role":null,"current_user_parent_role_seq":null,"current_user_profiles":null,"profileGlobalPermission":null,"profileTabsPermission":null,"profileActionPermission":null,"current_user_groups":null,"subordinate_roles":null,"parent_roles":null,"subordinate_roles_users":null,"user_info":{"user_name":"admin@faz3.com.tr","is_admin":"on","user_password":"$1$ad000000$hzXFXvL3XVlnUE\/X.1n9t\/","confirm_password":"$1$ad000000$hzXFXvL3XVlnUE\/X.1n9t\/","first_name":"","last_name":"Administrator","roleid":"H2","email1":"subbuserivces@gmail.com","status":"Active","activity_view":"This Week","lead_view":"Today","hour_format":"12","end_hour":"23:00","start_hour":"00:00","is_owner":"1","title":"","phone_work":"","department":"","phone_mobile":"","reports_to_id":"","phone_other":"","email2":"","phone_fax":"","secondaryemail":"","phone_home":"","date_format":"mm-dd-yyyy","signature":"","description":"","address_street":"","address_city":"","address_state":"","address_postalcode":"","address_country":"","accesskey":"k5C2wG1RUWmvAFJB","time_zone":"America\/Los_Angeles","currency_id":"1","currency_grouping_pattern":"123,456,789","currency_decimal_separator":".","currency_grouping_separator":",","currency_symbol_placement":"$1.0","imagename":"","internal_mailer":"1","theme":"alphagrey","language":"en_us","reminder_interval":"1 Minute","default_landing_page":"Home","default_dashboard_view":"1","phone_crm_extension":"","no_of_currency_decimals":"2","truncate_trailing_zeros":"1","dayoftheweek":"Sunday","callduration":"5","othereventduration":"5","calendarsharedtype":"public","default_record_view":"Summary","leftpanelhide":"0","rowheight":"","defaulteventstatus":"Planned","defaultactivitytype":"Call","hidecompletedevents":"0","defaultcalendarview":"MyCalendar","currency_name":"USA, Dollars","currency_code":"USD","currency_symbol":"$","conv_rate":"1.00000","record_id":"","record_module":"","id":"1"}}','','');
UNLOCK TABLES;
--
-- Table structure for table `workflow_activatedonce`
--
DROP TABLE IF EXISTS `workflow_activatedonce`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflow_activatedonce` (
  `workflow_id` int(11) NOT NULL,
  `entity_id` int(11) NOT NULL,
  PRIMARY KEY (`workflow_id`,`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_activatedonce`
--

LOCK TABLES `workflow_activatedonce` WRITE;
/*!40000 ALTER TABLE `workflow_activatedonce` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflow_activatedonce` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflow_tasktypes`
--

DROP TABLE IF EXISTS `workflow_tasktypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflow_tasktypes` (
  `id` int(11) NOT NULL,
  `tasktypename` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `classname` varchar(255) DEFAULT NULL,
  `classpath` varchar(255) DEFAULT NULL,
  `templatepath` varchar(255) DEFAULT NULL,
  `modules` varchar(500) DEFAULT NULL,
  `sourcemodule` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflow_tasktypes`
--

LOCK TABLES `workflow_tasktypes` WRITE;
/*!40000 ALTER TABLE `workflow_tasktypes` DISABLE KEYS */;
INSERT INTO `workflow_tasktypes` VALUES (1,'EmailTask','Send Mail','EmailTask','modules/Workflow/tasks/EmailTask.inc','workflow/taskforms/EmailTask.tpl','{\"include\":[],\"exclude\":[]}',''),(2,'EntityMethodTask','Invoke Custom Function','EntityMethodTask','modules/Workflow/tasks/EntityMethodTask.inc','workflow/taskforms/EntityMethodTask.tpl','{\"include\":[],\"exclude\":[]}',''),(3,'CreateTodoTask','Create Todo','CreateTodoTask','modules/Workflow/tasks/CreateTodoTask.inc','workflow/taskforms/CreateTodoTask.tpl','{\"include\":[\"Leads\",\"Accounts\",\"Potentials\",\"Contacts\",\"HelpDesk\",\"Campaigns\",\"Quotes\",\"PurchaseOrder\",\"SalesOrder\",\"Invoice\",\"Project\"],\"exclude\":[\"Calendar\",\"FAQ\",\"Events\"]}',''),(4,'CreateEventTask','Create Event','CreateEventTask','modules/Workflow/tasks/CreateEventTask.inc','workflow/taskforms/CreateEventTask.tpl','{\"include\":[\"Leads\",\"Accounts\",\"Potentials\",\"Contacts\",\"HelpDesk\",\"Campaigns\",\"Project\"],\"exclude\":[\"Calendar\",\"FAQ\",\"Events\"]}',''),(5,'UpdateFieldsTask','Update Fields','UpdateFieldsTask','modules/Workflow/tasks/UpdateFieldsTask.inc','workflow/taskforms/UpdateFieldsTask.tpl','{\"include\":[],\"exclude\":[]}',''),(6,'CreateEntityTask','Create Entity','CreateEntityTask','modules/Workflow/tasks/CreateEntityTask.inc','workflow/taskforms/CreateEntityTask.tpl','{\"include\":[],\"exclude\":[]}',''),(7,'SMSTask','SMS Task','SMSTask','modules/Workflow/tasks/SMSTask.inc','workflow/taskforms/SMSTask.tpl','{\"include\":[],\"exclude\":[]}','SMSNotifier');
/*!40000 ALTER TABLE `workflow_tasktypes` ENABLE KEYS */;
UNLOCK TABLES;

-- Table structure for table `workflows`
--

DROP TABLE IF EXISTS `workflows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflows` (
  `workflow_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `summary` varchar(400) NOT NULL,
  `test` text,
  `execution_condition` int(11) NOT NULL,
  `defaultworkflow` int(1) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `filtersavedinnew` int(1) DEFAULT NULL,
  `schtypeid` int(10) DEFAULT NULL,
  `schdayofmonth` varchar(100) DEFAULT NULL,
  `schdayofweek` varchar(100) DEFAULT NULL,
  `schannualdates` varchar(100) DEFAULT NULL,
  `schtime` varchar(50) DEFAULT NULL,
  `nexttrigger_time` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `workflowname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`workflow_id`),
  UNIQUE KEY `workflows_idx` (`workflow_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflows`
--

LOCK TABLES `workflows` WRITE;
/*!40000 ALTER TABLE `workflows` DISABLE KEYS */;
INSERT INTO `workflows` VALUES (1,'Invoice','UpdateInventoryProducts On Every Save','[{\"fieldname\":\"subject\",\"operation\":\"does not contain\",\"value\":\"`!`\"}]',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'UpdateInventoryProducts On Every Save'),(2,'Accounts','Send Email to user when Notifyowner is True','[{\"fieldname\":\"notify_owner\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',2,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to user when Notifyowner is True'),(3,'Contacts','Send Email to user when Notifyowner is True','[{\"fieldname\":\"notify_owner\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',2,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to user when Notifyowner is True'),(4,'Contacts','Send Email to user when Portal User is True','[{\"fieldname\":\"portal\",\"operation\":\"is\",\"value\":\"1\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"email\",\"operation\":\"is not empty\",\"value\":\"\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to user when Portal User is True'),(5,'Potentials','Send Email to users on Potential creation',NULL,1,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to users on Potential creation'),(6,'Contacts','Workflow for Contact Creation or Modification','',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Workflow for Contact Creation or Modification'),(7,'HelpDesk','Ticket Creation From Portal : Send Email to Record Owner and Contact','[{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":1,\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":0}]',1,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Ticket Creation From Portal : Send Email to Record Owner and Contact'),(9,'HelpDesk','Send Email to Contact on Ticket Update','[{\"fieldname\":\"(contact_id : (Contacts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":0,\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":0},{\"fieldname\":\"ticketstatus\",\"operation\":\"has changed to\",\"value\":\"Closed\",\"valuetype\":\"rawtext\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"solution\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"description\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to Contact on Ticket Update'),(10,'Events','Workflow for Events when Send Notification is True','[{\"fieldname\":\"sendnotification\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Workflow for Events when Send Notification is True'),(11,'Calendar','Workflow for Calendar Todos when Send Notification is True','[{\"fieldname\":\"sendnotification\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Workflow for Calendar Todos when Send Notification is True'),(12,'Potentials','Calculate or Update forecast amount','',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Calculate or Update forecast amount'),(13,'Events','Workflow for Events when Send Notification is True','[{\"fieldname\":\"sendnotification\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Workflow for Events when Send Notification is True'),(14,'Calendar','Workflow for Calendar Todos when Send Notification is True','[{\"fieldname\":\"sendnotification\",\"operation\":\"is\",\"value\":\"true:boolean\"}]',3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Workflow for Calendar Todos when Send Notification is True'),(15,'HelpDesk','Comment Added From CRM : Send Email to Organization','[{\"fieldname\":\"_VT_add_comment\",\"operation\":\"is added\",\"value\":\"\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"(parent_id : (Accounts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Comment Added From CRM : Send Email to Organization'),(16,'PurchaseOrder','Update Inventory Products On Every Save',NULL,3,1,'basic',5,NULL,NULL,NULL,NULL,NULL,NULL,1,'Update Inventory Products On Every Save'),(17,'HelpDesk','Comment Added From Portal : Send Email to Record Owner','[{\"fieldname\":\"_VT_add_comment\",\"operation\":\"is added\",\"value\":\"\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"1\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Comment Added From Portal : Send Email to Record Owner'),(18,'HelpDesk','Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User','[{\"fieldname\":\"(contact_id : (Contacts) portal)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"_VT_add_comment\",\"operation\":\"is added\",\"value\":\"\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"(contact_id : (Contacts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User'),(19,'HelpDesk','Comment Added From CRM : Send Email to Contact, where Contact is Portal User','[{\"fieldname\":\"(contact_id : (Contacts) portal)\",\"operation\":\"is\",\"value\":\"1\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"_VT_add_comment\",\"operation\":\"is added\",\"value\":\"\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"(contact_id : (Contacts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Comment Added From CRM : Send Email to Contact, where Contact is Portal User'),(20,'HelpDesk','Send Email to Record Owner on Ticket Update','[{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":0,\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":0},{\"fieldname\":\"ticketstatus\",\"operation\":\"has changed to\",\"value\":\"Closed\",\"valuetype\":\"rawtext\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"solution\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"assigned_user_id\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"description\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to Record Owner on Ticket Update'),(21,'HelpDesk','Ticket Creation From CRM : Send Email to Record Owner','[{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',1,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Ticket Creation From CRM : Send Email to Record Owner'),(22,'HelpDesk','Send Email to Organization on Ticket Update','[{\"fieldname\":\"(parent_id : (Accounts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":0,\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":0},{\"fieldname\":\"ticketstatus\",\"operation\":\"has changed to\",\"value\":\"Closed\",\"valuetype\":\"rawtext\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"solution\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"},{\"fieldname\":\"description\",\"operation\":\"has changed\",\"value\":\"\",\"valuetype\":\"\",\"joincondition\":\"or\",\"groupjoin\":\"and\",\"groupid\":\"1\"}]',3,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Send Email to Organization on Ticket Update'),(23,'HelpDesk','Ticket Creation From CRM : Send Email to Organization','[{\"fieldname\":\"(parent_id : (Accounts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',1,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Ticket Creation From CRM : Send Email to Organization'),(24,'HelpDesk','Ticket Creation From CRM : Send Email to Contact','[{\"fieldname\":\"(contact_id : (Contacts) emailoptout)\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"and\",\"groupjoin\":\"and\",\"groupid\":\"0\"},{\"fieldname\":\"from_portal\",\"operation\":\"is\",\"value\":\"0\",\"valuetype\":\"rawtext\",\"joincondition\":\"\",\"groupjoin\":\"and\",\"groupid\":\"0\"}]',1,1,'basic',6,NULL,NULL,NULL,NULL,NULL,NULL,1,'Ticket Creation From CRM : Send Email to Contact');
/*!40000 ALTER TABLE `workflows` ENABLE KEYS */;
UNLOCK TABLES;

-- Table structure for table `workflowtask_queue`
--

DROP TABLE IF EXISTS `workflowtask_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflowtask_queue` (
  `task_id` int(11) DEFAULT NULL,
  `entity_id` varchar(100) DEFAULT NULL,
  `do_after` int(11) DEFAULT NULL,
  `relatedinfo` varchar(255) DEFAULT NULL,
  `task_contents` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflowtask_queue`
--

LOCK TABLES `workflowtask_queue` WRITE;
/*!40000 ALTER TABLE `workflowtask_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflowtask_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflowtasks`
--

DROP TABLE IF EXISTS `workflowtasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflowtasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `workflow_id` int(11) DEFAULT NULL,
  `summary` varchar(400) NOT NULL,
  `task` text,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `workflowtasks_idx` (`task_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflowtasks`
--

LOCK TABLES `workflowtasks` WRITE;
/*!40000 ALTER TABLE `workflowtasks` DISABLE KEYS */;
INSERT INTO `workflowtasks` VALUES (1,1,'','O:18:\"EntityMethodTask\":6:{s:18:\"executeImmediately\";b:1;s:10:\"workflowId\";i:1;s:7:\"summary\";s:0:\"\";s:6:\"active\";b:1;s:10:\"methodName\";s:15:\"UpdateInventory\";s:2:\"id\";i:1;}'),(2,2,'An account has been created ','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:1:\"2\";s:7:\"summary\";s:28:\"An account has been created \";s:6:\"active\";s:1:\"1\";s:10:\"methodName\";s:11:\"NotifyOwner\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:26:\"Regarding Account Creation\";s:7:\"content\";s:299:\"An Account has been assigned to you on Joforce<br>Details of account are :<br><br>AccountId:<b>$account_no</b><br>AccountName:<b>$accountname</b><br>Rating:<b>$rating</b><br>Industry:<b>$industry</b><br>AccountType:<b>$accounttype</b><br>Description:<b>$description</b><br><br><br>Thank You<br>Admin\";s:2:\"id\";s:1:\"2\";}'),(3,3,'An contact has been created ','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:1:\"3\";s:7:\"summary\";s:28:\"An contact has been created \";s:6:\"active\";s:1:\"1\";s:10:\"methodName\";s:11:\"NotifyOwner\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:26:\"Regarding Contact Creation\";s:7:\"content\";s:303:\"An Contact has been assigned to you on Joforce<br>Details of Contact are :<br><br>Contact Id:<b>$contact_no</b><br>LastName:<b>$lastname</b><br>FirstName:<b>$firstname</b><br>Lead Source:<b>$leadsource</b><br>Department:<b>$department</b><br>Description:<b>$description</b><br><br><br>Thank You<br>Admin\";s:2:\"id\";s:1:\"3\";}'),(4,4,'Email Customer Portal Login Details','O:18:\"EntityMethodTask\":6:{s:18:\"executeImmediately\";b:1;s:10:\"workflowId\";i:4;s:7:\"summary\";s:35:\"Email Customer Portal Login Details\";s:6:\"active\";b:1;s:10:\"methodName\";s:22:\"SendPortalLoginDetails\";s:2:\"id\";i:4;}'),(5,5,'An Potential has been created ','O:11:\"EmailTask\":8:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:1:\"5\";s:7:\"summary\";s:30:\"An Potential has been created \";s:6:\"active\";s:1:\"1\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:30:\"Regarding Potential Assignment\";s:7:\"content\";s:340:\"An Potential has been assigned to you on Joforce<br>Details of Potential are :<br><br>Potential No:<b>$potential_no</b><br>Potential Name:<b>$potentialname</b><br>Amount:<b>$amount</b><br>Expected Close Date:<b>$closingdate ($_DATE_FORMAT_)</b><br>Type:<b>$opportunity_type</b><br><br><br>Description :$description<br><br>Thank You<br>Admin\";s:2:\"id\";s:1:\"5\";}'),(6,6,'An contact has been created ','O:11:\"EmailTask\":8:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:1:\"6\";s:7:\"summary\";s:28:\"An contact has been created \";s:6:\"active\";s:1:\"1\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:28:\"Regarding Contact Assignment\";s:7:\"content\";s:382:\"An Contact has been assigned to you on Joforce<br>Details of Contact are :<br><br>Contact Id:<b>$contact_no</b><br>LastName:<b>$lastname</b><br>FirstName:<b>$firstname</b><br>Lead Source:<b>$leadsource</b><br>Department:<b>$department</b><br>Description:<b>$description</b><br><br><br>And <b>CustomerPortal Login Details</b> is sent to the EmailID :-$email<br><br>Thank You<br>Admin\";s:2:\"id\";s:1:\"6\";}'),(7,7,'Notify Related Contact when Ticket is created from Portal','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:57:\"Notify Related Contact when Ticket is created from Portal\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:1:\"7\";s:10:\"workflowId\";s:1:\"7\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(contact_id : (Contacts) email)\";s:7:\"subject\";s:91:\"[From Portal] $ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:156:\"Ticket No : $ticket_no<br>\n							  Ticket ID : $(general : (__HeadMeta__) recordId)<br>\n							  Ticket Title : $ticket_title<br><br>\n							  $description\";}'),(10,9,'Send Email to Contact on Ticket Update','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:38:\"Send Email to Contact on Ticket Update\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"10\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(contact_id : (Contacts) email)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:622:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution<br>\n								The comments are : <br>\n								$allComments<br><br>\n								Regards<br>Support Administrator\";s:10:\"workflowId\";s:1:\"9\";}'),(13,12,'update forecast amount','O:18:\"UpdateFieldsTask\":7:{s:18:\"executeImmediately\";b:1;s:43:\"\0UpdateFieldsTask\0referenceFieldFocusList\";a:0:{}s:10:\"workflowId\";i:12;s:7:\"summary\";s:22:\"update forecast amount\";s:6:\"active\";b:1;s:19:\"field_value_mapping\";s:95:\"[{\"fieldname\":\"forecast_amount\",\"valuetype\":\"expression\",\"value\":\"amount * probability / 100\"}]\";s:2:\"id\";i:13;}'),(14,13,'Send Notification Email to Record Owner','O:11:\"EmailTask\":8:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:2:\"13\";s:7:\"summary\";s:39:\"Send Notification Email to Record Owner\";s:6:\"active\";s:1:\"1\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:17:\"Event :  $subject\";s:7:\"content\";s:767:\"$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/><b>Activity Notification Details:</b><br/>Subject             : $subject<br/>Start date and time : $date_start ($(general : (__HeadMeta__) usertimezone))<br/>End date and time   : $due_date ($(general : (__HeadMeta__) usertimezone)) <br/>Status              : $eventstatus <br/>Priority            : $taskpriority <br/>Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) $(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) $(parent_id : (Campaigns) campaignname) <br/>Contacts List       : $contact_id <br/>Location            : $location <br/>Description         : $description\";s:2:\"id\";s:2:\"14\";}'),(15,14,'Send Notification Email to Record Owner','O:11:\"EmailTask\":8:{s:18:\"executeImmediately\";s:0:\"\";s:10:\"workflowId\";s:2:\"14\";s:7:\"summary\";s:39:\"Send Notification Email to Record Owner\";s:6:\"active\";s:1:\"1\";s:9:\"recepient\";s:36:\"$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:16:\"Task :  $subject\";s:7:\"content\";s:687:\"$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/><b>Task Notification Details:</b><br/>Subject : $subject<br/>Start date and time : $date_start ($(general : (__HeadMeta__) usertimezone))<br/>End date and time   : $due_date ($_DATE_FORMAT_) <br/>Status              : $taskstatus <br/>Priority            : $taskpriority <br/>Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) $(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) $(parent_id : (Campaigns) campaignname) <br/>Contacts List       : $contact_id <br/>Description         : $description\";s:2:\"id\";s:2:\"15\";}'),(18,16,'Update Inventory Products','O:18:\"EntityMethodTask\":6:{s:18:\"executeImmediately\";b:1;s:10:\"workflowId\";i:16;s:7:\"summary\";s:25:\"Update Inventory Products\";s:6:\"active\";b:1;s:10:\"methodName\";s:15:\"UpdateInventory\";s:2:\"id\";i:18;}'),(19,17,'Comment Added From Portal : Send Email to Record Owner','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:54:\"Comment Added From Portal : Send Email to Record Owner\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"19\";s:10:\"workflowId\";s:2:\"17\";s:9:\"fromEmail\";s:112:\"$(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname)&lt;$(contact_id : (Contacts) email)&gt;\";s:9:\"recepient\";s:37:\",$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:90:\"Respond to Ticket ID## $(general : (__HeadMeta__) recordId) ## in Customer Portal - URGENT\";s:7:\"content\";s:325:\"Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\n								Customer has provided the following additional information to your reply:<br><br>\n								<b>$lastComment</b><br><br>\n								Kindly respond to above ticket at the earliest.<br><br>\n								Regards<br>Support Administrator\";}'),(20,18,'Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:82:\"Comment Added From CRM : Send Email to Contact, where Contact is not a Portal User\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"20\";s:10:\"workflowId\";s:2:\"18\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(contact_id : (Contacts) email)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:514:\"Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\n							The Ticket is replied the details are :<br><br>\n							Ticket No : $ticket_no<br>\n							Status : $ticketstatus<br>\n							Category : $ticketcategories<br>\n							Severity : $ticketseverities<br>\n							Priority : $ticketpriorities<br><br>\n							Description : <br>$description<br><br>\n							Solution : <br>$solution<br>\n							The comments are : <br>\n							$allComments<br><br>\n							Regards<br>Support Administrator\";}'),(21,19,'Comment Added From CRM : Send Email to Contact, where Contact is Portal User','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:76:\"Comment Added From CRM : Send Email to Contact, where Contact is Portal User\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"21\";s:10:\"workflowId\";s:2:\"19\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(contact_id : (Contacts) email)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:541:\"Ticket No : $ticket_no<br>\n										Ticket Id : $(general : (__HeadMeta__) recordId)<br>\n										Subject : $ticket_title<br><br>\n										Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\n										There is a reply to <b>$ticket_title</b> in the \"Customer Portal\" at VTiger.\n										You can use the following link to view the replies made:<br>\n										<a href=\"$(general : (__HeadMeta__) portaldetailviewurl)\">Ticket Details</a><br><br>\n										Thanks<br>$(general : (__HeadMeta__) supportName)\";}'),(22,15,'Comment Added From CRM : Send Email to Organization','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:51:\"Comment Added From CRM : Send Email to Organization\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"22\";s:10:\"workflowId\";s:2:\"15\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:34:\",$(parent_id : (Accounts) email1),\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:587:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(parent_id : (Accounts) accountname),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution<br>\n								The comments are : <br>\n								$allComments<br><br>\n								Regards<br>Support Administrator\";}'),(23,7,'Notify Record Owner when Ticket is created from Portal','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:54:\"Notify Record Owner when Ticket is created from Portal\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"23\";s:10:\"workflowId\";s:1:\"7\";s:9:\"fromEmail\";s:122:\"$(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:37:\",$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:91:\"[From Portal] $ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:156:\"Ticket No : $ticket_no<br>\n							  Ticket ID : $(general : (__HeadMeta__) recordId)<br>\n							  Ticket Title : $ticket_title<br><br>\n							  $description\";}'),(24,20,'Send Email to Record Owner on Ticket Update','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:43:\"Send Email to Record Owner on Ticket Update\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"24\";s:10:\"workflowId\";s:2:\"20\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:37:\",$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:40:\"Ticket Number : $ticket_no $ticket_title\";s:7:\"content\";s:594:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution\n								$allComments<br><br>\n								Regards<br>Support Administrator\";}'),(25,21,'Ticket Creation From CRM : Send Email to Record Owner','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:53:\"Ticket Creation From CRM : Send Email to Record Owner\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"25\";s:10:\"workflowId\";s:2:\"21\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:37:\",$(assigned_user_id : (Users) email1)\";s:7:\"subject\";s:40:\"Ticket Number : $ticket_no $ticket_title\";s:7:\"content\";s:594:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution\n								$allComments<br><br>\n								Regards<br>Support Administrator\";}'),(26,22,'Send Email to Organization on Ticket Update','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:43:\"Send Email to Organization on Ticket Update\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"26\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(parent_id : (Accounts) email1)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:587:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(parent_id : (Accounts) accountname),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution<br>\n								The comments are : <br>\n								$allComments<br><br>\n								Regards<br>Support Administrator\";s:10:\"workflowId\";s:2:\"22\";}'),(27,23,'Ticket Creation From CRM : Send Email to Organization','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:53:\"Ticket Creation From CRM : Send Email to Organization\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"27\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(parent_id : (Accounts) email1)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:587:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(parent_id : (Accounts) accountname),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution<br>\n								The comments are : <br>\n								$allComments<br><br>\n								Regards<br>Support Administrator\";s:10:\"workflowId\";s:2:\"23\";}'),(28,24,'Ticket Creation From CRM : Send Email to Contact','O:11:\"EmailTask\":9:{s:18:\"executeImmediately\";s:1:\"0\";s:7:\"summary\";s:48:\"Ticket Creation From CRM : Send Email to Contact\";s:6:\"active\";s:1:\"1\";s:2:\"id\";s:2:\"28\";s:9:\"fromEmail\";s:89:\"$(general : (__HeadMeta__) supportName)&lt;$(general : (__HeadMeta__) supportEmailId)&gt;\";s:9:\"recepient\";s:33:\",$(contact_id : (Contacts) email)\";s:7:\"subject\";s:77:\"$ticket_no [ Ticket Id : $(general : (__HeadMeta__) recordId) ] $ticket_title\";s:7:\"content\";s:622:\"Ticket ID : $(general : (__HeadMeta__) recordId)<br>Ticket Title : $ticket_title<br><br>\n								Dear $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname),<br><br>\n								The Ticket is replied the details are :<br><br>\n								Ticket No : $ticket_no<br>\n								Status : $ticketstatus<br>\n								Category : $ticketcategories<br>\n								Severity : $ticketseverities<br>\n								Priority : $ticketpriorities<br><br>\n								Description : <br>$description<br><br>\n								Solution : <br>$solution<br>\n								The comments are : <br>\n								$allComments<br><br>\n								Regards<br>Support Administrator\";s:10:\"workflowId\";s:2:\"24\";}');
/*!40000 ALTER TABLE `workflowtasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflowtasks_entitymethod`
--

DROP TABLE IF EXISTS `workflowtasks_entitymethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflowtasks_entitymethod` (
  `workflowtasks_entitymethod_id` int(11) NOT NULL,
  `module_name` varchar(100) DEFAULT NULL,
  `method_name` varchar(100) DEFAULT NULL,
  `function_path` varchar(400) DEFAULT NULL,
  `function_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`workflowtasks_entitymethod_id`),
  UNIQUE KEY `workflowtasks_entitymethod_idx` (`workflowtasks_entitymethod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflowtasks_entitymethod`
--

LOCK TABLES `workflowtasks_entitymethod` WRITE;
/*!40000 ALTER TABLE `workflowtasks_entitymethod` DISABLE KEYS */;
INSERT INTO `workflowtasks_entitymethod` VALUES (1,'SalesOrder','UpdateInventory','includes/InventoryHandler.php','handleInventoryProductRel'),(2,'Invoice','UpdateInventory','includes/InventoryHandler.php','handleInventoryProductRel'),(3,'Contacts','SendPortalLoginDetails','modules/Contacts/ContactsHandler.php','Contacts_sendCustomerPortalLoginDetails'),(4,'HelpDesk','NotifyOnPortalTicketCreation','modules/HelpDesk/HelpDeskHandler.php','HelpDesk_nofifyOnPortalTicketCreation'),(5,'HelpDesk','NotifyOnPortalTicketComment','modules/HelpDesk/HelpDeskHandler.php','HelpDesk_notifyOnPortalTicketComment'),(6,'HelpDesk','NotifyOwnerOnTicketChange','modules/HelpDesk/HelpDeskHandler.php','HelpDesk_notifyOwnerOnTicketChange'),(7,'HelpDesk','NotifyParentOnTicketChange','modules/HelpDesk/HelpDeskHandler.php','HelpDesk_notifyParentOnTicketChange'),(8,'ModComments','CustomerCommentFromPortal','modules/ModComments/ModCommentsHandler.php','CustomerCommentFromPortal'),(9,'ModComments','TicketOwnerComments','modules/ModComments/ModCommentsHandler.php','TicketOwnerComments'),(10,'PurchaseOrder','UpdateInventory','includes/InventoryHandler.php','handleInventoryProductRel');
/*!40000 ALTER TABLE `workflowtasks_entitymethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workflowtemplates`
--

DROP TABLE IF EXISTS `workflowtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workflowtemplates` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `title` varchar(400) DEFAULT NULL,
  `template` text,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workflowtemplates`
--

LOCK TABLES `workflowtemplates` WRITE;
/*!40000 ALTER TABLE `workflowtemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `workflowtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactgroupmembers`
--

DROP TABLE IF EXISTS `contactgroupmembers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactgroupmembers` (
  `contactgroup_id` int(19) NOT NULL,
  `contact_id` int(19) NOT NULL,
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (`contactgroup_id`,`contact_id`),
  KEY `contact_id_fk_contacts` (`contact_id`),
  CONSTRAINT `contact_id_fk_contacts` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`contact_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactgroupmembers`
--

LOCK TABLES `contactgroupmembers` WRITE;
/*!40000 ALTER TABLE `contactgroupmembers` DISABLE KEYS */;
/*!40000 ALTER TABLE `contactgroupmembers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactgroups`
--

DROP TABLE IF EXISTS `contactgroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactgroups` (
  `contactgroup_id` int(19) NOT NULL AUTO_INCREMENT,
  `user_id` int(19) DEFAULT NULL,
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` int(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`contactgroup_id`),
  KEY `contactgroups_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_contactgroups` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactgroups`
--

LOCK TABLES `contactgroups` WRITE;
/*!40000 ALTER TABLE `contactgroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `contactgroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `contact_id` int(19) NOT NULL AUTO_INCREMENT,
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` int(1) DEFAULT '0',
  `name` int(1) DEFAULT NULL,
  `email` int(1) DEFAULT NULL,
  `firstname` int(1) DEFAULT NULL,
  `surname` int(1) DEFAULT NULL,
  `vcard` int(1) DEFAULT NULL,
  `words` text,
  `user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`contact_id`),
  KEY `contact_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_contacts` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dictionary`
--

DROP TABLE IF EXISTS `dictionary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dictionary` (
  `user_id` int(19) NOT NULL,
  `language` varchar(5) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`user_id`),
  KEY `dictionary_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_dictionary` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dictionary`
--

LOCK TABLES `dictionary` WRITE;
/*!40000 ALTER TABLE `dictionary` DISABLE KEYS */;
/*!40000 ALTER TABLE `dictionary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `identities`
--

DROP TABLE IF EXISTS `identities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `identities` (
  `identity_id` int(19) NOT NULL AUTO_INCREMENT,
  `user_id` int(19) DEFAULT NULL,
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `del` int(1) DEFAULT '0',
  `standard` int(1) DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `reply-to` varchar(200) DEFAULT NULL,
  `bcc` varchar(200) DEFAULT NULL,
  `signature` varchar(200) DEFAULT NULL,
  `html_signature` int(1) DEFAULT '0',
  PRIMARY KEY (`identity_id`),
  KEY `identities_user_id_idx` (`user_id`),
  KEY `identities_email_idx` (`email`),
  CONSTRAINT `user_id_fk_identities` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `identities`
--

LOCK TABLES `identities` WRITE;
/*!40000 ALTER TABLE `identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `identities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_account`
--

DROP TABLE IF EXISTS `jo_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_account` (
  `accountid` int(19) NOT NULL DEFAULT '0',
  `account_no` varchar(100) NOT NULL,
  `accountname` varchar(100) NOT NULL,
  `parentid` int(19) DEFAULT '0',
  `account_type` varchar(200) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `annualrevenue` decimal(25,8) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `ownership` varchar(50) DEFAULT NULL,
  `siccode` varchar(50) DEFAULT NULL,
  `tickersymbol` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `otherphone` varchar(30) DEFAULT NULL,
  `email1` varchar(100) DEFAULT NULL,
  `email2` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `employees` int(10) DEFAULT '0',
  `emailoptout` varchar(3) DEFAULT '0',
  `notify_owner` varchar(3) DEFAULT '0',
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`accountid`),
  KEY `account_account_type_idx` (`account_type`),
  KEY `email_idx` (`email1`,`email2`),
  CONSTRAINT `fk_1_jo_account` FOREIGN KEY (`accountid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_account`
--

LOCK TABLES `jo_account` WRITE;
/*!40000 ALTER TABLE `jo_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_accountbillads`
--

DROP TABLE IF EXISTS `jo_accountbillads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_accountbillads` (
  `accountaddressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`accountaddressid`),
  CONSTRAINT `fk_1_jo_accountbillads` FOREIGN KEY (`accountaddressid`) REFERENCES `jo_account` (`accountid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_accountbillads`
--

LOCK TABLES `jo_accountbillads` WRITE;
/*!40000 ALTER TABLE `jo_accountbillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_accountbillads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_accountrating`
--

DROP TABLE IF EXISTS `jo_accountrating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_accountrating` (
  `accountratingid` int(19) NOT NULL AUTO_INCREMENT,
  `rating` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`accountratingid`),
  UNIQUE KEY `accountrating_rating_idx` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_accountrating`
--

LOCK TABLES `jo_accountrating` WRITE;
/*!40000 ALTER TABLE `jo_accountrating` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_accountrating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_accountscf`
--

DROP TABLE IF EXISTS `jo_accountscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_accountscf` (
  `accountid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountid`),
  CONSTRAINT `fk_1_jo_accountscf` FOREIGN KEY (`accountid`) REFERENCES `jo_account` (`accountid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_accountscf`
--

LOCK TABLES `jo_accountscf` WRITE;
/*!40000 ALTER TABLE `jo_accountscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_accountscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_accountshipads`
--

DROP TABLE IF EXISTS `jo_accountshipads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_accountshipads` (
  `accountaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`accountaddressid`),
  CONSTRAINT `fk_1_jo_accountshipads` FOREIGN KEY (`accountaddressid`) REFERENCES `jo_account` (`accountid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_accountshipads`
--

LOCK TABLES `jo_accountshipads` WRITE;
/*!40000 ALTER TABLE `jo_accountshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_accountshipads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_accounttype`
--

DROP TABLE IF EXISTS `jo_accounttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_accounttype` (
  `accounttypeid` int(19) NOT NULL AUTO_INCREMENT,
  `accounttype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`accounttypeid`),
  UNIQUE KEY `accounttype_accounttype_idx` (`accounttype`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_accounttype`
--

LOCK TABLES `jo_accounttype` WRITE;
/*!40000 ALTER TABLE `jo_accounttype` DISABLE KEYS */;
INSERT INTO `jo_accounttype` VALUES (2,'Analyst',1,2,1,NULL),(3,'Competitor',1,3,2,NULL),(4,'Customer',1,4,3,NULL),(5,'Integrator',1,5,4,NULL),(6,'Investor',1,6,5,NULL),(7,'Partner',1,7,6,NULL),(8,'Press',1,8,7,NULL),(9,'Prospect',1,9,8,NULL),(10,'Reseller',1,10,9,NULL),(11,'Other',1,11,10,NULL);
/*!40000 ALTER TABLE `jo_accounttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_actionmapping`
--

DROP TABLE IF EXISTS `jo_actionmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_actionmapping` (
  `actionid` int(19) NOT NULL,
  `actionname` varchar(200) NOT NULL,
  `securitycheck` int(19) DEFAULT NULL,
  PRIMARY KEY (`actionid`,`actionname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_actionmapping`
--

LOCK TABLES `jo_actionmapping` WRITE;
/*!40000 ALTER TABLE `jo_actionmapping` DISABLE KEYS */;
INSERT INTO `jo_actionmapping` VALUES (0,'Save',0),(0,'SavePriceBook',1),(0,'SaveVendor',1),(1,'DetailViewAjax',1),(1,'EditView',0),(1,'PriceBookEditView',1),(1,'QuickCreate',1),(1,'VendorEditView',1),(2,'Delete',0),(2,'DeletePriceBook',1),(2,'DeleteVendor',1),(3,'index',0),(3,'Popup',1),(4,'DetailView',0),(4,'PriceBookDetailView',1),(4,'TagCloud',1),(4,'VendorDetailView',1),(5,'Import',0),(6,'Export',0),(7,'CreateView',0),(8,'Merge',0),(9,'ConvertLead',0),(10,'DuplicatesHandling',0),(11,'ReceiveIncomingCalls',0),(12,'MakeOutgoingCalls',0),(13,'Print',0),(14,'Masquerade User',0);
/*!40000 ALTER TABLE `jo_actionmapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activity`
--

DROP TABLE IF EXISTS `jo_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activity` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(255) DEFAULT NULL,
  `semodule` varchar(20) DEFAULT NULL,
  `activitytype` varchar(200) NOT NULL,
  `date_start` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `time_start` varchar(50) DEFAULT NULL,
  `time_end` varchar(50) DEFAULT NULL,
  `sendnotification` varchar(3) NOT NULL DEFAULT '0',
  `duration_hours` varchar(200) DEFAULT NULL,
  `duration_minutes` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `eventstatus` varchar(200) DEFAULT NULL,
  `priority` varchar(200) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `notime` varchar(3) NOT NULL DEFAULT '0',
  `visibility` varchar(50) NOT NULL DEFAULT 'all',
  `recurringtype` varchar(200) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`activityid`),
  KEY `activity_activityid_subject_idx` (`activityid`,`subject`),
  KEY `activity_activitytype_date_start_idx` (`activitytype`,`date_start`),
  KEY `activity_date_start_due_date_idx` (`date_start`,`due_date`),
  KEY `activity_date_start_time_start_idx` (`date_start`,`time_start`),
  KEY `activity_eventstatus_idx` (`eventstatus`),
  KEY `activity_status_idx` (`status`),
  CONSTRAINT `fk_1_jo_activity` FOREIGN KEY (`activityid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activity`
--

LOCK TABLES `jo_activity` WRITE;
/*!40000 ALTER TABLE `jo_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activity_recurring_info`
--

DROP TABLE IF EXISTS `jo_activity_recurring_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activity_recurring_info` (
  `activityid` int(19) NOT NULL,
  `recurrenceid` int(19) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activity_recurring_info`
--

LOCK TABLES `jo_activity_recurring_info` WRITE;
/*!40000 ALTER TABLE `jo_activity_recurring_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activity_recurring_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activity_reminder`
--

DROP TABLE IF EXISTS `jo_activity_reminder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activity_reminder` (
  `activity_id` int(11) NOT NULL,
  `reminder_time` int(11) NOT NULL,
  `reminder_sent` int(2) NOT NULL,
  `recurringid` int(19) NOT NULL,
  PRIMARY KEY (`activity_id`,`recurringid`),
  CONSTRAINT `fk_activityid_jo_activity_reminder` FOREIGN KEY (`activity_id`) REFERENCES `jo_activity` (`activityid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activity_reminder`
--

LOCK TABLES `jo_activity_reminder` WRITE;
/*!40000 ALTER TABLE `jo_activity_reminder` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activity_reminder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activity_reminder_popup`
--

DROP TABLE IF EXISTS `jo_activity_reminder_popup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activity_reminder_popup` (
  `reminderid` int(19) NOT NULL AUTO_INCREMENT,
  `semodule` varchar(100) NOT NULL,
  `recordid` int(19) NOT NULL,
  `date_start` date NOT NULL,
  `time_start` varchar(100) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`reminderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activity_reminder_popup`
--

LOCK TABLES `jo_activity_reminder_popup` WRITE;
/*!40000 ALTER TABLE `jo_activity_reminder_popup` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activity_reminder_popup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activity_view`
--

DROP TABLE IF EXISTS `jo_activity_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activity_view` (
  `activity_viewid` int(19) NOT NULL AUTO_INCREMENT,
  `activity_view` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`activity_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activity_view`
--

LOCK TABLES `jo_activity_view` WRITE;
/*!40000 ALTER TABLE `jo_activity_view` DISABLE KEYS */;
INSERT INTO `jo_activity_view` VALUES (1,'Today',0,1),(2,'This Week',1,1),(3,'This Month',2,1),(4,'This Year',3,1),(5,'Agenda',4,1);
/*!40000 ALTER TABLE `jo_activity_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activitycf`
--

DROP TABLE IF EXISTS `jo_activitycf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activitycf` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityid`),
  CONSTRAINT `fk_activityid_jo_activitycf` FOREIGN KEY (`activityid`) REFERENCES `jo_activity` (`activityid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activitycf`
--

LOCK TABLES `jo_activitycf` WRITE;
/*!40000 ALTER TABLE `jo_activitycf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activitycf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activityproductrel`
--

DROP TABLE IF EXISTS `jo_activityproductrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activityproductrel` (
  `activityid` int(19) NOT NULL DEFAULT '0',
  `productid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityid`,`productid`),
  KEY `activityproductrel_activityid_idx` (`activityid`),
  KEY `activityproductrel_productid_idx` (`productid`),
  CONSTRAINT `fk_2_jo_activityproductrel` FOREIGN KEY (`productid`) REFERENCES `jo_products` (`productid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activityproductrel`
--

LOCK TABLES `jo_activityproductrel` WRITE;
/*!40000 ALTER TABLE `jo_activityproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_activityproductrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_activitytype`
--

DROP TABLE IF EXISTS `jo_activitytype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_activitytype` (
  `activitytypeid` int(19) NOT NULL AUTO_INCREMENT,
  `activitytype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`activitytypeid`),
  UNIQUE KEY `activitytype_activitytype_idx` (`activitytype`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_activitytype`
--

LOCK TABLES `jo_activitytype` WRITE;
/*!40000 ALTER TABLE `jo_activitytype` DISABLE KEYS */;
INSERT INTO `jo_activitytype` VALUES (1,'Call',0,12,0,NULL),(2,'Meeting',0,13,1,NULL),(3,'Mobile Call',0,323,2,NULL);
/*!40000 ALTER TABLE `jo_activitytype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_announcement`
--

DROP TABLE IF EXISTS `jo_announcement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_announcement` (
  `creatorid` int(19) NOT NULL,
  `announcement` text,
  `title` varchar(255) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`creatorid`),
  KEY `announcement_creatorid_idx` (`creatorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_announcement`
--

LOCK TABLES `jo_announcement` WRITE;
/*!40000 ALTER TABLE `jo_announcement` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_announcement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_asterisk`
--

DROP TABLE IF EXISTS `jo_asterisk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_asterisk` (
  `server` varchar(30) DEFAULT NULL,
  `port` varchar(30) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_asterisk`
--

LOCK TABLES `jo_asterisk` WRITE;
/*!40000 ALTER TABLE `jo_asterisk` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_asterisk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_asteriskextensions`
--

DROP TABLE IF EXISTS `jo_asteriskextensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_asteriskextensions` (
  `userid` int(11) DEFAULT NULL,
  `asterisk_extension` varchar(50) DEFAULT NULL,
  `use_asterisk` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_asteriskextensions`
--

LOCK TABLES `jo_asteriskextensions` WRITE;
/*!40000 ALTER TABLE `jo_asteriskextensions` DISABLE KEYS */;
INSERT INTO `jo_asteriskextensions` VALUES (1,NULL,NULL);
/*!40000 ALTER TABLE `jo_asteriskextensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_asteriskincomingcalls`
--

DROP TABLE IF EXISTS `jo_asteriskincomingcalls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_asteriskincomingcalls` (
  `from_number` varchar(50) DEFAULT NULL,
  `from_name` varchar(50) DEFAULT NULL,
  `to_number` varchar(50) DEFAULT NULL,
  `callertype` varchar(30) DEFAULT NULL,
  `flag` int(19) DEFAULT NULL,
  `timer` int(19) DEFAULT NULL,
  `refuid` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_asteriskincomingcalls`
--

LOCK TABLES `jo_asteriskincomingcalls` WRITE;
/*!40000 ALTER TABLE `jo_asteriskincomingcalls` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_asteriskincomingcalls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_asteriskincomingevents`
--

DROP TABLE IF EXISTS `jo_asteriskincomingevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_asteriskincomingevents` (
  `uid` varchar(255) NOT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `from_number` bigint(20) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `to_number` bigint(20) DEFAULT NULL,
  `callertype` varchar(100) DEFAULT NULL,
  `timer` int(20) DEFAULT NULL,
  `flag` varchar(3) DEFAULT NULL,
  `pbxrecordid` int(19) DEFAULT NULL,
  `relcrmid` int(19) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_asteriskincomingevents`
--

LOCK TABLES `jo_asteriskincomingevents` WRITE;
/*!40000 ALTER TABLE `jo_asteriskincomingevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_asteriskincomingevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_attachments`
--

DROP TABLE IF EXISTS `jo_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_attachments` (
  `attachmentsid` int(19) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `type` varchar(100) DEFAULT NULL,
  `path` text,
  `subject` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`attachmentsid`),
  KEY `attachments_attachmentsid_idx` (`attachmentsid`),
  CONSTRAINT `fk_1_jo_attachments` FOREIGN KEY (`attachmentsid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_attachments`
--

LOCK TABLES `jo_attachments` WRITE;
/*!40000 ALTER TABLE `jo_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_attachmentsfolder`
--

DROP TABLE IF EXISTS `jo_attachmentsfolder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_attachmentsfolder` (
  `folderid` int(19) NOT NULL AUTO_INCREMENT,
  `foldername` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `createdby` int(19) NOT NULL,
  `sequence` int(19) DEFAULT NULL,
  PRIMARY KEY (`folderid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_attachmentsfolder`
--

LOCK TABLES `jo_attachmentsfolder` WRITE;
/*!40000 ALTER TABLE `jo_attachmentsfolder` DISABLE KEYS */;
INSERT INTO `jo_attachmentsfolder` VALUES (1,'Default','This is a Default Folder',1,1);
/*!40000 ALTER TABLE `jo_attachmentsfolder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_audit_trial`
--

DROP TABLE IF EXISTS `jo_audit_trial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_audit_trial` (
  `auditid` int(19) NOT NULL,
  `userid` int(19) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `recordid` varchar(20) DEFAULT NULL,
  `actiondate` datetime DEFAULT NULL,
  PRIMARY KEY (`auditid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_audit_trial`
--

LOCK TABLES `jo_audit_trial` WRITE;
/*!40000 ALTER TABLE `jo_audit_trial` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_audit_trial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_blocks`
--

DROP TABLE IF EXISTS `jo_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_blocks` (
  `blockid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `blocklabel` varchar(100) NOT NULL,
  `sequence` int(10) DEFAULT NULL,
  `show_title` int(2) DEFAULT NULL,
  `visible` int(2) NOT NULL DEFAULT '0',
  `create_view` int(2) NOT NULL DEFAULT '0',
  `edit_view` int(2) NOT NULL DEFAULT '0',
  `detail_view` int(2) NOT NULL DEFAULT '0',
  `display_status` int(1) NOT NULL DEFAULT '1',
  `iscustom` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`blockid`),
  KEY `block_tabid_idx` (`tabid`),
  CONSTRAINT `fk_1_jo_blocks` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_blocks`
--

LOCK TABLES `jo_blocks` WRITE;
/*!40000 ALTER TABLE `jo_blocks` DISABLE KEYS */;
INSERT INTO `jo_blocks` VALUES (1,2,'LBL_OPPORTUNITY_INFORMATION',1,0,0,0,0,0,1,0),(2,2,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(3,2,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0,1,0),(4,4,'LBL_CONTACT_INFORMATION',1,0,0,0,0,0,1,0),(5,4,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(7,4,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0,1,0),(8,4,'LBL_DESCRIPTION_INFORMATION',5,0,0,0,0,0,1,0),(9,6,'LBL_ACCOUNT_INFORMATION',1,0,0,0,0,0,1,0),(10,6,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(11,6,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(12,6,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(13,7,'LBL_LEAD_INFORMATION',1,0,0,0,0,0,1,0),(14,7,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(15,7,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(16,7,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(17,8,'LBL_NOTE_INFORMATION',1,0,0,0,0,0,1,0),(18,8,'LBL_FILE_INFORMATION',3,1,0,0,0,0,1,0),(19,9,'LBL_TASK_INFORMATION',1,0,0,0,0,0,1,0),(20,9,'LBL_DESCRIPTION_INFORMATION',3,1,0,0,0,0,1,0),(21,10,'LBL_EMAIL_INFORMATION',1,0,0,0,0,0,1,0),(22,10,'Emails_Block1',2,1,0,0,0,0,1,0),(23,10,'Emails_Block2',3,1,0,0,0,0,1,0),(24,10,'Emails_Block3',4,1,0,0,0,0,1,0),(25,13,'LBL_TICKET_INFORMATION',1,0,0,0,0,0,1,0),(26,13,'',2,1,0,0,0,0,1,0),(27,13,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0,1,0),(28,13,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(29,13,'LBL_TICKET_RESOLUTION',5,0,0,1,0,0,1,0),(30,13,'LBL_COMMENTS',6,0,0,1,0,0,1,0),(31,14,'LBL_PRODUCT_INFORMATION',1,0,0,0,0,0,1,0),(32,14,'LBL_PRICING_INFORMATION',2,0,0,0,0,0,1,0),(33,14,'LBL_STOCK_INFORMATION',3,0,0,0,0,0,1,0),(34,14,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0,1,0),(35,14,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0,1,0),(36,14,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0,1,0),(37,16,'LBL_EVENT_INFORMATION',1,0,0,0,0,0,1,0),(38,16,'',2,1,0,0,0,0,1,0),(39,16,'',5,1,0,0,0,0,1,0),(40,18,'LBL_REMINDER_INFORMATION',1,0,0,0,0,0,1,0),(41,18,'LBL_DESCRIPTION_INFORMATION',2,0,0,0,0,0,1,0),(42,18,'LBL_VENDOR_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(43,18,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(44,19,'LBL_PRICEBOOK_INFORMATION',1,0,0,0,0,0,1,0),(45,19,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(46,19,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0,1,0),(47,20,'LBL_QUOTE_INFORMATION',1,0,0,0,0,0,1,0),(48,20,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(49,20,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(50,20,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0,1,0),(51,20,'LBL_TERMS_INFORMATION',5,0,0,0,0,0,1,0),(52,20,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0,1,0),(53,21,'LBL_PO_INFORMATION',1,0,0,0,0,0,1,0),(54,21,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(55,21,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(56,21,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0,1,0),(57,21,'LBL_TERMS_INFORMATION',5,0,0,0,0,0,1,0),(58,21,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0,1,0),(59,22,'LBL_SO_INFORMATION',1,0,0,0,0,0,1,0),(60,22,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0,1,0),(61,22,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0,1,0),(62,22,'LBL_RELATED_PRODUCTS',5,0,0,0,0,0,1,0),(63,22,'LBL_TERMS_INFORMATION',6,0,0,0,0,0,1,0),(64,22,'LBL_DESCRIPTION_INFORMATION',7,0,0,0,0,0,1,0),(65,23,'LBL_INVOICE_INFORMATION',1,0,0,0,0,0,1,0),(66,23,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(67,23,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0,1,0),(68,23,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0,1,0),(69,23,'LBL_TERMS_INFORMATION',5,0,0,0,0,0,1,0),(70,23,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0,1,0),(71,4,'LBL_IMAGE_INFORMATION',6,0,0,0,0,0,1,0),(72,26,'LBL_CAMPAIGN_INFORMATION',1,0,0,0,0,0,1,0),(73,26,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(74,26,'LBL_EXPECTATIONS_AND_ACTUALS',3,0,0,0,0,0,1,0),(75,29,'LBL_USERLOGIN_ROLE',1,0,0,0,0,0,1,0),(76,29,'LBL_CURRENCY_CONFIGURATION',3,0,0,0,0,0,1,0),(78,29,'LBL_ADDRESS_INFORMATION',5,0,0,0,0,0,1,0),(79,26,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(80,29,'LBL_USER_IMAGE_INFORMATION',5,0,0,0,0,0,1,0),(81,29,'LBL_USER_ADV_OPTIONS',6,0,0,0,0,0,1,0),(82,8,'LBL_DESCRIPTION',2,0,0,0,0,0,1,0),(83,22,'Recurring Invoice Information',2,0,0,0,0,0,1,0),(84,9,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0,1,0),(85,16,'LBL_CUSTOM_INFORMATION',6,0,0,0,0,0,1,0),(86,36,'LBL_SERVICE_INFORMATION',1,0,0,0,0,0,1,0),(87,36,'LBL_PRICING_INFORMATION',2,0,0,0,0,0,1,0),(88,36,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0,1,0),(89,36,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0,1,0),(90,37,'LBL_PBXMANAGER_INFORMATION',1,0,0,0,0,0,1,0),(91,42,'LBL_PROJECT_MILESTONE_INFORMATION',1,0,0,0,0,0,1,0),(92,42,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(93,42,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0,1,0),(94,43,'LBL_PROJECT_TASK_INFORMATION',1,0,0,0,0,0,1,0),(95,43,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(96,43,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0,1,0),(97,44,'LBL_PROJECT_INFORMATION',1,0,0,0,0,0,1,0),(98,44,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0,1,0),(99,44,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0,1,0),(100,47,'LBL_MODCOMMENTS_INFORMATION',1,0,0,0,0,0,1,0),(101,47,'LBL_OTHER_INFORMATION',2,0,0,0,0,0,1,0),(102,47,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0,1,0),(103,23,'LBL_ITEM_DETAILS',5,0,0,0,0,0,1,0),(104,22,'LBL_ITEM_DETAILS',5,0,0,0,0,0,1,0),(105,21,'LBL_ITEM_DETAILS',5,0,0,0,0,0,1,0),(106,20,'LBL_ITEM_DETAILS',5,0,0,0,0,0,1,0),(107,16,'LBL_RECURRENCE_INFORMATION',3,0,0,0,0,0,1,0),(108,29,'LBL_CALENDAR_SETTINGS',2,0,0,0,0,0,1,0),(109,16,'LBL_RELATED_TO',4,0,0,0,0,0,1,0),(110,9,'LBL_REMINDER_INFORMATION',2,0,0,0,0,0,1,0);
/*!40000 ALTER TABLE `jo_blocks` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_calendar_default_activitytypes`
--

DROP TABLE IF EXISTS `jo_calendar_default_activitytypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_calendar_default_activitytypes` (
  `id` int(19) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `fieldname` varchar(50) DEFAULT NULL,
  `defaultcolor` varchar(50) DEFAULT NULL,
  `isdefault` int(11) DEFAULT '1',
  `conditions` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_calendar_default_activitytypes`
--

LOCK TABLES `jo_calendar_default_activitytypes` WRITE;
/*!40000 ALTER TABLE `jo_calendar_default_activitytypes` DISABLE KEYS */;
INSERT INTO `jo_calendar_default_activitytypes` VALUES (1,'Events','[\"date_start\",\"due_date\"]','#17309A',1,NULL),(2,'Calendar','[\"date_start\",\"due_date\"]','#3A87AD',1,NULL),(3,'Potentials','[\"closingdate\"]','#AA6705',1,NULL),(4,'Contacts','[\"support_end_date\"]','#953B39',1,NULL),(5,'Contacts','[\"birthday\"]','#545252',1,NULL),(6,'Invoice','[\"duedate\"]','#87865D',1,NULL),(7,'Project','[\"startdate\",\"targetenddate\"]','#C71585',1,NULL),(8,'ProjectTask','[\"startdate\",\"enddate\"]','#006400',1,NULL);
/*!40000 ALTER TABLE `jo_calendar_default_activitytypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_calendar_user_activitytypes`
--

DROP TABLE IF EXISTS `jo_calendar_user_activitytypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_calendar_user_activitytypes` (
  `id` int(19) NOT NULL,
  `defaultid` int(19) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `visible` int(19) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_calendar_user_activitytypes`
--

LOCK TABLES `jo_calendar_user_activitytypes` WRITE;
/*!40000 ALTER TABLE `jo_calendar_user_activitytypes` DISABLE KEYS */;
INSERT INTO `jo_calendar_user_activitytypes` VALUES (1,1,1,'#17309A',1),(2,2,1,'#3A87AD',1),(3,3,1,'#AA6705',1),(4,4,1,'#953B39',1),(5,5,1,'#545252',1),(6,6,1,'#87865D',1),(7,7,1,'#C71585',1),(8,8,1,'#006400',1);
/*!40000 ALTER TABLE `jo_calendar_user_activitytypes` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_calendarsharedtype`
--

DROP TABLE IF EXISTS `jo_calendarsharedtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_calendarsharedtype` (
  `calendarsharedtypeid` int(11) NOT NULL AUTO_INCREMENT,
  `calendarsharedtype` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calendarsharedtypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_calendarsharedtype`
--

LOCK TABLES `jo_calendarsharedtype` WRITE;
/*!40000 ALTER TABLE `jo_calendarsharedtype` DISABLE KEYS */;
INSERT INTO `jo_calendarsharedtype` VALUES (1,'public',0,1),(2,'private',1,1),(3,'seletedusers',2,1);
/*!40000 ALTER TABLE `jo_calendarsharedtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_callduration`
--

DROP TABLE IF EXISTS `jo_callduration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_callduration` (
  `calldurationid` int(11) NOT NULL AUTO_INCREMENT,
  `callduration` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`calldurationid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_callduration`
--

LOCK TABLES `jo_callduration` WRITE;
/*!40000 ALTER TABLE `jo_callduration` DISABLE KEYS */;
INSERT INTO `jo_callduration` VALUES (1,'5',0,1),(2,'10',1,1),(3,'30',2,1),(4,'60',3,1),(5,'120',4,1);
/*!40000 ALTER TABLE `jo_callduration` ENABLE KEYS */;
UNLOCK TABLES;
--
-- Table structure for table `jo_campaign`
--

DROP TABLE IF EXISTS `jo_campaign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaign` (
  `campaign_no` varchar(100) NOT NULL,
  `campaignname` varchar(255) DEFAULT NULL,
  `campaigntype` varchar(200) DEFAULT NULL,
  `campaignstatus` varchar(200) DEFAULT NULL,
  `expectedrevenue` decimal(25,8) DEFAULT NULL,
  `budgetcost` decimal(25,8) DEFAULT NULL,
  `actualcost` decimal(25,8) DEFAULT NULL,
  `expectedresponse` varchar(200) DEFAULT NULL,
  `numsent` decimal(11,0) DEFAULT NULL,
  `product_id` int(19) DEFAULT NULL,
  `sponsor` varchar(255) DEFAULT NULL,
  `targetaudience` varchar(255) DEFAULT NULL,
  `targetsize` int(19) DEFAULT NULL,
  `expectedresponsecount` int(19) DEFAULT NULL,
  `expectedsalescount` int(19) DEFAULT NULL,
  `expectedroi` decimal(25,8) DEFAULT NULL,
  `actualresponsecount` int(19) DEFAULT NULL,
  `actualsalescount` int(19) DEFAULT NULL,
  `actualroi` decimal(25,8) DEFAULT NULL,
  `campaignid` int(19) NOT NULL,
  `closingdate` date DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`campaignid`),
  KEY `campaign_campaignstatus_idx` (`campaignstatus`),
  KEY `campaign_campaignname_idx` (`campaignname`),
  KEY `campaign_campaignid_idx` (`campaignid`),
  CONSTRAINT `fk_crmid_jo_campaign` FOREIGN KEY (`campaignid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaign`
--

LOCK TABLES `jo_campaign` WRITE;
/*!40000 ALTER TABLE `jo_campaign` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_campaign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaignaccountrel`
--

DROP TABLE IF EXISTS `jo_campaignaccountrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaignaccountrel` (
  `campaignid` int(19) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `campaignrelstatusid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaignaccountrel`
--

LOCK TABLES `jo_campaignaccountrel` WRITE;
/*!40000 ALTER TABLE `jo_campaignaccountrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_campaignaccountrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaigncontrel`
--

DROP TABLE IF EXISTS `jo_campaigncontrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaigncontrel` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  `contactid` int(19) NOT NULL DEFAULT '0',
  `campaignrelstatusid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`,`contactid`,`campaignrelstatusid`),
  KEY `campaigncontrel_contractid_idx` (`contactid`),
  CONSTRAINT `fk_2_jo_campaigncontrel` FOREIGN KEY (`contactid`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaigncontrel`
--

LOCK TABLES `jo_campaigncontrel` WRITE;
/*!40000 ALTER TABLE `jo_campaigncontrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_campaigncontrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaignleadrel`
--

DROP TABLE IF EXISTS `jo_campaignleadrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaignleadrel` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  `leadid` int(19) NOT NULL DEFAULT '0',
  `campaignrelstatusid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`,`leadid`,`campaignrelstatusid`),
  KEY `campaignleadrel_leadid_campaignid_idx` (`leadid`,`campaignid`),
  CONSTRAINT `fk_2_jo_campaignleadrel` FOREIGN KEY (`leadid`) REFERENCES `jo_leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaignleadrel`
--

LOCK TABLES `jo_campaignleadrel` WRITE;
/*!40000 ALTER TABLE `jo_campaignleadrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_campaignleadrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaignrelstatus`
--

DROP TABLE IF EXISTS `jo_campaignrelstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaignrelstatus` (
  `campaignrelstatusid` int(19) DEFAULT NULL,
  `campaignrelstatus` varchar(256) DEFAULT NULL,
  `sortorderid` int(19) DEFAULT NULL,
  `presence` int(19) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaignrelstatus`
--

LOCK TABLES `jo_campaignrelstatus` WRITE;
/*!40000 ALTER TABLE `jo_campaignrelstatus` DISABLE KEYS */;
INSERT INTO `jo_campaignrelstatus` VALUES (2,'Contacted - Successful',1,1,NULL),(3,'Contacted - Unsuccessful',2,1,NULL),(4,'Contacted - Never Contact Again',3,1,NULL);
/*!40000 ALTER TABLE `jo_campaignrelstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaignscf`
--

DROP TABLE IF EXISTS `jo_campaignscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaignscf` (
  `campaignid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`campaignid`),
  CONSTRAINT `fk_1_jo_campaignscf` FOREIGN KEY (`campaignid`) REFERENCES `jo_campaign` (`campaignid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaignscf`
--

LOCK TABLES `jo_campaignscf` WRITE;
/*!40000 ALTER TABLE `jo_campaignscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_campaignscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaignstatus`
--

DROP TABLE IF EXISTS `jo_campaignstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaignstatus` (
  `campaignstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `campaignstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`campaignstatusid`),
  KEY `campaignstatus_campaignstatus_idx` (`campaignstatus`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaignstatus`
--

LOCK TABLES `jo_campaignstatus` WRITE;
/*!40000 ALTER TABLE `jo_campaignstatus` DISABLE KEYS */;
INSERT INTO `jo_campaignstatus` VALUES (2,'Planning',1,15,1,NULL),(3,'Active',1,16,2,NULL),(4,'Inactive',1,17,3,NULL),(5,'Completed',1,18,4,NULL),(6,'Cancelled',1,19,5,NULL);
/*!40000 ALTER TABLE `jo_campaignstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_campaigntype`
--

DROP TABLE IF EXISTS `jo_campaigntype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_campaigntype` (
  `campaigntypeid` int(19) NOT NULL AUTO_INCREMENT,
  `campaigntype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`campaigntypeid`),
  UNIQUE KEY `campaigntype_campaigntype_idx` (`campaigntype`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_campaigntype`
--

LOCK TABLES `jo_campaigntype` WRITE;
/*!40000 ALTER TABLE `jo_campaigntype` DISABLE KEYS */;
INSERT INTO `jo_campaigntype` VALUES (2,'Conference',1,21,1,NULL),(3,'Webinar',1,22,2,NULL),(4,'Trade Show',1,23,3,NULL),(5,'Public Relations',1,24,4,NULL),(6,'Partners',1,25,5,NULL),(7,'Referral Program',1,26,6,NULL),(8,'Advertisement',1,27,7,NULL),(9,'Banner Ads',1,28,8,NULL),(10,'Direct Mail',1,29,9,NULL),(11,'Email',1,30,10,NULL),(12,'Telemarketing',1,31,11,NULL),(13,'Others',1,32,12,NULL);
/*!40000 ALTER TABLE `jo_campaigntype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_carrier`
--

DROP TABLE IF EXISTS `jo_carrier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_carrier` (
  `carrierid` int(19) NOT NULL AUTO_INCREMENT,
  `carrier` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`carrierid`),
  UNIQUE KEY `carrier_carrier_idx` (`carrier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_carrier`
--

LOCK TABLES `jo_carrier` WRITE;
/*!40000 ALTER TABLE `jo_carrier` DISABLE KEYS */;
INSERT INTO `jo_carrier` VALUES (1,'FedEx',1,33,0,NULL),(2,'UPS',1,34,1,NULL),(3,'USPS',1,35,2,NULL),(4,'DHL',1,36,3,NULL),(5,'BlueDart',1,37,4,NULL);
/*!40000 ALTER TABLE `jo_carrier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cntactivityrel`
--

DROP TABLE IF EXISTS `jo_cntactivityrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cntactivityrel` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactid`,`activityid`),
  KEY `cntactivityrel_contactid_idx` (`contactid`),
  KEY `cntactivityrel_activityid_idx` (`activityid`),
  CONSTRAINT `fk_2_jo_cntactivityrel` FOREIGN KEY (`contactid`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cntactivityrel`
--

LOCK TABLES `jo_cntactivityrel` WRITE;
/*!40000 ALTER TABLE `jo_cntactivityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_cntactivityrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_contactaddress`
--

DROP TABLE IF EXISTS `jo_contactaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_contactaddress` (
  `contactaddressid` int(19) NOT NULL DEFAULT '0',
  `mailingcity` varchar(40) DEFAULT NULL,
  `mailingstreet` varchar(250) DEFAULT NULL,
  `mailingcountry` varchar(40) DEFAULT NULL,
  `othercountry` varchar(30) DEFAULT NULL,
  `mailingstate` varchar(30) DEFAULT NULL,
  `mailingpobox` varchar(30) DEFAULT NULL,
  `othercity` varchar(40) DEFAULT NULL,
  `otherstate` varchar(50) DEFAULT NULL,
  `mailingzip` varchar(30) DEFAULT NULL,
  `otherzip` varchar(30) DEFAULT NULL,
  `otherstreet` varchar(250) DEFAULT NULL,
  `otherpobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`contactaddressid`),
  CONSTRAINT `fk_1_jo_contactaddress` FOREIGN KEY (`contactaddressid`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_contactaddress`
--

LOCK TABLES `jo_contactaddress` WRITE;
/*!40000 ALTER TABLE `jo_contactaddress` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_contactaddress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_contactdetails`
--

DROP TABLE IF EXISTS `jo_contactdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_contactdetails` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `contact_no` varchar(100) NOT NULL,
  `accountid` int(19) DEFAULT NULL,
  `salutation` varchar(200) DEFAULT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `lastname` varchar(80) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `department` varchar(30) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `reportsto` varchar(30) DEFAULT NULL,
  `training` varchar(50) DEFAULT NULL,
  `usertype` varchar(50) DEFAULT NULL,
  `contacttype` varchar(50) DEFAULT NULL,
  `otheremail` varchar(100) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `donotcall` varchar(3) DEFAULT NULL,
  `emailoptout` varchar(3) DEFAULT '0',
  `imagename` varchar(150) DEFAULT NULL,
  `reference` varchar(3) DEFAULT NULL,
  `notify_owner` varchar(3) DEFAULT '0',
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`contactid`),
  KEY `contactdetails_accountid_idx` (`accountid`),
  KEY `email_idx` (`email`),
  CONSTRAINT `fk_1_jo_contactdetails` FOREIGN KEY (`contactid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_contactdetails`
--

LOCK TABLES `jo_contactdetails` WRITE;
/*!40000 ALTER TABLE `jo_contactdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_contactdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_contactscf`
--

DROP TABLE IF EXISTS `jo_contactscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_contactscf` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactid`),
  CONSTRAINT `fk_1_jo_contactscf` FOREIGN KEY (`contactid`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_contactscf`
--

LOCK TABLES `jo_contactscf` WRITE;
/*!40000 ALTER TABLE `jo_contactscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_contactscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_contactsubdetails`
--

DROP TABLE IF EXISTS `jo_contactsubdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_contactsubdetails` (
  `contactsubscriptionid` int(19) NOT NULL DEFAULT '0',
  `homephone` varchar(50) DEFAULT NULL,
  `otherphone` varchar(50) DEFAULT NULL,
  `assistant` varchar(30) DEFAULT NULL,
  `assistantphone` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `laststayintouchrequest` int(30) DEFAULT '0',
  `laststayintouchsavedate` int(19) DEFAULT '0',
  `leadsource` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`contactsubscriptionid`),
  CONSTRAINT `fk_1_jo_contactsubdetails` FOREIGN KEY (`contactsubscriptionid`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_contactsubdetails`
--

LOCK TABLES `jo_contactsubdetails` WRITE;
/*!40000 ALTER TABLE `jo_contactsubdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_contactsubdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_contpotentialrel`
--

DROP TABLE IF EXISTS `jo_contpotentialrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_contpotentialrel` (
  `contactid` int(19) NOT NULL DEFAULT '0',
  `potentialid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`contactid`,`potentialid`),
  KEY `contpotentialrel_potentialid_idx` (`potentialid`),
  KEY `contpotentialrel_contactid_idx` (`contactid`),
  CONSTRAINT `fk_2_jo_contpotentialrel` FOREIGN KEY (`potentialid`) REFERENCES `jo_potential` (`potentialid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_contpotentialrel`
--

LOCK TABLES `jo_contpotentialrel` WRITE;
/*!40000 ALTER TABLE `jo_contpotentialrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_contpotentialrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_convertleadmapping`
--

DROP TABLE IF EXISTS `jo_convertleadmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_convertleadmapping` (
  `cfmid` int(19) NOT NULL AUTO_INCREMENT,
  `leadfid` int(19) NOT NULL,
  `accountfid` int(19) DEFAULT NULL,
  `contactfid` int(19) DEFAULT NULL,
  `potentialfid` int(19) DEFAULT NULL,
  `editable` int(19) DEFAULT '1',
  PRIMARY KEY (`cfmid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_convertleadmapping`
--

LOCK TABLES `jo_convertleadmapping` WRITE;
/*!40000 ALTER TABLE `jo_convertleadmapping` DISABLE KEYS */;
INSERT INTO `jo_convertleadmapping` VALUES (1,43,1,0,110,0),(2,49,14,0,0,1),(3,40,3,69,0,1),(4,44,5,77,0,1),(5,52,13,0,0,1),(6,46,9,80,0,0),(7,48,4,0,0,1),(8,61,26,98,0,1),(9,60,30,0,0,1),(10,62,32,104,0,1),(11,63,28,100,0,1),(12,59,24,96,0,1),(13,64,34,106,0,1),(14,61,27,0,0,1),(15,60,31,0,0,1),(16,62,33,0,0,1),(17,63,29,0,0,1),(18,59,25,0,0,1),(19,64,35,0,0,1),(20,65,36,109,125,1),(21,37,0,66,0,1),(22,38,0,67,0,0),(23,41,0,70,0,0),(24,42,0,71,0,1),(25,45,0,76,0,1),(26,55,0,83,0,1),(27,47,0,74,117,1),(28,50,0,0,0,1),(29,53,10,0,0,1),(30,51,17,0,0,1);
/*!40000 ALTER TABLE `jo_convertleadmapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_convertpotentialmapping`
--

DROP TABLE IF EXISTS `jo_convertpotentialmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_convertpotentialmapping` (
  `cfmid` int(19) NOT NULL AUTO_INCREMENT,
  `potentialfid` int(19) NOT NULL,
  `projectfid` int(19) DEFAULT NULL,
  `editable` int(11) DEFAULT '1',
  PRIMARY KEY (`cfmid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_convertpotentialmapping`
--

LOCK TABLES `jo_convertpotentialmapping` WRITE;
/*!40000 ALTER TABLE `jo_convertpotentialmapping` DISABLE KEYS */;
INSERT INTO `jo_convertpotentialmapping` VALUES (1,110,579,NULL),(2,125,595,NULL);
/*!40000 ALTER TABLE `jo_convertpotentialmapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_crmentity`
--

DROP TABLE IF EXISTS `jo_crmentity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_crmentity` (
  `crmid` int(19) NOT NULL,
  `smcreatorid` int(19) NOT NULL DEFAULT '0',
  `smownerid` int(19) NOT NULL DEFAULT '0',
  `modifiedby` int(19) NOT NULL DEFAULT '0',
  `setype` varchar(100) DEFAULT NULL,
  `description` mediumtext,
  `createdtime` datetime NOT NULL,
  `modifiedtime` datetime NOT NULL,
  `viewedtime` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `version` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `smgroupid` int(19) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`crmid`),
  KEY `crmentity_smcreatorid_idx` (`smcreatorid`),
  KEY `crmentity_modifiedby_idx` (`modifiedby`),
  KEY `crmentity_deleted_idx` (`deleted`),
  KEY `crm_ownerid_del_setype_idx` (`smownerid`,`deleted`,`setype`),
  KEY `jo_crmentity_labelidx` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_crmentity`
--

LOCK TABLES `jo_crmentity` WRITE;
/*!40000 ALTER TABLE `jo_crmentity` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_crmentity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_crmentity_user_field`
--

DROP TABLE IF EXISTS `jo_crmentity_user_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_crmentity_user_field` (
  `recordid` int(19) NOT NULL,
  `userid` int(19) NOT NULL,
  `starred` varchar(100) DEFAULT NULL,
  UNIQUE KEY `record_user_idx` (`recordid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_crmentity_user_field`
--

LOCK TABLES `jo_crmentity_user_field` WRITE;
/*!40000 ALTER TABLE `jo_crmentity_user_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_crmentity_user_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_crmentityrel`
--

DROP TABLE IF EXISTS `jo_crmentityrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_crmentityrel` (
  `crmid` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `relcrmid` int(11) NOT NULL,
  `relmodule` varchar(100) NOT NULL,
  KEY `crmid_idx` (`crmid`),
  KEY `relcrmid_idx` (`relcrmid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_crmentityrel`
--

LOCK TABLES `jo_crmentityrel` WRITE;
/*!40000 ALTER TABLE `jo_crmentityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_crmentityrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_crmsetup`
--

DROP TABLE IF EXISTS `jo_crmsetup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_crmsetup` (
  `userid` int(11) DEFAULT NULL,
  `setup_status` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_crmsetup`
--

LOCK TABLES `jo_crmsetup` WRITE;
/*!40000 ALTER TABLE `jo_crmsetup` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_crmsetup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cron_task`
--

DROP TABLE IF EXISTS `jo_cron_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cron_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `handler_file` varchar(100) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `laststart` int(11) unsigned DEFAULT NULL,
  `lastend` int(11) unsigned DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `handler_file` (`handler_file`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cron_task`
--

LOCK TABLES `jo_cron_task` WRITE;
/*!40000 ALTER TABLE `jo_cron_task` DISABLE KEYS */;
INSERT INTO `jo_cron_task` VALUES (1,'Workflow','cron/modules/Workflow/workflow.service',900,NULL,NULL,1,'workflow',1,'Recommended frequency for Workflow is 15 mins'),(2,'RecurringInvoice','cron/modules/SalesOrder/RecurringInvoice.service',43200,NULL,NULL,1,'SalesOrder',2,'Recommended frequency for RecurringInvoice is 12 hours'),(3,'SendReminder','cron/SendReminder.service',900,NULL,NULL,1,'Calendar',3,'Recommended frequency for SendReminder is 15 mins'),(5,'MailScanner','cron/MailScanner.service',900,NULL,NULL,1,'Settings',5,'Recommended frequency for MailScanner is 15 mins'),(7,'ScheduleReports','cron/modules/Reports/ScheduleReports.service',900,NULL,NULL,1,'Reports',7,'Recommended frequency for ScheduleReports is 15 mins'),(8,'Scheduled Import','cron/modules/Import/ScheduledImport.service',900,NULL,NULL,0,'Import',8,'Recommended frequency for MailScanner is 15 mins');
/*!40000 ALTER TABLE `jo_cron_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currencies`
--

DROP TABLE IF EXISTS `jo_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currencies` (
  `currencyid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(200) DEFAULT NULL,
  `currency_code` varchar(50) DEFAULT NULL,
  `currency_symbol` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`currencyid`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currencies`
--

LOCK TABLES `jo_currencies` WRITE;
/*!40000 ALTER TABLE `jo_currencies` DISABLE KEYS */;
INSERT INTO `jo_currencies` VALUES (1,'Albania, Leke','ALL','Lek'),(2,'Argentina, Pesos','ARS','$'),(3,'Aruba, Guilders','AWG','ƒ'),(4,'Australia, Dollars','AUD','$'),(5,'Azerbaijan, New Manats','AZN','ман'),(6,'Bahamas, Dollars','BSD','$'),(7,'Bahrain, Dinar','BHD','BD'),(8,'Barbados, Dollars','BBD','$'),(9,'Belarus, Rubles','BYR','p.'),(10,'Belize, Dollars','BZD','BZ$'),(11,'Bermuda, Dollars','BMD','$'),(12,'Bolivia, Bolivianos','BOB','$b'),(13,'China, Yuan Renminbi','CNY','¥'),(14,'Convertible Marka','BAM','KM'),(15,'Botswana, Pulas','BWP','P'),(16,'Bulgaria, Leva','BGN','лв'),(17,'Brazil, Reais','BRL','R$'),(18,'Great Britain Pounds','GBP','£'),(19,'Brunei Darussalam, Dollars','BND','$'),(20,'Canada, Dollars','CAD','$'),(21,'Cayman Islands, Dollars','KYD','$'),(22,'Chile, Pesos','CLP','$'),(23,'Colombia, Pesos','COP','$'),(24,'Costa Rica, Colón','CRC','₡'),(25,'Croatia, Kuna','HRK','kn'),(26,'Cuba, Pesos','CUP','₱'),(27,'Czech Republic, Koruny','CZK','Kč'),(28,'Cyprus, Pounds','CYP','£'),(29,'Denmark, Kroner','DKK','kr'),(30,'Dominican Republic, Pesos','DOP','RD$'),(31,'East Caribbean, Dollars','XCD','$'),(32,'Egypt, Pounds','EGP','E£'),(33,'El Salvador, Colón','SVC','₡'),(34,'England, Pounds','GBP','£'),(35,'Estonia, Krooni','EEK','kr'),(36,'Euro','EUR','€'),(37,'Falkland Islands, Pounds','FKP','£'),(38,'Fiji, Dollars','FJD','$'),(39,'Ghana, Cedis','GHC','¢'),(40,'Gibraltar, Pounds','GIP','£'),(41,'Guatemala, Quetzales','GTQ','Q'),(42,'Guernsey, Pounds','GGP','£'),(43,'Guyana, Dollars','GYD','$'),(44,'Honduras, Lempiras','HNL','L'),(45,'Hong Kong, Dollars','HKD','HK$'),(46,'Hungary, Forint','HUF','Ft'),(47,'Iceland, Krona','ISK','kr'),(48,'India, Rupees','INR','₹'),(49,'Indonesia, Rupiahs','IDR','Rp'),(50,'Iran, Rials','IRR','﷼'),(51,'Isle of Man, Pounds','IMP','£'),(52,'Israel, New Shekels','ILS','₪'),(53,'Jamaica, Dollars','JMD','J$'),(54,'Japan, Yen','JPY','¥'),(55,'Jersey, Pounds','JEP','£'),(56,'Jordan, Dinar','JOD','JOD'),(57,'Kazakhstan, Tenge','KZT','〒'),(58,'Kenya, Shilling','KES','KES'),(59,'Korea (North), Won','KPW','₩'),(60,'Korea (South), Won','KRW','₩'),(61,'Kuwait, Dinar','KWD','KWD'),(62,'Kyrgyzstan, Soms','KGS','лв'),(63,'Laos, Kips','LAK','₭'),(64,'Latvia, Lati','LVL','Ls'),(65,'Lebanon, Pounds','LBP','£'),(66,'Liberia, Dollars','LRD','$'),(67,'Switzerland Francs','CHF','CHF'),(68,'Lithuania, Litai','LTL','Lt'),(69,'MADAGASCAR, Malagasy Ariary','MGA','MGA'),(70,'Macedonia, Denars','MKD','ден'),(71,'Malaysia, Ringgits','MYR','RM'),(72,'Malta, Liri','MTL','₤'),(73,'Mauritius, Rupees','MUR','₨'),(74,'Mexico, Pesos','MXN','$'),(75,'Mongolia, Tugriks','MNT','₮'),(76,'Mozambique, Meticais','MZN','MT'),(77,'Namibia, Dollars','NAD','$'),(78,'Nepal, Rupees','NPR','₨'),(79,'Netherlands Antilles, Guilders','ANG','ƒ'),(80,'New Zealand, Dollars','NZD','$'),(81,'Nicaragua, Cordobas','NIO','C$'),(82,'Nigeria, Nairas','NGN','₦'),(83,'North Korea, Won','KPW','₩'),(84,'Norway, Krone','NOK','kr'),(85,'Oman, Rials','OMR','﷼'),(86,'Pakistan, Rupees','PKR','₨'),(87,'Panama, Balboa','PAB','B/.'),(88,'Paraguay, Guarani','PYG','Gs'),(89,'Peru, Nuevos Soles','PEN','S/.'),(90,'Philippines, Pesos','PHP','Php'),(91,'Poland, Zlotych','PLN','zł'),(92,'Qatar, Rials','QAR','﷼'),(93,'Romania, New Lei','RON','lei'),(94,'Russia, Rubles','RUB','руб'),(95,'Saint Helena, Pounds','SHP','£'),(96,'Saudi Arabia, Riyals','SAR','﷼'),(97,'Serbia, Dinars','RSD','Дин.'),(98,'Seychelles, Rupees','SCR','₨'),(99,'Singapore, Dollars','SGD','$'),(100,'Solomon Islands, Dollars','SBD','$'),(101,'Somalia, Shillings','SOS','S'),(102,'South Africa, Rand','ZAR','R'),(103,'South Korea, Won','KRW','₩'),(104,'Sri Lanka, Rupees','LKR','₨'),(105,'Sweden, Kronor','SEK','kr'),(106,'Switzerland, Francs','CHF','CHF'),(107,'Suriname, Dollars','SRD','$'),(108,'Syria, Pounds','SYP','£'),(109,'Taiwan, New Dollars','TWD','NT$'),(110,'Thailand, Baht','THB','฿'),(111,'Trinidad and Tobago, Dollars','TTD','TT$'),(112,'Turkey, New Lira','TRY','YTL'),(113,'Turkey, Liras','TRL','₤'),(114,'Tuvalu, Dollars','TVD','$'),(115,'Ukraine, Hryvnia','UAH','₴'),(116,'United Arab Emirates, Dirham','AED','AED'),(117,'United Kingdom, Pounds','GBP','£'),(118,'United Republic of Tanzania, Shilling','TZS','TZS'),(119,'USA, Dollars','USD','$'),(120,'Uruguay, Pesos','UYU','$U'),(121,'Uzbekistan, Sums','UZS','лв'),(122,'Venezuela, Bolivares Fuertes','VEF','Bs'),(123,'Vietnam, Dong','VND','₫'),(124,'Zambia, Kwacha','ZMK','ZMK'),(125,'Yemen, Rials','YER','﷼'),(126,'Zimbabwe Dollars','ZWD','Z$'),(127,'Malawi, Kwacha','MWK','MK'),(128,'Tunisian, Dinar','TD','TD'),(129,'Moroccan, Dirham','MAD','DH'),(130,'Iraqi Dinar','IQD','ID'),(131,'Maldivian Ruffiya','MVR','MVR'),(132,'Ugandan Shilling','UGX','Sh'),(133,'Sudanese Pound','SDG','£'),(134,'CFA Franc BCEAO','XOF','CFA'),(135,'CFA Franc BEAC','XAF','CFA'),(136,'Haiti, Gourde','HTG','G'),(137,'Libya, Dinar','LYD','LYD'),(138,'CFP Franc','XPF','F');
/*!40000 ALTER TABLE `jo_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currency`
--

DROP TABLE IF EXISTS `jo_currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency` (
  `currencyid` int(19) NOT NULL AUTO_INCREMENT,
  `currency` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currencyid`),
  UNIQUE KEY `currency_currency_idx` (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency`
--

LOCK TABLES `jo_currency` WRITE;
/*!40000 ALTER TABLE `jo_currency` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_currency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currency_decimal_separator`
--

DROP TABLE IF EXISTS `jo_currency_decimal_separator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency_decimal_separator` (
  `currency_decimal_separatorid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_decimal_separator` varchar(2) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_decimal_separatorid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency_decimal_separator`
--

LOCK TABLES `jo_currency_decimal_separator` WRITE;
/*!40000 ALTER TABLE `jo_currency_decimal_separator` DISABLE KEYS */;
INSERT INTO `jo_currency_decimal_separator` VALUES (1,'.',0,1),(2,',',1,1),(3,'\'',2,1),(4,' ',3,1),(5,'$',4,1);
/*!40000 ALTER TABLE `jo_currency_decimal_separator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currency_grouping_pattern`
--

DROP TABLE IF EXISTS `jo_currency_grouping_pattern`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency_grouping_pattern` (
  `currency_grouping_patternid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_grouping_pattern` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_grouping_patternid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency_grouping_pattern`
--

LOCK TABLES `jo_currency_grouping_pattern` WRITE;
/*!40000 ALTER TABLE `jo_currency_grouping_pattern` DISABLE KEYS */;
INSERT INTO `jo_currency_grouping_pattern` VALUES (1,'123,456,789',0,1),(2,'123456789',1,1),(3,'123456,789',2,1),(4,'12,34,56,789',3,1);
/*!40000 ALTER TABLE `jo_currency_grouping_pattern` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currency_grouping_separator`
--

DROP TABLE IF EXISTS `jo_currency_grouping_separator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency_grouping_separator` (
  `currency_grouping_separatorid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_grouping_separator` varchar(2) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_grouping_separatorid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency_grouping_separator`
--

LOCK TABLES `jo_currency_grouping_separator` WRITE;
/*!40000 ALTER TABLE `jo_currency_grouping_separator` DISABLE KEYS */;
INSERT INTO `jo_currency_grouping_separator` VALUES (1,',',0,1),(2,'.',1,1),(3,'\'',2,1),(4,' ',3,1),(5,'$',4,1);
/*!40000 ALTER TABLE `jo_currency_grouping_separator` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_currency_info`
--

DROP TABLE IF EXISTS `jo_currency_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(100) DEFAULT NULL,
  `currency_code` varchar(100) DEFAULT NULL,
  `currency_symbol` varchar(30) DEFAULT NULL,
  `conversion_rate` decimal(12,5) DEFAULT NULL,
  `currency_status` varchar(25) DEFAULT NULL,
  `defaultid` varchar(10) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency_info`
--

LOCK TABLES `jo_currency_info` WRITE;
/*!40000 ALTER TABLE `jo_currency_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_currency_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_currency_symbol_placement`
--

DROP TABLE IF EXISTS `jo_currency_symbol_placement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_currency_symbol_placement` (
  `currency_symbol_placementid` int(19) NOT NULL AUTO_INCREMENT,
  `currency_symbol_placement` varchar(30) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_symbol_placementid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_currency_symbol_placement`
--

LOCK TABLES `jo_currency_symbol_placement` WRITE;
/*!40000 ALTER TABLE `jo_currency_symbol_placement` DISABLE KEYS */;
INSERT INTO `jo_currency_symbol_placement` VALUES (1,'$1.0',0,1),(2,'1.0$',1,1);
/*!40000 ALTER TABLE `jo_currency_symbol_placement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customaction`
--

DROP TABLE IF EXISTS `jo_customaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customaction` (
  `cvid` int(19) NOT NULL,
  `subject` varchar(250) NOT NULL,
  `module` varchar(50) NOT NULL,
  `content` text,
  KEY `customaction_cvid_idx` (`cvid`),
  CONSTRAINT `fk_1_jo_customaction` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customaction`
--

LOCK TABLES `jo_customaction` WRITE;
/*!40000 ALTER TABLE `jo_customaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_customaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerdetails`
--

DROP TABLE IF EXISTS `jo_customerdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerdetails` (
  `customerid` int(19) NOT NULL,
  `portal` varchar(3) DEFAULT NULL,
  `support_start_date` date DEFAULT NULL,
  `support_end_date` date DEFAULT NULL,
  PRIMARY KEY (`customerid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerdetails`
--

LOCK TABLES `jo_customerdetails` WRITE;
/*!40000 ALTER TABLE `jo_customerdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_customerdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerportal_fields`
--

DROP TABLE IF EXISTS `jo_customerportal_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerportal_fields` (
  `tabid` int(19) NOT NULL,
  `fieldinfo` text,
  `records_visible` int(1) DEFAULT NULL,
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerportal_fields`
--

LOCK TABLES `jo_customerportal_fields` WRITE;
/*!40000 ALTER TABLE `jo_customerportal_fields` DISABLE KEYS */;
INSERT INTO `jo_customerportal_fields` VALUES (4,'{\"lastname\":1,\"assigned_user_id\":1}',1),(6,'{\"accountname\":1,\"assigned_user_id\":1}',1),(8,'{\"notes_title\":1,\"assigned_user_id\":1,\"filename\":0}',1),(13,'{\"ticket_title\":1,\"assigned_user_id\":1,\"ticketpriorities\":1,\"ticketstatus\":1,\"description\":1,\"product_id\":1,\"ticketseverities\":1,\"ticketcategories\":1}',1),(14,'{\"productname\":1,\"assigned_user_id\":1}',1),(20,'{\"subject\":1,\"quotestage\":1,\"account_id\":1,\"assigned_user_id\":1,\"bill_street\":1,\"ship_street\":1}',1),(23,'{\"subject\":1,\"account_id\":1,\"assigned_user_id\":1,\"bill_street\":1,\"ship_street\":1}',1),(36,'{\"servicename\":1}',1),(42,'{\"projectmilestonename\":1,\"projectid\":1,\"assigned_user_id\":1}',1),(43,'{\"projecttaskname\":1,\"projectid\":1,\"assigned_user_id\":1}',1),(44,'{\"projectname\":1,\"assigned_user_id\":1}',1);
/*!40000 ALTER TABLE `jo_customerportal_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerportal_prefs`
--

DROP TABLE IF EXISTS `jo_customerportal_prefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerportal_prefs` (
  `tabid` int(19) NOT NULL,
  `prefkey` varchar(100) NOT NULL,
  `prefvalue` int(20) DEFAULT NULL,
  PRIMARY KEY (`tabid`,`prefkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerportal_prefs`
--

LOCK TABLES `jo_customerportal_prefs` WRITE;
/*!40000 ALTER TABLE `jo_customerportal_prefs` DISABLE KEYS */;
INSERT INTO `jo_customerportal_prefs` VALUES (0,'defaultassignee',1),(0,'userid',1),(4,'showrelatedinfo',1),(6,'showrelatedinfo',1),(8,'showrelatedinfo',1),(13,'showrelatedinfo',1),(14,'showrelatedinfo',1),(20,'showrelatedinfo',1),(23,'showrelatedinfo',1),(36,'showrelatedinfo',1),(42,'showrelatedinfo',1),(43,'showrelatedinfo',1),(44,'showrelatedinfo',1);
/*!40000 ALTER TABLE `jo_customerportal_prefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerportal_relatedmoduleinfo`
--

DROP TABLE IF EXISTS `jo_customerportal_relatedmoduleinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerportal_relatedmoduleinfo` (
  `tabid` int(19) NOT NULL,
  `relatedmodules` text,
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerportal_relatedmoduleinfo`
--

LOCK TABLES `jo_customerportal_relatedmoduleinfo` WRITE;
/*!40000 ALTER TABLE `jo_customerportal_relatedmoduleinfo` DISABLE KEYS */;
INSERT INTO `jo_customerportal_relatedmoduleinfo` VALUES (8,'[{\"name\":\"History\",\"value\":1}]'),(13,'[{\"name\":\"History\",\"value\":1},{\"name\":\"ModComments\",\"value\":1},{\"name\":\"Documents\",\"value\":1}]'),(14,'[{\"name\":\"History\",\"value\":1}]'),(20,'[{\"name\":\"History\",\"value\":1}]'),(23,'[{\"name\":\"History\",\"value\":1}]'),(36,'[{\"name\":\"History\",\"value\":1}]'),(42,'[{\"name\":\"History\",\"value\":1}]'),(43,'[{\"name\":\"History\",\"value\":1},{\"name\":\"ModComments\",\"value\":1}]'),(44,'[{\"name\":\"History\",\"value\":1},{\"name\":\"ModComments\",\"value\":1},{\"name\":\"ProjectTask\",\"value\":1},{\"name\":\"ProjectMilestone\",\"value\":1},{\"name\":\"Documents\",\"value\":1}]');
/*!40000 ALTER TABLE `jo_customerportal_relatedmoduleinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerportal_settings`
--

DROP TABLE IF EXISTS `jo_customerportal_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerportal_settings` (
  `id` int(11) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `default_assignee` int(11) DEFAULT NULL,
  `support_notification` int(11) DEFAULT NULL,
  `announcement` text,
  `shortcuts` text,
  `widgets` text,
  `charts` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerportal_settings`
--

LOCK TABLES `jo_customerportal_settings` WRITE;
/*!40000 ALTER TABLE `jo_customerportal_settings` DISABLE KEYS */;
INSERT INTO `jo_customerportal_settings` VALUES (1,NULL,1,NULL,NULL,'{\"Documents\":{\"LBL_ADD_DOCUMENT\":1},\"HelpDesk\":{\"LBL_CREATE_TICKET\":1,\"LBL_OPEN_TICKETS\":1}}','{\"widgets\":{\"HelpDesk\":1,\"Documents\":1,\"Faq\":1}}','{\"charts\":{\"OpenTicketsByPriority\":1,\"TicketsClosureTimeByPriority\":1}}');
/*!40000 ALTER TABLE `jo_customerportal_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customerportal_tabs`
--

DROP TABLE IF EXISTS `jo_customerportal_tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customerportal_tabs` (
  `tabid` int(19) NOT NULL,
  `visible` int(1) DEFAULT '1',
  `sequence` int(1) DEFAULT NULL,
  `createrecord` tinyint(1) NOT NULL DEFAULT '0',
  `editrecord` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customerportal_tabs`
--

LOCK TABLES `jo_customerportal_tabs` WRITE;
/*!40000 ALTER TABLE `jo_customerportal_tabs` DISABLE KEYS */;
INSERT INTO `jo_customerportal_tabs` VALUES (4,0,8,0,1),(6,0,9,0,1),(8,1,7,1,0),(13,1,2,1,1),(14,1,5,0,0),(20,1,4,0,0),(23,1,3,0,0),(36,1,6,0,0),(42,1,10,0,0),(43,1,11,0,0),(44,1,12,0,0);
/*!40000 ALTER TABLE `jo_customerportal_tabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_customview`
--

DROP TABLE IF EXISTS `jo_customview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_customview` (
  `cvid` int(19) NOT NULL,
  `viewname` varchar(100) NOT NULL,
  `setdefault` int(1) DEFAULT '0',
  `setmetrics` int(1) DEFAULT '0',
  `entitytype` varchar(25) NOT NULL,
  `status` int(1) DEFAULT '1',
  `userid` int(19) DEFAULT '1',
  PRIMARY KEY (`cvid`),
  KEY `customview_entitytype_idx` (`entitytype`),
  CONSTRAINT `fk_1_jo_customview` FOREIGN KEY (`entitytype`) REFERENCES `jo_tab` (`name`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_customview`
--

LOCK TABLES `jo_customview` WRITE;
/*!40000 ALTER TABLE `jo_customview` DISABLE KEYS */;
INSERT INTO `jo_customview` VALUES (1,'All',1,0,'Leads',0,1),(2,'Hot Leads',0,1,'Leads',3,1),(3,'This Month Leads',0,0,'Leads',3,1),(4,'All',1,0,'Accounts',0,1),(5,'Prospect Accounts',0,1,'Accounts',3,1),(6,'New This Week',0,0,'Accounts',3,1),(7,'All',1,0,'Contacts',0,1),(8,'Contacts Address',0,0,'Contacts',3,1),(9,'Todays Birthday',0,0,'Contacts',3,1),(10,'All',1,0,'Potentials',0,1),(11,'Potentials Won',0,1,'Potentials',3,1),(12,'Prospecting',0,0,'Potentials',3,1),(13,'All',1,0,'HelpDesk',0,1),(14,'Open Tickets',0,1,'HelpDesk',3,1),(15,'High Prioriy Tickets',0,0,'HelpDesk',3,1),(16,'All',1,0,'Quotes',0,1),(17,'Open Quotes',0,1,'Quotes',3,1),(18,'Rejected Quotes',0,0,'Quotes',3,1),(19,'All',1,0,'Calendar',0,1),(20,'All',1,0,'Emails',0,1),(21,'All',1,0,'Invoice',0,1),(22,'All',1,0,'Documents',0,1),(23,'All',1,0,'PriceBooks',0,1),(24,'All',1,0,'Products',0,1),(25,'All',1,0,'PurchaseOrder',0,1),(26,'All',1,0,'SalesOrder',0,1),(27,'All',1,0,'Vendors',0,1),(28,'All',1,0,'Campaigns',0,1),(29,'Open Purchase Orders',0,0,'PurchaseOrder',3,1),(30,'Received Purchase Orders',0,0,'PurchaseOrder',3,1),(31,'Open Invoices',0,0,'Invoice',3,1),(32,'Paid Invoices',0,0,'Invoice',3,1),(33,'Pending Sales Orders',0,0,'SalesOrder',3,1),(43,'All',1,0,'PDFMaker',0,1),(44,'All',1,0,'EmailPlus',0,1),(45,'All',1,0,'PBXManager',0,1),(46,'All',1,0,'Services',0,1),(63,'All',0,0,'ModComments',0,1),(64,'All',1,0,'ProjectMilestone',0,1),(65,'All',1,0,'ProjectTask',0,1),(66,'All',1,0,'Project',0,1);
/*!40000 ALTER TABLE `jo_customview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cv2group`
--

DROP TABLE IF EXISTS `jo_cv2group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cv2group` (
  `cvid` int(25) NOT NULL,
  `groupid` int(25) NOT NULL,
  KEY `jo_cv2group_ibfk_1` (`cvid`),
  KEY `jo_groups_ibfk_1` (`groupid`),
  CONSTRAINT `jo_customview_ibfk_2` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
  CONSTRAINT `jo_groups_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cv2group`
--

LOCK TABLES `jo_cv2group` WRITE;
/*!40000 ALTER TABLE `jo_cv2group` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_cv2group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cv2role`
--

DROP TABLE IF EXISTS `jo_cv2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cv2role` (
  `cvid` int(25) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  KEY `jo_cv2role_ibfk_1` (`cvid`),
  KEY `jo_role_ibfk_1` (`roleid`),
  CONSTRAINT `jo_customview_ibfk_3` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
  CONSTRAINT `jo_role_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cv2role`
--

LOCK TABLES `jo_cv2role` WRITE;
/*!40000 ALTER TABLE `jo_cv2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_cv2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cv2rs`
--

DROP TABLE IF EXISTS `jo_cv2rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cv2rs` (
  `cvid` int(25) NOT NULL,
  `rsid` varchar(255) NOT NULL,
  KEY `jo_cv2role_ibfk_1` (`cvid`),
  KEY `jo_rolesd_ibfk_1` (`rsid`),
  CONSTRAINT `jo_customview_ibfk_4` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
  CONSTRAINT `jo_rolesd_ibfk_1` FOREIGN KEY (`rsid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cv2rs`
--

LOCK TABLES `jo_cv2rs` WRITE;
/*!40000 ALTER TABLE `jo_cv2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_cv2rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cv2users`
--

DROP TABLE IF EXISTS `jo_cv2users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cv2users` (
  `cvid` int(25) NOT NULL,
  `userid` int(25) NOT NULL,
  KEY `jo_cv2users_ibfk_1` (`cvid`),
  KEY `jo_users_ibfk_1` (`userid`),
  CONSTRAINT `jo_customview_ibfk_1` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
  CONSTRAINT `jo_users_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cv2users`
--

LOCK TABLES `jo_cv2users` WRITE;
/*!40000 ALTER TABLE `jo_cv2users` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_cv2users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cvadvfilter`
--

DROP TABLE IF EXISTS `jo_cvadvfilter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cvadvfilter` (
  `cvid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `comparator` varchar(20) DEFAULT NULL,
  `value` varchar(512) DEFAULT NULL,
  `groupid` int(11) DEFAULT '1',
  `column_condition` varchar(255) DEFAULT 'and',
  PRIMARY KEY (`cvid`,`columnindex`),
  KEY `cvadvfilter_cvid_idx` (`cvid`),
  CONSTRAINT `fk_1_jo_cvadvfilter` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cvadvfilter`
--

LOCK TABLES `jo_cvadvfilter` WRITE;
/*!40000 ALTER TABLE `jo_cvadvfilter` DISABLE KEYS */;
INSERT INTO `jo_cvadvfilter` VALUES (2,0,'jo_leaddetails:leadstatus:leadstatus:Leads_Lead_Status:V','e','Hot',1,'and'),(5,0,'jo_account:account_type:accounttype:Accounts_Type:V','e','Prospect',1,'and'),(11,0,'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V','e','Closed Won',1,'and'),(12,0,'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V','e','Prospecting',1,'and'),(14,0,'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V','n','Closed',1,'and'),(15,0,'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V','e','High',1,'and'),(17,0,'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V','n','Accepted',1,'and'),(17,1,'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V','n','Rejected',1,'and'),(18,0,'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V','e','Rejected',1,'and'),(29,0,'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V','e','Created, Approved, Sent',1,'and'),(30,0,'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V','e','Paid',1,'and'),(31,0,'jo_salesorder:sostatus:sostatus:SalesOrder_Status:V','e','Created, Approved',1,'and');
/*!40000 ALTER TABLE `jo_cvadvfilter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cvadvfilter_grouping`
--

DROP TABLE IF EXISTS `jo_cvadvfilter_grouping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cvadvfilter_grouping` (
  `groupid` int(11) NOT NULL,
  `cvid` int(19) NOT NULL,
  `group_condition` varchar(255) DEFAULT NULL,
  `condition_expression` text,
  PRIMARY KEY (`groupid`,`cvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cvadvfilter_grouping`
--

LOCK TABLES `jo_cvadvfilter_grouping` WRITE;
/*!40000 ALTER TABLE `jo_cvadvfilter_grouping` DISABLE KEYS */;
INSERT INTO `jo_cvadvfilter_grouping` VALUES (1,2,'',''),(1,5,'',''),(1,11,'',''),(1,12,'',''),(1,14,'',''),(1,15,'',''),(1,17,'',''),(1,18,'',''),(1,29,'',''),(1,30,'',''),(1,31,'','');
/*!40000 ALTER TABLE `jo_cvadvfilter_grouping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cvcolumnlist`
--

DROP TABLE IF EXISTS `jo_cvcolumnlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cvcolumnlist` (
  `cvid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  PRIMARY KEY (`cvid`,`columnindex`),
  KEY `cvcolumnlist_columnindex_idx` (`columnindex`),
  KEY `cvcolumnlist_cvid_idx` (`cvid`),
  CONSTRAINT `fk_1_jo_cvcolumnlist` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cvcolumnlist`
--

LOCK TABLES `jo_cvcolumnlist` WRITE;
/*!40000 ALTER TABLE `jo_cvcolumnlist` DISABLE KEYS */;
INSERT INTO `jo_cvcolumnlist` VALUES (1,1,'jo_leaddetails:firstname:firstname:Leads_First_Name:V'),(1,2,'jo_leaddetails:lastname:lastname:Leads_Last_Name:V'),(1,3,'jo_leaddetails:company:company:Leads_Company:V'),(1,4,'jo_leadaddress:phone:phone:Leads_Phone:V'),(1,5,'jo_leadsubdetails:website:website:Leads_Website:V'),(1,6,'jo_leaddetails:email:email:Leads_Email:V'),(1,7,'jo_crmentity:smownerid:assigned_user_id:Leads_Assigned_To:V'),(2,0,'jo_leaddetails:firstname:firstname:Leads_First_Name:V'),(2,1,'jo_leaddetails:lastname:lastname:Leads_Last_Name:V'),(2,2,'jo_leaddetails:company:company:Leads_Company:V'),(2,3,'jo_leaddetails:leadsource:leadsource:Leads_Lead_Source:V'),(2,4,'jo_leadsubdetails:website:website:Leads_Website:V'),(2,5,'jo_leaddetails:email:email:Leads_Email:E'),(3,0,'jo_leaddetails:firstname:firstname:Leads_First_Name:V'),(3,1,'jo_leaddetails:lastname:lastname:Leads_Last_Name:V'),(3,2,'jo_leaddetails:company:company:Leads_Company:V'),(3,3,'jo_leaddetails:leadsource:leadsource:Leads_Lead_Source:V'),(3,4,'jo_leadsubdetails:website:website:Leads_Website:V'),(3,5,'jo_leaddetails:email:email:Leads_Email:E'),(4,1,'jo_account:accountname:accountname:Accounts_Account_Name:V'),(4,2,'jo_accountbillads:bill_city:bill_city:Accounts_Billing_City:V'),(4,3,'jo_account:website:website:Accounts_Website:V'),(4,4,'jo_account:phone:phone:Accounts_Phone:V'),(4,5,'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),(5,0,'jo_account:accountname:accountname:Accounts_Account_Name:V'),(5,1,'jo_account:phone:phone:Accounts_Phone:V'),(5,2,'jo_account:website:website:Accounts_Website:V'),(5,3,'jo_account:rating:rating:Accounts_Rating:V'),(5,4,'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),(6,0,'jo_account:accountname:accountname:Accounts_Account_Name:V'),(6,1,'jo_account:phone:phone:Accounts_Phone:V'),(6,2,'jo_account:website:website:Accounts_Website:V'),(6,3,'jo_accountbillads:bill_city:bill_city:Accounts_City:V'),(6,4,'jo_crmentity:smownerid:assigned_user_id:Accounts_Assigned_To:V'),(7,1,'jo_contactdetails:firstname:firstname:Contacts_First_Name:V'),(7,2,'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V'),(7,3,'jo_contactdetails:title:title:Contacts_Title:V'),(7,4,'jo_contactdetails:accountid:account_id:Contacts_Account_Name:V'),(7,5,'jo_contactdetails:email:email:Contacts_Email:V'),(7,6,'jo_contactdetails:phone:phone:Contacts_Office_Phone:V'),(7,7,'jo_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),(8,0,'jo_contactdetails:firstname:firstname:Contacts_First_Name:V'),(8,1,'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V'),(8,2,'jo_contactaddress:mailingstreet:mailingstreet:Contacts_Mailing_Street:V'),(8,3,'jo_contactaddress:mailingcity:mailingcity:Contacts_Mailing_City:V'),(8,4,'jo_contactaddress:mailingstate:mailingstate:Contacts_Mailing_State:V'),(8,5,'jo_contactaddress:mailingzip:mailingzip:Contacts_Mailing_Zip:V'),(8,6,'jo_contactaddress:mailingcountry:mailingcountry:Contacts_Mailing_Country:V'),(9,0,'jo_contactdetails:firstname:firstname:Contacts_First_Name:V'),(9,1,'jo_contactdetails:lastname:lastname:Contacts_Last_Name:V'),(9,2,'jo_contactdetails:title:title:Contacts_Title:V'),(9,3,'jo_contactdetails:accountid:account_id:Contacts_Account_Name:I'),(9,4,'jo_contactdetails:email:email:Contacts_Email:E'),(9,5,'jo_contactsubdetails:otherphone:otherphone:Contacts_Phone:V'),(9,6,'jo_crmentity:smownerid:assigned_user_id:Contacts_Assigned_To:V'),(10,1,'jo_potential:potentialname:potentialname:Potentials_Potential_Name:V'),(10,2,'jo_potential:related_to:related_to:Potentials_Related_To:V'),(10,3,'jo_potential:sales_stage:sales_stage:Potentials_Sales_Stage:V'),(10,4,'jo_potential:leadsource:leadsource:Potentials_Lead_Source:V'),(10,5,'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),(10,6,'jo_potential:amount:amount:Potentials_Amount:N'),(10,7,'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),(10,8,'jo_potential:contact_id:contact_id:Potentials_Contact_Name:V'),(11,0,'jo_potential:potentialname:potentialname:Potentials_Potential_Name:V'),(11,1,'jo_potential:related_to:related_to:Potentials_Related_To:V'),(11,2,'jo_potential:amount:amount:Potentials_Amount:N'),(11,3,'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),(11,4,'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),(11,5,'jo_potential:contact_id:contact_id:Potentials_Contact_Name:V'),(12,0,'jo_potential:potentialname:potentialname:Potentials_Potential_Name:V'),(12,1,'jo_potential:related_to:related_to:Potentials_Related_To:V'),(12,2,'jo_potential:amount:amount:Potentials_Amount:N'),(12,3,'jo_potential:leadsource:leadsource:Potentials_Lead_Source:V'),(12,4,'jo_potential:closingdate:closingdate:Potentials_Expected_Close_Date:D'),(12,5,'jo_crmentity:smownerid:assigned_user_id:Potentials_Assigned_To:V'),(12,6,'jo_potential:contact_id:contact_id:Potentials_Contact_Name:V'),(13,1,'jo_troubletickets:title:ticket_title:HelpDesk_Title:V'),(13,2,'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:V'),(13,3,'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V'),(13,4,'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V'),(13,5,'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),(13,6,'jo_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),(14,0,'jo_troubletickets:title:ticket_title:HelpDesk_Title:V'),(14,1,'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I'),(14,2,'jo_troubletickets:priority:ticketpriorities:HelpDesk_Priority:V'),(14,3,'jo_troubletickets:product_id:product_id:HelpDesk_Product_Name:I'),(14,4,'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),(14,5,'jo_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),(15,0,'jo_troubletickets:title:ticket_title:HelpDesk_Title:V'),(15,1,'jo_troubletickets:parent_id:parent_id:HelpDesk_Related_To:I'),(15,2,'jo_troubletickets:status:ticketstatus:HelpDesk_Status:V'),(15,3,'jo_troubletickets:product_id:product_id:HelpDesk_Product_Name:I'),(15,4,'jo_crmentity:smownerid:assigned_user_id:HelpDesk_Assigned_To:V'),(15,5,'jo_troubletickets:contact_id:contact_id:HelpDesk_Contact_Name:V'),(16,1,'jo_quotes:subject:subject:Quotes_Subject:V'),(16,2,'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V'),(16,3,'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:V'),(16,4,'jo_quotes:accountid:account_id:Quotes_Account_Name:V'),(16,5,'jo_quotes:total:hdnGrandTotal:Quotes_Total:N'),(16,6,'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),(17,0,'jo_quotes:subject:subject:Quotes_Subject:V'),(17,1,'jo_quotes:quotestage:quotestage:Quotes_Quote_Stage:V'),(17,2,'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:I'),(17,3,'jo_quotes:accountid:account_id:Quotes_Account_Name:I'),(17,4,'jo_quotes:validtill:validtill:Quotes_Valid_Till:D'),(17,5,'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),(18,0,'jo_quotes:subject:subject:Quotes_Subject:V'),(18,1,'jo_quotes:potentialid:potential_id:Quotes_Potential_Name:I'),(18,2,'jo_quotes:accountid:account_id:Quotes_Account_Name:I'),(18,3,'jo_quotes:validtill:validtill:Quotes_Valid_Till:D'),(18,4,'jo_crmentity:smownerid:assigned_user_id:Quotes_Assigned_To:V'),(19,0,'jo_activity:status:taskstatus:Calendar_Status:V'),(19,1,'jo_activity:activitytype:activitytype:Calendar_Activity_Type:V'),(19,2,'jo_activity:subject:subject:Calendar_Subject:V'),(19,3,'jo_seactivityrel:crmid:parent_id:Calendar_Related_To:V'),(19,4,'jo_activity:date_start:date_start:Calendar_Start_Date_&_Time:DT'),(19,5,'jo_activity:due_date:due_date:Calendar_Due_Date:D'),(19,6,'jo_crmentity:smownerid:assigned_user_id:Calendar_Assigned_To:V'),(20,0,'jo_activity:subject:subject:Emails_Subject:V'),(20,1,'jo_emaildetails:to_email:saved_toid:Emails_To:V'),(20,2,'jo_activity:date_start:date_start:Emails_Date_&_Time_Sent:DT'),(21,1,'jo_invoice:subject:subject:Invoice_Subject:V'),(21,2,'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:V'),(21,3,'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),(21,4,'jo_invoice:total:hdnGrandTotal:Invoice_Total:N'),(21,5,'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),(22,1,'jo_notes:title:notes_title:Documents_Title:V'),(22,2,'jo_notes:filename:filename:Documents_File_Name:V'),(22,3,'jo_crmentity:modifiedtime:modifiedtime:Documents_Modified_Time:DT'),(22,4,'jo_crmentity:smownerid:assigned_user_id:Documents_Assigned_To:V'),(23,1,'jo_pricebook:bookname:bookname:PriceBooks_Price_Book_Name:V'),(23,2,'jo_pricebook:active:active:PriceBooks_Active:C'),(23,3,'jo_pricebook:currency_id:currency_id:PriceBooks_Currency:V'),(24,1,'jo_products:productname:productname:Products_Product_Name:V'),(24,2,'jo_products:productcode:productcode:Products_Part_Number:V'),(24,3,'jo_products:commissionrate:commissionrate:Products_Commission_Rate:N'),(24,4,'jo_products:qtyinstock:qtyinstock:Products_Qty_In_Stock:NN'),(24,5,'jo_products:qty_per_unit:qty_per_unit:Products_Qty/Unit:N'),(24,6,'jo_products:unit_price:unit_price:Products_Unit_Price:N'),(25,1,'jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),(25,2,'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:V'),(25,3,'jo_purchaseorder:tracking_no:tracking_no:PurchaseOrder_Tracking_Number:V'),(25,4,'jo_purchaseorder:total:hdnGrandTotal:PurchaseOrder_Total:N'),(25,5,'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),(26,1,'jo_salesorder:subject:subject:SalesOrder_Subject:V'),(26,2,'jo_salesorder:accountid:account_id:SalesOrder_Account_Name:V'),(26,3,'jo_salesorder:quoteid:quote_id:SalesOrder_Quote_Name:V'),(26,4,'jo_salesorder:total:hdnGrandTotal:SalesOrder_Total:N'),(26,5,'jo_crmentity:smownerid:assigned_user_id:SalesOrder_Assigned_To:V'),(27,1,'jo_vendor:vendorname:vendorname:Vendors_Vendor_Name:V'),(27,2,'jo_vendor:phone:phone:Vendors_Phone:V'),(27,3,'jo_vendor:email:email:Vendors_Email:V'),(27,4,'jo_vendor:category:category:Vendors_Category:V'),(28,1,'jo_campaign:campaignname:campaignname:Campaigns_Campaign_Name:V'),(28,2,'jo_campaign:campaigntype:campaigntype:Campaigns_Campaign_Type:V'),(28,3,'jo_campaign:campaignstatus:campaignstatus:Campaigns_Campaign_Status:V'),(28,4,'jo_campaign:expectedrevenue:expectedrevenue:Campaigns_Expected_Revenue:N'),(28,5,'jo_campaign:closingdate:closingdate:Campaigns_Expected_Close_Date:D'),(28,6,'jo_crmentity:smownerid:assigned_user_id:Campaigns_Assigned_To:V'),(29,0,'subject:subject:subject:Subject:V'),(29,1,'from:fromname:fromname:From:N'),(29,2,'to:tpname:toname:To:N'),(29,3,'body:body:body:Body:V'),(30,0,'jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),(30,1,'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V'),(30,2,'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I'),(30,3,'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),(30,4,'jo_purchaseorder:duedate:duedate:PurchaseOrder_Due_Date:V'),(31,0,'jo_purchaseorder:subject:subject:PurchaseOrder_Subject:V'),(31,1,'jo_purchaseorder:vendorid:vendor_id:PurchaseOrder_Vendor_Name:I'),(31,2,'jo_crmentity:smownerid:assigned_user_id:PurchaseOrder_Assigned_To:V'),(31,3,'jo_purchaseorder:postatus:postatus:PurchaseOrder_Status:V'),(31,4,'jo_purchaseorder:carrier:carrier:PurchaseOrder_Carrier:V'),(31,5,'jo_poshipads:ship_street:ship_street:PurchaseOrder_Shipping_Address:V'),(32,0,'jo_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V'),(32,1,'jo_invoice:subject:subject:Invoice_Subject:V'),(32,2,'jo_invoice:accountid:account_id:Invoice_Account_Name:I'),(32,3,'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I'),(32,4,'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),(32,5,'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),(32,6,'jo_crmentity:createdtime:createdtime:Invoice_Created_Time:DT'),(33,0,'jo_invoice:invoice_no:invoice_no:Invoice_Invoice_No:V'),(33,1,'jo_invoice:subject:subject:Invoice_Subject:V'),(33,2,'jo_invoice:accountid:account_id:Invoice_Account_Name:I'),(33,3,'jo_invoice:salesorderid:salesorder_id:Invoice_Sales_Order:I'),(33,4,'jo_invoice:invoicestatus:invoicestatus:Invoice_Status:V'),(33,5,'jo_invoiceshipads:ship_street:ship_street:Invoice_Shipping_Address:V'),(33,6,'jo_crmentity:smownerid:assigned_user_id:Invoice_Assigned_To:V'),(45,0,'jo_pbxmanager:callstatus:callstatus:PBXManager_Call_Status:V'),(45,1,'jo_pbxmanager:customernumber:customernumber:PBXManager_Customer_Number:V'),(45,2,'jo_pbxmanager:customer:customer:PBXManager_Customer:V'),(45,3,'jo_pbxmanager:user:user:PBXManager_User:V'),(45,4,'jo_pbxmanager:recordingurl:recordingurl:PBXManager_Recording_URL:V'),(45,5,'jo_pbxmanager:totalduration:totalduration:PBXManager_Total_Duration:I'),(45,6,'jo_pbxmanager:starttime:starttime:PBXManager_Start_Time:DT'),(46,0,'jo_service:service_no:service_no:Services_Service_No:V'),(46,1,'jo_service:servicename:servicename:Services_Service_Name:V'),(46,2,'jo_service:service_usageunit:service_usageunit:Services_Usage_Unit:V'),(46,3,'jo_service:unit_price:unit_price:Services_Price:N'),(46,4,'jo_service:qty_per_unit:qty_per_unit:Services_No_of_Units:N'),(46,5,'jo_service:servicecategory:servicecategory:Services_Service_Category:V'),(46,6,'jo_crmentity:smownerid:assigned_user_id:Services_Owner:I'),(63,0,'jo_modcomments:commentcontent:commentcontent:ModComments_Comment:V'),(63,1,'jo_modcomments:related_to:related_to:ModComments_Related_To:V'),(63,2,'jo_crmentity:modifiedtime:modifiedtime:ModComments_Modified_Time:DT'),(63,3,'jo_crmentity:smownerid:assigned_user_id:ModComments_Assigned_To:V'),(64,0,'jo_projectmilestone:projectmilestonename:projectmilestonename:ProjectMilestone_Project_Milestone_Name:V'),(64,1,'jo_projectmilestone:projectmilestonedate:projectmilestonedate:ProjectMilestone_Milestone_Date:D'),(64,3,'jo_crmentity:description:description:ProjectMilestone_description:V'),(64,4,'jo_crmentity:createdtime:createdtime:ProjectMilestone_Created_Time:DT'),(64,5,'jo_crmentity:modifiedtime:modifiedtime:ProjectMilestone_Modified_Time:DT'),(65,2,'jo_projecttask:projecttaskname:projecttaskname:ProjectTask_Project_Task_Name:V'),(65,3,'jo_projecttask:projectid:projectid:ProjectTask_Related_to:V'),(65,4,'jo_projecttask:projecttaskpriority:projecttaskpriority:ProjectTask_Priority:V'),(65,5,'jo_projecttask:projecttaskprogress:projecttaskprogress:ProjectTask_Progress:V'),(65,6,'jo_projecttask:projecttaskhours:projecttaskhours:ProjectTask_Worked_Hours:V'),(65,7,'jo_projecttask:startdate:startdate:ProjectTask_Start_Date:D'),(65,8,'jo_projecttask:enddate:enddate:ProjectTask_End_Date:D'),(65,9,'jo_crmentity:smownerid:assigned_user_id:ProjectTask_Assigned_To:V'),(66,0,'jo_project:projectname:projectname:Project_Project_Name:V'),(66,1,'jo_project:linktoaccountscontacts:linktoaccountscontacts:Project_Related_to:V'),(66,2,'jo_project:startdate:startdate:Project_Start_Date:D'),(66,3,'jo_project:targetenddate:targetenddate:Project_Target_End_Date:D'),(66,4,'jo_project:actualenddate:actualenddate:Project_Actual_End_Date:D'),(66,5,'jo_project:targetbudget:targetbudget:Project_Target_Budget:V'),(66,6,'jo_project:progress:progress:Project_Progress:V'),(66,7,'jo_project:projectstatus:projectstatus:Project_Status:V'),(66,8,'jo_crmentity:smownerid:assigned_user_id:Project_Assigned_To:V');
/*!40000 ALTER TABLE `jo_cvcolumnlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_cvstdfilter`
--

DROP TABLE IF EXISTS `jo_cvstdfilter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_cvstdfilter` (
  `cvid` int(19) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `stdfilter` varchar(250) DEFAULT '',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  PRIMARY KEY (`cvid`),
  KEY `cvstdfilter_cvid_idx` (`cvid`),
  CONSTRAINT `fk_1_jo_cvstdfilter` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_cvstdfilter`
--

LOCK TABLES `jo_cvstdfilter` WRITE;
/*!40000 ALTER TABLE `jo_cvstdfilter` DISABLE KEYS */;
INSERT INTO `jo_cvstdfilter` VALUES (3,'jo_crmentity:modifiedtime:modifiedtime:Leads_Modified_Time','thismonth','2005-06-01','2005-06-30'),(6,'jo_crmentity:createdtime:createdtime:Accounts_Created_Time','thisweek','2005-06-19','2005-06-25'),(9,'jo_contactsubdetails:birthday:birthday:Contacts_Birthdate','today','2005-06-25','2005-06-25');
/*!40000 ALTER TABLE `jo_cvstdfilter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_dashboard_tabs`
--

DROP TABLE IF EXISTS `jo_dashboard_tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_dashboard_tabs` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `tabname` varchar(50) DEFAULT NULL,
  `isdefault` int(1) DEFAULT '0',
  `sequence` int(5) DEFAULT '2',
  `appname` varchar(20) DEFAULT NULL,
  `modulename` varchar(50) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tabname` (`tabname`,`userid`),
  KEY `userid` (`userid`),
  CONSTRAINT `jo_dashboard_tabs_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_dashboard_tabs`
--

LOCK TABLES `jo_dashboard_tabs` WRITE;
/*!40000 ALTER TABLE `jo_dashboard_tabs` DISABLE KEYS */;
INSERT INTO `jo_dashboard_tabs` VALUES (2,'My Dashboard',1,1,'','',1);
/*!40000 ALTER TABLE `jo_dashboard_tabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_grp2grp`
--

DROP TABLE IF EXISTS `jo_datashare_grp2grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_grp2grp` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_grp2grp_share_groupid_idx` (`share_groupid`),
  KEY `datashare_grp2grp_to_groupid_idx` (`to_groupid`),
  CONSTRAINT `fk_3_jo_datashare_grp2grp` FOREIGN KEY (`to_groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_grp2grp`
--

LOCK TABLES `jo_datashare_grp2grp` WRITE;
/*!40000 ALTER TABLE `jo_datashare_grp2grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_grp2grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_grp2role`
--

DROP TABLE IF EXISTS `jo_datashare_grp2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_grp2role` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_grp2role_share_groupid` (`share_groupid`),
  KEY `idx_datashare_grp2role_to_roleid` (`to_roleid`),
  CONSTRAINT `fk_3_jo_datashare_grp2role` FOREIGN KEY (`to_roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_grp2role`
--

LOCK TABLES `jo_datashare_grp2role` WRITE;
/*!40000 ALTER TABLE `jo_datashare_grp2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_grp2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_grp2rs`
--

DROP TABLE IF EXISTS `jo_datashare_grp2rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_grp2rs` (
  `shareid` int(19) NOT NULL,
  `share_groupid` int(19) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_grp2rs_share_groupid_idx` (`share_groupid`),
  KEY `datashare_grp2rs_to_roleandsubid_idx` (`to_roleandsubid`),
  CONSTRAINT `fk_3_jo_datashare_grp2rs` FOREIGN KEY (`to_roleandsubid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_grp2rs`
--

LOCK TABLES `jo_datashare_grp2rs` WRITE;
/*!40000 ALTER TABLE `jo_datashare_grp2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_grp2rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_module_rel`
--

DROP TABLE IF EXISTS `jo_datashare_module_rel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_module_rel` (
  `shareid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `relationtype` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_module_rel_tabid` (`tabid`),
  CONSTRAINT `fk_1_jo_datashare_module_rel` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_module_rel`
--

LOCK TABLES `jo_datashare_module_rel` WRITE;
/*!40000 ALTER TABLE `jo_datashare_module_rel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_module_rel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_relatedmodule_permission`
--

DROP TABLE IF EXISTS `jo_datashare_relatedmodule_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_relatedmodule_permission` (
  `shareid` int(19) NOT NULL,
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`,`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodule_permission_shareid_permissions_idx` (`shareid`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_relatedmodule_permission`
--

LOCK TABLES `jo_datashare_relatedmodule_permission` WRITE;
/*!40000 ALTER TABLE `jo_datashare_relatedmodule_permission` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_relatedmodule_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_relatedmodules`
--

DROP TABLE IF EXISTS `jo_datashare_relatedmodules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_relatedmodules` (
  `datashare_relatedmodule_id` int(19) NOT NULL,
  `tabid` int(19) DEFAULT NULL,
  `relatedto_tabid` int(19) DEFAULT NULL,
  PRIMARY KEY (`datashare_relatedmodule_id`),
  KEY `datashare_relatedmodules_tabid_idx` (`tabid`),
  KEY `datashare_relatedmodules_relatedto_tabid_idx` (`relatedto_tabid`),
  CONSTRAINT `fk_2_jo_datashare_relatedmodules` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_relatedmodules`
--

LOCK TABLES `jo_datashare_relatedmodules` WRITE;
/*!40000 ALTER TABLE `jo_datashare_relatedmodules` DISABLE KEYS */;
INSERT INTO `jo_datashare_relatedmodules` VALUES (1,6,2),(2,6,13),(3,6,20),(4,6,22),(5,6,23),(6,2,20),(7,2,22),(8,20,22),(9,22,23);
/*!40000 ALTER TABLE `jo_datashare_relatedmodules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_role2group`
--

DROP TABLE IF EXISTS `jo_datashare_role2group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_role2group` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `idx_datashare_role2group_share_roleid` (`share_roleid`),
  KEY `idx_datashare_role2group_to_groupid` (`to_groupid`),
  CONSTRAINT `fk_3_jo_datashare_role2group` FOREIGN KEY (`share_roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_role2group`
--

LOCK TABLES `jo_datashare_role2group` WRITE;
/*!40000 ALTER TABLE `jo_datashare_role2group` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_role2group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_role2role`
--

DROP TABLE IF EXISTS `jo_datashare_role2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_role2role` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_role2role_share_roleid_idx` (`share_roleid`),
  KEY `datashare_role2role_to_roleid_idx` (`to_roleid`),
  CONSTRAINT `fk_3_jo_datashare_role2role` FOREIGN KEY (`to_roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_role2role`
--

LOCK TABLES `jo_datashare_role2role` WRITE;
/*!40000 ALTER TABLE `jo_datashare_role2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_role2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_role2rs`
--

DROP TABLE IF EXISTS `jo_datashare_role2rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_role2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleid` varchar(255) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_role2s_share_roleid_idx` (`share_roleid`),
  KEY `datashare_role2s_to_roleandsubid_idx` (`to_roleandsubid`),
  CONSTRAINT `fk_3_jo_datashare_role2rs` FOREIGN KEY (`to_roleandsubid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_role2rs`
--

LOCK TABLES `jo_datashare_role2rs` WRITE;
/*!40000 ALTER TABLE `jo_datashare_role2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_role2rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_rs2grp`
--

DROP TABLE IF EXISTS `jo_datashare_rs2grp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_rs2grp` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_groupid` int(19) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2grp_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2grp_to_groupid_idx` (`to_groupid`),
  CONSTRAINT `fk_3_jo_datashare_rs2grp` FOREIGN KEY (`share_roleandsubid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_rs2grp`
--

LOCK TABLES `jo_datashare_rs2grp` WRITE;
/*!40000 ALTER TABLE `jo_datashare_rs2grp` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_rs2grp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_rs2role`
--

DROP TABLE IF EXISTS `jo_datashare_rs2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_rs2role` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_roleid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2role_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `datashare_rs2role_to_roleid_idx` (`to_roleid`),
  CONSTRAINT `fk_3_jo_datashare_rs2role` FOREIGN KEY (`to_roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_rs2role`
--

LOCK TABLES `jo_datashare_rs2role` WRITE;
/*!40000 ALTER TABLE `jo_datashare_rs2role` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_rs2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_datashare_rs2rs`
--

DROP TABLE IF EXISTS `jo_datashare_rs2rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_datashare_rs2rs` (
  `shareid` int(19) NOT NULL,
  `share_roleandsubid` varchar(255) DEFAULT NULL,
  `to_roleandsubid` varchar(255) DEFAULT NULL,
  `permission` int(19) DEFAULT NULL,
  PRIMARY KEY (`shareid`),
  KEY `datashare_rs2rs_share_roleandsubid_idx` (`share_roleandsubid`),
  KEY `idx_datashare_rs2rs_to_roleandsubid_idx` (`to_roleandsubid`),
  CONSTRAINT `fk_3_jo_datashare_rs2rs` FOREIGN KEY (`to_roleandsubid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_datashare_rs2rs`
--

LOCK TABLES `jo_datashare_rs2rs` WRITE;
/*!40000 ALTER TABLE `jo_datashare_rs2rs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_datashare_rs2rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_date_format`
--

DROP TABLE IF EXISTS `jo_date_format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_date_format` (
  `date_formatid` int(19) NOT NULL AUTO_INCREMENT,
  `date_format` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`date_formatid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_date_format`
--

LOCK TABLES `jo_date_format` WRITE;
/*!40000 ALTER TABLE `jo_date_format` DISABLE KEYS */;
INSERT INTO `jo_date_format` VALUES (1,'dd-mm-yyyy',0,1),(2,'mm-dd-yyyy',1,1),(3,'yyyy-mm-dd',2,1);
/*!40000 ALTER TABLE `jo_date_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_dayoftheweek`
--

DROP TABLE IF EXISTS `jo_dayoftheweek`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_dayoftheweek` (
  `dayoftheweekid` int(11) NOT NULL AUTO_INCREMENT,
  `dayoftheweek` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`dayoftheweekid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_dayoftheweek`
--

LOCK TABLES `jo_dayoftheweek` WRITE;
/*!40000 ALTER TABLE `jo_dayoftheweek` DISABLE KEYS */;
INSERT INTO `jo_dayoftheweek` VALUES (1,'Sunday',0,1),(2,'Monday',1,1),(3,'Tuesday',2,1),(4,'Wednesday',3,1),(5,'Thursday',4,1),(6,'Friday',5,1),(7,'Saturday',6,1);
/*!40000 ALTER TABLE `jo_dayoftheweek` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_def_org_field`
--

DROP TABLE IF EXISTS `jo_def_org_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_def_org_field` (
  `tabid` int(10) DEFAULT NULL,
  `fieldid` int(19) NOT NULL,
  `visible` int(19) DEFAULT NULL,
  `readonly` int(19) DEFAULT NULL,
  PRIMARY KEY (`fieldid`),
  KEY `def_org_field_tabid_fieldid_idx` (`tabid`,`fieldid`),
  KEY `def_org_field_tabid_idx` (`tabid`),
  KEY `def_org_field_visible_fieldid_idx` (`visible`,`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_def_org_field`
--

LOCK TABLES `jo_def_org_field` WRITE;
/*!40000 ALTER TABLE `jo_def_org_field` DISABLE KEYS */;
INSERT INTO `jo_def_org_field` VALUES (6,1,0,0),(6,2,0,0),(6,3,0,0),(6,4,0,0),(6,5,0,0),(6,6,0,0),(6,7,0,0),(6,8,0,0),(6,9,0,0),(6,10,0,0),(6,11,0,0),(6,12,0,0),(6,13,0,0),(6,14,0,0),(6,15,0,0),(6,16,0,0),(6,17,0,0),(6,18,0,0),(6,19,0,0),(6,20,0,0),(6,21,0,0),(6,22,0,0),(6,23,0,0),(6,24,0,0),(6,25,0,0),(6,26,0,0),(6,27,0,0),(6,28,0,0),(6,29,0,0),(6,30,0,0),(6,31,0,0),(6,32,0,0),(6,33,0,0),(6,34,0,0),(6,35,0,0),(6,36,0,0),(7,37,0,0),(7,38,0,0),(7,39,0,0),(7,40,0,0),(7,41,0,0),(7,42,0,0),(7,43,0,0),(7,44,0,0),(7,45,0,0),(7,46,0,0),(7,47,0,0),(7,48,0,0),(7,49,0,0),(7,50,0,0),(7,51,0,0),(7,52,0,0),(7,53,0,0),(7,54,0,0),(7,55,0,0),(7,56,0,0),(7,57,0,0),(7,58,0,0),(7,59,0,0),(7,60,0,0),(7,61,0,0),(7,62,0,0),(7,63,0,0),(7,64,0,0),(7,65,0,0),(4,66,0,0),(4,67,0,0),(4,68,0,0),(4,69,0,0),(4,70,0,0),(4,71,0,0),(4,72,0,0),(4,73,0,0),(4,74,0,0),(4,75,0,0),(4,76,0,0),(4,77,0,0),(4,78,0,0),(4,79,0,0),(4,80,0,0),(4,81,0,0),(4,82,0,0),(4,83,0,0),(4,84,0,0),(4,85,0,0),(4,86,0,0),(4,87,0,0),(4,88,0,0),(4,89,0,0),(4,90,0,0),(4,91,0,0),(4,92,0,0),(4,93,0,0),(4,94,0,0),(4,95,0,0),(4,96,0,0),(4,97,0,0),(4,98,0,0),(4,99,0,0),(4,100,0,0),(4,101,0,0),(4,102,0,0),(4,103,0,0),(4,104,0,0),(4,105,0,0),(4,106,0,0),(4,107,0,0),(4,108,0,0),(4,109,0,0),(2,110,0,0),(2,111,0,0),(2,112,0,0),(2,113,0,0),(2,114,0,0),(2,115,0,0),(2,116,0,0),(2,117,0,0),(2,118,0,0),(2,119,0,0),(2,120,0,0),(2,121,0,0),(2,122,0,0),(2,123,0,0),(2,124,0,0),(2,125,0,0),(26,126,0,0),(26,127,0,0),(26,128,0,0),(26,129,0,0),(26,130,0,0),(26,131,0,0),(26,132,0,0),(26,133,0,0),(26,134,0,0),(26,135,0,0),(26,136,0,0),(26,137,0,0),(26,138,0,0),(26,139,0,0),(26,140,0,0),(26,141,0,0),(26,142,0,0),(26,143,0,0),(26,144,0,0),(26,145,0,0),(26,146,0,0),(26,147,0,0),(26,148,0,0),(26,149,0,0),(26,150,0,0),(4,151,0,0),(6,152,0,0),(7,153,0,0),(26,154,0,0),(13,155,0,0),(13,156,0,0),(13,157,0,0),(13,158,0,0),(13,159,0,0),(13,160,0,0),(13,161,0,0),(13,162,0,0),(13,163,0,0),(13,164,0,0),(13,165,0,0),(13,166,0,0),(13,167,0,0),(13,168,0,0),(13,169,0,0),(13,170,0,0),(13,171,0,0),(13,172,0,0),(13,173,0,0),(14,174,0,0),(14,175,0,0),(14,176,0,0),(14,177,0,0),(14,178,0,0),(14,179,0,0),(14,180,0,0),(14,181,0,0),(14,182,0,0),(14,183,0,0),(14,184,0,0),(14,185,0,0),(14,186,0,0),(14,187,0,0),(14,188,0,0),(14,189,0,0),(14,190,0,0),(14,191,0,0),(14,192,0,0),(14,193,0,0),(14,194,0,0),(14,195,0,0),(14,196,0,0),(14,197,0,0),(14,198,0,0),(14,199,0,0),(14,200,0,0),(14,201,0,0),(14,202,0,0),(14,203,0,0),(14,204,0,0),(8,205,0,0),(8,206,0,0),(8,207,0,0),(8,208,0,0),(8,209,0,0),(8,210,0,0),(8,211,0,0),(8,212,0,0),(8,213,0,0),(8,214,0,0),(8,215,0,0),(8,216,0,0),(8,217,0,0),(8,218,0,0),(8,219,0,0),(10,220,0,0),(10,221,0,0),(10,222,0,0),(10,223,0,0),(10,224,0,0),(10,225,0,0),(10,226,0,0),(10,227,0,0),(10,228,0,0),(10,229,0,0),(10,230,0,0),(10,231,0,0),(9,232,0,0),(9,233,0,0),(9,234,0,0),(9,235,0,0),(9,236,0,0),(9,237,0,0),(9,238,0,0),(9,239,0,0),(9,240,0,0),(9,241,0,0),(9,242,0,0),(9,243,0,0),(9,244,0,0),(9,245,0,0),(9,246,0,0),(9,247,0,0),(9,248,0,0),(9,249,0,0),(9,250,0,0),(9,251,0,0),(9,252,0,0),(9,253,0,0),(9,254,0,0),(9,255,0,0),(16,256,0,0),(16,257,0,0),(16,258,0,0),(16,259,0,0),(16,260,0,0),(16,261,0,0),(16,262,0,0),(16,263,0,0),(16,264,0,0),(16,265,0,0),(16,266,0,0),(16,267,0,0),(16,268,0,0),(16,269,0,0),(16,270,0,0),(16,271,0,0),(16,272,0,0),(16,273,0,0),(16,274,0,0),(16,275,0,0),(16,276,0,0),(16,277,0,0),(16,278,0,0),(18,279,0,0),(18,280,0,0),(18,281,0,0),(18,282,0,0),(18,283,0,0),(18,284,0,0),(18,285,0,0),(18,286,0,0),(18,287,0,0),(18,288,0,0),(18,289,0,0),(18,290,0,0),(18,291,0,0),(18,292,0,0),(18,293,0,0),(18,294,0,0),(18,295,0,0),(19,296,0,0),(19,297,0,0),(19,298,0,0),(19,299,0,0),(19,300,0,0),(19,301,0,0),(19,302,0,0),(19,303,0,0),(20,304,0,0),(20,305,0,0),(20,306,0,0),(20,307,0,0),(20,308,0,0),(20,309,0,0),(20,310,0,0),(20,311,0,0),(20,312,0,0),(20,313,0,0),(20,314,0,0),(20,315,0,0),(20,316,0,0),(20,317,0,0),(20,318,0,0),(20,319,0,0),(20,320,0,0),(20,321,0,0),(20,322,0,0),(20,323,0,0),(20,324,0,0),(20,325,0,0),(20,326,0,0),(20,327,0,0),(20,328,0,0),(20,329,0,0),(20,330,0,0),(20,331,0,0),(20,332,0,0),(20,333,0,0),(20,334,0,0),(20,335,0,0),(20,336,0,0),(20,337,0,0),(20,338,0,0),(20,339,0,0),(20,340,0,0),(21,341,0,0),(21,342,0,0),(21,343,0,0),(21,344,0,0),(21,345,0,0),(21,346,0,0),(21,347,0,0),(21,348,0,0),(21,349,0,0),(21,350,0,0),(21,351,0,0),(21,352,0,0),(21,353,0,0),(21,354,0,0),(21,355,0,0),(21,356,0,0),(21,357,0,0),(21,358,0,0),(21,359,0,0),(21,360,0,0),(21,361,0,0),(21,362,0,0),(21,363,0,0),(21,364,0,0),(21,365,0,0),(21,366,0,0),(21,367,0,0),(21,368,0,0),(21,369,0,0),(21,370,0,0),(21,371,0,0),(21,372,0,0),(21,373,0,0),(21,374,0,0),(21,375,0,0),(21,376,0,0),(21,377,0,0),(21,378,0,0),(22,379,0,0),(22,380,0,0),(22,381,0,0),(22,382,0,0),(22,383,0,0),(22,384,0,0),(22,385,0,0),(22,386,0,0),(22,387,0,0),(22,388,0,0),(22,389,0,0),(22,390,0,0),(22,391,0,0),(22,392,0,0),(22,393,0,0),(22,394,0,0),(22,395,0,0),(22,396,0,0),(22,397,0,0),(22,398,0,0),(22,399,0,0),(22,400,0,0),(22,401,0,0),(22,402,0,0),(22,403,0,0),(22,404,0,0),(22,405,0,0),(22,406,0,0),(22,407,0,0),(22,408,0,0),(22,409,0,0),(22,410,0,0),(22,411,0,0),(22,412,0,0),(22,413,0,0),(22,414,0,0),(22,415,0,0),(22,416,0,0),(22,417,0,0),(22,418,0,0),(22,419,0,0),(22,420,0,0),(22,421,0,0),(22,422,0,0),(22,423,0,0),(22,424,0,0),(22,425,0,0),(23,426,0,0),(23,427,0,0),(23,428,0,0),(23,429,0,0),(23,430,0,0),(23,431,0,0),(23,432,0,0),(23,433,0,0),(23,434,0,0),(23,435,0,0),(23,436,0,0),(23,437,0,0),(23,438,0,0),(23,439,0,0),(23,440,0,0),(23,441,0,0),(23,442,0,0),(23,443,0,0),(23,444,0,0),(23,445,0,0),(23,446,0,0),(23,447,0,0),(23,448,0,0),(23,449,0,0),(23,450,0,0),(23,451,0,0),(23,452,0,0),(23,453,0,0),(23,454,0,0),(23,455,0,0),(23,456,0,0),(23,457,0,0),(23,458,0,0),(23,459,0,0),(23,460,0,0),(23,461,0,0),(23,462,0,0),(23,463,0,0),(23,464,0,0),(29,465,0,0),(29,469,0,0),(29,470,0,0),(29,471,0,0),(29,472,0,0),(29,479,0,0),(29,480,0,0),(29,481,0,0),(29,482,0,0),(29,483,0,0),(29,484,0,0),(29,485,0,0),(29,486,0,0),(29,487,0,0),(29,488,0,0),(29,489,0,0),(29,494,0,0),(29,495,0,0),(29,496,0,0),(29,497,0,0),(29,500,0,0),(29,505,0,0),(10,512,0,0),(10,513,0,0),(10,514,0,0),(10,515,0,0),(10,516,0,0),(10,517,0,0),(36,518,0,0),(36,519,0,0),(36,520,0,0),(36,521,0,0),(36,522,0,0),(36,523,0,0),(36,524,0,0),(36,525,0,0),(36,526,0,0),(36,527,0,0),(36,528,0,0),(36,529,0,0),(36,530,0,0),(36,531,0,0),(36,532,0,0),(36,533,0,0),(36,534,0,0),(36,535,0,0),(36,536,0,0),(37,537,0,0),(37,538,0,0),(37,539,0,0),(37,540,0,0),(37,541,0,0),(37,542,0,0),(37,543,0,0),(37,544,0,0),(37,545,0,0),(37,546,0,0),(37,547,0,0),(37,548,0,0),(37,549,0,0),(37,550,0,0),(37,551,0,0),(37,552,0,0),(29,553,0,0),(42,554,0,0),(42,555,0,0),(42,556,0,0),(42,557,0,0),(42,558,0,0),(42,559,0,0),(42,560,0,0),(42,561,0,0),(42,562,0,0),(42,563,0,0),(43,564,0,0),(43,565,0,0),(43,566,0,0),(43,567,0,0),(43,568,0,0),(43,569,0,0),(43,570,0,0),(43,571,0,0),(43,572,0,0),(43,573,0,0),(43,574,0,0),(43,575,0,0),(43,576,0,0),(43,577,0,0),(43,578,0,0),(44,579,0,0),(44,580,0,0),(44,581,0,0),(44,582,0,0),(44,583,0,0),(44,584,0,0),(44,585,0,0),(44,586,0,0),(44,587,0,0),(44,588,0,0),(44,589,0,0),(44,590,0,0),(44,591,0,0),(44,592,0,0),(44,593,0,0),(44,594,0,0),(44,595,0,0),(47,596,0,0),(47,597,0,0),(47,598,0,0),(47,599,0,0),(47,600,0,0),(47,601,0,0),(47,602,0,0),(2,603,0,0),(29,604,0,0),(23,605,0,0),(23,606,0,0),(23,607,0,0),(23,608,0,0),(23,609,0,0),(23,610,0,0),(23,611,0,0),(23,612,0,0),(23,613,0,0),(22,614,0,0),(22,615,0,0),(22,616,0,0),(22,617,0,0),(22,618,0,0),(22,619,0,0),(22,620,0,0),(22,621,0,0),(22,622,0,0),(21,623,0,0),(21,624,0,0),(21,625,0,0),(21,626,0,0),(21,627,0,0),(21,628,0,0),(21,629,0,0),(21,630,0,0),(21,631,0,0),(20,632,0,0),(20,633,0,0),(20,634,0,0),(20,635,0,0),(20,636,0,0),(20,637,0,0),(20,638,0,0),(20,639,0,0),(20,640,0,0),(29,641,0,0),(29,644,0,0),(29,645,0,0),(29,646,0,0),(23,647,0,0),(22,648,0,0),(21,649,0,0),(20,650,0,0),(29,651,0,0),(6,652,0,0),(4,653,0,0),(2,654,0,0),(29,655,0,0),(23,656,0,0),(23,657,0,0),(21,658,0,0),(21,659,0,0),(7,660,0,0),(23,663,0,0),(20,664,0,0),(21,665,0,0),(22,666,0,0),(29,667,0,0),(2,668,0,0),(13,669,0,0),(29,670,0,0),(29,671,0,0),(29,672,0,0),(29,673,0,0),(14,696,0,0),(23,698,0,0),(29,699,0,0),(23,700,0,0),(23,701,0,0),(23,702,0,0),(20,703,0,0),(20,704,0,0),(20,705,0,0),(21,706,0,0),(22,707,0,0),(22,708,0,0),(22,709,0,0),(2,713,0,0),(4,714,0,0),(6,715,0,0),(7,716,0,0),(8,717,0,0),(9,718,0,0),(10,719,0,0),(13,720,0,0),(14,721,0,0),(16,722,0,0),(18,723,0,0),(19,724,0,0),(20,725,0,0),(21,726,0,0),(22,727,0,0),(23,728,0,0),(26,729,0,0),(10,735,0,0),(2,736,0,0),(4,737,0,0),(6,738,0,0),(7,739,0,0),(8,740,0,0),(9,741,0,0),(10,742,0,0),(13,743,0,0),(14,744,0,0),(16,745,0,0),(18,746,0,0),(19,747,0,0),(20,748,0,0),(21,749,0,0),(22,750,0,0),(23,751,0,0),(26,752,0,0),(2,758,0,0),(4,759,0,0),(6,760,0,0),(7,761,0,0),(8,762,0,0),(9,763,0,0),(10,764,0,0),(13,765,0,0),(14,766,0,0),(16,767,0,0),(18,768,0,0),(19,769,0,0),(20,770,0,0),(21,771,0,0),(22,772,0,0),(23,773,0,0),(26,774,0,0),(20,780,0,0),(21,781,0,0),(22,782,0,0),(23,783,0,0),(42,800,0,0),(42,801,0,0),(42,802,0,0);
/*!40000 ALTER TABLE `jo_def_org_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_def_org_share`
--

DROP TABLE IF EXISTS `jo_def_org_share`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_def_org_share` (
  `ruleid` int(11) NOT NULL AUTO_INCREMENT,
  `tabid` int(11) NOT NULL,
  `permission` int(19) DEFAULT NULL,
  `editstatus` int(19) DEFAULT NULL,
  PRIMARY KEY (`ruleid`),
  KEY `fk_1_jo_def_org_share` (`permission`),
  CONSTRAINT `fk_1_jo_def_org_share` FOREIGN KEY (`permission`) REFERENCES `jo_org_share_action_mapping` (`share_action_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_def_org_share`
--

LOCK TABLES `jo_def_org_share` WRITE;
/*!40000 ALTER TABLE `jo_def_org_share` DISABLE KEYS */;
INSERT INTO `jo_def_org_share` VALUES (1,2,3,0),(2,4,3,0),(3,6,3,0),(4,7,3,0),(5,9,3,1),(6,13,3,0),(7,16,3,2),(8,20,3,0),(9,21,3,0),(10,22,3,0),(11,23,3,0),(12,26,2,0),(13,8,3,0),(14,14,3,0),(15,36,3,0),(16,37,3,0),(17,42,2,0),(18,43,2,0),(19,44,2,0),(20,47,2,0),(21,10,2,0);
/*!40000 ALTER TABLE `jo_def_org_share` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_default_record_view`
--

DROP TABLE IF EXISTS `jo_default_record_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_default_record_view` (
  `default_record_viewid` int(11) NOT NULL AUTO_INCREMENT,
  `default_record_view` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`default_record_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_default_record_view`
--

LOCK TABLES `jo_default_record_view` WRITE;
/*!40000 ALTER TABLE `jo_default_record_view` DISABLE KEYS */;
INSERT INTO `jo_default_record_view` VALUES (1,'Summary',0,1),(2,'Detail',1,1);
/*!40000 ALTER TABLE `jo_default_record_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_defaultactivitytype`
--

DROP TABLE IF EXISTS `jo_defaultactivitytype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_defaultactivitytype` (
  `defaultactivitytypeid` int(11) NOT NULL AUTO_INCREMENT,
  `defaultactivitytype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`defaultactivitytypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_defaultactivitytype`
--

LOCK TABLES `jo_defaultactivitytype` WRITE;
/*!40000 ALTER TABLE `jo_defaultactivitytype` DISABLE KEYS */;
INSERT INTO `jo_defaultactivitytype` VALUES (1,'Call',1,327,1),(2,'Meeting',1,328,2);
/*!40000 ALTER TABLE `jo_defaultactivitytype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_defaultcalendarview`
--

DROP TABLE IF EXISTS `jo_defaultcalendarview`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_defaultcalendarview` (
  `defaultcalendarviewid` int(11) NOT NULL AUTO_INCREMENT,
  `defaultcalendarview` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`defaultcalendarviewid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_defaultcalendarview`
--

LOCK TABLES `jo_defaultcalendarview` WRITE;
/*!40000 ALTER TABLE `jo_defaultcalendarview` DISABLE KEYS */;
INSERT INTO `jo_defaultcalendarview` VALUES (1,'ListView',0,1),(2,'MyCalendar',1,1),(3,'SharedCalendar',2,1);
/*!40000 ALTER TABLE `jo_defaultcalendarview` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_defaultcv`
--

DROP TABLE IF EXISTS `jo_defaultcv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_defaultcv` (
  `tabid` int(19) NOT NULL,
  `defaultviewname` varchar(50) NOT NULL,
  `query` text,
  PRIMARY KEY (`tabid`),
  CONSTRAINT `fk_1_jo_defaultcv` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_defaultcv`
--

LOCK TABLES `jo_defaultcv` WRITE;
/*!40000 ALTER TABLE `jo_defaultcv` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_defaultcv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_defaulteventstatus`
--

DROP TABLE IF EXISTS `jo_defaulteventstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_defaulteventstatus` (
  `defaulteventstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `defaulteventstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  PRIMARY KEY (`defaulteventstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_defaulteventstatus`
--

LOCK TABLES `jo_defaulteventstatus` WRITE;
/*!40000 ALTER TABLE `jo_defaulteventstatus` DISABLE KEYS */;
INSERT INTO `jo_defaulteventstatus` VALUES (1,'Planned',1,324,1),(2,'Held',1,325,2),(3,'Not Held',1,326,3);
/*!40000 ALTER TABLE `jo_defaulteventstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_duplicatechecksettings`
--

DROP TABLE IF EXISTS `jo_duplicatechecksettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_duplicatechecksettings` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `modulename` varchar(50) DEFAULT NULL,
  `fieldstomatch` text,
  `isenabled` int(1) DEFAULT '1',
  `crosscheck` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_duplicatechecksettings`
--

LOCK TABLES `jo_duplicatechecksettings` WRITE;
/*!40000 ALTER TABLE `jo_duplicatechecksettings` DISABLE KEYS */;
INSERT INTO `jo_duplicatechecksettings` VALUES (1,'Contacts',NULL,1,0),(2,'Leads',NULL,1,0),(3,'Accounts',NULL,1,0),(4,'Potentials',NULL,1,0),(5,'Products',NULL,1,0),(6,'Services',NULL,1,0),(7,'HelpDesk',NULL,1,0),(8,'Project',NULL,1,0),(9,'ProjectTask',NULL,1,0),(10,'ProjectMilestone',NULL,1,0),(11,'Vendors',NULL,1,0),(12,'Calendar',NULL,1,0),(13,'Campaigns',NULL,1,0),(14,'Quotes',NULL,1,0),(15,'PurchaseOrder',NULL,1,0),(16,'SalesOrder',NULL,1,0),(17,'Invoice',NULL,1,0),(18,'PriceBooks',NULL,1,0),(19,'Documents',NULL,1,0),(20,'Emails',NULL,1,0),(21,'Events',NULL,1,0),(22,'Users',NULL,1,0),(23,'PBXManager',NULL,1,0),(24,'ModComments',NULL,1,0),(25,'SMSNotifier',NULL,1,0),(26,'deleteconflict',NULL,1,0),(27,'assignedto',NULL,1,0),(28,'DuplicateCheck',NULL,1,0);
/*!40000 ALTER TABLE `jo_duplicatechecksettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_duration_minutes`
--

DROP TABLE IF EXISTS `jo_duration_minutes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_duration_minutes` (
  `minutesid` int(19) NOT NULL AUTO_INCREMENT,
  `duration_minutes` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`minutesid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_duration_minutes`
--

LOCK TABLES `jo_duration_minutes` WRITE;
/*!40000 ALTER TABLE `jo_duration_minutes` DISABLE KEYS */;
INSERT INTO `jo_duration_minutes` VALUES (1,'00',0,1,NULL),(2,'15',1,1,NULL),(3,'30',2,1,NULL),(4,'45',3,1,NULL);
/*!40000 ALTER TABLE `jo_duration_minutes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_durationhrs`
--

DROP TABLE IF EXISTS `jo_durationhrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_durationhrs` (
  `hrsid` int(19) NOT NULL AUTO_INCREMENT,
  `hrs` varchar(50) DEFAULT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`hrsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_durationhrs`
--

LOCK TABLES `jo_durationhrs` WRITE;
/*!40000 ALTER TABLE `jo_durationhrs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_durationhrs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_durationmins`
--

DROP TABLE IF EXISTS `jo_durationmins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_durationmins` (
  `minsid` int(19) NOT NULL AUTO_INCREMENT,
  `mins` varchar(50) DEFAULT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`minsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_durationmins`
--

LOCK TABLES `jo_durationmins` WRITE;
/*!40000 ALTER TABLE `jo_durationmins` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_durationmins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_email_access`
--

DROP TABLE IF EXISTS `jo_email_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_email_access` (
  `crmid` int(11) DEFAULT NULL,
  `mailid` int(11) DEFAULT NULL,
  `accessdate` date DEFAULT NULL,
  `accesstime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_email_access`
--

LOCK TABLES `jo_email_access` WRITE;
/*!40000 ALTER TABLE `jo_email_access` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_email_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_email_track`
--

DROP TABLE IF EXISTS `jo_email_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_email_track` (
  `crmid` int(11) DEFAULT NULL,
  `mailid` int(11) DEFAULT NULL,
  `access_count` int(11) DEFAULT NULL,
  `click_count` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `link_tabidtype_idx` (`crmid`,`mailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_email_track`
--

LOCK TABLES `jo_email_track` WRITE;
/*!40000 ALTER TABLE `jo_email_track` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_email_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_emaildetails`
--

DROP TABLE IF EXISTS `jo_emaildetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_emaildetails` (
  `emailid` int(19) NOT NULL,
  `from_email` varchar(50) NOT NULL DEFAULT '',
  `to_email` text,
  `cc_email` text,
  `bcc_email` text,
  `assigned_user_email` varchar(50) NOT NULL DEFAULT '',
  `idlists` text,
  `email_flag` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`emailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_emaildetails`
--

LOCK TABLES `jo_emaildetails` WRITE;
/*!40000 ALTER TABLE `jo_emaildetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_emaildetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_emails_recipientprefs`
--

DROP TABLE IF EXISTS `jo_emails_recipientprefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_emails_recipientprefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabid` int(11) NOT NULL,
  `prefs` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_emails_recipientprefs`
--

LOCK TABLES `jo_emails_recipientprefs` WRITE;
/*!40000 ALTER TABLE `jo_emails_recipientprefs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_emails_recipientprefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_emailslookup`
--

DROP TABLE IF EXISTS `jo_emailslookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_emailslookup` (
  `crmid` int(20) DEFAULT NULL,
  `setype` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `fieldid` int(20) DEFAULT NULL,
  UNIQUE KEY `emailslookup_crmid_setype_fieldname_uk` (`crmid`,`setype`,`fieldid`),
  KEY `emailslookup_fieldid_setype_idx` (`fieldid`,`setype`),
  CONSTRAINT `emailslookup_crmid_fk` FOREIGN KEY (`crmid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_emailslookup`
--

LOCK TABLES `jo_emailslookup` WRITE;
/*!40000 ALTER TABLE `jo_emailslookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_emailslookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_emailtemplates`
--

DROP TABLE IF EXISTS `jo_emailtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_emailtemplates` (
  `foldername` varchar(100) DEFAULT NULL,
  `templatename` varchar(100) DEFAULT NULL,
  `templatepath` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` text,
  `body` text,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `templateid` int(19) NOT NULL AUTO_INCREMENT,
  `systemtemplate` int(1) NOT NULL DEFAULT '0',
  `module` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`templateid`),
  KEY `emailtemplates_foldernamd_templatename_subject_idx` (`foldername`,`templatename`,`subject`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_emailtemplates`
--

LOCK TABLES `jo_emailtemplates` WRITE;
/*!40000 ALTER TABLE `jo_emailtemplates` DISABLE KEYS */;
INSERT INTO `jo_emailtemplates` VALUES ('Public','Pending Invoices','','Invoices Pending','Payment Due','name <br />\nstreet, <br />\ncity, <br />\nstate, <br />\n zip <br />\n  <br />\n Dear <br />\n <br />\n Please check the following invoices that are yet to be paid by you: <br />\n <br />\n No. Date      Amount <br />\n 1   1/1/01    $4000 <br />\n 2   2/2//01   $5000 <br />\n 3   3/3/01    $10000 <br />\n 4   7/4/01    $23560 <br />\n <br />\n Kindly let us know if you have any clarifications in the invoice at sales@joforce.com or +1 872 9182. We are happy to help you and would like to continue our business with you.<br /><br />\n Thanks for your purchase!<br />\n The Joforce Team',0,1,0,''),('Public','Goods received acknowledgement','','Goods received acknowledgement','Acknowledged Receipt of Goods',' The undersigned hereby acknowledges receipt and delivery of the goods. <br />\nThe undersigned will release the payment subject to the goods being discovered not satisfactory. <br />\n<br />\nSigned under seal this <date>',0,2,0,''),('Public','Address Change','','Change of Address','Address Change','Dear <br />\n <br />\nWe are relocating our office to <br />\n11111,XYZDEF Cross, <br />\nUVWWX Circle <br />\nThe telephone number for this new location is (101) 1212-1328. <br />\n<br />\nOur Manufacturing Division will continue operations <br />\nat 3250 Lovedale Square Avenue, in Frankfurt. <br />\n<br />\nWe hope to keep in touch with you all. <br />\nPlease update your addressbooks.',0,3,0,''),('Public','Follow Up','','Follow Up','Follow Up of meeting','Dear <br />\n<br />\nThank you for extending us the opportunity to meet with <br />\nyou and members of your staff. <br />\n<br />\nI know that John Doe serviced your account <br />\nfor many years and made many friends at your firm. He has personally <br />\ndiscussed with me the deep relationship that he had with your firm. <br />\nWhile his presence will be missed, I can promise that we will <br />\ncontinue to provide the fine service that was accorded by <br />\nJohn to your firm. <br />\n<br />\nI was genuinely touched to receive such fine hospitality. <br />\n<br />\nThank you once again.',0,4,0,''),('Public','Target Crossed!','','Target Crossed!','Fantastic Sales Spree!','Congratulations! <br />\n<br />\nThe numbers are in and I am proud to inform you that our <br />\ntotal sales for the previous quarter <br />\namounts to $100,000,00.00!. This is the first time <br />\nwe have exceeded the target by almost 30%. <br />\nWe have also beat the previous quarter record by a <br />\nwhopping 75%! <br />\n<br />\nLet us meet at Smoking Joe for a drink in the evening! <br />\n\nC you all there guys!',0,5,0,''),('Public','Thanks Note','','Thanks Note','Note of thanks','Dear <br />\n<br />\nThank you for your confidence in our ability to serve you. <br />\nWe are glad to be given the chance to serve you.I look <br />\nforward to establishing a long term partnership with you. <br />\nConsider me as a friend. <br />\nShould any need arise,please do give us a call.',0,6,0,''),(NULL,'User Activation',NULL,'Joforce CRM Client Portal - Invitation','Mail send to activate user.','<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\r\n<html>\r\n<head>\r\n	<title></title>\r\n</head>\r\n<body class=\"scayt-enabled\">Hi $users-first_name$,<br />\r\n<br />\r\n<span class=\"post-message__text\">You&rsquo;re invited to access the Joforce CRM - Customer Self Service Portal.<br />\r\n<br />\r\nYou can login to your Joforce account using:<br />\r\n<br />\r\nLink: $site_url$<br />\r\n<br />\r\nUsername: </span>$users-user_name$<br />\r\n<br />\r\n<span class=\"post-message__text\">Password: </span>$users-user_password_custom$</body>\r\n</html>\r\n',0,7,1,'Users');
/*!40000 ALTER TABLE `jo_emailtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_entityname`
--

DROP TABLE IF EXISTS `jo_entityname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_entityname` (
  `tabid` int(19) NOT NULL DEFAULT '0',
  `modulename` varchar(100) DEFAULT NULL,
  `tablename` varchar(100) NOT NULL,
  `fieldname` varchar(150) NOT NULL,
  `entityidfield` varchar(150) NOT NULL,
  `entityidcolumn` varchar(150) NOT NULL,
  PRIMARY KEY (`tabid`),
  KEY `entityname_tabid_idx` (`tabid`),
  CONSTRAINT `fk_1_jo_entityname` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_entityname`
--

LOCK TABLES `jo_entityname` WRITE;
/*!40000 ALTER TABLE `jo_entityname` DISABLE KEYS */;
INSERT INTO `jo_entityname` VALUES (2,'Potentials','jo_potential','potentialname','potentialid','potential_id'),(4,'Contacts','jo_contactdetails','firstname,lastname','contactid','contact_id'),(6,'Accounts','jo_account','accountname','accountid','account_id'),(7,'Leads','jo_leaddetails','firstname,lastname','leadid','leadid'),(8,'Documents','jo_notes','title','notesid','notesid'),(9,'Calendar','jo_activity','subject','activityid','activityid'),(10,'Emails','jo_activity','subject','activityid','activityid'),(13,'HelpDesk','jo_troubletickets','title','ticketid','ticketid'),(14,'Products','jo_products','productname','productid','product_id'),(18,'Vendors','jo_vendor','vendorname','vendorid','vendor_id'),(19,'PriceBooks','jo_pricebook','bookname','pricebookid','pricebookid'),(20,'Quotes','jo_quotes','subject','quoteid','quote_id'),(21,'PurchaseOrder','jo_purchaseorder','subject','purchaseorderid','purchaseorderid'),(22,'SalesOrder','jo_salesorder','subject','salesorderid','salesorder_id'),(23,'Invoice','jo_invoice','subject','invoiceid','invoiceid'),(26,'Campaigns','jo_campaign','campaignname','campaignid','campaignid'),(29,'Users','jo_users','first_name,last_name','id','id'),(36,'Services','jo_service','servicename','serviceid','serviceid'),(37,'PBXManager','jo_pbxmanager','customernumber','pbxmanagerid','pbxmanagerid'),(42,'ProjectMilestone','jo_projectmilestone','projectmilestonename','projectmilestoneid','projectmilestoneid'),(43,'ProjectTask','jo_projecttask','projecttaskname','projecttaskid','projecttaskid'),(44,'Project','jo_project','projectname','projectid','projectid'),(47,'ModComments','jo_modcomments','commentcontent','modcommentsid','modcommentsid');
/*!40000 ALTER TABLE `jo_entityname` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_eventhandler_module`
--

DROP TABLE IF EXISTS `jo_eventhandler_module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_eventhandler_module` (
  `eventhandler_module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) DEFAULT NULL,
  `handler_class` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`eventhandler_module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_eventhandler_module`
--

LOCK TABLES `jo_eventhandler_module` WRITE;
/*!40000 ALTER TABLE `jo_eventhandler_module` DISABLE KEYS */;
INSERT INTO `jo_eventhandler_module` VALUES (2,'Home','Head_RecordLabelUpdater_Handler'),(3,'Invoice','InvoiceHandler'),(4,'PurchaseOrder','PurchaseOrderHandler'),(5,NULL,'NotificationHandler'),(6,'ModTracker','ModTrackerHandler');
/*!40000 ALTER TABLE `jo_eventhandler_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_eventhandlers`
--

DROP TABLE IF EXISTS `jo_eventhandlers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_eventhandlers` (
  `eventhandler_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `handler_path` varchar(400) NOT NULL,
  `handler_class` varchar(100) NOT NULL,
  `cond` text,
  `is_active` int(1) NOT NULL,
  `dependent_on` varchar(255) DEFAULT '[]',
  PRIMARY KEY (`eventhandler_id`,`event_name`,`handler_class`),
  UNIQUE KEY `eventhandler_idx` (`eventhandler_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_eventhandlers`
--

LOCK TABLES `jo_eventhandlers` WRITE;
/*!40000 ALTER TABLE `jo_eventhandlers` DISABLE KEYS */;
INSERT INTO `jo_eventhandlers` VALUES (1,'jo.entity.aftersave','modules/SalesOrder/RecurringInvoiceHandler.php','RecurringInvoiceHandler','',1,'[]'),(2,'jo.entity.beforesave','includes/data/EntityDelta.php','EntityDelta','',1,'[]'),(3,'jo.entity.aftersave','includes/data/EntityDelta.php','EntityDelta','',1,'[]'),(4,'jo.entity.aftersave','modules/Workflow/EventHandler.inc','EventHandler','',1,'[\"EntityDelta\"]'),(5,'jo.entity.afterrestore','modules/Workflow/EventHandler.inc','EventHandler','',1,'[]'),(6,'jo.entity.aftersave.final','modules/HelpDesk/HelpDeskHandler.php','HelpDeskHandler','',1,'[]'),(7,'jo.entity.aftersave','modules/WSAPP/WorkFlowHandlers/WSAPPAssignToTracker.php','WSAPPAssignToTracker','',1,'[\"EntityDelta\"]'),(8,'jo.entity.aftersave','modules/PBXManager/PBXManagerHandler.php','PBXManagerHandler','',1,'[\"EntityDelta\"]'),(9,'jo.entity.afterdelete','modules/PBXManager/PBXManagerHandler.php','PBXManagerHandler','',1,'[]'),(10,'jo.entity.afterrestore','modules/PBXManager/PBXManagerHandler.php','PBXManagerHandler','',1,'[]'),(11,'jo.batchevent.save','modules/PBXManager/PBXManagerHandler.php','PBXManagerBatchHandler','',1,'[]'),(12,'jo.batchevent.delete','modules/PBXManager/PBXManagerHandler.php','PBXManagerBatchHandler','',1,'[]'),(16,'jo.entity.aftersave','modules/Head/handlers/RecordLabelUpdater.php','Head_RecordLabelUpdater_Handler','',1,'[]'),(17,'jo.entity.aftersave','modules/Invoice/InvoiceHandler.php','InvoiceHandler','',1,'[]'),(18,'jo.entity.aftersave','modules/PurchaseOrder/PurchaseOrderHandler.php','PurchaseOrderHandler','',1,'[]'),(19,'jo.entity.aftersave','modules/ModComments/ModCommentsHandler.php','ModCommentsHandler','',1,'[]'),(20,'jo.picklist.afterrename','modules/Settings/Picklist/handlers/PickListHandler.php','PickListHandler','',1,'[]'),(21,'jo.picklist.afterdelete','modules/Settings/Picklist/handlers/PickListHandler.php','PickListHandler','',1,'[]'),(22,'jo.entity.aftersave','modules/Head/handlers/EmailLookupHandler.php','EmailLookupHandler','',1,'[\"EntityDelta\"]'),(23,'jo.entity.afterdelete','modules/Head/handlers/EmailLookupHandler.php','EmailLookupHandler','',1,'[]'),(24,'jo.entity.afterrestore','modules/Head/handlers/EmailLookupHandler.php','EmailLookupHandler','',1,'[]'),(25,'jo.batchevent.save','modules/Head/handlers/EmailLookupHandler.php','EmailLookupBatchHandler','',1,'[]'),(26,'jo.lead.convertlead','modules/Leads/handlers/LeadHandler.php','LeadHandler','',1,'[]'),(27,'jo.entity.aftersave','modules/Home/NotificationHandler.php','NotificationHandler','',1,'[]'),(28,'jo.entity.aftersave.final','modules/ModTracker/ModTrackerHandler.php','ModTrackerHandler','',1,'[]'),(29,'jo.entity.beforedelete','modules/ModTracker/ModTrackerHandler.php','ModTrackerHandler','',1,'[]'),(30,'jo.entity.afterrestore','modules/ModTracker/ModTrackerHandler.php','ModTrackerHandler','',1,'[]'),(31,'jo.entity.beforedelete','modules/Home/NotificationHandler.php','NotificationHandler','',1,'[]'),(32,'jo.entity.afterrestore','modules/Home/NotificationHandler.php','NotificationHandler','',1,'[]'), (33, 'jo.entity.aftersave', 'modules/Settings/Webhooks/WebhookHandler.php', 'WebhookHandler', '', 1, '[]'), (34, 'jo.entity.afterdelete', 'modules/Settings/Webhooks/WebhookDeleteHandler.php', 'WebhookDeleteHandler', '', 1, '[]');
/*!40000 ALTER TABLE `jo_eventhandlers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_eventstatus`
--

DROP TABLE IF EXISTS `jo_eventstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_eventstatus` (
  `eventstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `eventstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`eventstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_eventstatus`
--

LOCK TABLES `jo_eventstatus` WRITE;
/*!40000 ALTER TABLE `jo_eventstatus` DISABLE KEYS */;
INSERT INTO `jo_eventstatus` VALUES (1,'Planned',0,38,0,NULL),(2,'Held',0,39,1,NULL),(3,'Not Held',0,40,2,NULL);
/*!40000 ALTER TABLE `jo_eventstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_expectedresponse`
--

DROP TABLE IF EXISTS `jo_expectedresponse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_expectedresponse` (
  `expectedresponseid` int(19) NOT NULL AUTO_INCREMENT,
  `expectedresponse` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`expectedresponseid`),
  UNIQUE KEY `CampaignExpRes_UK01` (`expectedresponse`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_expectedresponse`
--

LOCK TABLES `jo_expectedresponse` WRITE;
/*!40000 ALTER TABLE `jo_expectedresponse` DISABLE KEYS */;
INSERT INTO `jo_expectedresponse` VALUES (2,'Excellent',1,42,1,NULL),(3,'Good',1,43,2,NULL),(4,'Average',1,44,3,NULL),(5,'Poor',1,45,4,NULL);
/*!40000 ALTER TABLE `jo_expectedresponse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_extnstore_users`
--

DROP TABLE IF EXISTS `jo_extnstore_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_extnstore_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(75) DEFAULT NULL,
  `instanceurl` varchar(255) DEFAULT NULL,
  `createdon` datetime DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_extnstore_users`
--

LOCK TABLES `jo_extnstore_users` WRITE;
/*!40000 ALTER TABLE `jo_extnstore_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_extnstore_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_feedback`
--

DROP TABLE IF EXISTS `jo_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_feedback` (
  `userid` int(19) DEFAULT NULL,
  `dontshow` varchar(19) DEFAULT 'false'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_feedback`
--

LOCK TABLES `jo_feedback` WRITE;
/*!40000 ALTER TABLE `jo_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_field`
--

DROP TABLE IF EXISTS `jo_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_field` (
  `tabid` int(19) NOT NULL,
  `fieldid` int(19) NOT NULL AUTO_INCREMENT,
  `columnname` varchar(30) NOT NULL,
  `tablename` varchar(100) DEFAULT NULL,
  `generatedtype` int(19) NOT NULL DEFAULT '0',
  `uitype` varchar(30) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `fieldlabel` varchar(50) NOT NULL,
  `readonly` int(1) NOT NULL,
  `presence` int(19) NOT NULL DEFAULT '1',
  `defaultvalue` text,
  `maximumlength` int(19) DEFAULT NULL,
  `sequence` int(19) DEFAULT NULL,
  `block` int(19) DEFAULT NULL,
  `displaytype` int(19) DEFAULT NULL,
  `typeofdata` varchar(100) DEFAULT NULL,
  `quickcreate` int(10) NOT NULL DEFAULT '1',
  `quickcreatesequence` int(19) DEFAULT NULL,
  `info_type` varchar(20) DEFAULT NULL,
  `masseditable` int(10) NOT NULL DEFAULT '1',
  `helpinfo` text,
  `summaryfield` int(10) NOT NULL DEFAULT '0',
  `headerfield` int(1) DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `field_tabid_idx` (`tabid`),
  KEY `field_fieldname_idx` (`fieldname`),
  KEY `field_block_idx` (`block`),
  KEY `field_displaytype_idx` (`displaytype`),
  CONSTRAINT `fk_1_jo_field` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=803 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_field`
--

LOCK TABLES `jo_field` WRITE;
/*!40000 ALTER TABLE `jo_field` DISABLE KEYS */;
INSERT INTO `jo_field` VALUES (6,1,'accountname','jo_account',1,'2','accountname','Account Name',1,0,'',100,1,9,1,'V~M',0,1,'BAS',1,NULL,1,0),(6,2,'account_no','jo_account',1,'4','account_no','Account No',1,0,'',100,2,9,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(6,3,'phone','jo_account',1,'11','phone','Phone',1,2,'',100,4,9,1,'V~O',2,2,'BAS',1,NULL,0,1),(6,4,'website','jo_account',1,'17','website','Website',1,2,'',100,3,9,1,'V~O',2,3,'BAS',1,NULL,0,1),(6,5,'fax','jo_account',1,'11','fax','Fax',1,2,'',100,6,9,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,6,'tickersymbol','jo_account',1,'1','tickersymbol','Ticker Symbol',1,2,'',100,5,9,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,7,'otherphone','jo_account',1,'11','otherphone','Other Phone',1,2,'',100,8,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,8,'parentid','jo_account',1,'51','account_id','Member Of',1,2,'',100,7,9,1,'I~O',1,NULL,'BAS',0,NULL,0,0),(6,9,'email1','jo_account',1,'13','email1','Email',1,2,'',100,10,9,1,'E~O',1,NULL,'BAS',1,NULL,0,1),(6,10,'employees','jo_account',1,'7','employees','Employees',1,2,'',100,9,9,1,'I~O',1,NULL,'ADV',1,NULL,0,0),(6,11,'email2','jo_account',1,'13','email2','Other Email',1,2,'',100,11,9,1,'E~O',1,NULL,'ADV',1,NULL,0,0),(6,12,'ownership','jo_account',1,'1','ownership','Ownership',1,2,'',100,12,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,13,'rating','jo_account',1,'15','rating','Rating',1,2,'',100,14,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,14,'industry','jo_account',1,'15','industry','industry',1,2,'',100,13,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,15,'siccode','jo_account',1,'1','siccode','SIC Code',1,2,'',100,16,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,16,'account_type','jo_account',1,'15','accounttype','Type',1,2,'',100,15,9,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(6,17,'annualrevenue','jo_account',1,'71','annual_revenue','Annual Revenue',1,2,'',100,18,9,1,'N~O',1,NULL,'ADV',1,NULL,0,0),(6,18,'emailoptout','jo_account',1,'56','emailoptout','Email Opt Out',1,0,'',100,17,9,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(6,19,'notify_owner','jo_account',1,'56','notify_owner','Notify Owner',1,2,'',10,20,9,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(6,20,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,19,9,1,'V~M',0,4,'BAS',1,NULL,1,0),(6,21,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,22,9,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(6,22,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,21,9,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(6,23,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,23,9,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(6,24,'bill_street','jo_accountbillads',1,'21','bill_street','Billing Address',1,2,'',100,1,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,25,'ship_street','jo_accountshipads',1,'21','ship_street','Shipping Address',1,2,'',100,2,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,26,'bill_city','jo_accountbillads',1,'1','bill_city','Billing City',1,2,'',100,5,11,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(6,27,'ship_city','jo_accountshipads',1,'1','ship_city','Shipping City',1,2,'',100,6,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,28,'bill_state','jo_accountbillads',1,'1','bill_state','Billing State',1,2,'',100,7,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,29,'ship_state','jo_accountshipads',1,'1','ship_state','Shipping State',1,2,'',100,8,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,30,'bill_code','jo_accountbillads',1,'1','bill_code','Billing Code',1,2,'',100,9,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,31,'ship_code','jo_accountshipads',1,'1','ship_code','Shipping Code',1,2,'',100,10,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,32,'bill_country','jo_accountbillads',1,'1','bill_country','Billing Country',1,2,'',100,11,11,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(6,33,'ship_country','jo_accountshipads',1,'1','ship_country','Shipping Country',1,2,'',100,12,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,34,'bill_pobox','jo_accountbillads',1,'1','bill_pobox','Billing Po Box',1,2,'',100,3,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,35,'ship_pobox','jo_accountshipads',1,'1','ship_pobox','Shipping Po Box',1,2,'',100,4,11,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(6,36,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,12,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,37,'salutation','jo_leaddetails',1,'55','salutationtype','Salutation',1,0,'',100,1,13,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,38,'firstname','jo_leaddetails',1,'55','firstname','First Name',1,0,'',100,2,13,1,'V~O',2,1,'BAS',1,NULL,1,0),(7,39,'lead_no','jo_leaddetails',1,'4','lead_no','Lead No',1,0,'',100,3,13,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(7,40,'phone','jo_leadaddress',1,'11','phone','Phone',1,2,'',100,5,13,1,'V~O',2,4,'BAS',1,NULL,0,1),(7,41,'lastname','jo_leaddetails',1,'255','lastname','Last Name',1,0,'',100,4,13,1,'V~M',0,2,'BAS',1,NULL,1,0),(7,42,'mobile','jo_leadaddress',1,'11','mobile','Mobile',1,2,'',100,7,13,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,43,'company','jo_leaddetails',1,'2','company','Company',1,2,'',100,6,13,1,'V~O',2,3,'BAS',1,NULL,1,0),(7,44,'fax','jo_leadaddress',1,'11','fax','Fax',1,2,'',100,9,13,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,45,'designation','jo_leaddetails',1,'1','designation','Designation',1,2,'',100,8,13,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,46,'email','jo_leaddetails',1,'13','email','Email',1,2,'',100,11,13,1,'E~O',2,5,'BAS',1,NULL,0,1),(7,47,'leadsource','jo_leaddetails',1,'15','leadsource','Lead Source',1,2,'',100,10,13,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(7,48,'website','jo_leadsubdetails',1,'17','website','Website',1,2,'',100,13,13,1,'V~O',1,NULL,'ADV',1,NULL,1,0),(7,49,'industry','jo_leaddetails',1,'15','industry','Industry',1,2,'',100,12,13,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(7,50,'leadstatus','jo_leaddetails',1,'15','leadstatus','Lead Status',1,2,'',100,15,13,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,51,'annualrevenue','jo_leaddetails',1,'71','annualrevenue','Annual Revenue',1,2,'',100,14,13,1,'N~O',1,NULL,'ADV',1,NULL,0,0),(7,52,'rating','jo_leaddetails',1,'15','rating','Rating',1,2,'',100,17,13,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(7,53,'noofemployees','jo_leaddetails',1,'1','noofemployees','No Of Employees',1,2,'',100,16,13,1,'I~O',1,NULL,'ADV',1,NULL,0,0),(7,54,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,19,13,1,'V~M',0,6,'BAS',1,NULL,1,0),(7,55,'secondaryemail','jo_leaddetails',1,'13','secondaryemail','Secondary Email',1,2,'',100,18,13,1,'E~O',1,NULL,'ADV',1,NULL,0,0),(7,56,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,21,13,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(7,57,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,20,13,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(7,58,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,23,13,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(7,59,'lane','jo_leadaddress',1,'21','lane','Street',1,2,'',100,1,15,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,60,'code','jo_leadaddress',1,'1','code','Postal Code',1,2,'',100,3,15,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,61,'city','jo_leadaddress',1,'1','city','City',1,2,'',100,4,15,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(7,62,'country','jo_leadaddress',1,'1','country','Country',1,2,'',100,5,15,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(7,63,'state','jo_leadaddress',1,'1','state','State',1,2,'',100,6,15,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,64,'pobox','jo_leadaddress',1,'1','pobox','Po Box',1,2,'',100,2,15,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(7,65,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,16,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,66,'salutation','jo_contactdetails',1,'55','salutationtype','Salutation',1,0,'',100,1,4,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,67,'firstname','jo_contactdetails',1,'55','firstname','First Name',1,0,'',100,2,4,1,'V~O',2,1,'BAS',1,NULL,1,0),(4,68,'contact_no','jo_contactdetails',1,'4','contact_no','Contact Id',1,0,'',100,3,4,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(4,69,'phone','jo_contactdetails',1,'11','phone','Office Phone',1,2,'',100,5,4,1,'V~O',2,4,'BAS',1,NULL,0,1),(4,70,'lastname','jo_contactdetails',1,'255','lastname','Last Name',1,0,'',100,4,4,1,'V~M',0,2,'BAS',1,NULL,1,0),(4,71,'mobile','jo_contactdetails',1,'11','mobile','Mobile',1,2,'',100,7,4,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,72,'accountid','jo_contactdetails',1,'51','account_id','Account Name',1,0,'',100,6,4,1,'I~O',2,3,'BAS',1,NULL,1,0),(4,73,'homephone','jo_contactsubdetails',1,'11','homephone','Home Phone',1,2,'',100,9,4,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(4,74,'leadsource','jo_contactsubdetails',1,'15','leadsource','Lead Source',1,2,'',100,8,4,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,75,'otherphone','jo_contactsubdetails',1,'11','otherphone','Other Phone',1,2,'',100,11,4,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(4,76,'title','jo_contactdetails',1,'1','title','Title',1,2,'',100,10,4,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(4,77,'fax','jo_contactdetails',1,'11','fax','Fax',1,2,'',100,13,4,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,78,'department','jo_contactdetails',1,'1','department','Department',1,2,'',100,12,4,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(4,79,'birthday','jo_contactsubdetails',1,'5','birthday','Birthdate',1,2,'',100,16,4,1,'D~O',1,NULL,'ADV',1,NULL,0,0),(4,80,'email','jo_contactdetails',1,'13','email','Email',1,2,'',100,15,4,1,'E~O',2,5,'BAS',1,NULL,0,1),(4,81,'reportsto','jo_contactdetails',1,'57','contact_id','Reports To',1,2,'',100,18,4,1,'V~O',1,NULL,'ADV',0,NULL,0,0),(4,82,'assistant','jo_contactsubdetails',1,'1','assistant','Assistant',1,2,'',100,17,4,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(4,83,'secondaryemail','jo_contactdetails',1,'13','secondaryemail','Secondary Email',1,2,'',100,20,4,1,'E~O',1,NULL,'ADV',1,NULL,0,0),(4,84,'assistantphone','jo_contactsubdetails',1,'11','assistantphone','Assistant Phone',1,2,'',100,19,4,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(4,85,'donotcall','jo_contactdetails',1,'56','donotcall','Do Not Call',1,2,'',100,22,4,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(4,86,'emailoptout','jo_contactdetails',1,'56','emailoptout','Email Opt Out',1,0,'',100,21,4,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(4,87,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,24,4,1,'V~M',0,6,'BAS',1,NULL,1,0),(4,88,'reference','jo_contactdetails',1,'56','reference','Reference',1,2,'',10,23,4,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(4,89,'notify_owner','jo_contactdetails',1,'56','notify_owner','Notify Owner',1,2,'',10,26,4,1,'C~O',1,NULL,'ADV',1,NULL,0,0),(4,90,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,25,4,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(4,91,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,27,4,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(4,92,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,28,4,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(4,96,'mailingstreet','jo_contactaddress',1,'21','mailingstreet','Mailing Street',1,2,'',100,1,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,97,'otherstreet','jo_contactaddress',1,'21','otherstreet','Other Street',1,2,'',100,2,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,98,'mailingcity','jo_contactaddress',1,'1','mailingcity','Mailing City',1,2,'',100,5,7,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(4,99,'othercity','jo_contactaddress',1,'1','othercity','Other City',1,2,'',100,6,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,100,'mailingstate','jo_contactaddress',1,'1','mailingstate','Mailing State',1,2,'',100,7,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,101,'otherstate','jo_contactaddress',1,'1','otherstate','Other State',1,2,'',100,8,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,102,'mailingzip','jo_contactaddress',1,'1','mailingzip','Mailing Zip',1,2,'',100,9,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,103,'otherzip','jo_contactaddress',1,'1','otherzip','Other Zip',1,2,'',100,10,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,104,'mailingcountry','jo_contactaddress',1,'1','mailingcountry','Mailing Country',1,2,'',100,11,7,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(4,105,'othercountry','jo_contactaddress',1,'1','othercountry','Other Country',1,2,'',100,12,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,106,'mailingpobox','jo_contactaddress',1,'1','mailingpobox','Mailing Po Box',1,2,'',100,3,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,107,'otherpobox','jo_contactaddress',1,'1','otherpobox','Other Po Box',1,2,'',100,4,7,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,108,'imagename','jo_contactdetails',1,'69','imagename','Contact Image',1,2,'',100,1,71,1,'V~O',3,NULL,'ADV',0,NULL,0,0),(4,109,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,8,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(2,110,'potentialname','jo_potential',1,'2','potentialname','Potential Name',1,0,'',100,1,1,1,'V~M',0,1,'BAS',1,NULL,1,0),(2,111,'potential_no','jo_potential',1,'4','potential_no','Potential No',1,0,'',100,2,1,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(2,112,'amount','jo_potential',1,'71','amount','Amount',1,2,'',100,5,1,1,'N~O',2,5,'BAS',1,NULL,0,1),(2,113,'related_to','jo_potential',1,'10','related_to','Related To',1,0,'',100,3,1,1,'V~O',0,2,'BAS',1,NULL,0,1),(2,114,'closingdate','jo_potential',1,'23','closingdate','Expected Close Date',1,2,'',100,8,1,1,'D~M',2,3,'BAS',1,NULL,1,0),(2,115,'potentialtype','jo_potential',1,'15','opportunity_type','Type',1,2,'',100,7,1,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(2,116,'nextstep','jo_potential',1,'1','nextstep','Next Step',1,2,'',100,10,1,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(2,117,'leadsource','jo_potential',1,'15','leadsource','Lead Source',1,2,'',100,9,1,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(2,118,'sales_stage','jo_potential',1,'15','sales_stage','Sales Stage',1,2,'',100,12,1,1,'V~M',2,4,'BAS',1,NULL,0,1),(2,119,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,2,'',100,11,1,1,'V~M',0,6,'BAS',1,NULL,1,0),(2,120,'probability','jo_potential',1,'9','probability','Probability',1,2,'',100,14,1,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(2,121,'campaignid','jo_potential',1,'58','campaignid','Campaign Source',1,2,'',100,13,1,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(2,122,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,16,1,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(2,123,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,15,1,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(2,124,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,17,1,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(2,125,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,3,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(26,126,'campaignname','jo_campaign',1,'2','campaignname','Campaign Name',1,0,'',100,1,72,1,'V~M',0,1,'BAS',1,NULL,1,0),(26,127,'campaign_no','jo_campaign',1,'4','campaign_no','Campaign No',1,0,'',100,2,72,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(26,128,'campaigntype','jo_campaign',1,'15','campaigntype','Campaign Type',1,2,'',100,5,72,1,'V~O',2,3,'BAS',1,NULL,1,0),(26,129,'product_id','jo_campaign',1,'59','product_id','Product',1,2,'',100,6,72,1,'I~O',2,5,'BAS',1,NULL,0,0),(26,130,'campaignstatus','jo_campaign',1,'15','campaignstatus','Campaign Status',1,2,'',100,4,72,1,'V~O',2,6,'BAS',1,NULL,1,0),(26,131,'closingdate','jo_campaign',1,'23','closingdate','Expected Close Date',1,2,'',100,8,72,1,'D~M',2,2,'BAS',1,NULL,1,0),(26,132,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,3,72,1,'V~M',0,7,'BAS',1,NULL,1,0),(26,133,'numsent','jo_campaign',1,'9','numsent','Num Sent',1,2,'',100,12,72,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(26,134,'sponsor','jo_campaign',1,'1','sponsor','Sponsor',1,2,'',100,9,72,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(26,135,'targetaudience','jo_campaign',1,'1','targetaudience','Target Audience',1,2,'',100,7,72,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(26,136,'targetsize','jo_campaign',1,'1','targetsize','TargetSize',1,2,'',100,10,72,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(26,137,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,11,72,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(26,138,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,13,72,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(26,139,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,16,72,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(26,140,'expectedresponse','jo_campaign',1,'15','expectedresponse','Expected Response',1,2,'',100,3,74,1,'V~O',2,4,'BAS',1,NULL,0,0),(26,141,'expectedrevenue','jo_campaign',1,'71','expectedrevenue','Expected Revenue',1,2,'',100,4,74,1,'N~O',1,NULL,'BAS',1,NULL,1,0),(26,142,'budgetcost','jo_campaign',1,'71','budgetcost','Budget Cost',1,2,'',100,1,74,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(26,143,'actualcost','jo_campaign',1,'71','actualcost','Actual Cost',1,2,'',100,2,74,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(26,144,'expectedresponsecount','jo_campaign',1,'1','expectedresponsecount','Expected Response Count',1,2,'',100,7,74,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(26,145,'expectedsalescount','jo_campaign',1,'1','expectedsalescount','Expected Sales Count',1,2,'',100,5,74,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(26,146,'expectedroi','jo_campaign',1,'71','expectedroi','Expected ROI',1,2,'',100,9,74,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(26,147,'actualresponsecount','jo_campaign',1,'1','actualresponsecount','Actual Response Count',1,2,'',100,8,74,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(26,148,'actualsalescount','jo_campaign',1,'1','actualsalescount','Actual Sales Count',1,2,'',100,6,74,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(26,149,'actualroi','jo_campaign',1,'71','actualroi','Actual ROI',1,2,'',100,10,74,1,'N~O',1,NULL,'BAS',1,NULL,0,0),(26,150,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,79,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(4,151,'campaignrelstatus','jo_campaignrelstatus',1,'16','campaignrelstatus','Status',1,0,'0',100,1,NULL,1,'V~O',1,NULL,'BAS',0,NULL,0,0),(6,152,'campaignrelstatus','jo_campaignrelstatus',1,'16','campaignrelstatus','Status',1,0,'0',100,1,NULL,1,'V~O',1,NULL,'BAS',0,NULL,0,0),(7,153,'campaignrelstatus','jo_campaignrelstatus',1,'16','campaignrelstatus','Status',1,0,'0',100,1,NULL,1,'V~O',1,NULL,'BAS',0,NULL,0,0),(26,154,'campaignrelstatus','jo_campaignrelstatus',1,'16','campaignrelstatus','Status',1,0,'0',100,1,NULL,1,'V~O',1,NULL,'BAS',0,NULL,0,0),(13,155,'ticket_no','jo_troubletickets',1,'4','ticket_no','Ticket No',1,0,'',100,14,25,1,'V~O',3,NULL,'BAS',0,NULL,1,0),(13,156,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,5,25,1,'V~M',0,4,'BAS',1,NULL,1,0),(13,157,'parent_id','jo_troubletickets',1,'10','parent_id','Related To',1,0,'',100,2,25,1,'I~O',1,NULL,'BAS',1,NULL,1,0),(13,158,'priority','jo_troubletickets',1,'15','ticketpriorities','Priority',1,2,'',100,7,25,1,'V~M',2,3,'BAS',1,NULL,0,1),(13,159,'product_id','jo_troubletickets',1,'59','product_id','Product Name',1,2,'',100,6,25,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(13,160,'severity','jo_troubletickets',1,'15','ticketseverities','Severity',1,2,'',100,9,25,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(13,161,'status','jo_troubletickets',1,'15','ticketstatus','Status',1,2,'',100,8,25,1,'V~M',1,2,'BAS',1,NULL,1,0),(13,162,'category','jo_troubletickets',1,'15','ticketcategories','Category',1,2,'',100,11,25,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(13,163,'update_log','jo_troubletickets',1,'19','update_log','Update History',1,1,'',100,12,25,3,'V~O',1,NULL,'BAS',0,NULL,0,0),(13,164,'hours','jo_troubletickets',1,'1','hours','Hours',1,2,'',100,10,25,1,'N~O',1,NULL,'BAS',1,'This gives the estimated hours for the Ticket.<br>When the same ticket is added to a Service Contract,based on the Tracking Unit of the Service Contract,Used units is updated whenever a ticket is Closed.',0,0),(13,165,'days','jo_troubletickets',1,'1','days','Days',1,2,'',100,11,25,1,'N~O',1,NULL,'BAS',1,'This gives the estimated days for the Ticket.<br>When the same ticket is added to a Service Contract,based on the Tracking Unit of the Service Contract,Used units is updated whenever a ticket is Closed.',0,0),(13,166,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,10,25,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(13,167,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,13,25,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(13,168,'from_portal','jo_ticketcf',1,'56','from_portal','From Portal',1,0,'',100,14,25,3,'C~O',3,NULL,'BAS',0,NULL,0,0),(13,169,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,17,25,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(13,170,'title','jo_troubletickets',1,'22','ticket_title','Title',1,0,'',100,1,25,1,'V~M',0,1,'BAS',1,NULL,1,0),(13,171,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,28,1,'V~O',2,4,'BAS',1,NULL,0,0),(13,172,'solution','jo_troubletickets',1,'19','solution','Solution',1,0,'',100,1,29,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(13,173,'comments','jo_ticketcomments',1,'19','comments','Add Comment',1,1,'',100,1,30,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(14,174,'productname','jo_products',1,'2','productname','Product Name',1,0,'',100,1,31,1,'V~M',0,1,'BAS',1,NULL,1,0),(14,175,'product_no','jo_products',1,'4','product_no','Product No',1,0,'',100,2,31,1,'V~O',3,NULL,'BAS',0,NULL,0,1),(14,176,'productcode','jo_products',1,'1','productcode','Part Number',1,2,'',100,4,31,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(14,177,'discontinued','jo_products',1,'56','discontinued','Product Active',1,2,'1',100,3,31,1,'V~O',2,2,'BAS',1,NULL,0,1),(14,178,'manufacturer','jo_products',1,'15','manufacturer','Manufacturer',1,2,'',100,6,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,179,'productcategory','jo_products',1,'15','productcategory','Product Category',1,2,'',100,6,31,1,'V~O',1,NULL,'BAS',1,NULL,0,1),(14,180,'sales_start_date','jo_products',1,'5','sales_start_date','Sales Start Date',1,2,'',100,5,31,1,'D~O',1,NULL,'BAS',1,NULL,0,0),(14,181,'sales_end_date','jo_products',1,'5','sales_end_date','Sales End Date',1,2,'',100,8,31,1,'D~O~OTH~GE~sales_start_date~Sales Start Date',1,NULL,'BAS',1,NULL,0,0),(14,182,'start_date','jo_products',1,'5','start_date','Support Start Date',1,2,'',100,7,31,1,'D~O',1,NULL,'BAS',1,NULL,0,0),(14,183,'expiry_date','jo_products',1,'5','expiry_date','Support Expiry Date',1,2,'',100,10,31,1,'D~O~OTH~GE~start_date~Start Date',1,NULL,'BAS',1,NULL,0,0),(14,184,'website','jo_products',1,'17','website','Website',1,2,'',100,14,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,185,'vendor_id','jo_products',1,'75','vendor_id','Vendor Name',1,2,'',100,13,31,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(14,186,'mfr_part_no','jo_products',1,'1','mfr_part_no','Mfr PartNo',1,2,'',100,16,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,187,'vendor_part_no','jo_products',1,'1','vendor_part_no','Vendor PartNo',1,2,'',100,15,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,188,'serialno','jo_products',1,'1','serial_no','Serial No',1,2,'',100,18,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,189,'productsheet','jo_products',1,'1','productsheet','Product Sheet',1,2,'',100,17,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,190,'glacct','jo_products',1,'15','glacct','GL Account',1,2,'',100,20,31,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(14,191,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,19,31,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(14,192,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,21,31,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(14,193,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,31,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(14,194,'unit_price','jo_products',1,'72','unit_price','Unit Price',1,0,'',100,1,32,1,'N~O',2,3,'BAS',0,NULL,1,0),(14,195,'commissionrate','jo_products',1,'9','commissionrate','Commission Rate',1,2,'',100,2,32,1,'N~O',1,NULL,'BAS',1,NULL,1,0),(14,196,'taxclass','jo_products',1,'83','taxclass','Taxes',1,2,'',100,4,32,1,'V~O',2,NULL,'BAS',1,NULL,0,0),(14,197,'usageunit','jo_products',1,'15','usageunit','Usage Unit',1,2,'',100,1,33,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(14,198,'qty_per_unit','jo_products',1,'1','qty_per_unit','Qty/Unit',1,2,'',100,2,33,1,'N~O',1,NULL,'ADV',1,NULL,1,0),(14,199,'qtyinstock','jo_products',1,'1','qtyinstock','Qty In Stock',1,2,'',100,3,33,1,'NN~O',0,4,'ADV',1,NULL,0,1),(14,200,'reorderlevel','jo_products',1,'1','reorderlevel','Reorder Level',1,2,'',100,4,33,1,'I~O',1,NULL,'ADV',1,NULL,0,0),(14,201,'smownerid','jo_crmentity',1,'53','assigned_user_id','Handler',1,0,'',100,5,33,1,'V~M',0,5,'BAS',1,NULL,0,0),(14,202,'qtyindemand','jo_products',1,'1','qtyindemand','Qty In Demand',1,2,'',100,6,33,1,'I~O',1,NULL,'ADV',1,NULL,0,0),(14,203,'imagename','jo_products',1,'69','imagename','Product Image',1,2,'',100,1,35,1,'V~O',3,NULL,'ADV',0,NULL,0,0),(14,204,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,36,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(8,205,'title','jo_notes',1,'2','notes_title','Title',1,0,'',100,1,17,1,'V~M',0,1,'BAS',1,NULL,1,0),(8,206,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,5,17,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(8,207,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,6,17,2,'DT~O',3,NULL,'BAS',0,NULL,1,0),(8,208,'filename','jo_notes',1,'28','filename','File Name',1,2,'',100,3,18,1,'V~O',0,NULL,'BAS',0,NULL,1,0),(8,209,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,4,17,1,'V~M',0,3,'BAS',1,NULL,1,0),(8,210,'notecontent','jo_notes',1,'19','notecontent','Note',1,2,'',100,1,82,1,'V~O',1,NULL,'BAS',0,NULL,0,0),(8,211,'filetype','jo_notes',1,'1','filetype','File Type',1,2,'',100,5,18,2,'V~O',3,0,'BAS',0,NULL,0,0),(8,212,'filesize','jo_notes',1,'1','filesize','File Size',1,2,'',100,4,18,2,'I~O',3,0,'BAS',0,NULL,0,0),(8,213,'filelocationtype','jo_notes',1,'27','filelocationtype','Download Type',1,0,'',100,1,18,1,'V~O',0,0,'BAS',0,NULL,0,0),(8,214,'fileversion','jo_notes',1,'1','fileversion','Version',1,2,'',100,6,18,1,'V~O',1,0,'BAS',1,NULL,0,0),(8,215,'filestatus','jo_notes',1,'56','filestatus','Active',1,2,'1',100,2,18,1,'V~O',1,0,'BAS',1,NULL,0,0),(8,216,'filedownloadcount','jo_notes',1,'1','filedownloadcount','Download Count',1,2,'',100,7,18,2,'I~O',3,0,'BAS',0,NULL,0,0),(8,217,'folderid','jo_notes',1,'26','folderid','Folder Name',1,2,'',100,2,17,1,'V~O',2,2,'BAS',1,NULL,1,0),(8,218,'note_no','jo_notes',1,'4','note_no','Document No',1,0,'',100,3,17,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(8,219,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,12,17,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(10,220,'date_start','jo_activity',1,'6','date_start','Date & Time Sent',1,0,'',100,1,21,1,'DT~M~time_start~Time Start',1,NULL,'BAS',1,NULL,0,0),(10,221,'semodule','jo_activity',1,'2','parent_type','Sales Enity Module',1,0,'',100,2,21,3,'',1,NULL,'BAS',1,NULL,0,0),(10,222,'activitytype','jo_activity',1,'2','activitytype','Activtiy Type',1,0,'',100,3,21,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(10,223,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,5,21,1,'V~M',1,NULL,'BAS',1,NULL,0,0),(10,224,'subject','jo_activity',1,'2','subject','Subject',1,0,'',100,1,23,1,'V~M',1,NULL,'BAS',1,NULL,0,0),(10,225,'name','jo_attachments',1,'61','filename','Attachment',1,0,'',100,2,23,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(10,226,'description','jo_crmentity',1,'19','description','Description',1,0,'',100,1,24,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(10,227,'time_start','jo_activity',1,'2','time_start','Time Start',1,0,'',100,9,23,1,'T~O',1,NULL,'BAS',1,NULL,0,0),(10,228,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,10,22,1,'DT~O',3,NULL,'BAS',0,NULL,0,0),(10,229,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,11,21,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(10,230,'access_count','jo_email_track',1,'25','access_count','Access Count',1,0,'0',100,6,21,3,'I~O',1,NULL,'BAS',0,NULL,0,0),(10,231,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,12,21,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(9,232,'subject','jo_activity',1,'2','subject','Subject',1,0,'',100,1,19,1,'V~M',0,1,'BAS',1,NULL,1,0),(9,233,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,2,19,1,'V~M',0,4,'BAS',1,NULL,1,0),(9,234,'date_start','jo_activity',1,'6','date_start','Start Date & Time',1,0,'',100,3,19,1,'DT~M~time_start',0,2,'BAS',1,NULL,1,0),(9,235,'time_start','jo_activity',1,'2','time_start','Time Start',1,0,'',100,4,19,3,'T~M',1,NULL,'BAS',1,NULL,1,0),(9,236,'time_end','jo_activity',1,'2','time_end','End Time',1,0,'',100,4,19,3,'T~O',1,NULL,'BAS',1,NULL,1,0),(9,237,'due_date','jo_activity',1,'23','due_date','Due Date',1,0,'',100,5,19,1,'D~M~OTH~GE~date_start~Start Date & Time',1,NULL,'BAS',1,NULL,1,0),(9,238,'crmid','jo_seactivityrel',1,'66','parent_id','Related To',1,0,'',100,7,19,1,'I~O',1,NULL,'BAS',1,NULL,1,0),(9,239,'contactid','jo_cntactivityrel',1,'57','contact_id','Contact Name',1,0,'',100,8,19,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(9,240,'status','jo_activity',1,'15','taskstatus','Status',1,0,'',100,8,19,1,'V~M',0,3,'BAS',1,NULL,0,0),(9,241,'eventstatus','jo_activity',1,'15','eventstatus','Status',1,0,'',100,9,19,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(9,242,'priority','jo_activity',1,'15','taskpriority','Priority',1,0,'',100,10,19,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(9,243,'sendnotification','jo_activity',1,'56','sendnotification','Send Notification',1,0,'',100,11,19,1,'C~O',1,NULL,'BAS',1,NULL,0,0),(9,244,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,14,19,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(9,245,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,15,19,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(9,246,'activitytype','jo_activity',1,'15','activitytype','Activity Type',1,0,'',100,16,19,3,'V~O',1,NULL,'BAS',1,NULL,1,0),(9,247,'visibility','jo_activity',1,'16','visibility','Visibility',1,0,'',100,17,19,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(9,248,'description','jo_crmentity',1,'19','description','Description',1,0,'',100,1,20,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(9,249,'duration_hours','jo_activity',1,'63','duration_hours','Duration',1,0,'',100,17,19,3,'T~O',1,NULL,'BAS',1,NULL,0,0),(9,250,'duration_minutes','jo_activity',1,'16','duration_minutes','Duration Minutes',1,0,'',100,18,19,3,'T~O',1,NULL,'BAS',1,NULL,0,0),(9,251,'location','jo_activity',1,'1','location','Location',1,0,'',100,19,19,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(9,252,'reminder_time','jo_activity_reminder',1,'30','reminder_time','Send Reminder',1,0,'',100,1,110,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(9,253,'recurringtype','jo_activity',1,'16','recurringtype','Recurrence',1,0,'',100,6,19,3,'O~O',1,NULL,'BAS',1,NULL,1,0),(9,254,'notime','jo_activity',1,'56','notime','No Time',1,0,'',100,20,19,3,'C~O',1,NULL,'BAS',1,NULL,0,0),(9,255,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,19,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(16,256,'subject','jo_activity',1,'2','subject','Subject',1,0,'',100,1,37,1,'V~M',0,1,'BAS',1,NULL,1,0),(16,257,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,2,37,1,'V~M',0,6,'BAS',1,NULL,1,0),(16,258,'date_start','jo_activity',1,'6','date_start','Start Date & Time',1,0,'',100,3,37,1,'DT~M~time_start',0,2,'BAS',1,NULL,1,0),(16,259,'time_start','jo_activity',1,'2','time_start','Time Start',1,0,'',100,4,37,3,'T~M',1,NULL,'BAS',1,NULL,1,0),(16,260,'due_date','jo_activity',1,'23','due_date','End Date',1,0,'',100,5,37,1,'D~M~OTH~GE~date_start~Start Date & Time',0,5,'BAS',1,NULL,1,0),(16,261,'time_end','jo_activity',1,'2','time_end','End Time',1,0,'',100,5,37,3,'T~M',1,NULL,'BAS',1,NULL,1,0),(16,262,'recurringtype','jo_activity',1,'16','recurringtype','Recurrence',1,0,'',100,6,107,1,'O~O',1,NULL,'BAS',1,NULL,1,0),(16,263,'duration_hours','jo_activity',1,'63','duration_hours','Duration',1,0,'',100,7,37,3,'I~M',1,NULL,'BAS',1,NULL,0,0),(16,264,'duration_minutes','jo_activity',1,'16','duration_minutes','Duration Minutes',1,0,'',100,8,37,3,'O~O',1,NULL,'BAS',1,NULL,0,0),(16,265,'crmid','jo_seactivityrel',1,'66','parent_id','Related To',1,0,'',100,9,109,1,'I~O',1,NULL,'BAS',1,NULL,1,0),(16,266,'eventstatus','jo_activity',1,'15','eventstatus','Status',1,0,'',100,10,37,1,'V~M',0,3,'BAS',1,NULL,0,0),(16,267,'sendnotification','jo_activity',1,'56','sendnotification','Send Notification',1,0,'',100,11,37,1,'C~O',1,NULL,'BAS',1,NULL,0,0),(16,268,'activitytype','jo_activity',1,'15','activitytype','Activity Type',1,0,'',100,12,37,1,'V~M',0,4,'BAS',1,NULL,1,0),(16,269,'location','jo_activity',1,'1','location','Location',1,0,'',100,13,37,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(16,270,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,14,37,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(16,271,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,15,37,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(16,272,'priority','jo_activity',1,'15','taskpriority','Priority',1,0,'',100,16,37,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(16,273,'notime','jo_activity',1,'56','notime','No Time',1,0,'',100,17,37,3,'C~O',1,NULL,'BAS',1,NULL,0,0),(16,274,'visibility','jo_activity',1,'16','visibility','Visibility',1,0,'',100,18,37,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(16,275,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,37,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(16,276,'description','jo_crmentity',1,'19','description','Description',1,0,'',100,1,41,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(16,277,'reminder_time','jo_activity_reminder',1,'30','reminder_time','Send Reminder',1,0,'',100,1,40,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(16,278,'contactid','jo_cntactivityrel',1,'57','contact_id','Contact Name',1,0,'',100,1,109,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(18,279,'vendorname','jo_vendor',1,'2','vendorname','Vendor Name',1,0,'',100,1,40,1,'V~M',0,1,'BAS',1,NULL,1,0),(18,280,'vendor_no','jo_vendor',1,'4','vendor_no','Vendor No',1,0,'',100,2,40,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(18,281,'phone','jo_vendor',1,'1','phone','Phone',1,2,'',100,4,40,1,'V~O',2,2,'BAS',1,NULL,0,1),(18,282,'email','jo_vendor',1,'13','email','Email',1,2,'',100,3,40,1,'E~O',2,3,'BAS',1,NULL,0,1),(18,283,'website','jo_vendor',1,'17','website','Website',1,2,'',100,6,40,1,'V~O',1,NULL,'BAS',1,NULL,0,1),(18,284,'glacct','jo_vendor',1,'15','glacct','GL Account',1,2,'',100,5,40,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(18,285,'category','jo_vendor',1,'1','category','Category',1,2,'',100,8,40,1,'V~O',1,NULL,'BAS',1,NULL,1,0),(18,286,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,7,40,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(18,287,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,9,40,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(18,288,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,12,40,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(18,289,'street','jo_vendor',1,'21','street','Street',1,2,'',100,1,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,290,'pobox','jo_vendor',1,'1','pobox','Po Box',1,2,'',100,2,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,291,'city','jo_vendor',1,'1','city','City',1,2,'',100,3,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,292,'state','jo_vendor',1,'1','state','State',1,2,'',100,4,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,293,'postalcode','jo_vendor',1,'1','postalcode','Postal Code',1,2,'',100,5,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,294,'country','jo_vendor',1,'1','country','Country',1,2,'',100,6,42,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(18,295,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,43,1,'V~O',1,NULL,'ADV',1,NULL,0,0),(19,296,'bookname','jo_pricebook',1,'2','bookname','Price Book Name',1,0,'',100,1,44,1,'V~M',0,1,'BAS',1,NULL,1,0),(19,297,'pricebook_no','jo_pricebook',1,'4','pricebook_no','PriceBook No',1,0,'',100,3,44,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(19,298,'active','jo_pricebook',1,'56','active','Active',1,2,'1',100,2,44,1,'C~O',2,2,'BAS',1,NULL,1,0),(19,299,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,4,44,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(19,300,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,5,44,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(19,301,'currency_id','jo_pricebook',1,'117','currency_id','Currency',1,0,'',100,5,44,1,'I~M',0,3,'BAS',0,NULL,0,0),(19,302,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,7,44,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(19,303,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,46,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(20,304,'quote_no','jo_quotes',1,'4','quote_no','Quote No',1,0,'',100,3,47,1,'V~O',3,NULL,'BAS',0,NULL,1,0),(20,305,'subject','jo_quotes',1,'2','subject','Subject',1,0,'',100,1,47,1,'V~M',1,NULL,'BAS',1,NULL,1,0),(20,306,'potentialid','jo_quotes',1,'76','potential_id','Potential Name',1,2,'',100,2,47,1,'I~O',3,NULL,'BAS',1,NULL,1,0),(20,307,'quotestage','jo_quotes',1,'15','quotestage','Quote Stage',1,2,'',100,4,47,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(20,308,'validtill','jo_quotes',1,'5','validtill','Valid Till',1,2,'',100,5,47,1,'D~O',3,NULL,'BAS',1,NULL,0,0),(20,309,'contactid','jo_quotes',1,'57','contact_id','Contact Name',1,2,'',100,6,47,1,'V~O',3,NULL,'BAS',1,NULL,0,1),(20,310,'carrier','jo_quotes',1,'15','carrier','Carrier',1,2,'',100,8,47,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,311,'subtotal','jo_quotes',1,'72','hdnSubTotal','Sub Total',1,2,'',100,9,47,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(20,312,'shipping','jo_quotes',1,'1','shipping','Shipping',1,2,'',100,10,47,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,313,'inventorymanager','jo_quotes',1,'77','assigned_user_id1','Inventory Manager',1,2,'',100,11,47,1,'I~O',3,NULL,'BAS',1,NULL,0,0),(20,314,'adjustment','jo_quotes',1,'72','txtAdjustment','Adjustment',1,2,'',100,20,47,3,'NN~O',3,NULL,'BAS',1,NULL,0,0),(20,315,'total','jo_quotes',1,'72','hdnGrandTotal','Total',1,2,'',100,14,47,3,'N~O',3,NULL,'BAS',1,NULL,0,1),(20,316,'taxtype','jo_quotes',1,'16','hdnTaxType','Tax Type',1,2,'',100,14,47,3,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,317,'discount_percent','jo_quotes',1,'1','hdnDiscountPercent','Discount Percent',1,2,'',100,14,106,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(20,318,'discount_amount','jo_quotes',1,'72','hdnDiscountAmount','Discount Amount',1,2,'',100,14,106,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(20,319,'s_h_amount','jo_quotes',1,'72','hdnS_H_Amount','S&H Amount',1,2,'',100,14,47,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(20,320,'accountid','jo_quotes',1,'73','account_id','Account Name',1,2,'',100,16,47,1,'I~M',3,NULL,'BAS',1,NULL,0,1),(20,321,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,17,47,1,'V~M',3,NULL,'BAS',1,NULL,1,0),(20,322,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,18,47,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(20,323,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,19,47,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(20,324,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,47,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(20,325,'currency_id','jo_quotes',1,'117','currency_id','Currency',1,2,'1',100,20,47,3,'I~O',3,NULL,'BAS',1,NULL,0,0),(20,326,'conversion_rate','jo_quotes',1,'1','conversion_rate','Conversion Rate',1,2,'1',100,21,47,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(20,327,'bill_street','jo_quotesbillads',1,'24','bill_street','Billing Address',1,2,'',100,1,49,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(20,328,'ship_street','jo_quotesshipads',1,'24','ship_street','Shipping Address',1,2,'',100,2,49,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(20,329,'bill_city','jo_quotesbillads',1,'1','bill_city','Billing City',1,2,'',100,5,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,330,'ship_city','jo_quotesshipads',1,'1','ship_city','Shipping City',1,2,'',100,6,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,331,'bill_state','jo_quotesbillads',1,'1','bill_state','Billing State',1,2,'',100,7,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,332,'ship_state','jo_quotesshipads',1,'1','ship_state','Shipping State',1,2,'',100,8,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,333,'bill_code','jo_quotesbillads',1,'1','bill_code','Billing Code',1,2,'',100,9,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,334,'ship_code','jo_quotesshipads',1,'1','ship_code','Shipping Code',1,2,'',100,10,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,335,'bill_country','jo_quotesbillads',1,'1','bill_country','Billing Country',1,2,'',100,11,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,336,'ship_country','jo_quotesshipads',1,'1','ship_country','Shipping Country',1,2,'',100,12,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,337,'bill_pobox','jo_quotesbillads',1,'1','bill_pobox','Billing Po Box',1,2,'',100,3,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,338,'ship_pobox','jo_quotesshipads',1,'1','ship_pobox','Shipping Po Box',1,2,'',100,4,49,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(20,339,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,52,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(20,340,'terms_conditions','jo_quotes',1,'19','terms_conditions','Terms & Conditions',1,2,'',100,1,51,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(21,341,'purchaseorder_no','jo_purchaseorder',1,'4','purchaseorder_no','PurchaseOrder No',1,0,'',100,2,53,1,'V~O',3,NULL,'BAS',0,NULL,1,0),(21,342,'subject','jo_purchaseorder',1,'2','subject','Subject',1,0,'',100,1,53,1,'V~M',3,NULL,'BAS',1,NULL,1,0),(21,343,'vendorid','jo_purchaseorder',1,'81','vendor_id','Vendor Name',1,0,'',100,3,53,1,'I~M',3,NULL,'BAS',1,NULL,1,0),(21,344,'requisition_no','jo_purchaseorder',1,'1','requisition_no','Requisition No',1,2,'',100,4,53,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,345,'tracking_no','jo_purchaseorder',1,'1','tracking_no','Tracking Number',1,2,'',100,5,53,1,'V~O',3,NULL,'BAS',1,NULL,1,0),(21,346,'contactid','jo_purchaseorder',1,'57','contact_id','Contact Name',1,2,'',100,6,53,1,'I~O',3,NULL,'BAS',1,NULL,0,1),(21,347,'duedate','jo_purchaseorder',1,'5','duedate','Due Date',1,2,'',100,7,53,1,'D~O',3,NULL,'BAS',1,NULL,0,0),(21,348,'carrier','jo_purchaseorder',1,'15','carrier','Carrier',1,2,'',100,8,53,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,349,'adjustment','jo_purchaseorder',1,'72','txtAdjustment','Adjustment',1,2,'',100,10,53,3,'NN~O',3,NULL,'BAS',1,NULL,0,0),(21,350,'salescommission','jo_purchaseorder',1,'1','salescommission','Sales Commission',1,2,'',100,11,53,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,351,'exciseduty','jo_purchaseorder',1,'1','exciseduty','Excise Duty',1,2,'',100,12,53,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,352,'total','jo_purchaseorder',1,'72','hdnGrandTotal','Total',1,2,'',100,13,53,3,'N~O',3,NULL,'BAS',1,NULL,1,0),(21,353,'subtotal','jo_purchaseorder',1,'72','hdnSubTotal','Sub Total',1,2,'',100,14,53,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,354,'taxtype','jo_purchaseorder',1,'16','hdnTaxType','Tax Type',1,2,'',100,14,53,3,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,355,'discount_percent','jo_purchaseorder',1,'1','hdnDiscountPercent','Discount Percent',1,2,'',100,14,105,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,356,'discount_amount','jo_purchaseorder',1,'72','hdnDiscountAmount','Discount Amount',1,0,'',100,14,105,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,357,'s_h_amount','jo_purchaseorder',1,'72','hdnS_H_Amount','S&H Amount',1,2,'',100,14,53,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,358,'postatus','jo_purchaseorder',1,'15','postatus','Status',1,2,'',100,15,53,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(21,359,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,16,53,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(21,360,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,17,53,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(21,361,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,18,53,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(21,362,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,53,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(21,363,'currency_id','jo_purchaseorder',1,'117','currency_id','Currency',1,2,'1',100,19,53,3,'I~O',3,NULL,'BAS',1,NULL,0,0),(21,364,'conversion_rate','jo_purchaseorder',1,'1','conversion_rate','Conversion Rate',1,2,'1',100,20,53,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(21,365,'bill_street','jo_pobillads',1,'24','bill_street','Billing Address',1,2,'',100,1,55,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(21,366,'ship_street','jo_poshipads',1,'24','ship_street','Shipping Address',1,2,'',100,2,55,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(21,367,'bill_city','jo_pobillads',1,'1','bill_city','Billing City',1,2,'',100,5,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,368,'ship_city','jo_poshipads',1,'1','ship_city','Shipping City',1,2,'',100,6,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,369,'bill_state','jo_pobillads',1,'1','bill_state','Billing State',1,2,'',100,7,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,370,'ship_state','jo_poshipads',1,'1','ship_state','Shipping State',1,2,'',100,8,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,371,'bill_code','jo_pobillads',1,'1','bill_code','Billing Code',1,2,'',100,9,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,372,'ship_code','jo_poshipads',1,'1','ship_code','Shipping Code',1,2,'',100,10,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,373,'bill_country','jo_pobillads',1,'1','bill_country','Billing Country',1,2,'',100,11,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,374,'ship_country','jo_poshipads',1,'1','ship_country','Shipping Country',1,2,'',100,12,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,375,'bill_pobox','jo_pobillads',1,'1','bill_pobox','Billing Po Box',1,2,'',100,3,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,376,'ship_pobox','jo_poshipads',1,'1','ship_pobox','Shipping Po Box',1,2,'',100,4,55,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(21,377,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,58,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(21,378,'terms_conditions','jo_purchaseorder',1,'19','terms_conditions','Terms & Conditions',1,2,'',100,1,57,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(22,379,'salesorder_no','jo_salesorder',1,'4','salesorder_no','SalesOrder No',1,0,'',100,4,59,1,'V~O',3,NULL,'BAS',0,NULL,1,0),(22,380,'subject','jo_salesorder',1,'2','subject','Subject',1,0,'',100,1,59,1,'V~M',3,NULL,'BAS',1,NULL,1,0),(22,381,'potentialid','jo_salesorder',1,'76','potential_id','Potential Name',1,2,'',100,2,59,1,'I~O',3,NULL,'BAS',1,NULL,0,0),(22,382,'customerno','jo_salesorder',1,'1','customerno','Customer No',1,2,'',100,3,59,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,383,'quoteid','jo_salesorder',1,'78','quote_id','Quote Name',1,2,'',100,5,59,1,'I~O',3,NULL,'BAS',0,NULL,1,0),(22,384,'purchaseorder','jo_salesorder',1,'1','jo_purchaseorder','Purchase Order',1,2,'',100,5,59,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,385,'contactid','jo_salesorder',1,'57','contact_id','Contact Name',1,2,'',100,6,59,1,'I~O',3,NULL,'BAS',1,NULL,0,1),(22,386,'duedate','jo_salesorder',1,'5','duedate','Due Date',1,2,'',100,8,59,1,'D~O',3,NULL,'BAS',1,NULL,0,0),(22,387,'carrier','jo_salesorder',1,'15','carrier','Carrier',1,2,'',100,9,59,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,388,'pending','jo_salesorder',1,'1','pending','Pending',1,2,'',100,10,59,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,389,'sostatus','jo_salesorder',1,'15','sostatus','Status',1,2,'',100,11,59,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(22,390,'adjustment','jo_salesorder',1,'72','txtAdjustment','Adjustment',1,2,'',100,12,59,3,'NN~O',3,NULL,'BAS',1,NULL,0,0),(22,391,'salescommission','jo_salesorder',1,'1','salescommission','Sales Commission',1,2,'',100,13,59,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,392,'exciseduty','jo_salesorder',1,'1','exciseduty','Excise Duty',1,2,'',100,13,59,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,393,'total','jo_salesorder',1,'72','hdnGrandTotal','Total',1,2,'',100,14,59,3,'N~O',3,NULL,'BAS',1,NULL,1,0),(22,394,'subtotal','jo_salesorder',1,'72','hdnSubTotal','Sub Total',1,2,'',100,15,59,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,395,'taxtype','jo_salesorder',1,'16','hdnTaxType','Tax Type',1,2,'',100,15,59,3,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,396,'discount_percent','jo_salesorder',1,'1','hdnDiscountPercent','Discount Percent',1,2,'',100,15,104,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,397,'discount_amount','jo_salesorder',1,'72','hdnDiscountAmount','Discount Amount',1,0,'',100,15,104,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,398,'s_h_amount','jo_salesorder',1,'72','hdnS_H_Amount','S&H Amount',1,2,'',100,15,59,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,399,'accountid','jo_salesorder',1,'73','account_id','Account Name',1,2,'',100,16,59,1,'I~M',3,NULL,'BAS',1,NULL,0,1),(22,400,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,17,59,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(22,401,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,18,59,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(22,402,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,19,59,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(22,403,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,59,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(22,404,'currency_id','jo_salesorder',1,'117','currency_id','Currency',1,2,'1',100,20,59,3,'I~O',3,NULL,'BAS',1,NULL,0,0),(22,405,'conversion_rate','jo_salesorder',1,'1','conversion_rate','Conversion Rate',1,2,'1',100,21,59,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(22,406,'bill_street','jo_sobillads',1,'24','bill_street','Billing Address',1,2,'',100,1,61,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(22,407,'ship_street','jo_soshipads',1,'24','ship_street','Shipping Address',1,2,'',100,2,61,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(22,408,'bill_city','jo_sobillads',1,'1','bill_city','Billing City',1,2,'',100,5,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,409,'ship_city','jo_soshipads',1,'1','ship_city','Shipping City',1,2,'',100,6,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,410,'bill_state','jo_sobillads',1,'1','bill_state','Billing State',1,2,'',100,7,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,411,'ship_state','jo_soshipads',1,'1','ship_state','Shipping State',1,2,'',100,8,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,412,'bill_code','jo_sobillads',1,'1','bill_code','Billing Code',1,2,'',100,9,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,413,'ship_code','jo_soshipads',1,'1','ship_code','Shipping Code',1,2,'',100,10,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,414,'bill_country','jo_sobillads',1,'1','bill_country','Billing Country',1,2,'',100,11,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,415,'ship_country','jo_soshipads',1,'1','ship_country','Shipping Country',1,2,'',100,12,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,416,'bill_pobox','jo_sobillads',1,'1','bill_pobox','Billing Po Box',1,2,'',100,3,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,417,'ship_pobox','jo_soshipads',1,'1','ship_pobox','Shipping Po Box',1,2,'',100,4,61,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(22,418,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,64,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(22,419,'terms_conditions','jo_salesorder',1,'19','terms_conditions','Terms & Conditions',1,2,'',100,1,63,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(22,420,'enable_recurring','jo_salesorder',1,'56','enable_recurring','Enable Recurring',1,0,'',100,1,83,1,'C~O',3,NULL,'BAS',0,NULL,0,0),(22,421,'recurring_frequency','jo_invoice_recurring_info',1,'16','recurring_frequency','Frequency',1,0,'',100,2,83,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(22,422,'start_period','jo_invoice_recurring_info',1,'5','start_period','Start Period',1,0,'',100,3,83,1,'D~O',3,NULL,'BAS',0,NULL,0,0),(22,423,'end_period','jo_invoice_recurring_info',1,'5','end_period','End Period',1,0,'',100,4,83,1,'D~O~OTH~G~start_period~Start Period',3,NULL,'BAS',0,NULL,0,0),(22,424,'payment_duration','jo_invoice_recurring_info',1,'16','payment_duration','Payment Duration',1,0,'',100,5,83,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(22,425,'invoice_status','jo_invoice_recurring_info',1,'15','invoicestatus','Invoice Status',1,0,'',100,6,83,1,'V~M',3,NULL,'BAS',0,NULL,0,0),(23,426,'subject','jo_invoice',1,'2','subject','Subject',1,0,'',100,1,65,1,'V~M',3,NULL,'BAS',1,NULL,1,0),(23,427,'salesorderid','jo_invoice',1,'80','salesorder_id','Sales Order',1,2,'',100,2,65,1,'I~O',3,NULL,'BAS',0,NULL,1,0),(23,428,'customerno','jo_invoice',1,'1','customerno','Customer No',1,2,'',100,3,65,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,429,'contactid','jo_invoice',1,'57','contact_id','Contact Name',1,2,'',100,4,65,1,'I~O',3,NULL,'BAS',1,NULL,0,1),(23,430,'invoicedate','jo_invoice',1,'5','invoicedate','Invoice Date',1,2,'',100,5,65,1,'D~O',3,NULL,'BAS',1,NULL,0,0),(23,431,'duedate','jo_invoice',1,'5','duedate','Due Date',1,2,'',100,6,65,1,'D~O',3,NULL,'BAS',1,NULL,0,0),(23,432,'purchaseorder','jo_invoice',1,'1','jo_purchaseorder','Purchase Order',1,2,'',100,8,65,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,433,'adjustment','jo_invoice',1,'72','txtAdjustment','Adjustment',1,2,'',100,9,65,3,'NN~O',3,NULL,'BAS',1,NULL,0,0),(23,434,'salescommission','jo_invoice',1,'1','salescommission','Sales Commission',1,2,'',10,13,65,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,435,'exciseduty','jo_invoice',1,'1','exciseduty','Excise Duty',1,2,'',100,11,65,1,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,436,'subtotal','jo_invoice',1,'72','hdnSubTotal','Sub Total',1,2,'',100,12,65,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,437,'total','jo_invoice',1,'72','hdnGrandTotal','Total',1,2,'',100,13,65,3,'N~O',3,NULL,'BAS',1,NULL,1,0),(23,438,'taxtype','jo_invoice',1,'16','hdnTaxType','Tax Type',1,2,'',100,13,65,3,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,439,'discount_percent','jo_invoice',1,'1','hdnDiscountPercent','Discount Percent',1,2,'',100,13,103,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,440,'discount_amount','jo_invoice',1,'72','hdnDiscountAmount','Discount Amount',1,2,'',100,13,103,5,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,441,'s_h_amount','jo_invoice',1,'72','hdnS_H_Amount','S&H Amount',1,2,'',100,14,57,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,442,'accountid','jo_invoice',1,'73','account_id','Account Name',1,2,'',100,14,65,1,'I~M',3,NULL,'BAS',1,NULL,0,1),(23,443,'invoicestatus','jo_invoice',1,'15','invoicestatus','Status',1,2,'',100,15,65,1,'V~O',3,NULL,'BAS',1,NULL,0,1),(23,444,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,16,65,1,'V~M',3,NULL,'BAS',1,NULL,0,1),(23,445,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,17,65,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(23,446,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,18,65,2,'DT~O',3,NULL,'BAS',0,NULL,0,0),(23,447,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,22,65,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(23,448,'currency_id','jo_invoice',1,'117','currency_id','Currency',1,2,'1',100,19,65,3,'I~O',3,NULL,'BAS',1,NULL,0,0),(23,449,'conversion_rate','jo_invoice',1,'1','conversion_rate','Conversion Rate',1,2,'1',100,20,65,3,'N~O',3,NULL,'BAS',1,NULL,0,0),(23,450,'bill_street','jo_invoicebillads',1,'24','bill_street','Billing Address',1,2,'',100,1,67,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(23,451,'ship_street','jo_invoiceshipads',1,'24','ship_street','Shipping Address',1,2,'',100,2,67,1,'V~M',3,NULL,'BAS',1,NULL,0,0),(23,452,'bill_city','jo_invoicebillads',1,'1','bill_city','Billing City',1,2,'',100,5,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,453,'ship_city','jo_invoiceshipads',1,'1','ship_city','Shipping City',1,2,'',100,6,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,454,'bill_state','jo_invoicebillads',1,'1','bill_state','Billing State',1,2,'',100,7,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,455,'ship_state','jo_invoiceshipads',1,'1','ship_state','Shipping State',1,2,'',100,8,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,456,'bill_code','jo_invoicebillads',1,'1','bill_code','Billing Code',1,2,'',100,9,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,457,'ship_code','jo_invoiceshipads',1,'1','ship_code','Shipping Code',1,2,'',100,10,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,458,'bill_country','jo_invoicebillads',1,'1','bill_country','Billing Country',1,2,'',100,11,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,459,'ship_country','jo_invoiceshipads',1,'1','ship_country','Shipping Country',1,2,'',100,12,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,460,'bill_pobox','jo_invoicebillads',1,'1','bill_pobox','Billing Po Box',1,2,'',100,3,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,461,'ship_pobox','jo_invoiceshipads',1,'1','ship_pobox','Shipping Po Box',1,2,'',100,4,67,1,'V~O',3,NULL,'BAS',1,NULL,0,0),(23,462,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,70,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(23,463,'terms_conditions','jo_invoice',1,'19','terms_conditions','Terms & Conditions',1,2,'',100,1,69,1,'V~O',3,NULL,'ADV',1,NULL,0,0),(23,464,'invoice_no','jo_invoice',1,'4','invoice_no','Invoice No',1,0,'',100,3,65,1,'V~O',3,NULL,'BAS',0,NULL,1,0),(29,465,'user_name','jo_users',1,'106','user_name','User Name',1,0,'',11,1,75,1,'V~M',1,NULL,'BAS',1,NULL,0,0),(29,466,'is_admin','jo_users',1,'156','is_admin','Admin',1,0,'',3,7,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,467,'user_password','jo_users',1,'99','user_password','Password',1,0,'',30,5,75,4,'P~M',1,NULL,'BAS',1,NULL,0,0),(29,468,'confirm_password','jo_users',1,'99','confirm_password','Confirm Password',1,0,'',30,6,75,4,'P~M',1,NULL,'BAS',1,NULL,0,0),(29,469,'first_name','jo_users',1,'1','first_name','First Name',1,0,'',30,3,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,470,'last_name','jo_users',1,'2','last_name','Last Name',1,0,'',30,4,75,1,'V~M',1,NULL,'BAS',1,NULL,0,0),(29,471,'roleid','jo_user2role',1,'98','roleid','Role',1,0,'',200,8,75,1,'V~M',1,NULL,'BAS',1,NULL,0,0),(29,472,'email1','jo_users',1,'104','email1','Email',1,0,'',100,2,75,1,'E~M',1,NULL,'BAS',1,NULL,0,0),(29,473,'status','jo_users',1,'115','status','Status',1,0,'Active',100,10,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,474,'activity_view','jo_users',1,'16','activity_view','Default Activity View',1,0,'',100,6,108,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,475,'lead_view','jo_users',1,'16','lead_view','Default Lead View',1,0,'',100,9,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,476,'hour_format','jo_users',1,'16','hour_format','Calendar Hour Format',1,0,'12',100,4,108,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,477,'end_hour','jo_users',1,'116','end_hour','Day ends at',1,0,'',100,11,75,3,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,478,'start_hour','jo_users',1,'16','start_hour','Day starts at',1,0,'',100,2,108,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,479,'is_owner','jo_users',1,'1','is_owner','Account Owner',0,2,'0',100,12,75,5,'V~O',0,1,'BAS',0,NULL,0,0),(29,480,'title','jo_users',1,'1','title','Title',1,0,'',50,9,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,481,'phone_work','jo_users',1,'11','phone_work','Office Phone',1,0,'',50,13,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,482,'department','jo_users',1,'1','department','Department',1,0,'',50,11,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,483,'phone_mobile','jo_users',1,'11','phone_mobile','Mobile',1,0,'',50,15,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,484,'reports_to_id','jo_users',1,'101','reports_to_id','Reports To',1,0,'',50,16,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,485,'phone_other','jo_users',1,'11','phone_other','Other Phone',1,0,'',50,11,77,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,486,'email2','jo_users',1,'13','email2','Other Email',1,0,'',100,4,77,1,'E~O',1,NULL,'BAS',1,NULL,0,0),(29,487,'phone_fax','jo_users',1,'11','phone_fax','Fax',1,0,'',50,2,77,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,488,'secondaryemail','jo_users',1,'13','secondaryemail','Secondary Email',1,0,'',100,6,77,1,'E~O',1,NULL,'BAS',1,NULL,0,0),(29,489,'phone_home','jo_users',1,'11','phone_home','Home Phone',1,0,'',50,17,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,490,'date_format','jo_users',1,'16','date_format','Date Format',1,0,'',30,3,76,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,491,'signature','jo_users',1,'21','signature','Signature',1,0,'',250,13,77,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,492,'description','jo_users',1,'21','description','Description',1,0,'',250,20,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,493,'address_street','jo_users',1,'21','address_street','Street Address',1,0,'',250,27,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,494,'address_city','jo_users',1,'1','address_city','City',1,0,'',100,29,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,495,'address_state','jo_users',1,'1','address_state','State',1,0,'',100,31,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,496,'address_postalcode','jo_users',1,'1','address_postalcode','Postal Code',1,0,'',100,30,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,497,'address_country','jo_users',1,'1','address_country','Country',1,0,'',100,28,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,498,'accesskey','jo_users',1,'3','accesskey','Webservice Access Key',1,0,'',100,2,81,2,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,499,'time_zone','jo_users',1,'16','time_zone','Time Zone',1,0,'',200,5,108,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,500,'currency_id','jo_users',1,'117','currency_id','Currency',1,0,'',100,1,76,1,'I~O',1,NULL,'BAS',1,NULL,0,0),(29,501,'currency_grouping_pattern','jo_users',1,'16','currency_grouping_pattern','Digit Grouping Pattern',1,0,'',100,2,76,1,'V~O',1,NULL,'BAS',1,'<b>Currency - Digit Grouping Pattern</b> <br/><br/>This pattern specifies the format in which the currency separator will be placed.',0,0),(29,502,'currency_decimal_separator','jo_users',1,'16','currency_decimal_separator','Decimal Separator',1,0,'.',2,3,76,1,'V~O',1,NULL,'BAS',1,'<b>Currency - Decimal Separator</b> <br/><br/>Decimal separator specifies the separator to be used to separate the fractional values from the whole number part. <br/><b>Eg:</b> <br/>. => 123.45 <br/>, => 123,45 <br/>\' => 123\'45 <br/>  => 123 45 <br/>$ => 123$45 <br/>',0,0),(29,503,'currency_grouping_separator','jo_users',1,'16','currency_grouping_separator','Digit Grouping Separator',1,0,',',2,4,76,1,'V~O',1,NULL,'BAS',1,'<b>Currency - Grouping Separator</b> <br/><br/>Grouping separator specifies the separator to be used to group the whole number part into hundreds, thousands etc. <br/><b>Eg:</b> <br/>. => 123.456.789 <br/>, => 123,456,789 <br/>\' => 123\'456\'789 <br/>  => 123 456 789 <br/>$ => 123$456$789 <br/>',0,0),(29,504,'currency_symbol_placement','jo_users',1,'16','currency_symbol_placement','Symbol Placement',1,0,'',20,5,76,1,'V~O',1,NULL,'BAS',1,'<b>Currency - Symbol Placement</b> <br/><br/>Symbol Placement allows you to configure the position of the currency symbol with respect to the currency value.<br/><b>Eg:</b> <br/>$1.0 => $123,456,789.50 <br/>1.0$ => 123,456,789.50$ <br/>',0,0),(29,505,'imagename','jo_users',1,'105','imagename','User Image',1,0,'',250,10,80,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,506,'internal_mailer','jo_users',1,'56','internal_mailer','INTERNAL_MAIL_COMPOSER',1,0,'',50,21,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,507,'theme','jo_users',1,'31','theme','Theme',1,0,'softed',100,16,77,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,508,'language','jo_users',1,'32','language','Language',1,0,'en_us',100,22,75,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,509,'reminder_interval','jo_users',1,'16','reminder_interval','Reminder Interval',1,0,'',100,11,108,1,'V~O',1,NULL,'BAS',1,NULL,0,0),(29,510,'default_landing_page','jo_users',1,'16','default_landing_page','Default Landing Page',1,2,'Home',100,23,75,1,'V~O',1,0,'BAS',1,NULL,0,0),(29,511,'default_dashboard_view','jo_users',1,'16','default_dashboard_view','Default Dashboard View',1,2,'1',1,20,0,1,'V~O',1,0,'BAS',1,NULL,0,0),(10,512,'from_email','jo_emaildetails',1,'12','from_email','From',1,2,'',100,1,21,3,'V~M',3,NULL,'BAS',0,NULL,0,0),(10,513,'to_email','jo_emaildetails',1,'8','saved_toid','To',1,2,'',100,2,21,1,'V~M',3,NULL,'BAS',0,NULL,0,0),(10,514,'cc_email','jo_emaildetails',1,'8','ccmail','CC',1,2,'',1000,3,21,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(10,515,'bcc_email','jo_emaildetails',1,'8','bccmail','BCC',1,2,'',1000,4,21,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(10,516,'idlists','jo_emaildetails',1,'357','parent_id','Parent ID',1,2,'',1000,5,21,1,'V~O',3,NULL,'BAS',0,NULL,0,0),(10,517,'email_flag','jo_emaildetails',1,'16','email_flag','Email Flag',1,2,'',1000,6,21,3,'V~O',3,NULL,'BAS',0,NULL,0,0),(36,518,'servicename','jo_service',1,'2','servicename','Service Name',1,0,'',100,1,86,1,'V~M',0,1,'BAS',1,'',1,NULL),(36,519,'service_no','jo_service',1,'4','service_no','Service No',1,0,'',100,2,86,1,'V~O',3,0,'BAS',0,'',1,NULL),(36,520,'discontinued','jo_service',1,'56','discontinued','Service Active',1,2,'1',100,4,86,1,'V~O',2,3,'BAS',1,'',0,NULL),(36,521,'sales_start_date','jo_service',1,'5','sales_start_date','Sales Start Date',1,2,'',100,9,86,1,'D~O',1,0,'BAS',1,'',0,NULL),(36,522,'sales_end_date','jo_service',1,'5','sales_end_date','Sales End Date',1,2,'',100,10,86,1,'D~O~OTH~GE~sales_start_date~Sales Start Date',1,0,'BAS',1,'',0,NULL),(36,523,'start_date','jo_service',1,'5','start_date','Support Start Date',1,2,'',100,11,86,1,'D~O',1,0,'BAS',1,'',0,NULL),(36,524,'expiry_date','jo_service',1,'5','expiry_date','Support Expiry Date',1,2,'',100,12,86,1,'D~O~OTH~GE~start_date~Start Date',1,0,'BAS',1,'',0,NULL),(36,525,'website','jo_service',1,'17','website','Website',1,2,'',100,6,86,1,'V~O',1,0,'BAS',1,'',0,NULL),(36,526,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,13,86,2,'DT~O',3,0,'BAS',0,'',0,NULL),(36,527,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,14,86,2,'DT~O',3,0,'BAS',0,'',0,NULL),(36,528,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,16,86,3,'V~O',3,0,'BAS',0,'',0,NULL),(36,529,'service_usageunit','jo_service',1,'15','service_usageunit','Usage Unit',1,2,'',100,3,86,1,'V~O',1,0,'BAS',1,'',0,NULL),(36,530,'qty_per_unit','jo_service',1,'1','qty_per_unit','No of Units',1,2,'',100,5,86,1,'N~O',1,0,'BAS',1,'',1,NULL),(36,531,'smownerid','jo_crmentity',1,'53','assigned_user_id','Owner',1,0,'',100,8,86,1,'I~O',1,0,'BAS',1,'',0,NULL),(36,532,'servicecategory','jo_service',1,'15','servicecategory','Service Category',1,2,'',100,7,86,1,'V~O',1,0,'BAS',1,'',0,NULL),(36,533,'unit_price','jo_service',1,'72','unit_price','Price',1,0,'',100,1,87,1,'N~O',2,2,'BAS',0,'',1,NULL),(36,534,'taxclass','jo_service',1,'83','taxclass','Taxes',1,2,'',100,4,87,1,'V~O',2,0,'BAS',1,'',0,NULL),(36,535,'commissionrate','jo_service',1,'9','commissionrate','Commission Rate',1,2,'',100,2,87,1,'N~O',1,0,'BAS',1,'',1,NULL),(36,536,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,89,1,'V~O',1,0,'BAS',1,'',0,NULL),(37,537,'direction','jo_pbxmanager',1,'1','direction','Direction',1,2,'',100,1,90,1,'V~O',1,0,'BAS',1,'',0,NULL),(37,538,'callstatus','jo_pbxmanager',1,'1','callstatus','Call Status',1,2,'',100,2,90,1,'V~O',1,0,'BAS',1,'',1,NULL),(37,539,'starttime','jo_pbxmanager',1,'70','starttime','Start Time',1,2,'',100,7,90,1,'DT~O',1,0,'BAS',1,'',1,NULL),(37,540,'endtime','jo_pbxmanager',1,'70','endtime','End Time',1,2,'',100,8,90,1,'DT~O',1,0,'BAS',1,'',0,NULL),(37,541,'totalduration','jo_pbxmanager',1,'7','totalduration','Total Duration',1,2,'',100,10,90,1,'I~O',1,0,'BAS',1,'',0,NULL),(37,542,'billduration','jo_pbxmanager',1,'7','billduration','Bill Duration',1,2,'',100,11,90,1,'I~O',1,0,'BAS',1,'',0,NULL),(37,543,'recordingurl','jo_pbxmanager',1,'17','recordingurl','Recording URL',1,2,'',100,9,90,1,'V~O',1,0,'BAS',1,'',1,NULL),(37,544,'sourceuuid','jo_pbxmanager',1,'1','sourceuuid','Source UUID',1,2,'',100,12,90,1,'V~O',1,0,'BAS',1,'',0,NULL),(37,545,'gateway','jo_pbxmanager',1,'1','gateway','Gateway',1,2,'',100,13,90,1,'V~O',1,0,'BAS',1,'',0,NULL),(37,546,'customer','jo_pbxmanager',1,'10','customer','Customer',1,2,'',100,3,90,1,'V~O',1,0,'BAS',1,'',1,NULL),(37,547,'user','jo_pbxmanager',1,'52','user','User',1,2,'',100,4,90,1,'V~O',1,0,'BAS',1,'',1,NULL),(37,548,'customernumber','jo_pbxmanager',1,'11','customernumber','Customer Number',1,2,'',100,5,90,1,'V~M',1,0,'BAS',1,'',0,NULL),(37,549,'customertype','jo_pbxmanager',1,'1','customertype','Customer Type',1,2,'',100,6,90,1,'V~O',1,0,'BAS',1,'',0,NULL),(37,550,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,2,'',100,14,90,1,'V~M',1,0,'BAS',1,'',0,NULL),(37,551,'createdtime','jo_crmentity',1,'70','CreatedTime','Created Time',1,2,'',100,15,90,2,'DT~O',1,0,'BAS',1,'',0,NULL),(37,552,'modifiedtime','jo_crmentity',1,'70','ModifiedTime','Modified Time',1,2,'',100,16,90,2,'DT~O',1,0,'BAS',1,'',0,NULL),(29,553,'phone_crm_extension','jo_users',1,'11','phone_crm_extension','CRM Phone Extension',1,2,'',100,24,75,1,'V~O',1,0,'BAS',1,'',0,NULL),(42,554,'projectmilestonename','jo_projectmilestone',1,'2','projectmilestonename','Project Milestone Name',1,2,'',100,1,91,1,'V~M',0,1,'BAS',1,'',1,NULL),(42,555,'projectmilestonedate','jo_projectmilestone',1,'5','projectmilestonedate','Milestone Date',1,2,'',100,5,91,1,'D~O',0,3,'BAS',1,'',1,NULL),(42,556,'projectid','jo_projectmilestone',1,'10','projectid','Related to',1,0,'',100,4,91,1,'V~M',0,4,'BAS',1,'',0,NULL),(42,557,'projectmilestonetype','jo_projectmilestone',1,'15','projectmilestonetype','Type',1,2,'',100,7,91,1,'V~O',1,0,'BAS',1,'',1,NULL),(42,558,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,2,'',100,6,91,1,'V~M',0,2,'BAS',1,'',0,NULL),(42,559,'projectmilestone_no','jo_projectmilestone',2,'4','projectmilestone_no','Project Milestone No',1,0,'',100,2,91,1,'V~O',3,4,'BAS',0,'',0,NULL),(42,560,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,2,'',100,1,92,2,'DT~O',1,0,'BAS',1,'',0,NULL),(42,561,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,2,'',100,2,92,2,'DT~O',1,0,'BAS',1,'',0,NULL),(42,562,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,3,92,3,'V~O',3,0,'BAS',0,'',0,NULL),(42,563,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,93,1,'V~O',1,0,'BAS',1,'',0,NULL),(43,564,'projecttaskname','jo_projecttask',1,'2','projecttaskname','Project Task Name',1,2,'',100,1,94,1,'V~M',0,1,'BAS',1,'',1,NULL),(43,565,'projecttasktype','jo_projecttask',1,'15','projecttasktype','Type',1,2,'',100,4,94,1,'V~O',1,0,'BAS',1,'',1,NULL),(43,566,'projecttaskpriority','jo_projecttask',1,'15','projecttaskpriority','Priority',1,2,'',100,3,94,1,'V~O',1,0,'BAS',1,'',0,NULL),(43,567,'projectid','jo_projecttask',1,'10','projectid','Related to',1,0,'',100,6,94,1,'V~M',0,5,'BAS',1,'',0,NULL),(43,568,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,2,'',100,7,94,1,'V~M',0,2,'BAS',1,'',1,NULL),(43,569,'projecttasknumber','jo_projecttask',1,'7','projecttasknumber','Project Task Number',1,2,'',100,5,94,1,'I~O',1,0,'BAS',1,'',0,NULL),(43,570,'projecttask_no','jo_projecttask',2,'4','projecttask_no','Project Task No',1,0,'',100,2,94,1,'V~O',3,4,'BAS',0,'',0,NULL),(43,571,'projecttaskprogress','jo_projecttask',1,'15','projecttaskprogress','Progress',1,2,'',100,1,95,1,'V~O',1,0,'BAS',1,'',1,NULL),(43,572,'projecttaskhours','jo_projecttask',1,'7','projecttaskhours','Worked Hours',1,2,'',100,2,95,1,'V~O',1,0,'BAS',1,'',0,NULL),(43,573,'startdate','jo_projecttask',1,'5','startdate','Start Date',1,2,'',100,3,95,1,'D~O',0,3,'BAS',1,'',1,NULL),(43,574,'enddate','jo_projecttask',1,'5','enddate','End Date',1,2,'',100,4,95,1,'D~O~OTH~GE~startdate~Start Date',1,0,'BAS',1,'',1,NULL),(43,575,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,2,'',100,5,95,2,'DT~O',1,0,'BAS',1,'',0,NULL),(43,576,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,2,'',100,6,95,2,'DT~O',1,0,'BAS',1,'',0,NULL),(43,577,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,7,95,3,'V~O',3,0,'BAS',0,'',0,NULL),(43,578,'description','jo_crmentity',1,'19','description','description',1,2,'',100,1,96,1,'V~O',1,0,'BAS',1,'',0,NULL),(44,579,'projectname','jo_project',1,'2','projectname','Project Name',1,2,'',100,1,97,1,'V~M',0,1,'BAS',1,'',1,NULL),(44,580,'startdate','jo_project',1,'23','startdate','Start Date',1,2,'',100,3,97,1,'D~O',0,3,'BAS',1,'',1,NULL),(44,581,'targetenddate','jo_project',1,'23','targetenddate','Target End Date',1,2,'',100,5,97,1,'D~O~OTH~GE~startdate~Start Date',0,4,'BAS',1,'',1,NULL),(44,582,'actualenddate','jo_project',1,'23','actualenddate','Actual End Date',1,2,'',100,6,97,1,'D~O~OTH~GE~startdate~Start Date',1,0,'BAS',1,'',0,NULL),(44,583,'projectstatus','jo_project',1,'15','projectstatus','Status',1,2,'',100,7,97,1,'V~O',1,0,'BAS',1,'',1,NULL),(44,584,'projecttype','jo_project',1,'15','projecttype','Type',1,2,'',100,8,97,1,'V~O',1,0,'BAS',1,'',1,NULL),(44,585,'linktoaccountscontacts','jo_project',1,'10','linktoaccountscontacts','Related to',1,2,'',100,9,97,1,'V~O',1,0,'BAS',1,'',0,1),(44,586,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,2,'',100,4,97,1,'V~M',0,2,'BAS',1,'',1,NULL),(44,587,'project_no','jo_project',2,'4','project_no','Project No',1,0,'',100,2,97,1,'V~O',3,0,'BAS',0,'',0,NULL),(44,588,'targetbudget','jo_project',1,'7','targetbudget','Target Budget',1,2,'',100,1,98,1,'V~O',1,0,'BAS',1,'',0,NULL),(44,589,'projecturl','jo_project',1,'17','projecturl','Project Url',1,2,'',100,2,98,1,'V~O',1,0,'BAS',1,'',0,NULL),(44,590,'projectpriority','jo_project',1,'15','projectpriority','Priority',1,2,'',100,3,98,1,'V~O',1,0,'BAS',1,'',0,NULL),(44,591,'progress','jo_project',1,'15','progress','Progress',1,2,'',100,4,98,1,'V~O',1,0,'BAS',1,'',0,NULL),(44,592,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,2,'',100,5,98,2,'DT~O',1,0,'BAS',1,'',0,NULL),(44,593,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,2,'',100,6,98,2,'DT~O',1,0,'BAS',1,'',0,NULL),(44,594,'modifiedby','jo_crmentity',1,'52','modifiedby','Last Modified By',1,0,'',100,7,98,3,'V~O',3,0,'BAS',0,'',0,NULL),(44,595,'description','jo_crmentity',1,'19','description','Description',1,2,'',100,1,99,1,'V~O',1,0,'BAS',1,'',0,NULL),(47,596,'commentcontent','jo_modcomments',1,'19','commentcontent','Comment',1,0,'',100,4,100,1,'V~M',0,4,'BAS',2,'',1,NULL),(47,597,'smownerid','jo_crmentity',1,'53','assigned_user_id','Assigned To',1,0,'',100,1,101,1,'V~M',0,1,'BAS',2,'',1,NULL),(47,598,'createdtime','jo_crmentity',1,'70','createdtime','Created Time',1,0,'',100,5,101,2,'DT~O',0,2,'BAS',0,'',0,NULL),(47,599,'modifiedtime','jo_crmentity',1,'70','modifiedtime','Modified Time',1,0,'',100,6,101,2,'DT~O',0,3,'BAS',0,'',0,NULL),(47,600,'related_to','jo_modcomments',1,'10','related_to','Related To',1,2,'',100,2,101,1,'V~M',2,5,'BAS',2,'',0,NULL),(47,601,'smcreatorid','jo_crmentity',1,'52','creator','Creator',1,2,'',100,4,101,2,'V~O',1,0,'BAS',1,'',0,NULL),(47,602,'parent_comments','jo_modcomments',1,'10','parent_comments','Related To Comments',1,2,'',100,7,101,1,'V~O',1,0,'BAS',1,'',0,NULL),(2,603,'forecast_amount','jo_potential',1,'71','forecast_amount','Forecast Amount',1,2,'',100,18,1,1,'N~O',1,0,'BAS',0,'',0,NULL),(29,604,'no_of_currency_decimals','jo_users',1,'16','no_of_currency_decimals','Number Of Currency Decimals',1,2,'2',100,6,76,1,'V~O',1,0,'BAS',1,'<b>Currency - Number of Decimal places</b> <br/><br/>Number of decimal places specifies how many number of decimals will be shown after decimal separator.<br/><b>Eg:</b> 123.00',0,NULL),(23,605,'productid','jo_inventoryproductrel',1,'10','productid','Item Name',0,2,'',100,1,103,5,'V~M',1,0,'BAS',0,'',0,NULL),(23,606,'quantity','jo_inventoryproductrel',1,'7','quantity','Quantity',0,2,'',100,2,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(23,607,'listprice','jo_inventoryproductrel',1,'71','listprice','List Price',0,2,'',100,3,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(23,608,'comment','jo_inventoryproductrel',1,'19','comment','Item Comment',0,2,'',100,4,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(23,609,'discount_amount','jo_inventoryproductrel',1,'71','discount_amount','Item Discount Amount',0,2,'',100,5,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(23,610,'discount_percent','jo_inventoryproductrel',1,'7','discount_percent','Item Discount Percent',0,2,'',100,6,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(23,611,'tax1','jo_inventoryproductrel',1,'83','tax1','VAT',0,2,'',100,7,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(23,612,'tax2','jo_inventoryproductrel',1,'83','tax2','Sales',0,2,'',100,8,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(23,613,'tax3','jo_inventoryproductrel',1,'83','tax3','Service',0,2,'',100,9,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,614,'productid','jo_inventoryproductrel',1,'10','productid','Item Name',0,2,'',100,1,104,5,'V~M',1,0,'BAS',0,'',0,NULL),(22,615,'quantity','jo_inventoryproductrel',1,'7','quantity','Quantity',0,2,'',100,2,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(22,616,'listprice','jo_inventoryproductrel',1,'71','listprice','List Price',0,2,'',100,3,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(22,617,'comment','jo_inventoryproductrel',1,'19','comment','Item Comment',0,2,'',100,4,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,618,'discount_amount','jo_inventoryproductrel',1,'71','discount_amount','Item Discount Amount',0,2,'',100,5,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(22,619,'discount_percent','jo_inventoryproductrel',1,'7','discount_percent','Item Discount Percent',0,2,'',100,6,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,620,'tax1','jo_inventoryproductrel',1,'83','tax1','VAT',0,2,'',100,7,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,621,'tax2','jo_inventoryproductrel',1,'83','tax2','Sales',0,2,'',100,8,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,622,'tax3','jo_inventoryproductrel',1,'83','tax3','Service',0,2,'',100,9,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(21,623,'productid','jo_inventoryproductrel',1,'10','productid','Item Name',0,2,'',100,1,105,5,'V~M',1,0,'BAS',0,'',0,NULL),(21,624,'quantity','jo_inventoryproductrel',1,'7','quantity','Quantity',0,2,'',100,2,105,5,'N~O',1,0,'BAS',0,'',0,NULL),(21,625,'listprice','jo_inventoryproductrel',1,'71','listprice','List Price',0,2,'',100,3,105,5,'N~O',1,0,'BAS',0,'',0,NULL),(21,626,'comment','jo_inventoryproductrel',1,'19','comment','Item Comment',0,2,'',100,4,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(21,627,'discount_amount','jo_inventoryproductrel',1,'71','discount_amount','Item Discount Amount',0,2,'',100,5,105,5,'N~O',1,0,'BAS',0,'',0,NULL),(21,628,'discount_percent','jo_inventoryproductrel',1,'7','discount_percent','Item Discount Percent',0,2,'',100,6,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(21,629,'tax1','jo_inventoryproductrel',1,'83','tax1','VAT',0,2,'',100,7,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(21,630,'tax2','jo_inventoryproductrel',1,'83','tax2','Sales',0,2,'',100,8,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(21,631,'tax3','jo_inventoryproductrel',1,'83','tax3','Service',0,2,'',100,9,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,632,'productid','jo_inventoryproductrel',1,'10','productid','Item Name',0,2,'',100,1,106,5,'V~M',1,0,'BAS',0,'',0,NULL),(20,633,'quantity','jo_inventoryproductrel',1,'7','quantity','Quantity',0,2,'',100,2,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(20,634,'listprice','jo_inventoryproductrel',1,'71','listprice','List Price',0,2,'',100,3,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(20,635,'comment','jo_inventoryproductrel',1,'19','comment','Item Comment',0,2,'',100,4,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,636,'discount_amount','jo_inventoryproductrel',1,'71','discount_amount','Item Discount Amount',0,2,'',100,5,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(20,637,'discount_percent','jo_inventoryproductrel',1,'7','discount_percent','Item Discount Percent',0,2,'',100,6,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,638,'tax1','jo_inventoryproductrel',1,'83','tax1','VAT',0,2,'',100,7,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,639,'tax2','jo_inventoryproductrel',1,'83','tax2','Sales',0,2,'',100,8,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,640,'tax3','jo_inventoryproductrel',1,'83','tax3','Service',0,2,'',100,9,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(29,641,'truncate_trailing_zeros','jo_users',1,'56','truncate_trailing_zeros','Truncate Trailing Zeros',1,2,'0',100,7,76,1,'V~O',1,0,'BAS',1,'<b> Truncate Trailing Zeros </b> <br/><br/>It truncated trailing 0s in any of Currency, Decimal and Percentage Field types<br/><br/><b>Ex:</b><br/>If value is 89.00000 then <br/>decimal and Percentage fields were shows 89<br/>currency field type - shows 89.00<br/>',0,NULL),(29,644,'dayoftheweek','jo_users',1,'16','dayoftheweek','Starting Day of the week',1,2,'Monday',100,1,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(29,645,'callduration','jo_users',1,'16','callduration','Default Call Duration',1,2,'5',100,7,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(29,646,'othereventduration','jo_users',1,'16','othereventduration','Other Event Duration',1,2,'5',100,8,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(23,647,'pre_tax_total','jo_invoice',1,'72','pre_tax_total','Pre Tax Total',1,2,'',100,23,65,3,'N~O',1,0,'BAS',1,'',0,NULL),(22,648,'pre_tax_total','jo_salesorder',1,'72','pre_tax_total','Pre Tax Total',1,2,'',100,23,59,3,'N~O',1,0,'BAS',1,'',0,NULL),(21,649,'pre_tax_total','jo_purchaseorder',1,'72','pre_tax_total','Pre Tax Total',1,2,'',100,23,53,3,'N~O',1,0,'BAS',1,'',0,NULL),(20,650,'pre_tax_total','jo_quotes',1,'72','pre_tax_total','Pre Tax Total',1,2,'',100,23,47,3,'N~O',1,0,'BAS',1,'',0,NULL),(29,651,'calendarsharedtype','jo_users',1,'16','calendarsharedtype','Calendar Shared Type',1,2,'Public',100,12,108,3,'V~O',1,0,'BAS',1,'',0,NULL),(6,652,'isconvertedfromlead','jo_account',1,'56','isconvertedfromlead','Is Converted From Lead',1,2,'no',100,24,9,1,'C~O',1,0,'BAS',1,'',0,NULL),(4,653,'isconvertedfromlead','jo_contactdetails',1,'56','isconvertedfromlead','Is Converted From Lead',1,2,'no',100,29,4,1,'C~O',1,0,'BAS',1,'',0,NULL),(2,654,'isconvertedfromlead','jo_potential',1,'56','isconvertedfromlead','Is Converted From Lead',1,2,'no',100,19,1,1,'C~O',1,0,'BAS',1,'',0,NULL),(29,655,'default_record_view','jo_users',1,'16','default_record_view','Default Record View',1,2,'Summary',100,25,75,1,'V~O',1,0,'BAS',1,'',0,NULL),(23,656,'received','jo_invoice',1,'72','received','Received',1,2,'0',100,24,65,3,'N~O',1,0,'BAS',1,'',0,NULL),(23,657,'balance','jo_invoice',1,'72','balance','Balance',1,2,'0',100,25,65,3,'N~O',1,0,'BAS',1,'',0,NULL),(21,658,'paid','jo_purchaseorder',1,'72','paid','Paid',1,2,'0',100,24,53,3,'N~O',1,0,'BAS',1,'',0,NULL),(21,659,'balance','jo_purchaseorder',1,'72','balance','Balance',1,2,'0',100,25,53,3,'N~O',1,0,'BAS',1,'',0,NULL),(7,660,'emailoptout','jo_leaddetails',1,'56','emailoptout','Email Opt Out',1,0,'',100,24,13,1,'C~O',1,0,'BAS',1,'',0,NULL),(23,663,'s_h_percent','jo_invoice',1,'1','hdnS_H_Percent','S&H Percent',0,2,'',100,10,103,5,'N~O',0,1,'BAS',0,'',0,NULL),(20,664,'s_h_percent','jo_quotes',1,'1','hdnS_H_Percent','S&H Percent',0,2,'',100,10,106,5,'N~O',0,1,'BAS',0,'',0,NULL),(21,665,'s_h_percent','jo_purchaseorder',1,'1','hdnS_H_Percent','S&H Percent',0,2,'',100,10,105,5,'N~O',0,1,'BAS',0,'',0,NULL),(22,666,'s_h_percent','jo_salesorder',1,'1','hdnS_H_Percent','S&H Percent',0,2,'',100,10,104,5,'N~O',0,1,'BAS',0,'',0,NULL),(29,667,'leftpanelhide','jo_users',1,'56','leftpanelhide','Left Panel Hide',1,2,'0',100,26,75,1,'V~O',1,0,'BAS',1,'',0,NULL),(2,668,'contact_id','jo_potential',1,'10','contact_id','Contact Name',1,2,'',100,4,1,1,'V~O',1,0,'BAS',1,'',1,NULL),(13,669,'contact_id','jo_troubletickets',1,'10','contact_id','Contact Name',1,2,'',100,3,25,1,'V~O',1,0,'BAS',1,'',1,NULL),(29,670,'rowheight','jo_users',1,'16','rowheight','Row Height',1,2,'medium',100,24,77,1,'V~O',1,0,'BAS',1,'',0,NULL),(29,671,'defaulteventstatus','jo_users',1,'15','defaulteventstatus','Default Event Status',1,2,'Planned',100,9,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(29,672,'defaultactivitytype','jo_users',1,'15','defaultactivitytype','Default Activity Type',1,2,'Call',100,10,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(29,673,'hidecompletedevents','jo_users',1,'56','hidecompletedevents','LBL_HIDE_COMPLETED_EVENTS',1,2,'0',100,13,108,1,'C~O',1,0,'BAS',1,'',0,NULL),(14,696,'purchase_cost','jo_products',1,'71','purchase_cost','Purchase Cost',1,0,'',100,5,32,1,'N~O',1,0,'BAS',1,'',0,NULL),(23,698,'potential_id','jo_invoice',1,'10','potential_id','Potential Name',1,2,'',100,26,65,1,'I~O',1,0,'BAS',1,'',0,NULL),(29,699,'defaultcalendarview','jo_users',1,'16','defaultcalendarview','Default Calendar View',1,0,'MyCalendar',100,14,108,1,'V~O',1,0,'BAS',1,'',0,NULL),(23,700,'image','jo_inventoryproductrel',1,'56','image','Image',0,1,'',100,14,103,5,'V~O',1,0,'BAS',0,'',0,NULL),(23,701,'purchase_cost','jo_inventoryproductrel',1,'71','purchase_cost','Purchase Cost',0,1,'',100,15,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(23,702,'margin','jo_inventoryproductrel',1,'71','margin','Margin',0,1,'',100,16,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(20,703,'image','jo_inventoryproductrel',1,'56','image','Image',0,1,'',100,15,106,5,'V~O',1,0,'BAS',0,'',0,NULL),(20,704,'purchase_cost','jo_inventoryproductrel',1,'71','purchase_cost','Purchase Cost',0,1,'',100,16,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(20,705,'margin','jo_inventoryproductrel',1,'71','margin','Margin',0,1,'',100,17,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(21,706,'image','jo_inventoryproductrel',1,'56','image','Image',0,1,'',100,15,105,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,707,'image','jo_inventoryproductrel',1,'56','image','Image',0,1,'',100,16,104,5,'V~O',1,0,'BAS',0,'',0,NULL),(22,708,'purchase_cost','jo_inventoryproductrel',1,'71','purchase_cost','Purchase Cost',0,1,'',100,17,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(22,709,'margin','jo_inventoryproductrel',1,'71','margin','Margin',0,1,'',100,18,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(2,713,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,20,1,2,'V~O',3,7,'BAS',0,'',0,NULL),(4,714,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,30,4,2,'V~O',3,7,'BAS',0,'',0,NULL),(6,715,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,25,9,2,'V~O',3,5,'BAS',0,'',0,NULL),(7,716,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,25,13,2,'V~O',3,7,'BAS',0,'',0,NULL),(8,717,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,13,17,2,'V~O',3,4,'BAS',0,'',0,NULL),(9,718,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,23,19,2,'V~O',3,5,'BAS',0,'',0,NULL),(10,719,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,13,21,2,'V~O',3,1,'BAS',0,'',0,NULL),(13,720,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,18,25,2,'V~O',3,5,'BAS',0,'',0,NULL),(14,721,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,23,31,2,'V~O',3,6,'BAS',0,'',0,NULL),(16,722,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,23,37,2,'V~O',3,7,'BAS',0,'',0,NULL),(18,723,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,13,40,2,'V~O',3,4,'BAS',0,'',0,NULL),(19,724,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,8,44,2,'V~O',3,4,'BAS',0,'',0,NULL),(20,725,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,24,47,2,'V~O',3,2,'BAS',0,'',0,NULL),(21,726,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,26,53,2,'V~O',3,2,'BAS',0,'',0,NULL),(22,727,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,24,59,2,'V~O',3,2,'BAS',0,'',0,NULL),(23,728,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,27,65,2,'V~O',3,2,'BAS',0,'',0,NULL),(26,729,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,17,72,2,'V~O',3,8,'BAS',0,'',0,NULL),(10,735,'click_count','jo_email_track',1,'25','click_count','Click Count',1,2,'0',100,14,21,3,'I~O',0,2,'BAS',0,'',0,NULL),(2,736,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,21,1,6,'C~O',3,8,'BAS',0,'',0,NULL),(4,737,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,31,4,6,'C~O',3,8,'BAS',0,'',0,NULL),(6,738,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,26,9,6,'C~O',3,6,'BAS',0,'',0,NULL),(7,739,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,26,13,6,'C~O',3,8,'BAS',0,'',0,NULL),(8,740,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,14,17,6,'C~O',3,5,'BAS',0,'',0,NULL),(9,741,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,24,19,6,'C~O',3,6,'BAS',0,'',0,NULL),(10,742,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,15,21,6,'C~O',3,3,'BAS',0,'',0,NULL),(13,743,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,19,25,6,'C~O',3,6,'BAS',0,'',0,NULL),(14,744,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,24,31,6,'C~O',3,7,'BAS',0,'',0,NULL),(16,745,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,24,37,6,'C~O',3,8,'BAS',0,'',0,NULL),(18,746,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,14,40,6,'C~O',3,5,'BAS',0,'',0,NULL),(19,747,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,9,44,6,'C~O',3,5,'BAS',0,'',0,NULL),(20,748,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,25,47,6,'C~O',3,3,'BAS',0,'',0,NULL),(21,749,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,27,53,6,'C~O',3,3,'BAS',0,'',0,NULL),(22,750,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,25,59,6,'C~O',3,3,'BAS',0,'',0,NULL),(23,751,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,28,65,6,'C~O',3,3,'BAS',0,'',0,NULL),(26,752,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,18,72,6,'C~O',3,9,'BAS',0,'',0,NULL),(2,758,'tags','jo_potential',1,'1','tags','tags',1,2,'',100,22,1,6,'V~O',3,9,'BAS',0,'',0,NULL),(4,759,'tags','jo_contactdetails',1,'1','tags','tags',1,2,'',100,32,4,6,'V~O',3,9,'BAS',0,'',0,NULL),(6,760,'tags','jo_account',1,'1','tags','tags',1,2,'',100,27,9,6,'V~O',3,7,'BAS',0,'',0,NULL),(7,761,'tags','jo_leaddetails',1,'1','tags','tags',1,2,'',100,27,13,6,'V~O',3,9,'BAS',0,'',0,NULL),(8,762,'tags','jo_notes',1,'1','tags','tags',1,2,'',100,15,17,6,'V~O',3,6,'BAS',0,'',0,NULL),(9,763,'tags','jo_activity',1,'1','tags','tags',1,2,'',100,25,19,6,'V~O',3,7,'BAS',0,'',0,NULL),(10,764,'tags','jo_activity',1,'1','tags','tags',1,2,'',100,16,21,6,'V~O',3,4,'BAS',0,'',0,NULL),(13,765,'tags','jo_troubletickets',1,'1','tags','tags',1,2,'',100,20,25,6,'V~O',3,7,'BAS',0,'',0,NULL),(14,766,'tags','jo_products',1,'1','tags','tags',1,2,'',100,25,31,6,'V~O',3,8,'BAS',0,'',0,NULL),(16,767,'tags','jo_activity',1,'1','tags','tags',1,2,'',100,25,37,6,'V~O',3,9,'BAS',0,'',0,NULL),(18,768,'tags','jo_vendor',1,'1','tags','tags',1,2,'',100,15,40,6,'V~O',3,6,'BAS',0,'',0,NULL),(19,769,'tags','jo_pricebook',1,'1','tags','tags',1,2,'',100,10,44,6,'V~O',3,6,'BAS',0,'',0,NULL),(20,770,'tags','jo_quotes',1,'1','tags','tags',1,2,'',100,26,47,6,'V~O',3,4,'BAS',0,'',0,NULL),(21,771,'tags','jo_purchaseorder',1,'1','tags','tags',1,2,'',100,28,53,6,'V~O',3,4,'BAS',0,'',0,NULL),(22,772,'tags','jo_salesorder',1,'1','tags','tags',1,2,'',100,26,59,6,'V~O',3,4,'BAS',0,'',0,NULL),(23,773,'tags','jo_invoice',1,'1','tags','tags',1,2,'',100,29,65,6,'V~O',3,4,'BAS',0,'',0,NULL),(26,774,'tags','jo_campaign',1,'1','tags','tags',1,2,'',100,19,72,6,'V~O',3,10,'BAS',0,'',0,NULL),(20,780,'region_id','jo_quotes',1,'16','region_id','Tax Region',0,2,'',100,18,106,5,'N~O',1,0,'BAS',0,'',0,NULL),(21,781,'region_id','jo_purchaseorder',1,'16','region_id','Tax Region',0,2,'',100,16,105,5,'N~O',1,0,'BAS',0,'',0,NULL),(22,782,'region_id','jo_salesorder',1,'16','region_id','Tax Region',0,2,'',100,19,104,5,'N~O',1,0,'BAS',0,'',0,NULL),(23,783,'region_id','jo_invoice',1,'16','region_id','Tax Region',0,2,'',100,17,103,5,'N~O',1,0,'BAS',0,'',0,NULL),(47,784,'customer','jo_modcomments',1,'10','customer','Customer',1,2,'',100,5,100,3,'V~O',1,0,'BAS',1,'',0,NULL),(47,785,'userid','jo_modcomments',1,'10','userid','UserId',1,2,'',100,6,100,3,'V~O',1,0,'BAS',1,'',0,NULL),(47,786,'reasontoedit','jo_modcomments',1,'19','reasontoedit','ReasonToEdit',1,2,'',100,7,100,1,'V~O',1,0,'BAS',1,'',0,NULL),(47,787,'is_private','jo_modcomments',1,'7','is_private','Is Private',1,2,'',100,8,100,1,'I~O',1,0,'BAS',1,'',0,NULL),(47,788,'filename','jo_modcomments',1,'61','filename','Attachment',1,0,'',100,9,100,1,'V~O',1,0,'BAS',1,'',0,NULL),(47,789,'related_email_id','jo_modcomments',1,'1','related_email_id','Related Email Id',1,2,'0',100,10,100,1,'I~O',1,0,'BAS',1,'',0,NULL),(43,790,'projecttaskstatus','jo_projecttask',1,'15','projecttaskstatus','Status',1,2,'',100,8,94,1,'V~O',0,6,'BAS',1,'',0,NULL),(43,791,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,10,94,6,'C~O',3,8,'BAS',0,'',0,NULL),(43,792,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,9,94,2,'V~O',3,7,'BAS',0,'',0,NULL),(43,793,'tags','jo_projecttask',1,'1','tags','tags',1,2,'',100,11,94,6,'V~O',3,9,'BAS',0,'',0,NULL),(44,794,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,10,97,2,'V~O',3,5,'BAS',0,'',0,NULL),(44,795,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,11,97,6,'C~O',3,6,'BAS',0,'',0,NULL),(44,796,'tags','jo_project',1,'1','tags','tags',1,2,'',100,12,97,6,'V~O',3,7,'BAS',0,'',0,NULL),(44,797,'isconvertedfrompotential','jo_project',1,'56','isconvertedfrompotential','Is Converted From Opportunity',1,2,'',100,13,97,1,'C~O',1,0,'BAS',1,'',0,NULL),(44,798,'potentialid','jo_project',1,'10','potentialid','Potential Name',1,2,'',100,14,97,1,'I~O',1,0,'BAS',1,'',0,NULL),(42,800,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,10,93,2,'V~O',3,5,'BAS',0,'',0,NULL),(42,801,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,11,93,3,'C~O',3,6,'BAS',0,'',0,NULL),(42,802,'tags','jo_projectmilestone',1,'1','tags','tags',1,2,'',100,12,93,6,'V~O',3,7,'BAS',0,'',0,NULL);
/*!40000 ALTER TABLE `jo_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_fieldmodulerel`
--

DROP TABLE IF EXISTS `jo_fieldmodulerel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_fieldmodulerel` (
  `fieldid` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `relmodule` varchar(100) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_fieldmodulerel`
--

LOCK TABLES `jo_fieldmodulerel` WRITE;
/*!40000 ALTER TABLE `jo_fieldmodulerel` DISABLE KEYS */;
INSERT INTO `jo_fieldmodulerel` VALUES (113,'Potentials','Accounts',NULL,0),(546,'PBXManager','Leads',NULL,NULL),(546,'PBXManager','Contacts',NULL,NULL),(546,'PBXManager','Accounts',NULL,NULL),(556,'ProjectMilestone','Project',NULL,NULL),(567,'ProjectTask','Project',NULL,NULL),(585,'Project','Accounts',NULL,NULL),(585,'Project','Contacts',NULL,NULL),(600,'ModComments','Leads',NULL,NULL),(600,'ModComments','Contacts',NULL,NULL),(600,'ModComments','Accounts',NULL,NULL),(602,'ModComments','ModComments',NULL,NULL),(600,'ModComments','Potentials',NULL,NULL),(600,'ModComments','Project',NULL,NULL),(600,'ModComments','ProjectTask',NULL,NULL),(605,'Invoice','Products',NULL,NULL),(605,'Invoice','Services',NULL,NULL),(614,'SalesOrder','Products',NULL,NULL),(614,'SalesOrder','Services',NULL,NULL),(623,'PurchaseOrder','Products',NULL,NULL),(623,'PurchaseOrder','Services',NULL,NULL),(632,'Quotes','Products',NULL,NULL),(632,'Quotes','Services',NULL,NULL),(643,'ModComments','Contacts',NULL,NULL),(600,'ModComments','HelpDesk',NULL,NULL),(668,'Potentials','Contacts',NULL,NULL),(157,'HelpDesk','Accounts',NULL,NULL),(669,'HelpDesk','Contacts',NULL,NULL),(238,'Accounts','Calendar',NULL,NULL),(238,'Leads','Calendar',NULL,NULL),(238,'HelpDesk','Calendar',NULL,NULL),(238,'Campaigns','Calendar',NULL,NULL),(238,'Potentials','Calendar',NULL,NULL),(238,'PurchaseOrder','Calendar',NULL,NULL),(238,'SalesOrder','Calendar',NULL,NULL),(238,'Quotes','Calendar',NULL,NULL),(238,'Invoice','Calendar',NULL,NULL),(239,'Contacts','Calendar',NULL,NULL),(698,'Invoice','Potentials',NULL,NULL),(600,'ModComments','Invoice',NULL,NULL),(600,'ModComments','Quotes',NULL,NULL),(600,'ModComments','PurchaseOrder',NULL,NULL),(600,'ModComments','SalesOrder',NULL,NULL),(798,'Project','Potentials',NULL,NULL),(784,'ModComments','ModComments',NULL,NULL),(785,'ModComments','ModComments',NULL,NULL),(786,'ModComments','ModComments',NULL,NULL),(787,'ModComments','ModComments',NULL,NULL),(788,'ModComments','ModComments',NULL,NULL),(789,'ModComments','ModComments',NULL,NULL);
/*!40000 ALTER TABLE `jo_fieldmodulerel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_freetagged_objects`
--

DROP TABLE IF EXISTS `jo_freetagged_objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_freetagged_objects` (
  `tag_id` int(20) NOT NULL DEFAULT '0',
  `tagger_id` int(20) NOT NULL DEFAULT '0',
  `object_id` int(20) NOT NULL DEFAULT '0',
  `tagged_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `module` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tag_id`,`tagger_id`,`object_id`),
  KEY `freetagged_objects_tag_id_tagger_id_object_id_idx` (`tag_id`,`tagger_id`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_freetagged_objects`
--

LOCK TABLES `jo_freetagged_objects` WRITE;
/*!40000 ALTER TABLE `jo_freetagged_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_freetagged_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_freetags`
--

DROP TABLE IF EXISTS `jo_freetags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_freetags` (
  `id` int(19) NOT NULL,
  `tag` varchar(50) NOT NULL DEFAULT '',
  `raw_tag` varchar(50) NOT NULL DEFAULT '',
  `visibility` varchar(100) NOT NULL DEFAULT 'PRIVATE',
  `owner` int(19) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_freetags`
--

LOCK TABLES `jo_freetags` WRITE;
/*!40000 ALTER TABLE `jo_freetags` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_freetags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_glacct`
--

DROP TABLE IF EXISTS `jo_glacct`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_glacct` (
  `glacctid` int(19) NOT NULL AUTO_INCREMENT,
  `glacct` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`glacctid`),
  UNIQUE KEY `glacct_glacct_idx` (`glacct`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_glacct`
--

LOCK TABLES `jo_glacct` WRITE;
/*!40000 ALTER TABLE `jo_glacct` DISABLE KEYS */;
INSERT INTO `jo_glacct` VALUES (1,'300-Sales-Software',1,46,0,NULL),(2,'301-Sales-Hardware',1,47,1,NULL),(3,'302-Rental-Income',1,48,2,NULL),(4,'303-Interest-Income',1,49,3,NULL),(5,'304-Sales-Software-Support',1,50,4,NULL),(6,'305-Sales Other',1,51,5,NULL),(7,'306-Internet Sales',1,52,6,NULL),(8,'307-Service-Hardware Labor',1,53,7,NULL),(9,'308-Sales-Books',1,54,8,NULL);
/*!40000 ALTER TABLE `jo_glacct` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_google_oauth2`
--

DROP TABLE IF EXISTS `jo_google_oauth2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_google_oauth2` (
  `service` varchar(20) DEFAULT NULL,
  `access_token` varchar(500) DEFAULT NULL,
  `refresh_token` varchar(500) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_google_oauth2`
--

LOCK TABLES `jo_google_oauth2` WRITE;
/*!40000 ALTER TABLE `jo_google_oauth2` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_google_oauth2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_google_sync_fieldmapping`
--

DROP TABLE IF EXISTS `jo_google_sync_fieldmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_google_sync_fieldmapping` (
  `jo_field` varchar(255) DEFAULT NULL,
  `google_field` varchar(255) DEFAULT NULL,
  `google_field_type` varchar(255) DEFAULT NULL,
  `google_custom_label` varchar(255) DEFAULT NULL,
  `user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_google_sync_fieldmapping`
--

LOCK TABLES `jo_google_sync_fieldmapping` WRITE;
/*!40000 ALTER TABLE `jo_google_sync_fieldmapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_google_sync_fieldmapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_google_sync_settings`
--

DROP TABLE IF EXISTS `jo_google_sync_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_google_sync_settings` (
  `user` int(11) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `clientgroup` varchar(255) DEFAULT NULL,
  `direction` varchar(50) DEFAULT NULL,
  `enabled` tinyint(3) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_google_sync_settings`
--

LOCK TABLES `jo_google_sync_settings` WRITE;
/*!40000 ALTER TABLE `jo_google_sync_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_google_sync_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_group2grouprel`
--

DROP TABLE IF EXISTS `jo_group2grouprel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_group2grouprel` (
  `groupid` int(19) NOT NULL,
  `containsgroupid` int(19) NOT NULL,
  PRIMARY KEY (`groupid`,`containsgroupid`),
  CONSTRAINT `fk_2_jo_group2grouprel` FOREIGN KEY (`groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_group2grouprel`
--

LOCK TABLES `jo_group2grouprel` WRITE;
/*!40000 ALTER TABLE `jo_group2grouprel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_group2grouprel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_group2role`
--

DROP TABLE IF EXISTS `jo_group2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_group2role` (
  `groupid` int(19) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`,`roleid`),
  KEY `fk_2_jo_group2role` (`roleid`),
  CONSTRAINT `fk_2_jo_group2role` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_group2role`
--

LOCK TABLES `jo_group2role` WRITE;
/*!40000 ALTER TABLE `jo_group2role` DISABLE KEYS */;
INSERT INTO `jo_group2role` VALUES (3,'H2'),(4,'H3'),(2,'H4');
/*!40000 ALTER TABLE `jo_group2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_group2rs`
--

DROP TABLE IF EXISTS `jo_group2rs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_group2rs` (
  `groupid` int(19) NOT NULL,
  `roleandsubid` varchar(255) NOT NULL,
  PRIMARY KEY (`groupid`,`roleandsubid`),
  KEY `fk_2_jo_group2rs` (`roleandsubid`),
  CONSTRAINT `fk_2_jo_group2rs` FOREIGN KEY (`roleandsubid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_group2rs`
--

LOCK TABLES `jo_group2rs` WRITE;
/*!40000 ALTER TABLE `jo_group2rs` DISABLE KEYS */;
INSERT INTO `jo_group2rs` VALUES (3,'H3'),(4,'H3'),(2,'H5');
/*!40000 ALTER TABLE `jo_group2rs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_groups`
--

DROP TABLE IF EXISTS `jo_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_groups` (
  `groupid` int(19) NOT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`groupid`),
  UNIQUE KEY `groups_groupname_idx` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_groups`
--

LOCK TABLES `jo_groups` WRITE;
/*!40000 ALTER TABLE `jo_groups` DISABLE KEYS */;
INSERT INTO `jo_groups` VALUES (2,'Team Selling','Group Related to Sales'),(3,'Marketing Group','Group Related to Marketing Activities'),(4,'Support Group','Group Related to providing Support to Customers');
/*!40000 ALTER TABLE `jo_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_home_layout`
--

DROP TABLE IF EXISTS `jo_home_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_home_layout` (
  `userid` int(19) NOT NULL,
  `layout` int(19) NOT NULL DEFAULT '4',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_home_layout`
--

LOCK TABLES `jo_home_layout` WRITE;
/*!40000 ALTER TABLE `jo_home_layout` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_home_layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homedashbd`
--

DROP TABLE IF EXISTS `jo_homedashbd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homedashbd` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `dashbdname` varchar(100) DEFAULT NULL,
  `dashbdtype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`),
  CONSTRAINT `fk_1_jo_homedashbd` FOREIGN KEY (`stuffid`) REFERENCES `jo_homestuff` (`stuffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homedashbd`
--

LOCK TABLES `jo_homedashbd` WRITE;
/*!40000 ALTER TABLE `jo_homedashbd` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_homedashbd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homedefault`
--

DROP TABLE IF EXISTS `jo_homedefault`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homedefault` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `hometype` varchar(30) NOT NULL,
  `maxentries` int(19) DEFAULT NULL,
  `setype` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`),
  CONSTRAINT `fk_1_jo_homedefault` FOREIGN KEY (`stuffid`) REFERENCES `jo_homestuff` (`stuffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homedefault`
--

LOCK TABLES `jo_homedefault` WRITE;
/*!40000 ALTER TABLE `jo_homedefault` DISABLE KEYS */;
INSERT INTO `jo_homedefault` VALUES (1,'ALVT',5,'Accounts'),(2,'HDB',5,'Dashboard'),(3,'PLVT',5,'Potentials'),(4,'QLTQ',5,'Quotes'),(5,'CVLVT',5,'NULL'),(6,'HLT',5,'HelpDesk'),(7,'UA',5,'Calendar'),(8,'GRT',5,'NULL'),(9,'OLTSO',5,'SalesOrder'),(10,'ILTI',5,'Invoice'),(11,'MNL',5,'Leads'),(12,'OLTPO',5,'PurchaseOrder'),(13,'PA',5,'Calendar'),(14,'LTFAQ',5,'Faq');
/*!40000 ALTER TABLE `jo_homedefault` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homemodule`
--

DROP TABLE IF EXISTS `jo_homemodule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homemodule` (
  `stuffid` int(19) NOT NULL,
  `modulename` varchar(100) DEFAULT NULL,
  `maxentries` int(19) NOT NULL,
  `customviewid` int(19) NOT NULL,
  `setype` varchar(30) NOT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`),
  CONSTRAINT `fk_1_jo_homemodule` FOREIGN KEY (`stuffid`) REFERENCES `jo_homestuff` (`stuffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homemodule`
--

LOCK TABLES `jo_homemodule` WRITE;
/*!40000 ALTER TABLE `jo_homemodule` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_homemodule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homemoduleflds`
--

DROP TABLE IF EXISTS `jo_homemoduleflds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homemoduleflds` (
  `stuffid` int(19) DEFAULT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  KEY `stuff_stuffid_idx` (`stuffid`),
  CONSTRAINT `fk_1_jo_homemoduleflds` FOREIGN KEY (`stuffid`) REFERENCES `jo_homemodule` (`stuffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homemoduleflds`
--

LOCK TABLES `jo_homemoduleflds` WRITE;
/*!40000 ALTER TABLE `jo_homemoduleflds` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_homemoduleflds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homereportchart`
--

DROP TABLE IF EXISTS `jo_homereportchart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homereportchart` (
  `stuffid` int(11) NOT NULL,
  `reportid` int(19) DEFAULT NULL,
  `reportcharttype` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homereportchart`
--

LOCK TABLES `jo_homereportchart` WRITE;
/*!40000 ALTER TABLE `jo_homereportchart` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_homereportchart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_homestuff`
--

DROP TABLE IF EXISTS `jo_homestuff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_homestuff` (
  `stuffid` int(19) NOT NULL DEFAULT '0',
  `stuffsequence` int(19) NOT NULL DEFAULT '0',
  `stufftype` varchar(100) DEFAULT NULL,
  `userid` int(19) NOT NULL,
  `visible` int(10) NOT NULL DEFAULT '0',
  `stufftitle` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`stuffid`),
  KEY `stuff_stuffid_idx` (`stuffid`),
  KEY `fk_1_jo_homestuff` (`userid`),
  CONSTRAINT `fk_1_jo_homestuff` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_homestuff`
--

LOCK TABLES `jo_homestuff` WRITE;
/*!40000 ALTER TABLE `jo_homestuff` DISABLE KEYS */;
INSERT INTO `jo_homestuff` VALUES (1,1,'Default',1,1,'Top Accounts'),(2,2,'Default',1,1,'Home Page Dashboard'),(3,3,'Default',1,1,'Top Potentials'),(4,4,'Default',1,1,'Top Quotes'),(5,5,'Default',1,1,'Key Metrics'),(6,6,'Default',1,1,'Top Trouble Tickets'),(7,7,'Default',1,1,'Upcoming Activities'),(8,8,'Default',1,1,'My Group Allocation'),(9,9,'Default',1,1,'Top Sales Orders'),(10,10,'Default',1,1,'Top Invoices'),(11,11,'Default',1,1,'My New Leads'),(12,12,'Default',1,1,'Top Purchase Orders'),(13,13,'Default',1,1,'Pending Activities'),(14,14,'Default',1,1,'My Recent FAQs'),(15,15,'Tag Cloud',1,0,'Tag Cloud');
/*!40000 ALTER TABLE `jo_homestuff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_hour_format`
--

DROP TABLE IF EXISTS `jo_hour_format`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_hour_format` (
  `hour_formatid` int(11) NOT NULL AUTO_INCREMENT,
  `hour_format` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`hour_formatid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_hour_format`
--

LOCK TABLES `jo_hour_format` WRITE;
/*!40000 ALTER TABLE `jo_hour_format` DISABLE KEYS */;
INSERT INTO `jo_hour_format` VALUES (1,'12',0,1),(2,'24',1,1);
/*!40000 ALTER TABLE `jo_hour_format` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_import_locks`
--

DROP TABLE IF EXISTS `jo_import_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_import_locks` (
  `jo_import_lock_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `importid` int(11) NOT NULL,
  `locked_since` datetime DEFAULT NULL,
  PRIMARY KEY (`jo_import_lock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_import_locks`
--

LOCK TABLES `jo_import_locks` WRITE;
/*!40000 ALTER TABLE `jo_import_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_import_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_import_maps`
--

DROP TABLE IF EXISTS `jo_import_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_import_maps` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(36) NOT NULL,
  `module` varchar(36) NOT NULL,
  `content` longblob,
  `has_header` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `is_published` varchar(3) NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  KEY `import_maps_assigned_user_id_module_name_deleted_idx` (`assigned_user_id`,`module`,`name`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_import_maps`
--

LOCK TABLES `jo_import_maps` WRITE;
/*!40000 ALTER TABLE `jo_import_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_import_maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_import_queue`
--

DROP TABLE IF EXISTS `jo_import_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_import_queue` (
  `importid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `field_mapping` text,
  `default_values` text,
  `merge_type` int(11) DEFAULT NULL,
  `merge_fields` text,
  `status` int(11) DEFAULT '0',
  `lineitem_currency_id` int(5) DEFAULT NULL,
  `paging` int(1) DEFAULT '0',
  PRIMARY KEY (`importid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_import_queue`
--

LOCK TABLES `jo_import_queue` WRITE;
/*!40000 ALTER TABLE `jo_import_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_import_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_industry`
--

DROP TABLE IF EXISTS `jo_industry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_industry` (
  `industryid` int(19) NOT NULL AUTO_INCREMENT,
  `industry` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`industryid`),
  UNIQUE KEY `industry_industry_idx` (`industry`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_industry`
--

LOCK TABLES `jo_industry` WRITE;
/*!40000 ALTER TABLE `jo_industry` DISABLE KEYS */;
INSERT INTO `jo_industry` VALUES (2,'Apparel',1,56,1,NULL),(3,'Banking',1,57,2,NULL),(4,'Biotechnology',1,58,3,NULL),(5,'Chemicals',1,59,4,NULL),(6,'Communications',1,60,5,NULL),(7,'Construction',1,61,6,NULL),(8,'Consulting',1,62,7,NULL),(9,'Education',1,63,8,NULL),(10,'Electronics',1,64,9,NULL),(11,'Energy',1,65,10,NULL),(12,'Engineering',1,66,11,NULL),(13,'Entertainment',1,67,12,NULL),(14,'Environmental',1,68,13,NULL),(15,'Finance',1,69,14,NULL),(16,'Food & Beverage',1,70,15,NULL),(17,'Government',1,71,16,NULL),(18,'Healthcare',1,72,17,NULL),(19,'Hospitality',1,73,18,NULL),(20,'Insurance',1,74,19,NULL),(21,'Machinery',1,75,20,NULL),(22,'Manufacturing',1,76,21,NULL),(23,'Media',1,77,22,NULL),(24,'Not For Profit',1,78,23,NULL),(25,'Recreation',1,79,24,NULL),(26,'Retail',1,80,25,NULL),(27,'Shipping',1,81,26,NULL),(28,'Technology',1,82,27,NULL),(29,'Telecommunications',1,83,28,NULL),(30,'Transportation',1,84,29,NULL),(31,'Utilities',1,85,30,NULL),(32,'Other',1,86,31,NULL);
/*!40000 ALTER TABLE `jo_industry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventory_tandc`
--

DROP TABLE IF EXISTS `jo_inventory_tandc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventory_tandc` (
  `id` int(19) NOT NULL,
  `type` varchar(30) NOT NULL,
  `tandc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventory_tandc`
--

LOCK TABLES `jo_inventory_tandc` WRITE;
/*!40000 ALTER TABLE `jo_inventory_tandc` DISABLE KEYS */;
INSERT INTO `jo_inventory_tandc` VALUES (2,'Invoice','\n - Unless otherwise agreed in writing by the supplier all invoices are payable within thirty (30) days of the date of invoice, in the currency of the invoice, drawn on a bank based in India or by such other method as is agreed in advance by the Supplier.\n\n - All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.'),(3,'Quotes','\n - Unless otherwise agreed in writing by the supplier all invoices are payable within thirty (30) days of the date of invoice, in the currency of the invoice, drawn on a bank based in India or by such other method as is agreed in advance by the Supplier.\n\n - All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.'),(4,'PurchaseOrder','\n - Unless otherwise agreed in writing by the supplier all invoices are payable within thirty (30) days of the date of invoice, in the currency of the invoice, drawn on a bank based in India or by such other method as is agreed in advance by the Supplier.\n\n - All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.'),(5,'SalesOrder','\n - Unless otherwise agreed in writing by the supplier all invoices are payable within thirty (30) days of the date of invoice, in the currency of the invoice, drawn on a bank based in India or by such other method as is agreed in advance by the Supplier.\n\n - All prices are not inclusive of VAT which shall be payable in addition by the Customer at the applicable rate.');
/*!40000 ALTER TABLE `jo_inventory_tandc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventorycharges`
--

DROP TABLE IF EXISTS `jo_inventorycharges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventorycharges` (
  `chargeid` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `format` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` decimal(12,5) DEFAULT NULL,
  `regions` text,
  `istaxable` int(1) NOT NULL DEFAULT '1',
  `taxes` varchar(1024) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`chargeid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventorycharges`
--

LOCK TABLES `jo_inventorycharges` WRITE;
/*!40000 ALTER TABLE `jo_inventorycharges` DISABLE KEYS */;
INSERT INTO `jo_inventorycharges` VALUES (1,'Shipping & Handling','Flat','Fixed',0.00000,'[]',1,'[\"1\",\"2\",\"3\"]',0);
/*!40000 ALTER TABLE `jo_inventorycharges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventorychargesrel`
--

DROP TABLE IF EXISTS `jo_inventorychargesrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventorychargesrel` (
  `recordid` int(19) NOT NULL,
  `charges` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventorychargesrel`
--

LOCK TABLES `jo_inventorychargesrel` WRITE;
/*!40000 ALTER TABLE `jo_inventorychargesrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_inventorychargesrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventorynotification`
--

DROP TABLE IF EXISTS `jo_inventorynotification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventorynotification` (
  `notificationid` int(19) NOT NULL AUTO_INCREMENT,
  `notificationname` varchar(200) DEFAULT NULL,
  `notificationsubject` varchar(200) DEFAULT NULL,
  `notificationbody` text,
  `label` varchar(50) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`notificationid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventorynotification`
--

LOCK TABLES `jo_inventorynotification` WRITE;
/*!40000 ALTER TABLE `jo_inventorynotification` DISABLE KEYS */;
INSERT INTO `jo_inventorynotification` VALUES (1,'InvoiceNotification','{PRODUCTNAME} Stock Level is Low','Dear {HANDLER},\n\nThe current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. Kindly procure required number of units as the stock level is below reorder level {REORDERLEVELVALUE}.\n\nPlease treat this information as Urgent as the invoice is already sent  to the customer.\n\nSeverity: Critical\n\nThanks,\n{CURRENTUSER} ','InvoiceNotificationDescription',NULL),(2,'QuoteNotification','Quote given for {PRODUCTNAME}','Dear {HANDLER},\n\nQuote is generated for {QUOTEQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}.\n\nSeverity: Minor\n\nThanks,\n{CURRENTUSER} ','QuoteNotificationDescription',NULL),(3,'SalesOrderNotification','Sales Order generated for {PRODUCTNAME}','Dear {HANDLER},\n\nSalesOrder is generated for {SOQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}.\n\nPlease treat this information  with priority as the sales order is already generated.\n\nSeverity: Major\n\nThanks,\n{CURRENTUSER} ','SalesOrderNotificationDescription',NULL);
/*!40000 ALTER TABLE `jo_inventorynotification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventoryproductrel`
--

DROP TABLE IF EXISTS `jo_inventoryproductrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventoryproductrel` (
  `id` int(19) DEFAULT NULL,
  `productid` int(19) DEFAULT NULL,
  `sequence_no` int(4) DEFAULT NULL,
  `quantity` decimal(25,3) DEFAULT NULL,
  `listprice` decimal(27,8) DEFAULT NULL,
  `discount_percent` decimal(7,3) DEFAULT NULL,
  `discount_amount` decimal(27,8) DEFAULT NULL,
  `comment` text,
  `description` text,
  `incrementondel` int(11) NOT NULL DEFAULT '0',
  `lineitem_id` int(11) NOT NULL AUTO_INCREMENT,
  `tax1` decimal(7,3) DEFAULT NULL,
  `tax2` decimal(7,3) DEFAULT NULL,
  `tax3` decimal(7,3) DEFAULT NULL,
  `image` varchar(2) DEFAULT NULL,
  `purchase_cost` decimal(27,8) DEFAULT NULL,
  `margin` decimal(27,8) DEFAULT NULL,
  PRIMARY KEY (`lineitem_id`),
  KEY `inventoryproductrel_id_idx` (`id`),
  KEY `inventoryproductrel_productid_idx` (`productid`),
  CONSTRAINT `fk_crmid_jo_inventoryproductrel` FOREIGN KEY (`id`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventoryproductrel`
--

LOCK TABLES `jo_inventoryproductrel` WRITE;
/*!40000 ALTER TABLE `jo_inventoryproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_inventoryproductrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventoryshippingrel`
--

DROP TABLE IF EXISTS `jo_inventoryshippingrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventoryshippingrel` (
  `id` int(19) DEFAULT NULL,
  `shtax1` decimal(7,3) DEFAULT NULL,
  `shtax2` decimal(7,3) DEFAULT NULL,
  `shtax3` decimal(7,3) DEFAULT NULL,
  KEY `inventoryishippingrel_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventoryshippingrel`
--

LOCK TABLES `jo_inventoryshippingrel` WRITE;
/*!40000 ALTER TABLE `jo_inventoryshippingrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_inventoryshippingrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventorysubproductrel`
--

DROP TABLE IF EXISTS `jo_inventorysubproductrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventorysubproductrel` (
  `id` int(19) NOT NULL,
  `sequence_no` int(10) NOT NULL,
  `productid` int(19) NOT NULL,
  `quantity` int(19) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventorysubproductrel`
--

LOCK TABLES `jo_inventorysubproductrel` WRITE;
/*!40000 ALTER TABLE `jo_inventorysubproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_inventorysubproductrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_inventorytaxinfo`
--

DROP TABLE IF EXISTS `jo_inventorytaxinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_inventorytaxinfo` (
  `taxid` int(3) NOT NULL,
  `taxname` varchar(50) DEFAULT NULL,
  `taxlabel` varchar(50) DEFAULT NULL,
  `percentage` decimal(7,3) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `compoundon` varchar(400) DEFAULT NULL,
  `regions` text,
  PRIMARY KEY (`taxid`),
  KEY `inventorytaxinfo_taxname_idx` (`taxname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_inventorytaxinfo`
--

LOCK TABLES `jo_inventorytaxinfo` WRITE;
/*!40000 ALTER TABLE `jo_inventorytaxinfo` DISABLE KEYS */;
INSERT INTO `jo_inventorytaxinfo` VALUES (1,'tax1','VAT',4.500,0,'Simple','Fixed','[]','[]'),(2,'tax2','Sales',10.000,0,'Simple','Fixed','[]','[]'),(3,'tax3','Service',12.500,0,'Simple','Fixed','[]','[]');
/*!40000 ALTER TABLE `jo_inventorytaxinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invitees`
--

DROP TABLE IF EXISTS `jo_invitees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invitees` (
  `activityid` int(19) NOT NULL,
  `inviteeid` int(19) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`activityid`,`inviteeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invitees`
--

LOCK TABLES `jo_invitees` WRITE;
/*!40000 ALTER TABLE `jo_invitees` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invitees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoice`
--

DROP TABLE IF EXISTS `jo_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoice` (
  `invoiceid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `salesorderid` int(19) DEFAULT NULL,
  `customerno` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `invoicedate` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `invoiceterms` varchar(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `shipping` varchar(100) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) DEFAULT NULL,
  `invoicestatus` varchar(200) DEFAULT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `compound_taxes_info` text,
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `received` decimal(25,8) DEFAULT NULL,
  `balance` decimal(25,8) DEFAULT NULL,
  `s_h_percent` decimal(25,8) DEFAULT NULL,
  `potential_id` varchar(100) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `region_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`invoiceid`),
  KEY `invoice_purchaseorderid_idx` (`invoiceid`),
  KEY `fk_2_jo_invoice` (`salesorderid`),
  CONSTRAINT `fk_2_jo_invoice` FOREIGN KEY (`salesorderid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE,
  CONSTRAINT `fk_crmid_jo_invoice` FOREIGN KEY (`invoiceid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoice`
--

LOCK TABLES `jo_invoice` WRITE;
/*!40000 ALTER TABLE `jo_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoice_recurring_info`
--

DROP TABLE IF EXISTS `jo_invoice_recurring_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoice_recurring_info` (
  `salesorderid` int(11) NOT NULL,
  `recurring_frequency` varchar(200) DEFAULT NULL,
  `start_period` date DEFAULT NULL,
  `end_period` date DEFAULT NULL,
  `last_recurring_date` date DEFAULT NULL,
  `payment_duration` varchar(200) DEFAULT NULL,
  `invoice_status` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`salesorderid`),
  CONSTRAINT `fk_salesorderid_jo_invoice_recurring_info` FOREIGN KEY (`salesorderid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoice_recurring_info`
--

LOCK TABLES `jo_invoice_recurring_info` WRITE;
/*!40000 ALTER TABLE `jo_invoice_recurring_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoice_recurring_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoicebillads`
--

DROP TABLE IF EXISTS `jo_invoicebillads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoicebillads` (
  `invoicebilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`invoicebilladdressid`),
  CONSTRAINT `fk_1_jo_invoicebillads` FOREIGN KEY (`invoicebilladdressid`) REFERENCES `jo_invoice` (`invoiceid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoicebillads`
--

LOCK TABLES `jo_invoicebillads` WRITE;
/*!40000 ALTER TABLE `jo_invoicebillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoicebillads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoicecf`
--

DROP TABLE IF EXISTS `jo_invoicecf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoicecf` (
  `invoiceid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`invoiceid`),
  CONSTRAINT `fk_1_jo_invoicecf` FOREIGN KEY (`invoiceid`) REFERENCES `jo_invoice` (`invoiceid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoicecf`
--

LOCK TABLES `jo_invoicecf` WRITE;
/*!40000 ALTER TABLE `jo_invoicecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoicecf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoiceshipads`
--

DROP TABLE IF EXISTS `jo_invoiceshipads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoiceshipads` (
  `invoiceshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`invoiceshipaddressid`),
  CONSTRAINT `fk_1_jo_invoiceshipads` FOREIGN KEY (`invoiceshipaddressid`) REFERENCES `jo_invoice` (`invoiceid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoiceshipads`
--

LOCK TABLES `jo_invoiceshipads` WRITE;
/*!40000 ALTER TABLE `jo_invoiceshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoiceshipads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoicestatus`
--

DROP TABLE IF EXISTS `jo_invoicestatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoicestatus` (
  `invoicestatusid` int(19) NOT NULL AUTO_INCREMENT,
  `invoicestatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`invoicestatusid`),
  UNIQUE KEY `invoicestatus_invoiestatus_idx` (`invoicestatus`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoicestatus`
--

LOCK TABLES `jo_invoicestatus` WRITE;
/*!40000 ALTER TABLE `jo_invoicestatus` DISABLE KEYS */;
INSERT INTO `jo_invoicestatus` VALUES (1,'AutoCreated',0,87,0,NULL),(2,'Created',0,88,1,NULL),(3,'Approved',0,89,2,NULL),(4,'Sent',0,90,3,NULL),(5,'Credit Invoice',0,91,4,NULL),(6,'Paid',0,92,5,NULL),(7,'Cancel',1,316,6,NULL);
/*!40000 ALTER TABLE `jo_invoicestatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_invoicestatushistory`
--

DROP TABLE IF EXISTS `jo_invoicestatushistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_invoicestatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `invoicestatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `invoicestatushistory_invoiceid_idx` (`invoiceid`),
  CONSTRAINT `fk_1_jo_invoicestatushistory` FOREIGN KEY (`invoiceid`) REFERENCES `jo_invoice` (`invoiceid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_invoicestatushistory`
--

LOCK TABLES `jo_invoicestatushistory` WRITE;
/*!40000 ALTER TABLE `jo_invoicestatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_invoicestatushistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_language`
--

DROP TABLE IF EXISTS `jo_language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `prefix` varchar(10) DEFAULT NULL,
  `label` varchar(30) DEFAULT NULL,
  `lastupdated` datetime DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `isdefault` int(1) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_language`
--

LOCK TABLES `jo_language` WRITE;
/*!40000 ALTER TABLE `jo_language` DISABLE KEYS */;
INSERT INTO `jo_language` VALUES (1,'English','en_us','US English','2018-05-29 18:53:54',NULL,1,1),(2,'Deutsch','de_de','DE Deutsch','2020-09-23 09:28:29',NULL,0,1),(3,'Italian','it_it','IT Italian','2020-09-23 09:28:29',NULL,0,1),(4,'Dutch','nl_nl','NL-Dutch','2020-09-23 09:28:28',NULL,0,1),(5,'Romana','ro_ro','Romana','2020-09-23 09:28:29',NULL,0,1),(6,'Arabic','ar_ae','Arabic','2020-09-23 09:28:01',NULL,0,1),(7,'Swedish','sv_se','Swedish','2020-09-23 09:28:28',NULL,0,1),(8,'Turkce','tr_tr','Turkce Dil Paketi','2020-09-23 09:28:29',NULL,0,1),(9,'Russian','ru_ru','Russian','2020-09-23 09:28:29',NULL,0,1),(10,'Brazilian','pt_br','PT Brasil','2020-09-23 09:28:28',NULL,0,1),(11,'Język Polski','pl_pl','Język Polski','2020-09-23 09:28:29',NULL,0,1),(12,'Hungarian','hu_hu','HU Magyar','2020-09-23 09:28:29',NULL,0,1),(13,'Mexican Spanish','es_mx','ES Mexico','2020-09-23 09:28:29',NULL,0,1),(14,'Spanish','es_es','ES Spanish','2020-09-23 09:28:29',NULL,0,1),(15,'British English','en_gb','British English','2020-09-23 09:28:29',NULL,0,1),(16,'Pack de langue français','fr_fr','Pack de langue français','2020-09-23 09:28:29',NULL,0,1);
/*!40000 ALTER TABLE `jo_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_lead_view`
--

DROP TABLE IF EXISTS `jo_lead_view`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_lead_view` (
  `lead_viewid` int(19) NOT NULL AUTO_INCREMENT,
  `lead_view` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`lead_viewid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_lead_view`
--

LOCK TABLES `jo_lead_view` WRITE;
/*!40000 ALTER TABLE `jo_lead_view` DISABLE KEYS */;
INSERT INTO `jo_lead_view` VALUES (1,'Today',0,1),(2,'Last 2 Days',1,1),(3,'Last Week',2,1);
/*!40000 ALTER TABLE `jo_lead_view` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leadaddress`
--

DROP TABLE IF EXISTS `jo_leadaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadaddress` (
  `leadaddressid` int(19) NOT NULL DEFAULT '0',
  `city` varchar(30) DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `pobox` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `lane` varchar(250) DEFAULT NULL,
  `leadaddresstype` varchar(30) DEFAULT 'Billing',
  PRIMARY KEY (`leadaddressid`),
  CONSTRAINT `fk_1_jo_leadaddress` FOREIGN KEY (`leadaddressid`) REFERENCES `jo_leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadaddress`
--

LOCK TABLES `jo_leadaddress` WRITE;
/*!40000 ALTER TABLE `jo_leadaddress` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_leadaddress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leaddetails`
--

DROP TABLE IF EXISTS `jo_leaddetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leaddetails` (
  `leadid` int(19) NOT NULL,
  `lead_no` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `interest` varchar(50) DEFAULT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `salutation` varchar(200) DEFAULT NULL,
  `lastname` varchar(80) NOT NULL,
  `company` varchar(100) NOT NULL,
  `annualrevenue` decimal(25,8) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `campaign` varchar(30) DEFAULT NULL,
  `rating` varchar(200) DEFAULT NULL,
  `leadstatus` varchar(200) DEFAULT NULL,
  `leadsource` varchar(200) DEFAULT NULL,
  `converted` int(1) DEFAULT '0',
  `designation` varchar(50) DEFAULT 'SalesMan',
  `licencekeystatus` varchar(50) DEFAULT NULL,
  `space` varchar(250) DEFAULT NULL,
  `comments` text,
  `priority` varchar(50) DEFAULT NULL,
  `demorequest` varchar(50) DEFAULT NULL,
  `partnercontact` varchar(50) DEFAULT NULL,
  `productversion` varchar(20) DEFAULT NULL,
  `product` varchar(50) DEFAULT NULL,
  `maildate` date DEFAULT NULL,
  `nextstepdate` date DEFAULT NULL,
  `fundingsituation` varchar(50) DEFAULT NULL,
  `purpose` varchar(50) DEFAULT NULL,
  `evaluationstatus` varchar(50) DEFAULT NULL,
  `transferdate` date DEFAULT NULL,
  `revenuetype` varchar(50) DEFAULT NULL,
  `noofemployees` int(50) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `assignleadchk` int(1) DEFAULT '0',
  `emailoptout` varchar(3) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`leadid`),
  KEY `leaddetails_converted_leadstatus_idx` (`converted`,`leadstatus`),
  KEY `email_idx` (`email`),
  CONSTRAINT `fk_1_jo_leaddetails` FOREIGN KEY (`leadid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leaddetails`
--

LOCK TABLES `jo_leaddetails` WRITE;
/*!40000 ALTER TABLE `jo_leaddetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_leaddetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leadscf`
--

DROP TABLE IF EXISTS `jo_leadscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadscf` (
  `leadid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`leadid`),
  CONSTRAINT `fk_1_jo_leadscf` FOREIGN KEY (`leadid`) REFERENCES `jo_leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadscf`
--

LOCK TABLES `jo_leadscf` WRITE;
/*!40000 ALTER TABLE `jo_leadscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_leadscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leadsource`
--

DROP TABLE IF EXISTS `jo_leadsource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadsource` (
  `leadsourceid` int(19) NOT NULL AUTO_INCREMENT,
  `leadsource` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`leadsourceid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadsource`
--

LOCK TABLES `jo_leadsource` WRITE;
/*!40000 ALTER TABLE `jo_leadsource` DISABLE KEYS */;
INSERT INTO `jo_leadsource` VALUES (2,'Cold Call',1,94,1,NULL),(3,'Existing Customer',1,95,2,NULL),(4,'Self Generated',1,96,3,NULL),(5,'Employee',1,97,4,NULL),(6,'Partner',1,98,5,NULL),(7,'Public Relations',1,99,6,NULL),(8,'Direct Mail',1,100,7,NULL),(9,'Conference',1,101,8,NULL),(10,'Trade Show',1,102,9,NULL),(11,'Web Site',1,103,10,NULL),(12,'Word of mouth',1,104,11,NULL),(13,'Other',1,105,12,NULL);
/*!40000 ALTER TABLE `jo_leadsource` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_leadstage`
--

DROP TABLE IF EXISTS `jo_leadstage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadstage` (
  `leadstageid` int(19) NOT NULL AUTO_INCREMENT,
  `stage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`leadstageid`),
  UNIQUE KEY `leadstage_stage_idx` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadstage`
--

LOCK TABLES `jo_leadstage` WRITE;
/*!40000 ALTER TABLE `jo_leadstage` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_leadstage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leadstatus`
--

DROP TABLE IF EXISTS `jo_leadstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadstatus` (
  `leadstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `leadstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`leadstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadstatus`
--

LOCK TABLES `jo_leadstatus` WRITE;
/*!40000 ALTER TABLE `jo_leadstatus` DISABLE KEYS */;
INSERT INTO `jo_leadstatus` VALUES (2,'Attempted to Contact',1,107,1,NULL),(3,'Cold',1,108,2,NULL),(4,'Contact in Future',1,109,3,NULL),(5,'Contacted',1,110,4,NULL),(6,'Hot',1,111,5,NULL),(7,'Junk Lead',1,112,6,NULL),(8,'Lost Lead',1,113,7,NULL),(9,'Not Contacted',1,114,8,NULL),(10,'Pre Qualified',1,115,9,NULL),(11,'Qualified',1,116,10,NULL),(12,'Warm',1,117,11,NULL);
/*!40000 ALTER TABLE `jo_leadstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_leadsubdetails`
--

DROP TABLE IF EXISTS `jo_leadsubdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_leadsubdetails` (
  `leadsubscriptionid` int(19) NOT NULL DEFAULT '0',
  `website` varchar(255) DEFAULT NULL,
  `callornot` int(1) DEFAULT '0',
  `readornot` int(1) DEFAULT '0',
  `empct` int(10) DEFAULT '0',
  PRIMARY KEY (`leadsubscriptionid`),
  CONSTRAINT `fk_1_jo_leadsubdetails` FOREIGN KEY (`leadsubscriptionid`) REFERENCES `jo_leaddetails` (`leadid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_leadsubdetails`
--

LOCK TABLES `jo_leadsubdetails` WRITE;
/*!40000 ALTER TABLE `jo_leadsubdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_leadsubdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_links`
--

DROP TABLE IF EXISTS `jo_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_links` (
  `linkid` int(11) NOT NULL,
  `tabid` int(11) DEFAULT NULL,
  `linktype` varchar(50) DEFAULT NULL,
  `linklabel` varchar(50) DEFAULT NULL,
  `linkurl` varchar(255) DEFAULT NULL,
  `linkicon` varchar(100) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `handler_path` varchar(128) DEFAULT NULL,
  `handler_class` varchar(50) DEFAULT NULL,
  `handler` varchar(50) DEFAULT NULL,
  `parent_link` int(19) DEFAULT NULL,
  PRIMARY KEY (`linkid`),
  KEY `link_tabidtype_idx` (`tabid`,`linktype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_links`
--

LOCK TABLES `jo_links` WRITE;
/*!40000 ALTER TABLE `jo_links` DISABLE KEYS */;
INSERT INTO `jo_links` VALUES (2,6,'DETAILVIEW','LBL_SHOW_ACCOUNT_HIERARCHY','index.php?module=Accounts&action=AccountHierarchy&accountid=$RECORD$','',0,NULL,NULL,NULL,NULL),(5,32,'DETAILVIEWSIDEBARWIDGET','PDF Maker','module=PDFMaker&view=ExportPDF&record=$RECORD$','NULL',0,NULL,NULL,NULL,NULL),(6,32,'HEADERSCRIPT','HEADERSCRIPT','layouts/modules/PDFMaker/resources/Helper.js','',0,NULL,NULL,NULL,NULL),(7,30,'HEADERSCRIPT','Duplicate Check','layouts/modules/Settings/DuplicateCheck/jsresources/duplicatecheck.js','NULL',0,NULL,NULL,NULL,NULL),(8,30,'HEADERSCRIPT','Duplicate Check Quick Create','layouts/modules/Settings/DuplicateCheck/jsresources/quickcreateduplicatecheck.js','NULL',0,NULL,NULL,NULL,NULL),(9,31,'HEADERSCRIPT','Address Autofill','layouts/modules/Settings/AddressLookup/jsresources/AddressLookup.js','NULL',0,NULL,NULL,NULL,NULL),(10,33,'HEADERSCRIPT','Check Server Details','layouts/modules/EmailPlus/resources/checkServerInfo.js','',0,NULL,NULL,NULL,NULL),(11,0,'HEADERSCRIPT','Incoming Calls','modules/PBXManager/resources/PBXManagerJS.js','',0,'modules/PBXManager/PBXManager.php','PBXManager','checkLinkPermission',NULL),(17,7,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(18,4,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(19,6,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(20,2,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(23,47,'HEADERSCRIPT','ModCommentsCommonHeaderScript','modules/ModComments/ModCommentsCommon.js','',0,NULL,NULL,NULL,NULL),(29,2,'DASHBOARDWIDGET','History','index.php?module=Potentials&view=ShowWidget&name=History','',1,NULL,NULL,NULL,NULL),(30,2,'DASHBOARDWIDGET','Upcoming Activities','index.php?module=Potentials&view=ShowWidget&name=CalendarActivities','',2,NULL,NULL,NULL,NULL),(31,2,'DASHBOARDWIDGET','Funnel','index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage','',3,NULL,NULL,NULL,NULL),(32,2,'DASHBOARDWIDGET','Potentials by Stage','index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson','',4,NULL,NULL,NULL,NULL),(33,2,'DASHBOARDWIDGET','Pipelined Amount','index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson','',5,NULL,NULL,NULL,NULL),(34,2,'DASHBOARDWIDGET','Total Revenue','index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson','',6,NULL,NULL,NULL,NULL),(35,2,'DASHBOARDWIDGET','Top Potentials','index.php?module=Potentials&view=ShowWidget&name=TopPotentials','',7,NULL,NULL,NULL,NULL),(36,2,'DASHBOARDWIDGET','Overdue Activities','index.php?module=Potentials&view=ShowWidget&name=OverdueActivities','',9,NULL,NULL,NULL,NULL),(37,6,'DASHBOARDWIDGET','History','index.php?module=Accounts&view=ShowWidget&name=History','',1,NULL,NULL,NULL,NULL),(38,6,'DASHBOARDWIDGET','Upcoming Activities','index.php?module=Accounts&view=ShowWidget&name=CalendarActivities','',2,NULL,NULL,NULL,NULL),(39,6,'DASHBOARDWIDGET','Overdue Activities','index.php?module=Accounts&view=ShowWidget&name=OverdueActivities','',3,NULL,NULL,NULL,NULL),(40,4,'DASHBOARDWIDGET','History','index.php?module=Contacts&view=ShowWidget&name=History','',1,NULL,NULL,NULL,NULL),(41,4,'DASHBOARDWIDGET','Upcoming Activities','index.php?module=Contacts&view=ShowWidget&name=CalendarActivities','',2,NULL,NULL,NULL,NULL),(42,4,'DASHBOARDWIDGET','Overdue Activities','index.php?module=Contacts&view=ShowWidget&name=OverdueActivities','',3,NULL,NULL,NULL,NULL),(43,7,'DASHBOARDWIDGET','History','index.php?module=Leads&view=ShowWidget&name=History','',1,NULL,NULL,NULL,NULL),(44,7,'DASHBOARDWIDGET','Upcoming Activities','index.php?module=Leads&view=ShowWidget&name=CalendarActivities','',2,NULL,NULL,NULL,NULL),(45,7,'DASHBOARDWIDGET','Leads by Status','index.php?module=Leads&view=ShowWidget&name=LeadsByStatus','',4,NULL,NULL,NULL,NULL),(46,7,'DASHBOARDWIDGET','Leads by Source','index.php?module=Leads&view=ShowWidget&name=LeadsBySource','',5,NULL,NULL,NULL,NULL),(47,7,'DASHBOARDWIDGET','Leads by Industry','index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry','',6,NULL,NULL,NULL,NULL),(48,7,'DASHBOARDWIDGET','Overdue Activities','index.php?module=Leads&view=ShowWidget&name=OverdueActivities','',7,NULL,NULL,NULL,NULL),(49,13,'DASHBOARDWIDGET','Tickets by Status','index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus','',1,NULL,NULL,NULL,NULL),(50,13,'DASHBOARDWIDGET','Open Tickets','index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets','',2,NULL,NULL,NULL,NULL),(51,3,'DASHBOARDWIDGET','History','index.php?module=Home&view=ShowWidget&name=History','',1,NULL,NULL,NULL,NULL),(52,3,'DASHBOARDWIDGET','Upcoming Activities','index.php?module=Home&view=ShowWidget&name=CalendarActivities','',2,NULL,NULL,NULL,NULL),(53,3,'DASHBOARDWIDGET','Funnel','index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage','',3,NULL,NULL,NULL,NULL),(54,3,'DASHBOARDWIDGET','Potentials by Stage','index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson','',4,NULL,NULL,NULL,NULL),(55,3,'DASHBOARDWIDGET','Pipelined Amount','index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson','',5,NULL,NULL,NULL,NULL),(56,3,'DASHBOARDWIDGET','Total Revenue','index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson','',6,NULL,NULL,NULL,NULL),(57,3,'DASHBOARDWIDGET','Top Potentials','index.php?module=Potentials&view=ShowWidget&name=TopPotentials','',7,NULL,NULL,NULL,NULL),(58,3,'DASHBOARDWIDGET','Leads by Status','index.php?module=Leads&view=ShowWidget&name=LeadsByStatus','',10,NULL,NULL,NULL,NULL),(59,3,'DASHBOARDWIDGET','Leads by Source','index.php?module=Leads&view=ShowWidget&name=LeadsBySource','',11,NULL,NULL,NULL,NULL),(60,3,'DASHBOARDWIDGET','Leads by Industry','index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry','',12,NULL,NULL,NULL,NULL),(61,3,'DASHBOARDWIDGET','Overdue Activities','index.php?module=Home&view=ShowWidget&name=OverdueActivities','',13,NULL,NULL,NULL,NULL),(62,3,'DASHBOARDWIDGET','Tickets by Status','index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus','',13,NULL,NULL,NULL,NULL),(63,3,'DASHBOARDWIDGET','Open Tickets','index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets','',14,NULL,NULL,NULL,NULL),(64,13,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(89,3,'DASHBOARDWIDGET','Key Metrics','index.php?module=Home&view=ShowWidget&name=KeyMetrics','',0,NULL,NULL,NULL,NULL),(90,3,'DASHBOARDWIDGET','Mini List','index.php?module=Home&view=ShowWidget&name=MiniList','',0,NULL,NULL,NULL,NULL),(91,3,'DASHBOARDWIDGET','Tag Cloud','index.php?module=Home&view=ShowWidget&name=TagCloud','',0,NULL,NULL,NULL,NULL),(92,2,'DASHBOARDWIDGET','Funnel Amount','index.php?module=Potentials&view=ShowWidget&name=FunnelAmount','',10,NULL,NULL,NULL,NULL),(93,3,'DASHBOARDWIDGET','Funnel Amount','index.php?module=Potentials&view=ShowWidget&name=FunnelAmount','',10,NULL,NULL,NULL,NULL),(94,3,'DASHBOARDWIDGET','Notebook','index.php?module=Home&view=ShowWidget&name=Notebook','',0,NULL,NULL,NULL,NULL),(95,25,'LISTVIEWBASIC','LBL_ADD_RECORD','','',0,NULL,NULL,NULL,NULL),(96,25,'LISTVIEWBASIC','LBL_DETAIL_REPORT','javascript:Reports_List_Js.addReport(\"Reports/view/Edit\")','',0,'modules/Reports/models/Module.php','Reports_Module_Model','checkLinkAccess',95),(97,25,'LISTVIEWBASIC','LBL_CHARTS','javascript:Reports_List_Js.addReport(\"Reports/view/ChartEdit\")','',0,'modules/Reports/models/Module.php','Reports_Module_Model','checkLinkAccess',95),(98,25,'LISTVIEWBASIC','LBL_ADD_FOLDER','javascript:Reports_List_Js.triggerAddFolder(\"Reports/EditFolder\")','',0,'modules/Reports/models/Module.php','Reports_Module_Model','checkLinkAccess',NULL),(99,4,'EXTENSIONLINK','Google','Contacts/view/Extension?extensionModule=Google&extensionView=Index&mode=settings','',0,NULL,NULL,NULL,NULL),(100,9,'EXTENSIONLINK','Google','Calendar/view/Extension?extensionModule=Google&extensionView=Index&mode=settings','',0,NULL,NULL,NULL,NULL),(117,43,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL),(121,50,'HEADERSCRIPT','ExtensionStoreCommonHeaderScript','modules/ExtensionStore/ExtensionStore.js','',0,NULL,NULL,NULL,NULL),(122,3,'HEADERSCRIPT','Masquerade','layouts/modules/Settings/Head/resources/Masquerade.js','',0,'','','',0),(123,44,'DETAILVIEWBASIC','Add Project Task','index.php?module=ProjectTask&view=Edit&projectid=$RECORD$&sourceModule=Project&return_action=DetailView&sourceRecord=$RECORD$','',0,NULL,NULL,NULL,NULL),(124,44,'DETAILVIEWWIDGET','DetailViewBlockCommentWidget','block://ModComments:modules/ModComments/ModComments.php','',0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `jo_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_loginhistory`
--

DROP TABLE IF EXISTS `jo_loginhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_loginhistory` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_ip` varchar(25) NOT NULL,
  `logout_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_time` datetime DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`login_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_loginhistory`
--

LOCK TABLES `jo_loginhistory` WRITE;
/*!40000 ALTER TABLE `jo_loginhistory` DISABLE KEYS */;
INSERT INTO `jo_loginhistory` VALUES (1,'admin','::1','0000-00-00 00:00:00','2018-05-28 21:59:00','Signed in'),(2,'admin','192.168.1.101','0000-00-00 00:00:00','2018-07-08 17:13:08','Signed in'),(3,'admin','127.0.0.1','0000-00-00 00:00:00','2018-07-10 19:04:32','Signed in'),(4,'admin','192.168.1.44','0000-00-00 00:00:00','2018-07-12 19:12:14','Signed in'),(5,'admin','127.0.0.1','0000-00-00 00:00:00','2018-07-13 06:13:17','Signed in'),(6,'admin','127.0.0.1','0000-00-00 00:00:00','2020-09-23 08:28:38','Signed in'),(7,'admin','127.0.0.1','0000-00-00 00:00:00','2020-09-23 08:29:41','Signed in');
/*!40000 ALTER TABLE `jo_loginhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mail_accounts`
--

DROP TABLE IF EXISTS `jo_mail_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mail_accounts` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `mail_id` varchar(50) DEFAULT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `mail_protocol` varchar(20) DEFAULT NULL,
  `mail_username` varchar(50) NOT NULL,
  `mail_password` text,
  `mail_servername` varchar(50) DEFAULT NULL,
  `box_refresh` int(10) DEFAULT NULL,
  `mails_per_page` int(10) DEFAULT NULL,
  `ssltype` varchar(50) DEFAULT NULL,
  `sslmeth` varchar(50) DEFAULT NULL,
  `int_mailer` int(1) DEFAULT '0',
  `status` varchar(10) DEFAULT NULL,
  `set_default` int(2) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mail_accounts`
--

LOCK TABLES `jo_mail_accounts` WRITE;
/*!40000 ALTER TABLE `jo_mail_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mail_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailer_queue`
--

DROP TABLE IF EXISTS `jo_mailer_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailer_queue` (
  `id` int(11) NOT NULL,
  `fromname` varchar(100) DEFAULT NULL,
  `fromemail` varchar(100) DEFAULT NULL,
  `mailer` varchar(10) DEFAULT NULL,
  `content_type` varchar(15) DEFAULT NULL,
  `subject` varchar(999) DEFAULT NULL,
  `body` text,
  `relcrmid` int(11) DEFAULT NULL,
  `failed` int(1) NOT NULL DEFAULT '0',
  `failreason` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailer_queue`
--

LOCK TABLES `jo_mailer_queue` WRITE;
/*!40000 ALTER TABLE `jo_mailer_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailer_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailer_queueattachments`
--

DROP TABLE IF EXISTS `jo_mailer_queueattachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailer_queueattachments` (
  `id` int(11) DEFAULT NULL,
  `path` text,
  `name` varchar(100) DEFAULT NULL,
  `encoding` varchar(50) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailer_queueattachments`
--

LOCK TABLES `jo_mailer_queueattachments` WRITE;
/*!40000 ALTER TABLE `jo_mailer_queueattachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailer_queueattachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailer_queueinfo`
--

DROP TABLE IF EXISTS `jo_mailer_queueinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailer_queueinfo` (
  `id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `type` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailer_queueinfo`
--

LOCK TABLES `jo_mailer_queueinfo` WRITE;
/*!40000 ALTER TABLE `jo_mailer_queueinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailer_queueinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner`
--

DROP TABLE IF EXISTS `jo_mailscanner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner` (
  `scannerid` int(11) NOT NULL AUTO_INCREMENT,
  `scannername` varchar(30) DEFAULT NULL,
  `server` varchar(100) DEFAULT NULL,
  `protocol` varchar(10) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ssltype` varchar(10) DEFAULT NULL,
  `sslmethod` varchar(30) DEFAULT NULL,
  `connecturl` varchar(255) DEFAULT NULL,
  `searchfor` varchar(10) DEFAULT NULL,
  `markas` varchar(10) DEFAULT NULL,
  `isvalid` int(1) DEFAULT NULL,
  `scanfrom` varchar(10) DEFAULT 'ALL',
  `time_zone` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`scannerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner`
--

LOCK TABLES `jo_mailscanner` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner_actions`
--

DROP TABLE IF EXISTS `jo_mailscanner_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner_actions` (
  `actionid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `actiontype` varchar(10) DEFAULT NULL,
  `module` varchar(30) DEFAULT NULL,
  `lookup` varchar(30) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`actionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner_actions`
--

LOCK TABLES `jo_mailscanner_actions` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner_folders`
--

DROP TABLE IF EXISTS `jo_mailscanner_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner_folders` (
  `folderid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `foldername` varchar(255) DEFAULT NULL,
  `lastscan` varchar(30) DEFAULT NULL,
  `rescan` int(1) DEFAULT NULL,
  `enabled` int(1) DEFAULT NULL,
  PRIMARY KEY (`folderid`),
  KEY `folderid_idx` (`folderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner_folders`
--

LOCK TABLES `jo_mailscanner_folders` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner_folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner_ids`
--

DROP TABLE IF EXISTS `jo_mailscanner_ids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner_ids` (
  `scannerid` int(11) DEFAULT NULL,
  `messageid` varchar(512) DEFAULT NULL,
  `crmid` int(11) DEFAULT NULL,
  `refids` text,
  KEY `scanner_message_ids_idx` (`scannerid`,`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner_ids`
--

LOCK TABLES `jo_mailscanner_ids` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner_ids` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner_ids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner_ruleactions`
--

DROP TABLE IF EXISTS `jo_mailscanner_ruleactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner_ruleactions` (
  `ruleid` int(11) DEFAULT NULL,
  `actionid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner_ruleactions`
--

LOCK TABLES `jo_mailscanner_ruleactions` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner_ruleactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner_ruleactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mailscanner_rules`
--

DROP TABLE IF EXISTS `jo_mailscanner_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mailscanner_rules` (
  `ruleid` int(11) NOT NULL AUTO_INCREMENT,
  `scannerid` int(11) DEFAULT NULL,
  `fromaddress` varchar(255) DEFAULT NULL,
  `toaddress` varchar(255) DEFAULT NULL,
  `subjectop` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `bodyop` varchar(20) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `matchusing` varchar(5) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `assigned_to` int(10) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ruleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mailscanner_rules`
--

LOCK TABLES `jo_mailscanner_rules` WRITE;
/*!40000 ALTER TABLE `jo_mailscanner_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_mailscanner_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_manufacturer`
--

DROP TABLE IF EXISTS `jo_manufacturer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_manufacturer` (
  `manufacturerid` int(19) NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`manufacturerid`),
  UNIQUE KEY `manufacturer_manufacturer_idx` (`manufacturer`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_manufacturer`
--

LOCK TABLES `jo_manufacturer` WRITE;
/*!40000 ALTER TABLE `jo_manufacturer` DISABLE KEYS */;
INSERT INTO `jo_manufacturer` VALUES (2,'AltvetPet Inc.',1,119,1,NULL),(3,'LexPon Inc.',1,120,2,NULL),(4,'MetBeat Corp',1,121,3,NULL);
/*!40000 ALTER TABLE `jo_manufacturer` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_masqueradeuserdetails`
--

DROP TABLE IF EXISTS `jo_masqueradeuserdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_masqueradeuserdetails` (
  `record_id` int(11) NOT NULL,
  `portal_id` int(11) NOT NULL,
  `masquerade_module` varchar(255) DEFAULT NULL,
  `support_start_date` datetime DEFAULT NULL,
  `support_end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_masqueradeuserdetails`
--

LOCK TABLES `jo_masqueradeuserdetails` WRITE;
/*!40000 ALTER TABLE `jo_masqueradeuserdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_masqueradeuserdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_mobile_alerts`
--

DROP TABLE IF EXISTS `jo_mobile_alerts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_mobile_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handler_path` varchar(500) DEFAULT NULL,
  `handler_class` varchar(50) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_mobile_alerts`
--

LOCK TABLES `jo_mobile_alerts` WRITE;
/*!40000 ALTER TABLE `jo_mobile_alerts` DISABLE KEYS */;
INSERT INTO `jo_mobile_alerts` VALUES (1,'modules/Mobile/api/ws/models/alerts/IdleTicketsOfMine.php','Mobile_WS_AlertModel_IdleTicketsOfMine',NULL,0),(2,'modules/Mobile/api/ws/models/alerts/NewTicketOfMine.php','Mobile_WS_AlertModel_NewTicketOfMine',NULL,0),(3,'modules/Mobile/api/ws/models/alerts/PendingTicketsOfMine.php','Mobile_WS_AlertModel_PendingTicketsOfMine',NULL,0),(4,'modules/Mobile/api/ws/models/alerts/PotentialsDueIn5Days.php','Mobile_WS_AlertModel_PotentialsDueIn5Days',NULL,0),(5,'modules/Mobile/api/ws/models/alerts/EventsOfMineToday.php','Mobile_WS_AlertModel_EventsOfMineToday',NULL,0),(6,'modules/Mobile/api/ws/models/alerts/ProjectTasksOfMine.php','Mobile_WS_AlertModel_ProjectTasksOfMine',NULL,0),(7,'modules/Mobile/api/ws/models/alerts/Projects.php','Mobile_WS_AlertModel_Projects',NULL,0);
/*!40000 ALTER TABLE `jo_mobile_alerts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modcomments`
--

DROP TABLE IF EXISTS `jo_modcomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modcomments` (
  `modcommentsid` int(11) DEFAULT NULL,
  `commentcontent` text,
  `related_to` int(19) DEFAULT NULL,
  `parent_comments` int(19) DEFAULT NULL,
  `customer` int(19) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL,
  `reasontoedit` varchar(100) DEFAULT NULL,
  `is_private` int(1) DEFAULT '0',
  `filename` varchar(255) DEFAULT NULL,
  `related_email_id` int(11) DEFAULT NULL,
  KEY `relatedto_idx` (`related_to`),
  KEY `fk_crmid_jo_modcomments` (`modcommentsid`),
  CONSTRAINT `fk_crmid_jo_modcomments` FOREIGN KEY (`modcommentsid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modcomments`
--

LOCK TABLES `jo_modcomments` WRITE;
/*!40000 ALTER TABLE `jo_modcomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_modcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modcommentscf`
--

DROP TABLE IF EXISTS `jo_modcommentscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modcommentscf` (
  `modcommentsid` int(11) NOT NULL,
  PRIMARY KEY (`modcommentsid`),
  CONSTRAINT `fk_modcommentsid_jo_modcommentscf` FOREIGN KEY (`modcommentsid`) REFERENCES `jo_modcomments` (`modcommentsid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modcommentscf`
--

LOCK TABLES `jo_modcommentscf` WRITE;
/*!40000 ALTER TABLE `jo_modcommentscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_modcommentscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modentity_num`
--

DROP TABLE IF EXISTS `jo_modentity_num`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modentity_num` (
  `num_id` int(19) NOT NULL,
  `semodule` varchar(100) DEFAULT NULL,
  `prefix` varchar(50) NOT NULL DEFAULT '',
  `start_id` varchar(50) NOT NULL,
  `cur_id` varchar(50) NOT NULL,
  `active` varchar(2) NOT NULL,
  PRIMARY KEY (`num_id`),
  UNIQUE KEY `num_idx` (`num_id`),
  KEY `semodule_active_idx` (`semodule`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modentity_num`
--

LOCK TABLES `jo_modentity_num` WRITE;
/*!40000 ALTER TABLE `jo_modentity_num` DISABLE KEYS */;
INSERT INTO `jo_modentity_num` VALUES (1,'Leads','LEA','1','1','1'),(2,'Accounts','ACC','1','1','1'),(3,'Campaigns','CAM','1','1','1'),(4,'Contacts','CON','1','1','1'),(5,'Potentials','POT','1','1','1'),(6,'HelpDesk','TT','1','1','1'),(7,'Quotes','QUO','1','1','1'),(8,'SalesOrder','SO','1','1','1'),(9,'PurchaseOrder','PO','1','1','1'),(10,'Invoice','INV','1','1','1'),(11,'Products','PRO','1','1','1'),(12,'Vendors','VEN','1','1','1'),(13,'PriceBooks','PB','1','1','1'),(14,'Documents','DOC','1','1','1'),(15,'Services','SER','1','1','1'),(16,'ProjectMilestone','PM','1','1','1'),(17,'ProjectTask','PT','1','1','1'),(18,'Project','PROJ','1','1','1');
/*!40000 ALTER TABLE `jo_modentity_num` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_modtracker_basic`
--

DROP TABLE IF EXISTS `jo_modtracker_basic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modtracker_basic` (
  `id` int(20) NOT NULL,
  `crmid` int(20) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `whodid` int(20) DEFAULT NULL,
  `changedon` datetime DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `crmidx` (`crmid`),
  KEY `idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modtracker_basic`
--

LOCK TABLES `jo_modtracker_basic` WRITE;
/*!40000 ALTER TABLE `jo_modtracker_basic` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_modtracker_basic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modtracker_detail`
--

DROP TABLE IF EXISTS `jo_modtracker_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modtracker_detail` (
  `id` int(11) DEFAULT NULL,
  `fieldname` varchar(100) DEFAULT NULL,
  `prevalue` text,
  `postvalue` text,
  KEY `idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modtracker_detail`
--

LOCK TABLES `jo_modtracker_detail` WRITE;
/*!40000 ALTER TABLE `jo_modtracker_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_modtracker_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modtracker_relations`
--

DROP TABLE IF EXISTS `jo_modtracker_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modtracker_relations` (
  `id` int(19) NOT NULL,
  `targetmodule` varchar(100) NOT NULL,
  `targetid` int(19) NOT NULL,
  `changedon` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modtracker_relations`
--

LOCK TABLES `jo_modtracker_relations` WRITE;
/*!40000 ALTER TABLE `jo_modtracker_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_modtracker_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_modtracker_tabs`
--

DROP TABLE IF EXISTS `jo_modtracker_tabs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_modtracker_tabs` (
  `tabid` int(11) NOT NULL,
  `visible` int(11) DEFAULT '0',
  PRIMARY KEY (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_modtracker_tabs`
--

LOCK TABLES `jo_modtracker_tabs` WRITE;
/*!40000 ALTER TABLE `jo_modtracker_tabs` DISABLE KEYS */;
INSERT INTO `jo_modtracker_tabs` VALUES (2,1),(4,1),(6,1),(7,1),(8,1),(9,1),(10,1),(13,1),(14,1),(16,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(26,1),(28,1),(36,1),(37,1),(42,1),(43,1),(44,1),(47,1);
/*!40000 ALTER TABLE `jo_modtracker_tabs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_module_dashboard_widgets`
--

DROP TABLE IF EXISTS `jo_module_dashboard_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_module_dashboard_widgets` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `linkid` int(19) DEFAULT NULL,
  `userid` int(19) DEFAULT NULL,
  `filterid` int(19) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `data` text,
  `position` varchar(50) DEFAULT NULL,
  `reportid` int(19) DEFAULT NULL,
  `dashboardtabid` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `dashboardtabid` (`dashboardtabid`),
  CONSTRAINT `jo_module_dashboard_widgets_ibfk_1` FOREIGN KEY (`dashboardtabid`) REFERENCES `jo_dashboard_tabs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_module_dashboard_widgets`
--

LOCK TABLES `jo_module_dashboard_widgets` WRITE;
/*!40000 ALTER TABLE `jo_module_dashboard_widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_module_dashboard_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_no_of_currency_decimals`
--

DROP TABLE IF EXISTS `jo_no_of_currency_decimals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_no_of_currency_decimals` (
  `no_of_currency_decimalsid` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_currency_decimals` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`no_of_currency_decimalsid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_no_of_currency_decimals`
--

LOCK TABLES `jo_no_of_currency_decimals` WRITE;
/*!40000 ALTER TABLE `jo_no_of_currency_decimals` DISABLE KEYS */;
INSERT INTO `jo_no_of_currency_decimals` VALUES (2,'2',2,1),(3,'3',3,1),(4,'4',4,1),(5,'5',5,1),(6,'0',0,1),(7,'1',1,1);
/*!40000 ALTER TABLE `jo_no_of_currency_decimals` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_notebook_contents`
--

DROP TABLE IF EXISTS `jo_notebook_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notebook_contents` (
  `userid` int(19) NOT NULL,
  `notebookid` int(19) NOT NULL,
  `contents` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notebook_contents`
--

LOCK TABLES `jo_notebook_contents` WRITE;
/*!40000 ALTER TABLE `jo_notebook_contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_notebook_contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_notes`
--

DROP TABLE IF EXISTS `jo_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notes` (
  `notesid` int(19) NOT NULL DEFAULT '0',
  `note_no` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `notecontent` text,
  `folderid` int(19) NOT NULL DEFAULT '1',
  `filetype` varchar(50) DEFAULT NULL,
  `filelocationtype` varchar(5) DEFAULT NULL,
  `filedownloadcount` int(19) DEFAULT NULL,
  `filestatus` int(19) DEFAULT NULL,
  `filesize` int(19) NOT NULL DEFAULT '0',
  `fileversion` varchar(50) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`notesid`),
  KEY `notes_title_idx` (`title`),
  KEY `notes_notesid_idx` (`notesid`),
  CONSTRAINT `fk_1_jo_notes` FOREIGN KEY (`notesid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notes`
--

LOCK TABLES `jo_notes` WRITE;
/*!40000 ALTER TABLE `jo_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_notescf`
--

DROP TABLE IF EXISTS `jo_notescf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notescf` (
  `notesid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`notesid`),
  CONSTRAINT `fk_notesid_jo_notescf` FOREIGN KEY (`notesid`) REFERENCES `jo_notes` (`notesid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notescf`
--

LOCK TABLES `jo_notescf` WRITE;
/*!40000 ALTER TABLE `jo_notescf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_notescf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_notification`
--

DROP TABLE IF EXISTS `jo_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notification` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `module_name` varchar(40) NOT NULL,
  `entity_id` int(20) NOT NULL,
  `notifier_id` int(10) NOT NULL,
  `is_seen` int(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `action_type` varchar(40) NOT NULL,
  `fieldname` varchar(50) DEFAULT NULL,
  `oldvalue` text,
  `newvalue` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notification`
--

LOCK TABLES `jo_notification` WRITE;
/*!40000 ALTER TABLE `jo_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_notificationscheduler`
--

DROP TABLE IF EXISTS `jo_notificationscheduler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notificationscheduler` (
  `schedulednotificationid` int(19) NOT NULL AUTO_INCREMENT,
  `schedulednotificationname` varchar(200) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `notificationsubject` varchar(200) DEFAULT NULL,
  `notificationbody` text,
  `label` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`schedulednotificationid`),
  UNIQUE KEY `notificationscheduler_schedulednotificationname_idx` (`schedulednotificationname`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notificationscheduler`
--

LOCK TABLES `jo_notificationscheduler` WRITE;
/*!40000 ALTER TABLE `jo_notificationscheduler` DISABLE KEYS */;
INSERT INTO `jo_notificationscheduler` VALUES (1,'LBL_TASK_NOTIFICATION_DESCRITPION',1,'Task Delay Notification','Tasks delayed beyond 24 hrs ','LBL_TASK_NOTIFICATION',NULL),(2,'LBL_BIG_DEAL_DESCRIPTION',1,'Big Deal notification','Success! A big deal has been won! ','LBL_BIG_DEAL',NULL),(3,'LBL_TICKETS_DESCRIPTION',1,'Pending Tickets notification','Ticket pending please ','LBL_PENDING_TICKETS',NULL),(4,'LBL_MANY_TICKETS_DESCRIPTION',1,'Too many tickets Notification','Too many tickets pending against this entity ','LBL_MANY_TICKETS',NULL),(5,'LBL_START_DESCRIPTION',1,'Support Start Notification','10','LBL_START_NOTIFICATION','select'),(6,'LBL_SUPPORT_DESCRIPTION',1,'Support ending please','11','LBL_SUPPORT_NOTICIATION','select'),(7,'LBL_SUPPORT_DESCRIPTION_MONTH',1,'Support ending please','12','LBL_SUPPORT_NOTICIATION_MONTH','select'),(8,'LBL_ACTIVITY_REMINDER_DESCRIPTION',1,'Activity Reminder Notification','This is a reminder notification for the Activity','LBL_ACTIVITY_NOTIFICATION',NULL);
/*!40000 ALTER TABLE `jo_notificationscheduler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_notifyauthtoken`
--

DROP TABLE IF EXISTS `jo_notifyauthtoken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_notifyauthtoken` (
  `userid` int(19) NOT NULL,
  `token` varchar(255) NOT NULL,
  `devicetype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_notifyauthtoken`
--

LOCK TABLES `jo_notifyauthtoken` WRITE;
/*!40000 ALTER TABLE `jo_notifyauthtoken` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_notifyauthtoken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_opportunity_type`
--

DROP TABLE IF EXISTS `jo_opportunity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_opportunity_type` (
  `opptypeid` int(19) NOT NULL AUTO_INCREMENT,
  `opportunity_type` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`opptypeid`),
  UNIQUE KEY `opportunity_type_opportunity_type_idx` (`opportunity_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_opportunity_type`
--

LOCK TABLES `jo_opportunity_type` WRITE;
/*!40000 ALTER TABLE `jo_opportunity_type` DISABLE KEYS */;
INSERT INTO `jo_opportunity_type` VALUES (2,'Existing Business',1,123,1,NULL),(3,'New Business',1,124,2,NULL);
/*!40000 ALTER TABLE `jo_opportunity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_opportunitystage`
--

DROP TABLE IF EXISTS `jo_opportunitystage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_opportunitystage` (
  `potstageid` int(19) NOT NULL AUTO_INCREMENT,
  `stage` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `probability` decimal(3,2) DEFAULT '0.00',
  PRIMARY KEY (`potstageid`),
  UNIQUE KEY `opportunitystage_stage_idx` (`stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_opportunitystage`
--

LOCK TABLES `jo_opportunitystage` WRITE;
/*!40000 ALTER TABLE `jo_opportunitystage` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_opportunitystage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_org_share_action2tab`
--

DROP TABLE IF EXISTS `jo_org_share_action2tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_org_share_action2tab` (
  `share_action_id` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  PRIMARY KEY (`share_action_id`,`tabid`),
  KEY `fk_2_jo_org_share_action2tab` (`tabid`),
  CONSTRAINT `fk_2_jo_org_share_action2tab` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_org_share_action2tab`
--

LOCK TABLES `jo_org_share_action2tab` WRITE;
/*!40000 ALTER TABLE `jo_org_share_action2tab` DISABLE KEYS */;
INSERT INTO `jo_org_share_action2tab` VALUES (0,2),(1,2),(2,2),(3,2),(0,4),(1,4),(2,4),(3,4),(0,6),(1,6),(2,6),(3,6),(0,7),(1,7),(2,7),(3,7),(0,8),(1,8),(2,8),(3,8),(0,9),(1,9),(2,9),(3,9),(0,10),(1,10),(2,10),(3,10),(0,13),(1,13),(2,13),(3,13),(0,14),(1,14),(2,14),(3,14),(0,16),(1,16),(2,16),(3,16),(0,20),(1,20),(2,20),(3,20),(0,21),(1,21),(2,21),(3,21),(0,22),(1,22),(2,22),(3,22),(0,23),(1,23),(2,23),(3,23),(0,26),(1,26),(2,26),(3,26),(0,36),(1,36),(2,36),(3,36),(0,37),(1,37),(2,37),(3,37),(0,42),(1,42),(2,42),(3,42),(0,43),(1,43),(2,43),(3,43),(0,44),(1,44),(2,44),(3,44),(0,47),(1,47),(2,47),(3,47);
/*!40000 ALTER TABLE `jo_org_share_action2tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_org_share_action_mapping`
--

DROP TABLE IF EXISTS `jo_org_share_action_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_org_share_action_mapping` (
  `share_action_id` int(19) NOT NULL,
  `share_action_name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`share_action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_org_share_action_mapping`
--

LOCK TABLES `jo_org_share_action_mapping` WRITE;
/*!40000 ALTER TABLE `jo_org_share_action_mapping` DISABLE KEYS */;
INSERT INTO `jo_org_share_action_mapping` VALUES (0,'Public: Read Only'),(1,'Public: Read, Create/Edit'),(2,'Public: Read, Create/Edit, Delete'),(3,'Private'),(4,'Hide Details'),(5,'Hide Details and Add Events'),(6,'Show Details'),(7,'Show Details and Add Events');
/*!40000 ALTER TABLE `jo_org_share_action_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_organizationdetails`
--

DROP TABLE IF EXISTS `jo_organizationdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_organizationdetails` (
  `organization_id` int(11) NOT NULL,
  `organizationname` varchar(60) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `code` varchar(30) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `logoname` varchar(50) DEFAULT NULL,
  `logo` text,
  `vatid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_organizationdetails`
--

LOCK TABLES `jo_organizationdetails` WRITE;
/*!40000 ALTER TABLE `jo_organizationdetails` DISABLE KEYS */;
INSERT INTO `jo_organizationdetails` VALUES (1,'Joforce','#R43/S1 Shah Complex','Tirunelveli','TamilNadu','India','627002','+91 462 4000004','','www.joforce.com','JoForce-Logo.png',NULL,'');
/*!40000 ALTER TABLE `jo_organizationdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_othereventduration`
--

DROP TABLE IF EXISTS `jo_othereventduration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_othereventduration` (
  `othereventdurationid` int(11) NOT NULL AUTO_INCREMENT,
  `othereventduration` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`othereventdurationid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_othereventduration`
--

LOCK TABLES `jo_othereventduration` WRITE;
/*!40000 ALTER TABLE `jo_othereventduration` DISABLE KEYS */;
INSERT INTO `jo_othereventduration` VALUES (1,'5',0,1),(2,'10',1,1),(3,'30',2,1),(4,'60',3,1),(5,'120',4,1);
/*!40000 ALTER TABLE `jo_othereventduration` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_parenttab`
--

DROP TABLE IF EXISTS `jo_parenttab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_parenttab` (
  `parenttabid` int(19) NOT NULL,
  `parenttab_label` varchar(100) NOT NULL,
  `sequence` int(10) NOT NULL,
  `visible` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`parenttabid`),
  KEY `parenttab_parenttabid_parenttabl_label_visible_idx` (`parenttabid`,`parenttab_label`,`visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_parenttab`
--

LOCK TABLES `jo_parenttab` WRITE;
/*!40000 ALTER TABLE `jo_parenttab` DISABLE KEYS */;
INSERT INTO `jo_parenttab` VALUES (1,'My Home Page',1,0),(2,'Marketing',2,0),(3,'Sales',3,0),(4,'Support',4,0),(5,'Analytics',5,0),(6,'Inventory',6,0),(7,'Tools',7,0),(8,'Settings',8,0);
/*!40000 ALTER TABLE `jo_parenttab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_parenttabrel`
--

DROP TABLE IF EXISTS `jo_parenttabrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_parenttabrel` (
  `parenttabid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `sequence` int(3) NOT NULL,
  KEY `parenttabrel_tabid_parenttabid_idx` (`tabid`,`parenttabid`),
  KEY `fk_2_jo_parenttabrel` (`parenttabid`),
  CONSTRAINT `fk_1_jo_parenttabrel` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE,
  CONSTRAINT `fk_2_jo_parenttabrel` FOREIGN KEY (`parenttabid`) REFERENCES `jo_parenttab` (`parenttabid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_parenttabrel`
--

LOCK TABLES `jo_parenttabrel` WRITE;
/*!40000 ALTER TABLE `jo_parenttabrel` DISABLE KEYS */;
INSERT INTO `jo_parenttabrel` VALUES (1,9,2),(1,28,4),(1,3,1),(3,7,1),(3,6,2),(3,4,3),(3,2,4),(3,20,5),(3,22,6),(3,23,7),(3,19,8),(3,8,9),(4,13,1),(4,6,3),(4,4,4),(4,8,5),(5,1,2),(5,25,1),(6,14,1),(6,18,2),(6,19,3),(6,21,4),(6,22,5),(6,20,6),(6,23,7),(7,24,1),(7,27,2),(7,8,3),(2,26,1),(2,6,2),(2,4,3),(2,28,4),(4,28,7),(2,7,5),(2,9,6),(4,9,8),(2,8,8),(3,9,11),(6,36,8),(6,36,9),(7,37,4),(7,37,5),(4,42,9),(4,42,10),(4,43,11),(4,43,12),(4,44,13),(4,44,14),(7,46,6),(7,46,7),(7,48,8),(7,48,9);
/*!40000 ALTER TABLE `jo_parenttabrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_payment_duration`
--

DROP TABLE IF EXISTS `jo_payment_duration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_payment_duration` (
  `payment_duration_id` int(11) DEFAULT NULL,
  `payment_duration` varchar(200) DEFAULT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_payment_duration`
--

LOCK TABLES `jo_payment_duration` WRITE;
/*!40000 ALTER TABLE `jo_payment_duration` DISABLE KEYS */;
INSERT INTO `jo_payment_duration` VALUES (1,'Net 30 days',0,1,NULL),(2,'Net 45 days',1,1,NULL),(3,'Net 60 days',2,1,NULL);
/*!40000 ALTER TABLE `jo_payment_duration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pbxmanager`
--

DROP TABLE IF EXISTS `jo_pbxmanager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pbxmanager` (
  `pbxmanagerid` int(20) NOT NULL AUTO_INCREMENT,
  `direction` varchar(10) DEFAULT NULL,
  `callstatus` varchar(20) DEFAULT NULL,
  `starttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `totalduration` int(11) DEFAULT NULL,
  `billduration` int(11) DEFAULT NULL,
  `recordingurl` varchar(200) DEFAULT NULL,
  `sourceuuid` varchar(100) DEFAULT NULL,
  `gateway` varchar(20) DEFAULT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `customernumber` varchar(100) DEFAULT NULL,
  `customertype` varchar(100) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`pbxmanagerid`),
  KEY `index_sourceuuid` (`sourceuuid`),
  KEY `index_pbxmanager_id` (`pbxmanagerid`),
  CONSTRAINT `fk_crmid_jo_pbxmanager` FOREIGN KEY (`pbxmanagerid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pbxmanager`
--

LOCK TABLES `jo_pbxmanager` WRITE;
/*!40000 ALTER TABLE `jo_pbxmanager` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pbxmanager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pbxmanager_gateway`
--

DROP TABLE IF EXISTS `jo_pbxmanager_gateway`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pbxmanager_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway` varchar(20) DEFAULT NULL,
  `parameters` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pbxmanager_gateway`
--

LOCK TABLES `jo_pbxmanager_gateway` WRITE;
/*!40000 ALTER TABLE `jo_pbxmanager_gateway` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pbxmanager_gateway` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pbxmanager_phonelookup`
--

DROP TABLE IF EXISTS `jo_pbxmanager_phonelookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pbxmanager_phonelookup` (
  `crmid` int(20) DEFAULT NULL,
  `setype` varchar(30) DEFAULT NULL,
  `fnumber` varchar(100) DEFAULT NULL,
  `rnumber` varchar(100) DEFAULT NULL,
  `fieldname` varchar(50) DEFAULT NULL,
  UNIQUE KEY `unique_key` (`crmid`,`setype`,`fieldname`),
  KEY `index_phone_number` (`fnumber`,`rnumber`),
  CONSTRAINT `jo_pbxmanager_phonelookup_ibfk_1` FOREIGN KEY (`crmid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pbxmanager_phonelookup`
--

LOCK TABLES `jo_pbxmanager_phonelookup` WRITE;
/*!40000 ALTER TABLE `jo_pbxmanager_phonelookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pbxmanager_phonelookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pbxmanagercf`
--

DROP TABLE IF EXISTS `jo_pbxmanagercf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pbxmanagercf` (
  `pbxmanagerid` int(11) NOT NULL,
  PRIMARY KEY (`pbxmanagerid`),
  CONSTRAINT `fk_pbxmanagerid_jo_pbxmanagercf` FOREIGN KEY (`pbxmanagerid`) REFERENCES `jo_pbxmanager` (`pbxmanagerid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pbxmanagercf`
--

LOCK TABLES `jo_pbxmanagercf` WRITE;
/*!40000 ALTER TABLE `jo_pbxmanagercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pbxmanagercf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pdfmaker`
--

DROP TABLE IF EXISTS `jo_pdfmaker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pdfmaker` (
  `pdfmakerid` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `description` text,
  `body` text,
  `status` int(2) DEFAULT NULL,
  `settings` text,
  `header` text,
  `footer` text,
  PRIMARY KEY (`pdfmakerid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pdfmaker`
--

LOCK TABLES `jo_pdfmaker` WRITE;
/*!40000 ALTER TABLE `jo_pdfmaker` DISABLE KEYS */;
INSERT INTO `jo_pdfmaker` VALUES (1,'Invoice','Invoice','','\n			<table width=\"985\">\n	<tbody>\n		<tr>\n			<td style=\"width:50%;\"><img alt=\"\" height=\"79\" src=\"$image_URL$\" width=\"200\" /></td>\n			<td style=\"width:50%;font-size:20px;text-align:right;\">\n                        <h3>INVOICE</h3>\n                        </td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"><b>$company-organizationname$</b></td>\n			<td style=\"font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);\">$invoice-invoice_no$</td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-address$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-country$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\"><span style=\"font-size:12px;\"><b>Balance Due</b></span></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\">$invoice-total$</td>\n		</tr>\n	</tbody>\n</table>\n<table width=\"985\">\n        <tbody>\n                <tr>\n                        <td style=\"width:50%;\"><span style=\"color:#A9A9A9;font-size:23px;\">Bill To:</span>\n\n                        <p style=\"font-size:23px;\">$invoice-accountid:accountname$</p>\n\n                        <p style=\"font-size:23px;\">$invoice-bill_street$</p>\n\n                        <p style=\"font-size:23px;\">$invoice-bill_city$</p>\n\n                        <p style=\"font-size:23px;\">$invoice-bill_country$</p>\n                        </td>\n                        <td style=\"text-align:right;width:100%;font-size:23px;\">\n                        <p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Invoice Date:</b></span> $custom-currentdate$</p>\n\n                        <p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Due Date:</b></span>$invoice-duedate$</p>\n                        </td>\n                </tr>\n        </tbody>\n</table>\n<br />\n\n<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"layout\" style=\"border-collapse:collapse;\" width=\"991\">\n	<tbody>\n		<tr style=\"background:#000;\">\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Sno.</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Product Name</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Quantity</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>List Price</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Total</strong></font></td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_start$</td>\n		</tr>\n		<tr>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_sno$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-productname$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-quantity$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-listprice$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-total$</td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_end$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Items Total</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-subtotal$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Discount</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-discount_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Tax</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-tax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\"><span class=\"pull-right\">Shipping &amp; Handling Charges</span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-s_h_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Taxes For Shipping and Handling</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-shtax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Adjustment</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-adjustment$</td>\n		</tr>\n		<tr style=\"height:10px;\">\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Grand Total <span><b>( in </b><b>$invoice-currency_id$</b><b> )</b></span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-total$</td>\n		</tr>\n	</tbody>\n</table>\n<br />',1,'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo3OiJJbnZvaWNlIjtzOjExOiJwYWdlX2Zvcm1hdCI7czoyOiJBNCI7czoxNjoicGFnZV9vcmllbnRhdGlvbiI7czoxOiJQIjtzOjEwOiJtYXJnaW5fdG9wIjtzOjM6IjEwJSI7czoxMzoibWFyZ2luX2JvdHRvbSI7czozOiIxMCUiO3M6MTE6Im1hcmdpbl9sZWZ0IjtzOjM6IjEwJSI7czoxMjoibWFyZ2luX3JpZ2h0IjtzOjM6IjEwJSI7czoxMDoiZGV0YWlsdmlldyI7czoyOiJvbiI7czo4OiJsaXN0dmlldyI7czoyOiJvbiI7fQ==','','##Page##'),(2,'Quotes','Quotes','','\n			<table width=\"985\">\n		<tbody>\n		<tr>\n			<td style=\"width:50%;\"><img alt=\"\" height=\"79\" src=\"$image_URL$\" width=\"200\" /></td>\n			<td style=\"width:50%;font-size:20px;text-align:right;\">\n                        <h3>QUOTE</h3>\n                        </td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"><b>$company-organizationname$</b></td>\n			<td style=\"font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);\">$quotes-quote_no$</td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-address$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-country$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\"><span style=\"font-size:12px;\"><b>Balance Due</b></span></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\">$quotes-total$</td>\n		</tr>\n	</tbody>\n</table>\n<table width=\"985\">\n        <tbody>\n                <tr>\n                        <td style=\"width:50%;\"><span style=\"color:#A9A9A9;font-size:23px;\">Bill To:</span>\n\n                        <p style=\"font-size:23px;\">$quotes-accountid:accountname$</p>\n\n                        <p style=\"font-size:23px;\">$quotes-bill_street$</p>\n\n                        <p style=\"font-size:23px;\">$quotes-bill_city$</p>\n\n                        <p style=\"font-size:23px;\">$quotes-bill_country$</p>\n                        </td>\n                        <td style=\"text-align:right;width:100%;font-size:23px;\">\n                        <p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Quote Date:</b></span> $custom-currentdate$</p>\n\n                        </td>\n                </tr>\n        </tbody>\n</table>\n<br />\n\n<br />\n<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"layout\" width=\"991\" style = \"border-collapse: collapse;\">\n	<tbody>\n		<tr style=\"background:#000;\" >\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><font color=\"#fff\"><strong>Sno.</strong></font></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><font color=\"#fff\"><strong>Product Name</strong></font></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><font color=\"#fff\"><strong>Quantity</strong></font></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><font color=\"#fff\"><strong>List Price</strong></font></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><font color=\"#fff\"><strong>Total</strong></font></td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$productblock_start$</td>\n		</tr>\n		<tr>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$productblock_sno$</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$products-productname$</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$products-quantity$</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$products-listprice$</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$products-total$</td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$productblock_end$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Items Total</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-subtotal$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Discount</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-discount_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Tax</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-tax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\"><span class=\"pull-right\">Shipping &amp; Handling Charges</span></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-s_h_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Taxes For Shipping and Handling</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-shtax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Adjustment</td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-adjustment$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">Grand Total<span><b>( in </b><b>$quotes-currency_id$</b><b> )</b></span></td>\n			<td style=\"border-bottom: 1px solid #ccc;padding: 4mm;\">$pdt-total$</td>\n		</tr>\n	</tbody>\n</table>\n<br />\n',1,'YTo5OntzOjk6ImZpbGVfbmFtZSI7czo2OiJRdW90ZXMiO3M6MTE6InBhZ2VfZm9ybWF0IjtzOjI6IkE0IjtzOjE2OiJwYWdlX29yaWVudGF0aW9uIjtzOjE6IlAiO3M6MTA6Im1hcmdpbl90b3AiO3M6MzoiMTAlIjtzOjEzOiJtYXJnaW5fYm90dG9tIjtzOjM6IjEwJSI7czoxMToibWFyZ2luX2xlZnQiO3M6MzoiMTAlIjtzOjEyOiJtYXJnaW5fcmlnaHQiO3M6MzoiMTAlIjtzOjEwOiJkZXRhaWx2aWV3IjtzOjI6Im9uIjtzOjg6Imxpc3R2aWV3IjtzOjI6Im9uIjt9','','##Page##'),(3,'PurchaseOrder','PurchaseOrder','','<table width=\"985\">\n	<tbody>\n		<tr>\n			<td style=\"width:50%;\"><img alt=\"\" height=\"79\" src=\"$image_URL$\" width=\"200\" /></td>\n			<td style=\"width:50%;font-size:20px;text-align:right;\">\n                        <h3>Purchase Order</h3>\n                        </td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"><b>$company-organizationname$</b></td>\n			<td style=\"font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);\">$purchaseorder-purchaseorder_no$</td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-address$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-country$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\"><span style=\"font-size:12px;\"><b>Balance Due</b></span></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\">$purchaseorder-total$</td>\n		</tr>\n	</tbody>\n</table>\n<br />\n<table width=\"985\">\n        <tbody>\n                <tr>\n                        <td style=\"width:50%;\"><span style=\"color:#A9A9A9;font-size:23px;\">Bill To:</span>\n\n                        <p style=\"font-size:23px;\">$purchaseorder-vendorid:vendorname$</p>\n\n                        <p style=\"font-size:23px;\">$purchaseorder-bill_street$</p>\n\n                        <p style=\"font-size:23px;\">$purchaseorder-bill_city$</p>\n\n                        <p style=\"font-size:23px;\">$purchaseorder-bill_country$</p>\n                        </td>\n                        <td style=\"text-align:right;width:100%;font-size:23px;\">\n                        <p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Purchase Date:</b></span> $custom-currentdate$</p>\n\n                        <p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Due Date:</b></span> $purchaseorder-duedate$</p>\n                        </td>\n                </tr>\n        </tbody>\n</table>\n<br />\n\n\n<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"layout\" style=\"border-collapse:collapse;\" width=\"991\">\n	<tbody>\n		<tr style=\"background:#000;\">\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Sno.</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Product Name</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Quantity</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>List Price</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Total</strong></font></td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_start$</td>\n		</tr>\n		<tr>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_sno$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-productname$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-quantity$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-listprice$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-total$</td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_end$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Items Total</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-subtotal$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Discount</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-discount_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Tax</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-tax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\"><span class=\"pull-right\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Shipping &amp; Handling Charges</span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-s_h_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Taxes For Shipping and Handling</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-shtax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Adjustment</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-adjustment$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Grand Total<span><b>( in </b> <b>$purchaseorder-currency_id$</b> <b> ) </b></span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-total$</td>\n		</tr>\n	</tbody>\n</table>\n<br />\n',1,'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMzoiUHVyY2hhc2VPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=','','##Page##'),(4,'SalesOrder','SalesOrder','','\n			<table width=\"985\">\n	<tbody>\n		<tr>\n			<td style=\"width:50%;\"><img alt=\"\" height=\"79\" src=\"$image_URL$\" width=\"200\" /></td>\n			<td style=\"width:50%;font-size:20px;text-align:right;\">\n			<h3>Sales Order</h3>\n			</td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"><b>$company-organizationname$</b></td>\n			<td style=\"font-size:20px;width:50%;text-align:right;color:rgb(128,128,128);\">$salesorder-salesorder_no$</td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-address$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\">$company-country$</td>\n			<td style=\"width:50%;\"></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\"><span style=\"font-size:15px;\"><b>Balance Due</b></span></td>\n		</tr>\n		<tr>\n			<td style=\"width:50%;\"></td>\n			<td style=\"text-align:right;width:50%;\">$salesorder-total$</td>\n		</tr>\n	</tbody>\n</table>\n\n<table width=\"985\">\n	<tbody>\n		<tr>\n			<td style=\"width:50%;\"><span style=\"color:#A9A9A9;font-size:23px;\">Bill To:</span>\n\n			<p style=\"font-size:23px;\">$salesorder-accountid:accountname$</p>\n\n			<p style=\"font-size:23px;\">$salesorder-bill_street$</p>\n\n			<p style=\"font-size:23px;\">$salesorder-bill_city$</p>\n\n			<p style=\"font-size:23px;\">$salesorder-bill_country$</p>\n			</td>\n			<td style=\"text-align:right;width:100%;font-size:23px;\">\n			<p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Sales Date:</b></span> $custom-currentdate$</p>\n\n			<p style=\"text-align:right;width:100%;font-size:23px;\"><span style=\"color:#808080;text-align:left;font-size:23px;\"><b>Due Date:</b></span>$salesorder-duedate$</p>\n			</td>\n		</tr>\n	</tbody>\n</table>\n<br />\n<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"layout\" style=\"border-collapse:collapse;\" width=\"991\">\n	<tbody>\n		<tr style=\"background:#000;\">\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Sno.</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Product Name</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Quantity</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>List Price</strong></font></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\"><font color=\"#ffffff\"><strong>Total</strong></font></td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_start$</td>\n		</tr>\n		<tr>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_sno$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-productname$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-quantity$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-listprice$</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$products-total$</td>\n		</tr>\n		<tr>\n			<td colspan=\"5\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">$productblock_end$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Items Total</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-subtotal$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Discount</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-discount_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Tax</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-tax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\"><span class=\"pull-right\">Shipping &amp; Handling Charges</span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-s_h_amount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Taxes For Shipping and Handling</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-shtax_totalamount$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Adjustment</td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-adjustment$</td>\n		</tr>\n		<tr>\n			<td colspan=\"4\" rowspan=\"1\" style=\"border-bottom:1px solid #ccc;padding:4mm;\">Grand Total<span><b>( in </b><b>$salesorder-currency_id$</b><b> )</b></span></td>\n			<td style=\"border-bottom:1px solid #ccc;padding:4mm;\">$pdt-total$</td>\n		</tr>\n	</tbody>\n</table>\n<br />\n',1,'YTo5OntzOjk6ImZpbGVfbmFtZSI7czoxMDoiU2FsZXNPcmRlciI7czoxMToicGFnZV9mb3JtYXQiO3M6MjoiQTQiO3M6MTY6InBhZ2Vfb3JpZW50YXRpb24iO3M6MToiUCI7czoxMDoibWFyZ2luX3RvcCI7czozOiIxMCUiO3M6MTM6Im1hcmdpbl9ib3R0b20iO3M6MzoiMTAlIjtzOjExOiJtYXJnaW5fbGVmdCI7czozOiIxMCUiO3M6MTI6Im1hcmdpbl9yaWdodCI7czozOiIxMCUiO3M6MTA6ImRldGFpbHZpZXciO3M6Mjoib24iO3M6ODoibGlzdHZpZXciO3M6Mjoib24iO30=','','##Page##');
/*!40000 ALTER TABLE `jo_pdfmaker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pdfmakersettings`
--

DROP TABLE IF EXISTS `jo_pdfmakersettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pdfmakersettings` (
  `id` int(19) NOT NULL,
  `version` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pdfmakersettings`
--

LOCK TABLES `jo_pdfmakersettings` WRITE;
/*!40000 ALTER TABLE `jo_pdfmakersettings` DISABLE KEYS */;
INSERT INTO `jo_pdfmakersettings` VALUES (1,'0.1');
/*!40000 ALTER TABLE `jo_pdfmakersettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_picklist`
--

DROP TABLE IF EXISTS `jo_picklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_picklist` (
  `picklistid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`picklistid`),
  UNIQUE KEY `picklist_name_idx` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_picklist`
--

LOCK TABLES `jo_picklist` WRITE;
/*!40000 ALTER TABLE `jo_picklist` DISABLE KEYS */;
INSERT INTO `jo_picklist` VALUES (1,'accounttype'),(2,'activitytype'),(3,'campaignstatus'),(4,'campaigntype'),(5,'carrier'),(41,'defaultactivitytype'),(40,'defaulteventstatus'),(6,'eventstatus'),(7,'expectedresponse'),(8,'glacct'),(9,'industry'),(10,'invoicestatus'),(11,'leadsource'),(12,'leadstatus'),(13,'manufacturer'),(14,'opportunity_type'),(15,'postatus'),(16,'productcategory'),(38,'progress'),(31,'projectmilestonetype'),(37,'projectpriority'),(35,'projectstatus'),(33,'projecttaskpriority'),(34,'projecttaskprogress'),(39,'projecttaskstatus'),(32,'projecttasktype'),(36,'projecttype'),(17,'quotestage'),(18,'rating'),(19,'sales_stage'),(20,'salutationtype'),(30,'servicecategory'),(29,'service_usageunit'),(21,'sostatus'),(22,'taskpriority'),(23,'taskstatus'),(24,'ticketcategories'),(25,'ticketpriorities'),(26,'ticketseverities'),(27,'ticketstatus'),(28,'usageunit');
/*!40000 ALTER TABLE `jo_picklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_picklist_dependency`
--

DROP TABLE IF EXISTS `jo_picklist_dependency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_picklist_dependency` (
  `id` int(11) NOT NULL,
  `tabid` int(19) NOT NULL,
  `sourcefield` varchar(255) DEFAULT NULL,
  `targetfield` varchar(255) DEFAULT NULL,
  `sourcevalue` varchar(100) DEFAULT NULL,
  `targetvalues` text,
  `criteria` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_picklist_dependency`
--

LOCK TABLES `jo_picklist_dependency` WRITE;
/*!40000 ALTER TABLE `jo_picklist_dependency` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_picklist_dependency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_picklist_transitions`
--

DROP TABLE IF EXISTS `jo_picklist_transitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_picklist_transitions` (
  `fieldname` varchar(255) NOT NULL,
  `module` varchar(100) NOT NULL,
  `transition_data` varchar(1000) NOT NULL,
  PRIMARY KEY (`fieldname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_picklist_transitions`
--

LOCK TABLES `jo_picklist_transitions` WRITE;
/*!40000 ALTER TABLE `jo_picklist_transitions` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_picklist_transitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pobillads`
--

DROP TABLE IF EXISTS `jo_pobillads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pobillads` (
  `pobilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`pobilladdressid`),
  CONSTRAINT `fk_1_jo_pobillads` FOREIGN KEY (`pobilladdressid`) REFERENCES `jo_purchaseorder` (`purchaseorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pobillads`
--

LOCK TABLES `jo_pobillads` WRITE;
/*!40000 ALTER TABLE `jo_pobillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pobillads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_portal`
--

DROP TABLE IF EXISTS `jo_portal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_portal` (
  `portalid` int(19) NOT NULL,
  `portalname` varchar(200) NOT NULL,
  `portalurl` varchar(255) NOT NULL,
  `sequence` int(3) NOT NULL,
  `setdefault` int(3) NOT NULL DEFAULT '0',
  `createdtime` datetime DEFAULT NULL,
  PRIMARY KEY (`portalid`),
  KEY `portal_portalname_idx` (`portalname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_portal`
--

LOCK TABLES `jo_portal` WRITE;
/*!40000 ALTER TABLE `jo_portal` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_portal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_portalinfo`
--

DROP TABLE IF EXISTS `jo_portalinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_portalinfo` (
  `id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `type` varchar(5) DEFAULT NULL,
  `cryptmode` varchar(20) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `isactive` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_1_jo_portalinfo` FOREIGN KEY (`id`) REFERENCES `jo_contactdetails` (`contactid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_portalinfo`
--

LOCK TABLES `jo_portalinfo` WRITE;
/*!40000 ALTER TABLE `jo_portalinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_portalinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_poshipads`
--

DROP TABLE IF EXISTS `jo_poshipads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_poshipads` (
  `poshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`poshipaddressid`),
  CONSTRAINT `fk_1_jo_poshipads` FOREIGN KEY (`poshipaddressid`) REFERENCES `jo_purchaseorder` (`purchaseorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_poshipads`
--

LOCK TABLES `jo_poshipads` WRITE;
/*!40000 ALTER TABLE `jo_poshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_poshipads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_postatus`
--

DROP TABLE IF EXISTS `jo_postatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_postatus` (
  `postatusid` int(19) NOT NULL AUTO_INCREMENT,
  `postatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`postatusid`),
  UNIQUE KEY `postatus_postatus_idx` (`postatus`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_postatus`
--

LOCK TABLES `jo_postatus` WRITE;
/*!40000 ALTER TABLE `jo_postatus` DISABLE KEYS */;
INSERT INTO `jo_postatus` VALUES (1,'Created',0,125,0,NULL),(2,'Approved',0,126,1,NULL),(3,'Delivered',0,127,2,NULL),(4,'Cancelled',0,128,3,NULL),(5,'Received Shipment',0,129,4,NULL);
/*!40000 ALTER TABLE `jo_postatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_postatushistory`
--

DROP TABLE IF EXISTS `jo_postatushistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_postatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `purchaseorderid` int(19) NOT NULL,
  `vendorname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `postatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `postatushistory_purchaseorderid_idx` (`purchaseorderid`),
  CONSTRAINT `fk_1_jo_postatushistory` FOREIGN KEY (`purchaseorderid`) REFERENCES `jo_purchaseorder` (`purchaseorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_postatushistory`
--

LOCK TABLES `jo_postatushistory` WRITE;
/*!40000 ALTER TABLE `jo_postatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_postatushistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_potential`
--

DROP TABLE IF EXISTS `jo_potential`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_potential` (
  `potentialid` int(19) NOT NULL DEFAULT '0',
  `potential_no` varchar(100) NOT NULL,
  `related_to` int(19) DEFAULT NULL,
  `potentialname` varchar(120) NOT NULL,
  `amount` decimal(25,8) DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `closingdate` date DEFAULT NULL,
  `typeofrevenue` varchar(50) DEFAULT NULL,
  `nextstep` varchar(100) DEFAULT NULL,
  `private` int(1) DEFAULT '0',
  `probability` decimal(7,3) DEFAULT '0.000',
  `campaignid` int(19) DEFAULT NULL,
  `sales_stage` varchar(200) DEFAULT NULL,
  `potentialtype` varchar(200) DEFAULT NULL,
  `leadsource` varchar(200) DEFAULT NULL,
  `productid` int(50) DEFAULT NULL,
  `productversion` varchar(50) DEFAULT NULL,
  `quotationref` varchar(50) DEFAULT NULL,
  `partnercontact` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `runtimefee` int(19) DEFAULT '0',
  `followupdate` date DEFAULT NULL,
  `evaluationstatus` varchar(50) DEFAULT NULL,
  `description` text,
  `forecastcategory` int(19) DEFAULT '0',
  `outcomeanalysis` int(19) DEFAULT '0',
  `forecast_amount` decimal(25,8) DEFAULT NULL,
  `isconvertedfromlead` varchar(3) DEFAULT '0',
  `contact_id` int(19) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `converted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`potentialid`),
  KEY `potential_relatedto_idx` (`related_to`),
  KEY `potentail_sales_stage_idx` (`sales_stage`),
  KEY `potentail_sales_stage_amount_idx` (`amount`,`sales_stage`),
  CONSTRAINT `fk_1_jo_potential` FOREIGN KEY (`potentialid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_potential`
--

LOCK TABLES `jo_potential` WRITE;
/*!40000 ALTER TABLE `jo_potential` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_potential` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_potentialscf`
--

DROP TABLE IF EXISTS `jo_potentialscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_potentialscf` (
  `potentialid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`potentialid`),
  CONSTRAINT `fk_1_jo_potentialscf` FOREIGN KEY (`potentialid`) REFERENCES `jo_potential` (`potentialid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_potentialscf`
--

LOCK TABLES `jo_potentialscf` WRITE;
/*!40000 ALTER TABLE `jo_potentialscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_potentialscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_potstagehistory`
--

DROP TABLE IF EXISTS `jo_potstagehistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_potstagehistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `potentialid` int(19) NOT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `stage` varchar(100) DEFAULT NULL,
  `probability` decimal(7,3) DEFAULT NULL,
  `expectedrevenue` decimal(10,0) DEFAULT NULL,
  `closedate` date DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `potstagehistory_potentialid_idx` (`potentialid`),
  CONSTRAINT `fk_1_jo_potstagehistory` FOREIGN KEY (`potentialid`) REFERENCES `jo_potential` (`potentialid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_potstagehistory`
--

LOCK TABLES `jo_potstagehistory` WRITE;
/*!40000 ALTER TABLE `jo_potstagehistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_potstagehistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pricebook`
--

DROP TABLE IF EXISTS `jo_pricebook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pricebook` (
  `pricebookid` int(19) NOT NULL DEFAULT '0',
  `pricebook_no` varchar(100) NOT NULL,
  `bookname` varchar(100) DEFAULT NULL,
  `active` int(1) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`pricebookid`),
  CONSTRAINT `fk_1_jo_pricebook` FOREIGN KEY (`pricebookid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pricebook`
--

LOCK TABLES `jo_pricebook` WRITE;
/*!40000 ALTER TABLE `jo_pricebook` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pricebook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pricebookcf`
--

DROP TABLE IF EXISTS `jo_pricebookcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pricebookcf` (
  `pricebookid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pricebookid`),
  CONSTRAINT `fk_1_jo_pricebookcf` FOREIGN KEY (`pricebookid`) REFERENCES `jo_pricebook` (`pricebookid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pricebookcf`
--

LOCK TABLES `jo_pricebookcf` WRITE;
/*!40000 ALTER TABLE `jo_pricebookcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pricebookcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_pricebookproductrel`
--

DROP TABLE IF EXISTS `jo_pricebookproductrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_pricebookproductrel` (
  `pricebookid` int(19) NOT NULL,
  `productid` int(19) NOT NULL,
  `listprice` decimal(27,8) DEFAULT NULL,
  `usedcurrency` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`pricebookid`,`productid`),
  KEY `pricebookproductrel_pricebookid_idx` (`pricebookid`),
  KEY `pricebookproductrel_productid_idx` (`productid`),
  CONSTRAINT `fk_1_jo_pricebookproductrel` FOREIGN KEY (`pricebookid`) REFERENCES `jo_pricebook` (`pricebookid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_pricebookproductrel`
--

LOCK TABLES `jo_pricebookproductrel` WRITE;
/*!40000 ALTER TABLE `jo_pricebookproductrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_pricebookproductrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_priority`
--

DROP TABLE IF EXISTS `jo_priority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_priority` (
  `priorityid` int(19) NOT NULL AUTO_INCREMENT,
  `priority` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`priorityid`),
  UNIQUE KEY `priority_priority_idx` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_priority`
--

LOCK TABLES `jo_priority` WRITE;
/*!40000 ALTER TABLE `jo_priority` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_priority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_productcategory`
--

DROP TABLE IF EXISTS `jo_productcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_productcategory` (
  `productcategoryid` int(19) NOT NULL AUTO_INCREMENT,
  `productcategory` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`productcategoryid`),
  UNIQUE KEY `productcategory_productcategory_idx` (`productcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_productcategory`
--

LOCK TABLES `jo_productcategory` WRITE;
/*!40000 ALTER TABLE `jo_productcategory` DISABLE KEYS */;
INSERT INTO `jo_productcategory` VALUES (2,'Hardware',1,131,1,NULL),(3,'Software',1,132,2,NULL),(4,'CRM Applications',1,133,3,NULL);
/*!40000 ALTER TABLE `jo_productcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_productcf`
--

DROP TABLE IF EXISTS `jo_productcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_productcf` (
  `productid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`productid`),
  CONSTRAINT `fk_1_jo_productcf` FOREIGN KEY (`productid`) REFERENCES `jo_products` (`productid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_productcf`
--

LOCK TABLES `jo_productcf` WRITE;
/*!40000 ALTER TABLE `jo_productcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_productcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_productcurrencyrel`
--

DROP TABLE IF EXISTS `jo_productcurrencyrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_productcurrencyrel` (
  `productid` int(11) NOT NULL,
  `currencyid` int(11) NOT NULL,
  `converted_price` decimal(28,8) DEFAULT NULL,
  `actual_price` decimal(28,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_productcurrencyrel`
--

LOCK TABLES `jo_productcurrencyrel` WRITE;
/*!40000 ALTER TABLE `jo_productcurrencyrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_productcurrencyrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_products`
--

DROP TABLE IF EXISTS `jo_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_products` (
  `productid` int(11) NOT NULL,
  `product_no` varchar(100) NOT NULL,
  `productname` varchar(100) DEFAULT NULL,
  `productcode` varchar(40) DEFAULT NULL,
  `productcategory` varchar(200) DEFAULT NULL,
  `manufacturer` varchar(200) DEFAULT NULL,
  `qty_per_unit` decimal(11,2) DEFAULT '0.00',
  `unit_price` decimal(25,8) DEFAULT NULL,
  `weight` decimal(11,3) DEFAULT NULL,
  `pack_size` int(11) DEFAULT NULL,
  `sales_start_date` date DEFAULT NULL,
  `sales_end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cost_factor` int(11) DEFAULT NULL,
  `commissionrate` decimal(7,3) DEFAULT NULL,
  `commissionmethod` varchar(50) DEFAULT NULL,
  `discontinued` int(1) NOT NULL DEFAULT '0',
  `usageunit` varchar(200) DEFAULT NULL,
  `reorderlevel` int(11) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `taxclass` varchar(200) DEFAULT NULL,
  `mfr_part_no` varchar(200) DEFAULT NULL,
  `vendor_part_no` varchar(200) DEFAULT NULL,
  `serialno` varchar(200) DEFAULT NULL,
  `qtyinstock` decimal(25,3) DEFAULT NULL,
  `productsheet` varchar(200) DEFAULT NULL,
  `qtyindemand` int(11) DEFAULT NULL,
  `glacct` varchar(200) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `imagename` text,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `is_subproducts_viewable` int(1) DEFAULT '1',
  `purchase_cost` decimal(27,8) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`productid`),
  CONSTRAINT `fk_1_jo_products` FOREIGN KEY (`productid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_products`
--

LOCK TABLES `jo_products` WRITE;
/*!40000 ALTER TABLE `jo_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_producttaxrel`
--

DROP TABLE IF EXISTS `jo_producttaxrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_producttaxrel` (
  `productid` int(11) NOT NULL,
  `taxid` int(3) NOT NULL,
  `taxpercentage` decimal(7,3) DEFAULT NULL,
  `regions` text,
  KEY `producttaxrel_productid_idx` (`productid`),
  KEY `producttaxrel_taxid_idx` (`taxid`),
  CONSTRAINT `fk_crmid_jo_producttaxrel` FOREIGN KEY (`productid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_producttaxrel`
--

LOCK TABLES `jo_producttaxrel` WRITE;
/*!40000 ALTER TABLE `jo_producttaxrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_producttaxrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile`
--

DROP TABLE IF EXISTS `jo_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile` (
  `profileid` int(10) NOT NULL AUTO_INCREMENT,
  `profilename` varchar(50) NOT NULL,
  `description` text,
  `directly_related_to_role` int(1) DEFAULT '0',
  PRIMARY KEY (`profileid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile`
--

LOCK TABLES `jo_profile` WRITE;
/*!40000 ALTER TABLE `jo_profile` DISABLE KEYS */;
INSERT INTO `jo_profile` VALUES (1,'Administrator','Admin Profile',0),(2,'Sales Profile','Profile Related to Sales',0),(3,'Support Profile','Profile Related to Support',0),(4,'Guest Profile','Guest Profile for Test Users',0),(5,'Masquerade User Profile','Profile for Masquerade User',0),(6,'Masquerade User+Profile',NULL,1),(7,'Sales Person+Profile',NULL,1),(8,'Sales Manager+Profile',NULL,1),(9,'Vice President+Profile',NULL,1);
/*!40000 ALTER TABLE `jo_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile2field`
--

DROP TABLE IF EXISTS `jo_profile2field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile2field` (
  `profileid` int(11) NOT NULL,
  `tabid` int(10) DEFAULT NULL,
  `fieldid` int(19) NOT NULL,
  `visible` int(19) DEFAULT NULL,
  `readonly` int(19) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`fieldid`),
  KEY `profile2field_profileid_tabid_fieldname_idx` (`profileid`,`tabid`),
  KEY `profile2field_tabid_profileid_idx` (`tabid`,`profileid`),
  KEY `profile2field_visible_profileid_idx` (`visible`,`profileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile2field`
--

LOCK TABLES `jo_profile2field` WRITE;
/*!40000 ALTER TABLE `jo_profile2field` DISABLE KEYS */;
INSERT INTO `jo_profile2field` VALUES (1,6,1,0,0),(1,6,2,0,0),(1,6,3,0,0),(1,6,4,0,0),(1,6,5,0,0),(1,6,6,0,0),(1,6,7,0,0),(1,6,8,0,0),(1,6,9,0,0),(1,6,10,0,0),(1,6,11,0,0),(1,6,12,0,0),(1,6,13,0,0),(1,6,14,0,0),(1,6,15,0,0),(1,6,16,0,0),(1,6,17,0,0),(1,6,18,0,0),(1,6,19,0,0),(1,6,20,0,0),(1,6,21,0,0),(1,6,22,0,0),(1,6,23,0,0),(1,6,24,0,0),(1,6,25,0,0),(1,6,26,0,0),(1,6,27,0,0),(1,6,28,0,0),(1,6,29,0,0),(1,6,30,0,0),(1,6,31,0,0),(1,6,32,0,0),(1,6,33,0,0),(1,6,34,0,0),(1,6,35,0,0),(1,6,36,0,0),(1,7,37,0,0),(1,7,38,0,0),(1,7,39,0,0),(1,7,40,0,0),(1,7,41,0,0),(1,7,42,0,0),(1,7,43,0,0),(1,7,44,0,0),(1,7,45,0,0),(1,7,46,0,0),(1,7,47,0,0),(1,7,48,0,0),(1,7,49,0,0),(1,7,50,0,0),(1,7,51,0,0),(1,7,52,0,0),(1,7,53,0,0),(1,7,54,0,0),(1,7,55,0,0),(1,7,56,0,0),(1,7,57,0,0),(1,7,58,0,0),(1,7,59,0,0),(1,7,60,0,0),(1,7,61,0,0),(1,7,62,0,0),(1,7,63,0,0),(1,7,64,0,0),(1,7,65,0,0),(1,4,66,0,0),(1,4,67,0,0),(1,4,68,0,0),(1,4,69,0,0),(1,4,70,0,0),(1,4,71,0,0),(1,4,72,0,0),(1,4,73,0,0),(1,4,74,0,0),(1,4,75,0,0),(1,4,76,0,0),(1,4,77,0,0),(1,4,78,0,0),(1,4,79,0,0),(1,4,80,0,0),(1,4,81,0,0),(1,4,82,0,0),(1,4,83,0,0),(1,4,84,0,0),(1,4,85,0,0),(1,4,86,0,0),(1,4,87,0,0),(1,4,88,0,0),(1,4,89,0,0),(1,4,90,0,0),(1,4,91,0,0),(1,4,92,0,0),(1,4,93,0,0),(1,4,94,0,0),(1,4,95,0,0),(1,4,96,0,0),(1,4,97,0,0),(1,4,98,0,0),(1,4,99,0,0),(1,4,100,0,0),(1,4,101,0,0),(1,4,102,0,0),(1,4,103,0,0),(1,4,104,0,0),(1,4,105,0,0),(1,4,106,0,0),(1,4,107,0,0),(1,4,108,0,0),(1,4,109,0,0),(1,2,110,0,0),(1,2,111,0,0),(1,2,112,0,0),(1,2,113,0,0),(1,2,114,0,0),(1,2,115,0,0),(1,2,116,0,0),(1,2,117,0,0),(1,2,118,0,0),(1,2,119,0,0),(1,2,120,0,0),(1,2,121,0,0),(1,2,122,0,0),(1,2,123,0,0),(1,2,124,0,0),(1,2,125,0,0),(1,26,126,0,0),(1,26,127,0,0),(1,26,128,0,0),(1,26,129,0,0),(1,26,130,0,0),(1,26,131,0,0),(1,26,132,0,0),(1,26,133,0,0),(1,26,134,0,0),(1,26,135,0,0),(1,26,136,0,0),(1,26,137,0,0),(1,26,138,0,0),(1,26,139,0,0),(1,26,140,0,0),(1,26,141,0,0),(1,26,142,0,0),(1,26,143,0,0),(1,26,144,0,0),(1,26,145,0,0),(1,26,146,0,0),(1,26,147,0,0),(1,26,148,0,0),(1,26,149,0,0),(1,26,150,0,0),(1,4,151,0,0),(1,6,152,0,0),(1,7,153,0,0),(1,26,154,0,0),(1,13,155,0,0),(1,13,156,0,0),(1,13,157,0,0),(1,13,158,0,0),(1,13,159,0,0),(1,13,160,0,0),(1,13,161,0,0),(1,13,162,0,0),(1,13,163,0,0),(1,13,164,0,0),(1,13,165,0,0),(1,13,166,0,0),(1,13,167,0,0),(1,13,168,0,0),(1,13,169,0,0),(1,13,170,0,0),(1,13,171,0,0),(1,13,172,0,0),(1,13,173,0,0),(1,14,174,0,0),(1,14,175,0,0),(1,14,176,0,0),(1,14,177,0,0),(1,14,178,0,0),(1,14,179,0,0),(1,14,180,0,0),(1,14,181,0,0),(1,14,182,0,0),(1,14,183,0,0),(1,14,184,0,0),(1,14,185,0,0),(1,14,186,0,0),(1,14,187,0,0),(1,14,188,0,0),(1,14,189,0,0),(1,14,190,0,0),(1,14,191,0,0),(1,14,192,0,0),(1,14,193,0,0),(1,14,194,0,0),(1,14,195,0,0),(1,14,196,0,0),(1,14,197,0,0),(1,14,198,0,0),(1,14,199,0,0),(1,14,200,0,0),(1,14,201,0,0),(1,14,202,0,0),(1,14,203,0,0),(1,14,204,0,0),(1,8,205,0,0),(1,8,206,0,0),(1,8,207,0,0),(1,8,208,0,0),(1,8,209,0,0),(1,8,210,0,0),(1,8,211,0,0),(1,8,212,0,0),(1,8,213,0,0),(1,8,214,0,0),(1,8,215,0,0),(1,8,216,0,0),(1,8,217,0,0),(1,8,218,0,0),(1,8,219,0,0),(1,10,220,0,0),(1,10,221,0,0),(1,10,222,0,0),(1,10,223,0,0),(1,10,224,0,0),(1,10,225,0,0),(1,10,226,0,0),(1,10,227,0,0),(1,10,228,0,0),(1,10,229,0,0),(1,10,230,0,0),(1,10,231,0,0),(1,9,232,0,0),(1,9,233,0,0),(1,9,234,0,0),(1,9,235,0,0),(1,9,236,0,0),(1,9,237,0,0),(1,9,238,0,0),(1,9,239,0,0),(1,9,240,0,0),(1,9,241,0,0),(1,9,242,0,0),(1,9,243,0,0),(1,9,244,0,0),(1,9,245,0,0),(1,9,246,0,0),(1,9,247,0,0),(1,9,248,0,0),(1,9,249,0,0),(1,9,250,0,0),(1,9,251,0,0),(1,9,252,0,0),(1,9,253,0,0),(1,9,254,0,0),(1,9,255,0,0),(1,16,256,0,0),(1,16,257,0,0),(1,16,258,0,0),(1,16,259,0,0),(1,16,260,0,0),(1,16,261,0,0),(1,16,262,0,0),(1,16,263,0,0),(1,16,264,0,0),(1,16,265,0,0),(1,16,266,0,0),(1,16,267,0,0),(1,16,268,0,0),(1,16,269,0,0),(1,16,270,0,0),(1,16,271,0,0),(1,16,272,0,0),(1,16,273,0,0),(1,16,274,0,0),(1,16,275,0,0),(1,16,276,0,0),(1,16,277,0,0),(1,16,278,0,0),(1,18,279,0,0),(1,18,280,0,0),(1,18,281,0,0),(1,18,282,0,0),(1,18,283,0,0),(1,18,284,0,0),(1,18,285,0,0),(1,18,286,0,0),(1,18,287,0,0),(1,18,288,0,0),(1,18,289,0,0),(1,18,290,0,0),(1,18,291,0,0),(1,18,292,0,0),(1,18,293,0,0),(1,18,294,0,0),(1,18,295,0,0),(1,19,296,0,0),(1,19,297,0,0),(1,19,298,0,0),(1,19,299,0,0),(1,19,300,0,0),(1,19,301,0,0),(1,19,302,0,0),(1,19,303,0,0),(1,20,304,0,0),(1,20,305,0,0),(1,20,306,0,0),(1,20,307,0,0),(1,20,308,0,0),(1,20,309,0,0),(1,20,310,0,0),(1,20,311,0,0),(1,20,312,0,0),(1,20,313,0,0),(1,20,314,0,0),(1,20,315,0,0),(1,20,316,0,0),(1,20,317,0,0),(1,20,318,0,0),(1,20,319,0,0),(1,20,320,0,0),(1,20,321,0,0),(1,20,322,0,0),(1,20,323,0,0),(1,20,324,0,0),(1,20,325,0,0),(1,20,326,0,0),(1,20,327,0,0),(1,20,328,0,0),(1,20,329,0,0),(1,20,330,0,0),(1,20,331,0,0),(1,20,332,0,0),(1,20,333,0,0),(1,20,334,0,0),(1,20,335,0,0),(1,20,336,0,0),(1,20,337,0,0),(1,20,338,0,0),(1,20,339,0,0),(1,20,340,0,0),(1,21,341,0,0),(1,21,342,0,0),(1,21,343,0,0),(1,21,344,0,0),(1,21,345,0,0),(1,21,346,0,0),(1,21,347,0,0),(1,21,348,0,0),(1,21,349,0,0),(1,21,350,0,0),(1,21,351,0,0),(1,21,352,0,0),(1,21,353,0,0),(1,21,354,0,0),(1,21,355,0,0),(1,21,356,0,0),(1,21,357,0,0),(1,21,358,0,0),(1,21,359,0,0),(1,21,360,0,0),(1,21,361,0,0),(1,21,362,0,0),(1,21,363,0,0),(1,21,364,0,0),(1,21,365,0,0),(1,21,366,0,0),(1,21,367,0,0),(1,21,368,0,0),(1,21,369,0,0),(1,21,370,0,0),(1,21,371,0,0),(1,21,372,0,0),(1,21,373,0,0),(1,21,374,0,0),(1,21,375,0,0),(1,21,376,0,0),(1,21,377,0,0),(1,21,378,0,0),(1,22,379,0,0),(1,22,380,0,0),(1,22,381,0,0),(1,22,382,0,0),(1,22,383,0,0),(1,22,384,0,0),(1,22,385,0,0),(1,22,386,0,0),(1,22,387,0,0),(1,22,388,0,0),(1,22,389,0,0),(1,22,390,0,0),(1,22,391,0,0),(1,22,392,0,0),(1,22,393,0,0),(1,22,394,0,0),(1,22,395,0,0),(1,22,396,0,0),(1,22,397,0,0),(1,22,398,0,0),(1,22,399,0,0),(1,22,400,0,0),(1,22,401,0,0),(1,22,402,0,0),(1,22,403,0,0),(1,22,404,0,0),(1,22,405,0,0),(1,22,406,0,0),(1,22,407,0,0),(1,22,408,0,0),(1,22,409,0,0),(1,22,410,0,0),(1,22,411,0,0),(1,22,412,0,0),(1,22,413,0,0),(1,22,414,0,0),(1,22,415,0,0),(1,22,416,0,0),(1,22,417,0,0),(1,22,418,0,0),(1,22,419,0,0),(1,22,420,0,0),(1,22,421,0,0),(1,22,422,0,0),(1,22,423,0,0),(1,22,424,0,0),(1,22,425,0,0),(1,23,426,0,0),(1,23,427,0,0),(1,23,428,0,0),(1,23,429,0,0),(1,23,430,0,0),(1,23,431,0,0),(1,23,432,0,0),(1,23,433,0,0),(1,23,434,0,0),(1,23,435,0,0),(1,23,436,0,0),(1,23,437,0,0),(1,23,438,0,0),(1,23,439,0,0),(1,23,440,0,0),(1,23,441,0,0),(1,23,442,0,0),(1,23,443,0,0),(1,23,444,0,0),(1,23,445,0,0),(1,23,446,0,0),(1,23,447,0,0),(1,23,448,0,0),(1,23,449,0,0),(1,23,450,0,0),(1,23,451,0,0),(1,23,452,0,0),(1,23,453,0,0),(1,23,454,0,0),(1,23,455,0,0),(1,23,456,0,0),(1,23,457,0,0),(1,23,458,0,0),(1,23,459,0,0),(1,23,460,0,0),(1,23,461,0,0),(1,23,462,0,0),(1,23,463,0,0),(1,23,464,0,0),(1,29,465,0,0),(1,29,469,0,0),(1,29,470,0,0),(1,29,472,0,0),(1,29,479,0,0),(1,29,480,0,0),(1,29,481,0,0),(1,29,482,0,0),(1,29,483,0,0),(1,29,485,0,0),(1,29,486,0,0),(1,29,487,0,0),(1,29,488,0,0),(1,29,489,0,0),(1,29,494,0,0),(1,29,495,0,0),(1,29,496,0,0),(1,29,497,0,0),(1,29,505,0,0),(1,10,512,0,0),(1,10,513,0,0),(1,10,514,0,0),(1,10,515,0,0),(1,10,516,0,0),(1,10,517,0,0),(1,36,518,0,0),(1,36,519,0,0),(1,36,520,0,0),(1,36,521,0,0),(1,36,522,0,0),(1,36,523,0,0),(1,36,524,0,0),(1,36,525,0,0),(1,36,526,0,0),(1,36,527,0,0),(1,36,528,0,0),(1,36,529,0,0),(1,36,530,0,0),(1,36,531,0,0),(1,36,532,0,0),(1,36,533,0,0),(1,36,534,0,0),(1,36,535,0,0),(1,36,536,0,0),(1,37,537,0,0),(1,37,538,0,0),(1,37,539,0,0),(1,37,540,0,0),(1,37,541,0,0),(1,37,542,0,0),(1,37,543,0,0),(1,37,544,0,0),(1,37,545,0,0),(1,37,546,0,0),(1,37,547,0,0),(1,37,548,0,0),(1,37,549,0,0),(1,37,550,0,0),(1,37,551,0,0),(1,37,552,0,0),(1,29,553,0,0),(1,42,554,0,0),(1,42,555,0,0),(1,42,556,0,0),(1,42,557,0,0),(1,42,558,0,0),(1,42,559,0,0),(1,42,560,0,0),(1,42,561,0,0),(1,42,562,0,0),(1,42,563,0,0),(1,43,564,0,0),(1,43,565,0,0),(1,43,566,0,0),(1,43,567,0,0),(1,43,568,0,0),(1,43,569,0,0),(1,43,570,0,0),(1,43,571,0,0),(1,43,572,0,0),(1,43,573,0,0),(1,43,574,0,0),(1,43,575,0,0),(1,43,576,0,0),(1,43,577,0,0),(1,43,578,0,0),(1,44,579,0,0),(1,44,580,0,0),(1,44,581,0,0),(1,44,582,0,0),(1,44,583,0,0),(1,44,584,0,0),(1,44,585,0,0),(1,44,586,0,0),(1,44,587,0,0),(1,44,588,0,0),(1,44,589,0,0),(1,44,590,0,0),(1,44,591,0,0),(1,44,592,0,0),(1,44,593,0,0),(1,44,594,0,0),(1,44,595,0,0),(1,47,596,0,0),(1,47,597,0,0),(1,47,598,0,0),(1,47,599,0,0),(1,47,600,0,0),(1,47,601,0,0),(1,47,602,0,0),(1,2,603,0,0),(1,29,604,0,0),(1,23,605,0,0),(1,23,606,0,0),(1,23,607,0,0),(1,23,608,0,0),(1,23,609,0,0),(1,23,610,0,0),(1,23,611,0,0),(1,23,612,0,0),(1,23,613,0,0),(1,22,614,0,0),(1,22,615,0,0),(1,22,616,0,0),(1,22,617,0,0),(1,22,618,0,0),(1,22,619,0,0),(1,22,620,0,0),(1,22,621,0,0),(1,22,622,0,0),(1,21,623,0,0),(1,21,624,0,0),(1,21,625,0,0),(1,21,626,0,0),(1,21,627,0,0),(1,21,628,0,0),(1,21,629,0,0),(1,21,630,0,0),(1,21,631,0,0),(1,20,632,0,0),(1,20,633,0,0),(1,20,634,0,0),(1,20,635,0,0),(1,20,636,0,0),(1,20,637,0,0),(1,20,638,0,0),(1,20,639,0,0),(1,20,640,0,0),(1,29,641,0,0),(1,29,644,0,0),(1,29,645,0,0),(1,29,646,0,0),(1,23,647,0,0),(1,22,648,0,0),(1,21,649,0,0),(1,20,650,0,0),(1,29,651,0,0),(1,6,652,0,0),(1,4,653,0,0),(1,2,654,0,0),(1,29,655,0,0),(1,23,656,0,0),(1,23,657,0,0),(1,21,658,0,0),(1,21,659,0,0),(1,7,660,0,0),(1,23,663,0,0),(1,20,664,0,0),(1,21,665,0,0),(1,22,666,0,0),(1,29,667,0,0),(1,2,668,0,0),(1,13,669,0,0),(1,29,670,0,0),(1,29,671,0,0),(1,29,672,0,0),(1,29,673,0,0),(1,14,696,0,0),(1,23,698,0,0),(1,29,699,0,0),(1,23,700,0,0),(1,23,701,0,0),(1,23,702,0,0),(1,20,703,0,0),(1,20,704,0,0),(1,20,705,0,0),(1,21,706,0,0),(1,22,707,0,0),(1,22,708,0,0),(1,22,709,0,0),(1,2,713,0,0),(1,4,714,0,0),(1,6,715,0,0),(1,7,716,0,0),(1,8,717,0,0),(1,9,718,0,0),(1,10,719,0,0),(1,13,720,0,0),(1,14,721,0,0),(1,16,722,0,0),(1,18,723,0,0),(1,19,724,0,0),(1,20,725,0,0),(1,21,726,0,0),(1,22,727,0,0),(1,23,728,0,0),(1,26,729,0,0),(1,10,735,0,0),(1,2,736,0,0),(1,4,737,0,0),(1,6,738,0,0),(1,7,739,0,0),(1,8,740,0,0),(1,9,741,0,0),(1,10,742,0,0),(1,13,743,0,0),(1,14,744,0,0),(1,16,745,0,0),(1,18,746,0,0),(1,19,747,0,0),(1,20,748,0,0),(1,21,749,0,0),(1,22,750,0,0),(1,23,751,0,0),(1,26,752,0,0),(1,2,758,0,0),(1,4,759,0,0),(1,6,760,0,0),(1,7,761,0,0),(1,8,762,0,0),(1,9,763,0,0),(1,10,764,0,0),(1,13,765,0,0),(1,14,766,0,0),(1,16,767,0,0),(1,18,768,0,0),(1,19,769,0,0),(1,20,770,0,0),(1,21,771,0,0),(1,22,772,0,0),(1,23,773,0,0),(1,26,774,0,0),(1,20,780,0,0),(1,21,781,0,0),(1,22,782,0,0),(1,23,783,0,0),(1,42,800,0,0),(1,42,801,0,0),(1,42,802,0,0),(2,6,1,0,0),(2,6,2,0,0),(2,6,3,0,0),(2,6,4,0,0),(2,6,5,0,0),(2,6,6,0,0),(2,6,7,0,0),(2,6,8,0,0),(2,6,9,0,0),(2,6,10,0,0),(2,6,11,0,0),(2,6,12,0,0),(2,6,13,0,0),(2,6,14,0,0),(2,6,15,0,0),(2,6,16,0,0),(2,6,17,0,0),(2,6,18,0,0),(2,6,19,0,0),(2,6,20,0,0),(2,6,21,0,0),(2,6,22,0,0),(2,6,23,0,0),(2,6,24,0,0),(2,6,25,0,0),(2,6,26,0,0),(2,6,27,0,0),(2,6,28,0,0),(2,6,29,0,0),(2,6,30,0,0),(2,6,31,0,0),(2,6,32,0,0),(2,6,33,0,0),(2,6,34,0,0),(2,6,35,0,0),(2,6,36,0,0),(2,7,37,0,0),(2,7,38,0,0),(2,7,39,0,0),(2,7,40,0,0),(2,7,41,0,0),(2,7,42,0,0),(2,7,43,0,0),(2,7,44,0,0),(2,7,45,0,0),(2,7,46,0,0),(2,7,47,0,0),(2,7,48,0,0),(2,7,49,0,0),(2,7,50,0,0),(2,7,51,0,0),(2,7,52,0,0),(2,7,53,0,0),(2,7,54,0,0),(2,7,55,0,0),(2,7,56,0,0),(2,7,57,0,0),(2,7,58,0,0),(2,7,59,0,0),(2,7,60,0,0),(2,7,61,0,0),(2,7,62,0,0),(2,7,63,0,0),(2,7,64,0,0),(2,7,65,0,0),(2,4,66,0,0),(2,4,67,0,0),(2,4,68,0,0),(2,4,69,0,0),(2,4,70,0,0),(2,4,71,0,0),(2,4,72,0,0),(2,4,73,0,0),(2,4,74,0,0),(2,4,75,0,0),(2,4,76,0,0),(2,4,77,0,0),(2,4,78,0,0),(2,4,79,0,0),(2,4,80,0,0),(2,4,81,0,0),(2,4,82,0,0),(2,4,83,0,0),(2,4,84,0,0),(2,4,85,0,0),(2,4,86,0,0),(2,4,87,0,0),(2,4,88,0,0),(2,4,89,0,0),(2,4,90,0,0),(2,4,91,0,0),(2,4,92,0,0),(2,4,93,0,0),(2,4,94,0,0),(2,4,95,0,0),(2,4,96,0,0),(2,4,97,0,0),(2,4,98,0,0),(2,4,99,0,0),(2,4,100,0,0),(2,4,101,0,0),(2,4,102,0,0),(2,4,103,0,0),(2,4,104,0,0),(2,4,105,0,0),(2,4,106,0,0),(2,4,107,0,0),(2,4,108,0,0),(2,4,109,0,0),(2,2,110,0,0),(2,2,111,0,0),(2,2,112,0,0),(2,2,113,0,0),(2,2,114,0,0),(2,2,115,0,0),(2,2,116,0,0),(2,2,117,0,0),(2,2,118,0,0),(2,2,119,0,0),(2,2,120,0,0),(2,2,121,0,0),(2,2,122,0,0),(2,2,123,0,0),(2,2,124,0,0),(2,2,125,0,0),(2,26,126,0,0),(2,26,127,0,0),(2,26,128,0,0),(2,26,129,0,0),(2,26,130,0,0),(2,26,131,0,0),(2,26,132,0,0),(2,26,133,0,0),(2,26,134,0,0),(2,26,135,0,0),(2,26,136,0,0),(2,26,137,0,0),(2,26,138,0,0),(2,26,139,0,0),(2,26,140,0,0),(2,26,141,0,0),(2,26,142,0,0),(2,26,143,0,0),(2,26,144,0,0),(2,26,145,0,0),(2,26,146,0,0),(2,26,147,0,0),(2,26,148,0,0),(2,26,149,0,0),(2,26,150,0,0),(2,4,151,0,0),(2,6,152,0,0),(2,7,153,0,0),(2,26,154,0,0),(2,13,155,0,0),(2,13,156,0,0),(2,13,157,0,0),(2,13,158,0,0),(2,13,159,0,0),(2,13,160,0,0),(2,13,161,0,0),(2,13,162,0,0),(2,13,163,0,0),(2,13,164,0,0),(2,13,165,0,0),(2,13,166,0,0),(2,13,167,0,0),(2,13,168,0,0),(2,13,169,0,0),(2,13,170,0,0),(2,13,171,0,0),(2,13,172,0,0),(2,13,173,0,0),(2,14,174,0,0),(2,14,175,0,0),(2,14,176,0,0),(2,14,177,0,0),(2,14,178,0,0),(2,14,179,0,0),(2,14,180,0,0),(2,14,181,0,0),(2,14,182,0,0),(2,14,183,0,0),(2,14,184,0,0),(2,14,185,0,0),(2,14,186,0,0),(2,14,187,0,0),(2,14,188,0,0),(2,14,189,0,0),(2,14,190,0,0),(2,14,191,0,0),(2,14,192,0,0),(2,14,193,0,0),(2,14,194,0,0),(2,14,195,0,0),(2,14,196,0,0),(2,14,197,0,0),(2,14,198,0,0),(2,14,199,0,0),(2,14,200,0,0),(2,14,201,0,0),(2,14,202,0,0),(2,14,203,0,0),(2,14,204,0,0),(2,8,205,0,0),(2,8,206,0,0),(2,8,207,0,0),(2,8,208,0,0),(2,8,209,0,0),(2,8,210,0,0),(2,8,211,0,0),(2,8,212,0,0),(2,8,213,0,0),(2,8,214,0,0),(2,8,215,0,0),(2,8,216,0,0),(2,8,217,0,0),(2,8,218,0,0),(2,8,219,0,0),(2,10,220,0,0),(2,10,221,0,0),(2,10,222,0,0),(2,10,223,0,0),(2,10,224,0,0),(2,10,225,0,0),(2,10,226,0,0),(2,10,227,0,0),(2,10,228,0,0),(2,10,229,0,0),(2,10,230,0,0),(2,10,231,0,0),(2,9,232,0,0),(2,9,233,0,0),(2,9,234,0,0),(2,9,235,0,0),(2,9,236,0,0),(2,9,237,0,0),(2,9,238,0,0),(2,9,239,0,0),(2,9,240,0,0),(2,9,241,0,0),(2,9,242,0,0),(2,9,243,0,0),(2,9,244,0,0),(2,9,245,0,0),(2,9,246,0,0),(2,9,247,0,0),(2,9,248,0,0),(2,9,249,0,0),(2,9,250,0,0),(2,9,251,0,0),(2,9,252,0,0),(2,9,253,0,0),(2,9,254,0,0),(2,9,255,0,0),(2,16,256,0,0),(2,16,257,0,0),(2,16,258,0,0),(2,16,259,0,0),(2,16,260,0,0),(2,16,261,0,0),(2,16,262,0,0),(2,16,263,0,0),(2,16,264,0,0),(2,16,265,0,0),(2,16,266,0,0),(2,16,267,0,0),(2,16,268,0,0),(2,16,269,0,0),(2,16,270,0,0),(2,16,271,0,0),(2,16,272,0,0),(2,16,273,0,0),(2,16,274,0,0),(2,16,275,0,0),(2,16,276,0,0),(2,16,277,0,0),(2,16,278,0,0),(2,18,279,0,0),(2,18,280,0,0),(2,18,281,0,0),(2,18,282,0,0),(2,18,283,0,0),(2,18,284,0,0),(2,18,285,0,0),(2,18,286,0,0),(2,18,287,0,0),(2,18,288,0,0),(2,18,289,0,0),(2,18,290,0,0),(2,18,291,0,0),(2,18,292,0,0),(2,18,293,0,0),(2,18,294,0,0),(2,18,295,0,0),(2,19,296,0,0),(2,19,297,0,0),(2,19,298,0,0),(2,19,299,0,0),(2,19,300,0,0),(2,19,301,0,0),(2,19,302,0,0),(2,19,303,0,0),(2,20,304,0,0),(2,20,305,0,0),(2,20,306,0,0),(2,20,307,0,0),(2,20,308,0,0),(2,20,309,0,0),(2,20,310,0,0),(2,20,311,0,0),(2,20,312,0,0),(2,20,313,0,0),(2,20,314,0,0),(2,20,315,0,0),(2,20,316,0,0),(2,20,317,0,0),(2,20,318,0,0),(2,20,319,0,0),(2,20,320,0,0),(2,20,321,0,0),(2,20,322,0,0),(2,20,323,0,0),(2,20,324,0,0),(2,20,325,0,0),(2,20,326,0,0),(2,20,327,0,0),(2,20,328,0,0),(2,20,329,0,0),(2,20,330,0,0),(2,20,331,0,0),(2,20,332,0,0),(2,20,333,0,0),(2,20,334,0,0),(2,20,335,0,0),(2,20,336,0,0),(2,20,337,0,0),(2,20,338,0,0),(2,20,339,0,0),(2,20,340,0,0),(2,21,341,0,0),(2,21,342,0,0),(2,21,343,0,0),(2,21,344,0,0),(2,21,345,0,0),(2,21,346,0,0),(2,21,347,0,0),(2,21,348,0,0),(2,21,349,0,0),(2,21,350,0,0),(2,21,351,0,0),(2,21,352,0,0),(2,21,353,0,0),(2,21,354,0,0),(2,21,355,0,0),(2,21,356,0,0),(2,21,357,0,0),(2,21,358,0,0),(2,21,359,0,0),(2,21,360,0,0),(2,21,361,0,0),(2,21,362,0,0),(2,21,363,0,0),(2,21,364,0,0),(2,21,365,0,0),(2,21,366,0,0),(2,21,367,0,0),(2,21,368,0,0),(2,21,369,0,0),(2,21,370,0,0),(2,21,371,0,0),(2,21,372,0,0),(2,21,373,0,0),(2,21,374,0,0),(2,21,375,0,0),(2,21,376,0,0),(2,21,377,0,0),(2,21,378,0,0),(2,22,379,0,0),(2,22,380,0,0),(2,22,381,0,0),(2,22,382,0,0),(2,22,383,0,0),(2,22,384,0,0),(2,22,385,0,0),(2,22,386,0,0),(2,22,387,0,0),(2,22,388,0,0),(2,22,389,0,0),(2,22,390,0,0),(2,22,391,0,0),(2,22,392,0,0),(2,22,393,0,0),(2,22,394,0,0),(2,22,395,0,0),(2,22,396,0,0),(2,22,397,0,0),(2,22,398,0,0),(2,22,399,0,0),(2,22,400,0,0),(2,22,401,0,0),(2,22,402,0,0),(2,22,403,0,0),(2,22,404,0,0),(2,22,405,0,0),(2,22,406,0,0),(2,22,407,0,0),(2,22,408,0,0),(2,22,409,0,0),(2,22,410,0,0),(2,22,411,0,0),(2,22,412,0,0),(2,22,413,0,0),(2,22,414,0,0),(2,22,415,0,0),(2,22,416,0,0),(2,22,417,0,0),(2,22,418,0,0),(2,22,419,0,0),(2,22,420,0,0),(2,22,421,0,0),(2,22,422,0,0),(2,22,423,0,0),(2,22,424,0,0),(2,22,425,0,0),(2,23,426,0,0),(2,23,427,0,0),(2,23,428,0,0),(2,23,429,0,0),(2,23,430,0,0),(2,23,431,0,0),(2,23,432,0,0),(2,23,433,0,0),(2,23,434,0,0),(2,23,435,0,0),(2,23,436,0,0),(2,23,437,0,0),(2,23,438,0,0),(2,23,439,0,0),(2,23,440,0,0),(2,23,441,0,0),(2,23,442,0,0),(2,23,443,0,0),(2,23,444,0,0),(2,23,445,0,0),(2,23,446,0,0),(2,23,447,0,0),(2,23,448,0,0),(2,23,449,0,0),(2,23,450,0,0),(2,23,451,0,0),(2,23,452,0,0),(2,23,453,0,0),(2,23,454,0,0),(2,23,455,0,0),(2,23,456,0,0),(2,23,457,0,0),(2,23,458,0,0),(2,23,459,0,0),(2,23,460,0,0),(2,23,461,0,0),(2,23,462,0,0),(2,23,463,0,0),(2,23,464,0,0),(2,29,465,0,0),(2,29,469,0,0),(2,29,470,0,0),(2,29,472,0,0),(2,29,479,0,0),(2,29,480,0,0),(2,29,481,0,0),(2,29,482,0,0),(2,29,483,0,0),(2,29,485,0,0),(2,29,486,0,0),(2,29,487,0,0),(2,29,488,0,0),(2,29,489,0,0),(2,29,494,0,0),(2,29,495,0,0),(2,29,496,0,0),(2,29,497,0,0),(2,29,505,0,0),(2,10,512,0,0),(2,10,513,0,0),(2,10,514,0,0),(2,10,515,0,0),(2,10,516,0,0),(2,10,517,0,0),(2,36,518,0,0),(2,36,519,0,0),(2,36,520,0,0),(2,36,521,0,0),(2,36,522,0,0),(2,36,523,0,0),(2,36,524,0,0),(2,36,525,0,0),(2,36,526,0,0),(2,36,527,0,0),(2,36,528,0,0),(2,36,529,0,0),(2,36,530,0,0),(2,36,531,0,0),(2,36,532,0,0),(2,36,533,0,0),(2,36,534,0,0),(2,36,535,0,0),(2,36,536,0,0),(2,37,537,0,0),(2,37,538,0,0),(2,37,539,0,0),(2,37,540,0,0),(2,37,541,0,0),(2,37,542,0,0),(2,37,543,0,0),(2,37,544,0,0),(2,37,545,0,0),(2,37,546,0,0),(2,37,547,0,0),(2,37,548,0,0),(2,37,549,0,0),(2,37,550,0,0),(2,37,551,0,0),(2,37,552,0,0),(2,29,553,0,0),(2,42,554,0,0),(2,42,555,0,0),(2,42,556,0,0),(2,42,557,0,0),(2,42,558,0,0),(2,42,559,0,0),(2,42,560,0,0),(2,42,561,0,0),(2,42,562,0,0),(2,42,563,0,0),(2,43,564,0,0),(2,43,565,0,0),(2,43,566,0,0),(2,43,567,0,0),(2,43,568,0,0),(2,43,569,0,0),(2,43,570,0,0),(2,43,571,0,0),(2,43,572,0,0),(2,43,573,0,0),(2,43,574,0,0),(2,43,575,0,0),(2,43,576,0,0),(2,43,577,0,0),(2,43,578,0,0),(2,44,579,0,0),(2,44,580,0,0),(2,44,581,0,0),(2,44,582,0,0),(2,44,583,0,0),(2,44,584,0,0),(2,44,585,0,0),(2,44,586,0,0),(2,44,587,0,0),(2,44,588,0,0),(2,44,589,0,0),(2,44,590,0,0),(2,44,591,0,0),(2,44,592,0,0),(2,44,593,0,0),(2,44,594,0,0),(2,44,595,0,0),(2,47,596,0,0),(2,47,597,0,0),(2,47,598,0,0),(2,47,599,0,0),(2,47,600,0,0),(2,47,601,0,0),(2,47,602,0,0),(2,2,603,0,0),(2,29,604,0,0),(2,23,605,0,0),(2,23,606,0,0),(2,23,607,0,0),(2,23,608,0,0),(2,23,609,0,0),(2,23,610,0,0),(2,23,611,0,0),(2,23,612,0,0),(2,23,613,0,0),(2,22,614,0,0),(2,22,615,0,0),(2,22,616,0,0),(2,22,617,0,0),(2,22,618,0,0),(2,22,619,0,0),(2,22,620,0,0),(2,22,621,0,0),(2,22,622,0,0),(2,21,623,0,0),(2,21,624,0,0),(2,21,625,0,0),(2,21,626,0,0),(2,21,627,0,0),(2,21,628,0,0),(2,21,629,0,0),(2,21,630,0,0),(2,21,631,0,0),(2,20,632,0,0),(2,20,633,0,0),(2,20,634,0,0),(2,20,635,0,0),(2,20,636,0,0),(2,20,637,0,0),(2,20,638,0,0),(2,20,639,0,0),(2,20,640,0,0),(2,29,641,0,0),(2,29,644,0,0),(2,29,645,0,0),(2,29,646,0,0),(2,23,647,0,0),(2,22,648,0,0),(2,21,649,0,0),(2,20,650,0,0),(2,29,651,0,0),(2,6,652,0,0),(2,4,653,0,0),(2,2,654,0,0),(2,29,655,0,0),(2,23,656,0,0),(2,23,657,0,0),(2,21,658,0,0),(2,21,659,0,0),(2,7,660,0,0),(2,23,663,0,0),(2,20,664,0,0),(2,21,665,0,0),(2,22,666,0,0),(2,29,667,0,0),(2,2,668,0,0),(2,13,669,0,0),(2,29,670,0,0),(2,29,671,0,0),(2,29,672,0,0),(2,29,673,0,0),(2,14,696,0,0),(2,23,698,0,0),(2,29,699,0,0),(2,23,700,0,0),(2,23,701,0,0),(2,23,702,0,0),(2,20,703,0,0),(2,20,704,0,0),(2,20,705,0,0),(2,21,706,0,0),(2,22,707,0,0),(2,22,708,0,0),(2,22,709,0,0),(2,2,713,0,0),(2,4,714,0,0),(2,6,715,0,0),(2,7,716,0,0),(2,8,717,0,0),(2,9,718,0,0),(2,10,719,0,0),(2,13,720,0,0),(2,14,721,0,0),(2,16,722,0,0),(2,18,723,0,0),(2,19,724,0,0),(2,20,725,0,0),(2,21,726,0,0),(2,22,727,0,0),(2,23,728,0,0),(2,26,729,0,0),(2,10,735,0,0),(2,2,736,0,0),(2,4,737,0,0),(2,6,738,0,0),(2,7,739,0,0),(2,8,740,0,0),(2,9,741,0,0),(2,10,742,0,0),(2,13,743,0,0),(2,14,744,0,0),(2,16,745,0,0),(2,18,746,0,0),(2,19,747,0,0),(2,20,748,0,0),(2,21,749,0,0),(2,22,750,0,0),(2,23,751,0,0),(2,26,752,0,0),(2,2,758,0,0),(2,4,759,0,0),(2,6,760,0,0),(2,7,761,0,0),(2,8,762,0,0),(2,9,763,0,0),(2,10,764,0,0),(2,13,765,0,0),(2,14,766,0,0),(2,16,767,0,0),(2,18,768,0,0),(2,19,769,0,0),(2,20,770,0,0),(2,21,771,0,0),(2,22,772,0,0),(2,23,773,0,0),(2,26,774,0,0),(2,20,780,0,0),(2,21,781,0,0),(2,22,782,0,0),(2,23,783,0,0),(2,42,800,0,0),(2,42,801,0,0),(2,42,802,0,0),(3,6,1,0,0),(3,6,2,0,0),(3,6,3,0,0),(3,6,4,0,0),(3,6,5,0,0),(3,6,6,0,0),(3,6,7,0,0),(3,6,8,0,0),(3,6,9,0,0),(3,6,10,0,0),(3,6,11,0,0),(3,6,12,0,0),(3,6,13,0,0),(3,6,14,0,0),(3,6,15,0,0),(3,6,16,0,0),(3,6,17,0,0),(3,6,18,0,0),(3,6,19,0,0),(3,6,20,0,0),(3,6,21,0,0),(3,6,22,0,0),(3,6,23,0,0),(3,6,24,0,0),(3,6,25,0,0),(3,6,26,0,0),(3,6,27,0,0),(3,6,28,0,0),(3,6,29,0,0),(3,6,30,0,0),(3,6,31,0,0),(3,6,32,0,0),(3,6,33,0,0),(3,6,34,0,0),(3,6,35,0,0),(3,6,36,0,0),(3,7,37,0,0),(3,7,38,0,0),(3,7,39,0,0),(3,7,40,0,0),(3,7,41,0,0),(3,7,42,0,0),(3,7,43,0,0),(3,7,44,0,0),(3,7,45,0,0),(3,7,46,0,0),(3,7,47,0,0),(3,7,48,0,0),(3,7,49,0,0),(3,7,50,0,0),(3,7,51,0,0),(3,7,52,0,0),(3,7,53,0,0),(3,7,54,0,0),(3,7,55,0,0),(3,7,56,0,0),(3,7,57,0,0),(3,7,58,0,0),(3,7,59,0,0),(3,7,60,0,0),(3,7,61,0,0),(3,7,62,0,0),(3,7,63,0,0),(3,7,64,0,0),(3,7,65,0,0),(3,4,66,0,0),(3,4,67,0,0),(3,4,68,0,0),(3,4,69,0,0),(3,4,70,0,0),(3,4,71,0,0),(3,4,72,0,0),(3,4,73,0,0),(3,4,74,0,0),(3,4,75,0,0),(3,4,76,0,0),(3,4,77,0,0),(3,4,78,0,0),(3,4,79,0,0),(3,4,80,0,0),(3,4,81,0,0),(3,4,82,0,0),(3,4,83,0,0),(3,4,84,0,0),(3,4,85,0,0),(3,4,86,0,0),(3,4,87,0,0),(3,4,88,0,0),(3,4,89,0,0),(3,4,90,0,0),(3,4,91,0,0),(3,4,92,0,0),(3,4,93,0,0),(3,4,94,0,0),(3,4,95,0,0),(3,4,96,0,0),(3,4,97,0,0),(3,4,98,0,0),(3,4,99,0,0),(3,4,100,0,0),(3,4,101,0,0),(3,4,102,0,0),(3,4,103,0,0),(3,4,104,0,0),(3,4,105,0,0),(3,4,106,0,0),(3,4,107,0,0),(3,4,108,0,0),(3,4,109,0,0),(3,2,110,0,0),(3,2,111,0,0),(3,2,112,0,0),(3,2,113,0,0),(3,2,114,0,0),(3,2,115,0,0),(3,2,116,0,0),(3,2,117,0,0),(3,2,118,0,0),(3,2,119,0,0),(3,2,120,0,0),(3,2,121,0,0),(3,2,122,0,0),(3,2,123,0,0),(3,2,124,0,0),(3,2,125,0,0),(3,26,126,0,0),(3,26,127,0,0),(3,26,128,0,0),(3,26,129,0,0),(3,26,130,0,0),(3,26,131,0,0),(3,26,132,0,0),(3,26,133,0,0),(3,26,134,0,0),(3,26,135,0,0),(3,26,136,0,0),(3,26,137,0,0),(3,26,138,0,0),(3,26,139,0,0),(3,26,140,0,0),(3,26,141,0,0),(3,26,142,0,0),(3,26,143,0,0),(3,26,144,0,0),(3,26,145,0,0),(3,26,146,0,0),(3,26,147,0,0),(3,26,148,0,0),(3,26,149,0,0),(3,26,150,0,0),(3,4,151,0,0),(3,6,152,0,0),(3,7,153,0,0),(3,26,154,0,0),(3,13,155,0,0),(3,13,156,0,0),(3,13,157,0,0),(3,13,158,0,0),(3,13,159,0,0),(3,13,160,0,0),(3,13,161,0,0),(3,13,162,0,0),(3,13,163,0,0),(3,13,164,0,0),(3,13,165,0,0),(3,13,166,0,0),(3,13,167,0,0),(3,13,168,0,0),(3,13,169,0,0),(3,13,170,0,0),(3,13,171,0,0),(3,13,172,0,0),(3,13,173,0,0),(3,14,174,0,0),(3,14,175,0,0),(3,14,176,0,0),(3,14,177,0,0),(3,14,178,0,0),(3,14,179,0,0),(3,14,180,0,0),(3,14,181,0,0),(3,14,182,0,0),(3,14,183,0,0),(3,14,184,0,0),(3,14,185,0,0),(3,14,186,0,0),(3,14,187,0,0),(3,14,188,0,0),(3,14,189,0,0),(3,14,190,0,0),(3,14,191,0,0),(3,14,192,0,0),(3,14,193,0,0),(3,14,194,0,0),(3,14,195,0,0),(3,14,196,0,0),(3,14,197,0,0),(3,14,198,0,0),(3,14,199,0,0),(3,14,200,0,0),(3,14,201,0,0),(3,14,202,0,0),(3,14,203,0,0),(3,14,204,0,0),(3,8,205,0,0),(3,8,206,0,0),(3,8,207,0,0),(3,8,208,0,0),(3,8,209,0,0),(3,8,210,0,0),(3,8,211,0,0),(3,8,212,0,0),(3,8,213,0,0),(3,8,214,0,0),(3,8,215,0,0),(3,8,216,0,0),(3,8,217,0,0),(3,8,218,0,0),(3,8,219,0,0),(3,10,220,0,0),(3,10,221,0,0),(3,10,222,0,0),(3,10,223,0,0),(3,10,224,0,0),(3,10,225,0,0),(3,10,226,0,0),(3,10,227,0,0),(3,10,228,0,0),(3,10,229,0,0),(3,10,230,0,0),(3,10,231,0,0),(3,9,232,0,0),(3,9,233,0,0),(3,9,234,0,0),(3,9,235,0,0),(3,9,236,0,0),(3,9,237,0,0),(3,9,238,0,0),(3,9,239,0,0),(3,9,240,0,0),(3,9,241,0,0),(3,9,242,0,0),(3,9,243,0,0),(3,9,244,0,0),(3,9,245,0,0),(3,9,246,0,0),(3,9,247,0,0),(3,9,248,0,0),(3,9,249,0,0),(3,9,250,0,0),(3,9,251,0,0),(3,9,252,0,0),(3,9,253,0,0),(3,9,254,0,0),(3,9,255,0,0),(3,16,256,0,0),(3,16,257,0,0),(3,16,258,0,0),(3,16,259,0,0),(3,16,260,0,0),(3,16,261,0,0),(3,16,262,0,0),(3,16,263,0,0),(3,16,264,0,0),(3,16,265,0,0),(3,16,266,0,0),(3,16,267,0,0),(3,16,268,0,0),(3,16,269,0,0),(3,16,270,0,0),(3,16,271,0,0),(3,16,272,0,0),(3,16,273,0,0),(3,16,274,0,0),(3,16,275,0,0),(3,16,276,0,0),(3,16,277,0,0),(3,16,278,0,0),(3,18,279,0,0),(3,18,280,0,0),(3,18,281,0,0),(3,18,282,0,0),(3,18,283,0,0),(3,18,284,0,0),(3,18,285,0,0),(3,18,286,0,0),(3,18,287,0,0),(3,18,288,0,0),(3,18,289,0,0),(3,18,290,0,0),(3,18,291,0,0),(3,18,292,0,0),(3,18,293,0,0),(3,18,294,0,0),(3,18,295,0,0),(3,19,296,0,0),(3,19,297,0,0),(3,19,298,0,0),(3,19,299,0,0),(3,19,300,0,0),(3,19,301,0,0),(3,19,302,0,0),(3,19,303,0,0),(3,20,304,0,0),(3,20,305,0,0),(3,20,306,0,0),(3,20,307,0,0),(3,20,308,0,0),(3,20,309,0,0),(3,20,310,0,0),(3,20,311,0,0),(3,20,312,0,0),(3,20,313,0,0),(3,20,314,0,0),(3,20,315,0,0),(3,20,316,0,0),(3,20,317,0,0),(3,20,318,0,0),(3,20,319,0,0),(3,20,320,0,0),(3,20,321,0,0),(3,20,322,0,0),(3,20,323,0,0),(3,20,324,0,0),(3,20,325,0,0),(3,20,326,0,0),(3,20,327,0,0),(3,20,328,0,0),(3,20,329,0,0),(3,20,330,0,0),(3,20,331,0,0),(3,20,332,0,0),(3,20,333,0,0),(3,20,334,0,0),(3,20,335,0,0),(3,20,336,0,0),(3,20,337,0,0),(3,20,338,0,0),(3,20,339,0,0),(3,20,340,0,0),(3,21,341,0,0),(3,21,342,0,0),(3,21,343,0,0),(3,21,344,0,0),(3,21,345,0,0),(3,21,346,0,0),(3,21,347,0,0),(3,21,348,0,0),(3,21,349,0,0),(3,21,350,0,0),(3,21,351,0,0),(3,21,352,0,0),(3,21,353,0,0),(3,21,354,0,0),(3,21,355,0,0),(3,21,356,0,0),(3,21,357,0,0),(3,21,358,0,0),(3,21,359,0,0),(3,21,360,0,0),(3,21,361,0,0),(3,21,362,0,0),(3,21,363,0,0),(3,21,364,0,0),(3,21,365,0,0),(3,21,366,0,0),(3,21,367,0,0),(3,21,368,0,0),(3,21,369,0,0),(3,21,370,0,0),(3,21,371,0,0),(3,21,372,0,0),(3,21,373,0,0),(3,21,374,0,0),(3,21,375,0,0),(3,21,376,0,0),(3,21,377,0,0),(3,21,378,0,0),(3,22,379,0,0),(3,22,380,0,0),(3,22,381,0,0),(3,22,382,0,0),(3,22,383,0,0),(3,22,384,0,0),(3,22,385,0,0),(3,22,386,0,0),(3,22,387,0,0),(3,22,388,0,0),(3,22,389,0,0),(3,22,390,0,0),(3,22,391,0,0),(3,22,392,0,0),(3,22,393,0,0),(3,22,394,0,0),(3,22,395,0,0),(3,22,396,0,0),(3,22,397,0,0),(3,22,398,0,0),(3,22,399,0,0),(3,22,400,0,0),(3,22,401,0,0),(3,22,402,0,0),(3,22,403,0,0),(3,22,404,0,0),(3,22,405,0,0),(3,22,406,0,0),(3,22,407,0,0),(3,22,408,0,0),(3,22,409,0,0),(3,22,410,0,0),(3,22,411,0,0),(3,22,412,0,0),(3,22,413,0,0),(3,22,414,0,0),(3,22,415,0,0),(3,22,416,0,0),(3,22,417,0,0),(3,22,418,0,0),(3,22,419,0,0),(3,22,420,0,0),(3,22,421,0,0),(3,22,422,0,0),(3,22,423,0,0),(3,22,424,0,0),(3,22,425,0,0),(3,23,426,0,0),(3,23,427,0,0),(3,23,428,0,0),(3,23,429,0,0),(3,23,430,0,0),(3,23,431,0,0),(3,23,432,0,0),(3,23,433,0,0),(3,23,434,0,0),(3,23,435,0,0),(3,23,436,0,0),(3,23,437,0,0),(3,23,438,0,0),(3,23,439,0,0),(3,23,440,0,0),(3,23,441,0,0),(3,23,442,0,0),(3,23,443,0,0),(3,23,444,0,0),(3,23,445,0,0),(3,23,446,0,0),(3,23,447,0,0),(3,23,448,0,0),(3,23,449,0,0),(3,23,450,0,0),(3,23,451,0,0),(3,23,452,0,0),(3,23,453,0,0),(3,23,454,0,0),(3,23,455,0,0),(3,23,456,0,0),(3,23,457,0,0),(3,23,458,0,0),(3,23,459,0,0),(3,23,460,0,0),(3,23,461,0,0),(3,23,462,0,0),(3,23,463,0,0),(3,23,464,0,0),(3,29,465,0,0),(3,29,469,0,0),(3,29,470,0,0),(3,29,472,0,0),(3,29,479,0,0),(3,29,480,0,0),(3,29,481,0,0),(3,29,482,0,0),(3,29,483,0,0),(3,29,485,0,0),(3,29,486,0,0),(3,29,487,0,0),(3,29,488,0,0),(3,29,489,0,0),(3,29,494,0,0),(3,29,495,0,0),(3,29,496,0,0),(3,29,497,0,0),(3,29,505,0,0),(3,10,512,0,0),(3,10,513,0,0),(3,10,514,0,0),(3,10,515,0,0),(3,10,516,0,0),(3,10,517,0,0),(3,36,518,0,0),(3,36,519,0,0),(3,36,520,0,0),(3,36,521,0,0),(3,36,522,0,0),(3,36,523,0,0),(3,36,524,0,0),(3,36,525,0,0),(3,36,526,0,0),(3,36,527,0,0),(3,36,528,0,0),(3,36,529,0,0),(3,36,530,0,0),(3,36,531,0,0),(3,36,532,0,0),(3,36,533,0,0),(3,36,534,0,0),(3,36,535,0,0),(3,36,536,0,0),(3,37,537,0,0),(3,37,538,0,0),(3,37,539,0,0),(3,37,540,0,0),(3,37,541,0,0),(3,37,542,0,0),(3,37,543,0,0),(3,37,544,0,0),(3,37,545,0,0),(3,37,546,0,0),(3,37,547,0,0),(3,37,548,0,0),(3,37,549,0,0),(3,37,550,0,0),(3,37,551,0,0),(3,37,552,0,0),(3,29,553,0,0),(3,42,554,0,0),(3,42,555,0,0),(3,42,556,0,0),(3,42,557,0,0),(3,42,558,0,0),(3,42,559,0,0),(3,42,560,0,0),(3,42,561,0,0),(3,42,562,0,0),(3,42,563,0,0),(3,43,564,0,0),(3,43,565,0,0),(3,43,566,0,0),(3,43,567,0,0),(3,43,568,0,0),(3,43,569,0,0),(3,43,570,0,0),(3,43,571,0,0),(3,43,572,0,0),(3,43,573,0,0),(3,43,574,0,0),(3,43,575,0,0),(3,43,576,0,0),(3,43,577,0,0),(3,43,578,0,0),(3,44,579,0,0),(3,44,580,0,0),(3,44,581,0,0),(3,44,582,0,0),(3,44,583,0,0),(3,44,584,0,0),(3,44,585,0,0),(3,44,586,0,0),(3,44,587,0,0),(3,44,588,0,0),(3,44,589,0,0),(3,44,590,0,0),(3,44,591,0,0),(3,44,592,0,0),(3,44,593,0,0),(3,44,594,0,0),(3,44,595,0,0),(3,47,596,0,0),(3,47,597,0,0),(3,47,598,0,0),(3,47,599,0,0),(3,47,600,0,0),(3,47,601,0,0),(3,47,602,0,0),(3,2,603,0,0),(3,29,604,0,0),(3,23,605,0,0),(3,23,606,0,0),(3,23,607,0,0),(3,23,608,0,0),(3,23,609,0,0),(3,23,610,0,0),(3,23,611,0,0),(3,23,612,0,0),(3,23,613,0,0),(3,22,614,0,0),(3,22,615,0,0),(3,22,616,0,0),(3,22,617,0,0),(3,22,618,0,0),(3,22,619,0,0),(3,22,620,0,0),(3,22,621,0,0),(3,22,622,0,0),(3,21,623,0,0),(3,21,624,0,0),(3,21,625,0,0),(3,21,626,0,0),(3,21,627,0,0),(3,21,628,0,0),(3,21,629,0,0),(3,21,630,0,0),(3,21,631,0,0),(3,20,632,0,0),(3,20,633,0,0),(3,20,634,0,0),(3,20,635,0,0),(3,20,636,0,0),(3,20,637,0,0),(3,20,638,0,0),(3,20,639,0,0),(3,20,640,0,0),(3,29,641,0,0),(3,29,644,0,0),(3,29,645,0,0),(3,29,646,0,0),(3,23,647,0,0),(3,22,648,0,0),(3,21,649,0,0),(3,20,650,0,0),(3,29,651,0,0),(3,6,652,0,0),(3,4,653,0,0),(3,2,654,0,0),(3,29,655,0,0),(3,23,656,0,0),(3,23,657,0,0),(3,21,658,0,0),(3,21,659,0,0),(3,7,660,0,0),(3,23,663,0,0),(3,20,664,0,0),(3,21,665,0,0),(3,22,666,0,0),(3,29,667,0,0),(3,2,668,0,0),(3,13,669,0,0),(3,29,670,0,0),(3,29,671,0,0),(3,29,672,0,0),(3,29,673,0,0),(3,14,696,0,0),(3,23,698,0,0),(3,29,699,0,0),(3,23,700,0,0),(3,23,701,0,0),(3,23,702,0,0),(3,20,703,0,0),(3,20,704,0,0),(3,20,705,0,0),(3,21,706,0,0),(3,22,707,0,0),(3,22,708,0,0),(3,22,709,0,0),(3,2,713,0,0),(3,4,714,0,0),(3,6,715,0,0),(3,7,716,0,0),(3,8,717,0,0),(3,9,718,0,0),(3,10,719,0,0),(3,13,720,0,0),(3,14,721,0,0),(3,16,722,0,0),(3,18,723,0,0),(3,19,724,0,0),(3,20,725,0,0),(3,21,726,0,0),(3,22,727,0,0),(3,23,728,0,0),(3,26,729,0,0),(3,10,735,0,0),(3,2,736,0,0),(3,4,737,0,0),(3,6,738,0,0),(3,7,739,0,0),(3,8,740,0,0),(3,9,741,0,0),(3,10,742,0,0),(3,13,743,0,0),(3,14,744,0,0),(3,16,745,0,0),(3,18,746,0,0),(3,19,747,0,0),(3,20,748,0,0),(3,21,749,0,0),(3,22,750,0,0),(3,23,751,0,0),(3,26,752,0,0),(3,2,758,0,0),(3,4,759,0,0),(3,6,760,0,0),(3,7,761,0,0),(3,8,762,0,0),(3,9,763,0,0),(3,10,764,0,0),(3,13,765,0,0),(3,14,766,0,0),(3,16,767,0,0),(3,18,768,0,0),(3,19,769,0,0),(3,20,770,0,0),(3,21,771,0,0),(3,22,772,0,0),(3,23,773,0,0),(3,26,774,0,0),(3,20,780,0,0),(3,21,781,0,0),(3,22,782,0,0),(3,23,783,0,0),(3,42,800,0,0),(3,42,801,0,0),(3,42,802,0,0),(4,6,1,0,0),(4,6,2,0,0),(4,6,3,0,0),(4,6,4,0,0),(4,6,5,0,0),(4,6,6,0,0),(4,6,7,0,0),(4,6,8,0,0),(4,6,9,0,0),(4,6,10,0,0),(4,6,11,0,0),(4,6,12,0,0),(4,6,13,0,0),(4,6,14,0,0),(4,6,15,0,0),(4,6,16,0,0),(4,6,17,0,0),(4,6,18,0,0),(4,6,19,0,0),(4,6,20,0,0),(4,6,21,0,0),(4,6,22,0,0),(4,6,23,0,0),(4,6,24,0,0),(4,6,25,0,0),(4,6,26,0,0),(4,6,27,0,0),(4,6,28,0,0),(4,6,29,0,0),(4,6,30,0,0),(4,6,31,0,0),(4,6,32,0,0),(4,6,33,0,0),(4,6,34,0,0),(4,6,35,0,0),(4,6,36,0,0),(4,7,37,0,0),(4,7,38,0,0),(4,7,39,0,0),(4,7,40,0,0),(4,7,41,0,0),(4,7,42,0,0),(4,7,43,0,0),(4,7,44,0,0),(4,7,45,0,0),(4,7,46,0,0),(4,7,47,0,0),(4,7,48,0,0),(4,7,49,0,0),(4,7,50,0,0),(4,7,51,0,0),(4,7,52,0,0),(4,7,53,0,0),(4,7,54,0,0),(4,7,55,0,0),(4,7,56,0,0),(4,7,57,0,0),(4,7,58,0,0),(4,7,59,0,0),(4,7,60,0,0),(4,7,61,0,0),(4,7,62,0,0),(4,7,63,0,0),(4,7,64,0,0),(4,7,65,0,0),(4,4,66,0,0),(4,4,67,0,0),(4,4,68,0,0),(4,4,69,0,0),(4,4,70,0,0),(4,4,71,0,0),(4,4,72,0,0),(4,4,73,0,0),(4,4,74,0,0),(4,4,75,0,0),(4,4,76,0,0),(4,4,77,0,0),(4,4,78,0,0),(4,4,79,0,0),(4,4,80,0,0),(4,4,81,0,0),(4,4,82,0,0),(4,4,83,0,0),(4,4,84,0,0),(4,4,85,0,0),(4,4,86,0,0),(4,4,87,0,0),(4,4,88,0,0),(4,4,89,0,0),(4,4,90,0,0),(4,4,91,0,0),(4,4,92,0,0),(4,4,93,0,0),(4,4,94,0,0),(4,4,95,0,0),(4,4,96,0,0),(4,4,97,0,0),(4,4,98,0,0),(4,4,99,0,0),(4,4,100,0,0),(4,4,101,0,0),(4,4,102,0,0),(4,4,103,0,0),(4,4,104,0,0),(4,4,105,0,0),(4,4,106,0,0),(4,4,107,0,0),(4,4,108,0,0),(4,4,109,0,0),(4,2,110,0,0),(4,2,111,0,0),(4,2,112,0,0),(4,2,113,0,0),(4,2,114,0,0),(4,2,115,0,0),(4,2,116,0,0),(4,2,117,0,0),(4,2,118,0,0),(4,2,119,0,0),(4,2,120,0,0),(4,2,121,0,0),(4,2,122,0,0),(4,2,123,0,0),(4,2,124,0,0),(4,2,125,0,0),(4,26,126,0,0),(4,26,127,0,0),(4,26,128,0,0),(4,26,129,0,0),(4,26,130,0,0),(4,26,131,0,0),(4,26,132,0,0),(4,26,133,0,0),(4,26,134,0,0),(4,26,135,0,0),(4,26,136,0,0),(4,26,137,0,0),(4,26,138,0,0),(4,26,139,0,0),(4,26,140,0,0),(4,26,141,0,0),(4,26,142,0,0),(4,26,143,0,0),(4,26,144,0,0),(4,26,145,0,0),(4,26,146,0,0),(4,26,147,0,0),(4,26,148,0,0),(4,26,149,0,0),(4,26,150,0,0),(4,4,151,0,0),(4,6,152,0,0),(4,7,153,0,0),(4,26,154,0,0),(4,13,155,0,0),(4,13,156,0,0),(4,13,157,0,0),(4,13,158,0,0),(4,13,159,0,0),(4,13,160,0,0),(4,13,161,0,0),(4,13,162,0,0),(4,13,163,0,0),(4,13,164,0,0),(4,13,165,0,0),(4,13,166,0,0),(4,13,167,0,0),(4,13,168,0,0),(4,13,169,0,0),(4,13,170,0,0),(4,13,171,0,0),(4,13,172,0,0),(4,13,173,0,0),(4,14,174,0,0),(4,14,175,0,0),(4,14,176,0,0),(4,14,177,0,0),(4,14,178,0,0),(4,14,179,0,0),(4,14,180,0,0),(4,14,181,0,0),(4,14,182,0,0),(4,14,183,0,0),(4,14,184,0,0),(4,14,185,0,0),(4,14,186,0,0),(4,14,187,0,0),(4,14,188,0,0),(4,14,189,0,0),(4,14,190,0,0),(4,14,191,0,0),(4,14,192,0,0),(4,14,193,0,0),(4,14,194,0,0),(4,14,195,0,0),(4,14,196,0,0),(4,14,197,0,0),(4,14,198,0,0),(4,14,199,0,0),(4,14,200,0,0),(4,14,201,0,0),(4,14,202,0,0),(4,14,203,0,0),(4,14,204,0,0),(4,8,205,0,0),(4,8,206,0,0),(4,8,207,0,0),(4,8,208,0,0),(4,8,209,0,0),(4,8,210,0,0),(4,8,211,0,0),(4,8,212,0,0),(4,8,213,0,0),(4,8,214,0,0),(4,8,215,0,0),(4,8,216,0,0),(4,8,217,0,0),(4,8,218,0,0),(4,8,219,0,0),(4,10,220,0,0),(4,10,221,0,0),(4,10,222,0,0),(4,10,223,0,0),(4,10,224,0,0),(4,10,225,0,0),(4,10,226,0,0),(4,10,227,0,0),(4,10,228,0,0),(4,10,229,0,0),(4,10,230,0,0),(4,10,231,0,0),(4,9,232,0,0),(4,9,233,0,0),(4,9,234,0,0),(4,9,235,0,0),(4,9,236,0,0),(4,9,237,0,0),(4,9,238,0,0),(4,9,239,0,0),(4,9,240,0,0),(4,9,241,0,0),(4,9,242,0,0),(4,9,243,0,0),(4,9,244,0,0),(4,9,245,0,0),(4,9,246,0,0),(4,9,247,0,0),(4,9,248,0,0),(4,9,249,0,0),(4,9,250,0,0),(4,9,251,0,0),(4,9,252,0,0),(4,9,253,0,0),(4,9,254,0,0),(4,9,255,0,0),(4,16,256,0,0),(4,16,257,0,0),(4,16,258,0,0),(4,16,259,0,0),(4,16,260,0,0),(4,16,261,0,0),(4,16,262,0,0),(4,16,263,0,0),(4,16,264,0,0),(4,16,265,0,0),(4,16,266,0,0),(4,16,267,0,0),(4,16,268,0,0),(4,16,269,0,0),(4,16,270,0,0),(4,16,271,0,0),(4,16,272,0,0),(4,16,273,0,0),(4,16,274,0,0),(4,16,275,0,0),(4,16,276,0,0),(4,16,277,0,0),(4,16,278,0,0),(4,18,279,0,0),(4,18,280,0,0),(4,18,281,0,0),(4,18,282,0,0),(4,18,283,0,0),(4,18,284,0,0),(4,18,285,0,0),(4,18,286,0,0),(4,18,287,0,0),(4,18,288,0,0),(4,18,289,0,0),(4,18,290,0,0),(4,18,291,0,0),(4,18,292,0,0),(4,18,293,0,0),(4,18,294,0,0),(4,18,295,0,0),(4,19,296,0,0),(4,19,297,0,0),(4,19,298,0,0),(4,19,299,0,0),(4,19,300,0,0),(4,19,301,0,0),(4,19,302,0,0),(4,19,303,0,0),(4,20,304,0,0),(4,20,305,0,0),(4,20,306,0,0),(4,20,307,0,0),(4,20,308,0,0),(4,20,309,0,0),(4,20,310,0,0),(4,20,311,0,0),(4,20,312,0,0),(4,20,313,0,0),(4,20,314,0,0),(4,20,315,0,0),(4,20,316,0,0),(4,20,317,0,0),(4,20,318,0,0),(4,20,319,0,0),(4,20,320,0,0),(4,20,321,0,0),(4,20,322,0,0),(4,20,323,0,0),(4,20,324,0,0),(4,20,325,0,0),(4,20,326,0,0),(4,20,327,0,0),(4,20,328,0,0),(4,20,329,0,0),(4,20,330,0,0),(4,20,331,0,0),(4,20,332,0,0),(4,20,333,0,0),(4,20,334,0,0),(4,20,335,0,0),(4,20,336,0,0),(4,20,337,0,0),(4,20,338,0,0),(4,20,339,0,0),(4,20,340,0,0),(4,21,341,0,0),(4,21,342,0,0),(4,21,343,0,0),(4,21,344,0,0),(4,21,345,0,0),(4,21,346,0,0),(4,21,347,0,0),(4,21,348,0,0),(4,21,349,0,0),(4,21,350,0,0),(4,21,351,0,0),(4,21,352,0,0),(4,21,353,0,0),(4,21,354,0,0),(4,21,355,0,0),(4,21,356,0,0),(4,21,357,0,0),(4,21,358,0,0),(4,21,359,0,0),(4,21,360,0,0),(4,21,361,0,0),(4,21,362,0,0),(4,21,363,0,0),(4,21,364,0,0),(4,21,365,0,0),(4,21,366,0,0),(4,21,367,0,0),(4,21,368,0,0),(4,21,369,0,0),(4,21,370,0,0),(4,21,371,0,0),(4,21,372,0,0),(4,21,373,0,0),(4,21,374,0,0),(4,21,375,0,0),(4,21,376,0,0),(4,21,377,0,0),(4,21,378,0,0),(4,22,379,0,0),(4,22,380,0,0),(4,22,381,0,0),(4,22,382,0,0),(4,22,383,0,0),(4,22,384,0,0),(4,22,385,0,0),(4,22,386,0,0),(4,22,387,0,0),(4,22,388,0,0),(4,22,389,0,0),(4,22,390,0,0),(4,22,391,0,0),(4,22,392,0,0),(4,22,393,0,0),(4,22,394,0,0),(4,22,395,0,0),(4,22,396,0,0),(4,22,397,0,0),(4,22,398,0,0),(4,22,399,0,0),(4,22,400,0,0),(4,22,401,0,0),(4,22,402,0,0),(4,22,403,0,0),(4,22,404,0,0),(4,22,405,0,0),(4,22,406,0,0),(4,22,407,0,0),(4,22,408,0,0),(4,22,409,0,0),(4,22,410,0,0),(4,22,411,0,0),(4,22,412,0,0),(4,22,413,0,0),(4,22,414,0,0),(4,22,415,0,0),(4,22,416,0,0),(4,22,417,0,0),(4,22,418,0,0),(4,22,419,0,0),(4,22,420,0,0),(4,22,421,0,0),(4,22,422,0,0),(4,22,423,0,0),(4,22,424,0,0),(4,22,425,0,0),(4,23,426,0,0),(4,23,427,0,0),(4,23,428,0,0),(4,23,429,0,0),(4,23,430,0,0),(4,23,431,0,0),(4,23,432,0,0),(4,23,433,0,0),(4,23,434,0,0),(4,23,435,0,0),(4,23,436,0,0),(4,23,437,0,0),(4,23,438,0,0),(4,23,439,0,0),(4,23,440,0,0),(4,23,441,0,0),(4,23,442,0,0),(4,23,443,0,0),(4,23,444,0,0),(4,23,445,0,0),(4,23,446,0,0),(4,23,447,0,0),(4,23,448,0,0),(4,23,449,0,0),(4,23,450,0,0),(4,23,451,0,0),(4,23,452,0,0),(4,23,453,0,0),(4,23,454,0,0),(4,23,455,0,0),(4,23,456,0,0),(4,23,457,0,0),(4,23,458,0,0),(4,23,459,0,0),(4,23,460,0,0),(4,23,461,0,0),(4,23,462,0,0),(4,23,463,0,0),(4,23,464,0,0),(4,29,465,0,0),(4,29,469,0,0),(4,29,470,0,0),(4,29,472,0,0),(4,29,479,0,0),(4,29,480,0,0),(4,29,481,0,0),(4,29,482,0,0),(4,29,483,0,0),(4,29,485,0,0),(4,29,486,0,0),(4,29,487,0,0),(4,29,488,0,0),(4,29,489,0,0),(4,29,494,0,0),(4,29,495,0,0),(4,29,496,0,0),(4,29,497,0,0),(4,29,505,0,0),(4,10,512,0,0),(4,10,513,0,0),(4,10,514,0,0),(4,10,515,0,0),(4,10,516,0,0),(4,10,517,0,0),(4,36,518,0,0),(4,36,519,0,0),(4,36,520,0,0),(4,36,521,0,0),(4,36,522,0,0),(4,36,523,0,0),(4,36,524,0,0),(4,36,525,0,0),(4,36,526,0,0),(4,36,527,0,0),(4,36,528,0,0),(4,36,529,0,0),(4,36,530,0,0),(4,36,531,0,0),(4,36,532,0,0),(4,36,533,0,0),(4,36,534,0,0),(4,36,535,0,0),(4,36,536,0,0),(4,37,537,0,0),(4,37,538,0,0),(4,37,539,0,0),(4,37,540,0,0),(4,37,541,0,0),(4,37,542,0,0),(4,37,543,0,0),(4,37,544,0,0),(4,37,545,0,0),(4,37,546,0,0),(4,37,547,0,0),(4,37,548,0,0),(4,37,549,0,0),(4,37,550,0,0),(4,37,551,0,0),(4,37,552,0,0),(4,29,553,0,0),(4,42,554,0,0),(4,42,555,0,0),(4,42,556,0,0),(4,42,557,0,0),(4,42,558,0,0),(4,42,559,0,0),(4,42,560,0,0),(4,42,561,0,0),(4,42,562,0,0),(4,42,563,0,0),(4,43,564,0,0),(4,43,565,0,0),(4,43,566,0,0),(4,43,567,0,0),(4,43,568,0,0),(4,43,569,0,0),(4,43,570,0,0),(4,43,571,0,0),(4,43,572,0,0),(4,43,573,0,0),(4,43,574,0,0),(4,43,575,0,0),(4,43,576,0,0),(4,43,577,0,0),(4,43,578,0,0),(4,44,579,0,0),(4,44,580,0,0),(4,44,581,0,0),(4,44,582,0,0),(4,44,583,0,0),(4,44,584,0,0),(4,44,585,0,0),(4,44,586,0,0),(4,44,587,0,0),(4,44,588,0,0),(4,44,589,0,0),(4,44,590,0,0),(4,44,591,0,0),(4,44,592,0,0),(4,44,593,0,0),(4,44,594,0,0),(4,44,595,0,0),(4,47,596,0,0),(4,47,597,0,0),(4,47,598,0,0),(4,47,599,0,0),(4,47,600,0,0),(4,47,601,0,0),(4,47,602,0,0),(4,2,603,0,0),(4,29,604,0,0),(4,23,605,0,0),(4,23,606,0,0),(4,23,607,0,0),(4,23,608,0,0),(4,23,609,0,0),(4,23,610,0,0),(4,23,611,0,0),(4,23,612,0,0),(4,23,613,0,0),(4,22,614,0,0),(4,22,615,0,0),(4,22,616,0,0),(4,22,617,0,0),(4,22,618,0,0),(4,22,619,0,0),(4,22,620,0,0),(4,22,621,0,0),(4,22,622,0,0),(4,21,623,0,0),(4,21,624,0,0),(4,21,625,0,0),(4,21,626,0,0),(4,21,627,0,0),(4,21,628,0,0),(4,21,629,0,0),(4,21,630,0,0),(4,21,631,0,0),(4,20,632,0,0),(4,20,633,0,0),(4,20,634,0,0),(4,20,635,0,0),(4,20,636,0,0),(4,20,637,0,0),(4,20,638,0,0),(4,20,639,0,0),(4,20,640,0,0),(4,29,641,0,0),(4,29,644,0,0),(4,29,645,0,0),(4,29,646,0,0),(4,23,647,0,0),(4,22,648,0,0),(4,21,649,0,0),(4,20,650,0,0),(4,29,651,0,0),(4,6,652,0,0),(4,4,653,0,0),(4,2,654,0,0),(4,29,655,0,0),(4,23,656,0,0),(4,23,657,0,0),(4,21,658,0,0),(4,21,659,0,0),(4,7,660,0,0),(4,23,663,0,0),(4,20,664,0,0),(4,21,665,0,0),(4,22,666,0,0),(4,29,667,0,0),(4,2,668,0,0),(4,13,669,0,0),(4,29,670,0,0),(4,29,671,0,0),(4,29,672,0,0),(4,29,673,0,0),(4,14,696,0,0),(4,23,698,0,0),(4,29,699,0,0),(4,23,700,0,0),(4,23,701,0,0),(4,23,702,0,0),(4,20,703,0,0),(4,20,704,0,0),(4,20,705,0,0),(4,21,706,0,0),(4,22,707,0,0),(4,22,708,0,0),(4,22,709,0,0),(4,2,713,0,0),(4,4,714,0,0),(4,6,715,0,0),(4,7,716,0,0),(4,8,717,0,0),(4,9,718,0,0),(4,10,719,0,0),(4,13,720,0,0),(4,14,721,0,0),(4,16,722,0,0),(4,18,723,0,0),(4,19,724,0,0),(4,20,725,0,0),(4,21,726,0,0),(4,22,727,0,0),(4,23,728,0,0),(4,26,729,0,0),(4,10,735,0,0),(4,2,736,0,0),(4,4,737,0,0),(4,6,738,0,0),(4,7,739,0,0),(4,8,740,0,0),(4,9,741,0,0),(4,10,742,0,0),(4,13,743,0,0),(4,14,744,0,0),(4,16,745,0,0),(4,18,746,0,0),(4,19,747,0,0),(4,20,748,0,0),(4,21,749,0,0),(4,22,750,0,0),(4,23,751,0,0),(4,26,752,0,0),(4,2,758,0,0),(4,4,759,0,0),(4,6,760,0,0),(4,7,761,0,0),(4,8,762,0,0),(4,9,763,0,0),(4,10,764,0,0),(4,13,765,0,0),(4,14,766,0,0),(4,16,767,0,0),(4,18,768,0,0),(4,19,769,0,0),(4,20,770,0,0),(4,21,771,0,0),(4,22,772,0,0),(4,23,773,0,0),(4,26,774,0,0),(4,20,780,0,0),(4,21,781,0,0),(4,22,782,0,0),(4,23,783,0,0),(4,42,800,0,0),(4,42,801,0,0),(4,42,802,0,0),(5,6,1,0,0),(5,6,2,0,0),(5,6,3,0,0),(5,6,4,0,0),(5,6,5,0,0),(5,6,6,0,0),(5,6,7,0,0),(5,6,8,0,0),(5,6,9,0,0),(5,6,10,0,0),(5,6,11,0,0),(5,6,12,0,0),(5,6,13,0,0),(5,6,14,0,0),(5,6,15,0,0),(5,6,16,0,0),(5,6,17,0,0),(5,6,18,0,0),(5,6,19,0,0),(5,6,20,0,0),(5,6,21,0,1),(5,6,22,0,1),(5,6,23,0,0),(5,6,24,0,0),(5,6,25,0,0),(5,6,26,0,0),(5,6,27,0,0),(5,6,28,0,0),(5,6,29,0,0),(5,6,30,0,0),(5,6,31,0,0),(5,6,32,0,0),(5,6,33,0,0),(5,6,34,0,0),(5,6,35,0,0),(5,6,36,0,0),(5,7,37,0,0),(5,7,38,0,0),(5,7,39,0,0),(5,7,40,0,0),(5,7,41,0,0),(5,7,42,0,0),(5,7,43,0,0),(5,7,44,0,0),(5,7,45,0,0),(5,7,46,0,0),(5,7,47,0,0),(5,7,48,0,0),(5,7,49,0,0),(5,7,50,0,0),(5,7,51,0,0),(5,7,52,0,0),(5,7,53,0,0),(5,7,54,0,0),(5,7,55,0,0),(5,7,56,0,1),(5,7,57,0,1),(5,7,58,0,0),(5,7,59,0,0),(5,7,60,0,0),(5,7,61,0,0),(5,7,62,0,0),(5,7,63,0,0),(5,7,64,0,0),(5,7,65,0,0),(5,4,66,0,0),(5,4,67,0,0),(5,4,68,0,0),(5,4,69,0,0),(5,4,70,0,0),(5,4,71,0,0),(5,4,72,0,0),(5,4,73,0,0),(5,4,74,0,0),(5,4,75,0,0),(5,4,76,0,0),(5,4,77,0,0),(5,4,78,0,0),(5,4,79,0,0),(5,4,80,0,0),(5,4,81,0,0),(5,4,82,0,0),(5,4,83,0,0),(5,4,84,0,0),(5,4,85,0,0),(5,4,86,0,0),(5,4,87,0,0),(5,4,88,0,0),(5,4,89,0,0),(5,4,90,0,1),(5,4,91,0,1),(5,4,92,0,0),(5,4,93,0,0),(5,4,94,0,0),(5,4,95,0,0),(5,4,96,0,0),(5,4,97,0,0),(5,4,98,0,0),(5,4,99,0,0),(5,4,100,0,0),(5,4,101,0,0),(5,4,102,0,0),(5,4,103,0,0),(5,4,104,0,0),(5,4,105,0,0),(5,4,106,0,0),(5,4,107,0,0),(5,4,108,0,0),(5,4,109,0,0),(5,2,110,0,0),(5,2,111,0,0),(5,2,112,0,0),(5,2,113,0,0),(5,2,114,0,0),(5,2,115,0,0),(5,2,116,0,0),(5,2,117,0,0),(5,2,118,0,0),(5,2,119,0,0),(5,2,120,0,0),(5,2,121,0,0),(5,2,122,0,1),(5,2,123,0,1),(5,2,124,0,0),(5,2,125,0,0),(5,26,126,0,0),(5,26,127,0,0),(5,26,128,0,0),(5,26,129,0,0),(5,26,130,0,0),(5,26,131,0,0),(5,26,132,0,0),(5,26,133,0,0),(5,26,134,0,0),(5,26,135,0,0),(5,26,136,0,0),(5,26,137,0,1),(5,26,138,0,1),(5,26,139,0,0),(5,26,140,0,0),(5,26,141,0,0),(5,26,142,0,0),(5,26,143,0,0),(5,26,144,0,0),(5,26,145,0,0),(5,26,146,0,0),(5,26,147,0,0),(5,26,148,0,0),(5,26,149,0,0),(5,26,150,0,0),(5,13,155,0,0),(5,13,156,0,0),(5,13,157,0,0),(5,13,158,0,0),(5,13,159,0,0),(5,13,160,0,0),(5,13,161,0,0),(5,13,162,0,0),(5,13,164,0,0),(5,13,165,0,0),(5,13,166,0,1),(5,13,167,0,1),(5,13,168,0,0),(5,13,169,0,0),(5,13,170,0,0),(5,13,171,0,0),(5,13,172,0,0),(5,14,174,0,0),(5,14,175,0,0),(5,14,176,0,0),(5,14,177,0,0),(5,14,178,0,0),(5,14,179,0,0),(5,14,180,0,0),(5,14,181,0,0),(5,14,182,0,0),(5,14,183,0,0),(5,14,184,0,0),(5,14,185,0,0),(5,14,186,0,0),(5,14,187,0,0),(5,14,188,0,0),(5,14,189,0,0),(5,14,190,0,0),(5,14,191,0,1),(5,14,192,0,1),(5,14,193,0,0),(5,14,194,0,0),(5,14,195,0,0),(5,14,197,0,0),(5,14,198,0,0),(5,14,199,0,0),(5,14,200,0,0),(5,14,201,0,0),(5,14,202,0,0),(5,14,203,0,0),(5,14,204,0,0),(5,8,205,0,0),(5,8,206,0,1),(5,8,207,0,1),(5,8,208,0,0),(5,8,209,0,0),(5,8,210,0,0),(5,8,211,0,1),(5,8,212,0,1),(5,8,213,0,0),(5,8,214,0,0),(5,8,215,0,0),(5,8,216,0,1),(5,8,217,0,0),(5,8,218,0,0),(5,8,219,0,0),(5,10,220,0,0),(5,10,221,0,0),(5,10,222,0,0),(5,10,223,0,0),(5,10,224,0,0),(5,10,225,0,0),(5,10,226,0,0),(5,10,227,0,0),(5,10,228,0,0),(5,10,229,0,1),(5,10,230,0,0),(5,10,231,0,0),(5,9,232,0,0),(5,9,233,0,0),(5,9,234,0,0),(5,9,235,0,0),(5,9,236,0,0),(5,9,237,0,0),(5,9,238,0,0),(5,9,239,0,0),(5,9,240,0,0),(5,9,241,0,0),(5,9,242,0,0),(5,9,243,0,0),(5,9,244,0,1),(5,9,245,0,1),(5,9,246,0,0),(5,9,247,0,0),(5,9,248,0,0),(5,9,249,0,0),(5,9,250,0,0),(5,9,251,0,0),(5,9,252,0,0),(5,9,253,0,0),(5,9,254,0,0),(5,9,255,0,0),(5,16,256,0,0),(5,16,257,0,0),(5,16,258,0,0),(5,16,259,0,0),(5,16,260,0,0),(5,16,261,0,0),(5,16,262,0,0),(5,16,263,0,0),(5,16,264,0,0),(5,16,265,0,0),(5,16,266,0,0),(5,16,267,0,0),(5,16,268,0,0),(5,16,269,0,0),(5,16,270,0,1),(5,16,271,0,1),(5,16,272,0,0),(5,16,273,0,0),(5,16,274,0,0),(5,16,275,0,0),(5,16,276,0,0),(5,16,277,0,0),(5,16,278,0,0),(5,18,279,0,0),(5,18,280,0,0),(5,18,281,0,0),(5,18,282,0,0),(5,18,283,0,0),(5,18,284,0,0),(5,18,285,0,0),(5,18,286,0,1),(5,18,287,0,1),(5,18,288,0,0),(5,18,289,0,0),(5,18,290,0,0),(5,18,291,0,0),(5,18,292,0,0),(5,18,293,0,0),(5,18,294,0,0),(5,18,295,0,0),(5,19,296,0,0),(5,19,297,0,0),(5,19,298,0,0),(5,19,299,0,1),(5,19,300,0,1),(5,19,301,0,0),(5,19,302,0,0),(5,19,303,0,0),(5,20,304,0,0),(5,20,305,0,0),(5,20,306,0,0),(5,20,307,0,0),(5,20,308,0,0),(5,20,309,0,0),(5,20,310,0,0),(5,20,311,0,0),(5,20,312,0,0),(5,20,313,0,0),(5,20,314,0,0),(5,20,315,0,0),(5,20,316,0,0),(5,20,317,0,0),(5,20,318,0,0),(5,20,319,0,0),(5,20,320,0,0),(5,20,321,0,0),(5,20,322,0,1),(5,20,323,0,1),(5,20,324,0,0),(5,20,325,0,0),(5,20,326,0,0),(5,20,327,0,0),(5,20,328,0,0),(5,20,329,0,0),(5,20,330,0,0),(5,20,331,0,0),(5,20,332,0,0),(5,20,333,0,0),(5,20,334,0,0),(5,20,335,0,0),(5,20,336,0,0),(5,20,337,0,0),(5,20,338,0,0),(5,20,339,0,0),(5,20,340,0,0),(5,21,341,0,0),(5,21,342,0,0),(5,21,343,0,0),(5,21,344,0,0),(5,21,345,0,0),(5,21,346,0,0),(5,21,347,0,0),(5,21,348,0,0),(5,21,349,0,0),(5,21,350,0,0),(5,21,351,0,0),(5,21,352,0,0),(5,21,353,0,0),(5,21,354,0,0),(5,21,355,0,0),(5,21,356,0,0),(5,21,357,0,0),(5,21,358,0,0),(5,21,359,0,0),(5,21,360,0,1),(5,21,361,0,1),(5,21,362,0,0),(5,21,363,0,0),(5,21,364,0,0),(5,21,365,0,0),(5,21,366,0,0),(5,21,367,0,0),(5,21,368,0,0),(5,21,369,0,0),(5,21,370,0,0),(5,21,371,0,0),(5,21,372,0,0),(5,21,373,0,0),(5,21,374,0,0),(5,21,375,0,0),(5,21,376,0,0),(5,21,377,0,0),(5,21,378,0,0),(5,22,379,0,0),(5,22,380,0,0),(5,22,381,0,0),(5,22,382,0,0),(5,22,383,0,0),(5,22,384,0,0),(5,22,385,0,0),(5,22,386,0,0),(5,22,387,0,0),(5,22,388,0,0),(5,22,389,0,0),(5,22,390,0,0),(5,22,391,0,0),(5,22,392,0,0),(5,22,393,0,0),(5,22,394,0,0),(5,22,395,0,0),(5,22,396,0,0),(5,22,397,0,0),(5,22,398,0,0),(5,22,399,0,0),(5,22,400,0,0),(5,22,401,0,1),(5,22,402,0,1),(5,22,403,0,0),(5,22,404,0,0),(5,22,405,0,0),(5,22,406,0,0),(5,22,407,0,0),(5,22,408,0,0),(5,22,409,0,0),(5,22,410,0,0),(5,22,411,0,0),(5,22,412,0,0),(5,22,413,0,0),(5,22,414,0,0),(5,22,415,0,0),(5,22,416,0,0),(5,22,417,0,0),(5,22,418,0,0),(5,22,419,0,0),(5,22,420,0,0),(5,22,421,0,0),(5,22,422,0,0),(5,22,423,0,0),(5,22,424,0,0),(5,22,425,0,0),(5,23,426,0,0),(5,23,427,0,0),(5,23,428,0,0),(5,23,429,0,0),(5,23,430,0,0),(5,23,431,0,0),(5,23,432,0,0),(5,23,433,0,0),(5,23,434,0,0),(5,23,435,0,0),(5,23,436,0,0),(5,23,437,0,0),(5,23,438,0,0),(5,23,439,0,0),(5,23,440,0,0),(5,23,441,0,0),(5,23,442,0,0),(5,23,443,0,0),(5,23,444,0,0),(5,23,445,0,1),(5,23,446,0,1),(5,23,447,0,0),(5,23,448,0,0),(5,23,449,0,0),(5,23,450,0,0),(5,23,451,0,0),(5,23,452,0,0),(5,23,453,0,0),(5,23,454,0,0),(5,23,455,0,0),(5,23,456,0,0),(5,23,457,0,0),(5,23,458,0,0),(5,23,459,0,0),(5,23,460,0,0),(5,23,461,0,0),(5,23,462,0,0),(5,23,463,0,0),(5,23,464,0,0),(5,29,465,0,0),(5,29,469,0,0),(5,29,470,0,0),(5,29,471,0,0),(5,29,472,0,0),(5,29,479,0,0),(5,29,480,0,0),(5,29,481,0,0),(5,29,482,0,0),(5,29,483,0,0),(5,29,484,0,0),(5,29,485,0,0),(5,29,486,0,0),(5,29,487,0,0),(5,29,488,0,0),(5,29,489,0,0),(5,29,494,0,0),(5,29,495,0,0),(5,29,496,0,0),(5,29,497,0,0),(5,29,500,0,0),(5,10,512,0,0),(5,10,513,0,0),(5,10,514,0,0),(5,10,515,0,0),(5,10,516,0,0),(5,10,517,0,0),(5,36,518,0,0),(5,36,519,0,0),(5,36,520,0,0),(5,36,521,0,0),(5,36,522,0,0),(5,36,523,0,0),(5,36,524,0,0),(5,36,525,0,0),(5,36,526,0,1),(5,36,527,0,1),(5,36,528,0,0),(5,36,529,0,0),(5,36,530,0,0),(5,36,531,0,0),(5,36,532,0,0),(5,36,533,0,0),(5,36,535,0,0),(5,36,536,0,0),(5,37,537,0,0),(5,37,538,0,0),(5,37,539,0,0),(5,37,540,0,0),(5,37,541,0,0),(5,37,542,0,0),(5,37,543,0,0),(5,37,544,0,0),(5,37,545,0,0),(5,37,546,0,0),(5,37,547,0,0),(5,37,548,0,0),(5,37,549,0,0),(5,37,550,0,0),(5,37,551,0,1),(5,37,552,0,1),(5,29,553,0,0),(5,42,554,0,0),(5,42,555,0,0),(5,42,556,0,0),(5,42,557,0,0),(5,42,558,0,0),(5,42,559,0,0),(5,42,560,0,1),(5,42,561,0,1),(5,42,562,0,0),(5,42,563,0,0),(5,43,564,0,0),(5,43,565,0,0),(5,43,566,0,0),(5,43,567,0,0),(5,43,568,0,0),(5,43,569,0,0),(5,43,570,0,0),(5,43,571,0,0),(5,43,572,0,0),(5,43,573,0,0),(5,43,574,0,0),(5,43,575,0,1),(5,43,576,0,1),(5,43,577,0,0),(5,43,578,0,0),(5,44,579,0,0),(5,44,580,0,0),(5,44,581,0,0),(5,44,582,0,0),(5,44,583,0,0),(5,44,584,0,0),(5,44,585,0,0),(5,44,586,0,0),(5,44,587,0,0),(5,44,588,0,0),(5,44,589,0,0),(5,44,590,0,0),(5,44,591,0,0),(5,44,592,0,1),(5,44,593,0,1),(5,44,594,0,0),(5,44,595,0,0),(5,47,596,0,0),(5,47,597,0,0),(5,47,598,0,1),(5,47,599,0,1),(5,47,600,0,0),(5,47,601,0,1),(5,47,602,0,0),(5,2,603,0,0),(5,23,605,0,0),(5,23,606,0,0),(5,23,607,0,0),(5,23,608,0,0),(5,23,609,0,0),(5,23,610,0,0),(5,22,614,0,0),(5,22,615,0,0),(5,22,616,0,0),(5,22,617,0,0),(5,22,618,0,0),(5,22,619,0,0),(5,21,623,0,0),(5,21,624,0,0),(5,21,625,0,0),(5,21,626,0,0),(5,21,627,0,0),(5,21,628,0,0),(5,20,632,0,0),(5,20,633,0,0),(5,20,634,0,0),(5,20,635,0,0),(5,20,636,0,0),(5,20,637,0,0),(5,23,647,0,0),(5,22,648,0,0),(5,21,649,0,0),(5,20,650,0,0),(5,6,652,0,1),(5,4,653,0,1),(5,2,654,0,1),(5,23,656,0,0),(5,23,657,0,0),(5,21,658,0,0),(5,21,659,0,0),(5,7,660,0,0),(5,23,663,0,0),(5,20,664,0,0),(5,21,665,0,0),(5,22,666,0,0),(5,2,668,0,0),(5,13,669,0,0),(5,14,696,0,0),(5,23,698,0,0),(5,2,713,0,1),(5,4,714,0,1),(5,6,715,0,1),(5,7,716,0,1),(5,8,717,0,1),(5,9,718,0,1),(5,10,719,0,1),(5,13,720,0,1),(5,14,721,0,1),(5,16,722,0,1),(5,18,723,0,1),(5,19,724,0,1),(5,20,725,0,1),(5,21,726,0,1),(5,22,727,0,1),(5,23,728,0,1),(5,26,729,0,1),(5,10,735,0,0),(5,2,736,0,0),(5,4,737,0,0),(5,6,738,0,0),(5,7,739,0,0),(5,8,740,0,0),(5,9,741,0,0),(5,10,742,0,0),(5,13,743,0,0),(5,14,744,0,0),(5,16,745,0,1),(5,18,746,0,0),(5,19,747,0,0),(5,20,748,0,0),(5,21,749,0,0),(5,22,750,0,0),(5,23,751,0,0),(5,26,752,0,0),(5,2,758,0,0),(5,4,759,0,0),(5,6,760,0,0),(5,7,761,0,0),(5,8,762,0,0),(5,9,763,0,0),(5,10,764,0,0),(5,13,765,0,0),(5,14,766,0,0),(5,16,767,0,1),(5,18,768,0,0),(5,19,769,0,0),(5,20,770,0,0),(5,21,771,0,0),(5,22,772,0,0),(5,23,773,0,0),(5,26,774,0,0),(5,20,780,0,0),(5,21,781,0,0),(5,22,782,0,0),(5,23,783,0,0),(5,47,784,0,0),(5,47,785,0,0),(5,47,786,0,0),(5,47,787,0,0),(5,47,788,0,0),(5,47,789,0,0),(5,43,790,0,0),(5,43,791,0,0),(5,43,792,0,1),(5,43,793,0,0),(5,44,794,0,1),(5,44,795,0,0),(5,44,796,0,0),(5,44,797,0,1),(5,44,798,0,0),(5,42,800,0,1),(5,42,801,0,0),(5,42,802,0,0),(6,6,1,0,0),(6,6,2,0,0),(6,6,3,0,0),(6,6,4,0,0),(6,6,5,0,0),(6,6,6,0,0),(6,6,7,0,0),(6,6,8,0,0),(6,6,9,0,0),(6,6,10,0,0),(6,6,11,0,0),(6,6,12,0,0),(6,6,13,0,0),(6,6,14,0,0),(6,6,15,0,0),(6,6,16,0,0),(6,6,17,0,0),(6,6,18,0,0),(6,6,19,0,0),(6,6,20,0,0),(6,6,21,0,1),(6,6,22,0,1),(6,6,23,0,0),(6,6,24,0,0),(6,6,25,0,0),(6,6,26,0,0),(6,6,27,0,0),(6,6,28,0,0),(6,6,29,0,0),(6,6,30,0,0),(6,6,31,0,0),(6,6,32,0,0),(6,6,33,0,0),(6,6,34,0,0),(6,6,35,0,0),(6,6,36,0,0),(6,7,37,0,0),(6,7,38,0,0),(6,7,39,0,0),(6,7,40,0,0),(6,7,41,0,0),(6,7,42,0,0),(6,7,43,0,0),(6,7,44,0,0),(6,7,45,0,0),(6,7,46,0,0),(6,7,47,0,0),(6,7,48,0,0),(6,7,49,0,0),(6,7,50,0,0),(6,7,51,0,0),(6,7,52,0,0),(6,7,53,0,0),(6,7,54,0,0),(6,7,55,0,0),(6,7,56,0,1),(6,7,57,0,1),(6,7,58,0,0),(6,7,59,0,0),(6,7,60,0,0),(6,7,61,0,0),(6,7,62,0,0),(6,7,63,0,0),(6,7,64,0,0),(6,7,65,0,0),(6,4,66,0,0),(6,4,67,0,0),(6,4,68,0,0),(6,4,69,0,0),(6,4,70,0,0),(6,4,71,0,0),(6,4,72,0,0),(6,4,73,0,0),(6,4,74,0,0),(6,4,75,0,0),(6,4,76,0,0),(6,4,77,0,0),(6,4,78,0,0),(6,4,79,0,0),(6,4,80,0,0),(6,4,81,0,0),(6,4,82,0,0),(6,4,83,0,0),(6,4,84,0,0),(6,4,85,0,0),(6,4,86,0,0),(6,4,87,0,0),(6,4,88,0,0),(6,4,89,0,0),(6,4,90,0,1),(6,4,91,0,1),(6,4,92,0,0),(6,4,96,0,0),(6,4,97,0,0),(6,4,98,0,0),(6,4,99,0,0),(6,4,100,0,0),(6,4,101,0,0),(6,4,102,0,0),(6,4,103,0,0),(6,4,104,0,0),(6,4,105,0,0),(6,4,106,0,0),(6,4,107,0,0),(6,4,108,0,0),(6,4,109,0,0),(6,2,110,0,0),(6,2,111,0,0),(6,2,112,0,0),(6,2,113,0,0),(6,2,114,0,0),(6,2,115,0,0),(6,2,116,0,0),(6,2,117,0,0),(6,2,118,0,0),(6,2,119,0,0),(6,2,120,0,0),(6,2,121,0,0),(6,2,122,0,1),(6,2,123,0,1),(6,2,124,0,0),(6,2,125,0,0),(6,26,126,0,0),(6,26,127,0,0),(6,26,128,0,0),(6,26,129,0,0),(6,26,130,0,0),(6,26,131,0,0),(6,26,132,0,0),(6,26,133,0,0),(6,26,134,0,0),(6,26,135,0,0),(6,26,136,0,0),(6,26,137,0,1),(6,26,138,0,1),(6,26,139,0,0),(6,26,140,0,0),(6,26,141,0,0),(6,26,142,0,0),(6,26,143,0,0),(6,26,144,0,0),(6,26,145,0,0),(6,26,146,0,0),(6,26,147,0,0),(6,26,148,0,0),(6,26,149,0,0),(6,26,150,0,0),(6,13,155,0,0),(6,13,156,0,0),(6,13,157,0,0),(6,13,158,0,0),(6,13,159,0,0),(6,13,160,0,0),(6,13,161,0,0),(6,13,162,0,0),(6,13,164,0,0),(6,13,165,0,0),(6,13,166,0,1),(6,13,167,0,1),(6,13,168,0,0),(6,13,169,0,0),(6,13,170,0,0),(6,13,171,0,0),(6,13,172,0,0),(6,14,174,0,0),(6,14,175,0,0),(6,14,176,0,0),(6,14,177,0,0),(6,14,178,0,0),(6,14,179,0,0),(6,14,180,0,0),(6,14,181,0,0),(6,14,182,0,0),(6,14,183,0,0),(6,14,184,0,0),(6,14,185,0,0),(6,14,186,0,0),(6,14,187,0,0),(6,14,188,0,0),(6,14,189,0,0),(6,14,190,0,0),(6,14,191,0,1),(6,14,192,0,1),(6,14,193,0,0),(6,14,194,0,0),(6,14,195,0,0),(6,14,197,0,0),(6,14,198,0,0),(6,14,199,0,0),(6,14,200,0,0),(6,14,201,0,0),(6,14,202,0,0),(6,14,203,0,0),(6,14,204,0,0),(6,8,205,0,0),(6,8,206,0,1),(6,8,207,0,1),(6,8,208,0,0),(6,8,209,0,0),(6,8,210,0,0),(6,8,211,0,1),(6,8,212,0,1),(6,8,213,0,0),(6,8,214,0,0),(6,8,215,0,0),(6,8,216,0,1),(6,8,217,0,0),(6,8,218,0,0),(6,8,219,0,0),(6,10,220,0,0),(6,10,221,0,0),(6,10,222,0,0),(6,10,223,0,0),(6,10,224,0,0),(6,10,225,0,0),(6,10,226,0,0),(6,10,227,0,0),(6,10,228,0,0),(6,10,229,0,1),(6,10,230,0,0),(6,10,231,0,0),(6,9,232,0,0),(6,9,233,0,0),(6,9,234,0,0),(6,9,235,0,0),(6,9,236,0,0),(6,9,237,0,0),(6,9,238,0,0),(6,9,239,0,0),(6,9,240,0,0),(6,9,241,0,0),(6,9,242,0,0),(6,9,243,0,0),(6,9,244,0,1),(6,9,245,0,1),(6,9,246,0,0),(6,9,247,0,0),(6,9,248,0,0),(6,9,249,0,0),(6,9,250,0,0),(6,9,251,0,0),(6,9,252,0,0),(6,9,253,0,0),(6,9,254,0,0),(6,9,255,0,0),(6,16,256,0,0),(6,16,257,0,0),(6,16,258,0,0),(6,16,259,0,0),(6,16,260,0,0),(6,16,261,0,0),(6,16,262,0,0),(6,16,263,0,0),(6,16,264,0,0),(6,16,265,0,0),(6,16,266,0,0),(6,16,267,0,0),(6,16,268,0,0),(6,16,269,0,0),(6,16,270,0,1),(6,16,271,0,1),(6,16,272,0,0),(6,16,273,0,0),(6,16,274,0,0),(6,16,275,0,0),(6,16,276,0,0),(6,16,277,0,0),(6,16,278,0,0),(6,18,279,0,0),(6,18,280,0,0),(6,18,281,0,0),(6,18,282,0,0),(6,18,283,0,0),(6,18,284,0,0),(6,18,285,0,0),(6,18,286,0,1),(6,18,287,0,1),(6,18,288,0,0),(6,18,289,0,0),(6,18,290,0,0),(6,18,291,0,0),(6,18,292,0,0),(6,18,293,0,0),(6,18,294,0,0),(6,18,295,0,0),(6,19,296,0,0),(6,19,297,0,0),(6,19,298,0,0),(6,19,299,0,1),(6,19,300,0,1),(6,19,301,0,0),(6,19,302,0,0),(6,19,303,0,0),(6,20,304,0,0),(6,20,305,0,0),(6,20,306,0,0),(6,20,307,0,0),(6,20,308,0,0),(6,20,309,0,0),(6,20,310,0,0),(6,20,311,0,0),(6,20,312,0,0),(6,20,313,0,0),(6,20,314,0,0),(6,20,315,0,0),(6,20,316,0,0),(6,20,317,0,0),(6,20,318,0,0),(6,20,319,0,0),(6,20,320,0,0),(6,20,321,0,0),(6,20,322,0,1),(6,20,323,0,1),(6,20,324,0,0),(6,20,325,0,0),(6,20,326,0,0),(6,20,327,0,0),(6,20,328,0,0),(6,20,329,0,0),(6,20,330,0,0),(6,20,331,0,0),(6,20,332,0,0),(6,20,333,0,0),(6,20,334,0,0),(6,20,335,0,0),(6,20,336,0,0),(6,20,337,0,0),(6,20,338,0,0),(6,20,339,0,0),(6,20,340,0,0),(6,21,341,0,0),(6,21,342,0,0),(6,21,343,0,0),(6,21,344,0,0),(6,21,345,0,0),(6,21,346,0,0),(6,21,347,0,0),(6,21,348,0,0),(6,21,349,0,0),(6,21,350,0,0),(6,21,351,0,0),(6,21,352,0,0),(6,21,353,0,0),(6,21,354,0,0),(6,21,355,0,0),(6,21,356,0,0),(6,21,357,0,0),(6,21,358,0,0),(6,21,359,0,0),(6,21,360,0,1),(6,21,361,0,1),(6,21,362,0,0),(6,21,363,0,0),(6,21,364,0,0),(6,21,365,0,0),(6,21,366,0,0),(6,21,367,0,0),(6,21,368,0,0),(6,21,369,0,0),(6,21,370,0,0),(6,21,371,0,0),(6,21,372,0,0),(6,21,373,0,0),(6,21,374,0,0),(6,21,375,0,0),(6,21,376,0,0),(6,21,377,0,0),(6,21,378,0,0),(6,22,379,0,0),(6,22,380,0,0),(6,22,381,0,0),(6,22,382,0,0),(6,22,383,0,0),(6,22,384,0,0),(6,22,385,0,0),(6,22,386,0,0),(6,22,387,0,0),(6,22,388,0,0),(6,22,389,0,0),(6,22,390,0,0),(6,22,391,0,0),(6,22,392,0,0),(6,22,393,0,0),(6,22,394,0,0),(6,22,395,0,0),(6,22,396,0,0),(6,22,397,0,0),(6,22,398,0,0),(6,22,399,0,0),(6,22,400,0,0),(6,22,401,0,1),(6,22,402,0,1),(6,22,403,0,0),(6,22,404,0,0),(6,22,405,0,0),(6,22,406,0,0),(6,22,407,0,0),(6,22,408,0,0),(6,22,409,0,0),(6,22,410,0,0),(6,22,411,0,0),(6,22,412,0,0),(6,22,413,0,0),(6,22,414,0,0),(6,22,415,0,0),(6,22,416,0,0),(6,22,417,0,0),(6,22,418,0,0),(6,22,419,0,0),(6,22,420,0,0),(6,22,421,0,0),(6,22,422,0,0),(6,22,423,0,0),(6,22,424,0,0),(6,22,425,0,0),(6,23,426,0,0),(6,23,427,0,0),(6,23,428,0,0),(6,23,429,0,0),(6,23,430,0,0),(6,23,431,0,0),(6,23,432,0,0),(6,23,433,0,0),(6,23,434,0,0),(6,23,435,0,0),(6,23,436,0,0),(6,23,437,0,0),(6,23,438,0,0),(6,23,439,0,0),(6,23,440,0,0),(6,23,441,0,0),(6,23,442,0,0),(6,23,443,0,0),(6,23,444,0,0),(6,23,445,0,1),(6,23,446,0,1),(6,23,447,0,0),(6,23,448,0,0),(6,23,449,0,0),(6,23,450,0,0),(6,23,451,0,0),(6,23,452,0,0),(6,23,453,0,0),(6,23,454,0,0),(6,23,455,0,0),(6,23,456,0,0),(6,23,457,0,0),(6,23,458,0,0),(6,23,459,0,0),(6,23,460,0,0),(6,23,461,0,0),(6,23,462,0,0),(6,23,463,0,0),(6,23,464,0,0),(6,29,465,0,0),(6,29,469,0,0),(6,29,470,0,0),(6,29,471,0,0),(6,29,472,0,0),(6,29,479,0,0),(6,29,480,0,0),(6,29,481,0,0),(6,29,482,0,0),(6,29,483,0,0),(6,29,484,0,0),(6,29,489,0,0),(6,29,494,0,0),(6,29,495,0,0),(6,29,496,0,0),(6,29,497,0,0),(6,29,500,0,0),(6,10,512,0,0),(6,10,513,0,0),(6,10,514,0,0),(6,10,515,0,0),(6,10,516,0,0),(6,10,517,0,0),(6,36,518,0,0),(6,36,519,0,0),(6,36,520,0,0),(6,36,521,0,0),(6,36,522,0,0),(6,36,523,0,0),(6,36,524,0,0),(6,36,525,0,0),(6,36,526,0,1),(6,36,527,0,1),(6,36,528,0,0),(6,36,529,0,0),(6,36,530,0,0),(6,36,531,0,0),(6,36,532,0,0),(6,36,533,0,0),(6,36,535,0,0),(6,36,536,0,0),(6,37,537,0,0),(6,37,538,0,0),(6,37,539,0,0),(6,37,540,0,0),(6,37,541,0,0),(6,37,542,0,0),(6,37,543,0,0),(6,37,544,0,0),(6,37,545,0,0),(6,37,546,0,0),(6,37,547,0,0),(6,37,548,0,0),(6,37,549,0,0),(6,37,550,0,0),(6,37,551,0,1),(6,37,552,0,1),(6,29,553,0,0),(6,42,554,0,0),(6,42,555,0,0),(6,42,556,0,0),(6,42,557,0,0),(6,42,558,0,0),(6,42,559,0,0),(6,42,560,0,1),(6,42,561,0,1),(6,42,562,0,0),(6,42,563,0,0),(6,43,564,0,0),(6,43,565,0,0),(6,43,566,0,0),(6,43,567,0,0),(6,43,568,0,0),(6,43,569,0,0),(6,43,570,0,0),(6,43,571,0,0),(6,43,572,0,0),(6,43,573,0,0),(6,43,574,0,0),(6,43,575,0,1),(6,43,576,0,1),(6,43,577,0,0),(6,43,578,0,0),(6,44,579,0,0),(6,44,580,0,0),(6,44,581,0,0),(6,44,582,0,0),(6,44,583,0,0),(6,44,584,0,0),(6,44,585,0,0),(6,44,586,0,0),(6,44,587,0,0),(6,44,588,0,0),(6,44,589,0,0),(6,44,590,0,0),(6,44,591,0,0),(6,44,592,0,1),(6,44,593,0,1),(6,44,594,0,0),(6,44,595,0,0),(6,47,596,0,0),(6,47,597,0,0),(6,47,598,0,1),(6,47,599,0,1),(6,47,600,0,0),(6,47,601,0,1),(6,47,602,0,0),(6,2,603,0,0),(6,23,605,0,0),(6,23,606,0,0),(6,23,607,0,0),(6,23,608,0,0),(6,23,609,0,0),(6,23,610,0,0),(6,22,614,0,0),(6,22,615,0,0),(6,22,616,0,0),(6,22,617,0,0),(6,22,618,0,0),(6,22,619,0,0),(6,21,623,0,0),(6,21,624,0,0),(6,21,625,0,0),(6,21,626,0,0),(6,21,627,0,0),(6,21,628,0,0),(6,20,632,0,0),(6,20,633,0,0),(6,20,634,0,0),(6,20,635,0,0),(6,20,636,0,0),(6,20,637,0,0),(6,23,647,0,0),(6,22,648,0,0),(6,21,649,0,0),(6,20,650,0,0),(6,6,652,0,1),(6,4,653,0,1),(6,2,654,0,1),(6,23,656,0,0),(6,23,657,0,0),(6,21,658,0,0),(6,21,659,0,0),(6,7,660,0,0),(6,23,663,0,0),(6,20,664,0,0),(6,21,665,0,0),(6,22,666,0,0),(6,2,668,0,0),(6,13,669,0,0),(6,14,696,0,0),(6,23,698,0,0),(6,2,713,0,1),(6,4,714,0,1),(6,6,715,0,1),(6,7,716,0,1),(6,8,717,0,1),(6,9,718,0,1),(6,10,719,0,1),(6,13,720,0,1),(6,14,721,0,1),(6,16,722,0,1),(6,18,723,0,1),(6,19,724,0,1),(6,20,725,0,1),(6,21,726,0,1),(6,22,727,0,1),(6,23,728,0,1),(6,26,729,0,1),(6,10,735,0,0),(6,2,736,0,0),(6,4,737,0,0),(6,6,738,0,0),(6,7,739,0,0),(6,8,740,0,0),(6,9,741,0,0),(6,10,742,0,0),(6,13,743,0,0),(6,14,744,0,0),(6,16,745,0,1),(6,18,746,0,0),(6,19,747,0,0),(6,20,748,0,0),(6,21,749,0,0),(6,22,750,0,0),(6,23,751,0,0),(6,26,752,0,0),(6,2,758,0,0),(6,4,759,0,0),(6,6,760,0,0),(6,7,761,0,0),(6,8,762,0,0),(6,9,763,0,0),(6,10,764,0,0),(6,13,765,0,0),(6,14,766,0,0),(6,16,767,0,1),(6,18,768,0,0),(6,19,769,0,0),(6,20,770,0,0),(6,21,771,0,0),(6,22,772,0,0),(6,23,773,0,0),(6,26,774,0,0),(6,20,780,0,0),(6,21,781,0,0),(6,22,782,0,0),(6,23,783,0,0),(6,47,784,0,0),(6,47,785,0,0),(6,47,786,0,0),(6,47,787,0,0),(6,47,788,0,0),(6,47,789,0,0),(6,43,790,0,0),(6,43,791,0,0),(6,43,792,0,1),(6,43,793,0,0),(6,44,794,0,1),(6,44,795,0,0),(6,44,796,0,0),(6,44,797,0,1),(6,44,798,0,0),(6,42,800,0,1),(6,42,801,0,0),(6,42,802,0,0),(7,6,1,0,0),(7,6,2,0,0),(7,6,3,0,0),(7,6,4,0,0),(7,6,5,0,0),(7,6,6,0,0),(7,6,7,0,0),(7,6,8,0,0),(7,6,9,0,0),(7,6,10,0,0),(7,6,11,0,0),(7,6,12,0,0),(7,6,13,0,0),(7,6,14,0,0),(7,6,15,0,0),(7,6,16,0,0),(7,6,17,0,0),(7,6,18,0,0),(7,6,19,0,0),(7,6,20,0,0),(7,6,21,0,1),(7,6,22,0,1),(7,6,23,0,0),(7,6,24,0,0),(7,6,25,0,0),(7,6,26,0,0),(7,6,27,0,0),(7,6,28,0,0),(7,6,29,0,0),(7,6,30,0,0),(7,6,31,0,0),(7,6,32,0,0),(7,6,33,0,0),(7,6,34,0,0),(7,6,35,0,0),(7,6,36,0,0),(7,7,37,0,0),(7,7,38,0,0),(7,7,39,0,0),(7,7,40,0,0),(7,7,41,0,0),(7,7,42,0,0),(7,7,43,0,0),(7,7,44,0,0),(7,7,45,0,0),(7,7,46,0,0),(7,7,47,0,0),(7,7,48,0,0),(7,7,49,0,0),(7,7,50,0,0),(7,7,51,0,0),(7,7,52,0,0),(7,7,53,0,0),(7,7,54,0,0),(7,7,55,0,0),(7,7,56,0,1),(7,7,57,0,1),(7,7,58,0,0),(7,7,59,0,0),(7,7,60,0,0),(7,7,61,0,0),(7,7,62,0,0),(7,7,63,0,0),(7,7,64,0,0),(7,7,65,0,0),(7,4,66,0,0),(7,4,67,0,0),(7,4,68,0,0),(7,4,69,0,0),(7,4,70,0,0),(7,4,71,0,0),(7,4,72,0,0),(7,4,73,0,0),(7,4,74,0,0),(7,4,75,0,0),(7,4,76,0,0),(7,4,77,0,0),(7,4,78,0,0),(7,4,79,0,0),(7,4,80,0,0),(7,4,81,0,0),(7,4,82,0,0),(7,4,83,0,0),(7,4,84,0,0),(7,4,85,0,0),(7,4,86,0,0),(7,4,87,0,0),(7,4,88,0,0),(7,4,89,0,0),(7,4,90,0,1),(7,4,91,0,1),(7,4,92,0,0),(7,4,96,0,0),(7,4,97,0,0),(7,4,98,0,0),(7,4,99,0,0),(7,4,100,0,0),(7,4,101,0,0),(7,4,102,0,0),(7,4,103,0,0),(7,4,104,0,0),(7,4,105,0,0),(7,4,106,0,0),(7,4,107,0,0),(7,4,108,0,0),(7,4,109,0,0),(7,2,110,0,0),(7,2,111,0,0),(7,2,112,0,0),(7,2,113,0,0),(7,2,114,0,0),(7,2,115,0,0),(7,2,116,0,0),(7,2,117,0,0),(7,2,118,0,0),(7,2,119,0,0),(7,2,120,0,0),(7,2,121,0,0),(7,2,122,0,1),(7,2,123,0,1),(7,2,124,0,0),(7,2,125,0,0),(7,26,126,0,0),(7,26,127,0,0),(7,26,128,0,0),(7,26,129,0,0),(7,26,130,0,0),(7,26,131,0,0),(7,26,132,0,0),(7,26,133,0,0),(7,26,134,0,0),(7,26,135,0,0),(7,26,136,0,0),(7,26,137,0,1),(7,26,138,0,1),(7,26,139,0,0),(7,26,140,0,0),(7,26,141,0,0),(7,26,142,0,0),(7,26,143,0,0),(7,26,144,0,0),(7,26,145,0,0),(7,26,146,0,0),(7,26,147,0,0),(7,26,148,0,0),(7,26,149,0,0),(7,26,150,0,0),(7,13,155,0,0),(7,13,156,0,0),(7,13,157,0,0),(7,13,158,0,0),(7,13,159,0,0),(7,13,160,0,0),(7,13,161,0,0),(7,13,162,0,0),(7,13,164,0,0),(7,13,165,0,0),(7,13,166,0,1),(7,13,167,0,1),(7,13,168,0,0),(7,13,169,0,0),(7,13,170,0,0),(7,13,171,0,0),(7,13,172,0,0),(7,14,174,0,0),(7,14,175,0,0),(7,14,176,0,0),(7,14,177,0,0),(7,14,178,0,0),(7,14,179,0,0),(7,14,180,0,0),(7,14,181,0,0),(7,14,182,0,0),(7,14,183,0,0),(7,14,184,0,0),(7,14,185,0,0),(7,14,186,0,0),(7,14,187,0,0),(7,14,188,0,0),(7,14,189,0,0),(7,14,190,0,0),(7,14,191,0,1),(7,14,192,0,1),(7,14,193,0,0),(7,14,194,0,0),(7,14,195,0,0),(7,14,197,0,0),(7,14,198,0,0),(7,14,199,0,0),(7,14,200,0,0),(7,14,201,0,0),(7,14,202,0,0),(7,14,203,0,0),(7,14,204,0,0),(7,8,205,0,0),(7,8,206,0,1),(7,8,207,0,1),(7,8,208,0,0),(7,8,209,0,0),(7,8,210,0,0),(7,8,211,0,1),(7,8,212,0,1),(7,8,213,0,0),(7,8,214,0,0),(7,8,215,0,0),(7,8,216,0,1),(7,8,217,0,0),(7,8,218,0,0),(7,8,219,0,0),(7,10,220,0,0),(7,10,221,0,0),(7,10,222,0,0),(7,10,223,0,0),(7,10,224,0,0),(7,10,225,0,0),(7,10,226,0,0),(7,10,227,0,0),(7,10,228,0,0),(7,10,229,0,1),(7,10,230,0,0),(7,10,231,0,0),(7,9,232,0,0),(7,9,233,0,0),(7,9,234,0,0),(7,9,235,0,0),(7,9,236,0,0),(7,9,237,0,0),(7,9,238,0,0),(7,9,239,0,0),(7,9,240,0,0),(7,9,241,0,0),(7,9,242,0,0),(7,9,243,0,0),(7,9,244,0,1),(7,9,245,0,1),(7,9,246,0,0),(7,9,247,0,0),(7,9,248,0,0),(7,9,249,0,0),(7,9,250,0,0),(7,9,251,0,0),(7,9,252,0,0),(7,9,253,0,0),(7,9,254,0,0),(7,9,255,0,0),(7,16,256,0,0),(7,16,257,0,0),(7,16,258,0,0),(7,16,259,0,0),(7,16,260,0,0),(7,16,261,0,0),(7,16,262,0,0),(7,16,263,0,0),(7,16,264,0,0),(7,16,265,0,0),(7,16,266,0,0),(7,16,267,0,0),(7,16,268,0,0),(7,16,269,0,0),(7,16,270,0,1),(7,16,271,0,1),(7,16,272,0,0),(7,16,273,0,0),(7,16,274,0,0),(7,16,275,0,0),(7,16,276,0,0),(7,16,277,0,0),(7,16,278,0,0),(7,18,279,0,0),(7,18,280,0,0),(7,18,281,0,0),(7,18,282,0,0),(7,18,283,0,0),(7,18,284,0,0),(7,18,285,0,0),(7,18,286,0,1),(7,18,287,0,1),(7,18,288,0,0),(7,18,289,0,0),(7,18,290,0,0),(7,18,291,0,0),(7,18,292,0,0),(7,18,293,0,0),(7,18,294,0,0),(7,18,295,0,0),(7,19,296,0,0),(7,19,297,0,0),(7,19,298,0,0),(7,19,299,0,1),(7,19,300,0,1),(7,19,301,0,0),(7,19,302,0,0),(7,19,303,0,0),(7,20,304,0,0),(7,20,305,0,0),(7,20,306,0,0),(7,20,307,0,0),(7,20,308,0,0),(7,20,309,0,0),(7,20,310,0,0),(7,20,311,0,0),(7,20,312,0,0),(7,20,313,0,0),(7,20,314,0,0),(7,20,315,0,0),(7,20,316,0,0),(7,20,317,0,0),(7,20,318,0,0),(7,20,319,0,0),(7,20,320,0,0),(7,20,321,0,0),(7,20,322,0,1),(7,20,323,0,1),(7,20,324,0,0),(7,20,325,0,0),(7,20,326,0,0),(7,20,327,0,0),(7,20,328,0,0),(7,20,329,0,0),(7,20,330,0,0),(7,20,331,0,0),(7,20,332,0,0),(7,20,333,0,0),(7,20,334,0,0),(7,20,335,0,0),(7,20,336,0,0),(7,20,337,0,0),(7,20,338,0,0),(7,20,339,0,0),(7,20,340,0,0),(7,21,341,0,0),(7,21,342,0,0),(7,21,343,0,0),(7,21,344,0,0),(7,21,345,0,0),(7,21,346,0,0),(7,21,347,0,0),(7,21,348,0,0),(7,21,349,0,0),(7,21,350,0,0),(7,21,351,0,0),(7,21,352,0,0),(7,21,353,0,0),(7,21,354,0,0),(7,21,355,0,0),(7,21,356,0,0),(7,21,357,0,0),(7,21,358,0,0),(7,21,359,0,0),(7,21,360,0,1),(7,21,361,0,1),(7,21,362,0,0),(7,21,363,0,0),(7,21,364,0,0),(7,21,365,0,0),(7,21,366,0,0),(7,21,367,0,0),(7,21,368,0,0),(7,21,369,0,0),(7,21,370,0,0),(7,21,371,0,0),(7,21,372,0,0),(7,21,373,0,0),(7,21,374,0,0),(7,21,375,0,0),(7,21,376,0,0),(7,21,377,0,0),(7,21,378,0,0),(7,22,379,0,0),(7,22,380,0,0),(7,22,381,0,0),(7,22,382,0,0),(7,22,383,0,0),(7,22,384,0,0),(7,22,385,0,0),(7,22,386,0,0),(7,22,387,0,0),(7,22,388,0,0),(7,22,389,0,0),(7,22,390,0,0),(7,22,391,0,0),(7,22,392,0,0),(7,22,393,0,0),(7,22,394,0,0),(7,22,395,0,0),(7,22,396,0,0),(7,22,397,0,0),(7,22,398,0,0),(7,22,399,0,0),(7,22,400,0,0),(7,22,401,0,1),(7,22,402,0,1),(7,22,403,0,0),(7,22,404,0,0),(7,22,405,0,0),(7,22,406,0,0),(7,22,407,0,0),(7,22,408,0,0),(7,22,409,0,0),(7,22,410,0,0),(7,22,411,0,0),(7,22,412,0,0),(7,22,413,0,0),(7,22,414,0,0),(7,22,415,0,0),(7,22,416,0,0),(7,22,417,0,0),(7,22,418,0,0),(7,22,419,0,0),(7,22,420,0,0),(7,22,421,0,0),(7,22,422,0,0),(7,22,423,0,0),(7,22,424,0,0),(7,22,425,0,0),(7,23,426,0,0),(7,23,427,0,0),(7,23,428,0,0),(7,23,429,0,0),(7,23,430,0,0),(7,23,431,0,0),(7,23,432,0,0),(7,23,433,0,0),(7,23,434,0,0),(7,23,435,0,0),(7,23,436,0,0),(7,23,437,0,0),(7,23,438,0,0),(7,23,439,0,0),(7,23,440,0,0),(7,23,441,0,0),(7,23,442,0,0),(7,23,443,0,0),(7,23,444,0,0),(7,23,445,0,1),(7,23,446,0,1),(7,23,447,0,0),(7,23,448,0,0),(7,23,449,0,0),(7,23,450,0,0),(7,23,451,0,0),(7,23,452,0,0),(7,23,453,0,0),(7,23,454,0,0),(7,23,455,0,0),(7,23,456,0,0),(7,23,457,0,0),(7,23,458,0,0),(7,23,459,0,0),(7,23,460,0,0),(7,23,461,0,0),(7,23,462,0,0),(7,23,463,0,0),(7,23,464,0,0),(7,29,465,0,0),(7,29,469,0,0),(7,29,470,0,0),(7,29,471,0,0),(7,29,472,0,0),(7,29,479,0,0),(7,29,480,0,0),(7,29,481,0,0),(7,29,482,0,0),(7,29,483,0,0),(7,29,484,0,0),(7,29,489,0,0),(7,29,494,0,0),(7,29,495,0,0),(7,29,496,0,0),(7,29,497,0,0),(7,29,500,0,0),(7,10,512,0,0),(7,10,513,0,0),(7,10,514,0,0),(7,10,515,0,0),(7,10,516,0,0),(7,10,517,0,0),(7,36,518,0,0),(7,36,519,0,0),(7,36,520,0,0),(7,36,521,0,0),(7,36,522,0,0),(7,36,523,0,0),(7,36,524,0,0),(7,36,525,0,0),(7,36,526,0,1),(7,36,527,0,1),(7,36,528,0,0),(7,36,529,0,0),(7,36,530,0,0),(7,36,531,0,0),(7,36,532,0,0),(7,36,533,0,0),(7,36,535,0,0),(7,36,536,0,0),(7,37,537,0,0),(7,37,538,0,0),(7,37,539,0,0),(7,37,540,0,0),(7,37,541,0,0),(7,37,542,0,0),(7,37,543,0,0),(7,37,544,0,0),(7,37,545,0,0),(7,37,546,0,0),(7,37,547,0,0),(7,37,548,0,0),(7,37,549,0,0),(7,37,550,0,0),(7,37,551,0,1),(7,37,552,0,1),(7,29,553,0,0),(7,42,554,0,0),(7,42,555,0,0),(7,42,556,0,0),(7,42,557,0,0),(7,42,558,0,0),(7,42,559,0,0),(7,42,560,0,1),(7,42,561,0,1),(7,42,562,0,0),(7,42,563,0,0),(7,43,564,0,0),(7,43,565,0,0),(7,43,566,0,0),(7,43,567,0,0),(7,43,568,0,0),(7,43,569,0,0),(7,43,570,0,0),(7,43,571,0,0),(7,43,572,0,0),(7,43,573,0,0),(7,43,574,0,0),(7,43,575,0,1),(7,43,576,0,1),(7,43,577,0,0),(7,43,578,0,0),(7,44,579,0,0),(7,44,580,0,0),(7,44,581,0,0),(7,44,582,0,0),(7,44,583,0,0),(7,44,584,0,0),(7,44,585,0,0),(7,44,586,0,0),(7,44,587,0,0),(7,44,588,0,0),(7,44,589,0,0),(7,44,590,0,0),(7,44,591,0,0),(7,44,592,0,1),(7,44,593,0,1),(7,44,594,0,0),(7,44,595,0,0),(7,47,596,0,0),(7,47,597,0,0),(7,47,598,0,1),(7,47,599,0,1),(7,47,600,0,0),(7,47,601,0,1),(7,47,602,0,0),(7,2,603,0,0),(7,23,605,0,0),(7,23,606,0,0),(7,23,607,0,0),(7,23,608,0,0),(7,23,609,0,0),(7,23,610,0,0),(7,22,614,0,0),(7,22,615,0,0),(7,22,616,0,0),(7,22,617,0,0),(7,22,618,0,0),(7,22,619,0,0),(7,21,623,0,0),(7,21,624,0,0),(7,21,625,0,0),(7,21,626,0,0),(7,21,627,0,0),(7,21,628,0,0),(7,20,632,0,0),(7,20,633,0,0),(7,20,634,0,0),(7,20,635,0,0),(7,20,636,0,0),(7,20,637,0,0),(7,23,647,0,0),(7,22,648,0,0),(7,21,649,0,0),(7,20,650,0,0),(7,6,652,0,1),(7,4,653,0,1),(7,2,654,0,1),(7,23,656,0,0),(7,23,657,0,0),(7,21,658,0,0),(7,21,659,0,0),(7,7,660,0,0),(7,23,663,0,0),(7,20,664,0,0),(7,21,665,0,0),(7,22,666,0,0),(7,2,668,0,0),(7,13,669,0,0),(7,14,696,0,0),(7,23,698,0,0),(7,2,713,0,1),(7,4,714,0,1),(7,6,715,0,1),(7,7,716,0,1),(7,8,717,0,1),(7,9,718,0,1),(7,10,719,0,1),(7,13,720,0,1),(7,14,721,0,1),(7,16,722,0,1),(7,18,723,0,1),(7,19,724,0,1),(7,20,725,0,1),(7,21,726,0,1),(7,22,727,0,1),(7,23,728,0,1),(7,26,729,0,1),(7,10,735,0,0),(7,2,736,0,0),(7,4,737,0,0),(7,6,738,0,0),(7,7,739,0,0),(7,8,740,0,0),(7,9,741,0,0),(7,10,742,0,0),(7,13,743,0,0),(7,14,744,0,0),(7,16,745,0,1),(7,18,746,0,0),(7,19,747,0,0),(7,20,748,0,0),(7,21,749,0,0),(7,22,750,0,0),(7,23,751,0,0),(7,26,752,0,0),(7,2,758,0,0),(7,4,759,0,0),(7,6,760,0,0),(7,7,761,0,0),(7,8,762,0,0),(7,9,763,0,0),(7,10,764,0,0),(7,13,765,0,0),(7,14,766,0,0),(7,16,767,0,1),(7,18,768,0,0),(7,19,769,0,0),(7,20,770,0,0),(7,21,771,0,0),(7,22,772,0,0),(7,23,773,0,0),(7,26,774,0,0),(7,20,780,0,0),(7,21,781,0,0),(7,22,782,0,0),(7,23,783,0,0),(7,47,784,0,0),(7,47,785,0,0),(7,47,786,0,0),(7,47,787,0,0),(7,47,788,0,0),(7,47,789,0,0),(7,43,790,0,0),(7,43,791,0,0),(7,43,792,0,1),(7,43,793,0,0),(7,44,794,0,1),(7,44,795,0,0),(7,44,796,0,0),(7,44,797,0,1),(7,44,798,0,0),(7,42,800,0,1),(7,42,801,0,0),(7,42,802,0,0),(8,6,1,0,0),(8,6,2,0,0),(8,6,3,0,0),(8,6,4,0,0),(8,6,5,0,0),(8,6,6,0,0),(8,6,7,0,0),(8,6,8,0,0),(8,6,9,0,0),(8,6,10,0,0),(8,6,11,0,0),(8,6,12,0,0),(8,6,13,0,0),(8,6,14,0,0),(8,6,15,0,0),(8,6,16,0,0),(8,6,17,0,0),(8,6,18,0,0),(8,6,19,0,0),(8,6,20,0,0),(8,6,21,0,1),(8,6,22,0,1),(8,6,23,0,0),(8,6,24,0,0),(8,6,25,0,0),(8,6,26,0,0),(8,6,27,0,0),(8,6,28,0,0),(8,6,29,0,0),(8,6,30,0,0),(8,6,31,0,0),(8,6,32,0,0),(8,6,33,0,0),(8,6,34,0,0),(8,6,35,0,0),(8,6,36,0,0),(8,7,37,0,0),(8,7,38,0,0),(8,7,39,0,0),(8,7,40,0,0),(8,7,41,0,0),(8,7,42,0,0),(8,7,43,0,0),(8,7,44,0,0),(8,7,45,0,0),(8,7,46,0,0),(8,7,47,0,0),(8,7,48,0,0),(8,7,49,0,0),(8,7,50,0,0),(8,7,51,0,0),(8,7,52,0,0),(8,7,53,0,0),(8,7,54,0,0),(8,7,55,0,0),(8,7,56,0,1),(8,7,57,0,1),(8,7,58,0,0),(8,7,59,0,0),(8,7,60,0,0),(8,7,61,0,0),(8,7,62,0,0),(8,7,63,0,0),(8,7,64,0,0),(8,7,65,0,0),(8,4,66,0,0),(8,4,67,0,0),(8,4,68,0,0),(8,4,69,0,0),(8,4,70,0,0),(8,4,71,0,0),(8,4,72,0,0),(8,4,73,0,0),(8,4,74,0,0),(8,4,75,0,0),(8,4,76,0,0),(8,4,77,0,0),(8,4,78,0,0),(8,4,79,0,0),(8,4,80,0,0),(8,4,81,0,0),(8,4,82,0,0),(8,4,83,0,0),(8,4,84,0,0),(8,4,85,0,0),(8,4,86,0,0),(8,4,87,0,0),(8,4,88,0,0),(8,4,89,0,0),(8,4,90,0,1),(8,4,91,0,1),(8,4,92,0,0),(8,4,96,0,0),(8,4,97,0,0),(8,4,98,0,0),(8,4,99,0,0),(8,4,100,0,0),(8,4,101,0,0),(8,4,102,0,0),(8,4,103,0,0),(8,4,104,0,0),(8,4,105,0,0),(8,4,106,0,0),(8,4,107,0,0),(8,4,108,0,0),(8,4,109,0,0),(8,2,110,0,0),(8,2,111,0,0),(8,2,112,0,0),(8,2,113,0,0),(8,2,114,0,0),(8,2,115,0,0),(8,2,116,0,0),(8,2,117,0,0),(8,2,118,0,0),(8,2,119,0,0),(8,2,120,0,0),(8,2,121,0,0),(8,2,122,0,1),(8,2,123,0,1),(8,2,124,0,0),(8,2,125,0,0),(8,26,126,0,0),(8,26,127,0,0),(8,26,128,0,0),(8,26,129,0,0),(8,26,130,0,0),(8,26,131,0,0),(8,26,132,0,0),(8,26,133,0,0),(8,26,134,0,0),(8,26,135,0,0),(8,26,136,0,0),(8,26,137,0,1),(8,26,138,0,1),(8,26,139,0,0),(8,26,140,0,0),(8,26,141,0,0),(8,26,142,0,0),(8,26,143,0,0),(8,26,144,0,0),(8,26,145,0,0),(8,26,146,0,0),(8,26,147,0,0),(8,26,148,0,0),(8,26,149,0,0),(8,26,150,0,0),(8,13,155,0,0),(8,13,156,0,0),(8,13,157,0,0),(8,13,158,0,0),(8,13,159,0,0),(8,13,160,0,0),(8,13,161,0,0),(8,13,162,0,0),(8,13,164,0,0),(8,13,165,0,0),(8,13,166,0,1),(8,13,167,0,1),(8,13,168,0,0),(8,13,169,0,0),(8,13,170,0,0),(8,13,171,0,0),(8,13,172,0,0),(8,14,174,0,0),(8,14,175,0,0),(8,14,176,0,0),(8,14,177,0,0),(8,14,178,0,0),(8,14,179,0,0),(8,14,180,0,0),(8,14,181,0,0),(8,14,182,0,0),(8,14,183,0,0),(8,14,184,0,0),(8,14,185,0,0),(8,14,186,0,0),(8,14,187,0,0),(8,14,188,0,0),(8,14,189,0,0),(8,14,190,0,0),(8,14,191,0,1),(8,14,192,0,1),(8,14,193,0,0),(8,14,194,0,0),(8,14,195,0,0),(8,14,197,0,0),(8,14,198,0,0),(8,14,199,0,0),(8,14,200,0,0),(8,14,201,0,0),(8,14,202,0,0),(8,14,203,0,0),(8,14,204,0,0),(8,8,205,0,0),(8,8,206,0,1),(8,8,207,0,1),(8,8,208,0,0),(8,8,209,0,0),(8,8,210,0,0),(8,8,211,0,1),(8,8,212,0,1),(8,8,213,0,0),(8,8,214,0,0),(8,8,215,0,0),(8,8,216,0,1),(8,8,217,0,0),(8,8,218,0,0),(8,8,219,0,0),(8,10,220,0,0),(8,10,221,0,0),(8,10,222,0,0),(8,10,223,0,0),(8,10,224,0,0),(8,10,225,0,0),(8,10,226,0,0),(8,10,227,0,0),(8,10,228,0,0),(8,10,229,0,1),(8,10,230,0,0),(8,10,231,0,0),(8,9,232,0,0),(8,9,233,0,0),(8,9,234,0,0),(8,9,235,0,0),(8,9,236,0,0),(8,9,237,0,0),(8,9,238,0,0),(8,9,239,0,0),(8,9,240,0,0),(8,9,241,0,0),(8,9,242,0,0),(8,9,243,0,0),(8,9,244,0,1),(8,9,245,0,1),(8,9,246,0,0),(8,9,247,0,0),(8,9,248,0,0),(8,9,249,0,0),(8,9,250,0,0),(8,9,251,0,0),(8,9,252,0,0),(8,9,253,0,0),(8,9,254,0,0),(8,9,255,0,0),(8,16,256,0,0),(8,16,257,0,0),(8,16,258,0,0),(8,16,259,0,0),(8,16,260,0,0),(8,16,261,0,0),(8,16,262,0,0),(8,16,263,0,0),(8,16,264,0,0),(8,16,265,0,0),(8,16,266,0,0),(8,16,267,0,0),(8,16,268,0,0),(8,16,269,0,0),(8,16,270,0,1),(8,16,271,0,1),(8,16,272,0,0),(8,16,273,0,0),(8,16,274,0,0),(8,16,275,0,0),(8,16,276,0,0),(8,16,277,0,0),(8,16,278,0,0),(8,18,279,0,0),(8,18,280,0,0),(8,18,281,0,0),(8,18,282,0,0),(8,18,283,0,0),(8,18,284,0,0),(8,18,285,0,0),(8,18,286,0,1),(8,18,287,0,1),(8,18,288,0,0),(8,18,289,0,0),(8,18,290,0,0),(8,18,291,0,0),(8,18,292,0,0),(8,18,293,0,0),(8,18,294,0,0),(8,18,295,0,0),(8,19,296,0,0),(8,19,297,0,0),(8,19,298,0,0),(8,19,299,0,1),(8,19,300,0,1),(8,19,301,0,0),(8,19,302,0,0),(8,19,303,0,0),(8,20,304,0,0),(8,20,305,0,0),(8,20,306,0,0),(8,20,307,0,0),(8,20,308,0,0),(8,20,309,0,0),(8,20,310,0,0),(8,20,311,0,0),(8,20,312,0,0),(8,20,313,0,0),(8,20,314,0,0),(8,20,315,0,0),(8,20,316,0,0),(8,20,317,0,0),(8,20,318,0,0),(8,20,319,0,0),(8,20,320,0,0),(8,20,321,0,0),(8,20,322,0,1),(8,20,323,0,1),(8,20,324,0,0),(8,20,325,0,0),(8,20,326,0,0),(8,20,327,0,0),(8,20,328,0,0),(8,20,329,0,0),(8,20,330,0,0),(8,20,331,0,0),(8,20,332,0,0),(8,20,333,0,0),(8,20,334,0,0),(8,20,335,0,0),(8,20,336,0,0),(8,20,337,0,0),(8,20,338,0,0),(8,20,339,0,0),(8,20,340,0,0),(8,21,341,0,0),(8,21,342,0,0),(8,21,343,0,0),(8,21,344,0,0),(8,21,345,0,0),(8,21,346,0,0),(8,21,347,0,0),(8,21,348,0,0),(8,21,349,0,0),(8,21,350,0,0),(8,21,351,0,0),(8,21,352,0,0),(8,21,353,0,0),(8,21,354,0,0),(8,21,355,0,0),(8,21,356,0,0),(8,21,357,0,0),(8,21,358,0,0),(8,21,359,0,0),(8,21,360,0,1),(8,21,361,0,1),(8,21,362,0,0),(8,21,363,0,0),(8,21,364,0,0),(8,21,365,0,0),(8,21,366,0,0),(8,21,367,0,0),(8,21,368,0,0),(8,21,369,0,0),(8,21,370,0,0),(8,21,371,0,0),(8,21,372,0,0),(8,21,373,0,0),(8,21,374,0,0),(8,21,375,0,0),(8,21,376,0,0),(8,21,377,0,0),(8,21,378,0,0),(8,22,379,0,0),(8,22,380,0,0),(8,22,381,0,0),(8,22,382,0,0),(8,22,383,0,0),(8,22,384,0,0),(8,22,385,0,0),(8,22,386,0,0),(8,22,387,0,0),(8,22,388,0,0),(8,22,389,0,0),(8,22,390,0,0),(8,22,391,0,0),(8,22,392,0,0),(8,22,393,0,0),(8,22,394,0,0),(8,22,395,0,0),(8,22,396,0,0),(8,22,397,0,0),(8,22,398,0,0),(8,22,399,0,0),(8,22,400,0,0),(8,22,401,0,1),(8,22,402,0,1),(8,22,403,0,0),(8,22,404,0,0),(8,22,405,0,0),(8,22,406,0,0),(8,22,407,0,0),(8,22,408,0,0),(8,22,409,0,0),(8,22,410,0,0),(8,22,411,0,0),(8,22,412,0,0),(8,22,413,0,0),(8,22,414,0,0),(8,22,415,0,0),(8,22,416,0,0),(8,22,417,0,0),(8,22,418,0,0),(8,22,419,0,0),(8,22,420,0,0),(8,22,421,0,0),(8,22,422,0,0),(8,22,423,0,0),(8,22,424,0,0),(8,22,425,0,0),(8,23,426,0,0),(8,23,427,0,0),(8,23,428,0,0),(8,23,429,0,0),(8,23,430,0,0),(8,23,431,0,0),(8,23,432,0,0),(8,23,433,0,0),(8,23,434,0,0),(8,23,435,0,0),(8,23,436,0,0),(8,23,437,0,0),(8,23,438,0,0),(8,23,439,0,0),(8,23,440,0,0),(8,23,441,0,0),(8,23,442,0,0),(8,23,443,0,0),(8,23,444,0,0),(8,23,445,0,1),(8,23,446,0,1),(8,23,447,0,0),(8,23,448,0,0),(8,23,449,0,0),(8,23,450,0,0),(8,23,451,0,0),(8,23,452,0,0),(8,23,453,0,0),(8,23,454,0,0),(8,23,455,0,0),(8,23,456,0,0),(8,23,457,0,0),(8,23,458,0,0),(8,23,459,0,0),(8,23,460,0,0),(8,23,461,0,0),(8,23,462,0,0),(8,23,463,0,0),(8,23,464,0,0),(8,29,465,0,0),(8,29,469,0,0),(8,29,470,0,0),(8,29,471,0,0),(8,29,472,0,0),(8,29,479,0,0),(8,29,480,0,0),(8,29,481,0,0),(8,29,482,0,0),(8,29,483,0,0),(8,29,484,0,0),(8,29,489,0,0),(8,29,494,0,0),(8,29,495,0,0),(8,29,496,0,0),(8,29,497,0,0),(8,29,500,0,0),(8,10,512,0,0),(8,10,513,0,0),(8,10,514,0,0),(8,10,515,0,0),(8,10,516,0,0),(8,10,517,0,0),(8,36,518,0,0),(8,36,519,0,0),(8,36,520,0,0),(8,36,521,0,0),(8,36,522,0,0),(8,36,523,0,0),(8,36,524,0,0),(8,36,525,0,0),(8,36,526,0,1),(8,36,527,0,1),(8,36,528,0,0),(8,36,529,0,0),(8,36,530,0,0),(8,36,531,0,0),(8,36,532,0,0),(8,36,533,0,0),(8,36,535,0,0),(8,36,536,0,0),(8,37,537,0,0),(8,37,538,0,0),(8,37,539,0,0),(8,37,540,0,0),(8,37,541,0,0),(8,37,542,0,0),(8,37,543,0,0),(8,37,544,0,0),(8,37,545,0,0),(8,37,546,0,0),(8,37,547,0,0),(8,37,548,0,0),(8,37,549,0,0),(8,37,550,0,0),(8,37,551,0,1),(8,37,552,0,1),(8,29,553,0,0),(8,42,554,0,0),(8,42,555,0,0),(8,42,556,0,0),(8,42,557,0,0),(8,42,558,0,0),(8,42,559,0,0),(8,42,560,0,1),(8,42,561,0,1),(8,42,562,0,0),(8,42,563,0,0),(8,43,564,0,0),(8,43,565,0,0),(8,43,566,0,0),(8,43,567,0,0),(8,43,568,0,0),(8,43,569,0,0),(8,43,570,0,0),(8,43,571,0,0),(8,43,572,0,0),(8,43,573,0,0),(8,43,574,0,0),(8,43,575,0,1),(8,43,576,0,1),(8,43,577,0,0),(8,43,578,0,0),(8,44,579,0,0),(8,44,580,0,0),(8,44,581,0,0),(8,44,582,0,0),(8,44,583,0,0),(8,44,584,0,0),(8,44,585,0,0),(8,44,586,0,0),(8,44,587,0,0),(8,44,588,0,0),(8,44,589,0,0),(8,44,590,0,0),(8,44,591,0,0),(8,44,592,0,1),(8,44,593,0,1),(8,44,594,0,0),(8,44,595,0,0),(8,47,596,0,0),(8,47,597,0,0),(8,47,598,0,1),(8,47,599,0,1),(8,47,600,0,0),(8,47,601,0,1),(8,47,602,0,0),(8,2,603,0,0),(8,23,605,0,0),(8,23,606,0,0),(8,23,607,0,0),(8,23,608,0,0),(8,23,609,0,0),(8,23,610,0,0),(8,22,614,0,0),(8,22,615,0,0),(8,22,616,0,0),(8,22,617,0,0),(8,22,618,0,0),(8,22,619,0,0),(8,21,623,0,0),(8,21,624,0,0),(8,21,625,0,0),(8,21,626,0,0),(8,21,627,0,0),(8,21,628,0,0),(8,20,632,0,0),(8,20,633,0,0),(8,20,634,0,0),(8,20,635,0,0),(8,20,636,0,0),(8,20,637,0,0),(8,23,647,0,0),(8,22,648,0,0),(8,21,649,0,0),(8,20,650,0,0),(8,6,652,0,1),(8,4,653,0,1),(8,2,654,0,1),(8,23,656,0,0),(8,23,657,0,0),(8,21,658,0,0),(8,21,659,0,0),(8,7,660,0,0),(8,23,663,0,0),(8,20,664,0,0),(8,21,665,0,0),(8,22,666,0,0),(8,2,668,0,0),(8,13,669,0,0),(8,14,696,0,0),(8,23,698,0,0),(8,2,713,0,1),(8,4,714,0,1),(8,6,715,0,1),(8,7,716,0,1),(8,8,717,0,1),(8,9,718,0,1),(8,10,719,0,1),(8,13,720,0,1),(8,14,721,0,1),(8,16,722,0,1),(8,18,723,0,1),(8,19,724,0,1),(8,20,725,0,1),(8,21,726,0,1),(8,22,727,0,1),(8,23,728,0,1),(8,26,729,0,1),(8,10,735,0,0),(8,2,736,0,0),(8,4,737,0,0),(8,6,738,0,0),(8,7,739,0,0),(8,8,740,0,0),(8,9,741,0,0),(8,10,742,0,0),(8,13,743,0,0),(8,14,744,0,0),(8,16,745,0,1),(8,18,746,0,0),(8,19,747,0,0),(8,20,748,0,0),(8,21,749,0,0),(8,22,750,0,0),(8,23,751,0,0),(8,26,752,0,0),(8,2,758,0,0),(8,4,759,0,0),(8,6,760,0,0),(8,7,761,0,0),(8,8,762,0,0),(8,9,763,0,0),(8,10,764,0,0),(8,13,765,0,0),(8,14,766,0,0),(8,16,767,0,1),(8,18,768,0,0),(8,19,769,0,0),(8,20,770,0,0),(8,21,771,0,0),(8,22,772,0,0),(8,23,773,0,0),(8,26,774,0,0),(8,20,780,0,0),(8,21,781,0,0),(8,22,782,0,0),(8,23,783,0,0),(8,47,784,0,0),(8,47,785,0,0),(8,47,786,0,0),(8,47,787,0,0),(8,47,788,0,0),(8,47,789,0,0),(8,43,790,0,0),(8,43,791,0,0),(8,43,792,0,1),(8,43,793,0,0),(8,44,794,0,1),(8,44,795,0,0),(8,44,796,0,0),(8,44,797,0,1),(8,44,798,0,0),(8,42,800,0,1),(8,42,801,0,0),(8,42,802,0,0),(9,6,1,0,0),(9,6,2,0,0),(9,6,3,0,0),(9,6,4,0,0),(9,6,5,0,0),(9,6,6,0,0),(9,6,7,0,0),(9,6,8,0,0),(9,6,9,0,0),(9,6,10,0,0),(9,6,11,0,0),(9,6,12,0,0),(9,6,13,0,0),(9,6,14,0,0),(9,6,15,0,0),(9,6,16,0,0),(9,6,17,0,0),(9,6,18,0,0),(9,6,19,0,0),(9,6,20,0,0),(9,6,21,0,1),(9,6,22,0,1),(9,6,23,0,0),(9,6,24,0,0),(9,6,25,0,0),(9,6,26,0,0),(9,6,27,0,0),(9,6,28,0,0),(9,6,29,0,0),(9,6,30,0,0),(9,6,31,0,0),(9,6,32,0,0),(9,6,33,0,0),(9,6,34,0,0),(9,6,35,0,0),(9,6,36,0,0),(9,7,37,0,0),(9,7,38,0,0),(9,7,39,0,0),(9,7,40,0,0),(9,7,41,0,0),(9,7,42,0,0),(9,7,43,0,0),(9,7,44,0,0),(9,7,45,0,0),(9,7,46,0,0),(9,7,47,0,0),(9,7,48,0,0),(9,7,49,0,0),(9,7,50,0,0),(9,7,51,0,0),(9,7,52,0,0),(9,7,53,0,0),(9,7,54,0,0),(9,7,55,0,0),(9,7,56,0,1),(9,7,57,0,1),(9,7,58,0,0),(9,7,59,0,0),(9,7,60,0,0),(9,7,61,0,0),(9,7,62,0,0),(9,7,63,0,0),(9,7,64,0,0),(9,7,65,0,0),(9,4,66,0,0),(9,4,67,0,0),(9,4,68,0,0),(9,4,69,0,0),(9,4,70,0,0),(9,4,71,0,0),(9,4,72,0,0),(9,4,73,0,0),(9,4,74,0,0),(9,4,75,0,0),(9,4,76,0,0),(9,4,77,0,0),(9,4,78,0,0),(9,4,79,0,0),(9,4,80,0,0),(9,4,81,0,0),(9,4,82,0,0),(9,4,83,0,0),(9,4,84,0,0),(9,4,85,0,0),(9,4,86,0,0),(9,4,87,0,0),(9,4,88,0,0),(9,4,89,0,0),(9,4,90,0,1),(9,4,91,0,1),(9,4,92,0,0),(9,4,96,0,0),(9,4,97,0,0),(9,4,98,0,0),(9,4,99,0,0),(9,4,100,0,0),(9,4,101,0,0),(9,4,102,0,0),(9,4,103,0,0),(9,4,104,0,0),(9,4,105,0,0),(9,4,106,0,0),(9,4,107,0,0),(9,4,108,0,0),(9,4,109,0,0),(9,2,110,0,0),(9,2,111,0,0),(9,2,112,0,0),(9,2,113,0,0),(9,2,114,0,0),(9,2,115,0,0),(9,2,116,0,0),(9,2,117,0,0),(9,2,118,0,0),(9,2,119,0,0),(9,2,120,0,0),(9,2,121,0,0),(9,2,122,0,1),(9,2,123,0,1),(9,2,124,0,0),(9,2,125,0,0),(9,26,126,0,0),(9,26,127,0,0),(9,26,128,0,0),(9,26,129,0,0),(9,26,130,0,0),(9,26,131,0,0),(9,26,132,0,0),(9,26,133,0,0),(9,26,134,0,0),(9,26,135,0,0),(9,26,136,0,0),(9,26,137,0,1),(9,26,138,0,1),(9,26,139,0,0),(9,26,140,0,0),(9,26,141,0,0),(9,26,142,0,0),(9,26,143,0,0),(9,26,144,0,0),(9,26,145,0,0),(9,26,146,0,0),(9,26,147,0,0),(9,26,148,0,0),(9,26,149,0,0),(9,26,150,0,0),(9,13,155,0,0),(9,13,156,0,0),(9,13,157,0,0),(9,13,158,0,0),(9,13,159,0,0),(9,13,160,0,0),(9,13,161,0,0),(9,13,162,0,0),(9,13,164,0,0),(9,13,165,0,0),(9,13,166,0,1),(9,13,167,0,1),(9,13,168,0,0),(9,13,169,0,0),(9,13,170,0,0),(9,13,171,0,0),(9,13,172,0,0),(9,14,174,0,0),(9,14,175,0,0),(9,14,176,0,0),(9,14,177,0,0),(9,14,178,0,0),(9,14,179,0,0),(9,14,180,0,0),(9,14,181,0,0),(9,14,182,0,0),(9,14,183,0,0),(9,14,184,0,0),(9,14,185,0,0),(9,14,186,0,0),(9,14,187,0,0),(9,14,188,0,0),(9,14,189,0,0),(9,14,190,0,0),(9,14,191,0,1),(9,14,192,0,1),(9,14,193,0,0),(9,14,194,0,0),(9,14,195,0,0),(9,14,197,0,0),(9,14,198,0,0),(9,14,199,0,0),(9,14,200,0,0),(9,14,201,0,0),(9,14,202,0,0),(9,14,203,0,0),(9,14,204,0,0),(9,8,205,0,0),(9,8,206,0,1),(9,8,207,0,1),(9,8,208,0,0),(9,8,209,0,0),(9,8,210,0,0),(9,8,211,0,1),(9,8,212,0,1),(9,8,213,0,0),(9,8,214,0,0),(9,8,215,0,0),(9,8,216,0,1),(9,8,217,0,0),(9,8,218,0,0),(9,8,219,0,0),(9,10,220,0,0),(9,10,221,0,0),(9,10,222,0,0),(9,10,223,0,0),(9,10,224,0,0),(9,10,225,0,0),(9,10,226,0,0),(9,10,227,0,0),(9,10,228,0,0),(9,10,229,0,1),(9,10,230,0,0),(9,10,231,0,0),(9,9,232,0,0),(9,9,233,0,0),(9,9,234,0,0),(9,9,235,0,0),(9,9,236,0,0),(9,9,237,0,0),(9,9,238,0,0),(9,9,239,0,0),(9,9,240,0,0),(9,9,241,0,0),(9,9,242,0,0),(9,9,243,0,0),(9,9,244,0,1),(9,9,245,0,1),(9,9,246,0,0),(9,9,247,0,0),(9,9,248,0,0),(9,9,249,0,0),(9,9,250,0,0),(9,9,251,0,0),(9,9,252,0,0),(9,9,253,0,0),(9,9,254,0,0),(9,9,255,0,0),(9,16,256,0,0),(9,16,257,0,0),(9,16,258,0,0),(9,16,259,0,0),(9,16,260,0,0),(9,16,261,0,0),(9,16,262,0,0),(9,16,263,0,0),(9,16,264,0,0),(9,16,265,0,0),(9,16,266,0,0),(9,16,267,0,0),(9,16,268,0,0),(9,16,269,0,0),(9,16,270,0,1),(9,16,271,0,1),(9,16,272,0,0),(9,16,273,0,0),(9,16,274,0,0),(9,16,275,0,0),(9,16,276,0,0),(9,16,277,0,0),(9,16,278,0,0),(9,18,279,0,0),(9,18,280,0,0),(9,18,281,0,0),(9,18,282,0,0),(9,18,283,0,0),(9,18,284,0,0),(9,18,285,0,0),(9,18,286,0,1),(9,18,287,0,1),(9,18,288,0,0),(9,18,289,0,0),(9,18,290,0,0),(9,18,291,0,0),(9,18,292,0,0),(9,18,293,0,0),(9,18,294,0,0),(9,18,295,0,0),(9,19,296,0,0),(9,19,297,0,0),(9,19,298,0,0),(9,19,299,0,1),(9,19,300,0,1),(9,19,301,0,0),(9,19,302,0,0),(9,19,303,0,0),(9,20,304,0,0),(9,20,305,0,0),(9,20,306,0,0),(9,20,307,0,0),(9,20,308,0,0),(9,20,309,0,0),(9,20,310,0,0),(9,20,311,0,0),(9,20,312,0,0),(9,20,313,0,0),(9,20,314,0,0),(9,20,315,0,0),(9,20,316,0,0),(9,20,317,0,0),(9,20,318,0,0),(9,20,319,0,0),(9,20,320,0,0),(9,20,321,0,0),(9,20,322,0,1),(9,20,323,0,1),(9,20,324,0,0),(9,20,325,0,0),(9,20,326,0,0),(9,20,327,0,0),(9,20,328,0,0),(9,20,329,0,0),(9,20,330,0,0),(9,20,331,0,0),(9,20,332,0,0),(9,20,333,0,0),(9,20,334,0,0),(9,20,335,0,0),(9,20,336,0,0),(9,20,337,0,0),(9,20,338,0,0),(9,20,339,0,0),(9,20,340,0,0),(9,21,341,0,0),(9,21,342,0,0),(9,21,343,0,0),(9,21,344,0,0),(9,21,345,0,0),(9,21,346,0,0),(9,21,347,0,0),(9,21,348,0,0),(9,21,349,0,0),(9,21,350,0,0),(9,21,351,0,0),(9,21,352,0,0),(9,21,353,0,0),(9,21,354,0,0),(9,21,355,0,0),(9,21,356,0,0),(9,21,357,0,0),(9,21,358,0,0),(9,21,359,0,0),(9,21,360,0,1),(9,21,361,0,1),(9,21,362,0,0),(9,21,363,0,0),(9,21,364,0,0),(9,21,365,0,0),(9,21,366,0,0),(9,21,367,0,0),(9,21,368,0,0),(9,21,369,0,0),(9,21,370,0,0),(9,21,371,0,0),(9,21,372,0,0),(9,21,373,0,0),(9,21,374,0,0),(9,21,375,0,0),(9,21,376,0,0),(9,21,377,0,0),(9,21,378,0,0),(9,22,379,0,0),(9,22,380,0,0),(9,22,381,0,0),(9,22,382,0,0),(9,22,383,0,0),(9,22,384,0,0),(9,22,385,0,0),(9,22,386,0,0),(9,22,387,0,0),(9,22,388,0,0),(9,22,389,0,0),(9,22,390,0,0),(9,22,391,0,0),(9,22,392,0,0),(9,22,393,0,0),(9,22,394,0,0),(9,22,395,0,0),(9,22,396,0,0),(9,22,397,0,0),(9,22,398,0,0),(9,22,399,0,0),(9,22,400,0,0),(9,22,401,0,1),(9,22,402,0,1),(9,22,403,0,0),(9,22,404,0,0),(9,22,405,0,0),(9,22,406,0,0),(9,22,407,0,0),(9,22,408,0,0),(9,22,409,0,0),(9,22,410,0,0),(9,22,411,0,0),(9,22,412,0,0),(9,22,413,0,0),(9,22,414,0,0),(9,22,415,0,0),(9,22,416,0,0),(9,22,417,0,0),(9,22,418,0,0),(9,22,419,0,0),(9,22,420,0,0),(9,22,421,0,0),(9,22,422,0,0),(9,22,423,0,0),(9,22,424,0,0),(9,22,425,0,0),(9,23,426,0,0),(9,23,427,0,0),(9,23,428,0,0),(9,23,429,0,0),(9,23,430,0,0),(9,23,431,0,0),(9,23,432,0,0),(9,23,433,0,0),(9,23,434,0,0),(9,23,435,0,0),(9,23,436,0,0),(9,23,437,0,0),(9,23,438,0,0),(9,23,439,0,0),(9,23,440,0,0),(9,23,441,0,0),(9,23,442,0,0),(9,23,443,0,0),(9,23,444,0,0),(9,23,445,0,1),(9,23,446,0,1),(9,23,447,0,0),(9,23,448,0,0),(9,23,449,0,0),(9,23,450,0,0),(9,23,451,0,0),(9,23,452,0,0),(9,23,453,0,0),(9,23,454,0,0),(9,23,455,0,0),(9,23,456,0,0),(9,23,457,0,0),(9,23,458,0,0),(9,23,459,0,0),(9,23,460,0,0),(9,23,461,0,0),(9,23,462,0,0),(9,23,463,0,0),(9,23,464,0,0),(9,29,465,0,0),(9,29,469,0,0),(9,29,470,0,0),(9,29,471,0,0),(9,29,472,0,0),(9,29,479,0,0),(9,29,480,0,0),(9,29,481,0,0),(9,29,482,0,0),(9,29,483,0,0),(9,29,484,0,0),(9,29,489,0,0),(9,29,494,0,0),(9,29,495,0,0),(9,29,496,0,0),(9,29,497,0,0),(9,29,500,0,0),(9,10,512,0,0),(9,10,513,0,0),(9,10,514,0,0),(9,10,515,0,0),(9,10,516,0,0),(9,10,517,0,0),(9,36,518,0,0),(9,36,519,0,0),(9,36,520,0,0),(9,36,521,0,0),(9,36,522,0,0),(9,36,523,0,0),(9,36,524,0,0),(9,36,525,0,0),(9,36,526,0,1),(9,36,527,0,1),(9,36,528,0,0),(9,36,529,0,0),(9,36,530,0,0),(9,36,531,0,0),(9,36,532,0,0),(9,36,533,0,0),(9,36,535,0,0),(9,36,536,0,0),(9,37,537,0,0),(9,37,538,0,0),(9,37,539,0,0),(9,37,540,0,0),(9,37,541,0,0),(9,37,542,0,0),(9,37,543,0,0),(9,37,544,0,0),(9,37,545,0,0),(9,37,546,0,0),(9,37,547,0,0),(9,37,548,0,0),(9,37,549,0,0),(9,37,550,0,0),(9,37,551,0,1),(9,37,552,0,1),(9,29,553,0,0),(9,42,554,0,0),(9,42,555,0,0),(9,42,556,0,0),(9,42,557,0,0),(9,42,558,0,0),(9,42,559,0,0),(9,42,560,0,1),(9,42,561,0,1),(9,42,562,0,0),(9,42,563,0,0),(9,43,564,0,0),(9,43,565,0,0),(9,43,566,0,0),(9,43,567,0,0),(9,43,568,0,0),(9,43,569,0,0),(9,43,570,0,0),(9,43,571,0,0),(9,43,572,0,0),(9,43,573,0,0),(9,43,574,0,0),(9,43,575,0,1),(9,43,576,0,1),(9,43,577,0,0),(9,43,578,0,0),(9,44,579,0,0),(9,44,580,0,0),(9,44,581,0,0),(9,44,582,0,0),(9,44,583,0,0),(9,44,584,0,0),(9,44,585,0,0),(9,44,586,0,0),(9,44,587,0,0),(9,44,588,0,0),(9,44,589,0,0),(9,44,590,0,0),(9,44,591,0,0),(9,44,592,0,1),(9,44,593,0,1),(9,44,594,0,0),(9,44,595,0,0),(9,47,596,0,0),(9,47,597,0,0),(9,47,598,0,1),(9,47,599,0,1),(9,47,600,0,0),(9,47,601,0,1),(9,47,602,0,0),(9,2,603,0,0),(9,23,605,0,0),(9,23,606,0,0),(9,23,607,0,0),(9,23,608,0,0),(9,23,609,0,0),(9,23,610,0,0),(9,22,614,0,0),(9,22,615,0,0),(9,22,616,0,0),(9,22,617,0,0),(9,22,618,0,0),(9,22,619,0,0),(9,21,623,0,0),(9,21,624,0,0),(9,21,625,0,0),(9,21,626,0,0),(9,21,627,0,0),(9,21,628,0,0),(9,20,632,0,0),(9,20,633,0,0),(9,20,634,0,0),(9,20,635,0,0),(9,20,636,0,0),(9,20,637,0,0),(9,23,647,0,0),(9,22,648,0,0),(9,21,649,0,0),(9,20,650,0,0),(9,6,652,0,1),(9,4,653,0,1),(9,2,654,0,1),(9,23,656,0,0),(9,23,657,0,0),(9,21,658,0,0),(9,21,659,0,0),(9,7,660,0,0),(9,23,663,0,0),(9,20,664,0,0),(9,21,665,0,0),(9,22,666,0,0),(9,2,668,0,0),(9,13,669,0,0),(9,14,696,0,0),(9,23,698,0,0),(9,2,713,0,1),(9,4,714,0,1),(9,6,715,0,1),(9,7,716,0,1),(9,8,717,0,1),(9,9,718,0,1),(9,10,719,0,1),(9,13,720,0,1),(9,14,721,0,1),(9,16,722,0,1),(9,18,723,0,1),(9,19,724,0,1),(9,20,725,0,1),(9,21,726,0,1),(9,22,727,0,1),(9,23,728,0,1),(9,26,729,0,1),(9,10,735,0,0),(9,2,736,0,0),(9,4,737,0,0),(9,6,738,0,0),(9,7,739,0,0),(9,8,740,0,0),(9,9,741,0,0),(9,10,742,0,0),(9,13,743,0,0),(9,14,744,0,0),(9,16,745,0,1),(9,18,746,0,0),(9,19,747,0,0),(9,20,748,0,0),(9,21,749,0,0),(9,22,750,0,0),(9,23,751,0,0),(9,26,752,0,0),(9,2,758,0,0),(9,4,759,0,0),(9,6,760,0,0),(9,7,761,0,0),(9,8,762,0,0),(9,9,763,0,0),(9,10,764,0,0),(9,13,765,0,0),(9,14,766,0,0),(9,16,767,0,1),(9,18,768,0,0),(9,19,769,0,0),(9,20,770,0,0),(9,21,771,0,0),(9,22,772,0,0),(9,23,773,0,0),(9,26,774,0,0),(9,20,780,0,0),(9,21,781,0,0),(9,22,782,0,0),(9,23,783,0,0),(9,47,784,0,0),(9,47,785,0,0),(9,47,786,0,0),(9,47,787,0,0),(9,47,788,0,0),(9,47,789,0,0),(9,43,790,0,0),(9,43,791,0,0),(9,43,792,0,1),(9,43,793,0,0),(9,44,794,0,1),(9,44,795,0,0),(9,44,796,0,0),(9,44,797,0,1),(9,44,798,0,0),(9,42,800,0,1),(9,42,801,0,0),(9,42,802,0,0);
/*!40000 ALTER TABLE `jo_profile2field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile2globalpermissions`
--

DROP TABLE IF EXISTS `jo_profile2globalpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile2globalpermissions` (
  `profileid` int(19) NOT NULL,
  `globalactionid` int(19) NOT NULL,
  `globalactionpermission` int(19) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`globalactionid`),
  KEY `idx_profile2globalpermissions` (`profileid`,`globalactionid`),
  CONSTRAINT `fk_1_jo_profile2globalpermissions` FOREIGN KEY (`profileid`) REFERENCES `jo_profile` (`profileid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile2globalpermissions`
--

LOCK TABLES `jo_profile2globalpermissions` WRITE;
/*!40000 ALTER TABLE `jo_profile2globalpermissions` DISABLE KEYS */;
INSERT INTO `jo_profile2globalpermissions` VALUES (1,1,0),(1,2,0),(2,1,1),(2,2,1),(3,1,1),(3,2,1),(4,1,1),(4,2,1),(5,1,1),(5,2,1),(6,1,1),(6,2,1),(7,1,1),(7,2,1),(8,1,1),(8,2,1),(9,1,1),(9,2,1);
/*!40000 ALTER TABLE `jo_profile2globalpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile2standardpermissions`
--

DROP TABLE IF EXISTS `jo_profile2standardpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile2standardpermissions` (
  `profileid` int(11) NOT NULL,
  `tabid` int(10) NOT NULL,
  `operation` int(10) NOT NULL,
  `permissions` int(1) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`tabid`,`operation`),
  KEY `profile2standardpermissions_profileid_tabid_operation_idx` (`profileid`,`tabid`,`operation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile2standardpermissions`
--

LOCK TABLES `jo_profile2standardpermissions` WRITE;
/*!40000 ALTER TABLE `jo_profile2standardpermissions` DISABLE KEYS */;
INSERT INTO `jo_profile2standardpermissions` VALUES (1,2,0,0),(1,2,1,0),(1,2,2,0),(1,2,3,0),(1,2,4,0),(1,2,7,0),(1,4,0,0),(1,4,1,0),(1,4,2,0),(1,4,3,0),(1,4,4,0),(1,4,7,0),(1,6,0,0),(1,6,1,0),(1,6,2,0),(1,6,3,0),(1,6,4,0),(1,6,7,0),(1,7,0,0),(1,7,1,0),(1,7,2,0),(1,7,3,0),(1,7,4,0),(1,7,7,0),(1,8,0,0),(1,8,1,0),(1,8,2,0),(1,8,3,0),(1,8,4,0),(1,8,7,0),(1,9,0,0),(1,9,1,0),(1,9,2,0),(1,9,3,0),(1,9,4,0),(1,9,7,0),(1,13,0,0),(1,13,1,0),(1,13,2,0),(1,13,3,0),(1,13,4,0),(1,13,7,0),(1,14,0,0),(1,14,1,0),(1,14,2,0),(1,14,3,0),(1,14,4,0),(1,14,7,0),(1,15,0,0),(1,15,1,0),(1,15,2,0),(1,15,3,0),(1,15,4,0),(1,15,7,0),(1,16,0,0),(1,16,1,0),(1,16,2,0),(1,16,3,0),(1,16,4,0),(1,16,7,0),(1,18,0,0),(1,18,1,0),(1,18,2,0),(1,18,3,0),(1,18,4,0),(1,18,7,0),(1,19,0,0),(1,19,1,0),(1,19,2,0),(1,19,3,0),(1,19,4,0),(1,19,7,0),(1,20,0,0),(1,20,1,0),(1,20,2,0),(1,20,3,0),(1,20,4,0),(1,20,7,0),(1,21,0,0),(1,21,1,0),(1,21,2,0),(1,21,3,0),(1,21,4,0),(1,21,7,0),(1,22,0,0),(1,22,1,0),(1,22,2,0),(1,22,3,0),(1,22,4,0),(1,22,7,0),(1,23,0,0),(1,23,1,0),(1,23,2,0),(1,23,3,0),(1,23,4,0),(1,23,7,0),(1,26,0,0),(1,26,1,0),(1,26,2,0),(1,26,3,0),(1,26,4,0),(1,26,7,0),(1,36,0,0),(1,36,1,0),(1,36,2,0),(1,36,3,0),(1,36,4,0),(1,36,7,0),(1,37,0,0),(1,37,1,0),(1,37,2,0),(1,37,3,0),(1,37,4,0),(1,37,7,0),(1,42,0,0),(1,42,1,0),(1,42,2,0),(1,42,3,0),(1,42,4,0),(1,42,7,0),(1,43,0,0),(1,43,1,0),(1,43,2,0),(1,43,3,0),(1,43,4,0),(1,43,7,0),(1,44,0,0),(1,44,1,0),(1,44,2,0),(1,44,3,0),(1,44,4,0),(1,44,7,0),(1,47,0,0),(1,47,1,0),(1,47,2,0),(1,47,3,0),(1,47,4,0),(1,47,7,0),(2,2,0,0),(2,2,1,0),(2,2,2,0),(2,2,3,0),(2,2,4,0),(2,2,7,0),(2,4,0,0),(2,4,1,0),(2,4,2,0),(2,4,3,0),(2,4,4,0),(2,4,7,0),(2,6,0,0),(2,6,1,0),(2,6,2,0),(2,6,3,0),(2,6,4,0),(2,6,7,0),(2,7,0,0),(2,7,1,0),(2,7,2,0),(2,7,3,0),(2,7,4,0),(2,7,7,0),(2,8,0,0),(2,8,1,0),(2,8,2,0),(2,8,3,0),(2,8,4,0),(2,8,7,0),(2,9,0,0),(2,9,1,0),(2,9,2,0),(2,9,3,0),(2,9,4,0),(2,9,7,0),(2,13,0,1),(2,13,1,1),(2,13,2,1),(2,13,3,0),(2,13,4,0),(2,13,7,1),(2,14,0,0),(2,14,1,0),(2,14,2,0),(2,14,3,0),(2,14,4,0),(2,14,7,0),(2,15,0,0),(2,15,1,0),(2,15,2,0),(2,15,3,0),(2,15,4,0),(2,15,7,0),(2,16,0,0),(2,16,1,0),(2,16,2,0),(2,16,3,0),(2,16,4,0),(2,16,7,0),(2,18,0,0),(2,18,1,0),(2,18,2,0),(2,18,3,0),(2,18,4,0),(2,18,7,0),(2,19,0,0),(2,19,1,0),(2,19,2,0),(2,19,3,0),(2,19,4,0),(2,19,7,0),(2,20,0,0),(2,20,1,0),(2,20,2,0),(2,20,3,0),(2,20,4,0),(2,20,7,0),(2,21,0,0),(2,21,1,0),(2,21,2,0),(2,21,3,0),(2,21,4,0),(2,21,7,0),(2,22,0,0),(2,22,1,0),(2,22,2,0),(2,22,3,0),(2,22,4,0),(2,22,7,0),(2,23,0,0),(2,23,1,0),(2,23,2,0),(2,23,3,0),(2,23,4,0),(2,23,7,0),(2,26,0,0),(2,26,1,0),(2,26,2,0),(2,26,3,0),(2,26,4,0),(2,26,7,0),(2,36,0,0),(2,36,1,0),(2,36,2,0),(2,36,3,0),(2,36,4,0),(2,36,7,0),(2,37,0,0),(2,37,1,0),(2,37,2,0),(2,37,3,0),(2,37,4,0),(2,37,7,0),(2,42,0,0),(2,42,1,0),(2,42,2,0),(2,42,3,0),(2,42,4,0),(2,42,7,0),(2,43,0,0),(2,43,1,0),(2,43,2,0),(2,43,3,0),(2,43,4,0),(2,43,7,0),(2,44,0,0),(2,44,1,0),(2,44,2,0),(2,44,3,0),(2,44,4,0),(2,44,7,0),(2,47,0,0),(2,47,1,0),(2,47,2,0),(2,47,3,0),(2,47,4,0),(2,47,7,0),(3,2,0,1),(3,2,1,1),(3,2,2,1),(3,2,3,0),(3,2,4,0),(3,2,7,1),(3,4,0,0),(3,4,1,0),(3,4,2,0),(3,4,3,0),(3,4,4,0),(3,4,7,0),(3,6,0,0),(3,6,1,0),(3,6,2,0),(3,6,3,0),(3,6,4,0),(3,6,7,0),(3,7,0,0),(3,7,1,0),(3,7,2,0),(3,7,3,0),(3,7,4,0),(3,7,7,0),(3,8,0,0),(3,8,1,0),(3,8,2,0),(3,8,3,0),(3,8,4,0),(3,8,7,0),(3,9,0,0),(3,9,1,0),(3,9,2,0),(3,9,3,0),(3,9,4,0),(3,9,7,0),(3,13,0,0),(3,13,1,0),(3,13,2,0),(3,13,3,0),(3,13,4,0),(3,13,7,0),(3,14,0,0),(3,14,1,0),(3,14,2,0),(3,14,3,0),(3,14,4,0),(3,14,7,0),(3,15,0,0),(3,15,1,0),(3,15,2,0),(3,15,3,0),(3,15,4,0),(3,15,7,0),(3,16,0,0),(3,16,1,0),(3,16,2,0),(3,16,3,0),(3,16,4,0),(3,16,7,0),(3,18,0,0),(3,18,1,0),(3,18,2,0),(3,18,3,0),(3,18,4,0),(3,18,7,0),(3,19,0,0),(3,19,1,0),(3,19,2,0),(3,19,3,0),(3,19,4,0),(3,19,7,0),(3,20,0,0),(3,20,1,0),(3,20,2,0),(3,20,3,0),(3,20,4,0),(3,20,7,0),(3,21,0,0),(3,21,1,0),(3,21,2,0),(3,21,3,0),(3,21,4,0),(3,21,7,0),(3,22,0,0),(3,22,1,0),(3,22,2,0),(3,22,3,0),(3,22,4,0),(3,22,7,0),(3,23,0,0),(3,23,1,0),(3,23,2,0),(3,23,3,0),(3,23,4,0),(3,23,7,0),(3,26,0,0),(3,26,1,0),(3,26,2,0),(3,26,3,0),(3,26,4,0),(3,26,7,0),(3,36,0,0),(3,36,1,0),(3,36,2,0),(3,36,3,0),(3,36,4,0),(3,36,7,0),(3,37,0,0),(3,37,1,0),(3,37,2,0),(3,37,3,0),(3,37,4,0),(3,37,7,0),(3,42,0,0),(3,42,1,0),(3,42,2,0),(3,42,3,0),(3,42,4,0),(3,42,7,0),(3,43,0,0),(3,43,1,0),(3,43,2,0),(3,43,3,0),(3,43,4,0),(3,43,7,0),(3,44,0,0),(3,44,1,0),(3,44,2,0),(3,44,3,0),(3,44,4,0),(3,44,7,0),(3,47,0,0),(3,47,1,0),(3,47,2,0),(3,47,3,0),(3,47,4,0),(3,47,7,0),(4,2,0,1),(4,2,1,1),(4,2,2,1),(4,2,3,0),(4,2,4,0),(4,2,7,1),(4,4,0,1),(4,4,1,1),(4,4,2,1),(4,4,3,0),(4,4,4,0),(4,4,7,1),(4,6,0,1),(4,6,1,1),(4,6,2,1),(4,6,3,0),(4,6,4,0),(4,6,7,1),(4,7,0,1),(4,7,1,1),(4,7,2,1),(4,7,3,0),(4,7,4,0),(4,7,7,1),(4,8,0,1),(4,8,1,1),(4,8,2,1),(4,8,3,0),(4,8,4,0),(4,8,7,1),(4,9,0,1),(4,9,1,1),(4,9,2,1),(4,9,3,0),(4,9,4,0),(4,9,7,1),(4,13,0,1),(4,13,1,1),(4,13,2,1),(4,13,3,0),(4,13,4,0),(4,13,7,1),(4,14,0,1),(4,14,1,1),(4,14,2,1),(4,14,3,0),(4,14,4,0),(4,14,7,1),(4,15,0,1),(4,15,1,1),(4,15,2,1),(4,15,3,0),(4,15,4,0),(4,15,7,1),(4,16,0,1),(4,16,1,1),(4,16,2,1),(4,16,3,0),(4,16,4,0),(4,16,7,1),(4,18,0,1),(4,18,1,1),(4,18,2,1),(4,18,3,0),(4,18,4,0),(4,18,7,1),(4,19,0,1),(4,19,1,1),(4,19,2,1),(4,19,3,0),(4,19,4,0),(4,19,7,1),(4,20,0,1),(4,20,1,1),(4,20,2,1),(4,20,3,0),(4,20,4,0),(4,20,7,1),(4,21,0,1),(4,21,1,1),(4,21,2,1),(4,21,3,0),(4,21,4,0),(4,21,7,1),(4,22,0,1),(4,22,1,1),(4,22,2,1),(4,22,3,0),(4,22,4,0),(4,22,7,1),(4,23,0,1),(4,23,1,1),(4,23,2,1),(4,23,3,0),(4,23,4,0),(4,23,7,1),(4,26,0,1),(4,26,1,1),(4,26,2,1),(4,26,3,0),(4,26,4,0),(4,26,7,1),(4,36,0,0),(4,36,1,0),(4,36,2,0),(4,36,3,0),(4,36,4,0),(4,36,7,0),(4,37,0,0),(4,37,1,0),(4,37,2,0),(4,37,3,0),(4,37,4,0),(4,37,7,0),(4,42,0,0),(4,42,1,0),(4,42,2,0),(4,42,3,0),(4,42,4,0),(4,42,7,0),(4,43,0,0),(4,43,1,0),(4,43,2,0),(4,43,3,0),(4,43,4,0),(4,43,7,0),(4,44,0,0),(4,44,1,0),(4,44,2,0),(4,44,3,0),(4,44,4,0),(4,44,7,0),(4,47,0,0),(4,47,1,0),(4,47,2,0),(4,47,3,0),(4,47,4,0),(4,47,7,0),(5,2,0,1),(5,2,1,1),(5,2,2,1),(5,2,4,0),(5,2,7,1),(5,4,0,1),(5,4,1,1),(5,4,2,1),(5,4,4,0),(5,4,7,1),(5,6,0,1),(5,6,1,1),(5,6,2,1),(5,6,4,0),(5,6,7,1),(5,7,0,1),(5,7,1,1),(5,7,2,1),(5,7,4,1),(5,7,7,1),(5,8,0,1),(5,8,1,1),(5,8,2,1),(5,8,4,0),(5,8,7,1),(5,9,0,1),(5,9,1,1),(5,9,2,1),(5,9,4,0),(5,9,7,1),(5,13,0,1),(5,13,1,1),(5,13,2,1),(5,13,4,0),(5,13,7,1),(5,14,0,1),(5,14,1,1),(5,14,2,1),(5,14,4,0),(5,14,7,1),(5,16,0,1),(5,16,1,1),(5,16,2,1),(5,16,4,0),(5,16,7,1),(5,18,0,1),(5,18,1,1),(5,18,2,1),(5,18,4,1),(5,18,7,1),(5,19,0,1),(5,19,1,1),(5,19,2,1),(5,19,4,1),(5,19,7,1),(5,20,0,1),(5,20,1,1),(5,20,2,1),(5,20,4,0),(5,20,7,1),(5,21,0,1),(5,21,1,1),(5,21,2,1),(5,21,4,1),(5,21,7,1),(5,22,0,1),(5,22,1,1),(5,22,2,1),(5,22,4,0),(5,22,7,1),(5,23,0,1),(5,23,1,1),(5,23,2,1),(5,23,4,0),(5,23,7,1),(5,25,0,0),(5,25,1,0),(5,25,2,0),(5,25,4,0),(5,25,7,0),(5,26,0,1),(5,26,1,1),(5,26,2,1),(5,26,4,0),(5,26,7,1),(5,36,0,1),(5,36,1,1),(5,36,2,1),(5,36,4,0),(5,36,7,1),(5,37,0,1),(5,37,1,1),(5,37,2,1),(5,37,4,0),(5,37,7,1),(5,42,0,1),(5,42,1,1),(5,42,2,1),(5,42,4,1),(5,42,7,1),(5,43,0,1),(5,43,1,1),(5,43,2,1),(5,43,4,1),(5,43,7,1),(5,44,0,1),(5,44,1,1),(5,44,2,1),(5,44,4,0),(5,44,7,1),(6,2,0,1),(6,2,1,1),(6,2,2,1),(6,2,4,0),(6,2,7,1),(6,4,0,1),(6,4,1,1),(6,4,2,1),(6,4,4,0),(6,4,7,1),(6,6,0,1),(6,6,1,1),(6,6,2,1),(6,6,4,0),(6,6,7,1),(6,7,0,1),(6,7,1,1),(6,7,2,1),(6,7,4,1),(6,7,7,1),(6,8,0,1),(6,8,1,1),(6,8,2,1),(6,8,4,0),(6,8,7,1),(6,9,0,1),(6,9,1,1),(6,9,2,1),(6,9,4,0),(6,9,7,1),(6,13,0,1),(6,13,1,1),(6,13,2,1),(6,13,4,0),(6,13,7,1),(6,14,0,1),(6,14,1,1),(6,14,2,1),(6,14,4,0),(6,14,7,1),(6,16,0,1),(6,16,1,1),(6,16,2,1),(6,16,4,0),(6,16,7,1),(6,18,0,1),(6,18,1,1),(6,18,2,1),(6,18,4,1),(6,18,7,1),(6,19,0,1),(6,19,1,1),(6,19,2,1),(6,19,4,1),(6,19,7,1),(6,20,0,1),(6,20,1,1),(6,20,2,1),(6,20,4,0),(6,20,7,1),(6,21,0,1),(6,21,1,1),(6,21,2,1),(6,21,4,1),(6,21,7,1),(6,22,0,1),(6,22,1,1),(6,22,2,1),(6,22,4,0),(6,22,7,1),(6,23,0,1),(6,23,1,1),(6,23,2,1),(6,23,4,0),(6,23,7,1),(6,25,0,0),(6,25,1,0),(6,25,2,0),(6,25,4,0),(6,25,7,0),(6,26,0,1),(6,26,1,1),(6,26,2,1),(6,26,4,0),(6,26,7,1),(6,36,0,1),(6,36,1,1),(6,36,2,1),(6,36,4,0),(6,36,7,1),(6,37,0,1),(6,37,1,1),(6,37,2,1),(6,37,4,0),(6,37,7,1),(6,42,0,1),(6,42,1,1),(6,42,2,1),(6,42,4,1),(6,42,7,1),(6,43,0,1),(6,43,1,1),(6,43,2,1),(6,43,4,1),(6,43,7,1),(6,44,0,1),(6,44,1,1),(6,44,2,1),(6,44,4,0),(6,44,7,1),(7,2,0,0),(7,2,1,0),(7,2,2,0),(7,2,4,0),(7,2,7,0),(7,4,0,0),(7,4,1,0),(7,4,2,0),(7,4,4,0),(7,4,7,0),(7,6,0,0),(7,6,1,0),(7,6,2,0),(7,6,4,0),(7,6,7,0),(7,7,0,0),(7,7,1,0),(7,7,2,0),(7,7,4,0),(7,7,7,0),(7,8,0,0),(7,8,1,0),(7,8,2,0),(7,8,4,0),(7,8,7,0),(7,9,0,0),(7,9,1,0),(7,9,2,0),(7,9,4,0),(7,9,7,0),(7,13,0,1),(7,13,1,1),(7,13,2,1),(7,13,4,0),(7,13,7,1),(7,14,0,0),(7,14,1,0),(7,14,2,0),(7,14,4,0),(7,14,7,0),(7,16,0,0),(7,16,1,0),(7,16,2,0),(7,16,4,0),(7,16,7,0),(7,18,0,0),(7,18,1,0),(7,18,2,0),(7,18,4,0),(7,18,7,0),(7,19,0,0),(7,19,1,0),(7,19,2,0),(7,19,4,0),(7,19,7,0),(7,20,0,0),(7,20,1,0),(7,20,2,0),(7,20,4,0),(7,20,7,0),(7,21,0,0),(7,21,1,0),(7,21,2,0),(7,21,4,0),(7,21,7,0),(7,22,0,0),(7,22,1,0),(7,22,2,0),(7,22,4,0),(7,22,7,0),(7,23,0,0),(7,23,1,0),(7,23,2,0),(7,23,4,0),(7,23,7,0),(7,25,0,0),(7,25,1,0),(7,25,2,0),(7,25,4,0),(7,25,7,0),(7,26,0,0),(7,26,1,0),(7,26,2,0),(7,26,4,0),(7,26,7,0),(7,36,0,0),(7,36,1,0),(7,36,2,0),(7,36,4,0),(7,36,7,0),(7,37,0,0),(7,37,1,0),(7,37,2,0),(7,37,4,0),(7,37,7,0),(7,42,0,0),(7,42,1,0),(7,42,2,0),(7,42,4,0),(7,42,7,0),(7,43,0,0),(7,43,1,0),(7,43,2,0),(7,43,4,0),(7,43,7,0),(7,44,0,0),(7,44,1,0),(7,44,2,0),(7,44,4,0),(7,44,7,0),(7,47,0,0),(7,47,1,0),(7,47,2,0),(7,47,4,0),(7,47,7,0),(8,2,0,0),(8,2,1,0),(8,2,2,0),(8,2,4,0),(8,2,7,0),(8,4,0,0),(8,4,1,0),(8,4,2,0),(8,4,4,0),(8,4,7,0),(8,6,0,0),(8,6,1,0),(8,6,2,0),(8,6,4,0),(8,6,7,0),(8,7,0,0),(8,7,1,0),(8,7,2,0),(8,7,4,0),(8,7,7,0),(8,8,0,0),(8,8,1,0),(8,8,2,0),(8,8,4,0),(8,8,7,0),(8,9,0,0),(8,9,1,0),(8,9,2,0),(8,9,4,0),(8,9,7,0),(8,13,0,1),(8,13,1,1),(8,13,2,1),(8,13,4,0),(8,13,7,1),(8,14,0,0),(8,14,1,0),(8,14,2,0),(8,14,4,0),(8,14,7,0),(8,16,0,0),(8,16,1,0),(8,16,2,0),(8,16,4,0),(8,16,7,0),(8,18,0,0),(8,18,1,0),(8,18,2,0),(8,18,4,0),(8,18,7,0),(8,19,0,0),(8,19,1,0),(8,19,2,0),(8,19,4,0),(8,19,7,0),(8,20,0,0),(8,20,1,0),(8,20,2,0),(8,20,4,0),(8,20,7,0),(8,21,0,0),(8,21,1,0),(8,21,2,0),(8,21,4,0),(8,21,7,0),(8,22,0,0),(8,22,1,0),(8,22,2,0),(8,22,4,0),(8,22,7,0),(8,23,0,0),(8,23,1,0),(8,23,2,0),(8,23,4,0),(8,23,7,0),(8,25,0,0),(8,25,1,0),(8,25,2,0),(8,25,4,0),(8,25,7,0),(8,26,0,0),(8,26,1,0),(8,26,2,0),(8,26,4,0),(8,26,7,0),(8,36,0,0),(8,36,1,0),(8,36,2,0),(8,36,4,0),(8,36,7,0),(8,37,0,0),(8,37,1,0),(8,37,2,0),(8,37,4,0),(8,37,7,0),(8,42,0,0),(8,42,1,0),(8,42,2,0),(8,42,4,0),(8,42,7,0),(8,43,0,0),(8,43,1,0),(8,43,2,0),(8,43,4,0),(8,43,7,0),(8,44,0,0),(8,44,1,0),(8,44,2,0),(8,44,4,0),(8,44,7,0),(8,47,0,0),(8,47,1,0),(8,47,2,0),(8,47,4,0),(8,47,7,0),(9,2,0,0),(9,2,1,0),(9,2,2,0),(9,2,4,0),(9,2,7,0),(9,4,0,0),(9,4,1,0),(9,4,2,0),(9,4,4,0),(9,4,7,0),(9,6,0,0),(9,6,1,0),(9,6,2,0),(9,6,4,0),(9,6,7,0),(9,7,0,0),(9,7,1,0),(9,7,2,0),(9,7,4,0),(9,7,7,0),(9,8,0,0),(9,8,1,0),(9,8,2,0),(9,8,4,0),(9,8,7,0),(9,9,0,0),(9,9,1,0),(9,9,2,0),(9,9,4,0),(9,9,7,0),(9,13,0,1),(9,13,1,1),(9,13,2,1),(9,13,4,0),(9,13,7,1),(9,14,0,0),(9,14,1,0),(9,14,2,0),(9,14,4,0),(9,14,7,0),(9,16,0,0),(9,16,1,0),(9,16,2,0),(9,16,4,0),(9,16,7,0),(9,18,0,0),(9,18,1,0),(9,18,2,0),(9,18,4,0),(9,18,7,0),(9,19,0,0),(9,19,1,0),(9,19,2,0),(9,19,4,0),(9,19,7,0),(9,20,0,0),(9,20,1,0),(9,20,2,0),(9,20,4,0),(9,20,7,0),(9,21,0,0),(9,21,1,0),(9,21,2,0),(9,21,4,0),(9,21,7,0),(9,22,0,0),(9,22,1,0),(9,22,2,0),(9,22,4,0),(9,22,7,0),(9,23,0,0),(9,23,1,0),(9,23,2,0),(9,23,4,0),(9,23,7,0),(9,25,0,0),(9,25,1,0),(9,25,2,0),(9,25,4,0),(9,25,7,0),(9,26,0,0),(9,26,1,0),(9,26,2,0),(9,26,4,0),(9,26,7,0),(9,36,0,0),(9,36,1,0),(9,36,2,0),(9,36,4,0),(9,36,7,0),(9,37,0,0),(9,37,1,0),(9,37,2,0),(9,37,4,0),(9,37,7,0),(9,42,0,0),(9,42,1,0),(9,42,2,0),(9,42,4,0),(9,42,7,0),(9,43,0,0),(9,43,1,0),(9,43,2,0),(9,43,4,0),(9,43,7,0),(9,44,0,0),(9,44,1,0),(9,44,2,0),(9,44,4,0),(9,44,7,0),(9,47,0,0),(9,47,1,0),(9,47,2,0),(9,47,4,0),(9,47,7,0);
/*!40000 ALTER TABLE `jo_profile2standardpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile2tab`
--

DROP TABLE IF EXISTS `jo_profile2tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile2tab` (
  `profileid` int(11) DEFAULT NULL,
  `tabid` int(10) DEFAULT NULL,
  `permissions` int(10) NOT NULL DEFAULT '0',
  KEY `profile2tab_profileid_tabid_idx` (`profileid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile2tab`
--

LOCK TABLES `jo_profile2tab` WRITE;
/*!40000 ALTER TABLE `jo_profile2tab` DISABLE KEYS */;
INSERT INTO `jo_profile2tab` VALUES (1,1,0),(1,2,0),(1,3,0),(1,4,0),(1,6,0),(1,7,0),(1,8,0),(1,9,0),(1,10,0),(1,13,0),(1,14,0),(1,15,0),(1,16,0),(1,18,0),(1,19,0),(1,20,0),(1,21,0),(1,22,0),(1,23,0),(1,24,0),(1,25,0),(1,26,0),(1,27,0),(2,1,0),(2,2,0),(2,3,0),(2,4,0),(2,6,0),(2,7,0),(2,8,0),(2,9,0),(2,10,0),(2,13,0),(2,14,0),(2,15,0),(2,16,0),(2,18,0),(2,19,0),(2,20,0),(2,21,0),(2,22,0),(2,23,0),(2,24,0),(2,25,0),(2,26,0),(2,27,0),(3,1,0),(3,2,0),(3,3,0),(3,4,0),(3,6,0),(3,7,0),(3,8,0),(3,9,0),(3,10,0),(3,13,0),(3,14,0),(3,15,0),(3,16,0),(3,18,0),(3,19,0),(3,20,0),(3,21,0),(3,22,0),(3,23,0),(3,24,0),(3,25,0),(3,26,0),(3,27,0),(4,1,0),(4,2,0),(4,3,0),(4,4,0),(4,6,0),(4,7,0),(4,8,0),(4,9,0),(4,10,0),(4,13,0),(4,14,0),(4,15,0),(4,16,0),(4,18,0),(4,19,0),(4,20,0),(4,21,0),(4,22,0),(4,23,0),(4,24,0),(4,25,0),(4,26,0),(4,27,0),(1,34,0),(2,34,0),(3,34,0),(4,34,0),(1,35,0),(2,35,0),(3,35,0),(4,35,0),(1,36,0),(2,36,0),(3,36,0),(4,36,0),(1,37,0),(2,37,0),(3,37,0),(4,37,0),(1,38,0),(2,38,0),(3,38,0),(4,38,0),(1,39,0),(2,39,0),(3,39,0),(4,39,0),(1,40,0),(2,40,0),(3,40,0),(4,40,0),(1,41,0),(2,41,0),(3,41,0),(4,41,0),(1,42,0),(2,42,0),(3,42,0),(4,42,0),(1,43,0),(2,43,0),(3,43,0),(4,43,0),(1,44,0),(2,44,0),(3,44,0),(4,44,0),(1,45,0),(2,45,0),(3,45,0),(4,45,0),(1,46,0),(2,46,0),(3,46,0),(4,46,0),(1,47,0),(2,47,0),(3,47,0),(4,47,0),(1,48,0),(2,48,0),(3,48,0),(4,48,0),(1,49,1),(2,49,1),(3,49,1),(4,49,1),(1,49,1),(2,49,1),(3,49,1),(4,49,1),(5,1,1),(5,2,0),(5,4,0),(5,6,0),(5,7,1),(5,8,0),(5,9,0),(5,10,0),(5,13,0),(5,14,0),(5,18,1),(5,19,1),(5,20,0),(5,21,1),(5,22,0),(5,23,0),(5,25,1),(5,26,0),(5,27,1),(5,30,1),(5,31,1),(5,32,1),(5,33,1),(5,36,0),(5,37,0),(5,41,1),(5,42,1),(5,43,1),(5,44,0),(5,45,1),(5,46,1),(5,47,1),(5,48,1),(5,49,1),(5,16,0),(6,1,1),(6,2,0),(6,4,0),(6,6,0),(6,7,1),(6,8,0),(6,9,0),(6,10,0),(6,13,0),(6,14,0),(6,18,1),(6,19,1),(6,20,0),(6,21,1),(6,22,0),(6,23,0),(6,25,1),(6,26,0),(6,27,1),(6,30,1),(6,31,1),(6,32,1),(6,33,1),(6,36,0),(6,37,0),(6,41,1),(6,42,1),(6,43,1),(6,44,0),(6,45,1),(6,46,1),(6,47,1),(6,48,1),(6,49,1),(6,16,0),(8,1,0),(8,2,0),(8,4,0),(8,6,0),(8,7,0),(8,8,0),(8,9,0),(8,10,0),(8,13,0),(8,14,0),(8,18,0),(8,19,0),(8,20,0),(8,21,0),(8,22,0),(8,23,0),(8,25,0),(8,26,0),(8,27,0),(8,30,0),(8,31,0),(8,32,0),(8,33,0),(8,36,0),(8,37,0),(8,41,0),(8,42,0),(8,43,0),(8,44,0),(8,45,0),(8,46,0),(8,47,0),(8,48,0),(8,49,1),(8,16,0),(9,1,0),(9,2,0),(9,4,0),(9,6,0),(9,7,0),(9,8,0),(9,9,0),(9,10,0),(9,13,0),(9,14,0),(9,18,0),(9,19,0),(9,20,0),(9,21,0),(9,22,0),(9,23,0),(9,25,0),(9,26,0),(9,27,0),(9,30,0),(9,31,0),(9,32,0),(9,33,0),(9,36,0),(9,37,0),(9,41,0),(9,42,0),(9,43,0),(9,44,0),(9,45,0),(9,46,0),(9,47,0),(9,48,0),(9,49,1),(9,16,0),(7,1,0),(7,2,0),(7,4,0),(7,6,0),(7,7,0),(7,8,0),(7,9,0),(7,10,0),(7,13,0),(7,14,0),(7,18,0),(7,19,0),(7,20,0),(7,21,0),(7,22,0),(7,23,0),(7,25,0),(7,26,0),(7,27,0),(7,30,0),(7,31,0),(7,32,0),(7,33,0),(7,36,0),(7,37,0),(7,41,0),(7,42,0),(7,43,0),(7,44,0),(7,45,0),(7,46,0),(7,47,0),(7,48,0),(7,49,1),(7,16,0);
/*!40000 ALTER TABLE `jo_profile2tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_profile2utility`
--

DROP TABLE IF EXISTS `jo_profile2utility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_profile2utility` (
  `profileid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `activityid` int(11) NOT NULL,
  `permission` int(1) DEFAULT NULL,
  PRIMARY KEY (`profileid`,`tabid`,`activityid`),
  KEY `profile2utility_profileid_tabid_activityid_idx` (`profileid`,`tabid`,`activityid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_profile2utility`
--

LOCK TABLES `jo_profile2utility` WRITE;
/*!40000 ALTER TABLE `jo_profile2utility` DISABLE KEYS */;
INSERT INTO `jo_profile2utility` VALUES (1,2,5,0),(1,2,6,0),(1,2,10,0),(1,4,5,0),(1,4,6,0),(1,4,8,0),(1,4,10,0),(1,4,14,0),(1,6,5,0),(1,6,6,0),(1,6,8,0),(1,6,10,0),(1,7,5,0),(1,7,6,0),(1,7,8,0),(1,7,9,0),(1,7,10,0),(1,8,6,0),(1,9,5,0),(1,9,6,0),(1,13,5,0),(1,13,6,0),(1,13,8,0),(1,13,10,0),(1,14,5,0),(1,14,6,0),(1,14,10,0),(1,18,5,0),(1,18,6,0),(1,18,10,0),(1,19,5,0),(1,19,6,0),(1,19,10,0),(1,20,5,0),(1,20,6,0),(1,21,5,0),(1,21,6,0),(1,22,5,0),(1,22,6,0),(1,23,5,0),(1,23,6,0),(1,25,6,0),(1,25,13,0),(1,36,5,0),(1,36,6,0),(1,36,10,0),(1,37,11,0),(1,37,12,0),(1,40,5,0),(1,40,6,0),(1,40,10,0),(1,42,5,0),(1,42,6,0),(1,42,10,0),(1,43,5,0),(1,43,6,0),(1,43,10,0),(1,44,5,0),(1,44,6,0),(1,44,10,0),(2,2,5,1),(2,2,6,1),(2,2,10,0),(2,4,5,1),(2,4,6,1),(2,4,8,0),(2,4,10,0),(2,4,14,1),(2,6,5,1),(2,6,6,1),(2,6,8,0),(2,6,10,0),(2,7,5,1),(2,7,6,1),(2,7,8,0),(2,7,9,0),(2,7,10,0),(2,8,6,1),(2,9,5,0),(2,9,6,0),(2,13,5,1),(2,13,6,1),(2,13,8,0),(2,13,10,0),(2,14,5,1),(2,14,6,1),(2,14,10,0),(2,18,5,1),(2,18,6,1),(2,18,10,0),(2,19,5,1),(2,19,6,1),(2,19,10,0),(2,20,5,0),(2,20,6,0),(2,21,5,0),(2,21,6,0),(2,22,5,0),(2,22,6,0),(2,23,5,0),(2,23,6,0),(2,25,6,0),(2,25,13,0),(2,36,5,0),(2,36,6,0),(2,36,10,0),(2,37,11,0),(2,37,12,0),(2,40,5,1),(2,40,6,1),(2,40,10,0),(2,42,5,0),(2,42,6,0),(2,42,10,0),(2,43,5,0),(2,43,6,0),(2,43,10,0),(2,44,5,0),(2,44,6,0),(2,44,10,0),(3,2,5,1),(3,2,6,1),(3,2,10,0),(3,4,5,1),(3,4,6,1),(3,4,8,0),(3,4,10,0),(3,4,14,1),(3,6,5,1),(3,6,6,1),(3,6,8,0),(3,6,10,0),(3,7,5,1),(3,7,6,1),(3,7,8,0),(3,7,9,0),(3,7,10,0),(3,8,6,1),(3,9,5,0),(3,9,6,0),(3,13,5,1),(3,13,6,1),(3,13,8,0),(3,13,10,0),(3,14,5,1),(3,14,6,1),(3,14,10,0),(3,18,5,1),(3,18,6,1),(3,18,10,0),(3,19,5,1),(3,19,6,1),(3,19,10,0),(3,20,5,0),(3,20,6,0),(3,21,5,0),(3,21,6,0),(3,22,5,0),(3,22,6,0),(3,23,5,0),(3,23,6,0),(3,25,6,0),(3,25,13,0),(3,36,5,0),(3,36,6,0),(3,36,10,0),(3,37,11,0),(3,37,12,0),(3,40,5,1),(3,40,6,1),(3,40,10,0),(3,42,5,0),(3,42,6,0),(3,42,10,0),(3,43,5,0),(3,43,6,0),(3,43,10,0),(3,44,5,0),(3,44,6,0),(3,44,10,0),(4,2,5,1),(4,2,6,1),(4,2,10,0),(4,4,5,1),(4,4,6,1),(4,4,8,1),(4,4,10,0),(4,4,14,1),(4,6,5,1),(4,6,6,1),(4,6,8,1),(4,6,10,0),(4,7,5,1),(4,7,6,1),(4,7,8,1),(4,7,9,0),(4,7,10,0),(4,8,6,1),(4,9,5,0),(4,9,6,0),(4,13,5,1),(4,13,6,1),(4,13,8,1),(4,13,10,0),(4,14,5,1),(4,14,6,1),(4,14,10,0),(4,18,5,1),(4,18,6,1),(4,18,10,0),(4,19,5,1),(4,19,6,1),(4,19,10,0),(4,20,5,0),(4,20,6,0),(4,21,5,0),(4,21,6,0),(4,22,5,0),(4,22,6,0),(4,23,5,0),(4,23,6,0),(4,25,6,0),(4,25,13,0),(4,36,5,0),(4,36,6,0),(4,36,10,0),(4,37,11,0),(4,37,12,0),(4,40,5,1),(4,40,6,1),(4,40,10,0),(4,42,5,0),(4,42,6,0),(4,42,10,0),(4,43,5,0),(4,43,6,0),(4,43,10,0),(4,44,5,0),(4,44,6,0),(4,44,10,0),(5,2,5,0),(5,2,6,0),(5,2,10,0),(5,4,5,0),(5,4,6,0),(5,4,8,0),(5,4,10,0),(5,6,5,0),(5,6,6,0),(5,6,8,0),(5,6,10,0),(5,7,5,0),(5,7,6,0),(5,7,8,0),(5,7,9,0),(5,7,10,0),(5,8,6,0),(5,9,5,0),(5,9,6,0),(5,13,5,0),(5,13,6,0),(5,13,8,0),(5,13,10,0),(5,14,5,0),(5,14,6,0),(5,14,10,0),(5,16,5,0),(5,16,6,0),(5,18,5,0),(5,18,6,0),(5,18,10,0),(5,19,5,0),(5,19,6,0),(5,19,10,0),(5,20,5,0),(5,20,6,0),(5,21,5,0),(5,21,6,0),(5,22,5,0),(5,22,6,0),(5,23,5,0),(5,23,6,0),(5,25,6,0),(5,25,13,0),(5,36,5,0),(5,36,6,0),(5,36,10,0),(5,37,5,0),(5,37,6,0),(5,37,8,0),(5,37,11,0),(5,37,12,0),(5,42,5,0),(5,42,6,0),(5,42,10,0),(5,43,5,0),(5,43,6,0),(5,43,10,0),(5,44,5,0),(5,44,6,0),(5,44,10,0),(6,2,5,0),(6,2,6,0),(6,2,10,0),(6,4,5,0),(6,4,6,0),(6,4,8,0),(6,4,10,0),(6,4,14,0),(6,6,5,0),(6,6,6,0),(6,6,8,0),(6,6,10,0),(6,7,5,0),(6,7,6,0),(6,7,8,0),(6,7,9,0),(6,7,10,0),(6,8,6,0),(6,9,5,0),(6,9,6,0),(6,13,5,0),(6,13,6,0),(6,13,8,0),(6,13,10,0),(6,14,5,0),(6,14,6,0),(6,14,10,0),(6,16,5,0),(6,16,6,0),(6,18,5,0),(6,18,6,0),(6,18,10,0),(6,19,5,0),(6,19,6,0),(6,19,10,0),(6,20,5,0),(6,20,6,0),(6,21,5,0),(6,21,6,0),(6,22,5,0),(6,22,6,0),(6,23,5,0),(6,23,6,0),(6,25,6,0),(6,25,13,0),(6,36,5,0),(6,36,6,0),(6,36,10,0),(6,37,5,0),(6,37,6,0),(6,37,8,0),(6,37,11,0),(6,37,12,0),(6,42,5,0),(6,42,6,0),(6,42,10,0),(6,43,5,0),(6,43,6,0),(6,43,10,0),(6,44,5,0),(6,44,6,0),(6,44,10,0),(7,2,5,0),(7,2,6,0),(7,2,10,0),(7,4,5,0),(7,4,6,0),(7,4,8,0),(7,4,10,0),(7,4,14,0),(7,6,5,0),(7,6,6,0),(7,6,8,0),(7,6,10,0),(7,7,5,0),(7,7,6,0),(7,7,8,0),(7,7,9,0),(7,7,10,0),(7,8,6,0),(7,9,5,0),(7,9,6,0),(7,13,5,0),(7,13,6,0),(7,13,8,0),(7,13,10,0),(7,14,5,0),(7,14,6,0),(7,14,10,0),(7,16,5,0),(7,16,6,0),(7,18,5,0),(7,18,6,0),(7,18,10,0),(7,19,5,0),(7,19,6,0),(7,19,10,0),(7,20,5,0),(7,20,6,0),(7,21,5,0),(7,21,6,0),(7,22,5,0),(7,22,6,0),(7,23,5,0),(7,23,6,0),(7,25,6,0),(7,25,13,0),(7,36,5,0),(7,36,6,0),(7,36,10,0),(7,37,5,0),(7,37,6,0),(7,37,8,0),(7,37,11,0),(7,37,12,0),(7,42,5,0),(7,42,6,0),(7,42,10,0),(7,43,5,0),(7,43,6,0),(7,43,10,0),(7,44,5,0),(7,44,6,0),(7,44,10,0),(8,2,5,0),(8,2,6,0),(8,2,10,0),(8,4,5,0),(8,4,6,0),(8,4,8,0),(8,4,10,0),(8,4,14,0),(8,6,5,0),(8,6,6,0),(8,6,8,0),(8,6,10,0),(8,7,5,0),(8,7,6,0),(8,7,8,0),(8,7,9,0),(8,7,10,0),(8,8,6,0),(8,9,5,0),(8,9,6,0),(8,13,5,0),(8,13,6,0),(8,13,8,0),(8,13,10,0),(8,14,5,0),(8,14,6,0),(8,14,10,0),(8,16,5,0),(8,16,6,0),(8,18,5,0),(8,18,6,0),(8,18,10,0),(8,19,5,0),(8,19,6,0),(8,19,10,0),(8,20,5,0),(8,20,6,0),(8,21,5,0),(8,21,6,0),(8,22,5,0),(8,22,6,0),(8,23,5,0),(8,23,6,0),(8,25,6,0),(8,25,13,0),(8,36,5,0),(8,36,6,0),(8,36,10,0),(8,37,5,0),(8,37,6,0),(8,37,8,0),(8,37,11,0),(8,37,12,0),(8,42,5,0),(8,42,6,0),(8,42,10,0),(8,43,5,0),(8,43,6,0),(8,43,10,0),(8,44,5,0),(8,44,6,0),(8,44,10,0),(9,2,5,0),(9,2,6,0),(9,2,10,0),(9,4,5,0),(9,4,6,0),(9,4,8,0),(9,4,10,0),(9,4,14,0),(9,6,5,0),(9,6,6,0),(9,6,8,0),(9,6,10,0),(9,7,5,0),(9,7,6,0),(9,7,8,0),(9,7,9,0),(9,7,10,0),(9,8,6,0),(9,9,5,0),(9,9,6,0),(9,13,5,0),(9,13,6,0),(9,13,8,0),(9,13,10,0),(9,14,5,0),(9,14,6,0),(9,14,10,0),(9,16,5,0),(9,16,6,0),(9,18,5,0),(9,18,6,0),(9,18,10,0),(9,19,5,0),(9,19,6,0),(9,19,10,0),(9,20,5,0),(9,20,6,0),(9,21,5,0),(9,21,6,0),(9,22,5,0),(9,22,6,0),(9,23,5,0),(9,23,6,0),(9,25,6,0),(9,25,13,0),(9,36,5,0),(9,36,6,0),(9,36,10,0),(9,37,5,0),(9,37,6,0),(9,37,8,0),(9,37,11,0),(9,37,12,0),(9,42,5,0),(9,42,6,0),(9,42,10,0),(9,43,5,0),(9,43,6,0),(9,43,10,0),(9,44,5,0),(9,44,6,0),(9,44,10,0);
/*!40000 ALTER TABLE `jo_profile2utility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_progress`
--

DROP TABLE IF EXISTS `jo_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_progress` (
  `progressid` int(11) NOT NULL AUTO_INCREMENT,
  `progress` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`progressid`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_progress`
--

LOCK TABLES `jo_progress` WRITE;
/*!40000 ALTER TABLE `jo_progress` DISABLE KEYS */;
INSERT INTO `jo_progress` VALUES (2,'10%',1,255,2,NULL),(3,'20%',1,256,3,NULL),(4,'30%',1,257,4,NULL),(5,'40%',1,258,5,NULL),(6,'50%',1,259,6,NULL),(7,'60%',1,260,7,NULL),(8,'70%',1,261,8,NULL),(9,'80%',1,262,9,NULL),(10,'90%',1,263,10,NULL),(11,'100%',1,264,11,NULL),(13,'10%',1,306,13,NULL),(14,'20%',1,307,14,NULL),(15,'30%',1,308,15,NULL),(16,'40%',1,309,16,NULL),(17,'50%',1,310,17,NULL),(18,'60%',1,311,18,NULL),(19,'70%',1,312,19,NULL),(20,'80%',1,313,20,NULL),(21,'90%',1,314,21,NULL),(22,'100%',1,315,22,NULL),(23,'--none--',1,380,23,NULL),(24,'10%',1,381,24,NULL),(25,'20%',1,382,25,NULL),(26,'30%',1,383,26,NULL),(27,'40%',1,384,27,NULL),(28,'50%',1,385,28,NULL),(29,'60%',1,386,29,NULL),(30,'70%',1,387,30,NULL),(31,'80%',1,388,31,NULL),(32,'90%',1,389,32,NULL),(33,'100%',1,390,33,NULL),(34,'--none--',1,431,34,NULL),(35,'10%',1,432,35,NULL),(36,'20%',1,433,36,NULL),(37,'30%',1,434,37,NULL),(38,'40%',1,435,38,NULL),(39,'50%',1,436,39,NULL),(40,'60%',1,437,40,NULL),(41,'70%',1,438,41,NULL),(42,'80%',1,439,42,NULL),(43,'90%',1,440,43,NULL),(44,'100%',1,441,44,NULL),(45,'--none--',1,482,45,NULL),(46,'10%',1,483,46,NULL),(47,'20%',1,484,47,NULL),(48,'30%',1,485,48,NULL),(49,'40%',1,486,49,NULL),(50,'50%',1,487,50,NULL),(51,'60%',1,488,51,NULL),(52,'70%',1,489,52,NULL),(53,'80%',1,490,53,NULL),(54,'90%',1,491,54,NULL),(55,'100%',1,492,55,NULL),(56,'--none--',1,533,56,NULL),(57,'10%',1,534,57,NULL),(58,'20%',1,535,58,NULL),(59,'30%',1,536,59,NULL),(60,'40%',1,537,60,NULL),(61,'50%',1,538,61,NULL),(62,'60%',1,539,62,NULL),(63,'70%',1,540,63,NULL),(64,'80%',1,541,64,NULL),(65,'90%',1,542,65,NULL),(66,'100%',1,543,66,NULL),(67,'--none--',1,584,67,NULL),(68,'10%',1,585,68,NULL),(69,'20%',1,586,69,NULL),(70,'30%',1,587,70,NULL),(71,'40%',1,588,71,NULL),(72,'50%',1,589,72,NULL),(73,'60%',1,590,73,NULL),(74,'70%',1,591,74,NULL),(75,'80%',1,592,75,NULL),(76,'90%',1,593,76,NULL),(77,'100%',1,594,77,NULL);
/*!40000 ALTER TABLE `jo_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_project`
--

DROP TABLE IF EXISTS `jo_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_project` (
  `projectid` int(19) NOT NULL,
  `projectname` varchar(255) DEFAULT NULL,
  `project_no` varchar(100) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `targetenddate` date DEFAULT NULL,
  `actualenddate` date DEFAULT NULL,
  `targetbudget` varchar(255) DEFAULT NULL,
  `projecturl` varchar(255) DEFAULT NULL,
  `projectstatus` varchar(100) DEFAULT NULL,
  `projectpriority` varchar(100) DEFAULT NULL,
  `projecttype` varchar(100) DEFAULT NULL,
  `progress` varchar(100) DEFAULT NULL,
  `linktoaccountscontacts` varchar(100) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `isconvertedfrompotential` int(1) NOT NULL DEFAULT '0',
  `potentialid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`projectid`),
  CONSTRAINT `fk_crmid_jo_project` FOREIGN KEY (`projectid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_project`
--

LOCK TABLES `jo_project` WRITE;
/*!40000 ALTER TABLE `jo_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projectcf`
--

DROP TABLE IF EXISTS `jo_projectcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectcf` (
  `projectid` int(11) NOT NULL,
  PRIMARY KEY (`projectid`),
  CONSTRAINT `fk_projectid_jo_projectcf` FOREIGN KEY (`projectid`) REFERENCES `jo_project` (`projectid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectcf`
--

LOCK TABLES `jo_projectcf` WRITE;
/*!40000 ALTER TABLE `jo_projectcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_projectcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projectmilestone`
--

DROP TABLE IF EXISTS `jo_projectmilestone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectmilestone` (
  `projectmilestoneid` int(11) NOT NULL,
  `projectmilestonename` varchar(255) DEFAULT NULL,
  `projectmilestone_no` varchar(100) DEFAULT NULL,
  `projectmilestonedate` varchar(255) DEFAULT NULL,
  `projectid` varchar(100) DEFAULT NULL,
  `projectmilestonetype` varchar(100) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`projectmilestoneid`),
  CONSTRAINT `fk_crmid_jo_projectmilestone` FOREIGN KEY (`projectmilestoneid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectmilestone`
--

LOCK TABLES `jo_projectmilestone` WRITE;
/*!40000 ALTER TABLE `jo_projectmilestone` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_projectmilestone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projectmilestonecf`
--

DROP TABLE IF EXISTS `jo_projectmilestonecf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectmilestonecf` (
  `projectmilestoneid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`projectmilestoneid`),
  CONSTRAINT `fk_projectmilestoneid_jo_projectmilestonecf` FOREIGN KEY (`projectmilestoneid`) REFERENCES `jo_projectmilestone` (`projectmilestoneid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectmilestonecf`
--

LOCK TABLES `jo_projectmilestonecf` WRITE;
/*!40000 ALTER TABLE `jo_projectmilestonecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_projectmilestonecf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projectmilestonetype`
--

DROP TABLE IF EXISTS `jo_projectmilestonetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectmilestonetype` (
  `projectmilestonetypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projectmilestonetype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projectmilestonetypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectmilestonetype`
--

LOCK TABLES `jo_projectmilestonetype` WRITE;
/*!40000 ALTER TABLE `jo_projectmilestonetype` DISABLE KEYS */;
INSERT INTO `jo_projectmilestonetype` VALUES (2,'administrative',1,215,2,NULL),(3,'operative',1,216,3,NULL),(4,'other',1,217,4,NULL),(6,'administrative',1,266,6,NULL),(7,'operative',1,267,7,NULL),(8,'other',1,268,8,NULL),(9,'--none--',1,340,9,NULL),(10,'administrative',1,341,10,NULL),(11,'operative',1,342,11,NULL),(12,'other',1,343,12,NULL),(13,'--none--',1,391,13,NULL),(14,'administrative',1,392,14,NULL),(15,'operative',1,393,15,NULL),(16,'other',1,394,16,NULL),(17,'--none--',1,442,17,NULL),(18,'administrative',1,443,18,NULL),(19,'operative',1,444,19,NULL),(20,'other',1,445,20,NULL),(21,'--none--',1,493,21,NULL),(22,'administrative',1,494,22,NULL),(23,'operative',1,495,23,NULL),(24,'other',1,496,24,NULL),(25,'--none--',1,544,25,NULL),(26,'administrative',1,545,26,NULL),(27,'operative',1,546,27,NULL),(28,'other',1,547,28,NULL);
/*!40000 ALTER TABLE `jo_projectmilestonetype` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_projectpriority`
--

DROP TABLE IF EXISTS `jo_projectpriority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectpriority` (
  `projectpriorityid` int(11) NOT NULL AUTO_INCREMENT,
  `projectpriority` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projectpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectpriority`
--

LOCK TABLES `jo_projectpriority` WRITE;
/*!40000 ALTER TABLE `jo_projectpriority` DISABLE KEYS */;
INSERT INTO `jo_projectpriority` VALUES (2,'low',1,251,2,NULL),(3,'normal',1,252,3,NULL),(4,'high',1,253,4,NULL),(6,'low',1,302,6,NULL),(7,'normal',1,303,7,NULL),(8,'high',1,304,8,NULL),(9,'--none--',1,376,9,NULL),(10,'low',1,377,10,NULL),(11,'normal',1,378,11,NULL),(12,'high',1,379,12,NULL),(13,'--none--',1,427,13,NULL),(14,'low',1,428,14,NULL),(15,'normal',1,429,15,NULL),(16,'high',1,430,16,NULL),(17,'--none--',1,478,17,NULL),(18,'low',1,479,18,NULL),(19,'normal',1,480,19,NULL),(20,'high',1,481,20,NULL),(21,'--none--',1,529,21,NULL),(22,'low',1,530,22,NULL),(23,'normal',1,531,23,NULL),(24,'high',1,532,24,NULL),(25,'--none--',1,580,25,NULL),(26,'low',1,581,26,NULL),(27,'normal',1,582,27,NULL),(28,'high',1,583,28,NULL);
/*!40000 ALTER TABLE `jo_projectpriority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projectstatus`
--

DROP TABLE IF EXISTS `jo_projectstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projectstatus` (
  `projectstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `projectstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projectstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projectstatus`
--

LOCK TABLES `jo_projectstatus` WRITE;
/*!40000 ALTER TABLE `jo_projectstatus` DISABLE KEYS */;
INSERT INTO `jo_projectstatus` VALUES (2,'prospecting',1,238,2,NULL),(3,'initiated',1,239,3,NULL),(4,'in progress',1,240,4,NULL),(5,'waiting for feedback',1,241,5,NULL),(6,'on hold',1,242,6,NULL),(7,'completed',1,243,7,NULL),(8,'delivered',1,244,8,NULL),(9,'archived',1,245,9,NULL),(11,'prospecting',1,289,11,NULL),(12,'initiated',1,290,12,NULL),(13,'in progress',1,291,13,NULL),(14,'waiting for feedback',1,292,14,NULL),(15,'on hold',1,293,15,NULL),(16,'completed',1,294,16,NULL),(17,'delivered',1,295,17,NULL),(18,'archived',1,296,18,NULL),(19,'--none--',1,363,19,NULL),(20,'prospecting',1,364,20,NULL),(21,'initiated',1,365,21,NULL),(22,'in progress',1,366,22,NULL),(23,'waiting for feedback',1,367,23,NULL),(24,'on hold',1,368,24,NULL),(25,'completed',1,369,25,NULL),(26,'delivered',1,370,26,NULL),(27,'archived',1,371,27,NULL),(28,'--none--',1,414,28,NULL),(29,'prospecting',1,415,29,NULL),(30,'initiated',1,416,30,NULL),(31,'in progress',1,417,31,NULL),(32,'waiting for feedback',1,418,32,NULL),(33,'on hold',1,419,33,NULL),(34,'completed',1,420,34,NULL),(35,'delivered',1,421,35,NULL),(36,'archived',1,422,36,NULL),(37,'--none--',1,465,37,NULL),(38,'prospecting',1,466,38,NULL),(39,'initiated',1,467,39,NULL),(40,'in progress',1,468,40,NULL),(41,'waiting for feedback',1,469,41,NULL),(42,'on hold',1,470,42,NULL),(43,'completed',1,471,43,NULL),(44,'delivered',1,472,44,NULL),(45,'archived',1,473,45,NULL),(46,'--none--',1,516,46,NULL),(47,'prospecting',1,517,47,NULL),(48,'initiated',1,518,48,NULL),(49,'in progress',1,519,49,NULL),(50,'waiting for feedback',1,520,50,NULL),(51,'on hold',1,521,51,NULL),(52,'completed',1,522,52,NULL),(53,'delivered',1,523,53,NULL),(54,'archived',1,524,54,NULL),(55,'--none--',1,567,55,NULL),(56,'prospecting',1,568,56,NULL),(57,'initiated',1,569,57,NULL),(58,'in progress',1,570,58,NULL),(59,'waiting for feedback',1,571,59,NULL),(60,'on hold',1,572,60,NULL),(61,'completed',1,573,61,NULL),(62,'delivered',1,574,62,NULL),(63,'archived',1,575,63,NULL);
/*!40000 ALTER TABLE `jo_projectstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttask`
--

DROP TABLE IF EXISTS `jo_projecttask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttask` (
  `projecttaskid` int(11) NOT NULL,
  `projecttaskname` varchar(255) DEFAULT NULL,
  `projecttask_no` varchar(100) DEFAULT NULL,
  `projecttasktype` varchar(100) DEFAULT NULL,
  `projecttaskpriority` varchar(100) DEFAULT NULL,
  `projecttaskprogress` varchar(100) DEFAULT NULL,
  `projecttaskhours` varchar(255) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `projectid` varchar(100) DEFAULT NULL,
  `projecttasknumber` int(11) DEFAULT NULL,
  `projecttaskstatus` varchar(100) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`projecttaskid`),
  CONSTRAINT `fk_crmid_jo_projecttask` FOREIGN KEY (`projecttaskid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttask`
--

LOCK TABLES `jo_projecttask` WRITE;
/*!40000 ALTER TABLE `jo_projecttask` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_projecttask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttask_status_color`
--

DROP TABLE IF EXISTS `jo_projecttask_status_color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttask_status_color` (
  `status` varchar(255) DEFAULT NULL,
  `defaultcolor` varchar(50) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttask_status_color`
--

LOCK TABLES `jo_projecttask_status_color` WRITE;
/*!40000 ALTER TABLE `jo_projecttask_status_color` DISABLE KEYS */;
INSERT INTO `jo_projecttask_status_color` VALUES ('Open','#0099ff',NULL),('In Progress','#fdff00',NULL),('Completed','#3BBF67',NULL),('Deferred','#fbb11e',NULL),('Canceled','#660066',NULL);
/*!40000 ALTER TABLE `jo_projecttask_status_color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttaskcf`
--

DROP TABLE IF EXISTS `jo_projecttaskcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttaskcf` (
  `projecttaskid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`projecttaskid`),
  CONSTRAINT `fk_projecttaskid_jo_projecttaskcf` FOREIGN KEY (`projecttaskid`) REFERENCES `jo_projecttask` (`projecttaskid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttaskcf`
--

LOCK TABLES `jo_projecttaskcf` WRITE;
/*!40000 ALTER TABLE `jo_projecttaskcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_projecttaskcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttaskpriority`
--

DROP TABLE IF EXISTS `jo_projecttaskpriority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttaskpriority` (
  `projecttaskpriorityid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskpriority` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projecttaskpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttaskpriority`
--

LOCK TABLES `jo_projecttaskpriority` WRITE;
/*!40000 ALTER TABLE `jo_projecttaskpriority` DISABLE KEYS */;
INSERT INTO `jo_projecttaskpriority` VALUES (2,'low',1,223,2,NULL),(3,'normal',1,224,3,NULL),(4,'high',1,225,4,NULL),(6,'low',1,274,6,NULL),(7,'normal',1,275,7,NULL),(8,'high',1,276,8,NULL),(9,'--none--',1,348,9,NULL),(10,'low',1,349,10,NULL),(11,'normal',1,350,11,NULL),(12,'high',1,351,12,NULL),(13,'--none--',1,399,13,NULL),(14,'low',1,400,14,NULL),(15,'normal',1,401,15,NULL),(16,'high',1,402,16,NULL),(17,'--none--',1,450,17,NULL),(18,'low',1,451,18,NULL),(19,'normal',1,452,19,NULL),(20,'high',1,453,20,NULL),(21,'--none--',1,501,21,NULL),(22,'low',1,502,22,NULL),(23,'normal',1,503,23,NULL),(24,'high',1,504,24,NULL),(25,'--none--',1,552,25,NULL),(26,'low',1,553,26,NULL),(27,'normal',1,554,27,NULL),(28,'high',1,555,28,NULL);
/*!40000 ALTER TABLE `jo_projecttaskpriority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttaskprogress`
--

DROP TABLE IF EXISTS `jo_projecttaskprogress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttaskprogress` (
  `projecttaskprogressid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskprogress` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projecttaskprogressid`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttaskprogress`
--

LOCK TABLES `jo_projecttaskprogress` WRITE;
/*!40000 ALTER TABLE `jo_projecttaskprogress` DISABLE KEYS */;
INSERT INTO `jo_projecttaskprogress` VALUES (2,'10%',1,227,2,NULL),(3,'20%',1,228,3,NULL),(4,'30%',1,229,4,NULL),(5,'40%',1,230,5,NULL),(6,'50%',1,231,6,NULL),(7,'60%',1,232,7,NULL),(8,'70%',1,233,8,NULL),(9,'80%',1,234,9,NULL),(10,'90%',1,235,10,NULL),(11,'100%',1,236,11,NULL),(13,'10%',1,278,13,NULL),(14,'20%',1,279,14,NULL),(15,'30%',1,280,15,NULL),(16,'40%',1,281,16,NULL),(17,'50%',1,282,17,NULL),(18,'60%',1,283,18,NULL),(19,'70%',1,284,19,NULL),(20,'80%',1,285,20,NULL),(21,'90%',1,286,21,NULL),(22,'100%',1,287,22,NULL),(23,'--none--',1,352,23,NULL),(24,'10%',1,353,24,NULL),(25,'20%',1,354,25,NULL),(26,'30%',1,355,26,NULL),(27,'40%',1,356,27,NULL),(28,'50%',1,357,28,NULL),(29,'60%',1,358,29,NULL),(30,'70%',1,359,30,NULL),(31,'80%',1,360,31,NULL),(32,'90%',1,361,32,NULL),(33,'100%',1,362,33,NULL),(34,'--none--',1,403,34,NULL),(35,'10%',1,404,35,NULL),(36,'20%',1,405,36,NULL),(37,'30%',1,406,37,NULL),(38,'40%',1,407,38,NULL),(39,'50%',1,408,39,NULL),(40,'60%',1,409,40,NULL),(41,'70%',1,410,41,NULL),(42,'80%',1,411,42,NULL),(43,'90%',1,412,43,NULL),(44,'100%',1,413,44,NULL),(45,'--none--',1,454,45,NULL),(46,'10%',1,455,46,NULL),(47,'20%',1,456,47,NULL),(48,'30%',1,457,48,NULL),(49,'40%',1,458,49,NULL),(50,'50%',1,459,50,NULL),(51,'60%',1,460,51,NULL),(52,'70%',1,461,52,NULL),(53,'80%',1,462,53,NULL),(54,'90%',1,463,54,NULL),(55,'100%',1,464,55,NULL),(56,'--none--',1,505,56,NULL),(57,'10%',1,506,57,NULL),(58,'20%',1,507,58,NULL),(59,'30%',1,508,59,NULL),(60,'40%',1,509,60,NULL),(61,'50%',1,510,61,NULL),(62,'60%',1,511,62,NULL),(63,'70%',1,512,63,NULL),(64,'80%',1,513,64,NULL),(65,'90%',1,514,65,NULL),(66,'100%',1,515,66,NULL),(67,'--none--',1,556,67,NULL),(68,'10%',1,557,68,NULL),(69,'20%',1,558,69,NULL),(70,'30%',1,559,70,NULL),(71,'40%',1,560,71,NULL),(72,'50%',1,561,72,NULL),(73,'60%',1,562,73,NULL),(74,'70%',1,563,74,NULL),(75,'80%',1,564,75,NULL),(76,'90%',1,565,76,NULL),(77,'100%',1,566,77,NULL);
/*!40000 ALTER TABLE `jo_projecttaskprogress` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_projecttaskstatus`
--

DROP TABLE IF EXISTS `jo_projecttaskstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttaskstatus` (
  `projecttaskstatusid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttaskstatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projecttaskstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttaskstatus`
--

LOCK TABLES `jo_projecttaskstatus` WRITE;
/*!40000 ALTER TABLE `jo_projecttaskstatus` DISABLE KEYS */;
INSERT INTO `jo_projecttaskstatus` VALUES (2,'Open',0,318,2,NULL),(3,'In Progress',0,319,3,NULL),(4,'Completed',0,320,4,NULL),(5,'Deferred',0,321,5,NULL),(6,'Canceled ',0,322,6,NULL);
/*!40000 ALTER TABLE `jo_projecttaskstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttasktype`
--

DROP TABLE IF EXISTS `jo_projecttasktype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttasktype` (
  `projecttasktypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttasktype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projecttasktypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttasktype`
--

LOCK TABLES `jo_projecttasktype` WRITE;
/*!40000 ALTER TABLE `jo_projecttasktype` DISABLE KEYS */;
INSERT INTO `jo_projecttasktype` VALUES (2,'administrative',1,219,2,NULL),(3,'operative',1,220,3,NULL),(4,'other',1,221,4,NULL),(6,'administrative',1,270,6,NULL),(7,'operative',1,271,7,NULL),(8,'other',1,272,8,NULL),(9,'--none--',1,344,9,NULL),(10,'administrative',1,345,10,NULL),(11,'operative',1,346,11,NULL),(12,'other',1,347,12,NULL),(13,'--none--',1,395,13,NULL),(14,'administrative',1,396,14,NULL),(15,'operative',1,397,15,NULL),(16,'other',1,398,16,NULL),(17,'--none--',1,446,17,NULL),(18,'administrative',1,447,18,NULL),(19,'operative',1,448,19,NULL),(20,'other',1,449,20,NULL),(21,'--none--',1,497,21,NULL),(22,'administrative',1,498,22,NULL),(23,'operative',1,499,23,NULL),(24,'other',1,500,24,NULL),(25,'--none--',1,548,25,NULL),(26,'administrative',1,549,26,NULL),(27,'operative',1,550,27,NULL),(28,'other',1,551,28,NULL);
/*!40000 ALTER TABLE `jo_projecttasktype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_projecttype`
--

DROP TABLE IF EXISTS `jo_projecttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_projecttype` (
  `projecttypeid` int(11) NOT NULL AUTO_INCREMENT,
  `projecttype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`projecttypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_projecttype`
--

LOCK TABLES `jo_projecttype` WRITE;
/*!40000 ALTER TABLE `jo_projecttype` DISABLE KEYS */;
INSERT INTO `jo_projecttype` VALUES (2,'administrative',1,247,2,NULL),(3,'operative',1,248,3,NULL),(4,'other',1,249,4,NULL),(6,'administrative',1,298,6,NULL),(7,'operative',1,299,7,NULL),(8,'other',1,300,8,NULL),(9,'--none--',1,372,9,NULL),(10,'administrative',1,373,10,NULL),(11,'operative',1,374,11,NULL),(12,'other',1,375,12,NULL),(13,'--none--',1,423,13,NULL),(14,'administrative',1,424,14,NULL),(15,'operative',1,425,15,NULL),(16,'other',1,426,16,NULL),(17,'--none--',1,474,17,NULL),(18,'administrative',1,475,18,NULL),(19,'operative',1,476,19,NULL),(20,'other',1,477,20,NULL),(21,'--none--',1,525,21,NULL),(22,'administrative',1,526,22,NULL),(23,'operative',1,527,23,NULL),(24,'other',1,528,24,NULL),(25,'--none--',1,576,25,NULL),(26,'administrative',1,577,26,NULL),(27,'operative',1,578,27,NULL),(28,'other',1,579,28,NULL);
/*!40000 ALTER TABLE `jo_projecttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_purchaseorder`
--

DROP TABLE IF EXISTS `jo_purchaseorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_purchaseorder` (
  `purchaseorderid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `quoteid` int(19) DEFAULT NULL,
  `vendorid` int(19) DEFAULT NULL,
  `requisition_no` varchar(100) DEFAULT NULL,
  `purchaseorder_no` varchar(100) DEFAULT NULL,
  `tracking_no` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `terms_conditions` text,
  `postatus` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `compound_taxes_info` text,
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `paid` decimal(25,8) DEFAULT NULL,
  `balance` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `region_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`purchaseorderid`),
  KEY `purchaseorder_vendorid_idx` (`vendorid`),
  KEY `purchaseorder_quoteid_idx` (`quoteid`),
  KEY `purchaseorder_contactid_idx` (`contactid`),
  CONSTRAINT `fk_4_jo_purchaseorder` FOREIGN KEY (`vendorid`) REFERENCES `jo_vendor` (`vendorid`) ON DELETE CASCADE,
  CONSTRAINT `fk_crmid_jo_purchaseorder` FOREIGN KEY (`purchaseorderid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_purchaseorder`
--

LOCK TABLES `jo_purchaseorder` WRITE;
/*!40000 ALTER TABLE `jo_purchaseorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_purchaseorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_purchaseordercf`
--

DROP TABLE IF EXISTS `jo_purchaseordercf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_purchaseordercf` (
  `purchaseorderid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`purchaseorderid`),
  CONSTRAINT `fk_1_jo_purchaseordercf` FOREIGN KEY (`purchaseorderid`) REFERENCES `jo_purchaseorder` (`purchaseorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_purchaseordercf`
--

LOCK TABLES `jo_purchaseordercf` WRITE;
/*!40000 ALTER TABLE `jo_purchaseordercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_purchaseordercf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotes`
--

DROP TABLE IF EXISTS `jo_quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotes` (
  `quoteid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `potentialid` int(19) DEFAULT NULL,
  `quotestage` varchar(200) DEFAULT NULL,
  `validtill` date DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `quote_no` varchar(100) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `shipping` varchar(100) DEFAULT NULL,
  `inventorymanager` int(19) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `compound_taxes_info` text,
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `region_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`quoteid`),
  KEY `quote_quotestage_idx` (`quotestage`),
  KEY `quotes_potentialid_idx` (`potentialid`),
  KEY `quotes_contactid_idx` (`contactid`),
  CONSTRAINT `fk_3_jo_quotes` FOREIGN KEY (`potentialid`) REFERENCES `jo_potential` (`potentialid`) ON DELETE CASCADE,
  CONSTRAINT `fk_crmid_jo_quotes` FOREIGN KEY (`quoteid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotes`
--

LOCK TABLES `jo_quotes` WRITE;
/*!40000 ALTER TABLE `jo_quotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_quotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotesbillads`
--

DROP TABLE IF EXISTS `jo_quotesbillads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotesbillads` (
  `quotebilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`quotebilladdressid`),
  CONSTRAINT `fk_1_jo_quotesbillads` FOREIGN KEY (`quotebilladdressid`) REFERENCES `jo_quotes` (`quoteid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotesbillads`
--

LOCK TABLES `jo_quotesbillads` WRITE;
/*!40000 ALTER TABLE `jo_quotesbillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_quotesbillads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotescf`
--

DROP TABLE IF EXISTS `jo_quotescf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotescf` (
  `quoteid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`quoteid`),
  CONSTRAINT `fk_1_jo_quotescf` FOREIGN KEY (`quoteid`) REFERENCES `jo_quotes` (`quoteid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotescf`
--

LOCK TABLES `jo_quotescf` WRITE;
/*!40000 ALTER TABLE `jo_quotescf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_quotescf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotesshipads`
--

DROP TABLE IF EXISTS `jo_quotesshipads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotesshipads` (
  `quoteshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`quoteshipaddressid`),
  CONSTRAINT `fk_1_jo_quotesshipads` FOREIGN KEY (`quoteshipaddressid`) REFERENCES `jo_quotes` (`quoteid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotesshipads`
--

LOCK TABLES `jo_quotesshipads` WRITE;
/*!40000 ALTER TABLE `jo_quotesshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_quotesshipads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotestage`
--

DROP TABLE IF EXISTS `jo_quotestage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotestage` (
  `quotestageid` int(19) NOT NULL AUTO_INCREMENT,
  `quotestage` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`quotestageid`),
  UNIQUE KEY `quotestage_quotestage_idx` (`quotestage`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotestage`
--

LOCK TABLES `jo_quotestage` WRITE;
/*!40000 ALTER TABLE `jo_quotestage` DISABLE KEYS */;
INSERT INTO `jo_quotestage` VALUES (1,'Created',0,134,0,NULL),(2,'Delivered',0,135,1,NULL),(3,'Reviewed',0,136,2,NULL),(4,'Accepted',0,137,3,NULL),(5,'Rejected',0,138,4,NULL);
/*!40000 ALTER TABLE `jo_quotestage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_quotestagehistory`
--

DROP TABLE IF EXISTS `jo_quotestagehistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_quotestagehistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `quoteid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `quotestage` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `quotestagehistory_quoteid_idx` (`quoteid`),
  CONSTRAINT `fk_1_jo_quotestagehistory` FOREIGN KEY (`quoteid`) REFERENCES `jo_quotes` (`quoteid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_quotestagehistory`
--

LOCK TABLES `jo_quotestagehistory` WRITE;
/*!40000 ALTER TABLE `jo_quotestagehistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_quotestagehistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_rating`
--

DROP TABLE IF EXISTS `jo_rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_rating` (
  `rating_id` int(19) NOT NULL AUTO_INCREMENT,
  `rating` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`rating_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_rating`
--

LOCK TABLES `jo_rating` WRITE;
/*!40000 ALTER TABLE `jo_rating` DISABLE KEYS */;
INSERT INTO `jo_rating` VALUES (2,'Acquired',1,140,1,NULL),(3,'Active',1,141,2,NULL),(4,'Market Failed',1,142,3,NULL),(5,'Project Cancelled',1,143,4,NULL),(6,'Shutdown',1,144,5,NULL);
/*!40000 ALTER TABLE `jo_rating` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_recurring_frequency`
--

DROP TABLE IF EXISTS `jo_recurring_frequency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_recurring_frequency` (
  `recurring_frequency_id` int(11) DEFAULT NULL,
  `recurring_frequency` varchar(200) DEFAULT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_recurring_frequency`
--

LOCK TABLES `jo_recurring_frequency` WRITE;
/*!40000 ALTER TABLE `jo_recurring_frequency` DISABLE KEYS */;
INSERT INTO `jo_recurring_frequency` VALUES (2,'Daily',1,1,NULL),(3,'Weekly',2,1,NULL),(4,'Monthly',3,1,NULL),(5,'Quarterly',4,1,NULL),(6,'Yearly',5,1,NULL);
/*!40000 ALTER TABLE `jo_recurring_frequency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_recurringevents`
--

DROP TABLE IF EXISTS `jo_recurringevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_recurringevents` (
  `recurringid` int(19) NOT NULL AUTO_INCREMENT,
  `activityid` int(19) NOT NULL,
  `recurringdate` date DEFAULT NULL,
  `recurringtype` varchar(30) DEFAULT NULL,
  `recurringfreq` int(19) DEFAULT NULL,
  `recurringinfo` varchar(50) DEFAULT NULL,
  `recurringenddate` date DEFAULT NULL,
  PRIMARY KEY (`recurringid`),
  KEY `fk_1_jo_recurringevents` (`activityid`),
  CONSTRAINT `fk_1_jo_recurringevents` FOREIGN KEY (`activityid`) REFERENCES `jo_activity` (`activityid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_recurringevents`
--

LOCK TABLES `jo_recurringevents` WRITE;
/*!40000 ALTER TABLE `jo_recurringevents` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_recurringevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_recurringtype`
--

DROP TABLE IF EXISTS `jo_recurringtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_recurringtype` (
  `recurringeventid` int(19) NOT NULL AUTO_INCREMENT,
  `recurringtype` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`recurringeventid`),
  UNIQUE KEY `recurringtype_status_idx` (`recurringtype`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_recurringtype`
--

LOCK TABLES `jo_recurringtype` WRITE;
/*!40000 ALTER TABLE `jo_recurringtype` DISABLE KEYS */;
INSERT INTO `jo_recurringtype` VALUES (2,'Daily',1,1,NULL),(3,'Weekly',2,1,NULL),(4,'Monthly',3,1,NULL),(5,'Yearly',4,1,NULL);
/*!40000 ALTER TABLE `jo_recurringtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_relatedlists`
--

DROP TABLE IF EXISTS `jo_relatedlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_relatedlists` (
  `relation_id` int(19) NOT NULL,
  `tabid` int(10) DEFAULT NULL,
  `related_tabid` int(10) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `sequence` int(10) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `presence` int(10) NOT NULL DEFAULT '0',
  `actions` varchar(50) NOT NULL DEFAULT '',
  `relationfieldid` int(19) DEFAULT NULL,
  `source` varchar(25) DEFAULT NULL,
  `relationtype` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`relation_id`),
  KEY `relatedlists_relation_id_idx` (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_relatedlists`
--

LOCK TABLES `jo_relatedlists` WRITE;
/*!40000 ALTER TABLE `jo_relatedlists` DISABLE KEYS */;
INSERT INTO `jo_relatedlists` VALUES (1,6,4,'get_contacts',2,'Contacts',0,'add',72,'','1:N'),(2,6,2,'get_opportunities',3,'Potentials',0,'add',113,'','1:N'),(3,6,20,'get_quotes',4,'Quotes',0,'add',320,'','1:N'),(4,6,22,'get_salesorder',5,'SalesOrder',0,'add',399,'','1:N'),(5,6,23,'get_invoices',6,'Invoice',0,'add',442,'','1:N'),(6,6,9,'get_activities',7,'Activities',0,'add',238,'','1:N'),(7,6,10,'get_emails',8,'Emails',0,'add',0,'','1:N'),(8,6,9,'get_history',9,'Activity History',0,'add',238,'','1:N'),(9,6,8,'get_attachments',10,'Documents',0,'add,select',0,'','1:N'),(10,6,13,'get_tickets',11,'HelpDesk',0,'add',157,'','1:N'),(11,6,14,'get_products',12,'Products',0,'select',0,'','1:N'),(12,7,9,'get_activities',2,'Activities',0,'add',238,'','1:N'),(13,7,10,'get_emails',3,'Emails',0,'add',0,'','1:N'),(14,7,9,'get_history',4,'Activity History',0,'add',238,'','1:N'),(15,7,8,'get_attachments',5,'Documents',0,'add,select',0,'','1:N'),(16,7,14,'get_products',6,'Products',0,'select',0,'','1:N'),(17,7,26,'get_campaigns',7,'Campaigns',0,'select',0,'','1:N'),(18,4,2,'get_opportunities',2,'Potentials',0,'add',668,'','1:N'),(19,4,9,'get_activities',3,'Activities',0,'add',239,'','1:N'),(20,4,10,'get_emails',4,'Emails',0,'add',0,'','1:N'),(21,4,13,'get_tickets',5,'HelpDesk',0,'add',669,'','1:N'),(22,4,20,'get_quotes',6,'Quotes',0,'add',309,'','1:N'),(23,4,21,'get_purchase_orders',7,'PurchaseOrder',0,'add',346,'','1:N'),(24,4,22,'get_salesorder',8,'SalesOrder',0,'add',385,'','1:N'),(25,4,14,'get_products',9,'Products',0,'select',0,'','1:N'),(26,4,9,'get_history',10,'Activity History',0,'add',239,'','1:N'),(27,4,8,'get_attachments',11,'Documents',0,'add,select',0,'','1:N'),(28,4,26,'get_campaigns',12,'Campaigns',0,'select',0,'','1:N'),(29,4,23,'get_invoices',13,'Invoice',0,'add',429,'','1:N'),(30,2,9,'get_activities',2,'Activities',0,'add',238,'','1:N'),(31,2,4,'get_contacts',3,'Contacts',0,'select',0,'','1:N'),(32,2,14,'get_products',4,'Products',0,'select',0,'','1:N'),(33,2,0,'get_stage_history',5,'Sales Stage History',0,'',0,'','1:N'),(34,2,8,'get_attachments',6,'Documents',0,'add,select',0,'','1:N'),(35,2,20,'get_Quotes',7,'Quotes',0,'add',306,'','1:N'),(36,2,22,'get_salesorder',8,'SalesOrder',0,'add',381,'','1:N'),(37,2,9,'get_history',9,'Activity History',0,'',238,'','1:N'),(38,14,13,'get_tickets',1,'HelpDesk',0,'add',159,'','1:N'),(39,14,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(40,14,20,'get_quotes',4,'Quotes',0,'add',632,'','1:N'),(41,14,21,'get_purchase_orders',5,'PurchaseOrder',0,'add',623,'','1:N'),(42,14,22,'get_salesorder',6,'SalesOrder',0,'add',614,'','1:N'),(43,14,23,'get_invoices',7,'Invoice',0,'add',605,'','1:N'),(44,14,19,'get_product_pricebooks',8,'PriceBooks',0,'ADD,SELECT',0,'','1:N'),(45,14,7,'get_leads',9,'Leads',0,'select',0,'','1:N'),(46,14,6,'get_accounts',10,'Accounts',0,'select',0,'','1:N'),(47,14,4,'get_contacts',11,'Contacts',0,'select',0,'','1:N'),(48,14,2,'get_opportunities',12,'Potentials',0,'select',0,'','1:N'),(49,14,14,'get_products',13,'Product Bundles',0,'add,select',0,'','1:N'),(50,14,14,'get_parent_products',14,'Parent Product',0,'',0,'','1:N'),(51,10,4,'get_contacts',1,'Contacts',0,'select,bulkmail',0,'','1:N'),(52,10,0,'get_users',2,'Users',0,'',0,'','1:N'),(53,10,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(54,13,9,'get_activities',2,'Activities',0,'add',238,'','1:N'),(55,13,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(56,13,0,'get_ticket_history',4,'Ticket History',0,'',0,'','1:N'),(57,13,9,'get_history',5,'Activity History',0,'add',238,'','1:N'),(58,19,14,'get_pricebook_products',2,'Products',0,'select',0,'','1:N'),(59,18,14,'get_products',1,'Products',0,'add,select',185,'','1:N'),(60,18,21,'get_purchase_orders',2,'PurchaseOrder',0,'add',343,'','1:N'),(61,18,4,'get_contacts',3,'Contacts',0,'select',0,'','1:N'),(62,18,10,'get_emails',4,'Emails',0,'add',0,'','1:N'),(63,20,22,'get_salesorder',2,'SalesOrder',0,'add',383,'','1:N'),(64,20,9,'get_activities',3,'Activities',0,'add',0,'','1:N'),(65,20,8,'get_attachments',4,'Documents',0,'add,select',0,'','1:N'),(66,20,9,'get_history',5,'Activity History',0,'',0,'','1:N'),(67,20,0,'get_quotestagehistory',6,'Quote Stage History',0,'',0,'','1:N'),(68,21,9,'get_activities',2,'Activities',0,'add',0,'','1:N'),(69,21,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(70,21,9,'get_history',4,'Activity History',0,'',0,'','1:N'),(71,21,0,'get_postatushistory',5,'PurchaseOrder Status History',0,'',0,'','1:N'),(72,22,9,'get_activities',2,'Activities',0,'add',0,'','1:N'),(73,22,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(74,22,23,'get_invoices',4,'Invoice',0,'',427,'','1:N'),(75,22,9,'get_history',5,'Activity History',0,'',0,'','1:N'),(76,22,0,'get_sostatushistory',6,'SalesOrder Status History',0,'',0,'','1:N'),(77,23,9,'get_activities',2,'Activities',0,'add',0,'','1:N'),(78,23,8,'get_attachments',3,'Documents',0,'add,select',0,'','1:N'),(79,23,9,'get_history',4,'Activity History',0,'',0,'','1:N'),(80,23,0,'get_invoicestatushistory',5,'Invoice Status History',0,'',0,'','1:N'),(81,9,0,'get_users',1,'Users',0,'',0,'','1:N'),(82,9,4,'get_contacts',2,'Contacts',0,'',0,'','1:N'),(83,26,4,'get_contacts',1,'Contacts',0,'add,select',0,'','1:N'),(84,26,7,'get_leads',2,'Leads',0,'add,select',0,'','1:N'),(85,26,2,'get_opportunities',3,'Potentials',0,'add',121,'','1:N'),(86,26,9,'get_activities',4,'Activities',0,'add',238,'','1:N'),(87,6,26,'get_campaigns',14,'Campaigns',0,'select',0,'','1:N'),(88,26,6,'get_accounts',5,'Accounts',0,'add,select',0,'','1:N'),(100,13,36,'get_related_list',6,'Services',0,'SELECT',NULL,NULL,'N:N'),(101,7,36,'get_related_list',8,'Services',0,'SELECT',NULL,NULL,'N:N'),(102,6,36,'get_related_list',15,'Services',0,'SELECT',NULL,NULL,'N:N'),(103,4,36,'get_related_list',14,'Services',0,'SELECT',NULL,NULL,'N:N'),(104,2,36,'get_related_list',10,'Services',0,'SELECT',NULL,NULL,'N:N'),(105,19,36,'get_pricebook_services',3,'Services',0,'SELECT',NULL,NULL,'N:N'),(106,4,37,'get_dependents_list',15,'PBXManager',0,'',546,NULL,'1:N'),(107,7,37,'get_dependents_list',9,'PBXManager',0,'',546,NULL,'1:N'),(108,6,37,'get_merged_list',16,'PBXManager',0,'',546,NULL,'1:N'),(115,6,44,'get_merged_list',17,'Projects',0,'add',585,NULL,'1:N'),(116,4,44,'get_dependents_list',16,'Projects',0,'add',585,NULL,'1:N'),(117,13,44,'get_related_list',7,'Projects',0,'SELECT',NULL,NULL,'N:N'),(124,4,18,'get_vendors',17,'Vendors',0,'SELECT',NULL,NULL,'N:N'),(125,2,23,'get_dependents_list',11,'Invoice',0,'ADD',698,NULL,'1:N'),(126,8,4,'get_related_list',1,'Contacts',0,'1',NULL,NULL,'N:N'),(127,8,6,'get_related_list',2,'Accounts',0,'1',NULL,NULL,'N:N'),(128,8,2,'get_related_list',3,'Potentials',0,'1',NULL,NULL,'N:N'),(129,8,7,'get_related_list',4,'Leads',0,'1',NULL,NULL,'N:N'),(130,8,14,'get_related_list',5,'Products',0,'1',NULL,NULL,'N:N'),(131,8,36,'get_related_list',6,'Services',0,'1',NULL,NULL,'N:N'),(132,8,44,'get_related_list',7,'Project',0,'1',NULL,NULL,'N:N'),(133,8,20,'get_related_list',8,'Quotes',0,'1',NULL,NULL,'N:N'),(134,8,23,'get_related_list',9,'Invoice',0,'1',NULL,NULL,'N:N'),(135,8,22,'get_related_list',10,'SalesOrder',0,'1',NULL,NULL,'N:N'),(136,8,21,'get_related_list',11,'PurchaseOrder',0,'1',NULL,NULL,'N:N'),(137,8,13,'get_related_list',12,'HelpDesk',0,'1',NULL,NULL,'N:N'),(138,7,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(139,4,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(140,6,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(141,2,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(144,13,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(145,23,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(146,20,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(147,21,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(148,22,47,'get_comments',1,'ModComments',0,'',238,'NULL','1:N'),(153,14,21,'get_purchase_orders',15,'PurchaseOrder',0,'ADD',NULL,NULL,NULL),(154,36,13,'get_related_list',1,'HelpDesk',0,'ADD,SELECT',NULL,NULL,NULL),(155,36,20,'get_quotes',2,'Quotes',0,'ADD',NULL,NULL,NULL),(156,36,21,'get_purchase_orders',3,'Purchase Order',0,'ADD',NULL,NULL,NULL),(157,36,22,'get_salesorder',4,'Sales Order',0,'ADD',NULL,NULL,NULL),(158,36,23,'get_invoices',5,'Invoice',0,'ADD',NULL,NULL,NULL),(159,36,19,'get_service_pricebooks',6,'PriceBooks',0,'ADD',NULL,NULL,NULL),(160,36,7,'get_related_list',7,'Leads',0,'SELECT',NULL,NULL,NULL),(161,36,6,'get_related_list',8,'Accounts',0,'SELECT',NULL,NULL,NULL),(162,36,4,'get_related_list',9,'Contacts',0,'SELECT',NULL,NULL,NULL),(163,36,2,'get_related_list',10,'Potentials',0,'SELECT',NULL,NULL,NULL),(164,36,8,'get_attachments',11,'Documents',0,'ADD,SELECT',NULL,NULL,NULL),(189,43,8,'get_attachments',1,'Documents',0,'ADD,SELECT',NULL,NULL,NULL),(190,43,10,'get_emails',2,'Emails',0,'ADD,SELECT',NULL,NULL,NULL),(191,44,43,'get_dependents_list',1,'Project Tasks',0,'ADD',567,NULL,NULL),(192,44,42,'get_dependents_list',2,'Project Milestones',0,'ADD',556,NULL,NULL),(193,44,8,'get_attachments',3,'Documents',0,'ADD,SELECT',NULL,NULL,NULL),(194,44,13,'get_related_list',4,'HelpDesk',0,'ADD,SELECT',NULL,NULL,NULL),(195,44,10,'get_emails',5,'Emails',0,'ADD',NULL,NULL,NULL),(196,44,9,'get_related_list',6,'Activities',0,'ADD',NULL,NULL,NULL),(197,44,20,'get_related_list',7,'Quotes',0,'SELECT',NULL,NULL,NULL),(198,44,0,'get_gantt_chart',8,'Charts',0,'',NULL,NULL,NULL),(199,2,13,'get_related_list',5,'HelpDesk',0,'add,select',0,'','1:N'),(200,13,2,'get_related_list',6,'Potentials',0,'add,select',0,'','1:N');
/*!40000 ALTER TABLE `jo_relatedlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_relatedlists_rb`
--

DROP TABLE IF EXISTS `jo_relatedlists_rb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_relatedlists_rb` (
  `entityid` int(19) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `rel_table` varchar(200) DEFAULT NULL,
  `rel_column` varchar(200) DEFAULT NULL,
  `ref_column` varchar(200) DEFAULT NULL,
  `related_crm_ids` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_relatedlists_rb`
--

LOCK TABLES `jo_relatedlists_rb` WRITE;
/*!40000 ALTER TABLE `jo_relatedlists_rb` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_relatedlists_rb` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_relcriteria`
--

DROP TABLE IF EXISTS `jo_relcriteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_relcriteria` (
  `queryid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `comparator` varchar(20) DEFAULT NULL,
  `value` varchar(512) DEFAULT NULL,
  `groupid` int(11) DEFAULT '1',
  `column_condition` varchar(256) DEFAULT 'and',
  PRIMARY KEY (`queryid`,`columnindex`),
  KEY `relcriteria_queryid_idx` (`queryid`),
  CONSTRAINT `fk_1_jo_relcriteria` FOREIGN KEY (`queryid`) REFERENCES `jo_selectquery` (`queryid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_relcriteria`
--

LOCK TABLES `jo_relcriteria` WRITE;
/*!40000 ALTER TABLE `jo_relcriteria` DISABLE KEYS */;
INSERT INTO `jo_relcriteria` VALUES (1,0,'jo_contactdetails:accountid:Contacts_Account_Name:account_id:V','n','',1,'and'),(2,0,'jo_contactdetails:accountid:Contacts_Account_Name:account_id:V','e','',1,'and'),(3,0,'jo_potential:potentialname:Potentials_Potential_Name:potentialname:V','n','',1,'and'),(7,0,'jo_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V','e','Closed Won',1,'and'),(12,0,'jo_troubletickets:status:HelpDesk_Status:ticketstatus:V','n','Closed',1,'and'),(15,0,'jo_quotes:quotestage:Quotes_Quote_Stage:quotestage:V','n','Accepted',1,'and'),(15,1,'jo_quotes:quotestage:Quotes_Quote_Stage:quotestage:V','n','Rejected',1,'and'),(22,0,'jo_email_track:access_count:Emails_Access_Count:access_count:V','n','',1,'and'),(23,0,'jo_email_track:access_count:Emails_Access_Count:access_count:V','n','',1,'and'),(24,0,'jo_email_track:access_count:Emails_Access_Count:access_count:V','n','',1,'and'),(25,0,'jo_email_track:access_count:Emails_Access_Count:access_count:V','n','',1,'and');
/*!40000 ALTER TABLE `jo_relcriteria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_relcriteria_grouping`
--

DROP TABLE IF EXISTS `jo_relcriteria_grouping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_relcriteria_grouping` (
  `groupid` int(11) NOT NULL,
  `queryid` int(19) NOT NULL,
  `group_condition` varchar(256) DEFAULT NULL,
  `condition_expression` text,
  PRIMARY KEY (`groupid`,`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_relcriteria_grouping`
--

LOCK TABLES `jo_relcriteria_grouping` WRITE;
/*!40000 ALTER TABLE `jo_relcriteria_grouping` DISABLE KEYS */;
INSERT INTO `jo_relcriteria_grouping` VALUES (1,1,'','0'),(1,2,'','0'),(1,3,'','0'),(1,7,'','0'),(1,12,'','0'),(1,15,'','0 and 1'),(1,22,'','0'),(1,23,'','0'),(1,24,'','0'),(1,25,'','0');
/*!40000 ALTER TABLE `jo_relcriteria_grouping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reminder_interval`
--

DROP TABLE IF EXISTS `jo_reminder_interval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reminder_interval` (
  `reminder_intervalid` int(19) NOT NULL AUTO_INCREMENT,
  `reminder_interval` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL,
  `presence` int(1) NOT NULL,
  PRIMARY KEY (`reminder_intervalid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reminder_interval`
--

LOCK TABLES `jo_reminder_interval` WRITE;
/*!40000 ALTER TABLE `jo_reminder_interval` DISABLE KEYS */;
INSERT INTO `jo_reminder_interval` VALUES (2,'1 Minute',1,1),(3,'5 Minutes',2,1),(4,'15 Minutes',3,1),(5,'30 Minutes',4,1),(6,'45 Minutes',5,1),(7,'1 Hour',6,1),(8,'1 Day',7,1);
/*!40000 ALTER TABLE `jo_reminder_interval` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_report`
--

DROP TABLE IF EXISTS `jo_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_report` (
  `reportid` int(19) NOT NULL,
  `folderid` int(19) NOT NULL,
  `reportname` varchar(100) DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `reporttype` varchar(50) DEFAULT '',
  `queryid` int(19) NOT NULL DEFAULT '0',
  `state` varchar(50) DEFAULT 'SAVED',
  `customizable` int(1) DEFAULT '1',
  `category` int(11) DEFAULT '1',
  `owner` int(11) DEFAULT '1',
  `sharingtype` varchar(200) DEFAULT 'Private',
  PRIMARY KEY (`reportid`),
  KEY `report_queryid_idx` (`queryid`),
  KEY `report_folderid_idx` (`folderid`),
  CONSTRAINT `fk_2_jo_report` FOREIGN KEY (`queryid`) REFERENCES `jo_selectquery` (`queryid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_report`
--

LOCK TABLES `jo_report` WRITE;
/*!40000 ALTER TABLE `jo_report` DISABLE KEYS */;
INSERT INTO `jo_report` VALUES (1,1,'Contacts by Accounts','Contacts related to Accounts','tabular',1,'CUSTOM',1,1,1,'Public'),(2,1,'Contacts without Accounts','Contacts not related to Accounts','tabular',2,'CUSTOM',1,1,1,'Public'),(3,1,'Contacts by Potentials','Contacts related to Potentials','tabular',3,'CUSTOM',1,1,1,'Public'),(4,2,'Lead by Source','Lead by Source','summary',4,'CUSTOM',1,1,1,'Public'),(5,2,'Lead Status Report','Lead Status Report','summary',5,'CUSTOM',1,1,1,'Public'),(6,3,'Potential Pipeline','Potential Pipeline','summary',6,'CUSTOM',1,1,1,'Public'),(7,3,'Closed Potentials','Potential that have Won','tabular',7,'CUSTOM',1,1,1,'Public'),(8,4,'Last Month Activities','Last Month Activities','tabular',8,'CUSTOM',1,1,1,'Public'),(9,4,'This Month Activities','This Month Activities','tabular',9,'CUSTOM',1,1,1,'Public'),(10,5,'Tickets by Products','Tickets related to Products','tabular',10,'CUSTOM',1,1,1,'Public'),(11,5,'Tickets by Priority','Tickets by Priority','summary',11,'CUSTOM',1,1,1,'Public'),(12,5,'Open Tickets','Tickets that are Open','tabular',12,'CUSTOM',1,1,1,'Public'),(13,6,'Product Details','Product Detailed Report','tabular',13,'CUSTOM',1,1,1,'Public'),(14,6,'Products by Contacts','Products related to Contacts','tabular',14,'CUSTOM',1,1,1,'Public'),(15,7,'Open Quotes','Quotes that are Open','tabular',15,'CUSTOM',1,1,1,'Public'),(16,7,'Quotes Detailed Report','Quotes Detailed Report','tabular',16,'CUSTOM',1,1,1,'Public'),(17,8,'PurchaseOrder by Contacts','PurchaseOrder related to Contacts','tabular',17,'CUSTOM',1,1,1,'Public'),(18,8,'PurchaseOrder Detailed Report','PurchaseOrder Detailed Report','tabular',18,'CUSTOM',1,1,1,'Public'),(19,9,'Invoice Detailed Report','Invoice Detailed Report','tabular',19,'CUSTOM',1,1,1,'Public'),(20,10,'SalesOrder Detailed Report','SalesOrder Detailed Report','tabular',20,'CUSTOM',1,1,1,'Public'),(21,11,'Campaign Expectations and Actuals','Campaign Expectations and Actuals','tabular',21,'CUSTOM',1,1,1,'Public'),(22,12,'Contacts Email Report','Emails sent to Contacts','tabular',22,'CUSTOM',1,1,1,'Public'),(23,12,'Accounts Email Report','Emails sent to Organizations','tabular',23,'CUSTOM',1,1,1,'Public'),(24,12,'Leads Email Report','Emails sent to Leads','tabular',24,'CUSTOM',1,1,1,'Public'),(25,12,'Vendors Email Report','Emails sent to Vendors','tabular',25,'CUSTOM',1,1,1,'Public');
/*!40000 ALTER TABLE `jo_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_report_sharegroups`
--

DROP TABLE IF EXISTS `jo_report_sharegroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_report_sharegroups` (
  `reportid` int(25) NOT NULL,
  `groupid` int(25) NOT NULL,
  KEY `jo_report_sharegroups_ibfk_1` (`reportid`),
  KEY `jo_groups_groupid_ibfk_1` (`groupid`),
  CONSTRAINT `jo_groups_groupid_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE,
  CONSTRAINT `jo_report_reportid_ibfk_2` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_report_sharegroups`
--

LOCK TABLES `jo_report_sharegroups` WRITE;
/*!40000 ALTER TABLE `jo_report_sharegroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_report_sharegroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_report_sharerole`
--

DROP TABLE IF EXISTS `jo_report_sharerole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_report_sharerole` (
  `reportid` int(25) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  KEY `jo_report_sharerole_ibfk_1` (`reportid`),
  KEY `jo_role_roleid_ibfk_1` (`roleid`),
  CONSTRAINT `jo_report_reportid_ibfk_3` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
  CONSTRAINT `jo_role_roleid_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_report_sharerole`
--

LOCK TABLES `jo_report_sharerole` WRITE;
/*!40000 ALTER TABLE `jo_report_sharerole` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_report_sharerole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_report_sharers`
--

DROP TABLE IF EXISTS `jo_report_sharers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_report_sharers` (
  `reportid` int(25) NOT NULL,
  `rsid` varchar(255) NOT NULL,
  KEY `jo_report_sharers_ibfk_1` (`reportid`),
  KEY `jo_rolesd_rsid_ibfk_1` (`rsid`),
  CONSTRAINT `jo_report_reportid_ibfk_4` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
  CONSTRAINT `jo_rolesd_rsid_ibfk_1` FOREIGN KEY (`rsid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_report_sharers`
--

LOCK TABLES `jo_report_sharers` WRITE;
/*!40000 ALTER TABLE `jo_report_sharers` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_report_sharers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_report_shareusers`
--

DROP TABLE IF EXISTS `jo_report_shareusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_report_shareusers` (
  `reportid` int(25) NOT NULL,
  `userid` int(25) NOT NULL,
  KEY `jo_report_shareusers_ibfk_1` (`reportid`),
  KEY `jo_users_userid_ibfk_1` (`userid`),
  CONSTRAINT `jo_reports_reportid_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
  CONSTRAINT `jo_users_userid_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_report_shareusers`
--

LOCK TABLES `jo_report_shareusers` WRITE;
/*!40000 ALTER TABLE `jo_report_shareusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_report_shareusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportdatefilter`
--

DROP TABLE IF EXISTS `jo_reportdatefilter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportdatefilter` (
  `datefilterid` int(19) NOT NULL,
  `datecolumnname` varchar(250) DEFAULT '',
  `datefilter` varchar(250) DEFAULT '',
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  PRIMARY KEY (`datefilterid`),
  KEY `reportdatefilter_datefilterid_idx` (`datefilterid`),
  CONSTRAINT `fk_1_jo_reportdatefilter` FOREIGN KEY (`datefilterid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportdatefilter`
--

LOCK TABLES `jo_reportdatefilter` WRITE;
/*!40000 ALTER TABLE `jo_reportdatefilter` DISABLE KEYS */;
INSERT INTO `jo_reportdatefilter` VALUES (8,'jo_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time','lastmonth','2005-05-01','2005-05-31'),(9,'jo_crmentity:modifiedtime:modifiedtime:Calendar_Modified_Time','thismonth','2005-06-01','2005-06-30');
/*!40000 ALTER TABLE `jo_reportdatefilter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportfilters`
--

DROP TABLE IF EXISTS `jo_reportfilters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportfilters` (
  `filterid` int(19) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportfilters`
--

LOCK TABLES `jo_reportfilters` WRITE;
/*!40000 ALTER TABLE `jo_reportfilters` DISABLE KEYS */;
INSERT INTO `jo_reportfilters` VALUES (1,'Private'),(2,'Public'),(3,'Shared');
/*!40000 ALTER TABLE `jo_reportfilters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportfolder`
--

DROP TABLE IF EXISTS `jo_reportfolder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportfolder` (
  `folderid` int(19) NOT NULL AUTO_INCREMENT,
  `foldername` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `state` varchar(50) DEFAULT 'SAVED',
  PRIMARY KEY (`folderid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportfolder`
--

LOCK TABLES `jo_reportfolder` WRITE;
/*!40000 ALTER TABLE `jo_reportfolder` DISABLE KEYS */;
INSERT INTO `jo_reportfolder` VALUES (1,'Organization and Contact Reports','Account and Contact Reports','SAVED'),(2,'Lead Reports','Lead Reports','SAVED'),(3,'Opportunity Reports','Potential Reports','SAVED'),(4,'Activity Reports','Activity Reports','SAVED'),(5,'Tickets Reports','HelpDesk Reports','SAVED'),(6,'Product Reports','Product Reports','SAVED'),(7,'Quote Reports','Quote Reports','SAVED'),(8,'Purchase Order Reports','PurchaseOrder Reports','SAVED'),(9,'Invoice Reports','Invoice Reports','SAVED'),(10,'Sales Order Reports','SalesOrder Reports','SAVED'),(11,'Campaign Reports','Campaign Reports','SAVED'),(12,'Email Reports','Email Reports','SAVED');
/*!40000 ALTER TABLE `jo_reportfolder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportgroupbycolumn`
--

DROP TABLE IF EXISTS `jo_reportgroupbycolumn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportgroupbycolumn` (
  `reportid` int(19) DEFAULT NULL,
  `sortid` int(19) DEFAULT NULL,
  `sortcolname` varchar(250) DEFAULT NULL,
  `dategroupbycriteria` varchar(250) DEFAULT NULL,
  KEY `fk_1_jo_reportgroupbycolumn` (`reportid`),
  CONSTRAINT `fk_1_jo_reportgroupbycolumn` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportgroupbycolumn`
--

LOCK TABLES `jo_reportgroupbycolumn` WRITE;
/*!40000 ALTER TABLE `jo_reportgroupbycolumn` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_reportgroupbycolumn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportmodules`
--

DROP TABLE IF EXISTS `jo_reportmodules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportmodules` (
  `reportmodulesid` int(19) NOT NULL,
  `primarymodule` varchar(100) DEFAULT NULL,
  `secondarymodules` varchar(250) DEFAULT '',
  PRIMARY KEY (`reportmodulesid`),
  CONSTRAINT `fk_1_jo_reportmodules` FOREIGN KEY (`reportmodulesid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportmodules`
--

LOCK TABLES `jo_reportmodules` WRITE;
/*!40000 ALTER TABLE `jo_reportmodules` DISABLE KEYS */;
INSERT INTO `jo_reportmodules` VALUES (1,'Contacts','Accounts'),(2,'Contacts','Accounts'),(3,'Contacts','Potentials'),(4,'Leads',''),(5,'Leads',''),(6,'Potentials',''),(7,'Potentials',''),(8,'Calendar',''),(9,'Calendar',''),(10,'HelpDesk','Products'),(11,'HelpDesk',''),(12,'HelpDesk',''),(13,'Products',''),(14,'Products','Contacts'),(15,'Quotes',''),(16,'Quotes',''),(17,'PurchaseOrder','Contacts'),(18,'PurchaseOrder',''),(19,'Invoice',''),(20,'SalesOrder',''),(21,'Campaigns',''),(22,'Contacts','Emails'),(23,'Accounts','Emails'),(24,'Leads','Emails'),(25,'Vendors','Emails');
/*!40000 ALTER TABLE `jo_reportmodules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportsharing`
--

DROP TABLE IF EXISTS `jo_reportsharing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportsharing` (
  `reportid` int(19) NOT NULL,
  `shareid` int(19) NOT NULL,
  `setype` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportsharing`
--

LOCK TABLES `jo_reportsharing` WRITE;
/*!40000 ALTER TABLE `jo_reportsharing` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_reportsharing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportsortcol`
--

DROP TABLE IF EXISTS `jo_reportsortcol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportsortcol` (
  `sortcolid` int(19) NOT NULL,
  `reportid` int(19) NOT NULL,
  `columnname` varchar(250) DEFAULT '',
  `sortorder` varchar(250) DEFAULT 'Asc',
  PRIMARY KEY (`sortcolid`,`reportid`),
  KEY `fk_1_jo_reportsortcol` (`reportid`),
  CONSTRAINT `fk_1_jo_reportsortcol` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportsortcol`
--

LOCK TABLES `jo_reportsortcol` WRITE;
/*!40000 ALTER TABLE `jo_reportsortcol` DISABLE KEYS */;
INSERT INTO `jo_reportsortcol` VALUES (1,4,'jo_leaddetails:leadsource:Leads_Lead_Source:leadsource:V','Ascending'),(1,5,'jo_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V','Ascending'),(1,6,'jo_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V','Ascending'),(1,11,'jo_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V','Ascending');
/*!40000 ALTER TABLE `jo_reportsortcol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reportsummary`
--

DROP TABLE IF EXISTS `jo_reportsummary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reportsummary` (
  `reportsummaryid` int(19) NOT NULL,
  `summarytype` int(19) NOT NULL,
  `columnname` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`reportsummaryid`,`summarytype`,`columnname`),
  KEY `reportsummary_reportsummaryid_idx` (`reportsummaryid`),
  CONSTRAINT `fk_1_jo_reportsummary` FOREIGN KEY (`reportsummaryid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reportsummary`
--

LOCK TABLES `jo_reportsummary` WRITE;
/*!40000 ALTER TABLE `jo_reportsummary` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_reportsummary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_reporttype`
--

DROP TABLE IF EXISTS `jo_reporttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_reporttype` (
  `reportid` int(10) NOT NULL,
  `data` text,
  PRIMARY KEY (`reportid`),
  CONSTRAINT `fk_1_jo_reporttype` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_reporttype`
--

LOCK TABLES `jo_reporttype` WRITE;
/*!40000 ALTER TABLE `jo_reporttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_reporttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_role`
--

DROP TABLE IF EXISTS `jo_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_role` (
  `roleid` varchar(255) NOT NULL,
  `rolename` varchar(200) DEFAULT NULL,
  `parentrole` varchar(255) DEFAULT NULL,
  `depth` int(19) DEFAULT NULL,
  `allowassignedrecordsto` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_role`
--

LOCK TABLES `jo_role` WRITE;
/*!40000 ALTER TABLE `jo_role` DISABLE KEYS */;
INSERT INTO `jo_role` VALUES ('H1','Organization','H1',0,1),('H2','CEO','H1::H2',1,1),('H3','Vice President','H1::H2::H3',2,1),('H4','Sales Manager','H1::H2::H3::H4',3,1),('H5','Sales Person','H1::H2::H3::H4::H5',4,1),('H6','Masquerade User','H1::H2::H3::H4::H5::H6',5,1);
/*!40000 ALTER TABLE `jo_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_role2picklist`
--

DROP TABLE IF EXISTS `jo_role2picklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_role2picklist` (
  `roleid` varchar(255) NOT NULL,
  `picklistvalueid` int(11) NOT NULL,
  `picklistid` int(11) NOT NULL,
  `sortid` int(11) DEFAULT NULL,
  PRIMARY KEY (`roleid`,`picklistvalueid`,`picklistid`),
  KEY `role2picklist_roleid_picklistid_idx` (`roleid`,`picklistid`,`picklistvalueid`),
  KEY `fk_2_jo_role2picklist` (`picklistid`),
  CONSTRAINT `fk_1_jo_role2picklist` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE,
  CONSTRAINT `fk_2_jo_role2picklist` FOREIGN KEY (`picklistid`) REFERENCES `jo_picklist` (`picklistid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_role2picklist`
--

LOCK TABLES `jo_role2picklist` WRITE;
/*!40000 ALTER TABLE `jo_role2picklist` DISABLE KEYS */;
INSERT INTO `jo_role2picklist` VALUES ('H1',1,1,0),('H1',2,1,1),('H1',3,1,2),('H1',4,1,3),('H1',5,1,4),('H1',6,1,5),('H1',7,1,6),('H1',8,1,7),('H1',9,1,8),('H1',10,1,9),('H1',11,1,10),('H1',12,2,0),('H1',13,2,1),('H1',14,3,0),('H1',15,3,1),('H1',16,3,2),('H1',17,3,3),('H1',18,3,4),('H1',19,3,5),('H1',20,4,0),('H1',21,4,1),('H1',22,4,2),('H1',23,4,3),('H1',24,4,4),('H1',25,4,5),('H1',26,4,6),('H1',27,4,7),('H1',28,4,8),('H1',29,4,9),('H1',30,4,10),('H1',31,4,11),('H1',32,4,12),('H1',33,5,0),('H1',34,5,1),('H1',35,5,2),('H1',36,5,3),('H1',37,5,4),('H1',38,6,0),('H1',39,6,1),('H1',40,6,2),('H1',41,7,0),('H1',42,7,1),('H1',43,7,2),('H1',44,7,3),('H1',45,7,4),('H1',46,8,0),('H1',47,8,1),('H1',48,8,2),('H1',49,8,3),('H1',50,8,4),('H1',51,8,5),('H1',52,8,6),('H1',53,8,7),('H1',54,8,8),('H1',55,9,0),('H1',56,9,1),('H1',57,9,2),('H1',58,9,3),('H1',59,9,4),('H1',60,9,5),('H1',61,9,6),('H1',62,9,7),('H1',63,9,8),('H1',64,9,9),('H1',65,9,10),('H1',66,9,11),('H1',67,9,12),('H1',68,9,13),('H1',69,9,14),('H1',70,9,15),('H1',71,9,16),('H1',72,9,17),('H1',73,9,18),('H1',74,9,19),('H1',75,9,20),('H1',76,9,21),('H1',77,9,22),('H1',78,9,23),('H1',79,9,24),('H1',80,9,25),('H1',81,9,26),('H1',82,9,27),('H1',83,9,28),('H1',84,9,29),('H1',85,9,30),('H1',86,9,31),('H1',87,10,0),('H1',88,10,1),('H1',89,10,2),('H1',90,10,3),('H1',91,10,4),('H1',92,10,5),('H1',93,11,0),('H1',94,11,1),('H1',95,11,2),('H1',96,11,3),('H1',97,11,4),('H1',98,11,5),('H1',99,11,6),('H1',100,11,7),('H1',101,11,8),('H1',102,11,9),('H1',103,11,10),('H1',104,11,11),('H1',105,11,12),('H1',106,12,0),('H1',107,12,1),('H1',108,12,2),('H1',109,12,3),('H1',110,12,4),('H1',111,12,5),('H1',112,12,6),('H1',113,12,7),('H1',114,12,8),('H1',115,12,9),('H1',116,12,10),('H1',117,12,11),('H1',118,13,0),('H1',119,13,1),('H1',120,13,2),('H1',121,13,3),('H1',122,14,0),('H1',123,14,1),('H1',124,14,2),('H1',125,15,0),('H1',126,15,1),('H1',127,15,2),('H1',128,15,3),('H1',129,15,4),('H1',130,16,0),('H1',131,16,1),('H1',132,16,2),('H1',133,16,3),('H1',134,17,0),('H1',135,17,1),('H1',136,17,2),('H1',137,17,3),('H1',138,17,4),('H1',139,18,0),('H1',140,18,1),('H1',141,18,2),('H1',142,18,3),('H1',143,18,4),('H1',144,18,5),('H1',145,19,0),('H1',146,19,1),('H1',147,19,2),('H1',148,19,3),('H1',149,19,4),('H1',150,19,5),('H1',151,19,6),('H1',152,19,7),('H1',153,19,8),('H1',154,19,9),('H1',155,20,0),('H1',156,20,1),('H1',157,20,2),('H1',158,20,3),('H1',159,20,4),('H1',160,20,5),('H1',161,21,0),('H1',162,21,1),('H1',163,21,2),('H1',164,21,3),('H1',165,22,0),('H1',166,22,1),('H1',167,22,2),('H1',168,23,0),('H1',169,23,1),('H1',170,23,2),('H1',171,23,3),('H1',172,23,4),('H1',173,23,5),('H1',174,24,0),('H1',175,24,1),('H1',176,24,2),('H1',177,25,0),('H1',178,25,1),('H1',179,25,2),('H1',180,25,3),('H1',181,26,0),('H1',182,26,1),('H1',183,26,2),('H1',184,26,3),('H1',185,27,0),('H1',186,27,1),('H1',187,27,2),('H1',188,27,3),('H1',189,28,0),('H1',190,28,1),('H1',191,28,2),('H1',192,28,3),('H1',193,28,4),('H1',194,28,5),('H1',195,28,6),('H1',196,28,7),('H1',197,28,8),('H1',198,28,9),('H1',199,28,10),('H1',200,28,11),('H1',201,28,12),('H1',202,28,13),('H1',203,28,14),('H1',204,28,15),('H1',205,29,1),('H1',206,29,2),('H1',207,29,3),('H1',208,30,1),('H1',209,30,2),('H1',210,30,3),('H1',211,30,4),('H1',212,30,5),('H1',213,30,6),('H1',214,31,1),('H1',215,31,2),('H1',216,31,3),('H1',217,31,4),('H1',218,32,1),('H1',219,32,2),('H1',220,32,3),('H1',221,32,4),('H1',222,33,1),('H1',223,33,2),('H1',224,33,3),('H1',225,33,4),('H1',226,34,1),('H1',227,34,2),('H1',228,34,3),('H1',229,34,4),('H1',230,34,5),('H1',231,34,6),('H1',232,34,7),('H1',233,34,8),('H1',234,34,9),('H1',235,34,10),('H1',236,34,11),('H1',237,35,1),('H1',238,35,2),('H1',239,35,3),('H1',240,35,4),('H1',241,35,5),('H1',242,35,6),('H1',243,35,7),('H1',244,35,8),('H1',245,35,9),('H1',246,36,1),('H1',247,36,2),('H1',248,36,3),('H1',249,36,4),('H1',250,37,1),('H1',251,37,2),('H1',252,37,3),('H1',253,37,4),('H1',254,38,1),('H1',255,38,2),('H1',256,38,3),('H1',257,38,4),('H1',258,38,5),('H1',259,38,6),('H1',260,38,7),('H1',261,38,8),('H1',262,38,9),('H1',263,38,10),('H1',264,38,11),('H1',265,31,5),('H1',266,31,6),('H1',267,31,7),('H1',268,31,8),('H1',269,32,5),('H1',270,32,6),('H1',271,32,7),('H1',272,32,8),('H1',273,33,5),('H1',274,33,6),('H1',275,33,7),('H1',276,33,8),('H1',277,34,12),('H1',278,34,13),('H1',279,34,14),('H1',280,34,15),('H1',281,34,16),('H1',282,34,17),('H1',283,34,18),('H1',284,34,19),('H1',285,34,20),('H1',286,34,21),('H1',287,34,22),('H1',288,35,10),('H1',289,35,11),('H1',290,35,12),('H1',291,35,13),('H1',292,35,14),('H1',293,35,15),('H1',294,35,16),('H1',295,35,17),('H1',296,35,18),('H1',297,36,5),('H1',298,36,6),('H1',299,36,7),('H1',300,36,8),('H1',301,37,5),('H1',302,37,6),('H1',303,37,7),('H1',304,37,8),('H1',305,38,12),('H1',306,38,13),('H1',307,38,14),('H1',308,38,15),('H1',309,38,16),('H1',310,38,17),('H1',311,38,18),('H1',312,38,19),('H1',313,38,20),('H1',314,38,21),('H1',315,38,22),('H1',316,10,6),('H1',317,39,1),('H1',318,39,2),('H1',319,39,3),('H1',320,39,4),('H1',321,39,5),('H1',322,39,6),('H1',323,2,2),('H1',324,40,1),('H1',325,40,2),('H1',326,40,3),('H1',327,41,1),('H1',328,41,2),('H1',331,29,4),('H1',332,29,5),('H1',333,29,6),('H1',334,30,7),('H1',335,30,8),('H1',336,30,9),('H1',337,30,10),('H1',338,30,11),('H1',339,30,12),('H1',340,31,9),('H1',341,31,10),('H1',342,31,11),('H1',343,31,12),('H1',344,32,9),('H1',345,32,10),('H1',346,32,11),('H1',347,32,12),('H1',348,33,9),('H1',349,33,10),('H1',350,33,11),('H1',351,33,12),('H1',352,34,23),('H1',353,34,24),('H1',354,34,25),('H1',355,34,26),('H1',356,34,27),('H1',357,34,28),('H1',358,34,29),('H1',359,34,30),('H1',360,34,31),('H1',361,34,32),('H1',362,34,33),('H1',363,35,19),('H1',364,35,20),('H1',365,35,21),('H1',366,35,22),('H1',367,35,23),('H1',368,35,24),('H1',369,35,25),('H1',370,35,26),('H1',371,35,27),('H1',372,36,9),('H1',373,36,10),('H1',374,36,11),('H1',375,36,12),('H1',376,37,9),('H1',377,37,10),('H1',378,37,11),('H1',379,37,12),('H1',380,38,23),('H1',381,38,24),('H1',382,38,25),('H1',383,38,26),('H1',384,38,27),('H1',385,38,28),('H1',386,38,29),('H1',387,38,30),('H1',388,38,31),('H1',389,38,32),('H1',390,38,33),('H1',391,31,13),('H1',392,31,14),('H1',393,31,15),('H1',394,31,16),('H1',395,32,13),('H1',396,32,14),('H1',397,32,15),('H1',398,32,16),('H1',399,33,13),('H1',400,33,14),('H1',401,33,15),('H1',402,33,16),('H1',403,34,34),('H1',404,34,35),('H1',405,34,36),('H1',406,34,37),('H1',407,34,38),('H1',408,34,39),('H1',409,34,40),('H1',410,34,41),('H1',411,34,42),('H1',412,34,43),('H1',413,34,44),('H1',414,35,28),('H1',415,35,29),('H1',416,35,30),('H1',417,35,31),('H1',418,35,32),('H1',419,35,33),('H1',420,35,34),('H1',421,35,35),('H1',422,35,36),('H1',423,36,13),('H1',424,36,14),('H1',425,36,15),('H1',426,36,16),('H1',427,37,13),('H1',428,37,14),('H1',429,37,15),('H1',430,37,16),('H1',431,38,34),('H1',432,38,35),('H1',433,38,36),('H1',434,38,37),('H1',435,38,38),('H1',436,38,39),('H1',437,38,40),('H1',438,38,41),('H1',439,38,42),('H1',440,38,43),('H1',441,38,44),('H1',442,31,17),('H1',443,31,18),('H1',444,31,19),('H1',445,31,20),('H1',446,32,17),('H1',447,32,18),('H1',448,32,19),('H1',449,32,20),('H1',450,33,17),('H1',451,33,18),('H1',452,33,19),('H1',453,33,20),('H1',454,34,45),('H1',455,34,46),('H1',456,34,47),('H1',457,34,48),('H1',458,34,49),('H1',459,34,50),('H1',460,34,51),('H1',461,34,52),('H1',462,34,53),('H1',463,34,54),('H1',464,34,55),('H1',465,35,37),('H1',466,35,38),('H1',467,35,39),('H1',468,35,40),('H1',469,35,41),('H1',470,35,42),('H1',471,35,43),('H1',472,35,44),('H1',473,35,45),('H1',474,36,17),('H1',475,36,18),('H1',476,36,19),('H1',477,36,20),('H1',478,37,17),('H1',479,37,18),('H1',480,37,19),('H1',481,37,20),('H1',482,38,45),('H1',483,38,46),('H1',484,38,47),('H1',485,38,48),('H1',486,38,49),('H1',487,38,50),('H1',488,38,51),('H1',489,38,52),('H1',490,38,53),('H1',491,38,54),('H1',492,38,55),('H1',493,31,21),('H1',494,31,22),('H1',495,31,23),('H1',496,31,24),('H1',497,32,21),('H1',498,32,22),('H1',499,32,23),('H1',500,32,24),('H1',501,33,21),('H1',502,33,22),('H1',503,33,23),('H1',504,33,24),('H1',505,34,56),('H1',506,34,57),('H1',507,34,58),('H1',508,34,59),('H1',509,34,60),('H1',510,34,61),('H1',511,34,62),('H1',512,34,63),('H1',513,34,64),('H1',514,34,65),('H1',515,34,66),('H1',516,35,46),('H1',517,35,47),('H1',518,35,48),('H1',519,35,49),('H1',520,35,50),('H1',521,35,51),('H1',522,35,52),('H1',523,35,53),('H1',524,35,54),('H1',525,36,21),('H1',526,36,22),('H1',527,36,23),('H1',528,36,24),('H1',529,37,21),('H1',530,37,22),('H1',531,37,23),('H1',532,37,24),('H1',533,38,56),('H1',534,38,57),('H1',535,38,58),('H1',536,38,59),('H1',537,38,60),('H1',538,38,61),('H1',539,38,62),('H1',540,38,63),('H1',541,38,64),('H1',542,38,65),('H1',543,38,66),('H1',544,31,25),('H1',545,31,26),('H1',546,31,27),('H1',547,31,28),('H1',548,32,25),('H1',549,32,26),('H1',550,32,27),('H1',551,32,28),('H1',552,33,25),('H1',553,33,26),('H1',554,33,27),('H1',555,33,28),('H1',556,34,67),('H1',557,34,68),('H1',558,34,69),('H1',559,34,70),('H1',560,34,71),('H1',561,34,72),('H1',562,34,73),('H1',563,34,74),('H1',564,34,75),('H1',565,34,76),('H1',566,34,77),('H1',567,35,55),('H1',568,35,56),('H1',569,35,57),('H1',570,35,58),('H1',571,35,59),('H1',572,35,60),('H1',573,35,61),('H1',574,35,62),('H1',575,35,63),('H1',576,36,25),('H1',577,36,26),('H1',578,36,27),('H1',579,36,28),('H1',580,37,25),('H1',581,37,26),('H1',582,37,27),('H1',583,37,28),('H1',584,38,67),('H1',585,38,68),('H1',586,38,69),('H1',587,38,70),('H1',588,38,71),('H1',589,38,72),('H1',590,38,73),('H1',591,38,74),('H1',592,38,75),('H1',593,38,76),('H1',594,38,77),('H2',1,1,0),('H2',2,1,1),('H2',3,1,2),('H2',4,1,3),('H2',5,1,4),('H2',6,1,5),('H2',7,1,6),('H2',8,1,7),('H2',9,1,8),('H2',10,1,9),('H2',11,1,10),('H2',12,2,0),('H2',13,2,1),('H2',14,3,0),('H2',15,3,1),('H2',16,3,2),('H2',17,3,3),('H2',18,3,4),('H2',19,3,5),('H2',20,4,0),('H2',21,4,1),('H2',22,4,2),('H2',23,4,3),('H2',24,4,4),('H2',25,4,5),('H2',26,4,6),('H2',27,4,7),('H2',28,4,8),('H2',29,4,9),('H2',30,4,10),('H2',31,4,11),('H2',32,4,12),('H2',33,5,0),('H2',34,5,1),('H2',35,5,2),('H2',36,5,3),('H2',37,5,4),('H2',38,6,0),('H2',39,6,1),('H2',40,6,2),('H2',41,7,0),('H2',42,7,1),('H2',43,7,2),('H2',44,7,3),('H2',45,7,4),('H2',46,8,0),('H2',47,8,1),('H2',48,8,2),('H2',49,8,3),('H2',50,8,4),('H2',51,8,5),('H2',52,8,6),('H2',53,8,7),('H2',54,8,8),('H2',55,9,0),('H2',56,9,1),('H2',57,9,2),('H2',58,9,3),('H2',59,9,4),('H2',60,9,5),('H2',61,9,6),('H2',62,9,7),('H2',63,9,8),('H2',64,9,9),('H2',65,9,10),('H2',66,9,11),('H2',67,9,12),('H2',68,9,13),('H2',69,9,14),('H2',70,9,15),('H2',71,9,16),('H2',72,9,17),('H2',73,9,18),('H2',74,9,19),('H2',75,9,20),('H2',76,9,21),('H2',77,9,22),('H2',78,9,23),('H2',79,9,24),('H2',80,9,25),('H2',81,9,26),('H2',82,9,27),('H2',83,9,28),('H2',84,9,29),('H2',85,9,30),('H2',86,9,31),('H2',87,10,0),('H2',88,10,1),('H2',89,10,2),('H2',90,10,3),('H2',91,10,4),('H2',92,10,5),('H2',93,11,0),('H2',94,11,1),('H2',95,11,2),('H2',96,11,3),('H2',97,11,4),('H2',98,11,5),('H2',99,11,6),('H2',100,11,7),('H2',101,11,8),('H2',102,11,9),('H2',103,11,10),('H2',104,11,11),('H2',105,11,12),('H2',106,12,0),('H2',107,12,1),('H2',108,12,2),('H2',109,12,3),('H2',110,12,4),('H2',111,12,5),('H2',112,12,6),('H2',113,12,7),('H2',114,12,8),('H2',115,12,9),('H2',116,12,10),('H2',117,12,11),('H2',118,13,0),('H2',119,13,1),('H2',120,13,2),('H2',121,13,3),('H2',122,14,0),('H2',123,14,1),('H2',124,14,2),('H2',125,15,0),('H2',126,15,1),('H2',127,15,2),('H2',128,15,3),('H2',129,15,4),('H2',130,16,0),('H2',131,16,1),('H2',132,16,2),('H2',133,16,3),('H2',134,17,0),('H2',135,17,1),('H2',136,17,2),('H2',137,17,3),('H2',138,17,4),('H2',139,18,0),('H2',140,18,1),('H2',141,18,2),('H2',142,18,3),('H2',143,18,4),('H2',144,18,5),('H2',145,19,0),('H2',146,19,1),('H2',147,19,2),('H2',148,19,3),('H2',149,19,4),('H2',150,19,5),('H2',151,19,6),('H2',152,19,7),('H2',153,19,8),('H2',154,19,9),('H2',155,20,0),('H2',156,20,1),('H2',157,20,2),('H2',158,20,3),('H2',159,20,4),('H2',160,20,5),('H2',161,21,0),('H2',162,21,1),('H2',163,21,2),('H2',164,21,3),('H2',165,22,0),('H2',166,22,1),('H2',167,22,2),('H2',168,23,0),('H2',169,23,1),('H2',170,23,2),('H2',171,23,3),('H2',172,23,4),('H2',173,23,5),('H2',174,24,0),('H2',175,24,1),('H2',176,24,2),('H2',177,25,0),('H2',178,25,1),('H2',179,25,2),('H2',180,25,3),('H2',181,26,0),('H2',182,26,1),('H2',183,26,2),('H2',184,26,3),('H2',185,27,0),('H2',186,27,1),('H2',187,27,2),('H2',188,27,3),('H2',189,28,0),('H2',190,28,1),('H2',191,28,2),('H2',192,28,3),('H2',193,28,4),('H2',194,28,5),('H2',195,28,6),('H2',196,28,7),('H2',197,28,8),('H2',198,28,9),('H2',199,28,10),('H2',200,28,11),('H2',201,28,12),('H2',202,28,13),('H2',203,28,14),('H2',204,28,15),('H2',205,29,1),('H2',206,29,2),('H2',207,29,3),('H2',208,30,1),('H2',209,30,2),('H2',210,30,3),('H2',211,30,4),('H2',212,30,5),('H2',213,30,6),('H2',214,31,1),('H2',215,31,2),('H2',216,31,3),('H2',217,31,4),('H2',218,32,1),('H2',219,32,2),('H2',220,32,3),('H2',221,32,4),('H2',222,33,1),('H2',223,33,2),('H2',224,33,3),('H2',225,33,4),('H2',226,34,1),('H2',227,34,2),('H2',228,34,3),('H2',229,34,4),('H2',230,34,5),('H2',231,34,6),('H2',232,34,7),('H2',233,34,8),('H2',234,34,9),('H2',235,34,10),('H2',236,34,11),('H2',237,35,1),('H2',238,35,2),('H2',239,35,3),('H2',240,35,4),('H2',241,35,5),('H2',242,35,6),('H2',243,35,7),('H2',244,35,8),('H2',245,35,9),('H2',246,36,1),('H2',247,36,2),('H2',248,36,3),('H2',249,36,4),('H2',250,37,1),('H2',251,37,2),('H2',252,37,3),('H2',253,37,4),('H2',254,38,1),('H2',255,38,2),('H2',256,38,3),('H2',257,38,4),('H2',258,38,5),('H2',259,38,6),('H2',260,38,7),('H2',261,38,8),('H2',262,38,9),('H2',263,38,10),('H2',264,38,11),('H2',265,31,5),('H2',266,31,6),('H2',267,31,7),('H2',268,31,8),('H2',269,32,5),('H2',270,32,6),('H2',271,32,7),('H2',272,32,8),('H2',273,33,5),('H2',274,33,6),('H2',275,33,7),('H2',276,33,8),('H2',277,34,12),('H2',278,34,13),('H2',279,34,14),('H2',280,34,15),('H2',281,34,16),('H2',282,34,17),('H2',283,34,18),('H2',284,34,19),('H2',285,34,20),('H2',286,34,21),('H2',287,34,22),('H2',288,35,10),('H2',289,35,11),('H2',290,35,12),('H2',291,35,13),('H2',292,35,14),('H2',293,35,15),('H2',294,35,16),('H2',295,35,17),('H2',296,35,18),('H2',297,36,5),('H2',298,36,6),('H2',299,36,7),('H2',300,36,8),('H2',301,37,5),('H2',302,37,6),('H2',303,37,7),('H2',304,37,8),('H2',305,38,12),('H2',306,38,13),('H2',307,38,14),('H2',308,38,15),('H2',309,38,16),('H2',310,38,17),('H2',311,38,18),('H2',312,38,19),('H2',313,38,20),('H2',314,38,21),('H2',315,38,22),('H2',316,10,6),('H2',317,39,1),('H2',318,39,2),('H2',319,39,3),('H2',320,39,4),('H2',321,39,5),('H2',322,39,6),('H2',323,2,2),('H2',324,40,1),('H2',325,40,2),('H2',326,40,3),('H2',327,41,1),('H2',328,41,2),('H2',331,29,4),('H2',332,29,5),('H2',333,29,6),('H2',334,30,7),('H2',335,30,8),('H2',336,30,9),('H2',337,30,10),('H2',338,30,11),('H2',339,30,12),('H2',340,31,9),('H2',341,31,10),('H2',342,31,11),('H2',343,31,12),('H2',344,32,9),('H2',345,32,10),('H2',346,32,11),('H2',347,32,12),('H2',348,33,9),('H2',349,33,10),('H2',350,33,11),('H2',351,33,12),('H2',352,34,23),('H2',353,34,24),('H2',354,34,25),('H2',355,34,26),('H2',356,34,27),('H2',357,34,28),('H2',358,34,29),('H2',359,34,30),('H2',360,34,31),('H2',361,34,32),('H2',362,34,33),('H2',363,35,19),('H2',364,35,20),('H2',365,35,21),('H2',366,35,22),('H2',367,35,23),('H2',368,35,24),('H2',369,35,25),('H2',370,35,26),('H2',371,35,27),('H2',372,36,9),('H2',373,36,10),('H2',374,36,11),('H2',375,36,12),('H2',376,37,9),('H2',377,37,10),('H2',378,37,11),('H2',379,37,12),('H2',380,38,23),('H2',381,38,24),('H2',382,38,25),('H2',383,38,26),('H2',384,38,27),('H2',385,38,28),('H2',386,38,29),('H2',387,38,30),('H2',388,38,31),('H2',389,38,32),('H2',390,38,33),('H2',391,31,13),('H2',392,31,14),('H2',393,31,15),('H2',394,31,16),('H2',395,32,13),('H2',396,32,14),('H2',397,32,15),('H2',398,32,16),('H2',399,33,13),('H2',400,33,14),('H2',401,33,15),('H2',402,33,16),('H2',403,34,34),('H2',404,34,35),('H2',405,34,36),('H2',406,34,37),('H2',407,34,38),('H2',408,34,39),('H2',409,34,40),('H2',410,34,41),('H2',411,34,42),('H2',412,34,43),('H2',413,34,44),('H2',414,35,28),('H2',415,35,29),('H2',416,35,30),('H2',417,35,31),('H2',418,35,32),('H2',419,35,33),('H2',420,35,34),('H2',421,35,35),('H2',422,35,36),('H2',423,36,13),('H2',424,36,14),('H2',425,36,15),('H2',426,36,16),('H2',427,37,13),('H2',428,37,14),('H2',429,37,15),('H2',430,37,16),('H2',431,38,34),('H2',432,38,35),('H2',433,38,36),('H2',434,38,37),('H2',435,38,38),('H2',436,38,39),('H2',437,38,40),('H2',438,38,41),('H2',439,38,42),('H2',440,38,43),('H2',441,38,44),('H2',442,31,17),('H2',443,31,18),('H2',444,31,19),('H2',445,31,20),('H2',446,32,17),('H2',447,32,18),('H2',448,32,19),('H2',449,32,20),('H2',450,33,17),('H2',451,33,18),('H2',452,33,19),('H2',453,33,20),('H2',454,34,45),('H2',455,34,46),('H2',456,34,47),('H2',457,34,48),('H2',458,34,49),('H2',459,34,50),('H2',460,34,51),('H2',461,34,52),('H2',462,34,53),('H2',463,34,54),('H2',464,34,55),('H2',465,35,37),('H2',466,35,38),('H2',467,35,39),('H2',468,35,40),('H2',469,35,41),('H2',470,35,42),('H2',471,35,43),('H2',472,35,44),('H2',473,35,45),('H2',474,36,17),('H2',475,36,18),('H2',476,36,19),('H2',477,36,20),('H2',478,37,17),('H2',479,37,18),('H2',480,37,19),('H2',481,37,20),('H2',482,38,45),('H2',483,38,46),('H2',484,38,47),('H2',485,38,48),('H2',486,38,49),('H2',487,38,50),('H2',488,38,51),('H2',489,38,52),('H2',490,38,53),('H2',491,38,54),('H2',492,38,55),('H2',493,31,21),('H2',494,31,22),('H2',495,31,23),('H2',496,31,24),('H2',497,32,21),('H2',498,32,22),('H2',499,32,23),('H2',500,32,24),('H2',501,33,21),('H2',502,33,22),('H2',503,33,23),('H2',504,33,24),('H2',505,34,56),('H2',506,34,57),('H2',507,34,58),('H2',508,34,59),('H2',509,34,60),('H2',510,34,61),('H2',511,34,62),('H2',512,34,63),('H2',513,34,64),('H2',514,34,65),('H2',515,34,66),('H2',516,35,46),('H2',517,35,47),('H2',518,35,48),('H2',519,35,49),('H2',520,35,50),('H2',521,35,51),('H2',522,35,52),('H2',523,35,53),('H2',524,35,54),('H2',525,36,21),('H2',526,36,22),('H2',527,36,23),('H2',528,36,24),('H2',529,37,21),('H2',530,37,22),('H2',531,37,23),('H2',532,37,24),('H2',533,38,56),('H2',534,38,57),('H2',535,38,58),('H2',536,38,59),('H2',537,38,60),('H2',538,38,61),('H2',539,38,62),('H2',540,38,63),('H2',541,38,64),('H2',542,38,65),('H2',543,38,66),('H2',544,31,25),('H2',545,31,26),('H2',546,31,27),('H2',547,31,28),('H2',548,32,25),('H2',549,32,26),('H2',550,32,27),('H2',551,32,28),('H2',552,33,25),('H2',553,33,26),('H2',554,33,27),('H2',555,33,28),('H2',556,34,67),('H2',557,34,68),('H2',558,34,69),('H2',559,34,70),('H2',560,34,71),('H2',561,34,72),('H2',562,34,73),('H2',563,34,74),('H2',564,34,75),('H2',565,34,76),('H2',566,34,77),('H2',567,35,55),('H2',568,35,56),('H2',569,35,57),('H2',570,35,58),('H2',571,35,59),('H2',572,35,60),('H2',573,35,61),('H2',574,35,62),('H2',575,35,63),('H2',576,36,25),('H2',577,36,26),('H2',578,36,27),('H2',579,36,28),('H2',580,37,25),('H2',581,37,26),('H2',582,37,27),('H2',583,37,28),('H2',584,38,67),('H2',585,38,68),('H2',586,38,69),('H2',587,38,70),('H2',588,38,71),('H2',589,38,72),('H2',590,38,73),('H2',591,38,74),('H2',592,38,75),('H2',593,38,76),('H2',594,38,77),('H3',1,1,0),('H3',2,1,1),('H3',3,1,2),('H3',4,1,3),('H3',5,1,4),('H3',6,1,5),('H3',7,1,6),('H3',8,1,7),('H3',9,1,8),('H3',10,1,9),('H3',11,1,10),('H3',12,2,0),('H3',13,2,1),('H3',14,3,0),('H3',15,3,1),('H3',16,3,2),('H3',17,3,3),('H3',18,3,4),('H3',19,3,5),('H3',20,4,0),('H3',21,4,1),('H3',22,4,2),('H3',23,4,3),('H3',24,4,4),('H3',25,4,5),('H3',26,4,6),('H3',27,4,7),('H3',28,4,8),('H3',29,4,9),('H3',30,4,10),('H3',31,4,11),('H3',32,4,12),('H3',33,5,0),('H3',34,5,1),('H3',35,5,2),('H3',36,5,3),('H3',37,5,4),('H3',38,6,0),('H3',39,6,1),('H3',40,6,2),('H3',41,7,0),('H3',42,7,1),('H3',43,7,2),('H3',44,7,3),('H3',45,7,4),('H3',46,8,0),('H3',47,8,1),('H3',48,8,2),('H3',49,8,3),('H3',50,8,4),('H3',51,8,5),('H3',52,8,6),('H3',53,8,7),('H3',54,8,8),('H3',55,9,0),('H3',56,9,1),('H3',57,9,2),('H3',58,9,3),('H3',59,9,4),('H3',60,9,5),('H3',61,9,6),('H3',62,9,7),('H3',63,9,8),('H3',64,9,9),('H3',65,9,10),('H3',66,9,11),('H3',67,9,12),('H3',68,9,13),('H3',69,9,14),('H3',70,9,15),('H3',71,9,16),('H3',72,9,17),('H3',73,9,18),('H3',74,9,19),('H3',75,9,20),('H3',76,9,21),('H3',77,9,22),('H3',78,9,23),('H3',79,9,24),('H3',80,9,25),('H3',81,9,26),('H3',82,9,27),('H3',83,9,28),('H3',84,9,29),('H3',85,9,30),('H3',86,9,31),('H3',87,10,0),('H3',88,10,1),('H3',89,10,2),('H3',90,10,3),('H3',91,10,4),('H3',92,10,5),('H3',93,11,0),('H3',94,11,1),('H3',95,11,2),('H3',96,11,3),('H3',97,11,4),('H3',98,11,5),('H3',99,11,6),('H3',100,11,7),('H3',101,11,8),('H3',102,11,9),('H3',103,11,10),('H3',104,11,11),('H3',105,11,12),('H3',106,12,0),('H3',107,12,1),('H3',108,12,2),('H3',109,12,3),('H3',110,12,4),('H3',111,12,5),('H3',112,12,6),('H3',113,12,7),('H3',114,12,8),('H3',115,12,9),('H3',116,12,10),('H3',117,12,11),('H3',118,13,0),('H3',119,13,1),('H3',120,13,2),('H3',121,13,3),('H3',122,14,0),('H3',123,14,1),('H3',124,14,2),('H3',125,15,0),('H3',126,15,1),('H3',127,15,2),('H3',128,15,3),('H3',129,15,4),('H3',130,16,0),('H3',131,16,1),('H3',132,16,2),('H3',133,16,3),('H3',134,17,0),('H3',135,17,1),('H3',136,17,2),('H3',137,17,3),('H3',138,17,4),('H3',139,18,0),('H3',140,18,1),('H3',141,18,2),('H3',142,18,3),('H3',143,18,4),('H3',144,18,5),('H3',145,19,0),('H3',146,19,1),('H3',147,19,2),('H3',148,19,3),('H3',149,19,4),('H3',150,19,5),('H3',151,19,6),('H3',152,19,7),('H3',153,19,8),('H3',154,19,9),('H3',155,20,0),('H3',156,20,1),('H3',157,20,2),('H3',158,20,3),('H3',159,20,4),('H3',160,20,5),('H3',161,21,0),('H3',162,21,1),('H3',163,21,2),('H3',164,21,3),('H3',165,22,0),('H3',166,22,1),('H3',167,22,2),('H3',168,23,0),('H3',169,23,1),('H3',170,23,2),('H3',171,23,3),('H3',172,23,4),('H3',173,23,5),('H3',174,24,0),('H3',175,24,1),('H3',176,24,2),('H3',177,25,0),('H3',178,25,1),('H3',179,25,2),('H3',180,25,3),('H3',181,26,0),('H3',182,26,1),('H3',183,26,2),('H3',184,26,3),('H3',185,27,0),('H3',186,27,1),('H3',187,27,2),('H3',188,27,3),('H3',189,28,0),('H3',190,28,1),('H3',191,28,2),('H3',192,28,3),('H3',193,28,4),('H3',194,28,5),('H3',195,28,6),('H3',196,28,7),('H3',197,28,8),('H3',198,28,9),('H3',199,28,10),('H3',200,28,11),('H3',201,28,12),('H3',202,28,13),('H3',203,28,14),('H3',204,28,15),('H3',205,29,1),('H3',206,29,2),('H3',207,29,3),('H3',208,30,1),('H3',209,30,2),('H3',210,30,3),('H3',211,30,4),('H3',212,30,5),('H3',213,30,6),('H3',214,31,1),('H3',215,31,2),('H3',216,31,3),('H3',217,31,4),('H3',218,32,1),('H3',219,32,2),('H3',220,32,3),('H3',221,32,4),('H3',222,33,1),('H3',223,33,2),('H3',224,33,3),('H3',225,33,4),('H3',226,34,1),('H3',227,34,2),('H3',228,34,3),('H3',229,34,4),('H3',230,34,5),('H3',231,34,6),('H3',232,34,7),('H3',233,34,8),('H3',234,34,9),('H3',235,34,10),('H3',236,34,11),('H3',237,35,1),('H3',238,35,2),('H3',239,35,3),('H3',240,35,4),('H3',241,35,5),('H3',242,35,6),('H3',243,35,7),('H3',244,35,8),('H3',245,35,9),('H3',246,36,1),('H3',247,36,2),('H3',248,36,3),('H3',249,36,4),('H3',250,37,1),('H3',251,37,2),('H3',252,37,3),('H3',253,37,4),('H3',254,38,1),('H3',255,38,2),('H3',256,38,3),('H3',257,38,4),('H3',258,38,5),('H3',259,38,6),('H3',260,38,7),('H3',261,38,8),('H3',262,38,9),('H3',263,38,10),('H3',264,38,11),('H3',265,31,5),('H3',266,31,6),('H3',267,31,7),('H3',268,31,8),('H3',269,32,5),('H3',270,32,6),('H3',271,32,7),('H3',272,32,8),('H3',273,33,5),('H3',274,33,6),('H3',275,33,7),('H3',276,33,8),('H3',277,34,12),('H3',278,34,13),('H3',279,34,14),('H3',280,34,15),('H3',281,34,16),('H3',282,34,17),('H3',283,34,18),('H3',284,34,19),('H3',285,34,20),('H3',286,34,21),('H3',287,34,22),('H3',288,35,10),('H3',289,35,11),('H3',290,35,12),('H3',291,35,13),('H3',292,35,14),('H3',293,35,15),('H3',294,35,16),('H3',295,35,17),('H3',296,35,18),('H3',297,36,5),('H3',298,36,6),('H3',299,36,7),('H3',300,36,8),('H3',301,37,5),('H3',302,37,6),('H3',303,37,7),('H3',304,37,8),('H3',305,38,12),('H3',306,38,13),('H3',307,38,14),('H3',308,38,15),('H3',309,38,16),('H3',310,38,17),('H3',311,38,18),('H3',312,38,19),('H3',313,38,20),('H3',314,38,21),('H3',315,38,22),('H3',316,10,6),('H3',317,39,1),('H3',318,39,2),('H3',319,39,3),('H3',320,39,4),('H3',321,39,5),('H3',322,39,6),('H3',323,2,2),('H3',324,40,1),('H3',325,40,2),('H3',326,40,3),('H3',327,41,1),('H3',328,41,2),('H3',331,29,4),('H3',332,29,5),('H3',333,29,6),('H3',334,30,7),('H3',335,30,8),('H3',336,30,9),('H3',337,30,10),('H3',338,30,11),('H3',339,30,12),('H3',340,31,9),('H3',341,31,10),('H3',342,31,11),('H3',343,31,12),('H3',344,32,9),('H3',345,32,10),('H3',346,32,11),('H3',347,32,12),('H3',348,33,9),('H3',349,33,10),('H3',350,33,11),('H3',351,33,12),('H3',352,34,23),('H3',353,34,24),('H3',354,34,25),('H3',355,34,26),('H3',356,34,27),('H3',357,34,28),('H3',358,34,29),('H3',359,34,30),('H3',360,34,31),('H3',361,34,32),('H3',362,34,33),('H3',363,35,19),('H3',364,35,20),('H3',365,35,21),('H3',366,35,22),('H3',367,35,23),('H3',368,35,24),('H3',369,35,25),('H3',370,35,26),('H3',371,35,27),('H3',372,36,9),('H3',373,36,10),('H3',374,36,11),('H3',375,36,12),('H3',376,37,9),('H3',377,37,10),('H3',378,37,11),('H3',379,37,12),('H3',380,38,23),('H3',381,38,24),('H3',382,38,25),('H3',383,38,26),('H3',384,38,27),('H3',385,38,28),('H3',386,38,29),('H3',387,38,30),('H3',388,38,31),('H3',389,38,32),('H3',390,38,33),('H3',391,31,13),('H3',392,31,14),('H3',393,31,15),('H3',394,31,16),('H3',395,32,13),('H3',396,32,14),('H3',397,32,15),('H3',398,32,16),('H3',399,33,13),('H3',400,33,14),('H3',401,33,15),('H3',402,33,16),('H3',403,34,34),('H3',404,34,35),('H3',405,34,36),('H3',406,34,37),('H3',407,34,38),('H3',408,34,39),('H3',409,34,40),('H3',410,34,41),('H3',411,34,42),('H3',412,34,43),('H3',413,34,44),('H3',414,35,28),('H3',415,35,29),('H3',416,35,30),('H3',417,35,31),('H3',418,35,32),('H3',419,35,33),('H3',420,35,34),('H3',421,35,35),('H3',422,35,36),('H3',423,36,13),('H3',424,36,14),('H3',425,36,15),('H3',426,36,16),('H3',427,37,13),('H3',428,37,14),('H3',429,37,15),('H3',430,37,16),('H3',431,38,34),('H3',432,38,35),('H3',433,38,36),('H3',434,38,37),('H3',435,38,38),('H3',436,38,39),('H3',437,38,40),('H3',438,38,41),('H3',439,38,42),('H3',440,38,43),('H3',441,38,44),('H3',442,31,17),('H3',443,31,18),('H3',444,31,19),('H3',445,31,20),('H3',446,32,17),('H3',447,32,18),('H3',448,32,19),('H3',449,32,20),('H3',450,33,17),('H3',451,33,18),('H3',452,33,19),('H3',453,33,20),('H3',454,34,45),('H3',455,34,46),('H3',456,34,47),('H3',457,34,48),('H3',458,34,49),('H3',459,34,50),('H3',460,34,51),('H3',461,34,52),('H3',462,34,53),('H3',463,34,54),('H3',464,34,55),('H3',465,35,37),('H3',466,35,38),('H3',467,35,39),('H3',468,35,40),('H3',469,35,41),('H3',470,35,42),('H3',471,35,43),('H3',472,35,44),('H3',473,35,45),('H3',474,36,17),('H3',475,36,18),('H3',476,36,19),('H3',477,36,20),('H3',478,37,17),('H3',479,37,18),('H3',480,37,19),('H3',481,37,20),('H3',482,38,45),('H3',483,38,46),('H3',484,38,47),('H3',485,38,48),('H3',486,38,49),('H3',487,38,50),('H3',488,38,51),('H3',489,38,52),('H3',490,38,53),('H3',491,38,54),('H3',492,38,55),('H3',493,31,21),('H3',494,31,22),('H3',495,31,23),('H3',496,31,24),('H3',497,32,21),('H3',498,32,22),('H3',499,32,23),('H3',500,32,24),('H3',501,33,21),('H3',502,33,22),('H3',503,33,23),('H3',504,33,24),('H3',505,34,56),('H3',506,34,57),('H3',507,34,58),('H3',508,34,59),('H3',509,34,60),('H3',510,34,61),('H3',511,34,62),('H3',512,34,63),('H3',513,34,64),('H3',514,34,65),('H3',515,34,66),('H3',516,35,46),('H3',517,35,47),('H3',518,35,48),('H3',519,35,49),('H3',520,35,50),('H3',521,35,51),('H3',522,35,52),('H3',523,35,53),('H3',524,35,54),('H3',525,36,21),('H3',526,36,22),('H3',527,36,23),('H3',528,36,24),('H3',529,37,21),('H3',530,37,22),('H3',531,37,23),('H3',532,37,24),('H3',533,38,56),('H3',534,38,57),('H3',535,38,58),('H3',536,38,59),('H3',537,38,60),('H3',538,38,61),('H3',539,38,62),('H3',540,38,63),('H3',541,38,64),('H3',542,38,65),('H3',543,38,66),('H3',544,31,25),('H3',545,31,26),('H3',546,31,27),('H3',547,31,28),('H3',548,32,25),('H3',549,32,26),('H3',550,32,27),('H3',551,32,28),('H3',552,33,25),('H3',553,33,26),('H3',554,33,27),('H3',555,33,28),('H3',556,34,67),('H3',557,34,68),('H3',558,34,69),('H3',559,34,70),('H3',560,34,71),('H3',561,34,72),('H3',562,34,73),('H3',563,34,74),('H3',564,34,75),('H3',565,34,76),('H3',566,34,77),('H3',567,35,55),('H3',568,35,56),('H3',569,35,57),('H3',570,35,58),('H3',571,35,59),('H3',572,35,60),('H3',573,35,61),('H3',574,35,62),('H3',575,35,63),('H3',576,36,25),('H3',577,36,26),('H3',578,36,27),('H3',579,36,28),('H3',580,37,25),('H3',581,37,26),('H3',582,37,27),('H3',583,37,28),('H3',584,38,67),('H3',585,38,68),('H3',586,38,69),('H3',587,38,70),('H3',588,38,71),('H3',589,38,72),('H3',590,38,73),('H3',591,38,74),('H3',592,38,75),('H3',593,38,76),('H3',594,38,77),('H4',1,1,0),('H4',2,1,1),('H4',3,1,2),('H4',4,1,3),('H4',5,1,4),('H4',6,1,5),('H4',7,1,6),('H4',8,1,7),('H4',9,1,8),('H4',10,1,9),('H4',11,1,10),('H4',12,2,0),('H4',13,2,1),('H4',14,3,0),('H4',15,3,1),('H4',16,3,2),('H4',17,3,3),('H4',18,3,4),('H4',19,3,5),('H4',20,4,0),('H4',21,4,1),('H4',22,4,2),('H4',23,4,3),('H4',24,4,4),('H4',25,4,5),('H4',26,4,6),('H4',27,4,7),('H4',28,4,8),('H4',29,4,9),('H4',30,4,10),('H4',31,4,11),('H4',32,4,12),('H4',33,5,0),('H4',34,5,1),('H4',35,5,2),('H4',36,5,3),('H4',37,5,4),('H4',38,6,0),('H4',39,6,1),('H4',40,6,2),('H4',41,7,0),('H4',42,7,1),('H4',43,7,2),('H4',44,7,3),('H4',45,7,4),('H4',46,8,0),('H4',47,8,1),('H4',48,8,2),('H4',49,8,3),('H4',50,8,4),('H4',51,8,5),('H4',52,8,6),('H4',53,8,7),('H4',54,8,8),('H4',55,9,0),('H4',56,9,1),('H4',57,9,2),('H4',58,9,3),('H4',59,9,4),('H4',60,9,5),('H4',61,9,6),('H4',62,9,7),('H4',63,9,8),('H4',64,9,9),('H4',65,9,10),('H4',66,9,11),('H4',67,9,12),('H4',68,9,13),('H4',69,9,14),('H4',70,9,15),('H4',71,9,16),('H4',72,9,17),('H4',73,9,18),('H4',74,9,19),('H4',75,9,20),('H4',76,9,21),('H4',77,9,22),('H4',78,9,23),('H4',79,9,24),('H4',80,9,25),('H4',81,9,26),('H4',82,9,27),('H4',83,9,28),('H4',84,9,29),('H4',85,9,30),('H4',86,9,31),('H4',87,10,0),('H4',88,10,1),('H4',89,10,2),('H4',90,10,3),('H4',91,10,4),('H4',92,10,5),('H4',93,11,0),('H4',94,11,1),('H4',95,11,2),('H4',96,11,3),('H4',97,11,4),('H4',98,11,5),('H4',99,11,6),('H4',100,11,7),('H4',101,11,8),('H4',102,11,9),('H4',103,11,10),('H4',104,11,11),('H4',105,11,12),('H4',106,12,0),('H4',107,12,1),('H4',108,12,2),('H4',109,12,3),('H4',110,12,4),('H4',111,12,5),('H4',112,12,6),('H4',113,12,7),('H4',114,12,8),('H4',115,12,9),('H4',116,12,10),('H4',117,12,11),('H4',118,13,0),('H4',119,13,1),('H4',120,13,2),('H4',121,13,3),('H4',122,14,0),('H4',123,14,1),('H4',124,14,2),('H4',125,15,0),('H4',126,15,1),('H4',127,15,2),('H4',128,15,3),('H4',129,15,4),('H4',130,16,0),('H4',131,16,1),('H4',132,16,2),('H4',133,16,3),('H4',134,17,0),('H4',135,17,1),('H4',136,17,2),('H4',137,17,3),('H4',138,17,4),('H4',139,18,0),('H4',140,18,1),('H4',141,18,2),('H4',142,18,3),('H4',143,18,4),('H4',144,18,5),('H4',145,19,0),('H4',146,19,1),('H4',147,19,2),('H4',148,19,3),('H4',149,19,4),('H4',150,19,5),('H4',151,19,6),('H4',152,19,7),('H4',153,19,8),('H4',154,19,9),('H4',155,20,0),('H4',156,20,1),('H4',157,20,2),('H4',158,20,3),('H4',159,20,4),('H4',160,20,5),('H4',161,21,0),('H4',162,21,1),('H4',163,21,2),('H4',164,21,3),('H4',165,22,0),('H4',166,22,1),('H4',167,22,2),('H4',168,23,0),('H4',169,23,1),('H4',170,23,2),('H4',171,23,3),('H4',172,23,4),('H4',173,23,5),('H4',174,24,0),('H4',175,24,1),('H4',176,24,2),('H4',177,25,0),('H4',178,25,1),('H4',179,25,2),('H4',180,25,3),('H4',181,26,0),('H4',182,26,1),('H4',183,26,2),('H4',184,26,3),('H4',185,27,0),('H4',186,27,1),('H4',187,27,2),('H4',188,27,3),('H4',189,28,0),('H4',190,28,1),('H4',191,28,2),('H4',192,28,3),('H4',193,28,4),('H4',194,28,5),('H4',195,28,6),('H4',196,28,7),('H4',197,28,8),('H4',198,28,9),('H4',199,28,10),('H4',200,28,11),('H4',201,28,12),('H4',202,28,13),('H4',203,28,14),('H4',204,28,15),('H4',205,29,1),('H4',206,29,2),('H4',207,29,3),('H4',208,30,1),('H4',209,30,2),('H4',210,30,3),('H4',211,30,4),('H4',212,30,5),('H4',213,30,6),('H4',214,31,1),('H4',215,31,2),('H4',216,31,3),('H4',217,31,4),('H4',218,32,1),('H4',219,32,2),('H4',220,32,3),('H4',221,32,4),('H4',222,33,1),('H4',223,33,2),('H4',224,33,3),('H4',225,33,4),('H4',226,34,1),('H4',227,34,2),('H4',228,34,3),('H4',229,34,4),('H4',230,34,5),('H4',231,34,6),('H4',232,34,7),('H4',233,34,8),('H4',234,34,9),('H4',235,34,10),('H4',236,34,11),('H4',237,35,1),('H4',238,35,2),('H4',239,35,3),('H4',240,35,4),('H4',241,35,5),('H4',242,35,6),('H4',243,35,7),('H4',244,35,8),('H4',245,35,9),('H4',246,36,1),('H4',247,36,2),('H4',248,36,3),('H4',249,36,4),('H4',250,37,1),('H4',251,37,2),('H4',252,37,3),('H4',253,37,4),('H4',254,38,1),('H4',255,38,2),('H4',256,38,3),('H4',257,38,4),('H4',258,38,5),('H4',259,38,6),('H4',260,38,7),('H4',261,38,8),('H4',262,38,9),('H4',263,38,10),('H4',264,38,11),('H4',265,31,5),('H4',266,31,6),('H4',267,31,7),('H4',268,31,8),('H4',269,32,5),('H4',270,32,6),('H4',271,32,7),('H4',272,32,8),('H4',273,33,5),('H4',274,33,6),('H4',275,33,7),('H4',276,33,8),('H4',277,34,12),('H4',278,34,13),('H4',279,34,14),('H4',280,34,15),('H4',281,34,16),('H4',282,34,17),('H4',283,34,18),('H4',284,34,19),('H4',285,34,20),('H4',286,34,21),('H4',287,34,22),('H4',288,35,10),('H4',289,35,11),('H4',290,35,12),('H4',291,35,13),('H4',292,35,14),('H4',293,35,15),('H4',294,35,16),('H4',295,35,17),('H4',296,35,18),('H4',297,36,5),('H4',298,36,6),('H4',299,36,7),('H4',300,36,8),('H4',301,37,5),('H4',302,37,6),('H4',303,37,7),('H4',304,37,8),('H4',305,38,12),('H4',306,38,13),('H4',307,38,14),('H4',308,38,15),('H4',309,38,16),('H4',310,38,17),('H4',311,38,18),('H4',312,38,19),('H4',313,38,20),('H4',314,38,21),('H4',315,38,22),('H4',316,10,6),('H4',317,39,1),('H4',318,39,2),('H4',319,39,3),('H4',320,39,4),('H4',321,39,5),('H4',322,39,6),('H4',323,2,2),('H4',324,40,1),('H4',325,40,2),('H4',326,40,3),('H4',327,41,1),('H4',328,41,2),('H4',331,29,4),('H4',332,29,5),('H4',333,29,6),('H4',334,30,7),('H4',335,30,8),('H4',336,30,9),('H4',337,30,10),('H4',338,30,11),('H4',339,30,12),('H4',340,31,9),('H4',341,31,10),('H4',342,31,11),('H4',343,31,12),('H4',344,32,9),('H4',345,32,10),('H4',346,32,11),('H4',347,32,12),('H4',348,33,9),('H4',349,33,10),('H4',350,33,11),('H4',351,33,12),('H4',352,34,23),('H4',353,34,24),('H4',354,34,25),('H4',355,34,26),('H4',356,34,27),('H4',357,34,28),('H4',358,34,29),('H4',359,34,30),('H4',360,34,31),('H4',361,34,32),('H4',362,34,33),('H4',363,35,19),('H4',364,35,20),('H4',365,35,21),('H4',366,35,22),('H4',367,35,23),('H4',368,35,24),('H4',369,35,25),('H4',370,35,26),('H4',371,35,27),('H4',372,36,9),('H4',373,36,10),('H4',374,36,11),('H4',375,36,12),('H4',376,37,9),('H4',377,37,10),('H4',378,37,11),('H4',379,37,12),('H4',380,38,23),('H4',381,38,24),('H4',382,38,25),('H4',383,38,26),('H4',384,38,27),('H4',385,38,28),('H4',386,38,29),('H4',387,38,30),('H4',388,38,31),('H4',389,38,32),('H4',390,38,33),('H4',391,31,13),('H4',392,31,14),('H4',393,31,15),('H4',394,31,16),('H4',395,32,13),('H4',396,32,14),('H4',397,32,15),('H4',398,32,16),('H4',399,33,13),('H4',400,33,14),('H4',401,33,15),('H4',402,33,16),('H4',403,34,34),('H4',404,34,35),('H4',405,34,36),('H4',406,34,37),('H4',407,34,38),('H4',408,34,39),('H4',409,34,40),('H4',410,34,41),('H4',411,34,42),('H4',412,34,43),('H4',413,34,44),('H4',414,35,28),('H4',415,35,29),('H4',416,35,30),('H4',417,35,31),('H4',418,35,32),('H4',419,35,33),('H4',420,35,34),('H4',421,35,35),('H4',422,35,36),('H4',423,36,13),('H4',424,36,14),('H4',425,36,15),('H4',426,36,16),('H4',427,37,13),('H4',428,37,14),('H4',429,37,15),('H4',430,37,16),('H4',431,38,34),('H4',432,38,35),('H4',433,38,36),('H4',434,38,37),('H4',435,38,38),('H4',436,38,39),('H4',437,38,40),('H4',438,38,41),('H4',439,38,42),('H4',440,38,43),('H4',441,38,44),('H4',442,31,17),('H4',443,31,18),('H4',444,31,19),('H4',445,31,20),('H4',446,32,17),('H4',447,32,18),('H4',448,32,19),('H4',449,32,20),('H4',450,33,17),('H4',451,33,18),('H4',452,33,19),('H4',453,33,20),('H4',454,34,45),('H4',455,34,46),('H4',456,34,47),('H4',457,34,48),('H4',458,34,49),('H4',459,34,50),('H4',460,34,51),('H4',461,34,52),('H4',462,34,53),('H4',463,34,54),('H4',464,34,55),('H4',465,35,37),('H4',466,35,38),('H4',467,35,39),('H4',468,35,40),('H4',469,35,41),('H4',470,35,42),('H4',471,35,43),('H4',472,35,44),('H4',473,35,45),('H4',474,36,17),('H4',475,36,18),('H4',476,36,19),('H4',477,36,20),('H4',478,37,17),('H4',479,37,18),('H4',480,37,19),('H4',481,37,20),('H4',482,38,45),('H4',483,38,46),('H4',484,38,47),('H4',485,38,48),('H4',486,38,49),('H4',487,38,50),('H4',488,38,51),('H4',489,38,52),('H4',490,38,53),('H4',491,38,54),('H4',492,38,55),('H4',493,31,21),('H4',494,31,22),('H4',495,31,23),('H4',496,31,24),('H4',497,32,21),('H4',498,32,22),('H4',499,32,23),('H4',500,32,24),('H4',501,33,21),('H4',502,33,22),('H4',503,33,23),('H4',504,33,24),('H4',505,34,56),('H4',506,34,57),('H4',507,34,58),('H4',508,34,59),('H4',509,34,60),('H4',510,34,61),('H4',511,34,62),('H4',512,34,63),('H4',513,34,64),('H4',514,34,65),('H4',515,34,66),('H4',516,35,46),('H4',517,35,47),('H4',518,35,48),('H4',519,35,49),('H4',520,35,50),('H4',521,35,51),('H4',522,35,52),('H4',523,35,53),('H4',524,35,54),('H4',525,36,21),('H4',526,36,22),('H4',527,36,23),('H4',528,36,24),('H4',529,37,21),('H4',530,37,22),('H4',531,37,23),('H4',532,37,24),('H4',533,38,56),('H4',534,38,57),('H4',535,38,58),('H4',536,38,59),('H4',537,38,60),('H4',538,38,61),('H4',539,38,62),('H4',540,38,63),('H4',541,38,64),('H4',542,38,65),('H4',543,38,66),('H4',544,31,25),('H4',545,31,26),('H4',546,31,27),('H4',547,31,28),('H4',548,32,25),('H4',549,32,26),('H4',550,32,27),('H4',551,32,28),('H4',552,33,25),('H4',553,33,26),('H4',554,33,27),('H4',555,33,28),('H4',556,34,67),('H4',557,34,68),('H4',558,34,69),('H4',559,34,70),('H4',560,34,71),('H4',561,34,72),('H4',562,34,73),('H4',563,34,74),('H4',564,34,75),('H4',565,34,76),('H4',566,34,77),('H4',567,35,55),('H4',568,35,56),('H4',569,35,57),('H4',570,35,58),('H4',571,35,59),('H4',572,35,60),('H4',573,35,61),('H4',574,35,62),('H4',575,35,63),('H4',576,36,25),('H4',577,36,26),('H4',578,36,27),('H4',579,36,28),('H4',580,37,25),('H4',581,37,26),('H4',582,37,27),('H4',583,37,28),('H4',584,38,67),('H4',585,38,68),('H4',586,38,69),('H4',587,38,70),('H4',588,38,71),('H4',589,38,72),('H4',590,38,73),('H4',591,38,74),('H4',592,38,75),('H4',593,38,76),('H4',594,38,77),('H5',1,1,0),('H5',2,1,1),('H5',3,1,2),('H5',4,1,3),('H5',5,1,4),('H5',6,1,5),('H5',7,1,6),('H5',8,1,7),('H5',9,1,8),('H5',10,1,9),('H5',11,1,10),('H5',12,2,0),('H5',13,2,1),('H5',14,3,0),('H5',15,3,1),('H5',16,3,2),('H5',17,3,3),('H5',18,3,4),('H5',19,3,5),('H5',20,4,0),('H5',21,4,1),('H5',22,4,2),('H5',23,4,3),('H5',24,4,4),('H5',25,4,5),('H5',26,4,6),('H5',27,4,7),('H5',28,4,8),('H5',29,4,9),('H5',30,4,10),('H5',31,4,11),('H5',32,4,12),('H5',33,5,0),('H5',34,5,1),('H5',35,5,2),('H5',36,5,3),('H5',37,5,4),('H5',38,6,0),('H5',39,6,1),('H5',40,6,2),('H5',41,7,0),('H5',42,7,1),('H5',43,7,2),('H5',44,7,3),('H5',45,7,4),('H5',46,8,0),('H5',47,8,1),('H5',48,8,2),('H5',49,8,3),('H5',50,8,4),('H5',51,8,5),('H5',52,8,6),('H5',53,8,7),('H5',54,8,8),('H5',55,9,0),('H5',56,9,1),('H5',57,9,2),('H5',58,9,3),('H5',59,9,4),('H5',60,9,5),('H5',61,9,6),('H5',62,9,7),('H5',63,9,8),('H5',64,9,9),('H5',65,9,10),('H5',66,9,11),('H5',67,9,12),('H5',68,9,13),('H5',69,9,14),('H5',70,9,15),('H5',71,9,16),('H5',72,9,17),('H5',73,9,18),('H5',74,9,19),('H5',75,9,20),('H5',76,9,21),('H5',77,9,22),('H5',78,9,23),('H5',79,9,24),('H5',80,9,25),('H5',81,9,26),('H5',82,9,27),('H5',83,9,28),('H5',84,9,29),('H5',85,9,30),('H5',86,9,31),('H5',87,10,0),('H5',88,10,1),('H5',89,10,2),('H5',90,10,3),('H5',91,10,4),('H5',92,10,5),('H5',93,11,0),('H5',94,11,1),('H5',95,11,2),('H5',96,11,3),('H5',97,11,4),('H5',98,11,5),('H5',99,11,6),('H5',100,11,7),('H5',101,11,8),('H5',102,11,9),('H5',103,11,10),('H5',104,11,11),('H5',105,11,12),('H5',106,12,0),('H5',107,12,1),('H5',108,12,2),('H5',109,12,3),('H5',110,12,4),('H5',111,12,5),('H5',112,12,6),('H5',113,12,7),('H5',114,12,8),('H5',115,12,9),('H5',116,12,10),('H5',117,12,11),('H5',118,13,0),('H5',119,13,1),('H5',120,13,2),('H5',121,13,3),('H5',122,14,0),('H5',123,14,1),('H5',124,14,2),('H5',125,15,0),('H5',126,15,1),('H5',127,15,2),('H5',128,15,3),('H5',129,15,4),('H5',130,16,0),('H5',131,16,1),('H5',132,16,2),('H5',133,16,3),('H5',134,17,0),('H5',135,17,1),('H5',136,17,2),('H5',137,17,3),('H5',138,17,4),('H5',139,18,0),('H5',140,18,1),('H5',141,18,2),('H5',142,18,3),('H5',143,18,4),('H5',144,18,5),('H5',145,19,0),('H5',146,19,1),('H5',147,19,2),('H5',148,19,3),('H5',149,19,4),('H5',150,19,5),('H5',151,19,6),('H5',152,19,7),('H5',153,19,8),('H5',154,19,9),('H5',155,20,0),('H5',156,20,1),('H5',157,20,2),('H5',158,20,3),('H5',159,20,4),('H5',160,20,5),('H5',161,21,0),('H5',162,21,1),('H5',163,21,2),('H5',164,21,3),('H5',165,22,0),('H5',166,22,1),('H5',167,22,2),('H5',168,23,0),('H5',169,23,1),('H5',170,23,2),('H5',171,23,3),('H5',172,23,4),('H5',173,23,5),('H5',174,24,0),('H5',175,24,1),('H5',176,24,2),('H5',177,25,0),('H5',178,25,1),('H5',179,25,2),('H5',180,25,3),('H5',181,26,0),('H5',182,26,1),('H5',183,26,2),('H5',184,26,3),('H5',185,27,0),('H5',186,27,1),('H5',187,27,2),('H5',188,27,3),('H5',189,28,0),('H5',190,28,1),('H5',191,28,2),('H5',192,28,3),('H5',193,28,4),('H5',194,28,5),('H5',195,28,6),('H5',196,28,7),('H5',197,28,8),('H5',198,28,9),('H5',199,28,10),('H5',200,28,11),('H5',201,28,12),('H5',202,28,13),('H5',203,28,14),('H5',204,28,15),('H5',205,29,1),('H5',206,29,2),('H5',207,29,3),('H5',208,30,1),('H5',209,30,2),('H5',210,30,3),('H5',211,30,4),('H5',212,30,5),('H5',213,30,6),('H5',214,31,1),('H5',215,31,2),('H5',216,31,3),('H5',217,31,4),('H5',218,32,1),('H5',219,32,2),('H5',220,32,3),('H5',221,32,4),('H5',222,33,1),('H5',223,33,2),('H5',224,33,3),('H5',225,33,4),('H5',226,34,1),('H5',227,34,2),('H5',228,34,3),('H5',229,34,4),('H5',230,34,5),('H5',231,34,6),('H5',232,34,7),('H5',233,34,8),('H5',234,34,9),('H5',235,34,10),('H5',236,34,11),('H5',237,35,1),('H5',238,35,2),('H5',239,35,3),('H5',240,35,4),('H5',241,35,5),('H5',242,35,6),('H5',243,35,7),('H5',244,35,8),('H5',245,35,9),('H5',246,36,1),('H5',247,36,2),('H5',248,36,3),('H5',249,36,4),('H5',250,37,1),('H5',251,37,2),('H5',252,37,3),('H5',253,37,4),('H5',254,38,1),('H5',255,38,2),('H5',256,38,3),('H5',257,38,4),('H5',258,38,5),('H5',259,38,6),('H5',260,38,7),('H5',261,38,8),('H5',262,38,9),('H5',263,38,10),('H5',264,38,11),('H5',265,31,5),('H5',266,31,6),('H5',267,31,7),('H5',268,31,8),('H5',269,32,5),('H5',270,32,6),('H5',271,32,7),('H5',272,32,8),('H5',273,33,5),('H5',274,33,6),('H5',275,33,7),('H5',276,33,8),('H5',277,34,12),('H5',278,34,13),('H5',279,34,14),('H5',280,34,15),('H5',281,34,16),('H5',282,34,17),('H5',283,34,18),('H5',284,34,19),('H5',285,34,20),('H5',286,34,21),('H5',287,34,22),('H5',288,35,10),('H5',289,35,11),('H5',290,35,12),('H5',291,35,13),('H5',292,35,14),('H5',293,35,15),('H5',294,35,16),('H5',295,35,17),('H5',296,35,18),('H5',297,36,5),('H5',298,36,6),('H5',299,36,7),('H5',300,36,8),('H5',301,37,5),('H5',302,37,6),('H5',303,37,7),('H5',304,37,8),('H5',305,38,12),('H5',306,38,13),('H5',307,38,14),('H5',308,38,15),('H5',309,38,16),('H5',310,38,17),('H5',311,38,18),('H5',312,38,19),('H5',313,38,20),('H5',314,38,21),('H5',315,38,22),('H5',316,10,6),('H5',317,39,1),('H5',318,39,2),('H5',319,39,3),('H5',320,39,4),('H5',321,39,5),('H5',322,39,6),('H5',323,2,2),('H5',324,40,1),('H5',325,40,2),('H5',326,40,3),('H5',327,41,1),('H5',328,41,2),('H5',331,29,4),('H5',332,29,5),('H5',333,29,6),('H5',334,30,7),('H5',335,30,8),('H5',336,30,9),('H5',337,30,10),('H5',338,30,11),('H5',339,30,12),('H5',340,31,9),('H5',341,31,10),('H5',342,31,11),('H5',343,31,12),('H5',344,32,9),('H5',345,32,10),('H5',346,32,11),('H5',347,32,12),('H5',348,33,9),('H5',349,33,10),('H5',350,33,11),('H5',351,33,12),('H5',352,34,23),('H5',353,34,24),('H5',354,34,25),('H5',355,34,26),('H5',356,34,27),('H5',357,34,28),('H5',358,34,29),('H5',359,34,30),('H5',360,34,31),('H5',361,34,32),('H5',362,34,33),('H5',363,35,19),('H5',364,35,20),('H5',365,35,21),('H5',366,35,22),('H5',367,35,23),('H5',368,35,24),('H5',369,35,25),('H5',370,35,26),('H5',371,35,27),('H5',372,36,9),('H5',373,36,10),('H5',374,36,11),('H5',375,36,12),('H5',376,37,9),('H5',377,37,10),('H5',378,37,11),('H5',379,37,12),('H5',380,38,23),('H5',381,38,24),('H5',382,38,25),('H5',383,38,26),('H5',384,38,27),('H5',385,38,28),('H5',386,38,29),('H5',387,38,30),('H5',388,38,31),('H5',389,38,32),('H5',390,38,33),('H5',391,31,13),('H5',392,31,14),('H5',393,31,15),('H5',394,31,16),('H5',395,32,13),('H5',396,32,14),('H5',397,32,15),('H5',398,32,16),('H5',399,33,13),('H5',400,33,14),('H5',401,33,15),('H5',402,33,16),('H5',403,34,34),('H5',404,34,35),('H5',405,34,36),('H5',406,34,37),('H5',407,34,38),('H5',408,34,39),('H5',409,34,40),('H5',410,34,41),('H5',411,34,42),('H5',412,34,43),('H5',413,34,44),('H5',414,35,28),('H5',415,35,29),('H5',416,35,30),('H5',417,35,31),('H5',418,35,32),('H5',419,35,33),('H5',420,35,34),('H5',421,35,35),('H5',422,35,36),('H5',423,36,13),('H5',424,36,14),('H5',425,36,15),('H5',426,36,16),('H5',427,37,13),('H5',428,37,14),('H5',429,37,15),('H5',430,37,16),('H5',431,38,34),('H5',432,38,35),('H5',433,38,36),('H5',434,38,37),('H5',435,38,38),('H5',436,38,39),('H5',437,38,40),('H5',438,38,41),('H5',439,38,42),('H5',440,38,43),('H5',441,38,44),('H5',442,31,17),('H5',443,31,18),('H5',444,31,19),('H5',445,31,20),('H5',446,32,17),('H5',447,32,18),('H5',448,32,19),('H5',449,32,20),('H5',450,33,17),('H5',451,33,18),('H5',452,33,19),('H5',453,33,20),('H5',454,34,45),('H5',455,34,46),('H5',456,34,47),('H5',457,34,48),('H5',458,34,49),('H5',459,34,50),('H5',460,34,51),('H5',461,34,52),('H5',462,34,53),('H5',463,34,54),('H5',464,34,55),('H5',465,35,37),('H5',466,35,38),('H5',467,35,39),('H5',468,35,40),('H5',469,35,41),('H5',470,35,42),('H5',471,35,43),('H5',472,35,44),('H5',473,35,45),('H5',474,36,17),('H5',475,36,18),('H5',476,36,19),('H5',477,36,20),('H5',478,37,17),('H5',479,37,18),('H5',480,37,19),('H5',481,37,20),('H5',482,38,45),('H5',483,38,46),('H5',484,38,47),('H5',485,38,48),('H5',486,38,49),('H5',487,38,50),('H5',488,38,51),('H5',489,38,52),('H5',490,38,53),('H5',491,38,54),('H5',492,38,55),('H5',493,31,21),('H5',494,31,22),('H5',495,31,23),('H5',496,31,24),('H5',497,32,21),('H5',498,32,22),('H5',499,32,23),('H5',500,32,24),('H5',501,33,21),('H5',502,33,22),('H5',503,33,23),('H5',504,33,24),('H5',505,34,56),('H5',506,34,57),('H5',507,34,58),('H5',508,34,59),('H5',509,34,60),('H5',510,34,61),('H5',511,34,62),('H5',512,34,63),('H5',513,34,64),('H5',514,34,65),('H5',515,34,66),('H5',516,35,46),('H5',517,35,47),('H5',518,35,48),('H5',519,35,49),('H5',520,35,50),('H5',521,35,51),('H5',522,35,52),('H5',523,35,53),('H5',524,35,54),('H5',525,36,21),('H5',526,36,22),('H5',527,36,23),('H5',528,36,24),('H5',529,37,21),('H5',530,37,22),('H5',531,37,23),('H5',532,37,24),('H5',533,38,56),('H5',534,38,57),('H5',535,38,58),('H5',536,38,59),('H5',537,38,60),('H5',538,38,61),('H5',539,38,62),('H5',540,38,63),('H5',541,38,64),('H5',542,38,65),('H5',543,38,66),('H5',544,31,25),('H5',545,31,26),('H5',546,31,27),('H5',547,31,28),('H5',548,32,25),('H5',549,32,26),('H5',550,32,27),('H5',551,32,28),('H5',552,33,25),('H5',553,33,26),('H5',554,33,27),('H5',555,33,28),('H5',556,34,67),('H5',557,34,68),('H5',558,34,69),('H5',559,34,70),('H5',560,34,71),('H5',561,34,72),('H5',562,34,73),('H5',563,34,74),('H5',564,34,75),('H5',565,34,76),('H5',566,34,77),('H5',567,35,55),('H5',568,35,56),('H5',569,35,57),('H5',570,35,58),('H5',571,35,59),('H5',572,35,60),('H5',573,35,61),('H5',574,35,62),('H5',575,35,63),('H5',576,36,25),('H5',577,36,26),('H5',578,36,27),('H5',579,36,28),('H5',580,37,25),('H5',581,37,26),('H5',582,37,27),('H5',583,37,28),('H5',584,38,67),('H5',585,38,68),('H5',586,38,69),('H5',587,38,70),('H5',588,38,71),('H5',589,38,72),('H5',590,38,73),('H5',591,38,74),('H5',592,38,75),('H5',593,38,76),('H5',594,38,77),('H6',1,1,0),('H6',2,1,1),('H6',3,1,2),('H6',4,1,3),('H6',5,1,4),('H6',6,1,5),('H6',7,1,6),('H6',8,1,7),('H6',9,1,8),('H6',10,1,9),('H6',11,1,10),('H6',12,2,0),('H6',13,2,1),('H6',14,3,0),('H6',15,3,1),('H6',16,3,2),('H6',17,3,3),('H6',18,3,4),('H6',19,3,5),('H6',20,4,0),('H6',21,4,1),('H6',22,4,2),('H6',23,4,3),('H6',24,4,4),('H6',25,4,5),('H6',26,4,6),('H6',27,4,7),('H6',28,4,8),('H6',29,4,9),('H6',30,4,10),('H6',31,4,11),('H6',32,4,12),('H6',33,5,0),('H6',34,5,1),('H6',35,5,2),('H6',36,5,3),('H6',37,5,4),('H6',38,6,0),('H6',39,6,1),('H6',40,6,2),('H6',41,7,0),('H6',42,7,1),('H6',43,7,2),('H6',44,7,3),('H6',45,7,4),('H6',46,8,0),('H6',47,8,1),('H6',48,8,2),('H6',49,8,3),('H6',50,8,4),('H6',51,8,5),('H6',52,8,6),('H6',53,8,7),('H6',54,8,8),('H6',55,9,0),('H6',56,9,1),('H6',57,9,2),('H6',58,9,3),('H6',59,9,4),('H6',60,9,5),('H6',61,9,6),('H6',62,9,7),('H6',63,9,8),('H6',64,9,9),('H6',65,9,10),('H6',66,9,11),('H6',67,9,12),('H6',68,9,13),('H6',69,9,14),('H6',70,9,15),('H6',71,9,16),('H6',72,9,17),('H6',73,9,18),('H6',74,9,19),('H6',75,9,20),('H6',76,9,21),('H6',77,9,22),('H6',78,9,23),('H6',79,9,24),('H6',80,9,25),('H6',81,9,26),('H6',82,9,27),('H6',83,9,28),('H6',84,9,29),('H6',85,9,30),('H6',86,9,31),('H6',87,10,0),('H6',88,10,1),('H6',89,10,2),('H6',90,10,3),('H6',91,10,4),('H6',92,10,5),('H6',93,11,0),('H6',94,11,1),('H6',95,11,2),('H6',96,11,3),('H6',97,11,4),('H6',98,11,5),('H6',99,11,6),('H6',100,11,7),('H6',101,11,8),('H6',102,11,9),('H6',103,11,10),('H6',104,11,11),('H6',105,11,12),('H6',106,12,0),('H6',107,12,1),('H6',108,12,2),('H6',109,12,3),('H6',110,12,4),('H6',111,12,5),('H6',112,12,6),('H6',113,12,7),('H6',114,12,8),('H6',115,12,9),('H6',116,12,10),('H6',117,12,11),('H6',118,13,0),('H6',119,13,1),('H6',120,13,2),('H6',121,13,3),('H6',122,14,0),('H6',123,14,1),('H6',124,14,2),('H6',125,15,0),('H6',126,15,1),('H6',127,15,2),('H6',128,15,3),('H6',129,15,4),('H6',130,16,0),('H6',131,16,1),('H6',132,16,2),('H6',133,16,3),('H6',134,17,0),('H6',135,17,1),('H6',136,17,2),('H6',137,17,3),('H6',138,17,4),('H6',139,18,0),('H6',140,18,1),('H6',141,18,2),('H6',142,18,3),('H6',143,18,4),('H6',144,18,5),('H6',145,19,0),('H6',146,19,1),('H6',147,19,2),('H6',148,19,3),('H6',149,19,4),('H6',150,19,5),('H6',151,19,6),('H6',152,19,7),('H6',153,19,8),('H6',154,19,9),('H6',155,20,0),('H6',156,20,1),('H6',157,20,2),('H6',158,20,3),('H6',159,20,4),('H6',160,20,5),('H6',161,21,0),('H6',162,21,1),('H6',163,21,2),('H6',164,21,3),('H6',165,22,0),('H6',166,22,1),('H6',167,22,2),('H6',168,23,0),('H6',169,23,1),('H6',170,23,2),('H6',171,23,3),('H6',172,23,4),('H6',173,23,5),('H6',174,24,0),('H6',175,24,1),('H6',176,24,2),('H6',177,25,0),('H6',178,25,1),('H6',179,25,2),('H6',180,25,3),('H6',181,26,0),('H6',182,26,1),('H6',183,26,2),('H6',184,26,3),('H6',185,27,0),('H6',186,27,1),('H6',187,27,2),('H6',188,27,3),('H6',189,28,0),('H6',190,28,1),('H6',191,28,2),('H6',192,28,3),('H6',193,28,4),('H6',194,28,5),('H6',195,28,6),('H6',196,28,7),('H6',197,28,8),('H6',198,28,9),('H6',199,28,10),('H6',200,28,11),('H6',201,28,12),('H6',202,28,13),('H6',203,28,14),('H6',204,28,15),('H6',205,29,1),('H6',206,29,2),('H6',207,29,3),('H6',208,30,1),('H6',209,30,2),('H6',210,30,3),('H6',211,30,4),('H6',212,30,5),('H6',213,30,6),('H6',214,31,1),('H6',215,31,2),('H6',216,31,3),('H6',217,31,4),('H6',218,32,1),('H6',219,32,2),('H6',220,32,3),('H6',221,32,4),('H6',222,33,1),('H6',223,33,2),('H6',224,33,3),('H6',225,33,4),('H6',226,34,1),('H6',227,34,2),('H6',228,34,3),('H6',229,34,4),('H6',230,34,5),('H6',231,34,6),('H6',232,34,7),('H6',233,34,8),('H6',234,34,9),('H6',235,34,10),('H6',236,34,11),('H6',237,35,1),('H6',238,35,2),('H6',239,35,3),('H6',240,35,4),('H6',241,35,5),('H6',242,35,6),('H6',243,35,7),('H6',244,35,8),('H6',245,35,9),('H6',246,36,1),('H6',247,36,2),('H6',248,36,3),('H6',249,36,4),('H6',250,37,1),('H6',251,37,2),('H6',252,37,3),('H6',253,37,4),('H6',254,38,1),('H6',255,38,2),('H6',256,38,3),('H6',257,38,4),('H6',258,38,5),('H6',259,38,6),('H6',260,38,7),('H6',261,38,8),('H6',262,38,9),('H6',263,38,10),('H6',264,38,11),('H6',265,31,5),('H6',266,31,6),('H6',267,31,7),('H6',268,31,8),('H6',269,32,5),('H6',270,32,6),('H6',271,32,7),('H6',272,32,8),('H6',273,33,5),('H6',274,33,6),('H6',275,33,7),('H6',276,33,8),('H6',277,34,12),('H6',278,34,13),('H6',279,34,14),('H6',280,34,15),('H6',281,34,16),('H6',282,34,17),('H6',283,34,18),('H6',284,34,19),('H6',285,34,20),('H6',286,34,21),('H6',287,34,22),('H6',288,35,10),('H6',289,35,11),('H6',290,35,12),('H6',291,35,13),('H6',292,35,14),('H6',293,35,15),('H6',294,35,16),('H6',295,35,17),('H6',296,35,18),('H6',297,36,5),('H6',298,36,6),('H6',299,36,7),('H6',300,36,8),('H6',301,37,5),('H6',302,37,6),('H6',303,37,7),('H6',304,37,8),('H6',305,38,12),('H6',306,38,13),('H6',307,38,14),('H6',308,38,15),('H6',309,38,16),('H6',310,38,17),('H6',311,38,18),('H6',312,38,19),('H6',313,38,20),('H6',314,38,21),('H6',315,38,22),('H6',316,10,6),('H6',317,39,1),('H6',318,39,2),('H6',319,39,3),('H6',320,39,4),('H6',321,39,5),('H6',322,39,6),('H6',323,2,2),('H6',324,40,1),('H6',325,40,2),('H6',326,40,3),('H6',327,41,1),('H6',328,41,2),('H6',331,29,4),('H6',332,29,5),('H6',333,29,6),('H6',334,30,7),('H6',335,30,8),('H6',336,30,9),('H6',337,30,10),('H6',338,30,11),('H6',339,30,12),('H6',340,31,9),('H6',341,31,10),('H6',342,31,11),('H6',343,31,12),('H6',344,32,9),('H6',345,32,10),('H6',346,32,11),('H6',347,32,12),('H6',348,33,9),('H6',349,33,10),('H6',350,33,11),('H6',351,33,12),('H6',352,34,23),('H6',353,34,24),('H6',354,34,25),('H6',355,34,26),('H6',356,34,27),('H6',357,34,28),('H6',358,34,29),('H6',359,34,30),('H6',360,34,31),('H6',361,34,32),('H6',362,34,33),('H6',363,35,19),('H6',364,35,20),('H6',365,35,21),('H6',366,35,22),('H6',367,35,23),('H6',368,35,24),('H6',369,35,25),('H6',370,35,26),('H6',371,35,27),('H6',372,36,9),('H6',373,36,10),('H6',374,36,11),('H6',375,36,12),('H6',376,37,9),('H6',377,37,10),('H6',378,37,11),('H6',379,37,12),('H6',380,38,23),('H6',381,38,24),('H6',382,38,25),('H6',383,38,26),('H6',384,38,27),('H6',385,38,28),('H6',386,38,29),('H6',387,38,30),('H6',388,38,31),('H6',389,38,32),('H6',390,38,33),('H6',391,31,13),('H6',392,31,14),('H6',393,31,15),('H6',394,31,16),('H6',395,32,13),('H6',396,32,14),('H6',397,32,15),('H6',398,32,16),('H6',399,33,13),('H6',400,33,14),('H6',401,33,15),('H6',402,33,16),('H6',403,34,34),('H6',404,34,35),('H6',405,34,36),('H6',406,34,37),('H6',407,34,38),('H6',408,34,39),('H6',409,34,40),('H6',410,34,41),('H6',411,34,42),('H6',412,34,43),('H6',413,34,44),('H6',414,35,28),('H6',415,35,29),('H6',416,35,30),('H6',417,35,31),('H6',418,35,32),('H6',419,35,33),('H6',420,35,34),('H6',421,35,35),('H6',422,35,36),('H6',423,36,13),('H6',424,36,14),('H6',425,36,15),('H6',426,36,16),('H6',427,37,13),('H6',428,37,14),('H6',429,37,15),('H6',430,37,16),('H6',431,38,34),('H6',432,38,35),('H6',433,38,36),('H6',434,38,37),('H6',435,38,38),('H6',436,38,39),('H6',437,38,40),('H6',438,38,41),('H6',439,38,42),('H6',440,38,43),('H6',441,38,44),('H6',442,31,17),('H6',443,31,18),('H6',444,31,19),('H6',445,31,20),('H6',446,32,17),('H6',447,32,18),('H6',448,32,19),('H6',449,32,20),('H6',450,33,17),('H6',451,33,18),('H6',452,33,19),('H6',453,33,20),('H6',454,34,45),('H6',455,34,46),('H6',456,34,47),('H6',457,34,48),('H6',458,34,49),('H6',459,34,50),('H6',460,34,51),('H6',461,34,52),('H6',462,34,53),('H6',463,34,54),('H6',464,34,55),('H6',465,35,37),('H6',466,35,38),('H6',467,35,39),('H6',468,35,40),('H6',469,35,41),('H6',470,35,42),('H6',471,35,43),('H6',472,35,44),('H6',473,35,45),('H6',474,36,17),('H6',475,36,18),('H6',476,36,19),('H6',477,36,20),('H6',478,37,17),('H6',479,37,18),('H6',480,37,19),('H6',481,37,20),('H6',482,38,45),('H6',483,38,46),('H6',484,38,47),('H6',485,38,48),('H6',486,38,49),('H6',487,38,50),('H6',488,38,51),('H6',489,38,52),('H6',490,38,53),('H6',491,38,54),('H6',492,38,55),('H6',493,31,21),('H6',494,31,22),('H6',495,31,23),('H6',496,31,24),('H6',497,32,21),('H6',498,32,22),('H6',499,32,23),('H6',500,32,24),('H6',501,33,21),('H6',502,33,22),('H6',503,33,23),('H6',504,33,24),('H6',505,34,56),('H6',506,34,57),('H6',507,34,58),('H6',508,34,59),('H6',509,34,60),('H6',510,34,61),('H6',511,34,62),('H6',512,34,63),('H6',513,34,64),('H6',514,34,65),('H6',515,34,66),('H6',516,35,46),('H6',517,35,47),('H6',518,35,48),('H6',519,35,49),('H6',520,35,50),('H6',521,35,51),('H6',522,35,52),('H6',523,35,53),('H6',524,35,54),('H6',525,36,21),('H6',526,36,22),('H6',527,36,23),('H6',528,36,24),('H6',529,37,21),('H6',530,37,22),('H6',531,37,23),('H6',532,37,24),('H6',533,38,56),('H6',534,38,57),('H6',535,38,58),('H6',536,38,59),('H6',537,38,60),('H6',538,38,61),('H6',539,38,62),('H6',540,38,63),('H6',541,38,64),('H6',542,38,65),('H6',543,38,66),('H6',544,31,25),('H6',545,31,26),('H6',546,31,27),('H6',547,31,28),('H6',548,32,25),('H6',549,32,26),('H6',550,32,27),('H6',551,32,28),('H6',552,33,25),('H6',553,33,26),('H6',554,33,27),('H6',555,33,28),('H6',556,34,67),('H6',557,34,68),('H6',558,34,69),('H6',559,34,70),('H6',560,34,71),('H6',561,34,72),('H6',562,34,73),('H6',563,34,74),('H6',564,34,75),('H6',565,34,76),('H6',566,34,77),('H6',567,35,55),('H6',568,35,56),('H6',569,35,57),('H6',570,35,58),('H6',571,35,59),('H6',572,35,60),('H6',573,35,61),('H6',574,35,62),('H6',575,35,63),('H6',576,36,25),('H6',577,36,26),('H6',578,36,27),('H6',579,36,28),('H6',580,37,25),('H6',581,37,26),('H6',582,37,27),('H6',583,37,28),('H6',584,38,67),('H6',585,38,68),('H6',586,38,69),('H6',587,38,70),('H6',588,38,71),('H6',589,38,72),('H6',590,38,73),('H6',591,38,74),('H6',592,38,75),('H6',593,38,76),('H6',594,38,77);
/*!40000 ALTER TABLE `jo_role2picklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_role2profile`
--

DROP TABLE IF EXISTS `jo_role2profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_role2profile` (
  `roleid` varchar(255) NOT NULL,
  `profileid` int(11) NOT NULL,
  PRIMARY KEY (`roleid`,`profileid`),
  KEY `role2profile_roleid_profileid_idx` (`roleid`,`profileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_role2profile`
--

LOCK TABLES `jo_role2profile` WRITE;
/*!40000 ALTER TABLE `jo_role2profile` DISABLE KEYS */;
INSERT INTO `jo_role2profile` VALUES ('H2',1),('H3',9),('H4',8),('H5',7),('H6',6);
/*!40000 ALTER TABLE `jo_role2profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_rollupcomments_settings`
--

DROP TABLE IF EXISTS `jo_rollupcomments_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_rollupcomments_settings` (
  `rollupid` int(19) NOT NULL AUTO_INCREMENT,
  `userid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `rollup_status` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rollupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_rollupcomments_settings`
--

LOCK TABLES `jo_rollupcomments_settings` WRITE;
/*!40000 ALTER TABLE `jo_rollupcomments_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_rollupcomments_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_rowheight`
--

DROP TABLE IF EXISTS `jo_rowheight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_rowheight` (
  `rowheightid` int(11) NOT NULL AUTO_INCREMENT,
  `rowheight` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`rowheightid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_rowheight`
--

LOCK TABLES `jo_rowheight` WRITE;
/*!40000 ALTER TABLE `jo_rowheight` DISABLE KEYS */;
INSERT INTO `jo_rowheight` VALUES (1,'wide',0,1),(2,'medium',1,1),(3,'narrow',2,1);
/*!40000 ALTER TABLE `jo_rowheight` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sales_stage`
--

DROP TABLE IF EXISTS `jo_sales_stage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sales_stage` (
  `sales_stage_id` int(19) NOT NULL AUTO_INCREMENT,
  `sales_stage` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`sales_stage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sales_stage`
--

LOCK TABLES `jo_sales_stage` WRITE;
/*!40000 ALTER TABLE `jo_sales_stage` DISABLE KEYS */;
INSERT INTO `jo_sales_stage` VALUES (1,'Prospecting',1,145,0,NULL),(2,'Qualification',1,146,1,NULL),(3,'Needs Analysis',1,147,2,NULL),(4,'Value Proposition',1,148,3,NULL),(5,'Id. Decision Makers',1,149,4,NULL),(6,'Perception Analysis',1,150,5,NULL),(7,'Proposal or Price Quote',1,151,6,NULL),(8,'Negotiation or Review',1,152,7,NULL),(9,'Closed Won',0,153,8,NULL),(10,'Closed Lost',0,154,9,NULL);
/*!40000 ALTER TABLE `jo_sales_stage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salesmanactivityrel`
--

DROP TABLE IF EXISTS `jo_salesmanactivityrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salesmanactivityrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `activityid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`activityid`),
  KEY `salesmanactivityrel_activityid_idx` (`activityid`),
  KEY `salesmanactivityrel_smid_idx` (`smid`),
  CONSTRAINT `fk_2_jo_salesmanactivityrel` FOREIGN KEY (`smid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salesmanactivityrel`
--

LOCK TABLES `jo_salesmanactivityrel` WRITE;
/*!40000 ALTER TABLE `jo_salesmanactivityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_salesmanactivityrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salesmanattachmentsrel`
--

DROP TABLE IF EXISTS `jo_salesmanattachmentsrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salesmanattachmentsrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `attachmentsid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`attachmentsid`),
  KEY `salesmanattachmentsrel_smid_idx` (`smid`),
  KEY `salesmanattachmentsrel_attachmentsid_idx` (`attachmentsid`),
  CONSTRAINT `fk_2_jo_salesmanattachmentsrel` FOREIGN KEY (`attachmentsid`) REFERENCES `jo_attachments` (`attachmentsid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salesmanattachmentsrel`
--

LOCK TABLES `jo_salesmanattachmentsrel` WRITE;
/*!40000 ALTER TABLE `jo_salesmanattachmentsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_salesmanattachmentsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salesmanticketrel`
--

DROP TABLE IF EXISTS `jo_salesmanticketrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salesmanticketrel` (
  `smid` int(19) NOT NULL DEFAULT '0',
  `id` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smid`,`id`),
  KEY `salesmanticketrel_smid_idx` (`smid`),
  KEY `salesmanticketrel_id_idx` (`id`),
  CONSTRAINT `fk_2_jo_salesmanticketrel` FOREIGN KEY (`smid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salesmanticketrel`
--

LOCK TABLES `jo_salesmanticketrel` WRITE;
/*!40000 ALTER TABLE `jo_salesmanticketrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_salesmanticketrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salesorder`
--

DROP TABLE IF EXISTS `jo_salesorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salesorder` (
  `salesorderid` int(19) NOT NULL DEFAULT '0',
  `subject` varchar(100) DEFAULT NULL,
  `potentialid` int(19) DEFAULT NULL,
  `customerno` varchar(100) DEFAULT NULL,
  `salesorder_no` varchar(100) DEFAULT NULL,
  `quoteid` int(19) DEFAULT NULL,
  `vendorterms` varchar(100) DEFAULT NULL,
  `contactid` int(19) DEFAULT NULL,
  `vendorid` int(19) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `carrier` varchar(200) DEFAULT NULL,
  `pending` varchar(200) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adjustment` decimal(25,8) DEFAULT NULL,
  `salescommission` decimal(25,3) DEFAULT NULL,
  `exciseduty` decimal(25,3) DEFAULT NULL,
  `total` decimal(25,8) DEFAULT NULL,
  `subtotal` decimal(25,8) DEFAULT NULL,
  `taxtype` varchar(25) DEFAULT NULL,
  `discount_percent` decimal(25,3) DEFAULT NULL,
  `discount_amount` decimal(25,8) DEFAULT NULL,
  `s_h_amount` decimal(25,8) DEFAULT NULL,
  `accountid` int(19) DEFAULT NULL,
  `terms_conditions` text,
  `purchaseorder` varchar(200) DEFAULT NULL,
  `sostatus` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `conversion_rate` decimal(10,3) NOT NULL DEFAULT '1.000',
  `enable_recurring` int(11) DEFAULT '0',
  `compound_taxes_info` text,
  `pre_tax_total` decimal(25,8) DEFAULT NULL,
  `s_h_percent` int(11) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  `region_id` int(19) DEFAULT NULL,
  PRIMARY KEY (`salesorderid`),
  KEY `salesorder_vendorid_idx` (`vendorid`),
  KEY `salesorder_contactid_idx` (`contactid`),
  CONSTRAINT `fk_3_jo_salesorder` FOREIGN KEY (`vendorid`) REFERENCES `jo_vendor` (`vendorid`) ON DELETE CASCADE,
  CONSTRAINT `fk_crmid_jo_salesorder` FOREIGN KEY (`salesorderid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salesorder`
--

LOCK TABLES `jo_salesorder` WRITE;
/*!40000 ALTER TABLE `jo_salesorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_salesorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salesordercf`
--

DROP TABLE IF EXISTS `jo_salesordercf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salesordercf` (
  `salesorderid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesorderid`),
  CONSTRAINT `fk_1_jo_salesordercf` FOREIGN KEY (`salesorderid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salesordercf`
--

LOCK TABLES `jo_salesordercf` WRITE;
/*!40000 ALTER TABLE `jo_salesordercf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_salesordercf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_salutationtype`
--

DROP TABLE IF EXISTS `jo_salutationtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_salutationtype` (
  `salutationid` int(19) NOT NULL AUTO_INCREMENT,
  `salutationtype` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`salutationid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_salutationtype`
--

LOCK TABLES `jo_salutationtype` WRITE;
/*!40000 ALTER TABLE `jo_salutationtype` DISABLE KEYS */;
INSERT INTO `jo_salutationtype` VALUES (2,'Mr.',1,156,1,NULL),(3,'Ms.',1,157,2,NULL),(4,'Mrs.',1,158,3,NULL),(5,'Dr.',1,159,4,NULL),(6,'Prof.',1,160,5,NULL);
/*!40000 ALTER TABLE `jo_salutationtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_scheduled_reports`
--

DROP TABLE IF EXISTS `jo_scheduled_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_scheduled_reports` (
  `reportid` int(11) NOT NULL,
  `recipients` text,
  `schedule` text,
  `format` varchar(10) DEFAULT NULL,
  `next_trigger_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_scheduled_reports`
--

LOCK TABLES `jo_scheduled_reports` WRITE;
/*!40000 ALTER TABLE `jo_scheduled_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_scheduled_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_schedulereports`
--

DROP TABLE IF EXISTS `jo_schedulereports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_schedulereports` (
  `reportid` int(10) DEFAULT NULL,
  `scheduleid` int(3) DEFAULT NULL,
  `recipients` text,
  `schdate` varchar(20) DEFAULT NULL,
  `schtime` time DEFAULT NULL,
  `schdayoftheweek` varchar(100) DEFAULT NULL,
  `schdayofthemonth` varchar(100) DEFAULT NULL,
  `schannualdates` varchar(500) DEFAULT NULL,
  `specificemails` varchar(500) DEFAULT NULL,
  `next_trigger_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fileformat` varchar(10) DEFAULT 'CSV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_schedulereports`
--

LOCK TABLES `jo_schedulereports` WRITE;
/*!40000 ALTER TABLE `jo_schedulereports` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_schedulereports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_seactivityrel`
--

DROP TABLE IF EXISTS `jo_seactivityrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_seactivityrel` (
  `crmid` int(19) NOT NULL,
  `activityid` int(19) NOT NULL,
  PRIMARY KEY (`crmid`,`activityid`),
  KEY `seactivityrel_activityid_idx` (`activityid`),
  KEY `seactivityrel_crmid_idx` (`crmid`),
  CONSTRAINT `fk_2_jo_seactivityrel` FOREIGN KEY (`crmid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_seactivityrel`
--

LOCK TABLES `jo_seactivityrel` WRITE;
/*!40000 ALTER TABLE `jo_seactivityrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_seactivityrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_seattachmentsrel`
--

DROP TABLE IF EXISTS `jo_seattachmentsrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_seattachmentsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `attachmentsid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`attachmentsid`),
  KEY `seattachmentsrel_attachmentsid_idx` (`attachmentsid`),
  KEY `seattachmentsrel_crmid_idx` (`crmid`),
  KEY `seattachmentsrel_attachmentsid_crmid_idx` (`attachmentsid`,`crmid`),
  CONSTRAINT `fk_2_jo_seattachmentsrel` FOREIGN KEY (`crmid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_seattachmentsrel`
--

LOCK TABLES `jo_seattachmentsrel` WRITE;
/*!40000 ALTER TABLE `jo_seattachmentsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_seattachmentsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_selectcolumn`
--

DROP TABLE IF EXISTS `jo_selectcolumn`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_selectcolumn` (
  `queryid` int(19) NOT NULL,
  `columnindex` int(11) NOT NULL DEFAULT '0',
  `columnname` varchar(250) DEFAULT '',
  PRIMARY KEY (`queryid`,`columnindex`),
  KEY `selectcolumn_queryid_idx` (`queryid`),
  CONSTRAINT `fk_1_jo_selectcolumn` FOREIGN KEY (`queryid`) REFERENCES `jo_selectquery` (`queryid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_selectcolumn`
--

LOCK TABLES `jo_selectcolumn` WRITE;
/*!40000 ALTER TABLE `jo_selectcolumn` DISABLE KEYS */;
INSERT INTO `jo_selectcolumn` VALUES (1,0,'jo_contactdetails:firstname:Contacts_First_Name:firstname:V'),(1,1,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(1,2,'jo_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),(1,3,'jo_contactdetails:accountid:Contacts_Account_Name:account_id:V'),(1,4,'jo_account:industry:Accounts_industry:industry:V'),(1,5,'jo_contactdetails:email:Contacts_Email:email:E'),(2,0,'jo_contactdetails:firstname:Contacts_First_Name:firstname:V'),(2,1,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(2,2,'jo_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),(2,3,'jo_contactdetails:accountid:Contacts_Account_Name:account_id:V'),(2,4,'jo_account:industry:Accounts_industry:industry:V'),(2,5,'jo_contactdetails:email:Contacts_Email:email:E'),(3,0,'jo_contactdetails:firstname:Contacts_First_Name:firstname:V'),(3,1,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(3,2,'jo_contactdetails:accountid:Contacts_Account_Name:account_id:V'),(3,3,'jo_contactdetails:email:Contacts_Email:email:E'),(3,4,'jo_potential:potentialname:Potentials_Potential_Name:potentialname:V'),(3,5,'jo_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),(4,0,'jo_leaddetails:firstname:Leads_First_Name:firstname:V'),(4,1,'jo_leaddetails:lastname:Leads_Last_Name:lastname:V'),(4,2,'jo_leaddetails:company:Leads_Company:company:V'),(4,3,'jo_leaddetails:email:Leads_Email:email:E'),(4,4,'jo_leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),(5,0,'jo_leaddetails:firstname:Leads_First_Name:firstname:V'),(5,1,'jo_leaddetails:lastname:Leads_Last_Name:lastname:V'),(5,2,'jo_leaddetails:company:Leads_Company:company:V'),(5,3,'jo_leaddetails:email:Leads_Email:email:E'),(5,4,'jo_leaddetails:leadsource:Leads_Lead_Source:leadsource:V'),(5,5,'jo_leaddetails:leadstatus:Leads_Lead_Status:leadstatus:V'),(6,0,'jo_potential:potentialname:Potentials_Potential_Name:potentialname:V'),(6,1,'jo_potential:amount:Potentials_Amount:amount:N'),(6,2,'jo_potential:potentialtype:Potentials_Type:opportunity_type:V'),(6,3,'jo_potential:leadsource:Potentials_Lead_Source:leadsource:V'),(6,4,'jo_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),(7,0,'jo_potential:potentialname:Potentials_Potential_Name:potentialname:V'),(7,1,'jo_potential:amount:Potentials_Amount:amount:N'),(7,2,'jo_potential:potentialtype:Potentials_Type:opportunity_type:V'),(7,3,'jo_potential:leadsource:Potentials_Lead_Source:leadsource:V'),(7,4,'jo_potential:sales_stage:Potentials_Sales_Stage:sales_stage:V'),(8,0,'jo_activity:subject:Calendar_Subject:subject:V'),(8,1,'jo_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I'),(8,2,'jo_activity:status:Calendar_Status:taskstatus:V'),(8,3,'jo_activity:priority:Calendar_Priority:taskpriority:V'),(8,4,'jo_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),(9,0,'jo_activity:subject:Calendar_Subject:subject:V'),(9,1,'jo_contactdetailsCalendar:lastname:Calendar_Contact_Name:contact_id:I'),(9,2,'jo_activity:status:Calendar_Status:taskstatus:V'),(9,3,'jo_activity:priority:Calendar_Priority:taskpriority:V'),(9,4,'jo_usersCalendar:user_name:Calendar_Assigned_To:assigned_user_id:V'),(10,0,'jo_troubletickets:title:HelpDesk_Title:ticket_title:V'),(10,1,'jo_troubletickets:status:HelpDesk_Status:ticketstatus:V'),(10,2,'jo_products:productname:Products_Product_Name:productname:V'),(10,3,'jo_products:discontinued:Products_Product_Active:discontinued:V'),(10,4,'jo_products:productcategory:Products_Product_Category:productcategory:V'),(10,5,'jo_products:manufacturer:Products_Manufacturer:manufacturer:V'),(11,0,'jo_troubletickets:title:HelpDesk_Title:ticket_title:V'),(11,1,'jo_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V'),(11,2,'jo_troubletickets:severity:HelpDesk_Severity:ticketseverities:V'),(11,3,'jo_troubletickets:status:HelpDesk_Status:ticketstatus:V'),(11,4,'jo_troubletickets:category:HelpDesk_Category:ticketcategories:V'),(11,5,'jo_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),(12,0,'jo_troubletickets:title:HelpDesk_Title:ticket_title:V'),(12,1,'jo_troubletickets:priority:HelpDesk_Priority:ticketpriorities:V'),(12,2,'jo_troubletickets:severity:HelpDesk_Severity:ticketseverities:V'),(12,3,'jo_troubletickets:status:HelpDesk_Status:ticketstatus:V'),(12,4,'jo_troubletickets:category:HelpDesk_Category:ticketcategories:V'),(12,5,'jo_usersHelpDesk:user_name:HelpDesk_Assigned_To:assigned_user_id:V'),(13,0,'jo_products:productname:Products_Product_Name:productname:V'),(13,1,'jo_products:productcode:Products_Product_Code:productcode:V'),(13,2,'jo_products:discontinued:Products_Product_Active:discontinued:V'),(13,3,'jo_products:productcategory:Products_Product_Category:productcategory:V'),(13,4,'jo_products:website:Products_Website:website:V'),(13,5,'jo_vendorRelProducts:vendorname:Products_Vendor_Name:vendor_id:I'),(13,6,'jo_products:mfr_part_no:Products_Mfr_PartNo:mfr_part_no:V'),(14,0,'jo_products:productname:Products_Product_Name:productname:V'),(14,1,'jo_products:manufacturer:Products_Manufacturer:manufacturer:V'),(14,2,'jo_products:productcategory:Products_Product_Category:productcategory:V'),(14,3,'jo_contactdetails:firstname:Contacts_First_Name:firstname:V'),(14,4,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(14,5,'jo_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),(15,0,'jo_quotes:subject:Quotes_Subject:subject:V'),(15,1,'jo_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I'),(15,2,'jo_quotes:quotestage:Quotes_Quote_Stage:quotestage:V'),(15,3,'jo_quotes:contactid:Quotes_Contact_Name:contact_id:V'),(15,4,'jo_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I'),(15,5,'jo_accountQuotes:accountname:Quotes_Account_Name:account_id:I'),(16,0,'jo_quotes:subject:Quotes_Subject:subject:V'),(16,1,'jo_potentialRelQuotes:potentialname:Quotes_Potential_Name:potential_id:I'),(16,2,'jo_quotes:quotestage:Quotes_Quote_Stage:quotestage:V'),(16,3,'jo_quotes:contactid:Quotes_Contact_Name:contact_id:V'),(16,4,'jo_usersRel1:user_name:Quotes_Inventory_Manager:assigned_user_id1:I'),(16,5,'jo_accountQuotes:accountname:Quotes_Account_Name:account_id:I'),(16,6,'jo_quotes:carrier:Quotes_Carrier:carrier:V'),(16,7,'jo_quotes:shipping:Quotes_Shipping:shipping:V'),(17,0,'jo_purchaseorder:subject:PurchaseOrder_Subject:subject:V'),(17,1,'jo_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I'),(17,2,'jo_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V'),(17,3,'jo_contactdetails:firstname:Contacts_First_Name:firstname:V'),(17,4,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(17,5,'jo_contactsubdetails:leadsource:Contacts_Lead_Source:leadsource:V'),(17,6,'jo_contactdetails:email:Contacts_Email:email:E'),(18,0,'jo_purchaseorder:subject:PurchaseOrder_Subject:subject:V'),(18,1,'jo_vendorRelPurchaseOrder:vendorname:PurchaseOrder_Vendor_Name:vendor_id:I'),(18,2,'jo_purchaseorder:requisition_no:PurchaseOrder_Requisition_No:requisition_no:V'),(18,3,'jo_purchaseorder:tracking_no:PurchaseOrder_Tracking_Number:tracking_no:V'),(18,4,'jo_contactdetailsPurchaseOrder:lastname:PurchaseOrder_Contact_Name:contact_id:I'),(18,5,'jo_purchaseorder:carrier:PurchaseOrder_Carrier:carrier:V'),(18,6,'jo_purchaseorder:salescommission:PurchaseOrder_Sales_Commission:salescommission:N'),(18,7,'jo_purchaseorder:exciseduty:PurchaseOrder_Excise_Duty:exciseduty:N'),(18,8,'jo_usersPurchaseOrder:user_name:PurchaseOrder_Assigned_To:assigned_user_id:V'),(19,0,'jo_invoice:subject:Invoice_Subject:subject:V'),(19,1,'jo_invoice:salesorderid:Invoice_Sales_Order:salesorder_id:I'),(19,2,'jo_invoice:customerno:Invoice_Customer_No:customerno:V'),(19,3,'jo_invoice:exciseduty:Invoice_Excise_Duty:exciseduty:N'),(19,4,'jo_invoice:salescommission:Invoice_Sales_Commission:salescommission:N'),(19,5,'jo_accountInvoice:accountname:Invoice_Account_Name:account_id:I'),(20,0,'jo_salesorder:subject:SalesOrder_Subject:subject:V'),(20,1,'jo_quotesSalesOrder:subject:SalesOrder_Quote_Name:quote_id:I'),(20,2,'jo_contactdetailsSalesOrder:lastname:SalesOrder_Contact_Name:contact_id:I'),(20,3,'jo_salesorder:duedate:SalesOrder_Due_Date:duedate:D'),(20,4,'jo_salesorder:carrier:SalesOrder_Carrier:carrier:V'),(20,5,'jo_salesorder:sostatus:SalesOrder_Status:sostatus:V'),(20,6,'jo_accountSalesOrder:accountname:SalesOrder_Account_Name:account_id:I'),(20,7,'jo_salesorder:salescommission:SalesOrder_Sales_Commission:salescommission:N'),(20,8,'jo_salesorder:exciseduty:SalesOrder_Excise_Duty:exciseduty:N'),(20,9,'jo_usersSalesOrder:user_name:SalesOrder_Assigned_To:assigned_user_id:V'),(21,0,'jo_campaign:campaignname:Campaigns_Campaign_Name:campaignname:V'),(21,1,'jo_campaign:campaigntype:Campaigns_Campaign_Type:campaigntype:V'),(21,2,'jo_campaign:targetaudience:Campaigns_Target_Audience:targetaudience:V'),(21,3,'jo_campaign:budgetcost:Campaigns_Budget_Cost:budgetcost:I'),(21,4,'jo_campaign:actualcost:Campaigns_Actual_Cost:actualcost:I'),(21,5,'jo_campaign:expectedrevenue:Campaigns_Expected_Revenue:expectedrevenue:I'),(21,6,'jo_campaign:expectedsalescount:Campaigns_Expected_Sales_Count:expectedsalescount:N'),(21,7,'jo_campaign:actualsalescount:Campaigns_Actual_Sales_Count:actualsalescount:N'),(21,8,'jo_usersCampaigns:user_name:Campaigns_Assigned_To:assigned_user_id:V'),(22,0,'jo_contactdetails:lastname:Contacts_Last_Name:lastname:V'),(22,1,'jo_contactdetails:email:Contacts_Email:email:E'),(22,2,'jo_activity:subject:Emails_Subject:subject:V'),(22,3,'jo_email_track:access_count:Emails_Access_Count:access_count:V'),(23,0,'jo_account:accountname:Accounts_Account_Name:accountname:V'),(23,1,'jo_account:phone:Accounts_Phone:phone:V'),(23,2,'jo_account:email1:Accounts_Email:email1:E'),(23,3,'jo_activity:subject:Emails_Subject:subject:V'),(23,4,'jo_email_track:access_count:Emails_Access_Count:access_count:V'),(24,0,'jo_leaddetails:lastname:Leads_Last_Name:lastname:V'),(24,1,'jo_leaddetails:company:Leads_Company:company:V'),(24,2,'jo_leaddetails:email:Leads_Email:email:E'),(24,3,'jo_activity:subject:Emails_Subject:subject:V'),(24,4,'jo_email_track:access_count:Emails_Access_Count:access_count:V'),(25,0,'jo_vendor:vendorname:Vendors_Vendor_Name:vendorname:V'),(25,1,'jo_vendor:glacct:Vendors_GL_Account:glacct:V'),(25,2,'jo_vendor:email:Vendors_Email:email:E'),(25,3,'jo_activity:subject:Emails_Subject:subject:V'),(25,4,'jo_email_track:access_count:Emails_Access_Count:access_count:V');
/*!40000 ALTER TABLE `jo_selectcolumn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_selectquery`
--

DROP TABLE IF EXISTS `jo_selectquery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_selectquery` (
  `queryid` int(19) NOT NULL,
  `startindex` int(19) DEFAULT '0',
  `numofobjects` int(19) DEFAULT '0',
  PRIMARY KEY (`queryid`),
  KEY `selectquery_queryid_idx` (`queryid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_selectquery`
--

LOCK TABLES `jo_selectquery` WRITE;
/*!40000 ALTER TABLE `jo_selectquery` DISABLE KEYS */;
INSERT INTO `jo_selectquery` VALUES (1,0,0),(2,0,0),(3,0,0),(4,0,0),(5,0,0),(6,0,0),(7,0,0),(8,0,0),(9,0,0),(10,0,0),(11,0,0),(12,0,0),(13,0,0),(14,0,0),(15,0,0),(16,0,0),(17,0,0),(18,0,0),(19,0,0),(20,0,0),(21,0,0),(22,0,0),(23,0,0),(24,0,0),(25,0,0);
/*!40000 ALTER TABLE `jo_selectquery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_senotesrel`
--

DROP TABLE IF EXISTS `jo_senotesrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_senotesrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `notesid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`notesid`),
  KEY `senotesrel_notesid_idx` (`notesid`),
  KEY `senotesrel_crmid_idx` (`crmid`),
  CONSTRAINT `fk1_crmid` FOREIGN KEY (`crmid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE,
  CONSTRAINT `fk_2_jo_senotesrel` FOREIGN KEY (`notesid`) REFERENCES `jo_notes` (`notesid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_senotesrel`
--

LOCK TABLES `jo_senotesrel` WRITE;
/*!40000 ALTER TABLE `jo_senotesrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_senotesrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_seproductsrel`
--

DROP TABLE IF EXISTS `jo_seproductsrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_seproductsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `productid` int(19) NOT NULL DEFAULT '0',
  `setype` varchar(30) NOT NULL,
  `quantity` int(19) DEFAULT '1',
  PRIMARY KEY (`crmid`,`productid`),
  KEY `seproductsrel_productid_idx` (`productid`),
  KEY `seproductrel_crmid_idx` (`crmid`),
  CONSTRAINT `fk_2_jo_seproductsrel` FOREIGN KEY (`productid`) REFERENCES `jo_products` (`productid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_seproductsrel`
--

LOCK TABLES `jo_seproductsrel` WRITE;
/*!40000 ALTER TABLE `jo_seproductsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_seproductsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_service`
--

DROP TABLE IF EXISTS `jo_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_service` (
  `serviceid` int(11) NOT NULL,
  `service_no` varchar(100) NOT NULL,
  `servicename` varchar(50) NOT NULL,
  `servicecategory` varchar(200) DEFAULT NULL,
  `qty_per_unit` decimal(11,2) DEFAULT '0.00',
  `unit_price` decimal(25,8) DEFAULT NULL,
  `sales_start_date` date DEFAULT NULL,
  `sales_end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `discontinued` int(1) NOT NULL DEFAULT '0',
  `service_usageunit` varchar(200) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `taxclass` varchar(200) DEFAULT NULL,
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `commissionrate` decimal(7,3) DEFAULT NULL,
  `purchase_cost` decimal(27,8) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`serviceid`),
  CONSTRAINT `fk_1_jo_service` FOREIGN KEY (`serviceid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_service`
--

LOCK TABLES `jo_service` WRITE;
/*!40000 ALTER TABLE `jo_service` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_service_usageunit`
--

DROP TABLE IF EXISTS `jo_service_usageunit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_service_usageunit` (
  `service_usageunitid` int(11) NOT NULL AUTO_INCREMENT,
  `service_usageunit` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`service_usageunitid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_service_usageunit`
--

LOCK TABLES `jo_service_usageunit` WRITE;
/*!40000 ALTER TABLE `jo_service_usageunit` DISABLE KEYS */;
INSERT INTO `jo_service_usageunit` VALUES (1,'Hours',1,205,1,NULL),(2,'Days',1,206,2,NULL),(3,'Incidents',1,207,3,NULL),(4,'Hours',1,331,4,NULL),(5,'Days',1,332,5,NULL),(6,'Incidents',1,333,6,NULL);
/*!40000 ALTER TABLE `jo_service_usageunit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_servicecategory`
--

DROP TABLE IF EXISTS `jo_servicecategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_servicecategory` (
  `servicecategoryid` int(11) NOT NULL AUTO_INCREMENT,
  `servicecategory` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(11) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`servicecategoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_servicecategory`
--

LOCK TABLES `jo_servicecategory` WRITE;
/*!40000 ALTER TABLE `jo_servicecategory` DISABLE KEYS */;
INSERT INTO `jo_servicecategory` VALUES (2,'Support',1,209,2,NULL),(3,'Installation',1,210,3,NULL),(4,'Migration',1,211,4,NULL),(5,'Customization',1,212,5,NULL),(6,'Training',1,213,6,NULL),(7,'--None--',1,334,7,NULL),(8,'Support',1,335,8,NULL),(9,'Installation',1,336,9,NULL),(10,'Migration',1,337,10,NULL),(11,'Customization',1,338,11,NULL),(12,'Training',1,339,12,NULL);
/*!40000 ALTER TABLE `jo_servicecategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_servicecf`
--

DROP TABLE IF EXISTS `jo_servicecf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_servicecf` (
  `serviceid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serviceid`),
  CONSTRAINT `fk_serviceid_jo_servicecf` FOREIGN KEY (`serviceid`) REFERENCES `jo_service` (`serviceid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_servicecf`
--

LOCK TABLES `jo_servicecf` WRITE;
/*!40000 ALTER TABLE `jo_servicecf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_servicecf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_seticketsrel`
--

DROP TABLE IF EXISTS `jo_seticketsrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_seticketsrel` (
  `crmid` int(19) NOT NULL DEFAULT '0',
  `ticketid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`crmid`,`ticketid`),
  KEY `seticketsrel_crmid_idx` (`crmid`),
  KEY `seticketsrel_ticketid_idx` (`ticketid`),
  CONSTRAINT `fk_2_jo_seticketsrel` FOREIGN KEY (`ticketid`) REFERENCES `jo_troubletickets` (`ticketid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_seticketsrel`
--

LOCK TABLES `jo_seticketsrel` WRITE;
/*!40000 ALTER TABLE `jo_seticketsrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_seticketsrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_settings_blocks`
--

DROP TABLE IF EXISTS `jo_settings_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_settings_blocks` (
  `blockid` int(19) NOT NULL,
  `label` varchar(250) DEFAULT NULL,
  `sequence` int(19) DEFAULT NULL,
  PRIMARY KEY (`blockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_settings_blocks`
--

LOCK TABLES `jo_settings_blocks` WRITE;
/*!40000 ALTER TABLE `jo_settings_blocks` DISABLE KEYS */;
INSERT INTO `jo_settings_blocks` VALUES (1,'LBL_USER_MANAGEMENT',1),(4,'LBL_OTHER_SETTINGS',10),(5,'LBL_INTEGRATION',8),(6,'LBL_MODULE_MANAGER',2),(7,'LBL_AUTOMATION',3),(8,'LBL_CONFIGURATION',4),(9,'LBL_MARKETING_SALES',5),(10,'LBL_INVENTORY',6),(11,'LBL_MY_PREFERENCES',7),(12,'LBL_EXTENSIONS',9),(13,'LBL_JOFORCE',12),(14,'LBL_MARKETPLACE',11);
/*!40000 ALTER TABLE `jo_settings_blocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_settings_field`
--

DROP TABLE IF EXISTS `jo_settings_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_settings_field` (
  `fieldid` int(19) NOT NULL,
  `blockid` int(19) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `iconpath` varchar(300) DEFAULT NULL,
  `description` text,
  `linkto` text,
  `sequence` int(19) DEFAULT NULL,
  `active` int(19) DEFAULT '0',
  `pinned` int(1) DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `fk_1_jo_settings_field` (`blockid`),
  CONSTRAINT `fk_1_jo_settings_field` FOREIGN KEY (`blockid`) REFERENCES `jo_settings_blocks` (`blockid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_settings_field`
--

LOCK TABLES `jo_settings_field` WRITE;
/*!40000 ALTER TABLE `jo_settings_field` DISABLE KEYS */;
INSERT INTO `jo_settings_field` VALUES (1,1,'LBL_USERS','fa fa-user','LBL_USER_DESCRIPTION','Users/Settings/List',1,0,1),(2,1,'LBL_ROLES','fa fa-registered','LBL_ROLE_DESCRIPTION','Roles/Settings/Index',2,0,0),(4,1,'USERGROUPLIST','fa fa-users','LBL_GROUP_DESCRIPTION','Groups/Settings/List',5,0,0),(5,1,'LBL_SHARING_ACCESS','fa fa-share-alt','LBL_SHARING_ACCESS_DESCRIPTION','SharingAccess/Settings/Index',4,0,0),(6,1,'LBL_LOGIN_HISTORY_DETAILS','fa fa-history','LBL_LOGIN_HISTORY_DESCRIPTION','LoginHistory/Settings/List',6,0,0),(7,6,'VTLIB_LBL_MODULE_MANAGER','fa fa-chain','VTLIB_LBL_MODULE_MANAGER_DESCRIPTION','ModuleManager/Settings/List',1,0,1),(8,8,'LBL_PICKLIST_EDITOR','fa fa-file-text-o','LBL_PICKLIST_DESCRIPTION','Picklist/Settings/Index',6,0,1),(9,8,'LBL_PICKLIST_DEPENDENCY','fa fa-list','LBL_PICKLIST_DEPENDENCY_DESCRIPTION','PickListDependency/Settings/List',7,0,0),(10,8,'LBL_COMPANY_DETAILS','fa fa-building-o','LBL_COMPANY_DESCRIPTION','Head/Settings/CompanyDetails',1,0,0),(11,8,'LBL_MAIL_SERVER_SETTINGS','fa fa-server','LBL_MAIL_SERVER_DESCRIPTION','Head/Settings/OutgoingServerDetail',4,0,0),(12,8,'LBL_CURRENCY_SETTINGS','fa fa-usd','LBL_CURRENCY_DESCRIPTION','Currency/Settings/List',3,0,0),(13,10,'LBL_TAX_SETTINGS','fa fa-money','LBL_TAX_DESCRIPTION','Head/Settings/TaxIndex',1,0,0),(15,10,'INVENTORYTERMSANDCONDITIONS','fa fa-info-circle','LBL_INV_TANDC_DESCRIPTION','Head/Settings/TermsAndConditionsEdit',2,0,0),(16,6,'LBL_CUSTOMIZE_MODENT_NUMBER','fa fa-sort-numeric-desc','LBL_CUSTOMIZE_MODENT_NUMBER_DESCRIPTION','Head/Settings/CustomRecordNumbering',4,0,0),(17,9,'LBL_MAIL_SCANNER','fa fa-envelope-o','LBL_MAIL_SCANNER_DESCRIPTION','MailConverter/Settings/List',5,0,0),(18,7,'LBL_LIST_WORKFLOWS','fa fa-sitemap','LBL_LIST_WORKFLOWS_DESCRIPTION','Workflows/Settings/List',3,0,1),(19,8,'Configuration Editor','fa fa-pencil-square-o','LBL_CONFIG_EDITOR_DESCRIPTION','Head/Settings/ConfigEditorDetail',5,0,0),(20,7,'Scheduler','fa fa-clock-o','Allows you to Configure Cron Task','CronTasks/Settings/List',2,0,0),(21,8,'Duplicate Check','fa fa-copy','DuplicateCheck','DuplicateCheck/Settings/List',7,0,0),(22,8,'Address Lookup','fa fa-search-plus','Auto Fill the address fields in each module','AddressLookup/Settings/List',8,0,0),(23,12,'LBL_PBXMANAGER','fa fa-phone','PBXManager module Configuration','PBXManager/Settings/Index',2,0,0),(24,4,'ModTracker','set-IcoLoginHistory.gif','LBL_MODTRACKER_DESCRIPTION','ModTracker/BasicSettings/Settings/ModTracker',9,0,0),(26,7,'Webforms','fa fa-file-zip-o','LBL_WEBFORMS_DESCRIPTION','Webforms/Settings/List',1,0,0),(28,6,'LBL_EDIT_FIELDS','fa fa-codepen','LBL_LAYOUT_EDITOR_DESCRIPTION','LayoutEditor/Settings/Index',2,0,0),(29,9,'LBL_LEAD_MAPPING','fa fa-exchange','NULL','Leads/Settings/MappingDetail',1,0,1),(30,9,'LBL_OPPORTUNITY_MAPPING','fa fa-map-signs','NULL','Potentials/Settings/MappingDetail',2,0,1),(31,11,'My Preferences','fa fa-user','NULL','Users/Settings/PreferenceDetail/1',1,0,1),(32,11,'Calendar Settings','fa fa-calendar-check-o','NULL','Users/Settings/Calendar/1',2,0,1),(33,11,'LBL_MY_TAGS','fa fa-tags','NULL','Tags/Settings/List/1',3,0,1),(34,11,'LBL_MENU_MANAGEMENT','fa fa-bars','NULL','MenuManager/Settings/Index',4,0,1),(35,12,'LBL_GOOGLE','fa fa-google','NULL','Contacts/Settings/Extension/Google/Index/settings',1,0,1),(36,6,'Module Studio','fa fa-video-camera','LBL_MODULEDESIGNER_DESCRIPTION','ModuleDesigner/Settings/Index',3,0,0),(37,13,'Contributors','fa fa-plus-square','Contributors','Head/Settings/Credits',1,0,0),(38,13,'License','fa fa-exclamation-triangle','License','Head/Settings/License',2,0,0),(39,4,'Google Settings','fa fa-cogs','Google Synchronization','Google/Settings/GoogleSettings',12,1,0),(40,6,'Language Editor','fa fa-pencil','LBL_LANGUAGE_EDITOR','LanguageEditor/Settings/Index',3,0,0),(41,11,'Notifications','fa fa-bell','Notifications','Notifications/Settings/Index',5,0,0),(42,12,'Masquerade User','fa fa-street-view','Masquerade User','PortalUser/Settings/Index',6,0,0),(43,14,'ExtensionStore','joicon-inventory','ExtensionStore','ExtensionStore/Settings/ExtensionStore',7,0,0),(44, 7, 'Webhooks', 'fa fa-cog', 'LBL_WEBHOOKS_DESCRIPTION', 'Webhooks/Settings/List', 4, 0, 0);
/*!40000 ALTER TABLE `jo_settings_field` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sharedcalendar`
--

DROP TABLE IF EXISTS `jo_sharedcalendar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sharedcalendar` (
  `userid` int(19) NOT NULL,
  `sharedid` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`sharedid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sharedcalendar`
--

LOCK TABLES `jo_sharedcalendar` WRITE;
/*!40000 ALTER TABLE `jo_sharedcalendar` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_sharedcalendar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_shareduserinfo`
--

DROP TABLE IF EXISTS `jo_shareduserinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_shareduserinfo` (
  `userid` int(19) NOT NULL DEFAULT '0',
  `shareduserid` int(19) NOT NULL DEFAULT '0',
  `color` varchar(50) DEFAULT NULL,
  `visible` int(19) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_shareduserinfo`
--

LOCK TABLES `jo_shareduserinfo` WRITE;
/*!40000 ALTER TABLE `jo_shareduserinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_shareduserinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_shippingtaxinfo`
--

DROP TABLE IF EXISTS `jo_shippingtaxinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_shippingtaxinfo` (
  `taxid` int(3) NOT NULL,
  `taxname` varchar(50) DEFAULT NULL,
  `taxlabel` varchar(50) DEFAULT NULL,
  `percentage` decimal(7,3) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  `method` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `compoundon` varchar(400) DEFAULT NULL,
  `regions` text,
  PRIMARY KEY (`taxid`),
  KEY `shippingtaxinfo_taxname_idx` (`taxname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_shippingtaxinfo`
--

LOCK TABLES `jo_shippingtaxinfo` WRITE;
/*!40000 ALTER TABLE `jo_shippingtaxinfo` DISABLE KEYS */;
INSERT INTO `jo_shippingtaxinfo` VALUES (1,'shtax1','VAT',4.500,0,'Simple','Fixed','[]','[]'),(2,'shtax2','Sales',10.000,0,'Simple','Fixed','[]','[]'),(3,'shtax3','Service',12.500,0,'Simple','Fixed','[]','[]');
/*!40000 ALTER TABLE `jo_shippingtaxinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_shorturls`
--

DROP TABLE IF EXISTS `jo_shorturls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_shorturls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) DEFAULT NULL,
  `handler_path` varchar(400) DEFAULT NULL,
  `handler_class` varchar(100) DEFAULT NULL,
  `handler_function` varchar(100) DEFAULT NULL,
  `handler_data` varchar(255) DEFAULT NULL,
  `onetime` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_shorturls`
--

LOCK TABLES `jo_shorturls` WRITE;
/*!40000 ALTER TABLE `jo_shorturls` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_shorturls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_soapservice`
--

DROP TABLE IF EXISTS `jo_soapservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_soapservice` (
  `id` int(19) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `sessionid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_soapservice`
--

LOCK TABLES `jo_soapservice` WRITE;
/*!40000 ALTER TABLE `jo_soapservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_soapservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sobillads`
--

DROP TABLE IF EXISTS `jo_sobillads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sobillads` (
  `sobilladdressid` int(19) NOT NULL DEFAULT '0',
  `bill_city` varchar(30) DEFAULT NULL,
  `bill_code` varchar(30) DEFAULT NULL,
  `bill_country` varchar(30) DEFAULT NULL,
  `bill_state` varchar(30) DEFAULT NULL,
  `bill_street` varchar(250) DEFAULT NULL,
  `bill_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`sobilladdressid`),
  CONSTRAINT `fk_1_jo_sobillads` FOREIGN KEY (`sobilladdressid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sobillads`
--

LOCK TABLES `jo_sobillads` WRITE;
/*!40000 ALTER TABLE `jo_sobillads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_sobillads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_soshipads`
--

DROP TABLE IF EXISTS `jo_soshipads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_soshipads` (
  `soshipaddressid` int(19) NOT NULL DEFAULT '0',
  `ship_city` varchar(30) DEFAULT NULL,
  `ship_code` varchar(30) DEFAULT NULL,
  `ship_country` varchar(30) DEFAULT NULL,
  `ship_state` varchar(30) DEFAULT NULL,
  `ship_street` varchar(250) DEFAULT NULL,
  `ship_pobox` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`soshipaddressid`),
  CONSTRAINT `fk_1_jo_soshipads` FOREIGN KEY (`soshipaddressid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_soshipads`
--

LOCK TABLES `jo_soshipads` WRITE;
/*!40000 ALTER TABLE `jo_soshipads` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_soshipads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sostatus`
--

DROP TABLE IF EXISTS `jo_sostatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sostatus` (
  `sostatusid` int(19) NOT NULL AUTO_INCREMENT,
  `sostatus` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`sostatusid`),
  UNIQUE KEY `sostatus_sostatus_idx` (`sostatus`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sostatus`
--

LOCK TABLES `jo_sostatus` WRITE;
/*!40000 ALTER TABLE `jo_sostatus` DISABLE KEYS */;
INSERT INTO `jo_sostatus` VALUES (1,'Created',0,161,0,NULL),(2,'Approved',0,162,1,NULL),(3,'Delivered',0,163,2,NULL),(4,'Cancelled',0,164,3,NULL);
/*!40000 ALTER TABLE `jo_sostatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sostatushistory`
--

DROP TABLE IF EXISTS `jo_sostatushistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sostatushistory` (
  `historyid` int(19) NOT NULL AUTO_INCREMENT,
  `salesorderid` int(19) NOT NULL,
  `accountname` varchar(100) DEFAULT NULL,
  `total` decimal(10,0) DEFAULT NULL,
  `sostatus` varchar(200) DEFAULT NULL,
  `lastmodified` datetime DEFAULT NULL,
  PRIMARY KEY (`historyid`),
  KEY `sostatushistory_salesorderid_idx` (`salesorderid`),
  CONSTRAINT `fk_1_jo_sostatushistory` FOREIGN KEY (`salesorderid`) REFERENCES `jo_salesorder` (`salesorderid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sostatushistory`
--

LOCK TABLES `jo_sostatushistory` WRITE;
/*!40000 ALTER TABLE `jo_sostatushistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_sostatushistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_sqltimelog`
--

DROP TABLE IF EXISTS `jo_sqltimelog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_sqltimelog` (
  `id` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `data` text,
  `started` decimal(20,6) DEFAULT NULL,
  `ended` decimal(20,6) DEFAULT NULL,
  `loggedon` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_sqltimelog`
--

LOCK TABLES `jo_sqltimelog` WRITE;
/*!40000 ALTER TABLE `jo_sqltimelog` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_sqltimelog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_start_hour`
--

DROP TABLE IF EXISTS `jo_start_hour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_start_hour` (
  `start_hourid` int(11) NOT NULL AUTO_INCREMENT,
  `start_hour` varchar(200) NOT NULL,
  `sortorderid` int(11) DEFAULT NULL,
  `presence` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`start_hourid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_start_hour`
--

LOCK TABLES `jo_start_hour` WRITE;
/*!40000 ALTER TABLE `jo_start_hour` DISABLE KEYS */;
INSERT INTO `jo_start_hour` VALUES (1,'00:00',0,1),(2,'01:00',1,1),(3,'02:00',2,1),(4,'03:00',3,1),(5,'04:00',4,1),(6,'05:00',5,1),(7,'06:00',6,1),(8,'07:00',7,1),(9,'08:00',8,1),(10,'09:00',9,1),(11,'10:00',10,1),(12,'11:00',11,1),(13,'12:00',12,1),(14,'13:00',13,1),(15,'14:00',14,1),(16,'15:00',15,1),(17,'16:00',16,1),(18,'17:00',17,1),(19,'18:00',18,1),(20,'19:00',19,1),(21,'20:00',20,1),(22,'21:00',21,1),(23,'22:00',22,1),(24,'23:00',23,1);
/*!40000 ALTER TABLE `jo_start_hour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_status`
--

DROP TABLE IF EXISTS `jo_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_status` (
  `statusid` int(19) NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`statusid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_status`
--

LOCK TABLES `jo_status` WRITE;
/*!40000 ALTER TABLE `jo_status` DISABLE KEYS */;
INSERT INTO `jo_status` VALUES (1,'Active',0,1),(2,'Inactive',1,1);
/*!40000 ALTER TABLE `jo_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_systems`
--

DROP TABLE IF EXISTS `jo_tab_sequence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tab_sequence` (
  `sequenceid` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255)  NULL DEFAULT '',
  `sequence` varchar(255) NOT NULL,
  PRIMARY KEY (`sequenceid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `jo_tab_sequence` WRITE;
INSERT INTO `jo_tab_sequence` VALUES (1,'workflow_tasktypes_seq',7),(2,'workflows_seq',24),(3,'workflowtasks_entitymethod_seq',10),(4,'workflowtasks_seq',28),(5,'jo_accounttype_seq',11),(6,'jo_activity_view_seq',5),(7,'jo_activitytype_seq',3),(8,'jo_attachmentsfolder_seq',1),(9,'jo_blocks_seq',110),(10,'jo_calendar_default_activitytypes_seq',8),(11,'jo_calendar_user_activitytypes_seq',8),(12,'jo_calendarsharedtype_seq',3),(13,'jo_callduration_seq',5),(14,'jo_campaignrelstatus_seq',4),(15,'jo_campaignstatus_seq',6),(16,'jo_campaigntype_seq',13),(17,'jo_carrier_seq',5),(18,'jo_crmentity_seq',1),(19,'jo_currencies_seq',138),(20,'jo_currency_decimal_separator_seq',5),(21,'jo_currency_grouping_pattern_seq',4),(22,'jo_currency_grouping_separator_seq',5),(23,'jo_currency_info_seq',0),(24,'jo_currency_symbol_placement_seq',2),(25,'jo_customview_seq',64),(26,'jo_datashare_relatedmodules_seq',9),(27,'jo_date_format_seq',3),(28,'jo_dayoftheweek_seq',7),(29,'jo_def_org_share_seq',21),(30,'jo_default_record_view_seq',2),(31,'jo_defaultactivitytype_seq',2),(32,'jo_defaultcalendarview_seq',3),(33,'jo_defaulteventstatus_seq',3),(34,'jo_duration_minutes_seq',4),(35,'jo_emailtemplates_seq',7),(36,'jo_eventhandler_module_seq',6),(37,'jo_eventhandlers_seq',34),(38,'jo_eventstatus_seq',3),(39,'jo_expectedresponse_seq',5),(40,'jo_field_seq',802),(41,'jo_freetags_seq',1),(42,'jo_glacct_seq',9),(43,'jo_homestuff_seq',15),(44,'jo_hour_format_seq',2),(45,'jo_industry_seq',32),(46,'jo_inventory_tandc_seq',5),(47,'jo_inventorynotification_seq',3),(48,'jo_inventoryproductrel_seq',0),(49,'jo_inventorytaxinfo_seq',3),(50,'jo_invoicestatus_seq',7),(51,'jo_language_seq',16),(52,'jo_lead_view_seq',3),(53,'jo_leadsource_seq',13),(54,'jo_leadstatus_seq',12),(55,'jo_links_seq',124),(56,'jo_manufacturer_seq',4),(57,'jo_modentity_num_seq',18),(58,'jo_no_of_currency_decimals_seq',7),(59,'jo_selectquery_seq',25),(60,'jo_notification_seq',1),(61,'jo_notificationscheduler_seq',8),(62,'jo_opportunity_type_seq',3),(63,'jo_organizationdetails_seq',1),(64,'jo_othereventduration_seq',5),(65,'jo_payment_duration_seq',3),(66,'jo_pdfmaker_seq',4),(67,'jo_picklist_seq',41),(68,'jo_picklistvalues_seq',594),(69,'jo_postatus_seq',5),(70,'jo_productcategory_seq',4),(71,'jo_profile_seq',9),(72,'jo_progress_seq',77),(73,'jo_projectmilestonetype_seq',28),(74,'jo_projectpriority_seq',28),(75,'jo_projectstatus_seq',63),(76,'jo_projecttaskpriority_seq',28),(77,'jo_projecttaskprogress_seq',77),(78,'jo_projecttaskstatus_seq',6),(79,'jo_projecttasktype_seq',28),(80,'jo_projecttype_seq',28),(81,'jo_quotestage_seq',5),(82,'jo_rating_seq',6),(83,'jo_recurring_frequency_seq',6),(84,'jo_recurringtype_seq',5),(85,'jo_relatedlists_seq',200),(86,'jo_reminder_interval_seq',8),(87,'jo_role_seq',6),(88,'jo_rowheight_seq',3),(89,'jo_sales_stage_seq',10),(90,'jo_salutationtype_seq',6),(91,'jo_seactivityrel_seq',1),(92,'jo_selectquery_seq',25),(93,'jo_service_usageunit_seq',6),(94,'jo_servicecategory_seq',12),(95,'jo_settings_blocks_seq',14),(96,'jo_settings_field_seq',44),(97,'jo_shippingtaxinfo_seq',3),(98,'jo_sostatus_seq',4),(99,'jo_start_hour_seq',24),(100,'jo_status_seq',2),(101,'jo_taskpriority_seq',3),(102,'jo_taskstatus_seq',6),(103,'jo_taxclass_seq',2),(104,'jo_ticketcategories_seq',3),(105,'jo_ticketpriorities_seq',4),(106,'jo_ticketseverities_seq',4),(107,'jo_ticketstatus_seq',4),(108,'jo_time_zone_seq',96),(109,'jo_usageunit_seq',16),(110,'jo_users_seq',4),(111,'jo_version_seq',1),(112,'jo_visibility_seq',2),(113,'jo_ws_entity_fieldtype_seq',10),(114,'jo_ws_entity_seq',31),(115,'jo_ws_operation_seq',26);
UNLOCK TABLES;

--
-- Table structure for table `jo_systems`
--

DROP TABLE IF EXISTS `jo_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_systems` (
  `id` int(19) NOT NULL,
  `server` varchar(100) DEFAULT NULL,
  `server_port` int(19) DEFAULT NULL,
  `server_username` varchar(100) DEFAULT NULL,
  `server_password` varchar(100) DEFAULT NULL,
  `server_type` varchar(20) DEFAULT NULL,
  `smtp_auth` varchar(5) DEFAULT NULL,
  `server_path` varchar(256) DEFAULT NULL,
  `from_email_field` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_systems`
--

LOCK TABLES `jo_systems` WRITE;
/*!40000 ALTER TABLE `jo_systems` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_systems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tab`
--

DROP TABLE IF EXISTS `jo_tab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tab` (
  `tabid` int(19) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL,
  `presence` int(19) NOT NULL DEFAULT '1',
  `tabsequence` int(10) DEFAULT NULL,
  `tablabel` varchar(100) DEFAULT NULL,
  `modifiedby` int(19) DEFAULT NULL,
  `modifiedtime` int(19) DEFAULT NULL,
  `customized` int(19) DEFAULT NULL,
  `ownedby` int(19) DEFAULT NULL,
  `isentitytype` int(11) NOT NULL DEFAULT '1',
  `trial` int(1) NOT NULL DEFAULT '0',
  `version` varchar(10) DEFAULT NULL,
  `parent` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`tabid`),
  UNIQUE KEY `tab_name_idx` (`name`),
  KEY `tab_modifiedby_idx` (`modifiedby`),
  KEY `tab_tabid_idx` (`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tab`
--

LOCK TABLES `jo_tab` WRITE;
/*!40000 ALTER TABLE `jo_tab` DISABLE KEYS */;
INSERT INTO `jo_tab` VALUES (1,'Dashboard',0,12,'Dashboards',NULL,NULL,0,1,0,0,NULL,'Analytics'),(2,'Potentials',0,7,'Potentials',NULL,NULL,0,0,1,0,NULL,'Sales'),(3,'Home',0,1,'Home',NULL,NULL,0,1,0,0,NULL,NULL),(4,'Contacts',0,6,'Contacts',NULL,NULL,0,0,1,0,NULL,'Sales'),(6,'Accounts',0,5,'Accounts',NULL,NULL,0,0,1,0,NULL,'Sales'),(7,'Leads',0,4,'Leads',NULL,NULL,0,0,1,0,NULL,'Sales'),(8,'Documents',0,9,'Documents',NULL,NULL,0,0,1,0,NULL,'Tools'),(9,'Calendar',0,3,'Calendar',NULL,NULL,0,0,1,0,NULL,'Tools'),(10,'Emails',0,10,'Emails',NULL,NULL,0,1,1,0,NULL,'Tools'),(13,'HelpDesk',0,11,'HelpDesk',NULL,NULL,0,0,1,0,NULL,'Support'),(14,'Products',0,8,'Products',NULL,NULL,0,0,1,0,NULL,'Inventory'),(16,'Events',2,-1,'Events',NULL,NULL,0,0,1,0,NULL,NULL),(18,'Vendors',0,-1,'Vendors',NULL,NULL,0,1,1,0,NULL,'Inventory'),(19,'PriceBooks',0,-1,'PriceBooks',NULL,NULL,0,1,1,0,NULL,'Inventory'),(20,'Quotes',0,-1,'Quotes',NULL,NULL,0,0,1,0,NULL,'Sales'),(21,'PurchaseOrder',0,-1,'PurchaseOrder',NULL,NULL,0,0,1,0,NULL,'Inventory'),(22,'SalesOrder',0,-1,'SalesOrder',NULL,NULL,0,0,1,0,NULL,'Sales'),(23,'Invoice',0,-1,'Invoice',NULL,NULL,0,0,1,0,NULL,'Sales'),(25,'Reports',0,-1,'Reports',NULL,NULL,0,1,0,0,NULL,'Analytics'),(26,'Campaigns',0,-1,'Campaigns',NULL,NULL,0,0,1,0,NULL,'Marketing'),(27,'Portal',0,-1,'Portal',NULL,NULL,0,1,0,0,NULL,'Tools'),(28,'Webmails',0,-1,'Webmails',NULL,NULL,0,1,1,0,NULL,NULL),(29,'Users',0,-1,'Users',NULL,NULL,0,1,0,0,NULL,NULL),(30,'DuplicateCheck',0,-1,'Duplicate Check',NULL,NULL,0,1,0,0,NULL,NULL),(31,'AddressLookup',0,-1,'Address Lookup',NULL,NULL,0,1,0,0,NULL,NULL),(32,'PDFMaker',0,-1,'PDF Maker',NULL,NULL,0,1,0,0,NULL,NULL),(33,'EmailPlus',0,-1,'Email Plus',NULL,NULL,0,1,0,0,NULL,NULL),(34,'Import',0,-1,'Import',NULL,NULL,1,0,0,0,'1.7',''),(35,'WSAPP',0,-1,'WSAPP',NULL,NULL,1,0,0,0,'3.4.4',''),(36,'Services',0,-1,'Services',NULL,NULL,0,0,1,0,'2.6','Inventory'),(37,'PBXManager',0,-1,'PBXManager',NULL,NULL,1,0,1,0,'2.2','Tools'),(38,'ModTracker',0,-1,'ModTracker',NULL,NULL,0,0,0,0,'1.2',''),(39,'Mobile',0,-1,'Mobile',NULL,NULL,1,0,0,0,'2.0',''),(40,'CustomerPortal',0,-1,'CustomerPortal',NULL,NULL,0,0,0,0,'1.4',''),(41,'Webforms',0,-1,'Webforms',NULL,NULL,0,0,0,0,'1.6',''),(42,'ProjectMilestone',0,-1,'ProjectMilestone',NULL,NULL,0,0,1,0,'3.0','Support'),(43,'ProjectTask',0,-1,'ProjectTask',NULL,NULL,0,0,1,0,'3.1','Support'),(44,'Project',0,-1,'Project',NULL,NULL,0,0,1,0,'3.3','Support'),(45,'Google',0,-1,'Google',NULL,NULL,0,0,0,0,'1.5',''),(46,'EmailTemplates',0,-1,'Email Templates',NULL,NULL,1,0,0,0,'1.0','Tools'),(47,'ModComments',0,-1,'Comments',NULL,NULL,0,0,1,0,'2.1',''),(48,'RecycleBin',0,-1,'Recycle Bin',NULL,NULL,0,0,0,0,'1.5','Tools'),(49,'ModuleDesigner',0,-1,'Module Designer',NULL,NULL,0,0,0,0,'1.0RC',''),(50,'ExtensionStore',0,-1,'Extension Store',NULL,NULL,1,0,0,0,'1.2','Settings');
/*!40000 ALTER TABLE `jo_tab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tab_info`
--

DROP TABLE IF EXISTS `jo_tab_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tab_info` (
  `tabid` int(19) DEFAULT NULL,
  `prefname` varchar(256) DEFAULT NULL,
  `prefvalue` varchar(256) DEFAULT NULL,
  KEY `fk_1_jo_tab_info` (`tabid`),
  CONSTRAINT `fk_1_jo_tab_info` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tab_info`
--

LOCK TABLES `jo_tab_info` WRITE;
/*!40000 ALTER TABLE `jo_tab_info` DISABLE KEYS */;
INSERT INTO `jo_tab_info` VALUES (34,'jo_min_version','6.0.0rc'),(34,'jo_max_version','7.*'),(35,'jo_min_version','6.0.0rc'),(36,'jo_min_version','6.0.0rc'),(36,'jo_max_version','7.*'),(37,'jo_min_version','6.0.0'),(37,'jo_max_version','7.*'),(38,'jo_min_version','6.0.0rc'),(39,'jo_min_version','6.0.0rc'),(40,'jo_min_version','6.0.0rc'),(40,'jo_max_version','7.*'),(41,'jo_min_version','6.0.0rc'),(41,'jo_max_version','7.*'),(42,'jo_min_version','6.0.0rc'),(42,'jo_max_version','7.*'),(43,'jo_min_version','6.0.0rc'),(44,'jo_min_version','6.0.0rc'),(45,'jo_min_version','6.0.0rc'),(45,'jo_max_version','7.*'),(46,'jo_min_version','6.0.0rc'),(46,'jo_max_version','7.*'),(47,'jo_min_version','6.0.0rc'),(47,'jo_max_version','7.*'),(48,'jo_min_version','6.0.0rc'),(48,'jo_max_version','7.*'),(49,'jo_min_version','6.1.0');
/*!40000 ALTER TABLE `jo_tab_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_taskpriority`
--

DROP TABLE IF EXISTS `jo_taskpriority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_taskpriority` (
  `taskpriorityid` int(19) NOT NULL AUTO_INCREMENT,
  `taskpriority` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`taskpriorityid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_taskpriority`
--

LOCK TABLES `jo_taskpriority` WRITE;
/*!40000 ALTER TABLE `jo_taskpriority` DISABLE KEYS */;
INSERT INTO `jo_taskpriority` VALUES (1,'High',1,165,0,NULL),(2,'Medium',1,166,1,NULL),(3,'Low',1,167,2,NULL);
/*!40000 ALTER TABLE `jo_taskpriority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_taskstatus`
--

DROP TABLE IF EXISTS `jo_taskstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_taskstatus` (
  `taskstatusid` int(19) NOT NULL AUTO_INCREMENT,
  `taskstatus` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`taskstatusid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_taskstatus`
--

LOCK TABLES `jo_taskstatus` WRITE;
/*!40000 ALTER TABLE `jo_taskstatus` DISABLE KEYS */;
INSERT INTO `jo_taskstatus` VALUES (1,'Not Started',0,168,0,NULL),(2,'In Progress',0,169,1,NULL),(3,'Completed',0,170,2,NULL),(4,'Pending Input',0,171,3,NULL),(5,'Deferred',0,172,4,NULL),(6,'Planned',0,173,5,NULL);
/*!40000 ALTER TABLE `jo_taskstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_taxclass`
--

DROP TABLE IF EXISTS `jo_taxclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_taxclass` (
  `taxclassid` int(19) NOT NULL AUTO_INCREMENT,
  `taxclass` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`taxclassid`),
  UNIQUE KEY `taxclass_carrier_idx` (`taxclass`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_taxclass`
--

LOCK TABLES `jo_taxclass` WRITE;
/*!40000 ALTER TABLE `jo_taxclass` DISABLE KEYS */;
INSERT INTO `jo_taxclass` VALUES (1,'SalesTax',0,1),(2,'Vat',1,1);
/*!40000 ALTER TABLE `jo_taxclass` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_taxregions`
--

DROP TABLE IF EXISTS `jo_taxregions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_taxregions` (
  `regionid` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`regionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_taxregions`
--

LOCK TABLES `jo_taxregions` WRITE;
/*!40000 ALTER TABLE `jo_taxregions` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_taxregions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketcategories`
--

DROP TABLE IF EXISTS `jo_ticketcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketcategories` (
  `ticketcategories_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketcategories` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ticketcategories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketcategories`
--

LOCK TABLES `jo_ticketcategories` WRITE;
/*!40000 ALTER TABLE `jo_ticketcategories` DISABLE KEYS */;
INSERT INTO `jo_ticketcategories` VALUES (1,'Big Problem',1,174,0,NULL),(2,'Small Problem',1,175,1,NULL),(3,'Other Problem',1,176,2,NULL);
/*!40000 ALTER TABLE `jo_ticketcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketcf`
--

DROP TABLE IF EXISTS `jo_ticketcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketcf` (
  `ticketid` int(19) NOT NULL DEFAULT '0',
  `from_portal` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`ticketid`),
  CONSTRAINT `fk_1_jo_ticketcf` FOREIGN KEY (`ticketid`) REFERENCES `jo_troubletickets` (`ticketid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketcf`
--

LOCK TABLES `jo_ticketcf` WRITE;
/*!40000 ALTER TABLE `jo_ticketcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_ticketcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketcomments`
--

DROP TABLE IF EXISTS `jo_ticketcomments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketcomments` (
  `commentid` int(19) NOT NULL AUTO_INCREMENT,
  `ticketid` int(19) DEFAULT NULL,
  `comments` text,
  `ownerid` int(19) NOT NULL DEFAULT '0',
  `ownertype` varchar(10) DEFAULT NULL,
  `createdtime` datetime NOT NULL,
  PRIMARY KEY (`commentid`),
  KEY `ticketcomments_ticketid_idx` (`ticketid`),
  CONSTRAINT `fk_1_jo_ticketcomments` FOREIGN KEY (`ticketid`) REFERENCES `jo_troubletickets` (`ticketid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketcomments`
--

LOCK TABLES `jo_ticketcomments` WRITE;
/*!40000 ALTER TABLE `jo_ticketcomments` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_ticketcomments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketpriorities`
--

DROP TABLE IF EXISTS `jo_ticketpriorities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketpriorities` (
  `ticketpriorities_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketpriorities` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ticketpriorities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketpriorities`
--

LOCK TABLES `jo_ticketpriorities` WRITE;
/*!40000 ALTER TABLE `jo_ticketpriorities` DISABLE KEYS */;
INSERT INTO `jo_ticketpriorities` VALUES (1,'Low',1,177,0,NULL),(2,'Normal',1,178,1,NULL),(3,'High',1,179,2,NULL),(4,'Urgent',1,180,3,NULL);
/*!40000 ALTER TABLE `jo_ticketpriorities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketseverities`
--

DROP TABLE IF EXISTS `jo_ticketseverities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketseverities` (
  `ticketseverities_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketseverities` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ticketseverities_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketseverities`
--

LOCK TABLES `jo_ticketseverities` WRITE;
/*!40000 ALTER TABLE `jo_ticketseverities` DISABLE KEYS */;
INSERT INTO `jo_ticketseverities` VALUES (1,'Minor',1,181,0,NULL),(2,'Major',1,182,1,NULL),(3,'Feature',1,183,2,NULL),(4,'Critical',1,184,3,NULL);
/*!40000 ALTER TABLE `jo_ticketseverities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ticketstatus`
--

DROP TABLE IF EXISTS `jo_ticketstatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ticketstatus` (
  `ticketstatus_id` int(19) NOT NULL AUTO_INCREMENT,
  `ticketstatus` varchar(200) DEFAULT NULL,
  `presence` int(1) NOT NULL DEFAULT '0',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`ticketstatus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ticketstatus`
--

LOCK TABLES `jo_ticketstatus` WRITE;
/*!40000 ALTER TABLE `jo_ticketstatus` DISABLE KEYS */;
INSERT INTO `jo_ticketstatus` VALUES (1,'Open',0,185,0,NULL),(2,'In Progress',0,186,1,NULL),(3,'Wait For Response',0,187,2,NULL),(4,'Closed',0,188,3,NULL);
/*!40000 ALTER TABLE `jo_ticketstatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_time_zone`
--

DROP TABLE IF EXISTS `jo_time_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_time_zone` (
  `time_zoneid` int(19) NOT NULL AUTO_INCREMENT,
  `time_zone` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`time_zoneid`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_time_zone`
--

LOCK TABLES `jo_time_zone` WRITE;
/*!40000 ALTER TABLE `jo_time_zone` DISABLE KEYS */;
INSERT INTO `jo_time_zone` VALUES (1,'Pacific/Midway',0,1),(2,'Pacific/Samoa',1,1),(3,'Pacific/Honolulu',2,1),(4,'America/Anchorage',3,1),(5,'America/Los_Angeles',4,1),(6,'America/Tijuana',5,1),(7,'America/Denver',6,1),(8,'America/Chihuahua',7,1),(9,'America/Mazatlan',8,1),(10,'America/Phoenix',9,1),(11,'America/Regina',10,1),(12,'America/Tegucigalpa',11,1),(13,'America/Chicago',12,1),(14,'America/Mexico_City',13,1),(15,'America/Monterrey',14,1),(16,'America/New_York',15,1),(17,'America/Bogota',16,1),(18,'America/Lima',17,1),(19,'America/Rio_Branco',18,1),(20,'America/Indiana/Indianapolis',19,1),(21,'America/Caracas',20,1),(22,'America/Halifax',21,1),(23,'America/Manaus',22,1),(24,'America/Santiago',23,1),(25,'America/La_Paz',24,1),(26,'America/Cuiaba',25,1),(27,'America/Asuncion',26,1),(28,'America/St_Johns',27,1),(29,'America/Argentina/Buenos_Aires',28,1),(30,'America/Sao_Paulo',29,1),(31,'America/Godthab',30,1),(32,'America/Montevideo',31,1),(33,'Atlantic/South_Georgia',32,1),(34,'Atlantic/Azores',33,1),(35,'Atlantic/Cape_Verde',34,1),(36,'Europe/London',35,1),(37,'UTC',36,1),(38,'Africa/Monrovia',37,1),(39,'Africa/Casablanca',38,1),(40,'Europe/Belgrade',39,1),(41,'Europe/Sarajevo',40,1),(42,'Europe/Brussels',41,1),(43,'Africa/Algiers',42,1),(44,'Europe/Amsterdam',43,1),(45,'Europe/Minsk',44,1),(46,'Africa/Cairo',45,1),(47,'Europe/Helsinki',46,1),(48,'Europe/Athens',47,1),(49,'Europe/Istanbul',48,1),(50,'Asia/Jerusalem',49,1),(51,'Asia/Amman',50,1),(52,'Asia/Beirut',51,1),(53,'Africa/Windhoek',52,1),(54,'Africa/Harare',53,1),(55,'Asia/Kuwait',54,1),(56,'Asia/Baghdad',55,1),(57,'Africa/Nairobi',56,1),(58,'Asia/Tehran',57,1),(59,'Asia/Tbilisi',58,1),(60,'Europe/Moscow',59,1),(61,'Asia/Muscat',60,1),(62,'Asia/Baku',61,1),(63,'Asia/Yerevan',62,1),(64,'Asia/Karachi',63,1),(65,'Asia/Tashkent',64,1),(66,'Asia/Kolkata',65,1),(67,'Asia/Colombo',66,1),(68,'Asia/Katmandu',67,1),(69,'Asia/Dhaka',68,1),(70,'Asia/Almaty',69,1),(71,'Asia/Yekaterinburg',70,1),(72,'Asia/Rangoon',71,1),(73,'Asia/Novosibirsk',72,1),(74,'Asia/Bangkok',73,1),(75,'Asia/Brunei',74,1),(76,'Asia/Krasnoyarsk',75,1),(77,'Asia/Ulaanbaatar',76,1),(78,'Asia/Kuala_Lumpur',77,1),(79,'Asia/Taipei',78,1),(80,'Australia/Perth',79,1),(81,'Asia/Irkutsk',80,1),(82,'Asia/Seoul',81,1),(83,'Asia/Tokyo',82,1),(84,'Australia/Darwin',83,1),(85,'Australia/Adelaide',84,1),(86,'Australia/Canberra',85,1),(87,'Australia/Brisbane',86,1),(88,'Australia/Hobart',87,1),(89,'Asia/Vladivostok',88,1),(90,'Pacific/Guam',89,1),(91,'Asia/Yakutsk',90,1),(92,'Pacific/Fiji',92,1),(93,'Asia/Kamchatka',93,1),(94,'Pacific/Auckland',94,1),(95,'Asia/Magadan',95,1),(96,'Pacific/Tongatapu',96,1),(97,'Etc/GMT-11',91,1);
/*!40000 ALTER TABLE `jo_time_zone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_read_group_rel_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_read_group_rel_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_read_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_read_group_rel_sharing_per_userid_sharedgroupid_tabid` (`userid`,`sharedgroupid`,`tabid`),
  CONSTRAINT `fk_4_jo_tmp_read_group_rel_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_read_group_rel_sharing_per`
--

LOCK TABLES `jo_tmp_read_group_rel_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_read_group_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_read_group_rel_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_read_group_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_read_group_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_read_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_read_group_sharing_per_userid_sharedgroupid_idx` (`userid`,`sharedgroupid`),
  CONSTRAINT `fk_3_jo_tmp_read_group_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_read_group_sharing_per`
--

LOCK TABLES `jo_tmp_read_group_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_read_group_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_read_group_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_read_user_rel_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_read_user_rel_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_read_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_read_user_rel_sharing_per_userid_shared_reltabid_idx` (`userid`,`shareduserid`,`relatedtabid`),
  CONSTRAINT `fk_4_jo_tmp_read_user_rel_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_read_user_rel_sharing_per`
--

LOCK TABLES `jo_tmp_read_user_rel_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_read_user_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_read_user_rel_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_read_user_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_read_user_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_read_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_read_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`),
  CONSTRAINT `fk_3_jo_tmp_read_user_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_read_user_sharing_per`
--

LOCK TABLES `jo_tmp_read_user_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_read_user_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_read_user_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_write_group_rel_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_write_group_rel_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_write_group_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`sharedgroupid`),
  KEY `tmp_write_group_rel_sharing_per_userid_sharedgroupid_tabid_idx` (`userid`,`sharedgroupid`,`tabid`),
  CONSTRAINT `fk_4_jo_tmp_write_group_rel_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_write_group_rel_sharing_per`
--

LOCK TABLES `jo_tmp_write_group_rel_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_write_group_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_write_group_rel_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_write_group_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_write_group_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_write_group_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `sharedgroupid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`sharedgroupid`),
  KEY `tmp_write_group_sharing_per_UK1` (`userid`,`sharedgroupid`),
  CONSTRAINT `fk_3_jo_tmp_write_group_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_write_group_sharing_per`
--

LOCK TABLES `jo_tmp_write_group_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_write_group_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_write_group_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_write_user_rel_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_write_user_rel_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_write_user_rel_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `relatedtabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`relatedtabid`,`shareduserid`),
  KEY `tmp_write_user_rel_sharing_per_userid_sharduserid_tabid_idx` (`userid`,`shareduserid`,`tabid`),
  CONSTRAINT `fk_4_jo_tmp_write_user_rel_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_write_user_rel_sharing_per`
--

LOCK TABLES `jo_tmp_write_user_rel_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_write_user_rel_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_write_user_rel_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tmp_write_user_sharing_per`
--

DROP TABLE IF EXISTS `jo_tmp_write_user_sharing_per`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tmp_write_user_sharing_per` (
  `userid` int(11) NOT NULL,
  `tabid` int(11) NOT NULL,
  `shareduserid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`,`shareduserid`),
  KEY `tmp_write_user_sharing_per_userid_shareduserid_idx` (`userid`,`shareduserid`),
  CONSTRAINT `fk_3_jo_tmp_write_user_sharing_per` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tmp_write_user_sharing_per`
--

LOCK TABLES `jo_tmp_write_user_sharing_per` WRITE;
/*!40000 ALTER TABLE `jo_tmp_write_user_sharing_per` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tmp_write_user_sharing_per` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_tracker`
--

DROP TABLE IF EXISTS `jo_tracker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(36) DEFAULT NULL,
  `module_name` varchar(25) DEFAULT NULL,
  `item_id` varchar(36) DEFAULT NULL,
  `item_summary` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_tracker`
--

LOCK TABLES `jo_tracker` WRITE;
/*!40000 ALTER TABLE `jo_tracker` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_tracker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_troubletickets`
--

DROP TABLE IF EXISTS `jo_troubletickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_troubletickets` (
  `ticketid` int(19) NOT NULL,
  `ticket_no` varchar(100) NOT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  `parent_id` varchar(100) DEFAULT NULL,
  `product_id` varchar(100) DEFAULT NULL,
  `priority` varchar(200) DEFAULT NULL,
  `severity` varchar(200) DEFAULT NULL,
  `status` varchar(200) DEFAULT NULL,
  `category` varchar(200) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `solution` text,
  `update_log` text,
  `version_id` int(11) DEFAULT NULL,
  `hours` decimal(25,8) DEFAULT NULL,
  `days` decimal(25,8) DEFAULT NULL,
  `contact_id` int(19) DEFAULT NULL,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`ticketid`),
  KEY `troubletickets_ticketid_idx` (`ticketid`),
  KEY `troubletickets_status_idx` (`status`),
  CONSTRAINT `fk_1_jo_troubletickets` FOREIGN KEY (`ticketid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_troubletickets`
--

LOCK TABLES `jo_troubletickets` WRITE;
/*!40000 ALTER TABLE `jo_troubletickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_troubletickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_usageunit`
--

DROP TABLE IF EXISTS `jo_usageunit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_usageunit` (
  `usageunitid` int(19) NOT NULL AUTO_INCREMENT,
  `usageunit` varchar(200) NOT NULL,
  `presence` int(1) NOT NULL DEFAULT '1',
  `picklist_valueid` int(19) NOT NULL DEFAULT '0',
  `sortorderid` int(11) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`usageunitid`),
  UNIQUE KEY `usageunit_usageunit_idx` (`usageunit`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_usageunit`
--

LOCK TABLES `jo_usageunit` WRITE;
/*!40000 ALTER TABLE `jo_usageunit` DISABLE KEYS */;
INSERT INTO `jo_usageunit` VALUES (1,'Box',1,189,0,NULL),(2,'Carton',1,190,1,NULL),(3,'Dozen',1,191,2,NULL),(4,'Each',1,192,3,NULL),(5,'Hours',1,193,4,NULL),(6,'Impressions',1,194,5,NULL),(7,'Lb',1,195,6,NULL),(8,'M',1,196,7,NULL),(9,'Pack',1,197,8,NULL),(10,'Pages',1,198,9,NULL),(11,'Pieces',1,199,10,NULL),(12,'Quantity',1,200,11,NULL),(13,'Reams',1,201,12,NULL),(14,'Sheet',1,202,13,NULL),(15,'Spiral Binder',1,203,14,NULL),(16,'Sq Ft',1,204,15,NULL);
/*!40000 ALTER TABLE `jo_usageunit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_user2mergefields`
--

DROP TABLE IF EXISTS `jo_user2mergefields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_user2mergefields` (
  `userid` int(11) DEFAULT NULL,
  `tabid` int(19) DEFAULT NULL,
  `fieldid` int(19) DEFAULT NULL,
  `visible` int(2) DEFAULT NULL,
  KEY `userid_tabid_idx` (`userid`,`tabid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_user2mergefields`
--

LOCK TABLES `jo_user2mergefields` WRITE;
/*!40000 ALTER TABLE `jo_user2mergefields` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_user2mergefields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_user2role`
--

DROP TABLE IF EXISTS `jo_user2role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_user2role` (
  `userid` int(11) NOT NULL,
  `roleid` varchar(255) NOT NULL,
  PRIMARY KEY (`userid`),
  KEY `user2role_roleid_idx` (`roleid`),
  CONSTRAINT `fk_2_jo_user2role` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_user2role`
--

LOCK TABLES `jo_user2role` WRITE;
/*!40000 ALTER TABLE `jo_user2role` DISABLE KEYS */;
INSERT INTO `jo_user2role` VALUES (1,'H2');
/*!40000 ALTER TABLE `jo_user2role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_user_module_preferences`
--

DROP TABLE IF EXISTS `jo_user_module_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_user_module_preferences` (
  `userid` int(19) NOT NULL,
  `tabid` int(19) NOT NULL,
  `default_cvid` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`tabid`),
  KEY `fk_2_jo_user_module_preferences` (`tabid`),
  CONSTRAINT `fk_2_jo_user_module_preferences` FOREIGN KEY (`tabid`) REFERENCES `jo_tab` (`tabid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_user_module_preferences`
--

LOCK TABLES `jo_user_module_preferences` WRITE;
/*!40000 ALTER TABLE `jo_user_module_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_user_module_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_users`
--

DROP TABLE IF EXISTS `jo_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `user_password` varchar(200) DEFAULT NULL,
  `user_hash` varchar(32) DEFAULT NULL,
  `cal_color` varchar(25) DEFAULT '#E6FAD8',
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `reports_to_id` varchar(36) DEFAULT NULL,
  `is_admin` varchar(3) DEFAULT '0',
  `currency_id` int(19) NOT NULL DEFAULT '1',
  `description` text,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `phone_home` varchar(50) DEFAULT NULL,
  `phone_mobile` varchar(50) DEFAULT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `phone_other` varchar(50) DEFAULT NULL,
  `phone_fax` varchar(50) DEFAULT NULL,
  `email1` varchar(100) DEFAULT NULL,
  `email2` varchar(100) DEFAULT NULL,
  `secondaryemail` varchar(100) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `signature` text,
  `address_street` varchar(150) DEFAULT NULL,
  `address_city` varchar(100) DEFAULT NULL,
  `address_state` varchar(100) DEFAULT NULL,
  `address_country` varchar(25) DEFAULT NULL,
  `address_postalcode` varchar(9) DEFAULT NULL,
  `user_preferences` text,
  `tz` varchar(30) DEFAULT NULL,
  `holidays` varchar(60) DEFAULT NULL,
  `namedays` varchar(60) DEFAULT NULL,
  `workdays` varchar(30) DEFAULT NULL,
  `weekstart` int(11) DEFAULT NULL,
  `date_format` varchar(200) DEFAULT NULL,
  `hour_format` varchar(30) DEFAULT 'am/pm',
  `start_hour` varchar(30) DEFAULT '10:00',
  `end_hour` varchar(30) DEFAULT '23:00',
  `is_owner` varchar(100) DEFAULT '0',
  `activity_view` varchar(200) DEFAULT 'Today',
  `lead_view` varchar(200) DEFAULT 'Today',
  `default_landing_page` varchar(200) DEFAULT 'Home',
  `default_dashboard_view` varchar(2) DEFAULT '1',
  `imagename` varchar(250) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `confirm_password` varchar(300) DEFAULT NULL,
  `internal_mailer` varchar(3) NOT NULL DEFAULT '1',
  `reminder_interval` varchar(100) DEFAULT NULL,
  `reminder_next_time` varchar(100) DEFAULT NULL,
  `crypt_type` varchar(20) NOT NULL DEFAULT 'MD5',
  `accesskey` varchar(36) DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `language` varchar(36) DEFAULT NULL,
  `time_zone` varchar(200) DEFAULT NULL,
  `currency_grouping_pattern` varchar(100) DEFAULT NULL,
  `currency_decimal_separator` varchar(2) DEFAULT NULL,
  `currency_grouping_separator` varchar(2) DEFAULT NULL,
  `currency_symbol_placement` varchar(20) DEFAULT NULL,
  `phone_crm_extension` varchar(100) DEFAULT NULL,
  `no_of_currency_decimals` varchar(2) DEFAULT NULL,
  `truncate_trailing_zeros` varchar(3) DEFAULT NULL,
  `dayoftheweek` varchar(100) DEFAULT NULL,
  `callduration` varchar(100) DEFAULT NULL,
  `othereventduration` varchar(100) DEFAULT NULL,
  `calendarsharedtype` varchar(100) DEFAULT NULL,
  `default_record_view` varchar(10) DEFAULT NULL,
  `leftpanelhide` varchar(3) DEFAULT NULL,
  `rowheight` varchar(10) DEFAULT NULL,
  `defaulteventstatus` varchar(50) DEFAULT NULL,
  `defaultactivitytype` varchar(50) DEFAULT NULL,
  `hidecompletedevents` int(11) DEFAULT NULL,
  `defaultcalendarview` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_user_name_idx` (`user_name`),
  KEY `user_user_password_idx` (`user_password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_users`
--

LOCK TABLES `jo_users` WRITE;
/*!40000 ALTER TABLE `jo_users` DISABLE KEYS */;
INSERT INTO `jo_users` VALUES (1,'admin','$1$ad000000$hzXFXvL3XVlnUE/X.1n9t/','21232f297a57a5a743894a0e4a801fc3','#E6FAD8','Aruna','Administrator','','on',1,'','2020-09-23 08:28:59',NULL,NULL,'','','','','','','','arunar@smackcoders.com','','','Active','','','','','','',NULL,NULL,NULL,NULL,NULL,NULL,'mm-dd-yyyy','12','00:00','23:00','1','This Week','Today','Home','1','',0,'$1$ad000000$hzXFXvL3XVlnUE/X.1n9t/','1','1 Minute',NULL,'PHP5.3MD5','k5C2wG1RUWmvAFJB','alphagrey','en_us','America/Los_Angeles','123,456,789','.',',','$1.0','','2','1','Sunday','5','5','public','Summary','0','','Planned','Call',0,'MyCalendar');
/*!40000 ALTER TABLE `jo_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_users2group`
--

DROP TABLE IF EXISTS `jo_users2group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_users2group` (
  `groupid` int(19) NOT NULL,
  `userid` int(19) NOT NULL,
  PRIMARY KEY (`groupid`,`userid`),
  KEY `users2group_groupname_uerid_idx` (`groupid`,`userid`),
  KEY `fk_2_jo_users2group` (`userid`),
  CONSTRAINT `fk_2_jo_users2group` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_users2group`
--

LOCK TABLES `jo_users2group` WRITE;
/*!40000 ALTER TABLE `jo_users2group` DISABLE KEYS */;
INSERT INTO `jo_users2group` VALUES (3,1);
/*!40000 ALTER TABLE `jo_users2group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_users_last_import`
--

DROP TABLE IF EXISTS `jo_users_last_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_users_last_import` (
  `id` int(36) NOT NULL AUTO_INCREMENT,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `bean_type` varchar(36) DEFAULT NULL,
  `bean_id` varchar(36) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`assigned_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_users_last_import`
--

LOCK TABLES `jo_users_last_import` WRITE;
/*!40000 ALTER TABLE `jo_users_last_import` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_users_last_import` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_userscf`
--

DROP TABLE IF EXISTS `jo_userscf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_userscf` (
  `usersid` int(19) NOT NULL,
  `enablepercentagecompletion` int(11) DEFAULT NULL,
  `completedpercentage` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`usersid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_userscf`
--

LOCK TABLES `jo_userscf` WRITE;
/*!40000 ALTER TABLE `jo_userscf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_userscf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_usersgrouprel`
--

DROP TABLE IF EXISTS `jo_usersgrouprel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_usersgrouprel` (
  `usersid` int(11) NOT NULL,
  `groupname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`usersid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_usersgrouprel`
--

LOCK TABLES `jo_usersgrouprel` WRITE;
/*!40000 ALTER TABLE `jo_usersgrouprel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_usersgrouprel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_vendor`
--

DROP TABLE IF EXISTS `jo_vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_vendor` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  `vendor_no` varchar(100) NOT NULL,
  `vendorname` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `glacct` varchar(200) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `street` text,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `pobox` varchar(30) DEFAULT NULL,
  `postalcode` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `description` text,
  `tags` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`vendorid`),
  CONSTRAINT `fk_1_jo_vendor` FOREIGN KEY (`vendorid`) REFERENCES `jo_crmentity` (`crmid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_vendor`
--

LOCK TABLES `jo_vendor` WRITE;
/*!40000 ALTER TABLE `jo_vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_vendorcf`
--

DROP TABLE IF EXISTS `jo_vendorcf`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_vendorcf` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendorid`),
  CONSTRAINT `fk_1_jo_vendorcf` FOREIGN KEY (`vendorid`) REFERENCES `jo_vendor` (`vendorid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_vendorcf`
--

LOCK TABLES `jo_vendorcf` WRITE;
/*!40000 ALTER TABLE `jo_vendorcf` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_vendorcf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_vendorcontactrel`
--

DROP TABLE IF EXISTS `jo_vendorcontactrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_vendorcontactrel` (
  `vendorid` int(19) NOT NULL DEFAULT '0',
  `contactid` int(19) NOT NULL DEFAULT '0',
  PRIMARY KEY (`vendorid`,`contactid`),
  KEY `vendorcontactrel_vendorid_idx` (`vendorid`),
  KEY `vendorcontactrel_contact_idx` (`contactid`),
  CONSTRAINT `fk_2_jo_vendorcontactrel` FOREIGN KEY (`vendorid`) REFERENCES `jo_vendor` (`vendorid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_vendorcontactrel`
--

LOCK TABLES `jo_vendorcontactrel` WRITE;
/*!40000 ALTER TABLE `jo_vendorcontactrel` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_vendorcontactrel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_version`
--

DROP TABLE IF EXISTS `jo_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_version` varchar(30) DEFAULT NULL,
  `current_version` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_version`
--

LOCK TABLES `jo_version` WRITE;
/*!40000 ALTER TABLE `jo_version` DISABLE KEYS */;
INSERT INTO `jo_version` VALUES (1,'1.5','2.0-alpha-1');
/*!40000 ALTER TABLE `jo_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_visibility`
--

DROP TABLE IF EXISTS `jo_visibility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_visibility` (
  `visibilityid` int(19) NOT NULL AUTO_INCREMENT,
  `visibility` varchar(200) NOT NULL,
  `sortorderid` int(19) NOT NULL DEFAULT '0',
  `presence` int(1) NOT NULL DEFAULT '1',
  `color` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`visibilityid`),
  UNIQUE KEY `visibility_visibility_idx` (`visibility`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_visibility`
--

LOCK TABLES `jo_visibility` WRITE;
/*!40000 ALTER TABLE `jo_visibility` DISABLE KEYS */;
INSERT INTO `jo_visibility` VALUES (1,'Private',0,1,NULL),(2,'Public',1,1,NULL);
/*!40000 ALTER TABLE `jo_visibility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_vtaddressmapping`
--

DROP TABLE IF EXISTS `jo_vtaddressmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_vtaddressmapping` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `location` text,
  `modulename` varchar(50) DEFAULT NULL,
  `street` text,
  `area` text,
  `locality` text,
  `city` text,
  `state` text,
  `country` text,
  `postalcode` text,
  `isenabled` int(1) DEFAULT '0',
  `fieldset` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_vtaddressmapping`
--

LOCK TABLES `jo_vtaddressmapping` WRITE;
/*!40000 ALTER TABLE `jo_vtaddressmapping` DISABLE KEYS */;
INSERT INTO `jo_vtaddressmapping` VALUES (1,NULL,'Contacts','YToxOntpOjA7czoyOiI5NiI7fQ==','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czoyOiI5OCI7fQ==','YToxOntpOjA7czozOiIxMDAiO30=','YToxOntpOjA7czozOiIxMDQiO30=','YToxOntpOjA7czozOiIxMDIiO30=',0,NULL),(2,NULL,'PurchaseOrder','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=','YToxOntpOjA7czowOiIiO30=',0,NULL);
/*!40000 ALTER TABLE `jo_vtaddressmapping` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `jo_webforms`
--

DROP TABLE IF EXISTS `jo_webforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_webforms` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `publicid` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '1',
  `targetmodule` varchar(50) NOT NULL,
  `description` text,
  `ownerid` int(19) NOT NULL,
  `returnurl` varchar(250) DEFAULT NULL,
  `captcha` int(1) NOT NULL DEFAULT '0',
  `roundrobin` int(1) NOT NULL DEFAULT '0',
  `roundrobin_userid` varchar(256) DEFAULT NULL,
  `roundrobin_logic` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `webformname` (`name`),
  UNIQUE KEY `publicid` (`id`),
  KEY `webforms_webforms_id_idx` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_webforms`
--

LOCK TABLES `jo_webforms` WRITE;
/*!40000 ALTER TABLE `jo_webforms` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_webforms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_webforms_field`
--

DROP TABLE IF EXISTS `jo_webforms_field`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_webforms_field` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `webformid` int(19) NOT NULL,
  `fieldname` varchar(50) NOT NULL,
  `neutralizedfield` varchar(50) NOT NULL,
  `defaultvalue` text,
  `required` int(10) NOT NULL DEFAULT '0',
  `sequence` int(10) DEFAULT NULL,
  `hidden` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `webforms_webforms_field_idx` (`id`),
  KEY `fk_1_jo_webforms_field` (`webformid`),
  KEY `fk_2_jo_webforms_field` (`fieldname`),
  CONSTRAINT `fk_1_jo_webforms_field` FOREIGN KEY (`webformid`) REFERENCES `jo_webforms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_webforms_field`
--

LOCK TABLES `jo_webforms_field` WRITE;
/*!40000 ALTER TABLE `jo_webforms_field` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_webforms_field` ENABLE KEYS */;
UNLOCK TABLES;


-- Table structure for table `jo_webhooks`
--

DROP TABLE IF EXISTS `jo_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_webhooks` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '1',
  `targetmodule` varchar(50) NOT NULL,
  `description` text,
  `events` varchar(50) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `fields` blob,
  `ownerid` int(19) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `webhookname` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_webhooks`
--

LOCK TABLES `jo_webhooks` WRITE;
/*!40000 ALTER TABLE `jo_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_webhooks` ENABLE KEYS */;
UNLOCK TABLES;
--
-- Table structure for table `jo_wordtemplates`
--

DROP TABLE IF EXISTS `jo_wordtemplates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wordtemplates` (
  `templateid` int(19) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `module` varchar(30) NOT NULL,
  `date_entered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_type` varchar(50) NOT NULL,
  `data` longblob,
  `description` text,
  `filesize` varchar(50) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`templateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wordtemplates`
--

LOCK TABLES `jo_wordtemplates` WRITE;
/*!40000 ALTER TABLE `jo_wordtemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wordtemplates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_entity`
--

DROP TABLE IF EXISTS `jo_ws_entity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_entity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `handler_path` varchar(255) NOT NULL,
  `handler_class` varchar(64) NOT NULL,
  `ismodule` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_entity`
--

LOCK TABLES `jo_ws_entity` WRITE;
/*!40000 ALTER TABLE `jo_ws_entity` DISABLE KEYS */;
INSERT INTO `jo_ws_entity` VALUES (1,'Vendors','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(2,'PriceBooks','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(3,'Quotes','includes/Webservices/LineItem/HeadInventoryOperation.php','HeadInventoryOperation',1),(4,'PurchaseOrder','includes/Webservices/LineItem/HeadInventoryOperation.php','HeadInventoryOperation',1),(5,'SalesOrder','includes/Webservices/LineItem/HeadInventoryOperation.php','HeadInventoryOperation',1),(6,'Invoice','includes/Webservices/LineItem/HeadInventoryOperation.php','HeadInventoryOperation',1),(7,'Campaigns','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(8,'Calendar','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(9,'Leads','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(10,'Accounts','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(11,'Contacts','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(12,'Potentials','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(13,'Products','includes/Webservices/HeadProductOperation.php','HeadProductOperation',1),(14,'Documents','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(15,'Emails','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(16,'HelpDesk','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(17,'Events','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(18,'Users','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(19,'Groups','includes/Webservices/HeadActorOperation.php','HeadActorOperation',0),(20,'Currency','includes/Webservices/HeadActorOperation.php','HeadActorOperation',0),(21,'DocumentFolders','includes/Webservices/HeadActorOperation.php','HeadActorOperation',0),(22,'CompanyDetails','includes/Webservices/HeadCompanyDetails.php','HeadCompanyDetails',0),(23,'Services','includes/Webservices/HeadProductOperation.php','HeadProductOperation',1),(24,'PBXManager','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(25,'ProjectMilestone','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(26,'ProjectTask','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(27,'Project','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(28,'ModComments','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(29,'LineItem','includes/Webservices/LineItem/HeadLineItemOperation.php','HeadLineItemOperation',0),(30,'Tax','includes/Webservices/LineItem/HeadTaxOperation.php','HeadTaxOperation',0),(31,'ProductTaxes','includes/Webservices/LineItem/HeadProductTaxesOperation.php','HeadProductTaxesOperation',0),(32,'PDFMaker','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(33,'EmailPlus','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1),(34,'ModComments','includes/Webservices/HeadModuleOperation.php','HeadModuleOperation',1);
/*!40000 ALTER TABLE `jo_ws_entity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_entity_fieldtype`
--

DROP TABLE IF EXISTS `jo_ws_entity_fieldtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_entity_fieldtype` (
  `fieldtypeid` int(19) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(50) NOT NULL,
  `field_name` varchar(50) NOT NULL,
  `fieldtype` varchar(200) NOT NULL,
  PRIMARY KEY (`fieldtypeid`),
  UNIQUE KEY `jo_idx_1_tablename_fieldname` (`table_name`,`field_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_entity_fieldtype`
--

LOCK TABLES `jo_ws_entity_fieldtype` WRITE;
/*!40000 ALTER TABLE `jo_ws_entity_fieldtype` DISABLE KEYS */;
INSERT INTO `jo_ws_entity_fieldtype` VALUES (1,'jo_attachmentsfolder','createdby','reference'),(2,'jo_organizationdetails','logoname','file'),(3,'jo_organizationdetails','phone','phone'),(4,'jo_organizationdetails','fax','phone'),(5,'jo_organizationdetails','website','url'),(6,'jo_inventoryproductrel','productid','reference'),(7,'jo_inventoryproductrel','id','reference'),(8,'jo_inventoryproductrel','incrementondel','autogenerated'),(9,'jo_producttaxrel','productid','reference'),(10,'jo_producttaxrel','taxid','reference');
/*!40000 ALTER TABLE `jo_ws_entity_fieldtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_entity_name`
--

DROP TABLE IF EXISTS `jo_ws_entity_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_entity_name` (
  `entity_id` int(11) NOT NULL,
  `name_fields` varchar(50) NOT NULL,
  `index_field` varchar(50) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  PRIMARY KEY (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_entity_name`
--

LOCK TABLES `jo_ws_entity_name` WRITE;
/*!40000 ALTER TABLE `jo_ws_entity_name` DISABLE KEYS */;
INSERT INTO `jo_ws_entity_name` VALUES (19,'groupname','groupid','jo_groups'),(20,'currency_name','id','jo_currency_info'),(21,'foldername','folderid','jo_attachmentsfolder'),(22,'organizationname','groupid','jo_organizationdetails'),(30,'taxlabel','taxid','jo_inventorytaxinfo');
/*!40000 ALTER TABLE `jo_ws_entity_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_entity_referencetype`
--

DROP TABLE IF EXISTS `jo_ws_entity_referencetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_entity_referencetype` (
  `fieldtypeid` int(19) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`fieldtypeid`,`type`),
  CONSTRAINT `jo_fk_1_actors_referencetype` FOREIGN KEY (`fieldtypeid`) REFERENCES `jo_ws_entity_fieldtype` (`fieldtypeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_entity_referencetype`
--

LOCK TABLES `jo_ws_entity_referencetype` WRITE;
/*!40000 ALTER TABLE `jo_ws_entity_referencetype` DISABLE KEYS */;
INSERT INTO `jo_ws_entity_referencetype` VALUES (5,'Users'),(6,'Products'),(7,'Invoice'),(7,'PurchaseOrder'),(7,'Quotes'),(7,'SalesOrder'),(9,'Products'),(10,'Tax');
/*!40000 ALTER TABLE `jo_ws_entity_referencetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_entity_tables`
--

DROP TABLE IF EXISTS `jo_ws_entity_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_entity_tables` (
  `webservice_entity_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  PRIMARY KEY (`webservice_entity_id`,`table_name`),
  CONSTRAINT `fk_1_jo_ws_actor_tables` FOREIGN KEY (`webservice_entity_id`) REFERENCES `jo_ws_entity` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_entity_tables`
--

LOCK TABLES `jo_ws_entity_tables` WRITE;
/*!40000 ALTER TABLE `jo_ws_entity_tables` DISABLE KEYS */;
INSERT INTO `jo_ws_entity_tables` VALUES (19,'jo_groups'),(20,'jo_currency_info'),(21,'jo_attachmentsfolder'),(22,'jo_organizationdetails'),(29,'jo_inventoryproductrel'),(30,'jo_inventorytaxinfo'),(31,'jo_producttaxrel');
/*!40000 ALTER TABLE `jo_ws_entity_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_fieldinfo`
--

DROP TABLE IF EXISTS `jo_ws_fieldinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_fieldinfo` (
  `id` varchar(64) NOT NULL,
  `property_name` varchar(32) DEFAULT NULL,
  `property_value` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_fieldinfo`
--

LOCK TABLES `jo_ws_fieldinfo` WRITE;
/*!40000 ALTER TABLE `jo_ws_fieldinfo` DISABLE KEYS */;
INSERT INTO `jo_ws_fieldinfo` VALUES ('jo_organizationdetails.organization_id','upload.path','1');
/*!40000 ALTER TABLE `jo_ws_fieldinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_fieldtype`
--

DROP TABLE IF EXISTS `jo_ws_fieldtype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_fieldtype` (
  `fieldtypeid` int(19) NOT NULL AUTO_INCREMENT,
  `uitype` varchar(30) NOT NULL,
  `fieldtype` varchar(200) NOT NULL,
  PRIMARY KEY (`fieldtypeid`),
  UNIQUE KEY `uitype_idx` (`uitype`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_fieldtype`
--

LOCK TABLES `jo_ws_fieldtype` WRITE;
/*!40000 ALTER TABLE `jo_ws_fieldtype` DISABLE KEYS */;
INSERT INTO `jo_ws_fieldtype` VALUES (1,'15','picklist'),(2,'16','picklist'),(3,'19','text'),(4,'20','text'),(5,'21','text'),(6,'24','text'),(7,'3','autogenerated'),(8,'11','phone'),(9,'33','multipicklist'),(10,'17','url'),(11,'85','skype'),(12,'56','boolean'),(13,'156','boolean'),(14,'53','owner'),(15,'61','file'),(16,'28','file'),(17,'13','email'),(18,'71','currency'),(19,'72','currency'),(20,'50','reference'),(21,'51','reference'),(22,'57','reference'),(23,'58','reference'),(24,'73','reference'),(25,'75','reference'),(26,'76','reference'),(27,'78','reference'),(28,'80','reference'),(29,'81','reference'),(30,'101','reference'),(31,'52','reference'),(32,'357','reference'),(33,'59','reference'),(34,'66','reference'),(35,'77','reference'),(36,'68','reference'),(37,'117','reference'),(38,'26','reference'),(39,'10','reference'),(40,'98','reference');
/*!40000 ALTER TABLE `jo_ws_fieldtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_operation`
--

DROP TABLE IF EXISTS `jo_ws_operation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_operation` (
  `operationid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `handler_path` varchar(255) NOT NULL,
  `handler_method` varchar(64) NOT NULL,
  `type` varchar(8) NOT NULL,
  `prelogin` int(3) NOT NULL,
  PRIMARY KEY (`operationid`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_operation`
--

LOCK TABLES `jo_ws_operation` WRITE;
/*!40000 ALTER TABLE `jo_ws_operation` DISABLE KEYS */;
INSERT INTO `jo_ws_operation` VALUES (1,'login','includes/Webservices/Login.php','vtws_login','POST',1),(2,'retrieve','includes/Webservices/Retrieve.php','vtws_retrieve','GET',0),(3,'create','includes/Webservices/Create.php','vtws_create','POST',0),(4,'update','includes/Webservices/Update.php','vtws_update','POST',0),(5,'delete','includes/Webservices/Delete.php','vtws_delete','POST',0),(6,'sync','includes/Webservices/GetUpdates.php','vtws_sync','GET',0),(7,'query','includes/Webservices/Query.php','vtws_query','GET',0),(8,'logout','includes/Webservices/Logout.php','vtws_logout','POST',0),(9,'listtypes','includes/Webservices/ModuleTypes.php','vtws_listtypes','GET',0),(10,'getchallenge','includes/Webservices/AuthToken.php','vtws_getchallenge','GET',1),(11,'describe','includes/Webservices/DescribeObject.php','vtws_describe','GET',0),(12,'extendsession','includes/Webservices/ExtendSession.php','vtws_extendSession','POST',1),(13,'convertlead','includes/Webservices/ConvertLead.php','vtws_convertlead','POST',0),(14,'revise','includes/Webservices/Revise.php','vtws_revise','POST',0),(15,'changePassword','includes/Webservices/ChangePassword.php','vtws_changePassword','POST',0),(16,'deleteUser','includes/Webservices/DeleteUser.php','vtws_deleteUser','POST',0),(17,'wsapp_register','modules/WSAPP/api/ws/Register.php','wsapp_register','POST',0),(18,'wsapp_deregister','modules/WSAPP/api/ws/DeRegister.php','wsapp_deregister','POST',0),(19,'wsapp_get','modules/WSAPP/api/ws/Get.php','wsapp_get','POST',0),(20,'wsapp_put','modules/WSAPP/api/ws/Put.php','wsapp_put','POST',0),(21,'wsapp_map','modules/WSAPP/api/ws/Map.php','wsapp_map','POST',0),(22,'mobile.fetchallalerts','modules/Mobile/api/wsapi.php','mobile_ws_fetchAllAlerts','POST',0),(23,'mobile.alertdetailswithmessage','modules/Mobile/api/wsapi.php','mobile_ws_alertDetailsWithMessage','POST',0),(24,'mobile.fetchmodulefilters','modules/Mobile/api/wsapi.php','mobile_ws_fetchModuleFilters','POST',0),(25,'mobile.fetchrecord','modules/Mobile/api/wsapi.php','mobile_ws_fetchRecord','POST',0),(26,'mobile.fetchrecordwithgrouping','modules/Mobile/api/wsapi.php','mobile_ws_fetchRecordWithGrouping','POST',0),(27,'mobile.filterdetailswithcount','modules/Mobile/api/wsapi.php','mobile_ws_filterDetailsWithCount','POST',0),(28,'mobile.listmodulerecords','modules/Mobile/api/wsapi.php','mobile_ws_listModuleRecords','POST',0),(29,'mobile.saverecord','modules/Mobile/api/wsapi.php','mobile_ws_saveRecord','POST',0),(30,'mobile.syncModuleRecords','modules/Mobile/api/wsapi.php','mobile_ws_syncModuleRecords','POST',0),(31,'mobile.query','modules/Mobile/api/wsapi.php','mobile_ws_query','POST',0),(32,'mobile.querywithgrouping','modules/Mobile/api/wsapi.php','mobile_ws_queryWithGrouping','POST',0),(33,'retrieve_inventory','includes/Webservices/LineItem/RetrieveInventory.php','vtws_retrieve_inventory','GET',0),(34,'relatedtypes','includes/Webservices/RelatedTypes.php','vtws_relatedtypes','GET',0),(35,'retrieve_related','includes/Webservices/RetrieveRelated.php','vtws_retrieve_related','GET',0),(36,'query_related','includes/Webservices/QueryRelated.php','vtws_query_related','GET',0);
/*!40000 ALTER TABLE `jo_ws_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_operation_parameters`
--

DROP TABLE IF EXISTS `jo_ws_operation_parameters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_operation_parameters` (
  `operationid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `type` varchar(64) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`operationid`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_operation_parameters`
--

LOCK TABLES `jo_ws_operation_parameters` WRITE;
/*!40000 ALTER TABLE `jo_ws_operation_parameters` DISABLE KEYS */;
INSERT INTO `jo_ws_operation_parameters` VALUES (1,'accessKey','String',2),(1,'username','String',1),(2,'id','String',1),(3,'element','encoded',2),(3,'elementType','String',1),(4,'element','encoded',1),(5,'id','String',1),(6,'elementType','String',2),(6,'modifiedTime','DateTime',1),(7,'query','String',1),(8,'sessionName','String',1),(9,'fieldTypeList','encoded',1),(10,'username','String',1),(11,'elementType','String',1),(13,'accountName','String',3),(13,'assignedTo','String',2),(13,'avoidPotential','Boolean',4),(13,'leadId','String',1),(13,'potential','Encoded',5),(14,'element','Encoded',1),(15,'confirmPassword','String',4),(15,'id','String',1),(15,'newPassword','String',3),(15,'oldPassword','String',2),(16,'id','String',1),(16,'newOwnerId','String',2),(17,'synctype','string',2),(17,'type','string',1),(18,'key','string',2),(18,'type','string',1),(19,'key','string',1),(19,'module','string',2),(19,'token','string',3),(20,'element','encoded',2),(20,'key','string',1),(21,'element','encoded',2),(21,'key','string',1),(23,'alertid','string',1),(24,'module','string',1),(25,'record','string',1),(26,'record','string',1),(27,'filterid','string',1),(28,'elements','encoded',1),(29,'module','string',1),(29,'record','string',2),(29,'values','encoded',3),(30,'module','string',1),(30,'page','string',3),(30,'syncToken','string',2),(31,'module','string',1),(31,'page','string',3),(31,'query','string',2),(32,'module','string',1),(32,'page','string',3),(32,'query','string',2),(33,'id','String',1),(34,'elementType','string',1),(35,'id','string',1),(35,'relatedLabel','string',3),(35,'relatedType','string',2),(36,'id','string',2),(36,'query','string',1),(36,'relatedLabel','string',3);
/*!40000 ALTER TABLE `jo_ws_operation_parameters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_referencetype`
--

DROP TABLE IF EXISTS `jo_ws_referencetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_referencetype` (
  `fieldtypeid` int(19) NOT NULL,
  `type` varchar(25) NOT NULL,
  PRIMARY KEY (`fieldtypeid`,`type`),
  CONSTRAINT `fk_1_jo_referencetype` FOREIGN KEY (`fieldtypeid`) REFERENCES `jo_ws_fieldtype` (`fieldtypeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_referencetype`
--

LOCK TABLES `jo_ws_referencetype` WRITE;
/*!40000 ALTER TABLE `jo_ws_referencetype` DISABLE KEYS */;
INSERT INTO `jo_ws_referencetype` VALUES (20,'Accounts'),(21,'Accounts'),(22,'Contacts'),(23,'Campaigns'),(24,'Accounts'),(25,'Vendors'),(26,'Potentials'),(27,'Quotes'),(28,'SalesOrder'),(29,'Vendors'),(30,'Users'),(31,'Users'),(32,'Accounts'),(32,'Contacts'),(32,'Leads'),(32,'Users'),(32,'Vendors'),(33,'Products'),(34,'Accounts'),(34,'Campaigns'),(34,'HelpDesk'),(34,'Leads'),(34,'Potentials'),(35,'Users'),(36,'Accounts'),(36,'Contacts'),(37,'Currency'),(38,'DocumentFolders');
/*!40000 ALTER TABLE `jo_ws_referencetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_ws_userauthtoken`
--

DROP TABLE IF EXISTS `jo_ws_userauthtoken`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_ws_userauthtoken` (
  `userid` int(19) NOT NULL,
  `token` varchar(36) NOT NULL,
  `expiretime` int(19) NOT NULL,
  PRIMARY KEY (`userid`,`expiretime`),
  UNIQUE KEY `userid_idx` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_ws_userauthtoken`
--

LOCK TABLES `jo_ws_userauthtoken` WRITE;
/*!40000 ALTER TABLE `jo_ws_userauthtoken` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_ws_userauthtoken` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp`
--

DROP TABLE IF EXISTS `jo_wsapp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp` (
  `appid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `appkey` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp`
--

LOCK TABLES `jo_wsapp` WRITE;
/*!40000 ALTER TABLE `jo_wsapp` DISABLE KEYS */;
INSERT INTO `jo_wsapp` VALUES (1,'vtigerCRM','5b0d950dd6444','user');
/*!40000 ALTER TABLE `jo_wsapp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_handlerdetails`
--

DROP TABLE IF EXISTS `jo_wsapp_handlerdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_handlerdetails` (
  `type` varchar(200) NOT NULL,
  `handlerclass` varchar(100) DEFAULT NULL,
  `handlerpath` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_handlerdetails`
--

LOCK TABLES `jo_wsapp_handlerdetails` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_handlerdetails` DISABLE KEYS */;
INSERT INTO `jo_wsapp_handlerdetails` VALUES ('Outlook','OutlookHandler','modules/WSAPP/Handlers/OutlookHandler.php'),('vtigerCRM','vtigerCRMHandler','modules/WSAPP/Handlers/vtigerCRMHandler.php'),('vtigerSyncLib','WSAPP_HeadSyncEventHandler','modules/WSAPP/synclib/handlers/HeadSyncEventHandler.php'),('Google_vtigerHandler','Google_Head_Handler','modules/Google/handlers/Head.php'),('Google_vtigerSyncHandler','Google_HeadSync_Handler','modules/Google/handlers/HeadSync.php');
/*!40000 ALTER TABLE `jo_wsapp_handlerdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_logs_basic`
--

DROP TABLE IF EXISTS `jo_wsapp_logs_basic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_logs_basic` (
  `id` int(25) NOT NULL AUTO_INCREMENT,
  `extensiontabid` int(19) DEFAULT NULL,
  `module` varchar(50) NOT NULL,
  `sync_datetime` datetime NOT NULL,
  `app_create_count` int(11) DEFAULT NULL,
  `app_update_count` int(11) DEFAULT NULL,
  `app_delete_count` int(11) DEFAULT NULL,
  `app_skip_count` int(11) DEFAULT NULL,
  `vt_create_count` int(11) DEFAULT NULL,
  `vt_update_count` int(11) DEFAULT NULL,
  `vt_delete_count` int(11) DEFAULT NULL,
  `vt_skip_count` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_logs_basic`
--

LOCK TABLES `jo_wsapp_logs_basic` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_logs_basic` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wsapp_logs_basic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_logs_details`
--

DROP TABLE IF EXISTS `jo_wsapp_logs_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_logs_details` (
  `id` int(25) NOT NULL,
  `app_create_ids` mediumtext,
  `app_update_ids` mediumtext,
  `app_delete_ids` mediumtext,
  `app_skip_info` mediumtext,
  `vt_create_ids` mediumtext,
  `vt_update_ids` mediumtext,
  `vt_delete_ids` mediumtext,
  `vt_skip_info` mediumtext,
  KEY `jo_wsapp_logs_basic_ibfk_1` (`id`),
  CONSTRAINT `jo_wsapp_logs_basic_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jo_wsapp_logs_basic` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_logs_details`
--

LOCK TABLES `jo_wsapp_logs_details` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_logs_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wsapp_logs_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_queuerecords`
--

DROP TABLE IF EXISTS `jo_wsapp_queuerecords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_queuerecords` (
  `syncserverid` int(19) DEFAULT NULL,
  `details` varchar(300) DEFAULT NULL,
  `flag` varchar(100) DEFAULT NULL,
  `appid` int(19) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_queuerecords`
--

LOCK TABLES `jo_wsapp_queuerecords` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_queuerecords` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wsapp_queuerecords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_recordmapping`
--

DROP TABLE IF EXISTS `jo_wsapp_recordmapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_recordmapping` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `serverid` varchar(10) DEFAULT NULL,
  `clientid` varchar(255) DEFAULT NULL,
  `clientmodifiedtime` datetime DEFAULT NULL,
  `appid` int(11) DEFAULT NULL,
  `servermodifiedtime` datetime DEFAULT NULL,
  `serverappid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_recordmapping`
--

LOCK TABLES `jo_wsapp_recordmapping` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_recordmapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wsapp_recordmapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jo_wsapp_sync_state`
--

DROP TABLE IF EXISTS `jo_wsapp_sync_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jo_wsapp_sync_state` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `stateencodedvalues` varchar(300) NOT NULL,
  `userid` int(19) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jo_wsapp_sync_state`
--

LOCK TABLES `jo_wsapp_sync_state` WRITE;
/*!40000 ALTER TABLE `jo_wsapp_sync_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `jo_wsapp_sync_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rc_server_details`
--

DROP TABLE IF EXISTS `rc_server_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rc_server_details` (
  `user_id` int(19) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `account_type` varchar(100) DEFAULT NULL,
  `port` int(19) DEFAULT NULL,
  `role` varchar(225) DEFAULT NULL,
  `user` varchar(225) DEFAULT NULL,
  `enabletype` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rc_server_details`
--

LOCK TABLES `rc_server_details` WRITE;
/*!40000 ALTER TABLE `rc_server_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `rc_server_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rc_settings`
--

DROP TABLE IF EXISTS `rc_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rc_settings` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rc_settings`
--

LOCK TABLES `rc_settings` WRITE;
/*!40000 ALTER TABLE `rc_settings` DISABLE KEYS */;
INSERT INTO `rc_settings` VALUES (1,'module_version','1.0');
/*!40000 ALTER TABLE `rc_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `searches`
--

DROP TABLE IF EXISTS `searches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `searches` (
  `search_id` int(19) NOT NULL AUTO_INCREMENT,
  `user_id` int(19) DEFAULT NULL,
  `type` int(5) DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`search_id`),
  KEY `search_user_id_idx` (`user_id`),
  CONSTRAINT `user_id_fk_searches` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `searches`
--

LOCK TABLES `searches` WRITE;
/*!40000 ALTER TABLE `searches` DISABLE KEYS */;
/*!40000 ALTER TABLE `searches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `sess_id` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `changed` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `ip` varchar(40) DEFAULT NULL,
  `vars` text,
  PRIMARY KEY (`sess_id`),
  KEY `session_changed_idx` (`changed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `session`
--

LOCK TABLES `session` WRITE;
/*!40000 ALTER TABLE `session` DISABLE KEYS */;
/*!40000 ALTER TABLE `session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system`
--

DROP TABLE IF EXISTS `system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system` (
  `name` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system`
--

LOCK TABLES `system` WRITE;
/*!40000 ALTER TABLE `system` DISABLE KEYS */;
/*!40000 ALTER TABLE `system` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(19) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `mail_host` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `last_login` datetime NOT NULL,
  `failed_login` datetime NOT NULL,
  `failed_login_counter` int(10) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `preferences` varchar(255) DEFAULT NULL,
  `jo_user_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_username_idx` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-09-23 14:18:13
