//<script type="text/javascript">
/**
	* @version 0.3
	* @author ev9eniy.info
	* @class jQuery.evNav
	* <br /> Плагин навигации по ссылкам с установкой якоря после аякс загрузки страницы
	* <br />
	* <br /> Двойная лицензия MIT или GPL v.2 
	* <br />Copyright 2011, (c) ev9eniy.info
	* 
	* @config {boolean} [debugToConsole=false]
	*  Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
	* 
	* @config {string} [debugPrefixString='| ']
	*  Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
	*
	* @config {boolean} [debugToConsoleNotSupport=false]
	*  Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
	*
	* @config {boolean} [debugFunctionGroup=false]
	*  Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций 
	*
	* @config {boolean} [scrollToTopAfterAJAXCall=true]
	*  Прокручивать вверх после аякс загрузки страницы
	* 
	* @config {selector} [liveSelectorForAJAX=a, input:submit]
	*  jQuery селектор для привязки
	*
	* @config {bind} [liveBindType='click']
	*  Тип события по которому загружаем страницу
	*
	* @config {string} [crossBrowserMinimumVersion.msie='8']
	*  Минимальная версия для совместимости 
	* 
	* @config {boolean} [folowByChangeOfHistory=true]
	*  Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории 
	*
	* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
	*  Через сколько попыток остановить вызов AJAX 
	*
	* @config {selector} [selectorForAJAXReplace=body]
	*  Селектор для заметы, в данном случае заменяем все тело страницы
	* 
	* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
	*  URL относительно которого производить ajax вызовы
	*
	* @config {string} [ancorePreFix=!]
	*  Префикс в якоре
	* <br />index.php#!test
	* 
	* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
	*  Ссылка совпадает с этим регулярным то не использовать аякс
	* <br />$href.match(/^http:\/\//g)
	*
	* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
	*  Если аякс режим добавить параметр в ссылку вызова скрипта
	* <br />index.php?ajax=ajax
	*
	* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
	*  Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
	*
	* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
	*  Если аякс работает в ЧПУ режиме, заменим адрес на $href.replace(/\.html$/g, '.ajax')
	* <br />index.html => index.ajax
	* 
	* @config {object} [functionsForAJAXIndicator=undefined]
	*  Функции для отображения индикации аякс погрузки 
	* 
	* @config {function} functionsForAJAXIndicator.On($options)
	* @config {function} functionsForAJAXIndicator.Off($options)
	*
	* @config {object} [loadAJAXOptions]
	*  Опции для AJAX загрузки
	*
	* @config {string} [loadAJAXOptions.dataType=html]
	*  Тип для загрузки страницы
	*
	* @config {function} [loadAJAXOptions.success(responseText,statusText,$options)=jQuery.evNav.showResponse(responseText, statusText, $options)]
	*  Успешное получение данных, вызываются через функцию - обертку с передачей опций
	*
	* @config {function} [loadAJAXOptions.error(responseText,statusText,$options)=jQuery.evNav.showResponseError(responseText, statusText, $options)]
	*  При ошибке, вызываются через функцию - обертку с передачей опций
	*/
