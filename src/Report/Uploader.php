<?php

/**
 * AbraFlexi Tools  - Custom report uploader
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2020-2023 Vitex Software
 */

namespace AbraFlexi\Report;

/**
 * Custom Report Uploader class
 *
 * @author Vítězaslav Dvořák <info@vitexsoftware.cz>
 */
class Uploader extends \AbraFlexi\Report
{
    public function __construct($init = null, $options = array())
    {
        parent::__construct($init, $options);
    }

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
     * @return \AbraFlexi\Priloha
     */
    public function attachFile($filename)
    {
        return \AbraFlexi\Priloha::addAttachmentFromFile($this, pathinfo($filename, PATHINFO_EXTENSION) == 'jrxml' ? self::compileJasper($filename) : $filename);
    }
}
