<?php

class autocomplete extends rcube_plugin
{

  public $task = 'autocomplete';

  function init()
  {
    $this->add_hook('crm_emailaddress', array($this, 'get_crm_emailaddres'));
//    $this->add_hook('authenticate', array($this, 'authenticate'));
  }

/*  function startup($args)
  {
    // change action to login
    if (empty($_SESSION['user_id']) && !empty($_GET['_autologin']) && $this->is_localhost())
      $args['action'] = 'login';

    return $args;
  }
*/


}

	function get_crm_emailaddress(){


	}
