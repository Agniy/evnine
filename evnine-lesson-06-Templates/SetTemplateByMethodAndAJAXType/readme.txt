en: An example of selecting a template, depending on the method and type of the AJAX call.
ru: Пример выбора шаблона в зависимости от метода и типа AJAX запроса.

<< $output[ajax]=>False
<< $output[inURLView]=>page.tpl
<< $output[ViewMethod][default]=>AJAXisTrue_inURLView.tpl

/index.php
	$output = $evnine->getControllerForParam(
		array(
			'controller' => 'helloworld',
			'ajax' => 'false',
		)
	);

/controllers/ControllersHelloWorld.php
	$this->controller = array(
		'inURLView' => 'page.tpl',
		...
		'default'=>array(
			'inURLView'=>'AJAXisTrue_inURLView.tpl'
		)
	...
	)

<< $output[ajax]=>True
<< $output[inURLView]=>AJAXisTrue_inURLView.tpl
<< $output[ViewMethod][default]=>AJAXisTrue_inURLView.tpl

/index_ajax.php
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'ajax' => 'ajax',
	)
);

/controllers/ControllersHelloWorld.php
	$this->controller = array(
		'inURLView' => 'page.tpl',
		...
		'default'=>array(
			'inURLView'=>'AJAXisTrue_inURLView.tpl'
		)
	...
	)

<< $output[ajax]=>False
<< $output[inURLView]=>page.tpl
<< $output[ViewMethod][default2]=>default2
<< $output[ViewMethod][default]=>AJAXisTrue_inURLView.tpl

/index_method_default2.php
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'method' => 'default2',
		'ajax' => 'false',
	)
);

/controllers/ControllersHelloWorld.php
	'default2'=>array(
		'inURLView'=>''
	)