<?php
/**
 * ControllersHelloWorld
 * @package Controller
 */
class ControllersHelloWorld
{
	var $controller_menu_view;
	function __construct($access_level){
		$this->controller_menu_view = array(
			'public_methods' => array(
				'default'=>array(
					'ModelsHelloWorld' => 'getQuery', 
					'ModelsDatabaseInstallTables'=>'setCreateHello'
				),
			)
		);
	}
} 

?>
