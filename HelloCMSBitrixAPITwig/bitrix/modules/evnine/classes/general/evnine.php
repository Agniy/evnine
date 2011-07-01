<?php
/**
 * en: Do not display errors
 * ru: Не выводить ошибки
 * error_reporting(0);
 *
 * en: Total output error
 * ru: Общий вывод ошибок
 * error_reporting(E_ERROR|E_RECOVERABLE_ERROR|E_PARSE|E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
 */
error_reporting(E_ERROR|E_RECOVERABLE_ERROR|E_PARSE|E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);

/** 
 * en: To inherit the configuration.
 * ru: Подключаем конфиг и наследуем от него настройки.
 */
require('evnine.config.php');

/** Controller 
 * 
 * en: The base controller.
 * ru: Базовый контроллер.
 * 
 * @uses Config
 * @package Controller
 * @version 0.3
 * @copyright 2009-2011 
 * @author ev9eniy.info
 * @updated 2011-06-01 17:53:02
 */
class Controller extends Config
{

	/** api
		* @access public
		* @var string
		* 
		* en: Alias API (MySQL, etc.) set in the $controller_alias of evnine.config.php.
		* ru: Название API (MySQL, итд) указанное в $controller_alias из evnine.config.php
		* evnine.config.php->api='ModelsMySQL';
		* evnine.config.php->api='ModelsJoomla';
		* evnine.config.php->api='ModelsBitrix';
		* 
		* en: The path to the class specified in the
		* ru: Путь до класса задаётся в 
		* evnine.config.php->class_path=array(
		*  'ModelsHelloWorld'=>array('path'=>'/models/')
		* )
		* 
		* @var string
		* @access public
		*/
	var $api;

	/** path_to 
		* 
		* en: Absolute path.
		* en: Used to connect classes of models and controllers.
		* en: IMPORTANT: All the controllers are in the folder /controllers/
		* ru: Абсолютный путь
		* ru: Используется при подключении классов моделей и контроллеров
		* ru: ВАЖНО: Все контроллеры находятся в папке /controllers/
		* 
		* @var string
		* @access public
		*/
	var $path_to;

	/** 
		* en: Path to the classes of models and variables are initialized by default
		* en: IMPORTANT: A weighty priority have parameters passed to
		* en: initializing the base controller
		* ru: Путь до классов моделей и переменные инициализации по умолчанию
		* ru: ВАЖНО: Весомым приоритетом обладают параметры, переданные 
		* ru: при инициализации базового контроллера
		* $evnine->getControllerForParam(
		*  array('hello'=>'config')
		* )
		* 
		* en: Set in:
		* ru: Задаются в:
		* evnine.config.php->class_path=array(
		* 
		* 'ModelsHelloWorld'=>array(
		* en: class name is the same as the class file
		* ru: Название класса, совпадает с названием файла класса
		* ModelsHelloWorld.php = class ModelsHelloWorld
		* 
		*   'path'=>'models'.DIRECTORY_SEPARATOR,
		*   ru: путь до класса
		*   'param'=>array(
		*    'hello'=>'config',
		*   )
		*  )
		* ),
		* 
		*/
	var $class_path;

	/** sef_mode
		* en: Flag of the SEF mode, set in the method $this->getControllerForParam
		* en: when pass to the controller to parse the string SEF URN
		* ru: Флаг работы в ЧПУ режиме, устанавливается в методе $this->getControllerForParam
		* ru: при условии, если в параметрах передана строка для SEF разбора URN
		* if (!empty($param['sef'])) {$this->sef_mode=true;}
		* 
		* @var bool
		* @access public
		*/
	var $sef_mode;

	/**
		* en: Aliases names controllers. In the folder /controllers/
		* ru: Псевдонимы названий контроллеров в папке /controllers/ 
		* ru: задаются в: 
		* evnine.config.php
		* $this->controller_alias=array(
		*  'helloworld'=>'ControllersHelloWorld',
		* );
		* 
		* @var array
		* @access public
		*/
	var $controller_alias;
	/**
		* en: Access levels are set at:
		* ru: Уровни доступа заданы в:
		* evnine.config.php
		* $this->access_level=array(
		*  'guest'=>'0',
		* );
		* 
		* @var array
		* @access public
		*/
	var $access_level;
	/**
		* en: Is there access to the methods? Based on the level of user access.
		* ru: Есть ли доступ к методам? Исходя из уровня доступа пользователя.
		* 
		* @var boolean
		* @access public
		*/
	
	var $isHasAccessSaveCheck;
	/** 
		* en: The name of the current template.
		* ru: Название текущего шаблона
		* setLoadController($current_controller){
		*  $this->$current_controller_name=$current_controller;
		* }
		* 
		* @var mixed
		* @access public
		*/
	var $current_controller_name;
	/**
		* en: Current controller.
		* ru: Текущий контроллер
		* 
		* @var string
		* @access public
		*/
	var $current_controller;
	/**
		* en: Loaded controllers.
		* ru: Загруженные контроллеры
		* 
		* @var mixed
		* @access public
		*/
	
