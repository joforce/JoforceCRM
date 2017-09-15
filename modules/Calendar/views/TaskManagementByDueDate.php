<?php

class Calendar_TaskManagementByDueDate_View extends Head_Index_View {

        function __construct() {
        }

        public function process(Head_Request $request) {
		global $adb, $current_user;

                $page = 1;
                if ($request->get('page')) {
                        $page = $request->get('page');
                }
		$fetch = false;
                $moduleName = $request->getModule();
                $pagingModel = new Head_Paging_Model();
                $pagingModel->set('page', $page);
                $pagingModel->set('limit', 10);
		$tasks_value = $this->generateColors($request);
		$due_type = array('Overdue' => '#FF5555', 'Today' =>  '#03C04A', 'Tomorrow' => '#54A7F5', 'Later' => '#54A7F5');
                $TaskHelper = new Calendar_TaskManagementByDueDate_View();

                $viewer = $this->getViewer($request);
                $viewer->assign('TASK_HELPER', $TaskHelper);
                $viewer->assign('MODULE', $moduleName);
		$viewer->assign('TASK_DUE', $due_type);
                $viewer->assign('TASKS', $tasks_value);
		
                $viewer->view('TaskManagementDueContents.tpl', $moduleName, $fetch);
	}

	public function getBasicInfo($task){
		$basicInfo = '{"subject":"'.$task['subject'].'","assigned_user_id":"1","date_start":"'.$task['due_date'].'","due_date":"'.$task['due_date'].'","taskstatus":"'.$task['status'].'","parent_id":null,"contact_id":null}';
		return $basicInfo;
	}
	public function getDueType($due_date){
                $current_date = date("Y-m-d");
                                        $tomorrow = date('Y-m-d', strtotime($current_date . ' +1 day'));
                                        if($current_date > $due_date){
                                                $due = 'Overdue';
                                        }
                                        elseif($current_date == $due_date){
                                                $due = 'Today';

                                        }
                                        elseif($tomorrow == $due_date){
                                                $due = 'Tomorrow';
                                        }
                                        else{
                                                $due = 'Later';

                                        }
		return $due;
                                

	}
        public function generateColors(Head_Request $request) {
		global $adb, $current_user;
                $moduleName = $request->getModule();
                 $userId = $current_user->id;
                $getCurrentUserTasks = $adb->pquery('select * from jo_activity join jo_crmentity on crmid = activityid and deleted = 0 and jo_activity.status != ? and activitytype = ? and smownerid = ?', array('Completed', 'Task', $userId));
                while($tasks_value = $adb->fetch_array($getCurrentUserTasks)){
                        $tasks[] = $tasks_value;
                }
		$current_date = date("Y-m-d");
		foreach($tasks as $single_value){
			foreach($single_value as $key => $value){
				if($key === 'due_date'){
					$tomorrow = date('Y-m-d H:i:s', strtotime($stop_date . ' +1 day'));
					if($current_date < $value){
						$due = 'Overdue';
						$colors['type'] = $due;
						$colors['subject'] = $single_value['subject'];
						$colors['due_date'] = $single_value['due_date'];
					}
					elseif($current_date == $value){
						$due = 'Today';
                                                $colors['type'] = $due;
                                                $colors['subject'] = $single_value['subject'];
                                                $colors['due_date'] = $single_value['due_date'];

					}
					elseif($tomorrow == $value){
						$due = 'Tomorrow';
                                                $colors['type'] = $due;
                                                $colors['subject'] = $single_value['subject'];
                                                $colors['due_date'] = $single_value['due_date'];

					}
					else{
						$due = 'Later';
                                                $colors['type'] = $due;
                                                $colors['subject'] = $single_value['subject'];
                                                $colors['due_date'] = $single_value['due_date'];

					}
				$single_value['due'] = $colors;
				}
			}
			$task_details[] = $single_value;
		}
                return $task_details;
        }

