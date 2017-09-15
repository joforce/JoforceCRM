<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class DuplicateCheck_saveForm_View extends Head_Index_View
{
	function __construct()
        {
                parent::__construct();
        }

	public function process(Head_Request $request) 
	{
		global $adb;
		$modulename = $_REQUEST['modulename'];
	//	$crosscheck=$_REQUEST['ischecked'];
	
		# unsetting the showfieldvalues
		unset($_REQUEST['showfieldstomerge_'.$modulename]);
		unset($_REQUEST['fm_showfieldstomerge_'.$modulename]);
		//$mapfields = $_REQUEST['fm_movedvalues_'.$modulename];
		$duplicatecheck = $_REQUEST['movedvalues_'.$modulename];

		/*if($mapfields)	
		{
			$mapfieldjoint = "";
			foreach($mapfields as $singleMapfield)	
			{
				$mapfieldjoint .= $singleMapfield.",";
			}
			$final_mapfield = substr($mapfieldjoint, 0, -1);
		}*/

		if($duplicatecheck)	
		{
			$duplicatecheckjoint = "";
			foreach($duplicatecheck as $singleduplicatecheck)	
			{
				$duplicatecheckjoint .= $singleduplicatecheck.",";
			}
			$final_duplicate = substr($duplicatecheckjoint, 0, -1);
		}

		$check = $adb->pquery("update jo_vtduplicatechecksettings set fieldstomatch = ? where modulename = ?",array($final_duplicate, $modulename));
		$msg = "failure";

		if(!empty($check))	
		{
			$msg = "success";
		}
		$link2redirect = "index.php?module=DuplicateCheck&view=List&msg=$msg&profile=$modulename";
		session_start();
		$_SESSION['saveprof'] = 1;
		?>
			<script type = "text/javascript">
			window.location.href = '<?php echo $link2redirect; ?>';
			</script>
			<?php
			die;	
	}
}
