# AbraFlexi Report Tools

Set of commandline tools related to AbraFlexi Custom reports

![Project Logo](project-logo.png?raw=true)

[![wakatime](https://wakatime.com/badge/user/5abba9ca-813e-43ac-9b5f-b1cfdf3dc1c7/project/9cec785f-7311-4d62-ab1d-7dfcdf74787f.svg)](https://wakatime.com/badge/user/5abba9ca-813e-43ac-9b5f-b1cfdf3dc1c7/project/9cec785f-7311-4d62-ab1d-7dfcdf74787f)

Project Extractor
-----------------


Create JasperStudio project from AbraFlexi report installation file

```shell
    repxmlunpacker <abraflexi-reports-import.xml> </saveto/jasper/project/destorworkspace>
```

Report Uploader
---------------

Upload or Compile & Upload report files: https://github.com/Vitexus/winstrom-reports

```shell
upreport  <code:recordIdent> <"Report Name"> <formInfoCode> <reportfile.jrxml|.jasper>
```

This tool do not use config file. Only environment variables like ABRAFLEXI_URL.

Report Preview
--------------

Download invoice in given report form (and language) and open it in preview application
```shell
previewreport code:Test3 code:VF1-0001/2023 en
```

Installation
------------

To install tools into vendor/bin please use [composer](https://getcomposer.org/):

    composer require vitexsoftware/abraflexi-report-tools

For Debian or Ubuntu please use [repo](http://vitexsoftware.cz/repos.php):

```sheel
sudo apt install lsb-release wget
echo "deb http://repo.vitexsoftware.com $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo wget -O /etc/apt/trusted.gpg.d/vitexsoftware.gpg http://repo.vitexsoftware.com/keyring.gpg
sudo apt update
sudo apt install abraflexi-report-tools
```


![Debian Installation](debian-screenshot.png?raw=true "Debian example")

We use:

* [PHP Language](https://secure.php.net/)
* [PHP AbraFlexi](https://github.com/Spoje-NET/php-abraflexi) - Library for Interaction with [AbraFlexi](https://abraflexi.eu/)
* [Ease Core](https://github.com/VitexSoftware/php-ease-core) - Glue & Tool Set 
* [Jasper Compiler](https://github.com/VitexSoftware/jaspercompiler) - commandline jrxml compiler with AbraFlexi support

Thanks to:
----------

* [PureHTML](https://purehtml.cz/) & [Spoje.Net]( https://spoje.net/ )  for support
* [Abra](https://abra.eu) for [AbraFlexi](https://abraflexi.eu/)
