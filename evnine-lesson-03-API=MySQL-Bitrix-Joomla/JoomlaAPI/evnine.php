<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
global $mainframe;
/*
 * en: Init evnine controller
 * ru: ������砥� ������ ����஫���
 */
require_once( JPATH_COMPONENT.DS.'evnine.controller.php' );
$evnine = new Controller();
$array_this = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'method' => 'default',
		'sef' => $_REQUEST['sef'],
		'REQUEST'=>$_REQUEST,
		'cookie' => $_COOKIE,                       
		'ajax' => $_REQUEST['ajax'],
	)
);
require_once(JPATH_COMPONENT.DS.'debug'.DS.'evnine.debug.php');
print_r2($array_this);
$mainframe->close();
?>