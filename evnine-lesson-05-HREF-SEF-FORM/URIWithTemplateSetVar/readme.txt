en: An example of adding a variable in the template.
ru: Пример добавление переменной в шаблоне.

>> $output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].'insert_by_template'.$output['inURL']['default']['post'];
<< ?c=helloworld&m=default&test_id=insert_by_template

/index.php

echo 'URN: '.$output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].'insert_by_template'.$output['inURL']['default']['post'];

/controllers/ControllersHelloWorld.php
	'test_id' => array(
		'to'=>'TestID'
		,'inURL' => true
		...
	)

