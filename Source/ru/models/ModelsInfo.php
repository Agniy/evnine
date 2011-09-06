<?php

/**
 * Модель для отображения информации.
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
	 * Массив информации.
	 * 
	 * @var array
	 * @access protected
	 */
	var $_info_array;

	/**
	 * Конструктор устанавливающий сообщения.
	 * @access protected
	 * @return void
	 */
	function __construct(){
		$this->_info_array=array(
			'alternative_way_of_setting_info' => 'ModelsInfo->_info_array[\'alternative_way_of_setting_info\']=description or array key',
		);
	}

	/**
	 * Получить из массива информации подробное описание.
	 *
	 * Информацию можно установить несколькими способами:
	 * 
	 * 1. Установка через вызов метода.
	 * ModelsError=>getInfo->alternative_way_of_setting_info
	 * 
	 * 2. Через исключение в методе.
	 *	class Models {
	 *		function method($param){
	 *			throw new Exception('alternative_way_of_setting_info');
	 *		}
	 *	}
	 *	
	 * 3. Напрямую через массив info..
	 *	class Models {
	 *		function method(&$param){
	 *			$param['info']='set_info';
	 *		}
	 *	}
	 *
	 * @see Controllers.controller
	 * @param array &$param
	 * Массив данных.
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
	 * Сброс после каждого теста.
	 */
	function setResetForTest(){
	}
}
?>
