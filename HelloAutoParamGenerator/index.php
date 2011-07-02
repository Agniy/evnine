<?php
include_once('evnine.php');
include_once 'debug/evnine.debug.php';
include_once 'evnine.views.generator.template.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'param_gen',
//		'method' => 'default',
		//'form_data'=>$_REQUEST,
		'ajax' => 'ajax',
	)
);
 //print_r2($output, "array",false);
//echo $output['ModelsPHPUnit_getMSGHeader'];
//foreach ($output["ModelsPHPUnit_getDataFromControllerByParam"] as $param_id =>$param_array){
	//echo '<br />'.$output['ModelsPHPUnit_getParamTextName'][$param_id].'<br />';
	//print_r2($param_array);
//}
//;
?>
