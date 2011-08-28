<?php
/**
	* en: An example of the controller with comments and options settings.
	* ru: Пример контроллера с комментариями и вариантами параметров.
	* 
	* /controllers/ControllersExample.php
	*
	* @package Controller
	* @author ev9eniy
	* @version 2
	* @updated 31.08.2011
	*/
class ControllersExample
/**
	* en: The name of the controller.
	* en: The name must coincide with the class name and file name. 
	* ru: Имя контроллера. 
	* ru: Имя обязательно совпадает с название класса и именем файла.
	*
	* class ControllersExample == ControllersExample.php
	* 
	* /evnine.config.php
	* $this->controller_alias=array(
	*  'example'=>'ControllersExample'
	* );
	*/
{
	/**
		* en: Array controller.
		* ru: Массив контроллера.
		* 
		* @var array
		* @access public
		*/
	var $controller;
	/**
		* en: The class constructor is initialized with the level of access.
		* ru: Конструктор класса инициализируем с передачей уровня доступа.
		* 
		* /evnine.php
		* new $controller($this->access_level);
		* 
		* /evnine.config.php
		* $this->access_level=array(
		*  'admin'=>'2',
		*  'user'=>'1',
		*  'guest'=>'0',
		* );
		*/

	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
			/**
				* en: 
				* ru: PHPUnit Test для всеx методов контроллера.
				*/
				'REQUEST' => array(),
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
				* en: If the SEF mode for the controller, the ending URL - index.html
				* en: Default: false
				* ru: ЧПУ режим в контроллере.
				* ru: Если ЧПУ режим для всего контроллера, есть окончание УРЛ - index.html
				* ru: По умолчанию false
				* 
				* >'inURLSEF'=> true
				* </controller/method/param=value/param=value/
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
					*  'admin'=>'2',
					*  'user'=>'1',
					*  'guest'=>'0',
					* );
					*/
				'default_private_methods' => 'isHasAccess',
				/**
					* en: In the case where the level does not coincide with minimal.
					* en: Run the test method of access. 
					* en: For example, based on the session or cookie.
					* en: The validation method must change $param['PermissionLevel']
					* ru: В случае когда уровень не совпадает с минимальным.
					* ru: Запускаем метод проверки доступа.
					* ru: Например на основе сессии или куков.
					* ru: Метод проверки должен изменить $param['PermissionLevel']
					*/
				'ModelsValidation::isValidCookie'=>array('access_level'=>$access_level['guest']),
				/**
					* en: Minimum level of access to the launch method.
					* en: Without specifying, the method allowed to run for safety.
					* ru: Минимальный уровень доступа к запуску метода.
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
				*/
				$access_level['admin']=>array('menu'=>'admin_menu'),
				$access_level['user']=>array('menu'=>'user_menu'),
				$access_level['guest']=>array('menu'=>'menu')
			),

			// BEGIN private_methods
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
			// END private_methods

			// BEGIN public_methods
			'public_methods' => array(
			/**
				* en: Public methods are available to all users.
				* en: Example call /c=controller&m=default
				* ru: Публичные методы доступные всем пользователям
				* ru: Пример вызова /c=имя контроллера&m=публичный метод	
				*/

				// BEGIN default method
				'default'=>array(
				/**
					* en: The default method is always performed when not set.
					* ru: Метод по умолчанию, выполняется всегда когда не указан.
					*/
					'inURLMethod' => array(
					/**
						* en: An array of method for generating URI
						* en: In the template available for $controller_result['ViewMethod']['default']
						* ru: Массив для генерации URI
						* ru: В шаблонизаторе доступ по $controller_result['ViewMethod']['default']
						* ru: inURLMethod - перезаписываем ссылки метода по умолчанию
						* ru: inURLMethod_add - добавляем к ссылкам метода по умолчанию.
						*/
						'default',
						/**
							* en: 
							* ru: Список методов в контроллере для генерации ссылок
							* ru: Для того что бы поставить ссылку в шаблоне на метод
							* ru: inURL.default.pre inURL.default.post
							* ru: Базовая часть урла, SEF часть
							*/
					),
					'inURLTemplate' => array(
					/**
						* en: Array for permanent links in the template on different methods.
						* ru: Массив для постоянных ссылок в шаблоне на разные методы.
						*/
						'info' => 'default',
						/**
							* en: Link $controller_result[inURLTemplate][info] to the default method
							* ru: Ссылка $controller_result[inURLTemplate][info] будет вести на метод по умолчанию
							* 
							*/
						'info' => 'some_method',
						/**
							* en: Link $controller_result[inURLTemplate][info] to the some_method method
							* ru: Ссылка $controller_result[inURLTemplate][info] будет вести на метод some_method
							*
							*/
						'error' => 'default',
						/**
							* en: A reference to a method of displaying an error.
							* ru: Ссылка на метод отображения ошибки.
							*
							*/
						),
						'inURLView' => 'ajax.php',
						/**
							* en: A reference to a template for the method.
							* ru: Ссылка на шаблон для метода.
							*/

						// BEGIN default validation
						'validation'=>array(
							/**
								* en: Type of validation for input validation from $param['REQUEST']
								* en: and to create forms and links to the SEF.
								* ru: Тип валидации для проверки входных данных из $param['REQUEST']
								* ru: и для создания форм и ссылок с ЧПУ.
								* 
								* /index.php
								* $controller_result = $evnine->getControllerForParam(
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
								* PHP ['']- validation_form:
								* <form action="<?=$controller_result[inURLTemplate][info][pre].$controller_result[inURLTemplate][info][post]?>" method="post">
								*  <?=$controller_result[inURLTemplate][info][inputs]?>
								*  <input name="<?=$controller_result[inURL][some_method][PathID]?>" value="">
								* </form>
								* 
								* PHP ['']- validation_multi_form:
								* <form action="<?=$controller_result[inURLTemplate][info][pre].$controller_result[inURLTemplate][info][post]?>" method="post">
								*  <?=$controller_result[inURLTemplate][info][inputs]?>
								*  <input type="submit" name="<?=$controller_result[inURL][some_method][submit]?>" value="OK"/>
								*  <input type="submit" name="<?=$controller_result[inURL][some_method_a][submit]?>" value=""/>
								*  <input type="submit" name="<?=$controller_result[inURL][some_method_b][submit]?>" value=""/>
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
									//TODO 69% ------------------------------{ 26.08.2011 }------------------------------
									* en: 
									* ru: Переменная сохранится в $param['REQUEST']['PathID']
									* ru: массив для ссылки URN будет доступен по ключу $controller_result['inURL']['PathID']
									*/
								,'inURL' => true
								/**
									* en: 
									* ru: false - по умолчанию.
									* ru: true - отдельный параметр для генерации ссылки URN.
									* ru: В шаблоне отобразить можно через inURL[default][PathID]='&path_id='
									*/
								,'inURLSave' => true
								/**
									* en: 
									* ru: false - по умолчанию.
									* ru: Сохранить ли параметры в мульти формах.
									* ru: Пример, когда нужно сохранить параметры из прошлого вызова
									* 
									* >>PHP['']: 
									* $controller_result[inURL][default][pre].$controller_result[inURL][default][PathID].'VAR'.[inURL][default][post]
									*
									* >>TWIG: 
									* {inURL.default.pre}{inURL.default.PathID}VAR{inURL.default.post}
									*
									* << &path_id[]=1&path_id[]=VAR
									*/
								,'is_array' => true
								/**
									* en: 
									* ru: false - по умолчанию.
									* ru: Является ли переменная массивом? 
									* ru: Пример &path_id[]=1&path_id[]=23
									*/
								,'type'=>'int'
								/**
									* en: 
									* ru: Тип валидации переменной
									* ru: int - число
									* ru: str - строка
									* ru: pass - пароль
									* ru: html - HTML код
									* ru: email - e-mail
									* ru: link - URL
									*/
								,'required'=>'false'
								/**
									* en: 
									* ru: Обязательна ли переменная для валидации, по умолчанию false
									*/
								,'error'=>'is_empty'
								/**
									* en: 
									* ru: Какую ошибку передать при валидации в $param['info']
									*/
								,'max' => '100'
								/**
									* en: 
									* ru: Максимальное значение переменной
									*/
							),
						),
						
						// END default validation
					'ModelsValidation' => 
						'isValidModifierParamFormError',
						/**
							* en: 
							* ru: Вызов валидации c данными из массива validation{_add,_form,_multi_form}
							*/
						'isValidModifierParamFormError_true' => array(
						/**
							* en: 
							* ru: Если валидация прошла успешно
							* ru: Перезаписываем приватный метод валидации
							*/
						),
						'inURLUnitTest' => array(
							'test_1'=>array(
									'1',
									array(
										'test' => '1',
										'test2' => '2',
									)
							),
						),
						'inURLExtController'=>'ext_controller',
						'inURLExtMethod'=>'ext_method',
				),
				// END default method

				// BEGIN some_method
				'some_method'=>array(
				/**
					* en: 
					* ru: публичный метод
					*/
					'inURLSEF' => array(
					/**
						* en: 
						* ru: ЧПУ для метода, формата контроллер\метод\параметры.html
						*/
						'1' => 'profile','.' => '.html',
						/**
							* en: 
							* ru: .html <= флаг для ЧПУ метода
							* ru: Сылка вида: /controller/method/profile.html
							*/
						'1' => 'upload','2' => 'files','page' => '','.' => '.html',
						/**
							* en: 
							* ru: ЧПУ для метода, последовательность вызова
							* 
							* /controller/method/upload-files.html
							* /controller/method/upload-files-2.html
							* /controller/method/upload-files-3.html
							*/
						'user_id' => '','user_name_for_seo' => '','.' => '.html',
						/**
							* en: 
							* ru: Для генерации ссылки любой длинны.
							* 
							* /controller/method/user_name-1-SEO-user-name.html
							*/
					),
					'access'=>array(
					/**
						* en: 
						* ru: Доступ к данному методу
						*/
						'default_access_level' => $access_level['user'],
						/**
							* en: 
							* ru: Уровень доступа по умолчанию
							*/
						'default_private_methods' => 'isHasAccess',
						/**
							* en: 
							* ru: Приватный метод для проверки уровня доступа
							*/
					),
					'controller' => 'method',
					/**
						* en: 
						* ru: Вызов внешнего контроллера с указанным методом
						*/
					'this' => 'default'
					/**
						* en: 
						* ru: Указываем на запрос из текущего контроллера метод по умолчанию
						*/
				),
				// END some_method

				'some_ext_public_method' => array(
					/**
						* en: 
						* ru: Внешний метод для генерации ссылки
						*/
					'inURLController' => 'path',
					/**
						* en: 
						* ru: Внешний контроллер для генерации ссылки
						*/
					'inURLMethod' => 'default'
					/**
						* en: 
						* ru: Внешний метод для генерации ссылки
						*/
				),

				'main' => array('inURLController' => 'main'),
					/**
						* en: 
						* ru: Пример ссылки на главную страницу 
						* ru: В шаблоне <?=$controller_result[inURL][main][pre].$controller_result[inURL][main][post]?>
						*/
				),
			
			// END public_methods
		);
	}
}
?>
