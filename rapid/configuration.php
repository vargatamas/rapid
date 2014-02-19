<?php

    // Rapid configuration file - variable name has to be $configuration
    $configuration = array(
                        'raintpl' => array(
                                                // The path of the directory of templates from webroot.
                                                'tpl_dir' =>        'views/',
                                                
                                                // The extension - without dot - of the templates which are included from the template directory.
                                                'tpl_ext' =>        'tpl',
                                                
                                                // The path of the directory of cache from webroot.
                                                'cache_dir' =>      'cache/',
                                                
                                                // If it's true, the relative URLs in templates will replaced to absolute URLs (not recommended).
                                                'path_replace' =>   false
                                            ),
                        'rapid' => array(
                                                // Name of the default application to run when not set in request.
                                                'defaultApplication' =>     'Home',
                                                
                                                // Name of the default action of an application when not set in request.
                                                'defaultAction' =>          'index',
                                                
                                                // The separator of key - value arguments in the request.
                                                'argArraySeparator' =>      ':',
                                                
                                                // The variable in the controllers which define the templates.
                                                'controllerTemplateVar' =>  '$template',
                                                
                                                // The default layout of applications when not set.
                                                'defaultLayout' =>          'default',
                                                
                                                // The filename of the sources for applications.
                                                'sourcesFile' =>            'sources.json',
                                                
                                                // The authorization template filename (without extension) for logging in to reach an application.
                                                'authTpl' =>                'auth',
                                                
                                                // The variable in the controllers which define the privilege level for the application.
                                                'controllerAuthVar' =>      '$auth_depth',
                                                
                                                // The default language (culture) when not set.
                                                'culture' =>                'English',
                                                
                                                // The path of the translations directory from the webroot.
                                                'translationsDir' =>        'translations/',
                                                
                                                // The filename of the file where the meta datas will placed (recommended extension: json).
                                                'metaFile' =>               'meta.json',
                                                
                                                // The filename of the file where the site datas will placed (recommended extension: json).
                                                'siteFile' =>               'site.json',
                                                
                                                // If it's true, you can manage the Administrator application via itself (not recommended).
                                                'editAdmin' =>              false,
                                                
                                                // The extensions of files which are editable in the Library function of Administrator.
                                                'libEditables' =>           array('js', 'less', 'css', 'txt'),
                                                
                                                // The filename of the updater file (in the rapid directory).
                                                'updaterFile' =>            'updater.php',
                                                
                                                // The filename of the installer file (in the rapid directory).
												'installerFile' =>			'installer.php'
                                            )
                    );
	
    
    // Database access configuration
	