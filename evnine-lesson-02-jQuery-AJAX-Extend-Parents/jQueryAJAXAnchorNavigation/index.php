<?php
error_reporting(2046);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';
$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'REQUEST' => $_REQUEST,
		'ajax' => $_REQUEST['ajax'],
	)
);
if ($_REQUEST['ajax']==='ajax'){
	echo '<html><head></head><body>';
	include('index-body.php');
	print_r2($output, "array",false);
	echo '</body></html>';
}else {
	include('index-header.php');
	include('index-body.php');
	print_r2($output, "array",false);
	include('index-footer.php');
}
?>
