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
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Model/Filter/FilterInterface.php';
require_once t3lib_extMgm::extPath('pt_extlist') . 'Classes/Domain/Model/Filter/AbstractFilter.php';

/**
 * Class implements fake implementation of abstract filter (for testing only!)
 *
 * @author Michael Knoll 
 * @package Tests
 * @subpackage Domain\Model\Filter
 */
class Tx_PtExtlist_Tests_Domain_Model_Filter_Stubs_FilterStub extends Tx_PtExtlist_Domain_Model_Filter_AbstractFilter {
	
	public function persistToSession() {}
    #public function getObjectNamespace() {return 'testnamespace';}
    public function injectSessionData(array $sessionData) {}
    public function init() {}
    protected function initFilterByTsConfig() {}
    protected function initFilterBySession() {}
    protected function initFilterByGpVars() {}
    protected function buildFilterQuery() {}
	protected function buildFilterCriteriaForAllFields() {}
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {}
    protected function initFilter() {}
 	protected function setActiveState() {}
    public function reset() {}
    public function getFilterBreadCrumb() {return null;}
    public function getFilterValueForBreadCrumb() { return null;}
    public function isActive() {return false;}
    public function getValue() {}
	
}

?>