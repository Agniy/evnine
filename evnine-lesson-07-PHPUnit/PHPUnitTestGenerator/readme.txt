en: PHP Unit Test generator with parameters in the controller.
ru: Генератор PHP Unit Test по параметрам в контроллере.

/evnine.config.php
	'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
	'CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
	'CacheDir'=>'PHPUnitCache',

en:
ru: Метод в контроллере для получения сериализованных данных. 
/evnine.php
function getControllerForParamTest($method,$array_init,$param){
		...
		$file_name = $this->loaded_class[$methods_class]->getFileNameMD5ForParam($this->path_to.$this->param_const['CacheDirPHPUnit'],$param);
		$array = $this->loaded_class[$methods_class]->getSerData($file_name,$param);
		if (empty($array)){
			...
			$this->loaded_class[$methods_class]->setSerData($file_name,$array,true);
		}
		...
}

en:
ru: Сформированный класс тестов по результатом входных параметров.
/evninePHPUnitTest.php
	cmd/sh: phpunit --skeleton-test "evninePHPUnitTest"
	
	PHP Unit install:
	#http://www.phpunit.de/manual/3.0/en/installation.html
	#http://pear.php.net/manual/en/installation.getting.php
	
	wget http://pear.php.net/go-pear.phar
	sudo php go-pear.phar
	pear channel-discover pear.phpunit.de
	pear install phpunit/PHPUnit

	/**
		* @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test1'=>'1', 'test3'=>'23'))))
		* @assert ('getControllerForParam_helloworld_default_Test',$array,$param) == $array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test2'=>'1'))))
		*/
	function getControllerForParam_helloworld_default_Test($method,$array,$param) {
		$this->getControllerForParamTest($method,$array,$param);
		return $this->result;
	}
	
en:
ru: PHPUnit класс для сверки сохраненных и данных полученных из контроллера.
/evninePHPUnitTestTest.php
	Test class for evninePHPUnitTest.
	public function testGetControllerForParam_helloworld_default_Test()
	{
		$this->assertEquals(
			$array=($this->object->getControllerForParam($param=array( 'controller'=>'helloworld', 'method'=>'default', 'for_all'=>'all', 'REQUEST'=>array( 'test1'=>'1', 'test3'=>'23'))))
	,
			$this->object->getControllerForParam_helloworld_default_Test('getControllerForParam_helloworld_default_Test',$array,$param)
			);
		}
	}

/index.php
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'php_unit_test',
		'ajax' => 'ajax',
	)
);

en:
ru: Тесты для методов указаны inURLUnitTest.
/controllers/ControllersHelloWorld.php
	'public_methods' => array(
			'default'=>array(
				'inURLUnitTest' => array(
					'REQUEST'=>array(
					en:
					ru:
						array('test1' => '1','test3'=>'23'),
					en:
					ru:
						array('test2' => '1',)
					),
				),
			),
			'default_no_inURLUnitTest'=>array(
				'inURLUnitTest' => array(),
			),
	)

en:
ru: Контроллера для опроса других контроллеров
/controllers/ControllersPHPUnit.php

en:
ru: Модель для генерации тестов
/models/ModelsPHPUnit.php

en:
ru: Для хранения всех тестов
/PHPUnitCache/PHPUnit
