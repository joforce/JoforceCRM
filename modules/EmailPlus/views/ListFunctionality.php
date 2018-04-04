<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

                $url = $site_URL . 'modules/EmailPlus/roundcube/';
                $config['autologinActive'] = true;
                if ($config['autologinActive'] == 'true') {
                        $account = EmailPlus_Autologin_Model::getAutologinUsers();
                        $checkUserId = $adb->pquery('select jo_user_id from users where username = ?', array($account['email']));
                        $IsUpdated  = $adb->query_result($checkUserId, 0, 'jo_user_id');
                        if(empty($IsUpdated)){
                                $adb->pquery('update users set jo_user_id = ? where username = ?', array($user_id, $account['email']));
                        }
//                        echo"<pre>";print_r($account);die;
                        if ($account) {
                                $url .= '?server='.$account['name'];
                                $url .= '&port='.$account['port'];
                                require_once 'modules/EmailPlus/RoundcubeLogin.class.php';
                                $rcl = new RoundcubeLogin($url, false);
                                try {
                                        if ($rcl->isLoggedIn()) {
                                                if ($rcl->getUsername() != $account['email']) {
                                                        $rcl->logout();
                                                        $rc_pass = base64_decode($account['password']);
                                                        $rcl->login($account['email'], $rc_pass);
                                                }
                                        } else {                                        
                                                 $rc_pass = base64_decode($account['password']);
                                                $result = $rcl->login($account['email'], $rc_pass);
                                                if(empty($result)){
                                                      $viewer->assign('FAILED', true);
                                                }
                                        }
                                } catch (RoundcubeLoginException $ex) {
                                        $log = vglobal('log');
                                        $log->error('EmailPlus_List_View|RoundcubeLoginException: ' . $ex->getMessage());
                                }
                        }
                        else
                                 header('Location: '.$site_URL.'EmailPlus/view/ServerSettings');
                }
                $viewer->assign('URL', $url);
                $viewer->view('List.tpl', $moduleName);

?>
