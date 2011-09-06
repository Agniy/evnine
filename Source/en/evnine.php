<?php
/**
 * 
 * The base controller.
 * 
 * @uses EvnineConfig 
 * @package EvnineController
 * @version 0.3
 * @copyright 2009-2011 
 * @author ev9eniy.info
 * @updated 2011-06-01 17:53:02
 */
/**
 * To inherit the configuration.
 * 
 * Total output error
 * error_reporting(E_ERROR|E_RECOVERABLE_ERROR|E_PARSE|E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
 * Do not display errors
 * error_reporting(0);
 */
include_once('evnine.config.php');
class EvnineController extends EvnineConfig
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
	 * @see Controllers.controller_alias
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

	/**
	 * Flag of the SEF mode. 
	 * Set in the method $this->getControllerForParam
	 * when pass to the controller to parse the string SEF.
	 * 
	 * /evnine.php
	 *	if (!empty($param['sef'])) {
	 *		$this->sef_mode=true;
	 *	}
	 * 
	 * @see EvnineController.getControllerForParam
	 * @var bool
	 * @access public
	 */
	var $sef_mode;


	/**
	 * Is there access to the methods? Based on the level of user access.
	 * 
	 * @see EvnineController.isUserHasAccessForMethod
	 * @see EvnineController.getDataFromMethod
	 * @var boolean
	 * @access public
	 */
	var $isHasAccessSaveCheck;

	/**
	 * The name of the current controller.
	 *	setLoadController($current_controller){
	 *		$this->$current_controller_name=$current_controller;
	 *	}
	 * 
	 * @see EvnineController.setLoadController  
	 * @var string
	 * @access public
	 */
	var $current_controller_name;

	/**
	 * Current controller.
	 * 
	 * @var string
	 * @access public
	 */
	var $current_controller;

	/**
	 * Loaded controllers.
	 * 
	 * @see EvnineController.setLoadController 
	 * @var array
	 * @access public
	 */
	var $loaded_controller;

	/**
	 * The parameters that are passed to each method
	 * $param = array($param_init,$param_const)
	 * 
	 * @see EvnineController.getDataFromController
	 * @var array
	 * @access public
	 */
	var $param;

	/**
	 * Loaded classes.
	 * 
	 * @see EvnineController.isSetClassToLoadAndSetParam 
	 * @var array
	 * @access public
	 */
	var $loaded_class;

	/**
	 * Array response of the controller contains all the class_method calls.
	 * 
	 * $result = $evnine->getControllerForParam()
	 * 
	 * @see EvnineController.getControllerForParam
	 * @var array
	 * @access public
	 */
	var $result;

	/**
	 * To debug scripts
	 * 
	 * @see EvnineController.__construct
	 * @see EvnineController.getDataFromMethod
	 * @var boolean
	 * @access public
	 */
	var $debug;

/**
 * 
 * The class constructor.
 * 
 * @access protected
 * @return void
 */
function __construct(){
	$this->sef_mode=false;
	$this->param=array();
	parent::__construct();
	if (!empty($this->api)){
		/**
		 * Initialize the API
		 */
	if ($this->isSetClassToLoadAndSetParam($this->api)){
		/**
		 * The class is initialized? If not, load it and add the parameters from the config.
		 */
			$this->loaded_class[$this->api]->setInitAPI($this->param);
		}
	}
	/**
	 * evnine.debug.php function getForDebugArrayDiff()
	 */
	if ($this->param_const['debug']&&function_exists('getForDebugArrayDiff')){
		$this->param_const['debug_param_diff']=true;
	}
}

/**
 * Get data from the controller.
 * /index.php
 *	include_once 'evnine.php';
 *	$evnine = new EvnineController();
 *
 * @access public
 * @param array $param 
 * /index.php
 *	$result = $evnine->getControllerForParam(
 *		$param = array(
 *			 'controller' => 'controller'
 *			,'method' => 'method'
 *			,'REQUEST' => $_REQUEST
 *			,'ajax' => $_REQUEST['ajax'],
 *			,'sef' => $_REQUEST['sef'],
 *		)
 *	);
 * @return array
 */
function getControllerForParam($param) {
	if (!empty($param['sef'])) {
		/**
		 * In the SEF? Defined in:
		 * .htaccess
		 * <IfModule mod_rewrite.c>
		 * RewriteEngine On
		 * RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
		 * </IfModule>	
		 */
		/**
		 * Get data from the SEF string.
		 */
		$sef = $this->getURNbySEF($param['sef']);
		unset($param['sef']);
		/**
		 * Set SEF flag.
		 */
		$this->sef_mode=true;
		unset($param['REQUEST']['sef']);
		/**
		 * Set data from parse SEF.
		 */
		$param['method']=$sef['method'];
		$param['controller']=$sef['controller'];
		/**
		 * Merge data from SEF and POST.
		 */
		$param['REQUEST']=array_merge($param['REQUEST'],$sef['REQUEST']);
	}
	if (!empty($param['REQUEST']['submit'])){
		/**
		 * The case is processed multiple forms
		 */
		if ($first_key=(
			is_array($param['REQUEST']['submit'])
			?
			$this->getFirstArrayKey($param['REQUEST']['submit'])
			:
			$param['REQUEST']['submit'])
		){
		/**
		 * Obtain the method of the first key from the name of the submit button
		 */
			unset($param['REQUEST']['submit']);
			if (!empty($param['REQUEST'][$first_key]['c'])){
			/**
			 * Obtain the values of the controller
			 */
				$param['controller']=$param['REQUEST'][$first_key]['c'];
				unset($param['REQUEST'][$first_key]['c']);
			}
			if (!empty($param['REQUEST'][$first_key]['m'])){
			/**
			 * Obtain the values of the method
			 */
				$param['method']=$param['REQUEST'][$first_key]['m'];
				unset($param['REQUEST'][$first_key]['m']);
			}else {
				$param['method']=$first_key;
			}
			if (count($param['REQUEST'][$first_key])>0){
			/**
			 * If the method of any linked data
			 * merge them to the main parameters
			 */
				foreach ($param['REQUEST'][$first_key] as $_title =>$_value){
					$param[$_title]= $_value;
				}
			}
		}
	}elseif(empty($param['method'])) {
		/**
		 * If the method is not specified
		 */
		$param['method']='default';
	}
	$this->result=array();
	if (empty($this->result['LoadController'])){
		/**
		 * In the results of the data set is first initialized:
		 */
		$this->result['LoadController']=$param['controller'];
		$this->result['LoadMethod']=$param['method'];
	}
	/**
	 * Type of operation AJAX
	 */
	if ($param['ajax'][0]==='b') {
		/**
		 * When you want to send only the body
		 * HTML <body>...</body>
		 */
		$this->result['ajax']='Body';
		$param['ajax']=false;
	}elseif ($param['ajax'][0]==='a'){
		/**
		 * The case where you want to run a method in AJAX mode
		 */
		$this->result['ajax']='True';
		$param['ajax']=true;
	}else {
		/**
		 * Case in which call default method in the controller
		 */
		$this->result['ajax']='False';
		$param['ajax']=false;
	}
	/**
	 * Obtain data from the master controller to the parameters
	 */
	$this->getDataFromController($param);
	/**
	 * Reset method
	 */
	$this->param['method']=$param['method'];
	/**
	 * Get the URN for the methods in the controller
	 * Validation of the methods used by the controller
	 */
	$this->getURL();
	if ($this->param_const['debug_param_diff']){
	/**
	 * For debug
	 */
		unset($this->result['param'][$this->param['method']]['param_out']);
		if (empty($this->result['param'][$this->param['method']])){
		/**
		 * If the array is empty.
		 */
			unset($this->result['param'][$this->param['method']]);
			if (empty($this->result['param'])){
			/**
			 * If the array is empty.
			 */
				unset($this->result['param']);
			}
		}
	}
	if ($this->param_const['param_out']){
	/**
	 * When you need to pass parameters to another controller.
	 * For use in model view.
	 * $this->param_const=array(
	 *  'param_out'=>true
	 * )
	 * 
	 */
		$this->result['param_out']=$this->param;
	}
	return $this->result;
}

/**
 * Get data from the SEF string.
 *
 * SEF mode can be of two types.
 *  
 * 1. When the SEF for the controller.
 * << /controller/method/param=value/param=value/
 * 
 * /controllers/ControllersExample.php
 *	$this->controller = array(
 *		'inURLSEF' => true
 *	)
 *
 * 2. When the SEF method:
 * << /controller/method/62-User.html 
 * 
 * /controllers/ControllersExample.php
 *	$this->controller = array(
 *		'inURLSEF' => false,
 *		'public_methods' => array(
 *			'method' => array(
 *				'inURLSEF' => array(
 *					'user_id' => '','User','.' => '.html',
 *				)
 *			)
 *		)
 *	)
 * @see Controllers.controller
 * 
 * @param string $sef 
 * SEF string.
 * 
 * Get from:
 * 
 * /.htaccess
 *	<IfModule mod_rewrite.c>
 *		RewriteEngine On
 *		RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
 *	 </IfModule>	
 *
 * /index.php
 *	$result = $evnine->getControllerForParam(
 *		$param = array(
 *			,'ajax' => $_REQUEST['ajax'],
 *			,'sef' => $_REQUEST['sef'],
 *		)
 *	); 
 *
 * @access public
 * @return void
 */
function getURNbySEF($sef){
	$split = split('/',$sef);
	$split_count = count($split);
	$param['REQUEST']=array();
	$param['controller']=$split['0'];
	if ($split[$split_count-1]==='index'){
		/**
		 * If the SEF mode for the controller, the ending URN - index.html
		 */
		for ( $i = 1; $i < $split_count-1; $i++ ) {
			$form_data_split = split('=',$split[$i]);
			$is_array= false;
			$lenght=strlen($form_data_split[0]);
			if ($form_data_split[0][$lenght-2]==='['&&$form_data_split[0][$lenght-1]===']'
			){
				$form_data_split[0] = substr($form_data_split[0],0,$lenght-2);
				$is_array= true;
			}
			if (empty($param['method'])&&empty($form_data_split[1])){
				$param['method']= $form_data_split[0];
			}else {
				if ($is_array){
					$param['REQUEST'][$form_data_split[0]][]=$form_data_split[1];
				}else {
					$param['REQUEST'][$form_data_split[0]]=$form_data_split[1];
				}
			}
		}
	}else {
		/**
		 * If the SEF mode for the method.
		 * /62-user.html
		 */
		if ($split_count==3){
		/**
		 * The case where there is a controller and method.
		 */
			$param['method']=$split['1'];
			$split_form_data = split('-',$split['2']);
		}elseif($split_count==2) {
		/**
		 * The case where the method is not specified.
		 */
			$split_form_data = split('-',$split['1']);
			$param['method']='default';
		}
		$split_count=count($split_form_data);
		/**
		 * Load the controller. To parse the variable SEF.
		 */
		$this->setLoadController($param['controller']);
		if (!empty($this->current_controller['public_methods'][$param['method']]['inURLSEF'])){
		/**
		 * If there is data to parse the SEF 
		 * In the method of the controller.
		 */
			$i=0;
			foreach ($this->current_controller['public_methods'][$param['method']]['inURLSEF'] as $_title =>$_value)if ($_title!=='.'){
				if ($_title==='date'){
					/**
					 * The date in SEF.
					 * 1901-11-10, 10-11-2014  
					 */
					$param['REQUEST'][$_title] = $split_form_data[$i].'-'.$split_form_data[$i+1].'-'.$split_form_data[$i+2];
					$i+=3;
				}else {
					$param['REQUEST'][$_title] = $split_form_data[$i];
					$i++;
				}
			}
		}
	}
	return $param;
}

