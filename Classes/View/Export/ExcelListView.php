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

require_once('PHPExcel/PHPExcel.php');

/**
 * Implements a view for rendering Excel sheets from list data
 *
 * Using this export view requires PHPExcel to be installed on your server.
 * For further information @see http://phpexcel.codeplex.com/
 *
 * PHPExcel itself requires php XMLWriter installed and running.
 *
 * @author Michael Knoll
 * @author Daniel Lienert
 * @package View
 * @subpackage Export
 */
class Tx_PtExtlist_View_Export_ExcelListView extends Tx_PtExtlist_View_Export_AbstractExportView {

	/**
	 * Holds an PHPExcel worksheet object
	 *
	 * @var PHPExcel
	 */
	protected $objPHPExcel;


	/**
	 * Holds an instance of FLUID template variable container
	 *
	 * @var Tx_Fluid_Core_ViewHelper_TemplateVariableContainer
	 */
	protected $templateVariableContainer;


	/**
	 * @var Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfigCollection
	 */
	protected $columnConfigCollection;


	/**
	 * Current rowNumber
	 * @var int
	 */
	protected $rowNumber = 1;


	/**
	 * Overwriting the render method to generate Excel output
	 *
	 * @return  void (never returns)
	 */
	public function render() {

		$this->init();
		$this->clearOutputBufferAndSendHeaders();

		$this->renderPreHeaderRows();

		$this->renderHeader();
		$this->renderBody();

		$this->renderPostBodyRows();

		// File format can be changed in FlexForm in 'fileFormat' field.
		// possible values: 'Excel2007', 'Excel5'
		// if no value is given, 'Excel2007' is taken.
		$fileFormat = ($this->exportConfiguration->getSettings('fileFormat') !== array() ? $this->exportConfiguration->getSettings('fileFormat') : 'Excel2007');
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $fileFormat);

		// TODO make output configurable via TS
		$objWriter->save('php://output');

		$this->closeOutputBufferAndExit();
	}


	/**
	 * Overwrite this to render pre header rows
	 */
	protected function renderPreHeaderRows() {}


	/**
	 * Overwrite this to render post body rows
	 */
	protected function renderPostBodyRows() {}


	/**
	 * Render the header row
	 */
	protected function renderHeader() {
		$columnNumber = 0;

		$activeSheet = $this->objPHPExcel->getActiveSheet();

		// Headers
		if ($this->templateVariableContainer->exists('listCaptions')) {
			foreach ($this->templateVariableContainer['listCaptions'] as $columnIdentifier => $caption) {

				/* @var $caption Tx_PtExtlist_Domain_Model_List_Cell */
				$activeSheet->setCellValueByColumnAndRow($columnNumber, $this->rowNumber, strip_tags($caption->getValue()));

				$excelSettings = $this->getExcelSettingsByColumnIdentifier($columnIdentifier);

				/**
				 * Styling
				 */
				$activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->applyFromArray($this->buildHeaderStyle());

				if (array_key_exists('width', $excelSettings)) {
					$activeSheet->getColumnDimensionByColumn($columnNumber)->setWidth($excelSettings['width']);
				} else {
					$activeSheet->getColumnDimensionByColumn($columnNumber)->setAutoSize(true);
				}

				$columnNumber++;
			}
		}

		$this->rowNumber++;
	}



	/**
	 * render all body rows
	 */
	protected function renderBody() {

		$columnNumber = 0;

		$activeSheet = $this->objPHPExcel->getActiveSheet();

		// Rows
		foreach ($this->templateVariableContainer['listData'] as $listRow) {
			/* @var $row Tx_PtExtlist_Domain_Model_List_Row */
			foreach ($listRow as $listCell) {
				/* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
				$activeSheet->setCellValueByColumnAndRow($columnNumber, $this->rowNumber, strip_tags($listCell->getValue()));

				$activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setWrapText(true);
				$activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				$activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setShrinkToFit(true);

				$columnNumber++;
			}

			$this->rowNumber++;
			$columnNumber = 0;
		}
	}


	/**
	 * @param string $columnIdentifier
	 * @return array
	 */
	protected function getExcelSettingsByColumnIdentifier($columnIdentifier) {
		if ($this->columnConfigCollection->hasIdentifier($columnIdentifier)) {
			$excelSettings = $this->columnConfigCollection->getColumnConfigByIdentifier($columnIdentifier)->getSettings('excelExport');
			if (!is_array($excelSettings)) $excelSettings = array();
		}

		return $excelSettings;
	}


	/**
	 * @return array
	 */
	protected function buildHeaderStyle() {

		$defaultBorder = array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('rgb' => '1006A3')
		);

		$headerStyle = array(
			'borders' => array(
				'bottom' => $defaultBorder,
				'left' => $defaultBorder,
				'top' => $defaultBorder,
				'right' => $defaultBorder,
			),
			'fill' => array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'ffcc00'),
			),
			'font' => array(
				'bold' => true,
			)
		);

		return $headerStyle;
	}


	/**
	 * Initializes empty worksheet object
	 *
	 * @return void
	 */
	protected function init() {
		$this->checkRequirements();
		$this->objPHPExcel = new PHPExcel();
		$this->objPHPExcel->setActiveSheetIndex(0);
		$this->templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();
		$this->columnConfigCollection = $this->configurationBuilder->buildColumnsConfiguration();
	}


	/**
	 * Clears output buffer and sends corresponding headers
	 *
	 * @return void
	 */
	protected function clearOutputBufferAndSendHeaders() {
		ob_clean();
		// redirect output to client browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $this->getFilenameFromTs() . '"');
		header('Cache-Control: max-age=0');
	}


	/**
	 * Closes output buffer and exits script
	 *
	 * @return void
	 */
	protected function closeOutputBufferAndExit() {
		exit();
	}


	/**
	 * Checks requirements of Excel export to be working
	 *
	 * @throws Exception
	 * @return void
	 */
	private function checkRequirements() {
		if (!class_exists('PHPExcel')) {
			throw new Exception('Library PHPExcel is required for using Excel export. You can get PHPExcel from http://phpexcel.codeplex.com 1316565593');
			exit();
		}

		if (!class_exists('XMLWriter')) {
			throw new Exception('Library XMLWriter is required for using Excel export. You have to set up PHP with XMLWriter enabled 1316565594');
			exit();
		}
	}
}

?>