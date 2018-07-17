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

class Inventory_DetailView_Model extends Head_DetailView_Model {

	/**
	 * Function to get the detail view links (links and widgets)
	 * @param <array> $linkParams - parameters which will be used to calicaulate the params
	 * @return <array> - array of link models in the format as below
	 *					 array('linktype'=>list of link models);
	 */
	public function getDetailViewLinks($linkParams) {
		global $site_URL, $adb;
		$linkModelList = parent::getDetailViewLinks($linkParams);
		$recordModel = $this->getRecord();
		$moduleName = $recordModel->getmoduleName();

		$recordId = $recordModel->getId();
		$getPDFDetails = $adb->pquery('select * from jo_pdfmaker where name = ? and status = ?', array($moduleName, 1));
                $tempId = $adb->query_result($getPDFDetails, 0, 'pdfmakerid');

		$export_pdf_url = "". $site_URL ."index.php?module=PDFMaker&view=DownloadPDF&recordId=$recordId&selected_template=$tempId&sourceModule=$moduleName";

		if(Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $recordModel->getId())) {
			$detailViewLinks = array(
					'linklabel' => vtranslate('LBL_EXPORT_TO_PDF', $moduleName),
					'linkurl' => $export_pdf_url,
					'linkicon' => ''
						);
			$linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($detailViewLinks);

			$sendEmailLink = array(
			                'linklabel' => vtranslate('LBL_SEND_MAIL_PDF', $moduleName),
                            'linkurl' => "javascript:PDFMaker_Helper_Js.sendEmail($recordId)",
			                'linkicon' => ''
				              );

            $linkModelList['DETAILVIEW'][] = Head_Link_Model::getInstanceFromValues($sendEmailLink);
		}

		return $linkModelList;
	}

}
