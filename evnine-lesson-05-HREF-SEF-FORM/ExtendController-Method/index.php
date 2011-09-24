<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
	)
);

print_r2($output, "array",false);

echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].$output['inURL']['default']['post'];

?>
