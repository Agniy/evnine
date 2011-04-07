<?php

class TemplateConfig{
	function getConfigTwig(  ) {
// twig
 return array(
  'tag_comment'  => array('{# ', ' #}',' '),
  'tag_block'    => array('{% ', ' %}'),
  'tag_variable' => array('{{ ', ' }}'),
  'tag_block_if'  => array('if ', 'endif'),
	'tag_block_for'  => array('for ', 'endfor',' in ','For_'),
	);
	}

	function getConfigPHP(  ) {
// php
 return array(
  'tag_comment'  => array('< ?'.'php /'.'*', '*'.'/ ?'.'>',' '),
	'tag_block'    => array('< ?'.'php ', ' ?'.'>'),
	'tag_block_open_close'    => array('(', '):'),
  'tag_variable' => array('< ?'.'php echo ', '; ?'.'>'),
  'tag_block_if'  => array('if ', 'endif;'),
	'tag_block_for'  => array('foreach ', 'endforeach',' => ','For_'),
	);
	}

	function getConfigPHPSHORT(  ) {
// php short
 return array(
  'tag_comment'  => array('< ?'.'/'.'*', '*'.'/ ?'.'>',' '),
	'tag_block'    => array('< ?'.'= ', ' ?'.'>'),
	'tag_block_open_close'    => array('(', '):'),
  'tag_variable' => array('< ?'.'=', '?'.'>'),
  'tag_block_if'  => array('if ', 'endif;'),
	'tag_block_for'  => array('foreach ', 'endforeach',' => ','For_'),
	);
	}


}

?>
