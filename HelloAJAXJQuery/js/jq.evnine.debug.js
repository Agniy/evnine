//<script type="text/javascript">
/*
	* JQuery AJAX Debug Evnine
	* ver 0.3
 *
 * Copyright 2011, (c) ev9eniy.info
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
new function (document, $, undefined) {
	jQuery.setEvnineDebug={
	// The current version of Evnine Debug being used

	//en:HELPER Debug
	/*ru:Помощник для олтадки*/
	initNotSupport:function($debugToConsoleNotSupport){
	if ($debugToConsoleNotSupport){
		window.console=new Object();
		var debug_buffer='';
		window.console.warn=function($str){
			alert('INFO:'+debug_buffer+"\n\r"+'WARN:'+"\n\r\t"+$str);
			debug_buffer='';
		};
		window.console.info=function($str){
			debug_buffer+="\n\r"+$str;
		};
		window.console.error=function($str){
			debug_buffer+="\n\rERROR:"+$str;
		};
	
		}
	},
	getTabByLevel:function($debugPrefixString,$shift){
		var $tab='';
		$i=0;
		while($i <= $shift) {
			$i++;
			$tab=$tab+$debugPrefixString;
		}
		return $tab;
	},
	/**
	* ru:Описать содержимое объекта с отступами
	*/
getTraceObject:function($obj,$tab,$shift){//Показываем свойства объектов
	$show_function=false;
		var s = "";
		if ($shift==undefined){
			$shift=' ';
		}
		if ($tab===undefined) $tab='';
		for (prop in $obj)
		{
			$typeof = typeof $obj[prop];
			if ($typeof != "function" && $typeof != "object")
			{
				s = $tab+"[" + prop + "] => " + $obj[prop] + "";
				console.info(s);
			}else if($typeof === "object"){
					s = $tab+"[" + prop + "] => "+typeof $obj[prop]+" (";
					console.info(s);
					this.getTraceObject($obj[prop],$tab+$shift);
					s= $tab+")";
					console.info(s);
			}else if($typeof === "function"){
				if ($show_function){
					console.warn($tab+"[" + prop + "] => "+$obj[prop].toString().split("\n").join("\n"+$tab));
				}else {
					console.info($tab+"[" + prop + "] => function ");
				}
			}
		}
	}
	};
}(document, jQuery);

//</script>
