<?php
/**
 * ControllersUml
 * @package controllers
 */
class ControllersUml
{
	public $controller;
	public function __construct($access_level)
	{
		$this->controller = array(
		'public_methods' => array(
			'default'=>array(
				'ModelsUmlTest' => 'getTest'
				),
			)
		);
	}
}
?>