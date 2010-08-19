<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * @author Michael Knoll <knoll@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_View_List_CsvListView Extends Tx_PtExtlist_View_BaseView {

    /**
     * Overwriting the render method to generate a CSV output
     *
     * @return  void (never returns)
     */
    public function render() {
        ob_clean();

        $csvContent = '';

        $full_filename = $this->generateFilenameFromTs();
        $this->sendHeader($full_filename);
        $out = fopen('php://output', 'w');

        // Headers
        $row = array();
        foreach ($this->variables['listHeader'] as $header) { /* @var $header Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn */
                $row[] = $header->getLabel();
        }
        fputcsv($out, $row, ";");

        // Rows
        foreach ($this->variables['listData'] as $listRow) { /* @var $row Tx_PtExtlist_Domain_Model_List_Row */
        	$row = array();
        	foreach ($listRow as $listCell) { /* @var $listCell Tx_PtExtlist_Domain_Model_List_Cell */
        		$row[] = $listCell->getValue();
        	}
        	$row = tx_pttools_div::iconvArray($row, 'UTF-8', 'ISO-8859-1');     // TODO: make encoding configurable via TS
            fputcsv($out, $row, ";");
        }

        fclose($out);

        exit();
    }
    
    
    
    /**
     * Helper method to generate file name from TS config
     * 
     * @return  string      File name of CSV File
     */
    protected function generateFilenameFromTs() {
        
        // load TS configuration for CSV generation
        $csvfFilename = $this->variables['settings']['view']['CsvListView']['fileName'];
        $useDateAndTimeInFilename = $this->variables['settings']['view']['CsvListView']['useDateAndTimestampInFilename'];

        if ($useDateAndTimeInFilename == '1') {
            $fileNamePrefix = $this->variables['settings']['view']['CsvListView']['fileNamePrefix'];
            if ($fileNamePrefix == '' ) {
                $fileNamePrefix = 'itemlist_';
            }
            $full_filename = $fileNamePrefix.date('Y-m-d', time()) .'.csv';
        } elseif ($csvfFilename != '') {
           $full_filename = $csvfFilename;
        }
        return $full_filename;
        
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
        
        $downloadType = $this->variables['settings']['view']['CsvListView']['fileHandlingType'];
        
        if ($downloadType == '') {
            $downloadType = 'I';
        }
        tx_pttools_assert::isInList($downloadType, 'D,I', array('message' => 'Invalid download type'));
        
        switch($downloadType)
        {
                case 'I':
                        //We send to a browser
                        header('Content-Type: text/x-csv');
                        if(headers_sent())
                                throw new Exception('Some data has already been output to browser, can\'t send CSV file');
                        header('Content-disposition: inline; filename="'.$filename.'"');
                        break;

                case 'D':
                        //Download file
                        if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
                                header('Content-Type: application/force-download');
                        else
                                header('Content-Type: application/octet-stream');
                        header('Content-disposition: attachment; filename="'.$filename.'"');
                        break;

                default:
                        throw new Exception('No valid download handling set for CSV file! 1281366941');
        }
        
    }
    
	
}