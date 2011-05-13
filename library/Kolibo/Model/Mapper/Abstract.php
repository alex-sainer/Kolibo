<?php
/**
 * @package Kolibo
 * @category Kolibo_Model
 * @subpackage Mapper
 *
 * @author Alex Sainer
 * @version $Id: Abstract.php 50 2011-02-22 08:57:09Z Akido $
 *
 * #### properties to be defined by the Developer ####
 * @var protected $_tableClass = {table}_DbTable DbTable ClassName
 * @var protected $_model	= {table}_Model Model Classname
 * @var protected $_primary	= only if primary is not "ID"
 */
abstract class Kolibo_Model_Mapper_Abstract
{

    /**
     * MUSS in der Child-Klasse angegeben werden
     * @var Klassenname der Tabellenklasse
     * @see Zend_Db_Table_Abstract
     */
    protected $_tableClass = null;

    /**
     * MUSS in der Child-Klasse angegeben werden
     * @var Klassenname des Models
     * @see Kolibo_Model_Abstract
     */
    protected $_modelClass = null;

    /**
     * Wenn nicht 'ID' muss der schlüssel auch angegeben werden
     * @var Primärschlüssel der Tabelle
     */
    protected $_primary = 'ID';

####################################################################

    /**
     * Hier wird die Tabellen-Klasse abgespeichert
     */
    protected $_dbTable = null;

    /**
     * Hier werden die Eigenschaften vom Model gespeichert
     */
    protected $_modelVars = null;
    
    public function __construct()
    {
	$this->_dbTable = new $this->_tableClass();
	return $this;
    }

    public function getDbTable()
    {
	return $this->_dbTable;
    }


    public function save(Kolibo_Model_Abstract $model)
    {
	$this->_modelVars = $model->getClassVars();
	unset($this->_modelVars['_className']);

	$model = $model->toArray();
	$save = array();
	
	foreach($this->_modelVars AS $key => $value){
		$key = ltrim($key,'_');
	    if(array_key_exists($key, $model)){
		$save[$key] = $model[$key];
	    }
	}
	if(null == ($id = $save[$this->_primary])){
	    unset($save[$this->_primary]);
	    $result = $this->getDbTable()->insert($save);
	} else {
	    $result = $this->getDbTable()->update($save, array($this->_primary.' = ?' => $id));
	}
	return $result;
    }

    /**
     *
     * @return INT the last insert ID
     */
    public function getTableStatus($key = null)
    {
	$tblName = $this->getDbTable()->info('name');

	$adapter = $this->getDbTable()->getAdapter();
	$connection = $adapter->getConnection();
	
	$query = "SHOW TABLE STATUS LIKE '$tblName'";

	$status = array();
	if ($resultSet = $connection->query($query)) {
	    while ($row = $resultSet->fetch_assoc()) {
		    $status[] = $row;
		}
	    $adapter->closeConnection();
	} else {
	    require_once 'Zend/Db/Adapter/Mysqli/Exception.php';
            throw new Zend_Db_Adapter_Mysqli_Exception($connection->error);
	}

	if ($key === null) {
            return $status[0];
        }

        if (!array_key_exists($key, $status[0])) {
            require_once 'Kolibo/Exception.php';
            throw new Kolibo_Exception('There is no table information for the key "' . $key . '"');
        }

        return $status[0][$key];
    }
    /**
     *
     * @param INTEGER $value
     */
    public function setAutoIncrement($value)
    {

	$tblName = $this->getDbTable()->info('name');
	$query = "ALTER TABLE $tblName AUTO_INCREMENT = $value";
	$adapter = $this->getDbTable()->getAdapter();

	if($adapter->getConnection()->query($query)){
	    $adapter->closeConnection();
	    return true;
	} else {
	    require_once 'Zend/Db/Adapter/Mysqli/Exception.php';
            throw new Zend_Db_Adapter_Mysqli_Exception($adapter->getConnection()->error);
	}
	return false;

    }

    public function find($id, $array = false)
    {
	$result = $this->getDbTable()->find($id);
	if(0 == count($result)){
	    return false;
	}
	if(!$array){
	    $result = new $this->_model($result->current()->toArray());
	} else {
	    $result = $result->current()->toArray();
	}
	return $result;
    }

    public function fetchAll()
    {
	$resultSet = $this->getDbTable()->fetchAll();

	$resultArray = array();
	foreach($resultSet AS $result){
	    $settingValues = $result->toArray();
	    $resultArray[] = new $this->_model($settingValues);
	}
	return $resultArray;
    }


}