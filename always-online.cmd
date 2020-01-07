@echo off

REM Set TIMEOUT to how many seconds you want in between when the server stops to when the next restart takes place

set TIMEOUT=3

cd /d %~dp0

netstat -o -n -a | findstr 0.0.0.0:19132 > NUL

if %ERRORLEVEL% equ 0 (

    goto :loop

) else (

    echo "Script has been initialized."

    goto :start

)

:loop

ping 127.0.0.1 -n %TIMEOUT% > NUL

netstat -o -n -a | findstr 0.0:19132 > NUL

if %ERRORLEVEL% equ 0 (

    goto :loop

) else (

    ping 127.0.0.1 -n %TIMEOUT% > NUL

    echo "Server stopped. It'll be restarted in %TIMEOUT% second(s). You can press Ctrl+C to stop the restart process if you don't want to restart."

    goto :start

)

:start

if exist bin\php\php.exe (

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

if exist bin\php\php_wxwidgets.dll (

    %PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*

) else (

    if exist bin\mintty.exe (

        start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*

    ) else (

        %PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*

    )

)

goto :loop