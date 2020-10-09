<?php

namespace PunktDe\PtExtlist\Domain\Model\ColumnSelector;


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

use PunktDe\PtExtbase\State\GpVars\GpVarsInjectableInterface;
use PunktDe\PtExtbase\State\Session\SessionPersistableInterface;
use PunktDe\PtExtlist\Domain\Configuration\ColumnSelector\ColumnSelectorConfig;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\HeaderColumn;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;

/**
 * Class implements columnSelector for handling dynamic hiding and unhiding of columns
 *
 * @package pt_extlist
 * @subpackage Domain\Model\ColumnSelector
 * @author Daniel Lienert
 */
class ColumnSelector
    implements GpVarsInjectableInterface,
    SessionPersistableInterface
{
    /**
     * Holds configuration
     *
     * @var ColumnSelectorConfig
     */
    protected $configuration;



    /**
     * @var array
     */
    protected $selectedColumnIdentifiers = [];



    /**
     * Injector for sorter configuration
     *
     * @param ColumnSelectorConfig $configuration
     */
    public function setConfiguration(ColumnSelectorConfig $configuration)
    {
        $this->configuration = $configuration;
    }



    /**
     * Generates an unique namespace for an object to be used
     * for addressing object specific session data and gp variables.
     *
     * Expected notation: ns1.ns2.ns3.(...)
     *
     * @return String Unique namespace for object
     */
    public function getObjectNamespace()
    {
        return $this->configuration->getListIdentifier() . '.columnSelector';
    }



    /**
     * Injects GetPost Vars into object
     *
     * @param array $GPVars GP Var data to be injected into the object
     */
    public function _injectGPVars($GPVars)
    {
        if (array_key_exists('selectedColumns', $GPVars) && is_array($GPVars['selectedColumns'])) {
            $this->selectedColumnIdentifiers = $GPVars['selectedColumns'];
        }
    }



    /**
     * Called by any mechanism to persist an object's state to session
     *
     * @return array Object's state to be persisted to session
     */
    public function _persistToSession()
    {
        $returnValue = null;
        if ($this->configuration->getPersistToSession()) {
            $returnValue = ['selectedColumns' => $this->selectedColumnIdentifiers];
        }
        return $returnValue;
    }



    /**
     * Called by any mechanism to inject an object's state from session
     *
     * @param array $sessionData Object's state previously persisted to session
     */
    public function _injectSessionData(array $sessionData)
    {
        if ($this->configuration->getPersistToSession()
                && array_key_exists('selectedColumns', $sessionData) && is_array($sessionData['selectedColumns'])) {
            $this->selectedColumnIdentifiers = $sessionData['selectedColumns'];
        }
    }



    /**
     * @return ColumnSelectorConfig
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }



    /**
     * Set the visibility of columns
     *
     * @param ListHeader $listHeader
     */
    public function setVisibilityOnListHeader(ListHeader $listHeader)
    {
        foreach ($listHeader as $columnIdentifier => $headerColumn) {
            /** @var HeaderColumn $headerColumn */
            if (in_array($columnIdentifier, $this->selectedColumnIdentifiers)) {
                $headerColumn->setIsVisible(true);
            } else {
                if ($this->configuration->getOnlyShowSelectedColumns()) {
                    $headerColumn->setIsVisible(false);
                }
            }
        }
    }
}
