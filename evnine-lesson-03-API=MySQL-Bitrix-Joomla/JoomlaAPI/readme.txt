en: Example of connection to the component API Joomla.
ru: Пример подключения в компоненте API Joomla. 

/evnine.config.php
	$this->path_to=$_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_evnine'.DIRECTORY_SEPARATOR;
	$this->api='ModelsJoomla';
	$this->class_path=array(
		'ModelsJoomla'=>	array('path'=>'/models/'),
	)

en: Entry point.
ru: Точка входа.
en: Initialization of the controller through the component.
ru: Пример подключения контроллера в компоненте.
/evnine.php
	defined( '_JEXEC' ) or die( 'Restricted access' );
	global $mainframe;
	/*
	* en: Init evnine controller
	* ru: подключаем базовый контроллер
	*/
	require_once( JPATH_COMPONENT.DS.'evnine.controller.php' );
	$evnine = new Controller();
	$array_this = $evnine->getControllerForParam(
		array(
			'controller' => 'helloworld',
			'method' => 'default',
			'sef' => $_REQUEST['sef'],
			'REQUEST'=>$_REQUEST,
			'cookie' => $_COOKIE,                       
			'ajax' => $_REQUEST['ajax'],
		)
	);
	require_once(JPATH_COMPONENT.DS.'debug'.DS.'evnine.debug.php');
	print_r2($array_this);
	$mainframe->close();

en: The base controller.
ru: Базовый контроллер.
/evnine.controller.php

en: Basic model of working with API
ru: Базовая модель работы с API
/models/ModelsJoomla.php
	function setInitAPI($param) {
		if ($this->isJoomla){
			$this->mysql = JFactory::getDBO();
		}
	}