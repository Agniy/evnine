<?php
/**
 * 
 * Базовый контроллер.
 * 
 * @uses EvnineConfig 
 * @package EvnineController
 * @version 0.3
 * @copyright 2009-2011 
 * @author ev9eniy.info
 * @updated 2011-06-01 17:53:02
 */
/**
 * Подключаем конфиг и наследуем от него настройки.
 * 
 * Общий вывод ошибок
 * error_reporting(E_ERROR|E_RECOVERABLE_ERROR|E_PARSE|E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
 * Не выводить ошибки
 * error_reporting(0);
 */
include_once('evnine.config.php');
class EvnineController extends EvnineConfig
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
	 * @see Controllers.controller_alias
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

	/**
	 * Флаг работы в ЧПУ режиме. 
	 * Устанавливается в методе $this->getControllerForParam
	 * при условии, если в параметрах передана строка для разбора ЧПУ
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
	 * Есть ли доступ к методам? Исходя из уровня доступа пользователя.
	 * 
	 * @see EvnineController.isUserHasAccessForMethod
	 * @see EvnineController.getDataFromMethod
	 * @var boolean
	 * @access public
	 */
	var $isHasAccessSaveCheck;

	/**
	 * Название текущего контроллера
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
	 * Текущий контроллер
	 * 
	 * @var string
	 * @access public
	 */
	var $current_controller;

	/**
	 * Загруженные контроллеры
	 * 
	 * @see EvnineController.setLoadController 
	 * @var array
	 * @access public
	 */
	var $loaded_controller;

	/**
	 * Параметры, которые передаются каждому методу
	 * $param = array($param_init,$param_const)
	 * 
	 * @see EvnineController.getDataFromController
	 * @var array
	 * @access public
	 */
	var $param;

	/**
	 * Загруженные классы
	 * 
	 * @see EvnineController.isSetClassToLoadAndSetParam 
	 * @var array
	 * @access public
	 */
	var $loaded_class;

	/**
	 * Массив ответа контроллера, содержит все вызванные методы.
	 * 
	 * $result = $evnine->getControllerForParam()
	 * 
	 * @see EvnineController.getControllerForParam
	 * @var array
	 * @access public
	 */
	var $result;

	/**
	 * Для отладки скриптов
	 * 
	 * @see EvnineController.__construct
	 * @see EvnineController.getDataFromMethod
	 * @var boolean
	 * @access public
	 */
	var $debug;

/**
 * 
 * Конструктор класса
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
		 * Инициализируем API
		 */
	if ($this->isSetClassToLoadAndSetParam($this->api)){
		/**
		 * Загружен ли класс? Если нет, загрузим и добавим параметры из конфига.
		 */
			$this->loaded_class[$this->api]->setInitAPI($this->param);
		}
	}
	/**
	 * Проверяем наличия функции для сравнения массивов.
	 */
	if ($this->param_const['debug']&&function_exists('getForDebugArrayDiff')){
		$this->param_const['debug_param_diff']=true;
	}
}

