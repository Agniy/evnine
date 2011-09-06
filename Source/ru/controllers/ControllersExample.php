<?php
/**
 * Пример контроллера с вариантами параметров.
 * 
 * Имя контроллера обязательно совпадает с название класса и именем файла.
 * 
 * class ControllersExample == ControllersExample.php
 * /controllers/ControllersExample.php
 * 
 * Определятся в конфигурационном файле:
 * /evnine.config.php
 *	$this->controller_alias=array(
 *		'example'=>'ControllersExample'
 *	);
 * 
 * Альтернативный вариант с указанием пути.
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
	 * Альтернативный способ определения контроллера через YAML
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
	 * Массив контроллера.
	 * @access public
	 * @link http://components.symfony-project.org/yaml/installation
	 */
	var $controller;

	/**
	 * Конструктор класса инициализируем с уровнем доступа.
	 * 
	 * @param array $access_level 
	 *  
	 * Уровень доступа задаётся в:
	 * /evnine.config.php
	 * $this->access_level=array(
	 *  'admin'=>'2',
	 *  'user'=>'1',
	 *  'guest'=>'0',
	 * );
	 * 
	 * Контроллер устанавливается в:
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
				 * Параметры для всех методов контроллера.
				 * Как работают тесты?
				 * 
				 * Случай когда тестируется первый раз:
				 * 1. Модель ModelsPHPUnit считывает данные с контроллера по параметрам теста.
				 * 2. Делает запрос с этими параметрами через EvnineController
				 * 3. Сохраняет текущее состояние в папке test/PHPUnitCache по md5 ключу от параметров.
				 * 4. Возвращает true, так как проверка первый раз.
				 * 
				 * Когда тест уже существует
				 * 1. Модель ModelsPHPUnit считывает данные с контроллера по параметрам теста.
				 * 2. Делает запрос с этими параметрами через EvnineController получаем 
				 * 3. Делает выборку "до" в папке test/PHPUnitCache по md5 ключу от параметров.
				 * 4. Сравнивает результат до и после.
				 * 
				 * Путь до тестов так же можно задать в:
				 * /evnine.config.php
				 * $this->param_const=array(
				 *  // Папка для хранения PHPUnit тестов.
				 *	'CacheDirPHPUnit'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'PHPUnit',
				 *  // Папка для хранения промежуточных данных.
				 *	 CacheDirControllerForParam'=>'PHPUnitCache'.DIRECTORY_SEPARATOR.'getControllerForParam',
				 *  // Общая папка для кэша.
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
				 * По параметрам создаются случаи.
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
			 * Для удобства, название контроллера.
			 * 
			 * /evnine.config.php
			 * $this->controller_alias=array(
			 *  'example'=>'ControllersExample',
			 *);
			 */
			'page_level'=>'',
			/**
			 * Глубина контроллера по древу вызова. Нужно для работы без AJAX.
			 * По умолчанию: 0
			 */
			'parent'=> '',
			/**
			 * Родитель контроллера для вызова без AJAX.
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
			 * Шаблон контроллера. Для работы шаблонизатора.
			 */
			'title'=> '',
			/**
			 * Название для передачи в шаблон.
			 */
			'inURLSEF'=> false,
			/**
			 * ЧПУ режим в контроллере.
			 * Если ЧПУ режим для всего контроллера, есть окончание В URN - index.html
			 * По умолчанию false
			 * 
			 * >>'inURLSEF'=> true
			 * <</controller/method/param=value/param=value/
			 */
			'access'=>array(
			/**
			 * Уровень доступа к контроллеру, а так же для метод проверки доступа.
			 */
				'default_access_level' => $access_level['guest'],
				/**
				 * За уровень доступа в модели отвечает $param['PermissionLevel']
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
				 * В случае когда уровень не совпадает с минимальным.
				 * Запускаем метод проверки доступа.
				 * Например на основе сессии или куков.
				 * Метод проверки должен изменить $param['PermissionLevel']
				 * Метод проверки должен быть в private_methods.
				 * 
				 * 'private_methods' => array(
				 *  'isHasAccess'=>array(
				 *   'ModelsValidation'=>'isValidCookie'
				 *  )
				 * ),
				 */
				'ModelsValidation::isValidCookie'=>array('access_level'=>$access_level['guest']),
				/**
				 * Минимальный уровень доступа к запуску метода проверки.
				 * Без указания, метод запрещено запускать в целях безопасности.
				 */
			),
			'templates' => array(
			/**
			 * Доступ к отображению частей шаблона.
			 * Зависит от доступа пользователя.
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
			 * Приватные методы, к ним запрещен доступ извне.
			 * Запускаются из публичных методов.
			 */
				'isHasAccess'=>array(
				/**
				 * Проверить, а есть ли доступ?
				 */
					'ModelsValidation'  => 
						'isValidCookie'
				),
						/**
						 * Используем проверку куков в модели валидации.
						 */
						'isValidCookie_false'  => array(
						/**
						 * Случай когда валидация не пройдена
						 */
							'ModelsErrors'=>'getError->no_auth'
							/**
							 * Если доступа нет. Выполним метод с передачей ошибки.
							 */
						),
				'isHasAccess_false' => array(
				/**
				 * Если нет доступа. 
				 */
					'ModelsErrors'=>'getError',
					/**
					 * В модели вызовем отображении ошибки.
					 */
				),
				'isHasAccess_true' => array(
				/**
				 * Если доступ есть. 
				 */
					'ModelsInfo'=>array(
					/**
					 * В модели информации, метод подтверждения с параметром OK.
					 */
						'getInfo->ok',
					)
				),
				'isValidModifierParamFormError_false' => array(
				/**
				 * В случае ошибки валидации.
				 */
					'ModelsErrors'=>'getError',
					/**
					 * Запускаем метод отображения ошибки.
					 */
				),
				'isValidModifierParamFormError_true' => array(
				/**
				 * Если валидация прошла успешно
				 */
				)
			),
			#END private_methods

			#BEGIN public_methods
			'public_methods' => array(
			/**
			 * Публичные методы доступные всем пользователям
			 * Пример вызова /c=контроллер&m=публичный метод	
			 */

				#BEGIN default method
				'default'=>array(
				/**
				 * Метод по умолчанию, выполняется всегда когда не указан.
				 */
					'controller' => 'method',
					/**
					 * Вызов внешнего контроллера с указанным методом.
					 */
					'this' => 'some_method',
					/**
					 * Указываем на запрос из текущего контроллера метод some_method.
					 */
					'inURLMethod' => array(
					/**
					 * Массив для генерации URI
					 * В шаблонизаторе доступ по $result['ViewMethod']['default']
					 * 
					 * inURLMethod - перезаписываем ссылки метода по умолчанию
					 * inURLMethod_add - добавляем к ссылкам метода по умолчанию.
					 */
						'default',
						/**
						 * Список методов в контроллере для генерации ссылок.
						 * Для того, чтобы поставить ссылку в шаблоне на метод.
						 * 
						 * PHP:  $result[inURL][default][pre].$result[inURL][default][post]
						 * TWIG: inURL.default.pre inURL.default.post
						 *
						 * pre - базовая часть URN.
						 * post - SEF часть
						 */
					),
					'inURLTemplate' => array(
					/**
					 * Массив для постоянных ссылок в шаблоне на разные методы.
					 */
						'info' => 'default',
						/**
						 * Ссылка $result[inURLTemplate][info] будет вести на метод по умолчанию
						 */
						'info' => 'some_method',
						/**
						 * Ссылка $result[inURLTemplate][info] будет вести на метод some_method
						 */
						'error' => 'default',
						/**
						 * Ссылка на метод отображения ошибки.
						 */
					),
					'inURLView' => 'ajax.php',
					/**
					 * Ссылка на шаблон для метода.
					 */

					#BEGIN default validation
					'validation'=>array(
						/**
						 * Тип валидации для проверки входных данных из $param['REQUEST']
						 * и для создания форм и ссылок с ЧПУ.
						 * 
						 * /index.php
						 * $result = $evnine->getControllerForParam(
						 *  array(
						 *   'controller' => 'param_gen',
						 *   'REQUEST'=>$_REQUEST,
						 *  )
						 * );
						 * 
						 * Данные для валидации, передаются в метод isValidModifierParamFormError
						 * 
						 * Может быть:
						 * validation - перезаписывает валидацию из метода по умолчанию - default.
						 * validation_add - добавить к валидации из метода по умолчанию - default.
						 * validation_form - создать форму.
						 * validation_multi_form - создать множественную форму, 
						 * когда разные методы могут использовать одни и те же данные.
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
							 * Переменная сохраняться в $param[REQUEST][path_id]
							 * Массив для ссылки URN будет доступен по ключу $result[inURL][PathID]
							 */
							,'default' => ''
							/**
							 * Значение по умолчанию, если переменная не указана.
							 */
							,'inURL' => true
							/**
							 * true - отдельный параметр для подстановки части ссылки.
							 * 
							 * >>inURL = true, 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=
							 * 
							 * false - по умолчанию.
							 * 
							 * >>inURL = false 
							 * >>REQUEST = array(path_id => 777)
							 * >>$result[inURL][default][PathID]
							 * 
							 * << &path_id=777
							 */
							,'inURLSave' => true
							/**
							 * Сохранить ли параметры в мульти формах.
							 * Пример, когда нужно сохранить параметры из прошлого вызова
							 * false - по умолчанию.
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
							 * Является ли переменная массивом? 
							 * false - по умолчанию.
							 * 
							 * >> is_array = true
							 * << &path_id[]=1&path_id[]=23
							 * 
							 * >> is_array = false
							 * << &path_id=1
							 */
							,'type'=>'int'
							/**
							 * Тип валидации переменной
							 * int - число
							 * str - строка
							 * pass - пароль
							 * html - HTML код
							 * email - e-mail
							 * link - URL
							 * 
							 */
							,'required'=>'false'
							/**
							 * Обязательна ли переменная для валидации, по умолчанию false
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
							 * Какую ошибку передать при валидации в $param['info']
							 */
							,'max' => '100'
							/**
							 * Максимальное значение переменной.
							 */
						),
					),
						
					#END default validation
					'ModelsValidation' => 
						'isValidModifierParamFormError',
						/**
						 * Вызов валидации c данными из массива validation{_add,_form,_multi_form}
						 */
						'isValidModifierParamFormError_true' => array(
						/**
						 * Если валидация прошла успешно.
						 * Перезаписываем приватный метод валидации
						 */
						),
			'inURLUnitTest' => array(
				/**
				 * PHPUnit Test. 
				 * Параметры тестов для этого метода.
				 */
				'REQUEST' => array(
					array('param' => 'first test'),
					array('param' => 'second test')
				),
				/**
				 * По параметрам создаются случаи.
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
				 * Случай когда задан тест для всего контроллера и метода.
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
				 * Ссылка на внешний контроллер.
				 * /c=ext_controller
				 */
				'inURLExtMethod'=>'ext_method',
				/**
				 * Ссылка на внешний метод.
				 * /m=ext_method
				 * 
				 * /c=ext_controller&m=ext_method
				 */
				),
				#END default method

				'main' => array('inURLExtController' => 'main'),
					/**
					 * Пример внешний ссылки на главную страницу 
					 * >> <?=$result[inURL][main][pre].$result[inURL][main][post]?>
					 * << /?c=main
					 */

				#BEGIN some method with SEF
				'some_method'=>array(
				/**
				 * публичный метод с ЧПУ
				 */
					'inURLSEF' => array(
					/**
					 * ЧПУ для метода, формата контроллер\метод\параметры.html
					 */
						'1' => 'profile','.' => '.html',
						/**
						 * >>/controller/method/profile.html
						 * 
						 * .html <= флаг для ЧПУ метода
						 */
						'1' => 'upload','2' => 'files','page' => '','.' => '.html',
						/**
						 * ЧПУ для метода, последовательность вызова
						 * 
						 * /controller/method/upload-files.html
						 * /controller/method/upload-files-2.html
						 * /controller/method/upload-files-3.html
						 */
						'user_id' => '','user_name_for_seo' => '','.' => '.html',
						/**
						 * Для генерации ссылки любой длины.
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
