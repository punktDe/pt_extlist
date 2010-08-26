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

/**
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * 
 * @package Domain
 * @subpackage Security
 */
class Tx_PtExtlist_Domain_Security_GroupSecurity implements Tx_PtExtlist_Domain_Security_SecurityInterface {
	
	protected $usergroups;
	
	public function __construct() {
		$this->usergroups = $GLOBALS['TSFE']->fe_user->user['groupData'];
		
	}
	/**
	 * Evaluates if a filter is accessable by the FE-User(-Group).
	 * 
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
	 * 
	 * @return bool
	 */
	public function isAccessableFilter(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig, Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configBuilder) {
		$fieldIdentifier = $filterConfig->getFieldIdentifier();
		$fieldIds = explode(',', trim($fieldIdentifier));
		$fieldConfigCollection = $configBuilder->buildFieldsConfiguration();
		
		if(!$this->checkFields($fieldConfigCollection, $fieldIds)) {
			return false;
		}
	
		// TODO: check if filter has own access rules...
		
		return true;
	}
	
	protected function checkFields(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfigCollection $fieldConfigCollection, array $fieldIds) {
		foreach($fieldIds as $fieldId) {
			$fieldConfig = $fieldConfigCollection->getItemById($fieldId);
			$ident = $fieldConfig->getAccessGroups();
				
			if( empty($ident) ) continue;
			if(! $this->compareAccess($ident) ) return false;

		}
		
		return true;
	}
	
	/**
	 * Compare the defined access credentials with internal data (e.g. FE-USER-GROUP)
	 * 
	 * @param string $ident
	 * @return bool
	 */
	protected function compareAccess(array $ident) {
	
		
		foreach($ident as $group) {
			foreach($this->usergroups as $groupData) {
				
				if($group == $groupData['uid']) return true;
			}
		}
		
		return false;
	}
}

?>