<?php
error_reporting(0);
include_once('evnine.php');
include_once 'debug/evnine.debug.php';
require_once('Twig/Autoloader.php');

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'form_data' => '',
		'ajax' => 'ajax',
	)
);

new dBug($output, "array",false);

Twig_Autoloader::register();
	
$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader, array(
			'cache' => 'views/cache',
		//'cache'=>false,
			//'auto_reload' => false,
			'auto_reload' => true,
			//'charset' => 'UTF-8',
			'debug' => true,
			'trim_blocks'=>false,
		)
);

echo $twig->loadTemplate('form.tpl')->render($output);
?>