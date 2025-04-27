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

use AbraFlexi\Functions;

/**
 * This file is part of the ReportTools for AbraFlexi package.
 *
 * https://github.com/VitexSoftware/AbraFlexi-Report-Tools
 *
 * (c) Vítězslav Dvořák <http://vitexsoftware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../vendor/autoload.php';
\define('EASE_APPNAME', 'AbraFlexi ReportUploader');

require_once '../vendor/autoload.php';
\Ease\Shared::init(['ABRAFLEXI_URL', 'ABRAFLEXI_LOGIN', 'ABRAFLEXI_PASSWORD', 'ABRAFLEXI_COMPANY'], '../.env');

if ($argc < 3) {
    echo 'usage: '.$argv[0]." <reportIdent> [invoiceIdent] [language] \n";
    echo 'example: '.$argv[0]."  code:TEST3 code:VF1-0001/2023\n";
} else {
    $reportID = $argv[1];
    $invoiceID = \array_key_exists(2, $argv) ? $argv[2] : null;
    $language = \array_key_exists(3, $argv) ? $argv[3] : null;

    $invoicer = new \AbraFlexi\FakturaVydana($invoiceID, ['throwException' => false]);

    if (null === $invoiceID) {
        $invoicer->loadFromAbraFlexi($invoicer->getFirstRecordID());
    }

    $pdfFile = $invoicer->downloadInFormat('pdf', sys_get_temp_dir().'/', Functions::uncode($reportID), $language);

    if ($pdfFile) {
        system('xdg-open '.$pdfFile);
    } else {
        $response = $invoicer->parseResponse($invoicer->rawResponseToArray((string) $invoicer->lastCurlResponse, $invoicer->responseFormat), $invoicer->lastResponseCode);
    }
}
