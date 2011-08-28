<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 01-oct-2010 22:03:41
 */
include_once('ViewsUnitPHPExtend.php');
class ViewsUnitPHP extends ViewsUnitPHPExtend
{
	var $twig=false;

	/**
	 * Инициализируем шаблонизатор
	 * 
	 * @access protected
	 * @return void
	 */
	function __construct() {
		if (file_exists($_SERVER["DOCUMENT_ROOT"].'/HelloAutoParamGenerator/Twig/Autoloader.php')){
			require_once($_SERVER["DOCUMENT_ROOT"].'/HelloAutoParamGenerator/Twig/Autoloader.php');
			Twig_Autoloader::register();
			$loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"].'/HelloAutoParamGenerator/views/');
			$this->twig = new Twig_Environment($loader, array(
					'cache' => $_SERVER["DOCUMENT_ROOT"].'/HelloAutoParamGenerator/views/cache/',
					'auto_reload' => true,
					//'charset' => 'UTF-8',
					'debug' => true,
					'trim_blocks'=>true,
				)
			);
		}
	}

	/** getViews($param)
	 * 
	 * Показать шаблон
	 * 
	 * @param mixed $param 
	 * @access public
	 * @return void
	 */
	function getViews($param) {
		if ($this->twig){
			echo $this->twig->loadTemplate('twig.tpl')->render($param);
		}
	}

	/**
	 * CodeHighlighter 
	 * Отобразить форматированный код
	 * http://qbnz.com/highlighter/
	 * 
	 * @param mixed $html 
	 * @param mixed $code 
	 * @param mixed $html_old 
	 * @access public
	 * @return void
	 */
	function CodeHighlighter($html) {
		include_once('geshi.php');
		$geshi = new GeSHi($html, 'html4strict');
		$geshi->set_header_type(GESHI_HEADER_DIV);
		echo $geshi->parse_code();
	}

}
?>
