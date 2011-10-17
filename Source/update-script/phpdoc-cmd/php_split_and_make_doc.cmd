title split src and make doc
set PATH_TO_THIS=update-script/phpdoc-cmd/
set PATH_TO_SCRIPT=%PATH_TO_THIS%
set PATH_TO_RU=ru
set PATH_TO_EN=en
set SPLIT_MODE=WITH_BR
cd ../..
mkdir "%PATH_TO_RU%"
mkdir "%PATH_TO_RU%/controllers"
mkdir "%PATH_TO_RU%/test"
mkdir "%PATH_TO_RU%/models"
mkdir "%PATH_TO_RU%/debug"
mkdir "%PATH_TO_EN%"
mkdir "%PATH_TO_EN%/controllers"
mkdir "%PATH_TO_EN%/test"
mkdir "%PATH_TO_EN%/models"
mkdir "%PATH_TO_EN%/debug"
:SPLIT_FILES
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ControllersExample.php controllers/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd index.php "" "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsInfo.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd evnine.php "" "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd evnine.config.php "" "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd phpunit.php test/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd evnine.debug.php debug/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd evnine.views.generator.template.php debug/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd evnine.views.generator.template.config.php debug/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ControllersPHPUnit.php test/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsBitrix.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsBitrixInfoBlockParser.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsErrors.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsInfo.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsJoomla.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsMySQL.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsPHPUnit.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
call %PATH_TO_THIS%php_split_en_ru.sh.cmd ModelsValidation.php models/ "%PATH_TO_SCRIPT%" %SPLIT_MODE%
if -%SPLIT_MODE%==- goto end
cd "%PATH_TO_RU%"
call php c:\php\pear\phpdoctor\phpdoc.php c:\php\pear\phpdoctor\default.ini
cd ..
cd "%PATH_TO_EN%"
call php c:\php\pear\phpdoctor\phpdoc.php c:\php\pear\phpdoctor\default.ini
cd ..
mkdir "../Docs/PHPDoc/PHPDoc-ru"
mkdir "../Docs/PHPDoc/PHPDoc-en"
xcopy /E /Y "%PATH_TO_RU%/phpdoc" "../Docs/PHPDoc/PHPDoc-ru"
xcopy /E /Y "%PATH_TO_EN%/phpdoc" "../Docs/PHPDoc/PHPDoc-en"
RD /Q /S "%PATH_TO_RU%/phpdoc"
RD /Q /S "%PATH_TO_EN%/phpdoc"
set SPLIT_MODE=
goto SPLIT_FILES
:END