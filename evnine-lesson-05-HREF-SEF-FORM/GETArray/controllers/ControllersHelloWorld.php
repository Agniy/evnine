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
				'REQUEST' => array('test_id' => array('0','3','2')),
			),

			'public_methods' => array(
				'default'=>array(
					'inURLMethod' => array(
						'default',
						'post'
					),
					'validation_add'
						=>array(
							'test_id' => array(
								'to'=>'TestID'
								,'inURL' => true
								,'inURLSave' => true
								,'is_array' => true
								,'type'=>'int'
								,'required'=>false
								,'error'=>'is_empty'
								,'max' => '920'
							),
					),
					'ModelsValidation' => 'isValidModifierParamFormError',
					'isValidModifierParamFormError_true'=>array(
						'ModelsHelloWorld' => 'getHelloWorld',
					)
				),

				'post'=>array(
					'validation_form'=>array(
						'test_id' => array(
							'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
							),
						),
					'ModelsValidation' => 
						'isValidModifierParamFormError'
				)
			)
		);
	}
} 
?>