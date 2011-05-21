<?php
/**
 * ModelsBase
 * @package ModelsBase
 * @author ev9eniy
 * @version 1.0
 * @updated 09-apr-2011 18:25:57
 */
include_once 'ModelsBitrixInfoBlockParser.php';
class ModelsBitrix extends ModelsBitrixInfoBlockParser
{

/**
 * After load class init api __construct_api 
 * 
 * @param mixed $param 
 * @access protected
 * @return void
 */
function setInitAPI($param) {
	$this->initCIBlock();
}

function getQuery($query) 
{
	return $this->getQueryFromBitrixAPI($query);
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
 * getQueryFromBitrixAPI
 * 
 * @param query
 */

function getQueryFromBitrixAPI($query) 
{
	global $DB;
	return $DB->Query($query);
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
	return $this->query('set character_set_client=\'utf8\',character_set_connection=\'utf8\', character_set_database=\'utf8\',   character_set_results=\'utf8\',character_set_server=\'utf8\';');
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

/**
 * getFormResult 
 * man: http://dev.1c-bitrix.ru/api_help/form/classes/cformresult/getlist.php
 * 
 * @param mixed $form_id 
 * @param mixed $by
 * @param mixed $order 
 * @param mixed $filter 
 * @param mixed $is_filtered 
 * @access public
 * @return array()
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

/**
 * Получить массив результатов формы
 * 
 * @param int $FORM_ID 
 * @param string $SEARCH 
 * @access public
 * @return array()
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


/**
 * getFormResultAnswer 
 * man http://dev.1c-bitrix.ru/api_help/form/classes/cform/getresultanswerarray.php
 * 
 * @param mixed $form_id 
 * @param string $callback 
 * @param mixed $filter 
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


	/**
	*Вспомогательная функция - добавляем элемент
	*/
	function ElementAdd ($id, $name, $property = array(), $table_date)
	{
		global $USER;
		$section = new CIBlockElement();
		$arLoadProductArray = Array(
									"MODIFIED_BY" => $USER->GetID(),
									//	"IBLOCK_SECTION_ID" => $section_id,
									"IBLOCK_ID" => $id,
									"PROPERTY_VALUES" => $property,
									"NAME" => $name,
									"PREVIEW_TEXT" => "",
									"DETAIL_TEXT" => "",
									"DETAIL_PICTURE" => ""
		);
		if ($PRODUCT_ID = $section->Add(
										$arLoadProductArray))
			$msg['msg'] = '<font color="green">Успешно добавлено</font>';
		else
			$msg['msg'] = '<font color="red">Ошибка добавления ' .
			 $section->LAST_ERROR . '</font>';
		return $msg;
	}

	/**
	*Вспомогательная функция - обновляем элемент
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
			$msg['msg'] = '<font color="green">Успешно сохранено</font>';
		} else
		{
			$msg['msg'] = '<font color="red">Ошибка cохранения</font>';
		}
		return $msg;
	}

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

function getSectionIDBySectionName($IBLOCK_ID, $SECTION_NAME){
		global $DB;
						$res = $DB->Query("
							SELECT ID
							FROM b_iblock_section
							WHERE IBLOCK_ID = ".$IBLOCK_ID."
							AND NAME = '".$DB->ForSQL($SECTION_NAME)."'"
						);
		$SECTION_ID = $res->Fetch();
		return $SECTION_ID['ID'];
	}



	/**
	*Вспомогательная функция - удаляем элемент
	*/
	function ElementDelete ($ELEMENT_ID)
	{
		global $DB;
		$DB->StartTransaction();
		$error = '';
		if (! CIBlockElement::Delete(
									$ELEMENT_ID))
		{
			$error = 'Error!';
			$DB->Rollback();
		} else
			$DB->Commit();
		return $error;
	}

	/**
	*Вспомогательная функция - выборка во ID элемента
	*/
	function getElementNameByID ($id)
	{
		if ($ar_res = CIBlockElement::GetByID(
			$id)->GetNext()
		){
			return $ar_res;
		}
	}

	/**
	 * Вспомогательная функция - выборка свойст во ID элемента
	 * man: http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getproperty.php
	*/
	function getElementPropertyArrayByID ($id, $infoblock,$parent_parser,$arFilter=array())
	{
		$retrun_array = array();
		$item_property = CIBlockElement::GetProperty(
													$infoblock
													,$id
													,array(
														"sort" => "asc"
													)
													,$arFilter
													);
		while ($product = $item_property->GetNext())
		{
			if (empty($parent_parser)){
				$retrun_array[$product['CODE']] = $product['VALUE'];
			}else {
				$retrun_array[$product['CODE']] = $this->$parent_parser($product['CODE'],$product['VALUE']);
			}
		}
		return $retrun_array;
	}

	/** getSectionArrayByID Получить секцию по ID
	 * getSectionArrayByID Получить секцию по ID
	 * 
	 * @param mixed $idsection 
	 * @param mixed $id_parent_section 
	 * @param mixed $callback 
	 * @access public
	 * @return void
	 */
	function getSectionArrayByID ($idsection, $id_parent_section, $callback)
	{

		$productions = CIBlockSection::GetByID($idsection);
		$arr = $productions->Fetch();
		if (isset($arr['IBLOCK_SECTION_ID'])){
			$productions = CIBlockSection::GetByID($arr['IBLOCK_SECTION_ID']);
			$arr = $productions->Fetch();
			if (isset($arr['IBLOCK_SECTION_ID'])&&$arr['IBLOCK_SECTION_ID']===$id_parent_section){
				return true;
			}
		}
		return false;
	}

	/**
	 *Вспомогательная функция - выбор всеx секций в инфоблоке
		*Но главный здесь тормоз $arSelect, попробуйте так:
		*$arSelect = array(
		*'IBLOCK_ID',
		*'ID',
		*'NAME',
		*'PROPERTY_*'
		) 
	*/
	function getAllSectionArrayByID ($idblock, $idsection, $callback, $arFilter, $arSelectFields, $arOrder)
	{
		$array_out = array();
		if (count(
					$arFilter) == 0)
		{
			$arFilter = array(
								"IBLOCK_ID" => $idblock,
								"SECTION_ID" => $idsection
			);
		}
			if (count(
					$arOrder) == 0)
			{
				$arOrder = Array(
					"SORT" => "ASC"
				);
			}

		$productions = CIBlockSection::GetList(
			$arOrder,
			$arFilter,
			false,
			$arSelectFields);
		$count = 0;
		while ($arr = $productions->Fetch())
		{
			$count ++;
			$array_out[$count] = $this->$callback(
				$arr);
		}
		return $array_out;
	}


function getFirstArrayKey($array) {
	$tmp = each($array);
	return $tmp['key'];
	//echo '#$tmp: <pre>'; if(function_exists(print_r2)) print_r2($tmp); else print_r($tmp);echo "</pre><br />\r\n";
	//list($key, $value)=$tmp;
	//echo '#$key: <pre>'; if(function_exists(print_r2)) print_r2($key); else print_r($key);echo "</pre><br />\r\n";
	//return $key;
}

/**
 *Вспомогательная функция - получение всеx элеметов из инфоблока с учётом фильтра
 *man CIBlockElement::GetList
 */
function getAllElementArrayByID ($idblock, $idsection, $callback, $arFilter, $arSelectFields, $data, $key_is_id=false, $arOrder,$arGroupBy=false,$arNavStartParams=false,$get_prop=true)
{
	$arProps=$array_out = array();
	if (count(
		$arFilter) == 0)
	{
		$arFilter = array(
			"IBLOCK_ID" => $idblock,
			"SECTION_ID" => $idsection
		);
	}
	if (count($arOrder) == 0){
			$arOrder = Array(
			"SORT" => "ASC"
			);
	}
	$productions = CIBlockElement::GetList(
			$arOrder,
			$arFilter
			,$arGroupBy
			,$arNavStartParams
			,$arSelectFields
		); 
	//false, //без группировки group by
	//false //без параметров постраничной навигации
	//array()//выборка только необходимых полей (IBLOCK_ID может пригодиться в некоторых особых случаях)
	$count = 0;
	while ($product = $productions->GetNextElement(false,false))
	{
		$count ++;
		if ($get_prop){
			$arProps = $product->GetProperties();
		}
		if ($key_is_id){
			$tmp = $this->$callback(
				$product,
				$arProps,
				$data
			);
			$array_out[$tmp[$key_is_id]]= $tmp;
			unset($array_out[$tmp[$key_is_id]][$key_is_id]);
		}else {
			$array_out[$count] = $this->$callback(
				$product,
				$arProps,
				$data
			);
		}
	}
	return $array_out;
}

/**
 *Вспомогательная функция - инициализация модуля инфоблоков
 */
function initCIBlock ()
{
	if (! class_exists(
		"CIBlockSection"))
		CModule::IncludeModule(
			"iblock");
}


/**
 *Вспомогательная функция - инициализация модуля форм
 */
function initCForm()
{
	if (!class_exists('CForm')){
		CModule::IncludeModule("form");
	}
}


/**
 *Вспомогательная функция - для пересохранения файла и вывода данных по нему данных
 */
function getIMGbyIDToSave ($id)
{
	return CFile::MakeFileArray(
		$id);
}

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

	function getStrlenUTF8($str){
		if (function_exists('mb_strlen')) 
			return mb_strlen($str, 'utf-8');
		return strlen(utf8_decode($str));
	}


}
?>