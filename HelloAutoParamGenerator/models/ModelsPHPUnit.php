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
	function getHelloWorld(&$param){
	$controller_evnine = new Controller();
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
				$public_methods= $controller_class->controller['public_methods'];
				foreach ($public_methods as $method =>$public_methods_value){
					$php_unit_param=$public_methods_value['inURLUnitTest'];
					$full_unit_param=array_merge($php_unit_param_for_all,$php_unit_param);
					$full_unit_param['controller']= array($controller_config_alias);
					$full_unit_param['method']= array($method);
					$param_out[$controller_config_alias][$method]= $full_unit_param;
				}
			}
		}
		$param['TEST']= $param_out;
		//return $param_out;
	}

	function getParamCaseByParamTest($param) {
		//Сбрасываем все случаи
		$save_case=array();
		//Счётчик теста
		$count = 1;

		foreach ($param["TEST"] as $param_const_title =>$param_array){
			echo '<pre>#$param_const_title: '; if(function_exists(print_r2))print_r2($param_const_title);else print_r($param_const_title); echo '</pre>';
			$case_count = count($save_case);
			echo '<pre>#$case_count: '; if(function_exists(print_r2))print_r2($case_count);else print_r($case_count); echo '</pre>';
			$param_count = count($param_array);
			echo '<pre>#$param_count: '; if(function_exists(print_r2))print_r2($param_count);else print_r($param_count); echo '</pre>';
			if ($count==1){//Устанавливаем началные значения
				$i=1;
				foreach ($param_array as $param_array_title =>$param_array_value){
					echo '<pre>#$param_array_title: '; if(function_exists(print_r2))print_r2($param_array_title);else print_r($param_array_title); echo '</pre>';
					echo '<pre>#$param_array_value: '; if(function_exists(print_r2))print_r2($param_array_value);else print_r($param_array_value); echo '</pre>';
					$save_case[$i++]=array(
						$param_const_title=>$param_array_value
					);
				}
				//echo '<pre>#$save_case: '; if(function_exists(print_r2))print_r2($save_case);else print_r($save_case); echo '</pre>';
			}else {//Когда уже есть случаи
			//Делаем копии текущи{ случаев в зависимости от кол-во новых параметоров
				if ($count!=1){
					//echo '#$case_count: '.$case_count."<br />\r\n";
					$j_count = 1;
					for ( $i = 1; $i <= $param_count; $i++ ) {
						//echo '#$i: '.$i."<br />\r\n";
						for ( $j = 1; $j <= $case_count; $j++ ) {
							//echo '&nbsp;&nbsp;#$j: '.$j.' * '.($i*$j)."<br />\r\n";
							//echo '#$i*$j: '.."<br />\r\n";
							//echo '<pre>#$save_case[$j]: '; print_r($save_case[$j]); echo '</pre>';
							//echo '#$j_count: '.$j_count."<br />\r\n";
							$save_case[$j_count]=$save_case[$j];
							$j_count++;
						}
					}
					if ($param_count==1) 
						$param_count=1;
					//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
					//echo '#$case_count: '.$case_count."<br />\r\n";
					//echo '<hr>';
					//die();
					//$case_count--;
					//$param_count--;
					//Заполняем параметры страми случаями
					$save_i=1;
					foreach ($param_array as $param_array_title =>$param_array_value){
						$param_array_title++;
						//echo '#$param_array_title: '.$param_array_title."<br />\r\n";
						//echo '<pre>#$param_array_value: '; print_r($param_array_value); echo '</pre>';
						//echo '#$case_count*$param_array_title: '.($case_count*$param_array_title)."<br />\r\n";
						//echo '#$save_i: '.$save_i."<br />\r\n";
						for ( $i = $save_i; $i <= $case_count*$param_array_title; $i++ ) 
							//echo '&nbsp;<hr>';
							//if (isset($save_case[$i]))
							{
								//echo '&nbsp;&nbsp;#$i: '.$i."<br />\r\n";
								$save_case[$i][$param_const_title]=$param_array_value;
							}
						$save_i= $i;
					}
					//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
					//die();
				}
			//}
			$count++;
		}
		}

		echo '<pre>#$save_case: '; if(function_exists(print_r2))print_r2($save_case);else print_r($save_case); echo '</pre>';
	}
}
?>
