en: Example of changing the link in the template, depending on the chosen method.
ru: Пример смены ссылке в шаблоне в зависимости от выбраного метода.

<< [inURLTemplate][info][pre] = ?c=helloworld&m=default

/index.php
	$output = $evnine->getControllerForParam(
		array(
			'controller' => 'helloworld',
			'method'=>'default',
		)
	);
/controllers/ControllersHelloWorld.php
	'default'=>array(
		'inURLMethod'=>array('default2'),
		'inURLTemplate' => array(
			'info' => 'default2'
		),
	),


<< [inURLTemplate][info][pre] = ?c=helloworld&m=default2

/index_new_info.php
	$output = $evnine->getControllerForParam(
		array(
			'controller' => 'helloworld',
			'method'=>'default2',
		)
	);

/controllers/ControllersHelloWorld.php
	'default'=>array(
		'inURLMethod'=>array('default'),
		'inURLTemplate' => array(
			'info' => 'default'
		),
	),

