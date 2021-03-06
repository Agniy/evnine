<?php
class EvnineConfig 
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
		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
		'CacheDir'=>'PHPUnitCache',
	);
	$this->controller_alias=array(
		'helloworld'=>'ControllersHelloWorld',
		'php_unit_test'=>'ControllersPHPUnit',
		'self_php_unit_test'=>'ControllersPHPUnitForTest',
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