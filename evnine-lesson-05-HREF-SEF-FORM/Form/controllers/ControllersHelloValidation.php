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
				'ajax' => 'ajax',
			),

			'public_methods' => array(
				
				'default'=>array(
					'inURLMethod' => array(
						'default'
					),
					'validation_form'=>array(
							'test_id' => array(
								'to'=>'TestID'
								,'inURL' => true
								,'inURLSave' => true
								,'is_array' => false
								,'type'=>'str'
								,'required'=>false
								,'error'=>'is_empty'
								,'max' => '920'
							),
						),
						'ModelsValidation' => 
							'isValidModifierParamFormError', 
							'isValidModifierParamFormError_true'=>array(
								'ModelsHelloWorld' => 'getContentFromFormData',
							)
					),
			)
		);
	}
} 

?>
