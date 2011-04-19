//<script type="text/javascript">
(function($) { 
jQuery.fn.evnine = function($rewrite_options){
	//Flag for load AJAX
	/*Текущий статус загрузки AJAX*/
	$ajax_is_load=false;
	//Default setting
	//getTraceObject($rewrite_options);
		/* настройки по умолчанию*/
var $options = jQuery.extend({
		live_bind_type											 :'click',
		debug_to_console                     :true,/*FirFox FireBug, Chrome, Opera*/
		live_selector_for_ajax               :'a, input:submit',
		//is ajax mode work in the folder?
		/*Если аякс должен работать в папке*/
		urn_for_ajax_call_and_set_anchore    :'/evnine/HelloAJAXJQuery/',
		ajax_indicator                       :'',
		ajax_data_type                       :'html',
		/*Ссылка совпадает с этим регулярным то не использовать аякс*/
		is_href_match_this_regexp_set_no_ajax:'^http://|^hop',//=$href.match(/^http://|^hop/g)
		/*Если аякс режим добавить параметр в ссылку*/
		if_ajax_add_this_param_to_href       :'ajax=ajax',//=index.php?ajax=ajax
		/*Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
		is_href_match_this_regexp_set_sef    :'.html$',//=$href.match(/\.html/g)
		/*Если аякс работает в ЧПУ режиме, заменим адрес на */
		if_sef_ajax_replace_href_match_to    :'.ajax'//=$href.replace(/\.html$/g, '.ajax')
		//Example: index.html => index.ajax
		//ajax_options_rewrite:{
			//error: showResponseError,
			//success: showResponse,
			//dataType: 'html'
		//}
},$rewrite_options);

if ($rewrite_options!=undefined)
	$options.ajax_options_rewrite = jQuery.extend({
		error: showResponseError,
		success: showResponse,
		dataType: 'html'
	},$rewrite_options.ajax_options_rewrite);

if (window.console&&$options.debug_to_console) console.warn("evnine::getTraceObject($options): ");
//if (window.console&&$options.debug_to_console) getTraceObject($options);

if (window.console) console.dir($options['ajax_options_rewrite']);
//HELPER Debug
/*Помощник для олтадки*/
function getTraceObject($obj,$tab){//Показываем свойства объектов
	var s = "";
	if ($tab===undefined) $tab='';
	for (prop in $obj)
	{
		if (typeof $obj[prop] != "function" && typeof $obj[prop] != "object")
		{
			s = $tab+"[" + prop + "] => " + $obj[prop] + "";
			if (window.console&&$options.debug_to_console) console.info(s);
		}else if(typeof $obj[prop] === "object"){
			s = $tab+"[" + prop + "] => object (";
			if (window.console&&$options.debug_to_console) console.info(s);
			getTraceObject($obj[prop],$tab+'                   ');
			s= $tab+")";
			if (window.console&&$options.debug_to_console) console.info(s);
		}
	}
}

//For get safe AJAX URN
function getRegSafeString ($str) {
	return $str.replace(/(["'\.\-])(?:(?=(\\?))\2.)*?\1/g,"");
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

function getClickHref($that){
	if (jQuery($that).attr('href')!=undefined){
		$href = jQuery($that).attr('href');
		if (window.console&&$options.debug_to_console) console.warn("		evnine::getClickHref $href: "+$href);
		$reg = new RegExp($options.is_href_match_this_regexp_set_no_ajax,"g");
		if ($href.match($reg)){
			if (window.console&&$options.debug_to_console) console.warn("		evnine::getClickHref is no ajax href");
			if (window.console&&$options.debug_to_console) console.warn("		evnine::getClickHref match $options.is_href_match_this_regexp_set_no_ajax");
			return true;
		}else {
			getAJAXHref($href);
			if (window.console&&$options.debug_to_console) console.info("		evnine::getClickHref is ajax href");
		}
	}else if (jQuery($that).attr('type')==='submit'){
		if (window.console&&$options.debug_to_console) console.info("		evnine::getClickHref is ajax submit");
		getAJAXSubmit($that);
	}
	return false;
}

function getAJAXSubmit($href) {
	if (window.console&&$options.debug_to_console) console.info("			evnine::getAJAXSubmit");
	getAJAX('','href');
}

function getAJAXHref($href) {
	if (window.console&&$options.debug_to_console) console.info("			evnine::getAJAXHref");
	getAJAX($href,'href');
}

function getURLWithAJAXFlag($href) {//Установить флаг в урл что запрос делаем аяском в .htaccess отловим потом
	if ($href===''){
		return '?'+$options.if_ajax_add_this_param_to_href;
	}
	$reg = new RegExp($options.is_href_match_this_regexp_set_sef,"g");
	if ($href.match($reg)){//IF SEF URN
		if (window.console&&$options.debug_to_console) console.warn("			#match sef: ");
		return $href.replace($reg, $options.if_sef_ajax_replace_href_match_to);
	}else if($href.match(/\?/)) {//IF not SEF URN and has param
		if (window.console&&$options.debug_to_console) console.warn("			#match &: ");
		return $href+'&'+$options.if_ajax_add_this_param_to_href;
	}else {//If not SEF URN and not has param
		if (window.console&&$options.debug_to_console) console.warn("			#match ?: ");
		return $href+'?'+$options.if_ajax_add_this_param_to_href;
	}
}


function showResponseError(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
	if (window.console) console.info("				evnine::showResponseError: ");
	$ajax_is_load = false;
	//return true;
}

function showResponse(responseText, statusText){//Функций - Показать AJAX ответ в случае ошибке
	if (window.console) console.info("				evnine::showResponse");
	$ajax_is_load = false;
	$($options.selector_for_ajax_replace).html(responseText);
}

function isURLBaseAJAX() {
	//$options.urn_for_ajax_call_and_set_anchore
	if (window.console) console.warn("					#$options.urn_for_ajax_call_and_set_anchore: "+$options.urn_for_ajax_call_and_set_anchore);
	if (location.pathname===$options.urn_for_ajax_call_and_set_anchore){
		return true;
	}else {
		return false;
	}
	getTraceObject(location,'						');
	//if (window.console&&$options.debug_to_console) console.warn("						evnine::isHasAnchore: ");
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
		//if (window.console) console.warn("#$current_url.*: "+$current_url);
		//if (window.console) console.warn("#$current_url.document.URL: "+$current_url);
		//$current_url=$current_url.replace(/^#!.*/,"");
//
		//if (window.console) console.warn("#$current_url/^#!.*/: "+$current_url);
		////getTraceObject(document);
		//if (window.console) console.warn("#$options.is_axaj_is_sub_folder: "+$options.is_axaj_is_sub_folder);
	//}else {
		//if (window.console) console.warn("#$options.is_axaj_is_sub_folder: "+$options.is_axaj_is_sub_folder);
	//}
	//if (window.console) console.warn("#location.hash: "+location.hash);
	//if (location.hash!==''){//Если есть хэш урл
		//return true;
	//}
//
	//if (window.console) console.warn("#document.URL: "+document.URL);
//
	//if (window.console) console.warn("#location.protocol+'//'+window.location.host+'/': "+location.protocol+'//'+window.location.host+'/');
	//if (document.URL===location.protocol+'//'+window.location.host+'/'){
		//return true;
	//}
	//$current_url=document.URL.replace(location.protocol+'//'+window.location.host+'/',"");
//
	//if (window.console) console.warn("#$current_url: "+$current_url);
	//if ($current_url===''){//Если текущий УРЛ не главная страница
		//return true;
	//}else {
		//$current_url=$current_url.replace(/^#!.*/,"");
		//if (window.console) console.warn("#$current_url: "+$current_url);
		//if ($current_url===''){//Проверим есть ли остаток якоря с прошлой страницы
			//return true;
		//}
		//if (window.console) console.warn("#$current_url: "+$current_url);
		//return false;
	//}
}


//Set URL as anchore
/*Установить адрес в историю просмотра*/
function setURLToHashAndLocation($href) {
	if (window.console&&$options.debug_to_console) console.warn("					#setURLToHashAndLocation: href ["+$href+']');
	if ($href==='/') {
		window.location= $options.urn_for_ajax_call_and_set_anchore+'#!';
	}else {
		window.location = $options.urn_for_ajax_call_and_set_anchore+'#!'+$href;
	}
}


return jQuery($options.live_selector_for_ajax).live($options.live_bind_type, function() {
		return getClickHref(this);
});

//return {getAJAX: this.getAJAX};


};

var getAJAX = function ($href,$type,$id,$data){//Функция - отправка данныx через jQuery plugin form в include.js
	if (window.console&&$options.debug_to_console) console.info("				evnine::getAJAX");
	//if (window.console&&$options.debug_to_console) console.warn("#getAJAXSubmit: ");
	//if (window.console&&$options.debug_to_console) console.warn("#href: "+$href);
	//if (window.console&&$options.debug_to_console) console.warn("#type: "+type);
	//if (window.console&&$options.debug_to_console) console.warn("#$id: "+$id);
	//if (window.console&&$options.debug_to_console) console.warn("#$ajax_is_load: "+$ajax_is_load);
	//$url_with_ajax = getURLWithAJAXFlag($href);
	//if (window.console&&$options.debug_to_console) console.warn("#$url_with_ajax: "+$url_with_ajax);
	$id='body';
	$ajax_run=true;
	if (!$ajax_is_load){
		//TODO re comment
		//$ajax_is_load = true;
		//$respond = getTypeResponseForHREF(href);
		//if (window.console) console.warn("#$respond: "+$respond);
		//$ajax_options = jQuery.extend({
			//url:getURLWithAJAXFlag($href)
		//},$options.ajax_options_rewrite);
		$options.ajax_options_rewrite.url=getURLWithAJAXFlag($href);
		//$options.ajax_options_rewrite.success=showResponse;
		//$options.ajax_options_rewrite.error=showResponseError;
		if (window.console&&$options.debug_to_console) console.warn("				#$options.ajax_options_rewrite: ");
		if (window.console&&$options.debug_to_console) getTraceObject($options.ajax_options_rewrite,'					');
		//$ajax_options.url=getURLWithAJAXFlag($href);
		//if (window.console) console.warn("#type: "+type);
		if ($type==='submit'&&$ajax_run===true){
			try{
				$load_href= $href;
				//if (window.console) console.warn("#$load_href: "+$load_href);
				//$('#'+$id).ajaxSubmit($options.ajax_options_rewrite);
			}catch($bug){
				$load_href= '';
				//$ajax_is_loader.hide();
				$ajax_is_load = false;
				//getAJAXSubmit($href,$type,$id);
			}
		}else if($ajax_run===true) {
			setURLToHashAndLocation($href);
			if (isURLBaseAJAX()){
				if (window.console&&$options.debug_to_console) console.warn("					isHasAnchore:TRUE");
				//setURLToHashAndLocation($href);
				try{
					//$load_href= $href;
					//if (window.console) console.warn("		#$.ajax: "+$.ajax);
					$.ajax(this.$options.ajax_options_rewrite);
				}catch($bug){
					if (window.console) console.warn("		evnine::CATCH #bug: "+$bug);
					//$load_href= '';
					////$ajax_is_loader.hide();
					//$ajax_is_load = false;
					//getAJAXSubmit($href,$type);
				}
			}else {
				if (window.console&&$options.debug_to_console) console.warn("					isHasAnchore:FALSE");
			}
			//else {
				//if (window.console) console.warn("	#setURLToHashAndLocation: "+setURLToHashAndLocation);
			//}
		}
	}
};


})(jQuery);
//var $init_hash_flag=true;
//TODO AFRER DEBUG
var $init_hash_flag=false;
//Когда все эл-ты страницы загружены выполним привязки АЙДИ к действиям
$(document).ready(function(){
	if (!$init_hash_flag){
	$init_hash_flag=true;
		//Set back button to work
		/*Для того что бы отслеживать когда юзер нажал кнопку назад или выбрал адрес из истории*/
	$(window).trigger('hashchange');
	//Load href if hash was changed
	/*При изменения адреса хэша подгрузим страницу из якоря адреса*/
	//var evnine_prev_hash_save='';
	$(window).bind( 'hashchange', function(){
			if (window.location.hash){ 
				if (window.console) console.info("				hashchange YES");
				$hash = location.hash.replace(/#!/,"");
				if (window.console) console.warn("				#getAJAXHref: "+$hash);
				jQuery().evnine().getAJAX($hash,'href');
					//.getAJAX(location.hash,'href');
				//getAJAXHref($hash,'href');
			} else {
				if (window.console) console.info("				hashchange NO");
			}
	});
	}
});
//}
//</script>
//http://stackoverflow.com/questions/1042072/how-to-call-functions-that-are-nested-inside-a-jquery-plugin
