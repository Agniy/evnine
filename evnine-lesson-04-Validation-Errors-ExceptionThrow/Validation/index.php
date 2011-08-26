<?php
error_reporting(0);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';
include_once 'evnine.views.generator.template.php';

$evnine = new Controller();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'REQUEST' => array('path_id' => '777'),
		'ajax' => 'ajax',
	)
);

print_r2($ctrlr, "array",false);

echo $ctrlr['inURL']['default']['pre'].$ctrlr['inURL']['default']['PathID'].$ctrlr['REQUEST_OUT']['PathID'].$ctrlr['inURL']['post']
?>
