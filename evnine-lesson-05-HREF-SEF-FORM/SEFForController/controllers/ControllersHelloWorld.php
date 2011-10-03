<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ControllersHelloWorld
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => 'ajax',
			),
			'inURLSEF'=> true,
			'public_methods' => array(
				'default'=>array(
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
					'ModelsHelloWorld' => 'getHelloWorld',
				),
			)
		);
	}
}
?>