<?
function getPHPUnitAllCase($template,$all_class,$param_const,$filter){
	echo '#$template: <pre>'; if(function_exists(print_r2)) print_r2($template); else print_r($template);echo "</pre><br />\r\n";
	echo '#$all_class: <pre>'; if(function_exists(print_r2)) print_r2($all_class); else print_r($all_class);echo "</pre><br />\r\n";
	echo '#$param_const: <pre>'; if(function_exists(print_r2)) print_r2($param_const); else print_r($param_const);echo "</pre><br />\r\n";
	echo '#$filter: <pre>'; if(function_exists(print_r2)) print_r2($filter); else print_r($filter);echo "</pre><br />\r\n";
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
//echo '#$SaController_case[$template]: <pre>'; if(function_exists(print_r2)) print_r2($SaController_case[$template]); else print_r($SaController_case[$template]);echo "</pre><br />\r\n";
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
echo '#$save_case: <pre>'; if(function_exists(print_r2)) print_r2($save_case); else print_r($save_case);echo "</pre><br />\r\n";
return $save_case;
}
  ?>
