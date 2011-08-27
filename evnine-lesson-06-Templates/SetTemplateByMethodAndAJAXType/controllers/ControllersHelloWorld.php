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
					'page_level'=>'',
					// en: Depth of the controller, for the parents controller load
					/* ru: Глубина контроллера, для случая подгрузки родителей*/
					'inURLView' => 'page.tpl',
					// en: Template controller 
					/* ru: Шаблон контроллера*/
					'this'=> 'helloworld',
					// en: The name of the current controller of evnine.config.php not used 
					/* ru: Имя текущего контроллера из evnine.config.php для удобства, не используется*/

				'templates' => array(
				// en: An array of display templates, depending on the level of $access_level ($access_level - evnine.config.php)
				/* ru: Массив показа доступных шаблонов в зависимости от уровня пользователя, уровни задаются в $access_level (массив $access_level evnine.config.php) */
				/*
				evnine.config.php: $this->access_level=array(
				evnine.config.php: 	'guest'=>'0',				
				evnine.config.php: );
        */
//					$access_level['admin']=>array(
//					),
//					$access_level['user']=>array(
//					),
					$access_level['guest']=>array(
						'hello2'=>'hello2.tpl',
					)
				),

			'public_methods' => array(
					//en: Public methods are available for all	
					//Example: index.php?t=the controller&m=the public method
					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/
					'default'=>array(
					// en: Default method is always executed unless you specify
					/* ru: Метод по умолчанию, выполняется всегда если не указан*/
						'inURLMethod' => array(
							// en: Array to generate the URN (URI) to the method
						  /* ru: Массив для генерации ссылок по методу*/

							'default',// en: The array of methods in the controller to generate links
												// en: That would put the link in the template to a method
												// en: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] 
												// en: TWIG: inURL.default.pre, inURL.default.post
												// en: The front part of URI, the end of the URI
												
												/* ru: Список методов в контроллере для генерации ссылок*/
												/* ru: Что бы поставить ссылку в шаблоне на метод*/
												/* ru: PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] */
												/* ru: TWIG: inURL.default.pre, inURL.default.post*/
												/* ru: Передняя часть урла, окончания адреса.*/
							'default2'
						),
						'inURLTemplate' => array(
						  // en: Template of 'inURLMethod'
						  // ru: Шаблон заданный в 'inURLMethod'
						  // en: Array for the permanent links in the template, same call, but in different ways
							/* ru: Массив для постоянных ссылок в шаблоне, но на разные методы */
							'info' => 'default2',//Например ссылка inURLTemplate.info будет вести на шаблон по умолчанию
							'error' => 'default2',
							'test' => 'default2',
						),
						'inURLView' => 'AJAXisTrue_inURLView.tpl', 
						// en: Link to the view when you request the method by ajax.
						/* ru: Ссылка на вид при запросе метода через ajax */
						'ModelsHelloWorld' => 'getHelloWorld', 
						// en: Call Method from class \models\ModelsHelloWorld->getHelloWorld
						'validation'
												//{_add - add to validation by default, _form - as form data, _multi_form - multi form for submit method  }
												/*{ ru: _add - добавить в валидацию по умолчанию,_form - как фома ,_multi_form - мулти форма для использования submit}*/
						
						=>array(
										// en: Data for validation are passed to the method
							
										/* ru: Данные для валидации, передаются в метод isValidModifierParamFormError*/
						
							'test_id' => array(
								'to'=>'TestID'
															// en: Variable is stored in an array $param['REQUEST']['PathID']
															// en: Also used in the template if you need to pass parameter through URN (URI)
								
															/* ru: Переменая сохраняется в массив $param['REQUEST']['PathID']*/
															/* ru: Так же используется в шаблоне если нужно передать параметр через URN (URI)*/
								
								,'inURL' => true
														// en: Passing a variable in the URN (URI) for the method.
														// en: Get the variable name - path_id,
														// en: Output by TWIG: inURL.default.PathID in the template will output &path_id=
														// en: PHP: $controller['inURL']['default']['PathID'].'777' in the template &path_id=777
								
														/* ru: Передавить ли переменную в урл для данного метода*/
														/* ru: Вызвать получить значение переменной path_id, */
														/* ru: можно через TWIG: {{inURL.default.PathID}} в шаблоне &path_id=*/
														/* ru: PHP: $controller['inURL']['default']['PathID'].'777' в шаблоне будет вывод &path_id=777*/
								
								,'inURLSave' => true
															// en: Store a parameter in the multi-forms, default is false
															// en: example when you want to save the settings from the prev URI
															// en: In tamplate:
															// en: TWIG:{{inURL.default.pre}}{{inURL.default.PathID}}VAR for method{{inURL.default.post}}
															// en: PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['PathID'].'VAR for method'.$cntler['inURL']['default']['post']
															// en: The output: &path_id[]=1&path_id[]=...
								
															/* ru: Сохранить ли параметр в мульти формах, по умолчанию false*/
															/* ru: Пример когда нужно сохранить параметры из прошлого вызова*/
															/* ru: В шаблонезаторе: */
															/* ru: {inURL.default.pre}{inURL.default.PathID}наш параметр{inURL.default.post}*/
															/* ru: PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['PathID'].'VAR for method'.$cntler['inURL']['default']['post']*/
															/* ru: На выходе: &path_id[]=1&path_id[]=...*/
								
								,'is_array' => false
															// en: Is the variable is an array? Default: false
															// en: Example: &path_id[]=1&path_id[]=23
								
															/* ru: Является ли переменная массивом? по умолчанию false*/
															/* ru: Пример: &path_id[]=1&path_id[]=23*/
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
					),
				'default2'=>array(
						'inURLMethod' => array(
							'default'
						),
						'inURLTemplate' => array(
							'info' => 'default',
							// en: Calling the same, but other links are already in the template
							/* ru: Вызов тот же, но ссылки уже другие для шаблона */
							'error' => 'default',
							'test' => 'default',
						),
						'validation'=>array(
							'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
						)
				),

			)
		);
	}
} 
?>