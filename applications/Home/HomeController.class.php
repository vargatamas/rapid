<?php

class HomeController {

    public static $template;

    public function __construct($args = array()) {
        
    }
    
    public function indexAction($args = array()) {
        // This is only for clean up after installation.
        // You can remove these lines right after installation. 
        if ( 'newly-installed' == $args[0] ) {
            @unlink(Rpd::$c['rapid']['installerFile']);
            @unlink(Rpd::$c['rapid']['updaterFile']);
        }
    }

}