<?php
chdir (dirname(__FILE__) . '/..');
include_once('includes/utils/utils.php');
include_once("modules/Emails/mail.php");
include_once('includes/logging.php');
include_once('includes/http/Session.php');
include_once('MySQLSearchReplace.php');
include_once('config/config.inc.php');

require_once('vendor/autoload.php');
include_once 'config/config.php';

include_once 'libraries/modlib/Head/Module.php';
include_once 'includes/main/WebUI.php';
global $dbconfig, $root_directory, $log, $site_URL, $current_user, $adb;
session_start();
$webUI = new Head_WebUI();
$current_user = $webUI->getLogin();
if(!$current_user && $current_user->is_admin == 'on')  {
    die('Login and make sure you are admin before running the script');
}
ini_set('display_errors', 'on');
$migrate_version = 1.5;
$jo_old_version = $adb->fetch_row($adb->pquery("SELECT current_version FROM jo_version"));
$old_version = $jo_old_version['current_version'];
if($_POST['FinishMigration'])	{
    if($old_version < 1.5 && $old_version !== 1.5) {
	include_once('libraries/modlib/Head/Module.php');
	//Kanbview settings starts
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$blockid = getSettingsBlockId('LBL_MODULE_MANAGER');
	$seq_res = $adb->pquery("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid = ?", array($blockid));
	$seq = 1;
	if ($adb->num_rows($seq_res) > 0) {
	    $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
	    if ($cur_seq != null) {
	        $seq = $cur_seq + 1;
	    }
	}

	$adb->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active, pinned) VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, $blockid, 'Kanban view', 'fa fa-th-large', 'KanbanView', 'Pipeline/Settings/Index', $seq, 0, 0));

	if (!Head_Utils::CheckTable('jo_visualpipeline')) {
		Head_Utils::CreateTable('jo_visualpipeline',
                                "(`pipeline_id` int(19) NOT NULL,
				  `tabid` int(10) DEFAULT NULL,
				  `tabname` varchar(200) DEFAULT NULL,
				  `picklist_name` varchar(100) DEFAULT NULL,
				  `records_per_page` int(10) DEFAULT NULL,
				  `selected_fields` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`pipeline_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8", true);
	}
	//Kanban view settings ends

	//adding missed Project milestone fields - starts
	$fieldid = $adb->getUniqueID('jo_field');

	$pjt_milestone_id = getTabid('ProjectMilestone');
	$pjt_task_id = getTabid('ProjectTask');
	$pjt_id = getTabid('Project');
	$users_id = getTabid('Users');

	$adb->pquery("insert into jo_field values($pjt_milestone_id , $fieldid,'source','jo_crmentity',1,'1','source','Source',1,2,'',100,8,93,2,'V~O',3,5,'BAS',0,'',0,NULL)");
	$fieldid = $adb->getUniqueID('jo_field');
	$adb->pquery("insert into jo_field values($pjt_milestone_id , $fieldid,'starred','jo_crmentity_user_field',1,'56','starred','starred',1,2,'',100,9,93,6,'C~O',3,6,'BAS',0,'',0,NULL)");
	$fieldid = $adb->getUniqueID('jo_field');
	$adb->pquery("insert into jo_field values($pjt_milestone_id , $fieldid,'tags','jo_projectmilestone',1,'1','tags','tags',1,2,'',100,10,93,6,'V~O',3,7,'BAS',0,'',0,NULL)");
	//adding missed Project milestone fields - ends

	//Settings side changes - starts
	$adb->pquery('UPDATE jo_settings_field SET blockid = 9 WHERE name = "LBL_MAIL_SCANNER"');
	$adb->pquery('UPDATE jo_settings_field SET blockid = 8 WHERE name = "Duplicate Check"');
	$adb->pquery('UPDATE jo_settings_field SET blockid = 8 WHERE name = "Address Lookup"');
	$adb->pquery('UPDATE jo_settings_field SET blockid = 12 WHERE name = "LBL_PBXMANAGER"');
	$adb->pquery('UPDATE jo_settings_field SET blockid = 12 WHERE name = "Masquerade User"');

 	$field_query = 'select * from jo_field where tabid = ? and columnname = ?';
	$jo_projectmilestone_runquery = $adb->fetch_row($adb->pquery($field_query, array($pjt_milestone_id, 'projectid')));
	$jo_projectmilestone_pjtid = $jo_projectmilestone_runquery['fieldid'];

        $jo_projecttask_runquery = $adb->fetch_row($adb->pquery($field_query, array($pjt_task_id, 'projectid')));
        $jo_projecttask_pjtid = $jo_projecttask_runquery['fieldid'];

	$rel_query = 'update jo_relatedlists set relationfieldid=? where tabid = ? and related_tabid = ?';
	$adb->pquery($rel_query, array( $jo_projectmilestone_pjtid, $pjt_id, $pjt_milestone_id ));
	$adb->pquery($rel_query, array( $jo_projecttask_pjtid, $pjt_id, $pjt_task_id ));
	//Settings side changes - ends

	//User side changes - starts
	$adb->pquery('delete from jo_blocks where tabid = ? and blocklabel = ?', array($users_id, 'LBL_MORE_INFORMATION'));

	$block_query = 'select * from jo_blocks where tabid = ? and blocklabel = ?'; // LBL_USERLOGIN_ROLE & LBL_CURRENCY_CONFIGURATION
	$get_LBL_USERLOGIN_ROLE = $adb->fetch_row($adb->pquery($block_query, array($users_id, 'LBL_USERLOGIN_ROLE')));
	$LBL_USERLOGIN_ROLE_id = $get_LBL_USERLOGIN_ROLE['blockid'];

        $get_LBL_CURRENCY_CONFIGURATION = $adb->fetch_row($adb->pquery($block_query, array($users_id, 'LBL_CURRENCY_CONFIGURATION')));
        $LBL_CURRENCY_CONFIGURATION_id = $get_LBL_CURRENCY_CONFIGURATION['blockid'];

	$users_change_field_array = array(
			'title' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 9),
			'phone_work' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 13),
			'department' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 13),
			'phone_mobile' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 15),
			'reports_to_id' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 16),
			'phone_home' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 17),
			'date_format' => array('block' => $LBL_CURRENCY_CONFIGURATION_id, 'sequence' => 3),
			'description' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 20),
			'address_street' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 27),
			'address_city' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 29),
			'address_state' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 31),
			'address_postalcode' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 30),
			'address_country' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 28),
			'internal_mailer' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 21),
			'language' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 22),
			'default_landing_page' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 23),
			'phone_crm_extension' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 24),
			'default_record_view' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 25),
			'leftpanelhide' => array('block' => $LBL_USERLOGIN_ROLE_id, 'sequence' => 26)
			);

	$field_update_query = "update jo_field set block = ? , sequence = ? where tabid = ? and fieldname =?";
	foreach($users_change_field_array as $fieldname => $fieldinfo) {
	    $adb->pquery($field_update_query, array($fieldinfo['block'], $fieldinfo['sequence'], $users_id, $fieldname));
	}
	//User side changes - ends

        $adb->pquery("INSERT INTO jo_relatedlists VALUES(" . $adb->getUniqueID('jo_relatedlists') . "," . getTabid('Potentials') . "," . getTabid('HelpDesk') . ",'get_related_list',5,'HelpDesk',0, 'add,select','','','1:N')");
	$adb->pquery("INSERT INTO jo_relatedlists VALUES(" . $adb->getUniqueID('jo_relatedlists') . "," . getTabid('HelpDesk') . "," . getTabid('Potentials') . ",'get_related_list',6,'Potentials',0, 'add,select','','','1:N')");

	$ticket_tabid = getTabid('HelpDesk');
	$adb->pquery('update jo_field set summaryfield = ? where fieldname = ? and tabid = ?', array(0,'description',$ticket_tabid));

	//Alter notification Table
	$adb->pquery('ALTER TABLE jo_notification add column fieldname varchar(50) default null');
	$adb->pquery('ALTER TABLE jo_notification add column oldvalue text default null');
	$adb->pquery('ALTER TABLE jo_notification add column newvalue text default null');
	//Add Notification handler
	$handlerId = $adb->getUniqueId('jo_eventhandlers');
	$adb->pquery("insert into jo_eventhandlers (eventhandler_id, event_name, handler_path, handler_class, cond, is_active, dependent_on) values (?,?,?,?,?,?,?)", array($handlerId, 'vtiger.entity.beforedelete', 'modules/Home/NotificationHandler.php', 'NotificationHandler', '', true, '[]'));
        $handlerId = $adb->getUniqueId('jo_eventhandlers');
	$adb->pquery("insert into jo_eventhandlers (eventhandler_id, event_name, handler_path, handler_class, cond, is_active, dependent_on) values (?,?,?,?,?,?,?)", array($handlerId, 'vtiger.entity.afterrestore', 'modules/Home/NotificationHandler.php', 'NotificationHandler', '', true, '[]'));

	//Version update
	$adb->pquery('update jo_version set old_version = ? , current_version = ?', array($old_version , $migrate_version));
	header('Location: ' . $site_URL . 'index.php');

	die();
    } else {
	$href = $site_URL . 'index.php';
	echo "Alrady migarated to the new version. <a href=$href >Go to home</a>";
    }
}
?>
<?php if(!$_POST['startMigration']){?>
<html>
    <head>
	<title>Joforce CRM Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="resources/js/jquery-min.js"></script>
	<link href="resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="resources/css/mkCheckbox.css" rel="stylesheet">
	<link href="resources/css/style.css" rel="stylesheet">
    </head>
    <body>
	<div class="container-fluid page-container">
	    <div class="row-fluid">
		<div class="span6">
		    <div class="logo"> <img src="resources/images/logo.png" alt="Logo"/> </div>
		</div>
		<div class="span6">
		    <div class="head pull-right"> <h3>Migration Wizard</h3> </div>
		</div>
	    </div>

	    <div class="row-fluid main-container">
		<div class="span12 inner-container">
		    <div class="row-fluid">
			<div class="span10"> <h4 class=""> Welcome to Joforce Migration </h4> </div>
		    </div>
		    <hr>
		    <div class="row-fluid">
			<div class="span12">
			    <div style = 'margin-left: 20%'>
                            	<br> <br>
				<strong> Warning: </strong>
				Please note that it is not possible to revert back to Joforce v1.3 after the upgrade to Joforce v1.4 <br>
				So, it is important to take a backup of the Joforce v1.3 files and database before upgrading.<br>
				<form action="index.php" method="POST">
				    <div>
					<input type="checkbox" id="checkBox1" name="checkBox1"/>
					<div class="chkbox"></div> Backup of source folder 
				    </div><br>
				    <div>
					<input type="checkbox" id="checkBox2" name="checkBox4"/>
					<div class="chkbox"></div> Backup of database 
				    </div><br>
				    <?php $filename = '.htaccess';
			            if (file_exists($filename)) {
			                    if (is_writable($filename)) {
						?><input type='hidden' name='htaccess' id='htaccess' value='true' /> <?php }
						?><input type='hidden' name='htaccess' id='htaccess' value='false' /> <?php }
				 	    else { 
						?><input type='hidden' name='htaccess' id='htaccess' value='false' /><?php } ?>
						  <div class="button-container">
							<input type="submit" class="btn btn-large btn-primary" id="startMigration" name="startMigration" value="Next" />
						  </div>
				</form>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	    <script>
		$(document).ready(function(){
		    $('input[name="startMigration"]').click(function(){
        	    	if($("#checkBox1").is(':checked') == false || $("#checkBox2").is(':checked') == false){
                            alert('Before starting migration, please take your database and source backup');
                            return false;
                        }
			var ht = $('#htaccess').val();
			if(ht == 'false') {
                            alert('Please Create htaccess file in your Root Directory with writable access');
                            return false;
			}
			return true;
                    });
		});
	</script>
    </body>
</html>
<?php }?>

<?php if($_POST['startMigration']){?>
<html>
    <head>
	<title>Joforce CRM Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="resources/js/jquery-min.js"></script>
	<link href="resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="resources/css/mkCheckbox.css" rel="stylesheet">
	<link href="resources/css/style.css" rel="stylesheet">
    </head>
    <body>
	<div class="container-fluid page-container">
	    <div class="row-fluid">
		<div class="span6">
		    <div class="logo"> <img src="resources/images/logo.png" alt="Logo"/> </div>
		</div>
		<div class="span6">
		    <div class="head pull-right"> <h3>Migration Wizard</h3> </div>
		</div>
	    </div>
	    <div class="row-fluid main-container">
		<div class="span12 inner-container">
		    <div class="row-fluid">
			<div class="span10"> <h4 class=""> Welcome to Joforce Migration </h4> </div>
		    </div>
		    <hr>
		    <div class="row-fluid">
			<div class="span12">
			    <div id="progressIndicator" class="row main-container hide" style="padding-left:49px;">
				<div class="inner-container">
				<div class="inner-container">
				<div class="row" style="text-align:center;">
				<h3>Migration in progress...</h3><br>
				<img src="install_loading.gif"/>
				<h6>Please Wait.... </h6>
			    </div>
			</div>
		    </div>
		</div>
		<div style = 'margin-left: 20%' class='cont'>
		    <form action="index.php" method="POST">
			<div style="padding-left:49px;">
			    <span style='color:green;font-size:12px;'>*</span>
			    <div class="chkbox"></div>
			    <strong>You agree that you’ve backed up the necessary details before making any changes.</strong>
			</div>
			<br><br>
			<div style="padding-left:49px;">
			    <span style='color:green;font-size:12px;'>*</span>
			    <div class="chkbox"></div>
			    <strong>We hope it doesn’t happen, but Joforce is not responsible for any data loss.</strong>
			</div>
			<br><br><br><br>
			<div class="button-container">
			    <input type="submit" class="btn btn-large btn-primary" id="FinishMigration" name="FinishMigration" value="Start Migration" />
			</div>
		    </form>
		</div>
	    </div>
	</div>
    </div>
</div>
<script>
    $(document).ready(function(){
	$('input[name="FinishMigration"]').click(function(){
	    var confirm_migration = confirm('Are you sure you want to start the migration ?');
	    if(!confirm_migration) {
		return false;
	    }
	    $('.cont').hide();
	    $('#progressIndicator').show();
	    return true;
	});
    });				
</script>
    </body>
</html>
<?php } ?>
