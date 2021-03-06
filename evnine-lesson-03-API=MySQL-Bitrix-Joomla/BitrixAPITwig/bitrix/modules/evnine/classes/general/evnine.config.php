<?php
class Config 
{

	var $param_const;
	var $path_to;
	var $api;
	var $access_level;
	var $class_path;
	var $controller_alias;

	function __construct(){

		$this->path_to=$_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/';
		$this->api='ModelsBitrix';
		$this->access_level=array(
			'guest'=>'0',
		);

		$this->param_const=array(
			'default_controller'=>'default_controller',
			'isPHPUnitDebug'=>true,
		);
		$this->controller_alias=array(
			'helloworld'=>'ControllersHelloWorld',
		);
		$this->class_path=array(
			'ModelsBitrix'=>array(
				'path'=>'/models/',
//				'param'=>array(
//					'host'=>'localhost',
//					'login' => 'root',
//					'pass' => 'root',
//					'db' => 'information_schema'
//				),
			),
			'ModelsHelloWorld'=>array(
				'path'=>'/models/',
				//'param'=>array(
					//'hello'=>'config',
				//),
			),
		);	
	}
}
?>
