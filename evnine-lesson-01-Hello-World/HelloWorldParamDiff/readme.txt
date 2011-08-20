en: Display changes in the parameters.
ru: Отображение изменений в параметрах.

index.php
$param = array(
	'controller' => 'helloworld',
	'hello'=>'hello',
)

controllers/ControllersHelloWorld.php
$controller['public_methods']['default']['ModelsHelloWorld']='isParamHello';
$controller['public_methods']['default']['isParamHello_true']['ModelsHelloWorld']='getHelloWorld';

models/ModelsHelloWorld.php
en: Call isParamHello, getHelloWorld 
ru: Вызов isParamHello, getHelloWorld
function getHelloWorld(&$param){
	unset($param['hello']);
}

function getByeBye(&$param){
 	$param['hello']='byebye';
}


