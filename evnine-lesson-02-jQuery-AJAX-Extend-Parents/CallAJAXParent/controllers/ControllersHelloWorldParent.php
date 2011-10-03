<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersHelloWorldParent
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'page_level'=>'1',
			'parent'=> 'helloworldparentparent',
			'this'=> 'helloworld',
			'inURLUnitTest' => array(
				'ajax' => array('false','ajax'),
			),
			'public_methods' => array(
					'default'=>array(
						'ModelsHelloWorld' => 'getHelloWorldParent',
					),
			)
		);
	}
} 
?>
