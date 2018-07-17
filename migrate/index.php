<?php
chdir (dirname(__FILE__) . '/..');
include_once('includes/utils/utils.php');
include_once("modules/Emails/mail.php");
include_once('includes/logging.php');
include_once('includes/http/Session.php');
include_once('version.php');
include_once('MySQLSearchReplace.php');
include_once('config/config.inc.php');

require_once('vendor/autoload.php');
include_once 'config/config.php';

include_once 'vtlib/Head/Module.php';
include_once 'includes/main/WebUI.php';
global $dbconfig, $root_directory, $log, $site_URL, $current_user;
session_start();
$webUI = new Head_WebUI();
$current_user = $webUI->getLogin();
if(!$current_user && $current_user->is_admin == 'on')  {
    die('Login and make sure you are admin before running the script');
}
$migrate_version = 1.3;
$jo_old_version = $adb->fetch_row($adb->pquery("SELECT current_version FROM jo_version"));
$old_version = $jo_old_version['current_version'];
if($_POST['FinishMigration'])	{
	$db = PearDatabase::getInstance();

	$permissions = array();
	$recordModel = new Settings_Profiles_Record_Model();
	$profileModules = $recordModel->getModulePermissions();
	$basicActions = Head_Action_Model::getAllBasic(true);
	foreach($profileModules as $profileModule)	{
		if($profileModule->name == 'Contacts')	{
			$contact_tab_id = $profileModule->id;
			$fieldsModel = $profileModule->getFields();
			$permissions[$contact_tab_id]['is_permitted'] = 'on';
			foreach($basicActions as $basicAction)	{
				if($basicAction->get('actionname') != 'Delete')	{
					$permissions[$contact_tab_id]['actions'][$basicAction->get('actionid')] = 'on';
				}
			}

			foreach($fieldsModel as $fieldInfo)	{
				$permissions[$contact_tab_id]['fields'][$fieldInfo->id] = $recordModel->getModuleFieldPermissionValue($profileModule, $fieldInfo);
			}
		}
		else	{
			$fieldsModel = $profileModule->getFields();
			foreach($fieldsModel as $fieldInfo)	{
				$permissions[$profileModule->id]['fields'][$fieldInfo->id] = $recordModel->getModuleFieldPermissionValue($profileModule, $fieldInfo);
			}	
		}
	}

	$profile_name = 'Portal User';
	$roleName = $profile_name;
	$profile_description = 'Profile for portal user';
	
	// Creating profile for Portal User
	$recordModel = new Settings_Profiles_Record_Model();
	$recordModel->set('profilename', $profile_name);
	$recordModel->set('description', $profile_description);
	$recordModel->set('viewall', 0);
	$recordModel->set('editall', 0);			
	$recordModel->set('profile_permissions', $permissions);
	$recordModel->save();

	$profile_id = $recordModel->get('profileid');

	// Create Role for Portal User
	$recordModel = new Settings_Roles_Record_Model();	
	$roleProfiles = array($profile_id);

	$getParentRoleId = $db->pquery("select roleid from jo_role where depth = (select max(depth) from jo_role)", array());
	$parentRoleId = $db->query_result($getParentRoleId, 0, 'roleid');
	if ($recordModel && !empty($parentRoleId)) {
		$parentRole = Settings_Roles_Record_Model::getInstanceById($parentRoleId);
		$recordModel->set('allowassignedrecordsto', 2);
		if ($parentRole && !empty($roleName) && !empty($roleProfiles)) {
			$recordModel->set('rolename', $roleName);
			$recordModel->set('profileIds', $roleProfiles);
			$response = $parentRole->addChildRole($recordModel);
		}

        // After role updation recreating user privilege files
		if ($roleProfiles) {
			foreach ($roleProfiles as $profileId) {
				$profileRecordModel = Settings_Profiles_Record_Model::getInstanceById($profileId);
				$profileRecordModel->recalculate();
			}
		}
	}

    // add Language editor to settings field
	$db->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned) VALUES (?,?,?,?,?,?,?,?,?)', array($db->getUniqueID('jo_settings_field'), 6, 'Language Editor', 'fa fa-pencil', 'LBL_LANGUAGE_EDITOR', 'LanguageEditor/Settings/Index', 3, 0, 0));

	// add notification settings to settings field table
	$db->pquery("INSERT into jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active, pinned) values(?,?,?,?,?,?,?,?,?)", array($db->getUniqueID('jo_settings_field'), 11, 'Notifications', 'fa fa-bell', 'Notifications', 'Notifications/Settings/Index', 5, 0, 0));

	// add settings page for portal user
	$db->pquery("INSERT into jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active, pinned) values(?,?,?,?,?,?,?,?,?)", array($db->getUniqueID('jo_settings_field'), 4, 'Portal User', 'fa fa-street-view', 'Portal User', 'PortalUser/Settings/Index', 6, 0, 0));

	// Add new action to action mapping table and profile2utility table
	$db->pquery("insert into jo_actionmapping values (?, ?, ?)", array(14, 'Portal User', 0));

	header('Location: ' . $site_URL . 'index.php');
	die();
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
				<div class="logo">
					<img src="resources/images/logo.png" alt="Logo"/>
				</div>
			</div>
			<div class="span6">
				<div class="head pull-right">
					<h3>Migration Wizard</h3>
				</div>
			</div>
		</div>
		<div class="row-fluid main-container">
			<div class="span12 inner-container">
				<div class="row-fluid">
					<div class="span10">
						<h4 class=""> Welcome to Joforce Migration </h4>
					</div>
				</div>
				<hr>
				<div class="row-fluid">
					<div class="span12">
						<div style = 'margin-left: 20%'>
                                		<br> <br>
						<strong> Warning: </strong>
							Please note that it is not possible to revert back to Joforce v1.3 after the upgrade to Joforce v1.4 <br>
							So, it is important to take a backup of the Joforce v1.3 files and database before upgrading.</p><br>
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
					<div class="logo">
						<img src="resources/images/logo.png" alt="Logo"/>
					</div>
				</div>
				<div class="span6">
					<div class="head pull-right">
						<h3>Migration Wizard</h3>
					</div>
				</div>
			</div>
			<div class="row-fluid main-container">
				<div class="span12 inner-container">
					<div class="row-fluid">
						<div class="span10">
							<h4 class=""> Welcome to Joforce Migration </h4>
						</div>
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
										
									
								<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div>  <strong>You agree that you’ve backed up the necessary details before making any changes.</strong> </div><br><br>
								<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> <strong>We hope it doesn’t happen, but Joforce is not responsible for any data loss.</strong> </div><br>
									<br><br><br>
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
                        if(!confirm_migration)  {

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
