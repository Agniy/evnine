<?php
/**
 * Вызов контроллера.
 * 
 * @uses EvnineConfig
 * @uses EvnineController
 * @version 0.3
 * @copyright 2009-2012
 * @author ev9eniy.info
 * @updated 2011-09-02 17:53:02
 */
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

/**
 * Объявление контроллера.
 * @var object
 */
$evnine = new EvnineController();

/**
 * Получение данных.
 * @var array
 */
$output = $evnine->getControllerForParam(
	array(
 			 'controller' => 'controller'
 			,'method' => 'method'
 			,'REQUEST' => $_REQUEST
 			,'ajax' => $_REQUEST['ajax']
 			,'sef' => $_REQUEST['sef']
	)
);

/**
 * Отладка полученных данных.
 */
print_r2($output);
?>
