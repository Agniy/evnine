//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evFunc
	* <br /> Плагин для запуска функций после аякс запроса или первой загрузки страницы.
	* <br />
	* <br /> Двойная лицензия MIT или GPL v.2 
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {object}  [=undefined]
	*  Доступ для плагина запуска функций
	* 
	* @config {object} [controller={paramName:'c',defaultValue:'default'}]
	*  Параметр для контроллера и значение по умолчанию
	* 
	* @config {object} [method={paramName:'m',defaultValue:'default'}]
	*  Параметр для метода и значение по умолчанию
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode='.html$']
	*  Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {object} [setFunction:{function_name:function()}=undefined]
	*  Функция для запуска после аякс загрузки страницы
	* 
	* @config {function} [setFunction[function_name]($options)=undefined]
	*  Передаём опции для того что бы иметь доступ к настройкам 
	* 
	* @config {function:return boolean} [setFunction.isHasAccess($obj,$options)=undefined]
	*  Проверяем доступ, инициализируем с объектом, функции из setFunction()
	*  А так же передаём опции<br />
	* 
	* @config {string} [strUnionControllerWithMethod='.']
	*  Символ для объединения методов в setFuncByEvnineParamMatch
	*
	* @config {object} [setFuncByEvnineParamMatch:<br />
	* {arguments.controller+arguments.strUnionControllerWithMethod+arguments.method:function_name}=undefined]
	*  Контроллер.метод и связанная с ним функции 
	*
	* @config {object} [setFuncByEvnineParamMatch:{string:function_name}=undefined]  
	*  Ссылка и связанная с ней функции
	*
	* @config {object} [setFuncByHREFMatch:{RegExp:function_name}=undefined]  
	*  Регулярное выражение и связанная с ней функции 
	* 
	* @config {boolean} [debugToConsole=false]
	*  Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} [debugPrefixString='| ']
	*  Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	*  Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
	*
	* @config {boolean} [debugFunctionGroup=false]
	*  Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций  
	*/
