<?php

    /**
     * Rapid installer file.
    */
    
    session_start();
    
    // Step 1: Permissions
    $filePermissions = substr(sprintf('%o', fileperms(__FILE__)), -4);
    $dirPermissions = substr(sprintf('%o', fileperms(getcwd())), -4);
    if ( "0777" == $filePermissions && "0777" == $dirPermissions ) $permsOk = true;
    
    // Step 2: Database
    if ( 4 != count($_SESSION['db']) ) {
        if ( isset($_POST['db']) ) {
            $db = @new mysqli($_POST['db']['host'], $_POST['db']['username'], $_POST['db']['password'], $_POST['db']['dbname']);
            if( 0 < $db->connect_errno )
                $dbError = $db->connect_error;
            else {
                $_SESSION['db'] = $_POST['db'];
                $dbOk = true;
            }
        }
    } else $dbOk = true;

    //Step 3: Download and Install
    if ( isset($_POST['install']) ) {
        if ( isset($permsOk) && 4 == count($_SESSION['db']) ) {
            // Get newest version
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_USERAGENT, 'vargatamas');
            curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/vargatamas/rapid/tags");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            $content = json_decode($data);
            $zipURL = $content[0]->zipball_url;
            
            // Download
            $path = 'rapid.zip';
            if ( is_file($path) ) @unlink($path);
            $fh = fopen($path, 'w');
            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_USERAGENT, 'vargatamas');
            curl_setopt($ch2, CURLOPT_URL, $zipURL); 
            curl_setopt($ch2, CURLOPT_FILE, $fh); 
            curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch2);
            if ( curl_error($ch2) != '' ) $downloadError = curl_error($ch);
            curl_close($ch2);
            fclose($fh);
            
            // Install
            if ( !isset($downloadError) ) {
                // Extract
                $zip = new ZipArchive;
                if ( true === $zip->open($path) ) {
                    $files = array_diff(scandir(getcwd()), array('.', '..'));
                    if ( @$zip->extractTo(getcwd()) ) {
                        $filesNow = array_diff(scandir(getcwd()), array('.', '..'));
                        $newFile = array_diff($filesNow, $files);
                        if ( 1 == count($newFile) ) {
                            $dir = array_shift($newFile);
                            @recurse_copy($dir, getcwd());
                            $newFiles = array_diff(scandir(getcwd()), array('.', '..'));
                            if ( 0 < array_diff($newFiles, $filesNow) ) {
                                @delTree($dir);
                                @file_put_contents('rapid/configuration.php', '$configuration[\'db\'] = array(\'host\' => \'' . $_SESSION['db']['host'] . '\',\'dbname\' => \'' . $_SESSION['db']['dbname'] . '\',\'username\' => \'' . $_SESSION['db']['username'] . '\',\'password\' => \'' . $_SESSION['db']['password'] . '\');', FILE_APPEND);
                                $data = "zip=" . $zipURL . "&host=" . $_SERVER['HTTP_HOST'] . "&user_agent" . $_SERVER['HTTP_USER_AGENT'] . "&server=" . $_SERVER['SERVER_SOFTWARE'] . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&date=" . date("Y/m/d-H:i:s");
                                $ch3 = curl_init('http://rapid.momentoom.hu/rapid.php');
                                curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, "POST");
                                curl_setopt($ch3, CURLOPT_POSTFIELDS, $data);
                                curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
                                @curl_exec($ch3);
                                $installed = true;
                            } else $installError = true;
                        } else $installError = true;
                    } else $installError = true;
                    @unlink($path);
                    $zip->close();
                } else $installError = true;
            }
        } else $installError = true;
    }
    
