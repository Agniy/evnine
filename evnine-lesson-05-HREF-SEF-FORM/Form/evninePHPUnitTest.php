<?php

require_once 'evninePHPUnit.php';

/**
 * Test class for evninePHPUnit.
 * Generated by PHPUnit on 2011-09-23 at 22:40:46.
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
     * Generated from @assert ('getControllerForParam_validation_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'validation', 'method'=>'default', 'ajax'=>'true'))).
     */
    public function testGetControllerForParam_validation_default_Test()
    {
        $this->assertEquals(
          $array=($this->object->getControllerForParam($param=array( 'controller'=>'validation', 'method'=>'default', 'ajax'=>'true'))),
          $this->object->getControllerForParam_validation_default_Test('getControllerForParam_validation_default_Test',$array,$param)
        );
    }
}
?>