/**
 * Получить данные из контроллера по параметрам.
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
		 * Если есть строка для ЧПУ. Определяется в:
		 * .htaccess
		 * <IfModule mod_rewrite.c>
		 * RewriteEngine On
		 * RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
		 * </IfModule>	
		 */
		/**
		 * Получить данные из ЧПУ строки
		 */
		$sef = $this->getURNbySEF($param['sef']);
		unset($param['sef']);
		/**
		 * Установить метку что адреса нужно обрабатывать в ЧПУ режиме
		 */
		$this->sef_mode=true;
		unset($param['REQUEST']['sef']);
		/**
		 * Установить параметры из ЧПУ
		 */
		$param['method']=$sef['method'];
		$param['controller']=$sef['controller'];
		/**
		 * Объединить данные, если данные передаются POST и GET одновременно 
		 */
		$param['REQUEST']=array_merge($param['REQUEST'],$sef['REQUEST']);
	}
	if (!empty($param['REQUEST']['submit'])){
		/**
		 * Случай когда обрабатываем несколько форм
		 */
		if ($first_key=(
			is_array($param['REQUEST']['submit'])
			?
			$this->getFirstArrayKey($param['REQUEST']['submit'])
			:
			$param['REQUEST']['submit'])
		){
		/**
		 * Получаем метод по первому ключу из имени кнопки submit
		 */
			unset($param['REQUEST']['submit']);
			if (!empty($param['REQUEST'][$first_key]['c'])){
			/**
			 * Получаем значения контроллера
			 */
				$param['controller']=$param['REQUEST'][$first_key]['c'];
				unset($param['REQUEST'][$first_key]['c']);
			}
			if (!empty($param['REQUEST'][$first_key]['m'])){
			/**
			 * Получаем значения метода
			 */
				$param['method']=$param['REQUEST'][$first_key]['m'];
				unset($param['REQUEST'][$first_key]['m']);
			}else {
				$param['method']=$first_key;
			}
			if (count($param['REQUEST'][$first_key])>0){
			/**
			 * Если к методу привязанные какие-либо данные, 
			 * переносим их в основные параметры
			 */
				foreach ($param['REQUEST'][$first_key] as $_title =>$_value){
					$param[$_title]= $_value;
				}
			}
		}
	}elseif(empty($param['method'])) {
		/**
		 * Если метод не указан
		 */
		$param['method']='default';
	}
	$this->result=array();
	if (empty($this->result['LoadController'])){
		/**
		 * В результаты работы устанавливаем данные первой инициализации:
		 */
		$this->result['LoadController']=$param['controller'];
		$this->result['LoadMethod']=$param['method'];
	}
	/**
	 * Тип режима работы AJAX
	 */
	if ($param['ajax'][0]==='b') {
		/**
		 * Случай, когда нужно отправить только тело 
		 * HTML <body>...</body>
		 */
		$this->result['ajax']='Body';
		$param['ajax']=false;
	}elseif ($param['ajax'][0]==='a'){
		/**
		 * Случай, когда нужно запускать метод в AJAX режиме
		 */
		$this->result['ajax']='True';
		$param['ajax']=true;
	}else {
		/**
		 * Случай, когда выполняется default метод в контроллере
		 */
		$this->result['ajax']='False';
		$param['ajax']=false;
	}
	/**
	 * Получим данные из главного контроллера
	 */
	$this->getDataFromController($param);
	/**
	 * Сбросить данные о методе, так как в процессе работы метод может меняться.
	 */
	$this->param['method']=$param['method'];
	/**
	 * Получить URN для методов в контроллере
	 * Используется валидация из методов контроллера
	 */
	$this->getURL();
	if ($this->param_const['debug_param_diff']){
	/**
	 * Для отладки, удалим параметры на выходе
	 */
		unset($this->result['param'][$this->param['method']]['param_out']);
		if (empty($this->result['param'][$this->param['method']])){
		/**
		 * Если массив пуст.
		 */
			unset($this->result['param'][$this->param['method']]);
			if (empty($this->result['param'])){
			/**
			 * Если массив пуст.
			 */
				unset($this->result['param']);
			}
		}
	}
	if ($this->param_const['param_out']){
	/**
	 * Когда нужно передать параметры в другой контроллер.
	 * Для использования в модели вида.
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
 * Получить данные из ЧПУ строки.
 *
 * ЧПУ режим может быть двух типов.
 *  
 * 1. Когда SEF для всего контроллера.
 * << /controller/method/param=value/param=value/
 * 
 * /controllers/ControllersExample.php
 *	$this->controller = array(
 *		'inURLSEF' => true
 *	)
 *
 * 2. Когда ЧПУ только для метода:
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
 * ЧПУ строка.
 * 
 * Передаётся из:
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
		 * Если ЧПУ режим для всего контроллера, есть окончание URN - index.html
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
		 * Если ЧПУ режим для метода.
		 * /62-user.html
		 */
		if ($split_count==3){
		/**
		 * Случай, когда есть и контроллер и метод
		 */
			$param['method']=$split['1'];
			$split_form_data = split('-',$split['2']);
		}elseif($split_count==2) {
		/**
		 * Случай, когда метод не указан
		 */
			$split_form_data = split('-',$split['1']);
			$param['method']='default';
		}
		$split_count=count($split_form_data);
		/**
		 * Загрузим контроллер. Для разбора переменных ЧПУ.
		 */
		$this->setLoadController($param['controller']);
		if (!empty($this->current_controller['public_methods'][$param['method']]['inURLSEF'])){
		/**
		 * Если есть данные для разбора ЧПУ 
		 * в методе контроллера.
		 */
			$i=0;
			foreach ($this->current_controller['public_methods'][$param['method']]['inURLSEF'] as $_title =>$_value)if ($_title!=='.'){
				if ($_title==='date'){
					/**
					 * Учтём дату в ЧПУ. 
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
 * Получить URN для методов в контроллере.
 * Используется валидация из методов контроллера.
 * 
 * /controllers/ControllersExample.php
 *	$this->controller = array(
 *		'inURLSEF' => false,
 *		// ЧПУ режим в контроллере.
 *		// Если ЧПУ режим для всего контроллера, есть окончание В URN - index.html
 *		// По умолчанию false
 *		// >> 'inURLSEF'=> true
 *		// << /controller/method/param=value/param=value/
 *	),
 *	'inURLMethod' => array(
 *		// В массиве указаны методы для которых нужно создать ссылки.
 *		'default',
 *	),
 *	'public_methods' => array(
 *		'default' => array(
 *			// URN строятся исходя из валидации параметров допустимых в методе.
 *			// По умолчанию данные берутся из default метода
 *			'validation'=> array()
 *		)
 *		'method' => array(	
 *			// Дополнительная валидация для метода.
 *			// В ней указываются URN.
 *			// Эти URNы используются для генерации параметров.
 *			'validation_add'=>array(
 *				'date' => array('inURL' => true,'to'=>'Date','type'=>'str','required'=>'false','max' => '10',),
 *			),
 *		)
 *		'ext_method_and_controller' => array(
 *			// Если хотим указать внешний контроллер в URN.
 *			// И метод во внешнем контроллере.
 *			'inURLExtController' => 'ext_controller', 
 *			'inURLExtMethod' => 'ext_method',
 *		)
 *		'sef_method' => array(
 *			// Если хотим использовать ЧПУ в методе.
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
 * Флаг работы в ЧПУ режиме. 
 * 
 * @see EvnineController.getControllerForParam
 * @access public
 * @return void
 */
function getURL($seo=false) {
	if ($this->current_controller['inURLSEF']){
	/**
	 * Случай когда SEF для всего контроллера.
	 */
		$seo= true;
	}
	$default = $this->getURLFromArray(
	/**
	 * Получить URN из массива проверки валидации
	 */
		$this->current_controller['public_methods']['default']['validation'],$seo
	);
	/**
	 * Создаётся базовая часть URN.
	 * В котором указывается контроллер и метод.
	 */
	$urn_base= $this->getControllerURN($this->param['controller'],$seo);
	//
	if ($seo){
	/**
	 * Если включен режим ЧПУ для всего контроллера.
	 * Установим строку завершения каждого URN.
	 * TODO user for the param['seo_end_url']
	 */
		if (empty($this->param['sef_url'])){
				$postfix='/index.html';
			}else {
				$postfix=$this->param['sef_url'];
			}
	}else {
	/**
	 * Не используем дополнения URN.
	 */
		$postfix='';
	}
	/**
	 * Флаг ЧПУ режима для метода.
	 */
	$seo_flag_save='';
	if (!empty($this->current_controller['public_methods']['default']['inURLMethod'])/*&&$this->param['ajax']==false*/){
	/**
	 * Если в методе по умолчанию, указаны методы на которые делать ссылки.
	 * 'inURLMethod' => array(
	 * //ru: Массив для генерации ссылок по методу
	 *  'default',
	 * )
	 */
		if (!is_array($this->current_controller['public_methods']['default']['inURLMethod'])){
		$this->current_controller['public_methods']['default']['inURLMethod']=array($this->current_controller['public_methods']['default']['inURLMethod']);
		}
		$url_method = $this->current_controller['public_methods']['default']['inURLMethod'];
		if(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
		/**
		 * Если существует в текущем методе массив для добавления
		 * Объединить массивы метода по умолчанию и метода для добавления.
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
		 * Случай когда в текущем методе указан новый массив методов для генерации ссылок.
		 * Заменим массив методов для генерации.
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
	 * Случай когда не указан метод по умолчанию.
	 * Используем массив методов указанный в текущем методе для добавления.
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
	 * В случае когда указан массив методов для перезаписи.
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
	 * Для всех методов которым создаём URN
	 */
			$method = $url_method[$i];
			if (
				$this->current_controller['public_methods'][$method]['access']['default_access_level']
				>$this->param['PermissionLevel']
				||
				empty($this->current_controller['public_methods'][$method])
			){
			/**
			 * Пропускаем обработку в случае:
			 * Когда нет доступа к методу.
			 * Когда метод не задан.
			 */
				continue;
			}
		if (!empty($this->current_controller['public_methods'][$method]['inURLSEF'])){
			/**
			 * Если есть ЧПУ для метода, установим флаг работы в СЕО режиме.
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
			 * Переходим к генерации URN по параметрам валидации.
			 */
		if (!empty($this->current_controller['public_methods'][$method]['validation_add'])){
			/**
			 * Когда валидация в методе с добавлением к валидации по умолчанию.
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation_add' => array(),
			 * )
			 */
			$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation_add'],$seo);
				if($seo&&$seo!==true){
				/**
				 * Если есть ЧПУ для метода.
				 * И СЕО режим не для всего контроллера.
				 * 
				 * Генерируем ссылку для контроллера и метода.
				 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->param['controller'],$seo)
						.$this->getMethodURN($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				} else {
				/**
				 * Если стандартный режим генерации URN.
				 */
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURN($method,$seo,true).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation'])) {
			/**
			 * Когда валидация в методе перезаписывает всю валидацию.
			 * 
			 * 'current_method' => array(
			 *  'inURLMethod' => array('current_method'),
			 *  'validation' => array(),
			 * )
			 */
			if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])) {
				/**
				 * Случай когда в URN нужно указать другой контроллер.
				 * 
				 * 'current_method' => array(
				 *  'inURLExtController' => 'other_controller',
				 *  'inURLMethod' => array('current_method'),
				 *  'validation' => array(),
				 *  )
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
				 * Генерируем ссылку для внешнего контроллера и метода
				 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
			 * Если не указан внешний контроллер
			 */
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
				/**
				 * Если есть ЧПУ для метода.
				 * И СЕО режим не для всего контроллера.
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
				 * Генерируем ссылку для контроллера и метода.
				 */
					$this->result['inURL'][$method]['pre']=
						 $this->getControllerURN($this->param['controller'],$seo)
						.$this->getMethodURN($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
					/**
				 * Если стандартный режим генерации URN.
				 */
					if ($method!=='default'){
					/**
					 * Если метод не по умолчанию, используем для генерации валидацию.
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
			 * Когда нужны данные для формы, не для ссылки. Используются валидация формы.
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
				 * Случай когда в URN нужно указать другой контроллер.
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
					 * Генерируем ссылку для контроллера и метода.
					 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
							.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			} else {
				/**
				 * Если не указан внешний контроллер
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
			 * Когда нужны данные для нескольких методов в одной форме, не для ссылки. 
			 * Используются валидация множественной формы.
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
				 * Случай когда в URN нужно указать другой контроллер.
				 *
				 * Генерируем input для контроллера и метода.
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
					 * Генерируем ссылку для контроллера и метода.
					 */
					$this->result['inURL'][$method]['pre']=
						$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
					$this->result['inURL'][$method]['post']=$postfix;
				}else{
				/**
				 * Если не указан внешний контроллер
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
			 * Случай, когда не указаны данные для генерации ссылок из валидации.
			 * Используем валидацию из метода по умолчанию
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
			 * Случай когда в URN нужно указать другой контроллер.
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
			 * Генерируем ссылку для контроллера и метода.
			 */
				$this->result['inURL'][$method]['pre']=
					$this->getControllerURN($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
					.$this->getMethodURN($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
			 * Если не указан внешний контроллер
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
				 * Если есть ЧПУ для метода.
				 * И СЕО режим не для всего контроллера.
				 * 
				 * Генерируем ссылку для контроллера и метода.
				 */
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					$this->result['inURL'][$method]['pre']=
					$this->getControllerURN($this->param['controller'],$seo)
					.$this->getMethodURN($method,$seo)
					.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
				/**
				 * Если стандартный режим генерации URN.
				 */
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURN($method,$seo).$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}
		if (count($this->result['inURL'][$method]['replace'])>0){
		/**
		 * Если в URN по умолчанию уже есть часть параметров.
		 * Заменяем на текущие параметры метода.
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
		 * Если есть ЧПУ для метода.
		 * И СЕО режим не для всего контроллера.
		 * 
		 * Сбрасываем флаг
		 */
			$seo=$seo_flag_save;
			$seo_flag_save= '';
		}
	}
	$this->getURNTemplate();
}

/**
 * 
 * Постоянные ссылки в шаблоне на разные методы.
 * 
 * В зависимости от метода, получаем доступ 
 * по одному и тому же ключу к URN разных методов.
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
	 * Если есть массив переменных ссылок в текущем методе.
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
		 * Если есть массив переменных ссылок в методе по умолчанию.
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
	 * Случай когда в текущем методе нет переменных ссылок, 
	 * но есть в методе по умолчанию.
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
 * Получить URN контроллера.
 * 
 * @param string $contoller_name 
 * Название контроллера.
 * @param string $seo 
 * Использовать ЧПУ?
 * @access public
 * @return string
 */
function getControllerURN($contoller_name,$seo) {
	if ($seo){
		/**
		 * Если общий метод ЧПУ для контроллера, используем /param=value/
		 */
		$urn_base='/'.$contoller_name;
	} else {
		/**
		 * Если ЧПУ режим не используется.
		 */
		$urn_base='?c='.$contoller_name;
		if ($this->sef_mode){
		/**
		 * Флаг работы в ЧПУ режиме, устанавливается в методе $this->getControllerForParam
		 * при условии, если в параметрах передана строка для SEF разбора URN
		 * if (!empty($param['sef'])) {$this->sef_mode=true;}
		 * TODO ADD name from config
		 */
			$urn_base='/index.php'.$urn_base;
		}
	}
	return $urn_base;
}

/**
 * Получить URN из названия метода. 
 * 
 * @param string $contoller_name 
 * Название метода.
 * @param string $seo 
 * Использовать ЧПУ?
 * @access public
 * @return string
 */
function getMethodURN($method,$seo) {
	if (empty($method)){
	/**
	 * Если метод не указан, будет использован метод по умолчанию.
	 */
		return '';
	}else {
		if ($seo){
		/**
		 * Если общий метод ЧПУ для контроллера, используем /param=value/
		 */
			$urn_base.='/'.$method;
		}else {
		/**
		 * Если ЧПУ режим не используется.
		 */
			$urn_base.='&m='.$method;
		}
	}
	return $urn_base;
}

/**
 * 
 * Получить для формы значение input.
 * 
 * @param string $name 
 * Имя параметра в форме.
 * 
 * @param string $value 
 * Значение параметра в форме.
 * 
 * @param string $multi_form 
 * Имя для параметров формы, если используется множественная форма.
 * 
 * @access public
 * @return string
 */
function getInputFormText($name,$value,$multi_form=false){
	if (empty($value)) {
	/**
	 * Если значение для формы не указано останавливаем обработку.
	 */
		return;
	}else {
		if ($multi_form){
		/**
		 * Имя для параметров формы, если используется множественная форма.
		 */
			$name=$multi_form.'['.$name.']';
		}
		return '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';
	}
}

/**
 *
 * Получить для массива валидации значение параметров в форме.
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
 * Массив с параметрами формы.
 * 
 * @param string $multi_form
 * Имя для параметров формы, если используется множественная форма.
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
		 * Если данный параметр из валидации указан в шаблоне как параметр ввода.
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
			 * Если параметр является массивом. Используем запись вида &param[]=1&param[]=2
			 */
				$_title.='[]';
			}
			$array_out[$_value['to']]=$_title;
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				/**
				 * Если параметр на выходе существует.
				 * 
				 * Создаём массив параметров для замены в URN по умолчанию.
				 */
				if ($seo===true){
				/**
				 * Если общий метод ЧПУ для контроллера, используем /param=value/
				 */
					$array_out['replace'][$_value['to']]='/'.$_title.'='.$REQUEST_OUT;
				}elseif($seo&&$seo!==true){
				/**
				 * Если есть ЧПУ для метода.
				 * И СЕО режим не для всего контроллера.
				 */
					$array_out['replace'][$_value['to']]=$REQUEST_OUT;
				}else {
				/**
				 * Если стандартный режим генерации URN.
				 */
					$array_out['replace'][$_value['to']]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
			/**
			 * Если параметр не указан в шаблоне как параметр ввода.
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
				 * Если параметр на выходе существует.
				 */
					//TODO check without multi_form=true if ($multi_form&&$_value['multi_form']){
				if ($multi_form){
				/**
				 * Если используется множественная форма.
				 */
					$pre_fix=$multi_form.'[';$post_fix= ']';
				}
				if ($_value['is_array']){
				/**
				 * Если параметр является массивом.
				 * 
				 * <input name="controller[method][]"/>
				 */
					foreach ($REQUEST_OUT as $REQUEST_OUT_title =>$REQUEST_OUT_value){
						$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'[]" value="'.$REQUEST_OUT_value.'"/>';
					}
				}else {
					/**
					 * Если параметр не массив.
					 * <input name="controller[method]"/>
					 */
					$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'" value="'.$this->result['REQUEST_OUT'][$_value['to']].'"/>';
				}
				//TODO check without multi_form=true if ($multi_form&&$_value['multi_form']){
				if ($multi_form){
				/**
				 * Если используется множественная форма.
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
 * Получить URN из массива проверки валидации
 * 
 * @param array $validate 
 * Массив с параметрами формы.
 *
 * @param boolean $seo 
 * Использовать ЧПУ?
 * 
 * @param boolean $is_add 
 * Параметр добавляться к текущему URN?
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
	 * Если есть ЧПУ для метода.
	 * И СЕО режим не для всего контроллера.
	 */
		foreach ($seo as $seo_title =>$seo_value) if ($seo_title!=='.'){
			/**
			 * Для каждого эл-та для SEO, выполнить если ключ не является точкой.
			 * 
			 * 'current_method' => array(
			 *  'inURLSEF' => array(
			 *   'user_id' => '','User','.' => '.html',
			 *  ),
			 * )
			 */
			if ($validate[$seo_title]['inURL']){
				/**
				 * Если данный параметр из валидации указан в шаблоне как параметр ввода.
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
				 * Если в URN по умолчанию уже есть часть параметров.
				 * Заменяем на текущие параметры метода.
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
			 * Если параметр не указан в шаблоне как параметр ввода.
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
				 * При начальном формирование URN. 
				 * Если значение пустое. Используем слэш.
				 */
					$array_out['pre'].= '/';
				}else {
				/**
				 * Во всех остальных случаях.
				 */
					$array_out['pre'].= '-';
				}
				if (!empty($seo_value)){
					/**
					 * Если значение для ЧПУ не пустое, используем его.
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
				 * Либо используем значение параметров выхода из контроллера.
				 */
					$array_out['pre'].=$this->result['REQUEST_OUT'][$validate[$seo_title]['to']];
				}
			}
		}
		if (empty($array_out['pre'])) {
			/**
			 * При начальном формирование URN. 
			 * Если значение пустое. Используем слэш.
			 */
			$array_out['pre'].= '/';
		}
		return $array_out;  
	}else {
		/**
		 * Если в методе не используется ЧПУ.
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
				 * Если данный параметр из валидации указан в шаблоне как параметр ввода.
				 * Или существует параметр на выходе из контроллера.
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
				 * Если параметр является массивом. Используем запись вида &param[]=1&param[]=2
				 */
					$save_key= '';
					$param_count = count($REQUEST_OUT);
					$_title=$_title.'[]';
					if ($param_count<=1){
					/**
					 * Если нет параметров на выходе или только один.
					 * Получить адресную часть для имени и значения параметра.
					 */
						$this->getURNForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[0],$seo);
						$key = $this->getFirstArrayKey($array_out['replace']);
						$save_key.= $array_out['replace'][$key];
					}else {
					/**
					 * Если параметров на выходе больше одного.
					 */
						for ( $i = 0; $i < $param_count; $i++ ) {
							$this->getURNForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[$i],$seo);
							if ($_value["inURLSave"]){
								/**
								 * Если нужно сохранить параметры в мульти формах. По умолчанию false.
								 * Пример, когда нужно сохранить параметры из прошлого вызова.
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
								 * Получить адресную часть для имени и значения параметра.
								 */
								$key = $this->getFirstArrayKey($array_out['replace']);
								$save_key.= $array_out['replace'][$key];
							}
						}
					}
					if ($_value["inURLSave"]){
					/**
					 * Когда нужно сохранить параметры из прошлого вызова.
					 * Удаляем параметры для замены.
					 */
						unset($array_out['replace']);
					}
				}else {
				/**
				 * Получить адресную часть для имени и значения параметра.
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
 * Получить адресную часть для имени и значения параметра.
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
 * Ссылка на массив с данными после обработки.
 * 
 * @param boolean $in_url 
 * 
 * Для подстановки части ссылки.
 * 
 * >>inURL = true, 
 * >>REQUEST = array(path_id => 777)
 * >>$result[inURL][default][PathID]
 * 
 * << &path_id=
 * 
 * 
 * false - по умолчанию.
 * 
 * >>inURL = false 
 * >>REQUEST = array(path_id => 777)
 * >>$result[inURL][default][PathID]
 * 
 * << &path_id=777
 * 
 * @param string $to 
 * Переменная сохраняться в $param[REQUEST][path_id]
 * Массив для ссылки URN будет доступен по ключу $result[inURL][PathID]
 *
 * @param string $_title 
 * Ключ в валидации, под каким хранить переменную.
 * 
 * 'search' => array()
 * 
 * @param array $REQUEST_OUT 
 * Параметр с выхода для подстановки.
 * 
 * @param boolean|array $seo 
 * Использовать ЧПУ?
 * 
 * @access public
 * @return void
 */
function getURNForTitleAndValue(&$array_out,$in_url,$to,$_title,$REQUEST_OUT,$seo){
	if ($in_url){
		/**
		 * Если данный параметр из валидации указан в шаблоне как параметр ввода.
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
			 * Если общий метод ЧПУ для контроллера, используем /param=value/
			 */
				$array_out[$to]='/'.$_title.'=';
				if (!empty($REQUEST_OUT)){
					/**
					 * Если в URN по умолчанию уже есть часть параметров.
					 * Заменяем на текущие параметры метода.
					 */
					$array_out['replace'][$to]='/'.$_title.'='.$REQUEST_OUT;
				}
			}else {
				/**
				 * Если стандартный режим генерации URN.
				 */
				$array_out[$to]='&'.$_title.'=';
				if (!empty($REQUEST_OUT)){
					/**
					 * Если в URN по умолчанию уже есть часть параметров.
					 * Заменяем на текущие параметры метода.
					 */
					$array_out['replace'][$to]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
			/**
			 * Если параметр не указан в шаблоне как параметр ввода.
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
				 * Если есть ЧПУ для метода.
				 * И СЕО режим не для всего контроллера.
				 */
					$array_out['pre'].='/'.$_title.'='.$REQUEST_OUT;
				}else {
					/**
					 * Если стандартный режим генерации URN.
					 */
					$array_out['pre'].='&'.$_title.'='.$REQUEST_OUT;
				}
			}
}

/**
 * Инициализируем контроллер.
 * 
 * задаётся в: 
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
 * @see EvnineController.controller_alias
 * @see Controllers.controller_alias
 * 
 * @param string $set_controller
 * Псевдонимы названий контроллеров.
 * @access public
 * @return void
 */
function setLoadController($set_controller) {
	if ($this->current_controller_name===$set_controller&&!empty($set_controller)){
	/**
	 * Если контроллер уже инициализирован и сейчас используется.
	 */
		return;
	}
	if (empty($set_controller)||
		empty($this->controller_alias[$set_controller])
	){
		/**
		 * В случае если контроллер не указан /evnine.config.php 
		 * используем контроллер указанный по умолчанию
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
	 * Если контроллер указан.
	 * /evnine.config.php:
	 * $this->controller_alias=array(
	 *  'controller_shorcut'=>'Controllers',
	 * );
	 */
		$this->current_controller_name = $set_controller;
	}
	if (empty($this->loaded_controller[$set_controller])){
	/**
	 * Если контроллер ещё не был загружен
	 */
		if (empty($this->result['LoadController'])){
			/**
			 * Устанавливаем для ответа evnine, какой контроллер запущен первым.
			 */
			$this->result['LoadController']=$this->current_controller_name;
		}
			$controller_file = $this->path_to.'controllers'.DIRECTORY_SEPARATOR.$this->controller_alias[$this->current_controller_name].'.php';
			if (file_exists($controller_file)){
			/**
			 * Если файл контроллера существует.
			 */
				$controller = $this->controller_alias[$this->current_controller_name];
			}elseif (is_array($this->controller_alias[$this->current_controller_name])){
			/**
			 * Если для контроллера задана отдельная папка.
			 */
				$controller_file = $this->path_to.$this->controller_alias[$this->current_controller_name]['path'].DIRECTORY_SEPARATOR.$this->controller_alias[$this->current_controller_name]['class_name'].'.php';
				if (file_exists($controller_file)){
					/**
					 * Если файл контроллера существует.
					 */
					$controller = $this->controller_alias[$this->current_controller_name]['class_name'];
				}else {
					/**
					 * Если файла контроллера из массива не существует, выводим ошибку.
					 */
					$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): controller file [array case]"'.$controller_file. '" not exist ';
					return;
				}
			}else {
			/**
			 * Если файла контроллера не существует, выводим ошибку.
			 */
				$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): controller file "'.$controller_file. '" not exist ';
				return;
			}
			include_once($controller_file);
			try {
			/**
			 * Пробуем получить данные.
			 */
				$this->loaded_controller[$set_controller] = new $controller($this->access_level);
			} catch (InvalidArgumentException $e){
			/**
			 * Если получили ошибку, сохраним её в массив параметров.
			 */
				$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): catch errors in the controller file "'.$controller_file. '"  <b>'.$e->getMessage().'</b>';
			}
			$this->current_controller=$this->loaded_controller[$set_controller]->controller;
		}elseif(!empty($this->loaded_controller[$set_controller])) {
		/**
		 * Если контроллер уже был загружен, используем его как текущий.
		 */
			$this->current_controller=$this->loaded_controller[$set_controller]->controller;
		}
}

/**
 * 
 * Базовый метод получения данных из контроллера по параметрам.
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
 * Параметры на входе.
 * 
 * @param boolean $debug 
 * Для отладки.
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
		 * Добавляем переданные параметры к параметрам из /evnine.config.php 
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
	 * Если указаны данные на входе.
	 */
		$this->result['REQUEST_IN']=$this->param['REQUEST'];
		$this->result['REQUEST_OUT']=array();
	}
	if (empty($this->result['inURLView'])){
		if ($this->param['ajax']&&
			!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])
		){
		/**
		 * Если используем AJAX и есть шаблон для отображения вида в контроллере.
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
		 * Во всех остальных случаях, используем шаблон указанный по умолчанию.
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
	 * Случай когда нужно передать содержимое <title></title> через контроллер.
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
	 * Если метод при загрузке не указан.
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
		 * Если метод по умолчанию существует, используем его.
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
		 * Если метод при загрузке указан.
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
		 * Если у метода указан шаблон.
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
		 * Если указанный метод не является методом по умолчанию.
		 */
			$this->result['ViewMethod'][$this->param['method']] = $this->param['method'];
		}
		$this->getPublicMethod($this->param);
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
		if ($this->param['ajax']===false){
		/**
		 * Если флаг работы через AJAX не указан.
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
			 * Если работает в под контроллере, и у родительского метода был закрыт доступ.
			 */
			if ($this->current_controller['page_level']!=0
					&&!empty($this->current_controller['parent']))
			{
			/**
			 * Проверяем глубину контроллера, если указан родитель подгружаем его.
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
				 * Загружаем контроллер родителя.
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
			 * Если в контроллере - ребенке не указан метод по умолчанию.
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
				 * Загружаем метод по умолчания в контроллере - родителе.
				 */
				$this->param['method']='default';
				$this->result['&rArr;'.$this->current_controller_name.':default'] = '&rArr;Method <font color="orange"><b>'.$this->current_controller_name.'::default</b></font> is load';
				if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])){
				/**
				 * Если у метода указан шаблон.
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
	 * Если указаны данные на входе.
	 */
		$this->result['REQUEST_OUT']=$this->param['REQUEST'];		
	}
}


