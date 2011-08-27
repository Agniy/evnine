en: SEF for controller. An example of adding a variable in the template.
ru: ЧПУ для контроллера. пример добавление переменной в шаблоне.

>> $output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].'insert_by_template'.$output['inURL']['default']['post'];
<< /helloworld/default/test_id=insert_by_template/index.html

/controllers/ControllersHelloWorld.php
	$this->controller = array(
		'inURLSEF'=> true,
	...
	)

/.htaccess
	DirectoryIndex index.php index.php
	
	<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteRule .* - [L]
	RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
	</IfModule>	

