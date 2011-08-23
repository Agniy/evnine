<?php
 /**
 * HelloWorld
 * @package Controller
 * @author *
 * @version *
 * @updated *
 */
class ControllersParamGenModels
{
	var $controller;
	// en: Array controller
	/* ru: Базовый массив контроллера*/
	function __construct($access_level){
	// en: Initialize the controller with access levels
	/* ru: Инициализируем контроллер передавая уровни доступа из конфига*/
		$this->controller = array(
			'public_methods' => array(//Public methods are available for all
					//Example: index.php?t=the controller&m=the public method
					//Публичные методы доступные всем пользователям
					//Пример вызова t=имя контроллера&m=публичный метод	
					'default'=>array(//Default method is always executed unless you specify
													 //Метод по умолчанию, выполняется всегда если не указан
					 'inURLMethod'=>array('reset_phpunit','default'),
						'ModelsPHPUnit' => array(
//							'getParamCaseByParamTest',
//							'13getHelloWorld', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
							'getParamTest', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
							'getModelsAndControllerModifierTimeFromCache',
							'getParamCaseByParamTest',
							'getCountParamByParamTest',
							'getParamTextName',
							'getDataFromControllerByParam',
							'getPHPUnitCode',
							//'getUniqueCaseModifierParamUniqueCase',
							'getComparePHPUnitForControllers',
						),
//						'ViewsUnitPHP'=>array(
//							'getHTMLCaseHeader',
//							'getHTMLMSGHeader',
//							'getHTMLData'
//						),
					),
					'reset_phpunit' => array(
						'validation_multi_form'/*{_add - добавить,_form - как фома,_multi_form - мулти форма}*/
							=>array(//Данные для валидации, передаются в метод isValidModifierParamFormError
							'reset_type' => array(
								'to'=>'ResetType'//Перемменая сохраняется в массив $param['form_data']['PathID']
								//Так же используется в шаблоне если нужно пердать параметр через URL
								,'inURL' => true//Передавить ли переменную в урл для данного метода
								//Вызвать имя переменной 'path_id', можно через inURL.default.PathID в шаблоне будет вывод &path_id=
								,'type'=>'string'//{варианты: str,html,email,pass,link} Тип валидации переменной
								,'max' => '100'//Максимальное значение переменной
							),
						),
						'ModelsPHPUnit' => array(
							'getResetPHPUnit', //Call Method from class \models\ModelsHelloWorld->getHelloWorld
						)
						,'this'=>'default'
					),
			)
		);
	}
} 
?>
