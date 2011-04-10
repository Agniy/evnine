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
					//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					
					/*Публичные методы доступные всем пользователям*/
					/*Пример вызова t=имя контроллера&m=публичный метод	*/
				
					'default'=>array(//Default method is always executed unless you specify

													 /*Метод по умолчанию, выполняется всегда если не указан*/

						'inURLMethod' => array(//Array to generate the URN (URI) to the method

																	 /*Массив для генерации ссылок по методу*/
						
							'default',//The array of methods in the controller to generate links
												//That would put the link in the template to a method
												//PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] 
												//TWIG: inURL.default.pre, inURL.default.post
												//The front part of URI, the end of the URI
												
												/*Список методов в контроллере для генерации ссылок*/
												/*Что бы поставить ссылку в шаблоне на метод*/
												/*PHP: $controller['inURL']['default']['pre'], $controller['inURL']['default']['post'] */
												/*TWIG: inURL.default.pre, inURL.default.post*/
												/*Передняя часть урла, окончания адреса.*/
							'post'
						),
						'validation_add'//{_add - add to validation by default, _form - as form data, _multi_form - multi form for submit method  }

														/*{_add - добавить в валидацию по умолчанию,_form - как фома ,_multi_form - мулти форма для использования submit}*/
						
						=>array(//Data for validation are passed to the method
							
										/*Данные для валидации, передаются в метод isValidModifierParamFormError*/
						
							'path_id' => array(
								'to'=>'PathID'//Variable is stored in an array $param['form_data']['PathID']
															//Also used in the template if you need to pass parameter through URN (URI)
								
															/*Переменая сохраняется в массив $param['form_data']['PathID']*/
															/*Так же используется в шаблоне если нужно передать параметр через URN (URI)*/
								
								,'inURL' => true
														//Passing a variable in the URN (URI) for the method.
														//Get the variable name - path_id,
														//Output by TWIG: inURL.default.PathID in the template will output &path_id=
														//PHP: $controller['inURL']['default']['PathID'].'777' in the template &path_id=777
								
														/*Передавить ли переменную в урл для данного метода*/
														/*Вызвать получить значение переменной path_id, */
														/*можно через TWIG: {{inURL.default.PathID}} в шаблоне &path_id=*/
														/*PHP: $controller['inURL']['default']['PathID'].'777' в шаблоне будет вывод &path_id=777*/
								
								,'inURLSave' => true
															//Store a parameter in the multi-forms, default is false
															//example when you want to save the settings from the prev URI
															//In tamplate:
															//TWIG:{{inURL.default.pre}}{{inURL.default.PathID}}VAR for method{{inURL.default.post}}
															//PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['PathID'].'VAR for method'.$cntler['inURL']['default']['post']
															//The output: &path_id[]=1&path_id[]=...
								
															/*Сохранить ли параметр в мульти формах, по умолчанию false*/
															/*Пример когда нужно сохранить параметры из прошлого вызова*/
															/*В шаблонезаторе: */
															/*{inURL.default.pre}{inURL.default.PathID}наш параметр{inURL.default.post}*/
															/*PHP:$cntler['inURL']['default']['pre'].$cntler['inURL']['default']['PathID'].'VAR for method'.$cntler['inURL']['default']['post']*/
															/*На выходе: &path_id[]=1&path_id[]=...*/
								
								,'is_array' => true
															//Is the variable is an array? Default: false
															//Example: &path_id[]=1&path_id[]=23
								
															/*Является ли переменная массивом? по умолчанию false*/
															/*Пример: &path_id[]=1&path_id[]=23*/
								,'type'=>'int'
															//{options: str, email, pass, link, html (download htmlpurifier.org)} type validation variable}
								
															/*{варианты: str,email,pass,link,html (download htmlpurifier.org)} Тип валидации переменной*/
								
								,'required'=>false
															//is validation required? default is false.
								
															/*Обязательна ли переменная для валидации, по умолчанию false*/
								
								,'error'=>'is_empty'//What a mistake to pass in the validation 
																		//if you do not specify a required parameter by default''
																		
																		/*Какую ошибку передать при валидации */
																		/*если не указан обязательный параметр по умолчанию ''*/
								
								,'max' => '920'//Maximum value of the variable
								
															 /*Максимальное значение переменной*/
							
							),
						),
						'ModelsValidation' => 'isValidModifierParamFormError', //Validation check in method
																																	 //Вызов валидации
					),

										'post'=>array(//Default method is always executed unless you specify

													 /*Метод по умолчанию, выполняется всегда если не указан*/

						'validation_form'//{_add - add to validation by default, _form - as form data, _multi_form - multi form for submit method  }

														/*{_add - добавить в валидацию по умолчанию,_form - как фома ,_multi_form - мулти форма для использования submit}*/
						
						=>array(//Data for validation are passed to the method
							
										/*Данные для валидации, передаются в метод isValidModifierParamFormError*/
						
							'path_id' => array(
								'to'=>'PathID','inURL' => true,'inURLSave' => true,'is_array' => true,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'
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
