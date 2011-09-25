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
						* en: Model for testing models and controllers.
						* ru: Модель для тестирования моделей и контроллеров. 
						*/
						'ModelsPHPUnit' => array(
							/**
							 * en: Get a test from the controller.
							 * ru: Получить тесты из контроллера.
							 */
							'getParamTest',
							/**
							 * en: Determine the number of tests for the controllers.
							 * ru: Определить количество тестов для контроллеров.
							 */
							'getParamCaseByParamTest',
							/**
							 * en: Count the number of tests.
							 * ru: Посчитать количество тестов.
							 */
							'getCountParamByParamTest',
							/**
							 * en: Get the source for the generation of PHP Unit Test.
							 * ru: Получить исходник для генерации PHP Unit Test.
							 */
							'getPHPUnitCode',
							/**
							 * en: Make a change of line breaks to the br.
							 * ru: Сделать замену переноса строк на br.
						   */
							'getPHPUnitCodeWithBR',
						)
					),
			)
		);
	}
} 
?>