<?php
/**
 * An example of the controller with comments and options settings.
 * 
 * The controller name must coincide with the class name and file name. 
 * 
 * class ControllersExample == ControllersExample.php
 * /controllers/ControllersExample.php
 * 
 * Determined in the configuration file:
 * /evnine.config.php
 *	$this->controller_alias=array(
 *		'example'=>'ControllersExample'
 *	);
 * 
 * The alternative with the path.
 * /evnine.config.php
 *	'helloworld'=>array(
 *	'class_name'=>'ControllersHelloWorld',
 *		'path'=>'controllers'.DIRECTORY_SEPARATOR,
 *	)
 *
 * @package Controllers
 * @author ev9eniy.info
 * @version 2
 * @updated 31.08.2011
 */
class ControllersExample
{
	/**
	 * An alternative method of determining the controller via YAML
	 *
	 * if (!class_exists('sfYamlParser')){
	 *	// /libs/ymal/sfYamlParser.php
	 *	 require_once(dirname(__FILE__).'/../libs/yaml/sfYamlParser.php');
	 * }
	 * class ControllersHelloWorld extends sfYamlParser 
	 * {
	 *	var $controller;
	 *	function __construct(){
	 *	$this->controller=$this->parse(
	 * <<<YAML
	 * public_methods:
	 *  default: 
	 *   ModelsHelloWorld: getHelloWorld
	 * YAML
	 *	);
	 * } 
	 * @var array
	 * 
	 * Array controller.
	 * @access public
	 * @link http://components.symfony-project.org/yaml/installation
	 */
	var $controller;

