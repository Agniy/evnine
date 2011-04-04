<?php
//error_reporting(E_ERROR|E_RECOVERABLE_ERROR|E_PARSE|E_COMPILE_ERROR|E_RECOVERABLE_ERROR|E_ERROR|E_CORE_ERROR);
error_reporting(0);
/**
 * Базовый контроллер 
 *
 * @package Controller
 * @author ev9eniy
 * @version 1.0
 * @updated 03-окт-2010 17:53:02
 */

require('evnine.config.php');

class Controller extends Config
{

	/** Cвязь с базой данных
	 */
	var $database;

	/** Список шаблонов с функциями в этих шаблонах
	 */
	var $class_path;
	var $sef_mode;
	var $controller_menu_view;
	var $access_level;
	var $isHasAccessSaveCheck;
	var $current_template;
	var $current_controller;
	var $loaded_controller;
	var $param;
	var $loaded_class;
	var $result;
	var $debug;
	var $path_to;

	/** __construct() Конструктор класса
	 * Конструктор класса
	 */
function __construct(){//$init_param
	$this->sef_mode=false;
	parent::__construct();
	//Инициализировать базу данных
	//if (!isset($this->database))
	$this->setInitDB();
}


/** setInitDB 
 * setInitDB 
 * 
 * @assert ($param) == $this->object->getDataForTest('setInitDB',$param='')
 * @access private
 * @return void
 */
function setInitDB(){
	$class_dir=$this->path_to.$this->class_path['ModelsDatabase']['path'].DIRECTORY_SEPARATOR.'ModelsDatabase'.'.php';
	if (file_exists($class_dir)){//Если существует файл
		require_once($class_dir);
		$this->database = new ModelsDatabase();
		}
}

/** Получить классы инициализации
 * 
 * @assert ($param) == $this->object->getDataForTest('setInitController',$param='')
 * @access public
 * @return void
 */
function setInitController($init_array) {
	//$html.='#Init:<br/> ';
	foreach ($init_array as $menu_title => $menu_value)if ($menu_title!=''){
		if ($this->isHasAccessSaveCheck)
			//$html.=
			$this->getMethodFromClass($menu_title,$menu_value);
	}		
	//return $html;
}

/** getControllerForParam - Получить данные из контроллера
 * 
 * @assert ($param) == $this->object->getDataForTest('getControllerForParam',$param = array('template' =>''))
 * @access public
 * @param template
 * @return array
 */
function getControllerForParam($param,$debug=false) {
	if (!empty($param['sef'])) {//Если URL в ЧПУ режиме
		//Получить реальные данные из адресной строки
		$sef = $this->getSEFUrl($param['sef']);
		unset($param['sef']);
		//Установить метку что адреса нужно обрабатывать в ЧПУ режиме
		$this->sef_mode=true;
		unset($param['form_data']['sef']);
		//Установить параметры из УРЛа
		$param['method']=$sef['method'];
		$param['controller']=$sef['controller'];
		//Объединить данные, если данные передаются POST и GET одновременно 
		$param['form_data']=array_merge($param['form_data'],$sef['form_data']);
		//$param['ajax']= $sef['form_data']['ajax'];
	}
	
	//Случай когда обрабатываем несколько форм
	if (!empty($param['form_data']['submit'])){
		//Получаем метод сабмита по первому ключу
		//if ($first_key=
			//$this->getFirstArrayKey($param['form_data']['submit'])||
		if ($first_key=(
			is_array($param['form_data']['submit'])
			?
			$this->getFirstArrayKey($param['form_data']['submit'])
			:
			$param['form_data']['submit'])
		){
			unset($param['form_data']['submit']);
			//Получаем значения шаблона и метода
			if (!empty($param['form_data'][$first_key]['t'])){
				$param['controller']=$param['form_data'][$first_key]['t'];
				unset($param['form_data'][$first_key]['t']);
			}
			if (!empty($param['form_data'][$first_key]['m'])){
				$param['method']=$param['form_data'][$first_key]['m'];
				unset($param['form_data'][$first_key]['m']);
			}else {
				$param['method']=$first_key;
			}
			//Переносим данные из метода в основные параметры
			if (count($param['form_data'][$first_key])>0){
				foreach ($param['form_data'][$first_key] as $_title =>$_value){
					$param[$_title]= $_value;
				}
			}
		}
		//echo '#$param["template"][0]: <pre>'; print_r($param["template"][0]); echo "</pre><br />\r\n";
		//echo '#$param["template"]: <pre>'; print_r($param["template"]); echo "</pre><br />\r\n";
	}elseif(empty($param['method'])) {
		$param['method']='default';
	}
	$this->result=array();
	if (empty($this->result['LoadController'])){
		$this->result['LoadController']=$param['controller'];
		$this->result['LoadMethod']=$param['method'];
	}
	//echo '#$this->result["ajax"]: <pre>'; print_r($this->result["ajax"]); echo "</pre><br />\r\n";
	//echo '#$param["ajax"]: <pre>'; print_r($param["ajax"]); echo "</pre><br />\r\n";
	//echo '#$param: <pre>'; print_r($param); echo "</pre><br />\r\n";
	if ($param['ajax'][0]==='b') {
		$this->result['ajax']='Body';
		$param['ajax']=false;
	}elseif ($param['ajax'][0]==='a'){
		$this->result['ajax']='True';
		$param['ajax']=true;
	}else {
		$this->result['ajax']='False';
		$param['ajax']=false;
	}
	//echo '#$param["ajax"]: <pre>'; print_r($param["ajax"]); echo "</pre><br />\r\n";
	$this->getDataFromController($param,$debug);
	$this->param['method']=$param['method'];//reset in param method
	//echo '#$this->param["method"]: <pre>'; print_r($this->param["method"]); echo "</pre><br />\r\n";
	$this->getURL();//Получить адреса в текущем контроллере
//	$this->param['isPHPUnitDebug']=true;
	//	if ($this->param['isPHPUnitDebug']){//TODO DELETE 
	//echo '#$this->result["param"][$this->param["method"]]["param_out"]: <pre>'; print_r($this->result["param"][$this->param["method"]]["param_out"]); echo "</pre><br />\r\n";
	//echo '#count($this->result["param"][$this->param["method"]]["param_out"]): <pre>'; print_r(count($this->result["param"][$this->param["method"]]["param_out"])); echo "</pre><br />\r\n";
	if (count($this->result['param'])>1)
		unset($this->result['param'][$this->param['method']]['param_out']);
//	}
	return $this->result;
}

/** Получить реальные данные из адресной строки
 * 
 * Случай когда общий SEF режим - когда параметры явно указаны 
 * /user=62/ - переключается в контроллере 'inURLSEF' => true
 *
 * И когда параметры явно не указаны /62-User.html 
 *		'Метод' => array(	
 *			'inURLSEF' => array(
 *						template/method'1' => 'Профиль','.' => '.html',
 *			)
 *		)
 * 
 * @param mixed $sef 
 * @access public
 * @return void
 */
function getSEFUrl($sef){
	$split = split('/',$sef);
	$split_count = count($split);
	$param['form_data']=array();
	$param['controller']=$split['0'];
	//Если общий ЧПУ режим окончание на index.html
	if ($split[$split_count-1]==='index'){
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
		//Если случай частный окончание /62-user.html, ЧПУ для конкретного метода
	}else {
		//Случаев может быть два
		if ($split_count==3){
			//когда есть и шаблон и метод
			$param['method']=$split['1'];
			$split_form_data = split('-',$split['2']);
		}elseif($split_count==2) {//когда метод не указан
			$split_form_data = split('-',$split['1']);
			$param['method']='default';
		}
		$split_count=count($split_form_data);
		//if ($split_form_data[$split_count-1]==='ajax'){
			//unset($split_form_data[$split_count-1]);
			//$param['form_data']['ajax']='true';
		//}
		//echo '#$split_form_data: <pre>'; print_r($split_form_data); echo "</pre><br />\r\n";
		//Загрузить для шаблона контроллер что бы разобрать случай когда параметры явно не указаны
		$this->setLoadController($param['controller']);
		//Разбираем переданную строку
		if (!empty($this->current_controller['public_methods'][$param['method']]['inURLSEF'])){
			$i=0;
			foreach ($this->current_controller['public_methods'][$param['method']]['inURLSEF'] as $_title =>$_value)if ($_title!=='.'){
				if ($_title==='date'){//Учитываем что в адресе может быть дата 10-11-2014 
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

/** getURL() Получить урл для методов в контроллере, используя валидацию в методе
 * 
 * $this->controller_menu_view = array(
 *	'inURLSEF'								=> false,//Включене ЧПУ режима в контроллере
 *)
 *			'inURLMethod' => array(
 *				в массиве указаны методы для которых нужно создать УРЛы
 *				}
 *			)
 *	'public_methods'			=> array(
 *		'default'								=> 	array(
 *			Урлы строятся исходя из валидации параметров допустимых в методе
 *			по умолчанию данные берутся из default метода
 *			'validation'=> array(
 *			)
 *		)
 *		
 *		'Метод' => array(	
 *			Дополнительная валидация для метода, в ней указываются урлы которые
 *			используются для генерации параметров
 *			'validation_add'=>array(		
 *					'date' 	=> array('inURL' => true,'to'=>'Date','type'=>'str','required'=>'false','max' => '10',),
 *				),
 *		)
 *		
 *	Случай когда хотим указать внешний котроллер и ссылку на метод во внешнем котроллере
 *		'Метод' => array(	
 *			'template' => '', Является обязательным для генерации ссылки
 *			'method' => '',
 *		)
 *		
 *	ЧПУ для метода, формата контроллер\метод\юзер-событие-дата.html
 *		'Метод' => array(	
 *			'inURLSEF' => array(
 *						template/method'1' => 'Профиль','.' => '.html',
 *			)
 *		)
 * @param mixed $validate 
 * @access public
 * @return void
 */
function getURL($seo=false) {
	//Если в контроллере указано что нужно строить для всех методов ЧПУ урлы
	if ($this->current_controller['inURLSEF']){
		$seo= true;
	}
	//Получаем для валидации данные по умолчанию
	//if ($this->param['ajax']){
		//if (!empty($this->current_controller['public_methods'][$this->result['LoadMethod']]['validation_add'])){
			//$validate= $this->current_controller['public_methods']['default']['validation'];
		//}elseif (!empty($this->current_controller['public_methods'][$this->result['LoadMethod']]['validation_form'])){
			//$validate= $this->current_controller['public_methods'][$this->result['LoadMethod']]['validation_form'];
		//}elseif (!empty($this->current_controller['public_methods'][$this->result['LoadMethod']]['validation_multi_form'])){
			//$validate= $this->current_controller['public_methods'][$this->result['LoadMethod']]['validation_multi_form'];
		//}else {
			//$validate=$this->current_controller['public_methods']['default']['validation'];
		//}
	//}else {
		//$default = $this->getURLFromArray(
		//$validate=$this->current_controller['public_methods']['default']['validation'];
		//,$seo);
	//}
	//echo '#$validate: <pre>'; print_r($validate); echo "</pre><br />\r\n";
	$default = $this->getURLFromArray(
		$this->current_controller['public_methods']['default']['validation'],$seo
	);
	//Создаётся начала урла в котором указывается контроллер-шаблон
	$url_template= $this->getTemplateURL($this->param['controller'],$seo);
	//Окончание для адреса
	if ($seo){
		$postfix='/index.html';
	}else {
		$postfix='';
	}
	//Флаг для включения ЧПУ режима в методе
	$seo_flag_save='';
	if (!empty($this->current_controller['public_methods']['default']['inURLMethod'])&&$this->param['ajax']==false){
		$url_method = $this->current_controller['public_methods']['default']['inURLMethod'];
		if(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
			//Обединить с методом по умолчанию и методом для добовления
			$url_method = array_merge(
				$this->current_controller['public_methods']['default']['inURLMethod'],
				$this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add']
			);
		//Сбросить данные для URL по умолчанию и использовать из метода
		}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod'])){
			$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod'];
		}
	//Случай если по умолчанию не указан метод шаблонов но есть в текущем методе 
	}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'])){
		$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod_add'];
	//В случае когда по умолчанию не указан метод шаблонов
	}elseif(!empty($this->current_controller['public_methods'][$this->param['method']]['inURLMethod'])){
		$url_method = $this->current_controller['public_methods'][$this->param['method']]['inURLMethod'];
	}
	$count = count($url_method);
		//Для всех методов для которых нужно создать адрес
		for ( $i = 0; $i < $count; $i++ ) {
			$method = $url_method[$i];
			//echo '#$this->param: <pre>'; print_r($this->param); echo "</pre><br />\r\n";
			//echo '#$this->param["PermissionLevel"]: <pre>'; print_r($this->param["PermissionLevel"]); echo "</pre><br />\r\n";
			//echo '#$this->current_controller["public_methods"][$method]["access"]["default_access_level"]: <pre>'; print_r($this->current_controller["public_methods"][$method]["access"]["default_access_level"]); echo "</pre><br />\r\n";
			if (
				//$method==='default'
				//||
				$this->current_controller['public_methods'][$method]['access']['default_access_level']
				>$this->param['PermissionLevel']
				||
				empty($this->current_controller['public_methods'][$method])
			){
				//echo '#$method: <pre>'; print_r($method); echo "</pre><br />\r\n";
				//echo '#$this->param["PermissionLevel"]: <pre>'; print_r($this->param["PermissionLevel"]); echo "</pre><br />\r\n";
				//echo '#$this->current_controller["public_methods"][$method]["access"]["default_access_level"]: <pre>'; print_r($this->current_controller["public_methods"][$method]["access"]["default_access_level"]); echo "</pre><br />\r\n";
				//echo 'SKIP<br />';
				//Пропустить метод по умолчанию
				continue;
			}
			
			//Если в методе указано что нужно использовать ЧПУ
		if (!empty($this->current_controller['public_methods'][$method]['inURLSEF'])){
			$seo_flag_save= $seo;
			$seo=$this->current_controller['public_methods'][$method]['inURLSEF'];
		}

			//случай когда валидация с добавлением, создаём массив с параметром
			//if case validation_ADD 
		if (!empty($this->current_controller['public_methods'][$method]['validation_add'])){
			//echo $method.'-'.'if case validation_ADD <br />';
			//Получаем из массива метода данные для адреса
			$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation_add'],$seo);
			//echo '#$seo_flag_save: <pre>'; print_r($seo_flag_save); echo "</pre><br />\r\n";
			
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
					$this->result['inURL'][$method]['pre']=
							//$url_template
							$this->getTemplateURL($this->param['controller'],$seo)
							.$this->getMethodURL($method,$seo)
							.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				
				}else {//Если нет ЧПУ урла.
					$this->result['inURL'][$method]['pre']=$url_template.$this->getMethodURL($method,$seo,true).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			
			//если случай когда нужно перезаписать всю валидацию для метода if case validation 
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation'])) {
			//echo $method.'-'.'if case validation <br />';
				//Случай когда в урле нужно указать другой шаблон
			if(!empty($this->current_controller['public_methods'][$method]['controller'])) {
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				//Создаём урл из данных в контроллере для другова шаблона и метода
				$this->result['inURL'][$method]['pre']=
					$this->getTemplateURL($this->current_controller['public_methods'][$method]['controller'],$seo)
					.$this->getMethodURL($this->current_controller['public_methods'][$method]['method'],$seo)
					.$this->result['inURL'][$method]['pre'];
				$this->result['inURL'][$method]['post']=$postfix;

				//Если не указан внешний шаблона
			}else {
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					$this->result['inURL'][$method]['pre']=
						 $this->getTemplateURL($this->param['controller'],$seo)
						.$this->getMethodURL($method,$seo)
						.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
					
				}else {//Если нет ЧПУ урла.
					if ($method!=='default'){
						$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					}
					$this->result['inURL'][$method]['pre']=$url_template.$this->getMethodURL($method,$seo).$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['pre'].=$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
			
			//Случай когда нужно данные передать в форму validation_form
			//if case validation_form
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_form'])) {
			//echo 'validation_form<br />';
				$this->result['inURL'][$method]=
					$this->getInputsFromArray($this->current_controller['public_methods'][$method]['validation_form']);
			
			if(!empty($this->current_controller['public_methods'][$method]['controller'])) {
					$this->result['inURL'][$method]['inputs']=
					$this->getInputFormText('t',$this->current_controller['public_methods'][$method]['controller'],$seo)
					.$this->getInputFormText('m',$this->current_controller['public_methods'][$method]['method'])
					.$this->result['inURL'][$method]['inputs'];
				//Создаём урл из данных в контроллере для другова шаблона и метода
				$this->result['inURL'][$method]['pre']=
					$this->getTemplateURL($this->current_controller['public_methods'][$method]['controller'],$seo)
					.$this->getMethodURL($this->current_controller['public_methods'][$method]['method'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			}else {
				$this->result['inURL'][$method]['inputs']=
					$this->getInputFormText('t',$this->param['controller'])
					.$this->getInputFormText('m',$method)
					.$this->result['inURL'][$method]['inputs'];
				$this->result['inURL'][$method]['pre']=
					$url_template;
					//$this->getTemplateURL($this->param['controller'],$seo);
				//.$this->getMethodURL($method,$seo);
				$this->result['inURL'][$method]['pre']=
					$url_template
					.$this->getMethodURL($method,$seo)
					.$default['pre'];
			}
				$this->result['inURL'][$method]['post']=$postfix;
				
			//validation_multi_form
			//Случай когда нужно обработать несколько форм в одной форме
		}elseif (!empty($this->current_controller['public_methods'][$method]['validation_multi_form'])) {
			$this->result['inURL'][$method]=
				$this->getInputsFromArray($this->current_controller['public_methods'][$method]['validation_multi_form'],$method);
			$this->result['inURL'][$method]['submit']='submit['.$method.']';		
				if(!empty($this->current_controller['public_methods'][$method]['controller'])) { 
					$this->result['inURL'][$method]['inputs']=
						//'<textarea rows=""4 cols="60">'
						$this->getInputFormText('t',$this->current_controller['public_methods'][$method]['controller'],$method)
						.$this->getInputFormText('m',$this->current_controller['public_methods'][$method]['method'],$method)
						.$this->result['inURL'][$method]['inputs']
						//.'</textarea>';
						;
					//Создаём урл из данных в контроллере для другова шаблона и метода
					$this->result['inURL'][$method]['pre']=
						$this->getTemplateURL($this->current_controller['public_methods'][$method]['controller'],$seo)
						.$this->getMethodURL($this->current_controller['public_methods'][$method]['method'],$seo);
						//$this->getInputFormText('t',$this->current_controller['public_methods'][$method]['controller'],$method);
					$this->result['inURL'][$method]['post']=$postfix;		
				}else {
					$this->result['inURL'][$method]['inputs']=
						//$this->getInputFormText('t',$this->param['controller'],$method)
						//$this->getInputFormText('m',$method,$method)
						$this->result['inURL'][$method]['inputs'];
					//$this->result['inURL'][$method]['pre']=
						//$url_template;
						//$this->getTemplateURL($this->param['controller'],$seo);
					//.$this->getMethodURL($method,$seo);
					//$this->result['inURL'][$method]['pre']=
						//$this->getTemplateURL($this->param['controller'],$seo)
						//.$this->getMethodURL($this->param['method'],$seo)
						//.$default['pre'];
				}
				
			//по умолчанию ставим урл из метода default
		}else {//if not exist validation and validation_add 
			//echo $method.' - if not exist validation and validation_add <br />';
			if(!empty($this->current_controller['public_methods'][$method]['controller'])) {
				//$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
				//Создаём урл из данных в контроллере для другова шаблона и метода
				$this->result['inURL'][$method]['pre']=
					$this->getTemplateURL($this->current_controller['public_methods'][$method]['controller'],$seo)
					.$this->getMethodURL($this->current_controller['public_methods'][$method]['method'],$seo);
				$this->result['inURL'][$method]['post']=$postfix;
			}else {
				if($seo&&$seo!==true){//Если в методе указан ЧПУ
					$this->result['inURL'][$method]=$this->getURLFromArray($this->current_controller['public_methods'][$method]['validation'],$seo);
					$this->result['inURL'][$method]['pre']=
					//$url_template
					$this->getTemplateURL($this->param['controller'],$seo)
					.$this->getMethodURL($method,$seo)
					.$this->result['inURL'][$method]['pre'];
					$this->result['inURL'][$method]['post']=$this->current_controller['public_methods'][$method]['inURLSEF']['.'];
				}else {//Если нет ЧПУ урла.
					$this->result['inURL'][$method]['pre']=$url_template.$this->getMethodURL($method,$seo).$default['pre'];
					$this->result['inURL'][$method]['post']=$postfix;
				}
			}
		}
		//Если в урле уже есть часть параметров, заменяем на текущие
		if (count($this->result['inURL'][$method]['replace'])>0){
			foreach ($this->result['inURL'][$method]['replace'] as $_title =>$_value)
			{
				$this->result['inURL'][$method]['pre']= str_replace($_value,'',$this->result['inURL'][$method]['pre']);
			}
			unset($this->result['inURL'][$method]['replace']);
		}
		if($seo&&$seo!==true){//Сбрасываем если используем ЧПУ в методе
			$seo=$seo_flag_save;
			$seo_flag_save= '';
		}
		}

	//if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'])){
		//foreach ($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'] as $button =>$method){
			//$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
		//}
		//Случай когда есть в шаблоне по умолчанию метод
	//}
	$this->getinURLTemplate();
	
}

/** Получаем переменные УРЛы в шаблоне
 * getinURLTemplate 
 * 
 * @access public
 * @return void
 */
function getinURLTemplate(){
	//inURLTemplate
	if (!empty($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'])){
		//echo '#[method][inURLTemplate]: ';
		foreach ($this->current_controller['public_methods'][$this->param['method']]['inURLTemplate'] as $button =>$method){
			$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
		}
		if(!empty($this->current_controller['public_methods']['default']['inURLTemplate'])){
			//echo '#$this->current_controller["public_methods"]["default"]["inURLTemplate"]: <pre>'; print_r($this->current_controller["public_methods"]["default"]["inURLTemplate"]); echo "</pre><br />\r\n";
			foreach ($this->current_controller['public_methods']['default']['inURLTemplate'] as $button =>$method)
			if (empty($this->result['inURLTemplate'][$button])){
				$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
			}
		}
		//Случай когда есть в шаблоне по умолчанию метод
	}elseif(!empty($this->current_controller['public_methods']['default']['inURLTemplate'])){
		//echo '#[default][inURLTemplate]: ';
		foreach ($this->current_controller['public_methods']['default']['inURLTemplate'] as $button =>$method){
			$this->result['inURLTemplate'][$button]=$this->result['inURL'][$method];
		}
	}
}

/** Получить адрес шаблона getTemplateURL 
 * 
 * @param mixed $tmpl 
 * @param mixed $seo 
 * @access public
 * @return void
 */
function getTemplateURL($tmpl,$seo) {
	if ($seo){//Если обший метод ЧПУ используем /param=value/
	//if ($seo===true){//Если обший метод ЧПУ используем /param=value/
		//$url_template='/'.$tmpl;
	//}elseif($seo&&$seo!==true)/*if SEF*/ {//Если указано формирование ЧПУ
		$url_template='/'.$tmpl;
	}else {
		$url_template='?t='.$tmpl;
		if ($this->sef_mode){
			$url_template='/index.php'.$url_template;
		}
	}
	//echo '#$seo: <pre>'; print_r($seo); echo "</pre><br />\r\n";
	//echo '#$url_template: <pre>'; print_r($url_template); echo "</pre><br />\r\n";
	return $url_template;
}

/** Получить название метода getMethodURL 
 * 
 * @param mixed $method 
 * @param mixed $seo 
 * @access public
 * @return void
 */
function getMethodURL($method,$seo) {
	if (empty($method)){
		return '';
	}else {
		if ($seo){//Если обший метод ЧПУ используем /param=value/
		//if ($seo===true){//Если обший метод ЧПУ используем /param=value/
			$url_template.='/'.$method;
		//}elseif($seo&&$seo!==true)/*if SEF*/ {//Если указано формирование ЧПУ
			//$url_template.='/'.$method;
		}else {
			$url_template.='&m='.$method;
		}
	}
	return $url_template;
}

function getInputFormText($name,$str,$multi_form=false){
	if (empty($str)) {
		return;
	}else {
		//echo '#$multi_form: <pre>'; print_r($multi_form); echo "</pre><br />\r\n";
		if ($multi_form){
			$name=$multi_form.'['.$name.']';
			//echo '#$name: <pre>'; print_r($name); echo "</pre><br />\r\n";
		}
		//else {
			//$name= '';
		//}
		return '<input type="hidden" name="'.$name.'" value="'.$str.'"/>';
	}
}

function getInputsFromArray($validate,$multi_form=false) {
	$inputs='';
	$array_out=array();
	$pre_fix=$post_fix= '';
	foreach ($validate as $_title =>$_value){
		$REQUEST_OUT=$this->result['REQUEST_OUT'][$_value['to']];
		if ($_value['inURL']){
			if ($_value['is_array']){
				$_title.='[]';
			}
			$array_out[$_value['to']]=$_title;
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				if ($seo===true){//Если обший метод ЧПУ используем /param=value/
					$array_out['replace'][$_value['to']]='/'.$_title.'='.$REQUEST_OUT;
				}elseif($seo&&$seo!==true)/*if SEF*/ {//Если указано формирование ЧПУ
					$array_out['replace'][$_value['to']]=$REQUEST_OUT;
				}else {
					$array_out['replace'][$_value['to']]='&'.$_title.'='.$REQUEST_OUT;
				}
			}
		}else {
			if (!empty($this->result['REQUEST_OUT'][$_value['to']])){
				if ($multi_form&&$_value['multi_form']){
						$pre_fix=$multi_form.'[';$post_fix= ']';
				}
				if ($_value['is_array']){
					//$param_count = count($REQUEST_OUT);
					//echo '#$REQUEST_OUT: <pre>'; print_r($REQUEST_OUT); echo "</pre><br />\r\n";
					//for ( $i = 0; $i < $param_count; $i++ ) {
					foreach ($REQUEST_OUT as $REQUEST_OUT_title =>$REQUEST_OUT_value){
						$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'[]" value="'.$REQUEST_OUT_value.'"/>';
					}
					//}
				}else {
					$inputs.='<input type="hidden" name="'.$pre_fix.$_title.$post_fix.'" value="'.$this->result['REQUEST_OUT'][$_value['to']].'"/>';
				}
				if ($multi_form&&$_value['multi_form']){
					$pre_fix=$post_fix= '';
				}
			}
		}
	}
	$array_out['inputs']= $inputs;
	return $array_out;
}

/** Получить адрсе из массива валидации - getURLFromArray 
 * 
 * @param mixed $validate 
 * @param mixed $seo 
 * @param mixed $is_add 
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
function setLoadController($template) {
	if ($this->current_template===$template&&!empty($template)){
		return;
	}
	if (empty($template)||
		empty($this->controller_menu_view[$template])
	){//В случае если шаблона нет в списке контроллеров или если шаблон не установлен
		$this->result['ControllerError'][]='<b>function setLoadController:</b> Controller "'.$template. '" not found '.$this->current_template;
		$this->param['controller']=$this->current_template = 'frontend';
	}else {
		$this->current_template = $template;
	}
	if (empty($this->loaded_controller[$template])){
		if (empty($this->result['LoadController'])){
			$this->result['LoadController']=$this->current_template;
		}
			$controller_file = $this->path_to.'controllers'.DIRECTORY_SEPARATOR.$this->controller_menu_view[$this->current_template].'.php';
			//$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
			if (file_exists($controller_file)){//Если существует файл
			//Подключить класс
				include_once($controller_file);
				$template_controller = $this->controller_menu_view[$this->current_template];//Получаем контроллер
				$this->loaded_controller[$template] = new $template_controller($this->access_level);//Создаём копию данных контрллера
				$this->current_controller=$this->loaded_controller[$template]->controller_menu_view;
			}
		}elseif(!empty($this->loaded_controller[$template])) {
			$this->current_controller=$this->loaded_controller[$template]->controller_menu_view;
		}
}

/** Получить данные из контроллера
 * 
 * @assert ($param) == $this->object->getDataForTest('getDataFromController',$param = array('template' =>''))
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
		$this->setInitController($this->current_controller['init'],$this->current_template);//Инициализируем данные
		if (isset($this->current_controller['public_methods']['default'])){
			$this->param['method']='default';
			$this->getPublicMethod($this->param);
		}
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_template);
	}else {
//		echo 'getPublicMethod =>><br />';
		if ($this->param['method']!=='default')
			$this->result['ViewMethod'][$this->param['method']] = $this->param['method'];
		if (!empty($this->current_controller['public_methods'][$this->param['method']]['view']))
			$this->result['ViewMethod'][$this->current_controller['public_methods'][$this->param['method']]['view']]=$this->current_controller['public_methods'][$this->param['method']]['view'];
		$this->getPublicMethod($this->param);
		$this->getAvailableTemplates($this->current_controller['templates'],$this->current_template);
		//echo '#$this->param["ajax"]: <pre>'; print_r($this->param["ajax"]); echo "</pre><br />\r\n";
		if ($this->param['ajax']===false){
			//Если в методе не было доступа, включаем проверку опять для инициализации метода по 
			$this->isHasAccessSaveCheck=true;
			if ($this->current_controller['page_level']!=0
					&&!empty($this->current_controller['parent']))
			{
				$parent = $this->current_controller['parent'];
				$this->result['&rArr;'.$parent.':parent-default'] = '&rArr;Parent Method <font color="orange"><b>'.$parent.'::default</b></font> is load';//Init method load double fix
				//Загружаем шаблон родителя 
				$save_template = $this->param['controller'];
				$save_method  = $this->param['method'];
				$this->param['method']= 'default';
				$this->param['controller']=$this->current_controller['parent'];
				//Выполняем в нем функции, с учётом текущего массива результатов
				$this->getDataFromController($this->param,false);
				$this->result['&lArr;'.$parent.':parent-default'] = '&lArr;Parent Method <font color="orange"><b>'.$parent.'::default</b></font> is unload';//Init method load double fix		
				$this->param['method']= $save_method;
				$this->param['controller']=$save_template;
			}elseif (!empty($this->current_controller['public_methods']['default'])){
				$this->setInitController($this->current_controller['init'],$this->current_template);//Инициализируем данные
				//Загружаем метод по умолчания в главном контроллере
				$this->param['method']='default';
				$this->result['&rArr;'.$this->current_template.':default'] = '&rArr;Extend Method <font color="orange"><b>'.$this->current_template.'::default</b></font> is load';//Init method load double fix
				$this->getPublicMethod($this->param);
				$this->result['&lArr;'.$this->current_template.':default'] = '&lArr;Extend Method <font color="orange"><b>'.$this->current_template.'::default</b></font> is unload';//Init method load double fix
			}
			$this->getAvailableTemplates($this->current_controller['templates'],$this->current_template);
		}
	}
	$this->result['REQUEST_OUT']=$this->param['form_data'];		
	//Получаем доступные шаблоны
	//Получаем доступные приватные методы доступные юзерам
	//$html.='<br/><font color="green">public_methods:</font> '.$this->getAvailableMethods($this->current_controller['public_methods'],$this->current_template,'green');
	//Получаем доступные приватные методы доступные классам
	//$html.='<br/><font color="orange">private_methods</font>: '.$this->getAvailableMethods($this->current_controller['private_methods'],$this->current_template,'orange');
	//Выводим подкотовленный массив в передаче данных в шаблон
//	if ($this->debug) {
//	echo $html;
//	}
	//return $this->result;
}


/** getAvailableTemplates() Отобразить доступные шаблоны
 * 
 * @assert ($param) == $this->object->getDataForTest('getAvailableTemplates',$param='')
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
 * @assert ($param) == $this->object->getDataForTest('getAvailableMethods',$param='')
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
 * @assert ($param) == $this->object->getDataForTest('getMethodFromClass',$param='')
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

		if (isset($this->controller_menu_view[$methods_class])){
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
			$this->result['&rArr;'.$methods_class.':'.$this->param['method']] = '&rArr;Extend Method <font color="orange"><b>'.$methods_class.'::'.$this->param['method'].'</b></font> is load';//Init method load double fix
//edit_univer_fix			if (!isset($this->result['ModelsValidation_isValidModifierParamFormError'])){
//				$is_save_validation_param= true;
//			}
				//Выполняем в нем функции, с учётом текущего массива результатов
			$this->getDataFromController($this->param,false);
			$this->result['&lArr;'.$methods_class.':'.$this->param['method']] = '&lArr;Extend Method <font color="orange"><b>'.$methods_class.'::'.$this->param['method'].'</b></font> is unload';//Init method load double fix
			$this->current_template = $this->param['controller']=$save_param['controller'];
//edit_univer_fix			if (!$is_save_validation_param)
//				$this->param['form_data']=$save_param['form_data'];
			$this->param['ajax']=$save_param['ajax'];
			$this->param['method']=$save_param['method'];
			$this->current_controller=$save_controller;
			return true;
		}else {
			$this->result['ControllerError'][]='<b>function getMethodFromClass</b>: Extend controller not exist <b>'.$methods_class.'</b>';
		}
		$methods_class=$this->getFirstArrayKey($methods_array);//Берем первый по ключу
		if (count($methods_array[$methods_class])>1)//Если методов больше одного, уменьшаем глубину на один уровень
			$methods_array=$methods_array[$methods_class];
	}
	//echo '<hr>';
	//echo '#$methods_class: <pre>'; print_r($methods_class); echo "</pre><br />\r\n";
	//if (!isset($this->loaded_class[$methods_class])){
		//echo 'isset<br />';
	//}
	//if (empty($this->loaded_class[$methods_class])){
		//echo 'empty<br />';
	//}
	//echo '#$this->loaded_class[$methods_class]: <pre>'; print_r($this->loaded_class[$methods_class]->database); echo "</pre><br />\r\n";
	//Создаём путь до класса
	//&&!empty($methods_class)
		if (empty($this->loaded_class[$methods_class])&&!empty($methods_class)){
			$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
			if (file_exists($class_dir)){//Если существует файл
			//Подключить класс
				include_once($class_dir);
//				echo '#$methods_class: <pre>'; print_r($methods_class); echo "</pre><br />\r\n";
//				echo '#$this->class_path[$methods_class]["param"]: <pre>'; print_r($this->class_path[$methods_class]["param"]); echo "</pre><br />\r\n";
				if (count($this->class_path[$methods_class]['param'])>0){
					//echo '#$this->class_path[$methods_class]["path"]: <pre>'; print_r($this->class_path[$methods_class]["param"]); echo "</pre><br />\r\n";
					$this->param=array_merge($this->param,$this->class_path[$methods_class]['param']);
				}
//				echo '#$this->param: <pre>'; print_r($this->param); echo "</pre><br />\r\n";
				$this->loaded_class[$methods_class] = new $methods_class($this->database);//Создём экземпляр
				$this->getDataFromMethod($methods_class,$methods_array);
			}
		}elseif(!empty($this->loaded_class[$methods_class])) {
			$this->getDataFromMethod($methods_class,$methods_array);
		}
/*
	$class_dir=$this->path_to.$this->class_path[$methods_class]['path'].DIRECTORY_SEPARATOR.$methods_class.'.php';
	//echo '#$class_dir: '.$class_dir."<br />\r\n";
	//$html.='&nbsp;&nbsp;'.'FILE: '.$class_dir."<br />\r\n";
	if (file_exists($class_dir)){//Если существует файл
		if (!isset($this->loaded_class[$methods_class])&&$methods_class!=''){
			//Подключить класс
			include_once($class_dir);
			$this->loaded_class[$methods_class] = new $methods_class($this->database);//Создём экземпляр
		}
		$this->getDataFromMethod($methods_class,$methods_array);
	}
 */
}

/** Получить данные из метода класса 
 *
 * 
 * @assert ($param) == $this->object->getDataForTest('getDataFromMethod',$param='getDataFromMethod')
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
			if ($this->param["PHPUnitTestStopReset"]==true)
				$this->loaded_class[$methods_class]->setResetForTest();//Сбрасываем для теста таблицу
			
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
					$answer = $this->loaded_class[$methods_class]->$methods_array_value($this->param);
			}else {
					$this->result['ControllerError'][]='<b>function getDataFromMethod</b>: Extend method not exist <b>'.$methods_array_value.'</b>';
			}
			//Ключ для массива
//			$array_key= $methods_class.':'.
//				$methods_array_value.($this->param['info']==''?'':'->'.$this->param['info']);
			$debug=true;
			//$debug=false;
//			$this->param['isPHPUnitDebug']=true;
//$this->param['isPHPUnitDebug']&&
			if ($debug=='true'){//TODO DELETE 
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
 * @assert ($param) == $this->object->getDataForTest('isUserHasAccessForMethod',$param='getDataFromMethod')
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
 * @assert ($param) == $this->object->getDataForTest('isGetMethodForAnswer',$param='')
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
 * @assert ($param) == $this->object->getDataForTest('getPublicMethod',$param=array('template' =>'profile_public'))
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
		$this->result['ControllerError'][]='<b>function getPublicMethod:</b> Method <b>'.$param['method'].'</b> not found in <b>'.$this->current_template.'</b>';
		if (!empty($this->current_controller['public_methods']['default'])){
			if (!empty($this->current_controller['public_methods']['default'])){
				$param['method']='default';
				foreach ($this->current_controller['public_methods'][$param['method']] as $_title =>$_value){
					$this->getMethodFromClass($_title,$_value);
				}
			}else {
				$this->setInitController($this->current_controller['init'],$this->current_template);//Инициализируем данные
			}
		}
	}
}

/** Запустить приватный метод
 * Запустить приватный метод
 * 
 * @assert ($param) == $this->object->getDataForTest('getPrivateMethod',$param='')
 * @param str $method 
 * @access public
 * @return void
 */
function getPrivateMethod($method){
	//Если есть такой метод
	if (!empty($this->current_controller['private_methods'][$method])){
		//Получаем метод
		$methods_callback = $this->current_controller['private_methods'][$method];
	}
	if (!empty($this->current_controller['public_methods'][$this->param['method']][$method]))
	{
	$methods_callback = $this->current_controller['public_methods'][$this->param['method']][$method];
	}
	//Запускаем каждый метод класса
	foreach ($methods_callback as $method_title =>$method_value){
		$this->getMethodFromClass($method_title,$method_value);
	}
}

/** Получить первый эл-т массива
 * Получить первый эл-т массива
 * 
 * @assert (array('TEST'=>'0','TEST2'=>'1',)) == TEST
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
	* Сделать выборку для теста
	*
	* @assert ('getControllerForParam',$param) == $this->object->getDataForTest('getControllerForParam',$param=array('test'=>'test'))
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