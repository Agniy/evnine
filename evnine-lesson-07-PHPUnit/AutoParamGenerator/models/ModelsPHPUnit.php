<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 01-oct-2010 22:03:41
 */
class ModelsPHPUnit
{

var $evnine;
function __construct(){
	set_time_limit ( '3000' );
	ini_set("memory_limit","920M");
	$this->evnine=new Controller();
}

/** function getResetPHPUnit($param)
 * Сбросить данные для тестов
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getResetPHPUnit($param) {
	$dir_from = $this->evnine->path_to.$this->evnine->param_const['CacheDirPHPUnit'];
	$dir_to= $this->evnine->path_to.$this->evnine->param_const['CacheDirPHPUnit'].date('_Y-m-j_h-i-s',time());
	return (rename($dir_from,$dir_to)?$dir_from.$dir_to:'reset_error');
}

/** getParamCaseByParamTest(&$param)
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getParamCaseByParamTest(&$param){
	//Сбрасываем все случаи
	$case_all=array();
	//Счётчик теста
	$case_count = 1;
	foreach ($param["getParamTest"] as $param_id =>$param_array){
		$multi_case_flag=true;
		$case_array=array();
		foreach ($param_array as $param_title =>$param_case_array){
			/**
			 * ru: Сохраняем первый случай
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
	//return $param['getParamCaseByParamTest']=$case_all;
}

/** getStringFromArray($array)
	* getStringFromArray 
	* Получить из массива строку
	* 
	* @param mixed $array 
	* @access public
	* @return void
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
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getCountParamByParamTest(&$param) {
	$case_count=0;
	$array=array();
	foreach ($param["getParamCaseByParamTest"] as $param_id =>$param_array){
		//echo '#$param_array: <pre>'; if(function_exists(print_r2)) print_r2($param_array); else print_r($param_array);echo "</pre><br />\r\n";
		foreach ($param_array as $param_title =>$param_sub_array){
			$case_count++;
			$array[$case_count]=$param_sub_array;
		}
	}
	return $param["getCountParamByParamTest"] = $array;
}

/** getParamTextName(&$param )
 * 
 * @param mixed $param_out 
 * @access public
 * @return void
 */
function getParamTextName(&$param ) {
	$case_count=0;
	$msg=array();
	foreach ($param["getParamCaseByParamTest"] as $param_id =>$param_array){
		//echo '#$param_array: <pre>'; if(function_exists(print_r2)) print_r2($param_array); else print_r($param_array);echo "</pre><br />\r\n";
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
	//echo '#$msg: <pre>'; if(function_exists(print_r2)) print_r2($msg); else print_r($msg);echo "</pre><br />\r\n";
	//TODO остановился на генераторе описаний к параметрам нашел ошибку в отладке передачи параметров
	//не становится серым
}

/** getNewString($after,$before)
	* 
	* Выделить строку если новая
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getNewString($after,$before,$title) {
	if ($after!==$before){
		return '<span style="background-color:#CCFFCC;background-image:none;background-position:0 0;">'.$title.$after.'</span>';
	}else {
		return $title.$after;
	}
}

/** getPHPUnitCode($param)
 * ru: Получить PHP Unit код
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getPHPUnitCode($param) {
	$php_unit_code= '&lt;'.'?'.'php'."\r\n".'<br />';
	$php_unit_code.= '';
	$php_unit_code.='/**<br />
		* Auto generator skeleton PHP Unit tests for the controller.<br />
		* cmd/sh: phpunit --skeleton-test "evninePHPUnitTest"<br />
		* <br />
		* PHP Unit install:<br />
		* #http://www.phpunit.de/manual/3.0/en/installation.html<br />
		* #http://pear.php.net/manual/en/installation.getting.php<br />
		* <br />
		* wget http://pear.php.net/go-pear.phar<br />
		* sudo php go-pear.phar<br />
		* pear channel-discover pear.phpunit.de<br />
		* pear install phpunit/PHPUnit<br />
		* <br />
		* @filename evninePHPUnitTest.php<br />
		* @package PHPUnitTest<br />
		* @author evnine<br />
		* @updated '.date('Y-m-d',time()).'<br />
		*/<br />';
	$php_unit_code.='//$_SERVER["DOCUMENT_ROOT"]=\'\'<br />';
	$php_unit_code.='include_once(\'evnine.php\');<br />';
	$php_unit_code.='class evninePHPUnitTest extends Controller {<br />';
	$php_unit_code.='/*'.'*'."\r\n<br />";
	$all_count=count($param['getCountParamByParamTest']);
	//$php_unit_code.='$all_count:'.$all_count;
	foreach ($param['getCountParamByParamTest'] as $count =>$param_array){
		$php_function='getControllerForParam_'.$param_array['controller'].'_'.$param_array['method'].'_Test';
		if ($save_function!=$php_function&&$save_function!=''){
			if ($save_function!=''){
				$php_unit_code.='&nbsp;&nbsp;&nbsp;*/'."\r\n<br />";
			}
			$php_unit_code.= 'function '.$save_function.'($method,$array,$param) {'."\r\n<br />"
				.'&nbsp;&nbsp;$this->getControllerForParamTest($method,$array,$param);'."\r\n<br />"
				.'&nbsp;&nbsp;return $this->result;'."\r\n<br />"
				.'}'."\r\n<br />";
			if ($all_count!=$count){
				$php_unit_code.='<br />';
				$php_unit_code.='/*'.'*'."\r\n<br />";
			}
		}
		$php_unit_code.='&nbsp;&nbsp;&nbsp;* @assert (\''.$php_function.'\',$array,$param) == ';
		$php_unit_code.='$array=($this->object->getControllerForParam(';
		$php_unit_code.='$param='.$this->getStringFromArray($param_array).'))'."\r\n<br />";
		//$php_unit_code.='&nbsp;&nbsp;&nbsp;* @assert ($param) == $this->object->getControllerForParamTest'.'(\'';
		//$php_unit_code.=$php_function;
		//$php_unit_code.='\',$param='.$this->getStringFromArray($param_array).')'."\r\n<br />";
		$save_function=$php_function;
	}
			$php_unit_code.='&nbsp;&nbsp;&nbsp;* @access public<br />';
			$php_unit_code.='&nbsp;&nbsp;&nbsp;* @param param<br />';
			$php_unit_code.='&nbsp;&nbsp;&nbsp;* @return array<br />';
			$php_unit_code.='&nbsp;&nbsp;&nbsp;*'.'/'.'<br />';
			$php_unit_code.= 'function '.$php_function.'($method,$array,$param) {'."\r\n<br />"
				.'&nbsp;&nbsp;$this->getControllerForParamTest($method,$array,$param);'."\r\n<br />"
				.'&nbsp;&nbsp;return $this->result;'."\r\n<br />"
				.'}'."\r\n<br />";
	$php_unit_code.= '}<br />';
	$php_unit_code.= '?'.'>';
	return $php_unit_code;
	/*
	if (is_array($filter)){
		$php_function='getControllerForParam'.getMethodNameForTest($template,$filter).'Test';
	}else {
		$php_function='getControllerForParam'.getMethodNameForTest($template,$filter).'Test';
	}
	foreach ($save_case as $save_case_title =>$save_case_value)if (count($save_case_value)!='1'){
		$this_comment=getCommentFromArray($save_case_value);
		if ($limit_test>$save_case_title) break;
		$phpunit.=' * '.$this_comment."\r\n";
		$phpunit.=' * @assert ($param) == $this->object->getDataForTest'.'(\'';
		$phpunit.=$php_function;
		$phpunit.='\',$param='.getArrayToPHPCode($save_case_value,$save_case_title).',false)'."\r\n";
	}
	$phpunit.= '*'.'/'."\r\n";
	$phpunit.= 'function '.$php_function.'($param,$debug=false) {'."\r\n"
		.'$this->getControllerForParam($param,$debug);'."\r\n"
		.'return $this->result;'."\r\n"
		.'}'."\r\n";
	return $phpunit ;
*/
}



/** getParamTest(&$param)
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getParamTest(&$param){
		$controller_evnine = $this->evnine;
		$php_unit_param_for_all = $php_unit_param=array();
		$param_out=array();
		/**
		 * en: For each model
		 * ru: Для каждой модели 
		 */
		foreach ($controller_evnine->controller_alias as $controller_config_alias =>$controller_class_name)
		if ($param['controller']!==$controller_config_alias){
			$controller_file = $controller_evnine->path_to.'controllers'.DIRECTORY_SEPARATOR.$controller_class_name.'.php';
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
					$php_unit_param=$public_methods_value['inURLUnitTest'];
					foreach ($php_unit_param as $php_unit_param_title =>$php_unit_param_value){
							if (!isset($php_unit_param_value[0])){
								$php_unit_param[$php_unit_param_title]=array($php_unit_param_value);
							}
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
	//return $param_out;
}


/** setInitParam ()
	 * 
	 * ru: Клонирование параметров для создания случаев запроса контроллера
	 * 
	 * @param $array
	 * @param $clone_level 
	 * @access public
	 * @return array
	 */
function setInitParam(
	&$case_array,
	$param_title,
	&$param_array,
	&$multi_case_flag
){
	$count = count($param_array[$param_title]);
	if ($count>0){
		$case_array['1'][$param_title]=$param_array[$param_title][$this->_getFirstArrayKey($param_array[$param_title])];
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

/** setGeneParam ()
	 * 
	 * ru: Копирование параметров
	 * 
	 * @param $array
	 * @param $clone_level 
	 * @access public
	 * @return array
	 */
function setGeneParam(
	&$case_array,
	$param_array
){
	//$case_array=$case_array;
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
			//$param_array_title++;
			//echo 'for ( $i = '.$save_i.'; $i <= '.$case_count.'*'.$param_array_title.'; $i++ )<br />';
			for ( $i = $save_i; $i <= $case_count*$count; $i++ ){
			//for ( $i = $save_i; $i <= $case_count*$param_array_title; $i++ ){
				$case_array[$i][$param_title]=$param_array_value;
				//if(function_exists(print_r2))$query[ '#$case_array[$i][$param_title]' ]=$case_array[$i][$param_title];else echo '<pre>'.print_r($case_array[$i][$param_title]).'</pre>';
			}
			$save_i= $i;
		}
		$case_count=count($case_array);
	}
}

/** _getFirstArrayKey($array,$key_mode='key')
 * Получить первый эл-т массива
 * 
 * @assert (array('TEST'=>'0','TEST2'=>'1',)) == TEST
 * @param mixed $array 
 * @param string $need 
 * @access public
 * @return void
 */
private function _getFirstArrayKey($array,$key_mode='key') {
	$tmp = each($array);
	list($key, $value)=$tmp;
	if ($key_mode=='key'){
		return $key;
	}else {
		return $value;
	}
}

/** getDataFromControllerByParam($param)
 *
 * ru: Получить ответ от контроллера
 * 
 * @param mixed $param 
 * @access public
 * @return void
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
				*  Случай для учёта обновления модели или контроллера
				*  Изменилось что либо - обновили кэш
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
	* 
	* Получить ключ для ответа без учёта входных, выходных и переходов
 * @param mixed $param 
 * @access public
 * @return void
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
 * 
 * Сравнить текущие ответы контроллеров с сохраненными для PHPUnit
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getComparePHPUnitForControllers(&$param) {
	$array = $array_tmp=array();
	foreach ($param['PHPUnitFile'] as $param_id =>$file) if (empty($param['same_case'][$param_id])){
		//echo '#$file: '; echo($file); echo "<br />\r\n";
		if (!$array_tmp = $this->getFromCache($file)){
				 $this->getFromCache(
					$file,$param['getDataFromControllerByParam'][$param_id],$reset_cache=true
			);
			$array[$param_id]= false;
		}else {
			/**
				*  Сравнение MD5 двух ответов
				*/
			if (md5($this->_getMultiImplode($array_tmp))!==
				md5($this->_getMultiImplode($param['getDataFromControllerByParam'][$param_id]))
			){
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

/**
 * getFileNameForParam 
 * 
 * @param mixed $path 
 * @param mixed $param 
 * @param mixed $md5 
 * @access public
 * @return void
 */
function getFileNameMD5ForParam($path,$param,&$md5=false) {
	if (!$md5){
		$md5= md5($this->_getMultiImplode($param));
	}
	return $path.DIRECTORY_SEPARATOR.$param['controller'].'-'.$param['method'].'-'.$md5.'.php';
}

/** getFromCache($file_name,$array_data,$reset=false){
	* Сделать выборку из кэша
	*/
function getFromCache($file_name,$array_data=false,$reset=false){
	//Если нужно сбросить кэш по флагу
		if($reset==true&&!isset($array_data)){
			return false;
		}
		//Получаем данные из кэша
		$array = $this->getSerData($file_name);
		if (empty($array)&&isset($array_data)||$reset===true){//||$reset==true
			//Сохраняем в кэш
			$this->setSerData($file_name,$array_data,$reset);
			$array=$array_data;
		}
		if (empty($array)){
			$array=false;
		}
		//Очищаем все данные
		return $array;
}

	/** getSerData($file_name,$param) 
	 * Получить данные
	 * 
 	 * @assert ($param) == $this->object->getDataForTest('getSerData',$param=array('test'=>'test'))
	 * @param file_name
	 */
	function getSerData($file_name,$param) 
	{
		if (!file_exists($file_name))
			return '';
		return unserialize(file_get_contents($file_name));
	}

/** _getMultiImplode($pieces)
 * Объединяет вложенные массивы
 * 
 * @param mixed $pieces 
 * @access public
 * @return void
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

/** setCreateDir 
 *  Создать древо древо папок
 * 
 * @param string $dir_name 
 * @access public
 * @return void
 */
public function _setCreateDir($file_dir) {
	if (!file_exists($file_dir)&&strlen($file_dir)>1){
		$this->_setCreateDir(dirname($file_dir));
		mkdir($file_dir, 0777);
	}
}

	/** setSerData($file_name, $str) 
	 * Сохранить данные
	 * 
	 * @param file_name
	 * @param str
	 */
	function setSerData($file_name, $str,$reset=false) 
	{
		$file_dir=dirname($file_name);
		$this->_setCreateDir($file_dir);
		if (!file_exists($file_dir)){
			echo '<br />Error ModelsPHPUnit.php line:'.__LINE__.' please check or create Cache folder'."\r\n";
			echo '$file_dir: '.$file_dir."<br />\r\n";
			echo '$file_name: '; echo($file_name); echo "<br />\r\n";
		}elseif($reset!=true) {
			return $str;
		}
		//echo '#$file_name: '; echo($file_name); echo "<br />\r\n";
		$save = fopen($file_name,'wb');
		fputs($save,serialize($str));
		fclose($save);
		return $str;
	}

/** getModelsModifierTimeFromCache(&$param){
 * Получить время изменения моделей и контроллеров 
 * 
 * @param mixed $param 
 * @access public
 * @return void
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
	* 
	* winapi fix
	* http://www.php.net/manual/en/function.filemtime.php
	* 
	* @param mixed $filePath 
	* @access public
	* @return void
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
