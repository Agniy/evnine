<?php
/**
 * ModelsMySQLFrozenTables
 * @package Models
 * @author ev9eniy
 * @version 1.0
 * @updated 10-ноя-2010 18:25:57
 */

class ModelsMySQLFrozenTables
{

/**
 * Связь с базой данных
 */
var $database;

/**
 * Конструктор класса
 */
function __construct($database){
	$this->database=$database;//Сохраняем досту
}

function setResetEvnine(){
	$this->setNewDatabase('evnine');
	$this->setDropTable('evnine');
	$this->setCreateEvnine();
	$this->setInsertEvnine();
}

function setNewDatabase($database){
	$getQuery='CREATE DATABASE IF NOT EXISTS `'.$database.'`';
	$this->database->getQuery($getQuery);		
	$getQuery='use `'.$database.'`';
	$this->database->getQuery($getQuery);		
}


function setDropTable($table){
	$getQuery='DROP TABLE IF EXISTS `'.$table.'`;';
	$this->database->getQuery($getQuery);		
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
	$this->database->getQuery($getQuery);

}

function setInsertEvnine(){
	$getQuery=' INSERT INTO `evnine` (`id`, `text`) VALUES'."\r\n"
		.'  (1, \'TEXT 1\')'
		.' ,(2, \'TEXT 2\')'
		.';';
	$this->database->getQuery($getQuery);
}

function setDelEvnine(){
	$getQuery=
		' DELETE FROM `evnine`;'."\n";
	$this->database->getQuery($getQuery);
}

}
?>
