<?php

    /**
     * Rapid updater file.
    */
    
    // Install the update. Filename: rapid-update.zip.
    
    // Set the variables
    $update = 'rapid-update.zip';
    $backup = "rapid-" . date("Y-m-d") . ".bak.zip";
    $messages = array();

    
    // Install update
    $zip = new ZipArchive;
    if ( true !== $zip->open($update) ) $messages[] = "Error: Can not open <em>" . $update . "</em> update file.";
    else {
        if ( @is_file("lib/" . $backup) ) @unlink("lib/" . $backup);
        if ( true !== @Zip(getcwd(), "lib/" . $backup) ) $messages[] = "Error: Can not create backup of Rapid.";
        else {
            $messages[] = "Backup archive created of Rapid (<a href=\"/lib/" . $backup . "\" target=\"_blank\">" . $backup . "</a> - " . number_format(filesize("lib/" . $backup) / 1048576, 2) . " MB).";

            $files = array_diff(scandir(getcwd()), array('.', '..'));
            $zip->extractTo(getcwd());
            $zip->close();
            $filesNow = array_diff(scandir(getcwd()), array('.', '..'));
            $newFile = array_diff($filesNow, $files);
            $dir = array_shift($newFile);
            if ( !empty($dir) ) {
                $messages[] = "Update pack is extracted to <em>" . $dir . "</em>";
                
                require_once 'rapid/configuration.php';
                $dbconf = $configuration['db'];
                $copied = @recurse_copy($dir, getcwd());
                if ( 0 < $copied ) {
                    $messages[] = $copied . " files are updated.";
                    
                    @delTree($dir);
                    @unlink($update);
                    @file_put_contents('rapid/configuration.php', '$configuration[\'db\'] = array(\'host\' => \'' . $dbconf['host'] . '\',\'dbname\' => \'' . $dbconf['dbname'] . '\',\'username\' => \'' . $dbconf['username'] . '\',\'password\' => \'' . $dbconf['password'] . '\');', FILE_APPEND);
                    $updated = true;
                    $messages[] = "Temporary files are removed.";
                    
                    $messages[] = "The update was successful, you have the newest version from now on.";
                }
            }
        }
    }
    
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Rapid | Updater</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//rapid.momentoom.hu/lib/css/installer.css" />
    </head>
    <body>
        <p></p><br />
        <div class="container">
            <div class="well">
                <?php if ( 0 < count($messages) ) { ?>
                    <ol>
                        <?php foreach ( $messages as $message ) { ?>
                            <li><?php print $message; ?></li>
                        <? } ?>
                    </ol>
                <?php } ?>
            </div>
        </div>
        
        <?php if ( isset($updated) ) { ?>
            <br /><br />
            <div class="text-center">
                <a href="/Home/index/newly-installed" class="btn btn-success btn-lg">Hurray, lets go.</a>
            </div>
        <? } ?>
        
        <script src="https://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    </body>
</html>
<?php

    // Functions
    function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        return rmdir($dir); 
    }
    
    function Zip($source, $destination) {
        if ( !extension_loaded('zip') || !file_exists($source) ) return false;
        $zip = new ZipArchive();
        if ( !$zip->open($destination, ZIPARCHIVE::CREATE) ) return false;
        $source = str_replace('\\', '/', realpath($source));
        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);
                if ( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ) continue;
                $file = realpath($file);
                if (is_dir($file) === true) $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                else if (is_file($file) === true) $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        } else if (is_file($source) === true) $zip->addFromString(basename($source), file_get_contents($source));
        return $zip->close();
    }
    
    function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        $copied = 0;
        while(false !== ($file = readdir($dir)) )
            if (( $file != '.' ) && ( $file != '..' ))
                if ( is_dir($src . '/' . $file) ) $copied += recurse_copy($src . '/' . $file,$dst . '/' . $file);
                else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                    $copied++;
                }
        closedir($dir); 
        return $copied;
    }

?>