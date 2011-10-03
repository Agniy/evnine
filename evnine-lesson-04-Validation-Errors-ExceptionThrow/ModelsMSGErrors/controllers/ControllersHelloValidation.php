<?php
/**
 * ControllersHelloValidation
 * @package Controller
 */
class ControllersHelloValidation
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => 'ajax'
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
								,'required'=>true
								,'error'=>'is_empty'
								,'max' => '920'
							),
						),
						'ModelsValidation' => 'isValidModifierParamFormError'
					),
			),
			'private_methods' => array(
				'isValidModifierParamFormError_false'=>array(
					'ModelsErrors'=>array(
						'getError',
						'getError->alternative_way_of_setting_errors',
					)
				),
			)
		);
	}
} 
?>