new function (document, $, undefined) {
	jQuery.evFunc = function($rewrite_options) {
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='evFunc'+'.';
		/**
			*  Настройки по умолчанию
			* 
			* @example
			* isHREFMatchThisRegExpSetSEFMode :'.html$',
			* controller                      :{paramName:'c',defaultValue:'default'},
			* method                          :{paramName:'m',defaultValue:'default'},
			* debugToConsole                  :true,
			* debugPrefixString               :'|	',
			* debugToConsoleNotSupport        :false,
			* debugFunctionGroup              :false,
			* strUnionControllerWithMethod    :'.'
			* 
			*/
		var $options = jQuery.extend({
			isHREFMatchThisRegExpSetSEFMode   :'.html$',//=$href.match(/\.html/g)
			/**
				*  Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
				*/
			controller:{
				paramName:'c',defaultValue:'default'
			},
			/**
				*  Параметр для контроллера и значение по умолчанию
				*/
			method:{
				paramName:'m',defaultValue:'default'
			},
			/**
				*  Параметр для метода и значение по умолчанию
				*/
			debugToConsole                    :true,
			/**
				*  Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
				*/
			debugPrefixString                 :'|	',
			//debugPrefixString               :' ',
			/**
				*  Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
				*/
			debugToConsoleNotSupport          :false,
			//debugToConsoleNotSupport        :true,
			/**
				*  Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
				*/
			debugFunctionGroup              :false,
			//debugFunctionGroup                :true,
			/**
				*  Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций  
				*/
			strUnionControllerWithMethod    :'.'
			/**
				*  Символ для объединения методов в setFuncByEvnineParamMatch
				*/
			//setFuncByEvnineParamMatch       :{
				//'default.default'               :'default',
			//}
			/**
				*  Контроллер.метод и связанная с ним функции
				*/
			//setFuncByHREFMatch              :{
				//'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			/** 
				*  Ссылка и связанная с ней функции
				*/
			//setFuncByMatchRegHREF              :{
				//'.*index\.php.*'                :'default'
			//}
			/**
				*  Регулярное выражение и связанная с ней функции
				*/
		},$rewrite_options);
		/**
			*  Флаг плагина evNav для первой загрузки страницы
			* @example
			* jq.evnine.nav.js
			* if ($options.flag_ev_func&&!$options.setJSFuncForLoadPage.$reload_page){
			*  $options.setJSFuncForLoadPage.setMethodAndControllerFunc('init');
			* }
			* 
			* @access public
			*/
		this.$reload_page=false;
		/**
			*  Подгруженные скрипты
			* @example
			* '/jq.ui.js': true
			* @access private
			*/
		var $include_scripts={};
		/**
			*  Методы загруженной страницы
			* @example
			*  $options.controller.paramName:'',
			*  $options.method.paramName:'',
			* @access private
			*/
		var $current_method_class=false;
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'init BEGIN');
		/**
			*  Объект для хранения загруженных методов и новых
			* @example 
			* 'from' : {
			*  $options.controller.paramName:'',
			*  $options.method.paramName:'',
			*  url:''
			* },
			* 'to'   : {
			*  $options.controller.paramName:'',
			*  $options.method.paramName:'',
			*  url:''
			* };
			* @access private
			*/
		var $loaded_state={
			'from' : {},'to'   : {}};
		/** $options.include_once=function($script_href,$fun)
			* @access public
			* 
			*  Получить скрипт и выполнить функцию после его загрузки
			* 
			* @param {string} $script_href
			*  Ссылка для загрузки скрипта
			* 
			* @param {function} $fun
			*  Функция, которую запустим после загрузки скрипта
			*
			* @return void
			*/
		$options.include_once=function($script_href,$fun) {
			/**
				*  Сохраняем функцию для последующего доступа
				*/
			var $function=$fun;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'include_once()');
			if ($include_scripts[$script_href]==undefined){
			/**
				*  Проверяем загружен ли скрипт
				*/
				jQuery.getScript($script_href, function() {
					/**
						*  Ставим флаг что скрипт загружен
						*/
					$include_scripts[$script_href]=true;
					jQuery(document).ready(function(){
						if ($options.debugToConsole) 
							console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
						try {
							/**
								*  Учитываем возможное появление ошибки. Запускаем переданную функцию
								*/
							$function();
						}catch($e){
							/**
								*  Учтём возможные ошибки
								*/
							if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
						}
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
					});
				});
			}else {
			/**
				*  Случай если скрипт уже загружен, запустим callback function
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
				try {
					/**
						*  Учитываем возможное появление ошибки. Запускаем переданную функцию
						*/
					$function();
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
			}
		};
		
		/** this.setPreCallShowResponse=function()<br />
			* 
			*  Вызываем из плагина навигации установку флага, 
			*  что не нужно перезагружать страницу
			* 
			* @access public
			* @return void
			*/
		this.setPreCallShowResponse=function() {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPreCallShowResponse() BEGIN');
			this.$reload_page=false;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPreCallShowResponse() END');
		};
		
		/** this.setPostCallShowResponse=function($load_href)<br />
			* 
			*  Функция выполняемая после отображения данных из скрипта, 
			*  указанная в опциях
			* 
			* @param {string} $load_href
			*  Ссылка для обработки
			*
			* @access public
			* @return void
			*/
		this.setPostCallShowResponse=function($load_href) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') BEGIN');
			/**
				*  Разбираем адрес загруженной страницы. Сохраним параметры загрузки адреса
				*/
			this.getParseURLAndSave($load_href);
			if ($loaded_state.to[$options.controller.paramName]!=undefined||
				$loaded_state.to[$options.method.paramName]!=undefined)
			{
			/**
				*  Устанавливаем функцию удаления действия
				*/
				setUnsetJSFuncForHREF('unSetAction');
			}
			/**
				*  Получить из адреса текущий контроллер и метод 
				*/
			this.setMethodAndControllerFunc();
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') END');
		};

		/** this.getParseURLAndSave=function($href)<br />
			* 
			*  Разбираем адрес загруженной страницы
			* 
			* @param {string} $href
			*  Ссылка для обработки
			* 
			* @access public
			* @return void
			*/
		this.getParseURLAndSave=function($href){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() BEGIN');
			if ($loaded_state.to.url===$href){
			/**
				*  Если адреса совпадают ничего не делаем
				*/
				return $loaded_state.to;
			}
			/**
				*  Сохраняем прошлое состояние
				*/
			$loaded_state.from= $loaded_state.to;
			/**
				*  Разберем адрес и получим метод и контроллер
				*/
			$loaded_state.to = getParseURL($href,4);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$loaded_state: ');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($loaded_state,jQuery.evDev.getTab($options.debugPrefixString,5),$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() END');
		};

		/** this.getMethodFromSEFURL=function($href)<br />
			* 
			*  Получим метод и контроллер в ЧПУ адресе
			*
			* @param {string} $href
			* 
			* @access public
			* @return {object} {$options.controller.paramName:function(),$options.method.paramName:function()}
			*/
		this.getMethodFromSEFURL=function($href){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'getMethodFromSEFURL() BEGIN');
			$href = $href.substring(1);
			$method_replace  = $href.match(/.*\//);
			$method_replace=$method_replace.toString().split(/\//);
			if ($method_replace[1].match(/=/)){
				$controller= $method_replace[0];
				$method='';
			}else {
				$controller=$method_replace[0];
				$method=$method_replace[1];
			}
			var $obj={};
			$obj[$options.controller.paramName]=$controller;
			$obj[$options.method.paramName]=$method;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'getMethodFromSEFURL() END');
			return $obj;
		};

		/** getParseURL($href,$tab_level)<br />
			* 
			*  Разберем адрес и получим метод и контроллер
			* 
			* @param {string} $href
			*  Ссылка для обработки
			* 
			* @param {int} $tab_level
			*  Отступы в режиме отладке
			* 
			* @access private
			* @return {object} {$options.controller.paramName:function(),$options.method.paramName:function(),$url=string}
			* <br /> Возвращаем объект для последующего сохранения и получения функции,<br /> по методу и контроллеру
			*  
		*/
		function getParseURL($href,$tab_level){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') BEGIN');
			if ($href===undefined){
				return false;
			}
			$href=$href.replace(/^.*\?/,"?");
			$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
			if ($href.match($reg)){
			/**
				*  Если совпадает с ЧПУ адресом
				*/
				$parse_url = this.getMethodFromSEFURL($href);
			}else {
			/**
				*  Используем плагин для разбора ссылки
				*/
				$parse_url = $.parseQuery($href);
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+$EVNINE_NAME+'$parse_url:');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($parse_url,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+2));
			if (!$parse_url[$options.controller.paramName] || $parse_url[$options.controller.paramName]===null || $parse_url[$options.controller.paramName]==='' || $parse_url[$options.controller.paramName]==undefined){
				/**
					*  Проверяем указан ли контроллер в ссылке?
					*/
				$controller=$options.controller.defaultValue;
			}else {
			/**
				*  В случае если контроллер не указан,<br /> 
				*  используем значение по умолчанию из опций
				*/
				$controller=$parse_url[$options.controller.paramName];
			}
			if (!$parse_url[$options.method.paramName] || $parse_url[$options.method.paramName]===null || $parse_url[$options.method.paramName]==='' || $parse_url[$options.method.paramName]==undefined){
				/**
					*  Проверяем указан ли метод в ссылке?
					*/
				$method=$options.method.defaultValue;
			}else {
			/**
				*  В случае если метод не указан,<br /> 
				*  используем значение по умолчанию из опций
				*/
				$method=$parse_url[$options.method.paramName];
			}
			var $obj={};
			$obj[$options.controller.paramName]=$controller;
			$obj[$options.method.paramName]=$method;
			$obj.url=$href;
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+'return ');
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') END');
			return $obj;
		}

		/** setUnsetJSFuncForHREF($function_callback)<br />
		 * 
		 *  Устанавливаем функцию удаления действия
		 * 
		 * @param {string} $function_callback
		 *  Функция обратного вызова
		 * 
		 * @access private
		 * @return void
		 */
		function setUnsetJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					*  Изменился ли контроллер с прошлого вызова? 
					*  Если да, запускаем callback function.
					*/
				if ($loaded_state.from[$options.controller.paramName]!==$loaded_state.to[$options.controller.paramName]){
					setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				}
				/**
					*  Изменился ли метод с прошлого вызова? 
					*  Если да, запускаем callback function.
					*/
				if ($loaded_state.from[$options.method.paramName]!==$loaded_state.to[$options.method.paramName]){
					setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
				}
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		
		/** setInitJSFuncForHREF($function_callback)<br />
			* 
			*  Инициализируем функцию для обратного вызова.
			* 
			* @param {string} $function_callback
			*  Функция обратного вызова
			* 
			* @access private
			* @return void
			*/
		function setInitJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					*  Для контроллера и метода из ссылки получим методы
					*/
				$current_method_class = getFuncForControllerAndMethod();
				setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		
		/** setRunFunction($obj,$before,$after,$function_callback)<br />
			* 
			*  Запустим функцию.
			* 
			* @param {object} $obj
			*  Объект с методами
			* 
			* @param {string} $function_callback
			*  Функция обратного вызова
			*
			* @param {string} $before
			*  Метод или контроллер до
			* 
			* @param {string} $after
			*  Метод или контроллер после
			*
			* @access private
			* @return {boolean}
			* <br /> true - если функция успешно выполнена.<br /> false - если функцию запустить не удалось 
			*/ 
		function setRunFunction($obj,$before,$after,$function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() BEGIN');
			if ($obj==undefined){
			/**
				*  Если не указан объект.
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$obj==undefined');
				return false;
			}
			if ($before!==$after){
				/**
					*  Сравниваем метод и контроллер до и после
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']!==[$after='+$after+']');
				if (isHasAccess($obj)){
					/**
						*  Проверяем, есть ли доступ к функции из опций?
						*/
					$obj = $obj[$function_callback];
					if (typeof $obj==='function'){
						/**
							*  Выполняется если в объекте указана функция
							*/
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true, case:$obj===function');
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction."+$function_callback+"()");
						$obj();
						return true;
					}else {
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$before!==$after ');
						return false;
					}
				}
			}else {
				/**
					*  Случай обновления той же страницы, когда метод или контроллер не изменился
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']===[$after='+$after+']');
				if ($obj.setReloadPageAction==undefined){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:setReloadPageAction==undefined');
					return false;
				}
				if (isHasAccess($obj)){
					/**
						*  Проверяем, есть ли доступ к функции из опций?
						*/
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction.setReloadPageAction()");
					$obj.setReloadPageAction();
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true');
					return true;
				}
			}
		}

		/** setMethodAndControllerFunc=function($mode)<br />
			*  Установить контроллер и метод из ссылки.
			* 
			* @param {string} [$mode='']
			*  Флаг для проверки первой инициализации
			*
			* @access public
			* @return void
			*/
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') BEGIN');
			if ($mode==='init'){
				/**
					*  Если первая инициализация
					*  Для установки начальных значений состояний до и после.
					*/
				$loaded_state.to = getParseURL(location.pathname,4);
				/**
					*  Инициализируем функцию для обратного вызова
					*/
				setInitJSFuncForHREF('setAction');
				$loaded_state.from= $loaded_state.to;
			} else {
				/**
					*  Инициализируем функцию для обратного вызова
					*/
				setInitJSFuncForHREF('setAction');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') END');
		};
	
		/** isHasAccess($obj)<br />
			*  Проверяем, есть ли доступ к функции из опций?
			* 
			* @param {object} $obj
			*  Объект с методом. Проверим есть ли доступ?
			* 
			* @example
			* $.evNav(
			* {
			*  setJSFuncForLoadPage:$.evFunc(
			*  {
			*   setFunction:
			*   {
			*    'default':function($options) 
			*    {
			*      Уровень доступа 
			*     this.access='1';
			*    }
			*   }
			*  })
			* });
			* 
			* @access private
			* @return {boolean}
			*  по умолчанию true
			*/
		function isHasAccess($obj) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() BEGIN');
			if ($options.setFunction.isHasAccess!=undefined){
				/**
					*  Если объект задан. 
					*  Возвращаем ответ от метода проверки доступа.
					*  Который задан в опциях.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun $options.setFunction.isHasAccess($obj,$options)');
				return $options.setFunction.isHasAccess($obj,$options);
			}else {
				/**
					*  Если проверки нет всегда возвращаем - доступ есть.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun true');
				return true;
			}
		}

		/** function getFuncForControllerAndMethod()<br />
			* 
			*  Для контроллера и метода из ссылки получим методы
			* 
			* @access private
			* @return {object} {[$options.controller.paramName],$obj[$options.method.paramName]}
			*/
		function getFuncForControllerAndMethod(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() BEGIN');
			var $obj={};
			/**
				*  Получить функцию по ключу из опций 
				*  Ключ из парсинга ссылки
				*/
			$obj[$options.controller.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]);
			$obj[$options.method.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]+$options.strUnionControllerWithMethod+$loaded_state.to[$options.method.paramName]);
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,6)+'return ',$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() END');
			return $obj;
		}
	
		/** function getFunctionFromOptions($key)<br />
			*  Получить функцию по ключу из опций 
			* 
			* @param {string} $key
			*  Ключ из парсинга ссылки
			* 
			* @access private
			* @return {object} $options.setFuncByEvnineParamMatch[$key] or undefined
			*/
		function getFunctionFromOptions($key){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() BEGIN');
			var $setFunctionKey='';
			try{
				if ($options.setFuncByEvnineParamMatch[$key]!=undefined){
				/**
					*  Проверяем существует ли ключ?
					*/
					$setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
					/**
						*  Вернем функцию для вызова
						*/
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}
				if ($options.setFuncByHREFMatch!=undefined) {
				/**
					*  Проверим совпадение с адресом
					*/
						if ($options.setFuncByHREFMatch[$loaded_state.to.url]!=undefined){
						$setFunctionKey = $options.setFuncByHREFMatch[$loaded_state.to.url];
						/**
							*  Если объект в массиве является функцией
							*/
						if (typeof $options.setFunction[$setFunctionKey]==='function'){
							if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByHREFMatch');
							return new $options.setFunction[$setFunctionKey]($options);
						}
					}
				}
				if ($options.setFuncByMatchRegHREF!=undefined) {
				/**
					*  Проверим совпадение с регулярным выражением
					*/
					var obj={};
					$.each($options.setFuncByMatchRegHREF, function($href_reg,$setFunctionKey){
						$reg = new RegExp($href_reg,"g");
						if ($loaded_state.to.url.match($reg)){
						/**
							*  Если объект в массиве является функцией
							*/
							if (typeof $options.setFunction[$setFunctionKey]==='function'){
								$obj=new $options.setFunction[$setFunctionKey]($options);
							}
						}
					});
					if ($obj!=undefined){
					/**
						*  Если объект указан возвращаем его
						*/
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByMatchRegHREF');
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() END return object');
						return $obj;
					}
				}
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions'+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() END return this');
			return undefined;
		}
		/**
			*  Ссылка на себя для доступа из jQuery evNav plugin
			*/
		return this;
	};
}(document, jQuery);
//</script>
