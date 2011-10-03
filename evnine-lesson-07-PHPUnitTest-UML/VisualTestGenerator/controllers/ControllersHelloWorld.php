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
				'debug'=>'false',
				'all' => 'all',
				'REQUEST'=>array(
					array('test_id'=>'1'),
				),
			),

			'public_methods' => array(
				
				'default'=>array(
					'ModelsHelloWorld' => 'getHelloWorld',
					'inURLUnitTest' => array(
						'REQUEST'=>array(
							array('test1' => '1',test=>'23'),
							array('test2' => '1',)
						),
						'PHPFlag' => '',
					),
				),
				
				'default2'=>array(
					'ModelsHelloWorld' => 'getHelloWorld'
				),
				
				'default_no_inURLUnitTest'=>array(
					'ModelsHelloWorld' => 'getHelloWorld'
				),
			)
		);
	}
} 
?>
