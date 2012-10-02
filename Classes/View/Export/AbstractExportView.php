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
 * Abstract view for exports. This class acts as a base class for
 * all exports. It handles settings for export and header generation etc.
 *
 * @package View
 * @subpackage Export
 * @author Daniel Lienert
 * @author Michael Knoll
 */
abstract class Tx_PtExtlist_View_Export_AbstractExportView extends Tx_PtExtlist_View_BaseView {

	/**
	 * Export configuration objekt
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Export_ExportConfig
	 */
	protected $exportConfiguration;


	/**
	 * Returns true, if view has a template
	 *
	 * TODO We return true here, because otherwise, another view class is
	 * instantiated in controller.
	 *
	 * @return bool
	 */
	public function hasTemplate() {
		return TRUE;
	}


	/**
	 * @param Tx_PtExtlist_Domain_Configuration_Export_ExportConfig $exportConfiguration
	 */
	public function setExportConfiguration(Tx_PtExtlist_Domain_Configuration_Export_ExportConfig $exportConfiguration) {
		$this->exportConfiguration = $exportConfiguration;
	}


	/**
	 * @return void
	 */
	public function initConfiguration() {
	//	$this->exportConfiguration = $this->configurationBuilder->buildExportConfiguration();
	}	

	
	/**
	 * (non-PHPdoc)
	 * @see Classes/View/Tx_Fluid_View_TemplateView::canRender()
	 */
	public function canRender(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext) {
		return true;
	}



	/**
	 * Helper method for generating file name from TS config
	 *
	 * @return  string      File name of Export File
	 */
	protected function getFilenameFromTs() {
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
	 * @param   string  name of the file to send to the browser
	 * @return  void
	 */
	protected function sendHeader($filename) {
		switch($this->exportConfiguration->getDownloadType()) {

			case Tx_PtExtlist_Domain_Configuration_Export_ExportConfig::OPEN_IN_BROWSER:

				if ($this->exportConfiguration->getContentType()) {
					header('Content-Type: ' . $this->exportConfiguration->getContentType());
				}

				if (headers_sent()) {
					throw new Exception('Some data has already been output to browser, can\'t send Export file.', 1283945901);
				}

				header('Content-disposition: inline; filename="'.$this->getFilenameFromTs().'"');
				break;

			case Tx_PtExtlist_Domain_Configuration_Export_ExportConfig::FORCE_DOWNLOAD:

				if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) {
					header('Content-Type: application/force-download, charset=' . $this->exportConfiguration->getSettings('outputEncoding'));
				} else {
					header('Content-Type: application/octet-stream, charset=' . $this->exportConfiguration->getSettings('outputEncoding'));
				}

				header('Content-disposition: attachment; filename="'. $this->getFilenameFromTs() .'"');
				break;

			default:
				throw new Exception('No valid download handling set for Export file!', 1283945902);
		}
	}
}
?>