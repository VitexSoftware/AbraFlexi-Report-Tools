# Report Tools for AbraFlexi

Set of command-line tools related to AbraFlexi custom reports.

![Project Logo](project-logo.svg?raw=true)

[![wakatime](https://wakatime.com/badge/user/5abba9ca-813e-43ac-9b5f-b1cfdf3dc1c7/project/9cec785f-7311-4d62-ab1d-7dfcdf74787f.svg)](https://wakatime.com/badge/user/5abba9ca-813e-43ac-9b5f-b1cfdf3dc1c7/project/9cec785f-7311-4d62-ab1d-7dfcdf74787f)

## Project Extractor


Create a JasperStudio project from an AbraFlexi report installation file.

```shell
abraflexi-repxmlunpacker <abraflexi-reports-import.xml> </saveto/jasper/project/destination-workspace>
```

## Jasper Classpath Updater

Update Jasper Studio `.classpath` files to match the installed AbraFlexi
version.

```shell
abraflexi-updatejasperclasspath
```

## Report Uploader

Upload or compile and upload report files: https://github.com/Vitexus/winstrom-reports

```shell
abraflexi-upreport <code:recordIdent> "<Report Name>" <formInfoCode> <reportfile.jrxml|.jasper> [attachments...]
```

This tool does not use a dedicated config file. It reads AbraFlexi connection
settings from environment variables such as `ABRAFLEXI_URL`.

## Report Preview

Download an invoice in a given report form (and language) and open it in the
default preview application.
```shell
abraflexi-previewreport code:Test3 code:VF1-0001/2023 en
```

## Installation

To install the tools into `vendor/bin`, use [composer](https://getcomposer.org/):

```shell
composer require vitexsoftware/abraflexi-report-tools
```

For Debian or Ubuntu, use [repo](http://vitexsoftware.cz/repos.php):

```shell
sudo apt install lsb-release wget
echo "deb http://repo.vitexsoftware.com $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo wget -O /etc/apt/trusted.gpg.d/vitexsoftware.gpg http://repo.vitexsoftware.com/keyring.gpg
sudo apt update
sudo apt install abraflexi-report-tools
```

After installing the Debian package, manual pages are available for all shipped
commands:
`man abraflexi-upreport`,
`man abraflexi-previewreport`,
`man abraflexi-repxmlunpacker`,
and `man abraflexi-updatejasperclasspath`.


![Debian Installation](debian-screenshot.png?raw=true "Debian example")

We use:

* [PHP Language](https://www.php.net/)
* [PHP AbraFlexi](https://github.com/Spoje-NET/php-abraflexi) - Library for Interaction with [AbraFlexi](https://abraflexi.eu/)
* [Ease Core](https://github.com/VitexSoftware/php-ease-core) - Glue & Tool Set 
* [Jasper Compiler](https://github.com/VitexSoftware/jaspercompiler) - commandline jrxml compiler with AbraFlexi support

## Thanks to:

* [PureHTML](https://purehtml.cz/) & [Spoje.Net]( https://spoje.net/ )  for support
* [Abra](https://abra.eu) for [AbraFlexi](https://abraflexi.eu/)
