en: Example of calling the parent controller with and without AJAX.
ru: Пример вызова родительского контроллера с AJAX и без.

/evnine.config.php
	$this->controller_alias=array(
		'helloworld'=>'ControllersHelloWorld',
		'helloworldparent'=>'ControllersHelloWorldParent',
		'helloworldparentparent'=>'ControllersHelloWorldParentParent'
	);

/controllers/ControllersHelloWorld.php
	'page_level'=>'2',
	'parent'=> 'helloworldparent',

/controllers/ControllersHelloWorldParent.php
	'page_level'=>'1',
	'parent'=> 'helloworldparentparent',

/controllers/ControllersHelloWorldParentParent.php
	'page_level'=>'0',
	'parent'=> 'helloworldparent',

