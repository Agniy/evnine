<?php
error_reporting(0);
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'hello'=>'hello',
		'ajax' => 'ajax',
	)
);
new dBug($output, "array",false);
echo '<br/>';
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'hello'=>'false',
		'ajax' => 'ajax',
	)
);
new dBug($output, "array",false);

?>