/**
 * 
 * Get the URN for the methods in the controller.
 * Validation of the methods used by the controller.
 * 
 * /controllers/ControllersExample.php
 *	$this->controller = array(
 *		'inURLSEF' => false,
 *		// SEF mode in the controller.
 *		// If the SEF mode for the controller, the ending URN - index.html
 *		// Default: false
 *		// >> 'inURLSEF'=> true
 *		// << /controller/method/param=value/param=value/
 *	),
 *	'inURLMethod' => array(
 *		// In the array are methods for which to create the URN.
 *		'default',
 *	),
 *	'public_methods' => array(
 *		'default' => array(
 *			// URNs are constructed on the basis of the validation parameters 
 *			// allowed in the method.
 *			// By default, data is taken from the default method.
 *			'validation'=> array()
 *		)
 *		'method' => array(	
 *			// Further validation for the method.
 *			// It shall contain URNs.
 *			// These URNs used to generate the parameters.
 *			'validation_add'=>array(
 *				'date' => array('inURL' => true,'to'=>'Date','type'=>'str','required'=>'false','max' => '10',),
 *			),
 *		)
 *		'ext_method_and_controller' => array(
 *			// If you want to specify an external controller in the URN.
 *			// And the method in an external controller.
 *			'inURLExtController' => 'ext_controller', 
 *			'inURLExtMethod' => 'ext_method',
 *		)
 *		'sef_method' => array(
 *			// If you want to use the SEF in the method.
 *			// << /controller/method/62-User.html 
 *			'inURLSEF' => array(
 *				'user_id' => '','User','.' => '.html',
 *			)
 *		)
 *	)
 *
 * /controllers/ControllersExample.php
 *
 * 
 * @see Controllers.controller
 * @param boolean $seo 
 * Flag of the SEF mode. 
 * 
 * @see EvnineController.getControllerForParam
 * @access public
 * @return void
 */
function getURL($seo=false) {
	if ($this->current_controller['inURLSEF']){
	/**
	 * The case when the SEF for the controller.
	 */
		$seo= true;
	}
	$default = $this->getURLFromArray(
	/**
	 * Get a URN from an array of validation.
	 */
		$this->current_controller['public_methods']['default']['validation'],$seo
	);
	/**
	 * Create a basic part of the URN.
	 * In stating the controller and method.
	 */
	$urn_base= $this->getControllerURN($this->param['controller'],$seo);
	//
	if ($seo){
	/**
	 * If the mode for the SEF controller.
	 * Set the postfix string end of each URN.
	 * TODO user for the param['seo_end_url']
	 */
		if (empty($this->param['sef_url'])){
				$postfix='/index.html';
			}else {
				$postfix=$this->param['sef_url'];
			}
	}else {
	/**
	 * Do not use postfix URN.
	 */
		$postfix='';
	}
	/**
	 * SEF mode flag for the method.
	 */
	$seo_flag_save='';
	if (!empty($this->current_controller['public_methods']['default']['inURLMethod'])/*&&$this->param['ajax']==false*/){
	/**
	 * If the default method is an array of methods for reference.
	 * 'inURLMethod' => array(
	 * //en: Array to generate the URN (URI) to the method
	 *  'default',
	 * )
	 */
		if (!is_array($this->current_controller['public_methods']['default']['inURLMethod'])){
		$this->current_controller['public_methods']['default']['inURLMethod']=array($this->current_controller['public_methods']['default']['inURLMethod']);
		}
		$url_method = $this->current_controller['public_methods']['default']['inURLMethod'];
		if(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
		/**
		 * If there is a current method for adding an array.
		 * Merge arrays default method and the method to add.
		 * 'default' => array(
		 *  'inURLMethod' => array(
		 *   'default'
		 *  )
		 * ),
		 * 'current_method' => array(
		 *  'inURLMethod_add' => array(
		 *   'current_method'
		 *  )
		 * )
		 */
			$url_method = array_merge(
				$this->current_controller['public_methods']['default']['inURLMethod'],
				$this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add']
			);
		}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod'])){
		/**
		 * The case when the current method, you specify a new array of methods to generate links.
		 * Replace the array of methods to generate.
		 * 'default' => array(
		 *  'inURLMethod' => array(
		 *   'default'
		 *  )
		 * ),
		 * 'current_method' => array(
		 *  'inURLMethod' => array(
		 *   'default',
		 *   'current_method'
		 *  )
		 * )
		 */
			$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod'];
		}
	}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
	/**
	 * The case when is not specified, the default method.
	 * We use an array of methods specified in the current method for add.
	 * 'default' => array(),
	 * 'current_method' => array(
	 *  'inURLMethod_add' => array(
	 *   'current_method'
	 *  )
	 * )
	 */
		$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'];
	}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod'])){
	/**
	 * In the case when the specified array of methods to overwrite.
	 * 'default' => array(),
	 * 'current_method' => array(
	 *  'inURLMethod' => array(
	 *   'current_method'
	 *  )
	 * )
	 */
		$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod'];
	}
	$count = count($url_method);
	for ( $i = 0; $i < $count; $i++ ) {
	/**
	 * For all methods, which create a URN.
	 */
			$method = $url_method[$i];
			if (
				$this->current_controller['public_methods'][$method]['access']['default_access_level']
				>$this->param['PermissionLevel']
				||
				empty($this->current_controller['public_methods'][$method])
			){
			/**
			 * Skipping processing of the case:
			 * When there is no access to the method.
			 * When this method is not specified.
			 */
				continue;
			}
		if (!empty($this->current_controller['public_methods'][$method]['inURLSEF'])){
			/**
			 * If there is a SEF for the method. Work in the SEO mode.
			 * 'current_method' => array(
			 *  'inURLSEF' => array(
			 *   'user_id' => '','User','.' => '.html',
			 *  ),
			 *  'inURLMethod' => array(
			 *   'current_method'
			 *  )
			 * )
			 */
			$seo_flag_save= $seo;
			$seo=$this->current_controller['public_methods'][$method]['inURLSEF'];
		}
			/**
			 * Proceed to generate a URN to the parameters of validation.
			 */
		if (!empty($this->current_controller['public_methods'][$method]['validation_add'])){
			/**
			 * When validating the method with the addition of validation by default.
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation_add' => array(),
			 * )
			 */
			$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation_add'],$seo);
				if($seo&&$seo!==true){
				/**
				 * If there is a SEF for the method.
				 * And SEO mode is not for the controller.
				 * 
				 * Generate a link to the controller and method.
				 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->param['controller'],$seo)
						.$this->getMethodURN($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				} else {
				/**
				 * If the standard mode of generating URN.
				 */
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURN($method,$seo,true).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation'])) {
			/**
			 * When validation of the method overwrites the entire validation.
			 * 
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation' => array(),
			 * )
			 */
			if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])) {
				/**
				 * Case when a URN to specify a different controller.
				 * 
				 * 'current_method' => array(
				 *  'inURLExtController' => 'other_controller',
				 *  'inURLMethod' => array('current_method'),
				 *  'validation' => array(),
				 *  )
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
				 * Generate a link to an external controller and method.
				 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
			 * If not specified an external controller.
			 */
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
				/**
				 * If there is a SEF for the method.
				 * And SEO mode is not for the controller.
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
				 * Generate a link to the controller and method.
				 */
					$this->result['inURL'][$method]['pre']=
						 $this->getControllerURN($this->param['controller'],$seo)
						.$this->getMethodURN($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
					/**
				 * If the standard mode of generating URN.
				 */
					if ($method!=='default'){
					/**
					 * If the method is not by default, we use to generate the validation.
					 * 
					 * 'current_method' => array(
					 *  'inURLMethod' => array('current_method'),
					 *  'validation' => array(),
					 * )
					 */
						$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					}
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURN($method,$seo).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_form'])) {
			/**
			 * When data are needed for the form, not by URN. Used validation form.
			 * 
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation_form' => array(),
			 * )
			 */
				$this->result['inURL'][$method]=
					$this->getInputsFromArray($this->current_controller['public_methods'][$method]['validation_form']);
				if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])) {
				/**
				 * Case when a URN to specify a different controller.
				 * 
				 * 'current_method' => array(
				 *  'inURLExtController' => 'other_controller',
				 *  'inURLMethod' => array('current_method'),
				 *  'validation_form' => array(),
				 *  )
				 */
					$this->result['inURL'][$method]['inputs']=
					$this->getInputFormText('c',$this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
					.$this->getInputFormText('m',$this->current_controller['public_methods'][$method]['inURLExtMethod'])
					.$this->result['inURL'][$method]['inputs'];
					/**
					 * Generate a link to the controller and method.
					 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
							.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			} else {
				/**
				 * If not specified an external controller.
				 */
				$this->result['inURL'][$method]['inputs']=
					$this->getInputFormText('c',$this->param['controller'])
					.$this->getInputFormText('m',$method)
					.$this->result['inURL'][$method]['inputs'];
				$this->result['inURL'][$method]['pre']=
					$urn_base;
				$this->result['inURL'][$method]['pre']=
					$urn_base
					.$this->getMethodURN($method,$seo)
					.$default['pre'];
			}
			$this->result['inURL'][$method]['post']=$postfix;
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_multi_form'])) {
			/**
			 * When data are needed for multiple methods in one form, not by reference.
			 * Used multiple forms of validation.
			 * 
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation_multi_form' => array(),
			 * )
			 */
			$this->result['inURL'][$method]=
				$this->getInputsFromArray($this->current_controller['public_methods'][$method]['validation_multi_form'],$method);
			$this->result['inURL'][$method]['submit']='submit['.$method.']';		
			if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])){ 
				/**
				 * Case when a URN to specify a different controller.
				 *
				 * Generate a input to the controller and method.
				 * 
				 * 'current_method' => array(
				 *  'inURLExtController' => 'other_controller',
				 *  'inURLMethod' => array('current_method'),
				 *  'validation_form' => array(),
				 *  )
				 */
					$this->result['inURL'][$method]['inputs']=
						$this->getInputFormText('c',$this->current_controller['public_methods'][$method]['inURLExtController'],$method)
						.$this->getInputFormText('m',$this->current_controller['public_methods'][$method]['inURLExtMethod'],$method)
						.$this->result['inURL'][$method]['inputs'];
					/**
					 * Generate a link to the controller and method.
					 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
					$this->result['inURL'][$method]['post']=$postfix;
				}else{
				/**
				 * If not specified an external controller.
				 * 
				 * 'current_method' => array(
				 *  'inURLMethod' => array('current_method'),
				 *  'validation_form' => array(),
				 *  )
				 */
					$this->result['inURL'][$method]['inputs']=
					$this->result['inURL'][$method]['inputs'];
				}
		} else {
			/**
			 * The case where no data are for the generation of validation links.
			 * Use the validation of the method by default.
			 *
			 * 'default' => array(
			 *  'validation' => array(),
			 * ),
			 *  
			 * 'current_method' => array(
			 *  'validation'=NULL,
			 *  'validation_add'=NULL,
			 *  'inURLMethod' => array('current_method'),
			 *  )
			 */
			if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])) {
			/**
			 * Case when a URN to specify a different controller.
			 * 
			 * 'default' => array(
			 *  'validation' => array(),
			 * ),
			 * 'current_method' => array(
			 *  'inURLExtController' => 'other_controller',
			 *  'validation'=NULL,
			 *  'validation_add'=NULL,
			 *  'inURLMethod' => array('current_method'),
			 *  )
			 *  
			 * Generate a link to the controller and method.
			 */
				$this->result['inURL'][$method]['pre']=
					$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
					.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
			 * If not specified an external controller.
			 * 
			 * 'default' => array(
			 *  'validation' => array(),
			 * ),
			 * 'current_method' => array(
			 *  'inURLExtController' => NULL,
			 *  'validation'=NULL,
			 *  'validation_add'=NULL,
			 *  'inURLMethod' => array('current_method'),
			 *  )
			 */
			if($seo&&$seo!==true){
				/**
				 * If there is a SEF for the method.
				 * And SEO mode is not for the controller.
				 * 
				 * Generate a link to the controller and method.
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					$this->result['inURL'][$method]['pre']=
					$this->getControllerURN($this->param['controller'],$seo)
					.$this->getMethodURN($method,$seo)
					.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
				/**
				 * If the standard mode of generating URN.
				 */
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURN($method,$seo).$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}
		if (count($this->result['inURL'][$method]['replace'])>0){
		/**
		 * If the URN has a default of the parameters.
		 * Replace the current method's parameters.
		 * 
		 * 'default' => array(
		 *  'inURLMethod' => array('current_method'),
		 *  'validation' => array('path_to' => array(...),),
		 * ),
		 * 'current_method' => array(
		 *  'validation_add' => array('path_to' => array(...),),
		 *  'inURLMethod' => array('current_method'),
		 * )
		 */
			foreach ($this->result['inURL'][$method]['replace'] as $_title =>$_value)
			{
				$this->result['inURL'][$method]['pre']= str_replace($_value,'',$this->result['inURL'][$method]['pre']);
			}
			unset($this->result['inURL'][$method]['replace']);
		}
		if($seo&&$seo!==true){
		/**
		 * If there is a SEF for the method.
		 * And SEO mode is not for the controller.
		 * 
		 * Reset SEO flag.
		 */
			$seo=$seo_flag_save;
			$seo_flag_save= '';
		}
	}
	$this->getURNTemplate();
}

