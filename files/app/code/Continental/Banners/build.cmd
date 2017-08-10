@echo off
echo starting
CALL :updateModule etc/module.xml
CALL :updateModule registration.php

sed -i 's/HelloWorld/%ModuleName%/g' etc/adminhtml/system.xml
sed -i 's/Amasty/%VendorName%/g' etc/adminhtml/system.xml
pause

:updateModule
SETLOCAL
SET VendorName=Continental
SET ModuleName=Banners
SET FileName=%1
sed -i 's/ModuleName/%ModuleName%/g' %Filename%
sed -i 's/VendorName/%VendorName%/g' %Filename%
ENDLOCAL
