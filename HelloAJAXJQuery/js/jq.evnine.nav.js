//<script type="text/javascript">
/*
 * JQuery AJAX Nav Evnine
 *
 * Copyright 2011, (c) ev9eniy.info
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 */
new function (document, $, undefined) {
	jQuery.setEvnineNav = function($rewrite_options){
		// The current version of Evnine being used
	$EVNINE_VER="0.3";
	$EVNINE_NAME='$.setEvnineNav'+'->';
		
	//Default setting
	/* настройки по умолчанию*/
	var $options = jQuery.extend({},$rewrite_options);
	if ($rewrite_options!=undefined){
		$options.loadAJAXOptions = jQuery.extend({
			error:showResponseError,
			success:showResponseError,
			dataType: 'html'
		},$rewrite_options.loadAJAXOptions);
		if ($rewrite_options.loadAJAXOptions.error!=undefined){
			$options.loadAJAXOptions.error_for_options=$rewrite_options.loadAJAXOptions.error;
			//Обертка для передачи в обратную функцию метода опции
			$options.loadAJAXOptions.error=function(responseText, statusText){
				try{
					$options.loadAJAXOptions.error_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
			};
		}
		if ($rewrite_options.loadAJAXOptions.success!=undefined){
			$options.loadAJAXOptions.success_for_options=$rewrite_options.loadAJAXOptions.success;
			//Обертка для передачи в обратную функцию метода опции
			$options.loadAJAXOptions.success=function(responseText, statusText){
				try{
					$options.loadAJAXOptions.success_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
			};
		}
	}

	// en:Flag for load AJAX
	/* ru:Текущий статус загрузки AJAX*/
	$options.$loaded_href_hash_fix='';
	$options.$loaded_href='';
	$options.$ajax_is_load=false;
	
	//jQuery.setEvnineDebug.getTraceObject($rewrite_options.loadAJAXOptions,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2),$options.debugPrefixString);
	if ($options.setJSFuncForLoadPage==undefined){
		$options.flagJSFunc=false;
	}else {
		$options.flagJSFunc=true;
	}
	if (!window.console||jQuery.setEvnineDebug==undefined){
		$options.debugToConsole=false;
	}else {
		jQuery.setEvnineDebug.initNotSupport($options.debugToConsoleNotSupport);
	}


	/**
		*ru:Функция получение числа
	*/
	function getInt($int){
		return parseInt(parseFloat($int), '10');
	}

	function trim(str){
		return str.replace(/^ +/g,'').replace(/ +$/g,'');
	}
	
	$options.scriptNameForAJAX= $options.scriptForAJAXCallAndSetAnchore.replace(/^.*(\\|\/|\:)/, '');
	
	/**
	 * ru:Проверяем является ли версия браузера минимальной для работы
	 * 
	 * @access public
	 * @return void
	 */
	function isHasCrossBrowserMinimumVersion() {
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion()");
		var userAgent = navigator.userAgent.toLowerCase();
		jQuery.browser = {
			'version': (userAgent.match( /.+(?:rv|it|ra|ie|me)[\/: ]([\d.]+)/ ) || [])[1],
			'chrome': (/chrome/).test( userAgent ),
			'safari': (/webkit/).test( userAgent ) && !(/chrome/).test( userAgent ),
			'opera': (/opera/).test( userAgent ),
			'msie': (/msie/).test( userAgent ) && !(/opera/).test( userAgent ),
			'mozilla': (/mozilla/).test( userAgent ) && !(/(compatible|webkit)/).test( userAgent )
		};
		var $return=true;
		jQuery.each($options.crossBrowserMinimumVersion, function($browser,$version){
			if (jQuery.browser[$browser]){
				if (getInt(jQuery.browser.version)>=$version){

					if ($options.debugToConsole) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if ($options.debugToConsole) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion return true");
					$return=true;
					return '';
				}else {
					if ($options.debugToConsole) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if ($options.debugToConsole) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+"isHasCrossBrowserMinimumVersion return false");
					$return=false;
					return '';
				}
			}
		});
		return $return;
	}
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();

	if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+$EVNINE_NAME+"$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

	/**
	* en:For get safe AJAX URN.
	* ru:Для получения безопасного выражения.
	* 
	* @param mixed $str 
	* @access public
	* @return void
	*/
	function getRegSafeString ($str) {
		return $str.replace(/(["'\.\-])(?:(?=(\\?))\2.)*?\1/g,"");
	}
	
	/**
	* ru:Обработать клик по ссылке
	* 
	* @param mixed $that 
	* @access public
	* @return void
	*/
	function getClickHref($that){
		if (jQuery($that).attr('href')!=undefined){
			$href = jQuery($that).attr('href');
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,3)+$EVNINE_NAME+"getClickHref $href: "+$href);
			$reg = new RegExp($options.isHREFMatchThisRegExpSetNoUseAJAX,"g");
			if ($href.match($reg)){
				if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is no ajax href");
				if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
				return true;
			}else {
				getAJAXHref($href);
				if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax href");
			}
		}else if (jQuery($that).attr('type')==='submit'){
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax submit");
				getAJAXSubmit($that);
		}
		return false;
	}
	
	
	/**
	* ru:Добавить параметры к адресной части
	*/
	function getURLWithFlag($href,$post_fix,$post_sef) {//Установить флаг в урл что запрос делаем аяском в .htaccess отловим потом
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() ");
		if ($href===''){
			return '?'+$post_fix;
		}
		$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
		if ($href.match($reg)){//IF SEF URN
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match SEF ");
			return $href.replace($reg, $post_sef);
		}else if($href.match(/\?/)) {//IF not SEF URN and has param
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match & ");
			return $href+'&'+$post_fix;
		}else {//If not SEF URN and not has param
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match ? ");
			return $href+'?'+$post_fix;
		}
	}
	
	/**
	* ru:Является ли адрес базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
	* /index.php#!?test=test 
	* return true
	*
	* ru:Для случая, ответ false
	* /index.php?param=param#!?test=test 
	* 
	* @access public
	* @return void
	*/
	function isURLBaseAJAX() {
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX()");
		if (location.search!==''){
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX retrun FALSE");
			return false;
		}
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX->=$options.scriptForAJAXCallAndSetAnchore="+$options.scriptForAJAXCallAndSetAnchore);
		//if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject(location,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8));
		if (location.pathname===$options.scriptForAJAXCallAndSetAnchore){
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX retrun TRUE");
			return true;
		}else {
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX retrun FALSE");
			return false;
		}
	}
	
	/**
		* ru:Сбрасываем в случае когда в адресной части есть параметры
		* /HelloAJAXJQuery/index.php?param=param#!?test=test
		* =
		* /HelloAJAXJQuery/index.php#!?test=test
		*  
	*/
	function setURLToBrowser($base,$href) {//
		/*Сбрасываем */
		$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore+'|\\?');
		$href=$href.replace($reg,'');
		if ($options.isAllowThisBrowser){
			window.location = $base+'#'+$options.ancorePreFix+$href;
		}else {
			window.location = $base+$href;
		}
	}
	
	//
	/*
	* en:Set URL as anchore
	* ru:Установить адрес в историю просмотра
	* 
	* @param mixed $href 
	* @param mixed $reset 
	* @access public
	* @return void
	*/
	function setURLToHashAndLocation($href,$reset) {
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,7)+$EVNINE_NAME+"setURLToHashAndLocation($href="+$href+")");
		if ($href==='/') {
			$options.$loaded_href_hash_fix= '';
			window.location.hash = $.URLDecode($options.ancorePreFix);
			if ($options.debugToConsole) document.title = '';
			return $options.scriptForAJAXCallAndSetAnchore;
		}else {
			$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore);
			$options.$loaded_href_hash_fix=$hash_href_without_script_name=$href.replace($reg,'');
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,8)+$EVNINE_NAME+"setURLToHashAndLocation->hash_href_without_script_name="+$hash_href_without_script_name);
			window.location.hash = $.URLDecode($options.ancorePreFix+$hash_href_without_script_name);
			if ($options.debugToConsole) document.title = $href;
			return $href;
		}
	}
	
	/**
	* ru:Выполнить POST запрос
	* 
	* @param string $href 
	* @access public
	* @return void
	*/
	function getAJAXSubmit($that) {
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit");
		$method = jQuery($that).attr('name');
		$form = jQuery($that).parents('form');
		$length = $method.length;
			//Extract method_name name="submit[method_name]"
		if ($length>6){
			$method = $method.substring(7, $length-1);
			$options.loadAJAXOptions.data= {'submit':$method};
				//$href =setMethodTOURL($form.attr('action'),$method);
			$href =$form.attr('action');
		}else {
			$href =$form.attr('action');
			$options.loadAJAXOptions.data='';
		}
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit->$options.loadAJAXOptions.data: "+$options.loadAJAXOptions.data);
		//If not exist add id to form
		/*Если не указан айди для формы, генерируем свой*/
		$id = $form.attr('id');
		if ($id!=undefined){
			$id = 'tmp_'+Math.floor(Math.random()*100);
			$form.attr('id',$id);
		}
		//loadAJAXOptions
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit->$id="+$id);
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit->$href="+$href);
		getAJAX($href,'submit',$id,0);
	}
	
	/**
	* ru:Выполнить GET запрос
	* 
	* @param string $href 
	* @access public
	* @return void
	*/
	function getAJAXHref($href) {
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXHref");
		getAJAX($href,'href','',0);
	}
	
	/**
	* ru:Случай когда ошибка в аякс запросе
	*/
	function showResponseError(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError: ");
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError->responseText="+responseText);
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError->statusText="+statusText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
			$options.functionsForAJAXIndicator.Off($options);
		}
		//return true;
	}
	
	/**
	* ru:Случай когда запрос успешно выполнен
	*/
	function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse");
		jQuery($options.selectorForAJAXReplace).html(responseText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
			$options.functionsForAJAXIndicator.Off($options);
		}
	}
	
	/**
	* ru:Функция - отправка через jQuery plugin form в include.js 
	*/
	function getAJAX ($href,$type,$id,$error_count){
		if ($error_count==undefined){
			$error_count=0;
		}
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAX()");
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"getAJAX->$options.$ajax_is_load="+$options.$ajax_is_load);
		//$id='body';
		if (!$options.$ajax_is_load){
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"getAJAX->$options.loadAJAXOptions: ");
			if ($options.debugToConsole) jQuery.setEvnineDebug.getTraceObject($options.loadAJAXOptions,jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6));
			try{
				if (isURLBaseAJAX()){
					$options.$ajax_is_load = true;
					$options.$loaded_href= $href;
					$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
					if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"getAJAX->$options.loadAJAXOptions.url="+$options.loadAJAXOptions.url);
					if (typeof $options.functionsForAJAXIndicator.On==='function'){
						$options.functionsForAJAXIndicator.On($options);
					}
					if ($type==='submit'){
						$('#'+$id).ajaxSubmit($options.loadAJAXOptions);
					}else {
						$.ajax($options.loadAJAXOptions);
					}
				}else {
					//case /HelloAJAXJQuery/index.php>>?param=param<<#!?test=test
					/*Случай когда в ссылке есть параметры и пытаемся использовать якорь*/
					setURLToBrowser($options.scriptForAJAXCallAndSetAnchore,$href);
				}
			}catch($bug){
				$options.$ajax_is_load = false;
				if ($options.debugToConsole)        console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,6)+$EVNINE_NAME+"getAJAX->$bug="+$bug);
				$options.$loaded_href= '';
				$error_count++;
				if ($error_count<=$options.maxErrorCountBeforeStopAJAXCall){
					getAJAX($href,$type,$id,$error_count);
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
			}
		}
	}
	
		
		/**
		* ru:Получаем кросс-браузерно якорную часть
		* 
		* @param loc $loc 
		* @access public
		* @return string
		*/
		function getHash(loc){
			loc = loc.toString();
			if (loc.indexOf("#") != -1){
				return loc.substring(loc.indexOf('#'));
			} else {
				return "";
			}
		}
		
	/**
		* en:if first load href with AJAX anchor #!axax=ajax
		* ru:Установить новую страницу если в ссылке есть аякс вызов ссылка#вызов
		* ru:При первой инициализации проверяем есть ли якорная часть
		* ru:для ссылок вида
		* ru:/HelloAJAXJQuery/index.php#!?test=test
		*  
	*/
	function setAnchoreClearWithoutURL (){
		if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL()");
		$href=getHash(location);
		$reg=new RegExp('^#'+$options.ancorePreFix,"g");
		$href= $href.replace($reg,"");
		if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL->$href_after_replace="+$href);
		if ($href){
			if ($options.flagJSFunc){
				$options.setJSFuncForLoadPage.$reload_page=true;
			}
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL->$href: "+$href);
			if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL->isURLBaseAJAX return true");
			$($options.selectorForAJAXReplace).html();
			getAJAX($options.scriptForAJAXCallAndSetAnchore+$href,'href');
		}
	}

	// en:if first load href with AJAX anchor #!axax=ajax
	/* ru:При первой инициализации проверяем есть ли якорная часть*/
	setAnchoreClearWithoutURL();
	/* Если установлена опция следовать за историей*/ 
	if ($options.folowByChangeOfHistory&&$options.isAllowThisBrowser){
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
				if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange()");
				if (window.location.hash){ 
					$reg=new RegExp('^#'+$options.ancorePreFix,"g");
					$hash = location.hash.replace($reg,"");
					if ($options.$loaded_href_hash_fix!==$hash){
						if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+$EVNINE_NAME+"hashchange=YES");
						if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+"|"+$hash + "|!=|"+$options.$loaded_href_hash_fix+"|");
						getAJAX($hash,'href');
					} else {
					if ($options.debugToConsole) console.info(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,1)+$EVNINE_NAME+"hashchange=NO");
					}
				}
		});
	}
	
	if ($options.debugToConsoleNotSupport){
		if ($options.debugToConsole) console.warn("END");
	}

	if ($options.isAllowThisBrowser){
		return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			return getClickHref(this);
		});
	}
};
}(document, jQuery);
//</script>
