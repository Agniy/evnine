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
						* Модель для тестирования моделей и контроллеров. 
						*/
						'ModelsPHPUnit' => array(
							/**
							 * Получить тесты из контроллера.
							 */
							'getParamTest',
							/**
							 * Определить количество тестов для контроллеров.
							 */
							'getParamCaseByParamTest',
							/**
							 * Посчитать количество тестов.
							 */
							'getCountParamByParamTest',
							/**
							 * Получить исходник для генерации PHP Unit Test.
							 */
							'getPHPUnitCode',
							/**
							 * Сделать замену переноса строк на br.
						   */
							'getPHPUnitCodeWithBR',
						)
					),
			)
		);
	}
} 
?>
