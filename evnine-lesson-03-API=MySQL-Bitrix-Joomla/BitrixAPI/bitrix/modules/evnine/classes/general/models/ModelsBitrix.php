<?php

/**
 * en: A class to parse the data from the information block.
 * ru: Класс для разбора данных из инфоблока.
 *  
 * class ModelsBitrixInfoBlockParser{
 *	function parseAllData ($product, $arProps)
 *	{
 *		$full_value = array();
 *		$full_value['NAME'] = $product->fields['NAME'];
 *		$full_value['ID'] = $product->fields['ID'];
 *		foreach ($arProps as $arProps_title => $arProps_value)
 *			$full_value[$arProps_title] = $arProps_value['VALUE'];
 *		return $full_value;
 *	}
 * }
 */
if (!class_exists('ModelsBitrixInfoBlockParser')){
	include_once 'ModelsBitrixInfoBlockParser.php';
}else {
	class ModelsBitrixInfoBlockParser{} 
}

/**
 * en: Model of working with API Bitrix.
 * en: The basic method for extracting data getAllFromAPI.
 * ru: Модель работы с API Bitrix. 
 * ru: Базовый метод для извлечения данных getAllFromAPI.
 * 
 * @link ModelsBitrixAPI.getAllFromAPI
 * @see ModelsBitrixAPI.parseAllData
 * @package ModelsBitrixAPI
 * @uses ModelsBitrixInfoBlockParser
 * @author ev9eniy
 * @version 4
 * @updated 02-sep-2011 18:00:57
 */
class ModelsBitrix extends ModelsBitrixInfoBlockParser
{

	/** __construct.
	 * en: The class constructor.
	 * ru: Конструктор класса.
	 * 
	 * @param array $param 
	 * @access protected
	 * @return void
	 */
	function setInitAPI($param) {
		$this->initCIBlock();
	}
	
	/** initCIBlock()
	 * en: Initialize the module iblock. 
	 * ru: Инициализация модуля инфоблоков.
	 * 
	 * @access public
	 * @return void
	 */
	function initCIBlock ()
	{
		if (!class_exists("CIBlockSection")){
			CModule::IncludeModule("iblock");
		}
	}

	/**
	 * en: Initialize the module forms.
	 * ru: Инициализация модуля форм.
	 * 
	 * @access public
	 * @return void
	 */
	function initCForm()
	{
		if (!class_exists('CForm')){
			CModule::IncludeModule("form");
		}
	}
	
