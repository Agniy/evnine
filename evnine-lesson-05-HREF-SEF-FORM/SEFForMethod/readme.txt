en: SEF for method. An example input data to an URI.
ru: ЧПУ для метода. Пример передачи в ссылке данных со входа.

>> $output['inURL']['default']['pre'].$output['inURL']['default']['TestID'].$output['REQUEST_OUT']['TestID'].$output['inURL']['default']['post']
<< /helloworld/default/hello-world-777.html

/controllers/ControllersHelloWorld.php
	$this->controller = array(
		'inURLSEF'=> false,
		'default'=>array(
			'inURLSEF' => array(
				'hello','world','test_id' => '','.' => '.html',
			)
	)

/.htaccess
	DirectoryIndex index.php index.php
	
	<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteRule .* - [L]
	RewriteRule ^(.*).(body|ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
	</IfModule>	