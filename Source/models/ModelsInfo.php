<?php

/** ModelsInfo
 * en: Model for information.
 * ru: Модель для отображения информации.
 *
 * @package ModelsValidation
 * @author ev9eniy
 * @version 2
 * @created 04-sep-2011 10:21:39
 */
class ModelsInfo
{
	/** $this->_info_array 
	 * 
	 * en: Array for info.
	 * ru: Массив информации.
	 * 
	 * @var array
	 * @access protected
	 */
	var $_info_array;

	/** __construct 
	 * en: The constructor set the info messages.
	 * ru: Конструктор устанавливающий сообщения.
	 * @access protected
	 * @return void
	 */
	function __construct(){
		$this->_info_array=array(
			'alternative_way_of_setting_info' => 'ModelsInfo->_info_array[\'alternative_way_of_setting_info\']=description or array key',
		);
	}

	/** getInfo(&$param)  
	 * en: Get the detailed description for information message.
	 * ru: Получить из массива информации подробное описание.
	 *
	 * en: Information message can be set in several ways:
	 * ru: Информацию можно установить несколькими способами:
	 * 
	 * en: 1. A method call.
	 * ru: 1. Установка через вызов метода.
	 * ModelsError=>getInfo->alternative_way_of_setting_info
	 * 
	 * en: 2. By the throw new Exception.
	 * ru: 2. Через исключение в методе.
	 *	class Models {
	 *		function method($param){
	 *			throw new Exception('alternative_way_of_setting_info');
	 *		}
	 *	}
	 *	
	 * en: 3. Directly for the &$param.
	 * ru: 3. Напрямую через массив info..
	 *	class Models {
	 *		function method(&$param){
	 *			$param['info']='set_info';
	 *		}
	 *	}
	 *
	 * @see Controllers.controller
	 * @param array &$param
	 * en: Array data.
	 * ru: Массив данных.
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
	 * en: Reset For PHPUnitTest.
	 * ru: Сброс после каждого теста.
	 */
	function setResetForTest(){
	}
}
?>
