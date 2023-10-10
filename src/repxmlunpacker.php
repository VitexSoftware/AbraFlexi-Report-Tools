<?php

/**
 * AbraFlexi Tools  - Report XML unpacker
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020,2023 Vitex Software
 */

$loaderPath = realpath(__DIR__ . "/../../../autoload.php");
if (file_exists($loaderPath)) {
    require $loaderPath;
} else {
    require __DIR__ . '/../vendor/autoload.php';
}

define('EASE_APPNAME', 'ReportUploader');
define('EASE_LOGGER', 'syslog|console');


if ($argc != 3) {
    echo "Create JasperStudio project from AbraFlexi report installation file\n\nUsage:\n";
    echo pathinfo(basename(__FILE__), PATHINFO_FILENAME) . "  abraflexi-reports-import.xml /saveto/jasper/project/destorworkspace \n";
    exit(1);
}

list($me, $sourceFile, $saveTo) = $argv;

$projectName = pathinfo(basename($sourceFile), PATHINFO_FILENAME);
$projectPath = $saveTo . $projectName . '/';
if (!file_exists($projectPath)) {
    mkdir($projectPath);
    mkdir($projectPath . '/bin');

    $fblib = '/usr/share/abraflexi/lib/';
    $fbver = file_exists($fblib . 'VERSION.txt') ? trim(file_get_contents($fblib . 'VERSION.txt')) : '2020.2.2';
    $classpath = '<?xml version="1.0" encoding="UTF-8"?>
<classpath>
        <classpathentry kind="con" path="org.eclipse.jdt.launching.JRE_CONTAINER/org.eclipse.jdt.internal.debug.ui.launcher.StandardVMType/JavaSE-11"/>
        <classpathentry exported="true" kind="con" path="net.sf.jasperreports.JR_CONTAINER"/>
        <classpathentry exported="true" kind="con" path="com.jaspersoft.server.JRS_CONTAINER"/>
        <classpathentry kind="lib" path="' . $fblib . 'binding-1.1.1b-modified.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-client-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-connector-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-core-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-core-uiswing-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-extlibs-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-help-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-launcher-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-launcher-api-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-launcher-tool-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-majetek-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-mzdy-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-reports-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-server-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-server-impl-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-sql-' . $fbver . '.jar"/>
        <classpathentry kind="lib" path="' . $fblib . 'winstrom-ucto-' . $fbver . '.jar"/>
        <classpathentry kind="output" path="bin"/>
</classpath>
';

    file_put_contents($projectPath . '/.classpath', $classpath);

    $project = '<?xml version="1.0" encoding="UTF-8"?>
<projectDescription>
        <name>' . $projectName . '</name>
        <comment>generated from ' . $sourceFile . '</comment>
        <projects>
        </projects>
        <buildSpec>
                <buildCommand>
                        <name>org.eclipse.jdt.core.javabuilder</name>
                        <arguments>
                        </arguments>
                </buildCommand>
        </buildSpec>
        <natures>
                <nature>org.eclipse.jdt.core.javanature</nature>
        </natures>
</projectDescription>
';

    file_put_contents($projectPath . '/.project', $project);
}

$reportsXML = \AbraFlexi\RO::xml2array(file_get_contents($sourceFile));
if (array_key_exists('report', $reportsXML) && count($reportsXML['report'])) {
    foreach ($reportsXML['report'] as $report) {
        if (array_key_exists('kod', $report)) {
            $reportName = $report['kod'];
            $reportPath = $projectPath . $reportName . '/';

            if (!file_exists($reportPath)) {
                mkdir($reportPath);
            }

            if (array_key_exists('prilohy', $report)) {
                foreach ($report['prilohy'][0]['priloha'] as $priloha) {
                    $attachName = $priloha['nazSoub'];
                    file_put_contents($reportPath . $attachName, base64_decode($priloha['content']));
                    unset($priloha['content']);
                    file_put_contents($reportPath . $attachName . '.json', json_encode($priloha, JSON_PRETTY_PRINT));
                }
            }
            unset($report['prilohy']);
            file_put_contents($projectPath . $reportName . '.json', json_encode($report, JSON_PRETTY_PRINT));
        }
    }
}
