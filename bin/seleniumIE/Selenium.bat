@echo off

set seleniumFolder=%~dp0

set selenium="%seleniumFolder%\selenium-server-standalone-2.46.0.jar"
set idDriver="%seleniumFolder%\IEDriverServer.exe"

IF EXIST %selenium% (
java -Dwebdriver.ie.driver=%idDriver% -jar %selenium%
) else (
echo file %selenium% not found.
REM Make a pause
echo. 
pause
echo. 
)


