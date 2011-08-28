en: Basic Example
en: Calling a method from the model controller.
ru: Базовый пример
ru: Hello World вызывается из модели контроллером.

/index.php
$param = array(
	'controller' => 'helloworld',
)

/controllers/ControllersHelloWorld.php
$controller['public_methods']['default']['ModelsHelloWorld']='getHelloWorld';

/models/ModelsHelloWorld.php
en: Call getHelloWorld 
ru: Вызов getHelloWorld


