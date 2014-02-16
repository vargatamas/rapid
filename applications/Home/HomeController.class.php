<?php

class HomeController {

    public static $template;

    public function indexAction($args = array()) {
        if ( 'newly-installed' == $args[0] ) {
            @unlink(Rpd::$c['rapid']['installerFile']);
            @unlink(Rpd::$c['rapid']['updaterFile']);
        }
    }

}