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
			),
			'public_methods' => array(
					'default'=>array(
						'ModelsHelloWorld' => array(
							'setInfoToParamform_info',
						),
						'ModelsInfo'=>array(
							'getInfo',
							'getInfo->alternative_way_of_setting_info',
						)
					),
			),
		);
	}
} 
?>
