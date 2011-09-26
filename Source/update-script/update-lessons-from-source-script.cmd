@echo off
@set lang=""
@if not -%~2==- set lang=%~2\
@set sub_path="%~3"
@if exist "%~1\%sub_path%js\jq.evnine.nav.js" xcopy "Source\%lang%js\jq.evnine.nav.js" "%~1\%sub_path%js\jq.evnine.nav.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.evnine.func.js" xcopy "Source\%lang%js\jq.evnine.func.js" "%~1\%sub_path%js\jq.evnine.func.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.evnine.debug.js" xcopy "Source\%lang%js\jq.evnine.debug.js" "%~1\%sub_path%js\jq.evnine.debug.js" /I /Y /U /H
@if exist "%~1\%sub_path%js\jq.include.js" xcopy "Source\%lang%js\jq.include.js" "%~1\%sub_path%js\jq.include.js" /I /Y /U /H
@if exist "%~1\%sub_path%test\ControllersPHPUnit.php" xcopy "Source\%lang%test\ControllersPHPUnit.php" "%~1\%sub_path%test\ControllersPHPUnit.php" /I /Y /U /H
@if exist "%~1\%sub_path%test\phpunit.php" xcopy "Source\%lang%test\phpunit.php" "%~1\%sub_path%test\phpunit.php" /I /Y /U /H
@if not exist "%~1\%sub_path%\evnine.controller.php" if exist "%~1\%sub_path%evnine.php" xcopy "Source\%lang%evnine.php" "%~1\%sub_path%\evnine.php" /I /Y /U /H
@if exist "%~1\%sub_path%evnine.controller.php" xcopy "Source\%lang%evnine.php" "%~1\%sub_path%\evnine.controller.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.debug.php" xcopy "Source\%lang%debug\evnine.debug.php" "%~1\%sub_path%debug\evnine.debug.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.views.generator.template.php" xcopy "Source\%lang%debug\evnine.views.generator.template.php" "%~1\%sub_path%debug\evnine.views.generator.template.php" /I /Y /U /H
@if exist "%~1\%sub_path%debug\evnine.views.generator.template.config.php" xcopy "Source\%lang%debug\evnine.views.generator.template.config.php" "%~1\%sub_path%debug\evnine.views.generator.template.config.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsBitrix.php" xcopy "Source\%lang%models\ModelsBitrix.php" "%~1\%sub_path%models\ModelsBitrix.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsBitrixInfoBlockParser.php" xcopy "Source\%lang%models\ModelsBitrixInfoBlockParser.php" "%~1\%sub_path%models\ModelsBitrixInfoBlockParser.php"  /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsErrors.php" xcopy "Source\%lang%models\ModelsErrors.php" "%~1\%sub_path%models\ModelsErrors.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsInfo.php" xcopy "Source\%lang%models\ModelsInfo.php" "%~1\%sub_path%models\ModelsInfo.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsJoomla.php" xcopy "Source\%lang%models\ModelsJoomla.php" "%~1\%sub_path%models\ModelsJoomla.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsMySQL.php" xcopy "Source\%lang%models\ModelsMySQL.php" "%~1\%sub_path%models\ModelsMySQL.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsPHPUnit.php" xcopy "Source\%lang%models\ModelsPHPUnit.php" "%~1\%sub_path%models\ModelsPHPUnit.php" /I /Y /U /H
@if exist "%~1\%sub_path%models\ModelsValidation.php" xcopy "Source\%lang%models\ModelsValidation.php" "%~1\%sub_path%models\ModelsValidation.php" /I /Y /U /H
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

