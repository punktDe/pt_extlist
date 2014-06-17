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
 * Implements a view for rendering CSV values
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_CsvListView extends Tx_PtExtlist_View_Export_AbstractExportView {


    /**
     * @var string sets the output encoding
     */
    protected $outputEncoding = 'ISO-8859-1';


    /**
     * @var string sets the delimiter
     */
    protected $delimiter = ';';


	/**
	 * @var string
	 */
	protected $enclosure = '"';


	/**
	 * @var Tx_Fluid_Core_ViewHelper_TemplateVariableContainer
	 */
	protected $templateVariableContainer;


	/**
	 * @var resource stream
	 */
	protected $outputStreamHandle;


	/**
	 * Init the configuration for CSVExport
	 */
	public function initConfiguration() {
		parent::initConfiguration();

		if ($this->exportConfiguration->getSettings('outputEncoding')) {
			$this->outputEncoding = $this->exportConfiguration->getSettings('outputEncoding');
		}

		if ($this->exportConfiguration->getSettings('delimiter')) {
			$this->delimiter = $this->exportConfiguration->getSettings('delimiter');
		}

		if ($this->exportConfiguration->getSettings('enclosure')) {
			$this->enclosure = $this->exportConfiguration->getSettings('enclosure');
		}
	}


	/**
	 * Overwriting the render method to generate a CSV output
	 *
	 * @return  void (never returns)
	 */
	public function render() {

		$this->templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();

		ob_clean();

		$this->sendHeader($this->getFilenameFromTs());
		$this->outputStreamHandle = fopen('php://output', 'w');

		$this->renderHeader();

		// Render the body
		$this->renderData($this->templateVariableContainer['listData']);

		// Render the aggregates if defined
		$this->renderData($this->templateVariableContainer['aggregateRows']);

		fclose($this->outputStreamHandle);

		exit();
	}



	/**
	 * Render the header
	 */
	public function renderHeader() {

		// Headers
		if ($this->templateVariableContainer->exists('listCaptions')) {
			$row = array();

			foreach ($this->templateVariableContainer['listCaptions'] as $caption) {
				$row[] = iconv('UTF-8', $this->outputEncoding, $caption);
			}

			fputcsv($this->outputStreamHandle, $row, $this->delimiter, $this->enclosure);
		}
	}



	/**
	 * Render given multi-row data
	 */
	public function renderData($data) {

		// Rows
		foreach ($data as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */

			$row = array();

			if($this->outputEncoding == 'UTF-8') {
				foreach ($listRow as &$listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
					$row[] = $listCell->getValue();
				}

			} else {
				foreach ($listRow as &$listCell) {
					$row[] =  iconv('UTF-8', $this->outputEncoding, $listCell->getValue());
				}
			}

			fputcsv($this->outputStreamHandle, $row, $this->delimiter, $this->enclosure);
		}
	}



	/**
	 * @param resource $outputStreamHandle
	 */
	public function setOutputStreamHandle($outputStreamHandle) {
		$this->outputStreamHandle = $outputStreamHandle;
	}


	/**
	 * @return resource
	 */
	public function getOutputStreamHandle() {
		return $this->outputStreamHandle;
	}


}