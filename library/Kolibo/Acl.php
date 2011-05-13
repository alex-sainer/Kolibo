<?php

class Kolibo_Acl extends Zend_Acl
{
	//Array für alle Rollen
	protected $_roles               = array();
	
	//Array für ressources
	protected $_resources 		= array();
	
	//Array für privileges
	protected $_privileges 		= array();
	
	//Array für ressources->privileges
	protected $_ressPrivTree 	= array();
	
	//hier wird das AclModel abgelegt
	protected $_aclModel 		= null;

        //Der Mapper für den Datenbankzugriff
        protected $_roleMapper          = null;
	
	
	
	/**
	 * @TODO Rollen, ressourcen und Priviliegien laden
	 * um SystemRessourcen zu sparen werden jeweils nur die Ress. und Privileges des aktuellen Moduls
	 * geladen
	 */
	public function __construct()
	{
		$this->_roleMapper = new Application_Model_Mapper_Acl();

                $this->_aclModel = new AclModel();	//initialisert das Model
		$this->_loadRoles();                    //Lädt Rollen
		$this->_loadRessources();		//Lädt Ressourcen (Modul_Controller)
		$this->_loadPrivileges();		//Lädt Privileges (Action)
		$this->_loadRessPrivTree();		//Lädt zuweisungsbaum
		$this->_allowRolePrivileges();		//Erlaubt den zugriff auf Ress. und Priv.
		$this->_denyRolePrivileges();		//Verbietet den zugriff auf Res. und Priv.
	}
	
	
	/**
	 * Lädt die Rollen aus der Datenbank und fügt Sie der ACL hinzu
	 * Wird zusälich in $_roles gespeichert
	 */
	protected function _loadRoles()
	{
		foreach($this->_aclModel->getRoles() AS $role){
			$this->_roles[$role['id']] = $role['name'];
			$this->addRole(new Zend_Acl_Role($role['name']));
		}
	}
	
	
	/**
	 * Lädt die Ressourcen aus der Datenbank und fügt Sie der ACL hinzu
	 * Wird zusätzlich in $_ressources gespeichert
	 */
	protected function _loadRessources()
	{
		foreach($this->_aclModel->getRessources() AS $ressource){
			$this->_ressources[$ressource->id] = $ressource->modul.'_'.$ressource->controller;
			$this->add(new Zend_Acl_Ressource($ressource->modul.'_'.$ressource->controller));
		}
	}
	
	/**
	 * Lädt die Privileges aus der Datenbank
	 * Wird in $_privileges gespeichert
	 */
	protected function _loadPrivileges()
	{
		foreach($this->_aclModel->getPrivileges() AS $privilege){
			$this->_privileges[$privilege->id] = $privilege->action;
		}
	}
	
	/**
	 * Lädt den ressource->privilege Tree aus der Datenbank
	 * Wird in $_ressPrivTree gespeichert
	 */
	protected function _loadRessPrivTree()
	{
		foreach($this->_aclModel->getResPrivTree() AS $tree){
			$this->_ressPrivTree[$tree->id] = array(
                            'idRessource'=> $tree->idressource,
                            'idPrivilege'=> $tree->idprivilege);
		}
	}
	
	/**
	 * Lädt die WhiteList aus der Datenbank und registriert sie in der ACL
	 */
	protected function _allowRolePrivileges()
	{
		foreach($this->_aclModel->getAllowedRoleTree() AS $roleTree){
			$this->allow($this->_roles[$roleTree->idrole], 
			$this->_ressources[$this->_ressPrivTree[$roleTree->idresspriv]['idRessource']],
			$this->_privileges[$this->_ressPrivTree[$roleTree->idresspriv]['idPrivilege']]);
		}
	}
	
	/**
	 * Lädt die Blacklist aus der Datenbank und registriert sie in der ACL
	 */
	protected function _denyRolePrivileges()
	{
		foreach($this->_aclModel->getDenydRoleTree() AS $roleTree){
			$this->deny($this->_roles[$roleTree->idrole], 
			$this->_ressources[$this->_ressPrivTree[$roleTree->idresspriv]['idRessource']],
			$this->_privileges[$this->_ressPrivTree[$roleTree->idresspriv]['idPrivilege']]);
		}
		
	}
}
