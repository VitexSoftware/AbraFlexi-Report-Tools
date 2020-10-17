# FlexiBee Report Tools

Set of commandline tools related to FlexiBee Custom reports

![Project Logo](project-logo.png?raw=true)


Project Extractor
-----------------


Create JasperStudio project from FlexiBee report installation file

```shell
    repxmlunpacker <flexibee-reports-import.xml> </saveto/jasper/project/destorworkspace>
```

Report Uploader
---------------

Upload or Compile & Upload report files: https://github.com/Vitexus/winstrom-reports

```shell
upreport  <code:recordIdent> <formInfoCode> <reportfile.jrxml|jasper>
```

This tool do not use config file. Only environment variables like FLEXIBEE_URL.


Installation
------------

To install tools into vendor/bin please use [composer](https://getcomposer.org/):

    composer require vitexsoftware/flexibee-report-tools

For Debian or Ubuntu please use [repo](http://vitexsoftware.cz/repos.php):

```sheel
sudo apt install lsb-release wget
echo "deb http://repo.vitexsoftware.cz $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/vitexsoftware.list
sudo wget -O /etc/apt/trusted.gpg.d/vitexsoftware.gpg http://repo.vitexsoftware.cz/keyring.gpg
sudo apt update
sudo apt install flexibee-report-tools
```


![Debian Installation](debian-screenshot.png?raw=true "Debian example")

We use:

  * [PHP Language](https://secure.php.net/)
  * [PHP FlexiBee](https://github.com/Spoje-NET/php-flexibee) - Library for Interaction with [FlexiBee](https://flexibee.eu/)
  * [Ease Core](https://github.com/VitexSoftware/php-ease-core) - Glue & Tool Set 
  * [Jasper Compiler](https://github.com/VitexSoftware/jaspercompiler) - commandline jrxml compiler with FlexiBee support

Thanks to:
----------

 * [PureHTML](https://purehtml.cz/) & [Spoje.Net]( https://spoje.net/ )  for support
 * [Abra](https://abra.eu) for [FlexiBee](https://flexibee.eu/)