/**
 * 
 * Constant references to the template on different methods.
 * 
 * Depending on the method, we get access
 * in the same way to the URN of different methods.
 * 
 * /controllers/ControllersExample.php
 *	'inURLTemplate' => array(
 *		'info' => 'info_method',
 *	),
 * >> echo $result[inURLTemplate][info][pre] 
 * << ?m=info_method
 * 
 *	'inURLTemplate' => array(
 *		'info' => 'error_method',
 *	),
 * >> echo $result[inURLTemplate][info][pre] 
 * << ?m=error_method
 * 
 * PHP:  $result[inURLTemplate][info][pre] $result[inURLTemplate][info][post]
 * TWIG: {{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}
 *
 * @see Controllers.controller
 * @access public
 * @return void
 */
function getURNTemplate(){
	if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'])){
	/**
	 * If you have an array of URN variables in the current method.
	 * 
	 * 'info_method' => array(
	 *  'inURLTemplate' => array(
	 *    'info' => 'default'
	 *   )
	 * )
	 */
		foreach ($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'] as $button =>$method){
			$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
		}
		if(!empty($this->current_controller['public_methods']['default']['inURLTemplate'])){
		/**
		 * If you have an array of URN variables in the method by default.
		 * 
		 * 'default' => array(
		 *  'inURLTemplate' => array('info' => 'default'),
		 * ),
		 * 
		 * 'info_method' => array(
		 *  'inURLTemplate' => array('info' => 'default')
		 * ),
		 */
			foreach ($this->current_controller['public_methods']['default']['inURLTemplate'] as $button =>$method)
			if (empty($this->result['inURLTemplate'][$button])){
				$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
			}
		}
	}elseif(!empty($this->current_controller['public_methods']['default']['inURLTemplate'])){
	/**
	 * The case when this method there is no variable URN, 
	 * but there is a method by default.
	 * 
	 * 'default' => array(
	 *  'inURLTemplate' => array('info' => 'default'),
	 * ),
	 * 
	 * 'info_method' => array(
	 * ),
	 */
		foreach ($this->current_controller['public_methods']['default']['inURLTemplate'] as $button =>$method){
			$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
		}
	}
}

/**
 * 
 * Get the URN of the controller.
 * 
 * @param string $contoller_name 
 * Controller name
 * @param string $seo 
 * Use SEF?
 * @access public
 * @return string
 */
function getControllerURN($contoller_name,$seo) {
	if ($seo){
		/**
		 * If a general method for SEF controller, use /param=value/
		 */
		$urn_base='/'.$contoller_name;
	} else {
		/**
		 * If the SEF mode is not used.
		 */
		$urn_base='?c='.$contoller_name;
		if ($this->sef_mode){
		/**
		 * Flag of the SEF mode, set in the method $this->getControllerForParam
		 * when pass to the controller to parse the string SEF URN
		 * if (!empty($param['sef'])) {$this->sef_mode=true;}
		 * TODO ADD name from config
		 */
			$urn_base='/index.php'.$urn_base;
		}
	}
	return $urn_base;
}

/**
 * Get the URN of the method name.
 * 
 * @param string $contoller_name 
 * Method name.
 * @param string $seo 
 * Use SEF?
 * @access public
 * @return string
 */
function getMethodURN($method,$seo) {
	if (empty($method)){
	/**
	 * If the method is not specified, the method will be used by default.
	 */
		return '';
	}else {
		if ($seo){
		/**
		 * If a general method for SEF controller, use /param=value/
		 */
			$urn_base.='/'.$method;
		}else {
		/**
		 * If the SEF mode is not used.
		 */
			$urn_base.='&m='.$method;
		}
	}
	return $urn_base;
}

/**
 * 
 * Get to the form input from string.
 * 
 * @param string $name 
 * Parameter name in form. 
 * 
 * @param string $value 
 * The value of the form.
 * 
 * @param string $multi_form 
 * Name for the form, if you use the multiple form.
 * 
 * @access public
 * @return string
 */
function getInputFormText($name,$value,$multi_form=false){
	if (empty($value)) {
	/**
	 * If the value of a form not specified stop processing.
	 */
		return;
	}else {
		if ($multi_form){
		/**
		 * Name for the form, if you use the multiple form.
		 */
			$name=$multi_form.'['.$name.']';
		}
		return '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';
	}
}

/**
 *
 * Get to the form input from validation array.
 * 
 * >> < ?=$result[inURL][ext_search][inputs]? >
 * << <input name="last_search" type="hidden" value="inURLFalse" />
 *
 * /controller/ControllersExample.php
 *	'public_methods' => array(
 *		'ext_search' => array(
 *			'inURLMethod' => array('ext_search'),
 *			'validation_form'=> array(
 *				'search' => array(
 *					'to'=>'SearchTitle',
 *					'inURL' => true,
 *					'type'=>'str',
 *					'required'=>'true',
 *					'error'=>'search',
 *					'min'=>'3',
 *					'max' => '250'
 *				),
 *				'last_search' => array(
 *					'to'=>'LastSearch',
 *					'inURL' => false,
 *				)
 *			)
 *		)
 *	)
 * 
 * PHP:
 * <form action="< ?=$result[inURL][ext_search][pre].$result[inURL][ext_search][post]? >" method="get"> 
 *  < ?=$result[inURL][ext_search][inputs]? >
 *  <input name="< ?=$result[inURL][ext_search][SearchTitle]? >" value="< ?=$result[param_out][SearchTitle]? >" type="text" />
 *  <input name="< ?=$result[inURL][ext_search][submit]? >" type="submit" value=" "/>
 * </form>
 *
 * TWIG:
 * <form action="{{ inURL.ext_search.pre }}{{ inURL.ext_search.post }}" method="get"> 
 *  {{ inURL.ext_search.inputs }}
 *  <input id="search_ajax" value="{{ param_out.SearchTitle }}" name="{{ inURL.ext_search.SearchTitle }}" type="text" />
 *  <input name="{{ inURL.ext_search.submit }}" type="submit" value=" "/>
 * </form>
 *
 * @param array $validate 
 * Array with the parameters of the form.
 * 
 * @param string $multi_form
 * Name for the form, if you use the multiple form.
 * <input name="multi_form_method[last_search]" type="hidden" value="inURLFalse" />
 * 
 * @see Controllers.controller
 * @access public
 * @return array
 *
 */
function getInputsFromArray($validate,$multi_form=false) {
	$inputs='';
	$array_out=array();
	$pre_fix=$post_fix= '';
	foreach ($validate as $_title =>$_value){
		$REQUEST_OUT=$this->result['REQUEST_OUT'][$_value['to']];
		if ($_value['inURL']){
		/**
		 * When specified as an input (user or template) parameter.
		 * 
		 * PHP:
		 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 1< ?=$result[inURL][ext_search][post]? >">test 1</a>
		 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
		 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 2< ?=$result[inURL][ext_search][post]? >">test 2</a>
		 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
		 *
		 * TWIG:
		 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 1{{ inURL.ext_search.post }}">test 1</a>
		 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
		 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 2{{ inURL.ext_search.post }}">test 2</a>
		 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
		 *
		 * 'public_methods' => array(
		 *  'ext_search' => array(
		 *   'inURLMethod' => array('ext_search'),
		 *   'validation_form'=> array(
		 *     'search' => array(
		 *       ...
		 *       'to'=>'oldSearch',
		 *       'inURL' => true,
		 *       ...
		 *      )
		 *   )
		 * )
		 */
			if ($_value['is_array']){
			/**
			 * If the parameter is an array. &param[]=1&param[]=2
			 */
				$_title.='[]';
			}
			$array_out[$_value['to']]=$_title;
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				/**
				 * If the parameter is the output there.
				 * 
				 * Create an array of options for replacing a URN by default.
				 */
				if ($seo===true){
				/**
				 * If a general method for SEF controller, use /param=value/
				 */
					$array_out['replace'][$_value['to']]='/'.$_title.'='.$REQUEST_OUT;
				}elseif($seo&&$seo!==true){
				/**
				 * If there is a SEF for the method.
				 * And SEO mode is not for the controller.
				 */
					$array_out['replace'][$_value['to']]=$REQUEST_OUT;
				}else {
				/**
				 * If the standard mode of generating URN.
				 */
					$array_out['replace'][$_value['to']]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
			/**
			 * If not specified in the template as an input parameter.
			 * 
			 * 'public_methods' => array(
			 *  'ext_search' => array(
			 *   'inURLMethod' => array('ext_search'),
			 *   'validation_form'=> array(
			 *     'search' => array(
			 *       ...
			 *       'inURL' => false,
			 *       ...
			 *      )
			 *   )
			 * )
			 */
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				/**
				 * If the parameter is the output there.
				 */
					//TODO check without multi_form=true if ($multi_form&&$_value['multi_form']){
				if ($multi_form){
				/**
				 * If you use the multiple form.
				 */
					$pre_fix=$multi_form.'[';$post_fix= ']';
				}
				if ($_value['is_array']){
				/**
				 * If the parameter is an array.
				 * 
				 * <input name="controller[method][]"/>
				 */
					foreach ($REQUEST_OUT as $REQUEST_OUT_title =>$REQUEST_OUT_value){
						$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'[]" value="'.$REQUEST_OUT_value.'"/>';
					}
				}else {
					/**
					 * If it is not an array.
					 * <input name="controller[method]"/>
					 */
					$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'" value="'.$this->result['REQUEST_OUT'][$_value['to']].'"/>';
				}
				//TODO check without multi_form=true if ($multi_form&&$_value['multi_form']){
				if ($multi_form){
				/**
				 * If you use the multiple form.
				 */
					$pre_fix=$post_fix= '';
				}
			}
		}
	}
	$array_out['inputs']= $inputs;
	return $array_out;
}

/**
 * 
 * Get a URN from an array of validation.
 * 
 * @param array $validate 
 * Array with the parameters of the form.
 *
 * @param boolean $seo 
 * Use SEF?
 * 
 * @param boolean $is_add 
 * Parameter added to the current URN?
 * >> $is_add = false
 * << ?param=value
 * 
 * >> $is_add = true
 * << &param=value
 * 
 * @access public
 * @return void
 */
