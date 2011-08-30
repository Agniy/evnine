<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';
class EvnineConfig{
	function __construct(){
		$this->controller_alias=array(
			'helloworld'=>'ControllersHelloWorld'
		);
	}
}
$evnine = new EvnineController();

$output = $evnine->getControllerForParam(
	$param = array(
		'controller' => 'helloworld',
	)
);
print_r2($output, "array",false);
?>
