en: Example data from one form to different methods.
ru: Пример передачи данныx из одной формы в разные методы.

en: URI to the methods with data generated here:
ru: Ссылки на методы с данными формируются тут:
/controllers/ControllersHelloWorld.php
	'default'=>array(
		'inURLMethod' => array(
			'default',
			'submit_1',
			'submit_2',
			'submit_3',
			'submit_4',
		),
		'validation_multi_form'=>array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)
		)
	)
	'submit_1' => array(
		'validation_multi_form' => array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)
		),
	),
	'submit_2' => array(
		'validation_multi_form' => array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)	
		),
	),
	'submit_3' => array(
		'validation_multi_form' => array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)	
		),
	),
	'submit_4' => array(
		'validation_multi_form' => array(
			'test_id' => array(
				'to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
			)	
		),
	),
	
