<?php

function getTemplateFromArray	($array_this,$config)//Init function
{
	$ControllerPHPUnitExcecAutoTemplate = new ControllerPHPUnitExcecAutoTemplate($config);
	return $ControllerPHPUnitExcecAutoTemplate->getArrayToTemplate($array_this);
}

class ControllerPHPUnitExcecAutoTemplate{
var $if_on;
var $echo_on;
var $comment;
var $tpl= 'Twig';
var $name= 'tpl';
var $cfg;
var $init_config;


function __construct($config) 
{
	if ($config['echo']){
//		$this->name.= '_echo';
		$this->if_echo=true;
	}else {
		$this->if_echo=false;
	}
	if ($config['if']){
//		$this->name.= '_if';
		$this->if_on=true;
	}else {
		$this->if_on=false;
	}
	if ($config['comment']){
//		$this->name.= '_com';
		$this->comment=true;
	}else {
		$this->comment=false;
	}
	if ($config['tpl']){
		$this->tpl= $config['tpl'];
	}
	$this->init_config=$config;
}

function getArrayToTemplate ($array,$shift=0,$template='Twig') {
	include_once('evnine.views.generator.template.config.php');
	$template_method='getConfig'.$this->tpl;
	$this->cfg = $cfg = TemplateConfig::$template_method();
	return 
//		(empty($this->init_config['id'])?'':'<a name="'.$this->init_config['id'].'-tpl-gen"></a>').
		$this->getB(
		'evnine.views.generator.template.php: '
			.'['
				.($this->if_on?' IF ':'').($this->if_echo?' ECHO for ':'').($this->tpl?' '.$this->tpl.' ':'')
			.']'
		)
		.$this->getBR()
		.$this->getTemplateStr(
		$this->getParsingArrayForTemplate(
			$this->getMinimizeArray($array)
		)
	,$shift,$template)
//	'<hr/>'
	;
}


	function getMinimizeArray($array,$shift=0,$key_sub) {//Make Array is Clear
		$array_count= 0;
		foreach ($array as $key => $value) {
			if (is_array($value)){
				if ($shift) 
					$array_count++;
				
				if ($shift>0&&$array_count>1&&$key_sub!=='inURL') 
					continue;
				$array_out[$key] = $this->getMinimizeArray($value,($shift+1),$key);//,($sub_array+1)
			}else {
				$array_out[$key]=$value;
			}
		}
		/**
		 foreach ($array as $key => $value) {
			if (is_array($value)){
				if ($shift) 
					$array_count++;
				if ($shift>0&&$array_count>1) 
					continue;
				$array_out[$key] = $this->getMinimizeArray($value,($shift+1),$template);//,($sub_array+1)
			}else {
				$array_out[$key]=$value;
			}
		}
		 * */
		return $array_out;
	}



function getParsingArrayForTemplate($array,$shift=0,$full_key,$this_key,$alias_key,$in_sub_array_flag,$before_in_sub_array_flag) {

	//echo '#$array: <pre>'; print_r($array); echo "</pre><br />\r\n";
	//die();
		$dot = $this->cfg['tag_variable_join']['0'];
		foreach ($array as $current_key => $value) {
			if (!$shift){ 
				$alias_key=$this_key=$full_key='';
				$in_sub_array_flag = false;
			}
			if (isset($full_key_save)){
				$full_key= $full_key_save;
			}
			if (is_array($value)){
				if ($full_key==''){
						$tmp_key = $current_key;
				}else {
					$tmp_key = $full_key.$dot.$current_key;
				}
				//Учёт случая когда в массиве есть под массивы и есть обычные данные
				if ($tmp_key!=$full_key){
					$full_key_save = $full_key;
				}
				$full_key=$tmp_key;
				$save_alias_key=$alias_key;
				//Получить алиас для люча
				$alias_key=$this->getSubKey($current_key,'');
				if ($save_alias_key=='')
					$save_alias_key= $alias_key;
				$this_key=$current_key;
				$tmp_key= 'array = $shift '.$shift.' $current_key: '.$current_key.' $full_key: '.$full_key.' $this_key '.$this_key.' $alias_key: '.$alias_key;
				//Сохранить флаг, находимся ли мы сейчас в под массиве
				$before_in_sub_array_flag = $in_sub_array_flag;
				//Получить данные для текущего флага, ключ массива и уменьшить глубину массива на один уровень
				$value = $this->isInSubArray($value,$in_sub_array_flag,$first_key);
				$array_out[$alias_key] 
				//Рекурсивный вызов, со сдвигом, с полным ключем, с ключем алиаса, находимся ли в под массиве, и был ли прошлый массив под массивом так же
					= $this->getParsingArrayForTemplate($value,$shift+1,$full_key,$this_key,$alias_key,$in_sub_array_flag,$before_in_sub_array_flag);
				//Если есть фалг под массив создаём алиас ключ для подмассива
				$array_out[$alias_key]['array'] = true;
				if (!is_array($value)){
					$array_out[$alias_key]['str'] = $value;		
				}
				if ($in_sub_array_flag){
					$array_out[$alias_key]['alias_key'] = $alias_key;
					//$array_out[$alias_key]['ARRAY_in_sub'] = '';
				}
				if ($before_in_sub_array_flag){
					$array_out[$alias_key]['key'] = $save_alias_key.$dot.$current_key;
					$array_out[$alias_key]['ARRAY_in_beforesub'] = '';
				}
				if (!$before_in_sub_array_flag){
					$array_out[$alias_key]['key'] = $full_key;
					//$array_out[$alias_key]['ARRAY_not_in_beforesub'] = '';
				}
				if (count($value)==1) {
					unset($array_out[$alias_key]);
					if (is_array($value)){
						$first_key.=$this->cfg['tag_variable_join']['0'].($tmp_key = $this->getFirstArrayKey($value));
						$value= $value[$tmp_key];
					}
					$array_out[$full_key.$this->cfg['tag_variable_join']['0'].$first_key]['full_key'] = $current_key;
					$array_out[$full_key.$this->cfg['tag_variable_join']['0'].$first_key]['str'] = $value;
					$array_out[$full_key.$this->cfg['tag_variable_join']['0'].$first_key]['array'] = false;
					$in_sub_array_flag= false;
				}
			}else {
				$tmp_key= 'key = $shift '.$shift.' $current_key: '.$current_key.' $full_key: '.$full_key.' $this_key '.$this_key.' $alias_key: '.$alias_key;
				if ($in_sub_array_flag){
					$tmp_key= $alias_key.$this->cfg['tag_variable_join']['0'].$current_key;
					$array_out[$tmp_key]['str']=$value;
					$array_out[$tmp_key]['key']=$current_key;
					$array_out[$tmp_key]['tab']=$shift;
					$array_out[$tmp_key]['VALUE_inSUB']='';
					$array_out[$tmp_key]['alias_key']=$alias_key;
				}else {
					if ($full_key=='') $dot='';
					$tmp_key= $full_key.$dot.$current_key;
					$array_out[$tmp_key]['str']=$value;
					$array_out[$tmp_key]['key']=$current_key;
					$array_out[$tmp_key]['VALUE_no_inSUB']='';
					$array_out[$tmp_key]['tab']=$shift;
					$array_out[$tmp_key]['full_key']=$full_key;
				}
		}
		}
			return $array_out;
	}
	
