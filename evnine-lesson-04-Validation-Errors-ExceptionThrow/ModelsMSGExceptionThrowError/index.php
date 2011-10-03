<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'hello',
		'ajax' => 'ajax',
	)
);

print_r2($ctrlr);
?>
