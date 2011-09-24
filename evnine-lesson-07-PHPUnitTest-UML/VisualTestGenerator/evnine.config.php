<?php
class EvnineConfig 
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
		/**
		 * en: To save parameters to exit.
		 * en: It is necessary for using several processing models.
		 * en: Data from the processing model output to the input of the visual model.
		 * ru: Для сохранения всех параметров на выходе.
		 * ru: Нужно при использования нескольких моделей обработки данных.
		 * ru: Пример: Создает данные модель обработки данных, а за вид визуальная модель.
		 * ru: Тогда данные со выхода модели обработки поступают на вход визуальной модели.
		 * 
		 *	$model_data = $evnine->getControllerForParam(
		 *		array('controller' => 'param_gen_models')
		 *	);
		 *	$output = $evnine->getControllerForParam(
		 *		array_merge($out['param_out'],array('controller'=>'param_gen_view'))
		 *	);
		 */
		'param_out'=>true,
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
