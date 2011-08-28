<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'hello'=>'hello',
	)
);

print_r2($output, "array",false);

echo '<br/>';

$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'hello'=>'false',
	)
);

print_r2($output, "array",false);
?>
