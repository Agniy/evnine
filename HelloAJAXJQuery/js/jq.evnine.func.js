//<script type="text/javascript">
/*
 * JQuery AJAX AJAX Event
 *
 * Copyright 2011, (c) ev9eniy.info
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
new function (document, $, undefined) {
	jQuery.evFunc = function($rewrite_options) {
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='evFunc'+'.';
		var $options = jQuery.extend({debugFunctionGroup:false},$rewrite_options);
		//en:
		/*ru: Учитываем случай когда при загрузки странице нужно запустить скрипт*/
		this.$reload_page=false;
		//en:
		/*ru:Какие скрипты подгружены*/
		var $includ_scripts={};
		//en:
		/*ru:Переменная в который храним класс текущего метода*/
		var $current_method_class=false;
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'init BEGIN');
		//en:
		/*ru: Случай когда для отладки нужно группировать в консоли вызовы*/
		if ($options.debugFunctionGroup){
			$options.debugPrefixString= '';
		}	
		/*ru:настройки по умолчанию*/
		var $loaded_state={//Массив для хранения загруженных методов и новых
			'from' : {},'to'   : {}};
			
		/**
		 *  ru: Получить скрипт и выполнить функцию после его загрузки
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
								/*ru: Ожидаем появления ошибки в функции запускаем переданную функцию*/	
								$function();
							}catch($e){
								if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
							}
								if ($options.debugToConsole) 
									console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
						});
					});
				}else {
					//en:
					/*ru: Случай если скрипт уже загружен*/
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
					try {
						$function();
					}catch($e){
						if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
					}
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
				}
			};
				
		/**
		 * en:
		 * ru: Вызываем из плагина навигации установку флага
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
			*  en:
			*  ru:Получим метод и контроллер в ЧПУ урле
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
	
		function getParseURL($href,$tab_level){//Парсим адрес для получения шаблона и метода
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') BEGIN');
			if ($href===undefined){
				return false;
			}
			$href=$href.replace(/^.*\?/,"?");
			$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
			if ($href.match($reg)){//IF SEF URN
				$parse_url = this.getMethodFromSEFURL($href);
			}else {
				$parse_url = $.parseQuery($href);
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+$EVNINE_NAME+'$parse_url:');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($parse_url,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+2));
			if (!$parse_url[$options.controller.paramName] || $parse_url[$options.controller.paramName]===null || $parse_url[$options.controller.paramName]==='' || $parse_url[$options.controller.paramName]==undefined){
				$controller=$options.controller.defaultValue;
			}else {
				$controller=$parse_url[$options.controller.paramName];
			}
			if (!$parse_url[$options.method.paramName] || $parse_url[$options.method.paramName]===null || $parse_url[$options.method.paramName]==='' || $parse_url[$options.method.paramName]==undefined){
				$method=$options.method.defaultValue;
			}else {
				$method=$parse_url[$options.method.paramName];
			}
			var $obj={};
			$obj[$options.controller.paramName]=$controller;
			$obj[$options.method.paramName]=$method;
			$obj.url=$href;
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+'return ');
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,$tab_level)+$EVNINE_NAME+'getParseURL($href='+$href+') END');
			return $obj;
			//return {controller_name : $controller,method_name : $method};
		}

		/**
		 * ru:Для случая когда в кнопке указан метод, устанавливаем этот метод в ссылку	
		 */
		this.setMethodTOURL=function($href,$method) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() BEGIN');
			if ($method.length===0){
				return $href;
			}else {
				if ($href.match(/\.html$/)){
					$href = $href.substring(1);
					$method_replace  = $href.match(/.*\//);
					$method_replace=$method_replace.toString().split(/\//);
					if ($method_replace[1].match(/=/)){
						$method_match==$method_replace[0]+'/';
						$method='/'+$method_replace[0]+'/'+$method;
					}else {
						$method_match=$method_replace[1];
					}
					$href = $href.replace($method_match,$method);
				}else {
					$method_match = $href.match(/&m=.*/);
					$href = $href.replace($method_match,"&m="+$method);
				}
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() END');
				return $href;
			}
		};

/**
 *  ru: Запуск удаления метода
 */
		function setUnsetJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				if ($loaded_state.from[$options.controller.paramName]!==$loaded_state.to[$options.controller.paramName]){
					setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				}
				if ($loaded_state.from[$options.method.paramName]!==$loaded_state.to[$options.method.paramName]){
					setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
				}
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		

/**
 *  ru: Запуск метода функции
 */
		function setInitJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				$current_method_class = getFuncForControllerAndMethod();
				setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				setRunFunction($current_method_class[$options.method.paramName],$loaded_state.from[$options.method.paramName],$loaded_state.to[$options.method.paramName],$function_callback);
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') END');
		}
		
		/**
		 * ru:Запустим функцию
		 */
		function setRunFunction($obj,$before,$after,$function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() BEGIN');
			if ($obj==undefined){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$obj==undefined');
				return false;
			}
			if ($before!==$after){
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']!==[$after='+$after+']');
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
				if (isHasAccess($obj)){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction.setReloadPageAction()");
					$obj.setReloadPageAction();
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true');
					return true;
				}
			}
		}

		/**
		* Получить из адреса текущий контроллер и метод 
		*/
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') BEGIN');
			if ($mode==='init'){
				$loaded_state.to = getParseURL(location.pathname,4);
				setInitJSFuncForHREF('setAction');
				$loaded_state.from= $loaded_state.to;
			}else {
				setInitJSFuncForHREF('setAction');
				//$loaded_state.from = getParseURL(location.pathname,4);
			}
			//Загрузим дополнительные скрипты
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') END');
		};
	
		/**
		 *  ru:Проверяем есть ли доступ по функции в опции
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
		 * ru:Для текущего шаблона получим класс из списка привязки шаблонов к классам
		 */
		function getFuncForControllerAndMethod(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() BEGIN');
			var $obj={};
			$obj[$options.controller.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]);
			$obj[$options.method.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]+$options.strUnionControllerWithMethod+$loaded_state.to[$options.method.paramName]);
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,6)+'return ',$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() END');
			return $obj;
		}
	
		/**
		 * ru:Получить обект функции
		 * 
		 * @access public
		 * @return void
		 */
		function getFunctionFromOptions($key){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() BEGIN');
			var $setFunctionKey='';
			try{
				if ($options.setFuncByEvnineParamMatch[$key]!=undefined){
					$setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}

				if ($options.setFuncByHREFMatch!=undefined) {
					if ($options.setFuncByHREFMatch[$loaded_state.to.url]!=undefined){
						$setFunctionKey = $options.setFuncByHREFMatch[$loaded_state.to.url];
						if (typeof $options.setFunction[$setFunctionKey]==='function'){
							if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByHREFMatch');
							//if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions() END return object');
							return new $options.setFunction[$setFunctionKey]($options);
						}
					}
				}
				
				if ($options.setFuncByMatchRegHREF!=undefined) {
					var obj={};
					$.each($options.setFuncByMatchRegHREF, function($href_reg,$setFunctionKey){
						$reg = new RegExp($href_reg,"g");
						if ($loaded_state.to.url.match($reg)){//IF SEF URN
							if (typeof $options.setFunction[$setFunctionKey]==='function'){
								$obj=new $options.setFunction[$setFunctionKey]($options);
							}
						}
					});
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
