<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class EmailPlus_Module_Model extends Head_Module_Model {

	public function isQuickCreateSupported() {

		return false;
	}

	public function isQuickSearchEnabled() {

		return false;
	}

	public function checkIonCubeLoaded(){
        return true;
		return extension_loaded('ionCube Loader');
	}

}