function getURLFromArray($validate,$seo=false,$is_add=false) {
	if($seo&&$seo!==true){
	/**
	 * If there is a SEF for the method.
	 * And SEO mode is not for the controller.
	 */
		foreach ($seo as $seo_title =>$seo_value) if ($seo_title!=='.'){
			/**
			 * For each element of the SEO, do if the key is not the point.
			 * 
			 * 'current_method' => array(
			 *  'inURLSEF' => array(
			 *   'user_id' => '','User','.' => '.html',
			 *  ),
			 * )
			 */
			if ($validate[$seo_title]['inURL']){
				/**
				 * When specified as an input (user or template) parameter.
				 * 
				 * PHP:
				 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 1< ?=$result[inURL][ext_search][post]? >">test 1</a>
				 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
				 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 2< ?=$result[inURL][ext_search][post]? >">test 2</a>
				 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
				 *
				 * TWIG:
				 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 1{{ inURL.ext_search.post }}">test 1</a>
				 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
				 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 2{{ inURL.ext_search.post }}">test 2</a>
				 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
				 * 
				 * 'public_methods' => array(
				 *  'ext_search' => array(
				 *   'inURLMethod' => array('ext_search'),
				 *   'validation'=> array(
				 *     'search' => array(
				 *       ...
				 *       'to'=>'oldSearch',
				 *       'inURL' => true,
				 *       ...
				 *      )
				 *   )
				 * ) 
				 */
				$array_out[$validate[$seo_title]['to']]='-';
				if (!empty($this->result['REQUEST_OUT'][$validate[$seo_title]['to']])){
				/**
				 * If the URN has a default of the parameters.
				 * Replace the current method's parameters.
				 * 
				 * 'default' => array(
				 *  'inURLMethod' => array('current_method'),
				 *  'validation' => array('path_to' => array(...),),
				 * ),
				 * 'current_method' => array(
				 *  'validation_add' => array('path_to' => array(...),),
				 *  'inURLMethod' => array('current_method'),
				 * ) 
				 */
					$array_out['replace'][$validate[$seo_title]['to']]='-'.$this->result['REQUEST_OUT'][$validate[$seo_title]['to']];
				}
			}else {
			/**
			 * If not specified in the template as an input parameter.
			 * 
			 * 'public_methods' => array(
			 *  'ext_search' => array(
			 *   'inURLMethod' => array('ext_search'),
			 *   'validation'=> array(
			 *     'search' => array(
			 *       ...
			 *       'inURL' => false,
			 *       ...
			 *      )
			 *   )
			 * )
			 */
				if (empty($array_out['pre'])) {
				/**
				 * For the initial formation of the URN.
				 * If the value is empty. Use the slash.
				 */
					$array_out['pre'].= '/';
				}else {
				/**
				 * In all other cases.
				 */
					$array_out['pre'].= '-';
				}
				if (!empty($seo_value)){
					/**
					 * If the value for the SEF is not empty, use it.
					 * 
					 * 'current_method' => array(
					 *  'inURLSEF' => array(
					 *    'user_id' => '','User','.' => '.html',
					 *  ),
					 * )
					 * 
					 * $array_out['pre'].= 'User';
					 */
						$array_out['pre'].= $seo_value;
				} else {
				/**
				 * Or use the value of the parameters output from the controller.
				 */
					$array_out['pre'].=$this->result['REQUEST_OUT'][$validate[$seo_title]['to']];
				}
			}
		}
		if (empty($array_out['pre'])) {
			/**
			 * For the initial formation of the URN.
			 * If the value is empty. Use the slash.
			 */
			$array_out['pre'].= '/';
		}
		return $array_out;  
	}else {
		/**
		 * If the method does not use SEF.
		 * 
		 * 'current_method' => array(
		 *  'inURLSEF' => NULL,
		 *   'validation'=> array(
		 *   'search' => array(...) 
		 *   )
		 * )
		 */
		foreach ($validate as $_title =>$_value){
			$REQUEST_OUT = $this->result['REQUEST_OUT'][$_value['to']];
			if (!empty($REQUEST_OUT)||$_value['inURL']){
				/**
				 * When specified as an input (user or template) parameter.
				 * Or is there a setting on the output of the controller.
				 * 
				 * PHP:
				 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 1< ?=$result[inURL][ext_search][post]? >">test 1</a>
				 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
				 * <a href="< ?=$result[inURL][ext_search][pre]? >< ?=$result[inURL][ext_search][oldSearch]? >test 2< ?=$result[inURL][ext_search][post]? >">test 2</a>
				 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
				 *
				 * TWIG:
				 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 1{{ inURL.ext_search.post }}">test 1</a>
				 * >> <a href="?c=default&m=ext_search&search=test1">test 1</a>
				 * <a href="{{ inURL.ext_search.pre }}{{ inURL.ext_search.oldSearch }}test 2{{ inURL.ext_search.post }}">test 2</a>
				 * >> <a href="?c=default&m=ext_search&search=test2">test 2</a>
				 * 
				 * 'public_methods' => array(
				 *  'ext_search' => array(
				 *   'inURLMethod' => array('ext_search'),
				 *   'validation'=> array(
				 *     'search' => array(
				 *       ...
				 *       'to'=>'oldSearch',
				 *       'inURL' => true,
				 *       ...
				 *      )
				 *   )
				 * ) 
				 */
				if ($_value['is_array']){
				/**
				 * If the parameter is an array. &param[]=1&param[]=2
				 */
					$save_key= '';
					$param_count = count($REQUEST_OUT);
					$_title=$_title.'[]';
					if ($param_count<=1){
					/**
					 * If there are no parameters on the output or just one.
					 * Get the URN part for the name and parameter value.
					 */
						$this->getURNForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[0],$seo);
						$key = $this->getFirstArrayKey($array_out['replace']);
						$save_key.= $array_out['replace'][$key];
					}else {
					/**
					 * If the parameters of the output is more than one.
					 */
						for ( $i = 0; $i < $param_count; $i++ ) {
							$this->getURNForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[$i],$seo);
							if ($_value["inURLSave"]){
								/**
								 * If you want to save the settings in multi-forms. Default is false.
								 * An example of when you want to save the settings from the last load.
								 *
								 * $result[REQUEST_OUT][PathID]=1;
								 * 
								 * PHP: 
								 * $result[inURL][default][pre].$result[inURL][default][PathID].'new_param'.$result[inURL][default][post]
								 * TWIG: 
								 * {inURL.default.pre}{inURL.default.PathID}new_param{inURL.default.post}
								 * 
								 * >> &test_id[]=1&test_id[]=new_param
								 * 
								 * controller:
								 * 'public_methods' => array(
								 *  'ext_search' => array(
								 *   'inURLMethod' => array('ext_search'),
								 *   'validation'=> array(
								 *     'test_id' => array(
								 *       ...
								 *       'to' => 'TestID',
								 *       'inURLSave' => true
								 *       'is_array' => true,
								 *       ...
								 *      )
								 *   )
								 * ) 
								 *
								 * Get the URN part for the name and parameter value.
								 */
								$key = $this->getFirstArrayKey($array_out['replace']);
								$save_key.= $array_out['replace'][$key];
							}
						}
					}
					if ($_value["inURLSave"]){
					/**
					 * When you want to save the settings from the last load.
					 * Remove the options for a replacement.
					 */
						unset($array_out['replace']);
					}
				}else {
				/**
				 * Get the URN part for the name and parameter value.
				 */
					$this->getURNForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT,$seo);
				}
		}
	}
	}
	return $array_out;
}

/**
 * 
 * Get the URN part for the name and parameter value.
 * 
 * /controller/ControllersExample.php
 *	'search' => array(
 *		'to'=>'SearchTitle',
 *		'inURL' => true,
 *		'type'=>'str',
 *		'required'=>'true',
 *		'error'=>'search',
 *		'min'=>'3',
 *		'max' => '250'
 *	),
 *
 * @param array $array_out 
 * Reference to an array of data after processing.
 * 
 * @param boolean $in_url 
 * 
 * For the substitution of references.
 * 
 * >>inURL = true, 
 * >>REQUEST = array(path_id => 777)
 * >>$result[inURL][default][PathID]
 * 
 * << &path_id=
 * 
 * 
 * false - by default
 * 
 * >>inURL = false 
 * >>REQUEST = array(path_id => 777)
 * >>$result[inURL][default][PathID]
 * 
 * << &path_id=777
 * 
 * @param string $to 
 * Stored in the variable $param[REQUEST][path_id]
 * An array of references to URN will be available on a key $result [inURL][PathID]
 *
 * @param string $_title 
 * The key to validation under which to store the variable.
 * 
 * 'search' => array()
 * 
 * @param array $REQUEST_OUT 
 * Parameter from the output to the substitution.
 * 
 * @param boolean|array $seo 
 * Use SEF?
 * 
 * @access public
 * @return void
 */
function getURNForTitleAndValue(&$array_out,$in_url,$to,$_title,$REQUEST_OUT,$seo){
	if ($in_url){
		/**
		 * When specified as an input (user or template) parameter.
		 * 
		 * 'public_methods' => array(
		 *  'ext_search' => array(
		 *   'inURLMethod' => array('ext_search'),
		 *   'validation'=> array(
		 *     'search' => array(
		 *       ...
		 *       'inURL' => true,
		 *       ...
		 *      )
		 *   )
		 * ) 
		 * 
		 */
			if ($seo===true){
			/**
			 * If a general method for SEF controller, use /param=value/
			 */
				$array_out[$to]='/'.$_title.'=';
				if (!empty($REQUEST_OUT)){
					/**
					 * If the URN has a default of the parameters.
					 * Replace the current method's parameters.
					 */
					$array_out['replace'][$to]='/'.$_title.'='.$REQUEST_OUT;
				}
			}else {
				/**
				 * If the standard mode of generating URN.
				 */
				$array_out[$to]='&'.$_title.'=';
				if (!empty($REQUEST_OUT)){
					/**
					 * If the URN has a default of the parameters.
					 * Replace the current method's parameters.
					 */
					$array_out['replace'][$to]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
			/**
			 * If not specified in the template as an input parameter.
			 * 
			 * 'public_methods' => array(
			 *  'ext_search' => array(
			 *   'inURLMethod' => array('ext_search'),
			 *   'validation_form'=> array(
			 *     'search' => array(
			 *       ...
			 *       'inURL' => false,
			 *       ...
			 *      )
			 *   )
			 * )
			 */
				if  ($seo===true){
				/**
				 * If there is a SEF for the method.
				 * And SEO mode is not for the controller.
				 */
					$array_out['pre'].='/'.$_title.'='.$REQUEST_OUT;
				}else {
					/**
					 * If the standard mode of generating URN.
					 */
					$array_out['pre'].='&'.$_title.'='.$REQUEST_OUT;
				}
			}
}

/**
 * Initialize the controller.
 * 
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
 * @see EvnineController.controller_alias
 * @see Controllers.controller_alias
 * 
 * @param string $set_controller
 * Aliases names controllers.
 * @access public
 * @return void
 */
function setLoadController($set_controller) {
	if ($this->current_controller_name===$set_controller&&!empty($set_controller)){
	/**
	 * If the controller has been initialized and is now used.
	 */
		return;
	}
	if (empty($set_controller)||
		empty($this->controller_alias[$set_controller])
	){
		/**
		 * If the controller is not specified /evnine.config.php.
		 * Use the controller specified by default.
		 * $this->param_const['default_controller']
		 * 
		 * /evnine.config.php:
		 * $this->param_const=array(
		 *  'default_controller'=>'default_controller',
		 * );
		 * $this->controller_alias=array(
		 *  ''=>'',
		 * );
		 * 
		 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): EvnineController "'.$set_controller. '" not found '.$this->current_controller_name.'';
		$this->param['controller']=$this->current_controller_name = $this->param_const['default_controller'];
	}else {
	/**
	 * If the controller is specified.
	 * /evnine.config.php:
	 * $this->controller_alias=array(
	 *  'controller_shorcut'=>'Controllers',
	 * );
	 */
		$this->current_controller_name = $set_controller;
	}
	if (empty($this->loaded_controller[$set_controller])){
	/**
	 * If the controller is not loaded.
	 */
		if (empty($this->result['LoadController'])){
			/**
			 * Set to answer evnin, which controller is init first.
			 */
			$this->result['LoadController']=$this->current_controller_name;
		}
			$controller_file = $this->path_to.'controllers'.DIRECTORY_SEPARATOR.$this->controller_alias[$this->current_controller_name].'.php';
			if (file_exists($controller_file)){
			/**
			 * If the file exists the controller.
			 */
				$controller = $this->controller_alias[$this->current_controller_name];
			}elseif (is_array($this->controller_alias[$this->current_controller_name])){
			/**
			 * If the controller set a folder.
			 */
				$controller_file = $this->path_to.$this->controller_alias[$this->current_controller_name]['path'].DIRECTORY_SEPARATOR.$this->controller_alias[$this->current_controller_name]['class_name'].'.php';
				if (file_exists($controller_file)){
					/**
					 * If the file exists the controller.
					 */
					$controller = $this->controller_alias[$this->current_controller_name]['class_name'];
				}else {
					/**
					 * If the controller file from array does not exist, set the error.
					 */
					$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): controller file [array case]"'.$controller_file. '" not exist ';
					return;
				}
			}else {
			/**
			 * If the controller file does not exist, set the error.
			 */
				$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): controller file "'.$controller_file. '" not exist ';
				return;
			}
			include_once($controller_file);
			try {
			/**
			 * Try to get the data.
			 */
				$this->loaded_controller[$set_controller] = new $controller($this->access_level);
			} catch (InvalidArgumentException $e){
			/**
			 * If you receive an error, save it in an array of parameters.
			 */
				$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): catch errors in the controller file "'.$controller_file. '"  <b>'.$e->getMessage().'</b>';
			}
			$this->current_controller=$this->loaded_controller[$set_controller]->controller;
		}elseif(!empty($this->loaded_controller[$set_controller])) {
		/**
		 * If the controller has already been loaded, use it as current.
		 */
			$this->current_controller=$this->loaded_controller[$set_controller]->controller;
		}
}

