@echo off
@echo %DATE% >test_log.txt
@for /D %%z in (*) do (
call ../Source/update-script/php-unit-test-script.cmd %%z
)
type test_log.txt
pause
