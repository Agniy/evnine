<?php
/**
 * ModelsBase
 * @package ModelsBase
 * @author ev9eniy
 * @version 1.0
 * @updated 09-apr-2011 18:25:57
 */

class ModelsJoomla
{
	/**
	 * Link to mysql
 */
var $mysql;
var $isJoomla=false;

	/**
	 * ru: Конструктор класса
	 * 
	 */
	function __construct() 
	{
		if (defined( '_JEXEC' )){
			$this->isJoomla=true;
		}
	}

/**
 * After load class init api __construct_api 
 * 
 * @param mixed $param 
 * @access protected
 * @return void
 */
function setInitAPI($param) {
	$this->mysql=mysql_connect($param['host'],$param['login'],$param['pass']);
	if (!mysql_select_db($param['db'],$this->mysql)) {
		die("MySQL Error");
	}
}

function getQuery($query) 
{
	if ($this->isJoomla){
		return $this->getQueryJoomlaAPI($query);
	}else {
		return $this->getQueryFromMySQL($query);
	}
}

/**
	 * Получить данные через joomla API
	 * 
	 * @param query
 */
	function getQueryJoomlaAPI($query) 
	{
		$this->mysql->setQuery($query);
//		$this->mysql->loadAssocList();
		return $this->mysql->loadAssocList();
	}



function getQueryFirstArrayValue($query) 
{
	$query = $this->query($query);
	return $query['0'];
}

/**
 * getQueryTrueOrFalse
 * @param query
 * @param param
 */
function getQueryTrueOrFalse($query) 
{
	$query = $this->query($query);
	if (isset($query[0])){
		return true;
	}else {
		return false;
	}	
}

/**
 * getQueryOrFalse
 * @param query
 * @param param
 */
function getQueryOrFalse($query, $param) 
{
	$query = $this->query($query);
	if (isset($query[0])){
		return $query;
	}else {
		return false;
	}	
}


/**
 * getQueryFromMySQL
 * 
 * @param query
 */

function getQueryFromMySQL($query) 
{
	$sql_answer=mysql_query($query,$this->mysql);
	if (empty($sql_answer)){
		$mysql_error = mysql_error();
		return '<b><font color="orange">'.$mysql_error.'</font></b>';
	}
	while ($row=mysql_fetch_assoc($sql_answer)) 
		$array_out[]=$row;
	return $array_out;
}


/**
 * getLastID
 *
 * @param param
 */

function getLastID($table)
{
	$query = $this->query('SHOW TABLE STATUS LIKE \''.$table.'\';');
	return $query['0']['Auto_increment'];
}


function setCharacterToUTF8() {
	$sql = 'set character_set_client=\'utf8\',character_set_connection=\'utf8\', character_set_database=\'utf8\',   character_set_results=\'utf8\',character_set_server=\'utf8\';';
	mysql_query ($sql,$this->mysql);
}


/**
 * getCharSet 
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */

function getCharset($param){
	return $this->query('show VARIABLES like \'%char%\'');
}


/**
 * getInQueryFor
 * 
 * @access public
 * @return void
 */

function getInQueryFor($param) {
	return "'".implode("','", $param)."'";
}





/**
 * getImplodeArrayWithKeygetFileIDForWhereQuery 
 * 
 * @access public
 * @return void
 */

function getImplodeArrayWithKey($array,$key,$cases=" OR ") {
	return "(".$key.implode($cases.$key, $array).")";
}


/**
 * getFileIDForWhereQuery 
 * 
 * @access public
 * @return void
 */

function getCompareArrayByKey($param) {
	$param_count = count($param);
	$where = '';
	for ( $i = 0; $i < $param_count; $i++ ) {
		$key = $this->getFirstArrayKey($param[$i]);
		$where[] = ' like \''.$param[$i][$key].'\'';
	}
	return $where;
}

function getLikeQueryByArray($like_title,$param,$prefix='%+',$postfix='+%') {
	$param_count = count($param);
	$where = array();
	$key = $this->getFirstArrayKey($like_title);
	for ( $i = 0; $i < $param_count; $i++ ) {
		$where[] = $like_title[$key].' LIKE \''.$prefix.$param[$i].$postfix.'\'';
	}
	return implode($key,$where);
}


function getSameQueryByArrayForPathID($like_title,$param,$same='=') {
	$param_count = count($param);
	$where = array();
	$key = $this->getFirstArrayKey($like_title);
	for ( $i = 0; $i < $param_count; $i++ ) {
		$where[] = $like_title[$key].' '.$same.' \''.$param[$i].'\'';
	}
	return implode($key,$where);
}


/**
 * Get first array key
 * 
 * @access public
 * @return str
 */
function getFirstArrayKey($array) {
	list($key, $value)=each($array);
	return $key;
}


/**
 * getArrayKeyForArrayQuery
 * @param query
 * @param param
 */

function getArrayKeyForArrayQuery($query,$key) {
	$query_count = count($query);
	$tmp= array();
	for ( $i = 0; $i < $query_count; $i++ ) {
		$tmp[$query[$i][$key]]=$query[$i];
	}
	return $tmp;
}


/**
 * Reset For PHPUnitTest
 *
 */

function setResetForTest() 
{	 
}

}
?>