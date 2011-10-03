<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
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
