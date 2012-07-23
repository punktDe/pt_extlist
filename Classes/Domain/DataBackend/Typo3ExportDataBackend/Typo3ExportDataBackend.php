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
 * Data backend for TYPO3 database with special functionality for export
 * 
 * @author Michael Knoll 
 * @package Domain
 * @subpackage Domain\DataBackend\Typo3ExportDataBackend
 */
class Tx_PtExtlist_Domain_DataBackend_Typo3ExportDataBackend_Typo3ExportDataBackend extends Tx_PtExtlist_Domain_DataBackend_Typo3DataBackend_Typo3DataBackend {

	/**
	 * Returns array of raw query data for export
	 *
	 * @return array Array of raw query data
	 */
	public function getRawExportData() {
		$rawDataArray = $this->getRawQueryDataArray();
		return $rawDataArray;
	}

}
?>