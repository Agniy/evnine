<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("HelloWorld");
$APPLICATION->SetPageProperty("title", "HelloWorld");
$APPLICATION->SetPageProperty("description", "HelloWorld");
$APPLICATION->SetPageProperty("keywords", "HelloWorld");   
?>
<?$APPLICATION->IncludeComponent("evnine:evnine", "evnine", Array(
		'IBLOCK_ID' => '4',
		)
	);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>