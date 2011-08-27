en: How to work with templating Smarty. smarty.net
ru: Пример работы с шаблонизатором Smarty. smarty.php5.com.ua/what.is.smarty

/index.php
require('Smarty/Smarty.class.php');
$smarty = new Smarty;
$smarty->debugging = true;
$smarty->caching = true;
$smarty->cache_lifetime = 120;
$smarty->assign("output",$output);
$smarty->display('form.tpl');