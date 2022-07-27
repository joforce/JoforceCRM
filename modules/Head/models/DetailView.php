<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Head_DetailView_Model extends Head_Base_Model {

	protected $module = false;
	protected $record = false;

	/**
	 * Function to get Module instance
	 * @return <Head_Module_Model>
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * Function to set the module instance
	 * @param <Head_Module_Model> $moduleInstance - module model
	 * @return Head_DetailView_Model>
	 */
	public function setModule($moduleInstance) {
		$this->module = $moduleInstance;
		return $this;
	}

	/**
	 * Function to get the Record model
	 * @return <Head_Record_Model>
	 */
	public function getRecord() {
		return $this->record;
	}

	/**
	 * Function to set the record instance3
	 * @param <type> $recordModuleInstance - record model
	 * @return Head_DetailView_Model
	 */
	public function setRecord($recordModuleInstance) {
		$this->record = $recordModuleInstance;
		return $this;
	}

	/**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *                   array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams) {
		global $site_URL;
		$linkTypes = array('DETAILVIEWBASIC','DETAILVIEW');
		$moduleModel = $this->getModule();
		$recordModel = $this->getRecord();

		$moduleName = $moduleModel->getName();
		$recordId = $recordModel->getId();

		$detailViewLink = array();
		$linkModelList = array();

		if(Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $recordId)) {
			$detailViewLinks[] = array(
                                        'linktype' => 'DETAILVIEWBASIC',
                                        'linklabel' => 'LBL_ACTIVITY',
                                        'linkurl' => $recordModel->getRecordActivityUrl(),
                                        'linkicon' => ''
                        );
		}

		if(Users_Privileges_Model::isPermitted($moduleName, 'EditView', $recordId)) {
			$detailViewLinks[] = array(
					'linktype' => 'DETAILVIEWBASIC',
					'linklabel' => 'LBL_EDIT',
					'linkurl' => $recordModel->getEditViewUrl(),
					'linkicon' => ''
			);

			foreach ($detailViewLinks as $detailViewLink) {
				$linkModelList['DETAILVIEWBASIC'][] = Head_Link_Model::getInstanceFromValues($detailViewLink);
			}
		}

		if(Users_Privileges_Model::isPermitted($moduleName, 'Delete', $recordId)) {
			$deletelinkModel = array(
					'linktype' => 'DETAILVIEW',
					'linklabel' => sprintf("%s %s", getTranslatedString('LBL_DELETE', $moduleName), vtranslate('SINGLE_'. $moduleName, $moduleName)),
					'linkurl' => 'javascript:Head_Detail_Js.deleteRecord("' . $recordModel->getDeleteUrl() . '")',
					'linkicon' => ''
			);
			$linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($deletelinkModel);
		}

		if($moduleModel->isDuplicateOptionAllowed('CreateView', $recordId)) {
			$duplicateLinkModel = array(
						'linktype' => 'DETAILVIEWBASIC',
						'linklabel' => 'LBL_DUPLICATE',
						'linkurl' => $recordModel->getDuplicateRecordUrl(),
						'linkicon' => ''
				);
			$linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($duplicateLinkModel);
		}

		if($this->getModule()->isModuleRelated('Emails') && Head_RecipientPreference_Model::getInstance($this->getModuleName())) {
			$emailRecpLink = array('linktype' => 'DETAILVIEW',
								'linklabel' => vtranslate('LBL_EMAIL_RECIPIENT_PREFS',  $this->getModuleName()),
								'linkurl' => 'javascript:Head_Index_Js.showRecipientPreferences("'.$this->getModuleName().'");',
								'linkicon' => '');
			$linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($emailRecpLink);
		}

		$linkModelListDetails = Head_Link_Model::getAllByType($moduleModel->getId(),$linkTypes,$linkParams);
		foreach($linkTypes as $linkType) {
			if(!empty($linkModelListDetails[$linkType])) {
				foreach($linkModelListDetails[$linkType] as $linkModel) {
					// Remove view history, needed in vtiger5 to see history but not in vtiger6
					if($linkModel->linklabel == 'View History') {
						continue;
					}
					$linkModelList[$linkType][] = $linkModel;
				}
			}
			unset($linkModelListDetails[$linkType]);
		}

		$relatedLinks = $this->getDetailViewRelatedLinks();

		foreach($relatedLinks as $relatedLinkEntry) {
			$relatedLink = Head_Link_Model::getInstanceFromValues($relatedLinkEntry);
			$linkModelList[$relatedLink->getType()][] = $relatedLink;
		}

		$widgets = $this->getWidgets();
		foreach($widgets as $widgetLinkModel) {
			$linkModelList['DETAILVIEWWIDGET'][] = $widgetLinkModel;
		}

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($currentUserModel->isAdminUser()) {
			$settingsLinks = $moduleModel->getSettingLinks();
			foreach($settingsLinks as $settingsLink) {
				$linkModelList['DETAILVIEWSETTING'][] = Head_Link_Model::getInstanceFromValues($settingsLink);
			}
		}
		return $linkModelList;
	}

	/**
	 * Function to get the detail view related links
	 * @return <array> - list of links parameters
	 */
	public function getDetailViewRelatedLinks() {
		global $site_URL;
		$recordModel = $this->getRecord();
		$moduleName = $recordModel->getModuleName();
		$parentModuleModel = $this->getModule();
		$relatedLinks = array();

		if($parentModuleModel->isSummaryViewSupported()) {
			$relatedLinks = array(array(
				'linktype' => 'DETAILVIEWTAB',
				'linklabel' => vtranslate('LBL_SUMMARY', $moduleName),
				'linkKey' => 'LBL_RECORD_SUMMARY',
				'linkurl' => $recordModel->getDetailViewUrl() . '/mode/showDetailViewByMode?requestMode=summary',
				'linkicon' => $site_URL.'/layouts/skins/images/summary_summary.png'
			));
		}
		//link which shows the summary information(generally detail of record)
		$relatedLinks[] = array(
				'linktype' => 'DETAILVIEWTAB',
				'linklabel' => vtranslate('LBL_DETAILS', $moduleName),
				'linkKey' => 'LBL_RECORD_DETAILS',
				'linkurl' => $recordModel->getDetailViewUrl().'/mode/showDetailViewByMode?requestMode=full',
				'linkicon' => $site_URL.'/layouts/skins/images/summary_detail.png'
		);

		if($parentModuleModel->isTrackingEnabled()) {
			$relatedLinks[] = array(
					'linktype' => 'DETAILVIEWTAB',
					'linklabel' => 'LBL_UPDATES',
					'linkurl' => $recordModel->getDetailViewUrl().'/mode/showRecentActivities?page=1',
					'linkicon' => $site_URL.'/layouts/skins/images/summary_history.png'
			);
		}

		$relationModels = $parentModuleModel->getRelations();

		foreach($relationModels as $relation) {
			//TODO : Way to get limited information than getting all the information
			$link = array(
					'linktype' => 'DETAILVIEWRELATED',
					'linklabel' => $relation->get('label'),
					'linkurl' => $relation->getListUrl($recordModel),
					'linkicon' => '',
					'relatedModuleName' => $relation->get('relatedModuleName'),
					'linkid' => $relation->getId()
			);
			$relatedLinks[] = $link;
		}

		return $relatedLinks;
	}

	/**
	 * Function to get the detail view widgets
	 * @return <Array> - List of widgets , where each widget is an Head_Link_Model
	 */
	public function getWidgets() {
		$moduleModel = $this->getModule();
		$widgets = array();

		if($moduleModel->isTrackingEnabled()) {
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'LBL_UPDATES',
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&mode=showRecentActivities&page=1&limit=5',
			);
		}

		$modCommentsModel = Head_Module_Model::getInstance('ModComments');
		if($moduleModel->isCommentEnabled() && $modCommentsModel->isPermitted('DetailView')) {
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'ModComments',
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&mode=showRecentComments&page=1&limit=5'
			);
		}

		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$documentsInstance = Head_Module_Model::getInstance('Documents');
		if($userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'DetailView') && $moduleModel->isModuleRelated('Documents')) {
			$createPermission = $userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'CreateView');
			$widgets[] = array(
					'linktype' => 'DETAILVIEWWIDGET',
					'linklabel' => 'Documents',
					'linkName'	=> $documentsInstance->getName(),
					'linkurl' => 'module='.$this->getModuleName().'&view=Detail&record='.$this->getRecord()->getId().
							'&relatedModule=Documents&mode=showRelatedRecords&page=1&limit=5',
					'action'	=>	($createPermission == true) ? array('Add') : array(),
					'actionURL' =>	$documentsInstance->getQuickCreateUrl()
			);
		}

		$widgetLinks = array();
		foreach ($widgets as $widgetDetails) {
			$widgetLinks[] = Head_Link_Model::getInstanceFromValues($widgetDetails);
		}
		return $widgetLinks;
	}

	/**
	 * Function to get the Quick Links for the Detail view of the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Head_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$moduleLinks = $this->getModule()->getSideBarLinks($linkTypes);

		$listLinkTypes = array('DETAILVIEWSIDEBARLINK', 'DETAILVIEWSIDEBARWIDGET');
		$listLinks = Head_Link_Model::getAllByType($this->getModule()->getId(), $listLinkTypes);

		if($listLinks['DETAILVIEWSIDEBARLINK']) {
			foreach($listLinks['DETAILVIEWSIDEBARLINK'] as $link) {
				$link->linkurl = $link->linkurl.'&record='.$this->getRecord()->getId().'&source_module='.$this->getModule()->getName();
				$moduleLinks['SIDEBARLINK'][] = $link;
			}
		}

		if($listLinks['DETAILVIEWSIDEBARWIDGET']) {
			foreach($listLinks['DETAILVIEWSIDEBARWIDGET'] as $link) {
				$link->linkurl = $link->linkurl.'&record='.$this->getRecord()->getId().'&source_module='.$this->getModule()->getName();
				$moduleLinks['SIDEBARWIDGET'][] = $link;
			}
		}

		return $moduleLinks;
	}

	/**
	 * Function to get the module label
	 * @return <String> - label
	 */
	public function getModuleLabel() {
		return $this->getModule()->get('label');
	}

	/**
	 *  Function to get the module name
	 *  @return <String> - name of the module
	 */
	public function getModuleName() {
		return $this->getModule()->get('name');
	}

	/**
	 * Function to get the instance
	 * @param <String> $moduleName - module name
	 * @param <String> $recordId - record id
	 * @return <Head_DetailView_Model>
	 */
	public static function getInstance($moduleName,$recordId) {
		$modelClassName = Head_Loader::getComponentClassName('Model', 'DetailView', $moduleName);
		$instance = new $modelClassName();

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);

		return $instance->setModule($moduleModel)->setRecord($recordModel);
	}
}
