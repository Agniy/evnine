<?php
/**
 * en: Model for testing models and controllers.
 * ru: Модель для тестирования моделей и контроллеров. 
 * 
 * @package ModelsPHPUnit
 * @author ev9eniy
 * @version 1.0
 * @created 05-spt-2011 18:38:41
 */
class ModelsPHPUnit
{

	/** $this->evnine 
	 * en: An object with a reference to the EvnineController.
	 * ru: Объект с ссылкой на evnine контроллер.
	 * 
	 * @var object
	 * @access public
	 */
	var $evnine;
	
	/** __construct($param)
	 * en: The constructor sets the environment variables.
	 * ru: Конструктор устанавливает переменные окружения.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access protected
	 * @return void
	 */
	function __construct($param){
		set_time_limit ( '3000' );
		ini_set("memory_limit","920M");
		$this->evnine=new EvnineController();
	}
	
	/** getResetPHPUnit($param)
	 * en: Clear all PHP Unit testing.
	 * en: This method renames a folder with the php unit test results.
	 * ru: Сбросить все PHP Unit тесты.
	 * ru: Метод переименовывает папку с результатами тестов.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return boolean
	 */
	function getResetPHPUnit($param) {
		$dir_from = $this->evnine->path_to.$this->evnine->param_const['CacheDirPHPUnit'];
		$dir_to= $this->evnine->path_to.$this->evnine->param_const['CacheDirPHPUnit'].date('_Y-m-j_h-i-s',time());
		return (rename($dir_from,$dir_to)?$dir_from.$dir_to:'reset_error');
	}
	
