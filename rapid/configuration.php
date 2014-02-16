<?php

    // Rapid configuration file - variable name has to be $configuration
    $configuration = array(
                        'raintpl' => array(
                                                'tpl_dir' =>        'views/',
                                                'tpl_ext' =>        'tpl',
                                                'cache_dir' =>      'cache/',
                                                'path_replace' =>   false
                                            ),
                        'rapid' => array(
                                                'defaultApplication' =>     'Home',
                                                'defaultAction' =>          'index',
                                                'argArraySeparator' =>      ':',
                                                'controllerTemplateVar' =>  '$template',
                                                'defaultLayout' =>          'default',
                                                'sourcesFile' =>            'sources.json',
                                                'authTpl' =>                'auth',
                                                'controllerAuthVar' =>      '$auth_depth',
                                                'culture' =>                'English',
                                                'translationsDir' =>        'translations/',
                                                'metaFile' =>               'meta.json',
                                                'siteFile' =>               'site.json',
                                                'editAdmin' =>              false,
                                                'libEditables' =>           array('js', 'less', 'css', 'txt'),
                                                'updaterFile' =>            'updater.php',
												'installerFile' =>			'installer.php'
                                            )
                    );
	
	