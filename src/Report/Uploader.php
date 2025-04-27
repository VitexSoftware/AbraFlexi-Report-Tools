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

namespace AbraFlexi\Report;

use AbraFlexi\Priloha;
use AbraFlexi\Report;

/**
 * Custom Report Uploader class.
 *
 * @author Vítězaslav Dvořák <info@vitexsoftware.cz>
 */
class Uploader extends Report
{
    /**
     * @param string $jrXMLfile
     *
     * @return false|string
     */
    public function compileJasper($jrXMLfile)
    {
        system('jaspercompiler '.$jrXMLfile);
        $jasperFile = str_replace('.jrxml', '.jasper', $jrXMLfile);

        return file_exists($jasperFile) ? $jasperFile : false;
    }

    /**
     * Attach file into current report.
     *
     * @param string $filename
     *
     * @return Priloha
     */
    public function attachFile($filename)
    {
        return Priloha::addAttachmentFromFile($this, pathinfo($filename, \PATHINFO_EXTENSION) === 'jrxml' ? self::compileJasper($filename) : $filename);
    }
}
