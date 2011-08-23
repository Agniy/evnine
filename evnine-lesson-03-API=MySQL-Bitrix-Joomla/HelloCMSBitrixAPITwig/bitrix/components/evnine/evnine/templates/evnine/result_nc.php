<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/evnine.php');
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
echo '#$arResult: <pre>'; if(function_exists(print_r2)) print_r2($arResult); else print_r($arResult);echo "</pre><br />\r\n";
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/debug/evnine.views.generator.template.php');
echo $twig_generator= 
	getTemplateFromArray(
		$arResult,
		$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'twig')
	);
?>
