//<script type="text/javascript">
$(document).ready(function()
{
//if (window.console) console.info("#include: ");
	//if ($('#test').is('*')){
		//$href=$('#test_href').attr('href')+'?test=true';
		//$('#test').html('<img src="tpl/i/ajax-loader.gif"/>');
		//$('#test').load($href);
	//}
	//});
	$key_object={
		'ctrl+alt+j':'head',
		'ctrl+alt+k':'1-tpl',
		'ctrl+alt+l':'1-array',
		'ctrl+alt+q':'1',
		'ctrl+alt+w':'2',
		'ctrl+alt+e':'3',
		'ctrl+alt+r':'4',
		'ctrl+alt+t':'5',
		'ctrl+alt+z':'6',
		'ctrl+alt+x':'7',
		'ctrl+alt+c':'8',
		'ctrl+alt+v':'9',
		'ctrl+alt+y':'10',
		'ctrl+alt+u':'11',
		'ctrl+alt+i':'12',
		'ctrl+alt+o':'13',
		'ctrl+alt+p':'14'
	};
	var $html='Shortcut keys: ';
	var $count=0;
	$.each($key_object, function($key,$anchore){
		if ($('a[name="'+$anchore+'"]').is('*')){
			var $sub_key=$key;
			var $sub_anchore=$anchore;
			$.keyboard($key,{event : 'keyup'},function () {
			window.location = '#'+$sub_anchore;
			});
			$html+='[ <b>'+$key+'</b>: '+$anchore+' ] ';
			$count++;
			if ($count%3===0) {
				$html+='<br />';
			}
		}
	});
	$('body').before($html);
});
//</script>
