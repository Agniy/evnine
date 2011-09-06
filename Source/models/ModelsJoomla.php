<?php
/** ModelsJoomla
 * en: The model works with Joomla.
 * ru: Модель работы с Joomla.
 * 
 * @package ModelsJoomlaAPI
 * @author ev9eniy
 * @version 2.0
 * @updated 03-sep-2011 18:25:57
 */

class ModelsJoomla
{
	/** $this->mysql 
	 * en: Link to the database connection.
	 * ru: Ссылка на соединение с базой.
	 * 
	 * @var object
	 * @access public
	 */
	var $mysql;

	/** $this->isJoomla 
	 * en: Is there access to joomla.
	 * ru: Есть ли доступ к joomla.
	 * 
	 * @var boolean
	 * @access public
	 */
	var $isJoomla=false;
	
	/** __construct() 
	 * en: Checking environment. Is joomla running?.
	 * ru: Проверка окружения. Запущена ли joomla? 
	 * 
	 * @access protected
	 * @return void
	 */
	function __construct() 
	{
		if (defined( '_JEXEC' )){
			$this->isJoomla=true;
		}
	}
	
	/** setInitAPI($param)
	 * en: The class constructor.
	 * ru: Конструктор класса.
	 * 
	 * @param array $param 
	 * @access protected
	 * @return void
	 */
	function setInitAPI($param) {
		if ($this->isJoomla){
			$this->mysql = JFactory::getDBO();
		} else {
			$this->mysql=mysql_connect($param['host'],$param['login'],$param['pass']);
			if (!mysql_select_db($param['db'],$this->mysql)) {
				die("MySQL Error");
			}
		}
	}
	
	/** getQuery($query)
	 * en: Get data from database.
	 * ru: Получить данные из базы. 
	 * 
	 * @param string $query 
	 * @access public
	 * @return array
	 */
	function getQuery($query) 
	{
		if ($this->isJoomla){
			return $this->getQueryJoomlaAPI($query);
		}else {
			return $this->getQueryFromMySQL($query);
		}
	}
	
	/** getQueryFromMySQL($query) 
	 * en: Get the answer from the database.
	 * ru: Получить ответ из базы.
	 * 
	 * @param string $query 
	 * @access public
	 * @return array
	 */
	function getQueryFromMySQL($query) 
	{
		$sql_answer=mysql_query($query,$this->mysql);
		if (empty($sql_answer)){
			$mysql_error = mysql_error();
			return '<b><font color="orange">'.$mysql_error.'</font></b>';
		}
		while ($row=mysql_fetch_assoc($sql_answer)){
			$array_out[]=$row;
		}
		return $array_out;
	}
	
	
	/** getQueryJoomlaAPI($query) 
	 * en: Get data from joomla API.
	 * ru: Получить данные через joomla API.
	 * 
	 * @param string query
	 */
		function getQueryJoomlaAPI($query) 
		{
			$this->mysql->setQuery($query);
			return $this->mysql->loadAssocList();
		}
	
	
	/** getQueryFirstArrayValue($query) 
	 * en: Get the first element of the array.
	 * ru: Получить первый ответ из массива. 
	 * 
	 * @param string $query 
	 * @access public
	 * @return array
	 */
	function getQueryFirstArrayValue($query) 
	{
		$query = $this->query($query);
		return $query['0'];
	}
	
	/** getQueryTrueOrFalse($query) 
	 * en: Get a boolean answer based on a query true - false.
	 * ru: Получить логический ответ по запросу true - false.
	 * 
	 * @param string $query 
	 * @access public
	 * @return boolean
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
	
	/** getQueryOrFalse($query) 
	 * en: Get the answer or false.
	 * ru: Получить ответ или false.
	 * 
	 * @param string $query 
	 * @access public
	 * @return boolean|array
	 */
	function getQueryOrFalse($query) 
	{
		$query = $this->query($query);
		if (isset($query[0])){
			return $query;
		}else {
			return false;
		}	
	}
	
	/** getLastID($table)
	 * en: Get the latest ID from the table.
	 * ru: Получить последний ID из таблицы.
	 * 
	 * @param string $table 
	 * @access public
	 * @return int
	 */
	function getLastID($table)
	{
		$query = $this->query('SHOW TABLE STATUS LIKE \''.$table.'\';');
		return $query['0']['Auto_increment'];
	}
	
	
	/** setCharacterToUTF8()
	 * en: Set the connection character UTF-8
	 * ru: Установить кодировку соединения UTF-8
	 * 
	 * @access public
	 * @return void
	 */
	function setCharacterToUTF8() {
		return $this->query('set character_set_client=\'utf8\',character_set_connection=\'utf8\', character_set_database=\'utf8\',   character_set_results=\'utf8\',character_set_server=\'utf8\';');
	}
	
