//<script type="text/javascript">
jQuery(document).ready(function(){  
	$().evnine({
		debug_to_console                     :true,/*FirFox FireBug, Chrome, Opera*/
		live_selector_for_ajax               :'a[class!=json][class!=body], input:submit',
		live_bind_type											 :'click',
		selector_for_ajax_replace            :'body', //body, 
		/*Адрес относительно которого производить ajax вызовы*/
		urn_for_ajax_call_and_set_anchore    :'/HelloAJAXJQuery/index.php',
		/*Ссылка совпадает с этим регулярным то не использовать аякс*/
		is_href_match_this_regexp_set_no_ajax:'^http://|^hop',//=$href.match(/^http://|^hop/g)
		/*Если аякс режим добавить параметр в ссылку*/
		if_ajax_add_this_param_to_href       :'ajax=ajax',//=index.php?ajax=ajax
		/*Ссылка совпадает с регулярным, работаем аякс в ЧПУ режиме*/
		is_href_match_this_regexp_set_sef    :'.html$',//=$href.match(/\.html/g)
		/*Если аякс работает в ЧПУ режиме, заменим адрес на */
		if_sef_ajax_replace_href_match_to    :'.ajax',//=$href.replace(/\.html$/g, '.ajax')
		//Example: index.html => index.ajax
		ajax_options_rewrite:{
			dataType:'html'
		}
	});
	//$('#clicked').click();
//$().evnine({
		//debug_to_console                     :true,/*FirFox FireBug, Chrome, Opera*/
		//live_selector_for_ajax               :'.json',
		///*Если аякс режим добавить параметр в ссылку*/
		//if_ajax_add_this_param_to_href       :'ajax=json',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//if_sef_ajax_replace_href_match_to    :'.json'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});
//
//$().evnine({
		//debug_to_console                     :true,/*FirFox FireBug, Chrome, Opera*/
		//live_selector_for_ajax               :'.body',
		///*Если аякс режим добавить параметр в ссылку*/
		//if_ajax_add_this_param_to_href       :'ajax=body',//=index.php?ajax=ajax
			///*Если аякс работает в ЧПУ режиме, заменим адрес на */
		//if_sef_ajax_replace_href_match_to    :'.body'//=$href.replace(/\.html$/g, '.ajax')
		////Example: index.html => index.json
//});


});
//</script>
