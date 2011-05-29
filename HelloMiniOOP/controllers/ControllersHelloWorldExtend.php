<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersHelloWorldExtend
{
	var $controller_menu_view;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller_menu_view = array(
			'private_methods' => array(
				//Private methods are not available for outside calls.
				//available only when a call from the public methods.
				#
				/*Приватные методы не доступные для вызова из вне,*/
				/*доступны только при вызова из публичных методов*/
				#
				'isGetNotHelloWorld_true'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
				'isGetNotHelloWorld_false'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				)
				//'isHasAccess'=>array(
					//Check for access?
					#
					/*Проверить, а есть ли доступ?*/
					//For example, for the validation check cookies
					#
					/*Например для валидации проверяем куки*/
					#
					//'ModelsValidation'  => 'isValidCookie',
					//),
				),
				
				'public_methods' => array(//Public methods are available for all

					//Example: index.php?t=the controller&m=the public method
					//Публичные методы доступные всем пользователям
					//Пример вызова t=имя контроллера&m=публичный метод	
					'default'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
//						'ModelsHelloWorld' => array(
//							'isGetNotHelloWorld',
//						), //Call Method from class \models\ModelsHelloWorld->isGetNotHelloWorld
							// If answer is true 
							// Call private method from controller::callPrivateMethod1
							#
							/*Если ответ правда */ 
							/* Вызываем приватный метод в контроллере */
							
					),
			)
		);
	}
} 

?>