	/** getCharset(){
	 * en: Get the current charset.
	 * ru: Получить текущую кодировку.
	 * 
	 * @access public
	 * @return array
	 */
	function getCharset(){
		return $this->query('show VARIABLES like \'%char%\'');
	}
	
	/** getInQueryFor($array)
	 * en: The SQL query assistant.
	 * ru: Помощь при выборке данных.
	 * 
	 * >> array(
	 * '0' => 'A',
	 * '1' => 'B',
	 * )
	 * << 'A','B'
	 * 
	 * @param array $array 
	 * @access public
	 * @return string
	 */
	function getInQueryFor($array) {
		return "'".implode("','", $array)."'";
	}
	/** getImplodeArrayWithKey($array,$key,$cases=" OR ") {
	 * en: The SQL query assistant.
	 * ru: Помощь при выборке данных.
	 * 
	 * >> array(
	 *	'0' => 'A',
	 *	'1' => 'B',
	 * )
	 * >> key = ' id like '
	 * >> $case = ' OR '
	 * << ( id like A OR id like B )
	 * 
	 * @param array $array 
	 * @param string $key
	 * @param string $case = " OR "
	 * @access public
	 * @return string
	 */
	function getImplodeArrayWithKey($array,$key,$case=" OR ") {
		return "(".$key.implode($case.$key, $array).")";
	}
	
	/** getCompareArrayByKey($array)
	 * en: The SQL query assistant.
	 * ru: Помощь при выборке данных.
	 * 
	 * >> array(
	 *	'0' => 'A',
	 *	'1' => 'B',
	 * )
	 * << array(
	 *	'0' => "like 'A'",
	 *	'1' => "like 'B'",
	 * )
	 * 
	 * @param array $array 
	 * @access public
	 * @return array
	 */
	function getCompareArrayByKey($array) {
		$array_count = count($array);
		$where = '';
		for ( $i = 0; $i < $array_count; $i++ ) {
			$key = $this->getFirstArrayKey($array[$i]);
			$where[] = ' like \''.$array[$i][$key].'\'';
		}
		return $where;
	}
	
	/** getLikeQueryByArray($like_title,$same='LIKE',$array,$prefix='%+',$postfix='+%')
	 * en: The SQL query assistant.
	 * ru: Помощь при выборке данных.
	 * 
	 * >> $like_title = array('OR' => 'id')
	 * >> $same = 'LIKE'
	 * >> array(
	 *	'0' => 'A',
	 *	'1' => 'B',
	 * )
	 * >> $prefix='%+',$postfix='+%'
	 * 
	 * << id LIKE '%A%' OR id LIKE '%B%'
	 * 
	 * @param string $like_title 
	 * @param string $same=LIKE
	 * @param array $array 
	 * @param string $prefix=%+
	 * @param string $postfix=+%
	 * @access public
	 * @return string
	 */
	function getLikeQueryByArray($like_title,$same='LIKE',$array,$prefix='%+',$postfix='+%') {
		$array_count = count($array);
		$where = array();
		$key = $this->getFirstArrayKey($like_title);
		for ( $i = 0; $i < $array_count; $i++ ) {
			$where[] = $like_title[$key].' '.$same.' \''.$prefix.$array[$i].$postfix.'\'';
		}
		return implode($key,$where);
	}
	
	/** getArrayKeyForArrayQuery($query,$key)
	 * en: Set the array key ID.
	 * ru: Установить ключом массива ID.
	 * 
	 * >> array(
	 *	'0' => array('id'=>'77','key'='value'),
	 *	'1' => array('id'=>'55','key'='value'),
	 * )
	 * << array(
	 *	'77' => array('id'=>'77','key'='value'),
	 *	'55' => array('id'=>'55','key'='value'),
	 * )
	 * 
	 * @param array $query 
	 * @param string $key 
	 * @access public
	 * @return array
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
	 * en: Reset For PHPUnitTest.
	 * ru: Сброс после каждого теста.
	 */
	function setResetForTest(){
	}

	/** getFirstArrayKey($array,$get_value=false)
	 * en: Get the first element of the array as a key or value.
	 * ru: Получить первый элемент массива как ключ или значение.
	 * 
	 * @param array $array 
	 * en: An input array.
	 * ru: Массив для обработки.
	 *
	 * @param boolean $get_value = false 
	 * en: Is first value?
	 * ru: Нужно значение массива?
	 * 
	 * @access public
	 * @return string
	 */
	function getFirstArrayKey($array,$get_value=false) {
		$tmp = each($array);
		list($key, $value)=$tmp;
		if (!$get_value){
		/**
		 * en: If you need a key.
		 * ru: Если нужен ключ.
		 */
			return $key;
		}else {
		/**
		 * en: If you want to get the value.
		 * ru: Если нужно получить значение параметра.
		 */
			return $value;
		}
	}
}
?>
