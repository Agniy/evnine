en: Example of data in the array as a reference and form.
ru: Пример передачи данных в массиве как ссылка и форма.

en: URI to the methods with data generated here:
ru: Ссылки на методы с данными формируются тут:
/controllers/ControllersHelloWorld.php
	'default'=>array(
		'inURLMethod' => array(
			'default',
			'post'
		)
	en: For get:
	ru: Для GET запроса:
		'validation_add'=>array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)
		)
	),
	'post'=>array(
	en: For post:
	ru: Для формы:
		'validation_form'=>array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
				),
		),
	)
