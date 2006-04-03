@echo off

set phpCli="c:\www\php512\php-win.exe"
set phpDoc="c:\www\htdocs\phpdoc\phpdoc"
set phpDocIni="phpdoc.ini"

%phpCli% %phpDoc% -c %phpDocIni%
