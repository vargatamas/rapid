<?php

    /**
     * Welcome in Rapid FrameWork
    */

    // Start session & set content type
    session_start();

    class Rapid {
        
        public static $dir =            'rapid',
                      $configFile =     'configuration.php',
                      $config =         array(),
                      $tpl =            null,
                      $culture =        '',
                      $version =        "v1.4.4",
                      $task =           array(),
                      $errors =         array();
        
        /**
         * Run all the initialization function and when there is no error, run the application.
        */
        public function __construct($controller = "", $action = "") {
            // Autoload function
            spl_autoload_register('Rapid::autoload');

            // RedBean
            if ( is_file(Rapid::$dir . DIRECTORY_SEPARATOR . 'rb.phar') ) {
                require_once Rapid::$dir . DIRECTORY_SEPARATOR . 'rb.phar';
                if ( Rpd::rq(Rpd::$c['db']) && 0 < count(Rpd::$c['db']) )
                    R::setup('mysql:host=' . Rpd::$c['db']['host'] . ';dbname=' . Rpd::$c['db']['dbname'], Rpd::$c['db']['username'], Rpd::$c['db']['password']);
                else {
                    Rpd::a('DBTMP', true);
                    R::setup();
                }
            } else self::crash('Error in Rapid class contsructor function: RedBean file (' . getcwd() . DIRECTORY_SEPARATOR . Rapid::$dir . DIRECTORY_SEPARATOR . 'rb.php) does not exists.');
            
            // PHPMailer
            if ( is_file(Rapid::$dir . DIRECTORY_SEPARATOR . 'phpmailer.php') )
                require_once Rapid::$dir . DIRECTORY_SEPARATOR . 'phpmailer.php';
            else self::crash('Error in Rapid class constructor function: PHPMailer file (' . getcwd() . DIRECTORY_SEPARATOR . Rapid::$dir . DIRECTORY_SEPARATOR . 'phpmailer.php) does not exists.');
            
            // Rapid gives control to application
            self::run();
        }

        /**
         * This is the heart of Rapid, this function runs the application.
        */
        public static function run($controller = "", $action = "") {
            self::pathToTask($controller, $action);
            $application = self::loadApplication();
            if ( !is_null($application) ) {
                $action = self::runApplication($application);
                if ( false !== $action ) {
                    // Get the application's content
                    $culture = Rapid::$culture;
                    self::assignSources();
                    self::assignMeta();
                    self::assignPreferences();
                    $buildLevel = self::buildLevel();
                    if ( Rpd::$c['rapid']['allwaysLoadDefaultApp'] ) {
                        $defApp = Rpd::$c['rapid']['defaultApplication'] . "Controller";
                        new $defApp(self::$task['args']);
                    }
                    if ( $application instanceof RapidAuth && !$application->authenticated ) 
                        $authError = true;
                    else if ( $application->authenticated && $_SESSION['user']['depth'] > eval('return ' . get_class($application) . '::' . Rpd::$c['rapid']['controllerAuthVar'] . ';') ) {
                        $applicationContent = "Permission denied. Your account level is not acceptable here.";
                    } else {
                        if ( null !== $action && !isset(self::$task['static']) ) {
                            $return = $application->$action(self::$task['args']);
                            $template = eval('return (isset(' . self::$task['controller'] . '::' . Rpd::$c['rapid']['controllerTemplateVar'] . ')?' . self::$task['controller'] . '::' . Rpd::$c['rapid']['controllerTemplateVar'] . ':"' . strtolower(str_replace('Action', '', self::$task['action'])) . '");');
                            if ( !is_null($return) ) $applicationContent = $return;
                            else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . $template . '.' . Rpd::$c['raintpl']['tpl_ext']) ) 
                                $applicationContent = Rapid::$tpl->draw($culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . $template, $return_string = true);
                            else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . $template . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                                $applicationContent = Rapid::$tpl->draw(Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . $template, $return_string = true);
                            else $applicationContent = 'The template (' . $template . '.' . Rpd::$c['raintpl']['tpl_ext'] . ') file for application (' . self::$task['application'] . ') does not exists.';
                        } else $applicationContent = Rapid::$tpl->draw($culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . self::$task['static'], $return_string = true);
                    }

                    // Build the template
                    if ( isset($authError) ) {
                        $applicationContent = Rapid::$tpl->draw($culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['authTpl'], $return_string = true);
                        Rapid::$tpl->assign('LAYOUT_CONTENT', $applicationContent);
                        Rapid::$tpl->draw('frame');
                    } else {
                        if ( 'application' == $buildLevel ) 
                            print $applicationContent;
                        else if ( 'layout' == $buildLevel ) {
                            $layout = self::loadLayout();
                            Rapid::$tpl->assign('APPLICATION_CONTENT', '<!-- application content start -->' . $applicationContent . '<!-- application content end -->');
                            Rapid::$tpl->draw($culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout);
                        } else {
                            $layout = self::loadLayout();
                            Rapid::$tpl->assign('APPLICATION_CONTENT', '<!-- application content start -->' . $applicationContent . '<!-- application content end -->');
                            $layoutContent = Rapid::$tpl->draw($culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout, $return_string = true);
                            Rapid::$tpl->assign('LAYOUT_CONTENT', $layoutContent);
                            Rapid::$tpl->draw('frame');
                        }
                    }
                }
            }
            if ( 0 < count(self::$errors) ) self::crash();
        }

        /**
         * Redirect Rapid to another application to run (and kill the current process).
        */
        public static function redirect($controller = "", $action = "") {
            self::run($controller, $action);
            die();
        }

        /**
         * This function do the configuration of Rapid and set the defaults.
        */ 
        public static function configuration($return = false) {
            if ( is_file(Rapid::$dir . DIRECTORY_SEPARATOR . Rapid::$configFile) ) {
                require_once Rapid::$dir . DIRECTORY_SEPARATOR . Rapid::$configFile;
                Rpd::$c = $configuration;
                if ( DIRECTORY_SEPARATOR != substr(Rpd::$c['raintpl']['tpl_dir'], -1) ) Rpd::$c['raintpl']['tpl_dir'] .= DIRECTORY_SEPARATOR;
                if ( DIRECTORY_SEPARATOR != substr(Rpd::$c['raintpl']['cache_dir'], -1) ) Rpd::$c['raintpl']['cache_dir'] .= DIRECTORY_SEPARATOR;
                if ( DIRECTORY_SEPARATOR != substr(Rpd::$c['rapid']['translationsDir'], -1) ) Rpd::$c['rapid']['translationsDir'] .= DIRECTORY_SEPARATOR;
                if ( DIRECTORY_SEPARATOR != substr(Rpd::$c['rapid']['mailsDir'], -1) ) Rpd::$c['rapid']['mailsDir'] .= DIRECTORY_SEPARATOR;
                if ( '$' != substr(Rpd::$c['rapid']['controllerTemplateVar'], 0, 1) ) 
                    Rpd::$c['rapid']['controllerTemplateVar'] = '$' . Rpd::$c['rapid']['controllerTemplateVar'];
            } else {
                Rpd::$c['raintpl'] = array(
                                                'tpl_dir' => 'views' . DIRECTORY_SEPARATOR,
                                                'tpl_ext' => 'tpl',
                                                'cache_dir' => 'cache' . DIRECTORY_SEPARATOR,
                                                'path_replace' => false
                                            );
                Rpd::$c['rapid'] = array(
                                                'defaultApplication' => 'Default',
                                                'defaultAction' => 'index',
                                                'argArraySeparator' => ':',
                                                'controllerTemplateVar' => '$template',
                                                'defaultLayout' => 'default',
                                                'sourcesFiles' => 'sources.json',
                                                'authTpl' => 'auth',
                                                'controllerAuthVar' => 'auth_depth',
                                                'language' => 'English',
                                                'translationsDir' => 'translations' . DIRECTORY_SEPARATOR,
                                                'metaFile' => 'meta.json',
                                                'siteFile' => 'site.json',
                                                'editAdmin' => false,
                                                'libEditables' => array('js', 'less', 'css', 'txt'),
												'updaterFile' => 'updater.php',
												'installerFile' => 'creator.php',
                                                'mailsDir' => 'mails' . DIRECTORY_SEPARATOR,
                                                'globalSourcesFile' => 'global-sources.json',
                                                'filesDir' => 'assets' . DIRECTORY_SEPARATOR,
                                                'allwaysLoadDefaultApp' => false
                                            );
                Rpd::$c['db'] = array();
            }

            // load RainTPL
            require_once Rapid::$dir . DIRECTORY_SEPARATOR . 'rain.tpl.class.php';
            if ( isset(Rpd::$c['raintpl']) )
                foreach ( Rpd::$c['raintpl'] as $key => $value ) raintpl::configure($key, $value);
            Rapid::$tpl = new RainTPL();

            // Set culture
            $culture = Rpd::$c['rapid']['culture'];
            if ( Rpd::rq($_POST['culture']['name']) && in_array($_POST['culture']['name'], Rpd::gC()) )
                $_SESSION['culture'] = $_POST['culture']['name'];
            if ( Rpd::rq($_SESSION['culture']) ) {
                $sCulture = $_SESSION['culture'];
                if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . DIRECTORY_SEPARATOR . $sCulture) ) $culture = $_SESSION['culture'];
            }
            Rapid::$tpl->assign('CULTURE', $culture);
            Rapid::$culture = $culture;
            
            if ( $return ) return Rpd::$c;
        }

        /**
         * This function is called when something go wrong. It dies with the error messages.
        */
        public static function crash($message = "") {
            if ( !empty($message) ) echo '- ' . $message;
            else foreach ( self::$errors as $error ) echo '- ' . $error . '<br />';
            die();
        }
        
        /**
         * Convert URL to Array and define the controller, action and arguments after checked the routing.
         * This function requires the .htaccess file with this line: 'RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]'
        */
        public static function pathToTask($controller = "", $action = "") {
            $path = $_GET['path'];
            $pathArray = array();
            if ( 0 < strlen($path) && 0 < count(explode('/', $path)) ) $pathArray = explode('/', $path);

            // Routing
            $routing = null;
            $routes = R::findAll('routes');
            if ( 0 < count($routes) ) {
                foreach ( $routes as $route ) {
                    $variables = array('text' => array(), 'number' => array());
                    $fromArray = explode('/', $route->from);
                    $argCount = count($pathArray);
                    $routeCount = 0;
                    foreach ( $pathArray as $pKey => $arg ) {
                        if ( strtolower($arg) == strtolower($fromArray[$pKey]) ) $routeCount++;
                        else if ( '#text#' == $fromArray[$pKey] ) {
                            $variables['text'][] = $arg;
                            $routeCount++;
                        } else if ( '#number#' == $fromArray[$pKey] && Rpd::nE(is_numeric($arg)) ) {
                            $variables['number'][] = $arg;
                            $routeCount++;
                        }
                    }
                    if ( $argCount == $routeCount && 0 < $argCount && count($fromArray) == $argCount ) {
                        $routing = explode('/', $route->to);
                        $count = array('text' => 0, 'number' => 0);
                        foreach ( $routing as &$rArg ) {
                            if ( '#text#' == $rArg ) $rArg = $variables['text'][$count['text']++];
                            if ( '#number#' == $rArg ) $rArg = $variables['number'][$count['number']++];
                        }
                        break;
                    }
                }
            }

            if ( !Rpd::rq($routing) ) {
                if ( Rpd::nE($controller) ) self::$task['controller'] = $controller;
                else self::$task['controller'] = ( !empty($pathArray[0]) ? $pathArray[0] : Rpd::$c['rapid']['defaultApplication'] );
                if ( Rpd::nE($action) ) self::$task['action'] = $action;
                else {
                    if ( !empty($pathArray[1]) ) self::$task['action'] = $pathArray[1];
                    else self::$task['action'] = $pathArray[0];
                }
                self::createArgs($pathArray);
            } else {
                self::$task['controller'] = $routing[0];
                self::$task['action'] = $routing[1];
                self::$createArgs($routing);
            }
        }

        /**
         * Now we know the requested controller and it's action. It's time to collect the arguments and pass to controller's action.
         * This function checks if there is any array argument (array separator can set through argArraySeparator).
        */ 
        public static function createArgs($pathArray = array()) {
            if ( isset($pathArray[0]) ) unset($pathArray[0]);
            if ( isset($pathArray[1]) ) unset($pathArray[1]);
            
            $args = array();
            if ( "array" == gettype($pathArray) ) 
                foreach ( $pathArray as $item ) {
                    if ( 1 < count(explode(Rpd::$c['rapid']['argArraySeparator'], $item)) && $arg = explode(Rpd::$c['rapid']['argArraySeparator'], $item) ) 
                        $args[$arg[0]] = $arg[count($arg)-1];
                    else $args[] = $item;
                }
            
            self::$task['args'] = $args;
        }

        /**
         * Gives the control to the requested Application (stored in $task variable).
         * Default application is defined in configuration file (defaultApplication).
         * 
         * @return Controller Object or NULL
        */
        public static function loadApplication() {
            $application = null;
            if ( is_dir('applications' . DIRECTORY_SEPARATOR . self::$task['controller']) ) {
                if ( is_file('applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php';
                    self::$task['controller'] = self::$task['controller'] . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = ucfirst(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . self::$task['controller'] . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = strtolower(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else self::$errors[] = "Error in Rapid class loadApplication function: the <em>" . self::$task['controller'] . "Controller.class.php</em> does not exists in <em>applications" . DIRECTORY_SEPARATOR . self::$task['controller'] . "</em>.";
            } else if ( is_dir('applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller'])) ) {
                if ( is_file('applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php';
                    self::$task['controller'] = self::$task['controller'] . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = ucfirst(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = strtolower(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else self::$errors[] = "Error in Rapid class loadApplication function: the <em>" . self::$task['controller'] . "Controller.class.php</em> does not exists in <em>applications" . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . "</em>.";
            } else if ( is_dir('applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller'])) ) {
                if ( is_file('applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . self::$task['controller'] . 'Controller.class.php';
                    self::$task['controller'] = self::$task['controller'] . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . ucfirst(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = ucfirst(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else if ( is_file('applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . 'Controller.class.php';
                    self::$task['controller'] = strtolower(self::$task['controller']) . 'Controller';
                    $application = new self::$task['controller']();
                } else self::$errors[] = "Error in Rapid class loadApplication function: the <em>" . self::$task['controller'] . "Controller.class.php</em> does not exists in <em>applications" . DIRECTORY_SEPARATOR . strtolower(self::$task['controller']) . "</em>.";
            } else {
                $defAction = Rpd::$c['rapid']['defaultApplication'];
                if ( is_file('applications' . DIRECTORY_SEPARATOR . $defAction . DIRECTORY_SEPARATOR . $defAction . 'Controller.class.php') ) {
                    require_once 'applications' . DIRECTORY_SEPARATOR . $defAction . DIRECTORY_SEPARATOR . $defAction . 'Controller.class.php';
                    self::$task['controller'] = $defAction . 'Controller';
                    $application = new self::$task['controller']();
                } else self::$errors[] = "Error in Rapid class loadApplication function: defaultApplication is defined (<em>" . Rpd::$c['rapid']['defaultApplication'] . "</em>), but does not exists in applications dir.";
            }
            self::$task['application'] = str_replace('Controller', '', self::$task['controller']);
            return $application;
        }
        
        /**
         * If we successfully loaded the requested (or default) application, let's run it's requested (or default) action.
        */ 
        public static function runApplication($application) {
            if ( !is_null($application) ) {
                if ( method_exists($application, self::$task['action'] . 'Action') ) {
                    self::$task['action'] = self::$task['action'] . 'Action';
                    return self::$task['action'];
                } else if ( method_exists($application, ucfirst(self::$task['action']) . 'Action') ) {
                    self::$task['action'] = ucfirst(self::$task['action']) . 'Action';
                    return self::$task['action'];
                } else if ( method_exists($application, strtolower(self::$task['action']) . 'Action') ) {
                    self::$task['action'] = strtolower(self::$task['action']) . 'Action';
                    return self::$task['action'];
                } else if ( @is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . self::$task['action'] . '.' . Rpd::$c['raintpl']['tpl_ext']) ) {
                    self::$task['static'] = self::$task['action'];
                    self::$task['action'] = null;
                    return self::$task['action'];
                } else if ( @is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['defaultApplication'] . DIRECTORY_SEPARATOR . self::$task['action'] . '.' . Rpd::$c['raintpl']['tpl_ext']) ) {
                    // return default app template (no function called)
                    self::$task['static'] = self::$task['action'];
                    self::$task['action'] = null;
                    return self::$task['action'];
                } else if ( method_exists($application, Rpd::$c['rapid']['defaultAction'] . 'Action') ) {
                    // run function of default app
                    self::$task['action'] = Rpd::$c['rapid']['defaultAction'] . 'Action';
                    return self::$task['action'];
                } else self::$errors[] = "Error in Rapid class runApplication function: both the <em>" . self::$task['action'] . "Action</em> and the default <em>" . Rpd::$c['rapid']['defaultAction'] . "Action</em> functions does not exists in <em>" . self::$task['controller'] . "</em> class.";
            } else self::$errors[] = "Error in Rapid class runApplication function: the loaded application is null.";
            return false;
        }

        /**
         * Load the attached layout for application.
        */
        public static function loadLayout($findDefault = false) {
            $culture = Rapid::$culture;
            if ( !$findDefault ) {
                $find = R::findOne('layoutlinks', 'application = ?', array(self::$task['application']));
                if ( $find->id )
                    if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $find->layout) )
                        return str_replace('.' . Rpd::$c['raintpl']['tpl_ext'], '', $find->layout);
                    else return self::loadLayout(true);
                else return self::loadLayout(true);
            } else {
                if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . strtolower(self::$task['application']) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                    $return = strtolower(self::$task['application']);
                else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . ucfirst(self::$task['application']) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                    $return = ucfirst(self::$task['application']);
                else $return = Rpd::$c['rapid']['defaultLayout'];
            }
            
            return 'layout.' . $return;
        }

        /**
         *  Check if the application own sources and load them.
        */ 
        public static function assignSources() {
            if ( is_file('applications' . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['globalSourcesFile']) )
                $globalSources = json_decode(file_get_contents('applications' . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['globalSourcesFile']), true);
            else $globalSources = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
            if ( is_file('applications' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']) )
                $appSources = json_decode(file_get_contents('applications' . DIRECTORY_SEPARATOR . self::$task['application'] . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']), true);
            else $appSources = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
            $sources = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
            foreach ( $globalSources as $sourceType => $item )
                foreach ( $item as $source )
                    if ( !empty($source) && !in_array($source, $sources[$sourceType]) ) $sources[$sourceType][] = $source;
            foreach ( $appSources as $sourceType => $item )
                foreach ( $item as $source )
                    if ( !empty($source) && !in_array($source, $sources[$sourceType]) ) $sources[$sourceType][] = $source;
            Rapid::assign('SOURCES', $sources);
        }

        /**
         *  Load the defined Meta data for Application (or set defaults).
        */ 
        public static function assignMeta() {
            $defData = array('title' => "Rapid.", 'keywords' => "rapid,framework", 'description' => "The Rapid Framework.");
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']) )
                $data = json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']), true);
                if ( isset($data[self::$task['application']]) ) $appData = $data[self::$task['application']];
            else $appData = $defData;
            Rpd::a('APP', $appData);
        }
        
        /**
         *  Assign the preferences of the site.
        */ 
        public static function assignPreferences() {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile']) )
                Rpd::a('SITE', array_merge(array('url' => $_SERVER['HTTP_HOST']), json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile']), true)));
        }

        /**
         *  Determine the template build level
        */ 
        public static function buildLevel() {
            $return = 'frame';
            if ( Rpd::rq(self::$task['args']['build-level']) || Rpd::rq($_POST['build-level']) ) {
                if ( '3' == self::$task['args']['build-level'] || '3' == $_POST['build-level'] )
                    $return = 'application';
                else if ( '2' == self::$task['args']['build-level'] || '2' == $_POST['build-level'] )
                    $return = 'layout';
                unset(self::$task['args']['build-level']);
            }
            return $return;
        }

        /**
         *  Assign variables to template files.
        */ 
        public static function assign($name, $value = '', $variables = array()) {
            $notAllowed = array('LAYOUT_CONTENT', 'APPLICATION_CONTENT', 'CULTURE');
            if ( !empty($name) && !in_array($name, $notAllowed) ) {
                Rapid::$tpl->assign($name, Rapid::translation($value, $variables));
                return true;
            } else return false;
        }

        /**
         * Autoload not defined classes and interfaces.
        */ 
        public static function autoload($class) {
            $backtrace = debug_backtrace();
            $dir = explode(DIRECTORY_SEPARATOR, $backtrace[1]['file']);
            $calldir = "";
            for ( $x = 0; $x < count($dir)-1; $x++ ) $calldir .= $dir[$x] . DIRECTORY_SEPARATOR;
            if ( is_file($calldir . $class . '.class.php') ) $require = $class;
            else if ( is_file($calldir . strtolower($class) . '.class.php') ) $require = strtolower($class);
            else if ( is_file($calldir . ucfirst($class) . '.class.php') ) $require = ucfirst($class);
            else if ( -1 < strpos($class, 'Controller') ) {
                $app = substr($class, 0, strpos($class, 'Controller'));
                if ( is_file('applications' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $class . '.class.php') )
                    require_once 'applications' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $class . '.class.php';
            } else if ( -1 < strpos($class, 'Modell') ) {
                $app = substr($class, 0, strpos($class, 'Modell'));
                if ( is_file('applications' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $class . '.class.php') )
                    require_once 'applications' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . $class . '.class.php';
            }
            if ( isset($require) ) require_once $calldir . $require . '.class.php';
            else return false;
        }

        /**
         * Get the existing Applications.
         * @return array Array with the Applications
        */ 
        public static function getApplications() {
            $applicationsArray = array_diff(scandir('applications'), array('.', '..'));
            foreach ( $applicationsArray as $key => $application )
                if ( !is_dir('applications' . DIRECTORY_SEPARATOR . $application) || ( 'administrator' == strtolower($application) && !Rpd::$c['rapid']['editAdmin'] ) )
                    unset($applicationsArray[$key]);
            return $applicationsArray;
        }

        /**
         * Get the existing Layouts.
         * @return array Array with the Layouts
        */ 
        public static function getLayouts() {
            $tpl_dir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR;
            $tpl_ext = Rpd::$c['raintpl']['tpl_ext'];
            $scan = array_diff(scandir($tpl_dir), array('.', '..'));
            $layouts = array();
            foreach ( $scan as $item )
                if ( is_file($tpl_dir . $item) && 'layout.' == substr($item, 0, 7) && '.' . $tpl_ext == substr($item, (strlen($tpl_ext)+1)*-1) )
                    $layouts[] = $item;
            return $layouts;
        }

        /**
         * Get the existing Cultures.
         * @return array Array with the Cultures
        */ 
        public static function getCultures() {
            $culturesArray = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir']), array('.', '..'));
            foreach ( $culturesArray as $key => $culture )
                if ( !is_dir(Rpd::$c['raintpl']['tpl_dir'] . $culture) ) unset($culturesArray[$key]);
            return $culturesArray;
        }

        /**
         * Looking for translation for input.
         * @return string String with translation or input string
        */ 
        public static function translation($value, $variables = array()) {
            if ( is_file(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n') && Rpd::$cl != Rpd::$c['rapid']['culture'] )
                $translations = json_decode(@file_get_contents(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n'), true);
            else $translations = array();
            if ( "string" == gettype($value) ) {
                if ( isset($translations[$value]) ) $translate = $translations[$value];
                else $translate = $value;
                $count = 0;
                while ( -1 < strpos($translate, '#text#') ) 
                    if ( isset($variables[$count]) )
                        $translate = preg_replace("/#text#/", $variables[$count++], $translate, 1);
                    else break;
                $value = $translate;
            }
            return $value;
        }

        /**
         * Get the current version of Rapid from Github.
        */ 
        public static function getCurrentVersion($return = false, $zipUrl = false) {
            $version = Rpd::$v;
            
            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_USERAGENT, 'vargatamas');
            curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/vargatamas/rapid/tags");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            $content = json_decode($data);
            
            if ( $return ) return $content[0]->name;
            if ( $zipUrl ) return $content[0]->zipball_url;
        }

        /**
         * Remove directories recursively.
        */
        public static function delTree($dir) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) ( is_dir($dir . DIRECTORY_SEPARATOR . $file) ) ? Rapid::delTree($dir . DIRECTORY_SEPARATOR . $file) : unlink($dir . DIRECTORY_SEPARATOR . $file);
            return rmdir($dir);
        }

    }

    /**
     * The Authentication for Rapid. Extend this class if your Application requires authentication.
    */ 
    class RapidAuth {
        
        public $authenticated = false;
        
        // Creates session, create admin user, if no user in database or login if posted.
        public function __construct() {
            if ( 0 == count(R::findAll('users')) ) {
                $adminArray = array('username' => 'rapid', 'password' => uniqid());
                $admin = R::dispense('users');
                $admin->username = $adminArray['username'];
                $admin->password = md5($adminArray['password']);
                $admin->is_active = true;
                $admin->depth = 0;
                $admin->last_modified = date('Y-m-d H:i:s', time());
                R::store($admin);
                Rpd::a('ADMIN', $adminArray);
            }
            if ( !isset($_POST['auth']) ) {
                if ( isset($_SESSION['user']) && 0 < intval($_SESSION['user']['id']) ) {
                    $user = R::load('users', intval($_SESSION['user']['id']));
                    if ( !$user->id ) {
                        $this->authenticated = false;
                        unset($_SESSION['user']);
                    } else {
                        $this->authenticated = true;
                        Rpd::a('USER', $user);
                    }
                } else $this->authenticated = false;
            } else $this->authenticate();
        }
        
        // Authenticating the posted login data.
        public function authenticate() {
            if ( isset($_POST['auth']['username']) && isset($_POST['auth']['password']) ) {
                $username = $_POST['auth']['username'];
                $password = md5($_POST['auth']['password']);
                $depth = eval('return ( isset(' . get_class($this) . '::' . Rpd::$c['rapid']['controllerAuthVar'] . ') ? ' . get_class($this) . '::' . Rpd::$c['rapid']['controllerAuthVar'] . ' : 1 );');
                $user = R::findOne('users', 'username = ? AND password = ? AND is_active = ? AND depth <= ?', array($username, $password, true, $depth));
                if ( $user->id ) {
                    $this->authenticated = true;
                    $_SESSION['user'] = $user->getProperties();
                    $_SESSION['current_version'] = Rapid::getCurrentVersion(true);
                    Rpd::a('USER', $user);
                } else {
                    $this->authenticated = false;
                    Rpd::a('AUTH_FAIL', true);
                }
            } else if ( isset($_POST['auth']['logout']) ) {
                $this->authenticated = false;
                unset($_SESSION['user']);
            } else $this->authenticated = false;
        }
        
    }

    /**
     * Rapid validators class.
    */ 
    class RapidValidate {
        
        // Checks if $field is empty.
        public static function nonEmpty($field) {
            if ( empty($field) ) return false;
            return true;
        }
        
        // Checks if $field is set.
        public static function required($field) {
            if ( null === $field ) return false;
            return true;
        }
        
        // Checks the minimum $lenght of $field.
        public static function minLength($field, $length = 0) {
            if ( $length > strlen($field) ) return false;
            return true;
        }
        
        // Checks the maximum $length of $field.
        public static function maxLength($field, $length = 0) {
            if ( $length < strlen($field) ) return false;
            return true;
        }

        // Check if $mail is a correct e-mail address.
        public static function isMail($mail) {
            if ( false === filter_var($mail, FILTER_VALIDATE_EMAIL) ) return false;
            return true;
        }

        // Check if $pattern matches on $string.
        public static function regexpMatch($string, $pattern) {
            if ( false === filter_var($string, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $pattern))) )
                return false;
            return true;
        }

        // Check if $field1 is equals $field2
        public static function compare($field1, $field2) {
            if ( $field1 != $field2 ) return false;
            return true;
        }

    }

    /**
     * This Rpd class is a shorthand for Rapid's static functions and variables.
    */ 
    class Rpd {
        
        // Rapid shorthands
        public static $c = array();
        
        public static $cl = '';
        
        public static $v = '';
        
        public static function a($n, $v = '', $vb = array()) { return Rapid::assign($n, $v, $vb); }
        
        public static function t($v = '', $vb = array()) { return Rapid::translation($v, $vb); }
        
        public static function gA(){ return Rapid::getApplications(); }
        
        public static function gL(){ return Rapid::getLayouts(); }
        
        public static function gC(){ return Rapid::getCultures(); }
        
        public static function dT($d) { return Rapid::delTree($d); }
        
        //RapidValidate shorthands
        public static function nE($f){ return RapidValidate::nonEmpty($f); }
        
        public static function rq($f) { return RapidValidate::required($f); }
        
        public static function mL($f, $l = 0) { return RapidValidate::minLength($f, $l); }
        
        public static function xL($f, $l = 0) { return RapidValidate::maxLength($f, $l); }
        
        public static function iM($m) { return RapidValidate::isMail($m); }
        
        public static function rM($s, $p) { return RapidValidate::regexpMatch($s, $p); }
        
        public static function cp($f1, $f2) { return RapidValidate::compare($f1, $f2); }
        
    }
    // Fill variables.
    Rpd::$c = Rapid::configuration(true);
    Rpd::$cl = Rapid::$culture;
    Rpd::$v = Rapid::$version;

?>
