//<script type="text/javascript">
new function (document, $, undefined) {
/** 
	* @class jQuery.evDev
	* <br />en: jQuery Plugin - Debug for evnine plugins
	* <br />ru: Плагин для отладки кода.
	* <br />
	* <br />en: Copyright 2011, (c) ev9eniy.info
	* <br />en: Dual licensed under the MIT or GPL Version 2 licenses
	* <br />
	* <br />ru: Двойная лицензия MIT или GPL v.2 
	*/
jQuery.evDev={
	/**
		* en: Return the indentation<br />
		* en: Example Call<br />
		* ru: Создаём отступы<br />
		* ru: Пример вызова<br />
		* this.getTab('| ',0)=''<br />
		* this.getTab('| ',1)='| '<br />
		* this.getTab('| ',2)='| | '
		*
		* @param {string} [$debugPrefixString='']
		* en: Debug prefix for group of functions<br />
		* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
		*
		* @param {int} [$shift_int='']
		* en: Base shift to show nesting<br />
		* ru: Начальный сдвиг для отображения вложенности
		* 
		* @return {string} $tab=''
		* <br />en: return the indentation
		* <br />ru: вернем отступы
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
	 * en: Get stack trace<br />
	 * en: (c) Eric Wendelin, eriwen.com/javascript<br />
	 * ru: Отследить вызов функции<br />
	 * ru: (c) Эрик Венделин, eriwen.com/javascript<br />
	 * 
	 * @return object
	 */
	getTraceFunction:function() {
		var callstack = [];
		var isCallstackPopulated = false;
		try {
			/**
			* en: doesn't exist- that's the point
			*/
			i.dont.exist+=0; 
		} catch(e) {
			var lines = e.stack.split('\n');
			if (e.stack) { 
			/**
				* en: Firefox
				*/
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						lines[i] = lines[i].replace(/@.*|(?=showResponse).*/,"");
						callstack.push(lines[i]);
					}
				}
				/**
					* en: Remove call to printStackTrace()
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
			else if (window.opera && e.message) { //Opera
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						var entry = lines[i];
						/**
							* en: Append next line also since it has the file info
							*/
						if (lines[i+1]) {
							entry += ' at ' + lines[i+1];
							i++;
						}
						callstack.push(entry);
					}
				}
				/**
					* en: Remove call to printStackTrace()
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
		}
			/**
				* en: IE and Safari
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
		* en: List contents of an object with spaces for debugging<br />
		* ru: Вывести содержимое объекта с отступами для отладки
		* 
		* @param {object} [$obj=undefined]
		* en: Object to output<br />
		* ru: Объект для вывода
		*
		* @param {string} [$tab='']
		* getTraceObject($obj[prop],$tab=$tab+$shift);
		* en: Indent, which changes the derivation of children objects<br />
		* ru: Отступ, который изменяется при выводе вложенных объектов
		* 
		* @param {string} [$shift=' ']
		* en: Base shift to show nesting<br />
		* ru: Начальный сдвиг для отображения вложенности
		* 
		* @param {boolean} [$show_function=false]
		* en: Derive methods in the object
		* ru: Вывести методы в объекте
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
		* en: Function for the join functions group in the console<br />
		* ru: Функция для замены объединения функций в консоли
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		* en: Use a buffer for the console and call alert()<br />
		* ru: Использовать буфер для консоли и делать alert()
		* 
		* @return void
		*/
	initGroupFunctionCall:function($debugToConsoleNotSupport){
		if (!$debugToConsoleNotSupport){
			/**
			 * en: Replace the basic techniques of working with the console<br />
			 * ru: Заменяем базовые методы работы с консолью
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
		* en: If clients browser is not supported by the console, use an 
		* <br />en: alternative way of collecting all .warn and .info in the buffer
		* <br />
		* <br />ru: Если в браузере консоль не поддерживается, 
		* <br />ru: используем альтернативный способ, собирая в буфер все данные
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		* en: Use a buffer for the console and call alert()<br />
		* ru: Использовать буфер для консоли и делать alert()
		* @return void
		*/
	initNotSupport:function($debugToConsoleNotSupport){
		if ($debugToConsoleNotSupport){
			/**
			 * en: Create a new object to work with the console<br />
			 * ru: Создаём новый объект для работы с консолью
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
