<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace \AbraFlexi\Report;

/**
 * Description of Uploader
 *
 * @author Vítězaslav Dvořák <info@vitexsoftware.cz>
 */
class Uploader extends \FlexiPeeHP\Report {

    public function __construct($init = null, $options = array()) {
        parent::__construct($init, $options);
    }

    /**
     * 
     * @param string $jrXMLfile
     * 
     * @return string|false
     */
    public function compileJasper($jrXMLfile) {
        system('jaspercompiler ' . $jrXMLfile);
        $jasperFile = str_replace('.jrxml', '.jasper', $jrXMLfile);
        return file_exists($jasperFile) ? $jasperFile : false;
    }

    /**
     * Attach file into current report
     * 
     * @param string $filename
     * 
     * @return \FlexiPeeHP\Priloha
     */
    public function attachFile($filename) {
        return \FlexiPeeHP\Priloha::addAttachmentFromFile($this, pathinfo($filename, PATHINFO_EXTENSION) == 'jrxml' ? self::compileJasper($filename) : $filename);
    }

}
