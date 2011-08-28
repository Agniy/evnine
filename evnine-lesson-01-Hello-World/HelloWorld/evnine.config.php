<?php
class Config 
{

var $param_const;
var $path_to;
var $access_level;
var $class_path;
var $controller_alias;

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
		'helloworld'=>array(
			'class_name'=>'ControllersHelloWorld',
			'path'=>'controllers'.DIRECTORY_SEPARATOR,
		),
	);
	$this->class_path=array(
	);	
	}
}
?>