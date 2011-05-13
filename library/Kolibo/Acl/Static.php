<?php

class Kolibo_Acl_Static extends Zend_Acl
{
	function __construct()
	{
//Rollen erstellen
		$this->addRole(new Zend_Acl_Role('guest'));
		$this->addRole(new Zend_Acl_Role('user'), 'guest');
		$this->addRole(new Zend_Acl_Role('admin'));


//Ressourcen erstellen (Modul_Controller)

		//Frontend-Ressourcen
		$this->add(new Zend_Acl_Resource('default'.'_index'));
		$this->add(new Zend_Acl_Resource('default'.'_error'));
		$this->add(new Zend_Acl_Resource('default'.'_restricted'));
		$this->add(new Zend_Acl_Resource('default'.'_user'));

		//Backend-Ressourcen
		$this->add(new Zend_Acl_Resource('admin'.'_index'));
		$this->add(new Zend_Acl_Resource('admin'.'_category'));
		$this->add(new Zend_Acl_Resource('admin'.'_video'));
		$this->add(new Zend_Acl_Resource('admin'.'_content'));
		$this->add(new Zend_Acl_Resource('admin'.'_image'));
		$this->add(new Zend_Acl_Resource('admin'.'_user'));


//Regeln definieren

		//GAST-REGELN
		$this->allow('guest', 'default_index', null);
		$this->allow('guest', 'default_error', null);
		$this->allow('guest', 'default_user', 'login');
		$this->allow('guest', 'default_user', 'register');
		$this->allow('guest', 'default_user', 'register');
		$this->allow('guest', 'default_user', 'logout');
		$this->allow('guest', 'default_user', 'auth');
		$this->allow('guest', 'default_user', 'checkmail');
		$this->allow('guest', 'default_user', 'forgotpass');
		$this->allow('guest', 'default_user', 'sendnewpass');

		//User-REGELN
		$this->allow('user', 'default_index', null);
		$this->allow('user', 'default_restricted', null);
		$this->allow('user', 'default_user', null);

		//ADMIN-REGELN
		$this->allow('admin', null, null);
	}
}