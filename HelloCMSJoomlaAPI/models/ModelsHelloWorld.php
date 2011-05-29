<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 04-apr-2010 11:03:41
 */
class ModelsHelloWorld
{	

	var $api;
	/**
	 * Constructor
	 */
	function __construct($api){
		$this->api=$api;
		//en: save api (Joomla link to class)
	}

	/** getQuery 
	 * 
	 * @param mixed $param 
	 * @access public
	 * @return void
	 */

	function getQuery($param) {
		$array=array();
		$array['databases']=$this->api->getQuery('show databases');
		$array['query_error']=$this->api->getQuery('fasdfas');
		return $array;
	}


	function setResetForTest($param){//Reset any data
		echo 'setResetForTest<br />';
	}

	/**
	 * en: If method create table is exists
	 * ru: Если метод существует создадим таблицу
	*/
	function setCreateTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsDatabaseInstallTables($this->api); 
		$installTables->setCreateHello();
	}

	/**
	 * en: For ecach model drop table. 
	 * ru: Для каждой модели попробуем сделать сброс таблицы
	 */
	function setDropTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsDatabaseInstallTables($this->api); 
		$installTables->setDropHello();
	}

}
?>