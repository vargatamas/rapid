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

    /**
     * Upload images from WYSIWYG (Summernote) if you using Summernote - otherwise you can remove this function (depends on administrator.pack.js)
     */ 
    public function imageUploadAction($args = array()) {
        if ( !@is_dir(Rpd::$c['rapid']['filesDir'] . "images") ) @mkdir(Rpd::$c['rapid']['filesDir'] . 'images');
        if ( !@is_dir(Rpd::$c['rapid']['filesDir'] . "images" . DIRECTORY_SEPARATOR . "upload") ) @mkdir(Rpd::$c['rapid']['filesDir'] . 'images' . DIRECTORY_SEPARATOR . 'upload');
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $formats = array('jpg', 'jpeg', 'png', 'gif');
        $return = array();
        if ( in_array($ext, $formats) ) {
            $targetPath = Rpd::$c['rapid']['filesDir'] . "images" . DIRECTORY_SEPARATOR . "upload" . DIRECTORY_SEPARATOR . basename($_FILES['image']['name']);
            if ( !@is_file($targetPath) )
                if ( @move_uploaded_file($_FILES['image']['tmp_name'], $targetPath) ) 
                    $return['url'] = DIRECTORY_SEPARATOR . $targetPath;
                else $return['error'] = "Something went wrong while trying to upload image. Probably permission denied.";
            else $return['error'] = "This file (" . $targetPath . ") is already exists. Please rename it and upload again.";
        } else $return['error'] = "This extension (" . $ext . ") is not supported. Supported extensions are: " . implode(", ", $formats) . ".";
        return json_encode($return);
    }

}