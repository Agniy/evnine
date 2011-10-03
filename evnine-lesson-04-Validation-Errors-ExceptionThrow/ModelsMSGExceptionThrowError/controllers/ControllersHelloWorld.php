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
							'setThrowNewException'
						),
						'ModelsErrors'=>array(
							'getError',
							'getError->alternative_way_of_setting_errors',
						)
					),
			)
		);
	}
} 
?>
