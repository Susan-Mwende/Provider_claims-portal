Set WshShell = CreateObject("WScript.Shell") 
WshShell.Run chr(34) & "C:\xampp\htdocs\CURRENT\portal_service_command.bat" & Chr(34), 0
Set WshShell = Nothing