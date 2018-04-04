<?php

class quickcreate extends rcube_plugin
{
    public $task = 'mail';
    public $version = '1.0';
    private static $features = array();

    public function init()
    {
        global $RCMAIL;

        $this->include_script("js/quickcreate.js");
    }
}
