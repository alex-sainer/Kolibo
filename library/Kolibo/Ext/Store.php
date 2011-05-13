<?php
/**
 * Kolibo_Ext_Store
 *
 *
 * @author $Author: Akido $
 * @version $Id: Store.php 41 2011-02-16 02:29:01Z Akido $
 */

class Kolibo_Ext_Store extends Zend_Db_Table
{

    private $_dateFormat = 'd.m.Y';
    private $_dateTimeFormat = 'H:i:s - d.m.Y';
    private $_url = 'default/ajax/getdata/store/';

    public function getStore($load_data = false, $store_name = false)
    {
	switch($load_data){
	    case true:
		$load_data = 'true';
		break;
	    case false:
		$load_data = 'false';
		break;
	}
	$i = $this->info();
	$str  = $this->getHead($i, $load_data, $store_name);
	$str .= $this->getFields($i);
	return $str;
    }

    private function getHead($i, $load_data, $store_name)
    {
	$name = $store_name ? $store_name : $i['name'];

	$ret = $name."Store = new Ext.data.JsonStore({\r\n";
	$ret.= "\t  url: '".$this->_url . $i['name']."',\r\n";
	$ret.= "\t  autoLoad: ".$load_data.",\r\n";
	$ret.= "\t  storeId:'".$name."Store',\r\n";
	return $ret;
    }

    private function getFields($i)
    {
	$ret = "\t fields:[\r\n";

	$count = 0;
	while(list($field_name, $meta)=each($i['metadata'])){
	    $count++;
	    $field_type = self::convertDataTypeToExtType($meta['DATA_TYPE']);
	    $ret.= "\t\t\t";
	    $ret.= "{name:'".$field_name."',type:'".$field_type."'";

	    switch ($meta['DATA_TYPE']){
		case 'date':
		    $ret.= ",dateFormat:'".$this->_dateFormat."'}";
		    break;
		case 'dateTime':
		    $ret.= ",dateFormat:'".$this->_dateTimeFormat."'}";
		    break;
		default:
		    $ret.= "}";
	    }

	    if($count<sizeOf($i['metadata'])) $ret.= ",\r\n";
	}
	$ret.= "]})\r\n";
        return $ret;
    }

    public function setDateFormat($format)
    {
	$this->dateFormat = $format;
	return $this;
    }

    public function setDateTimeFormat($format)
    {
	$this->dateTimeFormat = $format;
	return $this;
    }

    public function setUrl($url)
    {
	$this->_url = $url;
	return $this;
    }

    public static function convertDataTypeToExtType($type)	{
		$datatypes = array(
			'int'=>'int',
			'varchar'=>'string',
			'text'=>'string',
			'datetime'=>'date',
			'date'=>'date',
			'decimal'=>'float',
			'float'=>'float'
		);
		while(list($k, $v)=each($datatypes))	{
			if($type == $k)	return $v;
		}
		return 'auto';
	}
}