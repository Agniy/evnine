<?php

/** ModelsErrors
 * en: Model for Errors
 * ru: Модель для отображения ошибок.
 *
 * @package ModelsValidation
 * @author ev9eniy
 * @version 2
 * @created 04-sep-2011 10:21:39
 */
class ModelsErrors
{
	/**
	 * en: Array for errors.
	 * ru: Массив ошибок.
	 */
	var $_errors_array;

	/** __construct 
	 * en: The constructor set the error messages.
	 * ru: Конструктор устанавливающий сообщения об ошибках.
	 * 
	 * @access protected
	 * @return void
	 */
	function __construct(){
		$this->_errors_array=array(
			'alternative_way_of_setting_errors' => 'ModelsErrors->_errors_array[\'alternative_way_of_setting_errors\']=description or array key',
		);
	}

	/** getError(&$param)  
	 * en: Get an detailed description for the error.
	 * ru: Получить из массива ошибок подробное описание.
	 *
	 * en: Error can be set in several ways:
	 * ru: Ошибку можно установить несколькими способами:
	 * 
	 * en: 1. A method call.
	 * ru: 1. Установка через вызов метода.
	 *	ModelsError=>getError->alternative_way_of_setting_errors
	 * 
	 * en: 2. Error validation in an array form_error.
	 * ru: 2. Ошибку валидации, через массив form_error.
	 * /controllers/ControllersExample.php
	 *	'validation' => array(
	 *		'test_id' => array('to'=>'TestID','error' => 'set_errors')
	 *	)
	 * 
	 * en: 3. By the throw exception.
	 * ru: 3. Через исключение в методе.
	 *	class Models {
	 *		function method($param){
	 *			throw new Exception('set_error');
	 *		}
	 *	}
	 *	
	 * en: 4. Directly for the array of param.
	 * ru: 4. Напрямую через массив info в параметрах.
	 *	class Models {
	 *		function method(&$param){
	 *			$param['info']='set_error';
	 *		}
	 *	}
	 * 
	 * @see Controllers.controller
	 * @param array $param 
	 * en: Array data.
	 * ru: Массив данных.
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
	 * en: Reset For PHPUnitTest
	 * ru: Сброс после каждого теста
	 */
	function setResetForTest(){
	}
}
?>