	/** getFromCacheFunction
	 *
	 * en: Getting data from the cache with setting triggers.
	 * ru: Получение данных из кэша с установкой триггеров.
	 * 
	 * @param array $param 
	 * ru: Основной массив параметров.
	 * @param string $function_callback 
	 * ru: Функция для обратного вызова
	 * @param string $cache_key
	 * ru: Ключ для кэша 
	 * @access public
	 * @return array
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

	/** getAllFromAPI($param)
	 * en: A universal method for get data from the API.
	 * ru: Универсальный метод для получения данных из API
	 * 
	 * @param array  
	 * $param = array(
	 *  '$arOrder'=>array()
	 * ,'$arFilter'=>array()
	 * ,'$arGroupBy'=>array()
	 *  ,'$arNavStartParams'=>array()
	 *  ,'$arSelectFields'=>array()
	 * ,'set_cache'=>true
	 *    // ru: Триггер очистки кэша по IBLOCK_ID
	 *    // ru: обязательно указать $param['$arFilter']['IBLOCK_ID']
	 * ,'set_parser'=>''
	 *    // ru: Callback функция для обработки данных, 
	 *    // ru: задаётся в models/ModelsBitrixInfoBlockParser.php 
	 * ,'get_section'=>false
	 *    // ru: Получить данные секций, по умолчанию берутся эл-ты
	 * ,'get_sef'=>''
	 *    // Нуru: жен ли метод для форматирования URL?
	 *    // get_sef=true GetNextElement(false,false)
	 *    // get_sef=false Fetch(false,false)
	 * ,'set_sef'=>''
	 *    // ru: Использовать свой адрес ЧПУ 
	 *    // 'set_sef'=>#SECTION_ID#/#ELEMENT_ID#
	 *    // SetUrlTemplates("", $param['set_sef']);
	 * ,'get_prop'=>false
	 *    // ru: Получить свойства эл-тов, 
	 *    // ru: если указана секция, то 'get_prop'=false
	 * ,'set_key_id'=>false
	 *    // ru: Какой использовать ключ для формирования массива
	 *    // ru: Пример: На входе: 
	 *    // >> 'set_key_id'=>'ID',
	 *    // ru: На выходе:
	 *    // << array(
	 *    //  555=>array('ID'='555',array(..))
	 *    //  777=>array('ID'='7',array(..))
	 *    // )
	 * ,'get_first'=>false
	 *    // ru: Вернуть первый эл-т массива без ключа
	 *    // ru: По умолчанию, на входе:
	 *    // >> array(
	 *    //  '0'=>array('ID'='555',array(..)),
	 *    //  '1'=>array('ID'='777',array(..))
	 *    // )
	 *    // ru: C ключом ,
	 *    // >> 'get_first'=>true
	 *    // ru: на выходе:
	 *    // << array('ID'='555',array(..))
	 * )
	 * 
	 * @access public
	 * @return array
	 */
	function getAllFromAPI($param){
		$array_out=array();
		if ($param['set_cache']){
			global $CACHE_MANAGER;
			$CACHE_MANAGER->RegisterTag("iblock_id_".$param['$arFilter']['IBLOCK_ID']);
		}
		if ($param['set_parser']){
			$callback= $param['set_parser'];
		}else {
			$callback=false;
		}
		if ($param['get_section']){
			$items=CIBlockSection::GetList(
			$arOrder=$param['$arOrder']
			,$arFilter=$param['$arFilter']
			,$arNavStartParams=$param['$arNavStartParams']
			//,$arSelectFields=$param['$arSelectFields']
		);
		}else {
			$items=CIBlockElement::GetList(
			$arOrder=$param['$arOrder']
			,$arFilter=$param['$arFilter']
			,$arGroupBy=$param['$arGroupBy']
			,$arNavStartParams=$param['$arNavStartParams']
			,$arSelectFields=$param['$arSelectFields']
		);
		}
		if ($param['set_sef']){
			$items->SetUrlTemplates("", $param['set_sef']);
		}
		$count = 0;
		if ($param['get_prop']||$param['get_sef']){
			while ($item = $items->GetNextElement(false,false))
			{
				if ($param['get_prop']&&!$param['get_section']){
					$props = $item->GetProperties(false,false);
				}else {
					$props=array();
				}
				if ($callback){
					$tmp = $this->$callback(
						$item,
						$props,
						$param
					);
				}else {
					if ($param['get_prop']) {
						$item['props']=$props;
					}
					$tmp = $item;
				}
				if ($param['set_key_id']){
					$array_out[$tmp[$param['set_key_id']]]= $tmp;
					unset($array_out[$tmp[$param['set_key_id']]][$param['set_key_id']]);
				}else {
					$array_out[$count] = $tmp;
				}
				$count++;
			}
		}else {
			while ($item = $items->Fetch(false,false))
			{
				if ($callback){
					$tmp = $this->$callback(
						$item,
						$props,
						$param
					);
				}else {
					if ($param['get_prop']) {
						$item['props']=$props;
					}
					$tmp = $item;
				}
				if ($param['set_key_id']){
					$array_out[$tmp[$param['set_key_id']]]= $tmp;
					unset($array_out[$tmp[$param['set_key_id']]][$param['set_key_id']]);
				}else {
					$array_out[$count] = $tmp;
				}
				$count++;
			}
		}
		if ($param['get_first']){
			return $array_out['0'];
		}
		return $array_out;
	}

	/** getQuery($query)
	 * en: Get data from Database by API.
	 * ru: Получение данных из API через SQL запрос.
	 * 
	 * @param string $query 
	 * @access public
	 * @return array
	 */
	function getQuery($query) 
	{
		return $this->getQueryFromBitrixAPI($query);
	}
	
