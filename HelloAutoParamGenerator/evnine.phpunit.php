<?php
//error_reporting(E_ALL);
ob_start();
set_time_limit ( '3000' );
ini_set("memory_limit","920M");
global $test_faild;
if (!defined( '__DIR__' )) 
	define('__DIR__', getcwd());

function CodeHighlighter($html,$code,$html_old) {
	$html_orig = $html;
	if ($code){
		echo $html.'<hr>';
		include_once($_SERVER["DOCUMENT_ROOT"].'/components/com_sa_helpers/CodeHighlighter/geshi.php');
		$geshi = new GeSHi($html, 'html4strict');
		$geshi->set_header_type(GESHI_HEADER_DIV);
		$html = $geshi->parse_code();
		if (!empty($html_old)){
			$geshi = new GeSHi($html_old, 'html4strict');
			$geshi->set_header_type(GESHI_HEADER_DIV);
			$html_old = $geshi->parse_code();
			inline_diff($html_old,$html, '#**!)@#').'';
		}else {
			echo $html;
		}
		return true;
	}
	echo $html_orig;
}



function print_ar($array, $count=0) {
    $i=0;
				$tab ='';
//				$nbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				$nbsp = '&nbsp;&nbsp;';
    while($i != $count) {
        $i++;
        $tab .= $nbsp.$nbsp;
     }
    foreach($array as $key=>$value){
					if(is_array($value)){
						echo '<br/>'.$tab;
						if ($count>=1)	
						echo substr($nbsp, 0, -6*2);
            echo '[<b>'.$key.'</b>]&nbsp;=>&nbsp;Array';
            $count++;
            	print_ar($value,$count);
            $count--;
        }
        else{
					echo '<br/>';
					$tab2 = substr($tab, 0, -6*2);
					if (strlen($tab2)/6==0) 
						$tab2= '';
					echo $tab2;
					if ($count>=1){
						echo $nbsp;
			        echo '['.$key.']&nbsp;=>&nbsp;'.$value;
		        }else {
	        		echo '[<b>'.$key.'</b>]&nbsp;=>&nbsp;'.$value;
	          }
        }
        $k++;
    }
    $count--;
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

function getUnitTest($file,$param) {
	global $test_faild;
	$test_faild= '';
	echo '<b><font size="1px">';
	exec('ptr_php.cmd '.$file.' '.$param,$out);
	unset($out[0]);
	unset($out[1]);
	foreach ($out as $out_title =>$out_value){
		if ($out_value[0]==='+'&&$out_value[1]==='+'||
			$out_value[0]==='-'&&$out_value[1]==='-'||
			$out_value[0]==='@'&&$out_value[1]==='@'
		) {
			$out_value= '';
			unset($out[$out_title]);
		}
		if ($out_value[0]==='+') 
			$out[$out_title]='<b><font color="green">'.$out_value.'</font></b>';
		if ($out_value[0]==='-') 
			$out[$out_title]='<font color="gray">'.$out_value.'</font>';
		if ($out_value[0]==='T'&&$out_value[3]==='t'){
			if (preg_match("/Failures:.*(?=\.)/",$out[$out_title],$tmp)){
				$test_faild= 'Error: ';
			}
		}
	}	
	$out = implode("<br/>",$out);
	echo '<pre>'.$out.'</pre>';
	echo '</html>';
	echo '</font></b>';
}

function getCommentFromArray($array,$shift) {
$param_const_rus=array(
	'PermissionLevel'=>'Доступ',
	'PermissionLevelCookie'=>'Доступ&nbsp;по&nbsp;кукам',
	'ajax' => 'аякс',
	'ShowPageOnPagination'=>'страниц в пагинации',
	'RowsPerPageCount'=>'строк на странице',
	'method'=>'метод',
	'UserID'=>'юзер',
	'UserIDCookie'=>'юзер по кукам',
	'z'=>'Доступ',
	'i'=>'юзер',
	'p'=>'пароль',
	'template'=>'в&nbsp;шаблонe',
	'HistorySlicePerDay'=>'срез дней',
	'form_data'=>'формa',
	'cookie'=>'куки',
	);
$PermissionLevel=array(
		'0'=>'гость',
		'1'=>'юзер',
		'2'=>'владелец',
		'3'=>'модератор',
		'4'=>'admin',
	);
$array_str= '';
if (isset($array['cookie'])){
	$array['UserIDCookie']=$array['cookie']['i'];
	$array['PermissionLevelCookie']=$array['cookie']['z'];
	unset($array['UserID']);
	unset($array['PermissionLevel']);
}
if (!empty($shift)){
	$nbsp = '&nbsp;';
	$i=0;
	$shift=strlen((String)$shift);
while($i <= $shift) {
    $i++;
   $tab .= $nbsp;
 }
	//}	$tab = substr($nbsp, 0, 5*6+6*strlen($tab));
}
foreach ($param_const_rus as $key =>$value)if (isset($array[$key])){
	$value = $array[$key];
	if (
		($key=='form_data'||
			$key=='cookie'
		)
		&&!empty($tab)
	)
		$array_str.='<br/>'.$tab.' ';
	if ($key=='PermissionLevel'||$key=='PermissionLevelCookie')
		$value= $PermissionLevel[$value];
		if (is_array($value)){
			$array_str.='<b>'.$param_const_rus[$key].'</b> = '.getArrayToPHPCode($value).',  ';
		}else {
			$array_str.='<b>'.$param_const_rus[$key].'</b>: '.$value.',  ';
		}
	}
	return $array_str;
}

function getArrayToPHPCode($array,$count) {
	if ($count!=''){
		$array_str.='\'md5\'=>\''.($count+1).'\',';	
	}
	foreach ($array as $key => $value) {
		if (is_array($value)){
			$array_str.='\''.$key.'\'=>'.getArrayToPHPCode($value).', ';
		}else {
			$array_str.='\''.$key.'\'=>\''.$value.'\', ';
		}
	}
	$array_str='array( '.$array_str.')';
	return $array_str;
}


function getMethodNameForTest($template,$filter){
	if (is_array($filter)){
		$str=strtoupper($template.multi_implode('',$filter));
	}else {
		$str=strtoupper($template.$filter);
	}
	return $str;
}

function getPHPUnitCode($template,$save_case,$filter){
//echo '#$template: '.$template."<br />\r\n";
$phpunit= '/**'."\r\n";
//	$template=preg_replace("/_/","",$template);
if (is_array($filter)){
	$php_function='getControllerForParam'.getMethodNameForTest($template,$filter).'Test';
}else {
	$php_function='getControllerForParam'.getMethodNameForTest($template,$filter).'Test';
}
//echo '#$php_function: '.$php_function."<br />\r\n";
//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
 
foreach ($save_case as $save_case_title =>$save_case_value)if (count($save_case_value)!='1'){
	$this_comment=getCommentFromArray($save_case_value);
  if ($limit_test>$save_case_title) break;
	$phpunit.=' * '.$this_comment."\r\n";
	$phpunit.=' * @assert ($param) == $this->object->getDataForTest'.'(\'';
	$phpunit.=$php_function;
	$phpunit.='\',$param='.getArrayToPHPCode($save_case_value,$save_case_title).',false)'."\r\n";
}	
$phpunit.= '*/'."\r\n";
$phpunit.= 'function '.$php_function.'($param,$debug=false) {'."\r\n"
	.'$this->getControllerForParam($param,$debug);'."\r\n"
	.'return $this->result;'."\r\n"
	.'}'."\r\n";
return $phpunit ;
}

function getPHPUnitAllCase($template,$all_class,$param_const,$filter){
$count=0;
foreach ($all_class as $controller_title =>$controller_value){
	$controller_file = $_SERVER["DOCUMENT_ROOT"].'components/com_sa/controllers/'.$controller_value.'.php';
	include_once($controller_file);
		//	echo '#$controller_file: '.$controller_file."<br />\r\n";
		$SaController_case[$controller_title]=$param_const;
		$SaController_case[$controller_title]['template'][]=$controller_title;
		$Controller = new $controller_value();
		//echo '<pre>#$Controller->controller_menu_view: '; print_r($Controller->controller_menu_view); echo '</pre>';
		foreach ($Controller->controller_menu_view['public_methods'] as $Controller_title =>$Controller_value)if ($Controller_value!=''){
			if ($Controller_title=='default') 
				$Controller_title= '';
			if ($Controller_title==$filter['value']||isset($filter['value'][$Controller_title])||empty($filter)){
				$SaController_case[$controller_title]['method'][]=$Controller_title;
			}
	}		
		$count++;
}
//Сбрасываем все случаи
$save_case=array();
//Счётчик теста
$count = 1;
foreach ($SaController_case[$template] as $param_const_title =>$param_array){
	$case_count = count($save_case);
	$param_count = count($param_array);
	if ($count==1){//Устанавливаем началные значения
		$i=1;
		foreach ($param_array as $param_array_title =>$param_array_value){
			$save_case[$i++]=array(
				$param_const_title=>$param_array_value
			);
		}
		//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
	}else {//Когда уже есть случаи
	//Делаем копии текущи{ случаев в зависимости от кол-во новых параметоров
		if ($count!=1){
			//echo '#$case_count: '.$case_count."<br />\r\n";
			$j_count = 1;
			for ( $i = 1; $i <= $param_count; $i++ ) {
				//echo '#$i: '.$i."<br />\r\n";
				for ( $j = 1; $j <= $case_count; $j++ ) {
					//echo '&nbsp;&nbsp;#$j: '.$j.' * '.($i*$j)."<br />\r\n";
					//echo '#$i*$j: '.."<br />\r\n";
					//echo '<pre>#$save_case[$j]: '; print_r($save_case[$j]); echo '</pre>';
					//echo '#$j_count: '.$j_count."<br />\r\n";
					$save_case[$j_count]=$save_case[$j];
					$j_count++;
				}
			}
			if ($param_count==1) 
				$param_count=1;
			//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
			//echo '#$case_count: '.$case_count."<br />\r\n";
			//echo '<hr>';
			//die();
			//$case_count--;
			//$param_count--;
			//Заполняем параметры страми случаями
			$save_i=1;
			foreach ($param_array as $param_array_title =>$param_array_value){
				$param_array_title++;
				//echo '#$param_array_title: '.$param_array_title."<br />\r\n";
				//echo '<pre>#$param_array_value: '; print_r($param_array_value); echo '</pre>';
				//echo '#$case_count*$param_array_title: '.($case_count*$param_array_title)."<br />\r\n";
				//echo '#$save_i: '.$save_i."<br />\r\n";
				for ( $i = $save_i; $i <= $case_count*$param_array_title; $i++ ) 
					//echo '&nbsp;<hr>';
					//if (isset($save_case[$i]))
					{
						//echo '&nbsp;&nbsp;#$i: '.$i."<br />\r\n";
						$save_case[$i][$param_const_title]=$param_array_value;
					}
				$save_i= $i;
			}
			//echo '<pre>#$save_case: '; print_r($save_case); echo '</pre>';
			//die();
		}
	}
	$count++;
}
return $save_case;
}

function single_diff_assoc($first_array,$second_array,$not_check=array('param_in' => '','param_out' => ''/*,'ViewMethod' => ''*/),$max_in_array=200)
{
	$return = array (); // return
	$new='+';
	foreach ($first_array as $k => $pl) // payload
		if (!isset($not_check[$k])){
		if ( ! isset ($second_array[$k]) 
			|| 
			(	
				$second_array[$k] != $pl 
				&& 
				count($second_array[$k]) != count($pl)
			) 
			//|| (count(array_merge(array_diff($second_array[$k],$pl)))>0)
			||md5(multi_implode('',($first_array[$k])))!=md5(multi_implode('',$second_array[$k]))
		){
			//echo '<<===<pre>#$second_array[$k]: '; print_r($second_array[$k]); echo '</pre>!!';
			if (is_array($pl)&&count($pl)>$max_in_array){
				$i=0;
				foreach ($pl as $pl_title =>$pl_value){
					if($i>$max_in_array&&is_array($pl_value)&&isset ($second_array[$k])){
						$return[$k][$new][$pl_title] = '...';
					}else {
						$return[$k][$new][$pl_title] = $pl_value;
					}
					$i++;
				}		
			}else {
					$return[$k][$new] = $pl;
			}
			if (! isset ($second_array[$k])){
				//$return[$k][$new] 
				$tmp = $return[$k][$new];
				unset($return[$k]);
				$return[$new.$k]=$tmp;
			}
		}
	}
	$old='-';
	foreach ($second_array as $k => $pl) // payload
	if (!isset($not_check[$k])){
		if ( ( ! isset ($first_array[$k]) 
			|| ($first_array[$k] != $pl 
			&& count($first_array[$k]) != count($pl) 
		)
			||md5(multi_implode('',($first_array[$k])))!=md5(multi_implode('',$second_array[$k]))
			//|| (count(array_merge(array_diff($first_array[$k],$pl)))>0)
		) /*&& ! isset ($return[$k])*/ ){
			if (is_array($pl)&&count($pl)>$max_in_array){
				$i=0;
				foreach ($pl as $pl_title =>$pl_value){
					if($i>$max_in_array&&is_array($pl_value)/*&&isset($first_array[$k])*/){
						$return[$k][$old][$pl_title] = '...';
					}else {
						$return[$k][$old][$pl_title] = $pl_value;
					}
					$i++;
				}		
			}else {
					$return[$k][$old] = $pl;
			}
			if (count($return[$k])==1){
				$tmp = $return[$k][$old];
				unset($return[$k]);
				$return[$old.$k]=$tmp;
			}
		if (isset($first_array[$k])&&isset($first_array[$k])){
				$tmp1 = $return[$k][$new];
				$tmp2 = $return[$k][$old];
				$return[$k][$new] 
						=single_diff_assoc($tmp1,$tmp2,$not_check);
				$return[$k][$old] 
					=single_diff_assoc($tmp2,$tmp1,$not_check);
				if (count($return[$k][$old])==0){
					unset($return[$k][$old]);
					$return[$k][$old.' ']= $tmp2;
				}
				if (count($return[$k][$new])==0){
					unset($return[$k][$new]);
					$return[$k]['+']= $tmp1;
				}	
			}
			if ($return[$new.$k]==$return[$old.$k]&&
				count($return[$new.$k])==0&&
				count($return[$new.$k])==0
			){
				unset($return[$new.$k]);
				unset($return[$old.$k]);
			}
			if (count($return[$k])==2){
				unset($return[$k][$old]);
			}
		}
	}
	return $return;
} 

function multi_implode($glue, $pieces)
{
    $string='';
    if(is_array($pieces))
    {
        reset($pieces);
        while(list($key,$value)=each($pieces))
        {
            $string.=$glue.multi_implode($glue, $value);
        }
    }
    else
    {
        return $pieces;
    }
    return trim($string, $glue);
}


function getDataFromAllTest($template,$param_const,$filter='',$unitshow=false,$controller_output=true,$cache=true,$reset_cache=false,$view_only=true,$hide_debug_info=true){//Получить информации по тестам
	//Файл для запуска тестов и сбора данных
	$file= 'ControllerPHPUnitTest.php';
	if ($unitshow){
		getUnitTest($file,getMethodNameForTest($template,$filter['value']));
		die();
	}
	if (!$controller_output)
		die();
	include_once($_SERVER["DOCUMENT_ROOT"].'components/com_sa/Controller.php');
	$SaController = new Controller();
	//Получить все случае с учётом фильтра
	if ($cache) {
		$save_case = getDataForTest(
			$template.'::param-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data','',$reset_cache
		);
	}else {
		$save_case=array();
	}

	if ($unitshow){
		$echo.='<a href="#test"/></a>AJAX UnitTest:<div id="test"><a id="test_href" style="display:none;" href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF'].'</a></div><br/><hr/>';
	}
	$echo.='<a href="#head"/>Случаи</a>, переключение по случаям <b>Alt-Q = 1</b>, <b>Alt-W = 2</b>, <b>Alt-E = 3</b>, <b>Alt-R = 4</b>, <b>Alt-T = 5</b>  <b>Alt-Y = 6</b>, <b>Alt-U = 7</b>, <b>Alt-I = 8</b>, <b>Alt-O = 9</b>, <b>Ctrl-Alt-Q = 10</b>, <b>Ctrl-Alt-W = 11</b>, <b>Ctrl-Alt-E = 12</b> <br/>';
	if (count($save_case)==0){
		$echo.='Create new cache  PHP unit case<br />';
		$echo.='Ошибка, кэш параметров не найден, возможно изменены параметры запуска<br />';
		$save_case = getPHPUnitAllCase($template,$SaController->controller_menu_view,$param_const,$filter);
		getDataForTest($template.'::param-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data',$save_case,$reset_cache);
	}else {
		$echo.='[Load cache case]<br />';
	}
	$save_comment='';
	$count=0;
	$real_count=1;
	$echo.='<style>del{'./*background:#fcc*/';display:none;text-decoration:none;color:#f0e2ec;}ins{background:#cfc;text-decoration:none;}</style>';//ins{background:#cfc
	$echo.='<a name="head"></a>';
		//Подключается шаблонизатор
	require_once($_SERVER["DOCUMENT_ROOT"].'/components/com_sa_helpers/Twig/Autoloader.php');
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem($_SERVER["DOCUMENT_ROOT"].'components/com_sa/views/');
	$twig = new Twig_Environment($loader, array(
			'cache' => $_SERVER["DOCUMENT_ROOT"].'components/com_sa/views_compile/',
		//'cache'=>false,
			'auto_reload' => true,
			//'charset' => 'UTF-8',
			'debug' => true,
			'trim_blocks'=>true,
		)
	);
	if ($cache) {
		$html_comments = getDataForTest(
			$template.'::comment-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data','',$reset_cache
		);
	}else {
		$html_comments=array();
	}
	if (count($html_comments)==0){
		$echo.='Create new cache  case comments!<br />';
		foreach ($save_case as $save_case_title =>$save_case_value){
			$this_comment=getCommentFromArray($save_case_value,'#'.$save_case_title.': ');
			$html_comments[$save_case_title]=
				'<br/><a href="#'.$save_case_title.'">#'.$save_case_title.':</a>&nbsp;'
				.inline_diff($save_comment,$this_comment, '#**!)@#');
			$save_comment=$this_comment;
		}
		$html_comments[]=
			'<br/>'
			.'<a name="html"></a>'
			.'<a href="#head">Параметры</a>, '
			.'<a href="#html"></a>Верстка, '
			.'<a href="#tpl">Шаблон</a>, '
			.'<a href="#array">Массив</a>';
		getDataForTest($template.'::comment-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data',$html_comments,$reset_cache);
	}else {
		$echo.='[Load cache comment]<br />';
	}
	$echo.=implode('',$html_comments).'<br />';
	$save_comment='';
	$count=0;
	$md5_all_arrays=array();
	$html_comments_dif= array();
	if ($cache) {
		$html_comment_cache = getDataForTest($template.'::comment-dif-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data','',$reset_cache);
	}else {
		$html_comment_cache=array();
	}
	if (!$view_only) {
		echo $echo;
		$echo='';
	}
	//Выполнить тесты и создать PHPUnit code + метод для проверки
	foreach ($save_case as $save_case_title =>$save_case_value){
		//Запрашиваем из контроллера
		//$SaController = new 
		if ($cache) {
			$echo= '<font color="red">CACHE IS ON!!!</font>';
			$array_this = getDataForTest($save_case_value['template'].'::method-'.$save_case_value['method'].'-'.md5(multi_implode('',$save_case_value)).'.data','',$reset_cache);
		}else {
			$array_this=array();
		}
		if (count($array_this)==0){
			//$echo.='[Create cache]<br />';
			$array_this = $SaController->getControllerForParam($save_case_value,$debug=false);
			getDataForTest(
			$save_case_value['template'].'::method-'.$save_case_value['method'].'-'.md5(multi_implode('',$save_case_value)).'.data',
			$array_this,$reset_cache);
		}else {
			//$echo.='[cache]<br />';
		}
		$this_array_for_md5 = $array_this;// = $SaController->getControllerForParam($save_case_value,$debug=false);
		unset($this_array_for_md5['param_in']);
		unset($this_array_for_md5['param_out']);
		//Формируем md5 сумму
		$array_this_md5 = md5(multi_implode('',$this_array_for_md5));

		if ($array_this_md5!==$array_save_md5||isset($md5_all_arrays[$array_this_md5])){//Если новый случай
		//Если было сохранение начального массива
			if (isset($array_this_old)){
			//Проверяем различия между до этого сохраненного массива с этим
				if (!isset($md5_all_arrays[$array_this_md5])){
					if ($cache) {
						$array_diff = getDataForTest($save_case_value['template'].'::dif-method-'.$save_case_value['method'].'-'.$array_this_md5.'-'.md5(multi_implode('',$array_this_old)).'.data','',$reset_cache);
						$echo.='LoadArrayDiff<br />';
					}else {
						$array_diff=array();
					}
					if (count($array_diff)==0){
						$echo.='CreateNewArrayDiff<br />';
						$array_diff = single_diff_assoc($array_this,$array_this_old);
						getDataForTest(
							$save_case_value['template'].'::dif-method-'.$save_case_value['method'].'-'.$array_this_md5.'-'.md5(multi_implode('',$array_this_old)).'.data',
							$array_diff,$reset_cache
						);
					}
				}
				
				if (count($array_diff)>0){//Если изменения есть
					//Сохраним счёчтик для ссылки
					if (!isset($md5_all_arrays[$array_this_md5])){
					$save_count=$save_case_title;
					$md5_all_arrays[$array_this_md5]['id']=$save_case_title;
					if (isset($html_comment_cache[$save_case_title])){
						$md5_all_arrays[$array_this_md5]['comment']= $html_comment_cache[$save_case_title]['comment'];
						$echo.=$html_comment_cache[$save_case_title]['dif'];
					}else {
						$html_comments_dif[$save_case_title]['comment']=$md5_all_arrays[$array_this_md5]['comment']=getCommentFromArray($save_case_value,'#'.$save_case_title.' ('.$real_count.'):   ');
						$html_comments_dif[$save_case_title]['dif'].='<hr><a name="'.$save_case_title.'"/></a><a href="#head">#'.$save_case_title.' ('.$real_count.'):</a><a name="case_'.$real_count.'"></a>&nbsp;';
						$html_comments_dif[$save_case_title]['dif'].=inline_diff($save_comment,$md5_all_arrays[$array_this_md5]['comment'], '#**!)@#');
						$echo.=$html_comments_dif[$save_case_title]['dif'];
					}
					if (!$view_only){
						echo $echo;
						$echo='';
					}
					if (file_exists($_SERVER["DOCUMENT_ROOT"].'components/com_sa/views/'.$array_this['View'].'.tpl'))
						CodeHighlighter($html = $twig->loadTemplate('ViewsAjax'.$array_this['ajax'].'.tpl')->render($array_this),$_REQUEST['code'],$save_html);
					$save_html=$html;					
					$echo.='</font></b><b><br/>Отличия в:</b> '; 	
					if (!$view_only&&$hide_debug_info) {
						new dBug($array_diff, "array" ,false);
					}
					$echo.='</font></b>';
					//Сохраним для тестов массив параметров
					$save_case_phpunit[]= $save_case_value;
					$array_this_old = $array_this;
					$save_comment = $md5_all_arrays[$array_this_md5]['comment'];
					$real_count++;
					
				}else {//Not если нет изменений
						if (isset($html_comment_cache[$save_case_title])){
							$getCommentFromArray=$html_comment_cache[$save_case_title]['comment'];
							$echo.=$html_comment_cache[$save_case_title]['dif'];
						}else {
							$html_comments_dif[$save_case_title]['dif'].='<br/><a name="'.$save_case_title.'"/></a><a href="#'.$md5_all_arrays[$array_this_md5]['id'].'">'
								.' ['.$save_case_title.' cовпадает со случаем #'.$md5_all_arrays[$array_this_md5]['id'].']'.'</a>&nbsp;';
							$html_comments_dif[$save_case_title]['comment']=$getCommentFromArray = getCommentFromArray($save_case_value,$save_case_title.'cовпадает со случаем #'.$md5_all_arrays[$array_this_md5]['id']);
							$html_comments_dif[$save_case_title]['dif'].=inline_diff($save_comment,$getCommentFromArray, '#**!)@#');
							$echo.=$html_comments_dif[$save_case_title]['dif'];
						}
						$save_comment = $getCommentFromArray;
					}
					//Сформировать код 
				}else {
					//Если нет различий выводим совпадение со случаем до
					$echo.='<a name="'.$save_case_title.'"/></a>';
					if ($save_count+1==$save_case_title){
						$echo.='<a href="#'.$save_count.'">'.$save_case_title.' cовпадает со случаем #'.$save_count.'</a> ';
					}else {
						$echo.=' = '.$save_case_title;
					}
				}
				
		} else {//NEW  - Если первая инициализация 
			//Сохраним счёчтик для ссылки
				$save_count=$save_case_title;
				//Вывести комментарий к методу и массив с данными
				$md5_all_arrays[$array_this_md5]['id']=$save_case_title;
				if (isset($html_comment_cache[$save_case_title])){
					$md5_all_arrays[$array_this_md5]['comment']= $html_comment_cache[$save_case_title]['comment'];
					$echo.=$html_comment_cache[$save_case_title]['dif'];
				}else {				
					$html_comments_dif[$save_case_title]['comment']=$md5_all_arrays[$array_this_md5]['comment']=getCommentFromArray($save_case_value,'#'.$save_case_title.' ('.$real_count.'):  ');
					$html_comments_dif[$save_case_title]['dif'].='<hr><a name="'.$save_case_title.'"></a><a href="#head">#'.$save_case_title.' ('.$real_count.'):</a><a name="case_'.$real_count.'"></a>&nbsp;';
					$html_comments_dif[$save_case_title]['dif'].=inline_diff($save_comment,$md5_all_arrays[$array_this_md5]['comment'], '#**!)@#').'';
					$echo.=$html_comments_dif[$save_case_title]['dif'];
				}
				if (!$view_only){
					echo $echo;
					$echo='';
				}
				if (file_exists($_SERVER["DOCUMENT_ROOT"].'components/com_sa/views/'.$array_this['View'].'.tpl')){
					CodeHighlighter($save_html = $twig->loadTemplate('ViewsAjax'.$array_this['ajax'].'.tpl')->render($array_this),$_REQUEST['code'],'');
				}
				if ($view_only) {
					die();
				}
				include_once($_SERVER["DOCUMENT_ROOT"].'components/com_sa/ControllerPHPUnitExcecAutoTemplate.php');
				$template_param1= array('echo' => true,'if' => false,'comment' => true,'tpl' => 'Twig');
				$template_param2= array('echo' => false,'if' => true,'comment' => false,'tpl' => 'Twig');
				if ($cache) {
					$template_generate = getDataForTest(
						$template.'::template-'.md5(multi_implode('',$param_const).multi_implode('',$template_param1).multi_implode('',$template_param2).multi_implode('',$filter)).'.data','',$reset_cache
					);
				}else {
					$template_generate=array();
				}
				if (count($template_generate)==0){
					$template_generate = 
						'<br />'
						.'<a name="tpl"></a>'
						.'<a href="#head">Параметры</a>, '
						.'<a href="#html">Верстка</a>, '
						.'<a href="#tpl"></a>Шаблон, '
						.'<a href="#array">Массив</a>'
						.'<table><tr>'
						 .'<td>'.getTemplateFromArray($array_this,$template_param1).'</td>'
						 //.'<td>'.getTemplateFromArray($array_this,$template_param3).'</td>'
						 .'<td>'.getTemplateFromArray($array_this,$template_param2).'</td>'
						 .'</tr><table>'
						 ;
					getDataForTest(
						$template.'::template-'.md5(multi_implode('',$param_const).multi_implode('',$template_param1).multi_implode('',$template_param2).multi_implode('',$filter)).'.data'
					,$template_generate,$reset_cache);
				}else {
						$echo.='[cache]<br />';
				}
				if (!$view_only) {
					$echo.='Данные из массива переданого в шаблон:';
					echo $echo;
					$echo= '';
				}
				
				if (!$view_only&&$hide_debug_info) {
					echo $template_generate;
					echo '<br/>'
						.'<a name="array"></a>'
						.'<a href="#head">Параметры</a>, '
						.'<a href="#html">Верстка</a>, '
						.'<a href="#tpl">Шаблон</a>, '
						.'<a href="#array"></a>Массив';
					new dBug($array_this, "array",false);
				}
				//Сформировать код 
				$save_case_phpunit[]= $save_case_value;
				$array_this_old = $array_this;
				$real_count++;
				$save_comment = $md5_all_arrays[$array_this_md5]['comment'];
			}
			//Сохраняем этот массив как старый
		}else {//SAME
			//Если нет различий выводим совпадение со случаем до, выводим ссылку
			$echo.='<a name="'.$save_case_title.'"></a></a>';
			if ($save_count+1==$save_case_title){
				$echo.='<a href="#'.$save_count.'">Ответ совпадает с ответом #'.$save_count.'</a> ';
			}else {
				$echo.=' = '.$save_case_title;
			}
		}
		//Сохраняем контрольную сумму массива для будующей проверки
		$array_save_md5 = $array_this_md5;
	}	

	if (!$view_only){
		echo $echo;
		$echo='';
	}
	$html = ob_get_clean();

	if (count($html_comments_dif)>0){
		getDataForTest($template.'::comment-dif-'.md5(multi_implode('',$param_const).multi_implode('',$filter)).'.data',$html_comments_dif,$reset_cache);
	}
	global $test_faild;
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset="utf-8" /><title>'.$test_faild.$template.(!empty($method)?'::':'').$method.'</title></head><body width="500px">';
	echo $html;
	//Формируем код запроса тестов исходя из различий случаев
	$phpunit_str= getPHPUnitCode($template,$save_case_phpunit,$filter['value']);
//if ($unitshow){
	echo '<pre>//Исходя из уникальности ответа на тесты формируем PHPUnit код: <br />'; print_r($phpunit_str); echo '</pre>';
//	}
}
	include_once '\com_sa\ControllerPHPUnitExcecDiffText.php';
	include_once '\com_sa\ControllerPHPUnitExcecDebug.php';

	/** getDataForTest - Сделать выборку для теста
	 * 
	 *
	 * @assert ('getControllerForParam',$param) == $this->object->getDataForTest('getControllerForParam',$param=array('test'=>'test'))
	 */
