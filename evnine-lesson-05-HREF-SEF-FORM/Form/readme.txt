en: An example of the creation the form.
ru: Пример создания данных для формы.

en: URI to the methods with data generated here:
ru: Ссылки на методы с данными формируются тут:
/controllers/ControllersHelloWorld.php
	'default'=>array(
		'inURLMethod' => array(
			'default',
		)
	'post'=>array(
en: For post:
ru: Для формы:
		'validation_form'=>array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
				),
		),
	)