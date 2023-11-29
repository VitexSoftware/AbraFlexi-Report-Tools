<?php

/**
 * AbraFlexi Tools  - Preview invoice
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020-2023 Vitex Software
 */

require __DIR__ . '/../vendor/autoload.php';
define('EASE_APPNAME', 'AbraFlexi ReportUploader');
require_once '../vendor/autoload.php';
\Ease\Shared::init(['ABRAFLEXI_URL', 'ABRAFLEXI_LOGIN', 'ABRAFLEXI_PASSWORD', 'ABRAFLEXI_COMPANY'], '../.env');
if ($argc < 3) {
    echo "usage: " . $argv[0] . " <reportIdent> [invoiceIdent] \n";
    echo "example: " . $argv[0] . "  code:TEST3 code:VF1-0001/2023\n";
} else {
    $reportID = $argv[1];
    $invoiceID = $argv[2];

    $invoicer = new \AbraFlexi\FakturaVydana($invoiceID);
    $reporter = new \AbraFlexi\Report($reportID);

    $pdfFile = $invoicer->downloadInFormat('pdf', sys_get_temp_dir() . '/', $reportID);
    if ($pdfFile) {
        system('xdg-open ' . $pdfFile);
    }
}