	/**
	 * The class constructor is initialized with the level of access.
	 * 
	 * @param array $access_level 
	 *  
	 * The level of access set to:
	 * /evnine.config.php
	 * $this->access_level=array(
	 *  'admin'=>'2',
	 *  'user'=>'1',
	 *  'guest'=>'0',
	 * );
	 * 
	 * Controller to initialize:
	 * /evnine.php
	 * new $controller($this->access_level);
	 * 
	 * @link EvnineConfig.access_level
	 * @see EvnineController.setLoadController
	 * @access protected
	 * @return void
	 */
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				/**
				 * PHPUnit Test. 
				 * Case param for all the methods of the controller.
				 * How do the tests?
				 * 
				 * Case when testing the first time:
				 * 1. ModelsPHPUnit reads data from the controller parameters inURLUnitTest.
				 * 2. Makes a request with these parameters through EvnineController
				 * 3. Saves the current state in test/PHPUnitCache on md5 key parameters.
				 * 4. Returns true, because checking the first time.
				 * 
				 * When the test is already exist
				 * 1. ModelsPHPUnit reads data from the controller parameters on the test.
				 * 2. Makes a request with these parameters through EvninController we get the results "after".
				 * 3. Fetches the "before" in the folder test/PHPUnitCache on md5 key parameters.
				 * 4. Compares the results "before" and "after".
				 * 
				 * Path to the tests can also be set to:
				 * /evnine.config.php
				 * $this->param_const=array(
				 *  // Folder to store the PHPUnit tests.
				 *	'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
				 *  // Folder to store temporary data.
				 *	 CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
				 *  // A shared folder for the cache.
				 *	'CacheDir'=>'PHPUnitCache',
				 *);
				 * 
				 * 
				 */
				'REQUEST' => array(
					array('param' => 'first test'),
					array('param' => 'second test')
				),
				/**
				 * The parameters are generated cases.
				 * 
				 * >>'REQUEST' => array(
				 *  array('param' => 'first test'),
				 *  array('param' => 'second test')
				 * )
				 * 
				 * <<
				 * '0' => array(
				 *  'REQUEST' => array('param' => 'first test'),
				 * ),
				 * '1' => array(
				 *  'REQUEST' => array('param' => 'second test'),
				 * ),
				 *
				 */
			),
			'this'=> 'example', 
			/**
			 * For convenience, the name of the controller.
			 * 
			 * /evnine.config.php
			 * $this->controller_alias=array(
			 *  'example'=>'ControllersExample',
			 *);
			 */
			'page_level'=>'',
			/**
			 * The depth of the tree on the controller call. Need to work without AJAX.
			 * Default: 0
			 */
			'parent'=> '',
			/**
			 * Parent controller for a call without AJAX.
			 * {
			 *  parent_level=0 
			 *  parent=''
			 *  this=parent
			 * }
			 *   {
			 *    parent_level=1 
			 *    parent=parent
			 *    this=parent-child
			 *   }
			 *     {
			 *       parent_level=2
			 *       parent=parent-child
			 *       this=parent-child-child
			 *     }
			 */
			'inURLView' => 'example.tpl',
			/**
			 * The template for controller.
			 */
			'title'=> '',
			/**
			 * Title for the template.
			 */
			'inURLSEF'=> false,
			/**
			 * SEF mode in the controller.
			 * If the SEF mode for the controller, the ending URN - index.html
			 * Default: false
			 * 
			 * >>'inURLSEF'=> true
			 * <</controller/method/param=value/param=value/
			 */
			'access'=>array(
			/**
			 * The level of access to the controller, as well as to the method of access checks. 
			 */
				'default_access_level' => $access_level['guest'],
				/**
				 * For the level of access to model $param['PermissionLevel'].
				 * 
				 * /evnine.config.php
				 * $this->access_level=array(
				 *	'admin'=>'2',
				 *	'user'=>'1',
				 *	'guest'=>'0',
				 * );
				 */
				'default_private_methods' => 'isHasAccess',
				/**
				 * In the case where the level does not coincide with minimal.
				 * Run the test method of access. 
				 * For example, based on the session or cookie.
				 * The validation method must change $param['PermissionLevel']
				 * The validation method must be private_methods.
				 * 
				 * 'private_methods' => array(
				 *  'isHasAccess'=>array(
				 *   'ModelsValidation'=>'isValidCookie'
				 *  )
				 * ),
				 */
				'ModelsValidation::isValidCookie'=>array('access_level'=>$access_level['guest']),
				/**
				 * Minimum level of access to the call method for check.
				 * Without specifying, the method allowed to run for safety.
				 */
			),
			'templates' => array(
			/**
			 * Access to the mapping of the template.
			 * Depends on the user's access.
			 * 
			 * /evnine.config.php
			 * $this->access_level=array(
			 *  'admin'=>'2',
			 *  'user'=>'1',
			 *  'guest'=>'0',
			 * );
			 *
			 * >>$access_level='user'
			 * 
			 * <<array(
			 *  'menu'=>'user_menu'
			 *  'menu'=>'guest_menu'
			 * )
			 */
				$access_level['admin']=>array('menu'=>'admin_menu'),
				$access_level['user']=>array('menu'=>'user_menu'),
				$access_level['guest']=>array('menu'=>'guest_menu')
			),

			#BEGIN private_methods
			'private_methods' => array(
			/**
			 * Private methods are denied access to them from outside.
			 * Run out of public functions.
			 */
				'isHasAccess'=>array(
				/**
				 * Check access.
				 */
					'ModelsValidation'  => 
						'isValidCookie'
				),
						/**
						 * We use cookies to check the model validation.
						 */
						'isValidCookie_false'  => array(
						/**
						 * The case when validation fails.
						 */
							'ModelsErrors'=>'getError->no_auth'
							/**
							 * If you do not have access. Execute method with the error.
							 */
						),
				'isHasAccess_false' => array(
				/**
				 * If you do not have access.
				 */
					'ModelsErrors'=>'getError',
					/**
					 * Display an error.
					 */
				),
				'isHasAccess_true' => array(
				/**
				 * If you have access.
				 */
					'ModelsInfo'=>array(
					/**
					 * In the model of information verification method with the parameter OK.
					 */
						'getInfo->ok',
					)
				),
				'isValidModifierParamFormError_false' => array(
				/**
				 * In the case of validation errors.
				 */
					'ModelsErrors'=>'getError',
					/**
					 * Running a method of displaying an error.
					 */
				),
				'isValidModifierParamFormError_true' => array(
				/**
				 * If validation is successful.
				 */
				)
			),
			#END private_methods

			#BEGIN public_methods
			'public_methods' => array(
			/**
			 * Public methods are available to all users.
			 * Example call /c=controller&m=default
			 */

				#BEGIN default method
				'default'=>array(
				/**
				 * The default method is always performed when not set.
				 */
					'controller' => 'method',
					/**
					 * Calling an external controller with this method.
					 */
					'this' => 'some_method',
					/**
					 * Point to a query from the current controller is the some_method.
					 */
					'inURLMethod' => array(
					/**
					 * An array of method for generating URI
					 * In the template available for $result['ViewMethod']['default']
					 * 
					 * inURLMethod - override the default method references
					 * inURLMethod_add - add links to the default method.
					 */
						'default',
						/**
						 * The list of methods in the controller to generate links.
						 * In order to put a link to the template method.
						 * 
						 * PHP:  $result[inURL][default][pre].$result[inURL][default][post]
						 * TWIG: inURL.default.pre inURL.default.post
						 *
						 * pre - The basic part of URN
						 * post - SEF part
						 */
					),
					'inURLTemplate' => array(
					/**
					 * Array for permanent links in the template on different methods.
					 */
						'info' => 'default',
						/**
						 * Link $result[inURLTemplate][info] to the default method
						 */
						'info' => 'some_method',
						/**
						 * Link $result[inURLTemplate][info] to the some_method method
						 */
						'error' => 'default',
						/**
						 * A reference to a method of displaying an error.
						 */
					),
					'inURLView' => 'ajax.php',
					/**
					 * A reference to a template for the method.
					 */

					#BEGIN default validation
					'validation'=>array(
						/**
						 * Type of validation for input validation from $param['REQUEST']
						 * and to create forms and links to the SEF.
						 * 
						 * /index.php
						 * $result = $evnine->getControllerForParam(
						 *  array(
						 *   'controller' => 'param_gen',
						 *   'REQUEST'=>$_REQUEST,
						 *  )
						 * );
						 * 
						 * The data for the validation are passed to the method isValidModifierParamFormError
						 * 
						 * May be the following types:
						 * validation - validation of the method overwrites the default validation.
						 * validation_add - add to the validation of the method by default.
						 * validation_form - create a form.
						 * validation_multi_form - a plural form.
						 * where different methods can use the same data.
						 * 
						 * PHP - validation_form:
						 * <form action="<?=$result[inURLTemplate][info][pre].$result[inURLTemplate][info][post]?>" method="post">
						 *  <?=$result[inURLTemplate][info][inputs]?>
						 *  <input name="<?=$result[inURL][some_method][PathID]?>" value="">
						 * </form>
						 * 
						 * PHP - validation_multi_form:
						 * <form action="<?=$result[inURLTemplate][info][pre].$result[inURLTemplate][info][post]?>" method="post">
						 *  <?=$result[inURLTemplate][info][inputs]?>
						 *  <input type="submit" name="<?=$result[inURL][some_method][submit]?>" value="OK"/>
						 *  <input type="submit" name="<?=$result[inURL][some_method_a][submit]?>" value=""/>
						 *  <input type="submit" name="<?=$result[inURL][some_method_b][submit]?>" value=""/>
						 * </form>
						 * 
						 * TWIG - validation_form:
						 * <form action="{{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}" method="post">
						 *  {{ inURLTemplate.info.inputs }}
						 *  <input name="{{ inURL.some_method.PathID }}" value="">
						 * </form>
						 * 
						 * TWIG - validation_multi_form:
						 * <form action="{{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}" method="post">
						 *  {{ inURLTemplate.info.inputs }}
						 *  <input type="submit" name="{{ inURL.some_method.submit }}" value="OK"/>
						 *  <input type="submit" name="{{ inURL.some_method_a.submit }}" value=""/>
						 *  <input type="submit" name="{{ inURL.some_method_b.submit }}" value=""/>
						 * </form>
						 */
						'path_id' => array(
							'to'=>'PathID'
							/**
							 * Stored in the variable $param[REQUEST][path_id]
							 * An array of references to URN will be available on a key $result [inURL][PathID]
							 */
							,'default' => ''
							/**
							 * The default value if the variable is not specified.
							 */
							,'inURL' => true
							/**
							 * true - separate parameter for the substitution of references.
							 * 
							 * >>inURL = true, 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=
							 * 
							 * false - by default
							 * 
							 * >>inURL = false 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=777
							 */
							,'inURLSave' => true
							/**
							 * Save parameters in a multi-forms.
							 * Example, when you need to save the settings from the last call
							 * false - by default
							 * 
							 * >>inURLSave = true
							 * >>PHP: $result[inURL][default][pre].$result[inURL][default][PathID].'VAR'.[inURL][default][post]
							 *
							 * >>TWIG: {inURL.default.pre}{inURL.default.PathID}VAR{inURL.default.post}
							 *
							 * << &path_id[]=1&path_id[]=VAR
							 */
							,'is_array' => true
							/**
							 * Whether a variable is an array?
							 * false - by default
							 * 
							 * >> is_array = true
							 * << &path_id[]=1&path_id[]=23
							 * 
							 * >> is_array = false
							 * << &path_id=1
							 */
							,'type'=>'int'
							/**
							 * Type the validation variable
							 * Int - the number
							 * Str - string
							 * Pass - password
							 * Html - HTML code
							 * Email - e-mail
							 * Link - URL
							 * 
							 */
							,'required'=>'false'
							/**
							 * Prohibit validation, if not specified.
							 *
							 * >>required = true
							 * >>REQUEST = array(path_id => '')
							 * 
							 * <<isValidModifierParamFormError_false
							 *
							 *
							 * >>required = false
							 * >>REQUEST = array(path_id => '')
							 *
							 * <<isValidModifierParamFormError_true
							 *
							 * >>required = true
							 * >>default = '777'
							 * >>REQUEST = array(path_id => '')
							 * 
							 * <<isValidModifierParamFormError_true
							 * <<REQUEST_OUT[PathID] = 777
							 */
							,'error'=>'is_empty'
							/**
							 * What a mistake to pass in the validation of $param ['info']
							 */
							,'max' => '100'
							/**
							 * The maximum value of the variable.
							 */
						),
					),
						
					#END default validation
					'ModelsValidation' => 
						'isValidModifierParamFormError',
						/**
						 * Calling the validation with data from an array of validation{_add, _form, _multi_form}
						 */
						'isValidModifierParamFormError_true' => array(
						/**
						 * If validation is successful.
						 * Override the private method validation.
						 */
						),
			'inURLUnitTest' => array(
				/**
				 * PHPUnit Test. 
				 * Case param for the current methods of the controller.
				 */
				'REQUEST' => array(
					array('param' => 'first test'),
					array('param' => 'second test')
				),
				/**
				 * The parameters are generated cases.
				 * 
				 * >>'REQUEST' => array(
				 *  array('param' => 'first test'),
				 *  array('param' => 'second test')
				 * )
				 * 
				 * <<
				 * '0' => array(
				 *  'REQUEST' => array('param' => 'first test'),
				 * ),
				 * '1' => array(
				 *  'REQUEST' => array('param' => 'second test'),
				 * ),
				 * 
				 * The case when the test is given for the controller and method.
				 * 
				 * >>
				 * $this->controller=array(
				 * 'inURLUnitTest' => array(
				 *   'REQUEST' => array(
				 *     array('param_controller' => '1'),
				 *     array('param_controller' => '2'),
				 *   )
				 *  )
				 *  'inURLUnitTest' => array(
				 *    'REQUEST' => array(
				 *      array('param_method' => '3'),
				 *      array('param_method' => '4')
				 *    )
				 *   )
				 *  )
				 * 
				 * <<
				 * '0' => array(
				 *  'REQUEST' => array('param_controller' => '1', 'param_method' => '3'),
				 * ),
				 * '1' => array(
				 *  'REQUEST' => array('param_controller' => '1', 'param_method' => '4'),
				 * ),
				 * '1' => array(
				 *  'REQUEST' => array('param_controller' => '2', 'param_method' => '3'),
				 * ),
				 * '1' => array(
				 *  'REQUEST' => array('param_controller' => '2', 'param_method' => '4'),
				 * ),
				 *
				 */
			),
				'inURLExtController'=>'ext_controller',
				/**
				 * Link to an external controller.
				 * /c=ext_controller
				 */
				'inURLExtMethod'=>'ext_method',
				/**
				 * Link to an external method.
				 * /m=ext_method
				 * 
				 * /c=ext_controller&m=ext_method
				 */
				),
				#END default method

				'main' => array('inURLExtController' => 'main'),
					/**
					 * Example of external links to home page
					 * >> <?=$result[inURL][main][pre].$result[inURL][main][post]?>
					 * << /?c=main
					 */

				#BEGIN some method with SEF
				'some_method'=>array(
				/**
				 * Public method with the SEF.
				 */
					'inURLSEF' => array(
					/**
					 * SEF method, the format controller\method\parameters.html
					 */
						'1' => 'profile','.' => '.html',
						/**
						 * >>/controller/method/profile.html
						 * 
						 * .html <= flag for SEF method
						 */
						'1' => 'upload','2' => 'files','page' => '','.' => '.html',
						/**
						 * SEF for the method call sequence.
						 * 
						 * /controller/method/upload-files.html
						 * /controller/method/upload-files-2.html
						 * /controller/method/upload-files-3.html
						 */
						'user_id' => '','user_name_for_seo' => '','.' => '.html',
						/**
						 * To generate a link in any length.
						 * 
						 * /controller/method/user_name-1-SEO-user-name.html
						 */
					),
				),
				#END some method with SEF
			)
			#END public_methods
		);
	}
}
?>
