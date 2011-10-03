<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
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

	function getHelloWorldExt($param){
		return 'hello world!';
	}
	
	function getNotHelloWorld($param){
		return 'not hello world';
	}
}
?>