/**
 * 
 * The basic method to get data from the controller with parameters.
 * 
 * /index.php
 *	include_once('evnine.php');
 *	$evnine = new EvnineController();
 *	$result = $evnine->getControllerForParam(
 *		$param = array(
 *			 'controller' => 'controller'
 *			,'method' => 'method'
 *			,'REQUEST' => $_REQUEST
 *			,'ajax' => $_REQUEST['ajax'],
 *			,'sef' => $_REQUEST['sef'],
 *		)
 *	);
 * 
 * @param array $param 
 * Init parameters.
 * 
 * @param boolean $debug 
 * Debug mode.
 *
 * @see EvnineConfig.__construct
 * @see Controllers.controller
 * @access public
 * @return void
 */
function getDataFromController($param,$debug=false) {
	$this->isHasAccessSaveCheck=true;
	$this->param=$this->param_const;
	foreach ($param as $param_title =>$param_value){
		//TODO check the case if (isset($param[$param_title]))
		/**
		 * Add the passed parameters to the parameters of /evnine.config.php
		 * 
		 * /evnine.config.php
		 *  $this->param_const=array(
		 *   'default_controller'=>'default_controller',
		 *   'debug'=>true,
		 *  );
		 */
			$this->param[$param_title]=$param[$param_title];
	}
	$this->debug=$debug;
	$this->setLoadController($param['controller']);
	// TODO check case
	//if (empty($this->result['ajax'])&&empty($this->param['ajax'])){
	//	$this->result['ajax']='False'; 
	//} else { 
	//	$this->result['ajax']='True'; 
	//}
	if (!empty($this->param['REQUEST'])){
	/**
	 * If the specified data input.
	 */
		$this->result['REQUEST_IN']=$this->param['REQUEST'];
		$this->result['REQUEST_OUT']=array();
	}
	if (empty($this->result['inURLView'])){
		if ($this->param['ajax']&&
			!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])
		){
		/**
		 * If you are using AJAX and a template to display the view in the controller.
		 * 
		 * /index.php
		 * $evnine->getControllerForParam(
		 * array(
		 *  'controller' => 'helloworld',
		 *  'ajax' => 'ajax','ajax' => true,
		 *  )
		 * )
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'current_method' => array(
		 *  'inURLView' => 'ajax_template.php',
		 * )
		 * 
		 */
			$this->result['inURLView']=$this->current_controller['public_methods'][$this->param['method']]['inURLView'];
		}elseif (!empty($this->current_controller['inURLView'])){
		/**
		 * In all other cases, use the template specified by default.
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * $this->controller = array(
		 *   'inURLView' => 'templates_example.php',
		 * )
		 */
			$this->result['inURLView']=$this->current_controller['inURLView'];
		}
	}
	if (empty($this->result['Title'])&&!empty($this->current_controller['title'])){
	/**
	 * Case when it is necessary to set <title> </ title> by the controller.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * $this->controller = array(
	 *   'title' => 'Title',
	 * )
	 */
		$this->result['Title']=$this->current_controller['title'];
	}
	if (empty($this->param['method'])){
	/**
	 * If the method of loading is not specified.
	 *
	 * /index.php
	 * include_once('evnine.php');
	 * $evnine = new EvnineController();
	 * $result = $evnine->getControllerForParam(
	 * array(
	 *  'controller' => 'helloworld',
	 *  'method' => '',
	 * )
	 */
		if (isset($this->current_controller['public_methods']['default'])){
		/**
		 * If the default method exists, use it.
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'public_methods' => array(
		 *  'default' => array(),
		 * )
		 */
			$this->param['method']='default';
			$this->getPublicMethod($this->param);
		}
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
	}else {
		/**
		 * If the method of loading is specified.
		 * 
		 * /index.php
		 *	include_once('evnine.php');
		 *	$evnine = new EvnineController();
		 *	$result = $evnine->getControllerForParam(
		 *	array(
		 *		'controller' => 'helloworld',
		 *		'method' => 'hi',
		 *	)
		 */
		if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])){
		/**
		 * If the method specified template.
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'public_methods' => array(
		 *  'hi' => array(
		 *   'inURLView' => 'template_hi.php',
		 *  ),
		 * )
		 */
			$this->result['ViewMethod'][$this->param['method']]=$this->current_controller['public_methods'][$this->param['method']]['inURLView'];
		}elseif ($this->param['method']!=='default'){
		/**
		 * If this method is not the default method.
		 */
			$this->result['ViewMethod'][$this->param['method']] = $this->param['method'];
		}
		$this->getPublicMethod($this->param);
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
		if ($this->param['ajax']===false){
		/**
		 * If the flag is to work through AJAX is not specified.
		 * 
		 * /index.php
		 * include_once('evnine.php');
		 * $evnine = new EvnineController();
		 * $result = $evnine->getControllerForParam(
		 * array(
		 *  'ajax' => false,
		 *  'controller' => 'helloworld',
		 *  'method' => 'hi',
		 * )
		 */
			$this->isHasAccessSaveCheck=true;
			/**
			 * If it works in a sub controller, and method of the parent was denied access.
			 */
			if ($this->current_controller['page_level']!=0
					&&!empty($this->current_controller['parent']))
			{
			/**
			 * Check the depth of the controller, if you specify a parent load it.
			 * 
			 * /evnine.config.php
			 * * $this->controller_alias=array(
			 *  'helloworld'=>'ControllersHelloWorld',
			 *  'helloworld_parent'=>'ControllersHelloWorldParent',
			 * );
			 * 
			 * /controllers/ControllersHelloWorld.php
			 * $this->controller = array(
			 *   'page_level' => '1',
			 *   'parent' => 'helloworld_parent',
			 *   'public_methods' => array(
			 *    'default' => array(),
			 *   ),
			 * )
			 *
			 * /controllers/ControllersHelloWorldParent.php
			 * $this->controller = array(
			 *   'page_level' => '0',
			 *   'parent' => '',
			 *   'this' => 'helloworld_parent',
			 *   'public_methods' => array(
			 *    'default' => array(),
			 *   ),
			 * )
			 * 
			 */
				$parent = $this->current_controller['parent'];
				$this->result['&rArr;'.$parent.':parent-default'] = '&rArr;Parent Method <font color="orange">'.$parent.'::parent-default</font> is load';
				$save_template = $this->param['controller'];
				$save_method  = $this->param['method'];
				$this->param['method']= 'default';
				$this->param['controller']=$this->current_controller['parent'];
				/**
				 * Load controller parent.
				 */
				$this->getDataFromController($this->param,false);
				$this->result['&lArr;'.$parent.':parent-default'] = '&lArr;Parent Method <font color="orange">'.$parent.'::parent-default</font> is unload';
				$this->param['method']= $save_method;
				$this->param['controller']=$save_template;
			}elseif (
				!empty($this->current_controller['public_methods']['default'])
				&&$this->param['method']!=='default'
			){
			/**
			 * If the default method from the child controller is not specified.
			 * 
			 * /controllers/ControllersHelloWorld.php
			 * $this->controller = array(
			 *   'page_level' => '1',
			 *   'parent' => 'helloworld_parent',
			 *   'public_methods' => array(
			 *    'default' => array(),
			 *   ),
			 * )
			 *
			 * /controllers/ControllersHelloWorldParent.php
			 * $this->controller = array(
			 *   'page_level' => '0',
			 *   'parent' => '',
			 *   'this' => 'helloworld_parent',
			 *   'public_methods' => array(
			 *    'default_not_set' => array(),
			 *   ),
			 * )
			 */
				/**
				 * Load the default method in the controller parent.
				 */
				$this->param['method']='default';
				$this->result['&rArr;'.$this->current_controller_name.':default'] = '&rArr;Method <font color="orange"><b>'.$this->current_controller_name.'::default</b></font> is load';
				if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])){
				/**
				 * If the method specified template.
				 * 
				 * /controllers/ControllersHelloWorld.php
				 * 'public_methods' => array(
				 *  'hi' => array(
				 *   'inURLView' => 'template_hi.php',
				 *  ),
				 * )
				 */
					$this->result['ViewMethod'][$this->param['method']]=$this->current_controller['public_methods'][$this->param['method']]['inURLView'];
				}
				$this->getPublicMethod($this->param);
				$this->result['&lArr;'.$this->current_controller_name.':default'] = '&lArr;Method <font color="orange"><b>'.$this->current_controller_name.'::default</b></font> is unload';
			}
			$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
		}
	}
	if (!empty($this->param['REQUEST'])){
	/**
	 * If the specified data input.
	 */
		$this->result['REQUEST_OUT']=$this->param['REQUEST'];		
	}
}


/**
 * 
 * Display the available templates for the access level.
 * 
 * /evnine.config.php
 *	 $this->access_level=array(
 *		'admin'=>'2',
 *		'user'=>'1',
 *		'guest'=>'0',
 *	 );
 *	 
 * /controllers/ControllersHelloWorld.php
 *	'templates' => array(
 *		// Access to the mapping of the template.
 *		// Depends on the users access.
 *		access_level['admin']=>array('menu'=>'admin_menu'),
 *		access_level['user']=>array('menu'=>'user_menu'),
 *		access_level['guest']=>array('menu'=>'guest_menu')
 *	)
 *	
 * >> $access_level='user'
 * << array(
 *		'menu'=>'user_menu'
 *		'menu'=>'guest_menu'
 *	)
 * >> $access_level='guest'
 * << array(
 *		'menu'=>'guest_menu'
 *	)
 *	
 * @see Controllers.controller
 * @param array $available_templ 
 * @access public
 * @return void
 */
function getAvailableTemplates($available_templ) {
	if (count($available_templ)==0){
	/**
	 * If templates are not specified, will stop work.
	 */
		return true;
	}
	if (!isset($this->result['Templates'])){
	/**
	 * To merge the two arrays of templates for key initialize an array.
	 */
		$this->result['Templates']=array();
	}
	for ( $i = 0; $i <= $this->param['PermissionLevel']; $i++ ) {
		if (!empty($available_templ[$i])){
		/**
		 * Check the user level for the template.
		 * Used $param['PermissionLevel'], you can check via the method in the controller.
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'access'=>array(
		 *  'default_access_level' => $access_level['guest'],
		 *  'default_private_methods' => 'isHasAccess',
		 *  'Models::Method'=>array('access_level'=>$access_level['user']),
		 * ),
		 *
		 * 'private_methods' => array(
		 *  'isHasAccess'=>array(
		 *   'controller_auth_check'=>'method_check_access',
		 * ),
		 */
			$this->result['Templates'] = array_merge($this->result['Templates'],$available_templ[$i]);
		}
	}
}

/**
 * Call the methods in the class.
 * 
 * /controllers/ControllersExample.php
 *	'ModelsHelloWorld' => array(
 *		'getHelloWorld1',
 *		'getHelloWorld2'
 *	)
 * 
 * @param string $methods_class 
 * Class to call methods.
 * 'ModelsHelloWorld'=>
 * 
 * @param array $methods_array 
 * An array of methods.
 * =>array(
 *  'getHelloWorld1'
 *  'getHelloWorld2'
 * )
 * 
 * @see Controllers.controller
 * @access public
 * @return void
 */
