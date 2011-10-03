<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
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
	 * en: MySQL link to class
	 * ru: Сохраним доступ для работ с MySQL
	 */
	function __construct($api){
		$this->api=$api;
	}
	
	function setUpdateTableEvnineModifierParamSetResetFalse(&$param) {
		$param['setResetForTest']=false;
		return $this->api->getQuery('UPDATE `evnine` SET text=\'update\'');
	}
	
	function getQueryFromTableEvnine() {
		return $this->api->getQuery('SELECT * FROM `evnine`');
	}
	
	function getQueryFromTableEvnineAfterUpdate(&$param) {
		$data=$this->getQueryFromTableEvnine();
		$param['setResetForTest']=true;
		return $data;
		
	}
	
	function getQueryFromTableEvnineAfterUpdateWithReset(&$param) {
		return $this->getQueryFromTableEvnine($param);
	}
	
	
	function getContentFromFrozenFile($param){
		return file_get_contents($param['frozen_file']);
	}
	
	/**
	 * en: Reset any data
	 * ru: Сбросить все данные
	 */
	function setResetForTest($param){
	
		file_put_contents($param['frozen_file'],'ModelsHelloWorld::setResetForTest');
		include_once($param['ResetDBClass']);
		$ModelsMySQLFrozenTables = new ModelsMySQLFrozenTables($this->api); 
		$ModelsMySQLFrozenTables->setResetEvnine($param);
	}
}
?>
