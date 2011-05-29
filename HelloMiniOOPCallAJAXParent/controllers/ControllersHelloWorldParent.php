<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersHelloWorldParent
{
	var $controller_menu_view;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller_menu_view = array(
					'page_level'=>'1',
					// en: Depth of the controller, for the parents controller load
					/* ru: Глубина контроллера, для случая подгрузки родителей*/
					'parent'=> 'helloworldparentparent',
					// en: Parent controller of evnine.config.php
					/* ru: Родитель контроллера из конфига: evnine.config.php*/
					'this'=> 'helloworld',
					// en: The name of the current controller of evnine.config.php not used 
					/* ru: Имя текущего контроллера из evnine.config.php для удобства, не используется*/

			'public_methods' => array(//en: Public methods are available for all
	
					//Example: index.php?t=the controller&m=the public method
					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/
					'default'=>array(// en: Default method is always executed unless you specify
													 /* ru: Метод по умолчанию, выполняется всегда если не указан*/
						'ModelsHelloWorld' => 'getHelloWorldParent', // en: Call Method from class \models\ModelsHelloWorld->getHelloWorld
					),
			)
		);
	}
} 
?>
