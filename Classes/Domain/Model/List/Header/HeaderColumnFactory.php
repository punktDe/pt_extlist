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
 * Class implements header column factory
 * 
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory {
	
	public static function createInstance(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfiguration) {
		$headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
		
		$headerColumn->injectColumnConfig($columnConfiguration);
		
		// Inject settings from session.
        $sessionPersistenceManager = Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance();
        $sessionPersistenceManager->loadFromSession($headerColumn);
        
        // Inject settings from gp-vars.
        $gpAdapter = Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance();
        $gpAdapter->getParametersByObject($headerColumn);
		
		$headerColumn->init();
		
		$sessionPersistenceManager->persistToSession($headerColumn);
		
		return $headerColumn;
	}

}
?>