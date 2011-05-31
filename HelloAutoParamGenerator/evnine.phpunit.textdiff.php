<?php

/*
	file: inline_function.php

	This file defines a function which hacks two strings so they can be
	used by the Text_Diff parser, then recomposes a single string out of
	the two original ones, with inline diffs applied.

	The inline_diff code was written by Ciprian Popovici in 2004,
	as a hack building upon the Text_Diff PEAR package.
	It is released under the GPL.

	There are 3 files in this package: inline_example.php, inline_function.php, inline_renderer.php
*/

// for the following two you need Text_Diff from PEAR installed
include_once 'Text/Diff.php';
include_once 'Text/Diff/Renderer.php';
include_once 'Text/Diff/Renderer/unified.php';

// this is my own renderer
//include_once 'inline_renderer.php';
	class Text_Diff_Renderer_inline extends Text_Diff_Renderer {

	var $ins_prefix = '<ins>';
	var $ins_suffix = '</ins>';
	var $del_prefix = '<del>';
	var $del_suffix = '</del>';
	var $buffer;
	
	function Text_Diff_Renderer_inline($context_lines = 10000, $ins_prefix = '<ins>', $ins_suffix = '</ins>', $del_prefix = '<del>', $del_suffix = '</del>')
    {
		$this->$ins_prefix = $ins_prefix;
		$this->$ins_suffix = $ins_suffix;
		$this->$del_prefix = $del_prefix;
		$this->$del_suffix = $del_suffix;
		
        $this->_leading_context_lines = $context_lines;
        $this->_trailing_context_lines = $context_lines;
    }

    function _lines($lines)
    {
        foreach ($lines as $line) {
//            echo "$line ";
            $this->buffer.=$line.' ';
            // FIXME: don't output space if it's the last line.
        }
    }

    function _blockHeader($xbeg, $xlen, $ybeg, $ylen)
    {
		return '';
    }

    function _startBlock($header)
    {
        echo $header;
    }

    function _added($lines)
    {
			$this->buffer.=$this->ins_prefix;
      $this->_lines($lines);
			$this->buffer.=$this->ins_suffix.' ';
    }

    function _deleted($lines)
    {
			$this->buffer.=$this->del_prefix;
      $this->_lines($lines);
      $this->buffer.=$this->del_suffix.' ';
    }

    function _changed($orig, $final)
    {
        $this->_deleted($orig);
        $this->_added($final);
    }

}


function inline_diff($text1, $text2, $num_line) {

	// create the hacked lines for each file
	$htext1 = chunk_split($text1, 1, "\n");
	$htext2 = chunk_split($text2, 1, "\n");

	// convert the hacked texts to arrays
	// if you have PHP5, you can use str_split:
	/*
	$hlines1 = str_split(htext1, 2);
	$hlines2 = str_split(htext2, 2);
	*/
	// otherwise, use this code
	for ($i=0;$i<strlen($text1);$i++) {
		$hlines1[$i] = substr($htext1, $i*2, 2);
	}
	for ($i=0;$i<strlen($text2);$i++) {
		$hlines2[$i] = substr($htext2, $i*2, 2);
	}


//	$text1 = str_replace("&nbsp;",' ',$text1);
//	$text2 = str_replace("&nbsp;",' ',$text2);

	$text1 = str_replace("\n"," \n",$text1);
	$text2 = str_replace("\n"," \n",$text2);

	$hlines1 = explode(" ", $text1);
	$hlines2 = explode(" ", $text2);

	// create the diff object
	$diff = &new Text_Diff($hlines1, $hlines2);

	// get the diff in unified format
	// you can add 4 other parameters, which will be the ins/del prefix/suffix tags
	$renderer = &new Text_Diff_Renderer_inline(50000);
	$renderer->render($diff);
	return $renderer->buffer;
}

?>