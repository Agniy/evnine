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
	jQuery.evDev={
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
		
	initGoupFunctionCall:function($debugToConsoleNotSupport){
	if (!$debugToConsoleNotSupport){
		window.console.warn_old=window.console.warn;
		window.console.info_old=window.console.info;
		window.console.warn=function($str){
			if ($str.match(/END/)){
				console.groupEnd();
			}else if ($str.match(/BEGIN$/)){
				console.group($str);
			}else {
				window.console.warn_old($str);
			}
		};
		window.console.info=function($str){
			window.console.info_old($str);
		};
		}
	},
		getTab:function($debugPrefixString,$shift){
			if ($shift===0){
				return '';
			}
		var $tab='';
		$i=1;
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
},
	
/**
 *  ru:Отследить вызов функции
 */
getTraceFunction:function() {
	var callstack = [];
	var isCallstackPopulated = false;
	try {
		i.dont.exist+=0; //doesn't exist- that's the point
	} catch(e) {
		var lines = e.stack.split('\n');
		if (e.stack) { //Firefox
			for (i=0, len=lines.length; i<len; i++) {
				if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
					lines[i] = lines[i].replace(/@.*|(?=showResponse).*/,"");
					callstack.push(lines[i]);
				}
			}
			//Remove call to printStackTrace()
			callstack.shift();
			isCallstackPopulated = true;
		}
		else if (window.opera && e.message) { //Opera
			for (i=0, len=lines.length; i<len; i++) {
				if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
					var entry = lines[i];
					//Append next line also since it has the file info
					if (lines[i+1]) {
						entry += ' at ' + lines[i+1];
						i++;
					}
					callstack.push(entry);
				}
			}
			//Remove call to printStackTrace()
			callstack.shift();
			isCallstackPopulated = true;
		}
	}
	if (!isCallstackPopulated) { //IE and Safari
		var currentFunction = arguments.callee.caller;
		while (currentFunction) {
			var fn = currentFunction.toString();
			var fname = fn.substring(fn.indexOf("function") + 8, fn.indexOf('')) || 'anonymous';
			callstack.push(fname);
			currentFunction = currentFunction.caller;
		}
	}
	return callstack.reverse();
}
};
}(document, jQuery);

//</script>
