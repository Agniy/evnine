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

 	function getHelloWorld($param){
		return 'HelloWorld';
	}

 	function getByeBye($param){
		return 'ByeBye';
	}
}
?>
