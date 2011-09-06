<?php
/**
 * Модель парсинга данных.
 * @author ev9eniy
 * @version 2.0
 * @package ModelsBitrixAPI
 * @updated 01-sep-2011 9:02:07
 */
class ModelsBitrixInfoBlockParser
{
	/**
	 * Вспомогательная функция - разбор данныx из инфоблока.
	 * 
	 * @param array|object $arProduct 
	 * Основные данные.
	 * @param array|object $arProps 
	 * Свойства.
	 * @access public
	 * @return array
	 */
	function parseAllData ($arProduct, $arProps)
	{
		$full_value = array();
		$full_value['NAME'] = $arProduct->fields['NAME'];
		$full_value['ID'] = $arProduct->fields['ID'];
		$full_value['IBLOCK_ID'] = $arProduct->fields['IBLOCK_ID'];
		foreach ($arProps as $arProps_title => $arProps_value){
			$full_value[$arProps_title] = $arProps_value['VALUE'];
		}
		return $full_value;
	}
}
