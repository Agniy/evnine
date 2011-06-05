<?php
require_once( 'evnine.php' );
include('debug/evnine.debug.php');
$controller_evnine = new Controller();
$php_unit_param_for_all = $php_unit_param=array();
/**
 * en: For each model
 * ru: Для каждой модели 
 */
foreach ($controller_evnine->controller_alias as $controller_config_alias =>$controller_class_name){
	//echo '$controller_evnine->path_to:'.$controller_evnine->path_to;
	//echo '#$controller_config_alias: <pre>'; if(function_exists(print_r2)) print_r2($controller_config_alias); else print_r($controller_config_alias);echo "</pre><br />\r\n";
	//echo '#$controller_class_name: <pre>'; if(function_exists(print_r2)) print_r2($controller_class_name); else print_r($controller_class_name);echo "</pre><br />\r\n";
	$controller_file = $controller_evnine->path_to.'controllers'.DIRECTORY_SEPARATOR.$controller_class_name.'.php';
	//echo '#$controller_file: <pre>'; if(function_exists(print_r2)) print_r2($controller_file); else print_r($controller_file);echo "</pre><br />\r\n";
	if (file_exists($controller_file)){
		include_once($controller_file);
		//echo '#$controller_config_alias: <pre>'; if(function_exists(print_r2)) print_r2($controller_config_alias); else print_r($controller_config_alias);echo "</pre><br />\r\n";
		$controller_class=new $controller_class_name();
		$php_unit_param_for_all=$controller_class->controller['test_param'];
		echo '#$php_unit_param_for_all: <pre>'; if(function_exists(print_r2)) print_r2($php_unit_param_for_all); else print_r($php_unit_param_for_all);echo "</pre><br />\r\n";
		$public_methods= $controller_class->controller['public_methods'];
		foreach ($public_methods as $public_methods_title =>$public_methods_value){
			echo '#$public_methods_title: <pre>'; if(function_exists(print_r2)) print_r2($public_methods_title); else print_r($public_methods_title);echo "</pre><br />\r\n";
			//echo '#$public_methods_value: <pre>'; if(function_exists(print_r2)) print_r2($public_methods_value); else print_r($public_methods_value);echo "</pre><br />\r\n";
			$php_unit_param=$public_methods_value['test_param'];
			echo '#$php_unit_param: <pre>'; if(function_exists(print_r2)) print_r2($php_unit_param); else print_r($php_unit_param);echo "</pre><br />\r\n";
			$full_unit_param=array_merge($php_unit_param_for_all,$php_unit_param);
			echo '#$full_unit_param: <pre>'; if(function_exists(print_r2)) print_r2($full_unit_param); else print_r($full_unit_param);echo "</pre><br />\r\n";
		}		
	}
}
?>
