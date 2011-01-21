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
 * @author Christoph Ehscheidt 
 * 
 * @package Domain
 * @subpackage Security
 */
class Tx_PtExtlist_Domain_Security_GroupSecurity implements Tx_PtExtlist_Domain_Security_SecurityInterface {
	
	protected $usergroups;
	
	public function __construct() {
		//$this->usergroups = $GLOBALS['TSFE']->fe_user->user['groupData'];
		$this->usergroups = $GLOBALS['TSFE']->fe_user->user['usergroup'];
	}
	
	/**
	 * Evaluates if a column is accessable by the FE-User(-Group).
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig
	 * 
	 * @return bool
	 */
	public function isAccessableColumn(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
				
		// FAIL if one of this tests are failing.
		if(!$this->checkFields($columnConfig->getFieldIdentifier())) {
			return false;
		}
		// OR
		if(!$this->checkColumn($columnConfig)) {
			return false;
		}
		
		// OTHERWISE allow access.
		return true;
	}
	
	protected function checkColumn(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfig) {
		$groups = $columnConfig->getAccessGroups();
		
		if(!is_array($groups)) return true; // for testing purposes
		if(empty($groups)) return true;
		
		return $this->compareAccess($groups);
	}
	
	/**
	 * Evaluates if a filter is accessable by the FE-User(-Group).
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
	 * 
	 * @return bool
	 */
	public function isAccessableFilter(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configBuilder) {
		
		$fieldConfigCollection =  $filterConfig->getFieldIdentifier();
		
		// FAIL if one of this tests are failing.
		if(!$this->checkFields($fieldConfigCollection)) {
			return false;
		}
		
		// OR
		if(!$this->checkFilter($filterConfig)) {
			return false;
		}
		
		// OTHERWISE allow access.
		return true;
	}
	
	
	/**
	 * Check field access 
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection
	 */
	protected function checkFields(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection) {

		foreach($fieldConfigCollection as $fieldConfig) {
			$ident = $fieldConfig->getAccessGroups();
				
			if( empty($ident) ) continue;
			if(! $this->compareAccess($ident) ) return false;
		}
		
		return true;
	}
	
	protected function checkFilter(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filter) {
		$groups = $filter->getAccessGroups();
		if(!is_array($groups)) return true; // for testing purposes
	
		if(empty($groups)) return true;
		
		return $this->compareAccess($groups);
	}
	
	/**
	 * Compare the defined access credentials with internal data (e.g. FE-USER-GROUP)
	 * 
	 * @param string $groups
	 * @return bool
	 */
	protected function compareAccess(array $groups) {
		if(count($groups) == 0) return true;

		foreach($groups as $group) {
			$group = trim($group);
			if(empty($group)) return true;
			
			$groupArray = t3lib_div::trimExplode(',', $this->usergroups);
			
			foreach($groupArray as $groupData) {
				
				if($group == $groupData) return true;
			}
		}
		
		return false;
	}
}

?>