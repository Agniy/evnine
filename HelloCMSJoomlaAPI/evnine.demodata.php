<?php
defined('_JEXEC') or die( 'Restricted access' );
error_reporting(0);

$component_name='com_evnine';
if (defined( 'JPATH_ROOT' )){
	$path_to_controller = JPATH_ROOT .DS.'components'.DS.$component_name.DS;
}else {
	$path_to_controller=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$component_name.DIRECTORY_SEPARATOR;
}
require_once( $path_to_controller.'evnine.controller.php' );
$controller_evnine = new Controller();
foreach ($controller_evnine->class_path as $controller_evnine_title =>$controller_evnine_value)if ($controller_evnine_title!='ModelsDatabase'){
	$model_path_file = $path_to_controller.$controller_evnine_value['path'].DIRECTORY_SEPARATOR.$controller_evnine_title.'.php';
	if (file_exists($model_path_file)){
		include_once($model_path_file);
		$reset_db_class=new $controller_evnine_title($controller_evnine->loaded_class[$controller_evnine->api]);
		if (method_exists($reset_db_class,'setCreateTableClass')){
			echo '<br />'.$controller_evnine_title;
			echo ': Insert Demo Data - OK';
			$reset_db_class->setResetForTest();
		}
	}
}		
?>
