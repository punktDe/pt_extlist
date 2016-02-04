<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements a factory for the columnSelector.
 *
 * @author Daniel Lienert
 * @package pt_extlist
 * @subpackage Domain\Model\columnSelector
 */
class Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelectorFactory
    extends Tx_PtExtlist_Domain_AbstractComponentFactoryWithState
    implements \TYPO3\CMS\Core\SingletonInterface
{
    /**
     * Holds singleton instances of column selectors for each list
     *
     * @var array<Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelector>
     */
    private $instances = array();



    /**
     * Factory method for returning a singleton instance of a column selector
     *
     * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
     * @return Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelector
     */
    public function getInstance(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder)
    {
        $listIdentifier = $configurationBuilder->getListIdentifier();

        if ($this->instances[$listIdentifier] === null) {
            $this->instances[$listIdentifier] = new  Tx_PtExtlist_Domain_Model_ColumnSelector_ColumnSelector();
            $this->instances[$listIdentifier]->setConfiguration($configurationBuilder->buildColumnSelectorConfiguration());

            // Inject settings from session.
            $this->sessionPersistenceManagerBuilder->getInstance()->registerObjectAndLoadFromSession($this->instances[$listIdentifier]);

            // Inject settings from gp-vars.
            $this->getPostVarsAdapterFactory->getInstance()->injectParametersInObject($this->instances[$listIdentifier]);
        }

        return $this->instances[$listIdentifier];
    }
}
