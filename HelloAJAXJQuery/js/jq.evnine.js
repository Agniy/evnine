//<script type="text/javascript">
jQuery.fn.evnine = function($rewrite_options){
	//Flag for load AJAX
	/*Текущий статус загрузки AJAX*/
	var $ajax_is_load=false;
	var $loaded_href_hash_fix='';
	//Default setting
	//getTraceObject($rewrite_options);
		/* настройки по умолчанию*/
var $options = jQuery.extend({},$rewrite_options);

if ($rewrite_options!=undefined)
	$options.loadAJAXOptions = jQuery.extend({
		error: showResponseError,
		success: showResponse,
		dataType: 'html'
	},$rewrite_options.loadAJAXOptions);



//HELPER Debug

if (!window.console){
	$options.debugToConsole=false;
}

if ($options.debugToConsoleNotSupport){
	$options.debugPrefixString='';
	window.console=new Object();
	var debug_buffer='';
	window.console.warn=function($str){
		alert('INFO:'+debug_buffer+"\n\r"+'WARN:'+"\n\r\t"+$str);
		debug_buffer='';
	};
	window.console.info=function($str){
		debug_buffer+="\n\r"+$str;
	};
}

//if ($options.debugToConsole) console.warn("evnine->getTraceObject($options): ");
//if ($options.debugToConsole) getTraceObject($options,getTabByLevel(1));

function getTabByLevel($shift) {
	var $tab='';
	$i=0;
	while($i <= $shift) {
		$i++;
		$tab=$tab+$options.debugPrefixString;
	}
	return $tab;
}

/*Помощник для олтадки*/
function getTraceObject($obj,$tab){//Показываем свойства объектов
	var s = "";
	if ($tab===undefined) $tab='';
	for (prop in $obj)
	{
		if (typeof $obj[prop] != "function" && typeof $obj[prop] != "object")
		{
			s = $tab+"[" + prop + "] => " + $obj[prop] + "";
			if ($options.debugToConsole) console.info(s);
		}else if(typeof $obj[prop] === "object"){
			s = $tab+"[" + prop + "] => object (";
			if ($options.debugToConsole) console.info(s);
			getTraceObject($obj[prop],$tab+'                   ');
			s= $tab+")";
			if ($options.debugToConsole) console.info(s);
		}
	}
}


function getTraceFunction() {
  var callstack = [];
  var isCallstackPopulated = false;
  try {
    i.dont.exist+=0; //doesn't exist- that's the point
  } catch(e) {
      var lines = e.stack.split('\n');
    if (e.stack) { //Firefox
      for (i=0, len=lines.length; i<len; i++) {
				if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
					lines[i] = lines[i].replace(/@.*|(?=showResponse).*/,"");
          callstack.push(lines[i]);
        }
      }
      //Remove call to printStackTrace()
      callstack.shift();
      isCallstackPopulated = true;
    }
    else if (window.opera && e.message) { //Opera
      for (i=0, len=lines.length; i<len; i++) {
        if (lines[i].match(/^\s*[A-Za-z0-9\-_\$]+\(/)) {
          var entry = lines[i];
          //Append next line also since it has the file info
          if (lines[i+1]) {
            entry += ' at ' + lines[i+1];
            i++;
          }
          callstack.push(entry);
        }
      }
      //Remove call to printStackTrace()
      callstack.shift();
      isCallstackPopulated = true;
    }
  }
  getTraceObject(callstack);
}
/*END HELPER*/

	function getInt($int){//Функция получение числа
		return parseInt(parseFloat($int), '10');
	}
	
	$options.scriptNameForAJAX= $options.scriptForAJAXCallAndSetAnchore.replace(/^.*(\\|\/|\:)/, '');
	function isHasCrossBrowserMinimumVersion() {
		if ($options.debugToConsole) console.info(getTabByLevel(1)+"evnine::isHasCrossBrowserMinimumVersion()");
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

					if (window.console) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if (window.console) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(getTabByLevel(2)+"evnine::isHasCrossBrowserMinimumVersion return true");
					$return=true;
					return '';
				}else {
					if (window.console) console.warn("#getInt(jQuery.browser.version): "+getInt(jQuery.browser.version));
					if (window.console) console.warn("#$version: "+$version);
					if ($options.debugToConsole) console.info(getTabByLevel(2)+"evnine::isHasCrossBrowserMinimumVersion return false");
					$return=false;
					return '';
				}
			}
		});
		return $return;
	}
	$options.isAllowThisBrowser=isHasCrossBrowserMinimumVersion();
if (window.console) console.info(getTabByLevel(1)+"evnine::$options.isAllowThisBrowser="+$options.isAllowThisBrowser);

