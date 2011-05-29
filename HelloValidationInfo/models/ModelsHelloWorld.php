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
 	function setInfoToParamform_info(&$param){
		return $param['form_info']=array(
			'info of ModelsHelloWorld'=>'text of ModelsHelloWorld->setInfoToParamform_info()'
			,'info of ModelsHelloWorld 2'=>'text of ModelsHelloWorld->setInfoToParamform_info()'
		);
	}

	function setThrowNewException(&$param){
		throw new Exception('ModelsHelloWorld->setThrowNewException throw new Exception(\'\')');
	}
}
?>