function getMethodFromClass($methods_class,$methods_array) {
	if (!is_array($methods_array)){
	/**
	 * If the method is not an array. Creates an array for processing.
	 * >>'ModelsHelloWorld' => 'getHelloWorld1',
	 * <<'ModelsHelloWorld' => array('getHelloWorld1'),
	 */
		$methods_array=array($methods_array);
	}
	if (
	/**
	 * Skip the processing of technical information, validate, view, access, etc.
	 */
		($methods_class[9]==='n'&&$methods_class[4]==='d'&&$methods_class[0]==='v')
		/**
		 * Skip the 'validation' 
		 */
		||
		($methods_class[4]==='L'&&$methods_class[2]==='U'&&$methods_class[0]==='i')
		/**
		 * Skip the 'inURL...' 
		 */
		||
		($methods_class[5]==='s'&&$methods_class[3]==='e'&&$methods_class[0]==='a')
		/**
		 * Skip the 'access' 
		 */
	){ 
	/**
	 * Skip the processing of technical information, validate, view, access, etc.
	 */
		return false;
	}
	$methods_class_count = strlen($methods_class);
	if (
		$methods_class[$methods_class_count-6]==='_'
		||                                      
		$methods_class[$methods_class_count-5]==='_'
	){
	/**
	 * Processing possible cases of response methods.
	 * 
	 * class_method_case
	 * 
	 * _case = _false
	 *         654321
	 * The case of negative response to the method.
	 * 
	 * _case = _true
	 *         54321
	 * The case of a positive response to the method.
	 *         
	 * _case = _dont_load
	 *              54321
	 * Blanks from the initialization method in the class.
	 * class_method_dont_load - This means that the method - class method will not load.
	 */
		if (preg_match("/_false$|_true$|_dont_load$/",$methods_class,$tmp)){
			/**
			 * Checking the possible cases.
			 */
				if ($tmp[0]=='_dont_load'){
				/**
				 * In order not to load duplicate methods.
				 * 
				 * /controllers/ControllersNews.php
				 * 'public_methods' => array(
				 *  'user_news' => array(
				 *   'ModelsNewsUsers' => 
				 *    'isGetNewsWhereUserIsAuthor',
				 *    'isGetNewsWhereUserIsAuthor_true' => array(
				 *      'ModelsNewsUsers' => 'getNewsPaginationWhereUserIsAuthor',
				 *      'ModelsNews_getNewsPagination_dont_load'=>'',
				 *     ),
				 *   'ModelsNews'=>'getNewsPagination'
				 *  )
				 * )
				 */
					$array_key = str_replace($tmp[0],'',$methods_class);
					$this->result[$array_key] = 'STOP_LOAD';
				}
				return true;
		}
	}
	if (!isset($this->class_path[$methods_class])){
	/**
	 * If the method does not exist.
	 * Trying to determine what the case.
	 * This is in reference to themselves or a reference to an external controller.
	 * 
	 */
		if ($methods_class==='this'){
		/**
		 * When a reference to the current controller.
		 *
		 * /controllers/ControllersHelloWorld.php
		 * 'public_methods' => array(
		 *  'default' => array(
		 *    'this' => 'helloworld',
		 *   )
		 *   'helloworld' => array(
		 *   )
		 */
			$methods_class= $this->param['controller'];
		}
		if (isset($this->controller_alias[$methods_class])){
		/**
		 * If the method exists in the list of aliases controllers.
		 * The case when a reference to an external controller.
		 * 
		 * /evnine.config.php
		 * $this->controller_alias=array(
		 *  'helloworld'=>'ControllersHelloWorld',
		 *  'helloworld_parent'=>'ControllersHelloWorldParent',
		 * );
		 */
			$is_save_validation_param= false;
			$save_param['controller']= $this->param['controller'];
			$save_param['ajax']= $this->param['ajax'];
			$save_param['method']= $this->param['method'];
			$save_controller = $this->current_controller;
			$this->param['controller']=$methods_class;
			$this->param['ajax']=true;
			$this->param['method']=$this->getFirstArrayKey($methods_array,'first_value');
			$this->result['&rArr;'.$methods_class.':'.$this->param['method']] = '&rArr;Extend Method <font color="orange">'.$methods_class.'::'.$this->param['method'].'</font> is load';
			$this->getDataFromController($this->param,false);
			/**
			 * Load the controller with parameters.
			 */
			$this->result['&lArr;'.$methods_class.':'.$this->param['method']] = '&lArr;Extend Method <font color="orange">'.$methods_class.'::'.$this->param['method'].'</font> is unload';
			$this->current_controller_name = $this->param['controller']=$save_param['controller'];
			$this->param['ajax']=$save_param['ajax'];
			$this->param['method']=$save_param['method'];
			$this->current_controller=$save_controller;
			return true;
		}else {
		/**
		 * If the method not exists in the list of aliases controllers. Display the error.
		 */
				if (
					$methods_class['0']!=='M'&&
					$methods_class['1']!=='o'&&
					$methods_class['4']!=='l'
				){
				/**
				 * Exclude the case with the model.
				 * Models
				 * 012345
				 */
					$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):Extend controller not exist '.$methods_class.'';
				}
		}
		if ($methods_class['0']==='M'&&
				$methods_class['3']==='e'
		){
			/**
			 * The case when the model in the configuration file is not specified.
			 * Models
			 * 012345
			 */
				$this->isSetClassToLoadAndSetParam($methods_class,false);
		} else {
			$methods_class=$this->getFirstArrayKey($methods_array);
			if (count($methods_array[$methods_class])>1){
				/**
				 * If more than one method, reduces by one level.
				 * 
				 * /controllers/ControllersHelloWorld.php
				 * >>'ModelsHelloWorld_isHello_true'=>array(
				 *    'ModelsHelloWorld' => array(
				 *     'getHelloWorld1',
				 *     'getHelloWorld2'
				 *    )
				 *   )
				 * 
				 * <<'ModelsHelloWorld' => array(
				 *     'getHelloWorld1',
				 *     'getHelloWorld2'
				 *    )
				 */
					$methods_array=$methods_array[$methods_class];
			}
		}
	}
	if (empty($this->loaded_class[$methods_class])){
		/**
		 * The class is not initialized.
		 */
			if ($this->isSetClassToLoadAndSetParam($methods_class)&&!empty($methods_class)){
			/**
			 * The class is initialized? If not, load it and add the parameters from the config.
			 */
				$this->getDataFromMethod($methods_class,$methods_array);
			}
		}else{
		/**
		 * Initialized a class?
		 */
			$this->getDataFromMethod($methods_class,$methods_array);
		}
}

/**
 * 
 * The class is initialized? If not, load it and add the parameters from the config.
 * 
 * The path to the class specified in the
 * /evnine.config.php
 *	$this->class_path=array(
 *		'ModelsHelloWorld'=>array('path'=>'/models/')
 *	)
 * IMPORTANT:
 * Without specifying the path, all the models set in /models/
 * 
 * @param string $methods_class  
 * Name of class to load.
 * 
 * @param boolean $config_models
 * 
 * true - get the path of the configuration file.
 * 
 * false - use the path to a default model /models/
 * 
 * @see EvnineConfig.__construct
 * @access public
 * @return boolean
 */
