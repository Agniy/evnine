en: Basic Example with YAML
en: Calling a method from the model controller.
ru: Базовый пример с использования YAML
ru: Hello World вызывается из модели контроллером.

index.php
$param = array(
	'controller' => 'helloworld',
)

controllers/ControllersHelloWorld.php
<<<YAML
public_methods:
 default: 
  ModelsHelloWorld: getHelloWorld
YAML

models/ModelsHelloWorld.php
en: Call getHelloWorld 
ru: Вызов getHelloWorld


