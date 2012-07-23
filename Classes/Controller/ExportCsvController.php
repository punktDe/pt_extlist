<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Controller for CSV export of raw data.
 *
 * This controller can be used if you have to export a huge amount of data
 * without using any rendering!
 *
 * @package Controller
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class Tx_PtExtlist_Controller_ExportCsvController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Reset ConfigurationBuilder for actions in this Controller
	 *
	 * @var bool
	 */
	protected $resetConfigurationBuilder = TRUE;



	/**
	 * @var string
	 */
	protected $exportListIdentifier;



	/**
	 * @return void
	 */
	public function initializeAction() {
		parent::initializeAction();

		$this->exportListIdentifier = $this->settings['exportListIdentifier'];
		if(!$this->exportListIdentifier) $this->exportListIdentifier = $this->listIdentifier;
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($this->exportListIdentifier, array('message' => 'No exportListidentifier set. 1316446015'));
	}



	/**
	 * @return void
	 */
	public function showLinkAction() {
		$fileExtension = $this->configurationBuilder->buildExportConfiguration()->getFileExtension();
		$this->view->assign('fileExtension', $fileExtension);
	}



	/**
	 * Returns download for given parameters
	 *
	 * @return string
	 */
	public function downloadAction() {
		$dataBackend = $this->dataBackend;
		$this->exportConfiguration = $dataBackend->getConfigurationBuilder()->buildExportConfiguration();
		if($this->settings['exportListIdentifier']) {
			$exportListConfiguration = $this->settings['listConfig'][$this->settings['exportListIdentifier']];
			$extlistContext = Tx_PtExtlist_ExtlistContext_ExtlistContextFactory::getContextByCustomConfiguration($exportListConfiguration, $this->settings['exportListIdentifier'], false);
			$dataBackend = $extlistContext->getDataBackend();
		}

		if (!is_a($dataBackend, 'Tx_PtExtlist_Domain_DataBackend_Typo3ExportDataBackend_Typo3ExportDataBackend')) {
			throw new Exception('ExportCsvController requires Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3ExportDataBackend as data backend. 1343068340');
		} /* @var $dataBackend Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3ExportDataBackend */

		ob_clean();

		$this->sendHeader($this->getFilenameFromTs());
		$out = fopen('php://output', 'w');
		$rows = $dataBackend->getRawExportData();

		foreach ($rows as &$row) {

			// TODO We do not convert encoding ATM
			#if($this->outputEncoding != 'UTF-8') {
			#	$row = Tx_PtExtbase_Div::iconvArray($row, 'UTF-8', $this->outputEncoding);
			#}

			fputcsv($out, $row, ";");
		}

		fclose($out);

		exit();
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
					throw new Exception('Some data has already been output to browser, can\'t send Export file 1283945901');
				}

				header('Content-disposition: inline; filename="'.$this->getFilenameFromTs().'"');
				break;

			case Tx_PtExtlist_Domain_Configuration_Export_ExportConfig::FORCE_DOWNLOAD:

				if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')) {
					header('Content-Type: application/force-download');
				} else {
					header('Content-Type: application/octet-stream');
				}

				header('Content-disposition: attachment; filename="'. $this->getFilenameFromTs() .'"');
				break;

			default:
				throw new Exception('No valid download handling set for Export file! 1283945902');
		}
	}

}
?>