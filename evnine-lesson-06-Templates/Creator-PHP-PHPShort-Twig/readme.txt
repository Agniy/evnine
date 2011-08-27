en: Writing code for templating PHP, PHP Short, Twig (alpha ver)
ru: Создание кода для шаблонизаторов PHP, PHP Short, Twig (alpha версия)

en: class template
ru: Класс шаблонизатора
/evnine.views.generator.template.php
function getArrayToTemplate ($array,$shift=0,$template='Twig') {
	include_once('evnine.views.generator.template.config.php');
	return $this->getTemplateStr(
		$this->getParsingArrayForTemplate(
			$this->getMinimizeArray($array)
	),$shift,$template)
}

en: config template
ru: Конфиг шаблонизатора
/evnine.views.generator.template.config.php
function getConfigPHPSHORT(  ) {
 return array(
  'tag_comment'  => array('&lt;'.'?'.'/'.'*$', '*'.'/ ?'.'>',' '),
	'tag_block'    => array('&lt;'.'?'.' ', ' ?'.'>'),
	'tag_block_open_close'    => array('(', '):'),
  'tag_variable' => array('&lt;'.'?'.'=', '?'.'>','$'),
  'tag_variable_join'  => array('[\'','\']'),
  'tag_block_if'  => array('if ', 'endif;'),
	'tag_block_for'  => array('foreach ', 'endforeach',' as $','For_'),
	'tag_block_init_var'  => true,
	'tag_block_for_place_tmp_var'  => 2,
	);
}
