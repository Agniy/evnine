<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 01-oct-2010 22:03:41
 */
class ModelsHelloWorld
{	 
 	function isGetHelloWorld($param){
		return true;
	}
	
	function isGetNotHelloWorld($param){
		return false;
	}

	function getHelloWorld($param){
		return 'hello world!';
	}
	
	function getNotHelloWorld($param){
		return 'not hello world';
	}
}
?>
