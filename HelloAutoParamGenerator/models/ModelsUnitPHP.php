<?php
/**
 * Тестирование классов
 *
 * @package ModelsBase
 * @author ev9eniy
 * @version 1.0
 * @updated 30-сен-2010 23:37:19
 */
class ModelsUnitPHP
{
	/**
	 * Получить данные
	 * 
 	 * @assert ($param) == $this->object->getDataForTest('getSerData',$param=array('test'=>'test'))
	 * @param file_name
	 */
	function getSerData($file_name) 
	{
		$file_name = split('::',$file_name);
		$file_dir = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components/com_sa/phpunit_test_data/'.$file_name[0];
		$file_name = $file_dir.DIRECTORY_SEPARATOR.$file_name[0].'_'.$file_name[1];
		$file_name=$file_name.'.test';
		if (!file_exists($file_dir))
			return '';
		return unserialize(file_get_contents($file_name));
	}

	/**
	 * Сохранить данные
	 * 
 	 * @assert ($param) == $this->object->getDataForTest('setSerData',$param=array('test'=>'test'))
	 * @param file_name
	 * @param str
	 */
	function setSerData($file_name, $str) 
	{		
		$file_name = split('::',$file_name);
		$file_dir = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components/com_sa/phpunit_test_data/'.$file_name[0];
		$file_name = $file_dir.DIRECTORY_SEPARATOR.$file_name[0].'_'.$file_name[1];
		if (!file_exists($file_dir))
			mkdir($file_dir, 0777);
		if (!file_exists($file_dir)){
			echo 'Error ModelsUnitPHP.php, please create testdata'."\r\n";
			echo '#$file_dir: '.$file_dir."<br />\r\n";
		}
		$file_name=$file_name.'.test';
		if (file_exists($file_name))
				return $str;
		$save = fopen($file_name,'wb');
		$sertmp = serialize($str);
		fputs($save,serialize($str));
		fclose($save);
		return $str;
	}
}
?>
