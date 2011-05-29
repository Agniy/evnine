<?php
error_reporting(0);
$component_name='evnine';
defined( '_JEXEC' ) or die( 'Restricted access' );
$file_name= $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.$component_name.DIRECTORY_SEPARATOR.'evnine.config.php';
include($file_name);
$config = new Config();
if (isset($_REQUEST['save']['cancel'])){
	unset($_REQUEST);
}else {
	unset($_REQUEST['save']);
}
foreach ($_REQUEST as $config_title =>$config_value){
	if (!isset($config->$config_title)){
		unset($_REQUEST[$config_title]);
	}
}	
if (count($_REQUEST)>0){
	$config=$_REQUEST;
}
$rus =array(
);

echo '<head>
			<meta http-equiv="Content-Type" content="text/html charset=utf-8" />
		</head>';
$html.=$file_name;
$html.='<form action="'.$_SERVER['PHP_SELF'].'?option=com_sa" name="form" method="post">';
$html.=htmlinput('Save','save[ok]','submit').htmlinput('Cancel','save[cancel]','submit');
$html.= getConfigClassOut($config,$file_name,$rus);
$html.=htmlinput('Save','save[ok]','submit').htmlinput('Cancel','save[cancel]','submit');
$html.='</form>';
echo '<pre>'; print_r($html); echo "</pre><br />\r\n";

$tpl['var']['pre_class']='var $';
$tpl['var']['post_class']=';'."\r\n";
$tpl['var']['pre_construct_value']="\t\t".'$this->%VAR%=';
$tpl['var']['pre_construct_array']="\t\t".'$this->%VAR%=array('."\r\n";
$tpl['var']['contact_construct_array']='=>';
$tpl['var']['contact_construct_value']='=';
$tpl['var']['post_construct_array']="\t\t".');'."\r\n";
$tpl['var']['post_construct_value']=';'."\r\n";
$tpl['body']['pre_class']='<'.'?php'."\r\n"."/*>*/ class Config {"."\r\n\r\n";
$tpl['body']['pre_construct']="\r\n\t".'function __construct(){'."\r\n";
$tpl['body']['post_construct']="\r\n"."\t".'}'."\r\n";
$tpl['body']['post_class']='}'."\r\n".'?>'."\r\n";

$php = setConfigClass($config,$file_name,$tpl);
file_put_contents($file_name,$php);

function getArrayValue($array,$tpl,$tab) {
	foreach ($array as $array_title =>$array_value){
		if (is_array($array_value)){
				echo $tab.getColms($array_title).$tpl['contact_construct_array'].'array('."\r\n";
					getArrayValue($array_value,$tpl,$tab."\t");
				echo $tab.'),'."\r\n";				
			}else {
				echo $tab.getColms($array_title).$tpl['contact_construct_array'].getColms($array_value).",\r\n";
			}
	}		
}

function getColms($str) {
	return "'".mysql_escape_string($str)."'";
}

function setConfigClass($class,$file_name,$tpl) {
	ob_start();
	echo $tpl['body']['pre_class'];
	foreach ($class as $config_title =>$config_value){
		echo $tpl['var']['pre_class'].$config_title;
	echo $tpl['var']['post_class'];
	}	
	echo $tpl['body']['pre_construct'];
	foreach ($class as $config_title =>$config_value){
		$tab="\t\t\t";
		if (is_array($config_value)){
				echo str_replace("%VAR%",$config_title,$tpl['var']['pre_construct_array']);
				getArrayValue($config_value,$tpl['var'],$tab);
				echo $tpl['var']['post_construct_array'];
		}else {
				echo str_replace("%VAR%",$config_title,$tpl['var']['pre_construct_value']);
				echo getColms($config_value).$tpl['var']['post_construct_value'];
		}
		}	
	echo $tpl['body']['post_construct'];
	echo $tpl['body']['post_class'];
	$html = ob_get_clean();
return $html;
}

function getRusByTitle($title,$rus) {
	if (isset($rus[$title])){
		return $rus[$title];
	}else {
		return $title;
	}
}

