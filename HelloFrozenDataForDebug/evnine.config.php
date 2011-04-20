<?php
class Config 
{

	var $param_const;
	var $path_to;
	var $api;
	var $access_level;
	var $class_path;
	var $controller_menu_view;

	function __construct(){

		$this->path_to='';
		$this->api='ModelsMySQL';
		$this->access_level=array(
			'guest'=>'0',
		);

		$this->param_const=array(
			'default_controller'=>'default_controller',
			'frozen_file'=>'frozen_file.txt',
			'ResetDBClass'=>'models'.DIRECTORY_SEPARATOR.'ModelsMySQLFrozenTables.php',
			'setResetForTest'=>true,
				//before call the method 
				//call reset method setResetForTest,
				//->setResetForTest($param)

				/*Перед вызовом метода из класса*/
				/*Сбросим данные в классе*/
				/*По ментоду ->setResetForTest($param);*/

		);
		$this->controller_menu_view=array(
			'helloworld'=>'ControllersHelloWorld',
		);
		$this->class_path=array(
			'ModelsMySQL'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
				'param'=>array(
					'host'=>'localhost',
					'login' => 'root',
					'pass' => 'root',
					'db' => 'information_schema'
				),
			),
			'ModelsHelloWorld'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
				'param'=>array(
					'hello'=>'config',
				),
			),
		);	
	}
}
?>