function isSetClassToLoadAndSetParam($methods_class,$config_models=true){
	$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
	if ($config_models&&file_exists($class_dir)){
		/**
		 * There exists an a class file? path taken from the config.
		 */
		include_once($class_dir);
		if (count($this->class_path[$methods_class]['param'])>0){
		/**
		 * If parameters are specified in the config file, add them to an array of main parameters.
		 */
			$this->param=array_merge($this->param,$this->class_path[$methods_class]['param']);
		}
		$this->loaded_class[$methods_class] = new $methods_class($this->loaded_class[$this->api]);
		return true;
	}elseif(!$config_models&&file_exists($this->path_to.'models'.DIRECTORY_SEPARATOR.$methods_class.'.php')){
		/**
		 * The case of the installation path for the default model.
		 */
		include_once($this->path_to.'models'.DIRECTORY_SEPARATOR.$methods_class.'.php');
		$this->loaded_class[$methods_class] = new $methods_class($this->loaded_class[$this->api]);
		return true;
	}else {
		/**
		 * Display the error, the class is not loaded.
		 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):Class not found <br />'.$class_dir.'';
		return false;
	}
}

/**
 * 
 * Get data from the class methods.
 * 
 * /controllers/ControllersExample.php
 *	'ModelsHelloWorld' => array(
 *		'getHelloWorld1',
 *		'getHelloWorld2'
 *	)
 *	
 * Methods can start with is, get, set
 * 
 * Based on the type of method.
 * Get the data from the method. 
 *
 * is - to check it?
 * get - get the data.
 * set - set the data
 * ModifierParam at the end - it means changing parameter by the &link.
 *
 * @param string $methods_class 
 * Class to call methods.
 * 'ModelsHelloWorld' =>
 * 
 * @param array $methods_array 
 * An array of methods.
 * => array(
 *	'getHelloWorld1'
 *	 'getHelloWorld2'
 * )
 *
 * @see Controllers.controller
 * @link EvnineConfig.class_path
 * @access public
 * @return void
 */
function getDataFromMethod($methods_class,$methods_array){
	if ($this->isHasAccessSaveCheck||$methods_class==='ModelsErrors'){
		/**
		 * Is there access to the methods? 
		 * Perhaps there is no access and want to show the error?
		 */
		foreach ($methods_array as $methods_array_title =>$methods_array_value){
		/**
		 * For each method, we set the key to the answer.
		 * 'ModelsHelloWorld_getHelloWorld1'=>array()
		 */
		$array_key= $methods_class.'_'.$methods_array_value;
		if (!isset($this->result[$array_key]))
		{
		/**
		 * Each method is run only once!
		 * But you can get around in the class:
		 * 
		 * /models/ModelsHelloWorld.php
		 * function getFirstInitMethod($param){
		 *  echo 'Hello World!';
		 * }
		 * function getSecondInitMethod($param){
		 *  $this->getFirstInitMethod($param);
		 * }
		 * 
		 * Is there access to a method for this user?
		 */
			$isUserHasAccessForMethod = $this->isUserHasAccessForMethod($methods_class,$methods_array_value);
			if ($isUserHasAccessForMethod==='skip'){
			/**
			 * In the case where a particular method of access not, skip it.
			 */
				$this->result[$array_key.'_no_access'] = 'no_access';
				continue;
			}elseif(!$isUserHasAccessForMethod) {
			/**
			 * In the case where there is no access.
			 */
				return false;
			}
			if ($this->param["setResetForTest"]==true){
			/**
			 * For debugging, when you need to reset the data before get the answer.
			 * It is necessary to PHPUnitTest.
			 */
				if ((method_exists($this->loaded_class[$methods_class],'setResetForTest'))){
					$this->loaded_class[$methods_class]->setResetForTest($this->param);
					$this->result[$methods_class.'_'.$methods_array_value.'_'.'setResetForTest']=true;
				}else {
				/**
				 * If the method to reset does not exist, we display an error.
				 */
					$this->result['ControllerError'][]= __METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):NOT Exist: '.$methods_class.'_'.'setResetForTest';
				}
			}
			if (
			$methods_array_value[0]=='g'&&
				(
					$methods_array_value[3]=='E'
					||
					$methods_array_value[3]=='I'
				)
			){
			/**
			 * If you want to handle the error.
			 * getError 
			 * 01234567
			 * getInfo
			 * 01234567
			 * 
			 * /controllers/ControllersHelloWorld.php
			 * 'ModelsHelloWorld' => array(
			 *   'getError'
			 * )
			 */
			if (preg_match("/->/",$methods_array_value,$tmp)){
				/**
				 * If the type is an error in the method.
				 * /controllers/ControllersHelloWorld.php
				 * 'ModelsHelloWorld' => array(
				 *   'getError->not_hello'
				 * )
				 */
					$tmp_split=split('->',$methods_array_value);
					if (!empty($tmp_split[1])){
					/**
					 * >> 'getError->not_hello'
					 * << array(0 => 'getError',1 => 'not_hello',)
					 * 
					 * If the error is, set it to value.
					 * 
					 * )
					 */
						$this->param['info'] = $tmp_split[1];
					}
					$methods_array_value=$tmp_split[0];
					$array_key= $methods_class.'_'.$methods_array_value;
				}
			}
			if ($methods_class==='ModelsValidation'){
			/**
			 * If the class contains a method call to validate the data.
			 * 'method' => array(
			 *  'validation'=>array(
			 *   'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10'),
			 *  )
			 *  'ModelsValidation' => 
			 *    'isValidModifierParamFormError',
			 *    'isValidModifierParamFormError_false'=>array(),
			 *    'isValidModifierParamFormError_true'=>array(),
			 * )
			 * Define the method to use for validation.
			 */
				if (empty($this->param['method'])&&
						!empty($this->current_controller['public_methods']['default'])
					){
				/**
				 * If you do not specify an initialization method and in 
				 * the current controller is the default method.
				 * 
				 */
					$method_valid='default';
				}else {
				/**
				 * If you specify an initialization method.
				 */
					$method_valid=$this->param['method'];
				}
				if (!empty($this->current_controller['public_methods'][$method_valid]['validation'])){
				/**
				 * If the method is a validation of the array overwriting the default.
				 * 'public_methods' => array(
				 *  'default' => array(
				 *    'validation'=>array(
				 *     'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10'),
				 *    ),
				 *   ),
				 *  'overwriting_validation' => array(
				 *   'validation'=>array(
				 *    'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10'),
				 *    ),
				 *   ),
				 * )
				 */
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation'];
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_form'])) {
				/**
				 * If the method is a validation of the array form, 
				 * overwriting the default.
				 * _form - when data is transferred via the form.
				 * 
				 * /models/ModelsHelloWorld.php
				 * 'public_methods' => array(
				 *  'default' => array(
				 *   'inURLMethod' => array(
				 *    'default','overwriting_validation'
				 *    ),
				 *    'validation'=>array(
				 *     'date' => array('inURLSave' => true,'to'=>'Date','type'=>'str','required'=>'false','max' => '10'),
				 *    ),
				 *   ),
				 *  'overwriting_validation' => array(
				 *   'inURLMethod' => array(
				 *    'default','overwriting_validation'
				 *    ),
				 *   'validation_form'=>array(
				 *    'date' => array('inURL' => true,'to'=>'Date','inURL' => true,'type'=>'str','required'=>'false','max' => '10'),
				 *    ),
				 *   ),
				 * )
				 * 
				 * PHP:
				 * <form action="< ?=$result[inURLTemplate][default][pre].$result[inURLTemplate][default][post]? >" method="post">
				 *  < ?=$result[inURLTemplate][overwriting_validation][inputs]? >
				 *  <input name="< ?=$result[inURLTemplate][overwriting_validation][Data]? >" value="">
				 * </form>
				 * 
				 * TWIG:
				 * <form action="{{ inURLTemplate.default.pre }}{{ inURLTemplate.default.post }}" method="post">
				 *  {{ inURLTemplate.overwriting_validation.inputs }}
				 *  <input name="{{ inURL.overwriting_validation.Data }}" value="">
				 * </form>
				 */
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_form'];
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_multi_form'])) {
				/**
				 * If the method is an array of multiple forms of validation, 
				 * overwriting the default.
				 * _multi_form - when the same form can be transferred to any other method.
				 * 
				 * /models/ModelsHelloWorld.php
				 * ...
				 *  'validation_multi_form'=>...
				 * ...
				 * 
				 * PHP:
				 * <form action="< ?=$result[inURLTemplate][default][pre].$result[inURLTemplate][default][post]? >" method="post">
				 *  <input type="submit" name="< ?=$result[inURLTemplate][overwriting_validation][Data]? >" value="OK"/>
				 *  <input type="submit" name="< ?=$result[inURLTemplate][overwriting_validation_a][Data]? >" value="A"/>
				 * </form>
				 * 
				 * TWIG:
				 * <form action="{{ inURLTemplate.default.pre }}{{ inURLTemplate.default.post }}" method="post">
				 *  <input type="submit" name="{{ inURL.overwriting_validation.submit }}" value="OK"/>
				 *  <input type="submit" name="{{ inURL.overwriting_validation_a.submit }}" value="A"/>
				 * </form>
				 * 
				 */
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_multi_form'];
				}elseif (!empty($this->current_controller['public_methods']['default']['validation'])) {
				/**
				 * When combine validation of the default method and 
				 * validation data for a particular method.
				 * 
				 * /models/ModelsHelloWorld.php
				 * 'public_methods' => array(
				 *  'default' => array(
				 *    'validation'=>array(
				 *     'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10')
				 *    ),
				 *   ),
				 *  'method' => array(
				 *   'validation_add'=>array(
				 *    'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10')
				 *    ),
				 *   ),
				 * )
				 */
					$this->param['validation']= $this->current_controller['public_methods']['default']['validation'];
					if (!empty($this->current_controller['public_methods'][$method_valid]['validation_add'])){
					/**
					 * If there are additional options in the validation of a particular method.
					 * 
					 * /models/ModelsHelloWorld.php
					 * 'public_methods' => array(
					 *  'method' => array(
					 *   'validation_add'=>array(
					 *    'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10')
					 *    )
					 *   )
					 * )
					 */
						$this->param['validation']=array_merge(
							$this->param['validation'],
							$this->current_controller['public_methods'][$method_valid]['validation_add']
						);
					}
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_add'])) {
				/**
				 * In the case where the method has no default data for validation, 
				 * but there are add-on for validating.
				 * 
				 * /models/ModelsHelloWorld.php
				 * 'public_methods' => array(
				 *  'default' => array(
				 *   ),
				 *  'method' => array(
				 *   'validation_add'=>array(
				 *    'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10')
				 *    )
				 *   )
				 * )
				 */
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_add'];
				}
			}
			if (method_exists($this->loaded_class[$methods_class],$methods_array_value)){
				try{
				/**
				 * If there is method in the class. Try to get the data.
				 */
					$answer = $this->loaded_class[$methods_class]->$methods_array_value($this->param);
				} catch (Exception $e) {
				/**
				 * If you receive an error, save it in an array of parameters.
				 */
					$answer=$this->param['info']=$e->getMessage();
				}
			}else {
			/**
			 * If the method in the class does not exists, display an error.
			 */
				$this->result['ControllerError'][]=$answer=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Extend method not exist '.$methods_array_value.'';
			}
			if ($this->param['debug_param_diff']){
			/**
			 * If debugging is to compare the parameters of the method in the method is on.
			 */
				if (isset($this->result['param'][$this->param['method']]['param_out'])){
				/**
				 * If there are parameters of the previous method, we can compare them.
				 */
					$this->result['param'][$this->param['method']][$array_key] = getForDebugArrayDiff($this->param,$this->result['param'][$this->param['method']]['param_out']);
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}else {
				/**
				 * If the parameters of the previous method not save them.
				 */
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}
			}
			if (!empty($this->param['info'])){
			/**
			 * If the parameter is an error message. Append the message to the array response.
			 */
				$this->result[$array_key][$this->param['info']] = $answer;
			}else {
			/**
			 * If there is no data in error, save the data to answer evnine.
			 */
				$this->result[$array_key] = $answer;
			}
			if ($methods_array_value[0]=='i'&&
				$methods_array_value[1]=='s'){
				/**
				 * If the method contains an item, use the method to handle the cases.
				 * isHello
				 * 01
				 * 
				 * /controllers/ControllersHelloWorld.php
				 * 'default'=>array(
				 *  'ModelsHelloWorld'=>
				 *   'isHello',
				 *   'isHello_true'=>array(
				 *     'ModelsHelloWorld' => array(
				 *      'getHelloWorld',
				 *     )
				 *    )
				 * )
				 */
					$this->isGetMethodForAnswer($methods_array_value,	$this->result[$array_key] );
				}
				//TODO check elseif (empty($this->result[$array_key])) { $this->result[$array_key]='';}
		}elseif($methods_array_value[0]=='i'&&$methods_array_value[1]=='s') {
		/**
		 * Each method is run only once!
		 * But if the method contains a request to check conditions.
		 *
		 * isHello
		 * 01
		 * 
		 * We use the last answer to the check and method for processing cases.
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'default'=>array(
		 *  'ModelsHelloWorld'=>
		 *   'isHello',
		 *   'isHello_true'=>array(
		 *     'ModelsHelloWorld' => array(
		 *      'getHelloWorld',
		 *     )
		 *    )
		 * )
		 */
			$this->isGetMethodForAnswer($methods_array_value,	$this->result[$array_key] );
		}
	}
	}
}

/**
 * 
 * Is there access to a method for this user?
 * 
 * 'ModelsHelloWorld' => 'getHelloWorld1'
 * 
 * @param string $methods_class 
 * Class to call methods.
 * 'ModelsHelloWorld' =>
 * 
 * @param string $methods_array_value 
 * Method Name
 * => 'getHelloWorld1'
 *
 * @access public
 * @return bool
 */
function isUserHasAccessForMethod($methods_class,$methods_array_value) {
	if ($methods_class==='ModelsErrors'){
	/**
	 * If the class is to display the error, we give permission.
	 */
		return true;
	}
	$class_with_method = $methods_class.'::'.$methods_array_value;
	$access_for='';
	if (!empty($this->current_controller['access'][$class_with_method])
		&&isset($this->param['PermissionLevel'])
	){
	/**
	 * Verify access to a particular method.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * 'access'=>array(
	 *  'default_access_level' => $access_level['guest'],
	 *  'default_private_methods' => 'isHasAccess',
	 *  'Models::Method'=>array('access_level'=>$access_level['admin']),
	 * ),
	 */
		if ($this->param['PermissionLevel']>=$this->current_controller['access'][$class_with_method]['access_level']){
		/**
		 * Return the access is, when the method specified in the controller and
		 * current user level, above or equal to the minimum.
		 * 
		 * >>$this->param['PermissionLevel'] = $access_level['admin'];
		 * >>$class_with_method = 'Models::Method';
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * function __construct($access_level){
		 *  $this->controller = array(
		 *   'access'=>array(
		 *    'Models::Method'=>array(
		 *     'access_level'=>$access_level['admin']
		 *     'default_private_methods' => 'isHasAccess',
		 *    ),
		 *   ),
		 * )
		 * }
		 *
		 * <<true
		 */
			$access_for= 'method';
			return true;
		}else {
		/**
		 * In the case where there is no access, save data for later verification. 
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'access'=>array(
		 *  'Models::Method'=>array(
		 *    'access_level'=>$access_level['admin']
		 *    'default_private_methods' => 'isHasAccess',
		 *  ),
		 * ),
		 */
			$access_for= 'method';
			$run_method_case=$this->current_controller['access'][$class_with_method]['private_methods'];
			$level_for_check=$this->current_controller['access'][$class_with_method]['access_level'];
			if (empty($run_method_case)){
			/**
			 * In the case where a particular method is not specified a method to check access.
			 * Skip it.
			 * /controllers/ControllersHelloWorld.php
			 *  'Models::Method'=>array(
			 *    'access_level'=>$access_level['admin']
			 *    'default_private_methods' => '',
			 *  ),
			 */
				return 'skip';
			}
		}
	}else {
	/**
	 * If access to a particular method is not specified.
	 * Set the method to verify access.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * 'access'=>array(
	 *  'default_access_level' => $access_level['guest'],
	 *  'default_private_methods' => 'isHasAccess',
	 *  'Models::Method'=>'',
	 * )
	 */
		if ($this->param['PermissionLevel']>=$this->current_controller["access"]['default_access_level']||
			empty($this->current_controller['access'])
		){
		/**
		 * Make a check when the user level,
		 * above or equal to the minimum by default.
		 * Or access an array is empty.
		 * 
		 * >>$this->param['PermissionLevel'] = $access_level['admin'];
		 * /controllers/ControllersHelloWorld.php
		 * 'access'=>array(
		 *  'default_access_level' => $access_level['admin'],
		 *  'default_private_methods' => 'isHasAccess',
		 * ),
		 * ||
		 * /controllers/ControllersHelloWorld.php
		 * 'access'=>array(),
		 */
			if(empty($this->param['method'])){
			/**
			 * If the method is not specified, use the default method.
			 */
				$method='default';
			}else {
			/**
			 * If the method is specified, use it for check.
			 */
				$method=$this->param['method'];
			}
			if (isset($this->current_controller['public_methods'][$method]["access"]['default_access_level'])){
			/**
			 * Case where access is specified in the method.
			 * 
			 * /controllers/ControllersHelloWorld.php
			 * 'default'=>array(
			 *  'access'=>array(
			 *   'default_access_level' => $access_level['admin'],
			 *   'default_private_methods' => 'isHasAccess',
			 *  ),
			 *  'ModelsHelloWorld'=>'getHelloWorld',
			 * )
			 */
				$access_for= 'controller';
				if ($this->param['PermissionLevel']>=$this->current_controller['public_methods'][$method]["access"]['default_access_level']){
				/**
				 * Return the access is, when the access method is specified and
				 * current user level, above or equal to the minimum.
				 *
				 * >>$this->param['PermissionLevel'] = $access_level['admin'];
				 * 
				 * /controllers/ControllersHelloWorld.php
				 * 'default'=>array(
				 *  'access'=>array(
				 *   'default_access_level' => $access_level['admin'],
				 *  ),
				 * )
				 * 
				 * <<true
				 */
					return true;
				}else {
				/**
				 * If access to a particular method is not specified.
				 * Set the method to verify access.
				 * 
				 * /controllers/ControllersHelloWorld.php
				 * 'default'=>array(
				 *  'access'=>array(
				 *   'default_access_level' => $access_level['admin'],
				 *   'default_private_methods' => 'isHasAccess',
				 *   ),
				 * )
				 */
					$run_method_case=$this->current_controller['public_methods'][$method]['access']['default_private_methods'];
					$level_for_check=$this->current_controller['public_methods'][$method]['access']['default_access_level'];
					if ($run_method_case==''){
					/**
					 * In the case where a particular method is not specified a method to check access.
					 * Return not have access.
					 * 
					 * /controllers/ControllersHelloWorld.php
					 * 'default'=>array(
					 *  'access'=>array(
					 *   'default_access_level' => $access_level['admin'],
					 *   'default_private_methods' => '',
					 *  ),
					 */
						return false;
					}
				}
			}else {
			/**
			 * The case when there is no access method.
			 * Affirm that there is access.
			 * Because the check has previously been reported.
			 * 
			 * /controllers/ControllersHelloWorld.php
			 * 'default'=>array(
			 *  'access'=>''
			 * )
			 */
				return true;
			}
		}else {
		/**
		 * In the case where there is no access, save data for later verification.
		 * 
		 * >>$this->param['PermissionLevel'] = $access_level['guest'];
		 * 
		 * /controllers/ControllersHelloWorld.php
		 * 'default'=>array(
		 *  'access'=>array(
		 *   'default_access_level' => $access_level['admin'],
		 *   'default_private_methods' => 'isHasAccess',
		 *  ),
		 * )
		 * 
		 * <<$run_method_case='isHasAccess';
		 */
			$access_for= 'controller';
			$run_method_case=$this->current_controller['access']['default_private_methods'];
			$level_for_check=$this->current_controller['access']['default_access_level'];
		}
	}
	/**
	 * When there is no access, run the method specified by default for the access check.
	 */
	$this->getPrivateMethod($run_method_case);
	if ($this->param['PermissionLevel']<$level_for_check){
	/**
	 * Check after the launch, may change the level of access method.
	 * And now have access to.
	 * 
	 * We provide access to the case when there is no such an error.
	 */
		$this->isGetMethodForAnswer($run_method_case,false);
		/**
		 * Set for an access check only once.
		 */
		$this->isHasAccessSaveCheck=false;
		return false;
	}else {
	/**
	 * Return not have access.
	 */
		$this->isGetMethodForAnswer($run_method_case,true);
	}
	return true;
}

/**
 * Select the method for answer.
 * 
 * /controllers/ControllersHelloWorld.php
 *	'default'=>array(
 *		'ModelsHelloWorld'=>'isHello',
 *	 )
 * @param string $method
 * Method name.
 * =>'isHello'
 * 
 * @param boolean $methods_case 
 * The method answer 
 * 
 * true = 1
 * 
 * false = 0 = ''
 *
 * @access public
 * @return void
 */
function isGetMethodForAnswer($method,$methods_case) {
	if (
		$methods_case==''||$methods_case==0
	){
	/**
	 * If the answer is no.
	 */
		if ($method==='isValidModifierParamFormError'){
		/**
		 * If the method for testing, validation, deny access.
		 */
			$this->isHasAccessSaveCheck=false;//TODO check
		}
		$case= '_false';
	}else {
	/**
	 * If the answer is correct, set the key.
	 */
		$case= '_true';
	}
	if (!empty($this->current_controller['methods_case'])
		&&!empty($this->current_controller['methods_case'][$method.$case])
	){
	/**
	 * If the value of the method is an array of cases methods.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * 'methods_case' =>array(
	 *  'isValidModifierParamFormError_true' => 'isValid_false',
	 *  ),
	 * 'private_methods'=>array(
	 *  'isValid_false'=>array(
	 *   'ModelsErrors'=>'getError->valid_error',
	 *  ),
	 * )
	 * 
	 */
		$method = $this->current_controller['methods_case'][$method.$case];
	}else {
	/**
	 * If the value of the method is not in the array of cases methods.
	 * Use the key to access the current method.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * 'methods_case' =>array(
	 *  'isValidModifierParamFormError_true' => '',
	 * )
	 * 
	 */
		$method = $method.$case;
	}
	/**
	 * Call private method.
	 */
	$this->getPrivateMethod($method);
}

/**
 * Call public method.
 * 
 * @link Controllers.controller
 * 
 * @param array $param 
 * Parameters from the input.
 * 
 * @access public
 * @return void
 */
function getPublicMethod($param) {
	if (!empty($this->current_controller['public_methods'][$param['method']])){
	/**
	 * If the public method exists.
	 */
		foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
		/**
		 * Call the methods in the class.
		 */
			$this->getMethodFromClass($_title,$_value);
		}
	}else {
	/**
	 * If there is no public method. Display an error..
	 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Method '.$param['method'].' not found in '.$this->current_controller_name.'';
		if (!isset($this->current_controller)){
		/**
		 * If the controller does not exist. Display an error..
		 */
			$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Array $controller is not exist: <br />/controller/'.$this->controller_alias[$this->current_template].'.php <br /> var $controller;<br />function __construct($access_level){<br /> $this->controller = array(...);<br />;}';
		}
		if (!empty($this->current_controller['public_methods']['default'])){
		/**
		 * If there is a default method.
		 */
			$param['method']='default';
			foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
			/**
			 * Call the methods in the class.
			 */
				$this->getMethodFromClass($_title,$_value);
			}
		}
	}
}

