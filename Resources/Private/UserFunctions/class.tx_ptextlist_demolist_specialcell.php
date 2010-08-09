<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>,
*  Christoph Ehscheidt <ehscheidt@punkt.de>
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

class tx_ptextlist_demolist_specialcell {

	/**
	 * Returns an array with special values for a cell.
	 * 
	 * array('key1' => 'value1', 'key2' => 'value2')
	 * 
	 * 
	 * @param Tx_PtExtlist_Domain_Model_List_Cell &$cell
	 * @return array
	 */
	public static function processCell(Tx_PtExtlist_Domain_Model_List_Cell &$cell) {
		$columnIndex = $cell->getColumnIndex();
		$rowIndex = $cell->getRowIndex();
		$content = $cell->getValue();
		
		$specialValues = array();

		$color = self::fadeColor($columnIndex, $rowIndex);
		
		$specialValues['style'] = "background-color:rgb($color[0],$color[1],$color[2]);";

		return $specialValues;
	}
	
	public static function processGrey(Tx_PtExtlist_Domain_Model_List_Cell &$cell) {
	
		$color[] = 161;
		$color[] = 255;
		$color[] = 161;
		
		$specialValues['style'] = "background-color:rgb($color[0],$color[1],$color[2]);";

		return $specialValues;
	}
	
	public static function fadeColor($index, $row, $items = 5, $rowItems = 10) {
		
		$offset=floor((161/$rowItems)*$row);
		
		$s  = array(161-$offset,161-$offset,161-$offset);
		$e  = array(255-$offset,255-$offset,255-$offset);
		
		$r = array();
		$r[0] = ceil(max(0,$s[0]-((($e[0]-$s[0])/-$items)*$index)));
		$r[0] = 255-$offset;
	    $r[1] = ceil(max(0,$s[1]-((($e[1]-$s[1])/-$items)*$index)));
	    $r[2] = ceil(max(0,$s[2]-((($e[2]-$s[2])/-$items)*$index)));
//	    $r[2] = 200-$offset;
		    
		return $r;  
	}
	
}

?>