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
	 * @var PHPExcel_Worksheet
	 */
	protected $activeSheet;


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


	/*
	 * array
	 */
	protected $bodyCellStyleCache;


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
		// if no value is given, 'Excel5' is taken.
		$fileFormat = ($this->exportConfiguration->getSettings('fileFormat') ? $this->exportConfiguration->getSettings('fileFormat') : 'Excel5');
		$objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, $fileFormat);

		$this->saveOutputAndExit($objWriter);
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

		// Headers
		if ($this->templateVariableContainer->exists('listCaptions')) {
			foreach ($this->templateVariableContainer['listCaptions'] as $columnIdentifier => $caption) {

				/* @var $caption Tx_PtExtlist_Domain_Model_List_Cell */
				$this->activeSheet->setCellValueByColumnAndRow($columnNumber, $this->rowNumber, strip_tags($caption->getValue()));

				$excelSettings = $this->getExcelSettingsByColumnIdentifier($columnIdentifier);

				/**
				 * Width
				 */
				if (is_array($excelSettings) && array_key_exists('width', $excelSettings)) {
					$this->activeSheet->getColumnDimensionByColumn($columnNumber)->setWidth($excelSettings['width']);
				} else {
					$this->activeSheet->getColumnDimensionByColumn($columnNumber)->setAutoSize(true);
				}

				$this->doCellStyling($columnNumber, $columnIdentifier, 'header');

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
			foreach ($listRow as $columnIdentifier => $listCell) {
				/* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */

				//$activeSheet->setCellValueByColumnAndRow($columnNumber, $this->rowNumber, strip_tags($listCell->getValue()));

				//$this->doCellStyling($columnNumber, $columnIdentifier, 'body');
				$activeSheet->getCellByColumnAndRow($columnNumber, $this->rowNumber)->setValue(
					strip_tags($listCell->getValue())
				);

				$columnNumber++;
			}

			$this->rowNumber++;
			$columnNumber = 0;
		}

		//$activeSheet->fromArray($outputArray, NULL, 'A'.$bodyStartRowNumber);
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
	 * @param $columnNumber
	 * @param $columnIdentifier
	 * @param $type
	 */
	protected function doCellStyling($columnNumber, $columnIdentifier, $type) {

		$excelSettings = $this->getExcelSettingsByColumnIdentifier($columnIdentifier);
		if(!is_array($excelSettings[$type])) return;
		$settings = $excelSettings[$type];

		if($settings['wrapText']) $this->activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setWrapText($settings['wrapText']);
		if($settings['vertical']) $this->activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setVertical($settings['vertical']);
		if($settings['shrinkToFit']) $this->activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->getAlignment()->setShrinkToFit($settings['shrinkToFit']);

		if($type == 'body') {
			if(!array_key_exists($columnIdentifier, $this->bodyCellStyleCache)) {
				$this->bodyCellStyleCache[$columnIdentifier] = $this->buildStyleArray($settings);
			}

			$this->activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->applyFromArray($this->bodyCellStyleCache[$columnIdentifier]);
		} else {
			$this->activeSheet->getStyleByColumnAndRow($columnNumber, $this->rowNumber)->applyFromArray($this->buildStyleArray($settings));
		}
	}



	/**
	 * @param $styleSettings
	 * @return array
	 */
	protected function buildStyleArray($styleSettings) {

		$style = array();

		if(array_key_exists('borders', $styleSettings)) {
			$style['borders']['bottom'] = $this->buildBorderStyle($styleSettings['borders']['bottom']);
			$style['borders']['left'] = $this->buildBorderStyle($styleSettings['borders']['left']);
			$style['borders']['top'] = $this->buildBorderStyle($styleSettings['borders']['top']);
			$style['borders']['right'] = $this->buildBorderStyle($styleSettings['borders']['right']);
		}

		if(array_key_exists('fill', $styleSettings)) {
			$style['fill'] = array(
				'type' => $styleSettings['fill']['type'],
				'color' => array('rgb' => $styleSettings['fill']['color'])
			);
		}

		if(array_key_exists('font', $styleSettings)) {
			$style['font'] = $styleSettings['font'];
		}

		return $style;
	}



	/**
	 * @param $borderStyleSettings string
	 * @return array
	 */
	protected function buildBorderStyle($borderStyleSettings) {
		return array(
			'style' => $borderStyleSettings['style'],
			'color' => array('rgb' => $borderStyleSettings['color']),
		);
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
		$this->activeSheet = $this->objPHPExcel->getActiveSheet();

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
	 * @param $objWriter PHPExcel_Writer_IWriter
	 */
	protected function saveOutputAndExit(PHPExcel_Writer_IWriter $objWriter) {
		$objWriter->save('php://output');
		error_log('Export of ' . count($this->templateVariableContainer['listData']) . ', needed ' . memory_get_usage(true) / (1024*1024) . ' MB.');
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