en: “frozen” data for debug and unit test (in DB, files, env, etc)
ru: Пример как данные в процессе отладки и тестировании можно заморозить 

en: Config (DB, files, env, etc)
ru: Конфиг (Таблицы, Файлы, окружение, итд)
/evnine.config.php
	$this->param_const=array(
		'frozen_file'=>'frozen_file.txt',
		'ResetDBClass'=>'models'.DIRECTORY_SEPARATOR.'ModelsMySQLFrozenTables.php',
		'setResetForTest'=>true,
		...
	)

en: For debugging, when you need to reset the data before get the answer.
ru: Для отладки, когда нужно сбросить данные перед получением ответа.
/evnine.php
	if ($this->param["setResetForTest"]==true){
		if ((method_exists($this->loaded_class[$methods_class],'setResetForTest'))){
			$this->loaded_class[$methods_class]->setResetForTest($this->param);
		}
	}

en: Methods for modifying data in tables.
ru: Методы изменяют данные в таблице.
/controllers/ControllersHelloWorld.php
'default'=>array(
	'ModelsHelloWorld' => array(
		'getContentFromFrozenFile',
		'getQueryFromTableEvnine',
		'setUpdateTableEvnineModifierParamSetResetFalse',
		'getQueryFromTableEvnineAfterUpdate'
	), 

en: Model reset every time data when you call.
ru: Модель сбрасывает каждый раз данные при вызове метода.
/models/ModelsHelloWorld.php
	function setUpdateTableEvnineModifierParamSetResetFalse(&$param) {
		return $this->api->getQuery('UPDATE `evnine` SET text=\'update\'');
	}
	function setResetForTest($param){
		file_put_contents($param['frozen_file'],'ModelsHelloWorld::setResetForTest');
		include_once($param['ResetDBClass']);
		$ModelsMySQLFrozenTables = new ModelsMySQLFrozenTables($this->api); 
		$ModelsMySQLFrozenTables->setResetEvnine($param);
	}

/models/ModelsMySQLFrozenTables.php
function setResetEvnine(){
	$this->setDropTable('evnine');
	$this->setCreateEvnine();
	$this->setInsertEvnine();
}
function setDropTable($table){
	$getQuery='DROP TABLE IF EXISTS `'.$table.'`;';
	$this->database->getQuery($getQuery);		
}
function setCreateEvnine(){
	$getQuery=
		' CREATE TABLE `evnine` ('."\n"
		...
		.' '."\n";
	$this->database->getQuery($getQuery);
}
function setInsertEvnine(){
	$getQuery=' INSERT INTO `evnine` (`id`, `text`) VALUES'."\r\n"
		.'  (1, \'TEXT 1\')'
		.' ,(2, \'TEXT 2\')'
		.';';
	$this->database->getQuery($getQuery);
}

/models/ModelsMySQL.php
...
function getQuery($query) 
{
	$sql_answer=mysql_query($query,$this->mysql);
	...
}
