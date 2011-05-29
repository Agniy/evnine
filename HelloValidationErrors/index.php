<?php
error_reporting(0);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';
include_once 'evnine.views.generator.template.php';

$evnine = new Controller();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'form_data' => array('path_id' => ''),
		'ajax' => 'ajax',
	)
);

new dBug($ctrlr, "array",false);

echo $ctrlr['inURL']['default']['pre'].$ctrlr['inURL']['default']['PathID'].$ctrlr['REQUEST_OUT']['PathID'].$ctrlr['inURL']['post']


?>
