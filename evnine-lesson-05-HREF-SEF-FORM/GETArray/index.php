<?php
error_reporting(0);
include_once('evnine.php');
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'form_data' => array('path_id' => array('0','3','2')),
		'ajax' => 'ajax',
	)
);

new dBug($output, "array",false);

echo 'URN: '.$output['inURL']['default']['pre'];
	foreach ($output['REQUEST_OUT']['PathID'] as $output_title =>$output_value){
		echo $output['inURL']['default']['PathID'].$output_value;
	}		
echo $output['inURL']['post']['post'];

?>
