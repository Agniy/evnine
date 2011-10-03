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
				'REQUEST' => array('test_id' => '777'),
			),
			'inURLSEF'=> false,
			'public_methods' => array(
				'default'=>array(
					'inURLSEF' => array(
						'hello',
						'world',
						'test_id' => '',
						'short name not used / любой текст описания' => '777',
						'.' => '.html',
					),
					'inURLMethod' => array(
						'default'
					),					
					'validation_add'=>array(
						'test_id' => array(
							'to'=>'TestID'
							,'inURL' => true
							,'inURLSave' => true
							,'is_array' => false
							,'type'=>'int'
							,'required'=>false
							,'error'=>'is_empty'
							,'max' => '920'
						),
					),
					'ModelsValidation' => 'isValidModifierParamFormError'
				),
			),
				
			'private_methods' => array(
				'isValidModifierParamFormError_true'=>array(
					'ModelsHelloWorld' => 'getHelloWorld'
				)
			)
		);
	}
} 
?>
