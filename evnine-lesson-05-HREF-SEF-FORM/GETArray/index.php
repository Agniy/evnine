<?php
error_reporting(0);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'REQUEST' => array('test_id' => array('0','3','2')),
		'ajax' => 'ajax',
	)
);

print_r2($output, "array",false);

echo 'URN: '.$output['inURL']['default']['pre'];
	foreach ($output['REQUEST_OUT']['TestID'] as $output_title =>$output_value){
		echo $output['inURL']['default']['TestID'].$output_value;
	}		
echo $output['inURL']['post']['post'];

?>
