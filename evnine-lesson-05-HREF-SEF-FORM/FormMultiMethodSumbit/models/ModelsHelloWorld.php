<?php
/**
 * ModelsHelloWorld
 * @package Models
 * @author ev9eniy
 * @version 1.0
 * @created 04-apr-2010 11:03:41
 */
class ModelsHelloWorld
{	

/**
 * MySQL link to class 
 * 
 * @var object
 * @access public
 */
var $api;

/**
 * Constructor save api (MySQL link to class)
 * 
 *	Сохраним доступ апи, в данном случае для работ с MySQL  
 *	сервером
 */
function __construct($api){
	$this->api=$api;//
}


/**
 * setUpdateTableEvnine
 * ModifierParam
 * SetResetFalse 
 * 
 * @param array &$param 
 * @access public
 * @return void
 */
function getContentFromFormData(&$param) {
	return $param['REQUEST'];
}

}
?>
