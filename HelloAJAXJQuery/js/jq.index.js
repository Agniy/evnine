//<script type="text/javascript">
jQuery(document).ready(function(){  
/**
	* en: 
	* ru:Вызов плагина навигации с параметрами
	*/
$.evNav({
	functionsForAJAXIndicator         : {
		On:function($options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,4)+"$options.functionsForAJAXIndicator.On()");
		},
		Off:function($options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.functionsForAJAXIndicator.Off()");
		}
	},
	//en: 
	/*ru: Опции для AJAX загрузки, вызываются через обертку для того что бы учесть выставления флагов и показа индикатора */
	loadAJAXOptions                   :{
		//en: 
		/*ru: Тип данных */
		dataType:'html',
		//en: 
		/*ru: В случае успешного запроса - показать ответ*/
		success: function (responseText, statusText, $options){
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() BEGIN");
			//en: 
			/*ru: Функция установки флагов для плагина запуска функций*/
			$options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
			jQuery($options.selectorForAJAXReplace).html(responseText);
			//en: 
			/*ru: Функция пос загрузки с запуском скриптов для данной страницы*/
			$options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.success() END");
		},
		//en: 
		/*ru: В случае ошибки запроса, так же запускается в обертке */
		error: function (responseText, statusText, $options){//Функций - Показать AJAX ответ в случае ошибке
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.error() BEGIN");
			if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,1)+"$options.loadAJAXOptions.error() END");
		}
	},
	//en: 
	/*ru: Доступ для плагина запуска функций */
	setJSFuncForLoadPage              :$.evFunc({
		//en:
		/*ru: Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
		isHREFMatchThisRegExpSetSEFMode   :'.html$',//=$href.match(/\.html/g)
		//en:
		/*ru: Параметр для контроллера и значение по умолчанию*/
		controller:{
			paramName:'c',
			defaultValue:'default'
		},
		//en:
		/*ru: Параметр для контроллера и значение по умолчанию*/
		method:{
			paramName:'m',
			defaultValue:'default'
		},
		//en: 
		/*ru: Выводить отладочную информацию в консоль FireFox FireBug, Chrome, Opera итд*/
		debugToConsole                    :true,
		//debugToConsole                  :false,
		//en: 
		/*ru: Префикс для вывода в окно отладки FireFox FireBug, Chrome, Opera*/
		debugPrefixString                 :'|	',
		//debugPrefixString               :' ',
		//en: 
		/*ru: Если нужна отладка в консоль в IE 6-7, Safari итд */
		debugToConsoleNotSupport          :false,
		//debugToConsoleNotSupport        :true,
		//en: 
		/*ru: Использовать группировку в консоли для плагина навигации, так же нужно указать в плагине функций */
		//debugFunctionGroup                :true,
		debugFunctionGroup              :false,
		
		//en:
		/*ru: функция для запуска после аякс подгрузки */
		setFunction:
		//en:
		/*ru: Передаём опции для того что бы иметь доступ к настройкам */{
				'default'               :function($options) 
				{
					//en:
					/*ru: Уровень доступа */
					this.access='1';
					//en: if load new method
					/*ru: Если подгрузили новый метод*/
					this.setAction = function() {
						if ($options.debugToConsole) console.info(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.default.setAction()");
						//en:
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
				//en:
				/*ru: Проверяем доступ, инициализируем с объектом, функции из setFunction */
				/*ru: А так же передаём опции */
				'isHasAccess':function($obj,$options) {
					if ($obj.access==undefined){
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
						return true;
					}else {
						$current_level= 1;
						if ($current_level>=$obj.access){
						if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
							return true;
						}else {
							if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=false");
							return false;
						}
					}
					if ($options.debugToConsole) console.warn(jQuery.evDev.getTab($options.debugPrefixString,7)+"$options.setJSFuncForLoadPage.setFunction.isHasAccess()=true");
					return true;
				}
			},
			//en:
			/*ru: символ для объединения методов в setFuncByEvnineParamMatch*/
			strUnionControllerWithMethod    :'.',
				//
			//en: Function is for controller with method.
			/*ru: Контроллер.метод и связанная с ним функции */
			setFuncByEvnineParamMatch       :{
				//For controller
				'validation'                    :'default',
				'default'                       :'default',
				//'default.default'               :'default',
				'param1'                        :'default'
				//'param1.param2'                 :'default'
			}
			//en:
			/*ru: Ссылка и связанная с ней функции */	
			//setFuncByHREFMatch              :{
				//'/HelloAJAXJQuery/index.php'  : 'param1'
			//},
			//en:
			/*ru: Регулярное выражение и связанная с ней функции */	
			//setFuncByMatchRegHREF              :{
				//'.*index\.php.*'                :'default'
			//}
	})
});
});
//</script>
