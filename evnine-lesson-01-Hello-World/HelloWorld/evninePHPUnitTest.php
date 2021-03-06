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
class EvnineConfig{
	function __construct(){
		$this->controller_alias=array(
			'helloworld'=>'ControllersHelloWorld'
		);
	}
}

include_once('evnine.php');
class evninePHPUnit extends EvnineController {
/**
			* @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default')))
			* @access public
			* @param param
			* @return array
			*/
function getControllerForParam_helloworld_default_Test($method,$array,$param) {
			return  $this->getControllerForParamTest($method,$array,$param);
}
}

/**
 * Test class for evninePHPUnit.
 * Generated by PHPUnit on 2011-08-28 at 21:55:26.
 */
class evninePHPUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var evninePHPUnit
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new evninePHPUnit;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * Generated from @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default'))).
     */
    public function testGetControllerForParam_helloworld_default_Test()
    {
        $this->assertEquals(
          $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default'))),
          $this->object->getControllerForParam_helloworld_default_Test('getControllerForParam_helloworld_default_Test',$array,$param)
        );
    }
}
?>
