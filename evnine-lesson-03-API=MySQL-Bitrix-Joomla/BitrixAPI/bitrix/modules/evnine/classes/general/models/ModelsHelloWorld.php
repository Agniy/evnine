<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ModelsHelloWorld extends ModelsBitrix
{	
	var $api;
	function __construct($api){
		$this->api=$api;
	}

	/** 
	 * ru: Выполнить запрос через API и с использованием кэша.
	 *  
	 * @param mixed $param 
	 * @access public
	 * @return array
	 */
	function getQuery($param) {
		return $this->getFromCacheFunction($param, 'getAllElementArrayByID', /*$cache_keys=*/$param['arParams']['IBLOCK_ID']);
	}

	/**
	 * ru: Новый метод получения данных через API
	 * 
	 * @param array $param 
	 * @access public
	 * @return array
	 */
	function getAllElementUserNewAPI($param) {
	return $this->getAllFromAPI(
	array(
  '$arOrder'=>array()
  ,'$arFilter'=>array()
  ,'$arGroupBy'=>array()
  ,'$arNavStartParams'=>array()
  ,'$arSelectFields'=>array()
  ,'set_cache'=>false
     // Триггер очистки кэша по IBLOCK_ID
     // обязательно указать $param['$arFilter']['IBLOCK_ID']
  ,'set_parser'=>''
     // Callback функция для обработки данных, 
     // задаётся в models/ModelsBitrixInfoBlockParser.php 
  ,'get_section'=>false
     // Получить данные секций, по умолчанию берутся эл-ты
  ,'get_sef'=>''
     // Нужен ли метод для форматирования URL?
     // get_sef=true GetNextElement(false,false)
     // get_sef=false Fetch(false,false)
  ,'set_sef'=>''
     // Использовать свой адрес ЧПУ 
     // 'set_sef'=>#SECTION_ID#/#ELEMENT_ID#
     // SetUrlTemplates("", $param['set_sef']);
  ,'get_prop'=>false
     // Получить свойства эл-тов, 
     // если указана секция, то 'get_prop'=false
  ,'set_key_id'=>false
     // Какой использовать ключ для формирования массива
     // Пример: На входе: 'set_key_id'=>'ID',
     // На выходе: 
     // array(
     //  555=>array('ID'='555',array(..))
     //  777=>array('ID'='7',array(..))
     // )
  ,'get_first'=>false
     // Вернуть первый эл-т массива без ключа
     // По умолчанию, на выходе:
     // array(
     //  '0'=>array('ID'='555',array(..))
     // )
     // C ключом ,'get_first'=>true,
     // на выходе:
     // array('ID'='555',array(..))
  ));
	}

	/** getAllElementArrayByID
	 *
	 * ru: Старая версия функции получения данных.
	 * 
	 * @param array $param 
	 * @access public
	 * @return array
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

	/**
	 * en: Reset any data
	 * ru: Сбросить все данные
	 */
	function setResetForTest($param){
	}
}
?>