	/** getQueryFromBitrixAPI($query) 
	 * en: Get the answer from the database using the API.
	 * ru: Получить ответ из базы используя API.
	 * 
	 * @param string $query 
	 * @access public
	 * @return array
	 */
	function getQueryFromBitrixAPI($query) 
	{
		global $DB;
		$results=$DB->Query($query);
		$array_out= array();
		while ($row = $results->Fetch())
			array_push($array_out, $row);
		return $array_out;
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
	 * en: Get the array response, or false, if the answer is no.
	 * ru: Получить массив ответа или false, если ответа нет.
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
	 * @param string $case
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
	
	/** getLikeQueryByArray($like_title,$array,$prefix='%+',$postfix='+%')
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
	 * @param array $array 
	 * @param string $prefix 
	 * @param string $postfix 
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
	 * en: Set the array value as key for an array.
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
	
	/** getFormResult($form_id,$callback='',$by,$order,$filter,$is_filtered){
	 * en: Get form results.
	 * ru: Получить результаты формы.
	 * 
	 * @link http://dev.1c-bitrix.ru/api_help/form/classes/cformresult/getlist.php 
	 * @param int $form_id 
	 * @param array $by
	 * @param array $order 
	 * @param array $filter 
	 * @param array $is_filtered 
	 * @access public
	 * @return array
	 */
	function getFormResult($form_id,$callback='',$by,$order,$filter,$is_filtered){
		$this->initCForm();
		$array_out=array();
		$rsResults=CFormResult::GetList(
			$form_id
			,$by
			,$order
			,$filter
			,$is_filtered
		);
		$count=0;
		while ($arResult = $rsResults->Fetch()){
			$count ++;
			if ($callback){
				$array_out[$count] = $this->$callback($arResult);
			}else {
				$array_out[$count] = $arResult;
			}
		}
		return $array_out;
	}
	
	/** getFormResultIDArrayForValue($FORM_ID, $SEARCH)
	 * en: Get an array of results forms.
	 * ru: Получить массив результатов формы.
	 * 
	 * @param int $FORM_ID 
	 * @param string $SEARCH 
	 * @access public
	 * @return array
	 */
		function getFormResultIDArrayForValue($FORM_ID, $SEARCH){
			global $DB;
			$rsResults = $DB->Query("
				SELECT 
				RESULT_ID 
				FROM b_form_result_answer
				WHERE FORM_ID = ".$FORM_ID."
				AND USER_TEXT_SEARCH = '".$DB->ForSQL($SEARCH)."'"
			);
			$count=0;
			$array_out=array();
			while ($arResult = $rsResults->Fetch()){
				$count++;
				$array_out[$count] = $arResult['RESULT_ID'];
			}
			return $array_out;
		}
	
	/** getFormResultAnswer($form_id,$callback='',$filter,$only_one_result=true){
	 * en: Get answers to questions of form.
	 * ru: Получить ответы на вопросы формы.
	 * 
	 * @link http://dev.1c-bitrix.ru/api_help/form/classes/cform/getresultanswerarray.php
	 * @param int $form_id 
	 * @param string $callback 
	 * @param array $filter 
	 * @param boolean $only_one_result
	 * @access public
	 * @return void
	 */
	function getFormResultAnswer($form_id,$callback='',$filter,$only_one_result=true){
		$this->initCForm();
		$array_out=array();
		CForm::GetResultAnswerArray(
			$form_id
			,$columns
			,$answers
			,$answers2
			,$filter
		);
		if ($only_one_result){
			return $answers2[$filter['RESULT_ID']];
		}else {
			return $answers2;
		}
	}
	
	/** ElementAdd ($id, $name, $property = array())
	 * en: Add the element to iblock.  
	 * ru: Добавляем элемент в инфоблок.
	 * 
	 * @param int $id 
	 * @param string $name 
	 * @param array $property 
	 * @access public
	 * @return string
	 */
	function ElementAdd ($id, $name, $property = array())
	{
		global $USER;
		$section = new CIBlockElement();
		$arLoadProductArray = Array(
			"MODIFIED_BY" => $USER->GetID(),
		"IBLOCK_ID" => $id,
		"PROPERTY_VALUES" => $property,
		"NAME" => $name,
		"PREVIEW_TEXT" => "",
		"DETAIL_TEXT" => "",
		"DETAIL_PICTURE" => ""
	);
		if ($PRODUCT_ID = $section->Add(
			$arLoadProductArray))
			$msg = 'OK';
		else
			$msg = $section->LAST_ERROR;
		return $msg;
	}
	
	
	
	/** ElementDelete($id)
	 * en: Delete the item.
	 * ru: Удаляем элемент.
	 * 
	 * @param int $id 
	 * @access public
	 * @return string
	 */
	function ElementDelete ($id)
	{
		global $DB;
		$DB->StartTransaction();
		$error = '';
		if (! CIBlockElement::Delete(
			$id))
		{
			$error = 'Error!';
			$DB->Rollback();
		} else
			$DB->Commit();
		return $error;
	}
	
	/** ElementUpdate ($id, $property)
	 * en: Update the element.
	 * ru: Обновляем элемент.
	 * 
	 * @param int $id 
	 * @param array $property 
	 * @access public
	 * @return string
	 */
	function ElementUpdate ($id, $property)
	{
		global $USER;
		$arLoadProductArray = array();
		$section = new CIBlockElement();
		if ($property['NAME'] != '')
			$arLoadProductArray['NAME'] = $property['NAME'];
		$arLoadProductArray['MODIFIED_BY'] = $USER->GetID();
		$arLoadProductArray['PROPERTY_VALUES'] = $property;
		$res = $section->Update(
			$id,
			$arLoadProductArray);
		if ($res == 1)
		{
			$msg = 'OK';
		} else
		{
			$msg = $section->LAST_ERROR;
		}
		return $msg;
	}
	
	/** getSectionIDBySectionCode($IBLOCK_ID, $SECTION_CODE)
	 * en: Get the section ID by the code.
	 * ru: Получить ID секции по коду.
	 * 
	 * @param int $IBLOCK_ID 
	 * @param string $SECTION_CODE 
	 * @access public
	 * @return int
	 */
	function getSectionIDBySectionCode($IBLOCK_ID, $SECTION_CODE){
		global $DB;
		$res = $DB->Query("
			SELECT ID
			FROM b_iblock_section
			WHERE IBLOCK_ID = ".$IBLOCK_ID."
			AND CODE = '".$DB->ForSQL($SECTION_CODE)."'"
		);
		$SECTION_ID = $res->Fetch();
		return $SECTION_ID['ID'];
	}

	/** getAllSectionArrayByID()
	 * en: Select all the sections in the iblock by ID.
	 * ru: Выбор всех секций в инфоблоке по ID.
	 * 
	 * @deprecated
	 * en: The method will be removed due to the addition method getAllFromAPI.
	 * ru: Метод будет удален в связи с добавлением метода getAllFromAPI.
	 * @see ModelsBitrixAPI.getAllFromAPI
	 * @return array
	 */
	function getAllSectionArrayByID (
		$idblock, 
		$idsection, 
		$callback, 
		$arFilter, 
		$arSelectFields, 
		$arOrder, 
		$key_is_id=false)
	{
		return $this->getAllFromAPI(
			array(
				'$arOrder'=>$arOrde
				,'$arFilter'=>$arFilter
				,'$arSelectFields'=>$arSelectFields
				,'set_cache'=>$set_cache
				,'set_parser'=>$callback
				,'get_section'=>true
				,'set_key_id'=>$key_is_id
			)
		);
	}

	/** getAllElementArrayByID()
	 * en: Get all the items from the iblock by ID. 
	 * ru: Получить все элементы по ID инфоблока.
	 * 
	 * @deprecated
	 * en: The method will be removed due to the addition method getAllFromAPI.
	 * ru: Метод будет удален в связи с добавлением метода getAllFromAPI.
	 * @see ModelsBitrixAPI.getAllFromAPI
	 * @return array
	 */
	function getAllElementArrayByID (
		$idblock
		,$idsection
		,$callback
		,$arFilter
		,$arSelectFields
		,$data
		,$key_is_id=false
		,$arOrde
		,$arGroupBy=false
		,$arNavStartParams=false
		,$get_prop=true
		,$get_first_array_key=false
		,$set_cache=false
		,$set_url_templates=false
	)
	{
		return $this->getAllFromAPI(
			array(
				'$arOrder'=>$arOrde
				,'$arFilter'=>$arFilter
				,'$arGroupBy'=>$arGroupBy
				,'$arNavStartParams'=>$arNavStartParams
				,'$arSelectFields'=>$arSelectFields
				,'set_cache'=>$set_cache
				,'set_parser'=>$callback
				,'get_section'=>false
				,'set_sef'=>$set_url_templates
				,'get_prop'=>$get_prop
				,'set_key_id'=>$key_is_id
				,'get_first'=>$get_first_array_key,
			)
		);
	}
	
	/** getFirstArrayKey($array,$get_value=false)
	 * 
	 * en: Get the first element of the array as a key or value.
	 * ru: Получить первый элемент массива как ключ или значение.
	 * 
	 * @param array $array 
	 * en: An input array.
	 * ru: Массив для обработки.
	 *
	 * @param boolean $get_value 
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
	
	/** getIMGbyIDToSave ($id)
	 * en: Re save the file with info.
	 * ru: Повторно сохранить файл с выводом данных о нём.
	 * 
	 * @param int $id 
	 * @access public
	 * @return array
	 */
	function getIMGbyIDToSave ($id)
	{
		return CFile::MakeFileArray($id);
	}
	
	/** getIMGbyFileIDToSRC($id)
	 * en: Get the file URL by ID.
	 * ru: Получить адреса файлов по ID.
	 * 
	 * @param array $id 
	 * @access public
	 * @return array
	 */
	function getIMGbyFileIDToSRC($id){
		if (is_array($id)){
			foreach ($id as $id_title => $id_value){
				$file_tmp = CFile::GetFileArray($id_value);
				$file[$id_title]=$file_tmp['SRC'];
			}
		}else {
			$file_tmp = CFile::GetFileArray($id);
			$file=$file_tmp['SRC'];
		}
		return $file;
	}
	
	/** getStrlenUTF8($str)
	 * en: Get the length of the UTF8 string.
	 * ru: Получить длину UTF8 строки.
	 * 
	 * @param mixed $str 
	 * @access public
	 * @return void
	 */
	function getStrlenUTF8($str){
		if (function_exists('mb_strlen')) 
			return mb_strlen($str, 'utf-8');
		return strlen(utf8_decode($str));
	}
}
?>
