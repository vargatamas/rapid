<?php

class AdministratorModell {

    // Manage Layouts
    public static function getLayouts() {
        return R::findAll('layouts', 'ORDER BY last_modified DESC');
    }

    public static function saveLayout($name, $filename) {
        if ( !Rpd::nE($name) || !Rpd::nE($filename) ) return false;
        $layout = R::dispense('layouts');
        $layout->name = $name;
        $layout->filename = $filename;
        $layout->last_modified = date('Y-m-d H:i:s');
        R::store($layout);
        return true;
    }

    public static function getLayout($id) {
        if ( '' == $id || 1 > intval($id) ) return false;
        $layout = R::load('layouts', $id);
        if ( !$layout->id ) return false;
        else return $layout;
    }

    public static function updateLayout($id, $name) {
        if ( !Rpd::nE($id) || !Rpd::nE($name) ) return false;
        $layout = R::load('layouts', $id);
        if ( !$layout->id ) return false;
        $layout->name = $name;
        $layout->last_modified = date('Y-m-d H:i:s');
        R::store($layout);
        return true;
    }

    public static function removeLayout($id) {
        if ( !Rpd::nE($id) ) return false;
        $layout = R::load('layouts', $id);
        if ( !$layout->id ) return false;
        else $filename = $layout->filename;
        $layoutLinks = R::find('layoutlinks', 'layout_id = ?', array($id));
        foreach ( $layoutLinks as $layoutLink ) R::trash($layoutLink);
        R::trash($layout);
        return $filename;
    }


    // Link Layouts
    public static function getLinkedLayout($application) {
        if ( !Rpd::nE($application) ) return false;
        $layoutLinks = R::findOne('layoutlinks', 'application = ?', array($application));
        if ( !$layoutLinks->id ) return false;
        return $layoutLinks->layout;
    }
    
    public static function addLayoutLink($application, $layout) {
        if ( !Rpd::nE($application) || !Rpd::nE($layout) ) return false;
        $layoutLink = R::findOne('layoutlinks', 'application = ?', array($application));
        if ( 0 == count($layoutLink) ) $layoutLink = R::dispense('layoutlinks');
        $layoutLink->application = $application;
        $layoutLink->layout = $layout;
        R::store($layoutLink);
        return true;
    }

    public function getLinkedLayoutName($layoutLinkId) {
        if ( !Rpd::nE($layoutLinkId) ) return false;
        $idFind = R::load('layouts', $layoutLinkId);
        if ( !$idFind->id ) return false;
        else return $idFind->name;
    }

    public static function removeLinkedLayout($application) {
        if ( !Rpd::nE($application) ) return false;
        $layoutLink = R::findOne('layoutlinks', 'application = ?', array($application));
        if ( !$layoutLink->id ) return true;
        R::trash($layoutLink);
        return true;        
    }

    public static function removeAllLinkedLayout($layout) {
        if ( !Rpd::nE($layout) ) return false;
        $layouts = R::find('layoutlinks', 'layout = ?', array($layout));
        foreach ( $layouts as $layout ) R::trash($layout);
        return true;
    }

    public static function saveLinkedLayout($application, $layout) {
        if ( !Rpd::nE($application) || !Rpd::nE($layout) ) return false;
        $layoutLink = R::findOne('layoutlinks', 'application = ?', array($application));
        if ( !$layoutLink->id ) $layoutLink = R::dispense('layoutlinks');
        $layoutLink->application = $application;
        $layoutLink->layout = $layout;
        R::store($layoutLink);
        return true;
    }

    
    // Beans
    public static function inspectBeans() {
        return R::inspect();
    }
    
    public static function inspectBean($bean) {
        if ( !Rpd::nE($bean) ) return false;
        return R::inspect($bean);
    }
    
    public static function findBeans($bean, $start = 0, $items = 30) {
        if ( !Rpd::nE($bean) ) return false;
        return R::findAll($bean, ' LIMIT ? OFFSET ? ', array($items, $start));
    }
    
    public static function removeAllBean($bean) {
        if ( !Rpd::nE($bean) ) return false;
        R::wipe($bean);
        return true;
    }

    public static function getBeanSample($bean) {
        if ( !Rpd::nE($bean) ) return false;
        $find = R::findOne($bean);
        if ( !$find->id ) return false;
        return $find;
    }

    public static function getBean($beanName, $id) {
        if ( !Rpd::nE($beanName) || !Rpd::nE($id) ) return false;
        $load = R::load($beanName, $id);
        if ( !$load->id ) return false;
        return $load->getProperties();
    }

    public static function saveBean($beanName, $bean) {
        if ( !Rpd::nE($beanName) || !Rpd::nE($bean) || !is_array($bean) ) return false;
        $newBean = R::load($beanName, $bean['id']);
        if ( !$newBean->id ) $newBean = R::dispense($beanName);
        foreach ( $bean as $key => $prop ) $newBean->{$key} = $prop;
        R::store($newBean);
        return true;
    }

    public static function removeBean($beanName, $id) {
        if ( !Rpd::nE($beanName) || !Rpd::nE($id) ) return false;
        $bean = R::load($beanName, $id);
        if ( !$bean->id ) return false;
        R::trash($bean);
        return true;
    }

    public static function createBean($name, $fields) {
        if ( !Rpd::nE($name) || !Rpd::nE($fields) || !is_array($fields) ) return false;
        if ( 0 < R::count($name) ) return false;
        $bean = R::dispense($name);
        foreach ( $fields as $field )
            $bean->$field = 'test';
        R::store($bean);
        R::wipe($name);
        return true;
    }
    
    public static function countBean($name) {
        if ( !Rpd::nE($name) ) return false;
        return R::count($name);
    }

    public static function executeSQL($sql) {
        if ( !Rpd::nE($sql) ) return false;
        return R::exec($sql);
    }

    public static function getAllSQL($sql) {
        if ( !Rpd::nE($sql) ) return false;
        return R::getAll($sql);
    }


    // Routes
    public static function getRoutes() {
        return R::findAll('routes', 'ORDER BY last_modified DESC');
    }

    public static function getUsername($uid) {
        if ( !Rpd::nE($uid) ) return false;
        $user = R::findOne('users', 'id = ?', array($uid));
        if ( !$user->id ) return false;
        return $user->username;
    }

    public static function saveRoute($from, $to, $uid) {
        if ( !Rpd::nE($from) || !Rpd::nE($to) || !Rpd::nE($uid) ) return false;
        $route = R::dispense('routes');
        $route->from = $from;
        $route->to = $to;
        $route->uid = $uid;
        $route->last_modified = date('Y-m-d H:i:s');
        R::store($route);
        return true;
    }

    public static function removeRoute($id) {
        if ( !Rpd::nE($id) ) return false;
        $route = R::load('routes', $id);
        if ( !$route->id ) return false;
        R::trash($route);
        return true;
    }

}