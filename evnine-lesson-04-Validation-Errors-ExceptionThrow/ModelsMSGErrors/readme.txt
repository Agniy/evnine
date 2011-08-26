en: Model display errors.
ru: Модель отображения ошибок.

en: Pass the array REQUEST of model validation.
ru: Передаём массив в модель валидации.
/index.php
	$evnine = new Controller();
	$ctrlr = $evnine->getControllerForParam(
		array(
			'controller' => 'validation',
			'REQUEST' => array('path_id' => ''),
			'ajax' => 'ajax',
		)
	);

en: Model validation.
ru: Модель валидации.
/evnine.config.php
	$this->class_path=array(
			'ModelsErrors'=>array(
				'path'=>'models'.DIRECTORY_SEPARATOR,
			),
	)

en: Controller for validation.
ru: Контроллер для валидации
/controllers/ControllersHelloValidation.php
	'validation_add'=>array(
		'path_id' => array(
			'to'=>'PathID'
			,'inURL' =>
			,'inURLSave' => true
			,'is_array' => false
			,'type'=>'int'
			,'required'=>true
			,'error'=>'is_empty'
			,'max' => '920'
		),
	),

en: Validation check in method
ru: Вызов метода валидации
	'ModelsValidation' => 
		'isValidModifierParamFormError'
	
en: Private methods are not available for outside calls.
en: available only when a call from the public methods.
ru: Приватные методы не доступные для вызова из вне,
ru: доступны только при вызова из публичных методов
	'private_methods' => array(
		'isValidModifierParamFormError_false'=>array(
			'ModelsErrors'=>array(
				'getError',
				'getError->alternative_way_of_setting_errors'
				)
		),
	)