<?php

class Head_KanbanAjax_View extends Head_ListAjax_View {
    function __construct() {
        parent::__construct();
        $this->exposeMethod('getListViewCount');
    }

    public function process(Head_Request $request) {
        $mode = $request->get('mode');
        if($this->isMethodExposed($mode)) {
            $this->invokeExposedMethod($mode, $request);
        }
    }

    public function getListViewCount(Head_Request $request){
	$moduleName = $request->get('module');
	$cvId = $request->get('cvid');
        $listViewModel = $this->getListViewEntriesModel($moduleName, $cvId);
        $count = $listViewModel->getListViewCount();

	$response = new Head_Response();
	$response->setResult(array('success'=>true, 'count'=>$count ));
	$response->emit();
    }

    function getListViewEntriesModel($moduleName, $cvId) {
        return Head_ListView_Model::getInstance($moduleName, $cvId);
    }
}
