<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * @see Zend_Navigation
 */
require_once 'Zend/Navigation.php';

class Kolibo_Navigation
{
    /**
     *
     * @var array all Navigation-Containers
     */
    protected $_containers = array();

    /**
     *
     * @param string $container the new containername
     * @return Zend_Navigation
     */
    public function addContainer($container)
    {
	if(!$this->containerExists($container)){
	    return $this->_containers[$container] = new Zend_Navigation();
	} else {
	    return $this->_containers[$container];
	}
    }

    /**
     * Liefert den benötigten container zurück
     *
     * @param <type> $container name of the needed container
     */
    public function getContainer($container)
    {
	return $this->containerExists($container);
    }

    public function getContainers()
    {
	return $this->_containers;
    }

    public function removeContainer($container)
    {
	if($this->containerExists($container)){
	    unset($this->_containers[$container]);
	    return true;
	}

	return false;
    }

    public function containerExists($container = null)
    {
	if(null === $container){
	    throw new Kolibo_Navigation_Exception('no container-name given');
	}

	if(array_key_exists($container, $this->_containers)){
	    return $this->_containers[$container];
	}

	return false;
    }

}
?>
