<?php
/**
 * en: Model for info
 * ru: Модель сообщений 
 *
 * @package Models
 * @author 1
 * @version 1.0
 * @created 01-окт-2010 22:03:39
 */
class ModelsInfo
{
	/**
	 * en: Array for info
	 * ru: Масcив информации
	 */
	var $form_info;
	function __construct(){
	/**
	 * en: Array for info
	 * ru: Масcив информации
	 */
		$this->form_info=array(
			'alternative_way_of_setting_info' 	=> 'ModelsInfo->form_info[\'alternative_way_of_setting_info\']=description or array key',
		);
	}

	/**
   * en: Get description or array key
   * en: of array by key $param['form_info']
	 * ru: Получить из массива ошибок подробное описание
   * ru: из массива $param['form_info']
	 * 
	 * @param array
	 * @return $param_info array or $param['info']
   */
function getInfo(&$param) 
{
	$param_info=array();
	if (is_array($param['form_info'])){
		foreach ($param['form_info'] as $param_title =>$param_value){
			if (is_array($param_value)){
				foreach ($param_value as $param_value_title =>$param_value_value){
					if (isset($this->form_info[$param_value])){
						$param_info[$param_title][$param_value_title] = $this->form_info[$param_value_value];
					}else {
						$param_info[$param_title][$param_value_title] = $param_value_value;
					}
				}
			}else {
				if (isset($this->form_info[$param_value])){
						$param_info[$param_title] = $this->form_info[$param_value];
				}else {
						$param_info[$param_title] = $param_value;
				}
			}
		}
		unset($param['form_info']);
		return $param_info;
	}elseif (!empty($param['info'])){
		if (isset($this->form_info[$param['info']])){
			return $this->form_info[$param['info']];
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
