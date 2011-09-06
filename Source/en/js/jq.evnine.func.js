//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evFunc
	* <br /> jQuery plugin - call function after ajax complete or reload page
	* <br />
	* <br /> Dual licensed under the MIT or GPL Version 2 licenses
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {object}  [=undefined]
	*  Init evFunc plugin
	* 
	* @config {object} [controller={paramName:'c',defaultValue:'default'}]
	*  Parameter for the controller and the default value
	* 
	* @config {object} [method={paramName:'m',defaultValue:'default'}]
	*  Parameter to the method and the default value
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode='.html$']
	*  If match RegExp, use SEF mode
	*
	* @config {object} [setFunction:{function_name:function()}=undefined]
	*  Functions for execute after ajax complete
	* 
	* @config {function} [setFunction[function_name]($options)=undefined]
	*  Init with $options
	* 
	* @config {function:return boolean} [setFunction.isHasAccess($obj,$options)=undefined]
	*  Is user has access for function, init with (setFunction[function_name],$options)
	* 
	* @config {string} [strUnionControllerWithMethod='.']
	*  The symbol for the union of controller and methods in the setFuncByEvnineParamMatch
	*
	* @config {object} [setFuncByEvnineParamMatch:<br />
	* {arguments.controller+arguments.strUnionControllerWithMethod+arguments.method:function_name}=undefined]
	*  Controller with method and function
	*
	* @config {object} [setFuncByEvnineParamMatch:{string:function_name}=undefined]  
	*  URN and related functions
	*
	* @config {object} [setFuncByHREFMatch:{RegExp:function_name}=undefined]  
	*  RegExp and associated functions<br />
	* 
	* @config {boolean} [debugToConsole=false]
	*  Debug to console
	* 
	* @config {string} [debugPrefixString='| ']
	*  Debug prefix for group of functions<br />
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	*  If you want debug in IE 6-7, Safari, etc. using alert() as console.info
	*
	* @config {boolean} [debugFunctionGroup=false]
	*  Use console.group as alternative to $options.debugPrefixString
	*/
