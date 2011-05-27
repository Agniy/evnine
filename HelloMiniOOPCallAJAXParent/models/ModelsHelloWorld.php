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
 	function getHelloWorld($param){
		return 'HelloWorld';
	}


	function getHelloWorldParent($param){
		return $this->getHelloWorld($param);
	}

	function getHelloWorldParentParent($param){
		return $this->getHelloWorld($param);
	}


}
?>
