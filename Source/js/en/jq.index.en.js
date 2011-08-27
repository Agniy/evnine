//<script type="text/javascript">
jQuery(document).ready(function(){  
	/**
	* @name $.evNav
	* @author ev9eniy.info 
	* <a href="http://ev9eniy.info/evnine">ev9eniy.info/evnine</a>
	* 
	*
	* @class $.evNav
	* <br /> Init $.evNav() jQuery plugin with options and 
	* <br /> $.evFunc() jQuery plugin as callback function
	* <br />
	* 
	* @config {object} [ =<a href="jQuery.evNav.html#constructor">jQuery.evNav arguments</a>]
	*  Init evNav Plugin<br />
	* 
	* @config {object}  [setJSFuncForLoadPage:$.evFunc({})=<a href="jQuery.evFunc.html#constructor">jQuery.evFunc arguments</a>] 
	*  Init evFunc plugin<br />
	* 
	* @example 
	*  Example of running plugin with debug to console and group prefix.
	* $.evNav({
	*	scriptForAJAXCallAndSetAnchore:'/HelloAJAXJQuery/index.php',
	*	liveSelectorForAJAX           : 'a',
	*	debugToConsole                :true,
	*	debugPrefixString             :'|	'
	*	functionsForAJAXIndicator     :{
	*		On:function($options){$('#ajax_load').show();},
	*		Off:function($options){$('#ajax_load').off();}
	*	},
	*	});
	*	
	* console:
	* evNav.$options.isAllowThisBrowser=true
	* evNav.setAnchoreClearWithoutURL() BEGIN
	*|	evFunc.getParseURL($href=/HelloAJAXJQuery/index.php) BEGIN
	*|	|	return [c] => default
	*|	|	return [m] => default
	*|	|	return [url] => /HelloAJAXJQuery/index.php
	*|	evFunc.getParseURL($href=/HelloAJAXJQuery/index.php) END
	* evNav.setAnchoreClearWithoutURL() end
	*
	*  Example for AJAX Indicator.
	* $.evNav({
	*	functionsForAJAXIndicator: {
	*		On:function($options){$('#ajax_load').show();},
	*		Off:function($options){$('#ajax_load').off();}
	*	},
	*});
	*
	*  case - save ajax indicator object in $options.
	* $.evNav({
	*	ajax_load=$('#ajax_load'),
	*	functionsForAJAXIndicator: {
	*		On:function($options){$($options.ajax_load).show();},
	*		Off:function($options){$($options.ajax_load).hide();}
	*	},
	*});
	*
	* @public
	* @returns {object} jQuery
	*/
$.evNav({
	debugToConsole                    :true,
	debugPrefixString                 :'|	',
	scriptForAJAXCallAndSetAnchore    :'/HelloAJAXJQuery/index.php',
	functionsForAJAXIndicator         : {
		On:function($options){if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+"$options.functionsForAJAXIndicator.On()");},
		Off:function($options){if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.functionsForAJAXIndicator.Off()");}
	},
	//  Options for the AJAX load
	loadAJAXOptions                   :{
		// In the case of a successful request - show a response. 
		success: function (responseText, statusText, $options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() BEGIN");
			// Setting function of flags for evFunc()
			$options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
			jQuery($options.selectorForAJAXReplace).html(responseText);
			// Call function after jQuery html replace.
			$options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() END");
		}
	},
	// Init evFunc plugin
	setJSFuncForLoadPage              :$.evFunc({
		// Functions for execute after ajax complete
		setFunction:
		// Init with setJSFuncForLoadPage.$options
				'default'               :function($options) 
				{
					// Default access level
					this.access='1';
					// if load new method
					this.setAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setAction()");
						// include once method with call back function
						$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test.js',function(){
							$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test2.js',function(){
								getTestScript();
							});
						});
					};
					// if reload same page(method)
					this.setReloadPageAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setReloadPageAction()");
					};
					// if change current page(method)
					this.unSetAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.unSetAction()");
					};
				},
				// Is user has access for function, init with setFunction[function] and setJSFuncForLoadPage.$options
				'isHasAccess':function($obj,$options) {
					if ($obj.access==undefined){
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}else {
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}
					return true;
				}
		},
			// Function is for controller with method.
			setFuncByEvnineParamMatch       :{
				//For controller
				'validation'                  :'default',
				'default'                     :'default',
				'param1'                      :'default',
				//controller.method
				'param1.param1'               :'default'
			}
			// URN and related functions
			//setFuncByHREFMatch              :{
			//	'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			// RegExp and associated functions.
			//setFuncByMatchRegHREF              :{
			//	'.*index\.php.*'                :'default'
	})
});
});
//</script>
