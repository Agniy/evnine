<?php
/**
 * en: An example of the controller with comments and options settings.
 * ru: Пример контроллера с вариантами параметров.
 * 
 * en: The controller name must coincide with the class name and file name. 
 * ru: Имя контроллера обязательно совпадает с название класса и именем файла.
 * 
 * class ControllersExample == ControllersExample.php
 * /controllers/ControllersExample.php
 * 
 * en: Determined in the configuration file:
 * ru: Определятся в конфигурационном файле:
 * /evnine.config.php
 *	$this->controller_alias=array(
 *		'example'=>'ControllersExample'
 *	);
 * 
 * en: The alternative with the path.
 * ru: Альтернативный вариант с указанием пути.
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
	 * en: An alternative method of determining the controller via YAML
	 * ru: Альтернативный способ определения контроллера через YAML
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
	 * en: Array controller.
	 * ru: Массив контроллера.
	 * @access public
	 * @link http://components.symfony-project.org/yaml/installation
	 */
	var $controller;

	/** __construct 
	 * en: The class constructor is initialized with the level of access.
	 * ru: Конструктор класса инициализируем с уровнем доступа.
	 * 
	 * @param array $access_level 
	 *  
	 * en: The level of access set to:
	 * ru: Уровень доступа задаётся в:
	 * /evnine.config.php
	 * $this->access_level=array(
	 *  'admin'=>'2',
	 *  'user'=>'1',
	 *  'guest'=>'0',
	 * );
	 * 
	 * en: Controller to initialize:
	 * ru: Контроллер устанавливается в:
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
				 * en: Case param for all the methods of the controller.
				 * ru: Параметры для всех методов контроллера.
				 * en: How do the tests?
				 * ru: Как работают тесты?
				 * 
				 * en: Case when testing the first time:
				 * ru: Случай когда тестируется первый раз:
				 * en: 1. ModelsPHPUnit reads data from the controller parameters inURLUnitTest.
				 * ru: 1. Модель ModelsPHPUnit считывает данные с контроллера по параметрам теста.
				 * en: 2. Makes a request with these parameters through EvnineController
				 * ru: 2. Делает запрос с этими параметрами через EvnineController
				 * en: 3. Saves the current state in test/PHPUnitCache on md5 key parameters.
				 * ru: 3. Сохраняет текущее состояние в папке test/PHPUnitCache по md5 ключу от параметров.
				 * en: 4. Returns true, because checking the first time.
				 * ru: 4. Возвращает true, так как проверка первый раз.
				 * 
				 * en: When the test is already exist
				 * ru: Когда тест уже существует
				 * en: 1. ModelsPHPUnit reads data from the controller parameters on the test.
				 * ru: 1. Модель ModelsPHPUnit считывает данные с контроллера по параметрам теста.
				 * en: 2. Makes a request with these parameters through EvninController we get the results "after".
				 * ru: 2. Делает запрос с этими параметрами через EvnineController получаем 
				 * en: 3. Fetches the "before" in the folder test/PHPUnitCache on md5 key parameters.
				 * ru: 3. Делает выборку "до" в папке test/PHPUnitCache по md5 ключу от параметров.
				 * en: 4. Compares the results "before" and "after".
				 * ru: 4. Сравнивает результат до и после.
				 * 
				 * en: Path to the tests can also be set to:
				 * ru: Путь до тестов так же можно задать в:
				 * /evnine.config.php
				 * $this->param_const=array(
				 *  // en: Folder to store the PHPUnit tests.
				 *  // ru: Папка для хранения PHPUnit тестов.
				 *	'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
				 *  // en: Folder to store temporary data.
				 *  // ru: Папка для хранения промежуточных данных.
				 *	 CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
				 *  // en: A shared folder for the cache.
				 *  // ru: Общая папка для кэша.
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
				 * en: The parameters are generated cases.
				 * ru: По параметрам создаются случаи.
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
			 * en: For convenience, the name of the controller.
			 * ru: Для удобства, название контроллера.
			 * 
			 * /evnine.config.php
			 * $this->controller_alias=array(
			 *  'example'=>'ControllersExample',
			 *);
			 */
			'page_level'=>'',
			/**
			 * en: The depth of the tree on the controller call. Need to work without AJAX.
			 * en: Default: 0
			 * ru: Глубина контроллера по древу вызова. Нужно для работы без AJAX.
			 * ru: По умолчанию: 0
			 */
			'parent'=> '',
			/**
			 * en: Parent controller for a call without AJAX.
			 * ru: Родитель контроллера для вызова без AJAX.
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
			 * en: The template for controller.
			 * ru: Шаблон контроллера. Для работы шаблонизатора.
			 */
			'title'=> '',
			/**
			 * en: Title for the template.
			 * ru: Название для передачи в шаблон.
			 */
			'inURLSEF'=> false,
			/**
			 * en: SEF mode in the controller.
			 * en: If the SEF mode for the controller, the ending URN - index.html
			 * en: Default: false
			 * ru: ЧПУ режим в контроллере.
			 * ru: Если ЧПУ режим для всего контроллера, есть окончание В URN - index.html
			 * ru: По умолчанию false
			 * 
			 * >>'inURLSEF'=> true
			 * <</controller/method/param=value/param=value/
			 */
			'access'=>array(
			/**
			 * en: The level of access to the controller, as well as to the method of access checks. 
			 * ru: Уровень доступа к контроллеру, а так же для метод проверки доступа.
			 */
				'default_access_level' => $access_level['guest'],
				/**
				 * en: For the level of access to model $param['PermissionLevel'].
				 * ru: За уровень доступа в модели отвечает $param['PermissionLevel']
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
				 * en: In the case where the level does not coincide with minimal.
				 * en: Run the test method of access. 
				 * en: For example, based on the session or cookie.
				 * en: The validation method must change $param['PermissionLevel']
				 * en: The validation method must be private_methods.
				 * ru: В случае когда уровень не совпадает с минимальным.
				 * ru: Запускаем метод проверки доступа.
				 * ru: Например на основе сессии или куков.
				 * ru: Метод проверки должен изменить $param['PermissionLevel']
				 * ru: Метод проверки должен быть в private_methods.
				 * 
				 * 'private_methods' => array(
				 *  'isHasAccess'=>array(
				 *   'ModelsValidation'=>'isValidCookie'
				 *  )
				 * ),
				 */
				'ModelsValidation::isValidCookie'=>array('access_level'=>$access_level['guest']),
				/**
				 * en: Minimum level of access to the call method for check.
				 * en: Without specifying, the method allowed to run for safety.
				 * ru: Минимальный уровень доступа к запуску метода проверки.
				 * ru: Без указания, метод запрещено запускать в целях безопасности.
				 */
			),
			'templates' => array(
			/**
			 * en: Access to the mapping of the template.
			 * en: Depends on the user's access.
			 * ru: Доступ к отображению частей шаблона.
			 * ru: Зависит от доступа пользователя.
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
			 * en: Private methods are denied access to them from outside.
			 * en: Run out of public functions.
			 * ru: Приватные методы, к ним запрещен доступ извне.
			 * ru: Запускаются из публичных методов.
			 */
				'isHasAccess'=>array(
				/**
				 * en: Check access.
				 * ru: Проверить, а есть ли доступ?
				 */
					'ModelsValidation'  => 
						'isValidCookie'
				),
						/**
						 * en: We use cookies to check the model validation.
						 * ru: Используем проверку куков в модели валидации.
						 */
						'isValidCookie_false'  => array(
						/**
						 * en: The case when validation fails.
						 * ru: Случай когда валидация не пройдена
						 */
							'ModelsErrors'=>'getError->no_auth'
							/**
							 * en: If you do not have access. Execute method with the error.
							 * ru: Если доступа нет. Выполним метод с передачей ошибки.
							 */
						),
				'isHasAccess_false' => array(
				/**
				 * en: If you do not have access.
				 * ru: Если нет доступа. 
				 */
					'ModelsErrors'=>'getError',
					/**
					 * en: Display an error.
					 * ru: В модели вызовем отображении ошибки.
					 */
				),
				'isHasAccess_true' => array(
				/**
				 * en: If you have access.
				 * ru: Если доступ есть. 
				 */
					'ModelsInfo'=>array(
					/**
					 * en: In the model of information verification method with the parameter OK.
					 * ru: В модели информации, метод подтверждения с параметром OK.
					 */
						'getInfo->ok',
					)
				),
				'isValidModifierParamFormError_false' => array(
				/**
				 * en: In the case of validation errors.
				 * ru: В случае ошибки валидации.
				 */
					'ModelsErrors'=>'getError',
					/**
					 * en: Running a method of displaying an error.
					 * ru: Запускаем метод отображения ошибки.
					 */
				),
				'isValidModifierParamFormError_true' => array(
				/**
				 * en: If validation is successful.
				 * ru: Если валидация прошла успешно
				 */
				)
			),
			#END private_methods

			#BEGIN public_methods
			'public_methods' => array(
			/**
			 * en: Public methods are available to all users.
			 * en: Example call /c=controller&m=default
			 * ru: Публичные методы доступные всем пользователям
			 * ru: Пример вызова /c=контроллер&m=публичный метод	
			 */

				#BEGIN default method
				'default'=>array(
				/**
				 * en: The default method is always performed when not set.
				 * ru: Метод по умолчанию, выполняется всегда когда не указан.
				 */
					'controller' => 'method',
					/**
					 * en: Calling an external controller with this method.
					 * ru: Вызов внешнего контроллера с указанным методом.
					 */
					'this' => 'some_method',
					/**
					 * en: Point to a query from the current controller is the some_method.
					 * ru: Указываем на запрос из текущего контроллера метод some_method.
					 */
					'inURLMethod' => array(
					/**
					 * en: An array of method for generating URI
					 * ru: Массив для генерации URI
					 * en: In the template available for $result['ViewMethod']['default']
					 * ru: В шаблонизаторе доступ по $result['ViewMethod']['default']
					 * 
					 * en: inURLMethod - override the default method references
					 * en: inURLMethod_add - add links to the default method.
					 * ru: inURLMethod - перезаписываем ссылки метода по умолчанию
					 * ru: inURLMethod_add - добавляем к ссылкам метода по умолчанию.
					 */
						'default',
						/**
						 * en: The list of methods in the controller to generate links.
						 * en: In order to put a link to the template method.
						 * ru: Список методов в контроллере для генерации ссылок.
						 * ru: Для того, чтобы поставить ссылку в шаблоне на метод.
						 * 
						 * PHP:  $result[inURL][default][pre].$result[inURL][default][post]
						 * TWIG: inURL.default.pre inURL.default.post
						 *
						 * en: pre - The basic part of URN
						 * en: post - SEF part
						 * ru: pre - базовая часть URN.
						 * ru: post - SEF часть
						 */
					),
					'inURLTemplate' => array(
					/**
					 * en: Array for permanent links in the template on different methods.
					 * ru: Массив для постоянных ссылок в шаблоне на разные методы.
					 */
						'info' => 'default',
						/**
						 * en: Link $result[inURLTemplate][info] to the default method
						 * ru: Ссылка $result[inURLTemplate][info] будет вести на метод по умолчанию
						 */
						'info' => 'some_method',
						/**
						 * en: Link $result[inURLTemplate][info] to the some_method method
						 * ru: Ссылка $result[inURLTemplate][info] будет вести на метод some_method
						 */
						'error' => 'default',
						/**
						 * en: A reference to a method of displaying an error.
						 * ru: Ссылка на метод отображения ошибки.
						 */
					),
					'inURLView' => 'ajax.php',
					/**
					 * en: A reference to a template for the method.
					 * ru: Ссылка на шаблон для метода.
					 */

					#BEGIN default validation
					'validation'=>array(
						/**
						 * en: Type of validation for input validation from $param['REQUEST']
						 * en: and to create forms and links to the SEF.
						 * ru: Тип валидации для проверки входных данных из $param['REQUEST']
						 * ru: и для создания форм и ссылок с ЧПУ.
						 * 
						 * /index.php
						 * $result = $evnine->getControllerForParam(
						 *  array(
						 *   'controller' => 'param_gen',
						 *   'REQUEST'=>$_REQUEST,
						 *  )
						 * );
						 * 
						 * en: The data for the validation are passed to the method isValidModifierParamFormError
						 * ru: Данные для валидации, передаются в метод isValidModifierParamFormError
						 * 
						 * en: May be the following types:
						 * en: validation - validation of the method overwrites the default validation.
						 * en: validation_add - add to the validation of the method by default.
						 * en: validation_form - create a form.
						 * en: validation_multi_form - a plural form.
						 * en: where different methods can use the same data.
						 * ru: Может быть:
						 * ru: validation - перезаписывает валидацию из метода по умолчанию - default.
						 * ru: validation_add - добавить к валидации из метода по умолчанию - default.
						 * ru: validation_form - создать форму.
						 * ru: validation_multi_form - создать множественную форму, 
						 * ru: когда разные методы могут использовать одни и те же данные.
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
							 * en: Stored in the variable $param[REQUEST][path_id]
							 * en: An array of references to URN will be available on a key $result [inURL][PathID]
							 * ru: Переменная сохраняться в $param[REQUEST][path_id]
							 * ru: Массив для ссылки URN будет доступен по ключу $result[inURL][PathID]
							 */
							,'default' => ''
							/**
							 * en: The default value if the variable is not specified.
							 * ru: Значение по умолчанию, если переменная не указана.
							 */
							,'inURL' => true
							/**
							 * en: true - separate parameter for the substitution of references.
							 * ru: true - отдельный параметр для подстановки части ссылки.
							 * 
							 * >>inURL = true, 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=
							 * 
							 * en: false - by default
							 * ru: false - по умолчанию.
							 * 
							 * >>inURL = false 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=777
							 */
							,'inURLSave' => true
							/**
							 * en: Save parameters in a multi-forms.
							 * en: Example, when you need to save the settings from the last call
							 * en: false - by default
							 * ru: Сохранить ли параметры в мульти формах.
							 * ru: Пример, когда нужно сохранить параметры из прошлого вызова
							 * ru: false - по умолчанию.
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
							 * en: Whether a variable is an array?
							 * en: false - by default
							 * ru: Является ли переменная массивом? 
							 * ru: false - по умолчанию.
							 * 
							 * >> is_array = true
							 * << &path_id[]=1&path_id[]=23
							 * 
							 * >> is_array = false
							 * << &path_id=1
							 */
							,'type'=>'int'
							/**
							 * en: Type the validation variable
							 * en: Int - the number
							 * en: Str - string
							 * en: Pass - password
							 * en: Html - HTML code
							 * en: Email - e-mail
							 * en: Link - URL
							 * ru: Тип валидации переменной
							 * ru: int - число
							 * ru: str - строка
							 * ru: pass - пароль
							 * ru: html - HTML код
							 * ru: email - e-mail
							 * ru: link - URL
							 * 
							 */
							,'required'=>'false'
							/**
							 * en: Prohibit validation, if not specified.
							 * ru: Обязательна ли переменная для валидации, по умолчанию false
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
							 * en: What a mistake to pass in the validation of $param ['info']
							 * ru: Какую ошибку передать при валидации в $param['info']
							 */
							,'max' => '100'
							/**
							 * en: The maximum value of the variable.
							 * ru: Максимальное значение переменной.
							 */
						),
					),
						
					#END default validation
					'ModelsValidation' => 
						'isValidModifierParamFormError',
						/**
						 * en: Calling the validation with data from an array of validation{_add, _form, _multi_form}
						 * ru: Вызов валидации c данными из массива validation{_add,_form,_multi_form}
						 */
						'isValidModifierParamFormError_true' => array(
						/**
						 * en: If validation is successful.
						 * en: Override the private method validation.
						 * ru: Если валидация прошла успешно.
						 * ru: Перезаписываем приватный метод валидации
						 */
						),
			'inURLUnitTest' => array(
				/**
				 * PHPUnit Test. 
				 * en: Case param for the current methods of the controller.
				 * ru: Параметры тестов для этого метода.
				 */
				'REQUEST' => array(
					array('param' => 'first test'),
					array('param' => 'second test')
				),
				/**
				 * en: The parameters are generated cases.
				 * ru: По параметрам создаются случаи.
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
				 * en: The case when the test is given for the controller and method.
				 * ru: Случай когда задан тест для всего контроллера и метода.
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
				 * en: Link to an external controller.
				 * ru: Ссылка на внешний контроллер.
				 * /c=ext_controller
				 */
				'inURLExtMethod'=>'ext_method',
				/**
				 * en: Link to an external method.
				 * ru: Ссылка на внешний метод.
				 * /m=ext_method
				 * 
				 * /c=ext_controller&m=ext_method
				 */
				),
				#END default method

				'main' => array('inURLExtController' => 'main'),
					/**
					 * en: Example of external links to home page
					 * ru: Пример внешний ссылки на главную страницу 
					 * >> <?=$result[inURL][main][pre].$result[inURL][main][post]?>
					 * << /?c=main
					 */

				#BEGIN some method with SEF
				'some_method'=>array(
				/**
				 * en: Public method with the SEF.
				 * ru: публичный метод с ЧПУ
				 */
					'inURLSEF' => array(
					/**
					 * en: SEF method, the format controller\method\parameters.html
					 * ru: ЧПУ для метода, формата контроллер\метод\параметры.html
					 */
						'1' => 'profile','.' => '.html',
						/**
						 * >>/controller/method/profile.html
						 * 
						 * en: .html <= flag for SEF method
						 * ru: .html <= флаг для ЧПУ метода
						 */
						'1' => 'upload','2' => 'files','page' => '','.' => '.html',
						/**
						 * en: SEF for the method call sequence.
						 * ru: ЧПУ для метода, последовательность вызова
						 * 
						 * /controller/method/upload-files.html
						 * /controller/method/upload-files-2.html
						 * /controller/method/upload-files-3.html
						 */
						'user_id' => '','user_name_for_seo' => '','.' => '.html',
						/**
						 * en: To generate a link in any length.
						 * ru: Для генерации ссылки любой длины.
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
