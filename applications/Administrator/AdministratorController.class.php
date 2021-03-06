<?php

class AdministratorController extends RapidAuth {

    public static $template;
    public static $auth_depth = 0;

    /**
     * Fill the Beans dropdown list. 
     * Use contructor to assign variables to Layout.
     * Important: do not forget to call parent::__construct() if this class extends another Rapid class!
    */ 
    public function __construct($args = array()) {
        parent::__construct();
        if ( 0 < count(AdministratorModell::inspectBeans()) ) Rpd::a('navBeans', AdministratorModell::inspectBeans());
        Rpd::a('cultures', Rpd::gC());
        Rpd::a('activeCulture', Rpd::$cl);
        Rpd::a('thisVersion', Rpd::$v);
        if ( Rpd::$v != $_SESSION['current_version'] ) Rpd::a('currentVersion', $_SESSION['current_version']);
        Rpd::a('baseURL', "/");
    }

    /**
     * The default action when nothing requested.
    */ 
    public function indexAction($args = array()) {
        $apps = array_diff(scandir('applications'), array('.', '..'));
        foreach ( $apps as $k => $a ) if ( !is_dir('applications' . DIRECTORY_SEPARATOR . $a) ) unset($apps[$k]);
        Rpd::a('appCount', count($apps));

        Rpd::a('langCount', count(Rpd::gC()));

        $index = false;
        if ( is_file('robots.txt') ) {
            $robot = file_get_contents('robots.txt');
            if ( false === strpos($robot, 'Disallow: /') ) $index = true;
            else $index = false;
        } else $index = true;
        Rpd::a('index', $index);
    }

    /**
     * Managing the Layouts. (Add, Edit and Remove).
    */ 
    public function layoutsAction($args = array()) {
        Rpd::a('menu', array('layoutActive' => true, 'layoutsActive' => true));

        $tpl_ext = Rpd::$c['raintpl']['tpl_ext'];
        Rpd::a('tpl_ext', $tpl_ext);
        if ( '' == $args[0] ) {
            // List layouts
            $tpl_dir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts';
            $scan = array_diff(scandir($tpl_dir), array('.', '..'));
            $layoutArray = array();
            foreach ( $scan as $key => $item ) {
                if ( is_file($tpl_dir . DIRECTORY_SEPARATOR . $item) && 'layout.' == substr($item, 0, 7) && '.' . $tpl_ext == substr($item, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) ) {
                    if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($item) != 'layout.administrator.' . $tpl_ext ) )
                        $layoutArray[] = array(
                                                    'name' => $item,
                                                    'last_modified' => date('Y-m-d H:i:s', filemtime($tpl_dir . DIRECTORY_SEPARATOR . $item)),
                                                    'writable' => is_writable($tpl_dir . DIRECTORY_SEPARATOR . $item)
                                                );
                }
            }
            
            $items = 30;
            $start = ( 0 <= intval($args['start']) && count($layoutArray) > intval($args['start']) ? intval($args['start']) : 0 );
            if ( ($start + $items) < count($layoutArray) ) Rpd::a('nextStart', ($start + $items));
            foreach ( $layoutArray as $key => $item ) if ( $start > $key || ($start + $items) <= $key ) unset($layoutArray[$key]);
            if ( ($start - $items) > -1 ) Rpd::a('prevStart', ($start - $items));
            Rpd::a('page', ($start + $items) / $items);
            
