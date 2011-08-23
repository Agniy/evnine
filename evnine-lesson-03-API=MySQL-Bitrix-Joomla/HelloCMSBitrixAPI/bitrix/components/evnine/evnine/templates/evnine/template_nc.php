<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// template modifier nocache
echo '#$arResult: <pre>'; if(function_exists(print_r2)) print_r2($arResult); else print_r($arResult);echo "</pre><br />\r\n";
?>

