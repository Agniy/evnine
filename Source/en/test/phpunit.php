<?php
/**
 * /test/phpunit.php
 * The script creates a class for testing.
 * run: php phpunit.php
 * 
 * /test/ControllersPHPUnit.php
 * Controller test generation for the model.
 * 
 * /models/ModelsPHPUnit.php
 * Model for creating tests.
 * 
 * /evninePHPUnit.php
 * Created a script /test/phpunit.php for testing.
 * 
 * /evninePHPUnitTest.php 
 * Created by the cmd/sh:
 * phpunit --skeleton-test "evninePHPUnit"
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
 * /evnine.config.php
 *	$this->param_const=array(
 *		// A shared folder for the cache.
 *		'CacheDir'=>'PHPUnitCache',
 *		// Folder to store the PHPUnit tests.
 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
 *	) 
 */
include_once '../evnine.php';
include_once '../debug/evnine.debug.php';
$php_unit_file='../evninePHPUnit.php';
/**
 * Creating a configuration controller tests.
 */
$phpunit_config=array(
'controller_alias'=>array(
		'php_unit_test'=>array(
			'class_name'=>'ControllersPHPUnit',
			'path'=>'test'.DIRECTORY_SEPARATOR,
		)
	),
);
$evnine = new EvnineController();
$evnine->controller_alias = array_merge($evnine->controller_alias,$phpunit_config['controller_alias']);
/**
 * Add the path to the configuration of the controller tests.
 */
$evnine->path_to='..'.DIRECTORY_SEPARATOR;
/**
 * Reduce the depth of the path.
 */
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
		'evnine'=>$evnine
	)
);
/**
 * Make a request to the controller.
 * Use this link to itself as an argument to the test.
 */
echo $output['ModelsPHPUnit_getPHPUnitCodeWithBR'];
if (!file_exists($php_unit_file)){
	/**
	 * If the file test does not exist, create it.
	 */
	file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
	$dir = (defined( '__DIR__' )?__DIR__:getcwd());
	$basename = basename($dir);
	$dir = preg_replace("/[\/\\\]$basename$/i",'',$dir).DIRECTORY_SEPARATOR;
	$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
	/**
	 * Run the test generator for PHPUnit
	 */
	exec($exec);
}
?>
