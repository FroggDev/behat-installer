@echo off

set seleniumFolder=%~dp0

set selenium="%seleniumFolder%\selenium-server-standalone-2.46.0.jar"
set ieDriver="%seleniumFolder%\IEDriverServer.exe"
set chromeDriver="%seleniumFolder%\chromedriver.exe"

IF EXIST %selenium% (
java -Dwebdriver.chrome.driver=%chromeDriver% -Dwebdriver.ie.driver=%ieDriver% -jar %selenium%
) else (
echo file %selenium% not found.
REM Make a pause
echo. 
pause
echo. 
)


