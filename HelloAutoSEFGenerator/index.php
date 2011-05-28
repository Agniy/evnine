<?php
error_reporting(0);
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'form_data' => array('path_id' => '777'),
		'ajax' => 'ajax',
	)
);

new dBug($output, "array",false);

echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['PathID'].$output['REQUEST_OUT']['PathID'].$output['inURL']['post'];

?>
