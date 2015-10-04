<?php

    /*
     *
     * Welcome in Rapid
     *
    */
    
    ob_start();
    header('Content-Type: text/html; charset=utf-8');
    require_once 'rapid' . DIRECTORY_SEPARATOR . 'rapid.php';
    new Rapid();
    ob_end_flush();

?>