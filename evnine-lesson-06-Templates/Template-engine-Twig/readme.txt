en: How to work with templating Twig. twig-project.org
ru: Пример работы с шаблонизатором Twig. twig.kron0s.com

/index.php
require_once 'Twig/Autoloader.php';
Twig_Autoloader::register();
	
$loader = new Twig_Loader_Filesystem('views/');
$twig = new Twig_Environment($loader, array(
			'cache' => 'views/cache',
			'auto_reload' => true,
			'charset' => 'UTF-8',
			'debug' => true,
			'trim_blocks'=>false,
		)
);

echo $twig->loadTemplate('form.tpl')->render($output);
