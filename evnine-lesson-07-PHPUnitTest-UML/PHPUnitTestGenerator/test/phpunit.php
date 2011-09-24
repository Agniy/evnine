<?php
/**
 * /test/phpunit.php
 * en: The script creates a class for testing.
 * en: run: php phpunit.php
 * ru: Скрипт создаёт класс для тестирования. 
 * ru: Запуск: php phpunit.php
 * 
 * /test/ControllersPHPUnit.php
 * en: Controller test generation for the model.
 * ru: Контроллер генерации тестов по модели.
 * 
 * /models/ModelsPHPUnit.php
 * en: Model for creating tests.
 * ru: Модель для создания тестов.
 * 
 * /evninePHPUnit.php
 * en: Created a script /test/phpunit.php for testing.
 * ru: Создан скриптом /test/phpunit.php для тестов.
 * 
 * /evninePHPUnitTest.php 
 * en: Created by the cmd/sh:
 * ru: Созданный по команде cmd/sh:
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
 *		// en: A shared folder for the cache.
 *		// ru: Общая папка для кэша.
 *		'CacheDir'=>'PHPUnitCache',
 *		// en: Folder to store the PHPUnit tests.
 *		// ru: Папка для хранения PHPUnit тестов.
 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
 *	) 
 */
include_once '../evnine.php';
include_once '../debug/evnine.debug.php';
$php_unit_file='../evninePHPUnit.php';
/**
 * en: Creating a configuration controller tests.
 * ru: Создаём конфигурацию с контроллером тестов.
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
 * en: Add the path to the configuration of the controller tests.
 * ru: Добавляем в конфигурацию путь до контроллера тестов.
 */
$evnine->path_to='..'.DIRECTORY_SEPARATOR;
/**
 * en: Reduce the depth of the path.
 * ru: Уменьшаем глубину пути.
 */
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
		'evnine'=>$evnine
	)
);
/**
 * en: Make a request to the controller.
 * en: Use this link to itself as an argument to the test.
 * ru: Делаем запрос в контроллер. 
 * ru: Используем ссылку на себя как аргумент при формировании тестов.
 */
echo '#$output: <pre>'; if(function_exists(print_r2)) print_r2($output); else print_r($output);echo "</pre><br />\r\n";
echo $output['ModelsPHPUnit_getPHPUnitCodeWithBR'];
if (!file_exists($php_unit_file)){
	/**
	 * en: If the file test does not exist, create it.
	 * ru: Если файла тестов не существует, создадим его.
	 */
	file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
	$dir = (defined( '__DIR__' )?__DIR__:getcwd());
	$basename = basename($dir);
	$dir = preg_replace("/[\/\\\]$basename$/i",'',$dir).DIRECTORY_SEPARATOR;
	$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
	/**
	 * en: Run the test generator for PHPUnit
	 * ru: Запускаем генератор тестов для PHPUnit
	 */
	exec($exec);
}
?>
