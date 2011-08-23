<?php

/**
 * ModelsValidation
 *
 * @package ModelsBase
 * @author ev9eniy
 * @version 1.0
 * @created 07-apr-2010 11:03:39
 */

class ModelsValidation
{

	private $_valid_input;

	/**
	 * Constructor
	 * 
	 */
	function __construct($database){
		$this->database=$database;//Сохраняем досту
		$this->init();
	}

	function init() 
	{
		$this->_valid_input=array();
	}


	/**
	* isValidModifierParamFormError 
	* 
	* @param mixed $param 
	* @access public
	* @return void
	*/
	function isValidModifierParamFormError(&$param) 
	{ 
		$param['array_name']='REQUEST';
		$isValid = $this->isValid(&$param);
		unset($param['validation']);
		unset($param['array_name']);
		return $isValid ;
	}
	
	/**
	 * getStrlenUTF8 
	 * 
	 * @param mixed $str 
	 * @access public
	 * @return void
	 */
	function getStrlenUTF8($str){
		if (function_exists('mb_strlen')) 
			return mb_strlen($str, 'utf-8');
		return strlen(utf8_decode($str));
	}

	
	/**
	 * isRequestValid?
	 * 
	 * @assert ($param) == $this->object->getDataForTest('isValidModifierParamFormError',$param=array('test'=>'test'))
	 * @param param
	 */
	function isValid(&$param) 
	{ 
		$param_form_data=array();
		$key_template=$param["template"].(!empty($param["method"])?'::':'::default').$param["method"];
		if (isset($param['validation'])){
			$validation=$param['validation'];
		}else {
			$validation=$this->_valid_input[$key_template];
		}
		foreach ($validation as $title => $value){
			$to = $validation[$title]['to'];
			if (isset($param[$param['array_name']][$title])){
						if ($validation[$title]['is_array']){
							if (!is_array($param[$param['array_name']][$title])){
								$param_form_data[$to]=array($param[$param['array_name']][$title]);
							}else {
								$param_form_data[$to]=$param[$param['array_name']][$title];
							}
						}else {
							$param_form_data[$to]=trim($param[$param['array_name']][$title]);
						}
					switch ( $validation[$title]['type'] ) {
					case 'int' :
						if ($validation[$title]['is_array']){
							foreach ($param_form_data[$to] as $_title =>$_value){
								if (!empty($_value))
									$param_form_data[$to][$_title]=intval($_value);
							}
						}else {
							if (!empty($param_form_data[$to]))
								$param_form_data[$to] = intval($param_form_data[$to]);
							}
						break;
					case 'email' :
						$param_form_data[$to] = mysql_escape_string(htmlspecialchars(strtolower($param_form_data[$to])));
						if (!empty($param_form_data[$to])&&!filter_var((String)$param_form_data[$to], FILTER_VALIDATE_EMAIL)){//Проверяем валидность
							if ($validation[$title]['required'])
								$form_errors[$title][]=$validation[$title]['error_format'];//Ставим ошибку в валидности данных
						}
						case 'pass' :
							$param_form_data[$to] = mysql_escape_string($param_form_data[$to]);
						case 'str' :
							$param_form_data[$to] = mysql_escape_string(htmlspecialchars(strip_tags($param_form_data[$to])));
							break;
						case 'link' :
								$param_form_data[$to] = preg_replace("/(http:)|(\\\*)|(\/*)|(www\.)|(\?.*)|(@)/i","",$param_form_data[$to]);
						break;
						/*
						//Donwload from htmlpurifier.org
						case 'html' :
								include_once('/htmlfilter/HTMLPurifier.auto.php');
							if ($purifier==''){
								$config = HTMLPurifier_Config::createDefault();
								$purifier = new HTMLPurifier($config);
							}
							$param_form_data[$to] = htmlspecialchars($purifier->purify($param_form_data[$to]));
							break;								
					*/
					}

					$to_strlen = $this->getStrlenUTF8($param_form_data[$to]);
					if ($validation[$title]['required']){
						if ($validation[$title]['is_array']){
							if (count($param_form_data[$to])==0)
								return false;
						}else {
							if ($param_form_data[$to]==''||$to_strlen==0) {
								$form_errors[$title][]=$validation[$title]['error'];
							}
						}
					}
					if ($to_strlen>$validation[$title]['max']){
							$form_errors[$title][]='valid';
					}
					if (!empty($validation[$title]['min']))
						if ($to_strlen<$validation[$title]['min']){
								$form_errors[$title][]=$validation[$title]['min_error'];
					}
				}elseif ($validation[$title]['required']
					&&$param['RestoreCall']!=true
					){
						$form_errors[$title][]=$validation[$title]['error'];
					}elseif ($validation[$title]['type']==='file'){
						if (isset($_FILES[$title])){
							$param_form_data[$to]=$_FILES[$title];
						}elseif ($validation[$title]['required']) {
							$form_errors[$title][]=$validation[$title]['error'];
						}
					}
		}
				
		$param['form_error']=$form_errors;
		$param[$param['array_name']]=$param_form_data;
		if (count($form_errors)>0){
			return false;
		}else {
			return true;
		}
	}

	/**
	 * ForUnitTestReset
	 *
	 * @assert ($param) == $this->object->getDataForTest('setResetForTest',$param=array('test'=>'test'))
	 */
	function setResetForTest() 
	{	 
	}

}
?>
