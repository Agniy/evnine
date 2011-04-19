<?php
error_reporting(2046);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'form_data' => $_REQUEST,
		'ajax' => $_REQUEST['ajax'],
	)
);
if ($_REQUEST['ajax']==='ajax'){
	include('index-body.php');
	print_r2($ctrlr, "array",false);
}else {
	include('index-header.php');
	include('index-body.php');
	print_r2($ctrlr, "array",false);
	include('index-footer.php');
}
?>