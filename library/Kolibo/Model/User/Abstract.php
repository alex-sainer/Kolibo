<?php

class Kolibo_Model_User_Abstract {

	public $username 	= 'guest';
	public $role 		= 'guest';	
	public $parent		= '';
	
	/* Namensdaten */
	public $salution;
	public $title;
	public $firstname;
	public $lastname;
	
	/* Adressdaten */
	public $street;
	public $number;
	public $zip;
	public $city;
	public $country;
	
	/* Webdaten Allgemein */
	public $email;
	public $website;
	
	/* Webdaten Social*/
	public $gravatar;
	public $twitter;
	public $facebook;
}

?>