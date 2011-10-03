<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ModelsHelloWorld
{	 
 	function isParamHello($param){
	 	if ($param['hello']==='hello') {
	 		return true;
	 	}else {
	 		return false;
	 	}
	}

 	function getHelloWorld(&$param){
 		unset($param['hello']);
		return 'HelloWorld';
	}

 	function getByeBye(&$param){
	 	$param['hello']='byebye';
		return 'ByeBye';
	}
}
?>