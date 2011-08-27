//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evFunc
	* <br />en: jQuery plugin - call function after ajax complete or reload page
	* <br />ru: Плагин для запуска функций после аякс запроса или первой загрузки страницы.
	* <br />
	* <br />en: Dual licensed under the MIT or GPL Version 2 licenses
	* <br />ru: Двойная лицензия MIT или GPL v.2 
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {object}  [=undefined]
	* en: Init evFunc plugin
	* ru: Доступ для плагина запуска функций
	* 
	* @config {object} [controller={paramName:'c',defaultValue:'default'}]
	* en: Parameter for the controller and the default value
	* ru: Параметр для контроллера и значение по умолчанию
	* 
	* @config {object} [method={paramName:'m',defaultValue:'default'}]
	* en: Parameter to the method and the default value
	* ru: Параметр для метода и значение по умолчанию
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode='.html$']
	* en: If match RegExp, use SEF mode
	* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {object} [setFunction:{function_name:function()}=undefined]
	* en: Functions for execute after ajax complete
	* ru: Функция для запуска после аякс загрузки страницы
	* 
	* @config {function} [setFunction[function_name]($options)=undefined]
	* en: Init with $options
	* ru: Передаём опции для того что бы иметь доступ к настройкам 
	* 
	* @config {function:return boolean} [setFunction.isHasAccess($obj,$options)=undefined]
	* en: Is user has access for function, init with (setFunction[function_name],$options)
	* ru: Проверяем доступ, инициализируем с объектом, функции из setFunction()
	* ru: А так же передаём опции<br />
	* 
	* @config {string} [strUnionControllerWithMethod='.']
	* en: The symbol for the union of controller and methods in the setFuncByEvnineParamMatch
	* ru: Символ для объединения методов в setFuncByEvnineParamMatch
	*
	* @config {object} [setFuncByEvnineParamMatch:<br />
	* {arguments.controller+arguments.strUnionControllerWithMethod+arguments.method:function_name}=undefined]
	* en: Controller with method and function
	* ru: Контроллер.метод и связанная с ним функции 
	*
	* @config {object} [setFuncByEvnineParamMatch:{string:function_name}=undefined]  
	* en: URN and related functions
	* ru: Ссылка и связанная с ней функции
	*
	* @config {object} [setFuncByHREFMatch:{RegExp:function_name}=undefined]  
	* en: RegExp and associated functions<br />
	* ru: Регулярное выражение и связанная с ней функции 
	* 
	* @config {boolean} [debugToConsole=false]
	* en: Debug to console
	* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} [debugPrefixString='| ']
	* en: Debug prefix for group of functions<br />
	* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info
	* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
	*
	* @config {boolean} [debugFunctionGroup=false]
	* en: Use console.group as alternative to $options.debugPrefixString
	* ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций  
	*/
