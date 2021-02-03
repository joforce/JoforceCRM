<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ********************************************************************************/

// Note is used to store customer information.
class Documents extends CRMEntity {

	var $log;
	var $db;
	var $table_name = "jo_notes";
	var $table_index= 'notesid';
	var $default_note_name_dom = array('Meeting jo_notes', 'Reminder');

	var $tab_name = Array('jo_crmentity','jo_notes','jo_notescf');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_notes'=>'notesid','jo_senotesrel'=>'notesid','jo_notescf'=>'notesid');

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_notescf', 'notesid');

	var $column_fields = Array();

	var $sortby_fields = Array('title','modifiedtime','filename','createdtime','lastname','filedownloadcount','smownerid');

	// This is used to retrieve related jo_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
				'Title'=>Array('notes'=>'title'),
				'File Name'=>Array('notes'=>'filename'),
				'Modified Time'=>Array('crmentity'=>'modifiedtime'),
				'Assigned To' => Array('crmentity'=>'smownerid'),
				'Folder Name' => Array('attachmentsfolder'=>'folderid')
				);
	var $list_fields_name = Array(
					'Title'=>'notes_title',
					'File Name'=>'filename',
					'Modified Time'=>'modifiedtime',
					'Assigned To'=>'assigned_user_id',
					'Folder Name' => 'folderid'
					 );

	var $search_fields = Array(
					'Title' => Array('notes'=>'notes_title'),
					'File Name' => Array('notes'=>'filename'),
					'Assigned To' => Array('crmentity'=>'smownerid'),
					'Folder Name' => Array('attachmentsfolder'=>'foldername')
		);

	var $search_fields_name = Array(
					'Title' => 'notes_title',
					'File Name' => 'filename',
					'Assigned To' => 'assigned_user_id',
					'Folder Name' => 'folderid'
	);
	var $list_link_field= 'notes_title';
	var $old_filename = '';
	//var $groupTable = Array('jo_notegrouprelation','notesid');

	var $mandatory_fields = Array('notes_title','createdtime' ,'modifiedtime','filename','filesize','filetype','filedownloadcount','assigned_user_id','document_source','notecontent','filelocationtype','folderid');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'title';
	var $default_sort_order = 'ASC';
	function Documents() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Documents() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Documents');
		$this->log->debug("Exiting Documents method ...");
	}

	function save_module($module)
	{
		global $log,$adb,$upload_badext;
		$insertion_mode = $this->mode;
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;
		//inserting into jo_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
		$filetype_fieldname = $this->getFileTypeFieldName();
		$filename_fieldname = $this->getFile_FieldName();
		if($this->column_fields[$filetype_fieldname] == 'I' ){
			if($_FILES[$filename_fieldname]['name'] != ''){
				$errCode=$_FILES[$filename_fieldname]['error'];
					if($errCode == 0){
						foreach($_FILES as $fileindex => $files)
						{
							if($files['name'] != '' && $files['size'] > 0){
								$filename = $_FILES[$filename_fieldname]['name'];
								$filename = from_html(preg_replace('/\s+/', '_', $filename));
								$filetype = $_FILES[$filename_fieldname]['type'];
								$filesize = $_FILES[$filename_fieldname]['size'];
								$filelocationtype = 'I';
								$binFile = sanitizeUploadFileName($filename, $upload_badext);
								$filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters
							}
						}

					}
			}elseif($this->mode == 'edit') {
				$fileres = $adb->pquery("select filetype, filesize,filename,filedownloadcount,filelocationtype from jo_notes where notesid=?", array($this->id));
				if ($adb->num_rows($fileres) > 0) {
					$filename = $adb->query_result($fileres, 0, 'filename');
					$filetype = $adb->query_result($fileres, 0, 'filetype');
					$filesize = $adb->query_result($fileres, 0, 'filesize');
					$filedownloadcount = $adb->query_result($fileres, 0, 'filedownloadcount');
					$filelocationtype = $adb->query_result($fileres, 0, 'filelocationtype');
				}
			}elseif($this->column_fields[$filename_fieldname]) {
				$filename = $this->column_fields[$filename_fieldname];
				$filesize = $this->column_fields['filesize'];
				$filetype = $this->column_fields['filetype'];
				$filelocationtype = $this->column_fields[$filetype_fieldname];
				$filedownloadcount = 0;
			} else {
				$filelocationtype = 'I';
				$filetype = '';
				$filesize = 0;
				$filedownloadcount = null;
			}
		} else if($this->column_fields[$filetype_fieldname] == 'E' ){
			$filelocationtype = 'E';
			$filename = $this->column_fields[$filename_fieldname];
			// If filename does not has the protocol prefix, default it to http://
			// Protocol prefix could be like (https://, smb://, file://, \\, smb:\\,...)
			if(!empty($filename) && !preg_match('/^\w{1,5}:\/\/|^\w{0,3}:?\\\\\\\\/', trim($filename), $match)) {
				$filename = "http://$filename";
			}
			$filetype = '';
			$filesize = 0;
			$filedownloadcount = null;
		}
		$query = "UPDATE jo_notes SET filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ? WHERE notesid = ?";
		$re=$adb->pquery($query,array(decode_html($filename),$filesize,$filetype,$filelocationtype,$filedownloadcount,$this->id));
		//Inserting into attachments table
		if($filelocationtype == 'I') {
			$this->insertIntoAttachment($this->id,'Documents');
		}else{
			$query = "delete from jo_seattachmentsrel where crmid = ?";
			$qparams = array($this->id);
			$adb->pquery($query, $qparams);
		}
		//set the column_fields so that its available in the event handlers
		$this->column_fields['filename'] = $filename;
		$this->column_fields['filesize'] = $filesize;
		$this->column_fields['filetype'] = $filetype;
		$this->column_fields['filedownloadcount'] = $filedownloadcount;
	}


	/**
	 *      This function is used to add the jo_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the jo_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = modlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	/**    Function used to get the sort order for Documents listview
	*      @return string  $sorder - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['NOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		else
			$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**     Function used to get the order by value for Documents listview
	*       @return string  $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['NOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
	*/
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");

		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}

		if (isset($_REQUEST['order_by']))
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		else
			$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($use_default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}

	/**
	 * Function used to get the sort order for Documents listview
	 * @return String $sorder - sort order for a given folder.
	 */
	function getSortOrderForFolder($folderId) {
		if(isset($_REQUEST['sorder']) && $_REQUEST['folderid'] == $folderId) {
			$sorder = $this->db->sql_escape_string($_REQUEST['sorder']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_SORT_ORDER']) &&
					!empty($_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId])) {
				$sorder = $_SESSION['NOTES_FOLDER_SORT_ORDER'][$folderId];
		} else {
			$sorder = $this->default_sort_order;
		}
		return $sorder;
	}

	/**
	 * Function used to get the order by value for Documents listview
	 * @return String order by column for a given folder.
	 */
	function getOrderByForFolder($folderId) {
		$use_default_order_by = '';
		if(PerformancePrefs::getBoolean('LISTVIEW_DEFAULT_SORTING', true)) {
			$use_default_order_by = $this->default_order_by;
		}
		if (isset($_REQUEST['order_by'])  && $_REQUEST['folderid'] == $folderId) {
			$order_by = $this->db->sql_escape_string($_REQUEST['order_by']);
		} elseif(is_array($_SESSION['NOTES_FOLDER_ORDER_BY']) &&
				!empty($_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId])) {
			$order_by = $_SESSION['NOTES_FOLDER_ORDER_BY'][$folderId];
		} else {
			$order_by = ($use_default_order_by);
		}
		return $order_by;
	}

	/** Function to export the notes in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Documents Query.
	*/
	function create_export_query($where)
	{
		global $log,$current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("includes/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Documents", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT $fields_list, case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name" .
				" FROM jo_notes
				inner join jo_crmentity
					on jo_crmentity.crmid=jo_notes.notesid
				LEFT JOIN jo_attachmentsfolder on jo_notes.folderid=jo_attachmentsfolder.folderid
				LEFT JOIN jo_users ON jo_crmentity.smownerid=jo_users.id " .
				" LEFT JOIN jo_groups ON jo_crmentity.smownerid=jo_groups.groupid "
				;
		$query .= getNonAdminAccessControlQuery('Documents',$current_user);
		$where_auto=" jo_crmentity.deleted=0";
		if($where != "")
			$query .= "  WHERE ($where) AND ".$where_auto;
		else
			$query .= "  WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
				return $query;
	}

	function del_create_def_folder($query)
	{
		global $adb;
		$dbQuery = $query." and jo_attachmentsfolderfolderid.folderid = 0";
		$dbresult = $adb->pquery($dbQuery,array());
		$noofnotes = $adb->num_rows($dbresult);
		if($noofnotes > 0)
		{
			$folderQuery = "select folderid from jo_attachmentsfolder";
			$folderresult = $adb->pquery($folderQuery,array());
			$noofdeffolders = $adb->num_rows($folderresult);

			if($noofdeffolders == 0)
			{
				$insertQuery = "insert into jo_attachmentsfolder values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
				$insertresult = $adb->pquery($insertQuery,array());
			}
		}

	}

	function insertintonotesrel($relid,$id)
	{
		global $adb;
		$dbQuery = "insert into jo_senotesrel values ( ?, ? )";
		$dbresult = $adb->pquery($dbQuery,array($relid,$id));
	}

	/*function save_related_module($module, $crmid, $with_module, $with_crmid){
	}*/


	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module,$queryplanner){
		$moduletable = $this->table_name;
		$moduleindex = $this->tab_name_index[$moduletable];
		$query = "from $moduletable
			inner join jo_crmentity on jo_crmentity.crmid=$moduletable.$moduleindex";
		if ($queryplanner->requireTable("jo_attachmentsfolder")){
			$query .= " inner join jo_attachmentsfolder on jo_attachmentsfolder.folderid=$moduletable.folderid";
		}
		if ($queryplanner->requireTable("jo_groups".$module)){
			$query .= " left join jo_groups as jo_groups".$module." on jo_groups".$module.".groupid = jo_crmentity.smownerid";
		}
		if($queryplanner->requireTable('jo_createdby'.$module)){
			$query .= " LEFT JOIN jo_users AS jo_createdby$module ON jo_createdby$module.id = jo_crmentity.smcreatorid";
		}
		if ($queryplanner->requireTable("jo_users".$module)){
			$query .= " left join jo_users as jo_users".$module." on jo_users".$module.".id = jo_crmentity.smownerid";
		}
		$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
		$query .= " left join jo_notescf on jo_notes.notesid = jo_notescf.notesid";
		$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";
		if ($queryplanner->requireTable("jo_lastModifiedBy".$module)){
			$query .= " left join jo_users as jo_lastModifiedBy".$module." on jo_lastModifiedBy".$module.".id = jo_crmentity.modifiedby ";
		}
		return $query;

	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();
		$matrix->setDependency("jo_crmentityDocuments",array("jo_groupsDocuments","jo_usersDocuments","jo_lastModifiedByDocuments"));

		if (!$queryplanner->requireTable('jo_notes', $matrix)) {
			return '';
		}
		$matrix->setDependency("jo_notes",array("jo_crmentityDocuments","jo_attachmentsfolder"));
		// TODO Support query planner
		$query = $this->getRelationQuery($module,$secmodule,"jo_notes","notesid", $queryplanner);
		$query .= " left join jo_notescf on jo_notes.notesid = jo_notescf.notesid";
		if ($queryplanner->requireTable("jo_crmentityDocuments",$matrix)){
			$query .=" left join jo_crmentity as jo_crmentityDocuments on jo_crmentityDocuments.crmid=jo_notes.notesid and jo_crmentityDocuments.deleted=0";
		}
		if ($queryplanner->requireTable("jo_attachmentsfolder")){
			$query .=" left join jo_attachmentsfolder on jo_attachmentsfolder.folderid=jo_notes.folderid";
		}
		if ($queryplanner->requireTable("jo_groupsDocuments")){
			$query .=" left join jo_groups as jo_groupsDocuments on jo_groupsDocuments.groupid = jo_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("jo_usersDocuments")){
			$query .=" left join jo_users as jo_usersDocuments on jo_usersDocuments.id = jo_crmentityDocuments.smownerid";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByDocuments")){
			$query .=" left join jo_users as jo_lastModifiedByDocuments on jo_lastModifiedByDocuments.id = jo_crmentityDocuments.modifiedby ";
		}
		if ($queryplanner->requireTable("jo_createdbyDocuments")){
			$query .= " left join jo_users as jo_createdbyDocuments on jo_createdbyDocuments.id = jo_crmentityDocuments.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array();
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Documents Related Records
		$se_q = 'SELECT crmid FROM jo_senotesrel WHERE notesid = ?';
		$se_res = $this->db->pquery($se_q, array($id));
		if ($this->db->num_rows($se_res) > 0) {
			for($k=0;$k < $this->db->num_rows($se_res);$k++)
			{
				$se_id = $this->db->query_result($se_res,$k,"crmid");
				$params = array($id, RB_RECORD_DELETED, 'jo_senotesrel', 'notesid', 'crmid', $se_id);
				$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$sql = 'DELETE FROM jo_senotesrel WHERE notesid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$sql = 'DELETE FROM jo_senotesrel WHERE notesid = ? AND (crmid = ? OR crmid IN (SELECT contactid FROM jo_contactdetails WHERE accountid=?))';
			$this->db->pquery($sql, array($id, $return_id, $return_id));
		} else {
			$sql = 'DELETE FROM jo_senotesrel WHERE notesid = ? AND crmid = ?';
			$this->db->pquery($sql, array($id, $return_id));

			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}


// Function to get fieldname for uitype 27 assuming that documents have only one file type field

	function getFileTypeFieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from jo_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filetype_uitype = 27;
		$res = $adb->pquery($query,array($tabid,$filetype_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;

	}

//	Function to get fieldname for uitype 28 assuming that doc has only one file upload type

	function getFile_FieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from jo_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$filename_uitype = 28;
		$res = $adb->pquery($query,array($tabid,$filename_uitype));
		$fieldname = null;
		if(isset($res)){
			$rowCount = $adb->num_rows($res);
			if($rowCount > 0){
				$fieldname = $adb->query_result($res,0,'fieldname');
			}
		}
		return $fieldname;
	}

	/**
	 * Check the existence of folder by folderid
	 */
	function isFolderPresent($folderid) {
		global $adb;
		$result = $adb->pquery("SELECT folderid FROM jo_attachmentsfolder WHERE folderid = ?", array($folderid));
		if(!empty($result) && $adb->num_rows($result) > 0) return true;
		return false;
	}

	/**
	 * Customizing the restore procedure.
	 */
	function restore($modulename, $id) {
		parent::restore($modulename, $id);

		global $adb;
		$fresult = $adb->pquery("SELECT folderid FROM jo_notes WHERE notesid = ?", array($id));
		if(!empty($fresult) && $adb->num_rows($fresult)) {
			$folderid = $adb->query_result($fresult, 0, 'folderid');
			if(!$this->isFolderPresent($folderid)) {
				// Re-link to default folder
				$adb->pquery("UPDATE jo_notes set folderid = 1 WHERE notesid = ?", array($id));
			}
		}
	}

	function getQueryByModuleField($module, $fieldname, $srcrecord, $query) {
		if($module == "MailManager") {
			$tempQuery = split('WHERE', $query);
			if(!empty($tempQuery[1])) {
				$where = " jo_notes.filelocationtype = 'I' AND jo_notes.filename != '' AND jo_notes.filestatus != 0 AND ";
				$query = $tempQuery[0].' WHERE '.$where.$tempQuery[1];
			} else{
				$query = $tempQuery[0].' WHERE '.$tempQuery;
			}
			return $query;
		}
	}

	/**
	 * Function to check the module active and user action permissions before showing as link in other modules
	 * like in more actions of detail view.
	 */
	static function isLinkPermitted($linkData) {
		$moduleName = "Documents";
		if(modlib_isModuleActive($moduleName) && isPermitted($moduleName, 'CreateView') == 'yes') {
			return true;
		}
		return false;
	}

	/**
	 * Function to get query for related list in Documents module
	 */
	function get_related_list($id, $cur_tab_id, $rel_tab_id) {
		$related_module = modlib_getModuleNameById($rel_tab_id);
		$other = CRMEntity::getInstance($related_module);
		modlib_setup_modulevars('Documents', $this);
		modlib_setup_modulevars($related_module, $other);

		$returnset = "&return_module=Documents&return_action=CallRelatedList&return_id=".$id;

		$query = "SELECT jo_crmentity.*, $other->table_name.*";

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		if (!empty($other->related_tables)) {
			foreach ($other->related_tables as $tname => $relmap) {
				$query .= ", $tname.*";
				if (empty($relmap[1]))
					$relmap[1] = $other->table_name;
				if (empty($relmap[2]))
					$relmap[2] = $relmap[0];
				$more_relation .= " LEFT JOIN $tname ON $tname.$relmap[0] = $relmap[1].$relmap[2]";
			}
		}
		$query .= " FROM $other->table_name";
		$query .= " INNER JOIN jo_crmentity ON jo_crmentity.crmid = $other->table_name.$other->table_index";
		$query .= " INNER JOIN jo_senotesrel ON jo_senotesrel.crmid = jo_crmentity.crmid ".$more_relation;
		$query .= " LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";
		$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";
		$query .= " WHERE jo_crmentity.deleted = 0 AND jo_senotesrel.notesid=$id";

		//eliminate lead converted 
		if($related_module == 'Leads') {
			$query .= " AND jo_leaddetails.converted=0 ";
		}

		$return_value = GetRelatedList('Documents', $related_module, $other, $query, '', $returnset);
		return $return_value;
	}
}
?>