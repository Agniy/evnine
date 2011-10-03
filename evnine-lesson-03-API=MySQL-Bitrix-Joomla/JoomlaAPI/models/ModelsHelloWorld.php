<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ModelsHelloWorld
{	

	var $api;
	function __construct($api){
		$this->api=$api;
	}

	/** 
   * en: Get the data for query.
   * ru: Выполнить запрос через API
	 *  
	 * @param mixed $param 
	 * @access public
	 * @return array
	 */
	function getQuery($param) {
		$array=array();
		$array['databases']=$this->api->getQuery('show databases');
		return $array;
	}

	/**
	 * en: If method create table is exists
	 * ru: Если метод существует создадим таблицу
	 */
	function setCreateTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setCreateHello();
	}

	/**
	 * en: Reset any data
	 * ru: Сбросить все данные
	 */
	function setResetForTest()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setResetHello();
	}

	/**
	 * en: For ecach model drop table. 
	 * ru: Для каждой модели попробуем сделать сброс таблицы
	 */
	function setDropTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setDropHello();
	}
}
?>