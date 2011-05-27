<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'method' => 'default2',
		//'form_data'=>$_REQUEST,
		'ajax' => 'false',
	)
);
new dBug($output, "array",false);
?>