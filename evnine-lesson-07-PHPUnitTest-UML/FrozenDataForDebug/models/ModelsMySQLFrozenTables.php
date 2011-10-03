<?php
/**
 * ModelsMySQLFrozenTables
 * @package ModelsMySQLFrozenTables
 */
class ModelsMySQLFrozenTables
{

	/**
	 * en: API interface for mysql
	 * ru: Связь с базой данных
	 */
	var $api;

	function __construct($api){
		$this->api=$api;
	}
	
	function setResetEvnine(){
		$this->setNewDatabase('evnine');
		$this->setDropTable('evnine');
		$this->setCreateEvnine();
		$this->setInsertEvnine();
	}
	
	function setNewDatabase($database){
		$getQuery='CREATE DATABASE IF NOT EXISTS `'.$database.'`';
		$this->api->getQuery($getQuery);		
		$getQuery='use `'.$database.'`';
		$this->api->getQuery($getQuery);		
	}
	
	function setDropTable($table){
		$getQuery='DROP TABLE IF EXISTS `'.$table.'`;';
		$this->api->getQuery($getQuery);		
	}
	
	function setCreateEvnine(){
		$getQuery=
			' CREATE TABLE `evnine` ('."\n"
			.'  `id` int(11) COMMENT \'ID\','."\n"
			.'  `text` char(250) COMMENT \'TEXT\''."\n"
			.' )'."\n"
			.' ENGINE=INNODB'."\n"
			.' CHARACTER SET utf8'."\n"
			.' COLLATE utf8_general_ci ;'."\n"
			.' '."\n";
		$this->api->getQuery($getQuery);
	}
	
	function setInsertEvnine(){
		$getQuery=' INSERT INTO `evnine` (`id`, `text`) VALUES'."\r\n"
			.'  (1, \'TEXT 1\')'
			.' ,(2, \'TEXT 2\')'
			.';';
		$this->api->getQuery($getQuery);
	}
	
	function setDelEvnine(){
		$getQuery=
			' DELETE FROM `evnine`;'."\n";
		$this->api->getQuery($getQuery);
	}
	
}
?>
