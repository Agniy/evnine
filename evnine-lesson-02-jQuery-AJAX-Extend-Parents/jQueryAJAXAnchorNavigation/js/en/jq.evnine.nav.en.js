//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evNav
	* <br /> jQuery Plugin - AJAX websites based on anchor navigation
	* <br />
	* <br /> Dual licensed under the MIT or GPL Version 2 licenses
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {boolean} [debugToConsole=false]
	*  Debug to console
	* 
	* @config {string} [debugPrefixString='| ']
	*  Debug prefix for group of functions
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	*  If you want debug in IE 6-7, Safari, etc. using alert() as console.info 
	*
	* @config {boolean} [debugFunctionGroup=false]
	*  Use console.group as alternative to $options.debugPrefixString
	*
	* @config {boolean} [scrollToTopAfterAJAXCall=true]
	*  Scroll to the top of page after ajax is complete
	* 
	* @config {selector} [liveSelectorForAJAX=a, input:submit]
	*  jQuery selector for bind
	*
	* @config {bind} [liveBindType='click']
	*  Type of bind
	*
	* @config {string} [crossBrowserMinimumVersion.msie='8']
	*  Minimum version for compatibility
	* 
	* @config {boolean} [folowByChangeOfHistory=true]
	*  Check if the used the back button, or a link from the history
	*
	* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
	*  How many false attempts AJAX load
	*
	* @config {selector} [selectorForAJAXReplace=body]
	*  jQuery selector for replace content after ajax is complete
	* 
	* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
	*  The base path for ajax call
	*
	* @config {string} [ancorePreFix=!]
	*  Prefix in the anchor
	* <br />index.php#!test
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
	*  If match this RegExp, not user ajax load  
	* <br />$href.match(/^http:\/\//g)
	*
	* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
	*  If ajax, add param to the call url
	* <br />index.php?ajax=ajax
	*
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
	*  If match RegExp, use SEF mode
	*
	* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
	*  If ajax in SEF mode, add this param to the url
	* <br />index.html => index.ajax
	* 
	* @config {object} [functionsForAJAXIndicator=undefined]
	*  Function for the AJAX indicator off and on
	* 
	* @config {function} functionsForAJAXIndicator.On($options)
	* @config {function} functionsForAJAXIndicator.Off($options)
	*
	* @config {object} [loadAJAXOptions]
	*  Options for the AJAX load
	*
	* @config {string} [loadAJAXOptions.dataType=html]
	*  Page type
	*
	* @config {function} [loadAJAXOptions.success(responseText,statusText,$options)=jQuery.evNav.showResponse(responseText, statusText, $options)]
	*  Success function wrapper
	*
	* @config {function} [loadAJAXOptions.error(responseText,statusText,$options)=jQuery.evNav.showResponseError(responseText, statusText, $options)]
	*  Error function wrapper
	*/
new function (document, $, undefined) {
	jQuery.evNav = function($rewrite_options){
	// The current version of Evnine being used
	$EVNINE_VER="0.3";
	$EVNINE_NAME='evNav'+'.';
	/**
		*  Default setting
		* @example 
		* debugToConsole                    :false,
		* debugPrefixString                 :'|	',
		* debugToConsoleNotSupport          :false,
		* debugFunctionGroup                :false,
		* scrollToTopAfterAJAXCall          :true,
		* liveSelectorForAJAX               :'a, input:submit',
		* liveBindType                      :'click',
		* crossBrowserMinimumVersion        :{msie   :'8'},
		* folowByChangeOfHistory            :true,
		* maxErrorCountBeforeStopAJAXCall   :3,
		* selectorForAJAXReplace            :'body', 
		* scriptForAJAXCallAndSetAnchore    :'/index.php',
		* ancorePreFix                      :'!',
		* isHREFMatchThisRegExpSetNoUseAJAX :'^http://',
		* ifAJAXAddThisParamToScript        :'ajax=ajax',
		* isHREFMatchThisRegExpSetSEFMode   :'.html$',
		* ifSEFAJAXReplaceHREFMatchTo       :'.ajax',
		* functionsForAJAXIndicator         :undefined,
		* setJSFuncForLoadPage              :undefined
		*/
	var $options = jQuery.extend({
	//debugToConsole                  :true,
		debugToConsole                  :false,
	/**
		* @config {boolean} [debugToConsole=false]
		*  Debug to console
		*/
	debugPrefixString                 :'|	',
	//debugPrefixString               :' ',
	/**
		* @config {string} [debugPrefixString='| ']
		*  Debug prefix for group of functions
		*/
	debugToConsoleNotSupport          :false,
	//debugToConsoleNotSupport        :true,
	/**
		* @config {boolean} [debugToConsoleNotSupport=false]
		*  If you want debug in IE 6-7, Safari, etc. using alert() as console.info
		*/
	debugFunctionGroup                :false,
	//debugFunctionGroup              :true,
	/**
		* @config {boolean} [debugFunctionGroup=false]
		*  Use console.group as alternative to $options.debugPrefixString
		*/
	scrollToTopAfterAJAXCall          :true,
	/**
		* @config {boolean} [scrollToTopAfterAJAXCall=true]
		*  Scroll to the top of page after ajax is complete
		*/
	liveSelectorForAJAX               :'a, input:submit',
	/**
		* @config {selector} [liveSelectorForAJAX=a, input:submit]
		*  jQuery selector for bind
		*/
	liveBindType                      :'click',
	//liveBindType                    :'mouseover',
	/**
		* @config {bind} [liveBindType='click']
		*  Type of bind
		*/
	crossBrowserMinimumVersion        :{msie   :'8'},
	/**
		* @config {string} [crossBrowserMinimumVersion.msie='8']
		*  Minimum version for compatibility
		*
		*/
	folowByChangeOfHistory            :true,
	/**
		* @config {boolean} [folowByChangeOfHistory=true]
		*  Check if the used the back button, or a link from the history
		*/
	maxErrorCountBeforeStopAJAXCall   :3,
	/**
		* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
		*  How many false attempts AJAX load
		*/
	selectorForAJAXReplace            :'body', 
	/**
		* @config {selector} [selectorForAJAXReplace=body]
		*  jQuery selector for replace content after ajax is complete
		*/
	scriptForAJAXCallAndSetAnchore    :'/index.php',
	/**
		* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
		*  The base path for ajax call
		*/
	ancorePreFix                      :'!',
	/**
		* @config {string} [ancorePreFix=!]
		*  Prefix in the anchor
		* <br />index.php#!test
		*/
	isHREFMatchThisRegExpSetNoUseAJAX :'^http://',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
		*  If match this RegExp, not user ajax load  
		* <br />$href.match(/^http:\/\//g)
		*/
	ifAJAXAddThisParamToScript        :'ajax=ajax',
	/**
		* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
		*  If ajax, add param to the call url
		* <br />index.php?ajax=ajax
		*/
	isHREFMatchThisRegExpSetSEFMode   :'.html$',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
		*  If match RegExp, use SEF mode
		*/
	ifSEFAJAXReplaceHREFMatchTo       :'.ajax',
	/**
		* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
		*  If ajax in SEF mode, add this param to the url
		* <br />index.html => index.ajax
		*/
	functionsForAJAXIndicator         :undefined,
	/**
		* @config {object} [functionsForAJAXIndicator=undefined]
		* @config {function} functionsForAJAXIndicator.On($options)
		* @config {function} functionsForAJAXIndicator.Off($options)
		*  Function for the AJAX indicator off and on
		*/
	setJSFuncForLoadPage              :undefined
	},$rewrite_options);
	if ($rewrite_options!=undefined){
		$options.loadAJAXOptions = jQuery.extend({
			success: showResponse,error: showResponseError,dataType: 'html'
		},$rewrite_options.loadAJAXOptions);
	/**
		*  Wrapper callback function after the AJAX call if an error occurred<br />
		*  Performed when initializing a given function.
		*/
		if ($rewrite_options.loadAJAXOptions.error!=undefined){
			$options.loadAJAXOptions.error_for_options=$rewrite_options.loadAJAXOptions.error;
			$options.loadAJAXOptions.error=function(responseText, statusText){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() BEGIN");
				try{
					/**
						*  Passing the function is created with $options
						*  For example, what would execute methods of setJSFuncForLoadPage:$.evFunc({})<br />
						*/
					$options.loadAJAXOptions.error_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				/**
					*  Turn off the AJAX Indicator
					*/
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() END");
			};
		}
		/**
			*  Wrapper callback function after the AJAX call if an success occurred
			*  Performed when initializing a given function.<br />
			*/
		if ($rewrite_options.loadAJAXOptions.success!=undefined){
			$options.loadAJAXOptions.success=function(responseText, statusText){
			$options.loadAJAXOptions.success_for_options=$rewrite_options.loadAJAXOptions.success;
				try{
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"$options.loadAJAXOptions.success_for_options() BEGIN");
					$options.loadAJAXOptions.success_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				if ($options.scrollToTopAfterAJAXCall){
				 	jQuery('html,body').scrollTop(0);
				}
				$options.$ajax_is_load = false;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"$options.loadAJAXOptions.success_for_options() END");
			};
		}
	}

	/**
		*  Flag that would only load once Page
		*  
		* @var string
		* @access public
		*/
	$options.$loaded_href_hash_fix='';
	/**
		*  Currently loaded address will be used for callback function in evFunc()
		* jq.index.js
		* loadAJAXOptions:{...
		*  $options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
		*  ...
		*  $options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
		*  ...}
		* @var string
		* @access public
		*/
	$options.$loaded_href='';
	/**
		*  The current state of AJAX loading
		*  that is used to perform only one AJAX request<br />
		* 
		* @var boolean
		* @access public
		*/
	$options.$ajax_is_load=false;
	if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call BEGIN');
	/**
		*  Is init evFunc()?
		* @var string
		* @access public
	*/
	$options.flag_ev_func;
	if ($options.setJSFuncForLoadPage==undefined){
			$options.flag_ev_func=false;
	}else {
			$options.flag_ev_func=true;
	}
	/**
		*  Is there any support for console for debugging in the browser?
		*/
	if ($options.debugToConsole){
		if (!window.console||jQuery.evDev==undefined){
			$options.debugToConsole=false;
		}else {
			jQuery.evDev.initNotSupport();
		}
		/**
			*  If you use a group by the console, remove the prefix for the indentation
			*/
		if ($options.debugFunctionGroup){
			$options.debugPrefixString= '';
			jQuery.evDev.initGroupFunctionCall($options.debugToConsoleNotSupport);
		}
	}

	/** getInt($int)<br />
		*  Function to obtain the number of
		*  
		* @param {string} $int
		* @access public
		* @return int
		*/
	function getInt($int){
		return parseInt(parseFloat($int), '10');
	}

	/** trim(str)<br />
		* 
		*  Remove the spaces before and after.
		* 
		* @param {string} $str 
		* @access public
		* @return string
		*/
	function trim(str){
		return str.replace(/^ +/g,'').replace(/ +$/g,'');
	}

	/**
		*  Obtain the name of the script used to install anchors.
		* 
		* @var string
		* @access public
		*/
	$options.scriptNameForAJAX= $options.scriptForAJAXCallAndSetAnchore.replace(/^.*(\\|\/|\:)/, '');
	
	/** isHasCrossBrowserMinimumVersion()<br />
		* 
		*  Check function.<br /> 
		*  Is there a minimum version of the browser to work with to anchor navigation.
		* 
		* @access public
		* @return {boolean}
		*/
	function isHasCrossBrowserMinimumVersion() {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion() BEGIN");
		var userAgent = navigator.userAgent.toLowerCase();
		jQuery.browser = {
			'version': (userAgent.match( /.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/ ) || [])[1],
			'chrome': (/chrome/).test( userAgent ),
			'safari': (/webkit/).test( userAgent ) && !(/chrome/).test( userAgent ),
			'opera': (/opera/).test( userAgent ),
			'msie': (/msie/).test( userAgent ) && !(/opera/).test( userAgent ),
			'mozilla': (/mozilla/).test( userAgent ) && !(/(compatible|webkit)/).test( userAgent )
		};
		/**
		 *  Does user browser work with the evNav plugin?
		 * 
		 * @var boolean
		 * @access public
		 */
		var $return=true;
		jQuery.each($options.crossBrowserMinimumVersion, function($browser,$version){
			if (jQuery.browser[$browser]){
				if (getInt(jQuery.browser.version)>=$version){
					if ($options.debugToConsole) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if ($options.debugToConsole) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion return true");
					$return=true;
					return '';
				}else {
					if ($options.debugToConsole) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if ($options.debugToConsole) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion return false");
					$return=false;
					return '';
				}
			}
		});
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion() END");
		return $return;
	}
	
	/**
		*  Is there a minimum version of the browser to work with to anchor navigation<br />
		* 
		* @var {boolean} [isAllowThisBrowser=true]
		* @access public
		*/
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();
	if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

	/** getRegSafeString($str)<br />
		* 
		* For get safe AJAX URN
		* 
		* @param {string} $str 
		* @access public
		* @return {string}
		*/
	function getRegSafeString($str){
		return $str.replace(/(["'\.\-])(?:(?=(\\?))\2.)*?\1/g,"");
	}
	
	/** getClickHref($that)<br />
		* 
		*  Run the AJAX query based on the type of element clicked
		* 
		* @param {jQuery object} $that
		* @access public
		* @return {boolean}
		*/
	function getClickHref($that){
		if (jQuery($that).attr('href')!=undefined){
			$href = jQuery($that).attr('href');
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getClickHref $href: "+$href);
			$reg = new RegExp($options.isHREFMatchThisRegExpSetNoUseAJAX,"g");
			if ($href.match($reg)){
			/**
				*  Case there is no need to use AJAX
				*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is no ajax href");
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
				return true;
			}else {
			/**
				*  Use for processing AJAX GET request when user click on the link
				*/
				getAJAXHref($href);
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax href");
			}
		}else if (jQuery($that).attr('type')==='submit'){
		/**
			*  If you clicked on the submit button via AJAX POST
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax submit");
				getAJAXSubmit($that);
		}
		return false;
	}
	
	/** getURLWithFlag($href,$post_fix,$post_sef)<br />
		* 
		*  Add param to URN
		* 
		* @param {string} $href
		*  URN
		* 
		* @param {string} $post_fix
		*  Parameters of the request.
		* 
		* @param {string} $post_sef
		*  For SEF mode
		*
		* @return {string} '?'+$post_fix or $href.replace($reg, $post_sef) or $href+'&'+$post_fix or $href+'?'+$post_fix
		*/
	function getURLWithFlag($href,$post_fix,$post_sef) {//Установить флаг в урл что запрос делаем аяском в .htaccess отловим потом
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() BEGIN");
		if ($href===''){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return '?'+$post_fix;
		}
		$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
		if ($href.match($reg)){
		/**
			*  IF SEF mode
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match SEF ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href.replace($reg, $post_sef);
		}else if($href.match(/\?/)) {
		/**
			*  If you do not SEF is the settings in the address part
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match & ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'&'+$post_fix;
		}else {
		/**
			*  If you do not SEF and no parameters in the address part
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match ? ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'?'+$post_fix;
		}
	}
	
	/** isURLBaseAJAX()<br />
		* 
		*  Is the current URL specified in the basic $options.scriptForAJAXCallAndSetAnchore?
		* 
		* @example 
		* /index.php#!?test=test 
		* return true
		*
		* /index.php?param=param#!?test=test 
		*  return false - in the ?param=param
		* 
		* @access public
		* @return {boolean}
		*/
	function isURLBaseAJAX() {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() BEGIN");
		if (location.search!==''){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return false");
			return false;
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX.$options.scriptForAJAXCallAndSetAnchore="+$options.scriptForAJAXCallAndSetAnchore);
		if (location.pathname===$options.scriptForAJAXCallAndSetAnchore){
			/**
				*  When the name matches the name of the options to initialize
				*/
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return true");
			return true;
		}else {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return false");
			return false;
		}
	}
	
	/** setURLToBrowser($base,$href)<br />
		* 
		*  This function set the anchor link if browser allows it<br />
		*  If no base address is used, set the basic URL of the script and anchor as URN.
		* 
		* @example 
		* /HelloAJAXJQuery/index.php?param=param#!?test=test
		* =
		* /HelloAJAXJQuery/index.php#!?test=test
		* 
		* @param {string} $base
		*  The current URL
		*
		* @param {string} $href
		*  Link to go to
		* 
		* @return void
		*/
	function setURLToBrowser($base,$href) {
		$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore+'|\\?');
		$href=$href.replace($reg,'');
		if ($options.isAllowThisBrowser){
			window.location = $base+'#'+$options.ancorePreFix+$href;
		}else {
			window.location = $base+$href;
		}
	}
	
	/** setURLToHashAndLocation($href)<br />
		*  Set URN as anchor
		* 
		* @param {string} $href 
		*  Link to go to.
		* 
		* @access public
		* @return {string} $href or $options.scriptForAJAXCallAndSetAnchore 
		* 
		*/
	function setURLToHashAndLocation($href) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"setURLToHashAndLocation($href="+$href+")");
		if ($href==='/') {
		/**
			*  If you want to go home page, go to the base script page.
			*/
			$options.$loaded_href_hash_fix= '';
			window.location.hash = $.URLDecode($options.ancorePreFix);
			if ($options.debugToConsole) document.title = '';
			return $options.scriptForAJAXCallAndSetAnchore;
		}else {
		/**
			*  Set the anchor link.
			*/
			$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore);
			$options.$loaded_href_hash_fix=$hash_href_without_script_name=$href.replace($reg,'');
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"setURLToHashAndLocation.hash_href_without_script_name="+$hash_href_without_script_name);
			window.location.hash = $.URLDecode($options.ancorePreFix+$hash_href_without_script_name);
			if ($options.debugToConsole) document.title = $href;
			return $href;
		}
	}

	/** setMethodTOURL ($href,$method)<br />
		* 
		*  For the case in the button method is specified, set this method in the link.
		* 
		* @access public
		* 
		* @param {string} $href
		* @param {string} $method
		* 
		* @return {string} $href 
		*  Return URN with the added method.
		* 
		*/
		function setMethodTOURL($href,$method) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() BEGIN');
			if ($method.length===0){
			/**
				*  The case when the method is not specified.
				*/
				return $href;
			}else {
				//TODO TEST!!!!! SEF add method
				//if ($href.match(/\.html$/)){
				$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
				if ($href.match($reg)){
				/**
					*  Check the case when using the SEF.
					*/
					$href = $href.substring(1);
					$method_replace  = $href.match(/.*\//);
					$method_replace=$method_replace.toString().split(/\//);
					if ($method_replace[1].match(/=/)){
					/**
						*  Case when using the SEF for controller.
						* 
						*/
						$method_match==$method_replace[0]+'/';
						$method='/'+$method_replace[0]+'/'+$method;
					}else {
						/**
							*  Case when using the SEF for method.
							*/
						$method_match=$method_replace[1];
					}
					$href = $href.replace($method_match,$method);
				}else {
					/**
						*  Case when handed a standard URN.
						*/
					$method_match = $href.match(/&m=.*/);
					$href = $href.replace($method_match,"&m="+$method);
				}
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() END');
				return $href;
			}
		}

	/** getAJAXSubmit($that)<br />
		*  Perform POST request.
		* 
		* @param {jQuery object} $that
		*  Object with the data on the form.
		* 
		* @access public
		* @return void
		*/
	function getAJAXSubmit($that) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit");
		$method = jQuery($that).attr('name');
		$form = jQuery($that).parents('form');
		$length = $method.length;
		if ($length>6){
			/**
				*  Extract the method_name <input name="submit[method_name]" type="submit" />
				*/
			$method = $method.substring(7, $length-1);
			$options.loadAJAXOptions.data= {'submit':$method};
			$href =setMethodTOURL($form.attr('action'),$method);
		}else {
			/**
				*  The case when the method is not specified.
				*/
			$href =setMethodTOURL($form.attr('action'),$method);
			$options.loadAJAXOptions.data='';
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit.$options.loadAJAXOptions.data: "+$options.loadAJAXOptions.data);
		$id = $form.attr('id');
		if ($id!=undefined){
			/**
				*  If not specified the id for the form, generate your own.
				*/
			$id = 'tmp_'+Math.floor(Math.random()*100);
			$form.attr('id',$id);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$id="+$id);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$href="+$href);
		getAJAX($href,'submit',$id,0);
	}
	
	/** getAJAXHref($href)<br />
		*  Perform a GET request
		* 
		* @param {string} $href 
		* 
		* @access public
		* @return void
		*/
	function getAJAXHref($href) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXHref () BEGIN");
		getAJAX($href,'href','',0);
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXHref () END");
	}
	
	/** showResponseError(responseText, statusText)<br />
		*  Callback function by default, when an error in the AJAX request.
		* 
		* @access public
		* @param responseText
		* 
		* @param statusText
		* 
		* @return void
		*/
	function showResponseError(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError.responseText="+responseText);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError.statusText="+statusText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
		/**
			*  Turn off the AJAX loading indicator.
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() END");
	}
	
	/** showResponse(responseText, statusText)<br />
		* 
		*  Callback function by default, when the request is completed successfully.
		* 
		* @access public
		* @param responseText
		* 
		* @param statusText
		* 
		* @return void
		*/
	function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() BEGIN");
		jQuery($options.selectorForAJAXReplace).html(responseText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
		/**
			*  Turn off the AJAX loading indicator.
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() END");
	}
	
	/** getAJAX($href,$type,$id,$error_count)<br />
		* 
		*  Processing request via jQuery plugin form in include.js.
		* 
		* @param {string} [$href='']
		* 
		* @param {string} [$type='']
		*  Request type submit [ POST ] and GET
		*
		* @param {string} [$id='']
		*  The form ID
		* 
		* @param {int} [$error_count=undefined]
		*  The count of errors in the AJAX call.
		*
		* @access private
		* @return void
		*/
	function getAJAX($href,$type,$id,$error_count){
		if ($error_count==undefined){
		/**
			*  Initialize the default value of the error count.
			*/
			$error_count=0;
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.$ajax_is_load="+$options.$ajax_is_load);
		if (!$options.$ajax_is_load){
			/**
				*  The case when the user has activated a few times to AJAX loading.
				*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions: ");
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($options.loadAJAXOptions,jQuery.evDev.getTab($options.debugPrefixString,5));
			try{
				/**
					*  Watch for errors.
					*/
				if (isURLBaseAJAX()){
					/**
						*  Is the address specified in the basic $options.scriptForAJAXCallAndSetAnchore?
						*/
					$options.$ajax_is_load = true;
					$options.$loaded_href= $href;
					$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions.url="+$options.loadAJAXOptions.url);
					if (typeof $options.functionsForAJAXIndicator.On==='function'){
						/**
							*  Turn on the AJAX loading indicator.<br />
							*/
						$options.functionsForAJAXIndicator.On($options);
					}
					if ($type==='submit'){
					/**
						*  Send data via POST.
						*/
						$('#'+$id).ajaxSubmit($options.loadAJAXOptions);
					}else {
					/**
						*  Send data via GET.
						*/
						$.ajax($options.loadAJAXOptions);
					}
				}else {
				/**
					*  The case when the URL has the parameters. Set the default URL for anchor
					*
					* /HelloAJAXJQuery/index.php?param=param#!?test=test
					* /HelloAJAXJQuery/index.php>>?param=param<<#!?test=test
					*/
					setURLToBrowser($options.scriptForAJAXCallAndSetAnchore,$href);
				}
			}catch($bug){
				/**
					*  Case of errors.
					*/
				$options.$ajax_is_load = false;
				if ($options.debugToConsole)        console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$bug="+$bug);
				$options.$loaded_href= '';
				$error_count++;
				if ($error_count<=$options.maxErrorCountBeforeStopAJAXCall){
					/**
						*  Repeat this operation once more, but not more than the maximum
						*  error of the options.
						*/
					getAJAX($href,$type,$id,$error_count);
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
				/**
					*  Turn off the AJAX loading indicator.
					*/
					$options.functionsForAJAXIndicator.Off($options);
				}
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() END");
	}
	
		
	/** getHash(loc)<br />
		*  Obtain a cross-browser anchor of
		* 
		* @param {string} $loc 
		* 
		* @access public
		* @return {string}
		*/
		function getHash(loc){
			loc = loc.toString();
			if (loc.indexOf("#") != -1){
				return loc.substring(loc.indexOf('#'));
			} else {
				return "";
			}
		}
		
	/** setAnchoreClearWithoutURL()<br />
		* 
		*  When first initialized, loaded, if there is a link from the anchor
		* <br />#!?test=test
		* <br />/HelloAJAXJQuery/index.php#!?test=test
		* 
		* @access public
		* @return void
		*/
	function setAnchoreClearWithoutURL(){
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() BEGIN");
		$href=getHash(location);
		$reg=new RegExp('^#'+$options.ancorePreFix,"g");
		$href= $href.replace($reg,"");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.$href_after_replace="+$href);
		if ($href){
			if ($options.flag_ev_func){
				$options.setJSFuncForLoadPage.$reload_page=true;
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.$href: "+$href);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.isURLBaseAJAX return true");
			$($options.selectorForAJAXReplace).html();
			getAJAX($options.scriptForAJAXCallAndSetAnchore+$href,'href');
		}else {
			/**
				*  Save the current controller and method
				*  User jQuery.evFunc
				*/
			if ($options.flag_ev_func&&!$options.setJSFuncForLoadPage.$reload_page){
				$options.setJSFuncForLoadPage.setMethodAndControllerFunc('init');
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() END");
	}
	/**
		*  When first initialized, loaded, if there is a link from the anchor #!axax=ajax
		*/
	setAnchoreClearWithoutURL();
	if ($options.folowByChangeOfHistory&&$options.isAllowThisBrowser){
		/**
			*  If set the option to follow the history
			*/
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange() BEGIN");
			if (window.location.hash){ 
				/**
					*  If there is an anchor at init
					*/
				$reg=new RegExp('^#'+$options.ancorePreFix,"g");
				$hash = location.hash.replace($reg,"");
				if ($options.$loaded_href_hash_fix!==$hash){
					/**
						*  No need to load already loaded link in the anchor.
						*/
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"hashchange=YES");
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+"|"+$hash + "|!=|"+$options.$loaded_href_hash_fix+"|");
					getAJAX($hash,'href');
				} else {
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"hashchange=NO");
				}
			}
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange() END");
		});
	}
	
	if ($options.debugToConsoleNotSupport){
		/**
			*  If you want debug in IE 6-7, Safari, etc. using alert() as console.info
			*/
		if ($options.debugToConsole) console.warn("END");
	}
	if ($options.debugToConsole) {
		/**
			*  Debug to console
			*/
		console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call END');
	}
	if ($options.isAllowThisBrowser){
		/**
			*  Is there a minimum version of the browser to work with anchor navigation
			*/
		return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			/**
				*  For selector from the options set the AJAX anchor navigation function.
				*/
			return getClickHref(this);
		});
	}
};
}(document, jQuery);
//</script>
