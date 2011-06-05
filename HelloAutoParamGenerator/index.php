<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'param_gen',
//		'method' => 'default',
		//'form_data'=>$_REQUEST,
		'ajax' => 'ajax',
	)
);
new dBug($output, "array",false);
?>