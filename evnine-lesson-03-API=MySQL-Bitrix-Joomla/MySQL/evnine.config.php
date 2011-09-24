<?php
class EvnineConfig 
{

	var $param_const;
	var $path_to;
	var $api;
	var $access_level;
	var $class_path;
	var $controller_alias;

	function __construct(){

		$this->path_to='';
		$this->api='ModelsMySQL';
		$this->access_level=array(
			'guest'=>'0',
		);

		$this->param_const=array(
			'default_controller'=>'default_controller',
			'debug'=>true,
		);
		$this->controller_alias=array(
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
