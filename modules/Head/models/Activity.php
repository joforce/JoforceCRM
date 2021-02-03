<?php
vimport('~~/modules/ModTracker/core/ModTracker_Basic.php');

class Head_Activity_Model extends Head_Record_Model {

	const UPDATE = 0;
	const DELETE = 1;
	const CREATE = 2;
	const RESTORE = 3;
	const LINK = 4;
	const UNLINK = 5;

    function getDateActivities($user_id, $moduleName, $filters = []) {
        $db = PearDatabase::getInstance();
        $listQuery = "SELECT * FROM jo_modtracker_basic WHERE ";
		
		if(count($filters) > 0) {
			$user_id = $filters['user_id'];
			$listQuery .= "whodid = ? ";
			if($filters['date'] != '') {
				$listQuery .= "and changedon like '".$filters['date']."%'";
			}
		} else {
			$listQuery .= "whodid = ? ";
		}
		
		$listQuery .= " ORDER BY changedon DESC";
		$recordInstances = array();
        $result = $db->pquery($listQuery, array($user_id));
        $rows = $db->num_rows($result);
        
        for ($i=0; $i<$rows; $i++) {
            $row = $db->query_result_rowdata($result, $i);
			$date = explode(" ", $row['changedon']);
            $recordInstances[] = date_format(date_create($date[0]),"M d Y");
        }
        return array_unique($recordInstances);
    }
}