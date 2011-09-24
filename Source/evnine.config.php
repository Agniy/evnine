<?php
/** EvnineConfig 
 * 
 * en: Configuration file.
 * ru: Конфигурационный файл.
 * 
 * en: An alternative way of specifying the configuration.
 * ru: Альтернативный способ задания конфигурации.
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
	
	/** $this->access_level
	 * en: Access levels are set at:
	 * ru: Уровни доступа заданы в:
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

	/** $this->api
	 * en: Alias API (MySQL, etc.) 
	 * ru: Название API (MySQL, итд)
	 * 
	 * /evnine.config.php
	 *	$this->api='ModelsMySQL';
	 *	
	 * en: The path to the class specified in the:
	 * ru: Путь до класса задаётся в: 
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
	 * en: Initialize:
	 * ru: Объявляться:
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

	/** $this->class_path
	 * en: Path to the classes of models and variables are initialized by default
	 * ru: Путь до классов моделей и переменные инициализации по умолчанию
	 * 
	 * en: Caused by a model in the controller:
	 * ru: Вызываются модели в контроллерах:
	 * /controllers/Controllers.php
	 *	$this->controller=array(
	 *		ModelsHelloWorld => 'getHelloWorld'
	 *	)
	 *	
	 * en: IMPORTANT:
	 * en: Without specifying the path, all the models set in /models/
	 * ru: ВАЖНО:
	 * ru: Без указания пути, считается что все модели лежат в /models/
	 *	
	 * en: Also, may set in:
	 * ru: Так же могут быть заданы в:
	 * /evnine.config.php
	 *	$this->class_path=array(
	 *		'ModelsHelloWorld'=>array(
	 *		// en: class name is the same as the class file
	 *		// ru: Название класса, совпадает с названием файла класса
	 *		// ModelsHelloWorld.php = class ModelsHelloWorld
	 *		'path'=>'models'.DIRECTORY_SEPARATOR,
	 *		 // en: Path to class.
	 *		 // ru: Путь до класса.
	 *		'param'=>array(
	 *			// en: Parameters are passed to initialize the class.
	 *			// ru: Параметры которые передаются при инициализации класса.
	 *			'hello'=>'config',
	 *		)
	 *	)
	 * )
	 * 
	 * en: IMPORTANT: A weighty priority have parameters passed to
	 * en: initializing the base controller
	 * ru: ВАЖНО: Весомым приоритетом обладают параметры, переданные 
	 * ru: при инициализации базового контроллера
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
	
	/** $this->controller_alias
	 * en: Aliases names controllers. In the folder /controllers/
	 * en: Define in:
	 * ru: Псевдонимы названий контроллеров в папке /controllers/ 
	 * ru: задаются в: 
	 * /evnine.config.php
	 *	$this->controller_alias=array(
	 *		'helloworld'=>'ControllersHelloWorld',
	 *	);
	 * 
	 * en: The alternative with set the path.
	 * ru: Альтернативный вариант с указанием пути.
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

	/** $this->param_const
	 * en: Initialization parameters.
	 * en: Transmitted to all methods.
	 * ru: Параметры инициализации. 
	 * ru: Передаются во все методы.
	 *
	 * /evnine.config.php
	 *	$this->param_const=array(
	 *		// en: The default controller.
	 *		// ru: Контроллер по умолчанию.
	 *		// >> /?c=m=&param=777
	 *		// << /?c=default_controllerm=default&param=777
	 *		'default_controller'=>'default_controller',
	 *		// en: Debug mode.
	 *		// en: We need to track changes in the $param from method to method.
	 *		// ru: Режим отладки. 
	 *		// ru: Нужен для отслеживания изменения в $param от метода к методу.
	 *		'debug'=>true,
	 *		// en: A shared folder for the cache.
	 *		// ru: Общая папка для кэша.
	 *		'CacheDir'=>'PHPUnitCache',
	 *		// en: Folder to store the PHPUnit tests.
	 *		// ru: Папка для хранения PHPUnit тестов.
	 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
	 *		// en: Folder to store temporary data.
	 *		// ru: Папка для хранения промежуточных данных.
	 *		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
	 * 		//en: To save parameters to exit.
	 * 		//en: It is necessary for using several processing models.
	 * 		//en: Data from the processing model output to the input of the visual model.
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

	/** $this->path_to 
	 * en: Absolute path.
	 * en: Used to connect classes of models and controllers.
	 * ru: Абсолютный путь
	 * ru: Используется при подключении классов моделей и контроллеров
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
		 * en: Automatically detect the current path.
		 * ru: Автоматическое определение текущего пути.
		 */
		$this->api='ModelsMySQL';
		/**
		 * en: Alias API (MySQL, etc.) 
		 * ru: Название API (MySQL, итд)
		 */
		$this->access_level=array(
		/**
		 * en: Access levels.
		 * ru: Уровни доступа.
		 */
			'admin'=>'3',
			'moderator'=>'2',
			'user'=>'1',
			'guest'=>'0',
		);
		$this->param_const=array(
		/**
		 * en: Initialization parameters.
		 * ru: Параметры инициализации. 
		 */
			'default_controller'=>'default_controller',
			'debug'=>true,
		);
		$this->controller_alias=array(
		/**
		 * en: Aliases names controllers. In the folder /controllers/
		 * ru: Псевдонимы названий контроллеров в папке /controllers/ 
		 */
			'helloworld'=>'ControllersHelloWorld',
			'helloworld'=>array(
				'class_name'=>'ControllersHelloWorld',
				'path'=>'controllers'.DIRECTORY_SEPARATOR,
			)
		);
		$this->class_path=array(
		/**
		 * en: Path to the classes of models and variables are initialized by default
		 * ru: Путь до классов моделей и переменные инициализации по умолчанию
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
