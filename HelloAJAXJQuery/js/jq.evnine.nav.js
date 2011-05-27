//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evNav
	* <br />en: jQuery Plugin - AJAX websites based on anchor navigation
	* <br />ru: Плагин навигации по ссылкам с установкой якоря после аякс загрузки страницы.
	* <br />
	* <br />en: Copyright 2011, (c) ev9eniy.info
	* <br />en: Dual licensed under the MIT or GPL Version 2 licenses
	* <br />
	* <br />ru: Двойная лицензия MIT или GPL v.2 
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
	*
	* @config {boolean} [scrollToTopAfterAJAXCall=true]
	* en: Scroll to the top of page after ajax is complete<br />
	* ru: Прокручивать вверх после аякс загрузки страницы
	* 
	* @config {selector} [liveSelectorForAJAX=a, input:submit]
	* en: jQuery selector for bind<br />
	* ru: jQuery селектор для привязки
	*
	* @config {bind} [liveBindType='click']
	* en: Type of bind <br />
	* ru: Тип события по которому загружаем страницу
	*
	* @config {string} [crossBrowserMinimumVersion.msie='8']
	* en: Minimum version for compatibility<br />
	* ru: Минимальная версия для совместимости 
	* 
	* @config {boolean} [folowByChangeOfHistory=true]
	* en: Check if the used the back button, or a link from the history<br />
	* ru: Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории 
	*
	* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
	* en: How many false attempts AJAX load<br />
	* ru: Через сколько попыток остановить вызов AJAX 
	*
	* @config {selector} [selectorForAJAXReplace=body]
	* en: jQuery selector for replace content after ajax is complete<br />
	* ru: Селектор для заметы, в данном случае заменяем все тело страницы
	* 
	* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
	* en: The base path for ajax call<br />
	* ru: URL относительно которого производить ajax вызовы
	*
	* @config {string} [ancorePreFix=!]
	* en: Prefix in the anchor<br />
	* ru: Префикс в якоре<br />
	* index.php#!test
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
	* en: If match this RegExp, not user ajax load  <br />
	* ru: Ссылка совпадает с этим регулярным то не использовать аякс<br />
	* $href.match(/^http:\/\//g)
	*
	* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
	* en: If ajax, add param to the call url  <br />
	* ru: Если аякс режим добавить параметр в ссылку вызова скрипта<br />
	* index.php?ajax=ajax
	*
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
	* en: If match RegExp, use SEF mode<br />
	* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
	* en: If ajax in SEF mode, add this param to the url<br />
	* ru: Если аякс работает в ЧПУ режиме, заменим адрес на $href.replace(/\.html$/g, '.ajax')<br />
	* index.html => index.ajax
	* 
	* @config {object} [functionsForAJAXIndicator=undefined]
	* en: Function for the AJAX indicator off and on<br />
	* ru: Функции для отображения индикации аякс погрузки 
	* 
	* @config {function} functionsForAJAXIndicator.On($options)
	* @config {function} functionsForAJAXIndicator.Off($options)
	*
	* @config {object} [loadAJAXOptions]
	* en: Options for the AJAX load<br />
	* ru: Опции для AJAX загрузки<br />
	*
	* @config {string} [loadAJAXOptions.dataType=html]
	* en: Page type<br />
	* ru: Тип для загрузки страницы<br />
	*
	* @config {function} [loadAJAXOptions.success(responseText,statusText,$options)=jQuery.evNav.showResponse(responseText, statusText, $options)]
	* en: Success function wrapper<br />
	* ru: Успешное получение данных, вызываются через функцию - обертку с передачей опций<br />
	*
	* @config {function} [loadAJAXOptions.error(responseText,statusText,$options)=jQuery.evNav.showResponseError(responseText, statusText, $options)]
	* en: Error function wrapper<br />
	* ru: При ошибке, вызываются через функцию - обертку с передачей опций<br />
	*/
new function (document, $, undefined) {
	jQuery.evNav = function($rewrite_options){
	// The current version of Evnine being used
	$EVNINE_VER="0.3";
	$EVNINE_NAME='evNav'+'.';
/**
	*  en:Default setting<br />
	*  ru:настройки по умолчанию
	*  @config {boolean} debugToConsole                   default: false<br />
	* en: Debug to console<br />
	* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} debugPrefixString                 default: '| '<br />
	* en: Debug prefix for group of functions<br />
	* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
 */
	var $options = jQuery.extend({
	//en: <br />
	/*ru: Выводить отладочную информацию в консоль FireFox FireBug, Chrome, Opera итд*/
	//debugToConsole                  :true,
	debugToConsole                    :false,
	//en: <br />
	/*ru: Префикс для вывода в окно отладки FireFox FireBug, Chrome, Opera*/
	debugPrefixString                 :'|	',
	//debugPrefixString               :' ',
	//en: <br />
	/*ru: Если нужна отладка в консоль в IE 6-7, Safari итд */
	debugToConsoleNotSupport          :false,
	//debugToConsoleNotSupport        :true,
	//en: <br />
	/*ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций */
	//debugFunctionGroup              :true,
	debugFunctionGroup                :false,
	//en:<br />
	/*ru: Прокручивать вверх до загрузки страницы*/
	scrollToTopAfterAJAXCall          :true,
	//en: <br />
	/*ru: Селектор для привязки, в данном случае не учитываем с классом  json, body*/
	liveSelectorForAJAX               :'a, input:submit',
	//en: <br />
	/*ru: Тип события по которому загружаем страницу*/
	liveBindType                      :'click',
	//liveBindType                    :'mouseover',
	//en: <br />
	/*ru: Минимальная версия для совместимости */
	crossBrowserMinimumVersion        :{
		//en: <br />
		/*ru: Минимальная версия для IE */	
		msie   :'8'
	},
	//en: <br />
	/*ru: Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории */
	folowByChangeOfHistory            :true,
	//en: <br />
	/*ru: Через сколько попыток остановить вызов AJAX */
	maxErrorCountBeforeStopAJAXCall   :3,
	//en: <br />
	/*ru: Селектор для заметы, в данном случае заменяем все тело страницы*/
	selectorForAJAXReplace            :'body', 
	//en: <br />
	/*ru: Адрес относительно которого производить ajax вызовы*/
	scriptForAJAXCallAndSetAnchore    :'/index.php',
	//en: <br />
	/*ru: Префикс для указания в якоре */
	ancorePreFix                      :'!',//index.php#!test
	//en: <br />
	/*ru: Ссылка совпадает с этим регулярным то не использовать аякс*/
	isHREFMatchThisRegExpSetNoUseAJAX :'^http://',//=$href.match(/^http:\/\//g)
	//en: <br />
	/*ru: Если аякс режим добавить параметр в ссылку вызова скрипта*/
	ifAJAXAddThisParamToScript        :'ajax=ajax',//=index.php?ajax=ajax
	//en: <br />
	/*ru: Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
	isHREFMatchThisRegExpSetSEFMode   :'.html$',//=$href.match(/\.html/g)
	//en: <br />
	/*ru: Если аякс работает в ЧПУ режиме, заменим адрес на */
	//Example: index.html => index.ajax
	ifSEFAJAXReplaceHREFMatchTo       :'.ajax',//=$href.replace(/\.html$/g, '.ajax')
	//en: Function for the AJAX indicator off and on
	/*ru: Функции для отображения индикации аякс погрузки*/ 
	functionsForAJAXIndicator         :undefined,
	setJSFuncForLoadPage              :undefined
	},$rewrite_options);
	if ($rewrite_options!=undefined){
		$options.loadAJAXOptions = jQuery.extend({
			success: showResponse,error: showResponseError,dataType: 'html'
		},$rewrite_options.loadAJAXOptions);
	/**
		*  en:
		*  ru: Обертка для передачи в обратную функцию метода опции 
		*/
		if ($rewrite_options.loadAJAXOptions.error!=undefined){
			$options.loadAJAXOptions.error_for_options=$rewrite_options.loadAJAXOptions.error;
			$options.loadAJAXOptions.error=function(responseText, statusText){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() BEGIN");
				try{
					$options.loadAJAXOptions.error_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() END");
			};
		}
		//en:
		/*ru: Обертка для передачи в обратную функцию метода опции */
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

	// en:Flag for load AJAX
	/* ru:Текущий статус загрузки AJAX*/
	$options.$loaded_href_hash_fix='';
	$options.$loaded_href='';
	$options.$ajax_is_load=false;
	if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call BEGIN');
	if ($options.debugToConsole){
		if ($options.setJSFuncForLoadPage==undefined){
			$options.flagJSFunc=false;
		}else {
			$options.flagJSFunc=true;
		}
		if (!window.console||jQuery.evDev==undefined){
			$options.debugToConsole=false;
		}else {
			jQuery.evDev.initNotSupport();
		}
		if ($options.debugFunctionGroup){
			$options.debugPrefixString= '';
			jQuery.evDev.initGoupFunctionCall($options.debugToConsoleNotSupport);
		}
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
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();

	if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

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
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getClickHref $href: "+$href);
			$reg = new RegExp($options.isHREFMatchThisRegExpSetNoUseAJAX,"g");
			if ($href.match($reg)){
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is no ajax href");
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
				return true;
			}else {
				getAJAXHref($href);
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax href");
			}
		}else if (jQuery($that).attr('type')==='submit'){
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax submit");
				getAJAXSubmit($that);
		}
		return false;
	}
	
	
	/**
	* ru:Добавить параметры к адресной части
	*/
	function getURLWithFlag($href,$post_fix,$post_sef) {//Установить флаг в урл что запрос делаем аяском в .htaccess отловим потом
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() BEGIN");
		if ($href===''){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return '?'+$post_fix;
		}
		$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
		if ($href.match($reg)){//IF SEF URN
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match SEF ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href.replace($reg, $post_sef);
		}else if($href.match(/\?/)) {//IF not SEF URN and has param
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match & ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'&'+$post_fix;
		}else {//If not SEF URN and not has param
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match ? ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'?'+$post_fix;
		}
	}
	
	/**
	* ru:Является ли адрес базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
	* @example 
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
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() BEGIN");
		if (location.search!==''){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return false");
			return false;
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"isURLBaseAJAX.$options.scriptForAJAXCallAndSetAnchore="+$options.scriptForAJAXCallAndSetAnchore);
		//if ($options.debugToConsole) jQuery.evDev.getTraceObject(location,jQuery.evDev.getTab($options.debugPrefixString,8));
		if (location.pathname===$options.scriptForAJAXCallAndSetAnchore){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return true");
			return true;
		}else {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"isURLBaseAJAX() END return false");
			return false;
		}
	}
	
	/**
		* en:
		* ru:Сбрасываем в случае когда в адресной части есть параметры
		* example 
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
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"setURLToHashAndLocation($href="+$href+")");
		if ($href==='/') {
			$options.$loaded_href_hash_fix= '';
			window.location.hash = $.URLDecode($options.ancorePreFix);
			if ($options.debugToConsole) document.title = '';
			return $options.scriptForAJAXCallAndSetAnchore;
		}else {
			$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore);
			$options.$loaded_href_hash_fix=$hash_href_without_script_name=$href.replace($reg,'');
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"setURLToHashAndLocation.hash_href_without_script_name="+$hash_href_without_script_name);
			window.location.hash = $.URLDecode($options.ancorePreFix+$hash_href_without_script_name);
			if ($options.debugToConsole) document.title = $href;
			return $href;
		}
	}

		/**
			* en: 
			* ru:Для случая когда в кнопке указан метод, устанавливаем этот метод в ссылку
			* 
			* @access public
			* @param $href - string
			* @param $method - string
			* @return $href string
			* 
		 */
		function setMethodTOURL ($href,$method) {
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
		}

	/**
	* ru: Выполнить POST запрос
	* 
	* @param string $href 
	* @access public
	* @return void
	*/
	function getAJAXSubmit($that) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit");
		$method = jQuery($that).attr('name');
		$form = jQuery($that).parents('form');
		$length = $method.length;
			//Extract method_name name="submit[method_name]"
		if ($length>6){
			$method = $method.substring(7, $length-1);
			$options.loadAJAXOptions.data= {'submit':$method};
			$href =setMethodTOURL($form.attr('action'),$method);
			//$href =$form.attr('action');
		}else {
			$href =setMethodTOURL($form.attr('action'),$method);
			//$href =$form.attr('action');
			$options.loadAJAXOptions.data='';
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit.$options.loadAJAXOptions.data: "+$options.loadAJAXOptions.data);
		//eN: If not exist form id add rand id 
		/*ru: Если не указан айди для формы, генерируем свой*/
		$id = $form.attr('id');
		if ($id!=undefined){
			$id = 'tmp_'+Math.floor(Math.random()*100);
			$form.attr('id',$id);
		}
		//loadAJAXOptions
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$id="+$id);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$href="+$href);
		getAJAX($href,'submit',$id,0);
	}
	
	/**
	* en:
	* ru:Выполнить GET запрос
	* 
	* @param string $href 
	* @access private
	* @return void
	*/
	function getAJAXHref($href) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXHref () BEGIN");
		getAJAX($href,'href','',0);
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXHref () END");
	}
	
	/**
	* en:
	* ru:Случай по умолчанию, когда ошибка в аякс запросе
	* @access private
	* @return void
	*/
	function showResponseError(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError.responseText="+responseText);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,6)+$EVNINE_NAME+"showResponseError.statusText="+statusText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() END");
	}
	
	/**
	* en:
	* ru:Случай по умолчанию, когда запрос успешно выполнен
	* @access private
	* @param responseText, statusText
	* @return void
	*/
	function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() BEGIN");
		jQuery($options.selectorForAJAXReplace).html(responseText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.On==='function'){
			$options.functionsForAJAXIndicator.On($options);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() END");
	}
	
	/**
	* en:
	* ru:Функция - отправка через jQuery plugin form в include.js 
	* 
	* @access private
	* @param $href,$type,$id,$error_count
	* @return void
	*/
	function getAJAX($href,$type,$id,$error_count){
		if ($error_count==undefined){
			$error_count=0;
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.$ajax_is_load="+$options.$ajax_is_load);
		//$id='body';
		if (!$options.$ajax_is_load){
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions: ");
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($options.loadAJAXOptions,jQuery.evDev.getTab($options.debugPrefixString,5));
			try{
				if (isURLBaseAJAX()){
					$options.$ajax_is_load = true;
					$options.$loaded_href= $href;
					$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions.url="+$options.loadAJAXOptions.url);
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
				if ($options.debugToConsole)        console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$bug="+$bug);
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
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() END");
	}
	
		
		/**
		* en:
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
		* en:if first load href with AJAX anchor #!axax=ajax<br />
		* ru:Установить новую страницу если в ссылке есть аякс вызов ссылка#вызов
		* @example 
		* ru:При первой инициализации проверяем есть ли якорная часть
		* ru:для ссылок вида
		* ru:/HelloAJAXJQuery/index.php#!?test=test
		*  
	*/
	function setAnchoreClearWithoutURL (){
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() BEGIN");
		$href=getHash(location);
		$reg=new RegExp('^#'+$options.ancorePreFix,"g");
		$href= $href.replace($reg,"");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.$href_after_replace="+$href);
		if ($href){
			if ($options.flagJSFunc){
				$options.setJSFuncForLoadPage.$reload_page=true;
			}
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.$href: "+$href);
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+"setAnchoreClearWithoutURL.isURLBaseAJAX return true");
			$($options.selectorForAJAXReplace).html();
			getAJAX($options.scriptForAJAXCallAndSetAnchore+$href,'href');
		}else {
			/* ru:Сохраним текущие данные по шаблону и методу, нужно для загрузки дополнительных скриптов*/
			if ($options.flagJSFunc&&!$options.setJSFuncForLoadPage.$reload_page){
				$options.setJSFuncForLoadPage.setMethodAndControllerFunc('init');
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() END");
	}

	// en:if first load href with AJAX anchor #!axax=ajax
	/* ru:При первой инициализации проверяем есть ли якорная часть*/
	setAnchoreClearWithoutURL();
	/* Если установлена опция следовать за историей*/ 
	if ($options.folowByChangeOfHistory&&$options.isAllowThisBrowser){
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange() BEGIN");
			if (window.location.hash){ 
				$reg=new RegExp('^#'+$options.ancorePreFix,"g");
				$hash = location.hash.replace($reg,"");
				if ($options.$loaded_href_hash_fix!==$hash){
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
		if ($options.debugToConsole) console.warn("END");
	}
	if ($options.debugToConsole) {
		console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call END');
	}
	if ($options.isAllowThisBrowser){
		/** @constructs */
		return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			return getClickHref(this);
		});
	}
};
}(document, jQuery);
//</script>
