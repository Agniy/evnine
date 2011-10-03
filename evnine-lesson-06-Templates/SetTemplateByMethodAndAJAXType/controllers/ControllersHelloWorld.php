<?php
/**
 * ControllersHelloValidation
 * @package Controller
 */
class ControllersHelloWorld
{
	var $controller;
	function __construct($access_level){
		$this->controller = array(
			'inURLUnitTest' => array(
				'ajax' => array('false','ajax'),
			),
			'page_level'=>'',
			'inURLView' => 'page.tpl',
			'this'=> 'helloworld',
			'templates' => array(
				$access_level['guest']=>array(
					'hello2'=>'hello2.tpl',
				)
			),

			'public_methods' => array(
				
				'default'=>array(
					'inURLMethod' => array(
						'default','default2'
					),
					'inURLTemplate' => array(
						'info' => 'default2',
						'error' => 'default2',
						'test' => 'default2',
					),
					'inURLView' => 'AJAXisTrue_inURLView.tpl', 
					'ModelsHelloWorld' => 'getHelloWorld', 
					'validation'=>array(
						'test_id' => array(
							'to'=>'TestID'
								,'inURL' => true
								,'inURLSave' => true
								,'is_array' => false
								,'type'=>'int'
								,'required'=>false
								,'error'=>'is_empty'
								,'max' => '920'
						),
					),
				),
					
				'default2'=>array(
					'inURLMethod' => array(
						'default'
					),
					'inURLTemplate' => array(
						'info' => 'default',
						'error' => 'default',
						'test' => 'default',
					),
					'validation'=>array(
						'test_id' => array('to'=>'TestID','inURL' => true,'inURLSave' => true,'is_array' => false,'type'=>'int','required'=>false,'error'=>'is_empty','max' => '920'),
					)
				)
			)
		);
	}
} 
?>
