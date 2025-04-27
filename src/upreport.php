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
    echo 'usage: '.$argv[0]." <recordIdent> <report Name> <formInfoCode> <reportfile> <reportfile2 ...> \n";
    echo 'example: '.$argv[0]."  code:PokladDen \"My Report\" pokDenik WinstromReports/vykazAnalyzaZakazky/analyzaZakazky.jrxml localization.json logo-pl.png \n";
} else {
    $reportID = $argv[1];
    $reportName = $argv[2];
    $formCode = $argv[3];
    $reportFile = $argv[4];
    $attachments = \array_slice($argv, 5);
    $kod = Functions::uncode($reportID);
    $uploader = new \AbraFlexi\Report\Uploader($reportID, ['ignore404' => true]);

    if ($uploader->lastResponseCode === 404) {
        $uploader->sync(['kod' => $kod, 'formInfoCode' => $formCode, 'nazev' => $reportName]);
    }

    $uploader->unsetDataValue('kod'); // Dirty Hack
    $uploader->updateApiURL();

    foreach ($attachments as $attachment) {
        if (file_exists($attachment)) {
            $uploader->attachFile($attachment);
        } else {
            $uploader->addStatusMessage($attachment.' not found', 'errorr');
        }
    }

    if (file_exists($reportFile)) {
        $oldReportId = (int) $uploader->getDataValue('hlavniReport');
        $attachment = $uploader->attachFile($reportFile);

        if ($uploader->sync(['hlavniReport' => $attachment->getRecordID(), 'id' => $uploader->getRecordID()])) {
            if ($oldReportId) {
                $attachment->deleteFromAbraFlexi($oldReportId);
            }

            $uploader->addStatusMessage(_('Report updated'), 'success');
        }
    }
}
