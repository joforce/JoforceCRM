<?php
/*
Install:
1) create a folder: disablecsrf inside 'plugins/' directory.
2) place this file there and name it: disablecsrf.php
3) go to config/config.inc.php, and add it to plugins, like:
   $config['plugins'] = array('disablecsrf');

CSRF should now be disabled for login.
*/


/**
 * Sample plugin to disable csrf for RoundCube mail (tested only on 1.0.3)
 *
 * @license MIT
 * @author huglester@gmail.com
 */
class disablecsrf extends rcube_plugin
{
  public $task = 'login';

  function init()
  {
    $this->add_hook('authenticate', array($this, 'authenticate'));
  }

  function authenticate($args)
  {
    $args['valid'] = true;

    return $args;
  }

}
