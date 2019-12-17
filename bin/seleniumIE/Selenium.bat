@ECHO off

SET SELENIUMFOLDER=%~dp0

SET SELENIUM="%SELENIUMFOLDER%\selenium-server-standalone-2.46.0.jar"
SET IEDRIVER="%SELENIUMFOLDER%\IEDriverServer.exe"
SET CHROMEDRIVER="%SELENIUMFOLDER%\chromedriver.exe"


REM check if can find selenium
IF NOT EXIST %SELENIUM% (
ECHO "Cannot find %SELENIUM%"
PAUSE
EXIT 1
)

REM check if can find ieDriver
IF NOT EXIST %IEDRIVER% (
ECHO "Cannot find %IEDRIVER%"
PAUSE
EXIT 1
)

REM check if can find chromeDriver
IF NOT EXIST %CHROMEDRIVER% (
ECHO "Cannot find %CHROMEDRIVER%"
PAUSE
EXIT 1
)

REM Start selenium with IE driver and chrome driver
java -Dwebdriver.chrome.driver=%CHROMEDRIVER% -Dwebdriver.ie.driver=%IEDRIVER% -jar %SELENIUM%



