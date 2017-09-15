<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class DuplicateCheck_List_View extends Head_Index_View {

        public function checkPermission() {
                throw new AppException('LBL_PERMISSION_DENIED');
        }
}
