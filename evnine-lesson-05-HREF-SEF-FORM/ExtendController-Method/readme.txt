en: Example of calling an external method and controller.
ru: Пример вызова внешнего метода и контроллера. 

<< ?c=ext_controller&m=ext_method

/controllers/ControllersHelloWorld.php
'default'=>array(
	'inURLExtController'=>'ext_controller',
	'inURLExtMethod'=>'ext_method',
	'inURLMethod' => array(
		'default'
	)
),