	var $loaded_controller;
	/**
		* en: The parameters that are passed to each method
		* ru: Параметры, которые передаются каждому методу
		* 
		* @var array
		* @access public
		*/
	var $param;
	/**
		* en: Loaded classes.
		* ru: Загруженные классы
		* 
		* @var array
		* @access public
		*/
	var $loaded_class;
	/**
		* en: An array of responses.
		* ru: Массив ответов методов
		* 
		* @var array
		* @access public
		*/
	var $result;
	/**
		* en: To debug scripts
		* ru: Для отладки скриптов
		* 
		* @var boolean
		* @access public
		*/
	var $debug;
	/** __construct() 
		* en: The class constructor.
		* ru: Конструктор класса
		*/
function __construct(){
	$this->sef_mode=false;
	$this->param=array();
	parent::__construct();
	if (!empty($this->api)){
		/**
			* en: Initialize the API
			* ru: Инициализируем API
			*/
	if ($this->isSetClassToLoadAndSetParam($this->api)){
		/**
			* en: The class is initialized? If not, load it and add the parameters from the config.
			* ru: Загружен ли класс? Если нет, загрузим и добавим параметры из конфига.
			*/
			$this->loaded_class[$this->api]->setInitAPI($this->param);
		}
	}
}

/** setInitController($init_array)
 * 
 * en: Get the class initialization.
 * ru: Получить классы инициализации
 * 
 * @param array $init_array
 * @access public
 * @return void
 */
function setInitController($init_array) {
	foreach ($init_array as $menu_title => $menu_value) if ($menu_title!=''){
		if ($this->isHasAccessSaveCheck){
		/**
			* en: Is there access to the methods? Based on the level of user access.
			* ru: Есть ли доступ к методам? Исходя из уровня доступа пользователя.
			*/
			$this->getMethodFromClass($menu_title,$menu_value);
		}
	}
}

/** getControllerForParam($param)
	* 
	* en: Get data from the controller to the parameters.
	* ru: Получить данные из контроллера по параметрам.
	* 
	* @access public
	* @param array $param 
	* @return array
	*/
function getControllerForParam($param) {
	if (!empty($param['sef'])) {//Если URL в ЧПУ режиме
		/**
			* en: In the SEF? Defined in:
			* ru: Если есть строка для ЧПУ. Определяется в:
			* .htaccess
			* <IfModule mod_rewrite.c>
			* RewriteEngine On
			* RewriteRule .* - [L]
			* RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
			* </IfModule>	
			*/
		/**
			* en: Get data from the SEF string.
			* ru: Получить данные из ЧПУ строки
			*/
		$sef = $this->getSEFUrl($param['sef']);
		unset($param['sef']);
		/**
			* en: Set SEF flag.
			* ru: Установить метку что адреса нужно обрабатывать в ЧПУ режиме
			*/
		$this->sef_mode=true;
		unset($param['form_data']['sef']);
		/**
			* en: Set data from parse SEF.
			* ru: Установить параметры из УРЛа
			*/
		$param['method']=$sef['method'];
		$param['controller']=$sef['controller'];
		//
		/**
			* en: Merge data from SEF and POST.
			* ru: Объединить данные, если данные передаются POST и GET одновременно 
			*/
		$param['form_data']=array_merge($param['form_data'],$sef['form_data']);
	}
	
	if (!empty($param['form_data']['submit'])){
		/**
			* en: The case is processed multiple forms
			* ru: Случай когда обрабатываем несколько форм
			*/
		if ($first_key=(
			is_array($param['form_data']['submit'])
			?
			$this->getFirstArrayKey($param['form_data']['submit'])
			:
			$param['form_data']['submit'])
		){
		/**
			* en: Obtain the method of the first key from the name of the submit button
			* ru: Получаем метод по первому ключу из имени кнопки submit
			*/
			unset($param['form_data']['submit']);
			if (!empty($param['form_data'][$first_key]['c'])){
			/**
				* en: Obtain the values of the controller
				* ru: Получаем значения контроллера
				*/
				$param['controller']=$param['form_data'][$first_key]['c'];
				unset($param['form_data'][$first_key]['c']);
			}
			if (!empty($param['form_data'][$first_key]['m'])){
			/**
				* en: Obtain the values of the method
				* ru: Получаем значения метода
				*/
				$param['method']=$param['form_data'][$first_key]['m'];
				unset($param['form_data'][$first_key]['m']);
			}else {
				$param['method']=$first_key;
			}
			if (count($param['form_data'][$first_key])>0){
			/**
				* en: If the method of any linked data
				* en: merge them to the main parameters
				* ru: Если к методу привязанные какие-либо данные, 
				* ru: переносим их в основные параметры
				*/
				foreach ($param['form_data'][$first_key] as $_title =>$_value){
					$param[$_title]= $_value;
				}
			}
		}
	}elseif(empty($param['method'])) {
		/**
			* en: If the method is not specified
			* ru: Если метод не указан
			*/
		$param['method']='default';
	}
	$this->result=array();
	if (empty($this->result['LoadController'])){
		/**
			* en: In the results of the data set is first initialized:
			* ru: В результаты работы устанавливаем данные первой инициализации:
			*/
		$this->result['LoadController']=$param['controller'];
		$this->result['LoadMethod']=$param['method'];
	}
	/**
		* en: Type of operation AJAX
		* ru: Тип режима работы AJAX
		*/
	if ($param['ajax'][0]==='b') {
		/**
			* en: When you want to send only the body
			* ru: Случай, когда нужно отправить только тело 
			* HTML <body>...</body>
			*/
		$this->result['ajax']='Body';
		$param['ajax']=false;
	}elseif ($param['ajax'][0]==='a'){
		/**
			* en: The case where you want to run a method in AJAX mode
			* ru: Случай, когда нужно запускать метод в AJAX режиме
			*/
		$this->result['ajax']='True';
		$param['ajax']=true;
	}else {
		/**
			* en: Case in which call default method in the controller
			* ru: Случай, когда выполняется default метод в контроллере
			*/
		$this->result['ajax']='False';
		$param['ajax']=false;
	}
	/**
		* en: Obtain data from the master controller to the parameters
		* ru: Получим данные из главного контроллера
		*/
	$this->getDataFromController($param);
	/**
		* en: Reset method
		* ru: Сбросить данные о методе, так как в процессе работы метод может меняться.
		*/
	$this->param['method']=$param['method'];
	/**
		* en: Get the URN for the methods in the controller
		* en: Validation of the methods used by the controller
		* ru: Получить URN для методов в контроллере
		* ru: Используется валидация из методов контроллера
		*/
	$this->getURL();
	if (count($this->result['param'])>1){
		/**
			* TODO ADD if($this->param['debug']
			* en: For debug
			* ru: Для отладки, удалим параметры на выходи
			*/
		unset($this->result['param'][$this->param['method']]['param_out']);
	}
	return $this->result;
}

/** getSEFUrl($sef){
 * en: Get data from the SEF string.
 * ru: Получить данные из ЧПУ строки
 * 
 * en: The case when the SEF for the controller.
 * ru: Случай когда SEF для всего контроллера.
 * /user=62/
 * en: Flag in the controller: 
 * ru: Флаг в контроллере: 
 * 'inURLSEF' => true
 *
 * en: And when the SEF method:
 * ru: И когда ЧПУ для метода:
 * /controller/method/62-User.html 
 * 'method' => array(
 *  'inURLSEF' => array(
 *   'user_id' => '','User','.' => '.html',
 *  )
 * )
 * 
 * @param string $sef 
 * @access public
 * @return void
 */
function getSEFUrl($sef){
	$split = split('/',$sef);
	$split_count = count($split);
	$param['form_data']=array();
	$param['controller']=$split['0'];
	if ($split[$split_count-1]==='index'){
		/**
			* en: If the SEF mode for the controller, the ending URL - index.html
			* ru: Если ЧПУ режим для всего контроллера, есть окончание УРЛ - index.html
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
					$param['form_data'][$form_data_split[0]][]=$form_data_split[1];
				}else {
					$param['form_data'][$form_data_split[0]]=$form_data_split[1];
				}
			}
		}
	}else {
		/**
			* en: If the SEF mode for the method.
			* ru: Если ЧПУ режим для метода.
			* /62-user.html
			*/
		if ($split_count==3){
		/**
			* en: The case where there is a controller and method.
			* ru: Случай, когда есть и контроллер и метод
			*/
			$param['method']=$split['1'];
			$split_form_data = split('-',$split['2']);
		}elseif($split_count==2) {
		/**
			* en: The case where the method is not specified.
			* ru: Случай, когда метод не указан
			*/
			$split_form_data = split('-',$split['1']);
			$param['method']='default';
		}
		$split_count=count($split_form_data);
		/**
			* en: Load the controller. To parse the variable SEF.
			* ru: Загрузим контроллер. Для разбора переменных ЧПУ.
			*/
		$this->setLoadController($param['controller']);
		if (!empty($this->current_controller['public_methods'][$param['method']]['inURLSEF'])){
		/**
			* en: If there is data to parse the SEF 
			* en: In the method of the controller.
			* ru: Если есть данные для разбора ЧПУ 
			* ru: в методе контроллера.
			*/
			$i=0;
			foreach ($this->current_controller['public_methods'][$param['method']]['inURLSEF'] as $_title =>$_value)if ($_title!=='.'){
				if ($_title==='date'){
					/**
						* en: The date in SEF.
						* ru: Учтём дату в ЧПУ. 
						* 1901-11-10, 10-11-2014  
						*/
					$param['form_data'][$_title] = $split_form_data[$i].'-'.$split_form_data[$i+1].'-'.$split_form_data[$i+2];
					$i+=3;
				}else {
					$param['form_data'][$_title] = $split_form_data[$i];
					$i++;
				}
			}
		}
	}
	return $param;
}

/** getURL($seo = false) 
	* 
	* en: Get the URN for the methods in the controller
	* en: Validation of the methods used by the controller
	* ru: Получить URN для методов в контроллере
	* ru: Используется валидация из методов контроллера
	* 
	* $this->controller = array(
	*  // en: SEF mode flag in the controller.
	*  // ru: Включение ЧПУ режима в контроллере.
	*  'inURLSEF' => false,
	* )
	* 'inURLMethod' => array(
	*  // en: In the array are methods for which to create the URN.
	*  // ru: В массиве указаны методы для которых нужно создать ссылки.
	* )
	* 'public_methods' => array(
	*  'default' => array(
	*  // en: URLs are constructed on the basis of the validation parameters 
	*  // en: allowed in the method.
	*  // en: By default, data is taken from the default method.
	*  // ru: Урлы строятся исходя из валидации параметров допустимых в методе.
	*  // ru: По умолчанию данные берутся из default метода
	*   'validation'=> array()
	* )
	* 'method' => array(	
	*  // en: Further validation for the method.
	*  // en: It shall contain URNs.
	*  // en: These URNs used to generate the parameters.
	*  // ru: Дополнительная валидация для метода.
	*  // ru: В ней указываются урлы.
	*  // ru: Эти урлы используются для генерации параметров.
	*  'validation_add'=>array(		
	*   'date' => array('inURL' => true,'to'=>'Date','type'=>'str','required'=>'false','max' => '10',),
	*  ),
	* )
	*
	* en: The case where we want to specify an external controller.
	* en: And the reference to the method in an external controller.
	* ru: Случай, когда хотим указать внешний контроллер.
	* ru: И ссылку на метод во внешнем контроллере.
	* 'method' => array(
	* // en: 
	* // ru: ВАЖНО!!! Является обязательным для генерации ссылки
	*  'inURLExtController' => '', 
	*  'method' => '',
	* )
	* 
	* en: When the SEF method:
	* ru: Когда ЧПУ для метода:
	* /controller/method/62-User.html 
	* 'method' => array(
	*  'inURLSEF' => array(
	*   'user_id' => '','User','.' => '.html',
	*  )
	* )
	* 
	* @param boolean $seo 
	* @access public
	* @return void
	*/
function getURL($seo=false) {
	if ($this->current_controller['inURLSEF']){
	/**
		* en: The case when the SEF for the controller.
		* ru: Случай когда SEF для всего контроллера.
		*/
		$seo= true;
	}
	$default = $this->getURLFromArray(
	/**
		* en: Get a URN from an array of validation.
		* ru: Получить URN из массива проверки валидации
		*/
		$this->current_controller['public_methods']['default']['validation'],$seo
	);
	/**
		* en: Create a basic part of the URN.
		* en: In stating the controller and method.
		* ru: Создаётся базовая часть URN.
		* ru: В котором указывается контроллер и метод.
		*/
	$urn_base= $this->getTemplateURL($this->param['controller'],$seo);
	//
	if ($seo){
	/**
		* en: If the mode for the SEF controller.
		* en: Set the postfix string end of each URN.
		* ru: Если включен режим ЧПУ для всего контроллера.
		* ru: Установим строку завершения каждого URN.
		* TODO user for the param['seo_end_url']
		*/
		if (empty($this->param['sef_url'])){
				$postfix='/index.html';
			}else {
				$postfix=$this->param['sef_url'];
			}
	}else {
	/**
		* en: Do not use postfix URN.
		* ru: Не используем дополнения URN.
		*/
		$postfix='';
	}
	/**
		* en: SEF mode flag for the method.
		* ru: Флаг ЧПУ режима для метода.
		*/
	$seo_flag_save='';
	if (!empty($this->current_controller['public_methods']['default']['inURLMethod'])/*&&$this->param['ajax']==false*/){
	/**
		* en: If the default method is an array of methods for reference.
		* ru: Если в методе по умолчанию, указаны методы на которые делать ссылки.
		* 'inURLMethod' => array(
		* //en: Array to generate the URN (URI) to the method
		* //ru: Массив для генерации ссылок по методу
		*  'default',
		* )
		*/
		$url_method = $this->current_controller['public_methods']['default']['inURLMethod'];
		if(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
		/**
			* en: If there is a current method for adding an array.
			* en: Merge arrays default method and the method to add.
			* ru: Если существует в текущем методе массив для добавления
			* ru: Объединить массивы метода по умолчанию и метода для добавления.
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
			* en: The case when the current method, you specify a new array of methods to generate links.
			* en: Replace the array of methods to generate.
			* ru: Случай когда в текущем методе указан новый массив методов для генерации ссылок.
			* ru: Заменим массив методов для генерации.
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
		* en: The case when is not specified, the default method.
		* en: We use an array of methods specified in the current method for add.
		* ru: Случай когда не указан метод по умолчанию.
		* ru: Используем массив методов указанный в текущем методе для добавления.
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
		* en: In the case when the specified array of methods to overwrite.
		* ru: В случае когда указан массив методов для перезаписи.
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
		* en: For all methods, which create a URN.
		* ru: Для всех методов которым создаём URN
		*/
			$method = $url_method[$i];
			if (
				$this->current_controller['public_methods'][$method]['access']['default_access_level']
				>$this->param['PermissionLevel']
				||
				empty($this->current_controller['public_methods'][$method])
			){
			/**
				* en: Skipping processing of the case:
				* en: When there is no access to the method.
				* en: When this method is not specified.
				* ru: Пропускаем обработку в случае:
				* ru: Когда нет доступа к методу.
				* ru: Когда метод не задан.
				*/
				continue;
			}
		if (!empty($this->current_controller['public_methods'][$method]['inURLSEF'])){
			/**
				* en: If there is a SEF for the method. Work in the SEO mode.
				* ru: Если есть ЧПУ для метода, установим флаг работы в СЕО режиме.
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
				* en: Proceed to generate a URN to the parameters of validation.
				* ru: Переходим к генерации URN по параметрам валидации.
				*/
		if (!empty($this->current_controller['public_methods'][$method]['validation_add'])){
			/**
				* en: When validating the method with the addition of validation by default.
				* ru: Когда валидация в методе с добавлением к валидации по умолчанию.
				* 'current_method' => array(
				*  'inURLMethod' => array('current_method'),
				*  'validation_add' => array(),
				* )
				*/
			$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation_add'],$seo);
				if($seo&&$seo!==true){
				/**
					* en: If there is a SEF for the method.
					* en: And SEO mode is not for the controller.
					* ru: Если есть ЧПУ для метода.
					* ru: И СЕО режим не для всего контроллера.
					* 
					* en: Generate a link to the controller and method.
					* ru: Генерируем ссылку для контроллера и метода.
					*/
					$this->result['inURL'][$method]['pre']=
						$this->getTemplateURL($this->param['controller'],$seo)
						.$this->getMethodURL($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				} else {
				/**
					* en: If the standard mode of generating URN.
					* ru: Если стандартный режим генерации URN.
					*/
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURL($method,$seo,true).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation'])) {
			/**
				* en: When validation of the method overwrites the entire validation.
				* ru: Когда валидация в методе перезаписывает всю валидацию.
				* 
				* 'current_method' => array(
				*  'inURLMethod' => array('current_method'),
				*  'validation' => array(),
				* )
				*/
			if(!empty($this->current_controller['public_methods'][$method]['inURLExtController'])) {
				/**
					* en: Case when a URN to specify a different controller.
					* ru: Случай когда в URN нужно указать другой контроллер.
					* 
					* 'current_method' => array(
					*  'inURLExtController' => 'other_controller',
					*  'inURLMethod' => array('current_method'),
					*  'validation' => array(),
					*  )
					*/
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
					* en: Generate a link to an external controller and method.
					* ru: Генерируем ссылку для внешнего контроллера и метода
					*/
					$this->result['inURL'][$method]['pre']=
						$this->getTemplateURL($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURL($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
				* en: If not specified an external controller.
				* ru: Если не указан внешний контроллер
				*/
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
				/**
					* en: If there is a SEF for the method.
					* en: And SEO mode is not for the controller.
					* ru: Если есть ЧПУ для метода.
					* ru: И СЕО режим не для всего контроллера.
					*/
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				/**
					* en: Generate a link to the controller and method.
					* ru: Генерируем ссылку для контроллера и метода.
					*/
					$this->result['inURL'][$method]['pre']=
						 $this->getTemplateURL($this->param['controller'],$seo)
						.$this->getMethodURL($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
					/**
					* en: If the standard mode of generating URN.
					* ru: Если стандартный режим генерации URN.
					*/
					if ($method!=='default'){
					/**
						* en: If the method is not by default, we use to generate the validation.
						* ru: Если метод не по умолчанию, используем для генерации валидацию.
						* 
						* 'current_method' => array(
						*  'inURLMethod' => array('current_method'),
						*  'validation' => array(),
						* )
						*/
						$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					}
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURL($method,$seo).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_form'])) {
			/**
				* en: When data are needed for the form, not by URN. Used validation form.
				* ru: Когда нужны данные для формы, не для ссылки. Используются валидация формы.
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
					* en: Case when a URN to specify a different controller.
					* ru: Случай когда в URN нужно указать другой контроллер.
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
						* en: Generate a link to the controller and method.
						* ru: Генерируем ссылку для контроллера и метода.
						*/
					$this->result['inURL'][$method]['pre']=
						$this->getTemplateURL($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
							.$this->getMethodURL($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			} else {
				/**
					* en: If not specified an external controller.
					* ru: Если не указан внешний контроллер
					*/
				$this->result['inURL'][$method]['inputs']=
					$this->getInputFormText('c',$this->param['controller'])
					.$this->getInputFormText('m',$method)
					.$this->result['inURL'][$method]['inputs'];
				$this->result['inURL'][$method]['pre']=
					$urn_base;
				$this->result['inURL'][$method]['pre']=
					$urn_base
					.$this->getMethodURL($method,$seo)
					.$default['pre'];
			}
			$this->result['inURL'][$method]['post']=$postfix;
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_multi_form'])) {
			/**
				* en: When data are needed for multiple methods in one form, not by reference.
				* en: Used multiple forms of validation.
				* ru: Когда нужны данные для нескольких методов в одной форме, не для ссылки. 
				* ru: Используются валидация множественной формы.
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
					* en: Case when a URN to specify a different controller.
					* ru: Случай когда в URN нужно указать другой контроллер.
					*
					* en: Generate a input to the controller and method.
					* ru: Генерируем input для контроллера и метода.
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
						* en: Generate a link to the controller and method.
						* ru: Генерируем ссылку для контроллера и метода.
						*/
					$this->result['inURL'][$method]['pre']=
						$this->getTemplateURL($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
						.$this->getMethodURL($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
					$this->result['inURL'][$method]['post']=$postfix;
				}else{
				/**
					* en: If not specified an external controller.
					* ru: Если не указан внешний контроллер
					* 
					* 'current_method' => array(
					*  'inURLMethod' => array('current_method'),
					*  'validation_form' => array(),
					*  )
					*/
					$this->result['inURL'][$method]['inputs']=
					//$this->getInputFormText('t',$this->param['controller'],$method)
					//$this->getInputFormText('m',$method,$method)
					$this->result['inURL'][$method]['inputs'];
				}
		} else {
			/**
				* en: The case where no data are for the generation of validation links.
				* en: Use the validation of the method by default.
				* ru: Случай, когда не указаны данные для генерации ссылок из валидации.
				* ru: Используем валидацию из метода по умолчанию
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
				* en: Case when a URN to specify a different controller.
				* ru: Случай когда в URN нужно указать другой контроллер.
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
				* en: Generate a link to the controller and method.
				* ru: Генерируем ссылку для контроллера и метода.
				*/
				$this->result['inURL'][$method]['pre']=
					$this->getTemplateURL($this->current_controller['public_methods'][$method]['inURLExtController'],$seo)
					.$this->getMethodURL($this->current_controller['public_methods'][$method]['inURLExtMethod'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			}else {
			/**
				* en: If not specified an external controller.
				* ru: Если не указан внешний контроллер
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
					* en: If there is a SEF for the method.
					* en: And SEO mode is not for the controller.
					* ru: Если есть ЧПУ для метода.
					* ru: И СЕО режим не для всего контроллера.
					* 
					* en: Generate a link to the controller and method.
					* ru: Генерируем ссылку для контроллера и метода.
					*/
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					$this->result['inURL'][$method]['pre']=
					$this->getTemplateURL($this->param['controller'],$seo)
					.$this->getMethodURL($method,$seo)
					.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {
				/**
					* en: If the standard mode of generating URN.
					* ru: Если стандартный режим генерации URN.
					*/
					$this->result['inURL'][$method]['pre']=$urn_base.$this->getMethodURL($method,$seo).$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}
		if (count($this->result['inURL'][$method]['replace'])>0){
		/**
			* en: If the URN has a default of the parameters.
			* en: Replace the current method's parameters.
			* ru: Если в URN по умолчанию уже есть часть параметров.
			* ru: Заменяем на текущие параметры метода.
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
			* en: If there is a SEF for the method.
			* en: And SEO mode is not for the controller.
			* ru: Если есть ЧПУ для метода.
			* ru: И СЕО режим не для всего контроллера.
			* 
			* en: Reset SEO flag.
			* ru: Сбрасываем флаг
			*/
			$seo=$seo_flag_save;
			$seo_flag_save= '';
		}
	}
	$this->getinURLTemplate();
}

/** getinURLTemplate()
	* 
	* en: Different patterns can be obtained on the same call, different URN.
	* en: (Alternate comment)
	* en: Depending on the method, we get access
	* en: in the same way to the URN of different methods.
	* ru: Для разных шаблонов можно получать по одному и тому же вызову, разные URN.
	* ru: (Альтернативный комментарий) 
	* ru: В зависимости от метода, получаем доступ 
	* ru: по одному и тому же ключу к URN разных методов.
	* 
	* PHP:  $result[inURLTemplate][info][pre] $result[inURLTemplate][info][post]
	* PHP:  $result[inURLTemplate][error][pre] $result[inURLTemplate][error][post]
	* TWIG: {{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}
	* TWIG: {{ inURLTemplate.error.pre }}{{ inURLTemplate.error.post }}
	*
	* 'default' => array(
	*  'inURLTemplate' => array(
	*   'info' => 'info_method',
	*   'error' => 'error_method',
	*  ),
	* ),
	*
	* echo $result[inURLTemplate][info][pre] 
	* >> ?m=info_method
	* echo $result[inURLTemplate][error][pre] 
	* >> ?m=error_method
	* 
	* 'info_method' => array(
	*  'inURLTemplate' => array(
	*    'info' => 'default',
	*   ),
	* ),
	*
	* echo $result[inURLTemplate][info][pre] 
	* >> ?m=default
	*  
	* 'error_method' => array(
	*  'inURLTemplate' => array(
	*    'info' => 'error_method',
	*   ),
	* )
	* 
	* echo $result[inURLTemplate][info][pre] 
	* >> ?m=error_method
	*  
	* @access public
	* @return void
	*/
function getinURLTemplate(){
	if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'])){
	/**
		* en: If you have an array of URN variables in the current method.
		* ru: Если есть массив переменных ссылок в текущем методе.
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
			* en: If you have an array of URN variables in the method by default.
			* ru: Если есть массив переменных ссылок в методе по умолчанию.
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
		* en: The case when this method there is no variable URN, 
		* en: but there is a method by default.
		* ru: Случай когда в текущем методе нет переменных ссылок, 
		* ru: но есть в методе по умолчанию.
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

/** getTemplateURL($tmpl,$seo)
	* 
	* ru: Get the URN of the controller.
	* en: Получить URN контроллера.
	* 
	* @param string $tmpl 
	* @param string $seo 
	* @access public
	* @return string
	*/
function getTemplateURL($tmpl,$seo) {
	if ($seo){
		/**
			* en: If a general method for SEF controller, use /param=value/
			* ru: Если общий метод ЧПУ для контроллера, используем /param=value/
			* TODO check SEF for controller
			*/
		$urn_base='/'.$tmpl;
	} else {
		/**
			* en: If the SEF mode is not used.
			* ru: Если ЧПУ режим не используется.
			*/
		$urn_base='?c='.$tmpl;
		if ($this->sef_mode){
		/**
			* en: Flag of the SEF mode, set in the method $this->getControllerForParam
			* en: when pass to the controller to parse the string SEF URN
			* ru: Флаг работы в ЧПУ режиме, устанавливается в методе $this->getControllerForParam
			* ru: при условии, если в параметрах передана строка для SEF разбора URN
			* if (!empty($param['sef'])) {$this->sef_mode=true;}
			* TODO ADD name from config
			*/
			$urn_base='/index.php'.$urn_base;
		}
	}
	return $urn_base;
}

/** getMethodURL($method,$seo)
	* 
	* en: Get the URN of the method name.
	* ru: Получить URN из названия метода. 
	* 
	* @param string $method
	* @param string $seo
	* @access public
	* @return string
	*/
function getMethodURL($method,$seo) {
	if (empty($method)){
	/**
		* en: If the method is not specified, the method will be used by default.
		* ru: Если метод не указан, будет использован метод по умолчанию.
		*/
		return '';
	}else {
		if ($seo){
		/**
			* en: If a general method for SEF controller, use /param=value/
			* ru: Если общий метод ЧПУ для контроллера, используем /param=value/
			*/
			$urn_base.='/'.$method;
		}else {
		/**
			* en: If the SEF mode is not used.
			* ru: Если ЧПУ режим не используется.
			*/
			$urn_base.='&m='.$method;
		}
	}
	return $urn_base;
}

/** getInputFormText($name, $str, $multi_form=false)
	* 
	* ru: Get to the form to input.
	* en: Получить для формы значение input.
	* 
	* @param string $name 
	* en: Parameter name in form. 
	* ru: Имя параметра в форме.
	* 
	* @param string $value 
	* en: The value of the form.
	* ru: Значение параметра в форме.
	* 
	* @param string $multi_form 
	* ru: Name for the form, if you use the multiple form.
	* en: Имя для параметров формы, если используется множественная форма.
	* 
	* @access public
	* @return string
	*/
function getInputFormText($name,$value,$multi_form=false){
	if (empty($value)) {
	/**
		* en: If the value of a form not specified stop processing.
		* ru: Если значение для формы не указано останавливаем обработку.
		*/
		return;
	}else {
		if ($multi_form){
		/**
			* ru: Name for the form, if you use the multiple form.
			* en: Имя для параметров формы, если используется множественная форма.
			*/
			$name=$multi_form.'['.$name.']';
		}
		return '<input type="hidden" name="'.$name.'" value="'.$value.'"/>';
	}
}

/** getInputsFromArray($validate,$multi_form=false)
	*
	* en: Get an array of validation - the value of options in the form.
	* ru: Получить для массива валидации - значение параметров в форме.
	*
	* 'public_methods' => array(
	*  'ext_search' => array(
	*   'inURLMethod' => array('ext_search'),
	*   'validation_form'=> array(
	*     'search' => array(
	*       'to'=>'SearchTitle',
	*       'inURL' => true,
	*       'type'=>'str',
	*       'required'=>'true',
	*       'error'=>'search',
	*       'min'=>'3',
	*       'max' => '250'
	*      )
	*   )
	* )
	* 
	* PHP ['...']:
	* <form action="<?=$result[inURL][ext_search][pre].$result[inURL][ext_search][post]?>" method="get"> 
	*  <?=$result[inURL][ext_search][inputs]?>
	*  <input name="<?=$result[inURL][ext_search][SearchTitle]?>" value="<?=$result[param_out][SearchTitle]?>" type="text" />
	*  <input name="<?=$result[inURL][ext_search][submit]?>" type="submit" value=" "/>
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
	* @param string $multi_form
	* ru: Name for the form, if you use the multiple form.
	* en: Имя для параметров формы, если используется множественная форма.
	* 
	* @access public
	* @return array
	*
	* en: Array with the parameters of the form.
	* ru: Массив с параметрами формы.
	*/
function getInputsFromArray($validate,$multi_form=false) {
	$inputs='';
	$array_out=array();
	$pre_fix=$post_fix= '';
	foreach ($validate as $_title =>$_value){
		//TODO 58% ------------------------------{ 30.06.2011 }------------------------------
		/**
			* en:
			* ru: 
			*/
		$REQUEST_OUT=$this->result['REQUEST_OUT'][$_value['to']];
		if ($_value['inURL']){
		/**
			* en:
			* ru: 
			*/
			if ($_value['is_array']){
			/**
				* en:
				* ru: 
				*/
				$_title.='[]';
			}
			$array_out[$_value['to']]=$_title;
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				/**
					* en:
					* ru: 
					*/
				if ($seo===true){//Если обший метод ЧПУ используем /param=value/
				/**
					* en:
					* ru: 
					*/
					$array_out['replace'][$_value['to']]='/'.$_title.'='.$REQUEST_OUT;
				}elseif($seo&&$seo!==true)/*if SEF*/ {//Если указано формирование ЧПУ
				/**
					* en:
					* ru: 
					*/
					$array_out['replace'][$_value['to']]=$REQUEST_OUT;
				}else {
				/**
					* en:
					* ru: 
					*/
					$array_out['replace'][$_value['to']]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
				/**
					* en:
					* ru: 
					*/
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				/**
					* en:
					* ru: 
					*/
				if ($multi_form&&$_value['multi_form']){
				/**
					* en:
					* ru: 
					*/
					$pre_fix=$multi_form.'[';$post_fix= ']';
				}
				if ($_value['is_array']){
				/**
					* en:
					* ru: 
					*/
					foreach ($REQUEST_OUT as $REQUEST_OUT_title =>$REQUEST_OUT_value){
						$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'[]" value="'.$REQUEST_OUT_value.'"/>';
					}
				}else {
					/**
					* en:
					* ru: 
					*/
				$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'" value="'.$this->result['REQUEST_OUT'][$_value['to']].'"/>';
				}
				if ($multi_form&&$_value['multi_form']){
				/**
					* en:
					* ru: 
					*/
					$pre_fix=$post_fix= '';
				}
			}
		}
	}
	$array_out['inputs']= $inputs;
	return $array_out;
}

/** getURLFromArray($validate,$seo=false,$is_add=false)
 * 
 * en: Get a URN from an array of validation.
 * ru: Получить URN из массива проверки валидации
 * 
 * @param array $validate 
 * @param boolean $seo 
 * @param boolean $is_add 
 * 
 * @access public
 * @return void
 */
function getURLFromArray($validate,$seo=false,$is_add=false) {
	if($seo&&$seo!==true)/*if SEF*/ {//Если указано формирование ЧПУ
		foreach ($seo as $seo_title =>$seo_value)if ($seo_title!=='.'){
			if ($validate[$seo_title]['inURL']){//Случай когда в шаблоне используем параметр в УРЛе
				$array_out[$validate[$seo_title]['to']]='-';
				if (!empty($this->result['REQUEST_OUT'][$validate[$seo_title]['to']]))
					$array_out['replace'][$validate[$seo_title]['to']]='-'.$this->result['REQUEST_OUT'][$validate[$seo_title]['to']];;
			}else {//Когда передаем данные в шаблон целиком без имзенений
				if (empty($array_out['pre'])) {
					$array_out['pre'].= '/';
				}else {
					$array_out['pre'].= '-';
				}
				if (!empty($seo_value)){
						$array_out['pre'].= $seo_value;
				} else {
					$array_out['pre'].=$this->result['REQUEST_OUT'][$validate[$seo_title]['to']];
				}
			}
		}
		if (empty($array_out['pre'])) {
			$array_out['pre'].= '/';
		}
		return $array_out;  
	}else {//Если не указано в методе что есть чпу
		foreach ($validate as $_title =>$_value){
			$REQUEST_OUT = $this->result['REQUEST_OUT'][$_value['to']];
			if (!empty($REQUEST_OUT)||$_value['inURL']){
				if ($_value['is_array']){
					$save_key= '';
					//echo '#$_value["is_array"]: <pre>'; print_r($_value["is_array"]); echo "</pre><br />\r\n";
					$param_count = count($REQUEST_OUT);
					//echo '#$REQUEST_OUT: <pre>'; print_r($REQUEST_OUT); echo "</pre><br />\r\n";
					//echo '#$param_count: <pre>'; print_r($param_count); echo "</pre><br />\r\n";
					//$_value['inURLSave']
					//echo '#$_value["inURLSave"]: <pre>'; print_r($_value["inURLSave"]); echo "</pre><br />\r\n";
					$_title=$_title.'[]';
					if ($param_count<=1){
						//if ($_value["inURLSave"]){
							//$_title=$_title.'[]';
						//}
						$this->getURLForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[0],$seo);
						$key = $this->getFirstArrayKey($array_out['replace']);
						$save_key.= $array_out['replace'][$key];
					}else {
						//echo '#$_title: <pre>'; print_r($_title); echo "</pre><br />\r\n";
						//echo '#$param_count: <pre>'; print_r($param_count); echo "</pre><br />\r\n";
						for ( $i = 0; $i < $param_count; $i++ ) {
							$this->getURLForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT[$i],$seo);
							if ($_value["inURLSave"]){
								$key = $this->getFirstArrayKey($array_out['replace']);
								$save_key.= $array_out['replace'][$key];
							}
						}
					}
					if ($_value["inURLSave"]){
						//$save_key
						//$array_out[$key]=$array_out[$key];
						unset($array_out['replace']);
					}
					//echo '#$array_out: <pre>'; print_r($array_out); echo "</pre><br />\r\n";
				}else {
					$this->getURLForTitleAndValue($array_out,$_value['inURL'],$_value['to'],$_title,$REQUEST_OUT,$seo);
				}
		}
	}
	}
	return $array_out;
}

function getURLForTitleAndValue(&$array_out,$in_url,$to,$_title,$REQUEST_OUT,$seo){
	if ($in_url){//Случай когда в шаблоне используем параметр в УРЛе
				if ($seo===true){//Если обший метод ЧПУ используем /param=value/
					$array_out[$to]='/'.$_title.'=';
					if (!empty($REQUEST_OUT))
						$array_out['replace'][$to]='/'.$_title.'='.$REQUEST_OUT;
				}else {//Если не используем ЧПУ
					$array_out[$to]='&'.$_title.'=';
					if (!empty($REQUEST_OUT))
						$array_out['replace'][$to]='&'.$_title.'='.$REQUEST_OUT;
					//echo '#$array_out: <pre>'; print_r($array_out); echo "</pre><br />\r\n";
				}
			}else {//Когда передаем данные в шаблон целиком без имзенений
				if  ($seo===true){//Если обший метод ЧПУ используем /param=value/
					$array_out['pre'].='/'.$_title.'='.$REQUEST_OUT;
				}else {//Если не используем ЧПУ
					$array_out['pre'].='&'.$_title.'='.$REQUEST_OUT;
				}
			}
}

/** Инициализация контроллера в буффер
 * 
 * @param mixed $template 
 * @access public
 * @return void
 */
function setLoadController($set_controller) {
	if ($this->current_controller_name===$set_controller&&!empty($set_controller)){
		return;
	}
	if (empty($set_controller)||
		empty($this->controller_alias[$set_controller])
	){//В случае если шаблона нет в списке контроллеров или если шаблон не установлен
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Controller "'.$set_controller. '" not found '.$this->current_controller_name.'';
		$this->param['controller']=$this->current_controller_name = $this->param_const['default_controller'];
	}else {
		$this->current_controller_name = $set_controller;
	}
	if (empty($this->loaded_controller[$set_controller])){
		if (empty($this->result['LoadController'])){
			$this->result['LoadController']=$this->current_controller_name;
		}
			$controller_file = $this->path_to.'controllers'.DIRECTORY_SEPARATOR.$this->controller_alias[$this->current_controller_name].'.php';
			if (file_exists($controller_file)){//Если существует файл
			//Подключить класс
				include_once($controller_file);
				$template_controller = $this->controller_alias[$this->current_controller_name];//Получаем контроллер
				$this->loaded_controller[$set_controller] = new $template_controller($this->access_level);//Создаём копию данных контрллера
				$this->current_controller=$this->loaded_controller[$set_controller]->controller;
			}
		}elseif(!empty($this->loaded_controller[$set_controller])) {
			$this->current_controller=$this->loaded_controller[$set_controller]->controller;
		}
}

/** Получить данные из контроллера
 * 
 * @access public
 * @param template
 * @return array
 */
function getDataFromController($param,$debug=false) {
	//echo '<pre>#$param: '; print_r($param); echo '</pre>';
	$this->isHasAccessSaveCheck=true;
	//TODO DELETE
	//Для тестирования, перезаписываем параметры по умолчанию, на переданные для теста
	$this->param=$this->param_const;
	foreach ($param as $param_title =>$param_value){
		if (isset($param[$param_title])){
				$this->param[$param_title]=$param[$param_title];
		} 
	}
	$this->debug=$debug;
//	if ($debug!=true){
//		$this->debug=false;
	//	}
	$this->setLoadController($param['controller']);
	/*
		if (!isset($this->result['ajax'])){
		if (empty($this->param['ajax'])){
			$this->result['ajax']='False';
		}	else {
			$this->result['ajax']='True';
		}
		}
	*/
	$this->result['REQUEST_IN']=$this->param['form_data'];
	$this->result['REQUEST_OUT']=array();
	//if (/*$this->current_controller['page_level']!=0&&*/empty($this->param['method'])){
		//$this->param['method']= 'default';
	//}
	if (empty($this->result['View'])){
		if ($this->param['ajax']&&
			!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView'])
		){//Если подгружаем метод AJAXом
			if(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLView']))
				$this->result['View']=$this->current_controller['public_methods'][$this->param['method']]['inURLView'];
		}else {
			if (!empty($this->current_controller['view']))
			$this->result['View']=$this->current_controller['view'];
		}
	}
	if (empty($this->result['Title'])&&!empty($this->current_controller['title']))
		$this->result['Title']=$this->current_controller['title'];

	//Если загрузка по умолчанию, выбираем метод по умолчанию из публичнх методов
	if (empty($this->param['method'])){
	//if (!isset($this->param['method'])||empty($this->param['method'])){
		//$html.= 
		$this->setInitController($this->current_controller['init'],$this->current_controller_name);//Инициализируем данные
		if (isset($this->current_controller['public_methods']['default'])){
			$this->param['method']='default';
			$this->getPublicMethod($this->param);
		}
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
	}else {
//		echo 'getPublicMethod =>><br />';
		if ($this->param['method']!=='default')
			$this->result['ViewMethod'][$this->param['method']] = $this->param['method'];
		if (!empty($this->current_controller['public_methods'][$this->param['method']]['view']))
			$this->result['ViewMethod'][$this->current_controller['public_methods'][$this->param['method']]['view']]=$this->current_controller['public_methods'][$this->param['method']]['view'];
		$this->getPublicMethod($this->param);
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
		//echo '#$this->param["ajax"]: <pre>'; print_r($this->param["ajax"]); echo "</pre><br />\r\n";
		if ($this->param['ajax']===false){
			//Если в методе не было доступа, включаем проверку опять для инициализации метода по 
			$this->isHasAccessSaveCheck=true;
			if ($this->current_controller['page_level']!=0
					&&!empty($this->current_controller['parent']))
			{
				$parent = $this->current_controller['parent'];
				$this->result['&rArr;'.$parent.':parent-default'] = '&rArr;Parent Method <font color="orange">'.$parent.'::parent-default</font> is load';//Init method load double fix
				//Загружаем шаблон родителя 
				$save_template = $this->param['controller'];
				$save_method  = $this->param['method'];
				$this->param['method']= 'default';
				$this->param['controller']=$this->current_controller['parent'];
				//Выполняем в нем функции, с учётом текущего массива результатов
				$this->getDataFromController($this->param,false);
				$this->result['&lArr;'.$parent.':parent-default'] = '&lArr;Parent Method <font color="orange">'.$parent.'::parent-default</font> is unload';//Init method load double fix		
				$this->param['method']= $save_method;
				$this->param['controller']=$save_template;
			}elseif (!empty($this->current_controller['public_methods']['default'])){
				$this->setInitController($this->current_controller['init'],$this->current_controller_name);//Инициализируем данные
				//Загружаем метод по умолчания в главном контроллере
				$this->param['method']='default';
				$this->result['&rArr;'.$this->current_controller_name.':default'] = '&rArr;Extend Method <font color="orange">'.$this->current_controller_name.'::default</font> is load';//Init method load double fix
				$this->getPublicMethod($this->param);
				$this->result['&lArr;'.$this->current_controller_name.':default'] = '&lArr;Extend Method <font color="orange">'.$this->current_controller_name.'::default</font> is unload';//Init method load double fix
			}
			$this->getAvailableTemplates($this->current_controller['templates'],$this->current_controller_name);
		}
	}
	$this->result['REQUEST_OUT']=$this->param['form_data'];		
	//Получаем доступные шаблоны
	//Получаем доступные приватные методы доступные юзерам
	//$html.='<br/><font color="green">public_methods:</font> '.$this->getAvailableMethods($this->current_controller['public_methods'],$this->current_controller_name,'green');
	//Получаем доступные приватные методы доступные классам
	//$html.='<br/><font color="orange">private_methods</font>: '.$this->getAvailableMethods($this->current_controller['private_methods'],$this->current_controller_name,'orange');
	//Выводим подкотовленный массив в передаче данных в шаблон
//	if ($this->debug) {
//	echo $html;
//	}
	//return $this->result;
}


/** getAvailableTemplates() Отобразить доступные шаблоны
 * 
 * @param mixed $available_templ 
 * @access public
 * @return void
 */
function getAvailableTemplates($available_templ) {
	//echo '<pre>#$available_templ: '; print_r($available_templ); echo '</pre>';
	//echo '#$this->param["PermissionLevel"]: '.$this->param["PermissionLevel"]."<br />\r\n";
	if (count($available_templ)==0)
		return true;
	if (!isset($this->result['Templates']))
		$this->result['Templates']=array();
	for ( $i = 0; $i <= $this->param['PermissionLevel']; $i++ ) {//Проверка для указания меню только определенному типу юзер
	if (!empty($available_templ[$i]))
		$this->result['Templates'] = array_merge($this->result['Templates'],$available_templ[$i]);
	}
}

/** Отобразить доступные методы
 * Отобразить доступные методы
 * 
 * @param mixed $priv_methods 
 * @access public
 * @return void
 */
//function getAvailableMethods($priv_methods,$template,$color) {
//	echo '<pre>#$priv_methods: '; print_r($priv_methods); echo '</pre>';
	//foreach ($priv_methods as $templ_title =>$templ_value)if ($templ_title!=''){
		//$html.='<br /><font color="'.$color.'">Method:</font> <a href="'
			//.$_SERVER['PHP_SELF']
			//.'?template='.$template.'&method='
			//.$templ_title
			//.'">'
			//.$templ_title
			//.'</a>';
	//}
	//return $html;
//}


/** Вызвать класс и метод в классе
 * 
 * @access public
 * @return void
 */
function getMethodFromClass($methods_class,$methods_array) {
	if (!is_array($methods_array)){//Если метод не в массиве
			$methods_array=array($methods_array);//Создаём массив для последующей обработки
	}
	if (//Пропускаем техническую инфомацию, валидцаию, ссылки и доступ
			(//'validation'
			$methods_class[9]==='n'&&
			//$methods_class[7]==='i'&&
			//$methods_class[6]==='t'&&
			//$methods_class[5]==='a'&&
			$methods_class[4]==='d'&&
			//$methods_class[3]==='i'&&
			//$methods_class[2]==='l'&&
			//$methods_class[1]==='a'&&
			$methods_class[0]==='v'
		)
		||
			(//inURL
			$methods_class[4]==='L'&&
			//$methods_class[3]==='R'&&
			$methods_class[2]==='U'&&
			//$methods_class[1]==='n'&&
			$methods_class[0]==='i'
		)
		||
		(//access
			$methods_class[5]==='s'&&
			//$methods_class[4]==='s'&&
			$methods_class[3]==='e'&&
			//$methods_class[2]==='c'&&
			//$methods_class[1]==='c'&&
			$methods_class[0]==='a'
		)
//		||
//		(//sef
			//$methods_class[5]==='f'&&
			//$methods_class[4]==='s'&&
//			$methods_class[3]==='_'&&
//			$methods_class[2]==='f'&&
//			$methods_class[1]==='e'&&
//			$methods_class[0]==='s'
//		)
	){ 
		return false;
	}
	$methods_class_count = strlen($methods_class);
	if (
		$methods_class[$methods_class_count-6]==='_'
		||                                      
		$methods_class[$methods_class_count-5]==='_'
	)
		if (preg_match("/_false$|_true$|_dont_load$/",$methods_class,$tmp)){
				if ($tmp[0]=='_dont_load'){//Если нет AJAX не загружать повторно методы дублируюшие функционал текущего
					$array_key = str_replace($tmp[0],'',$methods_class);
					$this->result[$array_key] = 'STOP_LOAD';//Init method load double fix
				}
				return true;
		}
	//Если метода не существует
	if (!isset($this->class_path[$methods_class])){
		if ($methods_class==='this'){
			$methods_class= $this->param['controller'];
		}
		//ev9eniy 20.06.2011 правка
		if (isset($this->current_controller[$methods_class])){
			$is_save_validation_param= false;
			$save_param['controller']= $this->param['controller'];
//edit_univer_fix			$save_param['form_data']= $this->param['form_data'];
			$save_param['ajax']= $this->param['ajax'];
			//echo 'getPublicMethod #$save_param["ajax"]: '.$save_param["ajax"]."<br />\r\n";
			$save_param['method']= $this->param['method'];
			$save_controller = $this->current_controller;
			//Загружаем шаблон родителя 
			$this->param['controller']=$methods_class;
			$this->param['ajax']=true;
			$this->param['method']=$this->getFirstArrayKey($methods_array,'first_value');//Берем первый по ключу
			$this->result['&rArr;'.$methods_class.':'.$this->param['method']] = '&rArr;Extend Method <font color="orange">'.$methods_class.'::'.$this->param['method'].'</font> is load';//Init method load double fix
//edit_univer_fix			if (!isset($this->result['ModelsValidation_isValidModifierParamFormError'])){
//				$is_save_validation_param= true;
//			}
				//Выполняем в нем функции, с учётом текущего массива результатов
			$this->getDataFromController($this->param,false);
			$this->result['&lArr;'.$methods_class.':'.$this->param['method']] = '&lArr;Extend Method <font color="orange">'.$methods_class.'::'.$this->param['method'].'</font> is unload';//Init method load double fix
			$this->current_controller_name = $this->param['controller']=$save_param['controller'];
//edit_univer_fix			if (!$is_save_validation_param)
//				$this->param['form_data']=$save_param['form_data'];
			$this->param['ajax']=$save_param['ajax'];
			$this->param['method']=$save_param['method'];
			$this->current_controller=$save_controller;
			return true;
		}else {
			$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):Extend controller not exist '.$methods_class.'';
		}
		$methods_class=$this->getFirstArrayKey($methods_array);//Берем первый по ключу
		if (count($methods_array[$methods_class])>1)//Если методов больше одного, уменьшаем глубину на один уровень
			$methods_array=$methods_array[$methods_class];
	}
		if (empty($this->loaded_class[$methods_class])&&!empty($methods_class)){
			if ($this->isSetClassToLoadAndSetParam($methods_class)){
				$this->getDataFromMethod($methods_class,$methods_array);
			}
			
		}elseif(!empty($this->loaded_class[$methods_class])) {
			$this->getDataFromMethod($methods_class,$methods_array);
		}
}

/** isSetClassToLoadAndSetParam($methods_class)
 * 
 * en: The class is initialized? If not, load it and add the parameters from the config.
 * ru: Загружен ли класс? Если нет, загрузим и добавим параметры из конфига.
 * 
 * @param string $methods_class  
 * @access public
 * @return boolean
 */
function isSetClassToLoadAndSetParam($methods_class){
	$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
	if (file_exists($class_dir)){//
		/**
			* en: There exists an a class file? path taken from the config.
			* ru: Если существует файл c классом, путь берем из конфига.
			*/
			include_once($class_dir);
			if (count($this->class_path[$methods_class]['param'])>0){
			/**
				* en: If parameters are specified in the config file, add them to an array of main parameters.
				* ru: Если в конфиге указаны параметры, добавим их в массив основных параметров.
				*/
				$this->param=array_merge($this->param,$this->class_path[$methods_class]['param']);
			}
			$this->loaded_class[$methods_class] = new $methods_class($this->loaded_class[$this->api]);//Создём экземпляр
			return true;
		}else {
			return false;
		}
}

/** Получить данные из метода класса 
 *
 * 
 * @param mixed $class_dir 
 * @param mixed $methods_class 
 * @param mixed $methods_array 
 * @access public
 * @return html
 */
function getDataFromMethod($methods_class,$methods_array){
	//echo '<br />=>>>>>>>>>>>>#getDataFromMethod: <br />'."<br />\r\n";
	//echo '#$methods_class: '.$methods_class."<br />\r\n";
	//echo '<pre>#$methods_array: '; print_r($methods_array); echo '</pre>';
	if ($this->isHasAccessSaveCheck||$methods_class==='ModelsErrors')
	foreach ($methods_array as $methods_array_title =>$methods_array_value){
	//При AJAX
	//Сначала запускаем метод
	//Потом запускаем инициализацию
	//Что бы не запускат по два раза, делаем проверку, а не был ли запушенн данный метод ранее
	//Остановить обработку если были ошибки, 
	//Введено для обработки публичных методов в сообщениях 
	//и когда реакция на проверку может быть разной
	//			'select_user' => array(
	//						'ModelsValidation' => 'isValidModifierParamFormError',
	//						'ModelsUsersMsg'=>'isGetMsgFromUser'
		//					
		$array_key= $methods_class.'_'.$methods_array_value;
		if (!isset($this->result[$array_key]))
		{
		//if(function_exists(print_r21))$query[ '#$methods_class.:.$methods_array_value' ]=$methods_class.':'.$methods_array_value;else echo '<pre>'.print_r2($methods_class.':'.$methods_array_value).'</pre>';
//		echo '#$this->isHasAccessSaveCheck: '.$this->isHasAccessSaveCheck."<br />\r\n";
//		echo '#$methods_class: '.$methods_class."<br />\r\n";
		//Для всех переданных методов делаем проверку на доступ
			//echo '<br />run check getDataFromMethod=><br />';
			$isUserHasAccessForMethod = $this->isUserHasAccessForMethod($methods_class,$methods_array_value);
			//echo '#$isUserHasAccessForMethod: <pre>'; print_r($isUserHasAccessForMethod); echo "</pre><br />\r\n";
			if ($isUserHasAccessForMethod==='skip'){
				$this->result[$array_key.'_no_access'] = 'no_access';
				//$this->result[$array_key.'_skip'] = '';
				continue;
			}elseif(!$isUserHasAccessForMethod) {
				return false;
			}
			//DEBUG TODO DELETE
			if ($this->param["setResetForTest"]==true){
				if ((method_exists($this->loaded_class[$methods_class],'setResetForTest'))){
				$this->loaded_class[$methods_class]->setResetForTest($this->param);//Сбрасываем для теста таблицу
				$this->result[$methods_class.'_'.$methods_array_value.'_'.'setResetForTest']=true;
				}else {
					$this->result['ControllerError'][]= __METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'):NOT Exist: '.$methods_class.'_'.'setResetForTest';
				}
			}
			
			//$this->param['info']= '';
			
			//If getError Если нужно обработать ошибку
			if (
			$methods_array_value[0]=='g'&&
				(
					$methods_array_value[3]=='E'//getError
					||
					$methods_array_value[3]=='I'//getInfo
				)
			){
				if (preg_match("/->/",$methods_array_value,$tmp)){
					$tmp_split=split('->',$methods_array_value);
					if (!empty($tmp_split[1]))
						$this->param['info'] = $tmp_split[1];
					$methods_array_value=$tmp_split[0];
					$array_key= $methods_class.'_'.$methods_array_value;
				}
			}
			if ($methods_class==='ModelsValidation'){
				if (empty($this->param['method'])&&
						!empty($this->current_controller['public_methods']['default'])
					){
					$method_valid='default';
				}else {
					$method_valid=$this->param['method'];
				}
				//Добавление валидации для модели
				if (!empty($this->current_controller['public_methods'][$method_valid]['validation'])){
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation'];
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_form'])) {
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_form'];
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_multi_form'])) {
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_multi_form'];
				}elseif (!empty($this->current_controller['public_methods']['default']['validation'])) {
					$this->param['validation']= $this->current_controller['public_methods']['default']['validation'];
					//Если есть дополнительные параметры в валидации
					if (!empty($this->current_controller['public_methods'][$method_valid]['validation_add'])){
						$this->param['validation']=array_merge(
							$this->param['validation'],
							$this->current_controller['public_methods'][$method_valid]['validation_add']
						);
					}
				//В случае когда в методе по умолчанию нет данных для валидации но есть добавление для валидации
				}elseif (!empty($this->current_controller['public_methods'][$method_valid]['validation_add'])) {
					$this->param['validation']= $this->current_controller['public_methods'][$method_valid]['validation_add'];
				}
			}
			//if (empty($this->result[$array_key])){
				//echo 'NOT SET<br />';
			//}else {
				//echo 'isSET!<br />';
			//}
			if (method_exists($this->loaded_class[$methods_class],$methods_array_value)){
				try{
					$answer = $this->loaded_class[$methods_class]->$methods_array_value($this->param);
				} catch (Exception $e) {
					$answer=$this->param['info']=$e->getMessage();
				}
			}else {
				$this->result['ControllerError'][]=$answer=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Extend method not exist '.$methods_array_value.'';
			}
			//Ключ для массива
//			$array_key= $methods_class.':'.
//				$methods_array_value.($this->param['info']==''?'':'->'.$this->param['info']);
			//$debug=true;
			//$debug=false;
//			$this->param['isPHPUnitDebug']=true;
//$this->param['isPHPUnitDebug']&&
			if ($this->param['debug']){//TODO DELETE 
				if (isset($this->result['param'][$this->param['method']]['param_out'])){
					$this->result['param'][$this->param['method']][$array_key] = $this->getForDebugArrayDiff($this->param,$this->result['param'][$this->param['method']]['param_out']);
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}else {
					$this->result['param'][$this->param['method']]['param_out'] = $this->param;
				}
			}
			if (!empty($this->param['info'])){
				$this->result[$array_key][$this->param['info']] = $answer;
			}else {
				$this->result[$array_key] = $answer;
			}

			//if Method is has. - если метод содержит вопрос, используем метод случаев
			if ($methods_array_value[0]=='i'&&
				$methods_array_value[1]=='s'){//isMethod
					//$html.=
						$this->isGetMethodForAnswer($methods_array_value,	$this->result[$array_key] );
				}elseif (empty($this->result[$array_key])) {
					$this->result[$array_key]='';
				}
		}elseif($methods_array_value[0]=='i'&&$methods_array_value[1]=='s') {
			//echo $array_key.' SECOND!!<br/>';
			//echo $this->result[$array_key];
			//echo 'ALTER LOAD:getDataFromMethod-#$methods_array_value: <pre>'; print_r($methods_array_value); echo "</pre><br />\r\n";
			//echo '#$array_key: <pre>'; print_r($array_key); echo "</pre><br />\r\n";
			//echo 'ALTER: #$array_key:';	echo $array_key.'<br />';
			//echo '#$this->result[$array_key]: <pre>'; print_r($this->result[$array_key]); echo "</pre><br />\r\n";
			$this->isGetMethodForAnswer($methods_array_value,	$this->result[$array_key] );
		}
	}
		//return $html;
}

/** Есть ли доступ у данного юзера?
 * Есть ли доступ у данного юзера?
 * 
 * @param mixed $methods_class 
 * @param mixed $methods_array_value 
 * @access public
 * @return bool
 */
// $methods_class,$methods_array_value
function isUserHasAccessForMethod($methods_class,$methods_array_value) {
	if ($methods_class==='ModelsErrors'){
		return true;
	}
	$class_with_method = $methods_class.'::'.$methods_array_value;
	$access_for='';
	//echo '<hr>&nbsp;isUserHasAccessForMethod::<br />';
	//echo '&nbsp;#$class_with_method: '.$class_with_method."<br />\r\n";
	//Проверим доступ для конкретной функции
	if (!empty($this->current_controller['access'][$class_with_method])
		&&isset($this->param['PermissionLevel'])
	){
		//echo 'check<br />';
		if ($this->param['PermissionLevel']>=$this->current_controller['access'][$class_with_method]['access_level']){
			//Возврашаем да, доступ есть, когда метод в контроллре указан и уровень текущего
			//Юзера, выше или равен тому что указан в контроллере
					//echo '&nbsp;&nbsp;true! method';
				$access_for= 'method';
			return true;
		}else {
				$access_for= 'method';
			//В случае когда доступа нет, сохраняем данные для последующей проверки
			//echo '&nbsp;&nbsp;false method';
			//echo '#$class_with_method: '.$class_with_method."<br />\r\n";
			//echo '<pre>#$this->current_controller["access"][$class_with_method]: '; print_r($this->current_controller["access"][$class_with_method]); echo '</pre>';
			$run_method_case=$this->current_controller['access'][$class_with_method]['private_methods'];
			$level_for_check=$this->current_controller['access'][$class_with_method]['access_level'];
			//				echo '#$run_method_case: '.$run_method_case."<br />\r\n";
				//echo '<br />#$run_method_case: '.$run_method_case."<br />\r\n";
			if (empty($run_method_case)){
				//echo '<br />SKIP!!! #$class_with_method: '.$class_with_method."<br />\r\n";
				return 'skip';
			}
		}//END $this->param['PermissionLevel']>=
	}else {
		if ($this->param['PermissionLevel']>=$this->current_controller["access"]['default_access_level']||
			empty($this->current_controller['access'])//TODO DELETE AFTER Prvmisson add for all
		){//Проверить уровень доступа к контроллеру
			//Выводим подтверждения доступа если доступа выше или равен минимальному
			//echo '&nbsp;&nbsp;true class';
			if(empty($this->param['method'])){
				$method='default';
			}else {
				$method=$this->param['method'];
			}
			if (isset($this->current_controller['public_methods'][$method]["access"]['default_access_level'])){
			$access_for= 'controller';
				if ($this->param['PermissionLevel']>=$this->current_controller['public_methods'][$method]["access"]['default_access_level']){
					//echo '<pre>#$method: '; print_r($method); echo '</pre>';
					//echo '#$this->current_controller["public_methods"][$method]["access"]["default_access_level"]: '.$this->current_controller["public_methods"][$method]["access"]["default_access_level"]."<br />\r\n";
					//echo '#$this->current_controller[$method]["access"]["default_access_level"]: '.$this->current_controller['public_methods'][$method]["access"]["default_access_level"]."<br />\r\n";
					//echo 'public_methods Method access true';
					return true;
				}else {
//					echo 'public_methods Method access false';
					$run_method_case=$this->current_controller['public_methods'][$method]['access']['default_private_methods'];
					$level_for_check=$this->current_controller['public_methods'][$method]['access']['default_access_level'];
					if ($run_method_case==''){
						return false;
					}
				}
			}else {
				return true;
			}
		}else {
			//В случае когда доступа нет, сохраняем данные для последующей проверки
			$access_for= 'controller';
			$run_method_case=$this->current_controller['access']['default_private_methods'];
			$level_for_check=$this->current_controller['access']['default_access_level'];
		}
	}
	//echo '#$access_for: '.$access_for."<br />\r\n";
	//echo '<br />All false<br />';
	//echo '#$level_for_check: '.$level_for_check."<br />\r\n";
	//echo '#$run_method_case: '.$run_method_case."<br />\r\n";
	//Когда доступа нет, запускаем метод указанный по умолчанию для проверки доступа 
	$this->getPrivateMethod($run_method_case);
	//Проверяем после запуска, возможно метод изменил уровень доступа, есть ли нужный досту
	if ($this->param['PermissionLevel']<$level_for_check){
		//Выполняем случай когда доступа нет, например выводим ошибку
		$this->isGetMethodForAnswer($run_method_case,false);
		//Устанавливаем что бы не проверять в дальнейшем при инициализации
		$this->isHasAccessSaveCheck=false;
		//echo '&nbsp;&nbsp;false all';
		return false;
	}else {
		$this->isGetMethodForAnswer($run_method_case,true);
	}
	return true;
}

/** Отобразить возможные варианты ответа методов
 * 
 * @param mixed $methods_case 
 * @access public
 * @return method_array
 */
function isGetMethodForAnswer($method,$methods_case) {
//	echo '>>#$methods_case: '.$methods_case."<br />\r\n";
//	echo '>>#$method.$case: '.$method.$case."<br />\r\n";
	if (
		$methods_case==''||$methods_case==0
	){
		if ($method==='isValidModifierParamFormError'){
			$this->isHasAccessSaveCheck=false;
		}
		$case= '_false';
	}else {
		$case= '_true';
	}
//	echo '>>#$method.$case: '.$method.$case."<br />\r\n";
	//Если случай метода явно указан
	if (isset($this->current_controller['methods_case'][$method.$case])) {
		$method = $this->current_controller['methods_case'][$method.$case];
	}else {
		$method = $method.$case;
	}
	//Запустить приватный метод исходя из случая
	$this->getPrivateMethod($method);
}

/** Запустить публичное действие в шаблоне
 * Запустить публичное действие в шаблоне
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getPublicMethod($param) {
	if (!empty($this->current_controller['public_methods'][$param['method']])){
//		if (empty($this->result['LoadMethod'])){
//			$this->result['LoadMethod']=$param['method'];
//		}
		foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
			$this->getMethodFromClass($_title,$_value);
		}
	}else {
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Method '.$param['method'].' not found in '.$this->current_controller_name.'';
		if (!isset($this->current_controller)){
			$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): Array $controller is not exist: <br />/controller/'.$this->controller_alias[$this->current_template].'.php <br /> var $controller;<br />function __construct($access_level){<br /> $this->controller = array(...);<br />;}';
		}
		if (!empty($this->current_controller['public_methods']['default'])){
			if (!empty($this->current_controller['public_methods']['default'])){
				$param['method']='default';
				foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
					$this->getMethodFromClass($_title,$_value);
				}
			}else {
				$this->setInitController($this->current_controller['init'],$this->current_controller_name);//Инициализируем данные
			}
		}
	}
}

/** Запустить приватный метод
 * Запустить приватный метод
 * 
 * @param str $method 
 * @access public
 * @return void
 */
function getPrivateMethod($method){
	//Если есть такой метод
	if (!empty($this->current_controller['private_methods'][$method])){
		//Получаем метод
		$methods_callback = $this->current_controller['private_methods'][$method];
	}elseif (!empty($this->current_controller['public_methods'][$this->param['method']][$method])){
		$methods_callback = $this->current_controller['public_methods'][$this->param['method']][$method];
	}else {
		$this->result['ControllerError'][]=__METHOD__.' ('.preg_replace("/.*\\\/","",__FILE__).', line:'.__LINE__.'): In controller "'.$this->current_controller_name.'" not found Method "'.$method.'"';	
		return true;
	} 
	//Запускаем каждый метод класса
	foreach ($methods_callback as $method_title =>$method_value)
		$this->getMethodFromClass($method_title,$method_value);
}

/** Получить первый эл-т массива
 * Получить первый эл-т массива
 * 
 * @param mixed $array 
 * @param string $need 
 * @access public
 * @return void
 */
function getFirstArrayKey($array,$key_mode='key') {
	$tmp = each($array);
	list($key, $value)=$tmp;
	if ($key_mode=='key'){
		return $key;
	}else {
		return $value;
	}
}

/** Сделать выборку для теста
	*
	*/
function getDataForTest($method,$param){
		//Буфиризируем вывод шаблона
		ob_start();
		//Подключаем файл тестирования
		include_once($_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components/com_sa/models/base/ModelsUnitPHP.php');
		//Создаём имя файла дерриктория + метод
		$file_name = __CLASS__.'::'.$method.$param['controller'].'-'.md5(implode('","',$param));
		//Получаем данные из кэша
		$array = ModelsUnitPHP::getSerData($file_name);
		//Если данных нет, обновляем кэш метода
		if (empty($array)){
			//Запрашиваем метод в текущем классе
			if (method_exists($this,$method))	
				$array = $this->$method($param);
			//Сохраняем в кэш
			ModelsUnitPHP::setSerData($file_name,$array);
		}
		//Обнуляем все данные
		//$this->setRestetForTest();
		ob_end_flush();
		return $array;
}

function getForDebugArrayDiff($first_array,$second_array,$not_check=array('REQUEST_IN' => '','REQUEST_OUT' => ''/*,'ViewMethod' => ''*/),$max_in_array=200)
{
	$return = array (); // return
	$new='+';
	foreach ($first_array as $k => $pl) // payload
		if (!isset($not_check[$k])){
		if ( ! isset ($second_array[$k]) 
			|| 
			(	
				$second_array[$k] != $pl 
				&& 
				count($second_array[$k]) != count($pl)
			) 
			//|| (count(array_merge(array_diff($second_array[$k],$pl)))>0)
			||md5(
			$this->getMultiImplode(
				'',($first_array[$k])
			)
			)!=md5(
				$this->getMultiImplode('',$second_array[$k])
				)
		){
			//echo '<<===<pre>#$second_array[$k]: '; print_r($second_array[$k]); echo '</pre>!!';
			if (is_array($pl)&&count($pl)>$max_in_array){
				$i=0;
				foreach ($pl as $pl_title =>$pl_value){
					if($i>$max_in_array&&is_array($pl_value)&&isset ($second_array[$k])){
						$return[$k][$new][$pl_title] = '...';
					}else {
						$return[$k][$new][$pl_title] = $pl_value;
					}
					$i++;
				}		
			}else {
					$return[$k][$new] = $pl;
			}
			if (! isset ($second_array[$k])){
				//$return[$k][$new] 
				$tmp = $return[$k][$new];
				unset($return[$k]);
				$return[$new.$k]=$tmp;
			}
		}
	}
	$old='-';
	foreach ($second_array as $k => $pl) // payload
	if (!isset($not_check[$k])){
		if ( ( ! isset ($first_array[$k]) 
			|| ($first_array[$k] != $pl 
			&& count($first_array[$k]) != count($pl) 
		)
			||md5($this->getMultiImplode('',($first_array[$k])))!=md5($this->getMultiImplode('',$second_array[$k]))
			//|| (count(array_merge(array_diff($first_array[$k],$pl)))>0)
		) /*&& ! isset ($return[$k])*/ ){
			if (is_array($pl)&&count($pl)>$max_in_array){
				$i=0;
				foreach ($pl as $pl_title =>$pl_value){
					if($i>$max_in_array&&is_array($pl_value)/*&&isset($first_array[$k])*/){
						$return[$k][$old][$pl_title] = '...';
					}else {
						$return[$k][$old][$pl_title] = $pl_value;
					}
					$i++;
				}		
			}else {
					$return[$k][$old] = $pl;
			}
			if (count($return[$k])==1){
				$tmp = $return[$k][$old];
				unset($return[$k]);
				$return[$old.$k]=$tmp;
			}
		if (isset($first_array[$k])&&isset($first_array[$k])){
				$tmp1 = $return[$k][$new];
				$tmp2 = $return[$k][$old];
				$return[$k][$new] 
						=$this->getForDebugArrayDiff($tmp1,$tmp2,$not_check);
				$return[$k][$old] 
					=$this->getForDebugArrayDiff($tmp2,$tmp1,$not_check);
				if (count($return[$k][$old])==0){
					unset($return[$k][$old]);
					$return[$k][$old.' ']= $tmp2;
				}
				if (count($return[$k][$new])==0){
					unset($return[$k][$new]);
					$return[$k]['+']= $tmp1;
				}	
			}
			if ($return[$new.$k]==$return[$old.$k]&&
				count($return[$new.$k])==0&&
				count($return[$new.$k])==0
			){
				unset($return[$new.$k]);
				unset($return[$old.$k]);
			}
			if (count($return[$k])==2){
				unset($return[$k][$old]);
			}
		}
	}
	return $return;
} 

function getMultiImplode($glue, $pieces)
{
    $string='';
    if(is_array($pieces))
    {
        reset($pieces);
        while(list($key,$value)=each($pieces))
        {
            $string.=$glue.$this->getMultiImplode($glue, $value);
        }
    }
    else
    {
        return $pieces;
    }
    return trim($string, $glue);
}


}
?>
