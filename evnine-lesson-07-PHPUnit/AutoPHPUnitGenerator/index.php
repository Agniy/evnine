<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'param_gen',
		'ajax' => 'ajax',
	)
);
echo $output['ModelsPHPUnit_getPHPUnitCode'];
print_r2($output, "array",false);
?>