	/** getParamCaseByParamTest(&$param)
	 * en: Determine the number of tests for the controllers.
	 * ru: Определить количество тестов для контроллеров.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getParamCaseByParamTest(&$param){
		$case_all=array();
		/**
		 * en: Reset all the case.
		 * ru: Сбрасываем все случаи.
		 */
		$case_count = 1;
		/**
		 * en: Test count.
		 * ru: Счётчик теста.
		 */
		foreach ($param["getParamTest"] as $param_id =>$param_array){
			$multi_case_flag=true;
			$case_array=array();
			foreach ($param_array as $param_title =>$param_case_array){
				/**
				 * en: Save the first case.
				 * ru: Сохраняем первый случай.
				 */
				$this->setInitParam($case_array,$param_title,$param_array,$multi_case_flag);
			}
			if ($multi_case_flag){
				$this->setGeneParam($case_array,$param_array);
			}
			$case_all[$case_count]=$case_array;
			$case_count++;
		}
		return $param['getParamCaseByParamTest']=$case_all;
	}
	
	/** getStringFromArray($array,$count)
	 * en: Recursively convert an array into a string.
	 * ru: Рекурсивно преобразовать массив в строку.
	 * 
	 * @param array $array 
	 * @param int $count 
	 * en: Meter depth.
	 * ru: Счётчик глубины.
	 * @access public
	 * @return string
	 */
	function getStringFromArray($array,$count) {
		if ($count!=''){
			$array_str.='\'md5\'=>\''.($count+1).'\',';	
		}
		$first_flag=false;
		foreach ($array as $key => $value) {
			if ($first_flag){
				$array_str.=', ';
			}else {
				$first_flag=true;
			}
			if (is_array($value)){
				$array_str.='\''.$key.'\'=>'.$this->getStringFromArray($value).'';
			}else {
				$array_str.='\''.$key.'\'=>\''.$value.'\'';
			}
		}
		$array_str='array( '.$array_str.')';
		return $array_str;
	}
	
	/** getCountParamByParamTest(&$param)
	 * en: Count the number of tests.
	 * ru: Посчитать количество тестов.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getCountParamByParamTest(&$param) {
		$case_count=0;
		$array=array();
		foreach ($param["getParamCaseByParamTest"] as $param_id =>$param_array){
			foreach ($param_array as $param_title =>$param_sub_array){
				$case_count++;
				$array[$case_count]=$param_sub_array;
			}
		}
		return $param["getCountParamByParamTest"] = $array;
	}
	
	/** getParamTextName(&$param )
	 * en: Get a description of cases.
	 * ru: Получить описание случаев.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getParamTextName(&$param ) {
		$case_count=0;
		$msg=array();
		foreach ($param["getParamCaseByParamTest"] as $param_id =>$param_array){
			foreach ($param_array as $param_title =>$param_sub_array){
				$case_count++;
				$first_flag=false;
				foreach ($param_sub_array as $_title =>$value){
					if ($first_flag){
						$msg[$case_count].=', ';
					}else {
						$msg[$case_count].=' ';
						$first_flag=true;
					}
					if (is_array($value)){
						$param_sub_array[$_title]=$this->getStringFromArray($value);
						$msg[$case_count].='<br />'.$this->getNewString($param_sub_array[$_title],$prev_array[$_title],'<b>'.$_title.'</b>: ');
					}elseif (empty($value)){
						$msg[$case_count].=$this->getNewString('NULL',$prev_array[$_title],'<b>'.$_title.'</b>: ');
						$param_sub_array[$_title]='NULL';
					}else {
						$msg[$case_count].=$this->getNewString($value,$prev_array[$_title],'<b>'.$_title.'</b>: ');
					}
				}
				$prev_array=$param_sub_array;
			}
		}
		return $param['getParamTextName']=$msg;
	}
	
	/** getNewString($after,$before,$title)
	 * en: Highlight the new string.
	 * ru: Выделить строку, если новая.
	 * 
	 * @param string $after 
	 * @param string $before 
	 * @param string $title 
	 * @access public
	 * @return string
	 */
	function getNewString($after,$before,$title) {
		if ($after!==$before){
			return '<span style="background-color:#CCFFCC;background-image:none;background-position:0 0;">'.$title.$after.'</span>';
		}else {
			return $title.$after;
		}
	}
	
	/** getPHPUnitCode($param)
	 * en: Get the source for the generation of PHP Unit Test.
	 * ru: Получить исходник для генерации PHP Unit Test.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return string
	 */
	function getPHPUnitCode(&$param) {
		$php_unit_code= '<'.'?'.'php'."\r\n";
		$php_unit_code.= '';
		$php_unit_code.='/*'.'*'."\r\n"
		.' * Auto generator skeleton PHP Unit tests for the controller.'."\r\n"
		.' * cmd/sh: phpunit --skeleton-test "evninePHPUnit"'."\r\n"
		.' * '."\r\n"
		.' * PHP Unit install:'."\r\n"
		.' * #http://www.phpunit.de/manual/3.0/en/installation.html'."\r\n"
		.' * #http://pear.php.net/manual/en/installation.getting.php'."\r\n"
		.' * '."\r\n"
		.' * wget http://pear.php.net/go-pear.phar'."\r\n"
		.' * sudo php go-pear.phar'."\r\n"
		.' * pear channel-discover pear.phpunit.de'."\r\n"
		.' * pear install phpunit/PHPUnit'."\r\n"
		.' * '."\r\n"
		.' * @filename evninePHPUnit.php'."\r\n"
		.' * @package PHPUnitTest'."\r\n"
		.' * @author evnine'."\r\n"
		.' * @updated 2011-10-03'."\r\n"
		.' */'."\r\n";
		$php_unit_code.='//$_SERVER["DOCUMENT_ROOT"]=\'\''."\r\n";
		$php_unit_code.='include_once(\'evnine.php\');'."\r\n";
		$php_unit_code.='class evninePHPUnit extends EvnineController {'."\r\n";
		$php_unit_code.='/*'.'*'."\r\n";
		$all_count=count($param['getCountParamByParamTest']);
		$if_open = true;
		if (!empty($param['getCountParamByParamTest']))
		foreach ($param['getCountParamByParamTest'] as $count =>$param_array){
			$php_function='getControllerForParam_'.$param_array['controller'].'_'.$param_array['method'].'_Test';
			if ($save_function!=$php_function&&$save_function!=''){
				if ($save_function!=''){
					$php_unit_code.=" ".'* @access public'."\r\n";
					$php_unit_code.=" ".'* @param param'."\r\n";
					$php_unit_code.=" ".'* @return array'."\r\n";
					$php_unit_code.=" ".'*/'."\r\n";
					$if_open=false;
				}
				$php_unit_code.= 'function '.$save_function.'($method,$array,$param) {'."\r\n"
					."\t\t\t".'return $this->getControllerForParamTest($method,$array,$param);'."\r\n"
					.'}'."\r\n";
				$if_open=false;
				if ($all_count!=$count){
					$php_unit_code.="\r\n";
					$php_unit_code.='/*'.'*'."\r\n";
					$if_open=true;
				}
			}
			if (!$if_open){
				$php_unit_code.='/*'.'*'."\r\n";
				$if_open=true;
			}
			//echo '#$all_count: <pre>'; if(function_exists(print_r2)) print_r2($all_count); else print_r($all_count);echo "</pre><br />\r\n";
			//echo '#$count: <pre>'; if(function_exists(print_r2)) print_r2($count); else print_r($count);echo "</pre><br />\r\n";
			$php_unit_code.=" ".'* @assert (\''.$php_function.'\',$array,$param) == ';
			$php_unit_code.='$array=($this->object->getControllerForParam(';
			$php_unit_code.='$param='.$this->getStringFromArray($param_array).'))'."\r\n";
			$save_function=$php_function;
		}
		if ($php_function!=''){
				if (!$if_open){
					$php_unit_code.='/*'.'*'."\r\n";
					$if_open=true;
				}
				//$php_unit_code.='/*'.'*'."\r\n";
				$php_unit_code.=" ".'* @access public'."\r\n";
				$php_unit_code.=" ".'* @param param'."\r\n";
				$php_unit_code.=" ".'* @return array'."\r\n";
				$php_unit_code.=" ".'*'.'/'."\r\n";
				$php_unit_code.= 'function '.$php_function.'($method,$array,$param) {'."\r\n"
					."\t\t\t".'return $this->getControllerForParamTest($method,$array,$param);'."\r\n"
					.'}'."\r\n";
		}else {
			$php_unit_code.='*'.'/'."\r\n";	
		}
		$php_unit_code.= '}'."\r\n";
		$php_unit_code.= '?'.'>';
		return $param['php_unit']=$php_unit_code;
	}
	
	/** getPHPUnitCodeWithBR($param)
	 * en: Make a change of line breaks to the br.
	 * ru: Сделать замену переноса строк на br.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return void
	 */
	function getPHPUnitCodeWithBR($param) {
		return preg_replace("/\n/",'<br>',$param['php_unit']);
	}
	
	
	/** getParamTest(&$param)
	 * en: Get a test from the controller.
	 * ru: Получить тесты из контроллера.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getParamTest(&$param){
			if (!empty($param['evnine'])){
				$controller_evnine = $param['evnine'];
				unset($param['evnine']);
			}else {
				$controller_evnine = $this->evnine;
			}
			$php_unit_param_for_all = $php_unit_param=array();
			$param_out=array();
			/**
			 * en: For each model
			 * ru: Для каждой модели 
			 */
			foreach ($controller_evnine->controller_alias as $controller_config_alias =>$controller_class_name)
				if ($param['controller']!==$controller_config_alias){
					$controller_path='controllers';
					if (is_array($controller_class_name)){
						$controller_path= $controller_class_name['path'];
						$controller_class_name=$controller_class_name['class_name'];
					}
					$controller_file = $controller_evnine->path_to.$controller_path.DIRECTORY_SEPARATOR.$controller_class_name.'.php';
				if (file_exists($controller_file)){
					include_once($controller_file);
					$controller_class=new $controller_class_name();
					$php_unit_param_for_all=$controller_class->controller['inURLUnitTest'];
					if (count($php_unit_param_for_all)==0){
						$php_unit_param_for_all=array();
					}else {
						foreach ($php_unit_param_for_all as $php_unit_param_title =>$php_unit_param_value){
							if (!is_array($php_unit_param_value)){
								$php_unit_param_for_all[$php_unit_param_title]=array($php_unit_param_value);
							} 
						}
					}
					$public_methods= $controller_class->controller['public_methods'];
					foreach ($public_methods as $method =>$public_methods_value){
						if (!empty($public_methods_value['inURLUnitTest'])){
							$php_unit_param=$public_methods_value['inURLUnitTest'];
							foreach ($php_unit_param as $php_unit_param_title =>$php_unit_param_value){
								if (!isset($php_unit_param_value[0])){
									$php_unit_param[$php_unit_param_title]=array($php_unit_param_value);
								}
							}
						}else {
							$php_unit_param=array();
						}
						$full_unit_param=array_merge($php_unit_param_for_all,$php_unit_param);
						$full_unit_param=array_merge(array(
							'controller'=> array($controller_config_alias),
							'method'=> array($method)
						),$full_unit_param);
						$param_out[]= $full_unit_param;
					}
				}
			}
			return $param['getParamTest']= $param_out;
	}
	
	
	/** setInitParam(&$case_array,$param_title,&$param_array,&$multi_case_flag)
	 * en: Cloning options for creating tests of the controller.
	 * ru: Клонирование параметров для создания тестов контроллера.
	 * 
	 * @param array &$case_array 
	 * @param string $param_title 
	 * @param array &$param_array 
	 * @param boolean $multi_case_flag 
	 * @access public
	 * @return array
	 */
	function setInitParam(&$case_array,$param_title,&$param_array,&$multi_case_flag){
		$count = count($param_array[$param_title]);
		if ($count>0){
			$key = $this->_getFirstArrayKey($param_array[$param_title]);
			if ((int)$key>0||$key=='0'){
				$case_array['1'][$param_title]=$param_array[$param_title][$this->_getFirstArrayKey($param_array[$param_title])];
			}else {
				$case_array['1'][$param_title][$key]=$param_array[$param_title][$key];
			}
			if ($count==1){
				unset($param_array[$param_title]);
			}
			if ($count>1){
				$multi_case_flag= true;
			}
		}else {
			if (isset($param_array[$param_title])&&!isset($case_array[$param_title])){
				$case_array['1'][$param_title]='';
				unset($param_array[$param_title]);
			}
		}
	}

	/** setGeneParam(&$case_array,$param_array)
	 * en: Generating options for all tests.
	 * ru: Генерирование параметров для всех случаев.
	 * 
	 * @param array $case_array 
	 * @param array $param_array 
	 * @access public
	 * @return void
	 */
	function setGeneParam(&$case_array,$param_array){
		$case_count=1;
		foreach ($param_array as $param_title =>$param_array_out){
			$j_count = 1;
			$param_count=count($param_array_out);
			// ru: Делаем копии текущих случаев в зависимости от кол-во новых параметоров
			for ( $i = 1; $i <= $param_count; $i++ ) {
				for ( $j = 1; $j <= $case_count; $j++ ) {
					$case_array[$j_count]=$case_array[$j];
					$j_count++;
				}
			}
			$save_i=1;
			$count=0;
			// ru: Заполняем параметры случаями
			foreach ($param_array_out as $param_array_title =>$param_array_value){
				$count++;
				for ( $i = $save_i; $i <= $case_count*$count; $i++ ){
					$case_array[$i][$param_title]=$param_array_value;
				}
				$save_i= $i;
			}
			$case_count=count($case_array);
		}
	}
	
	/** getFirstArrayKey($array,$get_value=false)
	 * en: Get the first element of the array as a key or value.
	 * ru: Получить первый элемент массива как ключ или значение.
	 * 
	 * @param array $array 
	 * en: An input array.
	 * ru: Массив для обработки.
	 * @param boolean $get_value 
	 * en: Is first value?
	 * ru: Нужно значение массива?
	 * @assert (array('TEST'=>'0','TEST2'=>'1',)) == TEST
	 * @access private
	 * @return string
	 */
	private function _getFirstArrayKey($array,$get_value=false) {
		$tmp = each($array);
		list($key, $value)=$tmp;
		if (!$get_value){
		/**
		 * en: If you need a key.
		 * ru: Если нужен ключ.
		 */
			return $key;
		}else {
		/**
		 * en: If you want to get the value.
		 * ru: Если нужно получить значение параметра.
		 */
			return $value;
		}
	}

	
	/** getDataFromControllerByParam($param)
	 * en: Get the answer from the controller to the generated parameters.
	 * ru: Получить ответ от контроллера по параметрам.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getDataFromControllerByParam(&$param) {
		$array=array();
		$unique_case=array();
		foreach ($param["getCountParamByParamTest"] as $param_id =>$param_array){
			try{
				$file = $this->getFileNameMD5ForParam($this->evnine->path_to.$this->evnine->param_const['CacheDirControllerForParam'],$param_array,$md5);
				$param['PHPUnitFile'][$param_id] = $this->getFileNameMD5ForParam($this->evnine->path_to.$this->evnine->param_const['CacheDirPHPUnit'],$param_array,$md5);
				$update_source=false;
				$update_info=false;
				$time_cache=0;
				if (!$array[$param_id] = $this->getFromCache($file/*$param_array,$reset_cache*/)){
					$array[$param_id]=$this->evnine->getControllerForParam($param_array);
					$getMD5KeyForControllerAnswer= $this->_getMD5KeyForControllerAnswer($array[$param_id]);
					if (empty($unique_case[$getMD5KeyForControllerAnswer])){
						$unique_case[$getMD5KeyForControllerAnswer]=$param_id;
					}else {
						$param['same_case'][$param_id]=$unique_case[$getMD5KeyForControllerAnswer];
					}
					$this->getFromCache(
						$file,$array[$param_id],$reset_cache=true
					);
				}else {
				/**
				 * ru: Случай для учёта обновления модели или контроллера
				 * ru: Если что-то изменилось, обновим кэш.
				 */
					$controller = $this->evnine->controller_alias[$array[$param_id]['LoadController']];
					if ($param['modifier_time'][$controller]['updated']){
						$update_source=true;
					}elseif (!empty($param['modifier_time'][$controller])){
						if (!$time_cache){
								$time_cache = $this->_getModifierFileTime($file)	;
						}
						if ($time_cache<$param['modifier_time'][$controller]['time']){
							$update_source=true;
							$param['modifier_time'][$controller]['updated']=true;
							$update_info='Controller is updated: '.$array[$param_id]['LoadController'].' => '.$this->evnine->controller_alias[$array[$param_id]['LoadController']];
						}
					}
					if (!$update_source){
						foreach ($array[$param_id] as $key =>$_value){
							if (preg_match("/.*(?=_[gsi])/",$key,$method)){
								if ($param['modifier_time'][$method['0']]['updated']){
									$update_source=true;
									break;
								}elseif (!empty($param['modifier_time'][$method['0']])){
									if (!$time_cache){
										$time_cache = $this->_getModifierFileTime($file)	;
									}
									if ($time_cache<$param['modifier_time'][$method['0']]['time']){
										$update_source=true;
										$update_info='Models is updated: ['.$method['0'].']';
										$param['modifier_time'][$method['0']]['updated']=true;
										break;
									}
								}
						}
						}
					}
				}
				if ($update_source){
					if ($update_info){
						echo '<font color="red">Reset cache file - '.$update_info.'</font><br />';
					}
					$array[$param_id]=$this->evnine->getControllerForParam($param_array);
					$this->getFromCache($file,$array[$param_id],$reset_cache=true);
					$getMD5KeyForControllerAnswer= $this->_getMD5KeyForControllerAnswer($array[$param_id]);
					if (empty($unique_case[$getMD5KeyForControllerAnswer])){
							$unique_case[$getMD5KeyForControllerAnswer]=$param_id;
						}else {
							$param['same_case'][$param_id]=$unique_case[$getMD5KeyForControllerAnswer];
					}
				}
			}catch(Exception $e){
				$array[$param_id]= $e;
			}
		}
		return $param['getDataFromControllerByParam']=$array;
	}
	
	/** _getMD5KeyForControllerAnswer($param)
	 * en: Get the key to the answer without the input and output parameters.
	 * ru: Получить ключ для ответа без учёта параметров на входе выходе и переходов.
	 * 
	 * @param array $array 
	 * @access private
	 * @return string
	 */
	private function _getMD5KeyForControllerAnswer($array) {
		unset($array['LoadController']);
		unset($array['LoadMethod']);
		unset($array['ajax']);
		unset($array['REQUEST_IN']);
		unset($array['REQUEST_OUT']);
		unset($array['param']);
		return md5($this->_getMultiImplode($array));
	}
	
	
	/** getComparePHPUnitForControllers(&$param)
	 * en: Compare the current controller with stored responses for PHPUnit.
	 * ru: Сравнить текущие ответы контроллеров с сохраненными для PHPUnit.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getComparePHPUnitForControllers(&$param) {
		$array = $array_tmp=array();
		foreach ($param['PHPUnitFile'] as $param_id =>$file) if (empty($param['same_case'][$param_id])){
			if (!$array_tmp = $this->getFromCache($file)){
					$this->getFromCache(
						$file,$param['getDataFromControllerByParam'][$param_id],$reset_cache=true
				);
				$array[$param_id]= false;
			}else {
				// ru: Сравнение MD5 двух ответов
				if (md5($this->_getMultiImplode($array_tmp))!==
					md5($this->_getMultiImplode($param['getDataFromControllerByParam'][$param_id]))
				){
					if ($this->param['debug_param_diff'])
						$array[$param_id]=getForDebugArrayDiff(
							$param['getDataFromControllerByParam'][$param_id],$array_tmp
						);
					if (empty($array[$param_id])){
						$array[$param_id]=false;
					}
				}else {
					$array[$param_id]=false;
				}
			}
		}else {
			if (!empty($array[$param['same_case'][$param_id]])){
				$array[$param_id]=$array[$param['same_case'][$param_id]];
				if (!$array_tmp = $this->getFromCache($file)){
					$this->getFromCache(
						$file,$param['getDataFromControllerByParam'][$param_id],$reset_cache=true
				);
				}
			}
		}
		unset($param['PHPUnitFile']);
		return $param['PHPUnitCompare'] = $array;
	}
	
	/** getFileNameMD5ForParam($path,$param,&$md5=false)
	 * en: Get a folder and file name parameters.
	 * ru: Получить папку и имя файла для параметров.
	 * 
	 * @param string $path 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @param string &$md5 
	 * @access public
	 * @return string
	 */
	function getFileNameMD5ForParam($path,$param,&$md5=false) {
		if (!$md5){
			$md5= md5($this->_getMultiImplode($param));
		}
		return $path.DIRECTORY_SEPARATOR.$param['controller'].'-'.$param['method'].'-'.$md5.'.php';
	}
	
	/** getFromCache($file_name,$array_data=false,$reset=false)
	 * en: Get the data from cache
	 * ru: Сделать выборку из кэша.
	 * 
	 * @param string $file_name 
	 * @param array $array_data 
	 * @param boolean $reset 
	 * @access public
	 * @return array
	 */
	function getFromCache($file_name,$array_data=false,$reset=false){
			// ru: Если нужно сбросить кэш по флагу
			if($reset==true&&!isset($array_data)){
				return false;
			}
			// ru: Получаем данные из кэша
			$array = $this->getSerData($file_name);
			if (empty($array)&&isset($array_data)||$reset===true){//||$reset==true
				// ru: Сохраняем в кэш
				$this->setSerData($file_name,$array_data,$reset);
				$array=$array_data;
			}
			if (empty($array)){
				$array=false;
			}
			// ru: Очищаем все данные
			return $array;
	}
	
	/** getSerData($file_name,$param) 
	 * en: Get data from a file.
	 * ru: Получить данные из файла.
	 * 
	 * @param string $file_name
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @return string
	 */
		function getSerData($file_name,$param) 
		{
			if (!file_exists($file_name))
				return '';
			return unserialize(file_get_contents($file_name));
		}
	
	/** _getMultiImplode($pieces)
	 * en: Combines the line in the array.
	 * ru: Объединяет строки в массиве.
	 * 
	 * @param string $pieces 
	 * @access private
	 * @return string
	 */
	private function _getMultiImplode($pieces)
	{
		$string='';
		if(is_array($pieces))
		{
				reset($pieces);
				while(list($key,$value)=each($pieces))
				{
					$string.=$key.$this->_getMultiImplode($value);
				}
		}
		else
		{
				return $pieces;
		}
		return $string;
	}
	
	/** _setCreateDir($file_dir)
	 * en: Create a folder tree.
	 * ru: Создать древо папок.
	 * 
	 * @param string $dir_name 
	 * @access private
	 * @return void
	 */
	public function _setCreateDir($file_dir) {
		if (!file_exists($file_dir)&&strlen($file_dir)>1){
			$this->_setCreateDir(dirname($file_dir));
			mkdir($file_dir, 0777);
		}
	}
	
	/** setSerData($file_name, $str, $reset=false) 
	 * en: Save the data.
	 * ru: Сохранить данные.
	 * 
	 * @param string $file_name 
	 * @param array $str 
	 * @param boolean $reset 
	 * @access public
	 * @return string
	 */
	function setSerData($file_name, $str, $reset=false){
			$file_dir=dirname($file_name);
			$this->_setCreateDir($file_dir);
			if (!file_exists($file_dir)){
				echo '<br />Error ModelsPHPUnit.php line:'.__LINE__.' please check or create Cache folder'."\r\n";
				echo '$file_dir: '.$file_dir."<br />\r\n";
				echo '$file_name: '; echo($file_name); echo "<br />\r\n";
			}elseif($reset!=true) {
				return $str;
			}
			$save = fopen($file_name,'wb');
			fputs($save,serialize($str));
			fclose($save);
			return $str;
		}
	
	/** getModelsAndControllerModifierTimeFromCache(&$param)
	 * en: Get a time of change models and controllers.
	 * ru: Получить время изменения моделей и контроллеров.
	 * 
	 * @param array $param 
	 * en: An array of parameters.
	 * ru: Основной массив параметров.
	 * @access public
	 * @return array
	 */
	function getModelsAndControllerModifierTimeFromCache(&$param){
		$array=array();
		$file_cache = $this->evnine->path_to.$this->evnine->param_const['CacheDir'].DIRECTORY_SEPARATOR.'cache_for_controllers_and_models.php';
		$file_time_with_cache = $this->_getModifierFileTime($file_cache)+$this->evnine->param_const['CacheTimeSecPHPUnit'];
		if ($file_time_with_cache>=time()){
			$array = $this->getFromCache($file_cache,array());
		}elseif ($this->evnine->param_const['CacheTimeSecPHPUnit']!=0){
			echo '<b><font color="red">cache time out ('.date('F d Y H:i:s',$file_time_with_cache).' < now '.date('F d Y H:i:s',time()).'), set reset for the file:</font></b><br />';
			echo $file_name.'<br />';
		}
		if (!$array){
			$array=array();
			foreach ($this->evnine->class_path as $class_name =>$class_path){
				$array[$class_name]= 
					array(
						'time'=>$this->_getModifierFileTime($this->evnine->path_to.$class_path['path'].$class_name.'.php')
						,'debug'=>date('F d H:i:s',$this->_getModifierFileTime($this->evnine->path_to.$class_path['path'].$class_name.'.php'))
				);
			}
			foreach ($this->evnine->controller_alias as $class_name){
				$array[$class_name]= 
					array(
						'time'=>$this->_getModifierFileTime($this->evnine->path_to.'controllers'.DIRECTORY_SEPARATOR.$class_name.'.php')
						,'debug'=>date('F d H:i:s',$this->_getModifierFileTime($this->evnine->path_to.'controllers'.DIRECTORY_SEPARATOR.$class_name.'.php'))
				);
			}
			$this->getFromCache($file_cache,$array,$reset=true);
		}
		return $param['modifier_time']=$array;
	}
	
	/** _getModifierFileTime($file_path){
	 * en: Get file modification time.
	 * ru: Получить время модификации файла.
	 * 
	 * @link http://www.php.net/manual/en/function.filemtime.php
	 * @param string $file_path 
	 * @access private
	 * @return string
	 */
	private function _getModifierFileTime($file_path){
		$time = filemtime($file_path);
		$isDST = (date('I', $time) == 1);
		$systemDST = (date('I') == 1);
		$adjustment = 0;
		if($isDST == false && $systemDST == true){
			$adjustment = 3600;
		}else if($isDST == true && $systemDST == false){
			$adjustment = -3600;
		}else {
			$adjustment = 0;
		}
		return ($time + $adjustment);
	} 
	
	
	}
?>
