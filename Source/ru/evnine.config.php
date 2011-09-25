<?php
/**
 * 
 * Конфигурационный файл.
 * 
 * Альтернативный способ задания конфигурации.
 * 
 * /index.php
 *	include_once 'evnine.php';
 *	class EvnineConfig{
 *		function __construct(){
 *			$this->controller_alias=array(
 *				'helloworld'=>'ControllersHelloWorld'
 *			);
 *		}
 *	 }
 *	$evnine = new EvnineController();
 * 
 * @package EvnineConfig 
 * @version 1
 * @copyright 2009-2012 ev9eniy.info
 * @author ev9eniy.info
 */
class EvnineConfig 
{
	
	/**
	 * Уровни доступа заданы в:
	 * /evnine.config.php
	 *	$this->access_level=array(
	 *		'admin'=>'2',
	 *		'user'=>'1',
	 *		'guest'=>'0',
	 *	);
	 *	
	 * @see EvnineController.isUserHasAccessForMethod
	 * @see Controllers.__construct
	 * @see EvnineController.access_level
	 * @see EvnineConfig.access_level
	 * @var array
	 * @access public
	 */
	var $access_level;

	/**
	 * Название API (MySQL, итд)
	 * 
	 * /evnine.config.php
	 *	$this->api='ModelsMySQL';
	 *	
	 * Путь до класса задаётся в: 
	 * /evnine.config.php
	 *	 $class_path=array(
	 *		'ModelsMySQL'=>array(
	 *			'path'=>'models/'
	 *			'param'=>array(
	 *				'host' => 'localhost'
	 *				'login' => 'root',
	 *				'pass' => 'pass',
	 *			)
	 *		)
	 *	)
	 *	
	 * Объявляться:
	 * /evnine.php
	 *	if ($this->isSetClassToLoadAndSetParam($this->api)){
	 *		$this->loaded_class[$this->api]->setInitAPI($this->param);
	 *	}
	 *	
	 * /models/ModelsMySQL.php
	 *	function setInitAPI($param) {
	 *		$this->mysql=mysql_connect($param['host'],$param['login'],$param['pass']);
	 *	}
	 *
	 * @see EvnineController.__construct
	 * @see ModelsMySQL.setInitAPI
	 * @see EvnineController.api
	 * @see EvnineConfig.api
	 * @var string
	 * @access public
	 */
	var $api;

	/**
	 * Путь до классов моделей и переменные инициализации по умолчанию
	 * 
	 * Вызываются модели в контроллерах:
	 * /controllers/Controllers.php
	 *	$this->controller=array(
	 *		ModelsHelloWorld => 'getHelloWorld'
	 *	)
	 *	
	 * ВАЖНО:
	 * Без указания пути, считается что все модели лежат в /models/
	 *	
	 * Так же могут быть заданы в:
	 * /evnine.config.php
	 *	$this->class_path=array(
	 *		'ModelsHelloWorld'=>array(
	 *		// Название класса, совпадает с названием файла класса
	 *		// ModelsHelloWorld.php = class ModelsHelloWorld
	 *		'path'=>'models'.DIRECTORY_SEPARATOR,
	 *		 // Путь до класса.
	 *		'param'=>array(
	 *			// Параметры которые передаются при инициализации класса.
	 *			'hello'=>'config',
	 *		)
	 *	)
	 * )
	 * 
	 * ВАЖНО: Весомым приоритетом обладают параметры, переданные 
	 * при инициализации базового контроллера
	 *	$evnine->getControllerForParam(
	 *		array(
	 *			'hello'=>'supper_config'
	 *		)
	 *	)
	 * << supper_config
	 * 
	 * @see Controllers.controller
	 * @see EvnineController.getDataFromMethod
	 * @see EvnineController.class_path
	 * @see EvnineConfig.class_path
	 * @var array
	 * @access public
	 */
	var $class_path;
	
	/**
	 * Псевдонимы названий контроллеров в папке /controllers/ 
	 * задаются в: 
	 * /evnine.config.php
	 *	$this->controller_alias=array(
	 *		'helloworld'=>'ControllersHelloWorld',
	 *	);
	 * 
	 * Альтернативный вариант с указанием пути.
	 * /evnine.config.php
	 *	'helloworld'=>array(
	 *		'class_name'=>'ControllersHelloWorld',
	 *		'path'=>'controllers'.DIRECTORY_SEPARATOR,
	 *	)
	 *
	 * @see EvnineController.setLoadController  
	 * @see EvnineController.controller_alias
	 * @see EvnineConfig.controller_alias
	 * @var array
	 * @access public
	 */
	var $controller_alias;

	/**
	 * Параметры инициализации. 
	 * Передаются во все методы.
	 *
	 * /evnine.config.php
	 *	$this->param_const=array(
	 *		// Контроллер по умолчанию.
	 *		// >> /?c=m=&param=777
	 *		// << /?c=default_controllerm=default&param=777
	 *		'default_controller'=>'default_controller',
	 *		// Режим отладки. 
	 *		// Нужен для отслеживания изменения в $param от метода к методу.
	 *		'debug'=>true,
	 *		// Общая папка для кэша.
	 *		'CacheDir'=>'PHPUnitCache',
	 *		// Папка для хранения PHPUnit тестов.
	 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
	 *		// Папка для хранения промежуточных данных.
	 *		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
	 * 		//ru: Для сохранения всех параметров на выходе.
	 * 		//ru: Нужно при использования нескольких моделей обработки данных.
	 * 		//ru: Пример: Создает данные модель обработки данных, а за вид визуальная модель.
	 * 		//ru: Тогда данные со выхода модели обработки поступают на вход визуальной модели.
	 *		//$model_data = $evnine->getControllerForParam(
	 *		//	array('controller' => 'param_gen_models')
	 *		//);
	 *		//$output = $evnine->getControllerForParam(
	 *		//	array_merge($out['param_out'],array('controller'=>'param_gen_view'))
	 *		//);
	 * 		'param_out'=>true,
	 *	) 
	 * @see EvnineController.getDataFromMethod
	 * @see EvnineController.param_const
	 * @see EvnineConfig.param_const
	 * @var array
	 * @access public
	 */
	var $param_const;

	/**
	 * Абсолютный путь
	 * Используется при подключении классов моделей и контроллеров
	 * 
	 * @var string
	 * @access public
	 * @see EvnineController.path_to
	 * @see EvnineConfig.path_to
	 */
	var $path_to;

	function __construct(){
		$this->path_to=(defined( '__DIR__' )?__DIR__:getcwd()).DIRECTORY_SEPARATOR;
		/**
		 * Автоматическое определение текущего пути.
		 */
		$this->api='ModelsMySQL';
		/**
		 * Название API (MySQL, итд)
		 */
		$this->access_level=array(
		/**
		 * Уровни доступа.
		 */
			'admin'=>'3',
			'moderator'=>'2',
			'user'=>'1',
			'guest'=>'0',
		);
		$this->param_const=array(
		/**
		 * Параметры инициализации. 
		 */
			'default_controller'=>'default_controller',
			'debug'=>true,
		);
		$this->controller_alias=array(
		/**
		 * Псевдонимы названий контроллеров в папке /controllers/ 
		 */
			'helloworld'=>'ControllersHelloWorld',
			'helloworld'=>array(
				'class_name'=>'ControllersHelloWorld',
				'path'=>'controllers'.DIRECTORY_SEPARATOR,
			)
		);
		$this->class_path=array(
		/**
		 * Путь до классов моделей и переменные инициализации по умолчанию
		 */
			'ModelsMySQL' => array(
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
