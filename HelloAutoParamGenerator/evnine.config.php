<?php
class Config 
{

var $param_const;
var $path_to;
var $access_level;
var $class_path;
var $controller;

	function __construct(){

	$this->path_to='';
	$this->access_level=array(
		'guest'=>'0',
	);

	$this->param_const=array(
		'default_controller'=>'default_controller',
		'debug'=>true,
	);
	$this->controller_alias=array(
		'helloworld'=>'ControllersHelloWorld',
		'helloworld2'=>'ControllersHelloWorld2',
		'param_gen'=>'ControllersParamGen',
	);
	$this->class_path=array(
		'ModelsHelloWorld'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
			),
		'ModelsPHPUnit'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
			),
	);	
	}
}
?>