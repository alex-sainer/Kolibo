<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


class Kolibo_Acl_Database_Mapper
{
    public $dbTableRessources;
    public $dbTablePrivileges;
    public $dbTableRules;
    public $dbTableRoles;

    public function  __construct() 
    {
        $this->dbTableRessources = new Kolibo_Acl_Model_DbTable_AclRessources();
        $this->dbTablePrivileges = new Kolibo_Acl_Model_DbTable_AclPrivileges();
        $this->dbTableRules = new Kolibo_Acl_Model_DbTable_AclRules();
        $this->dbTableRoles = new Kolibo_Acl_Model_DbTable_AclRoles();

    }

    public function getRessourceID($modul, $controller)
    {
        $whereModul = $this->dbTableRessources->getAdapter()->quoteInto('modul=?', $modul);
        $whereController = $this->dbTableRessources->getAdapter()->quoteInto('controller=?', $controller);
        $result = $this->dbTableRessources->fetchRow(array($whereModul, $whereController));

        return $result->ressourceID;
    }

    public function getPrivilegeID($action)
    {
        $whereAction = $this->dbTablePrivileges->getAdapter()->quoteInto('action=?', $action);
        $result = $this->dbTablePrivileges->fetchRow($whereAction);

        return $result->privilegeID;
    }

    public function getRule($ressurce, $privilege, $role)
    {
        $whereRessource = $this->dbTableRules->getAdapter()->quoteInto('ressourceID=?', $ressurce);
        $wherePrivilege = $this->dbTableRules->getAdapter()->quoteInto('privilegeID=?', $privilege);
        $whereRole = $this->_dbTableRules->getAdapter()->quoteInto('role=?', $role);

        $result = $this->dbTableRules->fetchRow(array($whereRessource, $wherePrivilege));

        return $result;
    }

    public function getRoles()
    {
        $resultSet = $this->dbTableRoles->fetchAll();
        return $resultSet;
    }

    

    public function getRessources($modul = null){
        if($modul){
            $whereModul = $this->dbTableRessources->getAdapter()->quoteInto('modul=?', $modul);
            $resultSet = $this->dbTableRessources->fetchAll($whereModul);
        } else {
            $resultSet = $this->dbTableRessources->fetchAll();
        }
        
        return $resultSet;
    }


    public function getAllowedPrivs($ressourceID){
        $select = $this->dbTableRules->getAdapter()->select(array('role'));
        $select->from('acl_rules');
        $select->where('ressourceID = ?', $ressourceID);
        $select->where('acl_rules.access = ?', 'allow');
        $select->join('acl_privileges',
                            'acl_rules.privilegeID = acl_privileges.privilegeID',
                            'acl_privileges.action');
        $result = $select->query()->fetchAll();

        $query = $select->__toString();
        #echo $query;
        #Zend_Debug::dump($result);
        return $result;

    }

    public function getDeniedPrivs($ressourceID){
        $select = $this->dbTableRules->getAdapter()->select();
        $select->from('acl_rules AS rule');
        $select->columns(array('rule.role', 'rule.access'));
        $select->where('ressourceID = ?', $ressourceID);
        $select->where('rule.access = ?', 'deny');
        $select->joinLeft('acl_privileges AS privilege',
                            'rule.privilegeID = privilege.privilegeID',
                            'privilege.action');

        $result = $select->query();


        return $result;
    }

}
?>