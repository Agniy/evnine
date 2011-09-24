<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'method'=>'default',
	)
);
print_r2($output, "array",false);
?>