<?php

/**
 * ModelsHelloWorld
 * @package HelloWorld
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
