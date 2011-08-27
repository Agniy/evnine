en: Example input data to an URI.
ru: Пример передачи в ссылке данных со входа.

>> 'REQUEST' => array('test_id' => '777'),
>> $output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].$output['inURL']['default']['post']

<< ?c=helloworld&m=default&test_id=777

/index.php
$output = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'REQUEST' => array('test_id' => '777'),
		'ajax' => 'ajax',
	)
);
echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].'insert_by_template'.$output['inURL']['default']['post'];

/controllers/ControllersHelloWorld.php
	'test_id' => array(
		'to'=>'TestID'
		,'inURL' => false
		...
	)
