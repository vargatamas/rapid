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
    if ( isset($_GET['start']) ) {
		if ( 0 < count($_POST['update']) ) {
			$admin = ( isset($_POST['update']['admin-app']) ? true : false );
			$visual = ( isset($_POST['update']['visual']) ? true : false );;
			$core = ( isset($_POST['update']['core']) ? true : false );;
			$config = ( isset($_POST['update']['config']) ? true : false );;
			
			$zip = new ZipArchive;
			if ( true !== $zip->open($update) ) $messages[] = "Error: Can not open <em>" . $update . "</em> update file.";
			else {
				if ( @is_file("assets" . DIRECTORY_SEPARATOR . $backup) ) @unlink("assets" . DIRECTORY_SEPARATOR . $backup);
				if ( true !== @Zip(getcwd(), "assets" . DIRECTORY_SEPARATOR . $backup) ) $messages[] = "Error: Can not create backup of Rapid.";
				else {
					$messages[] = "Backup archive created of Rapid (<a href=\"/assets/" . $backup . "\" target=\"_blank\">" . $backup . "</a> - " . number_format(filesize("assets" . DIRECTORY_SEPARATOR . $backup) / 1048576, 2) . " MB).";

					$files = array_diff(scandir(getcwd()), array('.', '..'));
					$zip->extractTo(getcwd());
					$zip->close();
					$filesNow = array_diff(scandir(getcwd()), array('.', '..'));
					$newFile = array_diff($filesNow, $files);
					$dir = array_shift($newFile);
					if ( !empty($dir) ) {
						$messages[] = "Update pack is extracted to <em>" . $dir . "</em>";
						
						require_once 'rapid' . DIRECTORY_SEPARATOR . 'configuration.php';
						$dbconf = $configuration['db'];
						//$copied = @recurse_copy($dir, getcwd());
						$copied = 0;
						if ( $admin ) {
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'applications' . DIRECTORY_SEPARATOR . 'Administrator', getcwd() . DIRECTORY_SEPARATOR . 'applications' . DIRECTORY_SEPARATOR . 'Administrator');
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'English' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.administrator.tpl', getcwd() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'English' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.administrator.tpl');
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'English' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Administrator', getcwd() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'English' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Administrator');
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'Hungarian' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.administrator.tpl', getcwd() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'Hungarian' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.administrator.tpl');
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'Hungarian' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Administrator', getcwd() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'Hungarian' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Administrator');
						}
						if ( $visual )
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'assets', getcwd() . DIRECTORY_SEPARATOR . 'assets');
						if ( $core )
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'rapid', getcwd() . DIRECTORY_SEPARATOR . 'rapid');
						if ( $config )
							$copied += @recurse_copy($dir . DIRECTORY_SEPARATOR . 'rapid' . DIRECTORY_SEPARATOR . 'configuration.php', getcwd() . DIRECTORY_SEPARATOR . 'rapid' . DIRECTORY_SEPARATOR . 'configuration.php');
						if ( 0 < $copied ) {
							$messages[] = $copied . " files are updated.";
							
							@delTree($dir);
							@unlink($update);
							if ( $config || $core )
								@file_put_contents('rapid' . DIRECTORY_SEPARATOR . 'configuration.php', '$configuration[\'db\'] = array(\'host\' => \'' . $dbconf['host'] . '\',\'dbname\' => \'' . $dbconf['dbname'] . '\',\'username\' => \'' . $dbconf['username'] . '\',\'password\' => \'' . $dbconf['password'] . '\');', FILE_APPEND);
							$updated = true;
							$messages[] = "Temporary files are removed.";
							
							$messages[] = "The update was successful, you have the newest version from now on.";
						}
					}
				}
			}
		} else $noItem = true;
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
				<h1>Rapid update</h1>
				<h3>Choose the components you want to update:</h3>
				<?php if ( isset($noItem) ) { ?>
					<div class="alert alert-danger">
						<strong>No components selected.</strong> You have to choose at least one component to update.
						<br><br>
						<a href="/" class="btn btn-default">Cancel and back to my Rapid</a>
					</div>
				<?php } ?>
				<?php if ( !isset($_GET['start']) || isset($noItem) ) { ?>
					<form class="form-horizontal" method="post" action="?start" id="form-update" role="form">
						<div class="form-group">
							<label for="admin-app" class="col-lg-3 control-label">Administrator application</label>
							<div class="col-lg-9">
								<div class="checkbox">
									<input type="checkbox" name="update[admin-app]" id="admin-app" checked="checked">
								</div>
								<p class="help-block">It refresh the functions of the Administrator application.</p>
							</div>
						</div>
						<div class="form-group">
							<label for="visual" class="col-lg-3 control-label">Visual elements (assets/)</label>
							<div class="col-lg-9">
								<div class="checkbox">
									<input type="checkbox" name="update[visual]" id="visual" checked="checked">
								</div>
								<p class="help-block">It refresh the look of Rapid elements, only Rapid's images, styles and scripts will be overwritten.</p>
							</div>
						</div>
						<div class="form-group">
							<label for="core" class="col-lg-3 control-label">Rapid core</label>
							<div class="col-lg-9">
								<div class="checkbox">
									<input type="checkbox" name="update[core]" id="core" checked="checked">
								</div>
								<p class="help-block">It refresh the core files of Rapid, everything except configuration file.</p>
							</div>
						</div>
						<div class="form-group">
							<label for="config" class="col-lg-3 control-label">Configuration</label>
							<div class="col-lg-9">
								<div class="checkbox">
									<input type="checkbox" name="update[config]" id="config" checked="checked">
								</div>
								<p class="help-block">
									It refresh the variables of the configuration file and set everything to default, except database access.<br>
									Default Application, Language and settings like these have to reconfigured after update.
								</p>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-5 col-sm-7">
								<button type="button" onclick="this.innerHTML = 'Updating Rapid ..';this.disabled = true;document.getElementById('form-update').submit();" class="btn btn-primary">Update these items</button>
							</div>
						</div>
					</form>
				<?php } ?>
				
				<?php if ( 0 < count($messages) ) { ?>
					<br /><br />
					<ol>
						<?php foreach ( $messages as $message ) { ?>
							<li><?php print $message; ?></li>
						<? } ?>
					</ol>
				<?php } ?>
				
				<?php if ( isset($updated) ) { ?>
					<br /><br />
					<div class="text-center">
						<a href="/Home/index/newly-installed" class="btn btn-success btn-lg">Hurray, lets go.</a>
					</div>
				<? } ?>
			</div>
		</div>
        
        <script src="https://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    </body>
