<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'REQUEST' => array('test_id' => '777'),
		'ajax' => 'ajax',
	)
);

print_r2($output, "array",false);
echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].$output['REQUEST_OUT']['TestID'].$output['inURL']['default']['post'];
?>
