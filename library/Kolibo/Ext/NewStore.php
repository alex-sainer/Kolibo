<?php

class Kolibo_Ext_Store extends Zend_Db_Table
{

    private $_dateFormat = 'd.m.Y';
    private $_dateTimeFormat = 'H:i:s - d.m.Y';
    private $_url = 'default/ajax/getdata/store/';


    public function getStore($autoLoad = false, $storeName = false)
    {
	switch($autoLoad){
	    case true:
		$autoLoad = 'true';
		break;
	    case false:
		$autoLoad = 'false';
		break;
	}
	$i = $this->info();
	$str  = $this->getHead($i, $autoLoad, $storeName);
	$str .= $this->getFields($i);
	return $str;
    }

    private function getHead($i, $autoLoad, $storeName)
    {
	$name = $storeName ? $StoreName : $i['name'];

	$ret = $name." = new Ext.data.JsonStore({\r\n";
	$ret.= "\t  url: '".$this->_url . $i['name']."',\r\n";
	$ret.= "\t  autoLoad: ".$autoLoad.",\r\n";
	$ret.= "\t  storeId:'".$name."',\r\n";
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

    public function setStoreUrl($url)
    {
	$this->_url = $url;
	return $this;
    }


    public function addDataWriter()
    {
	
    }


    public static function convertDataTypeToExtType($type)
    {
	$datatypes = array(
	    'int'=>'int',
	    'varchar'=>'string',
	    'text'=>'string',
	    'datetime'=>'date',
	    'date'=>'date',
	    'decimal'=>'float',
	    'float'=>'float'
	);
	while(list($k, $v)=each($datatypes)){
	    if($type == $k) return $v;
	}
	return 'auto';
    }
}