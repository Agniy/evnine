<?php

/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ModelsHelloWorld
{	 
 	function setErrorsToParamform_error(&$param){
		$param['form_error']=array(
			'error of ModelsHelloWorld'=>'1 text of ModelsHelloWorld->setErrorsToParamform_error()'
			,'error of ModelsHelloWorld 2'=>'2 text of ModelsHelloWorld->setErrorsToParamform_error()'
		);
	}

	function setThrowNewException($param){
		throw new Exception('ModelsHelloWorld->setThrowNewException throw new Exception(\'\')');
	}
}
?>
