<?php
include_once 'debug/evnine.debug.php';
include_once 'evnine.php';
include_once 'debug/evnine.views.generator.template.php';
include('index.header.php');
/**
 * en: Use two controllers.
 * en: One model for other view (param in of the model),
 * en: to access the URL Generator.
 * ru: Используем вызов двух контроллеров.
 * ru: Один для модели другой для вида (На входе параметры модели), 
 * ru: для получения доступа к URL генератору.
 */
$evnine = new EvnineController();
$out = $evnine->getControllerForParam(
	array(
		'controller' => 'param_gen_models',
		'ajax' => 'ajax',
		'REQUEST' => $_REQUEST,
	)
);
$output = $evnine->getControllerForParam(
array_merge(
		$out['param_out'],
		array(
			'controller'=>'param_gen_view'
			,'method'=>'default'
			,'inURL'=>$out['inURL']
		)
	)
);
include('index.footer.php');
?>