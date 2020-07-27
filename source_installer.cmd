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

where git >nul 2>nul || (powershell -command "& { iwr https://github.com/git-for-windows/git/releases/download/v2.20.1.windows.1/Git-2.20.1-64-bit.exe -OutFile Git-2.20.1-64-bit.exe }" & start Git-2.20.1-64-bit.exe & pause)

git clone https://github.com/IceCruelStuff/Steadfast5.git --recursive

cd Steadfast5

git clone https://github.com/IceCruelStuff/bin.git --recursive

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist Steadfast5.phar (
	set POCKETMINE_FILE=Steadfast5.phar
) else (
	if exist PocketMine-MP.phar (
		set POCKETMINE_FILE=PocketMine-MP.phar
	) else (
		if exist src\pocketmine\PocketMine.php (
			set POCKETMINE_FILE=src\pocketmine\PocketMine.php
		) else (
			echo "Couldn't find a valid Steadfast5 installation"
			pause
			exit 1
		)
	)
)

REM if exist bin\php\php_wxwidgets.dll (
REM 	%PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*
REM ) else (
	if exist bin\mintty.exe (
		start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
	) else (
		%PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*
	)
REM )
