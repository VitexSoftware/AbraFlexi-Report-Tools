<?php

declare(strict_types=1);

/**
 * This file is part of the ReportTools for AbraFlexi package
 *
 * https://github.com/VitexSoftware/AbraFlexi-Report-Tools
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$destFile = '/home/vitex/Projects/Dativery/VinozArchivu/faktura-blue.xml';
$loadFrom = '/tmp/';

$loaderPath = realpath(__DIR__.'/../../../autoload.php');

if (file_exists($loaderPath)) {
    require $loaderPath;
} else {
    require __DIR__.'/../vendor/autoload.php';
}

\define('EASE_APPNAME', 'JasperProjectToAbraFlexiXML');
\define('EASE_LOGGER', 'syslog|console');
