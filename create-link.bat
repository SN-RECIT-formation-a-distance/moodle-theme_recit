echo off
set pluginPath=..\moodle\theme\recit

rem remove the current link
..\outils\junction -d src

rem set the link
..\outils\junction src %pluginPath%

pause