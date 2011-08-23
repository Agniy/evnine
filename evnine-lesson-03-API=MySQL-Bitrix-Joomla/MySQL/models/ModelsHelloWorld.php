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
		$this->api=$api;//save api (MySQL link to class)
	}

	function getQuery($param) {
		$array=array();
		$array['databases']=$this->api->getQuery('show databases');
		$array['query_error']=$this->api->getQuery('fasdfas');
		return $array;
	}

	function setResetForTest($param){//Reset any data
		echo 'setResetForTest<br />';
	}
}
?>
