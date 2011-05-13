<?php
/**
 * Kolibo_Application_Resource_Doctrine
 *
 * @package Kolibo_Application_Resource
 * @author Alexander Sainer
 * @copyright 2010
 * @version $Id: Doctrine.php 15 2011-02-13 20:41:17Z Akido $
 * @access public
 *
 * ++++++++++++++++++++++++++++++++++++++++++++
 * Kofigurationsparameter:
 *  resources.doctrine
 *
 *  resources.doctrine.model_path -> Pfad zu den Models
 *  resources.doctrine.connections.[name] = [DSN]  | [name] = Name der Datenbank, [DSN] sind zugangsdaten zur DB
 *
 *
 */
class Kolibo_Application_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Configures an Doctrine instance and initializes all Connections
     *
     * @return Doctrine_Manager
     */
    public function init()
    {
	require_once '../../../Application/Resource/Doctrine.php';
        // retrieves the options &  converts them to an Zend_Config Object
        $pluginConfig = new Zend_Config( $this->getOptions() );
        if(!isset($pluginConfig->options) || empty($pluginConfig->options)) {
            throw new Zend_Application_Resource_Exception('Missing Configuration!');
        }

        // extends our default Zend_Loader_Autoloader with the Doctrine Autoloader
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Doctrine');
        $autoloader->pushAutoloader(array('Doctrine', 'autoload'), 'Doctrine_');

        // setting some default Doctrine Options
        $manager = Doctrine_Manager::getInstance();
        $manager->setAttribute(
            Doctrine::ATTR_MODEL_LOADING,
            Doctrine::MODEL_LOADING_AGGRESSIVE
        );
        $manager->setAttribute(
            Doctrine::ATTR_AUTOLOAD_TABLE_CLASSES,
            true
        );

        // tells Doctrine that it should please load all generated Models
        if(!isset($pluginConfig->options->models_path) || empty($pluginConfig->options->models_path)) {
            throw new Zend_Application_Resource_Exception('Missing Configuration Option "models_path"!');
        }
        Doctrine::loadModels($pluginConfig->options->models_path);
        Doctrine::loadModels($pluginConfig->options->models_path . '/generated');

        // setting up all connections
        if(!isset($pluginConfig->connections) || empty($pluginConfig->connections)) {
            throw new Zend_Application_Resource_Exception('Missing Configuration for Doctrine Connections!');
        }
        foreach($pluginConfig->connections as $name => $dsn) {
            $conn = Doctrine_Manager::connection($dsn, $name);
            $conn->setCharset('utf8');

            // a compatiblity option proposed by Doctrine
            $conn->setAttribute(Doctrine::ATTR_USE_NATIVE_ENUM, true);
        }

        return $manager;
    }
}

?>
