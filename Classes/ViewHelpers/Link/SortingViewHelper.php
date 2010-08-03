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

class  Tx_PtExtlist_ViewHelpers_Link_SortingViewHelper extends Tx_Fluid_ViewHelpers_Link_ActionViewHelper {

	/**
	 * @param $header Tx_PtExtlist_Domain_Model_List_Header_ListHeader
	 * @param $action string
	 * 
	 */
	public function render(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header, $action='show') {
		
		$value = $this->invertSortingState($header->getSortingState());
		$param = $this->buildNamespaceArray($header, $value);
	
		return parent::render($action,$param);
	}
	
	/**
	 * Inverting the current sorting state.
	 * 
	 * @param int $sortingState
	 * @return int The inverted sorting state.
	 */
	protected function invertSortingState($sortingState) {
		switch($sortingState) {
			case Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC:
				return Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC;
			case Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_DESC:
				return Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_ASC;
			default:
				return Tx_PtExtlist_Domain_QueryObject_Query::SORTINGSTATE_NONE;
		}
	}
	
	/**
	 * Building a namespace array filled with an value.
	 * 
	 * @param $header Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface
	 * @param $value mixed
	 * @return array The built array filled with the given value.
	 */
	public function buildNamespaceArray(Tx_PtExtlist_Domain_StateAdapter_IdentifiableInterface $header, $value) {
		$nameSpace = $header->getObjectNamespace();
		tx_pttools_assert::isNotEmptyString($nameSpace, array('message' => 'No ObjectNamespace returned from Obejct ' . get_class($object) . '! 1280771624'));
		
		$identChunks =  t3lib_div::trimExplode('.', $nameSpace);
		
		$returnArray = array();
		$pointer = &$returnArray;
		
		// Build array
		foreach($identChunks as $chunk) {
			$pointer = &$pointer[$chunk];
		}

		// Add value
		$pointer = $header->getSortingState();
		
		return $returnArray;
	}
}

?>