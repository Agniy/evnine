//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evFunc
	* <br />en: jQuery plugin - call function after ajax complete or reload page
	* <br />ru: Плагин для запуска функций после аякс запроса или загрузки новой страницы.
	* <br />
	* <br />en: Copyright 2011, (c) ev9eniy.info
	* <br />en: Dual licensed under the MIT or GPL Version 2 licenses
	* <br />
	* <br />ru: Двойная лицензия MIT или GPL v.2 
	* 
	* @config {object}  [=undefined]
	* en: Init evFunc plugin<br />
	* ru: Доступ для плагина запуска функций
	* 
	* @config {object} [controller={paramName:'c',defaultValue:'default'}]
	* en: Parameter for the controller and the default value<br />
	* ru: Параметр для контроллера и значение по умолчанию
	* 
	* @config {object} [method={paramName:'m',defaultValue:'default'}]
	* en: Parameter to the method and the default value<br />
	* ru: Параметр для метода и значение по умолчанию
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode='.html$']
	* en: If match RegExp, use SEF mode<br />
	* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {object} [setFunction:{function_name:function()}=undefined]
	* en: Functions for execute after ajax complete<br />
	* ru: Функция для запуска после аякс загрузки страницы
	* 
	* @config {function} [setFunction[function_name]($options)=undefined]
	* en: Init with $options<br />
	* ru: Передаём опции для того что бы иметь доступ к настройкам 
	* 
	* @config {function:return boolean} [setFunction.isHasAccess($obj,$options)=undefined]
	* en: Is user has access for function, init with (setFunction[function_name],$options)<br />
	* ru: Проверяем доступ, инициализируем с объектом, функции из setFunction()<br />
	* ru: А так же передаём опции<br />
	* 
	* @config {string} [strUnionControllerWithMethod='.']
	* en: The symbol for the union of controller and methods in the setFuncByEvnineParamMatch
	* ru: Символ для объединения методов в setFuncByEvnineParamMatch
	*
	* @config {object} [setFuncByEvnineParamMatch:<br />
	* {arguments.controller+arguments.strUnionControllerWithMethod+arguments.method:function_name}=undefined]
	* en: Controller with method and function<br />
	* ru: Контроллер.метод и связанная с ним функции 
	*
	* @config {object} [setFuncByEvnineParamMatch:{string:function_name}=undefined]  
	* en: URN and related functions<br />
	* ru: Ссылка и связанная с ней функции
	*
	* @config {object} [setFuncByHREFMatch:{RegExp:function_name}=undefined]  
	* en: RegExp and associated functions<br />
	* ru: Регулярное выражение и связанная с ней функции 
	* 
	* @config {boolean} [debugToConsole=false]
	* en: Debug to console<br />
	* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} [debugPrefixString='| ']
	* en: Debug prefix for group of functions<br />
	* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info <br />
	* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
	*
	* @config {boolean} [debugFunctionGroup=false]
	* en: Use console.group as alternative to $options.debugPrefixString  <br />
	* ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций  
	*/
