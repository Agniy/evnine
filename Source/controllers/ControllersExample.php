<?php
/**
	* 
	* Имя /controllers/ControllersExample.php
	* en: An example of the controller with comments and options settings.
	* ru: Пример контроллера с комментариями и вариантами параметров.
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
	//TODO 6% ------------------------------{ 18.08.2011 }------------------------------

	function __construct($access_level){
		$this->controller = array(
			'page_level'=>'',
			/**
				* en: 
				* ru: Глубина контроллера для вызова без AJAX.
				*/
			'parent'=> '',
			/**
				* en: 
				* ru: Родитель контроллера для вызова без AJAX.
				*/
			'inURLView' => '',
			/**
				* en: 
				* ru: Шаблон контроллера
				*/
			'title'=> '',
			/**
				* en: 
				* ru: Название для передачи в шаблон.
				*/
			'this'=> 'example', 
			/**
				* en: 
				* ru: Для удобства, название контроллера.
				* 
				* /evnine.config.php
				* $this->controller_alias=array(
				*  'example'=>'ControllersHelloWorld',
				*);
				*/
			'inURLSEF'=> false,
			/**
				* en: 
				* ru: Включить ЧПУ режима в контроллере, по умолчанию false
				*/
			'access'=>array(
			/**
				* en: 
				* ru: Уровень доступа к меню, а так же для метод проверки доступа.
				*/
				'default_access_level' => $access_level['guest'],
				/**
					* en: 
					* ru: За уровень доступа в модели отвечает $param['PermissionLevel']
					* ru:
					* ru:
					*/
				'default_private_methods' => 'isHasAccess',
				/**
					* en: 
					* ru: В случае когда уровень не совпадает с минимальным
					* ru: запускаем метод проверки в нем мы можем обновить уровень доступа
					* ru: когда проверка запущена для авторизации.
					*/
				'ModelsValidation::isValidCookie'=>array('access_level'=>$access_level['guest']),
			/**
				* en: 
				* ru: Минимальный уровень доступа к запуску метода.
				* ru: Без указания, проверку невозможно запустить в целях безопасности.
				*/
			),
			'templates' => array(
			/**
				* en: 
				* ru: Массив показа шаблона в зависимости от уровня
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

			'private_methods' => array(
			/**
				* en: 
				* ru: Приватные методы не доступные для вызова из вне, только при выполнении публичных методов
				*/
				'isHasAccess'=>array(
				/**
					* en: 
					* ru: Проверить, а есть ли доступ?
					*/
					'ModelsValidation'  => 'isValidCookie',
					/**
						* en: 
						* ru: Например для валидации проверяем куки
						*/
				),
				'isValidCookie_false'  => array(
				/**
					* en: 
					* ru: Случай когда валидация не пройдена
					*/
					'ModelsErrors'=>'getError->no_auth'
					/**
						* en: 
						* ru: Если доступа нет выполнен данный метод
						*/
				),
				'isHasAccess_false' => array(
				/**
					* en: 
					* ru: Вызываем в модели ошибок отображение ошибки
					*/
					'ModelsErrors'=>'getError',
					/**
						* en: 
						* ru: Если доступа есть выполним данный метод
						*/
				),
				'isHasAccess_true' => array(
				/**
					* en: 
					* ru: Вызываем в модели информации
					*/
					'ModelsInfo'=>array(
					/**
						* en: 
						* ru: Метод получения информации с параметром OK
						*/
						'getInfo->ok',
					)
				),
				'isValidModifierParamFormError_false' => array(
				/**
					* en: 
					* ru: В случае ошибки валидации 
					*/
					'ModelsErrors'=>'getError',
					/**
						* en: 
						* ru: Запускаем метод отображения ошибки.
						*/
				),
				'isValidModifierParamFormError_true' => array(
				/**
					* en: 
					* ru: Если валидация прошла успешно
					*/
				)
			),

			'public_methods' => array(
			/**
				* en: 
				* en: 
				* ru: Публичные методы доступные всем пользователям
				* ru: Пример вызова c=имя контроллера&m=публичный метод	
				*/
					'default'=>array(
					/**
						* en: 
						* ru: Метод по умолчанию, выполняется всегда если не указан.
						* ru: В шаблонизаторе доступ по $controller_result['ViewMethod']['default']
						* ru: другой публичный метод
						* ru: А так же для генерации урлов
						*/
						'inURLMethod' => array(
						/**
							* en: 
							* ru: Массив для генерации ссылок
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
						* en: 
						* ru: Массив для постоянных ссылок в шаблоне, но на разные методы
						*/
							'info' => 'default',
							/**
								* en: 
								* ru: Например ссылка inURLTemplate.info будет вести на шаблон по умолчанию
								*/
							'error' => 'default',
							'test' => 'default',
						),
						'inURLView' => 'ajax.php',
						/**
							* en: 
							* ru: Ссылка на шаблон для метода.
							*/
							'validation'
							/**
								* en: 
								* ru: Тип валидации для проверки входных данных из $param['REQUEST']
								* ru: и для создания форм и ссылок с ЧПУ.
								* 
								* /index.php
								* $output = $evnine->getControllerForParam(
								*  array(
								*   'controller' => 'param_gen',
								*   'REQUEST'=>$_REQUEST,
								*  )
								* );
								* 
								* ru: Может быть:
								* ru: validation - перезаписывает валидацию из метода по умолчанию - default.
								* ru: validation_add - добавить к валидации из метода по умолчанию - default.
								* ru: validation_form - создать форму.
								* ru: validation_multi_form - создать множественную форму, 
								* ru: когда разные методы могут использовать одни и те же данные.
								*/
								=>array(//
								/**
									* en: 
									* ru: Данные для валидации, передаются в метод isValidModifierParamFormError
									*/
							'path_id' => array(
								'to'=>'PathID'
								/**
									* en: Переменная сохранится в $param['REQUEST']['PathID']
									* ru: массив для ссылки URN будет доступен по ключу $output['inURL']['PathID']
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
									* $output[inURL][default][pre].$output[inURL][default][PathID].'VAR'.[inURL][default][post]
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
						'ModelsValidation' => 'isValidModifierParamFormError',
						/**
							* en: 
							* ru: Вызов валидации c данными из массива validation{_add,_form,_multi_form}
							*/
					),

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
								* en: .html <= флаг для ЧПУ метода
								* ru: Сылка вида: /controller/method/profile.html
								*/
							'1' => 'upload','2' => 'files','page' => '','.' => '.html',
							/**
								* en: 
								* ru: ЧПУ для метода, последовательность вызова
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
						/*
							'inURLSEF' => array(//ЧПУ для метода
							
             */
						//'access'=>array(//Доступ к данному методу
							//'default_access_level' => $access_level['user'],//Уровень доступа по умолчанию
							//'default_private_methods' => 'isHasAccess',//Приватный метод для проверки уровня доступа
						//),
						//'inURLMethod_add' => array('default'),//Добавляем исходя из текущего метода
						/*
							А если 'inURLMethod' => array()//Перезаписываем default метод для генерации URL
            */
						//ссылки на другие методы, в данном случае на метод по умолчанию
						//Валидация
						//'validation_add'=>array(),//Массив _add когда данные добавляются в 
						//публичный массив метода default и передаются валидатору
						//'validation'=>array()//Указывает что нужно использовать только текущий метод валидации метода 
						//И не нужно объединять с default валидацией

						//'validation_form'=>	array(),//Массив _form когда данные передаются через форму
						//Пример для TWIG
						//<form action="{{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}" method="post">
						//Поля в формах по умолчанию {{ inURLTemplate.info.inputs }}
						//<input name="{{ inURL.some_method.PathID }}" value="">
						//</form>
						//'validation_multi_form'=>	array()//Массив _multi_form когда одна и та же форма
						//может передаватся в любой другой метод
						//Пример формы для TWIG для вывода формы:
						//<form action="{{ inURLTemplate.info.pre }}{{ inURLTemplate.info.post }}" method="post">
						//Поля в формах по умолчанию {{ inURLTemplate.info.inputs }}
						//<input type="submit" name="{{ inURL.some_method.submit }}" value="OK"/>
						//При клике на ОК вызываем метод some_method
						//<input type="submit" name="{{ inURL.some_method_a.submit }}" value="A"/>
						//При клике на A вызываем метод some_method_a
						//<input type="submit" name="{{ inURL.some_method_b.submit }}" value="B"/>
						//При клике на B вызываем метод some_method_b
						//<input type="submit" name="{{ inURL.some_method_c.submit }}" value="C"/>
						//При клике на C вызываем метод some_method_c
						//</form>
						//'*controller*' => '*method*',//Вызов внешнего котроллера с указанным методом
						//'ModelsValidation' => 'isValidModifierParamFormError', //Вызов валидации 
						//'isValidModifierParamFormError_true' => array(//Перезаписываем приватный метод валидации
							//Если валидация прошла успешно
						//),
						//'this' => 'default',//Указываем на запрос из текущего котроллера метод по умолчанию
//					),

					//'some_ext_public_method' => array(//Обычны внешний метод
						//'template' => 'path',//Внешний контроллер для генерации ссылки
						//'method' => 'default',//Внешний метод для генерации ссылки
					),
//
					//'main' => array('template' => 'main'),//Ссылка на главную страницу 
					//Вызов в шаблонизаторе $controller_result[inURL][main][pre].$controller_result[inURL][main][post]
			),

		);
		}
}
?>
