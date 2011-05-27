<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (isset($arParams["COMPONENT_ENABLE"]) && $arParams["COMPONENT_ENABLE"] === false)
	return;

// Режим разработки под админом
$bDesignMode = $GLOBALS["APPLICATION"]->GetShowIncludeAreas() && is_object($GLOBALS["USER"]) && $GLOBALS["USER"]->IsAdmin();

// RSS
if (!$bDesignMode && $arParams["IS_RSS"] == "Y")
{
	$APPLICATION->RestartBuffer();
	header("Content-Type: text/xml; charset=".LANG_CHARSET);
	header("Pragma: no-cache");
}

$arNavParams = CDBResult::GetNavParams();

// Дополнительно кешируем текущую страницу
$ADDITIONAL_CACHE_ID[] = $arNavParams["PAGEN"];
$ADDITIONAL_CACHE_ID[] = $arNavParams["SIZEN"];

$arParams['CACHE_PATH'] = $CACHE_PATH = "/".SITE_ID."/".LANGUAGE_ID.$this->__relativePath;

// Подключается файл result-modifier.php
if($this->StartResultCache($arParams["CACHE_TIME"], $ADDITIONAL_CACHE_ID, $CACHE_PATH)) 
{
	if($arParams["IS_RSS"] == "Y" && $bDesignMode)
	{
		ob_start();
		$this->IncludeComponentTemplate();
		$contents = ob_get_contents();
		ob_end_clean();
		echo "<pre>",htmlspecialchars($contents),"</pre>";
	}
	else
		$this->IncludeComponentTemplate();
}

// RSS
if (!$bDesignMode && $arParams["IS_RSS"] == "Y")
{
	$r = $APPLICATION->EndBufferContentMan();
	echo $r;
	if(defined("HTML_PAGES_FILE") && !defined("ERROR_404")) CHTMLPagesCache::writeFile(HTML_PAGES_FILE, $r);
	die();
}

// Подключаем файл без кеширования
$modifier_path = $_SERVER["DOCUMENT_ROOT"].$arResult["__TEMPLATE_FOLDER"]."/result_nc.php";
$nocahe_template_path = $_SERVER["DOCUMENT_ROOT"].$arResult["__TEMPLATE_FOLDER"]."/template_nc.php";
if (file_exists($modifier_path))
{
	require_once($modifier_path);
	$mod_name = "result_modifier_nc.php";
}


// Подключаем шаблон без кеширования
if (file_exists($nocahe_template_path))
{
	require_once($nocahe_template_path);
}

// Возвращаемое значение
if (!empty($arResult["__RETURN_VALUE"]))
	return $arResult["__RETURN_VALUE"];
?>
