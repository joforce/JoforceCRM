<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/
include_once('libraries/modlib/Head/Package.php');

/**
 * Provides API to package vtiger CRM language files.
 * @package modlib
 */
class Head_ThemeExport extends Head_Package {
    const TABLENAME = 'jo_layoutskins';

    /**
     * Constructor
     */
    function __construct() {
            parent::__construct();
    }

    /**
     * Generate unique id for insertion
     * @access private
     */
    static function __getUniqueId() {
            global $adb;
            return $adb->getUniqueID(self::TABLENAME);
    }

    /**
     * Initialize Export
     * @access private
     */
    function __initExport($layoutName, $themeName) {
            // Security check to ensure file is withing the web folder.
            Head_Utils::checkFileAccessForInclusion("layouts/$layoutName/skins/$themeName/style.less");

            $this->_export_modulexml_file = fopen($this->__getManifestFilePath(), 'w');
            $this->__write("<?xml version='1.0'?>\n");
    }

    /**
     * Export Module as a zip file.
     * @param Head_Module Instance of module
     * @param Path Output directory path
     * @param String Zipfilename to use
     * @param Boolean True for sending the output as download
     */
    function export($layoutName, $themeName, $todir='', $zipfilename='', $directDownload=false) {
            $this->__initExport($layoutName, $themeName);

            // Call layout export function
            $this->export_Theme($layoutName, $themeName);

            $this->__finishExport();

            // Export as Zip
            if($zipfilename == '') $zipfilename = "$layoutName-$themeName" . date('YmdHis') . ".zip";
            $zipfilename = "$this->_export_tmpdir/$zipfilename";

            $zip = new Head_Zip($zipfilename);

            // Add manifest file
            $zip->addFile($this->__getManifestFilePath(), "manifest.xml");

            // Copy module directory
            $zip->copyDirectoryFromDisk("layouts/$layoutName/skins/$themeName");

            $zip->save();

            if($todir) {
                    copy($zipfilename, $todir);
            }
            
            if($directDownload) {
                    $zip->forceDownload($zipfilename);
                    unlink($zipfilename);
            }
            $this->__cleanupExport();
    }

    /**
     * Export Language Handler
     * @access private
     */
    function export_Theme($layoutName, $themeName) {
            global $adb;

            $sqlresult = $adb->pquery("SELECT * FROM jo_layoutskins WHERE name = ?", array($themeName));
            $layoutresultrow = $adb->fetch_array($sqlresult);

            $resultThemename  = decode_html($layoutresultrow['name']);
            $resultThemelabel = decode_html($layoutresultrow['label']);
            $resultthemeparent = decode_html($layoutresultrow['parent']);
            
            if(!empty($resultThemename)){
                $themeName = $resultThemename;
            }
            
            if(!empty($resultThemelabel)){
                $themelabel = $resultThemename;
            }else{
                $themelabel = $themeName;
            }
            
            if(!empty($resultthemeparent)){
                $themeparent = $resultthemeparent;
            }else{
                $themeparent = $layoutName;
            }

            $this->openNode('module');
            $this->outputNode(date('Y-m-d H:i:s'),'exporttime');
            $this->outputNode($themeName, 'name');
            $this->outputNode($themelabel, 'label');
            $this->outputNode($themeparent, 'parent');

            $this->outputNode('theme', 'type');

            // Export dependency information
            $this->export_Dependencies();

            $this->closeNode('module');
    }

    /**
     * Export vtiger dependencies
     * @access private
     */
    function export_Dependencies() {
            global $jo_current_version, $adb;

            $vtigerMinVersion = $jo_current_version;
            $vtigerMaxVersion = false;

            $this->openNode('dependencies');
            $this->outputNode($vtigerMinVersion, 'jo_version');
            if($vtigerMaxVersion !== false)	$this->outputNode($vtigerMaxVersion, 'jo_max_version');
            $this->closeNode('dependencies');
    }


    /**
     * Initialize Language Schema
     * @access private
     */
    static function __initSchema() {
            $hastable = Head_Utils::CheckTable(self::TABLENAME);
            if(!$hastable) {
                    Head_Utils::CreateTable(
                            self::TABLENAME,
                            '(id INT NOT NULL PRIMARY KEY,
                            name VARCHAR(50), label VARCHAR(30), parent VARCHAR(100), lastupdated DATETIME, isdefault INT(1), active INT(1))',
                            true
                    );
                    global $languages, $adb;
                    foreach($languages as $langkey=>$langlabel) {
                            $uniqueid = self::__getUniqueId();
                            $adb->pquery('INSERT INTO '.self::TABLENAME.'(id,name,label,parent,lastupdated,active) VALUES(?,?,?,?,?,?)',
                                    Array($uniqueid, $langlabel,$langkey,$langlabel,date('Y-m-d H:i:s',time()), 1));
                    }
            }
    }

    /**
     * Register language pack information.
     */
    static function register($label, $name='',$parent='', $isdefault=false, $isactive=true, $overrideCore=false) {
            self::__initSchema();

            $prefix = trim($prefix);
            // We will not allow registering core layouts unless forced
            if(strtolower($name) == 'vlayout' && $overrideCore == false) return;

            $useisdefault = ($isdefault)? 1 : 0;
            $useisactive  = ($isactive)?  1 : 0;

            global $adb;
            $checkres = $adb->pquery('SELECT * FROM '.self::TABLENAME.' WHERE name=?', Array($name));
            $datetime = date('Y-m-d H:i:s');
            if($adb->num_rows($checkres)) {
                    $id = $adb->query_result($checkres, 0, 'id');
                    $adb->pquery('UPDATE '.self::TABLENAME.' set label=?, name=?, parent=?, lastupdated=?, isdefault=?, active=? WHERE id=?',
                            Array($label, $name, $parent, $datetime, $useisdefault, $useisactive, $id));
            } else {
                    $uniqueid = self::__getUniqueId();
                    $adb->pquery('INSERT INTO '.self::TABLENAME.' (id,name,label,parent,lastupdated,isdefault,active) VALUES(?,?,?,?,?,?)',
                            Array($uniqueid, $name, $label, $parent, $datetime, $useisdefault, $useisactive));
            }
            self::log("Registering Language $label [$prefix] ... DONE");		
    }

}