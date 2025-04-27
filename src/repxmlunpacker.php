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

$loaderPath = realpath(__DIR__.'/../../../autoload.php');

if (file_exists($loaderPath)) {
    require $loaderPath;
} else {
    require __DIR__.'/../vendor/autoload.php';
}

\define('EASE_APPNAME', 'ReportUploader');
\define('EASE_LOGGER', 'syslog|console');

if ($argc !== 3) {
    echo "Create JasperStudio project from AbraFlexi report installation file\n\nUsage:\n";
    echo pathinfo(basename(__FILE__), \PATHINFO_FILENAME)."  abraflexi-reports-import.xml /saveto/jasper/project/destorworkspace \n";

    exit(1);
}

[$me, $sourceFile, $saveTo] = $argv;

$projectName = pathinfo(basename($sourceFile), \PATHINFO_FILENAME);
$projectPath = $saveTo.$projectName.'/';

if (!file_exists($projectPath)) {
    mkdir($projectPath);
    mkdir($projectPath.'/bin');

    $fblib = '/usr/share/abraflexi/lib/';
    $fbver = file_exists($fblib.'VERSION.txt') ? trim(file_get_contents($fblib.'VERSION.txt')) : '2020.2.2';
    $classpath = <<<'EOD'
<?xml version="1.0" encoding="UTF-8"?>
<classpath>
        <classpathentry kind="con" path="org.eclipse.jdt.launching.JRE_CONTAINER/org.eclipse.jdt.internal.debug.ui.launcher.StandardVMType/JavaSE-11"/>
        <classpathentry exported="true" kind="con" path="net.sf.jasperreports.JR_CONTAINER"/>
        <classpathentry exported="true" kind="con" path="com.jaspersoft.server.JRS_CONTAINER"/>
        <classpathentry kind="lib" path="
EOD.$fblib.<<<'EOD'
binding-1.1.1b-modified.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-client-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-connector-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-core-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-core-uiswing-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-extlibs-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-help-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-launcher-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-launcher-api-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-launcher-tool-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-majetek-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-mzdy-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-reports-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-server-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-server-impl-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-sql-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="lib" path="
EOD.$fblib.'winstrom-ucto-'.$fbver.<<<'EOD'
.jar"/>
        <classpathentry kind="output" path="bin"/>
</classpath>

EOD;

    file_put_contents($projectPath.'/.classpath', $classpath);

    $project = <<<'EOD'
<?xml version="1.0" encoding="UTF-8"?>
<projectDescription>
        <name>
EOD.$projectName.<<<'EOD'
</name>
        <comment>generated from
EOD.$sourceFile.<<<'EOD'
</comment>
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

EOD;

    file_put_contents($projectPath.'/.project', $project);
}

$reportsXML = \AbraFlexi\Functions::xml2array(file_get_contents($sourceFile));

if (\array_key_exists('report', $reportsXML) && \count($reportsXML['report'])) {
    foreach ($reportsXML['report'] as $report) {
        if (\array_key_exists('kod', $report)) {
            $reportName = $report['kod'];
            $reportPath = $projectPath.$reportName.'/';

            if (!file_exists($reportPath)) {
                mkdir($reportPath);
            }

            if (\array_key_exists('prilohy', $report)) {
                foreach ($report['prilohy'][0]['priloha'] as $priloha) {
                    $attachName = $priloha['nazSoub'];
                    file_put_contents($reportPath.$attachName, base64_decode($priloha['content'], true));
                    unset($priloha['content']);
                    file_put_contents($reportPath.$attachName.'.json', json_encode($priloha, \JSON_PRETTY_PRINT));
                }
            }

            unset($report['prilohy']);
            file_put_contents($projectPath.$reportName.'.json', json_encode($report, \JSON_PRETTY_PRINT));
        }
    }
}