</html>
<?php

    // Functions
    function delTree($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
        foreach ($files as $file) (is_dir("$dir" . DIRECTORY_SEPARATOR . "$file")) ? delTree("$dir" . DIRECTORY_SEPARATOR . "$file") : unlink("$dir" . DIRECTORY_SEPARATOR . "$file");
        return rmdir($dir); 
    }
    
    function Zip($source, $destination) {
        if ( !extension_loaded('zip') || !file_exists($source) ) return false;
        $zip = new ZipArchive();
        if ( !$zip->open($destination, ZIPARCHIVE::CREATE) ) return false;
        $source = str_replace('\\', DIRECTORY_SEPARATOR, realpath($source));
        if (is_dir($source) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
                if ( in_array(substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1), array('.', '..')) ) continue;
                $file = realpath($file);
                if (is_dir($file) === true) $zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
                else if (is_file($file) === true) $zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file), file_get_contents($file));
            }
        } else if (is_file($source) === true) $zip->addFromString(basename($source), file_get_contents($source));
        return $zip->close();
    }
    
    function recurse_copy($src, $dst) {
        if ( !is_file($src) ) {
			$dir = opendir($src);
			@mkdir($dst);
			$copied = 0;
			while(false !== ($file = readdir($dir)) )
				if (( $file != '.' ) && ( $file != '..' ))
					if ( is_dir($src . DIRECTORY_SEPARATOR . $file) )
						$copied += recurse_copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
					else {
						copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
						$copied++;
					}
			closedir($dir);
		} else if ( true === copy($src, $dst) ) $copied = 1;
        return $copied;
    }

?>