<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'ajax' => 'ajax',
	)
);

print_r2($output, "array",false);
echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].'insert_by_template'.$output['inURL']['default']['post'];
?>