new function (document, $, undefined) {
	jQuery.evNav = function($rewrite_options){
	// The current version of Evnine being used
	$EVNINE_VER="0.3";
	$EVNINE_NAME='evNav'+'.';
	/**
		*  Настройки по умолчанию
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
		*  Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
		*/
	debugPrefixString                 :'|	',
	//debugPrefixString               :' ',
	/**
		* @config {string} [debugPrefixString='| ']
		*  Префикс для вывода в окно отладки группирования по функциям (FireFox FireBug, Chrome, Opera)
		*/
	debugToConsoleNotSupport          :false,
	//debugToConsoleNotSupport        :true,
	/**
		* @config {boolean} [debugToConsoleNotSupport=false]
		*  Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
		*/
	debugFunctionGroup                :false,
	//debugFunctionGroup              :true,
	/**
		* @config {boolean} [debugFunctionGroup=false]
		*  Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций 
		*/
	scrollToTopAfterAJAXCall          :true,
	/**
		* @config {boolean} [scrollToTopAfterAJAXCall=true]
		*  Прокручивать вверх после аякс загрузки страницы
		*/
	liveSelectorForAJAX               :'a, input:submit',
	/**
		* @config {selector} [liveSelectorForAJAX=a, input:submit]
		*  jQuery селектор для привязки
		*/
	liveBindType                      :'click',
	//liveBindType                    :'mouseover',
	/**
		* @config {bind} [liveBindType='click']
		*  Тип события по которому загружаем страницу
		*/
	crossBrowserMinimumVersion        :{msie   :'8'},
	/**
		* @config {string} [crossBrowserMinimumVersion.msie='8']
		*  Минимальная версия для совместимости 
		*
		*/
	folowByChangeOfHistory            :true,
	/**
		* @config {boolean} [folowByChangeOfHistory=true]
		*  Отслеживаем если юзер нажал кнопку назад, либо выбрал ссылку из истории 
		*/
	maxErrorCountBeforeStopAJAXCall   :3,
	/**
		* @config {int} [maxErrorCountBeforeStopAJAXCall=3]
		*  Через сколько попыток остановить вызов AJAX 
		*/
	selectorForAJAXReplace            :'body', 
	/**
		* @config {selector} [selectorForAJAXReplace=body]
		*  Селектор для заметы, в данном случае заменяем все тело страницы
		*/
	scriptForAJAXCallAndSetAnchore    :'/index.php',
	/**
		* @config {string} [scriptForAJAXCallAndSetAnchore=/index.php]
		*  URL относительно которого производить ajax вызовы
		*/
	ancorePreFix                      :'!',
	/**
		* @config {string} [ancorePreFix=!]
		*  Префикс в якоре
		* <br />index.php#!test
		*/
	isHREFMatchThisRegExpSetNoUseAJAX :'^http://',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetNoUseAJAX=^http://]
		*  Ссылка совпадает с этим регулярным то не использовать аякс
		* <br />$href.match(/^http:\/\//g)
		*/
	ifAJAXAddThisParamToScript        :'ajax=ajax',
	/**
		* @config {string} [ifAJAXAddThisParamToScript=ajax=ajax]
		*  Если аякс режим добавить параметр в ссылку вызова скрипта
		* <br />index.php?ajax=ajax
		*/
	isHREFMatchThisRegExpSetSEFMode   :'.html$',
	/**
		* @config {RegExp} [isHREFMatchThisRegExpSetSEFMode=.html$]
		*  Ссылка совпадает с регулярным, аякс в ЧПУ режиме $href.match(/\.html/g)
		*/
	ifSEFAJAXReplaceHREFMatchTo       :'.ajax',
	/**
		* @config {string} [ifSEFAJAXReplaceHREFMatchTo=.ajax]
		*  Если аякс работает в ЧПУ режиме, заменим адрес на $href.replace(/\.html$/g, '.ajax')
		* <br />index.html => index.ajax
		*/
	functionsForAJAXIndicator         :undefined,
	/**
		* @config {object} [functionsForAJAXIndicator=undefined]
		* @config {function} functionsForAJAXIndicator.On($options)
		* @config {function} functionsForAJAXIndicator.Off($options)
		*  Функции для отображения индикации аякс погрузки 
		*/
	setJSFuncForLoadPage              :undefined
	},$rewrite_options);
	if ($rewrite_options!=undefined){
		$options.loadAJAXOptions = jQuery.extend({
			success: showResponse,error: showResponseError,dataType: 'html'
		},$rewrite_options.loadAJAXOptions);
	/**
		*  Обертка callback функции после AJAX вызова если произошла ошибка<br />
		*  Выполняется в случае, когда при инициализации задана функция.
		*/
		if ($rewrite_options.loadAJAXOptions.error!=undefined){
			$options.loadAJAXOptions.error_for_options=$rewrite_options.loadAJAXOptions.error;
			$options.loadAJAXOptions.error=function(responseText, statusText){
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() BEGIN");
				try{
					/**
						*  Передаём в функцию созданную при инициализации текущие опции<br />
						*  Для примера что бы выполнить методы из setJSFuncForLoadPage:$.evFunc({})
						*/
					$options.loadAJAXOptions.error_for_options(responseText, statusText, $options);
				}catch($e){
					if ($options.debugToConsole) console.error(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+'setJSFuncForHREF(): '+"try{...} catch(){"+$e+'}');
				}
				/**
					*  Выключаем индикатор загрузки
					*/
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
					$options.functionsForAJAXIndicator.Off($options);
				}
				$options.$ajax_is_load = false;
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.loadAJAXOptions.error_for_options() END");
			};
		}
		/**
			*  Обертка callback функции после AJAX вызова если успешный вызов<br />
			*  Выполняется в случае, когда при инициализации задана функция.
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
		*  Флаг, что бы загружать только один раз страницу
		*  
		* @var string
		* @access public
		*/
	$options.$loaded_href_hash_fix='';
	/**
		*  Текущий загруженный адрес используется для callback function в evFunc() 
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
		*  Текущее состояние AJAX загрузки,<br /> 
		*  используется что бы выполнять только один AJAX запрос
		* 
		* @var boolean
		* @access public
		*/
	$options.$ajax_is_load=false;
	if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call BEGIN');
	/**
		*  Используется ли evFunc()?
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
		*  Есть ли поддержка консоли для отладки в браузере?
		*/
	if ($options.debugToConsole){
		if (!window.console||jQuery.evDev==undefined){
			$options.debugToConsole=false;
		}else {
			jQuery.evDev.initNotSupport();
		}
		/**
			*  Если используется группировка средствами консоли, удаляем префикс для отступов
			*/
		if ($options.debugFunctionGroup){
			$options.debugPrefixString= '';
			jQuery.evDev.initGroupFunctionCall($options.debugToConsoleNotSupport);
		}
	}

	/** getInt($int)<br />
		*  Функция получение числа
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
		*  Убираем пробелы до и после.
		* 
		* @param {string} $str 
		* @access public
		* @return string
		*/
	function trim(str){
		return str.replace(/^ +/g,'').replace(/ +$/g,'');
	}

	/**
		*  Получим имя скрипта, используется для установки якоря.
		* 
		* @var string
		* @access public
		*/
	$options.scriptNameForAJAX= $options.scriptForAJAXCallAndSetAnchore.replace(/^.*(\\|\/|\:)/, '');
	
	/** isHasCrossBrowserMinimumVersion()<br />
		* 
		*  Функция проверки. <br />
		*  Является ли версия браузера минимальной для работы к с якорной навигацией.
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
		 *  Поддерживает ли браузер работу с evNav плагином?
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
		*  Является ли версия браузера минимальной для работы к с якорной навигацией
		* 
		* @var {boolean} [isAllowThisBrowser=true]
		* @access public
		*/
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();
	if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

	/** getRegSafeString($str)<br />
		* 
		* Для получения безопасного выражения
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
		*  Выполнить AJAX запрос исходя из типа кликнутого элемента
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
				*  Случай когда не нужно использовать AJAX
				*/
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is no ajax href");
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
				return true;
			}else {
			/**
				*  Используем для обработки AJAX GET запроса при клике на ссылку
				*/
				getAJAXHref($href);
				if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax href");
			}
		}else if (jQuery($that).attr('type')==='submit'){
		/**
			*  Если кликнули на отправку формы через AJAX POST
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getClickHref is ajax submit");
				getAJAXSubmit($that);
		}
		return false;
	}
	
	/** getURLWithFlag($href,$post_fix,$post_sef)<br />
		* 
		*  Добавить параметры к адресной части
		* 
		* @param {string} $href
		*  Адрес
		* 
		* @param {string} $post_fix
		*  Параметры запроса.
		* 
		* @param {string} $post_sef
		*  Для ЧПУ режима
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
			*  Если ЧПУ режим
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match SEF ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href.replace($reg, $post_sef);
		}else if($href.match(/\?/)) {
		/**
			*  Если не режим ЧПУ и есть параметры в адресной части
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match & ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'&'+$post_fix;
		}else {
		/**
			*  Если не режим ЧПУ и нет параметров в адресной части
			*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,8)+$EVNINE_NAME+"getURLWithFlag match ? ");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"getURLWithFlag() END");
			return $href+'?'+$post_fix;
		}
	}
	
	/** isURLBaseAJAX()<br />
		* 
		*  Является ли текущий URL базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
		* 
		* @example 
		* /index.php#!?test=test 
		* return true
		*
		* /index.php?param=param#!?test=test 
		*  return false - в адресе ?param=param
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
				*  Когда имя соответствует имени из опций инициализации
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
		*  Ставим якорную ссылку если браузер позволяет это сделать<br />
		*  В случае если не базовый адрес используется, ставим базовый скрипт и параметры адреса
		* 
		* @example 
		* /HelloAJAXJQuery/index.php?param=param#!?test=test
		* =
		* /HelloAJAXJQuery/index.php#!?test=test
		* 
		* @param {string} $base
		*  Текущий URL
		*
		* @param {string} $href
		*  Ссылка для перехода
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
		*  Установить адрес в историю просмотра
		* 
		* @param {string} $href 
		*  Ссылка для перехода
		* 
		* @access public
		* @return {string} $href or $options.scriptForAJAXCallAndSetAnchore 
		* 
		*/
	function setURLToHashAndLocation($href) {
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+$EVNINE_NAME+"setURLToHashAndLocation($href="+$href+")");
		if ($href==='/') {
		/**
			*  Если хотим перейти на главную, переходим на базовую страницу
			*/
			$options.$loaded_href_hash_fix= '';
			window.location.hash = $.URLDecode($options.ancorePreFix);
			if ($options.debugToConsole) document.title = '';
			return $options.scriptForAJAXCallAndSetAnchore;
		}else {
		/**
			*  Установим якорную ссылку
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
		*  Для случая, когда в кнопке указан метод, устанавливаем этот метод в ссылку
		* 
		* @access public
		* 
		* @param {string} $href
		*  Ссылка для добавления метода
		* @param {string} $method
		*  Название метода
		* 
		* @return {string} $href 
		*  Возвращаем адрес с добавленным методом
		* 
		*/
		function setMethodTOURL($href,$method) {
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() BEGIN');
			if ($method.length===0){
			/**
				*  Случай когда метод не указан
				*/
				return $href;
			}else {
				//TODO TEST!!!!! SEF add method
				//if ($href.match(/\.html$/)){
				$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
				if ($href.match($reg)){
				/**
					*  Проверяем случай когда передан ЧПУ.
					*/
					$href = $href.substring(1);
					$method_replace  = $href.match(/.*\//);
					$method_replace=$method_replace.toString().split(/\//);
					if ($method_replace[1].match(/=/)){
					/**
						*  Случай когда используется ЧПУ для контроллера.
						* 
						*/
						$method_match==$method_replace[0]+'/';
						$method='/'+$method_replace[0]+'/'+$method;
					}else {
						/**
							*  Случай когда используется ЧПУ для метода.
							*/
						$method_match=$method_replace[1];
					}
					$href = $href.replace($method_match,$method);
				}else {
					/**
						*  Случай когда передан стандартный адрес, без ЧПУ
						*/
					$method_match = $href.match(/&m=.*/);
					$href = $href.replace($method_match,"&m="+$method);
				}
				if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,2)+$EVNINE_NAME+'setMethodTOURL() END');
				return $href;
			}
		}

	/** getAJAXSubmit($that)<br />
		*  Выполнить POST запрос
		* 
		* @param {jQuery object} $that
		*  Объект для получения данных о форме
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
				*  Получим имя метода из <input name="submit[method_name]" type="submit" />
				*/
			$method = $method.substring(7, $length-1);
			$options.loadAJAXOptions.data= {'submit':$method};
			$href =setMethodTOURL($form.attr('action'),$method);
		}else {
			/**
				*  Случай когда метод не указан.
				*/
			$href =setMethodTOURL($form.attr('action'),$method);
			$options.loadAJAXOptions.data='';
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAXSubmit.$options.loadAJAXOptions.data: "+$options.loadAJAXOptions.data);
		$id = $form.attr('id');
		if ($id!=undefined){
			/**
				*  Если не указан id для формы, генерируем свой
				*/
			$id = 'tmp_'+Math.floor(Math.random()*100);
			$form.attr('id',$id);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$id="+$id);
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"getAJAXSubmit.$href="+$href);
		getAJAX($href,'submit',$id,0);
	}
	
	/** getAJAXHref($href)<br />
		*  Выполнить GET запрос
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
		*  Callback функция по умолчанию, когда ошибка в AJAX запросе
		* 
		* @access public
		* @param responseText
		*  Текст ответа
		* 
		* @param statusText
		*  Статус ответа
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
			*  Выключаем индикатор AJAX загрузки
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponseError() END");
	}
	
	/** showResponse(responseText, statusText)<br />
		* 
		*  Callback функция по умолчанию, когда запрос успешно выполнен 
		* 
		* @access public
		* @param responseText
		*  Текст ответа
		* 
		* @param statusText
		*  Статус ответа
		* 
		* @return void
		*/
	function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() BEGIN");
		jQuery($options.selectorForAJAXReplace).html(responseText);
		$options.$ajax_is_load = false;
		if (typeof $options.functionsForAJAXIndicator.Off==='function'){
		/**
			*  Выключаем индикатор AJAX загрузки
			*/
			$options.functionsForAJAXIndicator.Off($options);
		}
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse() END");
	}
	
	/** getAJAX($href,$type,$id,$error_count)<br />
		* 
		*  Отправка через jQuery plugin form в include.js 
		* 
		* @param {string} [$href='']
		* 
		* @param {string} [$type='']
		*  Тип запроса submit [ POST ] и GET
		*
		* @param {string} [$id='']
		*  ID формы
		* 
		* @param {int} [$error_count=undefined]
		*  Количество ошибок при AJAX запросе
		*
		* @access private
		* @return void
		*/
	function getAJAX($href,$type,$id,$error_count){
		if ($error_count==undefined){
		/**
			*  Инициализируем начальное значение ошибок.
			*/
			$error_count=0;
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() BEGIN");
		if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.$ajax_is_load="+$options.$ajax_is_load);
		if (!$options.$ajax_is_load){
			/**
				*  Случай когда юзер активировал несколько 
				*/
			if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions: ");
			if ($options.debugToConsole) jQuery.evDev.getTraceObject($options.loadAJAXOptions,jQuery.evDev.getTab($options.debugPrefixString,5));
			try{
				/**
					*  Учитываем возможное появление ошибки
					*/
				if (isURLBaseAJAX()){
					/**
						*  Является ли адрес базовым указанный в $options.scriptForAJAXCallAndSetAnchore?
						*/
					$options.$ajax_is_load = true;
					$options.$loaded_href= $href;
					$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
					if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$options.loadAJAXOptions.url="+$options.loadAJAXOptions.url);
					if (typeof $options.functionsForAJAXIndicator.On==='function'){
						/**
							*  Включаем индикатор AJAX загрузки
							*/
						$options.functionsForAJAXIndicator.On($options);
					}
					if ($type==='submit'){
					/**
						*  Отправим данные через POST 
						*/
						$('#'+$id).ajaxSubmit($options.loadAJAXOptions);
					}else {
					/**
						*  Отправим данные через GET 
						*/
						$.ajax($options.loadAJAXOptions);
					}
				}else {
				/**
					*  Случай когда в ссылке есть параметры, 
					*  устанавливаем по умолчанию URL для якорной навигации
					*
					* /HelloAJAXJQuery/index.php?param=param#!?test=test
					* /HelloAJAXJQuery/index.php>>?param=param<<#!?test=test
					*/
					setURLToBrowser($options.scriptForAJAXCallAndSetAnchore,$href);
				}
			}catch($bug){
				/**
					*  Учтём возможные ошибки
					*/
				$options.$ajax_is_load = false;
				if ($options.debugToConsole)        console.info(jQuery.evDev.getTab($options.debugPrefixString,4)+$EVNINE_NAME+"getAJAX.$bug="+$bug);
				$options.$loaded_href= '';
				$error_count++;
				if ($error_count<=$options.maxErrorCountBeforeStopAJAXCall){
					/**
						*  Повторяем операцию ещё раз, 
						*  но не более максимального значения ошибок из опций
						*/
					getAJAX($href,$type,$id,$error_count);
				}
				if (typeof $options.functionsForAJAXIndicator.Off==='function'){
				/**
					*  Выключаем индикатор AJAX загрузки
					*/
					$options.functionsForAJAXIndicator.Off($options);
				}
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,3)+$EVNINE_NAME+"getAJAX() END");
	}
	
		
	/** getHash(loc)<br />
		*  Получаем кросс-браузерно якорную часть
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
		*  При первой инициализации, загрузим, если есть, ссылку из якоря
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
				*  Сохраним текущие данные по контроллеру и методу, 
				*  нужно для загрузки дополнительных скриптов
				*/
			if ($options.flag_ev_func&&!$options.setJSFuncForLoadPage.$reload_page){
				$options.setJSFuncForLoadPage.setMethodAndControllerFunc('init');
			}
		}
		if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+$EVNINE_NAME+"setAnchoreClearWithoutURL() END");
	}
	/**
		*  При первой инициализации, загрузим, если есть, ссылку из якоря #!axax=ajax
		*/
	setAnchoreClearWithoutURL();
	if ($options.folowByChangeOfHistory&&$options.isAllowThisBrowser){
		/**
			*  Если установлена опция следовать за историей
			*/
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+"hashchange() BEGIN");
			if (window.location.hash){ 
				/**
					*  Если есть якорь при инициализации
					*/
				$reg=new RegExp('^#'+$options.ancorePreFix,"g");
				$hash = location.hash.replace($reg,"");
				if ($options.$loaded_href_hash_fix!==$hash){
					/**
						*  Учитываем, что не нужно загружать уже загруженную ссылку в якоре.
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
			*  Если нужна отладка в консоль с использованием alert() в IE 6-7, Safari итд 
			*/
		if ($options.debugToConsole) console.warn("END");
	}
	if ($options.debugToConsole) {
		/**
			*  Выводить отладочную информацию в консоль (FireFox FireBug, Chrome, Opera итд)
			*/
		console.warn(jQuery.evDev.getTab($options.debugPrefixString,0)+$EVNINE_NAME+'call END');
	}
	if ($options.isAllowThisBrowser){
		/**
			*  Является ли версия браузера минимальной для работы с якорной навигацией
			*/
		return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			/**
				*  Для селекторов из опций устанавливаем связь
				*  с функцией обработки через AJAX якорной ссылки.
				*/
			return getClickHref(this);
		});
	}
};
}(document, jQuery);
//</script>