new function (document, $, undefined) {
	jQuery.evFunc = function($rewrite_options) {
		var $EVNINE_VER="0.3";
		var $EVNINE_NAME='evFunc'+'.';
		/**
			*  Default setting
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
				*  If match RegExp, use SEF mode
				*/
			controller:{
				paramName:'c',defaultValue:'default'
			},
			/**
				*  Parameter for the controller and the default value
				*/
			method:{
				paramName:'m',defaultValue:'default'
			},
			/**
				*  Parameter to the method and the default value
				*/
			debugToConsole                    :true,
			/**
				*  Debug to console
				*/
			debugPrefixString                 :'|	',
			//debugPrefixString               :' ',
			/**
				*  Debug prefix for group of functions
				*/
			debugToConsoleNotSupport          :false,
			//debugToConsoleNotSupport        :true,
			/**
				*  If you want debug in IE 6-7, Safari, etc. using alert() as console.info 
				*/
			debugFunctionGroup              :false,
			//debugFunctionGroup                :true,
			/**
				*  Use console.group as alternative to $options.debugPrefixString  
				*/
			strUnionControllerWithMethod    :'.'
			/**
				*  The symbol for the union of controller and methods in the setFuncByEvnineParamMatch
				*/
			//setFuncByEvnineParamMatch       :{
				//'default.default'               :'default',
			//}
			/**
				*  Function is for controller with method.
				*/
			//setFuncByHREFMatch              :{
				//'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			/** 
				*  HREF and related functions.
				*/
			//setFuncByMatchRegHREF              :{
				//'.*index\.php.*'                :'default'
			//}
			/**
				*  Regular expression and associated functions.
				*/
		},$rewrite_options);
		/**
			*  Flag evNav plugin for the first page load
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
			*  The loaded scripts
			* @example
			* '/jq.ui.js': true
			* @access private
			*/
		var $include_scripts={};
		/**
			*  Methods loaded page
			* @example
			*  $options.controller.paramName:'',
			*  $options.method.paramName:'',
			* @access private
			*/
		var $current_method_class=false;
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'init BEGIN');
		/**
			*  Object to store the loaded methods and new.
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
			*  Get a script and execute the function when it is loaded.
			* 
			* @param {string} $script_href
			*  Link to download script
			* 
			* @param {function} $fun
			*  Callback function
			*
			* @return void
			*/
		$options.include_once=function($script_href,$fun) {
			/**
				*  Save function for future access
				*/
			var $function=$fun;
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'include_once()');
			if ($include_scripts[$script_href]==undefined){
			/**
				*  Check whether the script is loaded
				*/
				jQuery.getScript($script_href, function() {
					/**
						*  We set a flag that the script is loaded
						*/
					$include_scripts[$script_href]=true;
					jQuery(document).ready(function(){
						if ($options.debugToConsole) 
							console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
						try {
							/**
								*  Watch for errors. Run callback function.
								*/
							$function();
						}catch($e){
							/**
								*  Case of errors.
								*/
							if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setInitJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
						}
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() END');
					});
				});
			}else {
			/**
				*  Case if the script is already loaded, run the callback function
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'getScript.ready.function() BEGIN');
				try {
					/**
						*  Watch for errors. Run callback function.
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
			*  Call of the plug-in navigation setup flag
			*  there is no need to reload page
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
			*  Function to execute after displaying data from a script
			*  specified in the options
			* 
			* @param {string} $load_href
			*  Link to processing
			*
			* @access public
			* @return void
			*/
		this.setPostCallShowResponse=function($load_href) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') BEGIN');
			/**
				*  Parse the address loaded in the page
				*/
			this.getParseURLAndSave($load_href);
			if ($loaded_state.to[$options.controller.paramName]!=undefined||
				$loaded_state.to[$options.method.paramName]!=undefined)
			{
			/**
				*  Set the option to delete the action
				*/
				setUnsetJSFuncForHREF('unSetAction');
			}
			/**
				*  Get the address of the current controller and method.
				*/
			this.setMethodAndControllerFunc();
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setPostCallShowResponse($load_href='+$load_href+') END');
		};

		/** this.getParseURLAndSave=function($href)<br />
			* 
			*  Parse the URL
			* 
			* @param {string} $href
			*  Link to processing
			* 
			* @access public
			* @return void
			*/
		this.getParseURLAndSave=function($href){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() BEGIN');
			if ($loaded_state.to.url===$href){
			/**
				*  If the URL are the same do nothing
				*/
				return $loaded_state.to;
			}
			/**
				*  Save the past state
				*/
			$loaded_state.from= $loaded_state.to;
			/**
				*  Obtain a method and controller of the URL
				*/
			$loaded_state.to = getParseURL($href,4);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$loaded_state: ');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($loaded_state,jQuery.evDev.getTab($options.debugPrefixString,5),$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'getParseURLAndSave() END');
		};

		/** this.getMethodFromSEFURL=function($href)<br />
			* 
			*  Obtain a method and a controller in the SEF URL.
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
			*  Obtain a method and controller of the URL
			* 
			* @param {string} $href
			*  Link to processing
			* 
			* @param {int} $tab_level
			*  Indentations in debug mode
			* 
			* @access private
			* @return {object} {$options.controller.paramName:function(),$options.method.paramName:function(),$url=string}
			* <br /> Returns an object for save and get the functions<br /> by controller and method name.
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
				*  If the SEF URL
				*/
				$parse_url = this.getMethodFromSEFURL($href);
			}else {
			/**
				*  Use the plugin to parse the references.
				*/
				$parse_url = $.parseQuery($href);
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,$tab_level+1)+$EVNINE_NAME+'$parse_url:');
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($parse_url,jQuery.evDev.getTab($options.debugPrefixString,$tab_level+2));
			if (!$parse_url[$options.controller.paramName] || $parse_url[$options.controller.paramName]===null || $parse_url[$options.controller.paramName]==='' || $parse_url[$options.controller.paramName]==undefined){
				/**
					*  Specify the controller in the link?
					*/
				$controller=$options.controller.defaultValue;
			}else {
			/**
				*  If the controller name is not specified,<br /> 
				*  use the default value of the options
				*/
				$controller=$parse_url[$options.controller.paramName];
			}
			if (!$parse_url[$options.method.paramName] || $parse_url[$options.method.paramName]===null || $parse_url[$options.method.paramName]==='' || $parse_url[$options.method.paramName]==undefined){
				/**
					*  Specify the method in the link?
					*/
				$method=$options.method.defaultValue;
			}else {
			/**
				*  If the method name is not specified,<br /> 
				*  use the default value of the options
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
		 *  Set the option to delete the action
		 * 
		 * @param {string} $function_callback
		 *  Function callback.
		 * 
		 * @access private
		 * @return void
		 */
		function setUnsetJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					*  Has the controller since the last call?
					*  If yes, run the callback function.
					*/
				if ($loaded_state.from[$options.controller.paramName]!==$loaded_state.to[$options.controller.paramName]){
					setRunFunction($current_method_class[$options.controller.paramName],$loaded_state.from[$options.controller.paramName],$loaded_state.to[$options.controller.paramName],$function_callback);
				}
				/**
					*  Has the method since the last call?
					*  If yes, run the callback function.
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
			*  Initialize the function to callback.
			* 
			* @param {string} $function_callback
			*  Function callback.
			* 
			* @access private
			* @return void
			*/
		function setInitJSFuncForHREF($function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setInitJSFuncForHREF($function_callback='+$function_callback+') BEGIN');
			try {
				/**
					*  For the controller and the method of reference is the method.
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
			*  Start the function.
			* 
			* @param {object} $obj
			*  An object with methods.
			* 
			* @param {string} $function_callback
			*  Function callback.
			*
			* @param {string} $before
			*  Method or controller before.
			* 
			* @param {string} $after
			*  Method or controller after.
			*
			* @access private
			* @return {boolean}
			* <br /> true - if the function executed successfully.<br /> false - if function failed to call
			*/ 
		function setRunFunction($obj,$before,$after,$function_callback){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() BEGIN');
			if ($obj==undefined){
			/**
				*  If not specified object.
				*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:$obj==undefined');
				return false;
			}
			if ($before!==$after){
				/**
					*  Compare the method and controller before and after.
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']!==[$after='+$after+']');
				if (isHasAccess($obj)){
					/**
						*  Check the level of access to the function of the options?
						*/
					$obj = $obj[$function_callback];
					if (typeof $obj==='function'){
						/**
							*  Executed if the object specified function.
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
					*  Case reload the same page when the method or the controller has not changed.
					*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+'[$before='+$before+']===[$after='+$after+']');
				if ($obj.setReloadPageAction==undefined){
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return false, case:setReloadPageAction==undefined');
					return false;
				}
				if (isHasAccess($obj)){
					/**
						*  Check the level of access to the function of the options?
						*/
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,6)+"$options.setJSFuncForLoadPage.setFunction.setReloadPageAction()");
					$obj.setReloadPageAction();
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'setRunFunction() END: return true');
					return true;
				}
			}
		}

		/** setMethodAndControllerFunc=function($mode)<br />
			*  Set a controller and method of URL.
			* 
			* @param {string} [$mode='']
			*  Flag for checking the first initialization.
			*
			* @access public
			* @return void
			*/
		this.setMethodAndControllerFunc=function($mode) {//Сохраним текущие данные по шаблону и методу
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') BEGIN');
			if ($mode==='init'){
				/**
					*  If the first initialization.
					*  To set the initial values of the states before and after.
					*/
				$loaded_state.to = getParseURL(location.pathname,4);
				/**
					*  Initialize the function to callback
					*/
				setInitJSFuncForHREF('setAction');
				$loaded_state.from= $loaded_state.to;
			} else {
				/**
					*  Initialize the function to callback
					*/
				setInitJSFuncForHREF('setAction');
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+'setMethodAndControllerFunc($mode='+$mode+') END');
		};
	
		/** isHasAccess($obj)<br />
			*  Check the level of access to the function of the options?
			* 
			* @param {object} $obj
			*  Object with the method. Check, have access to it?
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
			*      Default access level
			*     this.access='1';
			*    }
			*   }
			*  })
			* });
			* 
			* @access private
			* @return {boolean}
			*  default is true.
			*  если 
			*/
		function isHasAccess($obj) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() BEGIN');
			if ($options.setFunction.isHasAccess!=undefined){
				/**
					*  If the object specified.
					*  Return the response from the method of access checks.
					*  Which is set in the options.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun $options.setFunction.isHasAccess($obj,$options)');
				return $options.setFunction.isHasAccess($obj,$options);
			}else {
				/**
					*  When the checks do not always return - there is access.
					*/
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+'isHasAccess() END retrun true');
				return true;
			}
		}

		/** function getFuncForControllerAndMethod()<br />
			* 
			*  For the controller and the method of reference is the method
			* 
			* @access private
			* @return {object} {[$options.controller.paramName],$obj[$options.method.paramName]}
			*/
		function getFuncForControllerAndMethod(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() BEGIN');
			var $obj={};
			/**
				*  Get on a key function of the options
				*  Key from the parsing links
				*/
			$obj[$options.controller.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]);
			$obj[$options.method.paramName]=getFunctionFromOptions($loaded_state.to[$options.controller.paramName]+$options.strUnionControllerWithMethod+$loaded_state.to[$options.method.paramName]);
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($obj,jQuery.evDev.getTab($options.debugPrefixString,6)+'return ',$options.debugPrefixString);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+'getFuncForControllerAndMethod() END');
			return $obj;
		}
	
		/** function getFunctionFromOptions($key)<br />
			*  Get on a key function of the options
			* 
			* @param {string} $key
			*  Key from the parsing links
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
					*  Is set key?
					*/
					$setFunctionKey = $options.setFuncByEvnineParamMatch[$key];
					if (typeof $options.setFunction[$setFunctionKey]==='function'){
					/**
						*  Return the function to call
						*/
						return new $options.setFunction[$setFunctionKey]($options);
					}
				}
				if ($options.setFuncByHREFMatch!=undefined) {
				/**
					*  Verify the match with the address
					*/
						if ($options.setFuncByHREFMatch[$loaded_state.to.url]!=undefined){
						$setFunctionKey = $options.setFuncByHREFMatch[$loaded_state.to.url];
						/**
							*  If the object in the array is a function. 
							*/
						if (typeof $options.setFunction[$setFunctionKey]==='function'){
							if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'$options.setFuncByHREFMatch');
							return new $options.setFunction[$setFunctionKey]($options);
						}
					}
				}
				if ($options.setFuncByMatchRegHREF!=undefined) {
				/**
					*  To verify that the regular expression.
					*/
					var obj={};
					$.each($options.setFuncByMatchRegHREF, function($href_reg,$setFunctionKey){
						$reg = new RegExp($href_reg,"g");
						if ($loaded_state.to.url.match($reg)){
						/**
							*  If the object in the array is a function
							*/
							if (typeof $options.setFunction[$setFunctionKey]==='function'){
								$obj=new $options.setFunction[$setFunctionKey]($options);
							}
						}
					});
					if ($obj!=undefined){
					/**
						*  If the object specified return it
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
			*  Link to themselves for access from the jQuery evNav plugin
			*/
		return this;
	};
}(document, jQuery);
//</script>