new function (document, $, undefined) {
	jQuery.evFunc = function($rewrite_options) {
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='evFunc'+'.';
		/**
			* en: Default setting
			* ru: Настройки по умолчанию
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
				* en: If match RegExp, use SEF mode
				* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
				*/
			controller:{
				paramName:'c',defaultValue:'default'
			},
			/**
				* en: Parameter for the controller and the default value
				* ru: Параметр для контроллера и значение по умолчанию
				*/
			method:{
				paramName:'m',defaultValue:'default'
			},
			/**
				* en: Parameter to the method and the default value
				* ru: Параметр для метода и значение по умолчанию
				*/
			debugToConsole                    :true,
			/**
				* en: Debug to console
				* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
				*/
			debugPrefixString                 :'|	',
			//debugPrefixString               :' ',
			/**
				* en: Debug prefix for group of functions
				* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
				*/
			debugToConsoleNotSupport          :false,
			//debugToConsoleNotSupport        :true,
			/**
				* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info 
				* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
				*/
			debugFunctionGroup              :false,
			//debugFunctionGroup                :true,
			/**
				* en: Use console.group as alternative to $options.debugPrefixString  
				* ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций  
				*/
			strUnionControllerWithMethod    :'.'
			/**
				* en: The symbol for the union of controller and methods in the setFuncByEvnineParamMatch
				* ru: Символ для объединения методов в setFuncByEvnineParamMatch
				*/
			//setFuncByEvnineParamMatch       :{
				//'default.default'               :'default',
			//}
			/**
				* en: Function is for controller with method.
				* ru: Контроллер.метод и связанная с ним функции
				*/
			//setFuncByHREFMatch              :{
				//'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			/** 
				* en: HREF and related functions.
				* ru: Ссылка и связанная с ней функции
				*/
			//setFuncByMatchRegHREF              :{
				//'.*index\.php.*'                :'default'
			//}
			/**
				* en: Regular expression and associated functions.
				* ru: Регулярное выражение и связанная с ней функции
				*/
		},$rewrite_options);
		/**
			* en: Flag evNav plugin for the first page load
			* ru: Флаг плагина evNav для первой загрузки страницы
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
			* en: The loaded scripts
			* ru: Подгруженные скрипты
			* @example
			* '/jq.ui.js': true
			* @access private
			*/
		var $include_scripts={};
		/**
			* en: Methods loaded page
			* ru: Методы загруженной страницы
			* @example
			*  $options.controller.paramName:'',
			*  $options.method.paramName:'',
			* @access private
			*/
		var $current_method_class=false;
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'init BEGIN');
		/**
			* en: Object to store the loaded methods and new.
			* ru: Объект для хранения загруженных методов и новых
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
			* en: Get a script and execute the function when it is loaded.
			* ru: Получить скрипт и выполнить функцию после его загрузки
			* 
			* @param {string} $script_href
			* en: Link to download script
			* ru: Ссылка для загрузки скрипта
			* 
			* @param {function} $fun
			* en: Callback function
			* ru: Функция, которую запустим после загрузки скрипта
			*
			* @return void
			*/
		$options.include_once=function($script_href,$fun) {
			/**
				* en: Save function for future access
				* ru: Сохраняем функцию для последующего доступа
				*/
			var $function=$fun;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'include_once()');
			if ($include_scripts[$script_href]==undefined){
			/**
				* en: Check whether the script is loaded
				* ru: Проверяем загружен ли скрипт
				*/
				jQuery.getScript($script_href, function() {
					/**
						* en: We set a flag that the script is loaded
						* ru: Ставим флаг что скрипт загружен
						*/
					$include_scripts[$script_href]=true;
					jQuery(document).ready(function(){
						if ($options.debugToConsole) 
							console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
						try {
							/**
								* en: Watch for errors. Run callback function.
								* ru: Учитываем возможное появление ошибки. Запускаем переданную функцию
								*/
							$function();
						}catch($e){
							/**
								* en: Case of errors.
								* ru: Учтём возможные ошибки
								*/
							if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
						}
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
					});
				});
			}else {
			/**
				* en: Case if the script is already loaded, run the callback function
				* ru: Случай если скрипт уже загружен, запустим callback function
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
				try {
					/**
						* en: Watch for errors. Run callback function.
						* ru: Учитываем возможное появление ошибки. Запускаем переданную функцию
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
			* en: Call of the plug-in navigation setup flag
			* en: there is no need to reload page
			* ru: Вызываем из плагина навигации установку флага, 
			* ru: что не нужно перезагружать страницу
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
			* en: Function to execute after displaying data from a script
			* en: specified in the options
			* ru: Функция выполняемая после отображения данных из скрипта, 
			* ru: указанная в опциях
			* 
			* @param {string} $load_href
			* en: Link to processing
			* ru: Ссылка для обработки
			*
			* @access public
			* @return void
			*/
		this.setPostCallShowResponse=function($load_href) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') BEGIN');
			/**
				* en: Parse the address loaded in the page
				* ru: Разбираем адрес загруженной страницы. Сохраним параметры загрузки адреса
				*/
			this.getParseURLAndSave($load_href);
			if ($loaded_state.to[$options.controller.paramName]!=undefined||
				$loaded_state.to[$options.method.paramName]!=undefined)
			{
			/**
				* en: Set the option to delete the action
				* ru: Устанавливаем функцию удаления действия
				*/
				setUnsetJSFuncForHREF('unSetAction');
			}
			/**
				* en: Get the address of the current controller and method.
				* ru: Получить из адреса текущий контроллер и метод 
				*/
			this.setMethodAndControllerFunc();
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') END');
		};

		/** this.getParseURLAndSave=function($href)<br />
			* 
			* en: Parse the URL
			* ru: Разбираем адрес загруженной страницы
			* 
			* @param {string} $href
			* en: Link to processing
			* ru: Ссылка для обработки
			* 
			* @access public
			* @return void
			*/
		this.getParseURLAndSave=function($href){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() BEGIN');
			if ($loaded_state.to.url===$href){
			/**
				* en: If the URL are the same do nothing
				* ru: Если адреса совпадают ничего не делаем
				*/
				return $loaded_state.to;
			}
			/**
				* en: Save the past state
				* ru: Сохраняем прошлое состояние
				*/
			$loaded_state.from= $loaded_state.to;
			/**
				* en: Obtain a method and controller of the URL
				* ru: Разберем адрес и получим метод и контроллер
				*/
			$loaded_state.to = getParseURL($href,4);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$loaded_state: ');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($loaded_state,jQuery.evDev.getTab($options.debugPrefixString,5),$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() END');
		};

		/** this.getMethodFromSEFURL=function($href)<br />
			* 
			* en: Obtain a method and a controller in the SEF URL.
			* ru: Получим метод и контроллер в ЧПУ адресе
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
			* en: Obtain a method and controller of the URL
			* ru: Разберем адрес и получим метод и контроллер
			* 
			* @param {string} $href
			* en: Link to processing
			* ru: Ссылка для обработки
			* 
			* @param {int} $tab_level
			* en: Indentations in debug mode
			* ru: Отступы в режиме отладке
			* 
			* @access private
			* @return {object} {$options.controller.paramName:function(),$options.method.paramName:function(),$url=string}
			* <br />en: Returns an object for save and get the functions<br /> by controller and method name.
			* <br />ru: Возвращаем объект для последующего сохранения и получения функции,<br /> по методу и контроллеру
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
				* en: If the SEF URL
				* ru: Если совпадает с ЧПУ адресом
				*/
				$parse_url = this.getMethodFromSEFURL($href);
			}else {
			/**
				* en: Use the plugin to parse the references.
				* ru: Используем плагин для разбора ссылки
				*/
				$parse_url = $.parseQuery($href);
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+$EVNINE_NAME+'$parse_url:');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($parse_url,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+2));
			if (!$parse_url[$options.controller.paramName] || $parse_url[$options.controller.paramName]===null || $parse_url[$options.controller.paramName]==='' || $parse_url[$options.controller.paramName]==undefined){
				/**
					* en: Specify the controller in the link?
					* ru: Проверяем указан ли контроллер в ссылке?
					*/
				$controller=$options.controller.defaultValue;
			}else {
			/**
				* en: If the controller name is not specified,<br /> 
				* en: use the default value of the options
				* ru: В случае если контроллер не указан,<br /> 
				* ru: используем значение по умолчанию из опций
				*/
				$controller=$parse_url[$options.controller.paramName];
			}
			if (!$parse_url[$options.method.paramName] || $parse_url[$options.method.paramName]===null || $parse_url[$options.method.paramName]==='' || $parse_url[$options.method.paramName]==undefined){
				/**
					* en: Specify the method in the link?
					* ru: Проверяем указан ли метод в ссылке?
					*/
				$method=$options.method.defaultValue;
			}else {
			/**
				* en: If the method name is not specified,<br /> 
				* en: use the default value of the options
				* ru: В случае если метод не указан,<br /> 
				* ru: используем значение по умолчанию из опций
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
		 * en: Set the option to delete the action
		 * ru: Устанавливаем функцию удаления действия
		 * 
		 * @param {string} $function_callback
		 * en: Function callback.
		 * ru: Функция обратного вызова
		 * 
		 * @access private
		 * @return void
		 */
		function setUnsetJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					* en: Has the controller since the last call?
					* en: If yes, run the callback function.
					* ru: Изменился ли контроллер с прошлого вызова? 
					* ru: Если да, запускаем callback function.
					*/
				if ($loaded_state.from[$options.controller.paramName]!==$loaded_state.to[$options.controller.paramName]){
					setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				}
				/**
					* en: Has the method since the last call?
					* en: If yes, run the callback function.
					* ru: Изменился ли метод с прошлого вызова? 
					* ru: Если да, запускаем callback function.
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
			* en: Initialize the function to callback.
			* ru: Инициализируем функцию для обратного вызова.
			* 
			* @param {string} $function_callback
			* en: Function callback.
			* ru: Функция обратного вызова
			* 
			* @access private
			* @return void
			*/
		function setInitJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					* en: For the controller and the method of reference is the method.
					* ru: Для контроллера и метода из ссылки получим методы
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
			* en: Start the function.
			* ru: Запустим функцию.
			* 
			* @param {object} $obj
			* en: An object with methods.
			* ru: Объект с методами
			* 
			* @param {string} $function_callback
			* en: Function callback.
			* ru: Функция обратного вызова
			*
			* @param {string} $before
			* en: Method or controller before.
			* ru: Метод или контроллер до
			* 
			* @param {string} $after
			* en: Method or controller after.
			* ru: Метод или контроллер после
			*
			* @access private
			* @return {boolean}
			* <br />en: true - if the function executed successfully.<br /> false - if function failed to call
			* <br />ru: true - если функция успешно выполнена.<br /> false - если функцию запустить не удалось 
			*/ 
		function setRunFunction($obj,$before,$after,$function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() BEGIN');
			if ($obj==undefined){
			/**
				* en: If not specified object.
				* ru: Если не указан объект.
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$obj==undefined');
				return false;
			}
			if ($before!==$after){
				/**
					* en: Compare the method and controller before and after.
					* ru: Сравниваем метод и контроллер до и после
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']!==[$after='+$after+']');
				if (isHasAccess($obj)){
					/**
						* en: Check the level of access to the function of the options?
						* ru: Проверяем, есть ли доступ к функции из опций?
						*/
					$obj = $obj[$function_callback];
					if (typeof $obj==='function'){
						/**
							* en: Executed if the object specified function.
							* ru: Выполняется если в объекте указана функция
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
					* en: Case reload the same page when the method or the controller has not changed.
					* ru: Случай обновления той же страницы, когда метод или контроллер не изменился
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']===[$after='+$after+']');
				if ($obj.setReloadPageAction==undefined){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:setReloadPageAction==undefined');
					return false;
				}
				if (isHasAccess($obj)){
					/**
						* en: Check the level of access to the function of the options?
						* ru: Проверяем, есть ли доступ к функции из опций?
						*/
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction.setReloadPageAction()");
					$obj.setReloadPageAction();
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true');
					return true;
				}
			}
		}

		/** setMethodAndControllerFunc=function($mode)<br />
			* en: Set a controller and method of URL.
			* ru: Установить контроллер и метод из ссылки.
			* 
			* @param {string} [$mode='']
			* en: Flag for checking the first initialization.
			* ru: Флаг для проверки первой инициализации
			*
			* @access public
			* @return void
			*/
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') BEGIN');
			if ($mode==='init'){
				/**
					* en: If the first initialization.
					* en: To set the initial values of the states before and after.
					* ru: Если первая инициализация
					* ru: Для установки начальных значений состояний до и после.
					*/
				$loaded_state.to = getParseURL(location.pathname,4);
				/**
					* en: Initialize the function to callback
					* ru: Инициализируем функцию для обратного вызова
					*/
				setInitJSFuncForHREF('setAction');
				$loaded_state.from= $loaded_state.to;
			} else {
				/**
					* en: Initialize the function to callback
					* ru: Инициализируем функцию для обратного вызова
					*/
				setInitJSFuncForHREF('setAction');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') END');
		};
	
		/** isHasAccess($obj)<br />
			* en: Check the level of access to the function of the options?
			* ru: Проверяем, есть ли доступ к функции из опций?
			* 
			* @param {object} $obj
			* en: Object with the method. Check, have access to it?
			* ru: Объект с методом. Проверим есть ли доступ?
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
			*     en: Default access level
			*     ru: Уровень доступа 
			*     this.access='1';
			*    }
			*   }
			*  })
			* });
			* 
			* @access private
			* @return {boolean}
			* en: default is true.
			* en: если 
			* ru: по умолчанию true
			*/
		function isHasAccess($obj) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() BEGIN');
			if ($options.setFunction.isHasAccess!=undefined){
				/**
					* en: If the object specified.
					* en: Return the response from the method of access checks.
					* en: Which is set in the options.
					* ru: Если объект задан. 
					* ru: Возвращаем ответ от метода проверки доступа.
					* ru: Который задан в опциях.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun $options.setFunction.isHasAccess($obj,$options)');
				return $options.setFunction.isHasAccess($obj,$options);
			}else {
				/**
					* en: When the checks do not always return - there is access.
					* ru: Если проверки нет всегда возвращаем - доступ есть.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun true');
				return true;
			}
		}

		/** function getFuncForControllerAndMethod()<br />
			* 
			* en: For the controller and the method of reference is the method
			* ru: Для контроллера и метода из ссылки получим методы
			* 
			* @access private
			* @return {object} {[$options.controller.paramName],$obj[$options.method.paramName]}
			*/
		function getFuncForControllerAndMethod(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() BEGIN');
			var $obj={};
			/**
				* en: Get on a key function of the options
				* en: Key from the parsing links
				* ru: Получить функцию по ключу из опций 
				* ru: Ключ из парсинга ссылки
				*/
			$obj[$options.controller.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]);
			$obj[$options.method.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]+$options.strUnionControllerWithMethod+$loaded_state.to[$options.method.paramName]);
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,6)+'return ',$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() END');
			return $obj;
		}
	
		/** function getFunctionFromOptions($key)<br />
			* en: Get on a key function of the options
			* ru: Получить функцию по ключу из опций 
			* 
			* @param {string} $key
			* en: Key from the parsing links
			* ru: Ключ из парсинга ссылки
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
					* en: Is set key?
					* ru: Проверяем существует ли ключ?
					*/
					$setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
					/**
						* en: Return the function to call
						* ru: Вернем функцию для вызова
						*/
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}
				if ($options.setFuncByHREFMatch!=undefined) {
				/**
					* en: Verify the match with the address
					* ru: Проверим совпадение с адресом
					*/
						if ($options.setFuncByHREFMatch[$loaded_state.to.url]!=undefined){
						$setFunctionKey = $options.setFuncByHREFMatch[$loaded_state.to.url];
						/**
							* en: If the object in the array is a function. 
							* ru: Если объект в массиве является функцией
							*/
						if (typeof $options.setFunction[$setFunctionKey]==='function'){
							if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByHREFMatch');
							return new $options.setFunction[$setFunctionKey]($options);
						}
					}
				}
				if ($options.setFuncByMatchRegHREF!=undefined) {
				/**
					* en: To verify that the regular expression.
					* ru: Проверим совпадение с регулярным выражением
					*/
					var obj={};
					$.each($options.setFuncByMatchRegHREF, function($href_reg,$setFunctionKey){
						$reg = new RegExp($href_reg,"g");
						if ($loaded_state.to.url.match($reg)){
						/**
							* en: If the object in the array is a function
							* ru: Если объект в массиве является функцией
							*/
							if (typeof $options.setFunction[$setFunctionKey]==='function'){
								$obj=new $options.setFunction[$setFunctionKey]($options);
							}
						}
					});
					if ($obj!=undefined){
					/**
						* en: If the object specified return it
						* ru: Если объект указан возвращаем его
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
			* en: Link to themselves for access from the jQuery evNav plugin
			* ru: Ссылка на себя для доступа из jQuery evNav plugin
			*/
		return this;
	};
}(document, jQuery);
//</script>
