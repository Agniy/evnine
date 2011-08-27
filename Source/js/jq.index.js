//<script type="text/javascript">
jQuery(document).ready(function(){  
	/**
	* @name $.evNav
	* @author ev9eniy.info 
	* <a href="http://ev9eniy.info/evnine">ev9eniy.info/evnine</a>
	* 
	*
	* @class $.evNav
	* <br />en: Init $.evNav() jQuery plugin with options and 
	* <br />en: $.evFunc() jQuery plugin as callback function
	* <br />
	* <br />ru: Вызов jQuery плагина навигации с параметрами
	* <br />ru: и передача в плагин навигации jQuery плагина запуска функций $.evFunc()
	* 
	* @config {object} [ =<a href="jQuery.evNav.html#constructor">jQuery.evNav arguments</a>]
	* en: Init evNav Plugin<br />
	* ru: Инициализируем плагин навигации.
	* 
	* @config {object}  [setJSFuncForLoadPage:$.evFunc({})=<a href="jQuery.evFunc.html#constructor">jQuery.evFunc arguments</a>] 
	* en: Init evFunc plugin<br />
	* ru: Доступ для плагина запуска функций 
	* 
	* @example 
	* en: Example of running plugin with debug to console and group prefix.
	* ru: Пример запуска плагина для перехода по ссылкам, с отладкой
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
	* en: Example for AJAX Indicator.
	* ru: Пример для показа индикатора аякс загрузки.
	* $.evNav({
	*	functionsForAJAXIndicator: {
	*		On:function($options){$('#ajax_load').show();},
	*		Off:function($options){$('#ajax_load').off();}
	*	},
	*});
	*
	* en: case - save ajax indicator object in $options.
	* ru: случай - если хотим использовать индикатор в опциях.
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
	scriptForAJAXCallAndSetAnchore    :'/evnine-lesson-02-jQuery-AJAX/HelloAJAXJQuery/index.php',
	functionsForAJAXIndicator         : {
		On:function($options){if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+"$options.functionsForAJAXIndicator.On()");},
		Off:function($options){if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.functionsForAJAXIndicator.Off()");}
	},
	// en: Options for the AJAX load
	/* ru: Опции для AJAX загрузки*/
	loadAJAXOptions                   :{
		//en: In the case of a successful request - show a response. 
		/*ru: В случае успешного запроса - показать ответ */
		success: function (responseText, statusText, $options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() BEGIN");
			//en: Setting function of flags for evFunc()
			/*ru: Функция установки флагов для плагина запуска функций */
			$options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
			jQuery($options.selectorForAJAXReplace).html(responseText);
			//en: Call function after jQuery html replace.
			/*ru: Функция после загрузки с запуском скриптов для данной страницы */
			$options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() END");
		}
	},
	//en: Init evFunc plugin
	/*ru: Доступ для плагина запуска функций */
	setJSFuncForLoadPage              :$.evFunc({
		//en: Functions for execute after ajax complete
		/*ru: Функция для запуска после аякс загрузки страницы */
		setFunction:
		//en: Init with setJSFuncForLoadPage.$options
		/*ru: Передаём опции для того что бы иметь доступ к настройкам */{
				'default'               :function($options) 
				{
					//en: Default access level
					/*ru: Уровень доступа */
					this.access='1';
					//en: if load new method
					/*ru: Если подгрузили новый метод*/
					this.setAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setAction()");
						//en: include once method with call back function
						/*ru: метод include для выполнения функции после загрузки скрипта, если функция загружена не подгружаем */
						$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test.js',function(){
							$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test2.js',function(){
								getTestScript();
							});
						});
					};
					//en: if reload same page(method)
					/*ru: Если перегрузили страницу */
					this.setReloadPageAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setReloadPageAction()");
					};
					//en: if change current page(method)
					//ru: Если загрузил другой метод и нужно удалить что либо.
					this.unSetAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.unSetAction()");
					};
				},
				//en: Is user has access for function, init with setFunction[function] and setJSFuncForLoadPage.$options
				/*ru: Проверяем доступ, инициализируем с объектом, функции из setFunction */
				/*ru: А так же передаём опции */
				'isHasAccess':function($obj,$options) {
					if ($obj.access==undefined){
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}else {
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}
					return true;
				}
		},
			//en: Function is for controller with method.
			/*ru: Контроллер.метод и связанная с ним функции */
			setFuncByEvnineParamMatch       :{
				//For controller
				'validation'                  :'default',
				'default'                     :'default',
				'param1'                      :'default',
				//controller.method
				'param1.param1'               :'default'
			}
			//en: URN and related functions
			/*ru: Ссылка и связанная с ней функции */	
			//setFuncByHREFMatch              :{
			//	'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			//en: RegExp and associated functions.
			/*ru: Регулярное выражение и связанная с ней функции */	
			//setFuncByMatchRegHREF              :{
			//	'.*index\.php.*'                :'default'
	})
});
});
//</script>
