<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';


$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
	)
);
echo $output['ModelsPHPUnit_getPHPUnitCodeWithBR'];
print_r2($output);

$php_unit_file='evninePHPUnit.php';
if (!file_exists($php_unit_file)){
	file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
	$dir = (defined( '__DIR__' )?__DIR__:getcwd());
	$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
	exec($exec);
}
?>