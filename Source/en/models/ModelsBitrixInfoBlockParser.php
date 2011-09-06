<?php
/**
 * Model of parsing the data.
 * @author ev9eniy
 * @version 2.0
 * @package ModelsBitrixAPI
 * @updated 01-sep-2011 9:02:07
 */
class ModelsBitrixInfoBlockParser
{
	/**
	 * Helper function - an analysis of data from the information block.
	 * 
	 * @param array|object $arProduct 
	 * Basic data.
	 * @param array|object $arProps 
	 * Properties.
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
