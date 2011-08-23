<?
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
?>
<?$APPLICATION->IncludeComponent("evnine:evnine", "evnine", Array(
		'IBLOCK_ID' => '',
		'CACHE_TIME'=>'3600',
	//'CACHE_TIME'=>'0',
		)
	);
?>
