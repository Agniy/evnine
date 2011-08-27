<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'REQUEST' => '',
		'ajax' => 'ajax',
	)
);

print_r2($output, "array",false);

require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->debugging = true;
$smarty->caching = true;
$smarty->cache_lifetime = 120;
$smarty->assign("output",$output);
$smarty->display('form.tpl');
?>