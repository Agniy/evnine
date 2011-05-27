<?php

/**
 * Модель парсинга данных
 * @author ev9eniy
 * @version 1.0
 * @package model_infoblock
 * @updated 08-сен-2010 17:02:07
 */
class ModelsBitrixInfoBlockParser
{

	/**
	*Вспомогательная функция - разбор данныx из инфоблока
	*@return array
	*/
	function parseData ($product, $arProps)
	{
		$full_value = array();
		$full_value['NAME'] = $product->fields['NAME'];
		$full_value['ID'] = $product->fields['ID'];
		$full_value['IBLOCK_ID'] = $product->fields['IBLOCK_ID'];
		
		return $full_value;
	}

	/**
	*Вспомогательная функция - разбор данныx из инфоблока
	*@return array
	*/
	function parseAllData ($product, $arProps)
	{
		$full_value = array();
		$full_value['NAME'] = $product->fields['NAME'];
		$full_value['ID'] = $product->fields['ID'];
		$full_value['IBLOCK_ID'] = $product->fields['IBLOCK_ID'];
		foreach ($arProps as $arProps_title => $arProps_value)
			$full_value[$arProps_title] = $arProps_value['VALUE'];
		return $full_value;
	}

}
