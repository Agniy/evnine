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
				'for_all' => 'all',
			),
			
			'public_methods' => array(
				
				'default'=>array(
					'ModelsHelloWorld' => 'getHelloWorld', 
					'inURLUnitTest' => array(
						'REQUEST'=>array(
							array('test1' => '1','test3'=>'23'),
							array('test2' => '1',)
						),
					),
				),
				
				'default_no_inURLUnitTest'=>array(
					'ModelsHelloWorld' => 'getHelloWorld',
				),
			)
		);
	}
} 
?>