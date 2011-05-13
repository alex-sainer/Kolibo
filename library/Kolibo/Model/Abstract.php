<?php
/**
 * Description of Kolibo_Model_Abstract
 * @package Kolibo
 * @category Kolibo_Model
 * @author $Author: Akido$
 * @version $Id: Abstract.php 41 2011-02-16 02:29:01Z Akido $
 *
 * #### properties to be defined ####
 * @var protected $_tableClass = Model Classname
 *
 */
abstract class Kolibo_Model_Abstract
{
    protected $_tableClass = null;

    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options !== null) {
            throw new Exception('array needed but none given');
        }
    }

    public function __set($name, $value)
    {
        $var = '_'.$name;
        $classVars = get_class_vars($this->_tableClass);

        if(array_key_exists($var, $classVars)){
            $this->$var = $value;
            return $this;
        } else {
            throw new Exception('Eigenschaft '.$var.' nicht vorhanden');
        }
    }

    public function __get($name)
    {
        $var = '_'.$name;
        $classVars = get_class_vars($this->_tableClass);
        if(array_key_exists($var, $classVars)){
            return $this->$var;
        } else {
            throw new Exception('Eigenschaft '.$var.' nicht vorhanden');
        }
    }

    public function getClassVars()
    {
	$vars = get_class_vars($this->_tableClass);
	return $vars;
    }

    public function setOptions(array $options)
    {
        $classVars = get_class_vars($this->_tableClass);
        foreach ($options as $key => $value) {
            $var = '_'.$key;
            if (array_key_exists($var, $classVars)) {
                $this->$var = $value;
            }
        }
        return $this;
    }

    function toArray($data = array()) {
	if (is_array($data) || is_object($data)) {
	    $result = array();
	    foreach ($this as $key => $value) {
		$result[ltrim($key,'_')] = $this->toArray($value);
	    }
	    return $result;
	}
	return $data;
    }
}
?>
