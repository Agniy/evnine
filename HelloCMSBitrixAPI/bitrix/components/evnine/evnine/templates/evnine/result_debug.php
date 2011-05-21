<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/evnine/classes/general/debug/evnine.debug.php');
echo '#$arResult: <pre>'; if(function_exists(print_r2)) print_r2($arResult); else print_r($arResult);echo "</pre><br />\r\n";
?>