        public function getColor($priority) {
                $color = '';
                switch ($priority) {
                        case 'Overdue'             :       $color = '#FF5555';     break;
                        case 'Today'   :       $color = '#03C04A';     break;
                        case 'Tomorrow'              :       $color = '#54A7F5';     break;
                        case 'Later'              :       $color = '#54A7F5';     break;
                        default                 :       $color = '#'.dechex(rand(0x000000, 0xFFFFFF));
                                                                break;
                }
                return $color;
        }

        protected function setFiltersInSession($filters) {
                if (!isset($filters['status'])) {
                        $filters['status'] = array();
                }
                if (!isset($filters['assigned_user_id'])) {
                        $filters['assigned_user_id'] = array();
                }
                $_SESSION['task_filters'] = $filters;
        }

	public function filterRecords(Head_Request $request, $pagingModel) {
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$filters = $request->get('filters');
		$this->setFiltersInSession($filters);
		$conditions = array();
		foreach ($filters as $name => $value) {
			if ($name == 'date') {
				switch ($value) {
					case 'today':	$conditions[$name] = array();
									$startDate = new DateTimeField(date('Y-m-d').' 00:00:00');
									$endDate = new DateTimeField(date('Y-m-d').' 23:59:59');
									$conditions[$name]['comparator'] = 'bw';
									$conditions[$name]['fieldName'] = 'due_date';
									$conditions[$name]['fieldValue'] = array('start' => $startDate->getDBInsertDateTimeValue(),	'end' => $endDate->getDBInsertDateTimeValue());
									break;

					case 'thisweek':$conditions[$name] = array();
									$thisweek0 = date('Y-m-d', strtotime('-1 week Sunday'));
									$thisWeekStartDateTime = new DateTimeField($thisweek0.' 00:00:00');
									$thisweek1 = date('Y-m-d', strtotime('this Saturday'));
									$thisWeekEndDateTime = new DateTimeField($thisweek1.' 23:59:59');

									$conditions[$name]['comparator'] = 'bw';
									$conditions[$name]['fieldName'] = 'due_date';
									$conditions[$name]['fieldValue'] = array('start' => $thisWeekStartDateTime->getDBInsertDateTimeValue(), 'end' => $thisWeekEndDateTime->getDBInsertDateTimeValue());
									break;

					case 'range' :	$conditions[$name] = array();
									$startDate = new DateTimeField($filters['startRange'].' 00:00:00');
									$endDate = new DateTimeField($filters['endRange'].' 23:59:59');

									$conditions[$name]['comparator'] = 'bw';
									$conditions[$name]['fieldName'] = 'due_date';
									$conditions[$name]['fieldValue'] = array('start' => $startDate->getDBInsertDateTimeValue(), 'end' => $endDate->getDBInsertDateTimeValue());
									break;



					case 'all' :	$name = 'status';
									$conditions[$name] = array();
									$conditions[$name]['comparator'] = 'n';
									$conditions[$name]['fieldValue'] = 'Completed';
									$conditions[$name]['fieldName'] = 'taskstatus';	
									break;
				}
			} else if ($name == 'status') {
				$conditions[$name] = array();
				$conditions[$name]['comparator'] = 'e';
				$conditions[$name]['fieldValue'] = $value;
				$conditions[$name]['fieldName'] = 'taskstatus';

			} else if ($name == 'assigned_user_id') {
				$conditions[$name] = array();
				$conditions[$name]['comparator'] = 'e';
				$conditions[$name]['fieldValue'] = $value;
				$conditions[$name]['fieldName'] = 'assigned_user_id';
			}
		}

		if ($request->get('priority') != null) {
			$conditions['priority'] = array();
			$conditions['priority']['comparator'] = 'e';
			$conditions['priority']['fieldValue'] = $request->get('priority');
			$conditions['priority']['fieldName'] = 'taskpriority';
		}

		$tasks = $moduleModel->getAllTasksbyPriority($conditions, $pagingModel);
		return $tasks;

	}

}

