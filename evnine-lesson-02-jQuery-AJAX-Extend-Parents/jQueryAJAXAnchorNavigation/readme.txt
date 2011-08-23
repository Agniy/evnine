en: jQuery plugins for use with the controller evnine.
en: Cross-browser plugin AJAX anchor navigation.
ru: jQuery плагины для работы с контроллером evnine. 
ru: Кроссбраузерный плагин с AJAX якорной навигации.

/js/jq.index.js
	debugPrefixString                 :'|	',
	scriptForAJAXCallAndSetAnchore    :'/evnine-lesson-02-jQuery-AJAX/HelloAJAXJQuery/index.php',
	//en: Function is for controller with method.
	/*ru: Контроллер.метод и связанная с ним функции */
	setFuncByEvnineParamMatch       :{
		//For controller
		'validation'                  :'default',
		'default'                     :'default',
		'param1'                      :'default',
		//controller.method
		'param1.param1'               :'default'
	}
	
/index.php
	$output = $evnine->getControllerForParam(
		array(
			'controller' => 'validation',
			'REQUEST' => $_REQUEST,
			'ajax' => $_REQUEST['ajax'],
		)
	);

FF3.5+,Chrome9+,Opera9+,Safari4+,IE8+
/HelloAJAXJQuery/index.php?param=param#!?test=test
=
/HelloAJAXJQuery/index.php#!?test=test

if IE6, IE7
/HelloAJAXJQuery/index.php?param=param#!?test=test
=
/HelloAJAXJQuery/index.php?test=test

