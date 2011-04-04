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
	var $controller_menu_view;//Array controller
														//Базовый массив контроллера
	function __construct($access_level){//Initialize the controller with access levels
																			//Инициализируем контроллер передавая уровни доступа из конфига
		$this->controller_menu_view = array(
			'public_methods' => array(//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					//Публичные методы доступные всем пользователям
					//Пример вызова t=имя контроллера&m=публичный метод	
					'default'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
						'ModelsHelloWorld' => 
							'isParamHello',
							'isParamHello_true' =>array(
									'ModelsHelloWorld' =>'getHelloWorld'),
							'isParamHello_false' =>array(
									'ModelsHelloWorld' =>'getByeBye'),
					),
			)
		);
	}
} 

?>
