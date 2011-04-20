<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
//		'method' => 'default',
		//'form_data'=>$_REQUEST,
		'ajax' => 'ajax',
	)
);
print_r2($output);
?>
