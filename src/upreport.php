<?php

/**
 * AbraFlexi Tools  - Custom report uploader
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020-2023 Vitex Software
 */

require __DIR__ . '/../vendor/autoload.php';
define('EASE_APPNAME', 'ReportUploader');
require_once '../vendor/autoload.php';
\Ease\Shared::init(['ABRAFLEXI_URL', 'ABRAFLEXI_LOGIN', 'ABRAFLEXI_PASSWORD', 'ABRAFLEXI_COMPANY'], '../.env');
if ($argc < 3) {
    echo "usage: " . $argv[0] . " <recordIdent> <formInfoCode> <reportfile> <reportfile2 ...> \n";
    echo "example: " . $argv[0] . "  code:PokladDen pokDenik WinstromReports/vykazAnalyzaZakazky/analyzaZakazky.jrxml localization.json logo-pl.png \n";
} else {
    $reportID = $argv[1];
    $formCode = $argv[2];
    $reportFile = $argv[3];
    $attachments = array_slice($argv, 4);
    $uploader = new \AbraFlexi\Report\Uploader($reportID);
    foreach ($attachments as $attachment) {
        $uploader->attachFile($attachment);
    }

    if (strstr($reportFile, '.jrxml')) {
        system('jaspercompiler ' . $reportFile);
        $reportFile = str_replace('.jrxml', '.jasper', $reportFile);
    }


    if (file_exists($reportFile)) {
        $reporter = new \AbraFlexi\Report($reportID);
        $oldReportId = intval($reporter->getDataValue('hlavniReport'));
        $attachment = \AbraFlexi\Priloha::addAttachmentFromFile($reporter, $reportFile);
        if ($reporter->sync(['hlavniReport' => $attachment->getRecordID(), 'id' => $reporter->getRecordID()])) {
            if ($oldReportId) {
                $attachment->deleteFromAbraFlexi($oldReportId);
            }
            $reporter->addStatusMessage(_('Report updated'), 'success');
        }
    }
}
