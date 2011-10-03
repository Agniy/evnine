<?php
/**
 * ControllersHelloWorldExtend
 * @package Controller
 */
class ControllersHelloWorldExtend
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'private_methods' => array(
				'isGetNotHelloWorld_true'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
				'isGetNotHelloWorld_false'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				)
			),
				
			'public_methods' => array(
				'default'=>array(
					'ModelsHelloWorld' => 'getHelloWorldExt'
				),
			)
		);
	}
} 
?>
