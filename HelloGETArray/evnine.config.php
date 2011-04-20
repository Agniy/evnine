<?php
class Config 
{

var $param_const;
var $path_to;
var $access_level;
var $class_path;
var $controller_menu_view;

	function __construct(){

	$this->path_to='';
	$this->access_level=array(
		'guest'=>'0',
	);

	$this->param_const=array(
		'default_controller'=>'default_controller',
	);
	$this->controller_menu_view=array(
		'helloworld'=>'ControllersHelloWorld',
	);
	$this->class_path=array(
		'ModelsHelloWorld'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
			),
			'ModelsValidation'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
			),

	);	
	}
}
?>