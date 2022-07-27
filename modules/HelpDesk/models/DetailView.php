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

class HelpDesk_DetailView_Model extends Head_DetailView_Model {

	/**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *                   array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams) {
		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$linkModelList = parent::getDetailViewLinks($linkParams);
		$recordModel = $this->getRecord();

		$emailModuleModel = Head_Module_Model::getInstance('Emails');
		if($currentUserModel->hasModulePermission($emailModuleModel->getId())) {
			$basicActionLink = array(
				'linktype' => 'DETAILVIEWBASIC',
				'linklabel' => 'LBL_SEND_EMAIL',
				'linkurl' => 'javascript:Head_Detail_Js.triggerSendEmail("index.php?module='.$this->getModule()->getName().
								'&view=MassActionAjax&mode=showComposeEmailForm&step=step1","Emails");',
				'linkicon' => ''
			);
			$linkModelList['DETAILVIEWBASIC'][] = Head_Link_Model::getInstanceFromValues($basicActionLink);
		}

		$quotesModuleModel = Head_Module_Model::getInstance('Faq');
		//TODO Fix Call to member function hasModuleActionPermission() on boolean - JoForce
	if($quotesModuleModel && $currentUserModel->hasModuleActionPermission($quotesModuleModel->getId(), 'CreateView')) {
			$basicActionLink = array(
				'linktype' => 'DETAILVIEW',
				'linklabel' => 'LBL_CONVERT_FAQ',
				'linkurl' => $recordModel->getConvertFAQUrl(),
				'linkicon' => ''
			);
			$linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($basicActionLink);
		}

		return $linkModelList;
	}

	/**
	 * Function to get the detail view widgets
	 * @return <Array> - List of widgets , where each widget is an Head_Link_Model
	 */
	public function getWidgets() {
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$widgetLinks = parent::getWidgets();
		$widgets = array();

        $documentsInstance = Head_Module_Model::getInstance('Documents');
		if($userPrivilegesModel->hasModuleActionPermission($documentsInstance->getId(), 'DetailView')) {
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

		foreach ($widgets as $widgetDetails) {
			$widgetLinks[] = Head_Link_Model::getInstanceFromValues($widgetDetails);
		}

		return $widgetLinks;
	}


}
