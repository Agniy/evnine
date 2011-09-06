<?php
 /**
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersPHPUnit
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'public_methods' => array(
					'default'=>array(
						'ModelsPHPUnit' => array(
							'getParamTest',
							'getParamCaseByParamTest',
							'getCountParamByParamTest',
							'getPHPUnitCode',
							'getPHPUnitCodeWithBR',
						)
					),
			)
		);
	}
} 
?>