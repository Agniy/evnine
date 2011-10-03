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

			'private_methods'=>array(
				'isValidModifierParamFormError_true'=>array(
					'ModelsHelloWorld' => 'getContentFromFormData',
				)
			),
			
			'public_methods' => array(
				
				'default'=>array(
					'inURLController'=>'validation',
					'inURLMethod' => array(
						'submit_1',
						'submit_2',
						'submit_3',
						'submit_4',
						'default'
					),
					'validation_multi_form'=>array(
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
					'ModelsValidation' => 'isValidModifierParamFormError', 
					'ModelsHelloWorld' => 'getContentFromFormData',
				),
	
				'submit_1' => array(
					'this' => 'default',
					'validation_multi_form' => array(
						'test_id' => array('to'=>'TestID')
					),
				),
	
				'submit_2' => array(
					'this' => 'default',
					'validation_multi_form' => array(
						'test_id' => array('to'=>'TestID')
					),
				),
	
				'submit_3' => array(
					'this' => 'default',
					'validation_multi_form' => array(
						'test_id' => array('to'=>'TestID')
					),
				),
	
				'submit_4' => array(
					'this' => 'default',
					'validation_multi_form' => array(
						'test_id' => array('to'=>'TestID')
					),
				),
			)
		);
	}
} 
?>
