<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';
$evnine = new EvnineController();
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
	print_r2($output);
	echo '</body></html>';
}else {
	include('index-header.php');
	include('index-body.php');
	print_r2($output);
	include('index-footer.php');
}
?>
