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
			'page_level'=>'2',
			'parent'=> 'helloworldparent',
			'this'=> 'helloworld',
			'inURLUnitTest' => array(
				'ajax' => array('false','ajax'),
			),
			'public_methods' => array(
					'default'=>array(
						'ModelsHelloWorld' => 'getHelloWorld'
					),
			)
		);
	}
} 
?>
