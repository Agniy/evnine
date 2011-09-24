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
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'ajax'=>'ajax', 'REQUEST'=>array( 'var'=>array( '0'=>array( '0'=>'1', '1'=>'2'), '1'=>array( '0'=>'3', '1'=>'4'), '2'=>array( '0'=>'5', '1'=>'6'), '3'=>array( '0'=>'7')), '0'=>'3', '1'=>'4'))))
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'ajax'=>'ajax', 'REQUEST'=>array( '0'=>'3', '1'=>'4'))))
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'ajax'=>'ajax', 'REQUEST'=>array( '0'=>'5', '1'=>'6'))))
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'ajax'=>'ajax', 'REQUEST'=>array( '0'=>'7'))))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_helloworld_default_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}
}
?>