//For get safe AJAX URN
function getRegSafeString ($str) {
	return $str.replace(/(["'\.\-])(?:(?=(\\?))\2.)*?\1/g,"");
}

function getClickHref($that){
	if (jQuery($that).attr('href')!=undefined){
		$href = jQuery($that).attr('href');
		if ($options.debugToConsole) console.info(getTabByLevel(3)+"evnine::getClickHref $href: "+$href);
		$reg = new RegExp($options.isHREFMatchThisRegExpSetNoUseAJAX,"g");
		if ($href.match($reg)){
			if ($options.debugToConsole) console.info(getTabByLevel(4)+"evnine::getClickHref is no ajax href");
			if ($options.debugToConsole) console.info(getTabByLevel(4)+"evnine::getClickHref match $options.isHREFMatchThisRegExpSetNoUseAJAX");
			return true;
		}else {
			getAJAXHref($href);
			if ($options.debugToConsole) console.info(getTabByLevel(4)+"evnine::getClickHref is ajax href");
		}
	}else if (jQuery($that).attr('type')==='submit'){
		if ($options.debugToConsole) console.info(getTabByLevel(4)+"evnine::getClickHref is ajax submit");
		getAJAXSubmit($that);
	}
	return false;
}

function getAJAXSubmit($href) {
	if ($options.debugToConsole) console.warn(getTabByLevel(4)+"evnine::getAJAXSubmit");
	getAJAX('','href');
}

function getAJAXHref($href) {
	if ($options.debugToConsole) console.warn(getTabByLevel(4)+"evnine::getAJAXHref");
	getAJAX($href,'href');
}

function getURLWithFlag($href,$post_fix,$post_sef) {//Установить флаг в урл что запрос делаем аяском в .htaccess отловим потом
	if (window.console) console.warn(getTabByLevel(7)+"evnine::getURLWithFlag() ");
	if ($href===''){
		return '?'+$post_fix;
	}
	$reg = new RegExp($options.isHREFMatchThisRegExpSetSEFMode,"g");
	if ($href.match($reg)){//IF SEF URN
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::getURLWithFlag match SEF ");
		return $href.replace($reg, $post_sef);
	}else if($href.match(/\?/)) {//IF not SEF URN and has param
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::getURLWithFlag match & ");
		return $href+'&'+$post_fix;
	}else {//If not SEF URN and not has param
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::getURLWithFlag match ? ");
		return $href+'?'+$post_fix;
	}
}


function showResponseError(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
	if ($options.debugToConsole) console.warn(getTabByLevel(5)+"evnine::showResponseError: ");
	if ($options.debugToConsole) console.info(getTabByLevel(6)+"evnine::showResponseError->responseText="+responseText);
	if ($options.debugToConsole) console.info(getTabByLevel(6)+"evnine::showResponseError->statusText="+statusText);
	$ajax_is_load = false;
	//return true;
}

function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
	if ($options.debugToConsole) console.info(getTabByLevel(5)+"evnine::showResponse");
	$ajax_is_load = false;
	jQuery($options.selectorForAJAXReplace).html(responseText);
}

function isURLBaseAJAX() {
	if (window.console) console.warn(getTabByLevel(7)+"evnine::isURLBaseAJAX()");
	if (location.search!==''){
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::isURLBaseAJAX retrun FALSE");
		return false;
	}
	//$options.scriptForAJAXCallAndSetAnchore
	if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::isURLBaseAJAX->=$options.scriptForAJAXCallAndSetAnchore="+$options.scriptForAJAXCallAndSetAnchore);
	getTraceObject(location,getTabByLevel(8));
	if (location.pathname===$options.scriptForAJAXCallAndSetAnchore){
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::isURLBaseAJAX retrun TRUE");
		return true;
	}else {
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::isURLBaseAJAX retrun FALSE");
		return false;
	}
	//getTraceObject(location,'						');
	//if ($options.debugToConsole) console.info(getTabByLevel(5)+getTabByLevel(3)+"evnine::isHasAnchore: ");
	//if (location.hash!==''){
		//return true;
	//}else {
		//return false; 
	//}
	//if ($options.is_axaj_is_sub_folder){
		//location.pathname
		//$current_url=document.URL.replace(location.protocol+'//'+window.location.host+'/',"");
		
		//$current_url=document.URL.replace(location.protocol+'//'+window.location.host+'/',"");
		//getTraceObject(document,'						');
		//if (window.console) console.info("#$current_url.*: "+$current_url);
		//if (window.console) console.info("#$current_url.document.URL: "+$current_url);
		//$current_url=$current_url.replace(/^#!.*/,"");
//
		//if (window.console) console.info("#$current_url/^#!.*/: "+$current_url);
		////getTraceObject(document);
		//if (window.console) console.info("#$options.is_axaj_is_sub_folder: "+$options.is_axaj_is_sub_folder);
	//}else {
		//if (window.console) console.info("#$options.is_axaj_is_sub_folder: "+$options.is_axaj_is_sub_folder);
	//}
	//if (window.console) console.info("#location.hash: "+location.hash);
	//if (location.hash!==''){//Если есть хэш урл
		//return true;
	//}
//
	//if (window.console) console.info("#document.URL: "+document.URL);
//
	//if (window.console) console.info("#location.protocol+'//'+window.location.host+'/': "+location.protocol+'//'+window.location.host+'/');
	//if (document.URL===location.protocol+'//'+window.location.host+'/'){
		//return true;
	//}
	//$current_url=document.URL.replace(location.protocol+'//'+window.location.host+'/',"");
//
	//if (window.console) console.info("#$current_url: "+$current_url);
	//if ($current_url===''){//Если текущий УРЛ не главная страница
		//return true;
	//}else {
		//$current_url=$current_url.replace(/^#!.*/,"");
		//if (window.console) console.info("#$current_url: "+$current_url);
		//if ($current_url===''){//Проверим есть ли остаток якоря с прошлой страницы
			//return true;
		//}
		//if (window.console) console.info("#$current_url: "+$current_url);
		//return false;
	//}
}

function setURLToBrowser($base,$href) {
	$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore+'|\\?');
	$href=$href.replace($reg,'');
	if ($options.isAllowThisBrowser){
		window.location = $base+'#'+$options.ancorePreFix+$href;
	}else {
		window.location = $base+$href;
	}
}

//Set URL as anchore
/*Установить адрес в историю просмотра*/
function setURLToHashAndLocation($href,$reset) {
	if ($options.debugToConsole) console.warn(getTabByLevel(7)+"evnine::setURLToHashAndLocation($href="+$href+")");
	if ($href==='/') {
		//window.location= $options.scriptForAJAXCallAndSetAnchore+'#!';
		$loaded_href_hash_fix= '';
		window.location.hash = $.URLDecode($options.ancorePreFix);
		if ($options.debugToConsole) document.title = '';
		return $options.scriptForAJAXCallAndSetAnchore;
	}else {
		$reg = new RegExp('^'+$options.scriptNameForAJAX+'|^'+$options.scriptForAJAXCallAndSetAnchore);
		$loaded_href_hash_fix=$hash_href_without_script_name=$href.replace($reg,'');
		if ($options.debugToConsole) console.info(getTabByLevel(8)+"evnine::setURLToHashAndLocation->hash_href_without_script_name="+$hash_href_without_script_name);
		window.location.hash = $.URLDecode($options.ancorePreFix+$hash_href_without_script_name);
		if ($options.debugToConsole) document.title = $href;
		return $href;
	}
}


//return {getAJAX: this.getAJAX};

function getAJAX ($href,$type,$id,$data){//Функция - отправка данныx через jQuery plugin form в include.js
	if ($options.debugToConsole) console.warn(getTabByLevel(5)+"evnine::getAJAX()");
	//if ($options.debugToConsole) console.info("#getAJAXSubmit: ");
	//if ($options.debugToConsole) console.info("#href: "+$href);
	//if ($options.debugToConsole) console.info("#type: "+type);
	//if ($options.debugToConsole) console.info("#$id: "+$id);
	//if ($options.debugToConsole) console.info("#$ajax_is_load: "+$ajax_is_load);
	//$url_with_ajax = getURLWithFlag($href);
	//if ($options.debugToConsole) console.info("#$url_with_ajax: "+$url_with_ajax);
	$id='body';
	$ajax_run=true;
	if (!$ajax_is_load){
		//TODO re comment
		//$ajax_is_load = true;
		//$respond = getTypeResponseForHREF(href);
		//if (window.console) console.info("#$respond: "+$respond);
		//$ajax_options = jQuery.extend({
			//url:getURLWithFlag($href)
		//},$options.loadAJAXOptions);
		//$options.loadAJAXOptions.success=showResponse;
		//$options.loadAJAXOptions.error=showResponseError;
		if ($options.debugToConsole) console.info(getTabByLevel(6)+"evnine::getAJAX->$options.loadAJAXOptions: ");
		if ($options.debugToConsole) getTraceObject($options.loadAJAXOptions,getTabByLevel(6));
		//$ajax_options.url=getURLWithFlag($href);
		//if (window.console) console.info("#type: "+type);
		if ($type==='submit'&&$ajax_run===true){
			$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$option.ifSEFAJAXReplaceHREFMatchTo);
			try{
				$load_href= $href;
				//if (window.console) console.info("#$load_href: "+$load_href);
				//$('#'+$id).ajaxSubmit($options.loadAJAXOptions);
			}catch($bug){
				$load_href= '';
				//$ajax_is_loader.hide();
				$ajax_is_load = false;
				//getAJAXSubmit($href,$type,$id);
			}
		}else if($ajax_run===true) {
			//$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.);
			//$href = setURLToHashAndLocation($href);
			if ($options.debugToConsole)   console.info(getTabByLevel(6)+"evnine::getAJAX->loadAJAXOptions.url="+$options.loadAJAXOptions.url);
			if (isURLBaseAJAX()){
				$options.loadAJAXOptions.url=getURLWithFlag(setURLToHashAndLocation($href),$options.ifAJAXAddThisParamToScript,$options.ifSEFAJAXReplaceHREFMatchTo);
				//setURLToHashAndLocation($href);
				try{
					//$load_href= $href;
					//if (window.console) console.info(getTabByLevel(3)+"#$.ajax: "+$.ajax);
					$.ajax($options.loadAJAXOptions);
				}catch($bug){
					if (window.console)        console.info(getTabByLevel(6)+"evnine::getAJAX->$bug="+$bug);
					//$load_href= '';
					////$ajax_is_loader.hide();
					//$ajax_is_load = false;
					//getAJAXSubmit($href,$type);
				}
				//$loaded_href_hash_fix=$href;
			}
			else {
				setURLToBrowser($options.scriptForAJAXCallAndSetAnchore,$href);
				//window.location=$options.scriptForAJAXCallAndSetAnchore+$href;
				//if (window.console) console.info(getTabByLevel(2)+"#setURLToHashAndLocation: "+setURLToHashAndLocation);
			}
		}
	}
}

