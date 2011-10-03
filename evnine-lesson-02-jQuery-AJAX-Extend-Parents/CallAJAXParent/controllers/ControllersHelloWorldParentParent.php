<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersHelloWorldParentParent
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'page_level'=>'0',
			'parent'=> 'helloworldparent',
			'this'=> 'helloworld',
			'inURLUnitTest' => array(
				'ajax' => array('false','ajax'),
			),
			'public_methods' => array(
					'default'=>array(
						'ModelsHelloWorld' => 'getHelloWorldParentParent'
					),
			)
		);
	}
} 
?>