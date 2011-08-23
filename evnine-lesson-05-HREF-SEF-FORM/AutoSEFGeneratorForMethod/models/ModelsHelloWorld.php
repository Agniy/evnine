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
