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
					// en: Public methods are available for all	
					// Example: index.php?t=the controller&m=the public method

					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/

					'default'=>array(
						'inURLExtController'=>'ext_controller',
						'inURLExtMethod'=>'ext_method',
					// en: Default method is always executed unless you specify
					/* ru: Метод по умолчанию, выполняется всегда если не указан*/
						'inURLMethod' => array(
							// en: Array to generate the URN (URI) to the method
						  /* ru: Массив для генерации ссылок по методу*/
	        	
							'default',
												// en: The array of methods in the controller to generate links
												// en: That would put the link in the template to a method
												// en: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] 
												// en: TWIG: inURL.default.pre, inURL.default.post
												// en: The front part of URI, the end of the URI
												
												/* ru: Список методов в контроллере для генерации ссылок*/
												/* ru: Что бы поставить ссылку в шаблоне на метод*/
												/* ru: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] */
												/* ru: TWIG: inURL.default.pre, inURL.default.post*/
												/* ru: Передняя часть урла, окончания адреса.*/
					),					
				),
			),
		);
	}
} 

?>
