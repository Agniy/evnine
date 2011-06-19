//<script type="text/javascript">
new function (document, $, undefined) {
/** 
	* @class jQuery.evDev
	* <br /> jQuery Plugin - Debug for evnine plugins
	* <br />
	* <br /> Copyright 2011, (c) ev9eniy.info
	* <br /> Dual licensed under the MIT or GPL Version 2 licenses
	* <br />
	*/
jQuery.evDev={
	/**
		*  Return the indentation<br />
		*  Example Call<br />
		* this.getTab('| ',0)=''<br />
		* this.getTab('| ',1)='| '<br />
		* this.getTab('| ',2)='| | '
		*
		* @param {string} [$debugPrefixString='']
		*  Debug prefix for group of functions<br />
		*
		* @param {int} [$shift_int='']
		*  Base shift to show nesting<br />
		* 
		* @return {string} $tab=''
		* <br /> return the indentation
		*/
	getTab:function($debugPrefixString,$shift_int){
		if ($shift_int===0){
			return '';
		}
		var $tab='';
		$i=1;
		while($i <= $shift_int) {
			$i++;
			$tab=$tab+$debugPrefixString;
		}
		return $tab;
	},

	/** 
	 *  Get stack trace<br />
	 *  (c) Eric Wendelin, eriwen.com/javascript<br />
	 * 
	 * @return object
	 */
	getTraceFunction:function() {
		var callstack = [];
		var isCallstackPopulated = false;
		try {
			/**
			*  doesn't exist- that's the point
			*/
			i.dont.exist+=0; 
		} catch(e) {
			var lines = e.stack.split('\n');
			if (e.stack) { 
			/**
				*  Firefox
				*/
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						lines[i] = lines[i].replace(/@.*|(?=showResponse).*/,"");
						callstack.push(lines[i]);
					}
				}
				/**
					*  Remove call to printStackTrace()
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
			else if (window.opera && e.message) { //Opera
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						var entry = lines[i];
						/**
							*  Append next line also since it has the file info
							*/
						if (lines[i+1]) {
							entry += ' at ' + lines[i+1];
							i++;
						}
						callstack.push(entry);
					}
				}
				/**
					*  Remove call to printStackTrace()
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
		}
			/**
				*  IE and Safari
				*/
			if (!isCallstackPopulated) {
			var currentFunction = arguments.callee.caller;
			while (currentFunction) {
				var fn = currentFunction.toString();
				var fname = fn.substring(fn.indexOf("function") + 8, fn.indexOf('')) || 'anonymous';
				callstack.push(fname);
				currentFunction = currentFunction.caller;
			}
		}
		return callstack.reverse();
	},

		/** 
		*  List contents of an object with spaces for debugging<br />
		* 
		* @param {object} [$obj=undefined]
		*  Object to output<br />
		*
		* @param {string} [$tab='']
		* getTraceObject($obj[prop],$tab=$tab+$shift);
		*  Indent, which changes the derivation of children objects<br />
		* 
		* @param {string} [$shift=' ']
		*  Base shift to show nesting<br />
		* 
		* @param {boolean} [$show_function=false]
		*  Derive methods in the object
		* 
		* @return void
		*/
	getTraceObject:function($obj,$tab,$shift,$show_function){
		if ($show_function===undefined){
			$show_function=false;
		}
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
		*  Function for the join functions group in the console<br />
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		*  Use a buffer for the console and call alert()<br />
		* 
		* @return void
		*/
	initGroupFunctionCall:function($debugToConsoleNotSupport){
		if (!$debugToConsoleNotSupport){
			/**
			 *  Replace the basic techniques of working with the console<br />
			 */
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
		
	/**
		*  If clients browser is not supported by the console, use an 
		* <br /> alternative way of collecting all .warn and .info in the buffer<br />
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		*  Use a buffer for the console and call alert()<br />
		* @return void
		*/
	initNotSupport:function($debugToConsoleNotSupport){
		if ($debugToConsoleNotSupport){
			/**
			 *  Create a new object to work with the console<br />
			 */
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
	}
	
};
}(document, jQuery);

//</script>
