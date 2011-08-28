<?php
include_once '../evnine.php';
include_once '../debug/evnine.debug.php';
$php_unit_file='../evninePHPUnit.php';

$phpunit_config=array(
'controller_alias'=>array(
		'php_unit_test'=>array(
			'class_name'=>'ControllersPHPUnit',
			'path'=>'test'.DIRECTORY_SEPARATOR,
		)
	),
);

$evnine = new Controller();
$evnine->controller_alias = array_merge($evnine->controller_alias,$phpunit_config['controller_alias']);
$evnine->class_path=array_merge($evnine->class_path,$phpunit_config['class_path']);
$evnine->path_to=$evnine->path_to.'..'.DIRECTORY_SEPARATOR;
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
		'evnine'=>$evnine
	)
);

echo $output['ModelsPHPUnit_getPHPUnitCodeWithBR'];
print_r2($output, "array",false);
if (!file_exists($php_unit_file)){
	file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
	$dir = (defined( '__DIR__' )?__DIR__:getcwd());
	$basename = basename($dir);
	$dir = preg_replace("/[\/\\\]$basename$/i",'',$dir).DIRECTORY_SEPARATOR;
	$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
	exec($exec);
}
?>