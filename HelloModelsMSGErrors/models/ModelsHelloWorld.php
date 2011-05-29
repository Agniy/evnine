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
 	function setErrorsToParamform_error(&$param){
		$param['form_error']=array(
			'error of ModelsHelloWorld'=>'text of ModelsHelloWorld->setErrorsToParamform_error()'
			,'error of ModelsHelloWorld 2'=>'text of ModelsHelloWorld->setErrorsToParamform_error()'
		);
	}

	function setThrowNewException(&$param){
		throw new Exception('ModelsHelloWorld->setThrowNewException throw new Exception(\'\')');
	}
}
?>
