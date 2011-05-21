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
		$full_value['PREVIEW_TEXT'] = $product->fields['PREVIEW_TEXT'];
		return $full_value;
	}
}
