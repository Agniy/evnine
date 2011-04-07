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
	);
	$this->controller_menu_view=array(
		'validation'=>'ControllersHelloValidation',
	);
	$this->class_path=array(
			'ModelsValidation'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
			),

	);	
	}
}
?>