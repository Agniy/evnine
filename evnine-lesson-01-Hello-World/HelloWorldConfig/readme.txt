en: The model data is set from the configuration file.
ru: В модель передаются данные из конфигурационного файла.

/index.php
$param = array(
	'controller' => 'helloworld',
)

/evnine.config.php
$this->class_path=array(
	'ModelsHelloWorld'=>array(
		'path'=>'models'.DIRECTORY_SEPARATOR,
		'param'=>array(
			'hello'=>'config',
		),
	),
);

/controllers/ControllersHelloWorld.php
$controller['public_methods']['default']['ModelsHelloWorld']='getHelloWorld';

/models/ModelsHelloWorld.php
en: Call getHelloWorld 
ru: Вызов getHelloWorld


