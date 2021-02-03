<?php
/**
 * AbraFlexi Tools  - Custom report uploader
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020 Vitex Software
 */

$loaderPath = realpath(__DIR__ . "/../../../autoload.php");
if (file_exists($loaderPath)) {
    require $loaderPath;
} else {
    require __DIR__ . '/../vendor/autoload.php';
}

define('EASE_APPNAME', 'ReportUploader');
define('EASE_LOGGER', 'syslog|console');

if (empty(\Ease\Functions::cfg('ABRAFLEXI_URL'))) {
    echo "Please set up AbraFlexi client configuration environment: \n\n";
    echo "ABRAFLEXI_URL=https://demo.abraflexi.eu:5434\n";
    echo "ABRAFLEXI_PASSWORD=winstrom\n";
    echo "ABRAFLEXI_LOGIN=winstrom\n";
    echo "ABRAFLEXI_COMPANY=demo_de\n";
}

if ($argc < 3) {
    echo "usage: " . $argv[0] . " <recordIdent> <formInfoCode> <reportfile> <reportfile2 ...> \n";
    echo "example: " . $argv[0] . "  code:PokladDen pokDenik WinstromReports/vykazAnalyzaZakazky/analyzaZakazky.jrxml localization.json logo-pl.png \n";
} else {
    $reportID = $argv[1];
    $formCode = $argv[2];
    $reportFile = $argv[3];
    
    $attachments = array_slice($argv, 4);
    
    $uploader = new \AbraFlexi\Report\Uploader($reportID);
    
    
    foreach ($attachments as $attachment){
        $uploader->attachFile($attachment);
    }
    
    if (strstr($reportFile, '.jrxml')) {
        system('jaspercompiler ' . $reportFile);
         $reportFile = str_replace('.jrxml', '.jasper', $reportFile);
    }


    if (file_exists($reportFile)) {

        $reporter = new FlexiPeeHP\Report($reportID);
        $oldReportId = intval($reporter->getDataValue('hlavniReport'));
        $attachment = \FlexiPeeHP\Priloha::addAttachmentFromFile($reporter, $reportFile);
        if ($reporter->sync(['hlavniReport' => $attachment->getRecordID(), 'id' => $reporter->getRecordID()])) {
            if ($oldReportId) {
                $attachment->deleteFromAbraFlexi($oldReportId);
            }
            $reporter->addStatusMessage(_('Report updated'), 'success');
        }
        
        
        
    }
}


