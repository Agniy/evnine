<?php
/**
 * /test/phpunit.php
 * Скрипт создаёт класс для тестирования. 
 * Запуск: php phpunit.php
 * 
 * /test/ControllersPHPUnit.php
 * Контроллер генерации тестов по модели.
 * 
 * /models/ModelsPHPUnit.php
 * Модель для создания тестов.
 * 
 * /evninePHPUnit.php
 * Создан скриптом /test/phpunit.php для тестов.
 * 
 * /evninePHPUnitTest.php 
 * Созданный по команде cmd/sh:
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
 *		// Общая папка для кэша.
 *		'CacheDir'=>'PHPUnitCache',
 *		// Папка для хранения PHPUnit тестов.
 *		'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
 *	) 
 */
include_once '../evnine.php';
include_once '../debug/evnine.debug.php';
$php_unit_file='../evninePHPUnit.php';
/**
 * Создаём конфигурацию с контроллером тестов.
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
 * Добавляем в конфигурацию путь до контроллера тестов.
 */
$evnine->path_to='..'.DIRECTORY_SEPARATOR;
/**
 * Уменьшаем глубину пути.
 */
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
		'evnine'=>$evnine
	)
);
/**
 * Делаем запрос в контроллер. 
 * Используем ссылку на себя как аргумент при формировании тестов.
 */
echo $output['ModelsPHPUnit_getPHPUnitCodeWithBR'];
if (!file_exists($php_unit_file)){
	/**
	 * Если файла тестов не существует, создадим его.
	 */
	file_put_contents($php_unit_file,$output['ModelsPHPUnit_getPHPUnitCode']);
	$dir = (defined( '__DIR__' )?__DIR__:getcwd());
	$basename = basename($dir);
	$dir = preg_replace("/[\/\\\]$basename$/i",'',$dir).DIRECTORY_SEPARATOR;
	$exec= 'phpunit --skeleton-test "evninePHPUnit" "'.$dir.'evninePHPUnit.php"';
	/**
	 * Запускаем генератор тестов для PHPUnit
	 */
	exec($exec);
}
?>
