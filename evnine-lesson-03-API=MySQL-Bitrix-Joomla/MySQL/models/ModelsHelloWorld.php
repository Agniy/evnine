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

	function getQuery($param) {
		$array=array();
		$array['databases']=$this->api->getQuery('select 2*2 as answer');
		$array['query_error']=$this->api->getQuery('fasdfas');
		return $array;
	}

	/**
	 * en: Reset any data
	 * ru: Сбросить все данные
	 */
	function setResetForTest($param){
		echo 'setResetForTest<br />';
	}
}
?>
