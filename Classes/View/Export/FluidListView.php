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
 * Implements a view for rendering a export with fluid
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_View_Export_FluidListView Extends Tx_PtExtlist_View_Export_AbstractExportView {

    /**
     * Overwriting the render method to generate a downloadable output
     *
     * @return  void (never returns)
     */
    public function render() {
    	
    	ob_clean();

    	$outputData = parent::render();
    	
        $this->sendHeader($this->fullFilename);
        $out = fopen('php://output', 'w');
		
        fwrite($out, $outputData);
        
        fclose($out);

        exit();
    }
    
    
    
    /**
     * Returns file ending, if no file ending is given in TS
     *
     * @return string
     */
    protected function getDefaultFilePrefix() {
    	return 'csv';
    }
	
}