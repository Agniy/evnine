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
			'public_methods' => array(
					'default'=>array(
						'ModelsHelloWorld' => 
							'isParamHello',
							'isParamHello_true' =>array(
									'ModelsHelloWorld' =>'getHelloWorld'),
							'isParamHello_false' =>array(
									'ModelsHelloWorld' =>'getByeBye'),
					),
			)
		);
	}
} 

?>
