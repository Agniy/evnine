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
		$this->path_to=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_evnine'.DIRECTORY_SEPARATOR;
		$this->api='ModelsJoomla';
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
			'ModelsDatabaseInstallTables'	=>	array('path'=>'/models/'),
			'ModelsHelloWorld'						=>	array('path'=>'/models/'),
			'ModelsJoomla'								=>	array(
				'param'=>array(
					'host'=>'localhost',
					'login' => 'root',
					'pass' => 'root',
					'db' => 'information_schema'
				),
				'path'=>'/models/'
			),
		);	
	}
}
?>
