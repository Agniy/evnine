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
			'inURLUnitTest' => array(
					'debug'=>'false',
					'all' => 'all',
				'REQUEST'=>array(
					array('test_id'=>'1'),
				),
			),
				//'all' => 'all',
				//'REQUEST'=>array(
					//array('test_id'=>'1'),
				//),
				//'ajax' => array(
					//'full',
					//'true',
					//'',
				//),
			//),
			'public_methods' => array(//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					//Публичные методы доступные всем пользователям
					//Пример вызова t=имя контроллера&m=публичный метод	
					'default'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
						'ModelsHelloWorld' => 'getHelloWorld', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
						'inURLUnitTest' => array(
							//'test'=>array(
								//'1','2'
							//),
							'REQUEST'=>array(
								array('test1' => '1',test=>'23'),
								array('test2' => '1',)
							),
							'PHPFlag' => '',
							//'REQUEST'=>array(
								//array('test_id'=>'999'),
								//array('test_id'=>'777'),
								////array('test_id'=>'555'),
							//),
							//'ajax' => array(
								//'full',
								//'true'
							//),
						),
					),
					
					'default2'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
						'ModelsHelloWorld' => 'getHelloWorld', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
						//'inURLUnitTest' => array(
							//'REQUEST'=>array(),
							//'ajax' => array(
								//'full',
							//),
						//),
					),
					'default_no_inURLUnitTest'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
						'ModelsHelloWorld' => 'getHelloWorld', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
							//
								//array('test777' => '1',
											//'test99' => '2',),
								//array('1test777' => '1',
											//'2test99' => '2',),
							//),
							//
						//),
					),
			)
		);
	}
} 

?>
