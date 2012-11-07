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
 * Implements data provider for grouped list data
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 */
abstract class Tx_PtExtlist_Domain_Model_Filter_DataProvider_AbstractDataProvider implements Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface {

    /**
	 * Filter configuration object
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	protected $filterConfig;



	/**
	 * Holds a reference to solr dataBackend
	 *
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend;



    /**
     * Injects dataBackend into data provider
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     * @return void
     */
    public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend) {
        $this->dataBackend = $dataBackend;
    }



	/**
	 * (non-PHPdoc)
	 * @see Classes/Domain/Model/Filter/DataProvider/Tx_PtExtlist_Domain_Model_Filter_DataProvider_DataProviderInterface::injectFilterConfig()
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
	}



	/**
	 * Render a single option line by cObject or default
	 *
	 * @param array $optionData
	 * @return string $option;
	 */
	protected function renderOptionData($optionData) {

		foreach($this->displayFields as $displayField) {
        	$values[] = $optionData[$displayField->getIdentifier()];
        }

		$optionData['allDisplayFields'] = implode(' ', $values);

		$option = Tx_PtExtlist_Utility_RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig);

		return $option;
	}

}
?>