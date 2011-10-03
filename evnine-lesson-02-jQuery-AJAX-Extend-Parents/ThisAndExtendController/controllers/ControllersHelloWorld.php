<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersHelloWorld
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => 'ajax'
			),
			
			'public_methods' => array(
				
				'default'=>array(
					'ModelsHelloWorld' => array(
						'isGetNotHelloWorld',
					),
					'this'=>'this_public_method_call',
					'helloworldext'=>'default'
				),
					
				'this_public_method_call'=>array(
					'ModelsHelloWorld' => array(
						'isGetHelloWorld'
					)
				)
			),

			'private_methods' => array(
				'isGetNotHelloWorld_true'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
				'isGetNotHelloWorld_false'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
				'isGetHelloWorld_true'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
				'isGetHelloWorld_false'=>array(
					'ModelsHelloWorld' => 'getNotHelloWorld',
				),
			),
		);
	}
} 
?>
