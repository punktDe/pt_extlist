<?php


namespace PunktDe\PtExtlist\View\Export;

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
 * Abstract view for exports. This class acts as a base class for
 * all exports. It handles settings for export and header generation etc.
 *
 * @package View
 * @subpackage Export
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_View_Export_AbstractExportViewTest
 */
abstract class AbstractExportView extends \PunktDe\PtExtlist\View\BaseView
{
    /**
     * Export configuration object
     *
     * @var \PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig
     */
    protected $exportConfiguration;



    /**
     * Returns true, if view has a template
     *
     * TODO We return true here, because otherwise, another view class is instantiated in controller.
     *
     * @return bool
     */
    public function hasTemplate()
    {
        return true;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig $exportConfiguration
     */
    public function setExportConfiguration(\PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig $exportConfiguration)
    {
        $this->exportConfiguration = $exportConfiguration;
    }



    /**
     * @return void
     */
    public function initConfiguration()
    {
        //	$this->exportConfiguration = $this->configurationBuilder->buildExportConfiguration();
    }



    /**
     * (non-PHPdoc)
     * @see \TYPO3\CMS\Fluid\View\TemplateView::canRender()
     */
    public function canRender(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext)
    {
        return true;
    }



    /**
     * Helper method for generating file name from TS config
     *
     * @return   string      File name of Export File
     */
    protected function getFilenameFromTs()
    {
        $fullFilename = '';

        $fullFilename .= $this->exportConfiguration->getFileName();

        if ($this->exportConfiguration->getAddDateToFilename()) {
            $fullFilename .= date($this->exportConfiguration->getDateFormat(), time());
        }

        $fullFilename .= '.' . $this->exportConfiguration->getFileExtension();

        return $fullFilename;
    }



    /**
     * Generate header depending on download handling setting in TS
     *
     * Functionality is taken from FPDF!
     *
     * @throws Exception
     */
    protected function sendHeader()
    {
        switch ($this->exportConfiguration->getDownloadType()) {

            case \PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig::OPEN_IN_BROWSER:

                if ($this->exportConfiguration->getContentType()) {
                    header('Content-Type: ' . $this->exportConfiguration->getContentType());
                }

                if (headers_sent()) {
                    throw new Exception('Some data has already been output to browser, can\'t send Export file.', 1283945901);
                }

                header('Content-disposition: inline; filename="' . $this->getFilenameFromTs() . '"');
                break;

            case \PunktDe\PtExtlist\Domain\Configuration\Export\ExportConfig::FORCE_DOWNLOAD:

                if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
                    header('Content-Type: application/force-download, charset=' . $this->exportConfiguration->getSettings('outputEncoding'));
                } else {
                    header('Content-Type: application/octet-stream, charset=' . $this->exportConfiguration->getSettings('outputEncoding'));
                }

                header('Content-disposition: attachment; filename="' . $this->getFilenameFromTs() . '"');
                break;

            default:
                throw new Exception('No valid download handling set for Export file!', 1283945902);
        }
    }
}
