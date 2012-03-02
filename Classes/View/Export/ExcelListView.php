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
	 * Overwriting the render method to generate Excel output
	 *
	 * @return  void (never returns)
	 */
	public function render() {



        $this->init();
        $this->clearOutputBufferAndSendHeaders();

        $rowNumber = 1;
        $columnNumber = 0;


        // Headers
        if ($this->templateVariableContainer->exists('listCaptions')) {
            foreach ($this->templateVariableContainer['listCaptions'] as $caption) { /* @var $caption Tx_PtExtlist_Domain_Model_List_Cell */
                $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, strip_tags($caption->getValue()));
                $columnNumber++;
			}

            $rowNumber++;
            $columnNumber = 0;
		}

		// Rows
		foreach ($this->templateVariableContainer['listData'] as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */
			foreach ($listRow as $listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
				$this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, strip_tags($listCell->getValue()));
                $columnNumber++;
			}

            $rowNumber++;
            $columnNumber = 0;

			#$row = Tx_PtExtbase_Div::iconvArray($row, 'UTF-8', 'ISO-8859-1');     // TODO: make encoding configurable via TS
		}


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
     * Initializes empty worksheet object
     * 
     * @return void
     */
    protected function init() {
        $this->checkRequirements();
        $this->objPHPExcel = new PHPExcel();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();
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