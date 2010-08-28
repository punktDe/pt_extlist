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
 * Interface for configuration objects wich hold configuration for list columns
 *
 * @package pt_extlist
 * @subpackage Domain\Configuration
 * @author Daniel Lienert <lienert@punkt.de>
 */
interface Tx_PtExtlist_Domain_Configuration_ColumnConfigInterface extends Tx_PtExtlist_Domain_Configuration_RenderConfigInterface {
	
	
	/**
	 * Returns the identifier for the column
	 * @return string
	 */
	public function getColumnIdentifier();
	
	
	/**
	 * Returns an array of field identifiers, the datafields assigned to this collumn
	 * @return array
	 */
	public function getFieldIdentifier();
	
	
	
	public function getSpecialCell();
}
?>
