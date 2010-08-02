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
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Model/Filter/FilterInterface.php';
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Model/Filter/AbstractFilter.php';

/**
 * Class implements fake implementation of abstract filter (for testing only!)
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package Typo3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {
	
	public function persistToSession() {}
    public function getObjectNamespace() {return 'testnamespace';}
    public function injectSessionData(array $sessionData) {}
    public function init() {}
    protected function initFilterByTsConfig() {}
    protected function initFilterBySession() {}
    protected function iniFilterByGpVars() {}
	
}

?>