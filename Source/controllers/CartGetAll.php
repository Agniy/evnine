<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class CartGetAll
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'public_methods' => array(
				'get_data'=>array(
					'ModelsCart' => array('getCart','get_data')
				),
			)
		);
	}        
        	
} 
?>