/**
 * Call private method.
 * 
 * @link Controllers.controller
 * 
 * @param string $method 
 * Private method.
 * 
 * @access public
 * @return void
 */
function getPrivateMethod($method){
	if (!empty($this->current_controller['public_methods'][$this->param['method']][$method])){
	/**
	 * If there is a public method in the controller will use it.
	 * Has a higher priority than if given as a private method.
	 * 
	 * /controllers/ControllersHelloWorld.php
	 * 'private_methods'=>array(
	 *  'isValid_false'=>array(
	 *   'ModelsErrors'=>'getError->valid_error',
	 *  ),
	 * ),
	 * 'public_methods'=>array(
	 *  'isValid_false'=>array(
	 *   'ModelsErrors'=>'getError->a_higher_priority',
	 *  ),
	 * )
	 */
		$methods_callback = $this->current_controller['public_methods'][$this->param['method']][$method];
	}elseif (!empty($this->current_controller['private_methods'][$method])){
	/**
	 * If there is a private method. We use it to call.
	 * /controllers/ControllersHelloWorld.php
	 * 'private_methods'=>array(
	 *  'isValid_false'=>'',
	 * ),
	 * 'public_methods'=>array(
	 *  'isValid_false'=>array(
	 *   'ModelsErrors'=>'getError->a_higher_priority',
	 *  ),
	 * )
	 */
		$methods_callback = $this->current_controller['private_methods'][$method];
	}else {
	/**
	 * If the method does not exist. Display an error.
	 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): In controller "'.$this->current_controller_name.'" not found Method "'.$method.'"';	
	} 
	foreach ($methods_callback as $method_title =>$method_value){
	/**
	 * Call each class method.
	 * 'isValid_false'=>array(
	 *   'ModelsErrors'=>'getError',
	 *  ),
	 * 
	 * $method_title = 'ModelsErrors';
	 * $method_value = 'getError';
	 */
		$this->getMethodFromClass($method_title,$method_value);
	}
}

/**
 * 
 * Get the first element of the array as a key or value.
 * 
 * @param array $array 
 * An input array.
 *
 * @param boolean $get_value 
 * Is first value?
 * 
 * @access public
 * @return string
 */
function getFirstArrayKey($array,$get_value=false) {
	$tmp = each($array);
	list($key, $value)=$tmp;
	if (!$get_value){
	/**
	 * If you need a key.
	 */
		return $key;
	}else {
	/**
	 * If you want to get the value.
	 */
		return $value;
	}
}

/**
 *
 * A method for testing an controllers.
 * 
 * Required to operate: ModelsPHPUnit
 * And the call sequence:
 *
 * /controllers/ControllersPHPUnit.php 
 *	'ModelsPHPUnit' => array(
 *		'getParamTest',
 *		'getParamCaseByParamTest',
 *		'getCountParamByParamTest',
 *		'getPHPUnitCode',
 *	)
 *
 * Options to configure:
 * /evnine.config.php
 *	$this->controller_alias=array(
 *		'php_unit_test'=>'ControllersPHPUnit',
 *	);
 *	$this->param_const=array(
 *		// A shared folder for the cache.
 *		'CacheDir'=>'PHPUnitCache',
 *		// Folder to store the PHPUnit tests.
 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
 *		// Folder to store temporary data.
 *		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
 *	) 
 *	
 * Initialization:
 * /index.php 
 *	include_once 'evnine.php';
 *	$evnine = new EvnineController();
 *	$output = $evnine->getControllerForParam(array('controller' => 'php_unit_test'));
 *	$php_unit_file='evninePHPUnit.php';
 *	if (!file_exists($php_unit_file)){
 *		file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
 *		$dir = (defined( '__DIR__' )?__DIR__:getcwd());
 *		$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
 *		exec($exec);
 *	}
 *	
 * >> $output['ModelsPHPUnit_getPHPUnitCode']
 * <<
 * /evninePHPUnit.php
 *	//@assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default')))
 *	function getControllerForParam_helloworld_default_Test($method,$array,$param) {
 *		return $this->getControllerForParamTest($method,$array,$param);
 *	}
 * 
 * >> wget http://pear.php.net/go-pear.phar
 * >> sudo php go-pear.phar
 * >> pear channel-discover pear.phpunit.de
 * >> pear install phpunit/PHPUnit
 * >> cmd/sh: phpunit --skeleton-test "evninePHPUnit" /OR_SET_PATH_TO/evninePHPUnit.php
 * 
 * <<
 * /evninePHPUnitTest.php
 *	public function testGetControllerForParam_helloworld_default_Test()
 *	{
 *		$this->assertEquals(
 *			$array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default')))
 *		,
 *			$this->object->getControllerForParam_helloworld_default_Test('getControllerForParam_helloworld_default_Test',$array,$param)
 *		);
 *	}
 *
 * @link ModelsPHPUnit
 * @see EvnineConfig.class_path
 * 
 * @param string $method 
 * The method for check.
 * 
 * @param array $array_init 
 * Array to store data from an external method call.
 * To optimize performance.
 * 
 * @param array $param 
 * An array of initialization parameters.
 * 
 * @access public
 * @return void
 */
function getControllerForParamTest($method,$array_init,$param){
	if (empty($this->param_const['CacheDirPHPUnit'])){
	/**
	 * If the folder path to the cache is not specified.
	 */
		$this->param_const['CacheDirPHPUnit']='test'.DIRECTORY_SEPARATOR.'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit';
	}
	$methods_class='ModelsPHPUnit';
	if ($this->isSetClassToLoadAndSetParam($methods_class,$use_config=false)
			||$this->isSetClassToLoadAndSetParam($methods_class,$use_config=true)
	){
	/**
	 * Load model for testing.
	 * //evnine.config.ph
	 * 'ViewsUnitPHP'=>array(
	 *  'path'=>'views'.DIRECTORY_SEPARATOR,
	 * ),
	 * 
	 */
		ob_start();
		$file_name = $this->loaded_class[$methods_class]->getFileNameMD5ForParam($this->path_to.$this->param_const['CacheDirPHPUnit'],$param);
		$array = $this->loaded_class[$methods_class]->getSerData($file_name,$param);
		if (empty($array)){
		/**
		 * If the data from the cache is not received.
		 */
			if (!empty($array_init)){
			/**
			 * If the controller has already received the data, we use them.
			 */
				$array = $array_init;
			}elseif (method_exists($this,$method)){
			/**
			 * Run method in the current class.
			 */
				$array = $this->$method($param);
			}
			/**
			 * Save in the cache.
			 */
			$this->loaded_class[$methods_class]->setSerData($file_name,$array,true);
		}
		//TODO check case $this->setRestetForTest();
		ob_end_flush();
		return $array;
	}else {
	/**
	 * If the test model does not exist. Display an error. 
	 */
		ob_end_flush();
		return 'ERROR not exist in  evnine.config.php
			$this->class_path=array(\'ModelsPHPUnit\'=>array(\'path\'=>\'models\'.DIRECTORY_SEPARATOR)';
	}
}

}
?>
