//<script type="text/javascript">
/*
 * JQuery AJAX AJAX Event
 *
 * Copyright 2011, (c) ev9eniy.info
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
new function (document, $, undefined) {
	jQuery.setEvnineFunc = function($rewrite_options) {
		this.$reload_page=false;
		var $options = jQuery.extend({},$rewrite_options);
		/*ru:Какие скрипты подгружены*/
		var $js_script_is_load={};

		/*ru:Переменная в который храним класс текущего метода*/
		var $current_method_class=false;
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='$.setEvnineFunc'+'->';
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,0)+$EVNINE_NAME+'()');
		
		/*ru:настройки по умолчанию*/
		var $loaded_state={//Массив для хранения загруженных методов и новыx
			'from' : {
				'controller' : '','method' : ''
			},
			'to'   : {
				'controller' : '','method' :'','url' : ''
			}
		};

		/**
		 * setPreshowResponse 
		 * 
		 * @access public
		 * @return void
		 */
		this.setPreCallShowResponse=function() {
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse()');
			this.$reload_page=false;
		};
		
		/**
		 * setPostshowResponse 
		 * 
		 * @access public
		 * @return void
		 */
		this.setPostCallShowResponse=function($load_href) {
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+')');
			//$.scrollTo( '#main', 0);
				//if ($load_href!==''){
					//$load_href= '';
				//}
			this.getParseURLWithSave($load_href);
			this.setUnloadJSFuncForHREF();
			this.setMethodAndControllerFunc();
		};
			
		/**
			*  en:
			*  ru:Получим метод в ЧПУ урле
		*/
		this.getMethodFromSEFURL=function($url){
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'getMethodFromSEFURL()');
			$url = $url.substring(1);
			$method_replace  = $url.match(/.*\//);
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
			$obj[$options.method.paramName]=$controller;
			if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($obj,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5));
			if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($obj,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5));
			return $obj;
		};
	
		function getParseURL (href){//Парсим адрес для получения шаблона и метода
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'getParseURL()');
			if (href===undefined){
				return false;
			}
			if (href.match(/\.html$/)){
				$parse_url = this.getMethodFromSEFURL(href);
			}else {
				$parse_url = $.parseQuery(href);
			}
			$controller = $parse_url.t;
			if (!$parse_url.m || $parse_url.m===null || $parse_url.m==='' || $parse_url.m===undefined){
				$method=$options.method.defaultValue;
				if (!$parse_url.t || $parse_url.t===null || $parse_url.t==='' || $parse_url.t===undefined){
					$controller=$options.controller.defaultValue;
				}
			}else {
				$method= $parse_url.m;
			}
			var $obj={};
			$obj[$options.controller.paramName]=$controller;
			$obj[$options.method.paramName]=$controller;
			if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($obj,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5));
			return $obj;
			//return {controller_name : $controller,method_name : $method};
		}

		/**
		* ru:Получаем распарсеные данные из адреса и так же сохраним их в массив загруженных шаблонов
		*/
		this.getParseURLWithSave=function($url){
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'getParseURLWithSave()');
			//if ($options.debugToConsole) console.info("#function() getParseURLWithSave: ");
			if ( $loaded_state.to.url!==undefined){
				//if ($options.debugToConsole) console.info("#getParseURLWithSave: ");
			if ($loaded_state.to.url===$url){
				return $loaded_state.to;
			}
			}
			//$loaded_state.from.method= $loaded_state.to.method;
			//$loaded_state.from.controller= $loaded_state.to.controller;
			$param = getParseURL($url);
			$loaded_state.to.method=$param.method;
			$loaded_state.to.controller=$param.controller;
			$loaded_state.to.url=$url;
			return $param;
			//$loaded_state.to.controller=$param.controller;
			//$loaded_state.to.method=$param.method;
			//$loaded_state.to.url=$url;
			//return $param;
		};
	
		/**
		* ru:Для случая когда в кнопке указан метод, устанавливаем этот метод в ссылку	
		*/
		this.setMethodTOURL=function($url,$method) {
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL()');
			if ($method.length===0){
				return $url;
			}else {
				if ($url.match(/\.html$/)){
					$url = $url.substring(1);
					$method_replace  = $url.match(/.*\//);
					$method_replace=$method_replace.toString().split(/\//);
					if ($method_replace[1].match(/=/)){
						$method_match==$method_replace[0]+'/';
						$method='/'+$method_replace[0]+'/'+$method;
					}else {
						$method_match=$method_replace[1];
					}
					$url = $url.replace($method_match,$method);
				}else {
					$method_match = $url.match(/&m=.*/);
					$url = $url.replace($method_match,"&m="+$method);
				}
				return $url;
			}
		};
	

		/**
		*  ru:Установить для шаблона метод удаления активности если перешли в другой шаблон
		*/
		function setUnloadJSFuncForHREF(){
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'setUnloadJSFuncForHREF()');
			if ($options.debugToConsole) console.info("#>>>>>>setUnloadJSFuncForHREF: ");
				//if ($options.debugToConsole) console.warn("$current_method_class.controller: "+typeof $current_method_class.controller);
				//if ($options.debugToConsole) console.warn("$current_method_class.method.unSetAction: "+typeof $current_method_class.method.unSetAction);
				//if ($options.debugToConsole) console.warn("to.controller: "+$loaded_state.to.controller);
				//if ($options.debugToConsole) console.warn("from.controller: "+$loaded_state.from.controller);
				try {
				if ($loaded_state.to.controller!==undefined&&
					$loaded_state.from.controller!==undefined)
					//($current_method_class.controller!==undefined||$current_method_class.method!==undefined)
				//)
				{
					if ($options.debugToConsole) console.warn("to.method: "+$loaded_state.to.method+' == '+"from.method: "+$loaded_state.from.method);
					if ($loaded_state.to.method!==$loaded_state.from.method){
				if ($options.debugToConsole) console.warn("$current_method_class.method: "+typeof $current_method_class.method);
						if ($current_method_class.method!==undefined){
							if ($options.debugToConsole) console.warn("		method: "+$current_method_class.method);
							if ($options.debugToConsole) console.warn("		method.unSetAction: "+$current_method_class.method.unSetAction);
							if ($options.debugToConsole) console.warn("		method.unSetAction typeof: "+(typeof $current_method_class.method.unSetAction));
							if (typeof $current_method_class.method.unSetAction==='function'&&
							isHasAccess($current_method_class.method.default_access_level)){
							if ($options.debugToConsole) console.warn("method.unSetAction: ");
								$current_method_class.method.unSetAction();
								$current_method_class.method=undefined;
							}
						}
					}
					if ($options.debugToConsole) console.warn("to.controller: "+$loaded_state.to.controller+" == from.controller: "+$loaded_state.from.controller);
					if ($loaded_state.to.controller!==$loaded_state.from.controller){
						if ($options.debugToConsole) console.warn("$current_method_class.controller.unSetAction: "+typeof $current_method_class.controller.unSetAction);
							//if ($options.debugToConsole) console.warn("		controller: "+$current_method_class.method);
							//if ($options.debugToConsole) console.warn("		controller.unSetAction: "+$current_method_class.method.unSetAction);
							//if ($options.debugToConsole) console.warn("		controller.unSetAction typeof: "+(typeof $current_method_class.method.unSetAction));
						if (typeof $current_method_class.controller.unSetAction==='function'&&
							isHasAccess($current_method_class.controller.default_access_level)){
							if ($options.debugToConsole) console.warn("controller.unSetAction: ");
							$current_method_class.controller.unSetAction();
							$current_method_class.controller=undefined;
						}
					}
				}
				}catch (err) {}
				$debug=false;
			}

		/**
		* Получить из адреса текущий контроллер и метод 
		*/
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodAndControllerFunc()');
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,3)+$EVNINE_NAME+'$mode='+$mode);
			if ($mode==='init'){
				$getParseURL = getParseURL(location.pathname);
				$loaded_state.to.controller=$getParseURL[$options.controller.paramName];
				$loaded_state.to.method=$getParseURL[$options.method.paramName];
				$loaded_state.to.url=location.pathname;
			}
			//Загрузим дополнительные скрипты
			setJSFuncForHREF();	
			$getParseURL = getParseURL(location.pathname);
			$loaded_state.from.controller=$getParseURL[$options.controller.paramName];
			$loaded_state.from.method=$getParseURL[$options.method.paramName];
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,3)+$EVNINE_NAME+'$loaded_state: ');
			if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($loaded_state,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4),$options.debugPrefixString);
		};
	
		/**
		 * ru:Установить для шаблона метод активности
		 */
		function setJSFuncForHREF(){
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,3)+$EVNINE_NAME+'setJSFuncForHREF()');
			try {
				var $current_method_class = getFuncForController();
				if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+"$current_method_class=");
				if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($current_method_class,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5));
				setRunFunction($current_method_class.controller,$loaded_state.to.controller,$loaded_state.from.controller);
				setRunFunction($current_method_class.method,$loaded_state.to.method,$loaded_state.from.method);
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
			}
		}
	
	
		/**
		 * ru:Запустим функцию
		 */
		function setRunFunction($obj,$before,$after){
			if ($obj==undefined||$before==undefined||$after==undefined){
				return false;
			}
			if ($before!==$after){
				if ($obj.setReloadPageAction==undefined){
					return false;
				}
				if (isHasAccess($obj)){
					if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->setAction()");
					$obj.setAction();
				}
			}else {
				if ($obj.setReloadPageAction==undefined){
					return false;
				}
				if (isHasAccess($obj)){
					if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->setReloadPageAction()");
					$obj.setReloadPageAction();
				}
			}
		}
	
		/**
		 *  ru:Проверяем есть ли доступ по функции в опции
		 */
		function isHasAccess($obj) {
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'isHasAccess()');
			if ($options.setFunction.isHasAccess!=undefined){
				return $options.setFunction.isHasAccess($obj,$options);
			}else {
				return true;
			}
		}
	
		/**
		 * ru:Для текущего шаблона получим класс из списка привязки шаблонов к классам
		 */
		function getFuncForController(){
			if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'getFuncForController()');
			return {
				controller:getFunctionFromOptions($loaded_state.to.controller),
				method:getFunctionFromOptions($loaded_state.to.controller+$options.strUnionControllerWithMethod+$loaded_state.to.method)
			};
		}
	
		/**
		 * ru:Получить обект функции
		 * 
		 * @access public
		 * @return void
		 */
		function getFunctionFromOptions($key){
			try{
				if ($options.setFuncByEvnineParamMatch[$key]!=undefined){
					var $setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}
			}catch($e){
				if ($options.debugToConsole) console.error(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'getFunctionFromOptions'+"try{...} catch(){"+$e+'}');
			}
			return undefined;
		}
	
		/**
		 * ru:Сохраним текущие данные по шаблону и методу, нужно для загрузки дополнительных скриптов
		 */
		if (!this.$reload_page){
			this.setMethodAndControllerFunc('init');
		}
		return this;
	};
}(document, jQuery);
//</script>
