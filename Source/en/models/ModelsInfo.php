<?php

/**
 * Model for information.
 *
 * @package ModelsValidation
 * @author ev9eniy
 * @version 2
 * @created 04-sep-2011 10:21:39
 */
class ModelsInfo
{
	/**
	 * 
	 * Array for info.
	 * 
	 * @var array
	 * @access protected
	 */
	var $_info_array;

	/**
	 * The constructor set the info messages.
	 * @access protected
	 * @return void
	 */
	function __construct(){
		$this->_info_array=array(
			'alternative_way_of_setting_info' => 'ModelsInfo->_info_array[\'alternative_way_of_setting_info\']=description or array key',
		);
	}

	/**
	 * Get the detailed description for information message.
	 *
	 * Information message can be set in several ways:
	 * 
	 * 1. A method call.
	 * ModelsError=>getInfo->alternative_way_of_setting_info
	 * 
	 * 2. By the throw new Exception.
	 *	class Models {
	 *		function method($param){
	 *			throw new Exception('alternative_way_of_setting_info');
	 *		}
	 *	}
	 *	
	 * 3. Directly for the &$param.
	 *	class Models {
	 *		function method(&$param){
	 *			$param['info']='set_info';
	 *		}
	 *	}
	 *
	 * @see Controllers.controller
	 * @param array &$param
	 * Array data.
	 * @return array or $param['info']
	 */
	function getInfo(&$param) 
	{
		if (!empty($param['info'])){
			if (isset($this->_info_array[$param['info']])){
				return $this->_info_array[$param['info']];
			}else {
				return $param['info'];
			}
		}
	}
	
	/**
	 * Reset For PHPUnitTest.
	 */
	function setResetForTest(){
	}
}
?>
