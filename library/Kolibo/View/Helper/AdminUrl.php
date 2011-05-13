<?php
/**
 * Akido Framework
 *
 *
 * @category   Akido
 * @package    Akido_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2010 Alexander Sainer
 * @version    
 * @license    
 */

/** Zend_View_Helper_Abstract.php */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Helper for making Admin-Urls depending on the Akido_Plugin_Administration
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Kolibo_View_Helper_AdminUrl extends Zend_View_Helper_Abstract
{
    /**
     * Generates an url to any Administration-Page
     *
     * @access public
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function adminUrl(array $urlOptions = array(), $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $adminPlugin = Zend_Controller_Front::getInstance()->getPlugin('Kolibo_Plugin_Administration');
        
        if($adminPlugin){
            $router = Zend_Controller_Front::getInstance()->getRouter();
            return $router->assemble($urlOptions, 'admin', $reset, $encode);
        } else {
            throw new Zend_Exception('Kolibo_Plugin_Administration is not loaded so this helper can not be used!');
        }
        
    }
}
