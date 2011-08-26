<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersHelloWorld
{
	var $controller;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller = array(
			'public_methods' => array(
					//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					
					/*Публичные методы доступные всем пользователям*/
					/*Пример вызова t=имя контроллера&m=публичный метод	*/
				
					'default'=>array(
						// en: set info to $param['form_info']
						/* ru: ставим в массиве параметров $param['form_info']*/
						'ModelsHelloWorld' => array(
							'setInfoToParamform_info',
						),
						// en: Get info
						/* ru: Получем информацию */
						'ModelsInfo'=>array(
							'getInfo',
							// en: Alternative way of setting
							/* ru: Иной способ вывести информацию*/
							'getInfo->alternative_way_of_setting_info',
						)
					),
			),
		);
	}
} 

?>
