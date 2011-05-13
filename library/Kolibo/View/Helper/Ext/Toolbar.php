<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';


class Kolibo_View_Helper_Ext_Toolbar extends Zend_View_Helper_Abstract
{


    private $_separators = array('-', '_', '|');

    /**
     *
     * @param array $toolbar
     *
     * title, iconcls, tooltip, handler
     *
     *
     *
     */


    public function ext_toolbar(array $toolbar)
    {

	

	$bar = '{items:[';

	$i=0;
	foreach($toolbar AS $t){
	    if($i>=1) $bar.= ',';
	    if(is_array($t)) {
		$bar.= $this->addItem($t);
	    } else {
		$bar.= $this->addSeparator($t);
	    }
	    $i++;
	}
	$bar.= ']}';
	return $bar;
    }

    public function addItem($t)
    {
	$count = 0;
	$output = '{';
	while(list($field_name, $meta)=each($t)){
	    $count++;

	    if('handler' !== $field_name){
		$meta = "'$meta'";
	    }
	    $output.= $field_name .':'.$meta;


	    if($count<sizeOf($t)) $output.= ",";
	}
	$output.= '}';
	return $output;
    }

    public function addSeparator($separator)
    {
	$separators = array('-', '_', '|');
	if(in_array($separator, $this->_separators)){
	    return "'$separator'";
	} else {
	    return;
	}
    }

}

?>
