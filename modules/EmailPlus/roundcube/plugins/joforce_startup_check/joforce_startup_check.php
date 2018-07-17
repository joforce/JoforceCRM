<?php
/**
 * Check CRM User LoggedIn
 */
class joforce_startup_check extends rcube_plugin
{
    public $task = 'login';

    function init()
    {
        $jo_hash_key = $_REQUEST['jo_token'];
        $this->rc = rcmail::get_instance();
        $app_unique_key = $this->rc->config->get('des_key');
        // Validate the Hash key
        $hash = md5($app_unique_key . date('dFYaG'));
        if($hash != $jo_hash_key)  {
            die('Permission denied');
        }
    }
}