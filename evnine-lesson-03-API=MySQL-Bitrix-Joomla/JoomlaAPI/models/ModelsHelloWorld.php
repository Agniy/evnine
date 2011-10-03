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
   * ru: �믮����� ����� �१ API
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
	 * ru: �᫨ ��⮤ ������� ᮧ����� ⠡����
	 */
	function setCreateTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setCreateHello();
	}

	/**
	 * en: Reset any data
	 * ru: ������ �� �����
	 */
	function setResetForTest()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setResetHello();
	}

	/**
	 * en: For ecach model drop table. 
	 * ru: ��� ������ ������ ���஡㥬 ᤥ���� ��� ⠡����
	 */
	function setDropTableClass()
	{
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_evnine/models/ModelsInstallTables.php');
		$installTables = new ModelsInstallTables($this->api); 
		$installTables->setDropHello();
	}
}
?>