?>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Rapid | Installation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//rapid.momentoom.hu/lib/css/installer.css" />
    </head>
    <body>
        <p></p><br />
        <div class="container">
            <div class="well">
                <?php if ( !isset($installed) ) { ?>
                    <h1>Rapid <small>installation</small></h1>
                    <h4>Hello there, this is the Rapid mini FrameWork. We are gonna install the newest version of Rapid, are you ready?</h4>
                    <br />
                    <img src="http://rapid.momentoom.hu/lib/images/simplicity.jpg" class="simplicity" alt="Rapid" />
                    <br />
                    <h3>To begin the installation, please check these steps</h3>
                    <ul>
                        <li>Give file system permission (<em>777</em>) to this file and folder (<em>installer.php</em> and <em><?php print getcwd(); ?></em>).</li>
                        <li>Create database and an user for Rapid.</li>
                    </ul>
                    <br /><br />
                    <h4>Step 1: Permission status</h4>
                    <div class="alert alert-<?php print ( isset($permsOk) ? "success" : "danger" ); ?>">Give file system permission (<em>777</em>) to this file and folder (<em>installer.php</em> and <em><?php print getcwd(); ?></em>).</div>
                    <?php if ( isset($permsOk) ) { ?>
                        <br />
                        <h4>Step 2: Database</h4>
                        <?php if ( isset($dbOk) ) { ?>
                            <div class="alert alert-success">Database connection is OK and access is granted.</div>
                        <?php } else { ?>
                            <form role="form" action="" method="post">
                                <?php if ( isset($dbError) ) { ?>
                                    <div class="alert alert-danger">
                                        <strong>Database Error</strong><br />
                                        <?php print $dbError; ?>
                                    </div>
                                <? } ?>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="host">Host</label>
                                        <input type="text" class="form-control" name="db[host]" id="host" <?php print ( isset($_POST['db']['host']) ? 'value="' . $_POST['db']['host'] . '"' : 'placeholder="localhost"' ); ?>>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="dbname">Database name</label>
                                        <input type="text" class="form-control" name="db[dbname]" id="dbname"  <?php print ( isset($_POST['db']['dbname']) ? 'value="' . $_POST['db']['dbname'] . '"' : 'placeholder="rapid"' ); ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" name="db[username]" id="username"  <?php print ( isset($_POST['db']['username']) ? 'value="' . $_POST['db']['username'] . '"' : 'placeholder="john.appleseed"' ); ?>>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="db[password]" id="password" placeholder="********">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Check</button>
                            </form>
                        <?php } ?>
                    <?php } else { ?><a href="" class="btn btn-primary">Check again</a><?php } ?>
                    <?php if ( $permsOk && $dbOk ) { ?>
                        <br />
                        <h4>Step 3: Download and Install Rapid</h4>
                        The first and second step is ok. Now, we are ready to download and install the newest version of Rapid, click <em>Download &amp; Install</em> to confirm action.
                        <br />
                        <?php if ( isset($installError) ) { ?>
                            <br />
                            <div class="alert alert-danger">
                                <strong>Installation error</strong><br />
                                Something went wrong while trying to install Rapid, please <em>Try again</em>.
                            </div>
                            <br />
                            <a href="" class="btn btn-primary btn-lg">Try again</a>
                        <?php } else { ?>
                            <?php if ( isset($downloadError) ) { ?>
                                <div class="alert alert-danger">
                                <strong>Download error</strong><br />
                                    Something went wrong while trying to download Rapid, please click to <em>Download &amp; Install</em> to try again.<br />
                                    Note: the installer using cURL for downloading files, do you have cURL module for your PHP?
                                </div>
                                <br />
                            <?php } ?>
                            <form action="" method="post" id="form-install">
                                <input type="hidden" class="hided" name="install">
                                <br />
                                <div class="text-center">
                                    <button type="button" onclick="this.innerHTML = 'Downloading and Installing ..';this.disabled = true;document.getElementById('form-install').submit();" class="btn btn-primary btn-lg">Download &amp; Install</button>
                                </div>
                            </form>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <h1>Rapid installed</h1>
                    <h4>Congratulations, the Rapid installation went successful. Now you can use Rapid, with smile on your face.</h4>
                    <br /><br />
                    <div class="text-center">
                        <a href="/Home/index/newly-installed" class="btn btn-success btn-lg">Hurray, lets go.</a>
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    </body>
</html>
<?php

    // Functions
    function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ($file = readdir($dir)) )
            if (( $file != '.' ) && ( $file != '..' ))
                if ( is_dir($src . '/' . $file) ) recurse_copy($src . '/' . $file,$dst . '/' . $file);
                else 
					if ( 'installer.php' != $file ) copy($src . '/' . $file, $dst . '/' . $file);
        closedir($dir); 
    }

    function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        return rmdir($dir); 
    }

?>