		function isInSubArray($value,&$in_sub_array_flag,&$first_key) {
			$first_key = $this->getFirstArrayKey($value);
			if (isset($value[$first_key])&&count($value)==1){
					$in_sub_array_flag= true;
					$value=$value[$first_key];
			}else {
					$in_sub_array_flag= false;
			}
			return $value;
		}
		
		function getSubKey($key,$str) {
			if (substr_count($key,'_')){
				$key_split= split('_',$key);
				if (strlen(trim($key_split[1]))>0)
					$key_split[0]=$key_split[1];
				$key_sub = $str.$key_split[0];
				$preg_split = preg_split("/(?=[A-Z])/",$key_sub);
				if ($preg_split[0]=='') 
					unset($preg_split['0']);
				$preg_split = array_slice($preg_split,0,3);
				$key_sub = implode('',$preg_split);
			}else {
				$key_sub = $str.$key;
			}
			return $key_sub;
		}
	
		function getFirstArrayKey($array,$key_mode='key') {//finde fist array key
		$tmp = each($array);
		list($key, $value)=$tmp;
		if ($key_mode=='key'){
			return $key;
		}else {
			return $value;
		}
		}
	
	
	function getTemplateStr($array,$shift=0,$tab) {
		if (!$shift){
			//echo '#$array: <pre>'; print_r($array); echo "</pre><br />\r\n";
		}
		$cfg=$this->cfg;
//		echo '#$this: <pre>'; print_r($this->cfg); echo "</pre><br />\r\n";
///		echo $this->cfg['tag_variable_join']['0'];
		$dot= $this->cfg['tag_variable_join']['0'];
//		echo '...:'.$dot;
		foreach ($array as $current_key => $value) {
			if (is_array($value)){
				if ($value['array']){
					$tmp_str=$this->getTemplateStr($value,($shift+1),$this->getTab($value['tab']));//,($sub_array+1)
				}else {
					if ($value['alias_key']){
						$tab_add = $value['tab'];
					}
					$tmp_str=$this->getTemplateValue($this->getTab($tab_add),$full_key='',$dot,$current_key,$value['str'],$cfg,$value['alias_key']);
				}
				if (isset($value['alias_key'])&&$value['array']){
					$str_out.=$this->getTemplateBlock($tmp_str,$this->getTab($value['tab']+$shift),$value['alias_key'],$dot,$value['key'],$value['str'],$cfg,$value['alias_key']);
				}else {
					$str_out.= $tmp_str;
				}
			}
		}
			return $str_out;
	}
	
	
		function getTemplateBlock($str,$tab,$full_key='',$dot,$current_key,$value,$cfg,$tab_int){
			//echo '#$str: <pre>'; print_r($str); echo "</pre><br />\r\n";
			//echo '#$tab: <pre>'; print_r($tab); echo "</pre><br />\r\n";
			//echo '#$full_key: <pre>'; print_r($full_key); echo "</pre><br />\r\n";
			//echo '#$value: <pre>'; print_r($value); echo "</pre><br />\r\n";
			//echo '#$current_key: <pre>'; print_r($current_key); echo "</pre><br />\r\n";
			$current_key = preg_replace("/&#?[a-z0-9]{2,8};|:/i","",($current_key));
			return '' 
				//.'$tab_int:'.$tab_int
				//.'$tab_int|'.$this->getTabCount($tab)
				//.'|'
						//.'|'.$tab.'|'
						.$tab
						.$cfg['tag_block'][0]
						.$this->getB($cfg['tag_block_for'][0])
						.$cfg['tag_block_open_close'][0]
						.(
							$cfg['tag_block_for_place_tmp_var']==1
							?
								$full_key
								:
								($this->getTabCount($tab)==1
									?$cfg['tag_variable_join'][0]
									:$cfg['tag_variable'][2]
								)
							.$current_key
							.$cfg['tag_variable_join'][1]
						)
						.$cfg['tag_block_for'][2]
						.($cfg['tag_block_for_place_tmp_var']==1?$current_key:$full_key)
						.$cfg['tag_block_open_close'][1]
						.$cfg['tag_block'][1]
						.$tab
						.$this->getBR()
						.$str
						.$tab
						.$cfg['tag_block'][0]
						.$this->getB($cfg['tag_block_for'][1])
						.$cfg['tag_block'][1]
						.$this->getBR()
						;
		}
		
