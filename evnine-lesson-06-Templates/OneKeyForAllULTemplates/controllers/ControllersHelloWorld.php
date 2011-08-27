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
					//en: Public methods are available for all	
					//Example: index.php?t=the controller&m=the public method
					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/
					'default'=>array(
						'inURLMethod'=>array('default2'),
						'inURLTemplate' => array(
						  // en: Template of 'inURLMethod'
						  // ru: Шаблон заданный в 'inURLMethod'
						  // en: Array for the permanent links in the template, same call, but in different ways
							/* ru: Массив для постоянных ссылок в шаблоне, но на разные методы */
							'info' => 'default2',//Например ссылка inURLTemplate.info будет вести на шаблон по умолчанию
						),
						'validation'=>array(
							'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
						)

					),
				'default2'=>array(
						'inURLMethod'=>array('default'),
						'inURLTemplate' => array(
							'info' => 'default',
							// en: Calling the same, but other links are already in the template
							/* ru: Вызов тот же, но ссылки уже другие для шаблона */
						),
						'validation'=>array(
							'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
						)
				),

			)
		);
	}
} 
?>