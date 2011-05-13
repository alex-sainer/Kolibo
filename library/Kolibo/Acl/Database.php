<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Kolibo_Acl_Database extends Zend_Acl
{
    protected $_mapper;
    
    protected $_modul;
    protected $_controller;
    protected $_action;
    protected $_role;

    protected $_ressources;
    protected $_privileges;
    protected $_roles;

    protected $_rule;

    

    public function  __construct($modul, $controller, $action, $role)
    {
        $this->_modul = $modul;
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_role = $role;

        $this->_mapper = new Kolibo_Acl_Database_Mapper();

        $this->_loadRoles();
        $this->_loadRessources();
        $this->_loadPrivileges();
        $this->_createStandardAclTree();
        
    }

    protected function _loadRoles()
    {
        $roles = $this->_mapper->getRoles();

        foreach ($roles AS $role){
            $this->_roles[] = $role;
            $this->addRole($role->role);
        }
        return $this;
    }

    protected function _loadRessources()
    {
        //ressourcen auslesen
        $this->_ressources = $this->_mapper->getRessources();
        #Zend_Debug::dump($this->_ressources);
        //Ressourcen der ACL hinzufügen
        foreach($this->_ressources AS $ressource){
            $this->addResource($ressource->module.':'.$ressource->controller);
        }
        return $this;
    }

    protected function _loadPrivileges()
    {

        foreach($this->_ressources AS $ressource){
            $allow = $this->_mapper->getAllowedPrivs($ressource->ressourceID);
            $deny = $this->_mapper->getDeniedPrivs($ressource->ressourceID);
        }

        foreach($allow AS $rule){
            $this->allow($rule->rule->role, $resources, $rule->privilege->action);
        }
        foreach($deny AS $rule){
            $this->deny($rule->rule->role, $resources, $rule->privilege->action);
        }

    }

    protected function _createStandardAclTree()
    {
        $this->addResource('default:error');
        $this->allow(null, 'default:error', null);
        $this->allow('admin', null, null);
    }
}
?>
