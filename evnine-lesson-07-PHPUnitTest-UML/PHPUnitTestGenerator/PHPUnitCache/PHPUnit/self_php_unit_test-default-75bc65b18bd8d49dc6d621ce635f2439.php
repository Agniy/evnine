a:8:{s:14:"LoadController";s:18:"self_php_unit_test";s:10:"LoadMethod";s:7:"default";s:4:"ajax";s:5:"False";s:26:"ModelsPHPUnit_getParamTest";a:3:{i:0;a:4:{s:10:"controller";a:1:{i:0;s:10:"helloworld";}s:6:"method";a:1:{i:0;s:7:"default";}s:7:"for_all";a:1:{i:0;s:3:"all";}s:7:"REQUEST";a:2:{i:0;a:2:{s:5:"test1";s:1:"1";s:5:"test3";s:2:"23";}i:1;a:1:{s:5:"test2";s:1:"1";}}}i:1;a:3:{s:10:"controller";a:1:{i:0;s:10:"helloworld";}s:6:"method";a:1:{i:0;s:24:"default_no_inURLUnitTest";}s:7:"for_all";a:1:{i:0;s:3:"all";}}i:2;a:3:{s:10:"controller";a:1:{i:0;s:13:"php_unit_test";}s:6:"method";a:1:{i:0;s:7:"default";}s:4:"ajax";a:1:{i:0;s:4:"ajax";}}}s:37:"ModelsPHPUnit_getParamCaseByParamTest";a:3:{i:1;a:2:{i:1;a:4:{s:10:"controller";s:10:"helloworld";s:6:"method";s:7:"default";s:7:"for_all";s:3:"all";s:7:"REQUEST";a:2:{s:5:"test1";s:1:"1";s:5:"test3";s:2:"23";}}i:2;a:4:{s:10:"controller";s:10:"helloworld";s:6:"method";s:7:"default";s:7:"for_all";s:3:"all";s:7:"REQUEST";a:1:{s:5:"test2";s:1:"1";}}}i:2;a:1:{i:1;a:3:{s:10:"controller";s:10:"helloworld";s:6:"method";s:24:"default_no_inURLUnitTest";s:7:"for_all";s:3:"all";}}i:3;a:1:{i:1;a:3:{s:10:"controller";s:13:"php_unit_test";s:6:"method";s:7:"default";s:4:"ajax";s:4:"ajax";}}}s:38:"ModelsPHPUnit_getCountParamByParamTest";a:4:{i:1;a:4:{s:10:"controller";s:10:"helloworld";s:6:"method";s:7:"default";s:7:"for_all";s:3:"all";s:7:"REQUEST";a:2:{s:5:"test1";s:1:"1";s:5:"test3";s:2:"23";}}i:2;a:4:{s:10:"controller";s:10:"helloworld";s:6:"method";s:7:"default";s:7:"for_all";s:3:"all";s:7:"REQUEST";a:1:{s:5:"test2";s:1:"1";}}i:3;a:3:{s:10:"controller";s:10:"helloworld";s:6:"method";s:24:"default_no_inURLUnitTest";s:7:"for_all";s:3:"all";}i:4;a:3:{s:10:"controller";s:13:"php_unit_test";s:6:"method";s:7:"default";s:4:"ajax";s:4:"ajax";}}s:28:"ModelsPHPUnit_getPHPUnitCode";s:2262:"<?php
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
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test1'=>'1', 'test3'=>'23'))))
 * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test2'=>'1'))))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_helloworld_default_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}

/**
 * @assert ('getControllerForParam_helloworld_default_no_inURLUnitTest_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default_no_inURLUnitTest', 'for_all'=>'all')))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_helloworld_default_no_inURLUnitTest_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}
/**
 * @assert ('getControllerForParam_php_unit_test_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'php_unit_test', 'method'=>'default', 'ajax'=>'ajax')))
 * @access public
 * @param param
 * @return array
 */
function getControllerForParam_php_unit_test_default_Test($method,$array,$param) {
			return $this->getControllerForParamTest($method,$array,$param);
}
}
?>";s:34:"ModelsPHPUnit_getPHPUnitCodeWithBR";s:2418:"<?php<br>/**<br> * Auto generator skeleton PHP Unit tests for the controller.<br> * cmd/sh: phpunit --skeleton-test "evninePHPUnit"<br> * <br> * PHP Unit install:<br> * #http://www.phpunit.de/manual/3.0/en/installation.html<br> * #http://pear.php.net/manual/en/installation.getting.php<br> * <br> * wget http://pear.php.net/go-pear.phar<br> * sudo php go-pear.phar<br> * pear channel-discover pear.phpunit.de<br> * pear install phpunit/PHPUnit<br> * <br> * @filename evninePHPUnit.php<br> * @package PHPUnitTest<br> * @author evnine<br> * @updated 2011-09-24<br> */<br>//$_SERVER["DOCUMENT_ROOT"]=''<br>include_once('evnine.php');<br>class evninePHPUnit extends EvnineController {<br>/**<br> * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test1'=>'1', 'test3'=>'23'))))<br> * @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test2'=>'1'))))<br> * @access public<br> * @param param<br> * @return array<br> */<br>function getControllerForParam_helloworld_default_Test($method,$array,$param) {<br>			return $this->getControllerForParamTest($method,$array,$param);<br>}<br><br>/**<br> * @assert ('getControllerForParam_helloworld_default_no_inURLUnitTest_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default_no_inURLUnitTest', 'for_all'=>'all')))<br> * @access public<br> * @param param<br> * @return array<br> */<br>function getControllerForParam_helloworld_default_no_inURLUnitTest_Test($method,$array,$param) {<br>			return $this->getControllerForParamTest($method,$array,$param);<br>}<br>/**<br> * @assert ('getControllerForParam_php_unit_test_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'php_unit_test', 'method'=>'default', 'ajax'=>'ajax')))<br> * @access public<br> * @param param<br> * @return array<br> */<br>function getControllerForParam_php_unit_test_default_Test($method,$array,$param) {<br>			return $this->getControllerForParamTest($method,$array,$param);<br>}<br>}<br>?>";}