/**
 * 
 * Отобразить доступные шаблоны для уровня доступа.
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
 *		// Доступ к отображению частей шаблона.
 *		// Зависит от доступа пользователя.
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
	 * Если шаблоны не указаны, остановим работу.
	 */
		return true;
	}
	if (!isset($this->result['Templates'])){
	/**
	 * Для объединения двух массивов шаблонов, инициализируем по ключу как массив.
	 */
		$this->result['Templates']=array();
	}
	for ( $i = 0; $i <= $this->param['PermissionLevel']; $i++ ) {
		if (!empty($available_templ[$i])){
		/**
		 * Проверяем для указания шаблону только доступному уровню пользователя.
		 * Используется $param['PermissionLevel'], проверит можно через метод в контроллере.
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
 * Вызвать методы в классах.
 * 
 * /controllers/ControllersExample.php
 *	'ModelsHelloWorld' => array(
 *		'getHelloWorld1',
 *		'getHelloWorld2'
 *	)
 * 
 * @param string $methods_class 
 * Класс для вызова методов.
 * 'ModelsHelloWorld'=>
 * 
 * @param array $methods_array 
 * Массив методов.
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
	 * Если метод не в массиве. Создаём массив для обработки.
	 * >>'ModelsHelloWorld' => 'getHelloWorld1',
	 * <<'ModelsHelloWorld' => array('getHelloWorld1'),
	 */
		$methods_array=array($methods_array);
	}
	if (
	/**
	 * Пропускаем обработку технической информации, валидацию, вид, доступ итд
	 */
		($methods_class[9]==='n'&&$methods_class[4]==='d'&&$methods_class[0]==='v')
		/**
		 *           0123456789
		 * Пропускаем 'validation' 
		 *             0123456789
		 */
		||
		($methods_class[4]==='L'&&$methods_class[2]==='U'&&$methods_class[0]==='i')
		/**
		 *           01234
		 * Пропускаем 'inURL...' 
		 *             01234
		 */
		||
		($methods_class[5]==='s'&&$methods_class[3]==='e'&&$methods_class[0]==='a')
		/**
		 *           012345
		 * Пропускаем 'access' 
		 *             012345
		 */
	){ 
	/**
	 * Пропускаем обработку технической информации, валидацию, вид, доступ итд
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
	 * Обработка возможных случаев ответов методов и заглушка.
	 * 
	 * class_method_case
	 * 
	 * _case = _false
	 *         654321
	 * Случай отрицательного ответа на метод class_isMethod
	 * 
	 * _case = _true
	 *         54321
	 * Случай положительного ответа на метод class_isMethod
	 *         
	 * _case = _dont_load
	 *              54321
	 * Заглушка от инициализации метода в классе. 
	 * class_method_dont_load - Это значит что метод - class_method загружаться не будет.
	 */
		if (preg_match("/_false$|_true$|_dont_load$/",$methods_class,$tmp)){
			/**
			 * Проверка возможных случаев.
			 */
				if ($tmp[0]=='_dont_load'){
				/**
				 * Чтобы не загружать методы дублирующие друг друга.
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
	 * Если метода не существует. 
	 * Пытаемся определить какой случай.
	 * Это ссылка на себя или ссылка на внешний контроллер.
	 * 
	 */
		if ($methods_class==='this'){
		/**
		 * Когда указана ссылка на текущий контроллер.
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
		 * Если метод существует в списке псевдонимов контроллеров.
		 * Случай когда ссылка на внешний контроллер.
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
			 * Загружаем контроллер с параметрами.
			 */
			$this->result['&lArr;'.$methods_class.':'.$this->param['method']] = '&lArr;Extend Method <font color="orange">'.$methods_class.'::'.$this->param['method'].'</font> is unload';
			$this->current_controller_name = $this->param['controller']=$save_param['controller'];
			$this->param['ajax']=$save_param['ajax'];
			$this->param['method']=$save_param['method'];
			$this->current_controller=$save_controller;
			return true;
		}else {
		/**
		 * Если метода не существует в списке псевдонимов контроллеров. Выводим ошибку.
		 */
				if (
					$methods_class['0']!=='M'&&
					$methods_class['1']!=='o'&&
					$methods_class['4']!=='l'
				){
				/**
				 * Исключаем авто подстановку пути к модели.
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
			 * Проверим случай когда модель в конфигурационном файле не указана.  
			 * Models
			 * 012345
			 */
				$this->isSetClassToLoadAndSetParam($methods_class,false);
		} else {
			$methods_class=$this->getFirstArrayKey($methods_array);
			if (count($methods_array[$methods_class])>1){
				/**
				 * Если методов больше одного, уменьшаем глубину на один уровень.
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
		 * Класс не инициализирован.
		 */
			if ($this->isSetClassToLoadAndSetParam($methods_class)&&!empty($methods_class)){
			/**
			 * Загружен ли класс? Если нет, загрузим и добавим параметры из конфига.
			 */
				$this->getDataFromMethod($methods_class,$methods_array);
			}
		}else{
		/**
		 * Инициализирован ли класс?
		 */
			$this->getDataFromMethod($methods_class,$methods_array);
		}
}

/**
 * 
 * Загружен ли класс? Если нет, загрузим и добавим параметры из конфига.
 * 
 * Путь до класса задаётся в 
 * /evnine.config.php
 *	$this->class_path=array(
 *		'ModelsHelloWorld'=>array('path'=>'/models/')
 *	)
 * ВАЖНО:
 * Без указания пути, считается что все модели лежат в /models/
 * 
 * @param string $methods_class  
 * Название класса для загрузки.
 * 
 * @param boolean $config_models
 * 
 * true - получить путь из конфигурационного файла.
 * 
 * false - использовать путь к модели по умолчанию /models/
 * 
 * @see EvnineConfig.__construct
 * @access public
 * @return boolean
 */
function isSetClassToLoadAndSetParam($methods_class,$config_models=true){
	$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
	if ($config_models&&file_exists($class_dir)){
		/**
		 * Если существует файл c классом, путь берем из конфига.
		 */
		include_once($class_dir);
		if (count($this->class_path[$methods_class]['param'])>0){
		/**
		 * Если в конфиге указаны параметры, добавим их в массив основных параметров.
		 */
			$this->param=array_merge($this->param,$this->class_path[$methods_class]['param']);
		}
		$this->loaded_class[$methods_class] = new $methods_class($this->loaded_class[$this->api]);
		return true;
	}elseif(!$config_models&&file_exists($this->path_to.'models'.DIRECTORY_SEPARATOR.$methods_class.'.php')){
		/**
		 * Случай с установкой пути для модели по умолчанию.  
		 */
		include_once($this->path_to.'models'.DIRECTORY_SEPARATOR.$methods_class.'.php');
		$this->loaded_class[$methods_class] = new $methods_class($this->loaded_class[$this->api]);
		return true;
	}else {
		/**
		 * Выводим ошибку, класс не найден.
		 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):Class not found <br />'.$class_dir.'';
		return false;
	}
}

/**
 * 
 * Получить данные из методов класса.
 * 
 * /controllers/ControllersExample.php
 *	'ModelsHelloWorld' => array(
 *		'getHelloWorld1',
 *		'getHelloWorld2'
 *	)
 *	
 * Методы могут начинаться с is, get, set. Окончание ModifierParam 
 * 
 * Исходя из типа метода, получаем данные от метода. 
 *
 * is - проверить правда ли?
 * get - получить данные.
 * set - установить данные
 * ModifierParam в конце - значит изменяет по ссылке параметр.
 *
 * @param string $methods_class 
 * Класс для вызова методов.
 * 'ModelsHelloWorld' =>
 * 
 * @param array $methods_array 
 * Массив методов.
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
		 * Есть ли доступ к методам? 
		 * Возможно доступа нет и хотим показать ошибку?
		 */
		foreach ($methods_array as $methods_array_title =>$methods_array_value){
		/**
		 * Для каждого метода, получим ключ для ответа.
		 * 'ModelsHelloWorld_getHelloWorld1'=>array()
		 */
		$array_key= $methods_class.'_'.$methods_array_value;
		if (!isset($this->result[$array_key]))
		{
		/**
		 * Каждый метод запускается только один раз!
		 * Но можно обойти в классе:
		 * 
		 * /models/ModelsHelloWorld.php
		 * function getFirstInitMethod($param){
		 *  echo 'Hello World!';
		 * }
		 * function getSecondInitMethod($param){
		 *  $this->getFirstInitMethod($param);
		 * }
		 * 
		 * Есть ли доступ к методу у данного пользователя?
		 */
			$isUserHasAccessForMethod = $this->isUserHasAccessForMethod($methods_class,$methods_array_value);
			if ($isUserHasAccessForMethod==='skip'){
			/**
			 * В случае когда к конкретному методу доступа нет, пропускаем его.
			 */
				$this->result[$array_key.'_no_access'] = 'no_access';
				continue;
			}elseif(!$isUserHasAccessForMethod) {
			/**
			 * В случае когда доступа нет.
			 */
				return false;
			}
			if ($this->param["setResetForTest"]==true){
			/**
			 * Для отладки, когда нужно сбросить данные перед получением ответа.
			 * Нужно для PHPUnitTest
			 */
				if ((method_exists($this->loaded_class[$methods_class],'setResetForTest'))){
					$this->loaded_class[$methods_class]->setResetForTest($this->param);
					$this->result[$methods_class.'_'.$methods_array_value.'_'.'setResetForTest']=true;
				}else {
				/**
				 * Если метода для сброса не существует, выведем ошибку.
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
			 * Если нужно обработать ошибку.
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
				 * Если указан тип ошибки в методе.
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
					 * Если ошибка есть, установим её в параметр. 
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
			 * Если класс для вызова метода содержит валидацию данных.
			 * 'method' => array(
			 *  'validation'=>array(
			 *   'date' => array('to'=>'Date','type'=>'str','required'=>'false','max' => '10'),
			 *  )
			 *  'ModelsValidation' => 
			 *    'isValidModifierParamFormError',
			 *    'isValidModifierParamFormError_false'=>array(),
			 *    'isValidModifierParamFormError_true'=>array(),
			 * )
			 * Определим какой метод использовать для валидации.
			 */
				if (empty($this->param['method'])&&
						!empty($this->current_controller['public_methods']['default'])
					){
				/**
				 * Если не указан метод инициализации и 
				 * в текущем контроллере есть метод по умолчанию.
				 * 
				 */
					$method_valid='default';
				}else {
				/**
				 * Если указан метод инициализации.
				 */
					$method_valid=$this->param['method'];
				}
				if (!empty($this->current_controller['public_methods'][$method_valid]['validation'])){
				/**
				 * Если в методе есть массива валидации перезаписывающий метод по умолчанию. 
				 * Метод по умолчанию находится в методе default
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
				 * Если в методе есть массива валидации формы, 
				 * перезаписывающий метод по умолчанию. 
				 * _form - когда данные передаются через форму.
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
				 * Если в методе есть массива валидации множественной формы, 
				 * перезаписывающий метод по умолчанию. 
				 * _multi_form - когда одна и та же форма может передаваться в любой другой метод.
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
				 * Когда объединяем валидацию из метода по умолчанию и 
				 * валидацию данных для конкретного метода.
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
					 * Если есть дополнительные параметры в валидации конкретного метода.
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
				 * В случае когда в методе по умолчанию нет данных для валидации, 
				 * но есть добавление для валидации.
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
				 * Если метод в классе существует. Пробуем получить данные.
				 */
					$answer = $this->loaded_class[$methods_class]->$methods_array_value($this->param);
				} catch (Exception $e) {
				/**
				 * Если получили ошибку, сохраним её в массив параметров.
				 */
					$answer=$this->param['info']=$e->getMessage();
				}
			}else {
			/**
			 * Если метод в классе не существует. Покажем ошибку.
			 */
				$this->result['ControllerError'][]=$answer=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Extend method not exist '.$methods_array_value.'';
			}
			if ($this->param['debug_param_diff']){
			/**
			 * Если отладка для сравнения параметров из метода в метод включена.
			 */
				if (isset($this->result['param'][$this->param['method']]['param_out'])){
				/**
				 * Если существуют параметры от предыдущего метода, сравним их.
				 */
					$this->result['param'][$this->param['method']][$array_key] = getForDebugArrayDiff($this->param,$this->result['param'][$this->param['method']]['param_out']);
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}else {
				/**
				 * Если параметров от предыдущего метода нет, сохраним их.
				 */
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}
			}
			if (!empty($this->param['info'])){
			/**
			 * Если в параметрах есть сообщение об ошибке. Допишем это сообщение в массив ответа от evnine.
			 */
				$this->result[$array_key][$this->param['info']] = $answer;
			}else {
			/**
			 * Если данных по ошибке нет, сохраним данные для ответа evnine.
			 */
				$this->result[$array_key] = $answer;
			}
			if ($methods_array_value[0]=='i'&&
				$methods_array_value[1]=='s'){
				/**
				 * Если метод содержит вопрос, используем метод для обработки случаев.
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
		 * Каждый метод запускается только один раз!
		 * Но, если метод содержит запрос на проверку условия.
		 *
		 * isHello
		 * 01
		 * 
		 * Используем прошлый ответ на проверку и метод для обработки случаев.
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
 * Есть ли доступ к методу у данного пользователя?
 * 
 * 'ModelsHelloWorld' => 'getHelloWorld1'
 * 
 * @param string $methods_class 
 * Класс для вызова методов.
 * 'ModelsHelloWorld' =>
 * 
 * @param string $methods_array_value 
 * Название метода.
 * => 'getHelloWorld1'
 *
 * @access public
 * @return bool
 */
function isUserHasAccessForMethod($methods_class,$methods_array_value) {
	if ($methods_class==='ModelsErrors'){
	/**
	 * Если класс для вывода ошибки, даём разрешение.
	 */
		return true;
	}
	$class_with_method = $methods_class.'::'.$methods_array_value;
	$access_for='';
	if (!empty($this->current_controller['access'][$class_with_method])
		&&isset($this->param['PermissionLevel'])
	){
	/**
	 * Проверим доступ для конкретного метода.
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
		 * Возвращаем доступ есть, когда метод в контроллере указан и 
		 * уровень текущего пользователя, выше или равен минимальному.
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
		 * В случае когда доступа нет, сохраняем данные для последующей проверки доступа.
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
			 * В случае когда к конкретному методу не указан метод для проверки доступа.
			 * Пропускаем его.
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
	 * Если доступ для конкретного метода не указан.
	 * Проверим, есть ли метод для проверки доступ.
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
		 * Делаем проверку, когда уровень пользователя, 
		 * выше или равен минимальному по умолчанию.
		 * Или массив доступа пустой.
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
			 * Если метод не указан, используем метод по умолчанию.
			 */
				$method='default';
			}else {
			/**
			 * Если метод указан, используем его для проверки. 
			 */
				$method=$this->param['method'];
			}
			if (isset($this->current_controller['public_methods'][$method]["access"]['default_access_level'])){
			/**
			 * Случай когда доступ указан в методе.
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
				 * Возвращаем доступ есть, когда доступ в методе указан и 
				 * уровень текущего пользователя, выше или равен минимальному.
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
				 * Если доступ для конкретного метода не указан.
				 * Проверим, есть ли метод для проверки доступ.
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
					 * В случае когда к конкретному методу не указан метод для проверки доступа.
					 * Возвращаем доступа нет.
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
			 * Случай когда доступ в методе отсутствует.
			 * Подтверждаем что доступ есть, 
			 * так как до этого проверили общий доступ для контроллера.
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
		 * В случае когда доступа нет, сохраняем данные для последующей проверки.
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
	 * Когда доступа нет, запускаем метод указанный по умолчанию для проверки доступа.
	 */
	$this->getPrivateMethod($run_method_case);
	if ($this->param['PermissionLevel']<$level_for_check){
	/**
	 * Проверяем после запуска, возможно метод изменил уровень доступа.
	 * И сейчас уже есть доступ.
	 * 
	 * Выполняем случай когда доступа нет, например выводим ошибку.
	 */
		$this->isGetMethodForAnswer($run_method_case,false);
		/**
		 * Устанавливаем для проверки доступа только один раз.
		 */
		$this->isHasAccessSaveCheck=false;
		return false;
	}else {
	/**
	 * Возвращаем доступа нет.
	 */
		$this->isGetMethodForAnswer($run_method_case,true);
	}
	return true;
}

/**
 * Обработка ответов методов с вопросом.
 * 
 * /controllers/ControllersHelloWorld.php
 *	'default'=>array(
 *		'ModelsHelloWorld'=>'isHello',
 *	 )
 * @param string $method
 * Название метода.
 * =>'isHello'
 * 
 * @param boolean $methods_case 
 * Ответ метода.
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
	 * Если ответа нет.
	 */
		if ($method==='isValidModifierParamFormError'){
		/**
		 * Если метод для проверки валидации, запрещаем доступ.
		 */
			$this->isHasAccessSaveCheck=false;//TODO check
		}
		$case= '_false';
	}else {
	/**
	 * Если ответ верный, установим ключ.
	 */
		$case= '_true';
	}
	if (!empty($this->current_controller['methods_case'])
		&&!empty($this->current_controller['methods_case'][$method.$case])
	){
	/**
	 * Если значение метода есть в массиве случаев методов.
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
	 * Если значение метода отсутствует в массиве случаев методов.
	 * Используем ключ для доступа в текущем методе.
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
	 * Запустить приватный метод.
	 */
	$this->getPrivateMethod($method);
}

/**
 * Запустить публичный метод
 * 
 * @link Controllers.controller
 * 
 * @param array $param 
 * Параметры со входа.
 * 
 * @access public
 * @return void
 */
function getPublicMethod($param) {
	if (!empty($this->current_controller['public_methods'][$param['method']])){
	/**
	 * Если метод существует.
	 */
		foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
		/**
		 * Вызвать методы в классах.
		 */
			$this->getMethodFromClass($_title,$_value);
		}
	}else {
	/**
	 * Если публичного метода не существует. Выведем ошибку.
	 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Method '.$param['method'].' not found in '.$this->current_controller_name.'';
		if (!isset($this->current_controller)){
		/**
		 * Если контроллера так же не существует. Выведем ошибку.
		 */
			$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Array $controller is not exist: <br />/controller/'.$this->controller_alias[$this->current_template].'.php <br /> var $controller;<br />function __construct($access_level){<br /> $this->controller = array(...);<br />;}';
		}
		if (!empty($this->current_controller['public_methods']['default'])){
		/**
		 * Если есть метод по умолчанию.
		 */
			$param['method']='default';
			foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
			/**
			 * Вызвать методы в классах.
			 */
				$this->getMethodFromClass($_title,$_value);
			}
		}
	}
}

/**
 * Запустить приватный метод.
 * 
 * @link Controllers.controller
 * 
 * @param string $method 
 * Приватный метод.
 * 
 * @access public
 * @return void
 */
function getPrivateMethod($method){
	if (!empty($this->current_controller['public_methods'][$this->param['method']][$method])){
	/**
	 * Если существует публичный метод в данном контроллере будем использовать его.
	 * Имеет приоритет выше, чем если указан как приватный метод.
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
	 * Если приватный метод существует. Будем использовать его для вызова.
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
	 * Если метод не существуем. Покажем ошибку.
	 */
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): In controller "'.$this->current_controller_name.'" not found Method "'.$method.'"';	
	} 
	foreach ($methods_callback as $method_title =>$method_value){
	/**
	 * Запускаем каждый метод класса.
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
 * Получить первый элемент массива как ключ или значение.
 * 
 * @param array $array 
 * Массив для обработки.
 *
 * @param boolean $get_value 
 * Нужно значение массива?
 * 
 * @access public
 * @return string
 */
function getFirstArrayKey($array,$get_value=false) {
	$tmp = each($array);
	list($key, $value)=$tmp;
	if (!$get_value){
	/**
	 * Если нужен ключ.
	 */
		return $key;
	}else {
	/**
	 * Если нужно получить значение параметра.
	 */
		return $value;
	}
}

/**
 *
 * Метод для тестирования контроллеров.
 * 
 * Для работы обязательна модель: ModelsPHPUnit
 * И последовательность вызова:
 *
 * /controllers/ControllersPHPUnit.php 
 *	'ModelsPHPUnit' => array(
 *		'getParamTest',
 *		'getParamCaseByParamTest',
 *		'getCountParamByParamTest',
 *		'getPHPUnitCode',
 *	)
 *
 * Параметры для настройки:
 * /evnine.config.php
 *	$this->controller_alias=array(
 *		'php_unit_test'=>'ControllersPHPUnit',
 *	);
 *	$this->param_const=array(
 *		// Общая папка для кэша.
 *		'CacheDir'=>'PHPUnitCache',
 *		// Папка для хранения PHPUnit тестов.
 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
 *		// Папка для хранения промежуточных данных.
 *		'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
 *	) 
 *	
 * Инициализация:
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
 * Метод для проверки.
 * 
 * @param array $array_init 
 * Массив для сохранения данных от внешнего вызова метода.
 * Для оптимизации скорости работы.
 * 
 * @param array $param 
 * Массив параметров инициализации.
 * 
 * @access public
 * @return void
 */
function getControllerForParamTest($method,$array_init,$param){
	if (empty($this->param_const['CacheDirPHPUnit'])){
	/**
	 * Если путь папки для кэша не указан.
	 */
		$this->param_const['CacheDirPHPUnit']='test'.DIRECTORY_SEPARATOR.'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit';
	}
	$methods_class='ModelsPHPUnit';
	if ($this->isSetClassToLoadAndSetParam($methods_class,$use_config=false)
			||$this->isSetClassToLoadAndSetParam($methods_class,$use_config=true)
	){
	/**
	 * Загружаем модель для тестирования.
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
		 * Если данные из кэша не получили.
		 */
			if (!empty($array_init)){
			/**
			 * Если от контроллера уже были получены данные, используем их.
			 */
				$array = $array_init;
			}elseif (method_exists($this,$method)){
			/**
			 * Запрашиваем метод в текущем классе. 
			 */
				$array = $this->$method($param);
			}
			/**
			 * Сохраняем в кэше.
			 */
			$this->loaded_class[$methods_class]->setSerData($file_name,$array,true);
		}
		//TODO check case $this->setRestetForTest();
		ob_end_flush();
		return $array;
	}else {
	/**
	 * Если модели тестирования не существует. Выводим ошибку.
	 */
		ob_end_flush();
		return 'ERROR not exist in  evnine.config.php
			$this->class_path=array(\'ModelsPHPUnit\'=>array(\'path\'=>\'models\'.DIRECTORY_SEPARATOR)';
	}
}

}
?>
