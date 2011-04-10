<?php

class TemplateConfig{
	function getConfigTwig(  ) {
// twig
 return array(
  'tag_comment'  => array('{# ', ' #}',' '),
  'tag_block'    => array('{% ', ' %}'),
  'tag_variable' => array('{{ ', ' }}'),
  'tag_variable_join'  => array('.'),
  'tag_block_if'  => array('if ', 'endif'),
	'tag_block_for'  => array('for ', 'endfor',' in ','For_'),
	'tag_block_init_var'  => false,
	'tag_block_for_place_tmp_var'  => 1,
	);
	}

	function getConfigPHP(  ) {
// php
 return array(
  'tag_comment'  => array('&lt;'.'?'.'php /'.'*$', '*'.'/ ?'.'>',' '),
	'tag_block'    => array('&lt;'.'?'.'php ', ' ?'.'>'),
	'tag_block_open_close'    => array('(', '):'),
  'tag_variable' => array('&lt;'.'?'.'php echo ', '; ?'.'>','$'),
  'tag_variable_join'  => array('[\'','\']'),
  'tag_block_if'  => array('if ', 'endif;'),
	'tag_block_for'  => array('foreach ', 'endforeach',' as $','For_'),
	'tag_block_init_var'  => true,
	'tag_block_for_place_tmp_var'  => 2,
	);
	}

	function getConfigPHPSHORT(  ) {
// php short
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


}

?>
