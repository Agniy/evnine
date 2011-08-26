en: An example of a model for throw error.
ru: Пример модели для перехвата ошибок.

en: Run method with an error.
ru: Запускаем метод с ошибкой из контроллера.
/controllers/ControllersHelloWorld.php
	'ModelsHelloWorld' => array(
		'setThrowNewException'
 	),


en: In the method throws an error.
ru: В методе выбрасываем ошибку.
/models/ModelsHelloWorld.php
	function setThrowNewException($param){
		throw new Exception('ModelsHelloWorld->setThrowNewException throw new Exception(\'\')');
	}
	
en: Pass the error in the error model
ru: Передаём ошибку в модель ошибок
/controllers/ControllersHelloWorld.php
	'ModelsErrors'=>array(
		'getError',
		'getError->alternative_way_of_setting_errors',
	)