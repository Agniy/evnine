en: An example of load the module with the API in a component for Bitrix.
ru: Пример подключения модуля с API в компоненте для Bitrix. 

en: Entry point.
ru: Точка входа.
/helloworld/index.php
	<?$APPLICATION->IncludeComponent("evnine:evnine", "evnine", Array(
			'IBLOCK_ID' => '',
			'CACHE_TIME'=>'36000',
			)
		);
	?>

en: Unique key Cache.
ru: Ключи уникальности кэша.
/bitrix/components/evnine/evnine/component.php
	$ADDITIONAL_CACHE_ID[] = $arNavParams["PAGEN"];
	$ADDITIONAL_CACHE_ID[] = $arNavParams["SIZEN"];
	
en: Executed only if the cache is outdated.
ru: Исполняются только если устарел кэш.
/bitrix/components/evnine/evnine/templates/evnine/result_modifier.php
/bitrix/components/evnine/evnine/templates/evnine/template.php

en: Executed at every load.
ru: Исполняются при каждой загрузке.
/bitrix/components/evnine/evnine/templates/evnine/result_nc.php
	require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/evnine.php');
	$evnine = new Controller();
	$arResult = $evnine->getControllerForParam(
		array(
			'controller' => 'helloworld',
			'method' => 'default',
			'arParams'=>$arParams,
			'ajax' => 'ajax',
		)
	);

en: Debugging output.
ru: Шаблон. Вывод отладки.
/bitrix/components/evnine/evnine/templates/evnine/template_nc.php
	print_r2($arResult);

en: Get all the items. Uses an internal cache. Trigger IBLOCK_ID.
ru: Вызов метода получения всех эл-тов с учётом внутреннего кэша, 
ru: триггер обновления ставится на IBLOCK_ID.
/bitrix/modules/evnine/classes/general/ModelsHelloWorld.php
class ModelsHelloWorld extends ModelsBitrix 
{
	function getQuery($param) {
		return $this->getFromCacheFunction(
			$param, 
			'getAllElementArrayByID', 
			/*$cache_keys=*/$param['arParams']['IBLOCK_ID']
		);
	}
	
	function getAllElementUserNewAPI($param) {
		return $this->getAllFromAPI(array(..));
	}
}

/bitrix/modules/evnine/classes/general/evnine.config.php
	$this->path_to=$_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/';
	$this->api='ModelsBitrix';
	$this->class_path=array(
		'ModelsBitrix'=>array(
			'path'=>'/models/',
		),
	)

en: Basic model of working with API
ru: Базовая модель работы с API
/bitrix/modules/evnine/classes/general/models/ModelsBitrix.php
/** 
  $param=array(
  '$arOrder'=>array()
  ,'$arFilter'=>array()
  ,'$arGroupBy'=>array()
  ,'$arNavStartParams'=>array()
  ,'$arSelectFields'=>array()
  ,'set_cache'=>true
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
  )
 * 
 * @param array $param 
 * @access public
 * @return array
 */
function getAllFromAPI($param){}


en: Set callback functions to parse data.
ru: Набор callback функций для разбора данных.
/bitrix/modules/evnine/classes/general/models/ModelsBitrixInfoBlockParser.php
	function parseData ($product, $arProps)
	{
		$full_value = array();
		$full_value['NAME'] = $product->fields['NAME'];
		$full_value['ID'] = $product->fields['ID'];
		$full_value['IBLOCK_ID'] = $product->fields['IBLOCK_ID'];
		return $full_value;
	}

en: For debug.
ru: Для отладки.
/php_interface/init.php
	include($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/print_r.php');

/php_interface/print_r.php
