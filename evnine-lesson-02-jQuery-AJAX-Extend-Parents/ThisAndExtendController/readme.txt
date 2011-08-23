en: An example of references to methods in other controllers.
ru: Пример ссылки на методы в других контроллерах.

/evnine.config.php
	$this->controller_alias=array(
		'helloworld'=>'ControllersHelloWorld',
		'helloworldext'=>'ControllersHelloWorldExtend',
	);

/controllers/ControllersHelloWorld.php
	'public_methods' => array(
		'default'=>array(
			'this'=>'this_public_method_call',
			'helloworldext'=>'default',
		),
		'this_public_method_call'=>array(
			'ModelsHelloWorld' => 'isGetHelloWorld'
		)
	)

