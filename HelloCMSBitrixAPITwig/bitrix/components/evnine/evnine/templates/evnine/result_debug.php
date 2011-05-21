<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/debug/evnine.debug.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/debug/evnine.views.generator.template.php');
echo '#$arResult: <pre>'; if(function_exists(print_r2)) print_r2($arResult); else print_r($arResult);echo "</pre><br />\r\n";
echo $twig_generator= 
	getTemplateFromArray(
		$arResult,
		$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'twig')
	);
?>
