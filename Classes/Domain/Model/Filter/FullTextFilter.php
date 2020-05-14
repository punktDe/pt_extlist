<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfig;
use PunktDe\PtExtlist\Domain\QueryObject\Criteria;

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
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_FullTextFilterTest
 */
class FullTextFilter extends AbstractSingleValueFilter
{
    /**
     * @var integer
     */
    protected $minWordLength;



    /**
     * @return void
     */
    protected function initFilterByTsConfig()
    {
        $this->minWordLength = (int) $this->filterConfig->getSettings('minWordLength');
        parent::initFilterByTsConfig();
    }



    /**
     * Build the filterCriteria for filter
     *
     * @return Criteria
     */
    protected function buildFilterCriteriaForAllFields()
    {
        $criteria = null;

        if ($this->filterValue == '') {
            return null;
        }

        $criteria = Criteria::fullText($this->fieldIdentifierCollection, $this->filterValue, $this->getSearchParameterArray());

        return $criteria;
    }



    /**
     * Build an array with additional search parameter
     *
     * @return array
     */
    protected function getSearchParameterArray()
    {
        $searchParameter = [];
        
        $searchParameter['booleanMode'] = $this->filterConfig->getSettings('booleanMode') ? true : false;
        $searchParameter['booleanModeWrapWithStars'] = $this->filterConfig->getSettings('booleanModeWrapWithStars') ? true : false;

        return $searchParameter;
    }



    /**
     * Validates filter
     *
     * @return bool True, if filter validates
     */
    public function validate()
    {
        if (!$this->isActive() || strlen(trim($this->filterValue)) >= $this->minWordLength) {
            return true;
        } else {
            $this->errorMessage = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('filter.fullText.errorWordTooShort', 'ptExtlist', [$this->minWordLength], '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            return false;
        }
    }



    /**
     * Not called
     * @param FieldConfig $fieldIdentifier
     * @return void
     */
    protected function buildFilterCriteria(FieldConfig $fieldIdentifier)
    {
    }
}
