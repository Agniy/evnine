en: The case of response methods.
ru: Ответы методов. 

/index.php
$param = array(
	'controller' => 'helloworld',
	'hello'=>'hello',
)

/controllers/ControllersHelloWorld.php
$controller['public_methods']['default']['ModelsHelloWorld']='isParamHello';
$controller['public_methods']['default']['isParamHello_true']['ModelsHelloWorld']='getHelloWorld';

/models/ModelsHelloWorld.php
en: Call isParamHello, getHelloWorld 
ru: Вызов isParamHello, getHelloWorld