if ($options.folowByChangeOfHistory){
	function getHash(loc){
		loc = loc.toString();
		if (loc.indexOf("#") != -1){
			return loc.substring(loc.indexOf('#'));
		} else {
			return "";
		}
	}
	function setAnchoreClearWithoutURL (){//Установить новую страницу если в ссылке есть аякс вызов ссылка#вызов
		if ($options.debugToConsole) console.warn(getTabByLevel(1)+"evnine::setAnchoreClearWithoutURL()");
		$href=getHash(location);
		$reg=new RegExp('^#'+$options.ancorePreFix,"g");
		$href= $href.replace($reg,"");
		if (window.console) console.info(getTabByLevel(2)+"evnine::setAnchoreClearWithoutURL->$href_after_replace="+$href);
		if ($href){
			if ($options.debugToConsole) console.info(getTabByLevel(2)+"evnine::setAnchoreClearWithoutURL->$href: "+$href);
			//$reload_page=true;
			//$('#body').html();
				if (window.console) console.info(getTabByLevel(2)+"evnine::setAnchoreClearWithoutURL->isURLBaseAJAX return true");
				//if ($options.isAllowThisBrowser){
					getAJAX($options.scriptForAJAXCallAndSetAnchore+$href,'href');
				//}else {
					//window.location=$options.scriptForAJAXCallAndSetAnchore+$href;
				//}
				//else {
					//window.location=$options.scriptForAJAXCallAndSetAnchore+$href;
				//}
		}
	}
	setAnchoreClearWithoutURL();//if first load href with AJAX anchor #!axax=ajax
	if ($options.isAllowThisBrowser){
		jQuery(window).trigger('hashchange');
		jQuery(window).bind( 'hashchange', function(){
				if ($options.debugToConsole) console.warn(getTabByLevel(0)+"evnine::hashchange()");
				if (window.location.hash){ 
					$reg=new RegExp('^#'+$options.ancorePreFix,"g");
					$hash = location.hash.replace($reg,"");
					if ($loaded_href_hash_fix!==$hash){
						if ($options.debugToConsole) console.info(getTabByLevel(1)+"evnine::hashchange=YES");
						if (window.console) console.info(getTabByLevel(1)+"|"+$hash + "|!=|"+$loaded_href_hash_fix+"|");
						getAJAX($hash,'href');
					} else {
					if ($options.debugToConsole) console.info(getTabByLevel(1)+"evnine::hashchange=NO");
					}
				}
		});
	}
}
if ($options.debugToConsoleNotSupport){
	if (window.console) console.warn("END");
}

if ($options.isAllowThisBrowser){
	return jQuery($options.liveSelectorForAJAX).live($options.liveBindType, function() {
			return getClickHref(this);
	});
}
};
//</script>
