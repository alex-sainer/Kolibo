<?php

// ein Zend_Auth Objekt instanziieren:
// $auth = Zend_Auth::getInstance();
//
// die Klasse Akido_Acl in der Bootstrap laden:
// $acl = new Akido_Acl
//
// im FrontController wird dann das Plugin registriert:
// $fc->registerPlugin(new Akido_Plugin_Auth $auth, $acl)
//
//
class Kolibo_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    private $_auth;
    private $_acl;

    private $_noauth = array('module' => 'default',
                             'controller' => 'user',
                             'action' => 'login');

    private $_noacl = array('module' => 'default',
                            'controller' => 'user',
                            'action' => 'noacl');

    private $_nosite = array('module' => 'default',
                             'controller' => 'error',
                             'action' => 'error');

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($this->_auth->hasIdentity()){
            $role = $this->_auth->getIdentity()->getUser()->role;
        }else{
            $role = 'guest';
        }

        $controller = $request->controller;
        $action = $request->action;
        $module = $request->module;

        $this->_acl = new Akido_Acl_Database($modul, $controller, $action, $role);

        

    }
}