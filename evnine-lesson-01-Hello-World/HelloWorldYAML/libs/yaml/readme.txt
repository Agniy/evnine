Fix for PHPUnitTest

/sfYamlParser.php
if (!class_exists('sfYamlInline')){
	require_once(dirname(__FILE__).'/sfYamlInline.php');
}

/sfYamlInline.php
if (!class_exists('sfYaml')){
	require_once dirname(__FILE__).'/sfYaml.php';
}
