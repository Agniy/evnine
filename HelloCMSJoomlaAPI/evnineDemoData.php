<?php
//тест почему не пашет 
defined('_JEXEC') or die( 'Restricted access' );
error_reporting(0);

$com_sa='com_evnine';
if (defined( 'JPATH_ROOT' )){
	$path_to_controller = JPATH_ROOT .DS.'components'.DS.$com_sa.DS;
}else {
	$path_to_controller=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$com_sa.DIRECTORY_SEPARATOR;
}
require_once( $path_to_controller.'evnine_controller.php' );
$SaController = new Controller();
foreach ($SaController->class_path as $SaController_title =>$SaController_value)if ($SaController_title!='ModelsDatabase'){
	$model_path_file = $path_to_controller.$SaController_value['path'].DIRECTORY_SEPARATOR.$SaController_title.'.php';
	if (file_exists($model_path_file)){
		include_once($model_path_file);
		$reset_db_class=new $SaController_title($SaController->database);
		//print_r2($reset_db_class);
		if (method_exists($reset_db_class,'setCreateTableClass')){
			echo '<br />'.$SaController_title;
			echo ': Insert Demo Data - OK';
			$reset_db_class->setResetForTest();
		}
	}
}		
?>
