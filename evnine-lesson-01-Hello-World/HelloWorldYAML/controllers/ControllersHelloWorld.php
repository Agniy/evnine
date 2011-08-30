<?php
/**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
if (!class_exists('sfYamlParser')){
/**
	* en: Is the class was defined?
  * ru: Проверяем был ли инициализирован классс. 
  */
 require_once(dirname(__FILE__).'/../libs/yaml/sfYamlParser.php');
}
class ControllersHelloWorld extends sfYamlParser 
{
	var $controller;
	function __construct(){
		$this->controller=$this->parse(
<<<YAML
public_methods:
 default: 
  ModelsHelloWorld: getHelloWorld
YAML
	);
} 
}

?>

