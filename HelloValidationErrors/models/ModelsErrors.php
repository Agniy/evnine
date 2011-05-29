<?php
/**
 * en: Model for Errors
 * ru: Модель ошибок
 *
 * @package Models
 * @author 1
 * @version 1.0
 * @created 01-окт-2010 22:03:39
 */
class ModelsErrors
{
	/**
	 * en: Array for errors
	 * ru: Масcив ошибок
	 */
	var $errors_array;
	/**
	 * en: current_error
	 * ru: Текущие ошибки
	 */
	var $_current_error;
	function __construct(){
	/**
	 * en: Array for errors
	 * ru: Масcив ошибок
	 */
		$this->errors_array=array(
			'alternative_way_of_setting_errors' 	=> 'ModelsErrors->errors_array[\'alternative_way_of_setting_errors\']=description or array key',
		);
	}

	/**
   * en: Get description or array key
   * en: of array by key $param['form_error']
	 * ru: Получить из массива ошибок подробное описание
   * ru: из массива $param['form_error']
	 * 
	 * @param array
	 * @return $param_error array or $param['info']
   */
function getError(&$param) 
{
	$param_error=array();
	if (is_array($param['form_error'])){
		foreach ($param['form_error'] as $param_title =>$param_value){
			if (is_array($param_value)){
				foreach ($param_value as $param_value_title =>$param_value_value){
					if (isset($this->errors_array[$param_value])){
						$param_error[$param_title][$param_value_title] = $this->errors_array[$param_value_value];
					}else {
						$param_error[$param_title][$param_value_title] = $param_value_value;
					}
				}
			}else {
				if (isset($this->errors_array[$param_value])){
						$param_error[$param_title] = $this->errors_array[$param_value];
				}else {
						$param_error[$param_title] = $param_value;
				}
			}
		}
		unset($param['form_error']);
		return $param_error;
	}elseif (!empty($param['info'])){
		if (isset($this->errors_array[$param['info']])){
			return $this->errors_array[$param['info']];
		}else {
			return $param['info'];
		}
	}
}

/**
 * en; For unit test reset
 * ru: Обнулить таблицы для теста
 */
function setResetForTest() 
{	 
}


}
?>
