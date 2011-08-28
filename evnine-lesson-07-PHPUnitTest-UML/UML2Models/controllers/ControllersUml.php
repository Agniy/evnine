<?php


/**
 * @author 1
 * @version 1.0
 * @created 28-рту-2011 12:06:29
 */
class ControllersUml
{

	public $controller;

	/**
	 * 
	 * @param access_level
	 */
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