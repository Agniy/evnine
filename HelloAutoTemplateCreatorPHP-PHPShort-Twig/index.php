<?php
error_reporting(0);
include_once('evnine.php');
include_once 'debug/evnine.debug.php';
include_once 'evnine.views.generator.template.php';
$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'form_data' => '',
		'ajax' => 'ajax',
	)
);

new dBug($output, "array",false);

$twig_generator= 
	getTemplateFromArray(
		$output,
		$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'twig')
	);
echo $twig_generator;

$twig_generator= 
	getTemplateFromArray(
		$output,
		$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'php')
	);
echo $twig_generator;

$twig_generator= 
	getTemplateFromArray(
		$output,
		$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'PHPSHORT')
	);
echo $twig_generator;



?>
