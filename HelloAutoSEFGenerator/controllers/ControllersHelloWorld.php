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
	var $controller_menu_view;//Array controller

														/*Базовый массив контроллера*/
	
	function __construct($access_level){//Initialize the controller with access levels

																			/*Инициализируем контроллер передавая уровни доступа из конфига*/
	
		$this->controller_menu_view = array(
			'public_methods' => array(
					// en: Public methods are available for all	
					// Example: index.php?t=the controller&m=the public method

					/* ru: Публичные методы доступные всем пользователям*/
					/* ru: Пример вызова t=имя контроллера&m=публичный метод	*/

					'default'=>array(
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
															// en: Variable is stored in an array $param['form_data']['PathID']
															// en: Also used in the template if you need to pass parameter through URN (URI)
								
															/* ru: Переменая сохраняется в массив $param['form_data']['PathID']*/
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
						
						'ModelsValidation' => 'isValidModifierParamFormError', //Validation check in method
																																	 //Вызов валидации
					),
			)
		);
	}
} 

?>
