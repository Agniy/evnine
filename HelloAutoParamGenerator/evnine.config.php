<?php
class Config 
{

var $param_const;
var $path_to;
var $access_level;
var $class_path;
var $controller;

	function __construct(){

	$this->path_to=(defined( '__DIR__' )?__DIR__:getcwd()).DIRECTORY_SEPARATOR;
	$this->access_level=array(
		'guest'=>'0',
	);

	$this->param_const=array(
		'default_controller'=>'default_controller',
		'debug'=>true,
		'param_out'=>true,//ADD 06.08.2011 для использования данныx с выxода на вxоде
		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
		'CacheDir'=>'PHPUnitCache',
		'CacheTimeSecPHPUnit'=>'0',
	);
	$this->controller_alias=array(
		'helloworld'=>'ControllersHelloWorld',
		'param_gen_models'=>'ControllersParamGenModels',
		'param_gen_view'=>'ControllersParamGenView',
		'validation'=>'ControllersHelloValidation',
	);
	$this->class_path=array(
		'ModelsValidation'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
		),
		'ModelsHelloWorld'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
			),
		'ModelsPHPUnit'=>array(
			'path'=>'models'.DIRECTORY_SEPARATOR,
			),
		'ViewsUnitPHP'=>array(
			'path'=>'views'.DIRECTORY_SEPARATOR,
			),

	);	
	}
}
?>
