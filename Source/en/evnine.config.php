<?php
/**
 * 
 * Configuration file.
 * 
 * An alternative way of specifying the configuration.
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
	 * Access levels are set at:
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
	 * Alias API (MySQL, etc.) 
	 * 
	 * /evnine.config.php
	 *	$this->api='ModelsMySQL';
	 *	
	 * The path to the class specified in the:
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
	 * Initialize:
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
	 * Path to the classes of models and variables are initialized by default
	 * 
	 * Caused by a model in the controller:
	 * /controllers/Controllers.php
	 *	$this->controller=array(
	 *		ModelsHelloWorld => 'getHelloWorld'
	 *	)
	 *	
	 * IMPORTANT:
	 * Without specifying the path, all the models set in /models/
	 *	
	 * Also, may set in:
	 * /evnine.config.php
	 *	$this->class_path=array(
	 *		'ModelsHelloWorld'=>array(
	 *		// class name is the same as the class file
	 *		// ModelsHelloWorld.php = class ModelsHelloWorld
	 *		'path'=>'models'.DIRECTORY_SEPARATOR,
	 *		 // Path to class.
	 *		'param'=>array(
	 *			// Parameters are passed to initialize the class.
	 *			'hello'=>'config',
	 *		)
	 *	)
	 * )
	 * 
	 * IMPORTANT: A weighty priority have parameters passed to
	 * initializing the base controller
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
	 * Aliases names controllers. In the folder /controllers/
	 * Define in:
	 * /evnine.config.php
	 *	$this->controller_alias=array(
	 *		'helloworld'=>'ControllersHelloWorld',
	 *	);
	 * 
	 * The alternative with set the path.
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
	 * Initialization parameters.
	 * Transmitted to all methods.
	 *
	 * /evnine.config.php
	 *	$this->param_const=array(
	 *		// The default controller.
	 *		// >> /?c=m=&param=777
	 *		// << /?c=default_controllerm=default&param=777
	 *		'default_controller'=>'default_controller',
	 *		// Debug mode.
	 *		// We need to track changes in the $param from method to method.
	 *		'debug'=>true,
	 *		// A shared folder for the cache.
	 *		'CacheDir'=>'PHPUnitCache',
	 *		// Folder to store the PHPUnit tests.
	 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
	 *		// Folder to store temporary data.
	 *		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
	 *	) 
	 * @see EvnineController.getDataFromMethod
	 * @see EvnineController.param_const
	 * @see EvnineConfig.param_const
	 * @var array
	 * @access public
	 */
	var $param_const;

	/**
	 * Absolute path.
	 * Used to connect classes of models and controllers.
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
		 * Automatically detect the current path.
		 */
		$this->api='ModelsMySQL';
		/**
		 * Alias API (MySQL, etc.) 
		 */
		$this->access_level=array(
		/**
		 * Access levels.
		 */
			'admin'=>'3',
			'moderator'=>'2',
			'user'=>'1',
			'guest'=>'0',
		);
		$this->param_const=array(
		/**
		 * Initialization parameters.
		 */
			'default_controller'=>'default_controller',
			'debug'=>true,
		);
		$this->controller_alias=array(
		/**
		 * Aliases names controllers. In the folder /controllers/
		 */
			'helloworld'=>'ControllersHelloWorld',
			'helloworld'=>array(
				'class_name'=>'ControllersHelloWorld',
				'path'=>'controllers'.DIRECTORY_SEPARATOR,
			)
		);
		$this->class_path=array(
		/**
		 * Path to the classes of models and variables are initialized by default
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
