echo ------------------------[ HelloWorld ]------------------------  > test_log.txt
cd HelloWorld/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
cd..
echo ------------------------[ HelloWorldConfig ]------------------------  >> test_log.txt
cd HelloWorldConfig/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
cd..
echo ------------------------[ HelloWorldifParamHello ]------------------------  >> test_log.txt
cd HelloWorldifParamHello/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
cd..
echo ------------------------[ HelloWorldParamDiff ]------------------------  >> test_log.txt
cd HelloWorldParamDiff/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
cd..
echo ------------------------[ HelloWorldYAML ]------------------------  >> test_log.txt
cd  HelloWorldYAML/
call phpunit.bat evninePHPUnitTest.php >>../test_log.txt
cd..
type ../test_log.txt
pause
