<?php
/**
 * Auto generator skeleton PHP Unit tests for the controller.
 * cmd/sh: phpunit --skeleton-test "evninePHPUnit"
 * 
 * PHP Unit install:
 * #http://www.phpunit.de/manual/3.0/en/installation.html
 * #http://pear.php.net/manual/en/installation.getting.php
 * 
 * wget http://pear.php.net/go-pear.phar
 * sudo php go-pear.phar
 * pear channel-discover pear.phpunit.de
 * pear install phpunit/PHPUnit
 * 
 * @filename evninePHPUnit.php
 * @package PHPUnitTest
 * @author evnine
 * @updated 2011-09-24
 */
//$_SERVER["DOCUMENT_ROOT"]=''
include_once('evnine.php');
class evninePHPUnit extends EvnineController {
/**
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default')))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_helloworld_default_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}
/**
 * @assert ('getControllerForParam_helloworld_default2_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default2')))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_helloworld_default2_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}
}
?>