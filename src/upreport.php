<?php

/**
 * AbraFlexi Tools  - Custom report uploader
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020-2023 Vitex Software
 */

require __DIR__ . '/../vendor/autoload.php';
define('EASE_APPNAME', 'AbraFlexi ReportUploader');
require_once '../vendor/autoload.php';
\Ease\Shared::init(['ABRAFLEXI_URL', 'ABRAFLEXI_LOGIN', 'ABRAFLEXI_PASSWORD', 'ABRAFLEXI_COMPANY'], '../.env');
if ($argc < 3) {
    echo "usage: " . $argv[0] . " <recordIdent> <report Name> <formInfoCode> <reportfile> <reportfile2 ...> \n";
    echo "example: " . $argv[0] . "  code:PokladDen \"My Report\" pokDenik WinstromReports/vykazAnalyzaZakazky/analyzaZakazky.jrxml localization.json logo-pl.png \n";
} else {
    $reportID = $argv[1];
    $reportName = $argv[2];
    $formCode = $argv[3];
    $reportFile = $argv[4];
    $attachments = array_slice($argv, 5);
    $kod = \AbraFlexi\RO::uncode($reportID);
    $uploader = new \AbraFlexi\Report\Uploader($reportID, ['ignore404' => true]);

    if ($uploader->lastResponseCode == 404) {
        $uploader->sync(['kod' => $kod, 'formInfoCode' => $formCode, 'nazev' => $reportName]);
    }

    foreach ($attachments as $attachment) {
        if (file_exists($attachment)) {
            $uploader->attachFile($attachment);
        } else {
            $uploader->addStatusMessage($attachment . ' not found', 'errorr');
        }
    }

    if (file_exists($reportFile)) {
        $oldReportId = intval($uploader->getDataValue('hlavniReport'));
        $uploader->unsetDataValue('kod'); //Dirty Hack
        $uploader->updateApiURL();
        $attachment = $uploader->attachFile($reportFile);

        if ($uploader->sync(['hlavniReport' => $attachment->getRecordID(), 'id' => $uploader->getRecordID()])) {
            if ($oldReportId) {
                $attachment->deleteFromAbraFlexi($oldReportId);
            }
            $uploader->addStatusMessage(_('Report updated'), 'success');
        }
    }
}
