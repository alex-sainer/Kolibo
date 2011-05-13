<?php
/**
 * Startet Zend_Layout und setzt das layouts-Verzeichnis abh�ngig vom aktuellen module-Namen
 * Dadurch werden versch. Layouts pro Modul möglich
 */

require_once 'Zend/Controller/Plugin/Abstract.php';

class Kolibo_Plugin_LayoutLoader extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request) 
	{
        $curController = $this->getRequest()->getControllerName();
        switch ($curController){
			case 'admin':
				$layoutOptions = array(
					'layoutPath' 	=> '../FeApp/layouts/',
					'layout'		=> 'admin',
					'contentKey'	=> 'CONTENT');
			break;
			
			default:
				$layoutOptions = array(
					'layoutPath' 	=> '../FeApp/layouts/',
					'layout'		=> 'default',
					'contentKey'	=> 'CONTENT');
				
		}//switch($curAction)
        Zend_Layout::startMvc($layoutOptions);
    }
}