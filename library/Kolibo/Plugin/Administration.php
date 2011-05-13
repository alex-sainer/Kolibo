<?php
/**
 *
 */

require_once 'Zend/Controller/Plugin/Abstract.php';

class Kolibo_Plugin_Administration extends Zend_Controller_Plugin_Abstract
{
    protected $_admin = 0;

    /*
     * 
     */
    public function  routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $adminModuleRoute = new Zend_Controller_Router_Route(
            'admin/:module/:controller/:action/*',
            array('module' => 'default',
                  'controller' => 'index',
                  'action' => 'index',
                  'admin' => 1)
        );
        $adminControllerRoute = new Zend_Controller_Router_Route(
            'admin/:controller/:action/*',
            array('module' => 'default',
                  'controller' => 'index',
                  'action' => 'index',
                  'admin' => 1)
        );

        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute('admin', $adminModuleRoute);
    }


    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $this->_admin = $request->getParam('admin');
        
        if($this->_admin){
            $layoutOptions = array(
                'layoutPath' 	=> APPLICATION_PATH."/layouts/backend",
                'layout'        => 'admin',
                'contentKey'    => 'content');
            Zend_Layout::startMvc($layoutOptions);
        }
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($this->_admin){
            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();
            if($controller != 'error'){
                $adminController = 'Admin'.ucfirst($controller);
                $request->setModuleName($module);
                $request->setControllerName($adminController);
            }
        }
    }
}