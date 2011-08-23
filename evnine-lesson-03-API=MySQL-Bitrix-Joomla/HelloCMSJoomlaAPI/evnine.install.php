<?php
defined('_JEXEC') or die( 'Restricted access' );
error_reporting(0);
$component_name='com_evnine';
$user_name='apache';

/**
 * en: Gets path to component
 * ru: Получим путь к контроллеру
 */
if (defined( 'JPATH_ROOT' )){
	$path_to_controller = JPATH_ROOT .DS.'components'.DS.$component_name.DS;
}else {
	$path_to_controller=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$component_name.DIRECTORY_SEPARATOR;
}


/** chmod_R 
 * en: Set chown and chmod for controller
 * ru: Установить владельца для доступа и права доступа
 * 
 * @param mixed $path 
 * @param mixed $perm 
 * @param mixed $owner 
 * @access public
 * @return void
 */
function chmod_R($path, $perm, $owner) {
	$handle = opendir($path);
	while ( false !== ($file = readdir($handle)) ) {
		if ( ($file !== "..") ) {
			@chmod($path . "/" . $file, $perm);
			@chown($path . "/" . $file, $owner);
			if ( !is_file($path."/".$file) && ($file !== ".") )
			chmod_R($path . "/" . $file, $perm, $owner);
		}
	}
	closedir($handle);
}
//chmod_R($path_to_controller, 0777,$user_name);

require_once( $path_to_controller.'evnine.controller.php' );
$controller_evnine = new Controller();
/**
 * en: For ecach model create table. 
 * ru: Для каждой модели попробуем сделать установку таблицы
 */
foreach ($controller_evnine->class_path as $controller_evnine_title =>$controller_evnine_value)if ($controller_evnine_title!='ModelsDatabase'){
	$model_path_file = $path_to_controller.$controller_evnine_value['path'].DIRECTORY_SEPARATOR.$controller_evnine_title.'.php';
	if (file_exists($model_path_file)){
		include_once($model_path_file);
		$reset_db_class=new $controller_evnine_title($controller_evnine->loaded_class[$controller_evnine->api]);
		/**
		 * en: If method create table is exists
		 * ru: Если метод существует создадим таблицу
		*/
			if (method_exists($reset_db_class,'setCreateTableClass')){
			echo '<br />'.$controller_evnine_title;
			echo ': Create evnine tables - OK';
			$reset_db_class->setCreateTableClass();
		}
	}
}
/**
	* en: Set component as frontend
	* ru: Установим компонент на главную
 */
if (defined( '_JEXEC' )){
	$db =& JFactory::getDBO();	
	$query = 'UPDATE `#__menu` SET `home`=0;';
	$db->setQuery($query);
	$db->query();
	$query = 'UPDATE `#__menu` SET `published`=0 WHERE `alias`=\'home\';';
	$db->setQuery($query);
	$db->query();
	$query = 'INSERT INTO `#__menu` (`menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`) VALUES (\'mainmenu\', \'Evnine frontend\', \'evnine\', \'index.php?option='.$com_sa.'\', \'component\', 1, 0, 0, 0, 2, 62, \'2011-11-11 16:13:24\', 0, 0, 0, 0, \'menu_image=-1\n\n\', 0, 0, 1);';
	$db->setQuery($query);
	$db->query();
}
require_once( $path_to_controller.'evnine.demodata.php' );
?>
