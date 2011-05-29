<?php
/**
 * Модель установки и удаления таблиц
 * @package ModelsBase
 * @author ev9eniy
 * @version 1.0
 * @updated 10-ноя-2010 18:25:57
 */

class ModelsDatabaseInstallTables
{


	var $api;
	/**
	 * Constructor
	 */
	function __construct($api){
		$this->api=$api;
		//en: save api (Joomla link to class)
	}

function setResetHello(){
	$this->setDropHello();
	$this->setCreateHello();
	$this->setInsertHello();
}
function setDropHello(){
	$query=' DROP TABLE IF EXISTS `#__evnine`;';
	$this->api->query($query); 
}
function setCreateHello(){
	$query=
		' CREATE TABLE `#__evnine` ('."\r\n"
		.'   `UserID` int(11) NOT NULL COMMENT \'user_id joomla\','."\r\n"
		.'   `Date` datetime COMMENT \'just date\','."\r\n"
		.'   PRIMARY KEY(`UserID`),'."\r\n"
		.' )'."\r\n"
		.' ENGINE=INNODB'."\r\n"
		.' COMMENT = \'evnine example\';'."\r\n"
		.' ';
	$this->api->query($query); 
}
function setDelHello(){
	$query=' DELETE FROM `#__evnine`;';
	$this->api->query($query); 
}
function setInsertHello(){
	$query=
		' INSERT INTO `#__evnine` (`UserID`, `Date`) VALUES'."\r\n"
	 	 .' (62, \'2011-01-09\')'
		.' ,(62, \'2011-02-01\')'
		.';';
	$this->api->query($query);
}

}
?>
