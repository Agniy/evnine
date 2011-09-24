<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersHelloWorld
{
	var $controller;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => 'ajax',
				'REQUEST' => array('test_id' => '777'),
			),

			'inURLSEF'=> false,
			// en: The SEF URL in the controller mode, the default is false
			// ru: Включене ЧПУ режима в контроллере, по умолчанию false

			'public_methods' => array(
					// en: Public methods are available for all	
					// en: index.php?t=the controller&m=the public method

					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/

					'default'=>array(
						'inURLSEF' => array(
							// en: SEF for method URL like /template/method/array_param.html
							/* ru: ЧПУ для метода, формата /контроллер/метод/параметры.html*/
							// PHP: $array['inURL']['default']['pre'].$array['inURL']['default']['TestID'].$array['REQUEST_OUT']['TestID'].'insert_by_script_TestID'.$array['inURL']['default']['post'];
								'hello',
							// en: any text
							/* ru: любой текст */
								'world',
							// en: any text
							/* ru: любой текст */
								'test_id' => '',
							// en: variable name = TestID of array validation_add
							/* ru: имя переменной = TestID из массива validation_add */	
								'short name not used / любой текст описания' => '777',
							// en: any text
							/* ru: любой текст */
								'.' => '.html',
							/* en: .html <= flag for SEF method, jQuery plugin and .htaccess
							/* ru: .html <= флаг для ЧПУ метода, jQuery плагина и .htaccess*/
							// en: /template/method/array_param.html
							/* ru: контроллер/метод/параметры.html*/
							/*
							.htaccess
							<IfModule mod_rewrite.c>
								RewriteEngine On
								RewriteRule .* - [L]
								RewriteRule ^(.*).(ajax|html)$ index.php?sef=$1&ajax=$2 [NC,L]
							</IfModule>	
              */

						),
						/*
							'inURLSEF' => array(//ЧПУ для метода, последовательность вызова
								'1' => 'Загруженные','2' => 'файлы','page' => '','.' => '.html',
									//Сылка вида: /template/method/Загруженные-файлы.html
									//Сылка вида: /template/method/Загруженные-файлы-2.html
									//Сылка вида: /template/method/Загруженные-файлы-3.html
							),					
							'inURLSEF' => array(//ЧПУ для метода
								'Пользователь' => 'Пользователь','msg_to' => '','user_name_for_seo' => '','.' => '.html',
								//Сылка вида: /template/method/Пользователь-1-Имя-Юзера-Какое-Угодно.html							),				
             */


					// en: Default method is always executed unless you specify
					/* ru: Метод по умолчанию, выполняется всегда если не указан*/
						'inURLMethod' => array(
							// en: Array to generate the URN (URI) to the method
						  /* ru: Массив для генерации ссылок по методу*/
	        	
							'default',
												// en: The array of methods in the controller to generate links
												// en: That would put the link in the template to a method
												// en: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] 
												// en: TWIG: inURL.default.pre, inURL.default.post
												// en: The front part of URI, the end of the URI
												
												/* ru: Список методов в контроллере для генерации ссылок*/
												/* ru: Что бы поставить ссылку в шаблоне на метод*/
												/* ru: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] */
												/* ru: TWIG: inURL.default.pre, inURL.default.post*/
												/* ru: Передняя часть урла, окончания адреса.*/
						),					
					
					'validation_add'
												//{_add - add to validation by default, _form - as form data, _multi_form - multi form for submit method  }
												/*{ ru: _add - добавить в валидацию по умолчанию,_form - как фома ,_multi_form - мулти форма для использования submit}*/
						
						=>array(
										// en: Data for validation are passed to the method
							
										/* ru: Данные для валидации, передаются в метод isValidModifierParamFormError*/
						
							'test_id' => array(
								'to'=>'TestID'
															// en: Variable is stored in an array $param['REQUEST']['TestID']
															// en: Also used in the template if you need to pass parameter through URN (URI)
								
															/* ru: Переменая сохраняется в массив $param['REQUEST']['TestID']*/
															/* ru: Так же используется в шаблоне если нужно передать параметр через URN (URI)*/
								
								,'inURL' => true
														// en: Passing a variable in the URN (URI) for the method.
														// en: Get the variable name - test_id,
														// en: Output by TWIG: inURL.default.TestID in the template will output &test_id=
														// en: PHP: $controller['inURL']['default']['TestID'].'777' in the template &test_id=777
								
														/* ru: Передавить ли переменную в урл для данного метода*/
														/* ru: Вызвать получить значение переменной test_id, */
														/* ru: можно через TWIG: {{inURL.default.TestID}} в шаблоне &test_id=*/
														/* ru: PHP: $controller['inURL']['default']['TestID'].'777' в шаблоне будет вывод &test_id=777*/
								
								,'inURLSave' => true
															// en: Store a parameter in the multi-forms, default is false
															// en: example when you want to save the settings from the prev URI
															// en: In tamplate:
															// en: TWIG:{{inURL.default.pre}}{{inURL.default.TestID}}VAR for method{{inURL.default.post}}
															// en: PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['TestID'].'VAR for method'.$cntler['inURL']['default']['post']
															// en: The output: &test_id[]=1&test_id[]=...
								
															/* ru: Сохранить ли параметр в мульти формах, по умолчанию false*/
															/* ru: Пример когда нужно сохранить параметры из прошлого вызова*/
															/* ru: В шаблонезаторе: */
															/* ru: {inURL.default.pre}{inURL.default.TestID}наш параметр{inURL.default.post}*/
															/* ru: PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['TestID'].'VAR for method'.$cntler['inURL']['default']['post']*/
															/* ru: На выходе: &test_id[]=1&test_id[]=...*/
								
								,'is_array' => false
															// en: Is the variable is an array? Default: false
															// en: Example: &test_id[]=1&test_id[]=23
								
															/* ru: Является ли переменная массивом? по умолчанию false*/
															/* ru: Пример: &test_id[]=1&test_id[]=23*/
								,'type'=>'int'
															// en: {options: str, email, pass, link, html (download htmlpurifier.org)} type validation variable}
															/* ru: {варианты: str,email,pass,link,html (download htmlpurifier.org)} Тип валидации переменной*/
								
								,'required'=>false
															// en: is validation required? default is false.
															/* ru: Обязательна ли переменная для валидации, по умолчанию false*/
								
								,'error'=>'is_empty'// en: What a mistake to pass in the validation 
																		// en: if you do not specify a required parameter by default''
																		
																		/* ru: Какую ошибку передать при валидации */
																		/* ru: если не указан обязательный параметр по умолчанию ''*/
								
								,'max' => '920'
															// en: Maximum value of the variable
															/* ru: Максимальное значение переменной*/
							),
						),
						
						'ModelsValidation' => 'isValidModifierParamFormError', //Validation check in method
																																	 //Вызов валидации
					),
				),
				
			'private_methods' => array(
				// en: Private methods are not available for outside calls.
				// en: available only when a call from the public methods.
				#
				/* ru: Приватные методы не доступные для вызова из вне,*/
				/* ru: доступны только при вызова из публичных методов*/
				#
				'isValidModifierParamFormError_true'=>array(
					'ModelsHelloWorld' => 'getHelloWorld',
				),
			)
				
		);
	}
} 
?>