<?php

/**
 * AbraFlexi Tools  - Custom report uploader
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020-2023 Vitex Software
 */

namespace AbraFlexi\Report;

use AbraFlexi\Priloha;
use AbraFlexi\Report;
use AbraFlexi\RO;

/**
 * Custom Report Uploader class
 *
 * @author Vítězaslav Dvořák <info@vitexsoftware.cz>
 */
class Uploader extends Report
{
    /**
     *
     * @param string $jrXMLfile
     *
     * @return string|false
     */
    public function compileJasper($jrXMLfile)
    {
        system('jaspercompiler ' . $jrXMLfile);
        $jasperFile = str_replace('.jrxml', '.jasper', $jrXMLfile);
        return file_exists($jasperFile) ? $jasperFile : false;
    }

    /**
     * Attach file into current report
     *
     * @param string $filename
     *
     * @return Priloha
     */
    public function attachFile($filename)
    {
        return Priloha::addAttachmentFromFile($this, pathinfo($filename, PATHINFO_EXTENSION) == 'jrxml' ? self::compileJasper($filename) : $filename);
    }
}
