<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// template modifier nocache
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/libs/Twig/Autoloader.php');
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/views/');
$twig = new Twig_Environment($loader, array(
			'cache' => $_SERVER["DOCUMENT_ROOT"].$arParams['CACHE_PATH'].'/',
			//'cache'=>false,
			//'auto_reload' => false,
			'auto_reload' => true,
			//'charset' => 'UTF-8',
			'debug' => true,
			'trim_blocks'=>false,
		)
);
echo $twig->loadTemplate('bitrix.tpl')->render($arResult);
?>
