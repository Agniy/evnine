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
				'ajax' => 'ajax',
				'REQUEST' => array(
					array('var'=>
						array(
							array('1','2'),
							array('3','4'),
							array('5','6'),
							array('7'),
						),
						'3','4',
					),
					array('3','4'),
					array('5','6'),
					array('7'),
				),
			),

			'public_methods' => array(
				
				'default'=>array(
					'inURLMethod' => array(
						'default'
					),
					'validation_add'=>array(
						'path_id' => array(
							'to'=>'PathID'
							,'inURL' => true
							,'inURLSave' => true
							,'is_array' => false
							,'type'=>'int'
							,'required'=>false
							,'error'=>'is_empty'
							,'max' => '920'
						)
					)
				)
			)
		);
	}
} 

?>
