//<script type="text/javascript">
jQuery(document).ready(function(){  

	/**
	* ru:Случай когда запрос успешно выполнен
	*/
	$.setEvnineNav({
		debugPrefixString                 :'	',/*FireFox FireBug, Chrome, Opera*/
		//debugToConsoleNotSupport        :true,
		debugToConsoleNotSupport          :false,
		//debugToConsole                    :false,/*FireFox FireBug, Chrome, Opera*/
		debugToConsole                  :true,/*FireFox FireBug, Chrome, Opera*/
		/*Прокручивать вверх*/
		scrollToTopAfterAJAXCall          :false,//TODO
		liveSelectorForAJAX               :'a[class!=json][class!=body], input:submit',
		//liveSelectorForAJAX               :'a[class=test]',
		liveBindType                      :'click',
		crossBrowserMinimumVersion        :{
			msie   :'8'
			//chrome :'6',
			//safari :'4',
			//opera  :'9',
			//mozilla:'1'
		},
		folowByChangeOfHistory            :true,
		maxErrorCountBeforeStopAJAXCall   :3,
		selectorForAJAXReplace            :'body', //body, 
		/*Адрес относительно которого производить ajax вызовы*/
		scriptForAJAXCallAndSetAnchore    :'/HelloAJAXJQuery/index.php',
		ancorePreFix                      :'!',//index.php#!test
		/*Ссылка совпадает с этим регулярным то не использовать аякс*/
		isHREFMatchThisRegExpSetNoUseAJAX :'^http://|^hop',//=$href.match(/^http://|^hop/g)
		/*Если аякс режим добавить параметр в ссылку*/
		ifAJAXAddThisParamToScript        :'ajax=ajax',//=index.php?ajax=ajax
		/*Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
		isHREFMatchThisRegExpSetSEFMode   :'.html$',//=$href.match(/\.html/g)
		/*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//Example: index.html => index.ajax
		ifSEFAJAXReplaceHREFMatchTo       :'.ajax',//=$href.replace(/\.html$/g, '.ajax')
		//Function for the AJAX indicator off and on
		/*Функции для отображения индикации аякс погрузки*/ 
		functionsForAJAXIndicator         : {
			On:function($options){
				if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"functionsForAJAXIndicator.On()");
			},
			Off:function($options){
				if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"functionsForAJAXIndicator.Off()");
			}
		},
		loadAJAXOptions                   :{
			dataType:'html',
			success: function (responseText, statusText, $options){//Функций - Показать AJAX ответ 
				if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"showResponse");
				$options.setJSFuncForLoadPage.setPreCallShowResponse($options.$loaded_href);
				jQuery($options.selectorForAJAXReplace).html(responseText);
				$options.setJSFuncForLoadPage.setPostCallShowResponse($options.$loaded_href);
			},
			error: function (responseText, statusText, $options){//Функций - Показать AJAX ответ в случае ошибке
				if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+$EVNINE_NAME+"showErrorResponse");
			}
		},
		setJSFuncForLoadPage              :$.setEvnineFunc({
			controller:{
				paramName:'c',
				defaultValue:'default'
			},
			method:{
				paramName:'m',
				defaultValue:'default'
			},
			debugPrefixString               :'	',//FireFox FireBug, Chrome, Opera
			debugToConsole                  :true,//FireFox FireBug, Chrome, Opera
			aliasJScriptLinks               :{//Какие скрипты загружаем
				'ui':'/tpl/js/jq.ui.js'
		},
		setFunction:{/*Для того что бы не плодить дубли методов*/
				'function_name'               :function($options) 
				{
					this.access='1';
					//if load new method
					//Если подгрузили новый метод
					this.setAction = function() {
					};
					//if reload same method
					//Если перегрузили метод
					this.setReloadPageAction = function() {
					};
					//if change current method
					//Если загрузил
					this.unSetAction = function() {
						if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->unSetAction()");
					};
				},
				//Проверяем доступ
				//if (this.isHasAccess('function_name'.default_access_level))
				'isHasAccess':function($obj,$options) {
					if ($obj.access==undefined){
						if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->isHasAccess()=true");
						return true;
					}else {
						$current_level= 1;
						if ($current_level>=$obj.access){
						if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->isHasAccess()=true");
							return true;
						}else {
							if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->isHasAccess()=false");
							return false;
						}
					}
					if ($options.debugToConsole) console.warn(jQuery.setEvnineDebug.getTabByLevel($options.debugPrefixString,5)+"$options->setJSFuncForLoadPage->setFunction->isHasAccess()=true");
					return true;
				}
			},
			strUnionControllerWithMethod    :'::',
			setFuncByEvnineParamMatch       :{//Контроллеры и связанные с ними функции
				//For controller
				'default'                       :'function_name'
				//'default::default'              :'function_name',
				//'path'                          :'function_name',
				//For controller with method
				//'path::ext_search'              :'function_name'
			}
			//setFuncByHREFMatch              :{
				//eq:{
					//'index.php'                   : 'function_name'
				//},
				//MatchRegHREF:{
					//'index\.php.*'                :'function_name'
				//}
			//}
			//$getMatchURNFunction:{//Контроллеры и связанные с ними функции
			//}
		})
	});
	//$('#clicked').click();
//$().evnine({
		//debugToConsole                     :true,/*FireFox FireBug, Chrome, Opera*/
		//liveSelectorForAJAX               :'.json',
		///*Если аякс режим добавить параметр в ссылку*/
		//ifAJAXAddThisParamToScript       :'ajax=json',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//ifSEFAJAXReplaceHREFMatchTo    :'.json'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});
//
//$().evnine({
		//debugToConsole                     :true,/*FireFox FireBug, Chrome, Opera*/
		//liveSelectorForAJAX               :'.body',
		///*Если аякс режим добавить параметр в ссылку*/
		//ifAJAXAddThisParamToScript       :'ajax=body',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//ifSEFAJAXReplaceHREFMatchTo    :'.body'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});


});
//</script>