            if ( 0 < count($layoutArray) ) Rpd::a('layouts', $layoutArray);
        } else if ( 'add' == $args[0] ) {
            // Add new layout
            if ( 'save' == $args[1] && Rpd::rq($_POST['layout']) ) {
                if ( Rpd::nE($_POST['layout']['filename']) && Rpd::nE($_POST['layout']['content']) && -1 < strpos($_POST['layout']['content'], '{$APPLICATION_CONTENT}') ) {
                    $filename = $_POST['layout']['filename'];
                    $content = $_POST['layout']['content'];
                    $created = array();
                    if ( Rpd::rq($_POST['layout']['allLanguage']) ) $languages = Rpd::gC();
                    else $languages = array(Rapid::$culture);
                    foreach ( $languages as $language )
                        if ( !is_file(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . $filename . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                            if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . $filename . '.' . Rpd::$c['raintpl']['tpl_ext'], $content) )
                                $created[] = $language;
                    if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . $filename . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                        Rpd::a('success', "The Layout is stored correctly.#text#", array(( 0 < count($created) ? ' Layouts created for ' . count($created) . ' language(s).' : '' )));
                    else Rpd::a('error', "Something went wrong while saving the Layout. Probably permission denied on layouts directory (#text#). Layout does not saved.", array(Rpd::$c['raintpl']['tpl_dir']));
                    $this->layoutsAction();
                } else {
                    Rpd::a('error', "You not filled the name or content field or the content does not contain the {\$APPLICATION_CONTENT} variable.");
                    Rpd::a('layout', $_POST['layout']);
                    AdministratorController::$template = 'layout.add';
                }
            } else {
                Rpd::a('tpl_ext', Rpd::$c['raintpl']['tpl_ext']);
                AdministratorController::$template = 'layout.add';
            }
        } else if ( 'edit' == $args[0] && Rpd::rq($args['filename']) ) {
            // Edit layout
            $tpl_dir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts';
            $tpl_ext = Rpd::$c['raintpl']['tpl_ext'];
            $scan = array_diff(scandir($tpl_dir), array('.', '..'));
            $layoutArray = array();
            foreach ( $scan as $key => $item ) {
                if ( is_file($tpl_dir . DIRECTORY_SEPARATOR . $item) && 'layout.' == substr($item, 0, 7) && '.' . $tpl_ext == substr($item, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) ) {
                    if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($item) != 'layout.administrator.' . $tpl_ext ) )
                        $layoutArray[] = array(
                                                    'filename' => $item,
                                                    'last_modified' => date('Y-m-d H:i:s', filemtime($tpl_dir . DIRECTORY_SEPARATOR . $item)),
                                                    'writable' => is_writable($tpl_dir . DIRECTORY_SEPARATOR . $item)
                                                );
                }
            }
            if ( 0 < count($layoutArray) ) Rpd::a('otherLayouts', $layoutArray);

            $filename = $args['filename'];
            Rpd::a('filename', $filename);
            if ( 'save' != $args[1] ) {
                $content = htmlentities(@file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $filename), ENT_QUOTES, "UTF-8");
                Rpd::a('layout', array(
                                                'content' => $content,
                                                'writable' => is_writable(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $filename),
                                                'last_modified' => date('Y-m-d H:i:s', filemtime(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $filename))
                                        ));
                AdministratorController::$template = 'layout.edit';
            } else {
                // AJaX call
                if ( Rpd::nE($_POST['layout']['content']) && -1 < strpos($_POST['layout']['content'], '{$APPLICATION_CONTENT}') ) {
                    $content = $_POST['layout']['content'];
                    if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $filename, $content) )
                        return json_encode(array('success' => "The Layout is updated correctly."));
                    else return json_encode(array('error' => Rpd::t("Something went wrong while saving the Layout. Probably permission denied on templates directory (#text#) or the file already exists (#text#). Layout does not saved.", array(Rpd::$c['raintpl']['tpl_dir'], $filename))));
                } else return json_encode(array('error' => "You not filled the name or content field or the content does not contain the {\$APPLICATION_CONTENT} variable."));
            }
        } else if ( 'remove' == $args[0] && Rpd::rq($args['filename']) ) {
            $filename = $args['filename'];
            if ( !@unlink(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $filename) )
                Rpd::a('error', "Something went wrong while trying to remove the Layout file (<em>layout.#text#.#text#</em>). Try to remove manually.", array($layout, Rpd::$c['raintpl']['tpl_ext']));
            else {
                @AdministratorModell::removeAllLinkedLayout($filename);
                Rpd::a('success', "The selected Layout was removed.");
            }
            $this->layoutsAction();
        } else $this->layoutsAction();
    }

    /**
     * Attach Layouts to applications.
    */ 
    public function linkLayoutsAction($args = array()) {
        Rpd::a('menu', array('layoutActive' => true, 'linkLayoutsActive' => true));
        
        if ( '' == $args[0] ) {
            // List Layout links
            $scan = Rpd::gA();
            $llArray = array();
            foreach ( $scan as $key => $application ) {
                if ( is_dir('applications' . DIRECTORY_SEPARATOR . $application) ) {
                    if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($application) != 'administrator' ) ) {
                        $linked = AdministratorModell::getLinkedLayout($application);
                        if ( $linked ) $layout = $linked;
                        else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . strtolower($application) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                            $layout = strtolower($application);
                        else $layout = Rpd::$c['rapid']['defaultLayout'];
                        $llArray[] = array(
                                            'from' => $application,
                                            'to' => ( -1 < strpos($layout, 'layout.') ? $layout : 'layout.' . $layout . '.' . Rpd::$c['raintpl']['tpl_ext'] )
                                        );
                    }
                }
            }
            
            $items = 30;
            $start = ( 0 <= intval($args['start']) && count($llArray) > intval($args['start']) ? intval($args['start']) : 0 );
            if ( ($start + $items) < count($llArray) ) Rpd::a('nextStart', ($start + $items));
            foreach ( $llArray as $key => $item ) if ( $start > $key || ($start + $items) <= $key ) unset($llArray[$key]);
            if ( ($start - $items) > -1 ) Rpd::a('prevStart', ($start - $items));
            Rpd::a('page', ($start + $items) / $items);
            
            if ( 0 < count($llArray)) Rpd::a('linkedlayouts', $llArray);
        } else if ( 'add' == $args[0] ) {
                if ( 'save' != $args[1] ) {
                    $layouts = Rpd::gL();
                    if ( 0 < count($layouts) ) Rpd::a('layouts', $layouts);
                    $applicationsArray = Rpd::gA();
                    Rpd::a('applications', $applicationsArray);
                    
                    AdministratorController::$template = 'linklayout.add';
                } else {
                    if ( Rpd::nE($_POST['linklayout']['application']) && Rpd::nE($_POST['linklayout']['layout']) ) {
                        $application = $_POST['linklayout']['application'];
                        $layout = $_POST['linklayout']['layout'];
                        if ( false !== AdministratorModell::addLayoutLink($application, $layout) )
                            Rpd::a('success', "The Layout link is saved.");
                        else Rpd::a('error', "Something went wrong while trying to save the Layout link. Layout link not saved. [1]");
                    } else if ( Rpd::nE($_POST['linklayout']['application']) ) {
                        if ( false !== AdministratorModell::addLayoutLink($_POST['linklayout']['application'], Rpd::$c['rapid']['defaultLayout']) )
                            Rpd::a('success', "The <em>defaultLayout</em> is saved to this Application.");
                        else Rpd::a('error', "Something went wrong while trying to save the Layout link. Layout link not saved. [2]");
                    }
                    
                    $this->linkLayoutsAction();
                }
        } else if ( 'edit' == $args[0] && Rpd::nE($args['application']) ) {
            // Edit linked layout
            $application = $args['application'];
            if ( 'save' != $args[1] ) {
                $layouts = Rpd::gL();
                if ( 0 < count($layouts) ) Rpd::a('layouts', $layouts);
                $applicationsArray = Rpd::gA();
                Rpd::a('applications', $applicationsArray);
                
                $layout = array('application' => $application);
                $linked = AdministratorModell::getLinkedLayout($application);
                if ( false !== $linked ) $layout['layout'] = $linked;
                else if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'layout.' . strtolower($application) . '.' . Rpd::$c['raintpl']['tpl_ext']) )
                    $layout['layout'] = strtolower($application);
                else $layout['layout'] = Rpd::$c['rapid']['defaultLayout'];
                $layout['layout'] = ( -1 < strpos($layout['layout'], 'layout.') ? $layout['layout'] : 'layout.' . $layout['layout'] . '.' . Rpd::$c['raintpl']['tpl_ext'] );
                Rpd::a('layout', $layout);
                
                AdministratorController::$template = 'linklayout.edit';
            } else {
                if ( Rpd::rq($_POST['linklayout']['layout']) ) {
                    $layout = $_POST['linklayout']['layout'];
                    if ( !Rpd::nE($layout) ) {
                        if ( false !== AdministratorModell::removeLinkedLayout($application) )
                            Rpd::a('success', "The selected application's Layout link is removed. Using <em>defaultLayout</em>.");
                        else Rpd::a('error', "Something went wrong while trying to remove the application's Layout link. Layout link did not changed.");
                    } else {
                        if ( false !== AdministratorModell::saveLinkedLayout($application, $layout) )
                            Rpd::a('success', "The selected Layout is linked to the application from now on.");
                        else Rpd::a('error', "Something went wrong while trying to save the application's linked Layout. Layout link did not stored.");
                    }
                } else Rpd::a('error', "Something went wrong while trying to save the new Layout link.");
                $this->linkLayoutsAction();
            }
        } else if ( 'remove' == $args[0] && Rpd::nE($args['application']) ) {
            $application = $args['application'];
            if ( false !== AdministratorModell::removeLinkedLayout($application) )
                Rpd::a('success', "The selected application's Layout link is removed. Using default Layout.");
            else Rpd::a('error', "Something went wrong while trying to remove the application's Layout link. Layout link did not changed.");
            $this->linkLayoutsAction();
        } else $this->linkLayoutsAction();
    }

    /**
     * Add/edit/remove templates.
    */ 
    public function templatesAction($args = array()) {
        Rpd::a('menu', array('templatesActive' => true));
        
        if ( '' == $args[0] ) {
            // list templates
            $templatesArray = array();
            $templateDir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
            $applications = Rpd::gA();
            foreach ( $applications as $application ) {
                if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($application) != 'administrator' ) ) {
                    if ( is_dir($templateDir . $application) ) {
                        $templates = array_diff(scandir($templateDir . $application), array('.', '..'));
                        $templatesArray[$application] = array();
                        foreach ( $templates as $template ) {
                            if ( '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($template, -4) )
                                $templatesArray[$application][] = array(
                                                                    'application' => $application,
                                                                    'writable' => is_writable(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . $template),
                                                                    'filename' => $template,
                                                                    'template' => ( 60 < strlen($template) ? substr($template, 0, 28) . '...' . substr($template, -28) : $template ),
                                                                    'last_modified' => date('Y-m-d H:i:s', filemtime($templateDir . $application . DIRECTORY_SEPARATOR . $template))
                                                                );
                        }
                    }
                }
            }

            $items = 30;
            $start = ( 0 <= intval($args['start']) && count($templatesArray) > intval($args['start']) ? intval($args['start']) : 0 );
            if ( ($start + $items) < count($templatesArray) ) Rpd::a('nextStart', ($start + $items));
            foreach ( $templatesArray as $key => $item ) if ( $start > $key || ($start + $items) <= $key ) unset($templatesArray[$key]);
            if ( ($start - $items) > -1 ) Rpd::a('prevStart', ($start - $items));
            Rpd::a('page', ($start + $items) / $items);

            Rpd::a('templates', $templatesArray);
        } else if ( 'add' == $args[0] ) {
            // create new template
            if ( 'save' != $args[1] ) {
                $applicationsArray = Rpd::gA();
                if ( !Rpd::$c['rapid']['editAdmin'] ) $applicationsArray = array_diff($applicationsArray, array(str_replace('Controller', '', get_class($this))));
                Rpd::a('applications', $applicationsArray);
                
                Rpd::a('tpl_ext', Rpd::$c['raintpl']['tpl_ext']);
                
                AdministratorController::$template = 'template.add';
            } else {
                if ( Rpd::nE($_POST['template']['application']) && Rpd::nE($_POST['template']['filename']) && Rpd::nE($_POST['template']['content']) ) {
                    $application = str_replace(array('/', '\\'), '', $_POST['template']['application']) . DIRECTORY_SEPARATOR;
                    $filename = $_POST['template']['filename'] . '.' . Rpd::$c['raintpl']['tpl_ext'];
                    $content = $_POST['template']['content'];
                    
                    if ( Rpd::rq($_POST['template']['allLanguage']) ) $languages = Rpd::gC();
                    else $languages = array(Rapid::$culture);
                    $created = array();
                    foreach ( $languages as $language )
                        if ( !is_file(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . $filename) )
                            if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . $filename, $content) )
                                $created[] = $language;
                    if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . $filename) )
                        Rpd::a('success', "The Template is created for #text# language.", array(count($created)));
                    else Rpd::a('error', "Something went wrong (probably Permission Denied) while trying to save Template.");
                    
                    $this->templatesAction();
                } else {
                    Rpd::a('error', "You not filled one or more field.");
                    Rpd::a('template', $_POST['template']);
                    $this->templatesAction(array('add'));
                }
            }
        } else if ( 'edit' == $args[0] ) {
            if ( 'save' != $args[1] ) {
                if ( Rpd::rq($args['application']) && Rpd::rq($args['template']) ) {
                    $templateDir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $args['application'] . DIRECTORY_SEPARATOR;
                    $templates = array_diff(scandir($templateDir), array('.', '..'));
                    $otherTemplates = array();
                    foreach ( $templates as $template ) {
                        if ( '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($template, -4) )
                            $otherTemplates[] = array(
                                                                'filename' => $template,
                                                                'last_modified' => date('Y-m-d H:i:s', filemtime($templateDir . $application . DIRECTORY_SEPARATOR . $template))
                                                            );
                    }
                    if ( 1 < count($otherTemplates) ) Rpd::a('otherTemplates', $otherTemplates);
                    $path = $templateDir . $args['template'];
                    if ( is_file($path) ) {
                        $application = $args['application'];
                        $filename = $args['template'];
                        $content = htmlentities(@file_get_contents($path), ENT_QUOTES, "UTF-8");
                        Rpd::a('template', array(
                                                            'application' => $application,
                                                            'filename' => $filename,
                                                            'content' => $content,
                                                            'writable' => is_writable($path),
                                                            'last_modified' => date('Y-m-d H:i:s', filemtime($path))
                                                        ));
                        
                        $applicationsArray = Rpd::gA();
                        Rpd::a('applications', $applicationsArray);
                        Rpd::a('tpl_ext', Rpd::$c['raintpl']['tpl_ext']);
                        
                        AdministratorController::$template = 'template.edit';
                    } else {
                        Rpd::a('error', "Something went wrong while trying to load the Template. Template not found in <em>#text#</em>.", array($path));
                        $this->templatesAction();
                    }
                } else {
                    Rpd::a('error', "Something went wrong while trying to identificate the Template.");
                    $this->templatesAction();
                }
            } else {
                // AJaX call
                if ( Rpd::nE($_POST['template']['application']) && Rpd::nE($_POST['template']['filename']) && Rpd::nE($_POST['template']['content']) ) {
                    $application = str_replace(array('/', '\\'), '', $_POST['template']['application']) . DIRECTORY_SEPARATOR;
                    $filename = $_POST['template']['filename'];
                    $path = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . $filename;
                    $content = $_POST['template']['content'];
                    if ( is_file($path) ) {
                        if ( @file_put_contents($path, $content) ) {
                            return json_encode(array('success' => "The Template is saved."));
                        } else return json_encode(array('error' => "Something went wrong (probably Permission Denied) while trying to save Template."));
                    } else return json_encode(array('error' => "Something went wrong while trying to save Template, the Template not found in <em>" . $path . "</em>."));
                } else return json_encode(array('error' => "You not filled one or more field."));
            }
        } else if ( 'remove' == $args[0] && Rpd::rq($args['application']) && Rpd::rq($args['template']) ) {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $args['application'] . DIRECTORY_SEPARATOR . $args['template']) ) {
                if ( @unlink(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $args['application'] . DIRECTORY_SEPARATOR . $args['template']) )
                    Rpd::a('success', "The Template is removed.");
                else Rpd::a('error', "Something went wrong while trying to remove Template. Template not removed.");
            } else Rpd::a('error', "Something went wrong while trying to remove Template. The Template is not exists in <em>#text#</em>.", array(Rpd::$c['raintpl']['tpl_dir'] . $args['application'] . DIRECTORY_SEPARATOR . $args['template']));
            
            $this->templatesAction();
        } else $this->templatesAction();
    }

    /**
     * Edit mail templates
    */
    public function mailsAction($args = array()) {
        Rpd::a('menu', array('mailsActive' => true));

        if ( '' == $args[0] ) {
            // List
            if ( !@is_dir(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir']) )
                @mkdir(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir']);
            $mails = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir']), array('.', '..'));
            $mailsArray = array();
            foreach ( $mails as $mail )
                $mailsArray[] = array(
                                        'template' => $mail,
                                        'variables' => substr_count(@file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $mail), '{$'),
                                        'writable' => @is_writable(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $mail),
                                        'last_modified' => date('Y-m-d H:i:s', filemtime(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $mail))
                                    );
            
            $items = 30;
            $start = ( 0 <= intval($args['start']) && count($mailsArray) > intval($args['start']) ? intval($args['start']) : 0 );
            if ( ($start + $items) < count($mailsArray) ) Rpd::a('nextStart', ($start + $items));
            foreach ( $mailsArray as $key => $item ) if ( $start > $key || ($start + $items) <= $key ) unset($mailsArray[$key]);
            if ( ($start - $items) > -1 ) Rpd::a('prevStart', ($start - $items));
            Rpd::a('page', ($start + $items) / $items);
            
            if ( 0 < count($mailsArray) ) Rpd::a('mails', $mailsArray);
        } else if ( 'add' == $args[0] ) {
            // Add
            Rpd::a('tpl_ext', Rpd::$c['raintpl']['tpl_ext']);
            if ( 'save' != $args[1] ) AdministratorController::$template = 'mail.add';
            else {
                if ( Rpd::nE($_POST['mail']['filename']) && Rpd::nE($_POST['mail']['content']) ) {
                    $filename = $_POST['mail']['filename'] . '.' . Rpd::$c['raintpl']['tpl_ext'];
                    $content = $_POST['mail']['content'];
                    
                    if ( Rpd::rq($_POST['mail']['allLanguage']) ) $languages = Rpd::gC();
                    else $languages = array(Rapid::$culture);
                    $created = array();
                    foreach ( $languages as $language )
                        if ( !is_file(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $filename) )
                            if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $filename, $content) )
                                $created[] = $language;
                    if ( 0 < count($created) )
                        Rpd::a('success', "The Mail Template is created for #text# language.", array(count($created)));
                    else Rpd::a('error', "Something went wrong (probably Permission Denied) while trying to save Mail Template.");
                    
                    $this->mailsAction();
                } else {
                    Rpd::a('error', "Something went wrong while trying to create Mail Template. All fields have to filled.");
                    Rpd::a('mailTemplate', $_POST['mail']);
                    AdministratorController::$template = 'mail.add';
                }
            }
        } else if ( 'edit' == $args[0] ) {
            // Edit
            if ( 'save' != $args[1] ) {
                if ( Rpd::nE($args['mail']) ) {
                    $filename = $args['mail'];
                    $mailsDir = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . 'mails';
                    $mails = array_diff(scandir($mailsDir), array('.', '..'));
                    $otherMails = array();
                    foreach ( $mails as $mail ) {
                        if ( '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($mail, -4) )
                            $otherMails[] = array(
                                                                'filename' => $mail,
                                                                'last_modified' => date('Y-m-d H:i:s', filemtime($mailsDir . DIRECTORY_SEPARATOR . $template))
                                                            );
                    }
                    if ( 1 < count($otherMails) ) Rpd::a('otherMails', $otherMails);
                    $path = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $filename;
                    if ( $content = @file_get_contents($path) ) {
                        Rpd::a('mail', array(
                                                'filename' => $filename,
                                                'content' => $content,
                                                'last_modified' => date('Y-m-d H:i:s', filemtime($path)),
                                                'writable' => is_writable($path)
                                            )
                        );
                    AdministratorController::$template = 'mail.edit';
                    } else {
                        Rpd::a('error', "Something went wrong while trying to load content of Mail Template. Probably permission denied.");
                        $this->mailsAction();
                    }
                } else {
                    Rpd::a('error', "Something went wrong while trying to load Mail Template. Mail Template not found.");
                    $this->mailsAction();
                }
            } else {
                // AJaX call
                if ( Rpd::nE($_POST['mail']['filename']) && Rpd::nE($_POST['mail']['content']) ) {
                    $filename = $_POST['mail']['filename'];
                    $path = Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $filename;
                    $content = $_POST['mail']['content'];
                    if ( is_file($path) ) {
                        if ( @file_put_contents($path, $content) ) {
                            return json_encode(array('success' => "The Mail Template is saved."));
                        } else return json_encode(array('error' => "Something went wrong (probably Permission Denied) while trying to save Mail Template."));
                    } else return json_encode(array('error' => Rpd::t("Something went wrong while trying to save Mail Template, the Mail Template not found in <em>#text#</em>.", array($path))));
                } else return json_encode(array('error' => "You not filled one or more field."));
            }
        } else if ( 'remove' == $args[0] && Rpd::rq($args['mail']) ) {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $args['mail']) ) {
                if ( @unlink(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $args['mail']) )
                    Rpd::a('success', "The Mail Template is removed.");
                else Rpd::a('error', "Something went wrong while trying to remove Mail Template. Mail Template not removed.");
            } else Rpd::a('error', "Something went wrong while trying to remove Mail Template. The Mail Template is not exists in <em>#text#</em>.", array(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir'] . $args['mail']));
            
            $this->mailsAction();
        } else $this->mailsAction();
    }

    /**
     * Create, update and remove Beans (Database editor).
    */ 
    public function beansAction($args = array()) {
        if ( 'new' == $args[0] ) {
            // New Bean
            Rpd::a('menu', array('newBeanActive' => true));
            
            if ( 'save' != $args[1] ) AdministratorController::$template = 'bean.new';
            else {
                if ( Rpd::rq($_POST['bean']['name']) && Rpd::rq($_POST['bean']['field']) && is_array($_POST['bean']['field']) ) {
                    $name = $new_string = preg_replace("/[^A-Za-z0-9?!]/", '', $_POST['bean']['name']);;
                    $fields = array();
                    foreach ( $_POST['bean']['field'] as $field ) 
                        if ( Rpd::nE($field) ) $fields[] = $new_string = preg_replace("/[^A-Za-z0-9?!]/", '', $field);;
                    $fields = array_unique($fields);
                    if ( Rpd::nE($name) && Rpd::nE($fields[0]) ) {
                        if ( AdministratorModell::createBean($name, $fields) ) {
                            Rpd::a('success', "The Bean <em>#text#</em> is created with <em>#text#</em> fields.", array($name, count($fields)));
                            $this->beansAction(array('bean' => $name));
                        } else {
                            Rpd::a('error', "Something went wrong while trying to create Bean. Bean already exists.");
                            Rpd::a('beanName', $name);
                            AdministratorController::$template = 'bean.new';
                        }
                    } else {
                        Rpd::a('error', "Something went wrong while trying to create Bean. All fields have to filled.");
                        Rpd::a('beanName', $name);
                        AdministratorController::$template = 'bean.new';
                    }
                } else {
                    Rpd::a('error', "Something went wrong while trying to create Bean.");
                    AdministratorController::$template = 'bean.new';
                }
            }
        } else if ( Rpd::rq($args['bean']) && '' != $args['bean'] ) {
            $bean = $args['bean'];
            Rpd::a('menu', array('beanActive' => array($bean => true)));
            Rpd::a('beanName', $bean);
            if ( !Rpd::rq($args[0]) || '' == $args[0] ) {
                // List
                $inspect = AdministratorModell::inspectBean($bean);
                if ( 0 < count($inspect) ) Rpd::a('bean', $inspect);
                else Rpd::a('error', "Something went wrong while trying to load this Bean (#text#).", array($bean));
                
                $items = 30;
                $start = ( 0 < intval($args['start']) && AdministratorModell::countBean($bean) > intval($args['start']) ? intval($args['start']) : 0 );
                if ( ($start + $items) < AdministratorModell::countBean($bean) ) Rpd::a('nextStart', ($start + $items));
                if ( ($start - $items) > -1 ) Rpd::a('prevStart', ($start - $items));
                Rpd::a('page', ($start + $items) / $items);
                $beans = AdministratorModell::findBeans($bean, $start, $items);
                $beansArray = array();
                foreach ( $beans as $c_bean ) {
                    $cBeans = $c_bean->getProperties();
                    foreach ( $cBeans as $key => $item ) $cBeans[$key] = htmlentities($item, ENT_QUOTES, "UTF-8");
                    $beansArray[] = $cBeans;
                }

                if ( 0 < count($beansArray) ) Rpd::a('beans', $beansArray);
            } else if ( Rpd::rq($args[0]) && 'add' == $args[0] ) {
                // Add
                if ( 'save' != $args[1] ) {
                    $columns = AdministratorModell::inspectBean($bean);
                    Rpd::a('columns', $columns);
                    if ( false !== AdministratorModell::getBeanSample($bean) )
                        Rpd::a('sample', AdministratorModell::getBeanSample($bean)->getProperties());
    
                    AdministratorController::$template = 'bean.add';
                } else {
                    if ( Rpd::rq($_POST['bean']) && 0 < count($_POST['bean']) ) {
                        if ( false !== AdministratorModell::saveBean($bean, $_POST['bean']) ) {
                            Rpd::a('success', "The new Bean is saved.");
                            $this->beansAction(array('bean' => $bean));
                        } else {
                            Rpd::a('values', $_POST['bean']);
                            Rpd::a('error', "Something went wrong while trying to save the Bean. Bean did not saved.");
                            $this->beansAction(array('bean' => $bean, 0 => "add"));
                        }
                    } else {
                        Rpd::a('error', "Something went wrong while saving the Bean. Try again, Bean did not saved.");
                        $this->beansAction(array('bean' => $bean, 0 => "add"));
                    }
                }
            } else if ( Rpd::rq($args[0]) && 'edit' == $args[0] ) {
                // Edit
                if ( Rpd::rq($args['id']) && 0 < intval($args['id']) ) {
                    $id = intval($args['id']);
                    Rpd::a('beanID', $id);
                    if ( 'save' != $args[1] ) {
                        if ( false !== AdministratorModell::getBean($bean, $id) ) {
                            $bean = AdministratorModell::getBean($bean, $id);
                            foreach ( $bean as &$item ) $item = htmlentities($item, ENT_QUOTES, "UTF-8");
                            Rpd::a('bean', $bean);
                            AdministratorController::$template = 'bean.edit';
                        } else {
                            Rpd::a('error', "Something went wrong while tryingto load this Bean. Bean not found.");
                            $this->beansAction(array('bean' => $bean));
                        }
                    } else {
                        if ( Rpd::rq($_POST['bean']) && 0 < count($_POST['bean']) ) {
                            if ( false !== AdministratorModell::saveBean($bean, $_POST['bean']) ) {
                                Rpd::a('success', "The modification(s) of the Bean is saved.");
                                $this->beansAction(array('bean' => $bean));
                            } else {
                                Rpd::a('bean', $_POST['bean']);
                                Rpd::a('error', "Something went wrong while trying to save the Bean. Bean did not saved.");
                                $this->beansAction(array('bean' => $bean, 0 => "edit", 'id' => $id));
                            }
                        } else {
                            Rpd::a('error', "Something went wrong while saving the Bean. Try again, Bean did not saved.");
                            $this->beansAction(array('bean' => $bean));
                        }
                    }
                } else {
                    Rpd::a('error', "Something went wrong while trying to load the Bean for edit. Can not identify it.");
                    $this->beansAction(array('bean' => $bean));
                }
            } else if ( Rpd::rq($args[0]) && 'remove' == $args[0] ) {
                // Remove
                if ( Rpd::rq($args['id']) && 0 < intval($args['id']) ) {
                    if ( false !== AdministratorModell::removeBean($bean, intval($args['id'])) )
                        Rpd::a('success', "The Bean is removed.");
                    else Rpd::a('error', "Something went wrong while trying to remove this Bean. Can not remove it.");
                } else Rpd::a('error', "Something went wrong while trying to remove this Bean. Can not identify it.");
                $this->beansAction(array('bean' => $bean));
            } else if ( Rpd::rq($args[0]) && 'remove-all' == $args[0] ) {
                // Remove all
                if ( false!== AdministratorModell::removeAllBean($bean) )
                    Rpd::a('success', "The Bean (" . $bean . ") is removed with every existing Beans.");
                else Rpd::a('error', "Something went wrong while trying to remove this Bean (" . $bean . "). No Beans removed.");
            } else Rpd::a('error', "Something went wrong while working with Beans. Nothing changed.");
        } else if ( 'execute' == $args[0] ) {
            Rpd::a('menu', array('executeActive' => true));
            self::$template = 'bean.execute';

            if ( Rpd::nE($_POST['exec']['sql']) ) {
                // Execute
                $sql = $_POST['exec']['sql'];
                $isSelect = false;
                $runnable = false;
                switch ( strtoupper(substr($sql, 0, 6)) ) {
                    case 'SELECT':      $runnable = true; $isSelect = true; break;
                    case 'INSERT':      $runnable = true; $isSelect = false; break;
                    case 'UPDATE':      $runnable = true; $isSelect = false; break;
                    case 'DELETE':      $runnable = true; $isSelect = false; break;
                }
                if ( $runnable ) {
                    if ( $isSelect ) {
                        $result = AdministratorModell::getAllSQL(str_replace(';', '', $sql) . ' LIMIT 100 OFFSET 0;');
                        $result2 = AdministratorModell::getAllSQL($sql);
                        $tableMatches = array();
                        preg_match('/\bfrom\b\s*(\w+)/i', $sql, $tableMatches);
                        if ( isset($tableMatches[1]) && !empty($tableMatches[1]) ) Rpd::a('bean', $tableMatches[1]);
                        if ( is_array($result) && 0 < count($result) ) {
                            foreach ( $result as &$p ) foreach ( $p as $col => &$val ) $val = htmlentities($val);
                            Rpd::a('result', $result);
                            Rpd::a('sql', str_replace(';', '', $sql) . ' LIMIT 100 OFFSET 0;');
                            Rpd::a('success', "The command execution was successful.");
                        } else if ( is_array($result2) && 0 < count($result2) ) {
                            foreach ( $result2 as &$p ) foreach ( $p as $col => &$val ) $val = htmlentities($val);
                            Rpd::a('result', $result2);
                            Rpd::a('sql', $sql);
                            Rpd::a('success', "The command execution was successful.");
                        } else Rpd::a('error', "The result of the command was empty.");
                    } else {
                        $result = AdministratorModell::executeSQL($sql);
                        if ( false !== $result ) Rpd::a('success', "The execution was successful, number of affected rows: <em>#text#</em>.", array($result));
                        else Rpd::a('error', "The execution was unsuccessful.");
                    }
                } else Rpd::a('error', "This command is not runnable, allowed commands: SELECT, INSERT, UPDATE, DELETE.");
            }
        } else Rpd::a('error', "Something went wrong while trying to load this Bean.");
    }
    
    /**
     * Edit the Applications' sources.
    */ 
    public function sourcesAction($args = array()) {
        Rpd::a('menu', array('sourcesActive' => true, 'appSourcesActive' => true));
        
        if ( '' == $args[0] ) {
            // List
            $applicationsArray = Rpd::gA();
            $sources = array();
            foreach ( $applicationsArray as $application ) {
                if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($application) != 'administrator' ) ) {
                    if ( is_file('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']) )
                        $last_modified = date('Y-m-d H:i:s', filemtime('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']));
                    else $last_modified = "-";
                    $writable = is_writable('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']);
                    $sources[] = array('name' => $application, 'writable' => $writable, 'last_modified' => $last_modified);
                }
            }
            Rpd::a('sources', $sources);
        } else if ( 'edit' == $args[0] && Rpd::rq($args['application']) ) {
            // Edit
            $application = $args['application'];
            $sourcePath = 'applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile'];
            if ( 'save' != $args[1] ) {
                Rpd::a('application', $application);
                if ( @file_get_contents($sourcePath) )
                    $sourceContent = json_decode(file_get_contents($sourcePath), true);
                else $sourceContent = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
                if ( @is_file($sourcePath) ) Rpd::a('last_modified', date('Y-m-d H:i:s', filemtime($sourcePath)));
                Rpd::a('writable', is_writable($sourcePath));
                Rpd::a('sourceContent', $sourceContent);                
                AdministratorController::$template = 'source.edit';                
            } else if ( 'save' == $args[1] && Rpd::nE($_POST['source']) ) {
                $content = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
                if ( Rpd::rq($_POST['source']) ) {
                    if ( Rpd::rq($_POST['source']['javascripts']) )
                        foreach ( $_POST['source']['javascripts'] as $source )
                            if ( !empty($source) ) $content['javascripts'][] = $source;                                            
                    if ( Rpd::rq($_POST['source']['stylesheets']) )
                        foreach ( $_POST['source']['stylesheets'] as $source )
                            if ( !empty($source) ) $content['stylesheets'][] = $source;
                    if ( Rpd::rq($_POST['source']['less']) )
                        foreach ( $_POST['source']['less'] as $source )
                            if ( !empty($source) ) $content['less'][] = $source;                                                                    
                }
                if ( @file_put_contents($sourcePath, json_encode($content)) )
                    Rpd::a('success', "The Sources are saved.");
                else Rpd::a('error', "Something went wrong whily trying to save Sources. Sources does not changed.");
                $this->sourcesAction();
            } else $this->sourcesAction();
        } else if ( 'clear' == $args[0] && Rpd::rq($args['application']) ) {
            $application = $args['application'];
            if ( @file_put_contents('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile'], "{\"javascripts\":[],\"stylesheets\":[],\"less\":[]}") )
                Rpd::a('success', "The Source file is cleared.");
            else Rpd::a('error', "Something went wrong while trying to clear the Source file. Source file does not cleared.");
            $this->sourcesAction();
        } else $this->sourcesAction();
    }

    /**
     * Manage Library
    */ 
    private function human_filesize($bytes, $decimals = 2) {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
    public function libraryAction($args = array(), $loadpath = false) {
        Rpd::a('menu', array('libraryActive' => true));

        if ( !Rpd::rq($args[0]) || '' == $args[0] ) {
            Rpd::a('filesDir', Rpd::$c['rapid']['filesDir']);
            $return = array();
            $path = ( Rpd::nE($_POST['path']) && is_dir(str_replace('../', '', $_POST['path'])) ? str_replace('../', '', $_POST['path']) : str_replace(DIRECTORY_SEPARATOR, '', Rpd::$c['rapid']['filesDir']) );
            if ( false !== $loadpath ) $path = $loadpath;
            $pathArray = array();
            foreach ( explode('/', $path) as $dir ) $pathArray[] = $dir;
            if ( empty($pathArray[count($pathArray)-1]) ) unset($pathArray[count($pathArray)-1]);
            if ( 1 < count($pathArray) ) $return['path'] = $pathArray;
            $return['current'] = end($pathArray);
            $scan = array_diff(@scandir($path), array('.', '..'));
            $queue = array('directories' => array(), 'files' => array());
            foreach ( $scan as $item ) {
                if ( !is_dir($path . DIRECTORY_SEPARATOR . $item) ) {
                    $absPath = getcwd() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $item;
                    $filesize = $this->human_filesize(filesize($absPath), 2);
                    $queue['files'][] = array_merge(
                                                    pathinfo($absPath),
                                                    array('filesize' => $filesize),
                                                    array('path' => $path . DIRECTORY_SEPARATOR . $item)
                                                );
                } else {
                    $absPath = getcwd() . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $item;
                    $queue['directories'][] = array_merge(
                                                    pathinfo($absPath),
                                                    array('last_modified' => date("Y-m-d H:i:s", filemtime($absPath))),
                                                    array('path' => $path . DIRECTORY_SEPARATOR . $item)
                                                );
                }
            }
            if ( 0 < count($queue['directories']) || 0 < count($queue['files']) ) $return['tree'] = $queue;
            
            if ( Rpd::nE($_POST['dir-list']) ) return json_encode($return);
            else Rpd::a('library', $return);
        } else if ( 'upload' == $args[0] ) {
            $path = "";
            if ( Rpd::rq($_POST['library']['path']) ) $path = str_replace('../', '', $_POST['library']['path']);
            while ( '/' == substr($path, -1) ) $path = substr($path, 0, -1);
            if ( @is_dir(Rpd::$c['rapid']['filesDir'] . $path) ) {
                if ( Rpd::rq($_FILES) && Rpd::nE($_FILES['library']['name'][0]) ) {
                    $uploaded = 0;
                    foreach ( $_FILES['library']['tmp_name'] as $key => $tmp_name ) {
                        if ( @move_uploaded_file($tmp_name, Rpd::$c['rapid']['filesDir'] . $path . DIRECTORY_SEPARATOR . basename($_FILES['library']['name'][$key])) )
                            $uploaded++;
                    }
                    if ( 0 < $uploaded )
                        Rpd::a('success', "The selected #text# file(s) uploaded to <em>#text#</em>.", array($uploaded, Rpd::$c['rapid']['filesDir'] . $path));
                    else Rpd::a('error', "Something went wrong (probably permission denied) while trying to upload the selected file(s). No file(s) uploaded.");
                } else Rpd::a('error', "Something went wrong while trying to upload file. No file(s) selected.");
            } else Rpd::a('error', "Something went wrong while trying to upload file. The selected path is wrong.");
            $this->libraryAction(array(), Rpd::$c['rapid']['filesDir'] . $path);
        } else if ( 'mkdir' == $args[0] ) {
            $path = "";
            if ( Rpd::rq($_POST['lib-mkdir']['path']) ) $path = str_replace('../', '', $_POST['lib-mkdir']['path']);
            while ( '/' == substr($path, -1) ) $path = substr($path, 0, -1);
            if ( Rpd::rq($_POST['lib-mkdir']['name']) && !empty($_POST['lib-mkdir']['name']) ) {
                $dirname = $_POST['lib-mkdir']['name'];
                $success = false;
                if ( @is_dir(Rpd::$c['rapid']['filesDir'] . $path) ) {
                    if ( @mkdir(Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $dirname) ) {
                        Rpd::a('success', "The new directory (<em>#text#</em>) is created.", array(Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $dirname));
                        Rpd::a('path', Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $dirname);
                        $this->libraryAction(array(), Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $dirname);
                        $success = true;
                    } else Rpd::a('error', "Something went wrong while trying to create directory. Permission denied.");
                } else Rpd::a('error', "Something went wrong while trying to create directory. Wrong path.");
            } else Rpd::a('error', "Something went wrong while trying to create directory. Wrong name.");
            if ( false === $success ) $this->libraryAction();
        } else if ( 'mkfile' == $args[0] ) {
            $path = "";
            $success = false;
            if ( Rpd::rq($_POST['lib-mkfile']['path']) ) $path = str_replace('../', '', $_POST['lib-mkfile']['path']);
            while ( '/' == substr($path, -1) ) $path = substr($path, 0, -1);
            if ( Rpd::rq($_POST['lib-mkfile']['name']) && !empty($_POST['lib-mkfile']['name']) ) {
                $filename = $_POST['lib-mkfile']['name'];
                if ( !@is_file(Rpd::$c['rapid']['filesDir'] . $path . DIRECTORY_SEPARATOR . $filename ) ) {
                    if ( false !== file_put_contents(Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $filename, 'This is an automatic generated file.') ) {
                        Rpd::a('success', "The new file (<em>#text#</em>) is created.", array(Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ) . $filename));
                        Rpd::a('path', Rpd::$c['rapid']['filesDir'] . $path);
                        $this->libraryAction(array(), Rpd::$c['rapid']['filesDir'] . ( empty($path) ? '' : $path . DIRECTORY_SEPARATOR ));
                        $success = true;
                    } else Rpd::a('error', "Something went wrong while trying to create file. Permission denied.");
                } else Rpd::a('error', "Something went wrong while trying to create file. This file is already exists.");
            } else Rpd::a('error', "Something went wrong while trying to create directory. Wrong name.");
            if ( false === $success ) $this->libraryAction();
        } else if ( 'rmdir' == $args[0] ) {
            $path = $dirPath = Rpd::$c['rapid']['filesDir'];
            for ( $x = 1; $x < count($args); $x++ ) {
                if ( str_replace(DIRECTORY_SEPARATOR, '', Rpd::$c['rapid']['filesDir']) != $args[$x] ) {
                    $path .= $args[$x] . DIRECTORY_SEPARATOR;
                    if ( $x+1 < count($args) ) $dirPath .= $args[$x] . DIRECTORY_SEPARATOR;
                }
            }
            $path = substr(str_replace('../', '', $path), 0, -1);
            $success = false;
            if ( @is_dir($path) ) {
                if ( 0 < array_diff(scandir($path), array('.', '..')) )
                    if ( Rpd::dT($path) ) {
                        Rpd::a('success', "The selected directory (<em>#text#</em>) is removed recursively.", array($args[$x-1]));
                        $success = true;
                        $this->libraryAction(array(), $dirPath);
                    } else Rpd::a('error', "Something went wrong while trying to remove directory. Recursive remove did not work.");
                else 
                    if ( @rmdir($path) ) {
                        Rpd::a('success', "The selected directory (<em>#text#</em>) is removed.", array($args[$x-1]));
                        $success = true;
                        $this->libraryAction(array(), $dirPath);
                    } else Rpd::a('error', "Something went wrong while trying to remove directory. Permission denied or non-empty directory.");
            } else Rpd::a('error', "Something went wrong while trying to remove directory. Wrong path.");
            if ( false === $success ) $this->libraryAction();
        } else if ( 'rmfile' == $args[0] ) {
            $path = Rpd::$c['rapid']['filesDir'];
            $success = false;
            $dirPath = "";
            if ( str_replace(DIRECTORY_SEPARATOR, '', Rpd::$c['rapid']['filesDir']) == $args[1] ) $args[1] = "";
            for ( $x = 1; $x < count($args); $x++ ) {
                if ( !empty($args[$x]) ) $path .= $args[$x] . ( $x+1 == count($args) ? '' : DIRECTORY_SEPARATOR );
                if ( !empty($args[$x]) && $x+1 < count($args) ) $dirPath .= $args[$x] . DIRECTORY_SEPARATOR;
            }
            if ( @is_file($path) ) {
                if ( @unlink($path) ) {
                    Rpd::a('success', "The selected file (<em>#text#</em>) is removed.", array($path));
                    $this->libraryAction(array(), Rpd::$c['rapid']['filesDir'] . $dirPath);
                    $success = true;
                } else Rpd::a('error', "Something went wrong while trying to remove file (<em>#text#</em>). Permission denied.", array($args[$x-1]));
            } else Rpd::a('error', "Something went wrong while trying to remove file (<em>#text#</em>). Wrong path.", array($args[$x-1]));
            if ( false === $success ) $this->libraryAction();
        } else if ( 'view' == $args[0] ) {
            unset($args[0]);
            $path = join(DIRECTORY_SEPARATOR, $args);
            if ( is_file(str_replace('../', '', $path)) ) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                if ( in_array($ext, Rpd::$c['rapid']['libEditables']) ) {
                    $filename = $args[count($args)];
                    $content = htmlentities(@file_get_contents($path), ENT_QUOTES, "UTF-8");
                    Rpd::a('file', array(
                                                'path' => $path,
                                                'filename' => $filename,
                                                'content' => $content,
                                                'writable' => is_writable($path),
                                                'last_modified' => date('Y-m-d H:i:s', filemtime($path))
                                            ));
                } else header("Location: /" . $path);
            } else Rpd::a('error', "Something went wrong while trying to locate file. File not found.");
            AdministratorController::$template = 'library.edit';
        } else if ( 'file-save' == $args[0] ) {
            // AJaX call
            if ( Rpd::rq($_POST['file']['path']) && Rpd::rq($_POST['file']['content']) ) {
                $path = $_POST['file']['path'];
                $content = $_POST['file']['content'];
                if ( is_file($path) && is_writable($path) ) {
                    if ( false !== file_put_contents($path, $content) )
                        return json_encode(array('success' => "The file is saved."));
                    else return json_encode(array('error' => "Something went wrong while trying to save this File."));
                } else return json_encode(array('error' => "Something went wrong while trying to open this File. File not found or non-writable."));
            } else return json_encode(array('error' => "Something went wrong while trying to save this File. File not edited."));
        } else if ( 'use' == $args[0] ) {
            if ( 'save' != $args[1] ) {
                unset($args[0]);
                $path = join(DIRECTORY_SEPARATOR, $args);
                return json_encode(array('path' => $path, 'applications' => Rpd::gA(), 'defaultApp' => Rpd::$c['rapid']['defaultApplication']));
            } else {
                if ( Rpd::nE($_POST['usefile']['path']) && Rpd::nE($_POST['usefile']['application']) ) {
                    $path = $_POST['usefile']['path'];
                    $info = pathinfo($path);
                    $app = $_POST['usefile']['application'];
                    if ( is_file($path) && in_array($info['extension'] , array('js', 'css', 'less')) ) {
                        $sourcepath = 'applications' . DIRECTORY_SEPARATOR . $app . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile'];
                        $sources = json_decode(@file_get_contents($sourcepath), true);
                        $path = DIRECTORY_SEPARATOR . $path;
                        switch ($info['extension']) {
                            case 'js':  
                                if ( false === in_array($path, $sources['javascripts']) ) $sources['javascripts'][] = $path;
                                else $alreadyUsed = true;
                                break;
                            case 'css':
                                if ( false === in_array($path, $sources['stylesheets']) ) $sources['stylesheets'][] = $path;
                                else $alreadyUsed = true;
                                break;
                            case 'less':
                                if ( false === in_array($path, $sources['less']) ) $sources['less'][] = $path;
                                else $alreadyUsed = true;
                                break;
                        }
                        if ( !isset($alreadyUsed) ) {
                            if ( @file_put_contents($sourcepath, json_encode($sources)) )
                                Rpd::a('success', "The selected file is used for <strong>#text#</strong> application from now on.", array($app));
                            else Rpd::a('error', "Something went wrong while trying to use file. Can not save to sources.");
                        } else Rpd::a('error', "Something went wrong while trying to use file. This file is already in use.");
                    } else Rpd::a('error', "Something went wrong while trying to use file. File not found or wrong extension: <strong>#text#</strong>.", array($info['extension']));
                } else Rpd::a('error', "Something went wrong while trying to use file. Try again.");
                $this->libraryAction();
            }
        } else $this->libraryAction();
    }

    /**
     * Add/edit/delete the routing rules.
    */ 
    public function routesAction($args = array()) {
        Rpd::a('menu', array('routesActive' => true));
        
        $url = $_SERVER['HTTP_HOST'] . ( Rpd::nE($_SERVER['PHP_SELF']) ? str_replace('/index.php', '', $_SERVER['PHP_SELF']) : '' );
        Rpd::a('url', $url);
        if ( !Rpd::nE($args[0]) ) {
            // List
            $routes = AdministratorModell::getRoutes();
            $routesArray = array();
            if ( 0 < count($routes) )
                foreach ( $routes as $route ) {
                    $cRoute = $route->getProperties();
                    $cRoute['user'] = AdministratorModell::getUsername($cRoute['uid']);
                    $routesArray[] = $cRoute;
                }
            Rpd::a('routes', $routesArray);
        } else if ( 'add' == $args[0] ) {
            if ( 'save' != $args[1] ) {
                Rpd::a('applications', Rpd::gA());
                AdministratorController::$template = 'route.add';
            } else {
                if ( Rpd::nE($_POST['route']['from'][0]) && Rpd::nE($_POST['route']['to'][0]) ) {
                    $from = join('/', $_POST['route']['from']);
                    $to = join('/', $_POST['route']['to']);
                    
                    if ( AdministratorModell::saveRoute($from, $to, $_SESSION['user']['id']) )
                        Rpd::a('success', "The new Route is saved.");
                    else Rpd::a('error', "Something went wrong while trying to save the new Route. Nothing saved.");
                    
                    $this->routesAction();
                } else {
                    Rpd::a('error', "Something went wrong while trying to save Route. Please fill the <em>From</em> and/or <em>To</em> field.");
                    $this->routesAction(array('add'));
                }
            }
        } else if ( 'remove' == $args[0] ) {
            if ( Rpd::nE(intval($args['id'])) ) {
                $id = intval($args['id']);
                
                if ( AdministratorModell::removeRoute($id) )
                    Rpd::a('success', "The Route is removed.");
                else Rpd::a('error', "Something went wrong while trying to remove Route. Route not removed.");
                $this->routesAction();
            } else $this->routesAction();
        } else $this->routeAction();
    }

    /**
     * Manage Applications
    */ 
    public function applicationsAction($args = array()) {
        Rpd::a('menu', array('applicationsActive' => true));

        if ( !Rpd::nE($args[0]) ) {
            // List
            $applicationArray = array();
            foreach ( Rpd::gA() as $application ) {
                if ( Rpd::$c['rapid']['editAdmin'] || ( !Rpd::$c['rapid']['editAdmin'] && strtolower($application) != 'administrator' ) ) {
                    $applicationArray[$application] = array('languages' => "", 'sources' => 0, 'default' => false);
                    foreach ( Rpd::gC() as $language )
                        if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application) )
                            $applicationArray[$application]['languages'] .= $language . ', ';
                    if ( ', ' == substr($applicationArray[$application]['languages'], -2) )
                        $applicationArray[$application]['languages'] = substr($applicationArray[$application]['languages'], 0, -2);
                    if ( @is_file('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']) ) {
                        $sources = @json_decode(@file_get_contents('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile']));
                        foreach ( $sources as $source ) $applicationArray[$application]['sources'] += count($source);
                    }
                    if ( Rpd::$c['rapid']['defaultApplication'] == $application ) $applicationArray[$application]['default'] = true;
                }
            }
            
            Rpd::a('applications', $applicationArray);
        } else if ( 'add' == $args[0] ) {
            // Add
            if ( 'save' != $args[1] ) {
                Rpd::a('languages', Rpd::gC());
                AdministratorController::$template = 'application.add';
            } else {
                if ( Rpd::nE($_POST['application']['name']) && !in_array($_POST['application']['name'], Rpd::gA()) ) {
                    $name = $_POST['application']['name'];
                    $languages = ( 0 < count($_POST['application']['languages']) ? $_POST['application']['languages'] : array(Rpd::$c['rapid']['culture']) );
                    if ( @mkdir('applications' . DIRECTORY_SEPARATOR . $name) ) {
                        @file_put_contents('applications' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . 'Controller.class.php', "<?php\n\nclass " . $name . "Controller {\n\n\tpublic static \$template;\n\n\tpublic function indexAction(\$args = array()) {\n\t\t\n\t}\n\n}");
                        @file_put_contents('applications' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . 'Modell.class.php', "<?php\n\nclass " . $name . "Modell {\n\n\t\n\n}");
                        @file_put_contents('applications' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['sourcesFile'], "{\"javascripts\":[],\"stylesheets\":[],\"less\":[]}");
                    } else Rpd::a('error', "Something went wrong while trying to create Application. Permission denied on applications directory.");
                    foreach ( $languages as $language ) {
                        $data = array();
                        if ( @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name) ) {
                            @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['defaultAction'] . '.' . Rpd::$c['raintpl']['tpl_ext'], "This is an automatic generated content.");
                            if ( Rpd::nE($_POST['application']['title']) ) $data['title'] = $_POST['application']['title'];
                            if ( Rpd::nE($_POST['application']['keywords']) ) {
                                $keywords = $_POST['application']['keywords'];
                                $keywordsArray = explode(',', $keywords);
                                foreach ( $keywordsArray as &$keyword ) {
                                    while ( ' ' == substr($keyword, 0, 1) ) $keyword = substr($keyword, 1);
                                    while ( ' ' == substr($keyword, -1, 1) ) $keyword = substr($keyword, 0, -1);
                                }
                                $data['keywords'] = implode(',', $keywordsArray);
                            }
                            if ( Rpd::nE($_POST['application']['description']) ) $data['description'] = $_POST['application']['description'];
                            if ( is_array($_POST['application']['languages']) ) foreach ( $_POST['application']['languages'] as $subLanguage ) $data['languages'][$subLanguage] = true;
                            if ( !Rpd::rq($data['languages'][Rpd::$c['rapid']['culture']]) ) $data['languages'][Rpd::$c['rapid']['culture']] = true;
                        }
                        if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']) ) {
                            $metaData = json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']), true);
                        } else {
                            $metaData = array();
                        }
                        $metaData[$name] = $data;
                        @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile'], json_encode($metaData));
                    }
                    if ( @mkdir(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name) ) {
                        @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['defaultAction'] . '.' . Rpd::$c['raintpl']['tpl_ext'], "This is an automatic generated content.");
                    }
                    if ( is_dir('applications' . DIRECTORY_SEPARATOR . $name) && is_dir(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $name) )
                        Rpd::a('success', "The Application is created.");
                    else Rpd::a('error', "Something went wrong while trying to create Application. Application is not created successfully.");
                    $this->applicationsAction();
                } else {
                    Rpd::a('error', "Something went wrong while trying to save the Application. You did not fill the name, or this Applications is already exists.");
                    Rpd::a('applicationName', $_POST['application']['name']);
                    $this->applicationsAction(array('add'));
                }
            }
        } else if ( 'edit' == $args[0] && Rpd::rq($args['application']) ) {
            // Edit
            $application = $args['application'];
            if ( 'save' != $args[1] ) {
                if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']) ) {
                    $appMeta = json_decode(@file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']), true);
                    Rpd::a('appMeta', $appMeta[$application]);
                }
                Rpd::a('application', $application);
                AdministratorController::$template = 'application.edit';
            } else if ( 'save' == $args[1] && Rpd::rq($_POST['application']) ) {
                $data = array();
                if ( Rpd::nE($_POST['application']['title']) ) $data['title'] = $_POST['application']['title'];
                if ( Rpd::nE($_POST['application']['keywords']) ) {
                    $keywords = $_POST['application']['keywords'];
                    $keywordsArray = explode(',', $keywords);
                    foreach ( $keywordsArray as &$keyword ) {
                        while ( ' ' == substr($keyword, 0, 1) ) $keyword = substr($keyword, 1);
                        while ( ' ' == substr($keyword, -1, 1) ) $keyword = substr($keyword, 0, -1);
                    }
                    $data['keywords'] = implode(',', $keywordsArray);
                }
                if ( Rpd::nE($_POST['application']['description']) ) $data['description'] = $_POST['application']['description'];
                if ( Rpd::nE($_POST['application']['analytics']) ) $data['analytics'] = $_POST['application']['analytics'];
                $metaData = array();
                if ( @is_file(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']) )
                    $metaData = json_decode(@file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']), true);
                else $metaData = array();
                $metaData[$application] = $data;
                if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . Rapid::$culture . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile'], json_encode($metaData)) )
                    Rpd::a('success', "Application is saved.");
                else Rpd::a('error', "Something went wrong while trying to save the Application. Application is not saved (probably permission denied).");
                $this->applicationsAction();
            } else $this->applicationsAction();
        } else if ( 'remove' == $args[0] && Rpd::rq($args['application']) ) {
            $application = $args['application'];
            if ( in_array($application, Rpd::gA()) ) {
                if ( @is_dir('applications' . DIRECTORY_SEPARATOR . $application) ) {
                    $files = array_diff(scandir('applications' . DIRECTORY_SEPARATOR . $application), array('.', '..'));
                    foreach ( $files as $file ) @unlink('applications' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . $file);
                    @rmdir('applications' . DIRECTORY_SEPARATOR . $application);
                }
                foreach ( Rpd::gC() as $language ) {
                    if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application) ) {
                        $templates = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application), array('.', '..'));
                        foreach ( $templates as $template ) @unlink(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application . DIRECTORY_SEPARATOR . $template);
                        @rmdir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application);
                    }
                    if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']) )
                        $metaData = json_decode(file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile']), true);
                    else $metaData = array();
                    if ( isset($metaData[$application]) ) unset($metaData[$application]);
                    @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['metaFile'], json_encode($metaData));
                }
                if ( !is_dir('applications' . DIRECTORY_SEPARATOR . $application) && !is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application) )
                    Rpd::a('success', "The selected Application is removed.");
                else Rpd::a('error', "Something went wrong (probably permission denied) while trying to remove Application. The Application is not removed.");
            } else Rpd::a('error', "Something went wrong while trying to check application. Application not found.");
            $this->applicationsAction();
        } else $this->applicationsAction();
    }

    /**
     * Create translations for languages.
    */ 
    public function translationsAction($args = array()) {
        Rpd::a('menu', array('translationsActive' => true));

        if ( '' == $args[0] ) {
            if ( is_dir(Rpd::$c['rapid']['translationsDir']) ) {
                $translationsArray = array();
                $index = 1;
                if ( is_file(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n') ) {
                    $info = pathinfo(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n');
                    if ( in_array($info['filename'], Rpd::gC()) ) {
                        $content = json_decode(file_get_contents(Rpd::$c['rapid']['translationsDir'] . Rpd::$cl . '.i18n'), true);
                        if ( 0 < count($content) )
                            foreach ( $content as $from => $to )
                                $translationsArray[] = array(
                                                                'language' => $info['filename'],
                                                                'from' => htmlentities($from),
                                                                'to' => htmlentities($to),
                                                                'index' => $index++
                                                            );
                    }
                }
                if ( 1 < $index ) Rpd::a('translations', $translationsArray);
            }
        } else if ( 'add' == $args[0] ) {
            if ( 'save' != $args[1] ) AdministratorController::$template = 'translation.add';
            else {
                if ( Rpd::nE($_POST['translation']['from']) && Rpd::nE($_POST['translation']['to']) ) {
                    $language = Rpd::$cl;
                    $from = $_POST['translation']['from'];
                    $to = $_POST['translation']['to'];
                    if ( !is_file(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n') )
                        @file_put_contents(Rpd::$c['rapid']['translationsDir'] . DIRECTORY_SEPARATOR . $language . '.i18n', '{}');
                    if ( is_file(Rpd::$c['rapid']['translationsDir'] . DIRECTORY_SEPARATOR . $language . '.i18n') ) {
                        $content = json_decode(file_get_contents(Rpd::$c['rapid']['translationsDir'] . DIRECTORY_SEPARATOR . $language . '.i18n'), true);
                        $content[$from] = $to;
                        if ( @file_put_contents(Rpd::$c['rapid']['translationsDir'] . DIRECTORY_SEPARATOR . $language . '.i18n', json_encode($content)) )
                            Rpd::a('success', "The Translation is saved.");
                        else Rpd::a('error', "Something went wrong while trying to save Translation. Translation is not saved (probably permission denied).");
                    } else Rpd::a('error', "Something went wrong while trying to locate the translation file. Translations is not saved.");
                    $this->translationsAction();
                } else {
                    Rpd::a('translation', $_POST['translation']);
                    Rpd::a('error', "Something went wrong while trying to save Translation. Please fill all the fields.");
                    $this->translationsAction(array('add'));
                }
            }
        } else if ( 'edit' == $args[0] && Rpd::nE($args['language']) && Rpd::nE($args['index']) ) {
            $language = $args['language'];
            $index = $args['index'];
            Rpd::a('tLanguage', $language);
            Rpd::a('tIndex', $index);
            $content = json_decode(@file_get_contents(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n'), true);
            if ( 'save' != $args[1] ) {
                $cIndex = 1;
                foreach ( $content as $key => $line )
                    if ( $cIndex++ == $index ) $translate = array('from' => $key, 'to' => $line);
                if ( !isset($translate) ) {
                    Rpd::a('error', "Something went wrong while trying to allocate translation.");
                    $this->translationsAction();
                } else Rpd::a('translation', $translate);
                AdministratorController::$template = 'translation.edit';
            } else {
                if ( Rpd::nE($_POST['translation']['from']) && Rpd::nE($_POST['translation']['to']) ) {
                    $from = $_POST['translation']['from'];
                    $to = $_POST['translation']['to'];
                    $cIndex = 1;
                    foreach ( $content as $key => &$value )
                        if ( $cIndex++ == $index ) unset($content[$key]);
                    $content[$from] = $to;
                    if ( @file_put_contents(Rpd::$c['rapid']['translationsDir'] . DIRECTORY_SEPARATOR . $language . '.i18n', json_encode($content)) )
                        Rpd::a('success', "The Translation is saved.");
                    else Rpd::a('error', "Something went wrong while trying to save Translation. Translation is not saved (probably permission denied).");
                    $this->translationsAction();
                } else {
                    Rpd::a('error', "Something went wrong while trying to save Translation. Every fields have to filled.");
                    Rpd::a('translation', $_POST['translation']);
                    AdministratorController::$template = 'translation.edit';
                }
            }
        } else if ( 'remove' == $args[0] && Rpd::nE($args['language']) && Rpd::nE($args['index']) ) {
            $language = $args['language'];
            $index = $args['index'];
            if ( is_file(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n') ) {
                $content = json_decode(file_get_contents(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n'), true);
                $cIndex = 1;
                foreach ( $content as $key => $line ) 
                    if ( $cIndex++ == $index ) unset($content[$key]);
                if ( @file_put_contents(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n', json_encode($content)) )
                    Rpd::a('success', "The Translation is removed.");
                else Rpd::a('error', "Something went wrong while trying to remove Translation. Translations is not removed.");
            } else Rpd::a('error', "Something went wrong while trying to remove Translation. Translation file not found.");
            $this->translationsAction();
        } else $this->translationsAction();
    }

    /**
     * Set preferences.
    */ 
    public function preferencesAction($args = array()) {
        Rpd::a('menu', array('preferencesActive' => true));
        
        if ( '' == $args[0] ) {
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$cl . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile']) ) {
                if ( $siteArray = json_decode(@file_get_contents(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$cl . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile']), true) )
                    Rpd::a('preferences', $siteArray);
            } else @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$cl . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile'], json_encode(array('titlePrefix'=>"",'author'=>"",'favicon'=>"")));
        } else if ( 'save' == $args[0] ) {
            if ( Rpd::rq($_POST['preferences']['indexing']) ) @file_put_contents('robots.txt', "User-agent: *\r\nDisallow:");
            else @file_put_contents('robots.txt', "User-agent: *\r\nDisallow: /");
            if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$cl . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile']) ) {
                if ( @file_put_contents(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$cl . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['siteFile'], json_encode($_POST['preferences'])) )
                    Rpd::a('success', "The Preferences are saved.");
                else Rpd::a('error', "Something went wrong while trying to save Preferences. Probably permission denied.");
            } else Rpd::a('error', "Something went wrong while trying to save Preferences. Preferences not saved.");
            $this->preferencesAction();
        } else $this->preferencesAction();
    }

    /**
     * Check languages
     */ 
    public function languagesAction($args = array()) {
        Rpd::a('menu', array('languagesActive' => true));
        
        if ( '' == $args[0] ) {
            // List
            $languages = Rpd::gC();
            $languageInfo = array();
			$activeLang = array();
            foreach ( $languages as $language ) {
                if ( @is_file(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n') )
                    $translation = json_decode(@file_get_contents(Rpd::$c['rapid']['translationsDir'] . $language . '.i18n'), true);
                if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'layouts') ) {
                    $scan = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'layouts'), array('.', '..'));
                    $layoutsCount = 0;
                    foreach ( $scan as &$item )
                        if ( 'layout.' == substr($item, 0, 7) && '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($item, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) )
                            $layoutsCount++;
                }
                if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates') ) {
                    $templates = array();
                    $scan = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates'), array('.', '..'));
                    foreach ( $scan as $item ) {
                        $templateScan = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . $language . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $item), array('.', '..'));
                        $count = 0;
                        foreach ( $templateScan as &$template )
                            if ( '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($template, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) )
                                $count++;
                        $templates[$item] = $count;
                    }
                }
                if ( $language == Rpd::$cl )
					$activeLang[$language] = array(
														'name' => $language,
														'translationsCount' => ( isset($translation) ? count($translation) : 0 ),
														'layoutsCount' => ( isset($layoutsCount) ? $layoutsCount : 0 ),
														'templates' => ( isset($templates) ? $templates : array() ),
														'isDefault' => ( $language == Rpd::$c['rapid']['culture'] ? true : false ),
														'isActive' => ( $language == Rpd::$cl ? true : false )
													);
				else $languageInfo[$language] = array(
                                                    'name' => $language,
                                                    'translationsCount' => ( isset($translation) ? count($translation) : 0 ),
                                                    'layoutsCount' => ( isset($layoutsCount) ? $layoutsCount : 0 ),
                                                    'templates' => ( isset($templates) ? $templates : array() ),
                                                    'isDefault' => ( $language == Rpd::$c['rapid']['culture'] ? true : false ),
                                                    'isActive' => ( $language == Rpd::$cl ? true : false )
                                                );
            }
			if ( 1 == count($activeLang) ) $languageInfo = array_reverse(array_merge($languageInfo, $activeLang));
			if ( 0 < count($languageInfo) ) Rpd::a('languages', $languageInfo);
        } else if ( 'add' == $args[0] ) {
            if ( Rpd::rq($args['source']) && Rpd::rq($args['destination']) ) {
                $dir = opendir($args['source']); 
                @mkdir($args['destination']); 
                while ( false !== ($file = readdir($dir)) )
                    if ( ( $file != '.' ) && ( $file != '..' ) )
                        if ( is_dir($args['source'] . DIRECTORY_SEPARATOR . $file) )
                            $this->languagesAction(array(0 => 'add', 'source' => $args['source'] . DIRECTORY_SEPARATOR . $file, 'destination' => $args['destination'] . DIRECTORY_SEPARATOR . $file));
                        else 
                            if ( '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($file, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) )
                                @copy($args['source'] . DIRECTORY_SEPARATOR . $file, $args['destination'] . DIRECTORY_SEPARATOR . $file);
                closedir($dir); 
            } else {
                if ( Rpd::rq($_POST['language']['name']) ) {
                    $name = str_replace(DIRECTORY_SEPARATOR, '', $_POST['language']['name']);
                    if ( !in_array($name, Rpd::gC()) ) {
                        if ( @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $name) ) {
                            @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . 'layouts');
                            @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . 'templates');
                            @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['mailsDir']);
                            $applications = Rpd::gA();
                            foreach ( $applications as $application )
                                @mkdir(Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $application);
                            if ( Rpd::rq($_POST['language']['copy']) ) {
                                $files = array_diff(scandir(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture']), array('.', '..'));
                                foreach ( $files as $file )
                                    if ( is_file(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . $file) && '.' . Rpd::$c['raintpl']['tpl_ext'] == substr($file, (strlen(Rpd::$c['raintpl']['tpl_ext'])+1)*-1) )
                                        @copy(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . $file, Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . $file);
                                    else if ( is_dir(Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . $file) )
                                        $this->languagesAction(array(0 => 'add', 'source' => Rpd::$c['raintpl']['tpl_dir'] . Rpd::$c['rapid']['culture'] . DIRECTORY_SEPARATOR . $file, 'destination' => Rpd::$c['raintpl']['tpl_dir'] . $name . DIRECTORY_SEPARATOR . $file));
                                Rpd::a('success', "The new Language is created (<em>#text#</em>) and files are copied from default language.", array($name));
                            } else Rpd::a('success', "The new Language is created: <em>#text#</em>.", array($name));
                        } else Rpd::a('error', "Something went wrong while trying to add new Language. Probably permission denied.");
                    } else Rpd::a('error', "Something went wrong while trying to add new Language. The Language <em>#text#</em> is already exists.", array($name));
                } else Rpd::a('error', "Something went wrong while trying to add new Language. The name field is empty.");
                $this->languagesAction();
            }
        } else if ( 'remove' == $args[0] && Rpd::rq($args['language']) ) {
            $language = $args['language'];
            if ( Rpd::rq($args['dir']) ) {
                $dir = $args['dir'];
                $files = array_diff(scandir($dir), array('.','..')); 
                foreach ( $files as $file )
                    ( is_dir($dir . DIRECTORY_SEPARATOR . $file) ) ? $this->languagesAction(array(0 => 'remove', 'language' => $language, 'dir' => $dir . DIRECTORY_SEPARATOR . $file)) : @unlink($dir . DIRECTORY_SEPARATOR . $file);
                return @rmdir($dir);
            } else {
                if ( @is_dir(Rpd::$c['raintpl']['tpl_dir'] . $language) ) {
                    $dir = Rpd::$c['raintpl']['tpl_dir'] . $language;
                    if ( $this->languagesAction(array(0 => 'remove', 'language' => $language, 'dir' => $dir)) )
                        Rpd::a('success', "The Language is removed.");
                    else Rpd::a('error', "Something went wrong while trying to remove Language. Probably permission denied.");
                } else Rpd::a('error', "Something went wrong while trying to remove Language. Language not found.");
                $this->languagesAction();
            }
        } else $this->languagesAction();
    }

    public function globalsourcesAction($args = array()) {
        Rpd::a('menu', array('globalSourcesActive' => true));
        
        $sourcePath = 'applications' . DIRECTORY_SEPARATOR . Rpd::$c['rapid']['globalSourcesFile'];
        if ( !is_file($sourcePath) ) @file_put_contents($sourcePath, json_encode(array('javascripts' => array(), 'stylesheets' => array(), 'less' => array())));
        if ( 'save' != $args[0] ) {
            if ( @file_get_contents($sourcePath) )
                $sourceContent = json_decode(file_get_contents($sourcePath), true);
            else $sourceContent = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
            if ( @is_file($sourcePath) ) Rpd::a('last_modified', date('Y-m-d H:i:s', filemtime($sourcePath)));
            Rpd::a('writable', is_writable($sourcePath));
            Rpd::a('sourceContent', $sourceContent);
        } else {
            $content = array('javascripts' => array(), 'stylesheets' => array(), 'less' => array());
            if ( Rpd::rq($_POST['source']) ) {
                if ( Rpd::rq($_POST['source']['javascripts']) )
                    foreach ( $_POST['source']['javascripts'] as $source )
                        if ( !empty($source) ) $content['javascripts'][] = $source;
                if ( Rpd::rq($_POST['source']['stylesheets']) )
                    foreach ( $_POST['source']['stylesheets'] as $source )
                        if ( !empty($source) ) $content['stylesheets'][] = $source;
                if ( Rpd::rq($_POST['source']['less']) )
                    foreach ( $_POST['source']['less'] as $source )
                        if ( !empty($source) ) $content['less'][] = $source;
            }
            if ( @file_put_contents($sourcePath, json_encode($content)) )
                Rpd::a('success', "The Global Sources are saved.");
            else Rpd::a('error', "Something went wrong whily trying to save Global Sources. Global Sources does not changed.");
            $this->globalsourcesAction();
        }
    }

    /**
     * Rapid update
    */
    public function updateAction($args = array()) {
        if ( 'install' != $args[0] ) {
            // Get newest version
            $path = 'rapid-update.zip';
            if ( !is_file($path) ) {
                $zipURL = Rapid::getCurrentVersion(false, true);
                if ( @is_file($path) ) @unlink($path);
                $fh = fopen($path, 'w');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_USERAGENT, 'vargatamas');
                curl_setopt($ch, CURLOPT_URL, $zipURL); 
                curl_setopt($ch, CURLOPT_FILE, $fh); 
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_exec($ch);
                if ( curl_error($ch) != '' ) $downloadError = curl_error($ch);
                curl_close($ch);
                fclose($fh);
            }
            if ( !is_file($path) )
                Rpd::a('error', "Something went wrong while trying to download Update. #text#", array(( isset($downloadError) ? "Message: <em>" . $downloadError . "</em>." : "" )));
            else Rpd::a('success', "The new version of Rapid is downloaded (at #text#) successful, the filesize is #text# MB. This version is not installed yet, click on <em>Install Update</em> to confirm update.", array(date("Y. m. d. - H:i:s", filemtime($path)), number_format(filesize($path) / 1048576, 2)));
        } else {
            $updater = Rpd::$c['rapid']['updaterFile'];
            if ( !is_file(Rapid::$dir . DIRECTORY_SEPARATOR . $updater) )
                Rpd::a('error', "Something went wrong while trying to install Update. Missing updater file, can not install the update.");
            else {
                @copy(Rapid::$dir . DIRECTORY_SEPARATOR . $updater, $updater);
                if ( !is_file($updater) )
                    Rpd::a('error', "Something went wrong while beginning the installation. Can not move updater file.");
                else header('Location: /' . $updater);
            }
        }
    }

}