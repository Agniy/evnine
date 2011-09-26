cls
@set lang=""
@if not -%1==- set lang=%1
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-01-Hello-World\HelloWorld" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-01-Hello-World\HelloWorldConfig" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-01-Hello-World\HelloWorldifParamHello" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-01-Hello-World\HelloWorldParamDiff" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-01-Hello-World\HelloWorldYAML" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-02-jQuery-AJAX-Extend-Parents\CallAJAXParent" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-02-jQuery-AJAX-Extend-Parents\jQueryAJAXAnchorNavigation" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-02-jQuery-AJAX-Extend-Parents\ThisAndExtendController" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-03-API=MySQL-Bitrix-Joomla\BitrixAPI" %lang% "bitrix\modules\evnine\classes\general\"
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-03-API=MySQL-Bitrix-Joomla\BitrixAPITwig" %lang% "bitrix\modules\evnine\classes\general\"
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-03-API=MySQL-Bitrix-Joomla\JoomlaAPI" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-03-API=MySQL-Bitrix-Joomla\MySQL" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-04-Validation-Errors-ExceptionThrow\ModelsMSGErrors" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-04-Validation-Errors-ExceptionThrow\ModelsMSGExceptionThrowError" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-04-Validation-Errors-ExceptionThrow\ModelsMSGInfo" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-04-Validation-Errors-ExceptionThrow\Validation" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\ExtendController-Method" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\Form" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\FormMultiMethodSumbit" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\GETArray" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\SEFForController" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\SEFForMethod" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\URIWithParamFromInput" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-05-HREF-SEF-FORM\URIWithTemplateSetVar" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-06-Templates\Creator-PHP-PHPShort-Twig" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-06-Templates\OneKeyForAllURLTemplates" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-06-Templates\SetTemplateByMethodAndAJAXType" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-06-Templates\Template-engine-Smarty" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-06-Templates\Template-engine-Twig" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-07-PHPUnitTest-UML\FrozenDataForDebug" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-07-PHPUnitTest-UML\PHPUnitTestGenerator" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-07-PHPUnitTest-UML\UML2Models" %lang%
call Source\update-script\update-lessons-from-source-script.cmd "evnine-lesson-07-PHPUnitTest-UML\VisualTestGenerator" %lang%