<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll, Christoph Ehscheidt
 *  All rights reserved
 *
 *  For further information: http://extlist.punkt.de <extlist@punkt.de>
 *
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/



/**
 * Class implements a view for rendering PDF using the WKHTML2PDF rendering engine.
 *
 * @see https://github.com/antialize/wkhtmltopdf
 *
 * For further information about integration with PHP
 * @see https://code.google.com/p/wkhtmltopdf/wiki/IntegrationWithPhp
 *
 *
 * To use this view for PDF export, add the following line to your list setup:
 *
 * plugin.tx_ptextlist.settings.export.exportConfigs.pdfExport.viewClassName = Tx_PtExtlist_View_Export_WkHtml2PdfListView
 *
 * @author Michael Knoll
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_WkHtml2PdfListView extends Tx_PtExtlist_View_Export_AbstractExportView
{
    /**
     * Force the client to download PDF file when finish() is called.
     */
    const PDF_DOWNLOAD = 'D';



    /**
     * Returns the PDF file as a string when finish() is called.
     */
    const PDF_ASSTRING = 'S';



    /**
     * When possible, force the client to embed PDF file when finish() is called.
     */
    const PDF_EMBEDDED = 'I';



    /**
     * PDF file is saved into the server space when finish() is called. The path is returned.
     */
    const PDF_SAVEFILE = 'F';



    /**
     * PDF generated as landscape (vertical).
     */
    const PDF_PORTRAIT = 'Portrait';



    /**
     * PDF generated as landscape (horizontal).
     */
    const PDF_LANDSCAPE = 'Landscape';



    /**
     * Holds static HTML source to be rendered as footer for output PDF
     *
     * @var string
     */
    public $wkhtmlFooterHtml;



    /**
     * Holds static HTML source to be rendered as header of output PDF
     *
     * @var string
     */
    private $wkhtmlHeaderHtml;



    /**
     * Path to fluid template
     *
     * @var string
     */
    private $fluidTemplatePath;



    private $cmd = '';



    private $tmp = '';



    private $pdf = '';



    private $status = '';



    protected $orient = 'Portrait';



    private $size = 'A4';



    private $toc = false;



    private $copies = 1;



    private $grayscale = false;



    private $title = '';



    protected $additionalWkhtmlParams = null;



    private static $cpu = '';



    /**
     * Holds base path for wkpdf file operations
     *
     * @var string
     */
    private $tempPdfBasePath;



    /**
     * Holds path to CSS file used to style exported list
     *
     * @var string
     */
    private $cssFilePath;



    /**
     * Initialize additional class properties
     */
    public function initConfiguration()
    {
        parent::initConfiguration();

        $this->tempPdfBasePath = PATH_site.'typo3temp/';

        $this->cmd = 'wkhtmltopdf';
        // This method seems not to work to check, whether a command is available in unix
        #if (!file_exists($this->cmd)) throw new Exception('WKPDF static executable "' . htmlspecialchars($this->cmd, ENT_QUOTES) . '" was not found.', 1373448917);
        do {
            $this->tmp = $this->tempPdfBasePath . 'tmp/' . mt_rand() . '.html';
        } while (file_exists($this->tmp));

        $this->initTypoScriptSettings();
    }



    /**
     * Overwriting the render method to generate a PDF output
     *
     * @throws Exception if wkhtml command did not succeed.
     * @return  void (never returns)
     *
     * Partly taken from https://code.google.com/p/wkhtmltopdf/wiki/IntegrationWithPhp
     */
    public function render()
    {
        $htmlDocument = $this->tempPdfBasePath . basename($this->tmp);

        // Set css path as template variable
        $this->assign('cssFilePath', $this->cssFilePath);

        $html = $this->renderHtml();

        // WKHTML requires http accessible CSS, so we replace absolute path with http-URL
        $relativePath = str_replace($_SERVER['DOCUMENT_ROOT'], 'http://' . $_SERVER['HTTP_HOST'], $this->cssFilePath);
        $html = str_replace($this->cssFilePath, $relativePath, $html);

        if ((int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('showHTML') == 1) {
            die($html);
        }

        $this->runWkHtmlCommand($htmlDocument, $html);

        // TODO use settings from TypoScript here!
        $this->output(self::PDF_DOWNLOAD, $this->tempPdfBasePath . $this->tmp . '.pdf');

        exit();
    }



    /**
     * Template method that renders the html source which is converted to PDF.
     *
     * Can be overwritten in extending classes to change behaviour of HTML rendering.
     *
     * @return string
     */
    protected function renderHtml()
    {
        return parent::render();
    }



    private function runWkHtmlCommand($htmlDocument, $html)
    {
        file_put_contents($htmlDocument, $html);

        $addHeader = $addFooter = false;

        if (count($this->wkhtmlFooterHtml) > 0) {
            file_put_contents($htmlDocument . '.footer.html', $this->wkhtmlFooterHtml);
            $addFooter = true;
        }

        if (count($this->wkhtmlHeaderHtml) > 0) {
            file_put_contents($htmlDocument . '.header.html', $this->wkhtmlHeaderHtml);
            $addHeader = true;
        }

        $wkCommand = '"' . $this->cmd . '"'
            . ($addFooter ? ' --footer-html ' . $htmlDocument . '.footer.html' : '')
            . ($addHeader ? ' --header-html ' . $htmlDocument . '.header.html' : '')
            . (($this->additionalWkhtmlParams !== null) ? ' ' . $this->additionalWkhtmlParams : '')
            . (($this->copies > 1) ? ' --copies ' . $this->copies : '')        // number of copies
            . ' --orientation ' . $this->orient                                // orientation
            . ' --page-size ' . $this->size                                    // page size
            . ($this->toc ? ' --toc' : '')                                        // table of contents
            . ($this->grayscale ? ' --grayscale' : '')                            // grayscale
            . (($this->title != '') ? ' --title "' . $this->title . '"' : '')    // title
            . ' "' . $htmlDocument . '" -';

        $this->pdf = $this->pipeExec($wkCommand);

        if (strpos(strtolower($this->pdf['stderr']), 'error') !== false) {
            throw new Exception('WKPDF command: ' . $wkCommand . ' raised WKPDF system error: <pre>' . $this->pdf['stderr'] . '</pre>', 1373448918);
        }

        if ($this->pdf['stdout'] == '') {
            throw new Exception('WKPDF command: ' . $wkCommand . ' didn\'t return any data. <pre>' . $this->pdf['stderr'] . '</pre>', 1373448919);
        }

        if (((int)$this->pdf['return']) > 1) {
            throw new Exception('WKPDF command: ' . $wkCommand . ' raised WKPDF shell error, return code ' . (int)$this->pdf['return'] . '.', 1373448920);
        }

        $this->status = $this->pdf['stderr'];
        $this->pdf = $this->pdf['stdout'];
        unlink($htmlDocument);

        if ($addFooter) {
            unlink($htmlDocument . '.footer.html');
        }

        if ($addHeader) {
            unlink($htmlDocument . '.header.html');
        }

        return $wkCommand;
    }



    /**
     * Return PDF with various options.
     *
     * @param string $mode How two output (constants from this same class).
     * @param string $file The PDF's filename (the usage depends on $mode.
     * @throws Exception if headers were already sent.
     * @return string|boolean Depending on $mode, this may be success (boolean) or PDF (string).
     *
     * Taken from https://code.google.com/p/wkhtmltopdf/wiki/IntegrationWithPhp
     */
    private function output($mode, $file)
    {
        switch ($mode) {
            case self::PDF_DOWNLOAD:
                if (!headers_sent()) {
                    header('Content-Description: File Transfer');
                    header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    // force download dialog
                    header('Content-Type: application/force-download');
                    header('Content-Type: application/octet-stream', false);
                    header('Content-Type: application/download', false);
                    header('Content-Type: application/pdf', false);
                    // use the Content-Disposition header to supply a recommended filename
                    header('Content-Disposition: attachment; filename="' . basename($file) . '";');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . strlen($this->pdf));
                    echo $this->pdf;
                } else {
                    throw new Exception('WKPDF download headers were already sent.', 1373448921);
                }
                break;
            case self::PDF_ASSTRING:
                return $this->pdf;
                break;
            case self::PDF_EMBEDDED:
                if (!headers_sent()) {
                    header('Content-Type: application/pdf');
                    header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    header('Content-Length: ' . strlen($this->pdf));
                    header('Content-Disposition: inline; filename="' . basename($file) . '";');
                    echo $this->pdf;
                } else {
                    throw new Exception('WKPDF embed headers were already sent.', 1373448922);
                }
                break;
            case self::PDF_SAVEFILE:
                return file_put_contents($file, $this->pdf);
                break;
            default:
                throw new Exception('WKPDF invalid mode "' . htmlspecialchars($mode, ENT_QUOTES) . '".', 1373448923);
        }
        return false;
    }



    /**
     * Advanced execution routine.
     *
     * @param string $cmd The command to execute.
     * @param string $input Any input not in arguments.
     * @return array An array of execution data; stdout, stderr and return "error" code.
     *
     * Taken from https://code.google.com/p/wkhtmltopdf/wiki/IntegrationWithPhp
     */
    private static function pipeExec($cmd, $input = '')
    {
        $proc = proc_open($cmd, array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w')), $pipes);
        fwrite($pipes[0], $input);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $rtn = proc_close($proc);
        return array(
            'stdout' => $stdout,
            'stderr' => $stderr,
            'return' => $rtn
        );
    }



    private function initTypoScriptSettings()
    {
        $this->fluidTemplatePath = $this->exportConfiguration->getSettings('templatePath');
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->fluidTemplatePath, array('message' => 'No template path given for fluid export! 1284621481'));
        $this->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->fluidTemplatePath));

        // TODO take a look at http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf_0.10.0_rc2-doc.html for further information on parameters!
        // --page-size	<Size>	Set paper size to: A4, Letter, etc. (default A4)
        $this->size = strtolower($this->exportConfiguration->getSettings('paperSize'));
        Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->size, array('message' => 'No PaperSize given for the PDF output! 1322585559'));

        // TODO take a look at http://madalgo.au.dk/~jakobt/wkhtmltoxdoc/wkhtmltopdf_0.10.0_rc2-doc.html for further informatoin on parameters
        // --orientation	<orientation>	Set orientation to Landscape or Portrait (default Portrait)
        $this->orient = $this->exportConfiguration->getSettings('paperOrientation');
        Tx_PtExtbase_Assertions_Assert::isInArray($this->orient, array('portrait', 'landscape'), array('message' => 'The Orientation must either be portrait or landscape! 1322585560'));


        $this->cssFilePath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($this->exportConfiguration->getSettings('cssFilePath'));
        Tx_PtExtbase_Assertions_Assert::isTrue(file_exists($this->cssFilePath), array('message' => 'The CSS File with the filename ' . $this->cssFilePath . ' can not be found. 1322587627'));

        $this->additionalWkhtmlParams = $this->exportConfiguration->getSettings('additionalWkhtmlParams');

        $this->wkhtmlHeaderHtml = $this->exportConfiguration->getSettings('wkhtmlHeaderHtml');


        $this->wkhtmlFooterHtml = $this->exportConfiguration->getSettings('wkhtmlFooterHtml');
    }
}
