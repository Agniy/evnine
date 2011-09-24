en: !!! *nix/MAC Please add write permissions to the folder /PHPUnitCache/
ru: !!! *nix/MAC пожалуйста, добавьте права на запись в папку /PHPUnitCache/

en: Alternative PHPUnitTest with a visual display changes.
en: It is necessary for testing in CMS.
ru: Альтернатива PHPUnitTest с визуальным отображением изменений.
ru: Нужно для тестирования внутри CMS.

en: Calling the model tests.
ru: Вызов модели тестов.
/index.php
/**
 * en: Use two controllers.
 * en: One model for other view (param in of the model),
 * en: to access the URL Generator.
 * ru: Используем вызов двух контроллеров.
 * ru: Один для модели другой для вида (На входе параметры модели), 
 * ru: для получения доступа к URL генератору.
 */
$out = $evnine->getControllerForParam(
	array(
		'controller' => 'param_gen_models',
		'ajax' => 'ajax',
		'REQUEST' => $_REQUEST
	)
);
$output = $evnine->getControllerForParam(
array_merge(
		$out['param_out'],
		array(
			'controller'=>'param_gen_view'
			,'method'=>'default'
			,'inURL'=>$out['inURL']
		)
	)
);

en: Script processing on the hot keys.
ru: Скрипт обработки перехода по горячим клавишам.
/js/jq.index.js
	$key_object={
		'keys':'#anchor'
		'ctrl+alt+j':'head',
		'ctrl+alt+k':'1-tpl',
		'ctrl+alt+l':'1-array',
	}

en: Controller for testing.
ru: Контроллер для тестирования.
/controllers/ControllersHelloWorld.php

en: Controller to check the other controllers.
ru: Контроллера для опроса других контроллеров
/controllers/ControllersPHPUnit.php
/test/ControllersPHPUnit.php

en: The controller process the tests.
ru: Контроллер обработки тестов.
/controllers/ControllersParamGenModels.php
	'ModelsPHPUnit' => array(
		'getParamTest', 
		'getModelsAndControllerModifierTimeFromCache',
		'getParamCaseByParamTest',
		'getCountParamByParamTest',
		'getParamTextName',
		'getDataFromControllerByParam',
		'getPHPUnitCode',
		'getComparePHPUnitForControllers',
	)
	'reset_phpunit' => array(
	en: The method to reset the test.
	ru: Метод для сброса тестов.
	)
	
en: Controller to display the tests.
ru: Контроллер для отображения тестов.
/controllers/ControllersParamGenView.php
	'ViewsUnitPHP'=>array(
		'getHTMLButton',
		'getHTMLCaseHeader',
		'getHTMLMSGHeader',
		'getHTMLData'
	),

en: Folder to store the PHPUnit tests.
ru: Папка для хранения PHPUnit тестов.
/PHPUnitCache/PHPUnit/

en: Folder to store temporary data.
ru: Папка для хранения промежуточных данных.
/PHPUnitCache/getControllerForParam/

en: Time changes models and controllers to update the cache.
ru: Время изменения моделей и контроллеров для обновления кэша.
/PHPUnitCache/cache_for_controllers_and_models.php

en: To create tests for the controllers.
ru: Для создания тестов по контроллерам.
/test/phpunit.php

en: Model for test generation.
ru: Модель для генерации тестов.
/models/ModelsPHPUnit.php

en: The view models with Twig.
ru: Модель вида с подключение шаблонизатора.
/views/ViewsUnitPHP.php

en: The base model of the view.
ru: Базовая модель вида.
/views/ViewsUnitPHPExtend.php

en: Template for Twig.
ru: Шаблон для Twig.
/views/twig.tpl

en: Cache template for Twig.
ru: Кэш для шаблонизатора Twig.
/views/cache

en: Method in the controller for the stored data from the last call.
ru: Метод в контроллере для получения сохраненных данных с прошлого вызова. 
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

/evnine.config.php
en: To save parameters to exit.
ru: Для сохранения всех параметров на выходе.
	$this->param_const=array(
		'param_out'=>true
	)

en: class template
ru: Класс шаблонизатора
/debug/evnine.views.generator.template.php
function getArrayToTemplate ($array,$shift=0,$template='Twig') {
	include_once('evnine.views.generator.template.config.php');
	return $this->getTemplateStr(
		$this->getParsingArrayForTemplate(
			$this->getMinimizeArray($array)
	),$shift,$template)
}

en: config template
ru: Конфиг шаблонизатора
/debug/evnine.views.generator.template.config.php
function getConfigPHPSHORT(  ) {
 return array(
  'tag_comment'  => array('&lt;'.'?'.'/'.'*$', '*'.'/ ?'.'>',' '),
	'tag_block'    => array('&lt;'.'?'.' ', ' ?'.'>'),
	'tag_block_open_close'    => array('(', '):'),
  'tag_variable' => array('&lt;'.'?'.'=', '?'.'>','$'),
  'tag_variable_join'  => array('[\'','\']'),
  'tag_block_if'  => array('if ', 'endif;'),
	'tag_block_for'  => array('foreach ', 'endforeach',' as $','For_'),
	'tag_block_init_var'  => true,
	'tag_block_for_place_tmp_var'  => 2,
	);
}

en: The class of tests for the generator PHPUnitTest.
ru: Класс тестов для генератора PHPUnitTest.
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
	
en: PHPUnitTest class to validate the data stored from the last call and the current from the controller.
ru: PHPUnitTest класс для сверки данных сохраненных от прошлого вызова и текущих из контроллера.
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
	
en: !!! Please add write permissions to the folder /PHPUnitCache/
ru: !!! *nix/MAC пожалуйста, добавьте права на запись в папку /PHPUnitCache/
