<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersParamGen
{
	var $controller;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller = array(
			'public_methods' => array(//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					//Публичные методы доступные всем пользователям
					//Пример вызова t=имя контроллера&m=публичный метод	
					'default'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
						'ModelsPHPUnit' => array(
//							'getParamCaseByParamTest',
//							'13getHelloWorld', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
							'getParamTest', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
							'getParamCaseByParamTest',
							'getCountParamByParamTest',
							'getParamTextName',
							'getHTMLMSGHeader',
							'getHTMLCaseHeader',
							'getDataFromControllerByParam',
							//'getPHPUnitCode',
							'getHTMLData',
						)
					),
			)
		);
	}
} 
?>
