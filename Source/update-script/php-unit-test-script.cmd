set EXAMPLE=%~1
echo ------------------------[ %EXAMPLE% ]------------------------  >> test_log.txt
echo ------------------------[ %EXAMPLE% ]------------------------
cd %EXAMPLE%/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
set error=%errorlevel%
@if not exist evninePHPUnitTest.php set error=0
if %error%==1 cd..
type test_log.txt
if %error%==1 color 04
if %error%==1 echo ------------------------[ %EXAMPLE% ]------------------------
if %error%==1 echo ERROR [%~1] 
if %error%==1 pause
if %error%==1 exit
if %error%==1 color 08
cd..