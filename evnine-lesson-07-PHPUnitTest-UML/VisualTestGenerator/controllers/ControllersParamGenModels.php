<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersParamGenModels
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'debug'=>false
			),
			'public_methods' => array(
					'default'=>array(
					 'inURLMethod'=>array('reset_phpunit','default'),
						'ModelsPHPUnit' => array(
							'getParamTest', 
							'getModelsAndControllerModifierTimeFromCache',
							'getParamCaseByParamTest',
							'getCountParamByParamTest',
							'getParamTextName',
							'getDataFromControllerByParam',
							'getPHPUnitCode',
							'getComparePHPUnitForControllers',
						),
					),
					'reset_phpunit' => array(
						'validation_multi_form'=>array('reset_type' => array('to'=>'ResetType','inURL' => true,'type'=>'string','max' => '100')),
						'ModelsPHPUnit' => array(
							'getResetPHPUnit',
						)
						,'this'=>'default'
					),
			)
		);
	}
} 
?>