//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evNav
	* <br />en: jQuery Plugin - AJAX websites based on anchor navigation
	* <br />ru: Плагин навигации по ссылкам с установкой якоря после аякс загрузки страницы
	* <br />
	* <br />en: Dual licensed under the MIT or GPL Version 2 licenses
	* <br />ru: Двойная лицензия MIT или GPL v.2 
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {boolean} [debugToConsole=false]
	* en: Debug to console
	* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} [debugPrefixString='| ']
	* en: Debug prefix for group of functions
	* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info 
	* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
	*
	* @config {boolean} [debugFunctionGroup=false]
	* en: Use console.group as alternative to $options.debugPrefixString
	* ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций 
	*
	* @config {boolean} [scrollToTopAfterAJAXCall=true]
	* en: Scroll to the top of page after ajax is complete
	* ru: Прокручивать вверх после аякс загрузки страницы
	* 
	* @config {selector} [liveSelectorForAJAX=a, input:submit]
	* en: jQuery selector for bind
	* ru: jQuery селектор для привязки
	*
	* @config {bind} [liveBindType='click']
	* en: Type of bind
	* ru: Тип события по которому загружаем страницу
	*
	* @config {string} [crossBrowserMinimumVersion.msie='8']
	* en: Minimum version for compatibility
	* ru: Минимальная версия для совместимости 
	* 
	* @config {boolean} [folowByChangeOfHistory=true]
	* en: Check if the used the back button, or a link from the history
	* ru: Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории 
	*
	* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
	* en: How many false attempts AJAX load
	* ru: Через сколько попыток остановить вызов AJAX 
	*
	* @config {selector} [selectorForAJAXReplace=body]
	* en: jQuery selector for replace content after ajax is complete
	* ru: Селектор для заметы, в данном случае заменяем все тело страницы
	* 
	* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
	* en: The base path for ajax call
	* ru: URL относительно которого производить ajax вызовы
	*
	* @config {string} [ancorePreFix=!]
	* en: Prefix in the anchor
	* ru: Префикс в якоре
	* <br />index.php#!test
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
	* en: If match this RegExp, not user ajax load  
	* ru: Ссылка совпадает с этим регулярным то не использовать аякс
	* <br />$href.match(/^http:\/\//g)
	*
	* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
	* en: If ajax, add param to the call url
	* ru: Если аякс режим добавить параметр в ссылку вызова скрипта
	* <br />index.php?ajax=ajax
	*
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
	* en: If match RegExp, use SEF mode
	* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
	* en: If ajax in SEF mode, add this param to the url
	* ru: Если аякс работает в ЧПУ режиме, заменим адрес на $href.replace(/\.html$/g, '.ajax')
	* <br />index.html => index.ajax
	* 
	* @config {object} [functionsForAJAXIndicator=undefined]
	* en: Function for the AJAX indicator off and on
	* ru: Функции для отображения индикации аякс погрузки 
	* 
	* @config {function} functionsForAJAXIndicator.On($options)
	* @config {function} functionsForAJAXIndicator.Off($options)
	*
	* @config {object} [loadAJAXOptions]
	* en: Options for the AJAX load
	* ru: Опции для AJAX загрузки
	*
	* @config {string} [loadAJAXOptions.dataType=html]
	* en: Page type
	* ru: Тип для загрузки страницы
	*
	* @config {function} [loadAJAXOptions.success(responseText,statusText,$options)=jQuery.evNav.showResponse(responseText, statusText, $options)]
	* en: Success function wrapper
	* ru: Успешное получение данных, вызываются через функцию - обертку с передачей опций
	*
	* @config {function} [loadAJAXOptions.error(responseText,statusText,$options)=jQuery.evNav.showResponseError(responseText, statusText, $options)]
	* en: Error function wrapper
	* ru: При ошибке, вызываются через функцию - обертку с передачей опций
	*/
new function (document, $, undefined) {
	jQuery.evNav = function($rewrite_options){
	// The current version of Evnine being used
	$EVNINE_VER="0.3";
	$EVNINE_NAME='evNav'+'.';
	/**
		* en: Default setting
		* ru: Настройки по умолчанию
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
		* en: Debug to console
		* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
		*/
	debugPrefixString                 :'|	',
	//debugPrefixString               :' ',
	/**
		* @config {string} [debugPrefixString='| ']
		* en: Debug prefix for group of functions
		* ru: Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
		*/
	debugToConsoleNotSupport          :false,
	//debugToConsoleNotSupport        :true,
	/**
		* @config {boolean} [debugToConsoleNotSupport=false]
		* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info
		* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
		*/
	debugFunctionGroup                :false,
	//debugFunctionGroup              :true,
	/**
		* @config {boolean} [debugFunctionGroup=false]
		* en: Use console.group as alternative to $options.debugPrefixString
		* ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций 
		*/
	scrollToTopAfterAJAXCall          :true,
	/**
		* @config {boolean} [scrollToTopAfterAJAXCall=true]
		* en: Scroll to the top of page after ajax is complete
		* ru: Прокручивать вверх после аякс загрузки страницы
		*/
	liveSelectorForAJAX               :'a, input:submit',
	/**
		* @config {selector} [liveSelectorForAJAX=a, input:submit]
		* en: jQuery selector for bind
		* ru: jQuery селектор для привязки
		*/
	liveBindType                      :'click',
	//liveBindType                    :'mouseover',
	/**
		* @config {bind} [liveBindType='click']
		* en: Type of bind
		* ru: Тип события по которому загружаем страницу
		*/
	crossBrowserMinimumVersion        :{msie   :'8'},
	/**
		* @config {string} [crossBrowserMinimumVersion.msie='8']
		* en: Minimum version for compatibility
		* ru: Минимальная версия для совместимости 
		*
		*/
	folowByChangeOfHistory            :true,
	/**
		* @config {boolean} [folowByChangeOfHistory=true]
		* en: Check if the used the back button, or a link from the history
		* ru: Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории 
		*/
	maxErrorCountBeforeStopAJAXCall   :3,
	/**
		* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
		* en: How many false attempts AJAX load
		* ru: Через сколько попыток остановить вызов AJAX 
		*/
	selectorForAJAXReplace            :'body', 
	/**
		* @config {selector} [selectorForAJAXReplace=body]
		* en: jQuery selector for replace content after ajax is complete
		* ru: Селектор для заметы, в данном случае заменяем все тело страницы
		*/
	scriptForAJAXCallAndSetAnchore    :'/index.php',
	/**
		* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
		* en: The base path for ajax call
		* ru: URL относительно которого производить ajax вызовы
		*/
	ancorePreFix                      :'!',
	/**
		* @config {string} [ancorePreFix=!]
		* en: Prefix in the anchor
		* ru: Префикс в якоре
		* <br />index.php#!test
		*/
	isHREFMatchThisRegExpSetNoUseAJAX :'^http://',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
		* en: If match this RegExp, not user ajax load  
		* ru: Ссылка совпадает с этим регулярным то не использовать аякс
		* <br />$href.match(/^http:\/\//g)
		*/
	ifAJAXAddThisParamToScript        :'ajax=ajax',
	/**
		* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
		* en: If ajax, add param to the call url
		* ru: Если аякс режим добавить параметр в ссылку вызова скрипта
		* <br />index.php?ajax=ajax
		*/
	isHREFMatchThisRegExpSetSEFMode   :'.html$',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
		* en: If match RegExp, use SEF mode
		* ru: Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
		*/
	ifSEFAJAXReplaceHREFMatchTo       :'.ajax',
	/**
		* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
		* en: If ajax in SEF mode, add this param to the url
		* ru: Если аякс работает в ЧПУ режиме, заменим адрес на $href.replace(/\.html$/g, '.ajax')
		* <br />index.html => index.ajax
		*/
	functionsForAJAXIndicator         :undefined,
	/**
		* @config {object} [functionsForAJAXIndicator=undefined]
		* @config {function} functionsForAJAXIndicator.On($options)
		* @config {function} functionsForAJAXIndicator.Off($options)
		* en: Function for the AJAX indicator off and on
		* ru: Функции для отображения индикации аякс погрузки 
		*/
	setJSFuncForLoadPage              :undefined
	},$rewrite_options);
	if ($rewrite_options!=undefined){
		$options.loadAJAXOptions = jQuery.extend({
			success: showResponse,error: showResponseError,dataType: 'html'
		},$rewrite_options.loadAJAXOptions);
	/**
		* en: Wrapper callback function after the AJAX call if an error occurred<br />
		* en: Performed when initializing a given function.
		* ru: Обертка callback функции после AJAX вызова если произошла ошибка<br />
		* ru: Выполняется в случае, когда при инициализации задана функция.
		*/
		if ($rewrite_options.loadAJAXOptions.error!=undefined){
			$options.loadAJAXOptions.error_for_options=$rewrite_options.loadAJAXOptions.error;
			$options.loadAJAXOptions.error=function(responseText, statusText){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() BEGIN");
				try{
					/**
						* en: Passing the function is created with $options
						* en: For example, what would execute methods of setJSFuncForLoadPage:$.evFunc({})<br />
						* ru: Передаём в функцию созданную при инициализации текущие опции<br />
						* ru: Для примера что бы выполнить методы из setJSFuncForLoadPage:$.evFunc({})
						*/
					$options.loadAJAXOptions.error_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				/**
					* en: Turn off the AJAX Indicator
					* ru: Выключаем индикатор загрузки
					*/
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() END");
			};
		}
		/**
			* en: Wrapper callback function after the AJAX call if an success occurred
			* en: Performed when initializing a given function.<br />
			* ru: Обертка callback функции после AJAX вызова если успешный вызов<br />
			* ru: Выполняется в случае, когда при инициализации задана функция.
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
		* en: Flag that would only load once Page
		* ru: Флаг, что бы загружать только один раз страницу
		*  
		* @var string
		* @access public
		*/
	$options.$loaded_href_hash_fix='';
	/**
		* en: Currently loaded address will be used for callback function in evFunc()
		* ru: Текущий загруженный адрес используется для callback function в evFunc() 
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
		* en: The current state of AJAX loading
		* en: that is used to perform only one AJAX request<br />
		* ru: Текущее состояние AJAX загрузки,<br /> 
		* ru: используется что бы выполнять только один AJAX запрос
		* 
		* @var boolean
		* @access public
		*/
	$options.$ajax_is_load=false;
	if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call BEGIN');
	/**
		* en: Is init evFunc()?
		* ru: Используется ли evFunc()?
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
		* en: Is there any support for console for debugging in the browser?
		* ru: Есть ли поддержка консоли для отладки в браузере?
		*/
	if ($options.debugToConsole){
		if (!window.console||jQuery.evDev==undefined){
			$options.debugToConsole=false;
		}else {
			jQuery.evDev.initNotSupport();
		}
		/**
			* en: If you use a group by the console, remove the prefix for the indentation
			* ru: Если используется группировка средствами консоли, удаляем префикс для отступов
			*/
		if ($options.debugFunctionGroup){
			$options.debugPrefixString= '';
			jQuery.evDev.initGroupFunctionCall($options.debugToConsoleNotSupport);
		}
	}

	/** getInt($int)<br />
		* en: Function to obtain the number of
		* ru: Функция получение числа
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
		* en: Remove the spaces before and after.
		* ru: Убираем пробелы до и после.
		* 
		* @param {string} $str 
		* @access public
		* @return string
		*/
	function trim(str){
		return str.replace(/^ +/g,'').replace(/ +$/g,'');
	}

	/**
		* en: Obtain the name of the script used to install anchors.
		* ru: Получим имя скрипта, используется для установки якоря.
		* 
		* @var string
		* @access public
		*/
	$options.scriptNameForAJAX= $options.scriptForAJAXCallAndSetAnchore.replace(/^.*(\\|\/|\:)/, '');
	
	/** isHasCrossBrowserMinimumVersion()<br />
		* 
		* en: Check function.<br /> 
		* en: Is there a minimum version of the browser to work with to anchor navigation.
		* ru: Функция проверки. <br />
		* ru: Является ли версия браузера минимальной для работы к с якорной навигацией.
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
		 * en: Does user browser work with the evNav plugin?
		 * ru: Поддерживает ли браузер работу с evNav плагином?
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
		* en: Is there a minimum version of the browser to work with to anchor navigation<br />
		* ru: Является ли версия браузера минимальной для работы к с якорной навигацией
		* 
		* @var {boolean} [isAllowThisBrowser=true]
		* @access public
		*/
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();
	if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

	/** getRegSafeString($str)<br />
		* 
		* en:For get safe AJAX URN
		* ru:Для получения безопасного выражения
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
		* en: Run the AJAX query based on the type of element clicked
		* ru: Выполнить AJAX запрос исходя из типа кликнутого элемента
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
				* en: Case there is no need to use AJAX
				* ru: Случай когда не нужно использовать AJAX
				*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is no ajax href");
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
				return true;
			}else {
			/**
				* en: Use for processing AJAX GET request when user click on the link
				* ru: Используем для обработки AJAX GET запроса при клике на ссылку
				*/
				getAJAXHref($href);
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax href");
			}
		}else if (jQuery($that).attr('type')==='submit'){
		/**
			* en: If you clicked on the submit button via AJAX POST
			* ru: Если кликнули на отправку формы через AJAX POST
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax submit");
				getAJAXSubmit($that);
		}
		return false;
	}
	
	/** getURLWithFlag($href,$post_fix,$post_sef)<br />
		* 
		* en: Add param to URN
		* ru: Добавить параметры к адресной части
		* 
		* @param {string} $href
		* en: URN
		* ru: Адрес
		* 
		* @param {string} $post_fix
		* en: Parameters of the request.
		* ru: Параметры запроса.
		* 
		* @param {string} $post_sef
		* en: For SEF mode
		* ru: Для ЧПУ режима
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
			* en: IF SEF mode
			* ru: Если ЧПУ режим
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match SEF ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href.replace($reg, $post_sef);
		}else if($href.match(/\?/)) {
		/**
			* en: If you do not SEF is the settings in the address part
			* ru: Если не режим ЧПУ и есть параметры в адресной части
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match & ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'&'+$post_fix;
		}else {
		/**
			* en: If you do not SEF and no parameters in the address part
			* ru: Если не режим ЧПУ и нет параметров в адресной части
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match ? ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'?'+$post_fix;
		}
	}
	
	/** isURLBaseAJAX()<br />
		* 
		* en: Is the current URL specified in the basic $options.scriptForAJAXCallAndSetAnchore?
		* ru: Является ли текущий URL базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
		* 
		* @example 
		* /index.php#!?test=test 
		* return true
		*
		* /index.php?param=param#!?test=test 
		* en: return false - in the ?param=param
		* ru: return false - в адресе ?param=param
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
				* en: When the name matches the name of the options to initialize
				* ru: Когда имя соответствует имени из опций инициализации
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
		* en: This function set the anchor link if browser allows it<br />
		* en: If no base address is used, set the basic URL of the script and anchor as URN.
		* ru: Ставим якорную ссылку если браузер позволяет это сделать<br />
		* ru: В случае если не базовый адрес используется, ставим базовый скрипт и параметры адреса
		* 
		* @example 
		* /HelloAJAXJQuery/index.php?param=param#!?test=test
		* =
		* /HelloAJAXJQuery/index.php#!?test=test
		* 
		* @param {string} $base
		* en: The current URL
		* ru: Текущий URL
		*
		* @param {string} $href
		* en: Link to go to
		* ru: Ссылка для перехода
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
		* en: Set URN as anchor
		* ru: Установить адрес в историю просмотра
		* 
		* @param {string} $href 
		* en: Link to go to.
		* ru: Ссылка для перехода
		* 
		* @access public
		* @return {string} $href or $options.scriptForAJAXCallAndSetAnchore 
		* 
		*/
	function setURLToHashAndLocation($href) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"setURLToHashAndLocation($href="+$href+")");
		if ($href==='/') {
		/**
			* en: If you want to go home page, go to the base script page.
			* ru: Если хотим перейти на главную, переходим на базовую страницу
			*/
			$options.$loaded_href_hash_fix= '';
			window.location.hash = $.URLDecode($options.ancorePreFix);
			if ($options.debugToConsole) document.title = '';
			return $options.scriptForAJAXCallAndSetAnchore;
		}else {
		/**
			* en: Set the anchor link.
			* ru: Установим якорную ссылку
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
		* en: For the case in the button method is specified, set this method in the link.
		* ru: Для случая, когда в кнопке указан метод, устанавливаем этот метод в ссылку
		* 
		* @access public
		* 
		* @param {string} $href
		* ru: Ссылка для добавления метода
		* @param {string} $method
		* ru: Название метода
		* 
		* @return {string} $href 
		* en: Return URN with the added method.
		* ru: Возвращаем адрес с добавленным методом
		* 
		*/
		function setMethodTOURL($href,$method) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() BEGIN');
			if ($method.length===0){
			/**
				* en: The case when the method is not specified.
				* ru: Случай когда метод не указан
				*/
				return $href;
			}else {
				//TODO TEST!!!!! SEF add method
				//if ($href.match(/\.html$/)){
				$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
				if ($href.match($reg)){
				/**
					* en: Check the case when using the SEF.
					* ru: Проверяем случай когда передан ЧПУ.
					*/
					$href = $href.substring(1);
					$method_replace  = $href.match(/.*\//);
					$method_replace=$method_replace.toString().split(/\//);
					if ($method_replace[1].match(/=/)){
					/**
						* en: Case when using the SEF for controller.
						* ru: Случай когда используется ЧПУ для контроллера.
						* 
						*/
						$method_match==$method_replace[0]+'/';
						$method='/'+$method_replace[0]+'/'+$method;
					}else {
						/**
							* en: Case when using the SEF for method.
							* ru: Случай когда используется ЧПУ для метода.
							*/
						$method_match=$method_replace[1];
					}
					$href = $href.replace($method_match,$method);
				}else {
					/**
						* en: Case when handed a standard URN.
						* ru: Случай когда передан стандартный адрес, без ЧПУ
						*/
					$method_match = $href.match(/&m=.*/);
					$href = $href.replace($method_match,"&m="+$method);
				}
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() END');
				return $href;
			}
		}

	/** getAJAXSubmit($that)<br />
		* en: Perform POST request.
		* ru: Выполнить POST запрос
		* 
		* @param {jQuery object} $that
		* en: Object with the data on the form.
		* ru: Объект для получения данных о форме
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
				* en: Extract the method_name <input name="submit[method_name]" type="submit" />
				* ru: Получим имя метода из <input name="submit[method_name]" type="submit" />
				*/
			$method = $method.substring(7, $length-1);
			$options.loadAJAXOptions.data= {'submit':$method};
			$href =setMethodTOURL($form.attr('action'),$method);
		}else {
			/**
				* en: The case when the method is not specified.
				* ru: Случай когда метод не указан.
				*/
			$href =setMethodTOURL($form.attr('action'),$method);
			$options.loadAJAXOptions.data='';
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit.$options.loadAJAXOptions.data: "+$options.loadAJAXOptions.data);
		$id = $form.attr('id');
		if ($id!=undefined){
			/**
				* en: If not specified the id for the form, generate your own.
				* ru: Если не указан id для формы, генерируем свой
				*/
			$id = 'tmp_'+Math.floor(Math.random()*100);
			$form.attr('id',$id);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$id="+$id);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$href="+$href);
		getAJAX($href,'submit',$id,0);
	}
	
	/** getAJAXHref($href)<br />
		* en: Perform a GET request
		* ru: Выполнить GET запрос
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
		* en: Callback function by default, when an error in the AJAX request.
		* ru: Callback функция по умолчанию, когда ошибка в AJAX запросе
		* 
		* @access public
		* @param responseText
		* ru: Текст ответа
		* 
		* @param statusText
		* ru: Статус ответа
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
			* en: Turn off the AJAX loading indicator.
			* ru: Выключаем индикатор AJAX загрузки
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() END");
	}
	
	/** showResponse(responseText, statusText)<br />
		* 
		* en: Callback function by default, when the request is completed successfully.
		* ru: Callback функция по умолчанию, когда запрос успешно выполнен 
		* 
		* @access public
		* @param responseText
		* ru: Текст ответа
		* 
		* @param statusText
		* ru: Статус ответа
		* 
		* @return void
		*/
	function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() BEGIN");
		jQuery($options.selectorForAJAXReplace).html(responseText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
		/**
			* en: Turn off the AJAX loading indicator.
			* ru: Выключаем индикатор AJAX загрузки
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() END");
	}
	
	/** getAJAX($href,$type,$id,$error_count)<br />
		* 
		* en: Processing request via jQuery plugin form in include.js.
		* ru: Отправка через jQuery plugin form в include.js 
		* 
		* @param {string} [$href='']
		* 
		* @param {string} [$type='']
		* en: Request type submit [ POST ] and GET
		* ru: Тип запроса submit [ POST ] и GET
		*
		* @param {string} [$id='']
		* en: The form ID
		* ru: ID формы
		* 
		* @param {int} [$error_count=undefined]
		* en: The count of errors in the AJAX call.
		* ru: Количество ошибок при AJAX запросе
		*
		* @access private
		* @return void
		*/
	function getAJAX($href,$type,$id,$error_count){
		if ($error_count==undefined){
		/**
			* en: Initialize the default value of the error count.
			* ru: Инициализируем начальное значение ошибок.
			*/
			$error_count=0;
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.$ajax_is_load="+$options.$ajax_is_load);
		if (!$options.$ajax_is_load){
			/**
				* en: The case when the user has activated a few times to AJAX loading.
				* ru: Случай когда юзер активировал несколько 
				*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions: ");
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($options.loadAJAXOptions,jQuery.evDev.getTab($options.debugPrefixString,5));
			try{
				/**
					* en: Watch for errors.
					* ru: Учитываем возможное появление ошибки
					*/
				if (isURLBaseAJAX()){
					/**
						* en: Is the address specified in the basic $options.scriptForAJAXCallAndSetAnchore?
						* ru: Является ли адрес базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
						*/
					$options.$ajax_is_load = true;
					$options.$loaded_href= $href;
					$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions.url="+$options.loadAJAXOptions.url);
					if (typeof $options.functionsForAJAXIndicator.On==='function'){
						/**
							* en: Turn on the AJAX loading indicator.<br />
							* ru: Включаем индикатор AJAX загрузки
							*/
						$options.functionsForAJAXIndicator.On($options);
					}
					if ($type==='submit'){
					/**
						* en: Send data via POST.
						* ru: Отправим данные через POST 
						*/
						$('#'+$id).ajaxSubmit($options.loadAJAXOptions);
					}else {
					/**
						* en: Send data via GET.
						* ru: Отправим данные через GET 
						*/
						$.ajax($options.loadAJAXOptions);
					}
				}else {
				/**
					* en: The case when the URL has the parameters. Set the default URL for anchor
					* ru: Случай когда в ссылке есть параметры, 
					* ru: устанавливаем по умолчанию URL для якорной навигации
					*
					* /HelloAJAXJQuery/index.php?param=param#!?test=test
					* /HelloAJAXJQuery/index.php>>?param=param<<#!?test=test
					*/
					setURLToBrowser($options.scriptForAJAXCallAndSetAnchore,$href);
				}
			}catch($bug){
				/**
					* en: Case of errors.
					* ru: Учтём возможные ошибки
					*/
				$options.$ajax_is_load = false;
				if ($options.debugToConsole)        console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$bug="+$bug);
				$options.$loaded_href= '';
				$error_count++;
				if ($error_count<=$options.maxErrorCountBeforeStopAJAXCall){
					/**
						* en: Repeat this operation once more, but not more than the maximum
						* en: error of the options.
						* ru: Повторяем операцию ещё раз, 
						* ru: но не более максимального значения ошибок из опций
						*/
					getAJAX($href,$type,$id,$error_count);
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
				/**
					* en: Turn off the AJAX loading indicator.
					* ru: Выключаем индикатор AJAX загрузки
					*/
					$options.functionsForAJAXIndicator.Off($options);
				}
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() END");
	}
	
		
	/** getHash(loc)<br />
		* en: Obtain a cross-browser anchor of
		* ru: Получаем кросс-браузерно якорную часть
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
		* en: When first initialized, loaded, if there is a link from the anchor
		* ru: При первой инициализации, загрузим, если есть, ссылку из якоря
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
				* en: Save the current controller and method
				* en: User jQuery.evFunc
				* ru: Сохраним текущие данные по контроллеру и методу, 
				* ru: нужно для загрузки дополнительных скриптов
				*/
			if ($options.flag_ev_func&&!$options.setJSFuncForLoadPage.$reload_page){
				$options.setJSFuncForLoadPage.setMethodAndControllerFunc('init');
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() END");
	}
	/**
		* en: When first initialized, loaded, if there is a link from the anchor #!axax=ajax
		* ru: При первой инициализации, загрузим, если есть, ссылку из якоря #!axax=ajax
		*/
	setAnchoreClearWithoutURL();
	if ($options.folowByChangeOfHistory&&$options.isAllowThisBrowser){
		/**
			* en: If set the option to follow the history
			* ru: Если установлена опция следовать за историей
			*/
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange() BEGIN");
			if (window.location.hash){ 
				/**
					* en: If there is an anchor at init
					* ru: Если есть якорь при инициализации
					*/
				$reg=new RegExp('^#'+$options.ancorePreFix,"g");
				$hash = location.hash.replace($reg,"");
				if ($options.$loaded_href_hash_fix!==$hash){
					/**
						* en: No need to load already loaded link in the anchor.
						* ru: Учитываем, что не нужно загружать уже загруженную ссылку в якоре.
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
			* en: If you want debug in IE 6-7, Safari, etc. using alert() as console.info
			* ru: Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
			*/
		if ($options.debugToConsole) console.warn("END");
	}
	if ($options.debugToConsole) {
		/**
			* en: Debug to console
			* ru: Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
			*/
		console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call END');
	}
	if ($options.isAllowThisBrowser){
		/**
			* en: Is there a minimum version of the browser to work with anchor navigation
			* ru: Является ли версия браузера минимальной для работы с якорной навигацией
			*/
		return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			/**
				* en: For selector from the options set the AJAX anchor navigation function.
				* ru: Для селекторов из опций устанавливаем связь
				* ru: с функцией обработки через AJAX якорной ссылки.
				*/
			return getClickHref(this);
		});
	}
};
}(document, jQuery);
//</script>