function getDataForTest($file_name,$array_data,$reset=false){
		//Если нужно сбросить кэш по флагу
		if($reset==true&&!isset($array_data)){
			return array();
		}
		//Получаем данные из кэша
		$array = getSerData($file_name);
		if (empty($array)&&isset($array_data)||$reset==true){//||$reset==true
			//Сохраняем в кэш
			setSerData($file_name,$array_data,$reset);
			$array=$array_data;
		}
		if (empty($array)){
			$array=array();
		}
		//Обнуляем все данные
		return $array;
}


/** Прочитать или создать кеш 
 * getSplitDirAndFileName 
 * 
 * @param mixed $file_name 
 * @access public
 * @return void
 */
function getSplitDirAndFileName($file_name) {
	$file_name = split('::',$file_name);
//	$file_dir = __DIR__.DIRECTORY_SEPARATOR.'controllers_cache';
	$file_dir = '\components\com_sa\controllers_cache';
	if (!file_exists($file_dir))
		mkdir($file_dir, 0777);
	$file_dir = $file_dir.DIRECTORY_SEPARATOR.$file_name[0];
	$file_name = $file_dir.DIRECTORY_SEPARATOR.$file_name[0].'_'.$file_name[1];
	return array('name' => $file_name,'dir' => $file_dir);
}

	/** Получить данные
	 *
	 * 
	 * @param file_name
	 */
	function getSerData($file_name) 
	{
		$file = getSplitDirAndFileName($file_name);
		$file_name = $file['name'];
		$file_dir = $file['dir'];
		if (!file_exists($file_dir))
			return '';
		return unserialize(file_get_contents($file_name));
	}

	/** Сохранить данные
	 *
	 * 
	 * @param file_name
	 * @param str
	 */
	function setSerData($file_name, $str,$reset) 
	{		
		$file = getSplitDirAndFileName($file_name);
		$file_name = $file['name'];
		$file_dir = $file['dir'];
		if (!file_exists($file_dir))
			mkdir($file_dir, 0777);
		if (!file_exists($file_dir)){
			echo 'Error please create '.$file_dir.'<br />'."\r\n";
		}
		if (file_exists($file_name)&&!$reset)
			return $str;
		file_put_contents($file_name,serialize($str));
		return $str;
	}

	function getMaket(){
		return '<img src="'.preg_replace("/\.php/",".jpg",basename($_SERVER['PHP_SELF'])).'" width="80%" height="100%">';
	}
?>