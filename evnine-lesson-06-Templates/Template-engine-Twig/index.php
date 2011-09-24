<?php
error_reporting(0);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';


$evnine = new EvnineController();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'ajax' => 'ajax',
	)
);

print_r2($output, "array",false);

require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();
	
$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader, array(
			'cache' => 'views/cache',
			'auto_reload' => true,
			'charset' => 'UTF-8',
			'debug' => true,
			'trim_blocks'=>false,
		)
);

echo $twig->loadTemplate('form.tpl')->render($output);
?>