<?php

// im FrontController wird dann das Plugin registriert:
// $fc->registerPlugin(new Akido_Plugin_Auth $auth, $acl)
//
//
class Kolibo_Acl_Plugin extends Zend_Controller_Plugin_Abstract
{
    private $_auth;
    private $_acl;
    
    private $_noauth = array('module' => 'default',
                             'controller' => 'error',
                             'action' => 'acl');
                             
    private $_noacl = array('module' => 'default',
                            'controller' => 'error',
                            'action' => 'acl');
                            
    private $_nosite = array('module' => 'default',
                             'controller' => 'error',
                             'action' => 'acl');
                            

    public function __construct($aclResource, $aclType)
    {
	echo $acl. '<br>';
	echo $aclType;


//        if(null == $acl){
//	    throw new Kolibo_Acl_Plugin_Exception('no acl given but ACL is required to get this plugin working');
//	}
//
//
//	$this->_auth = Zend_Auth::getInstance();
//        $acl = 'Akido_Acl_'.ucfirst($acl);
//        $this->_acl = $acl;
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {


        if ($this->_auth->hasIdentity())
        {
            $role = $this->_auth->getIdentity()->role;
        }
        else
        {
            $role = 'guest';
        }

        if($role == 'admin')
            return;
        

        #$controller = $request->controller;
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $module = $request->getModuleName();

        $acl = new Kolibo_Acl_Database($module, $controller, $action, $role);


        if (!$acl->has($module.':'.$controller))
        {
            
            if ('development' == APPLICATION_ENV){
                /*
                 * @todo: wenn die ressource nicht vorhanden ist die ressource dem Error-Controller übergeben!
                 */
            }
            $this->_request->setModuleName($this->_nosite['module']);
            $this->_request->setControllerName($this->_nosite['controller']);
            $this->_request->setActionName($this->_nosite['action']);
            $this->_request->setParam('error_handler', 'no_ressource');
            return;
        }
        
        if (!$acl->isAllowed($role, $module.':'.$controller, $action))
        {
            if (!$this->_auth->hasIdentity())
            {
                #echo 'Identität nicht vorhanden';
                $this->_request->setModuleName($this->_noauth['module']);
                $this->_request->setControllerName($this->_noauth['controller']);
                $this->_request->setActionName($this->_noauth['action']);
                $this->_request->setParam('error_handler', 'no_identity');
            }
            else
            {
                $this->_request->setModuleName($this->_noacl['module']);
                $this->_request->setControllerName($this->_noacl['controller']);
                $this->_request->setActionName($this->_noacl['action']);
                $this->_request->setParam('error_handler', 'no_access');
            }
        }
    }
}  