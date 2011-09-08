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
 * Class implements a string filter
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Daniel Lienert 
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_Model_Filter_FullTextFilter extends Tx_PtExtlist_Domain_Model_Filter_AbstractSingleValueFilter {

	/**
	 * @var int
	 */
	protected $minWordLength;



	/**
	 * @return void
	 */
	protected function initFilterByTsConfig() {
		$this->minWordLength = (int) $this->filterConfig->getSettings('minWordLength');
		parent::initFilterByTsConfig();
	}



	/**
	 * Build the filterCriteria for filter
	 *
	 * @return Tx_PtExtlist_Domain_QueryObject_Criteria
	 */
	protected function buildFilterCriteriaForAllFields() {
		$criteria = NULL;

		if ($this->filterValue == '') return NULL;

		$criteria = Tx_PtExtlist_Domain_QueryObject_Criteria::fullText($this->fieldIdentifierCollection, $this->filterValue, $this->getSearchParameterArray());

		return $criteria;
	}



	/**
	 * Build an array with additional search parameter
	 *
	 * @return array
	 */
	protected function getSearchParameterArray() {
		$searchParameter = array();
		
		$searchParameter['booleanMode'] = $this->filterConfig->getSettings('booleanMode') ? true : false;
		$searchParameter['booleanModeWrapWithStars'] = $this->filterConfig->getSettings('booleanModeWrapWithStars') ? true : false;

		return $searchParameter;
	}



	/**
	 * Validates filter
	 *
	 * @return bool True, if filter validates
	 */
	public function validate() {
		if(!$this->isActive() || strlen(trim($this->filterValue)) >= $this->minWordLength) {
			return true;
		} else {
			$this->errorMessage = Tx_Extbase_Utility_Localization::translate('filter.fullText.errorWordTooShort', 'ptExtlist', array($this->minWordLength),'',t3lib_FlashMessage::ERROR);
			return false;
		}
	}



	/**
	 * Not called
	 * @return void
	 */
	protected function buildFilterCriteria(Tx_PtExtlist_Domain_Configuration_Data_Fields_FieldConfig $fieldIdentifier) {}
}
?>