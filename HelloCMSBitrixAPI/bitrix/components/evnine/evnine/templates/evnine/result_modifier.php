<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/evnine.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/debug/evnine.debug.php');
$evnine = new Controller();

//call bitrix api with evnine controller
$arResult = $evnine->getControllerForParam(
	array(
		'controller' => 'helloworld',
		'method' => 'default',
		'arParams'=>$arParams,
		'ajax' => 'ajax',
	)
);

// saving template name to cache array
$arResult["__TEMPLATE_FOLDER"] = $this->__folder;

// writing new $arResult to cache file
$this->__component->arResult = $arResult; 
?>