new function (document, $, undefined) {
	jQuery.evFunc = function($rewrite_options) {
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='evFunc'+'.';
		//en:
		/*ru: Выставляем опции по умолчанию */
		var $options = jQuery.extend({
			//en:
			/*ru: Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
			isHREFMatchThisRegExpSetSEFMode   :'.html$',//=$href.match(/\.html/g)
			//en: Parameter for the controller and the default value
			/*ru: Параметр для контроллера и значение по умолчанию*/
			controller:{
				paramName:'c',defaultValue:'default'
			},
			//en: Parameter to the method and the default value.
			/*ru: Параметр для метода и значение по умолчанию*/
			method:{
				paramName:'m',defaultValue:'default'
			},
			//en: 
			/*ru: Выводить отладочную информацию в консоль FireFox FireBug, Chrome, Opera итд*/
			debugToConsole                    :true,
			//debugToConsole                  :false,
			//en: 
			/*ru: Префикс для вывода в окно отладки FireFox FireBug, Chrome, Opera*/
			debugPrefixString                 :'|	',
			//debugPrefixString               :' ',
			//en: 
			/*ru: Если нужна отладка в консоль в IE 6-7, Safari итд */
			debugToConsoleNotSupport          :false,
			//debugToConsoleNotSupport        :true,
			//en: 
			/*ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций */
			//debugFunctionGroup                :true,
			debugFunctionGroup              :false,
			//en:
			/*ru: символ для объединения методов в setFuncByEvnineParamMatch*/
			strUnionControllerWithMethod    :'.'
			//en: Function is for controller with method.
			/*ru: Контроллер.метод и связанная с ним функции */
			//setFuncByEvnineParamMatch       :{
				//'default.default'               :'default',
			//}
			//en:
			/*ru: Ссылка и связанная с ней функции */	
			//setFuncByHREFMatch              :{
				//'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			//en:
			/*ru: Регулярное выражение и связанная с ней функции */	
			//setFuncByMatchRegHREF              :{
				//'.*index\.php.*'                :'default'
			//}
		},$rewrite_options);
		//en:
		/*ru: Учитываем случай когда при загрузки странице нужно запустить скрипт */
		this.$reload_page=false;
		//en:
		/*ru:Какие скрипты подгружены */
		var $includ_scripts={};
		//en:
		/*ru:Переменная в который храним класс текущего метода */
		var $current_method_class=false;
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'init BEGIN');
		//en:
		/*ru: Случай когда для отладки нужно группировать в консоли вызовы */
		//if ($options.debugFunctionGroup){
			//$options.debugPrefixString= '';
		//}
		//en:
		/*ru:Объект для хранения загруженных методов и новых */
		var $loaded_state={
			'from' : {},'to'   : {}};
			
		/**
		 * en:  
		 * ru: Получить скрипт и выполнить функцию после его загрузки
		 * @access public
		 * @param $script_href - string
		 * @param $fun - function
		 * @return void
		*/
		$options.include_once=function($script_href,$fun) {
			//en:
			/*ru: Сохраняем функцию для последующего доступа*/
				var $function=$fun;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'include_once()');
				//en:
				/*ru: Проверяем загружен ли скрипт*/
				if ($includ_scripts[$script_href]==undefined){
					jQuery.getScript($script_href, function() {
						//en:
						/*ru: Ставим флаг что скрипт загружен*/
						$includ_scripts[$script_href]=true;
						jQuery(document).ready(function(){
							if ($options.debugToConsole) 
								console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
							try {
								//en:
								/*ru: Ожидаем появления ошибки в функции. Запускаем переданную функцию*/	
								$function();
							}catch($e){
								if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
							}
							if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
						});
					});
				}else {
					//en:
					/*ru: Случай если скрипт уже загружен*/
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
					try {
						//en:
						/*ru: Ожидаем появления ошибки в функции. Запускаем переданную функцию*/	
						$function();
					}catch($e){
						if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
					}
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
				}
			};
				
		/**
		 * en:
		 * ru: Вызываем из плагина навигации установку флага, что не нужно перезагружать страницу
		 * 
		 * @access public
		 * @return void
		 */
		this.setPreCallShowResponse=function() {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPreCallShowResponse() BEGIN');
			this.$reload_page=false;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPreCallShowResponse() END');
		};
		
		/**
		 * en:
		 * ru: Функция выполняемая после отображения данных из скрипта, указанная в конфигурационном файла
		 * 
		 * @access public
		 * @param $load_href - string
		 * @return void
		 */
		this.setPostCallShowResponse=function($load_href) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') BEGIN');
			//en:
			/*ru: Сохраним параметры загрузки адреса */	
			this.getParseURLAndSave($load_href);
			if ($loaded_state.to[$options.controller.paramName]!=undefined||
				$loaded_state.to[$options.method.paramName]!=undefined)
			{
				//en:
				/*ru: Запускаем функцию удаления */
				setUnsetJSFuncForHREF('unSetAction');
			}
			//en: 
			/*ru: Запускаем функцию для страницы */
			this.setMethodAndControllerFunc();
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') END');
		};

		/**
		 * en:
		 * ru: Разбираем адрес загруженной страницы
		 * 
		 * @access public
		 * @param $href - string
		 * @return void
		 */
		this.getParseURLAndSave=function($href){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() BEGIN');
			//en:
			/*ru: Если адреса совпадают ничего не делаем */
			if ($loaded_state.to.url===$href){
				return $loaded_state.to;
			}
			//en:
			/*ru: Сохраняем в из состояние после  */
			$loaded_state.from= $loaded_state.to;
			//en:
			/*ru: Получаем текущее состояние разобрав адрес*/	
			$loaded_state.to = getParseURL($href,4);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$loaded_state: ');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($loaded_state,jQuery.evDev.getTab($options.debugPrefixString,5),$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() END');
		};

		/**
			*en:
			*ru:Получим метод и контроллер в ЧПУ адресе
			*
			* @access public
			* @param $href - string
			* @return object{[$options.controller.paramName],$obj[$options.method.paramName]}
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

		/**
			* en: Obtain a method and controller of the URL<br />
			* ru: Разберем адрес и получим метод и контроллер
			* 
			* @access private
			* @param $href - string
			* @param $tab_level - int ru: Отступы в режиме отладке
			* @return object{[$options.controller.paramName],$obj[$options.method.paramName]}
			*  
		*/
		function getParseURL($href,$tab_level){//Парсим адрес для получения шаблона и метода
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') BEGIN');
			if ($href===undefined){
				return false;
			}
			$href=$href.replace(/^.*\?/,"?");
			$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
			//en:
			/*ru: Если совпадает с ЧПУ адресом */
			if ($href.match($reg)){
				$parse_url = this.getMethodFromSEFURL($href);
			}else {
				$parse_url = $.parseQuery($href);
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+$EVNINE_NAME+'$parse_url:');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($parse_url,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+2));
			//en:
			/*ru: Проверяем есть ли значение контроллера в адресе*/
			if (!$parse_url[$options.controller.paramName] || $parse_url[$options.controller.paramName]===null || $parse_url[$options.controller.paramName]==='' || $parse_url[$options.controller.paramName]==undefined){
				$controller=$options.controller.defaultValue;
			}else {
				//en:
				/*ru: В случае если имени контроллера нет, выставляем значение по умолчанию из опций*/
				$controller=$parse_url[$options.controller.paramName];
			}
			//en:
			/*ru: Проверяем есть ли значение метода в адресе */
			if (!$parse_url[$options.method.paramName] || $parse_url[$options.method.paramName]===null || $parse_url[$options.method.paramName]==='' || $parse_url[$options.method.paramName]==undefined){
				$method=$options.method.defaultValue;
			}else {
				//en:
				/*ru: В случае если имени метода нет, выставляем значение по умолчанию из опций*/
				$method=$parse_url[$options.method.paramName];
			}
			//en:
			/*ru: Возвращаем объект с значениями для последующего сохранения и получения функции */
			var $obj={};
			$obj[$options.controller.paramName]=$controller;
			$obj[$options.method.paramName]=$method;
			$obj.url=$href;
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+'return ');
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') END');
			return $obj;
		}

		/**
		 * en:
		 * ru: Устанавливаем функцию удаления
		 * 
		 * @access private
		 * @param $function_callback - string
		 * @return void
		 */
		function setUnsetJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				//en:
				/*ru: проверяем изменился ли контроллер, если да, запускаем функцию переданную в функцию */	
				if ($loaded_state.from[$options.controller.paramName]!==$loaded_state.to[$options.controller.paramName]){
					setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				}
				//en:
				/*ru: проверяем изменился ли метод, если да, запускаем функцию переданную в функцию */	
				if ($loaded_state.from[$options.method.paramName]!==$loaded_state.to[$options.method.paramName]){
					setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
				}
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		

		/**
		 * en
		 * ru: Инициализируем функцию
		 * 
		 * @access private
		 * @param $function_callback - string
		 * @return void
		 */
		function setInitJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				//en:
				/*ru: Для текущего шаблона получим класс из списка привязки шаблонов к классам */
				$current_method_class = getFuncForControllerAndMethod();
				setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		
		/**
		 * en:
		 * ru:Запустим функцию
		 * 
		 * @access private
		 * @param $function_callback - string
		 * @param $before - string
		 * @param $after - string
		 * @param $function_callback - string
		 * @return bool
		*/ 
		function setRunFunction($obj,$before,$after,$function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() BEGIN');
			if ($obj==undefined){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$obj==undefined');
				return false;
			}
			//en:
			/*ru: проверяем изменен ли параметр с прошлого раза */
			if ($before!==$after){
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']!==[$after='+$after+']');
				//en:
				/*ru: проверяем уровень доступа */
				if (isHasAccess($obj)){
					$obj = $obj[$function_callback];
					if (typeof $obj==='function'){
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
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']===[$after='+$after+']');
				if ($obj.setReloadPageAction==undefined){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:setReloadPageAction==undefined');
					return false;
				}
				//en:
				/*ru: проверяем уровень доступа */
				if (isHasAccess($obj)){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction.setReloadPageAction()");
					$obj.setReloadPageAction();
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true');
					return true;
				}
			}
		}

		/**
		 * en:
		 * ru:Получить из адреса текущий контроллер и метод 
		 * 
		 * @access public
		 * @param $mode ru: метод инициализации
		 * @return void
		 */
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') BEGIN');
			if ($mode==='init'){
				$loaded_state.to = getParseURL(location.pathname,4);
				//en:
				/*ru: Инициализируем функцию */
				setInitJSFuncForHREF('setAction');
				$loaded_state.from= $loaded_state.to;
			}else {
				setInitJSFuncForHREF('setAction');
			}
			//en:
			/*ru: Загрузим дополнительные скрипты*/
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') END');
		};
	
		/**
		 * en:
		 * ru: Проверяем есть ли доступ к функции из опций
		 * 
		 * @access private
		 * @param $obj - object
		 * @return function() or bool
		 */
		function isHasAccess($obj) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() BEGIN');
			if ($options.setFunction.isHasAccess!=undefined){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun $options.setFunction.isHasAccess($obj,$options)');
				return $options.setFunction.isHasAccess($obj,$options);
			}else {
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun true');
				return true;
			}
		}
	
		/**
		 * en:
		 * ru:Для текущего шаблона получим класс из списка привязки шаблонов к классам
		 * 
		 * @access private
		 * @return object{[$options.controller.paramName],$obj[$options.method.paramName]}
		 */
		function getFuncForControllerAndMethod(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() BEGIN');
			var $obj={};
			// en:
			/* ru:Получить функции по ключу из объекта полученного после парсинга*/
			$obj[$options.controller.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]);
			$obj[$options.method.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]+$options.strUnionControllerWithMethod+$loaded_state.to[$options.method.paramName]);
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,6)+'return ',$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() END');
			return $obj;
		}
	
		/**
		 * en:
		 * ru:Получить функции по ключу из объекта полученного после парсинга
		 * 
		 * @access private
		 * @param $key string ru: ключ массива
		 * @return object[$options.setFuncByEvnineParamMatch[$key]] or undefined
		 */
		function getFunctionFromOptions($key){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() BEGIN');
			var $setFunctionKey='';
			try{
				//en:
				/*ru: Проверяем существует ли ключ */
				if ($options.setFuncByEvnineParamMatch[$key]!=undefined){
					$setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
						//en:
						/*ru: Вернем функцию для вызова  */
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}
				//en:
				/*ru: Проверим совпадение с адресом */
				if ($options.setFuncByHREFMatch!=undefined) {
					if ($options.setFuncByHREFMatch[$loaded_state.to.url]!=undefined){
						$setFunctionKey = $options.setFuncByHREFMatch[$loaded_state.to.url];
						//en:
						/*ru: Если объект в массиве является функцией */
						if (typeof $options.setFunction[$setFunctionKey]==='function'){
							if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByHREFMatch');
							return new $options.setFunction[$setFunctionKey]($options);
						}
					}
				}
				//en:
				/*ru: Проверим совпадение с регулярным выражением */
				if ($options.setFuncByMatchRegHREF!=undefined) {
					var obj={};
					$.each($options.setFuncByMatchRegHREF, function($href_reg,$setFunctionKey){
						$reg = new RegExp($href_reg,"g");
						if ($loaded_state.to.url.match($reg)){
							//en:
							/*ru: Если объект в массиве является функцией */
							if (typeof $options.setFunction[$setFunctionKey]==='function'){
								$obj=new $options.setFunction[$setFunctionKey]($options);
							}
						}
					});
					//en:
					/*ru: Если объект указан возвращаем его */
					if ($obj!=undefined){
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
		return this;
	};
}(document, jQuery);
//</script>
