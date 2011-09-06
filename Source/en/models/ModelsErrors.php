<?php

/**
 * Model for Errors
 *
 * @package ModelsValidation
 * @author ev9eniy
 * @version 2
 * @created 04-sep-2011 10:21:39
 */
class ModelsErrors
{
	/**
	 * Array for errors.
	 */
	var $_errors_array;

	/**
	 * The constructor set the error messages.
	 * 
	 * @access protected
	 * @return void
	 */
	function __construct(){
		$this->_errors_array=array(
			'alternative_way_of_setting_errors' => 'ModelsErrors->_errors_array[\'alternative_way_of_setting_errors\']=description or array key',
		);
	}

	/**
	 * Get an detailed description for the error.
	 *
	 * Error can be set in several ways:
	 * 
	 * 1. A method call.
	 *	ModelsError=>getError->alternative_way_of_setting_errors
	 * 
	 * 2. Error validation in an array form_error.
	 * /controllers/ControllersExample.php
	 *	'validation' => array(
	 *		'test_id' => array('to'=>'TestID','error' => 'set_errors')
	 *	)
	 * 
	 * 3. By the throw exception.
	 *	class Models {
	 *		function method($param){
	 *			throw new Exception('set_error');
	 *		}
	 *	}
	 *	
	 * 4. Directly for the array of param.
	 *	class Models {
	 *		function method(&$param){
	 *			$param['info']='set_error';
	 *		}
	 *	}
	 * 
	 * @see Controllers.controller
	 * @param array $param 
	 * Array data.
	 * 
	 * @access public
	 * @return array|string
	 */
	function getError(&$param) 
	{
		$param_error=array();
		if (is_array($param['form_error'])){
			foreach ($param['form_error'] as $param_title =>$param_value){
				if (is_array($param_value)){
					foreach ($param_value as $param_value_title =>$param_value_value){
						if (isset($this->_errors_array[$param_value])){
							$param_error[$param_title][$param_value_title] = $this->_errors_array[$param_value_value];
						}else {
							$param_error[$param_title][$param_value_title] = $param_value_value;
						}
					}
				}else {
					if (isset($this->_errors_array[$param_value])){
							$param_error[$param_title] = $this->_errors_array[$param_value];
					}else {
							$param_error[$param_title] = $param_value;
					}
				}
			}
			unset($param['form_error']);
			return $param_error;
		}elseif (!empty($param['info'])){
			if (isset($this->_errors_array[$param['info']])){
				return $this->_errors_array[$param['info']];
			}else {
				return $param['info'];
			}
		}
	}
	
	/**
	 * Reset For PHPUnitTest
	 */
	function setResetForTest(){
	}
}
?>
