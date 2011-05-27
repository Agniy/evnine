<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 04-apr-2010 11:03:41
 */
class ModelsHelloWorld
{	

	var $api;

	/**
	 * Constructor
	 */
	function __construct($api){
		$this->api=$api;//save api (Bitrix link to class)
	}

	/** getQuery 
	 * Пример запроса функцию через кэш с установкой в апи триггеров на удаления кэша
	 * 
	 * @param mixed $param 
	 * @access public
	 * @return void
	 */
	function getQuery($param) {
		return $this->getFromCacheFunction($param, 'getAllElementArrayByID', /*$cache_keys=*/$param['arParams']['IBLOCK_ID']);
	}

	/** getFromCacheFunction
	 * Получим данные из кэша и установим триггеры
	 * 
	 * @param mixed $param 
	 * @param mixed $function_callback 
	 * @access public
	 * @return void
	 */
	function getFromCacheFunction($param,$function_callback,$cache_key='') {
		$cache = new CPHPCache;
		$cache_dir= $param['arParams']['CACHE_PATH'].'/'.$function_callback;
		if($cache->InitCache($param['arParams']["CACHE_TIME"], $function_callback.$cache_key, $cache_dir))
		{
			if ($param['isPHPUnitDebug']){
				$array=$cache->GetVars();
				$array['#cache']=$function_callback;
				return $array;
			}else {
				return $cache->GetVars();
			}
		} else {
			global $CACHE_MANAGER;
			$cache->StartDataCache($param['arParams']["CACHE_TIME"],$function_callback.$cache_key, $cache_dir);
				$CACHE_MANAGER->StartTagCache($cache_dir);
				$array = $this->$function_callback($param);
				$CACHE_MANAGER->RegisterTag("iblock_id_new");
				$CACHE_MANAGER->EndTagCache();
			$cache->EndDataCache($array);
			return $array;	
		}
	}

	/** getAllElementArrayByID
	 * Функция получения данных
	 * 
	 * @param mixed $param 
	 * @access public
	 * @return void
	 */
	function getAllElementArrayByID($param) {
		return $this->api->getAllElementArrayByID(
			$id=$param['arParams']['IBLOCK_ID']
			,$idsection=''
			,$callback='parseData'
			,$arFilter=array()
			,$arSelect = array( 'IBLOCK_ID','ID','NAME')
			,$data=''
			,$key_is_id=false
			,$arOrder=array(
				'NAME' => 'ASC',
			)
			,$arGroupBy=false
			,array('nPageSize' => '10')
			,$get_prop=false
			,false/*first as array*/
			,$set_cache=true
		);
	}

	function setResetForTest($param){//Reset any data
		//echo 'setResetForTest<br />';
	}
}
?>