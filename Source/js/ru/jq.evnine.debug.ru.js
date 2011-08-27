//<script type="text/javascript">
new function (document, $, undefined) {
/** 
	* @class jQuery.evDev
	* <br /> Плагин для отладки кода.
	* <br />
	* <br />
	* <br /> Двойная лицензия MIT или GPL v.2 
	*/
jQuery.evDev={
	/**
		*  Создаём отступы<br />
		*  Пример вызова<br />
		* this.getTab('| ',0)=''<br />
		* this.getTab('| ',1)='| '<br />
		* this.getTab('| ',2)='| | '
		*
		* @param {string} [$debugPrefixString='']
		*  Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
		*
		* @param {int} [$shift_int='']
		*  Начальный сдвиг для отображения вложенности
		* 
		* @return {string} $tab=''
		* <br /> вернем отступы
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
	 *  Отследить вызов функции<br />
	 *  (c) Эрик Венделин, eriwen.com/javascript<br />
	 * 
	 * @return object
	 */
	getTraceFunction:function() {
		var callstack = [];
		var isCallstackPopulated = false;
		try {
			/**
			*/
			i.dont.exist+=0; 
		} catch(e) {
			var lines = e.stack.split('\n');
			if (e.stack) { 
			/**
				*/
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						lines[i] = lines[i].replace(/@.*|(?=showResponse).*/,"");
						callstack.push(lines[i]);
					}
				}
				/**
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
			else if (window.opera && e.message) { //Opera
				for (i=0, len=lines.length; i<len; i++) {
					if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
						var entry = lines[i];
						/**
							*/
						if (lines[i+1]) {
							entry += ' at ' + lines[i+1];
							i++;
						}
						callstack.push(entry);
					}
				}
				/**
					*/
				callstack.shift();
				isCallstackPopulated = true;
			}
		}
			/**
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
		*  Вывести содержимое объекта с отступами для отладки
		* 
		* @param {object} [$obj=undefined]
		*  Объект для вывода
		*
		* @param {string} [$tab='']
		* getTraceObject($obj[prop],$tab=$tab+$shift);
		*  Отступ, который изменяется при выводе вложенных объектов
		* 
		* @param {string} [$shift=' ']
		*  Начальный сдвиг для отображения вложенности
		* 
		* @param {boolean} [$show_function=false]
		*  Вывести методы в объекте
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
		*  Функция вывода группировки в консоле
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		*  Использовать буфер для консоли и делать alert()
		* 
		* @return void
		*/
	initGroupFunctionCall:function($debugToConsoleNotSupport){
		if (!$debugToConsoleNotSupport){
			/**
			 *  Заменяем базовые методы работы с консолью
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
		* <br /> Если в браузере консоль не поддерживается, 
		* <br /> используем альтернативный способ, собирая в буфер все данные
		* 
		* @param {boolean} [$debugToConsoleNotSupport=false]
		*  Использовать буфер для консоли и делать alert()
		* @return void
		*/
	initNotSupport:function($debugToConsoleNotSupport){
		if ($debugToConsoleNotSupport){
			/**
			 *  Создаём новый объект для работы с консолью
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
