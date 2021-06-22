[CmdletBinding(PositionalBinding=$false)]
param (
	[string]$php = "",
	[switch]$Loop = $false,
	[string]$file = "",
	[string][Parameter(ValueFromRemainingArguments)]$extraPocketMineArgs
)

if ($php -ne "") {
	$binary = $php
} elseif (Test-Path "bin\php\php.exe") {
	$env:PHPRC = ""
	$binary = "bin\php\php.exe"
} else {
	$binary = "php"
}

if ($file -eq "") {
	if (Test-Path "Steadfast5.phar") {
	    $file = "Steadfast5.phar"
	} else {
	    echo "Steadfast5.phar not found"
	    echo "Downloads can be found at https://github.com/Steadfast5/Steadfast5/releases"
	    pause
	    exit 1
	}
}

function StartServer {
	$command = "powershell -NoProfile " + $binary + " " + $file + " " + $extraPocketMineArgs
	iex $command
}

$loops = 0

StartServer

while ($Loop) {
	if ($loops -ne 0) {
		echo ("Restarted " + $loops + " times")
	}
	$loops++
	echo "To escape the loop, press CTRL+C now. Otherwise, wait 5 seconds for the server to restart."
	echo ""
	Start-Sleep 5
	StartServer
}
