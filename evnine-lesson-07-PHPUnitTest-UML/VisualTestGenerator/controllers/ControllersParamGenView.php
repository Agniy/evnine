<?php
/**
 * ControllersHelloWorld
 * @package Controller
 * @author ev9eniy
 */
class ControllersParamGenView
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'debug'=>false,
			),
			'public_methods' => array(
					'default'=>array(
						'ViewsUnitPHP'=>array(
							'getHTMLButton',
							'getHTMLCaseHeader',
							'getHTMLMSGHeader',
							'getHTMLData'
						),
					),
			)
		);
	}
} 
?>