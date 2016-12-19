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
 * Class implements header column factory
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Model\List\Header
 * @see Tx_PtExtlist_Twests_Domain_Model_List_Header_HeaderColumnFactoryTest
 */
class Tx_PtExtlist_Domain_Model_List_Header_HeaderColumnFactory extends Tx_PtExtlist_Domain_AbstractComponentFactoryWithState
{
    /**
     * build an instance of a header column by columnConfiguration 
     * 
     * @param Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfiguration
     * @return Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn
     */
    public function createInstance(Tx_PtExtlist_Domain_Configuration_Columns_ColumnConfig $columnConfiguration)
    {
        $headerColumn = new Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn();
        $headerColumn->injectColumnConfig($columnConfiguration);

        $this->sessionPersistenceManagerBuilder->getInstance()->registerObjectAndLoadFromSession($headerColumn);
        $this->getPostVarsAdapterFactory->getInstance()->injectParametersInObject($headerColumn);

        // Register headerColumn in sorter
        // TODO we cannot use DI here since this would lead to cyclic dependencies
        $sorterFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('Tx_PtExtlist_Domain_Model_Sorting_SorterFactory'); /* @var $sorterFactory Tx_PtExtlist_Domain_Model_Sorting_SorterFactory */
        $sorter = $sorterFactory->getInstance($columnConfiguration->getConfigurationBuilder());
        $sorter->registerSortingObserver($headerColumn);

        $headerColumn->init();
        
        return $headerColumn;
    }
}
