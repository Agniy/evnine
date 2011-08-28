<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 01-oct-2010 22:03:41
 */
class ViewsUnitPHPExtend
{

/** getHTMLOutput($param) 
 * 
 * ru: Вывод данных по тестам контроллера
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getHTMLData($param){
	echo '<a name="head"></a>';
	echo $param['getHTMLMSGHeader'];
	foreach ($param["getDataFromControllerByParam"] as $param_id =>$param_array){
		echo $param['getHTMLCaseHeader'][$param_id];
		if (empty($param['same_case'][$param_id])){
			echo $this->getHTMLLink($param_id,'param');
			if ($param['PHPUnitCompare'][$param_id]){
				echo '<a name="'.$param_id.'-test"></a>';
				echo '<font color="red">PHPUnitTest: error&ne;</font>';
				print_r2($param["PHPUnitCompare"][$param_id]);
				echo '<br />';
				echo $this->getHTMLLink($param_id,'array');
			}else {
				echo '<a name="'.$param_id.'-test"></a>';
				echo '<font color="green">PHPUnitTest: ok&radic;</font>';
			}
			echo '<a name="'.$param_id.'-array"></a>';
			print_r2($param_array);
			$this->getViews($param_array);
			if (function_exists('getTemplateFromArray')){ 
				echo $this->getHTMLLink($param_id,'tpl');
				echo getTemplateFromArray(
					$param_array,
						$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'twig','id' => $param_id)
				);
			}
			echo $this->getHTMLLink($param_id);
		}else {
			echo '<a href="#'.$param['same_case'][$param_id].'">same as #'.$param['same_case'][$param_id].'</a>&nbsp;';
			echo $this->getHTMLLink($param['same_case'][$param_id]);
		}
		$count++;
	}
}

function getViews($param){
}


/** getLink($param)
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getHTMLLink($id,$type) {
	if (!empty($type)){
		echo '<a name="'.$id.'-'.$type.'"></a>';
	}
	$html.='[';
	if ($type!=='param') $html.='<a href="#'.$id.'-param">param</a>, ';
	if ($type!=='html') $html.='<a href="#'.$id.'-html">html</a>';
	if ($type!=='test') $html.=', <a href="#'.$id.'-test">test</a>';
	if ($type!=='tpl') $html.=', <a href="#'.$id.'-tpl">tpl</a>';
	if ($type!=='array') $html.=', <a href="#'.$id.'-array">array</a>';
	$html.=']<br />';
	return $html;
}

function sendtToFirebug($data,$type) {
    echo "<script>\r\n//<![CDATA[\r\nif(!console){var console={log:function(){}}}";
    $output    =    explode("\n", print_r($data, true));
    if ($type!=='array'){
	    foreach ($output as $line) {
	        if (trim($line)) {
	            $line    =    addslashes($line);
	            echo "console.log(\"{$line}\");";
	        }
	    }
		}else {
   	 echo 'console.dir("'.$output.'");';
   	}
    echo "\r\n//]]>\r\n</script>";
}

/** getHTMLMSGHeader($param)
 * 
 * Вывести заголовок сообщений 
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getHTMLMSGHeader(&$param){
	//echo 'kuku<br />';
	//echo '#$param: <pre>'; if(function_exists(print_r2)) print_r2($param); else print_r($param);echo "</pre><br />\r\n";
	$html='<table>';
	foreach ($param["getParamTextName"] as $title =>$str){
		$html.='<tr><td style="vertical-align:text-top;">';
		if ($param['PHPUnitCompare'][$title]){
			$html.='<font color="red">&ne;</font>';
		}else {
			$html.='<font color="green">&radic;</font>';
		}
		$html.= '<a href="#'.$title.'">&dArr;';
		$html.= $title;
		$html.= '</a>';
		$html.= '</td><td>';
		$html.= $str;
		$html.= '</td></tr>';
	}
	$html.= '</table>';
	//echo $html;
	return $param['getHTMLMSGHeader']=' '.$html;
}

/** getHTMLButton($param) 
 * Кнопка для сброса кэша и сравнения
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getHTMLButton($param){
	echo '<form action="'.$param['inURL']['default']['pre'].'" method="get"><input value="Reset PHP Unit Cache" type="submit" name="'.$param['inURL']['reset_phpunit']['submit'].'"/></form>';
	//echo '#$param: <pre>'; if(function_exists(print_r2)) print_r2($param['inURL']); else print_r($param);echo "</pre><br />\r\n";
	//TODO сделать вывод формы и input
}

/** getHTMLCaseHeader($param)
 * 
 * Вывести заголовок сообщений 
 * 
 * @param mixed $param 
 * @access public
 * @return void
 */
function getHTMLCaseHeader(&$param){
	$array=array();
	$count= 0;
	foreach ($param["getParamTextName"] as $param_id =>$str){
		$array[$param_id].= 
		'<br />'
		.'<table><tr><td style="vertical-align:text-top;">'
		.'<a href="#head">';
		if ($param['PHPUnitCompare'][$param_id]){
			$array[$param_id].='<font color="red">&ne;</font>';
		}else {
			$array[$param_id].= '<font color="green">&radic;</font>';
		}
		$array[$param_id].='&uArr;'.$param_id.':</a>'
		.'</td><td>'
		.'<a name="'.$param_id.'"></a>'
		.$param['getParamTextName'][$param_id]
		.'</td></tr></table>';
		$array[$param_id].='<a name="'.$param_id.'-param"></a>';
	}
	return $param['getHTMLCaseHeader']=$array;
}


}
?>
