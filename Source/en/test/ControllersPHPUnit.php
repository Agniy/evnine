<?php
/**
 * ControllersPHPUnit
 * @package Controller
 * @author ev9eniy
 * @version 2
 * @updated 2011.09.11
 */
class ControllersPHPUnit
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'public_methods' => array(
					'default'=>array(
					/**
						* Model for testing models and controllers.
						*/
						'ModelsPHPUnit' => array(
							/**
							 * Get a test from the controller.
							 */
							'getParamTest',
							/**
							 * Determine the number of tests for the controllers.
							 */
							'getParamCaseByParamTest',
							/**
							 * Count the number of tests.
							 */
							'getCountParamByParamTest',
							/**
							 * Get the source for the generation of PHP Unit Test.
							 */
							'getPHPUnitCode',
							/**
							 * Make a change of line breaks to the br.
						   */
							'getPHPUnitCodeWithBR',
						)
					),
			)
		);
	}
} 
?>
