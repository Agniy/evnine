<?php
/**
 * ModelsHelloWorld
 * @package Models
 * @author ev9eniy
 * @version 1.0
 * @created 04-apr-2010 11:03:41
 */
class ModelsHelloWorld
{	

/**
 * MySQL link to class 
 * 
 * @var object
 * @access public
 */
var $api;

/**
 * Constructor save api (MySQL link to class)
 * 
 *	Сохраним доступ апи, в данном случае для работ с MySQL  
 *	сервером
 */
function __construct($api){
	$this->api=$api;//
}


/**
 * setUpdateTableEvnine
 * ModifierParam
 * SetResetFalse 
 * 
 * @param array &$param 
 * @access public
 * @return void
 */
function setUpdateTableEvnineModifierParamSetResetFalse(&$param) {
	$param['setResetForTest']=false;
	return $this->api->getQuery('UPDATE `evnine` SET text=\'update\'');
}

/**
 * getQueryFromTableEvnine 
 * 
 * @access public
 * @return array
 */
function getQueryFromTableEvnine() {
	return $this->api->getQuery('SELECT * FROM `evnine`');
}

/**
 * getQueryFromTableEvnineAfterUpdate 
 * 
 * @access public
 * @return array
 */
function getQueryFromTableEvnineAfterUpdate(&$param) {
	$data=$this->getQueryFromTableEvnine();
	$param['setResetForTest']=true;
	return $data;
	
}

/** getQueryFromTableEvnineAfterUpdateWithReset 
 * 
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getQueryFromTableEvnineAfterUpdateWithReset(&$param) {
	return $this->getQueryFromTableEvnine($param);
}


/**
 * getContentFromFrozenFile 
 * 
 * @param array $param 
 * @access public
 * @return str
 */
function getContentFromFrozenFile($param){
	return file_get_contents($param['frozen_file']);
}

/**
 * setResetForTest 
 * Reset any data 
 * 
 * @param array $param 
 * @access public
 * @return null
 */
function setResetForTest($param){

	file_put_contents($param['frozen_file'],'ModelsHelloWorld::setResetForTest');
	include_once($param['ResetDBClass']);
	$ModelsMySQLFrozenTables = new ModelsMySQLFrozenTables($this->api); 
	$ModelsMySQLFrozenTables->setResetEvnine($param);
}
}
?>