		function getTemplateValue($tab,$full_key='',$dot,$current_key,$value,$cfg,$in_sub_array_flag){
			//if (!$if_show){
				//$in_sub_array_flag= false;
			//}
			if ($full_key=='') 
				$dot= '';
			return ''
				.$tab
				.$this->getInput(''
				.(!$this->if_on?'':''
					.$cfg['tag_block'][0]
					.($cfg['tag_block_if'][0])
					.$cfg['tag_block_open_close'][0]
					.$full_key.$dot.preg_replace("/&#?[a-z0-9]{2,8};|:/i","",$current_key)
					.$cfg['tag_block_open_close'][1]
					.$cfg['tag_block'][1]
				)
					.(!$this->if_echo?'':''
						.$cfg['tag_variable'][0]
						.($in_sub_array_flag==false
							?
							($cfg['tag_block_init_var']==true?$cfg['tag_variable_join'][0]:'')
							:'')
						.($in_sub_array_flag!=false?$cfg['tag_variable'][2]:'')
						//.($in_sub_array_flag==true?$cfg['tag_variable'][5]:'')
						.$full_key.$dot.preg_replace("/&#?[a-z0-9]{2,8};|:/i","",$current_key)
						.$cfg['tag_variable_join'][1]
						//.($in_sub_array_flag==false?$cfg['tag_variable_join'][1]:'')
						.$cfg['tag_variable'][1]
					)
				.(!$this->if_on?'':''
					.$cfg['tag_block'][0]
					.($cfg['tag_block_if'][1])
					.$cfg['tag_block'][1]
				)
			)
			.(!$this->comment?'':''
				//.$this->getInput(''
					.($value==''||is_array($value)?'':''
						.$this->getHTMLGray(''
							.$cfg['tag_comment'][0]
							.$full_key.$dot.preg_replace("/&#?[a-z0-9]{2,8};|:/i","",$current_key)
							.$cfg['tag_variable_join'][1]
							.' = '
							.$cfg['tag_comment'][2].$value
							.$cfg['tag_comment'][1]
		//					.htmlspecialchars('<br />')
						)
						)
						//,$input=true
				//)
				)
				.$this->getBR()
				;
		}
	
	
		function getTabCount($shift) {
			return ceil(strlen($shift)/6/3);
		}
		
		function getTab($shift) {
				$i=0;
				$nbsp = '&nbsp;';
				while($i <= $shift) {
					$i++;
					$tab .= $nbsp.$nbsp.$nbsp;
				}
				return $tab;
			}
			
			
			function getBR($tab='') {
				return '<br />'.$tab;
			}
			
			function getB($str) {
				return '<b>'.$str.'</b>';
			}
			
			function getInput($str,$input) {
				//if (!$this->if_on&&!$this->for_on&&$this->comment&&!$input) return $srt;
					
				$strlen = strlen($str);
				if ($strlen>80){
					$strlen-=round($strlen/100*20,0);
				}else {
					$strlen+=round($strlen/100*20,0);
				}		
				//	else {
			//		$strlen-=1;
			//	}
				//return '<textarea style="outline: none;" rows="1" cols="'.strlen($str).'">'.$str.'</textarea>';
				//return '<input onclick="document.getElementById(this).select();" size="'.$strlen.'" value="'.$str.'"/>';
				return '<input onclick="this.select();" size="'.$strlen.'" value="'.$str.'"/>';
			}
			
			
			function getHTMLGray($str) {
				return '<font color="lightgray">'.$str.'</font>';
			}
}
?>
