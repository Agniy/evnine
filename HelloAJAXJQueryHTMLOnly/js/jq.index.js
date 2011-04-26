//<script type="text/javascript">
jQuery(document).ready(function(){  
	$().evnine({
		debugToConsole                    :true,/*FirFox FireBug, Chrome, Opera*/
		debugToConsoleNotSupport          :false,
		debugPrefixString                 :'	',/*FirFox FireBug, Chrome, Opera*/
		liveSelectorForAJAX               :'a[class!=json][class!=body], input:submit',
		liveBindType                      :'click',
		crossBrowserMinimumVersion        :{
			msie   :'8'
			//chrome :'6',
			//safari :'4',
			//opera  :'9',
			//mozilla:'1'
		},
		folowByChangeOfHistory            : true,
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
		functionsForAJAXIndicator         : function($debugToConsole){
			this.On=function(){
				if ($debugToConsole) console.warn("#this.On ");
			};
			this.Off=function(){
				if ($debugToConsole) console.warn("#this.Off ");
			};
		},
		loadAJAXOptions                   :{
			dataType:'html'
		}
	});
	//$('#clicked').click();
//$().evnine({
		//debugToConsole                     :true,/*FirFox FireBug, Chrome, Opera*/
		//liveSelectorForAJAX               :'.json',
		///*Если аякс режим добавить параметр в ссылку*/
		//ifAJAXAddThisParamToScript       :'ajax=json',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//ifSEFAJAXReplaceHREFMatchTo    :'.json'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});
//
//$().evnine({
		//debugToConsole                     :true,/*FirFox FireBug, Chrome, Opera*/
		//liveSelectorForAJAX               :'.body',
		///*Если аякс режим добавить параметр в ссылку*/
		//ifAJAXAddThisParamToScript       :'ajax=body',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//ifSEFAJAXReplaceHREFMatchTo    :'.body'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});


});
//</script>
