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
		* @updated 2011-08-28
		*/
//$_SERVER["DOCUMENT_ROOT"]=''
include_once('evnine.php');
class evninePHPUnit extends Controller {
/**
			* @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test1'=>'1', 'test3'=>'23'))))
			* @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test2'=>'1'))))
			*/
function getControllerForParam_helloworld_default_Test($method,$array,$param) {
		$this->getControllerForParamTest($method,$array,$param);
		$this->result;
}
			* @assert ('getControllerForParam_helloworld_default_no_inURLUnitTest_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default_no_inURLUnitTest', 'for_all'=>'all')))
			* @access public
			* @param param
			* @return array
			*/
function getControllerForParam_helloworld_default_no_inURLUnitTest_Test($method,$array,$param) {
			return  $this->getControllerForParamTest($method,$array,$param);
}
}
?>