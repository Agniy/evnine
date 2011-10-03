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
					'inURLMethod'=>array('default2'),
					'inURLTemplate' => array('info' => 'default2'),
					'validation'=>array(
						'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
					)
				),

				'default2'=>array(
					'inURLMethod'=>array('default'),
					'inURLTemplate' => array('info' => 'default'),
					'validation'=>array(
						'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
					)
				)
			)
		);
	}
} 
?>
