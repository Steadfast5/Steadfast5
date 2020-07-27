@echo off

::
::   _____ _                 _  __          _   _____ 
::  / ____| |               | |/ _|        | | | ____|
:: | (___ | |_ ___  __ _  __| | |_ __ _ ___| |_| |__  
::  \___ \| __/ _ \/ _` |/ _` |  _/ _` / __| __|___ \ 
::  ____) | ||  __/ (_| | (_| | || (_| \__ \ |_ ___) |
:: |_____/ \__\___|\__,_|\__,_|_| \__,_|___/\__|____/ 
::
:: This program is free software: you can redistribute it and/or modify
:: it under the terms of the GNU Lesser General Public License as published by
:: the Free Software Foundation, either version 3 of the License, or
:: (at your option) any later version.
::
:: @author Steadfast5 Team
:: @link https://steadfast5.tk
::

TITLE Steadfast5 server software for Minecraft: Bedrock Edition
cd /d %~dp0

cd /d %~dp0 goto PMSTART

mkdir Steadfast5


cd Steadfast5

powershell -command "& { iwr https://github.com/Steadfast5/Steadfast5/releases/latest/download/Steadfast5.phar -OutFile Steadfast5.phar }"

powershell -command "& { iwr https://jenkins.pmmp.io/job/PHP-7.3-Aggregate/lastSuccessfulBuild/artifact/PHP-7.3-Windows-x64.zip -OutFile PHP-7.3-Windows-x64.zip }"
powershell -command "Expand-Archive -Path PHP-7.3-Windows-x64.zip -DestinationPath ."

powershell -command "& { iwr https://raw.githubusercontent.com/Steadfast5/Installer-scripts/master/installer/start.cmd -OutFile start.cmd }"

del PHP-7.3-Windows-x64.zip

echo Steadfast5 has been installed. To start your server, run the start.cmd file. You can now safely close this window.
pause
exit 1
