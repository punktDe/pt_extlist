<?php


namespace PunktDe\PtExtlist\Domain\Model\Filter\DataProvider;

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
 * Implements data provider for explicit defined data in typoscript
 *
 * Have a look at the documentation for an example.
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\Filter\DataProvider
 * @see Tx_PtExtlist_Tests_Domain_Model_Filter_DataProvider_ExplicitDataTest
 */
class ExplicitData extends \PunktDe\PtExtlist\Domain\Model\Filter\DataProvider\AbstractDataProvider
{
    /**
     * array of options defined in typoscript
     *  
     * @var array
     */
    protected $tsOptions;

    

    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/DataProvider_DataProviderInterface::getRenderedOptions()
     */
    public function getRenderedOptions()
    {
        $renderedOptions = [];
        
        foreach ($this->tsOptions as $key => $option) {
            if (is_array($option)) {
                if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($option['value'], 'LLL:')) {
                    $optionData['allDisplayFields'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($option['value'], '');
                } else {
                    $optionData['allDisplayFields'] = $option['value'];
                }
                
                $optionKey = $option['key'];
            } else {
                $optionKey = $key;
                if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($option, 'LLL:')) {
                    $optionData['allDisplayFields'] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($option, '');
                } else {
                    $optionData['allDisplayFields'] = trim($option);
                }
            }

            $renderedOptions[$optionKey] = ['value' => \PunktDe\PtExtlist\Utility\RenderValue::renderByConfigObjectUncached($optionData, $this->filterConfig),
                                                     'selected' => false];
        }
        
        return $renderedOptions;
    }


    
    /**
     * (non-PHPdoc)
     * @see Classes/Domain/Model/Filter/DataProvider/DataProvider_DataProviderInterface::init()
     */
    public function init()
    {
        $this->initDataProviderByTsConfig($this->filterConfig->getSettings());
    }

    
    
    /**
     * Init the dataProvider by TS-conifg
     *  
     * @param array $filterSettings
     */
    protected function initDataProviderByTsConfig($filterSettings)
    {
        $this->tsOptions = $this->filterConfig->getSettings('options');
        Assert::isArray($this->tsOptions, ['message' => 'Options configured by TS has to be an array, '.gettype($this->tsOptions).' given! 1284142006']);
    }
}
