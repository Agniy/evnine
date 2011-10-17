@echo off
@set lang_dir=""
@if not -%~2==- set lang_dir=%~2\
@set sub_path="%~3"
@if exist "%~1\%sub_path%js\jq.evnine.nav.js" xcopy "Source\%lang_dir%js\jq.evnine.nav.js" "%~1\%sub_path%js\jq.evnine.nav.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.evnine.func.js" xcopy "Source\%lang_dir%js\jq.evnine.func.js" "%~1\%sub_path%js\jq.evnine.func.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.evnine.debug.js" xcopy "Source\%lang_dir%js\jq.evnine.debug.js" "%~1\%sub_path%js\jq.evnine.debug.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.include.js" xcopy "Source\%lang_dir%js\jq.include.js" "%~1\%sub_path%js\jq.include.js" /I /Y /U /H
@if exist "%~1\%sub_path%test\ControllersPHPUnit.php" xcopy "Source\%lang_dir%test\ControllersPHPUnit.php" "%~1\%sub_path%test\ControllersPHPUnit.php" /I /Y /U /H
@if exist "%~1\%sub_path%test\phpunit.php" xcopy "Source\%lang_dir%test\phpunit.php" "%~1\%sub_path%test\phpunit.php" /I /Y /U /H
@if not exist "%~1\%sub_path%\evnine.controller.php" if exist "%~1\%sub_path%evnine.php" xcopy "Source\%lang_dir%evnine.php" "%~1\%sub_path%\evnine.php" /I /Y /U /H
@if exist "%~1\%sub_path%evnine.controller.php" xcopy "Source\%lang_dir%evnine.php" "%~1\%sub_path%\evnine.controller.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.debug.php" xcopy "Source\%lang_dir%debug\evnine.debug.php" "%~1\%sub_path%debug\evnine.debug.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.views.generator.template.php" xcopy "Source\%lang_dir%debug\evnine.views.generator.template.php" "%~1\%sub_path%debug\evnine.views.generator.template.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.views.generator.template.config.php" xcopy "Source\%lang_dir%debug\evnine.views.generator.template.config.php" "%~1\%sub_path%debug\evnine.views.generator.template.config.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsBitrix.php" xcopy "Source\%lang_dir%models\ModelsBitrix.php" "%~1\%sub_path%models\ModelsBitrix.php" /I /Y /U /H
@if exist "%~1\%sub_path%controllers\ControllersExample.php" xcopy "Source\%lang_dir%controllers\ControllersExample.php" "%~1\%sub_path%controllers\ControllersExample.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsBitrixInfoBlockParser.php" xcopy "Source\%lang_dir%models\ModelsBitrixInfoBlockParser.php" "%~1\%sub_path%models\ModelsBitrixInfoBlockParser.php"  /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsErrors.php" xcopy "Source\%lang_dir%models\ModelsErrors.php" "%~1\%sub_path%models\ModelsErrors.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsInfo.php" xcopy "Source\%lang_dir%models\ModelsInfo.php" "%~1\%sub_path%models\ModelsInfo.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsJoomla.php" xcopy "Source\%lang_dir%models\ModelsJoomla.php" "%~1\%sub_path%models\ModelsJoomla.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsMySQL.php" xcopy "Source\%lang_dir%models\ModelsMySQL.php" "%~1\%sub_path%models\ModelsMySQL.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsPHPUnit.php" xcopy "Source\%lang_dir%models\ModelsPHPUnit.php" "%~1\%sub_path%models\ModelsPHPUnit.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsValidation.php" xcopy "Source\%lang_dir%models\ModelsValidation.php" "%~1\%sub_path%models\ModelsValidation.php" /I /Y /U /H
cd %~1
@if exist evninePHPUnitTest.php call phpunit.bat evninePHPUnitTest.php
set error=%errorlevel%
@if not exist phpunit.bat set error=0
if %error%==1 color 04
if %error%==1 echo ERROR [%~1] 
if %error%==1 pause
if %error%==1 exit
if %error%==1 color 08
cd..
cd..

