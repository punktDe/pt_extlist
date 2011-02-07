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
 * TODO what is this class doing? Write some comments!
 * 
 * @package Domain
 * @subpackage Lifecycle
 * @author Christoph Ehscheidt 
 */
class tx_PtExtlist_Domain_Lifecycle_LifecycleHookManager {
	
	function updateEnd(&$params, &$reference) {
		
		//If the class can not be resolved, we are not in pt_extlist context. therefore exit here.
		if(!class_exists('Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory')) return;
		
		$lifecycle = Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory::getInstance();
		$lifecycle->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::END);
	}
}

?>