@echo off

REM ------------------------------------------------------------------------
REM The agavi build script for Windows based systems
REM ------------------------------------------------------------------------

REM ------------------------------------------------------------------------
REM Change this to reflect your environment if the default value doesn't work
REM ------------------------------------------------------------------------

set PHING_COMMAND=phing
REM e.g. set PHING_COMMAND=c:\webserv\php\phing.bat
set AGAVI_INSTALLATION=c:/xampp/htdocs/our/www/vendor/agavi
REM e.g. set AGAVI_INSTALLATION=c:/htdocs/libs/agavi/src

REM -------------------------------------------------------------------------
REM Do not change anything below this line unless you know what you're doing.
REM -------------------------------------------------------------------------
set PWD_PATH=%CD%
for /F "tokens=1-26* delims=\" %%a in ("%PWD_PATH%") do (
set toka=%%a
set tokb=%%b
set tokc=%%c
set tokd=%%d
set toke=%%e
set tokf=%%f
set tokg=%%g
set tokh=%%h
set toki=%%i
set tokj=%%j
set tokk=%%k
set tokl=%%l
set tokm=%%m
set tokn=%%n
set toko=%%o
set tokp=%%p
set tokq=%%q
set tokr=%%r
set toks=%%s
set tokt=%%t
set toku=%%u
set tokv=%%v
set tokw=%%w
set tokx=%%x
set toky=%%y
set tokz=%%z
)
if defined toka set CWD_NAME=%toka%
SET toka=
if defined tokb set CWD_NAME=%tokb%
SET tokb=
if defined tokc set CWD_NAME=%tokc%
SET tokc=
if defined tokd set CWD_NAME=%tokd%
SET tokd=
if defined toke set CWD_NAME=%toke%
SET toke=
if defined tokf set CWD_NAME=%tokf%
SET tokf=
if defined tokg set CWD_NAME=%tokg%
SET tokg=
if defined tokh set CWD_NAME=%tokh%
SET tokh=
if defined toki set CWD_NAME=%toki%
SET toki=
if defined tokj set CWD_NAME=%tokj%
SET tokj=
if defined tokk set CWD_NAME=%tokk%
SET tokk=
if defined tokl set CWD_NAME=%tokl%
SET tokl=
if defined tokm set CWD_NAME=%tokm%
SET tokm=
if defined tokn set CWD_NAME=%tokn%
SET tokn=
if defined toko set CWD_NAME=%toko%
SET toko=
if defined tokp set CWD_NAME=%tokp%
SET tokp=
if defined tokq set CWD_NAME=%tokq%
SET tokq=
if defined tokr set CWD_NAME=%tokr%
SET tokr=
if defined toks set CWD_NAME=%toks%
SET toks=
if defined tokt set CWD_NAME=%tokt%
SET tokt=
if defined toku set CWD_NAME=%toku%
SET toku=
if defined tokv set CWD_NAME=%tokv%
SET tokv=
if defined tokw set CWD_NAME=%tokw%
SET tokw=
if defined tokx set CWD_NAME=%tokx%
SET tokx=
if defined toky set CWD_NAME=%toky%
SET toky=
if defined tokz set CWD_NAME=%tokz%
SET tokz=

REM (currently this is not reached)
REM if (test -z "$PHING_COMMAND") ; then
REM	echo "WARNING: PHP_COMMAND environment not set. (Assuming phing on PATH)"
REM	export PHING_COMMAND=php
REM fi

%PHING_COMMAND% -f "%AGAVI_INSTALLATION%/build.xml" -Dagavi.dir="%AGAVI_INSTALLATION%" -Dproject.dir="%PWD_PATH%" -Dcwd_name="%CWD_NAME%" %*