function getConfigClassOut($class,$file_name,$rus){
	ob_start();
	foreach ($class as $config_title =>$config_value){
		if (is_array($config_value)){
			echo '<tr><td>'.'<h2>'.getRusByTitle($config_title,$rus).':</h2></td><td>'.'<table>';
			getArrayValueOut($config_value,$tab,$rus,$config_title);
			echo '</table>'.htmlinput('Соxранить','save[ok]','submit').htmlinput('Отмена','save[cancel]','submit').'</td></tr>';
		}else {
			echo '<tr><td><b>'.getRusByTitle($config_title,$rus).'</b>:</td><td>'.htmlinput($config_value,$array_title,'type')."</td></tr>\r\n";
		}
	}
	$html = ob_get_clean();
return $html;
}

function getArrayValueOut($array,$tab,$rus,$parent_title) {
	foreach ($array as $array_title =>$array_value){
		$parent=$parent_title.'['.$array_title.']';
		if (is_array($array_value)){
			$tab='<td>&nbsp;</td>';
			echo '<tr><td></td><td><hr/><h2>'.getRusByTitle($array_title,$rus).':</h2></td></tr>';
			echo '<table>';
			getArrayValueOut($array_value,$tab,$rus,$parent);
			echo '</table></td></tr>';
			$tab='';
		}else {
			echo '<tr>'.$tab.'<td><b>'.getRusByTitle($array_title,$rus).'</b>:</td><td>'.htmlinput($array_value,$parent,'type')."</td>".'</tr>';
			}
	}		
}

function htmlFormOutput($form_name,$php){
		$html.='<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html charset=utf-8" />
			<link type="text/css" href="progress.css" rel="stylesheet" />	
			<script type="text/javascript" src="jq.include.js"></script>	
			<script type="text/javascript" src="jq.index.js"></script>  	
		</head>
		<body>';
		$html.=htmlinput('Save','save[ok]','submit').htmlinput('Cancel','save[cancel]','submit');
		$html.=htmlinput('Save','save[ok]','input').htmlinput('Cancel','save[cancel]','input');
		$html.='<br/>';
		$html.=htmlTable(
			'Test',
			array('5' => '6','7' => '8'),
			array('1' => '2','3' => '4')
		);
		$html.=htmlSelect(array('test' => 'test'),'test');
return $html;
}

	function htmlinput($value="",$name,$type="text",$text='',$style,$max_strlen='55'){
		if (strlen($value)<20) {
			$size= '18';
		}else {
			if ($max_strlen==''){
				$size=strlen($value)+'5';
			} else {
				$size= $max_strlen;
			}
		}
		$replace_input='no';
		if ($replace_input=='no')
			$html.='<input size="'.$size.'" type="'.$type.'" style="'.$style.'" value="'.$value.'" id="'.$name.'" name="'.$name.'"/>&nbsp;';
		if (!empty($text)){
			$html.= '<b>'.$text.'</b><br />';
		}
		return $html;
	}

	function GetFirstArrayKey($array,$need='key') {
		$a = each($array);
		list($k, $v)=$a;
		if ($need==='key'){
			return $k;
		}else {
			return $a[$k];
		}
	}


		function htmlSelect($array,$name){
			if (GetFirstArrayKey($array)=='') return '';
				$html.='<select onchange="this.form.submit();" name="'.$name.'" size="1">';
					foreach ($array as $status_title => $status_value){
						$html.= '<option style="" value="'.$status_value.'" '.(
							$gSetting[$name]['value']==$status_value
									?'selected="selected"'
									:'').
									'>'.$status_value.'</option>';
					}       
				return $html.='</select>&nbsp;';
		}

		function htmlTable($title,$table,$out/*,$mode,$name_for_jquery*/,$text){
				if (count($table)==0) return $html;
				$html.='<table>';
				$html.='<tr  style="align-text:left;">';
				foreach ($out as $out_title => $out_value){
					$html.='<td><b>'.$out_title.'</td></b>';
				}		
				$html.='</tr>';
				foreach ($table as $table_title => $table_value){
					$html.='<tr>';
						$html.='<tr style="color:green">';
					if (is_array($table_value)){
						foreach ($out as $out_title => $out_value){
							$html.='<td>'.$table_value[$out_title].'</td>';
						}		
					}else {
						foreach ($out as $out_title => $out_value){
							$html.='<td>'.$table_value.'</td>';
						}		
					}
					$html.='</tr>';
				}
				$html.='</table>';
				$html.=$text;
				return $html;
	}
?>