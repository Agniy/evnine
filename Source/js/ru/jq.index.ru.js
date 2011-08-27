//<script type="text/javascript">
jQuery(document).ready(function(){  
	/**
	* @name $.evNav
	* @author ev9eniy.info 
	* <a href="http://ev9eniy.info/evnine">ev9eniy.info/evnine</a>
	* 
	*
	* @class $.evNav
	* <br />
	* <br /> Вызов jQuery плагина навигации с параметрами
	* <br /> и передача в плагин навигации jQuery плагина запуска функций $.evFunc()
	* 
	* @config {object} [ =<a href="jQuery.evNav.html#constructor">jQuery.evNav arguments</a>]
	*  Инициализируем плагин навигации.
	* 
	* @config {object}  [setJSFuncForLoadPage:$.evFunc({})=<a href="jQuery.evFunc.html#constructor">jQuery.evFunc arguments</a>] 
	*  Доступ для плагина запуска функций 
	* 
	* @example 
	*  Пример запуска плагина для перехода по ссылкам, с отладкой
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
	*  Пример для показа индикатора аякс загрузки.
	* $.evNav({
	*	functionsForAJAXIndicator: {
	*		On:function($options){$('#ajax_load').show();},
	*		Off:function($options){$('#ajax_load').off();}
	*	},
	*});
	*
	*  случай - если хотим использовать индикатор в опциях.
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
	/*  Опции для AJAX загрузки*/
	loadAJAXOptions                   :{
		/* В случае успешного запроса - показать ответ */
		success: function (responseText, statusText, $options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() BEGIN");
			/* Функция установки флагов для плагина запуска функций */
			$options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
			jQuery($options.selectorForAJAXReplace).html(responseText);
			/* Функция после загрузки с запуском скриптов для данной страницы */
			$options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() END");
		}
	},
	/* Доступ для плагина запуска функций */
	setJSFuncForLoadPage              :$.evFunc({
		/* Функция для запуска после аякс загрузки страницы */
		setFunction:
		/* Передаём опции для того что бы иметь доступ к настройкам */{
				'default'               :function($options) 
				{
					/* Уровень доступа */
					this.access='1';
					/* Если подгрузили новый метод*/
					this.setAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setAction()");
						/* метод include для выполнения функции после загрузки скрипта, если функция загружена не подгружаем */
						$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test.js',function(){
							$options.include_once('/HelloAJAXJQuery/js/jq.getscript.test2.js',function(){
								getTestScript();
							});
						});
					};
					/* Если перегрузили страницу */
					this.setReloadPageAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setReloadPageAction()");
					};
					// Если загрузил другой метод и нужно удалить что либо.
					this.unSetAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.unSetAction()");
					};
				},
				/* Проверяем доступ, инициализируем с объектом, функции из setFunction */
				/* А так же передаём опции */
				'isHasAccess':function($obj,$options) {
					if ($obj.access==undefined){
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}else {
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					}
					return true;
				}
		},
			/* Контроллер.метод и связанная с ним функции */
			setFuncByEvnineParamMatch       :{
				//For controller
				'validation'                  :'default',
				'default'                     :'default',
				'param1'                      :'default',
				//controller.method
				'param1.param1'               :'default'
			}
			/* Ссылка и связанная с ней функции */	
			//setFuncByHREFMatch              :{
			//	'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			/* Регулярное выражение и связанная с ней функции */	
			//setFuncByMatchRegHREF              :{
			//	'.*index\.php.*'                :'default'
	})
});
});
//</script>
