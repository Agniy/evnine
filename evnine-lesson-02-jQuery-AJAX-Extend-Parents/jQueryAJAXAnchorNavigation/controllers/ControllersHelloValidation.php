<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersHelloValidation
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => array('false','ajax'),
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
						'validation_multi_form'
						=>array(
							'data' => array(
								'to'=>'DATA'
								,'inURL' => true
								,'inURLSave' => true
								,'is_array' => true
								,'type'=>'int'
								,'required'=>false
								,'error'=>'is_empty'
								,'max' => '920'
							)
						),
						'ModelsValidation' => 'isValidModifierParamFormError',
						'isValidModifierParamFormError_true'=>array(
							'ModelsHelloWorld' => 'getContentFromFormData'
						),
						'isValidModifierParamFormError_false'=>array(
							'ModelsHelloWorld' => 'getContentFromFormData'
						),
					),
					'submit_1' => array(
						'this' => 'default',
						'validation_multi_form' => array(
							'path_id' => array('to'=>'PathID')
						),
					),
					'submit_2' => array(
						'this' => 'default',
						'validation_multi_form' => array(
							'path_id' => array('to'=>'PathID')
						),
					),
					'submit_3' => array(
						'this' => 'default',
						'validation_multi_form' => array(
							'path_id' => array('to'=>'PathID')
						),
					),
					'submit_4' => array(
						'this' => 'default',
						'validation_multi_form' => array(
							'path_id' => array('to'=>'PathID')
						),
					),
			)
		);
	}
} 
?>
