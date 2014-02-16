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
                      $version =        "v1.1.1";
        
        public $task =                  array(),
               $errors =                array();
        
        /**
         * Run all the initialization function and when there is no error, run the application.
        */
        public function __construct() {
            // Autoload function
            spl_autoload_register('Rapid::autoload');

            // RedBean
            if ( is_file(Rapid::$dir . '/rb.php') ) {
                require_once Rapid::$dir . '/rb.php';
                if ( Rpd::rq(Rpd::$c['db']) && 0 < count(Rpd::$c['db']) )
                    R::setup('mysql:host=' . Rpd::$c['db']['host'] . ';dbname=' . Rpd::$c['db']['dbname'], Rpd::$c['db']['username'], Rpd::$c['db']['password']);
                else {
                    Rpd::a('DBTMP', true);
                    R::setup();
                }
            } else $this->crash('Error in Rapid class contructor function: RedBean file (' . getcwd() . '/' . Rapid::$dir . '/rb.php) does not exists.');
            
            // Rapid gives control to application
            $this->pathToTask();
            $application = $this->loadApplication();
            if ( !is_null($application) ) {
                $action = $this->runApplication($application);
                if ( false !== $action ) {
                    // Get the application's content
                    $culture = Rapid::$culture;
                    $this->assignSources();
                    $this->assignMeta();
                    $this->assignPreferences();
                    if ( $application instanceof RapidAuth && !$application->authenticated ) 
                        $authError = true;
					else if ( $application->authenticated && $_SESSION['user']['depth'] > eval('return ' . get_class($application) . '::' . Rpd::$c['rapid']['controllerAuthVar'] . ';') )
						$applicationContent = "Permission denied. Your account level is not acceptable here.";
                    else {
                        if ( null !== $action && !isset($this->task['static']) ) {
                            $return = $application->$action($this->task['args']);
                            $template = eval('return (isset(' . $this->task['controller'] . '::' . Rpd::$c['rapid']['controllerTemplateVar'] . ')?' . $this->task['controller'] . '::' . Rpd::$c['rapid']['controllerTemplateVar'] . ':"' . strtolower(str_replace('Action', '', $this->task['action'])) . '");');
                            if ( !is_null($return) ) $applicationContent = $return;
                            else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . '/templates/' . $this->task['application'] . '/' . $template . '.' . Rpd::$c['raintpl']['tpl_ext']) ) 
                                $applicationContent = Rapid::$tpl->draw($culture . '/templates/' . $this->task['application'] . '/' . $template, $return_string = true);
                            else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . '/templates/' . $this->task['application'] . '/' . $template . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                                $applicationContent = Rapid::$tpl->draw(Rpd::$c['rapid']['culture'] . '/templates/' . $this->task['application'] . '/' . $template, $return_string = true);
                            else $applicationContent = 'The template (' . $template . '.' . Rpd::$c['raintpl']['tpl_ext'] . ') file for application (' . $this->task['application'] . ') does not exists.';
                        } else $applicationContent = Rapid::$tpl->draw($culture . '/templates/' . $this->task['application'] . '/' . $this->task['static'], $return_string = true);
                    }

                    // Build the template
                    if ( isset($authError) ) {
                        $applicationContent = Rapid::$tpl->draw($culture . '/' . Rpd::$c['rapid']['authTpl'], $return_string = true);
                        Rapid::$tpl->assign('LAYOUT_CONTENT', $applicationContent);
                        Rapid::$tpl->draw('frame');
                    } else {
                        if ( 'application' == $this->buildLevel() ) 
                            print $applicationContent;
                        else if ( 'layout' == $this->buildLevel() ) {
                            $layout = $this->loadLayout();
                            Rapid::$tpl->assign('APPLICATION_CONTENT', '<!-- application content start -->' . $applicationContent . '<!-- application content end -->');
                            Rapid::$tpl->draw($culture . '/layouts/' . $layout);
                        } else {
                            $layout = $this->loadLayout();
                            Rapid::$tpl->assign('APPLICATION_CONTENT', '<!-- application content start -->' . $applicationContent . '<!-- application content end -->');
                            $layoutContent = Rapid::$tpl->draw($culture . '/layouts/' . $layout, $return_string = true);
                            Rapid::$tpl->assign('LAYOUT_CONTENT', $layoutContent);
                            Rapid::$tpl->draw('frame');
                        }
                    }
                }
            }
            if ( 0 < count($this->errors) ) $this->crash();
        }

        /**
         * This function do the configuration of Rapid and set the defaults.
        */ 
        public static function configuration($return = false) {
            if ( is_file(Rapid::$dir . '/' . Rapid::$configFile) ) {
                require_once Rapid::$dir . '/' . Rapid::$configFile;
                Rpd::$c = $configuration;
                if ( '/' != substr(Rpd::$c['raintpl']['tpl_dir'], -1) ) Rpd::$c['raintpl']['tpl_dir'] .= '/';
                if ( '/' != substr(Rpd::$c['rapid']['translationsDir'], -1) ) Rpd::$c['rapid']['translationsDir'] .= '/';
                if ( '$' != substr(Rpd::$c['rapid']['controllerTemplateVar'], 0, 1) ) 
                    Rpd::$c['rapid']['controllerTemplateVar'] = '$' . Rpd::$c['rapid']['controllerTemplateVar'];
            } else {
                Rpd::$c['raintpl'] = array(
                                                'tpl_dir' => 'views/',
                                                'tpl_ext' => 'tpl',
                                                'cache_dir' => 'cache/',
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
                                                'translationsDir' => 'translations/',
                                                'metaFile' => 'meta.json',
                                                'siteFile' => 'site.json',
                                                'editAdmin' => false,
                                                'libEditables' => array('js', 'less', 'css', 'txt'),
												'updaterFile' => 'updater.php',
												'installerFile' => 'creator.php'
                                            );
                Rpd::$c['db'] = array();
            }

            // load RainTPL
            require_once Rapid::$dir . '/rain.tpl.class.php';
            if ( isset(Rpd::$c['raintpl']) )
                foreach ( Rpd::$c['raintpl'] as $key => $value ) raintpl::configure($key, $value);
            Rapid::$tpl = new RainTPL();

            // Set culture
            $culture = Rpd::$c['rapid']['culture'];
            if ( Rpd::rq($_POST['culture']['name']) && in_array($_POST['culture']['name'], Rpd::gC()) )
                $_SESSION['culture'] = $_POST['culture']['name'];
            if ( Rpd::rq($_SESSION['culture']) ) {
                $sCulture = $_SESSION['culture'];
                if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . '/' . $sCulture) ) $culture = $_SESSION['culture'];
            }
            Rapid::$tpl->assign('CULTURE', $culture);
            Rapid::$culture = $culture;
            
            if ( $return ) return Rpd::$c;
        }

        /**
         * This function is called when something go wrong. It dies with the error messages.
        */
        private function crash($message = "") {
            if ( !empty($message) ) echo '- ' . $message;
            else foreach ( $this->errors as $error ) echo '- ' . $error . '<br />';
            die();
        }
        
        /**
         * Convert URL to Array and define the controller, action and arguments after checked the routing.
         * This function requires the .htaccess file with this line: 'RewriteRule ^(.*)$ index.php?path=$1 [QSA,L]'
        */
        private function pathToTask() {
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
                $this->task['controller'] = ( !empty($pathArray[0]) ? $pathArray[0] : Rpd::$c['rapid']['defaultApplication'] );
                if ( !empty($pathArray[1]) ) $this->task['action'] = $pathArray[1];
                else {
                    if ( @is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . '/templates/' . Rpd::$c['rapid']['defaultApplication'] . '/' . $pathArray[0] . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                        $this->task['action'] = $pathArray[0];
                    else $this->task['action'] = Rpd::$c['rapid']['defaultAction'];
                }
                $this->createArgs($pathArray);
            } else {
                $this->task['controller'] = $routing[0];
                $this->task['action'] = $routing[1];
                $this->createArgs($routing);
            }
        }

        /**
         * Now we know the requested controller and it's action. It's time to collect the arguments and pass to controller's action.
         * This function checks if there is any array argument (array separator can set through argArraySeparator).
        */ 
        private function createArgs($pathArray = array()) {
            if ( isset($pathArray[0]) ) unset($pathArray[0]);
            if ( isset($pathArray[1]) ) unset($pathArray[1]);
            
            $args = array();
            if ( "array" == gettype($pathArray) ) 
                foreach ( $pathArray as $item ) {
                    if ( 1 < count(explode(Rpd::$c['rapid']['argArraySeparator'], $item)) && $arg = explode(Rpd::$c['rapid']['argArraySeparator'], $item) ) 
                        $args[$arg[0]] = $arg[count($arg)-1];
                    else $args[] = $item;
                }
            
            $this->task['args'] = $args;
        }

        /**
         * Gives the control to the requested Application (stored in $task variable).
         * Default application is defined in configuration file (defaultApplication).
         * 
         * @return Controller Object or NULL
        */
        private function loadApplication() {
            $application = null;
            if ( is_dir('applications/' . $this->task['controller']) ) {
                if ( is_file('applications/' . $this->task['controller'] . '/' . $this->task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications/' . $this->task['controller'] . '/' . $this->task['controller'] . 'Controller.class.php';
                    $this->task['controller'] = $this->task['controller'] . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . $this->task['controller'] . '/' . ucfirst($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . $this->task['controller'] . '/' . ucfirst($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = ucfirst($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . $this->task['controller'] . '/' . strtolower($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . $this->task['controller'] . '/' . strtolower($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = strtolower($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else $this->errors[] = "Error in Rapid class loadApplication function: the <em>" . $this->task['controller'] . "Controller.class.php</em> does not exists in <em>applications/" . $this->task['controller'] . "</em>.";
            } else if ( is_dir('applications/' . ucfirst($this->task['controller'])) ) {
                if ( is_file('applications/' . ucfirst($this->task['controller']) . '/' . $this->task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications/' . ucfirst($this->task['controller']) . '/' . $this->task['controller'] . 'Controller.class.php';
                    $this->task['controller'] = $this->task['controller'] . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . ucfirst($this->task['controller']) . '/' . ucfirst($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . ucfirst($this->task['controller']) . '/' . ucfirst($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = ucfirst($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . ucfirst($this->task['controller']) . '/' . strtolower($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . ucfirst($this->task['controller']) . '/' . strtolower($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = strtolower($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else $this->errors[] = "Error in Rapid class loadApplication function: the <em>" . $this->task['controller'] . "Controller.class.php</em> does not exists in <em>applications/" . ucfirst($this->task['controller']) . "</em>.";
            } else if ( is_dir('applications/' . strtolower($this->task['controller'])) ) {
                if ( is_file('applications/' . strtolower($this->task['controller']) . '/' . $this->task['controller'] . 'Controller.class.php') ) {
                    require_once 'applications/' . strtolower($this->task['controller']) . '/' . $this->task['controller'] . 'Controller.class.php';
                    $this->task['controller'] = $this->task['controller'] . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . strtolower($this->task['controller']) . '/' . ucfirst($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . strtolower($this->task['controller']) . '/' . ucfirst($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = ucfirst($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else if ( is_file('applications/' . strtolower($this->task['controller']) . '/' . strtolower($this->task['controller']) . 'Controller.class.php') ) {
                    require_once 'applications/' . strtolower($this->task['controller']) . '/' . strtolower($this->task['controller']) . 'Controller.class.php';
                    $this->task['controller'] = strtolower($this->task['controller']) . 'Controller';
                    $application = new $this->task['controller']();
                } else $this->errors[] = "Error in Rapid class loadApplication function: the <em>" . $this->task['controller'] . "Controller.class.php</em> does not exists in <em>applications/" . strtolower($this->task['controller']) . "</em>.";
            } else {
                if ( isset(Rpd::$c['rapid']) && isset(Rpd::$c['rapid']['defaultApplication']) && $action = Rpd::$c['rapid']['defaultApplication'] ) {
                    if ( is_file('applications/' . $action . '/' . $action . 'Controller.class.php') ) {
                        require_once 'applications/' . $action . '/' . $action . 'Controller.class.php';
                        $this->task['controller'] = $action . 'Controller';
                        $application = new $this->task['controller']();
                    } else $this->errors[] = "Error in Rapid class loadApplication function: defaultApplication is defined (<em>" . Rpd::$c['rapid']['defaultApplication'] . "</em>), but does not exists in applications dir.";
                } else $this->errors[] = "Error in Rapid class loadApplication function: defaultApplication not defined in configuration file.";
            }
            $this->task['application'] = str_replace('Controller', '', $this->task['controller']);
            return $application;
        }
        
        /**
         * If we successfully loaded the requested (or default) application, let's run it's requested (or default) action.
        */ 
        private function runApplication($application) {
            if ( !is_null($application) ) {
                if ( method_exists($application, $this->task['action'] . 'Action') ) {
                    $this->task['action'] = $this->task['action'] . 'Action';
                    return $this->task['action'];
                } else if ( method_exists($application, ucfirst($this->task['action']) . 'Action') ) {
                    $this->task['action'] = ucfirst($this->task['action']) . 'Action';
                    return $this->task['action'];
                } else if ( method_exists($application, strtolower($this->task['action']) . 'Action') ) {
                    $this->task['action'] = strtolower($this->task['action']) . 'Action';
                    return $this->task['action'];
                } else if ( @is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . '/templates/' . Rpd::$c['rapid']['defaultApplication'] . '/' . $this->task['action'] . '.' . Rpd::$c['raintpl']['tpl_ext']) ) {
                    $this->task['static'] = $this->task['action'];
                    $this->task['action'] = null;
                    return $this->task['action'];
                } else if ( method_exists($application, Rpd::$c['rapid']['defaultAction'] . 'Action') ) {
                    $this->task['action'] = Rpd::$c['rapid']['defaultAction'] . 'Action';
                    return $this->task['action'];
                } else $this->errors[] = "Error in Rapid class runApplication function: both the <em>" . $this->task['action'] . "Action</em> and the default <em>" . Rpd::$c['rapid']['defaultAction'] . "Action</em> functions does not exists in <em>" . $this->task['controller'] . "</em> class.";
            } else $this->errors[] = "Error in Rapid class runApplication function: the loaded application is null.";
            return false;
        }

        /**
         * Load the attached layout for application.
        */
        private function loadLayout($findDefault = false) {
            if ( !$findDefault ) {
                $find = R::findOne('layoutlinks', 'application = ?', array($this->task['application']));
                if ( $find->id )
                    return str_replace('.' . Rpd::$c['raintpl']['tpl_ext'], '', $find->layout);
                else return $this->loadLayout(true);
            } else {
                $culture = Rapid::$culture;
                if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . '/layouts/layout.' . strtolower($this->task['application']) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                    $return = strtolower($this->task['application']);
                else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $culture . '/layouts/layout.' . ucfirst($this->task['application']) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                    $return = ucfirst($this->task['application']);
                else $return = Rpd::$c['rapid']['defaultLayout'];
            }
            
            return 'layout.' . $return;
        }

        /**
         *  Check if the application own sources and load them.
        */ 
        private function assignSources() {
            if ( is_file('applications/' . $this->task['application'] . '/' . Rpd::$c['rapid']['sourcesFile']) ) {
                $sources = json_decode(file_get_contents('applications/' . $this->task['application'] . '/' . Rpd::$c['rapid']['sourcesFile']), true);
                Rapid::assign('SOURCES', $sources);
            }
        }

        /**
         *  Load the defined Meta data for Application (or set defaults).
        */ 
        private function assignMeta() {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . '/templates/' . $this->task['application'] . '/' . Rpd::$c['rapid']['metaFile']) )
                $appData = json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . '/templates/' . $this->task['application'] . '/' . Rpd::$c['rapid']['metaFile']), true);
            else $appData = array('title' => "Rapid.", 'keywords' => "rapid,framework", 'description' => "The Rapid Framework.");
            Rpd::a('APP', $appData);
        }
        
        /**
         *  Assign the preferences of the site.
        */ 
        private function assignPreferences() {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['siteFile']) )
                Rpd::a('SITE', array_merge(array('url' => $_SERVER['HTTP_HOST']), json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['siteFile']), true)));
        }

        /**
         *  Determine the template build level
        */ 
        private function buildLevel() {
            $return = 'frame';
            if ( Rpd::rq($this->task['args']['build-level']) || Rpd::rq($_POST['build-level']) ) {
                if ( '3' == $this->task['args']['build-level'] || '3' == $_POST['build-level'] )
                    $return = 'application';
                else if ( '2' == $this->task['args']['build-level'] || '2' == $_POST['build-level'] )
                    $return = 'layout';
            }
            return $return;
        }

        /**
         *  Assign variables to template files.
        */ 
        public static function assign($name, $value = '') {
            $notAllowed = array('LAYOUT_CONTENT', 'APPLICATION_CONTENT', 'CULTURE');
            if ( !empty($name) && !in_array($name, $notAllowed) ) {
                Rapid::$tpl->assign($name, Rapid::translation($value));
                return false;
            } else return false;
        }

        /**
         * Autoload not defined classes and interfaces.
        */ 
        public static function autoload($class) {
            $backtrace = debug_backtrace();
            $dir = explode('/', $backtrace[1]['file']);
            $calldir = "";
            for ( $x = 0; $x < count($dir)-1; $x++ ) $calldir .= $dir[$x] . '/';
            if ( is_file($calldir . $class . '.class.php') ) $require = $class;
            else if ( is_file($calldir . strtolower($class) . '.class.php') ) $require = strtolower($class);
            else if ( is_file($calldir . ucfirst($class) . '.class.php') ) $require = ucfirst($class);
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
                if ( !is_dir('applications/' . $application) ) unset($applicationsArray[$key]);
            return $applicationsArray;
        }

        /**
         * Get the existing Layouts.
         * @return array Array with the Layouts
        */ 
        public static function getLayouts() {
            $tpl_dir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . '/layouts/';
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
        public static function translation($value) {
            if ( is_file(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n') && Rpd::$cl != Rpd::$c['rapid']['culture'] ) {
                $translations = json_decode(file_get_contents(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n'), true);
                if ( "string" == gettype($value) ) {
                    if ( isset($translations[$value]) ) $value = $translations[$value];
                    else {
                        $fromArray = array_keys($translations);
                        foreach ( $fromArray as $from ) {
                            $matches = null;
                            $from = str_replace('#text#', '.+', $from);
                            $returnValue = preg_match('/' . $from . '/', $value, $matches);
                            if ( 0 < count($matches) )
                                for ( $x = 1; $x < count($matches); $x++ )
                                    $value = preg_replace('/\#text\#/', $matches[$x], $translations[str_replace('.+', '#text#', $from)], 1);
                        }
                    }
                }
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
        
        public static function a($n, $v = '') { return Rapid::assign($n, $v); }
        
        public static function gA(){ return Rapid::getApplications(); }
        
        public static function gL(){ return Rapid::getLayouts(); }
        
        public static function gC(){ return Rapid::getCultures(); }
        
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
