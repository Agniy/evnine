en: Example API MySQL.
ru: Пример подключения API MySQL. 

/evnine.config.php
	$this->api='ModelsMySQL';
	$this->class_path=array(
				'ModelsMySQL'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
				'param'=>array(
					'host'=>'localhost',
					'login' => 'root',
					'pass' => 'root',
					'db' => 'information_schema'
				),
			),

	)

en: Basic model of working with API
ru: Базовая модель работы с API
/models/ModelsMySQL.php
	function setInitAPI($param) {
		$this->mysql=mysql_connect($param['host'],$param['login'],$param['pass']);
		if (!mysql_select_db($param['db'],$this->mysql)) {
			die("MySQL Error");
		}
	}

/models/ModelsHelloWorld.php
	function getQuery($param) {
		$array['databases']=$this->api->getQuery('show databases');
		return $array;
	}
