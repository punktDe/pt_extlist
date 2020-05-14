<?php

namespace PunktDe\PtExtlist\Domain\Model\BreadCrumbs;


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

use PunktDe\PtExtbase\Collection\ObjectCollection;
use PunktDe\PtExtbase\State\GpVars\GpVarsInjectableInterface;
use PunktDe\PtExtbase\State\IdentifiableInterface;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Class implements a collection of breadcrumbs
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_Model_BreadCrumbs_BreadCrumbCollectionTest
 */
class BreadCrumbCollection extends ObjectCollection
    implements IdentifiableInterface,
               GpVarsInjectableInterface
{
    /**
     * Restrict collection to breadcrumb class
     *
     * @var String
     */
    protected $restrictedClassName = BreadCrumb::class;



    /**
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;



    /**
     * @var array
     */
    protected $gpVarData;



    /**
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function injectConfigurationBuilder(ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }



    /**
     * Inject the GPVarData
     *
     * @param array $gpVarData
     */
    public function _injectGPVars($gpVarData)
    {
        $this->gpVarData = $gpVarData;
    }



    /**
     * Adds a breadcrumb to collection
     *
     * @param BreadCrumb $breadCrumb BreadCrumb to be added
     */
    public function addBreadCrumb(BreadCrumb $breadCrumb)
    {
        $breadcrumbIdentifier = $breadCrumb->getFilter()->getFilterBoxIdentifier() . '.' . $breadCrumb->getFilter()->getFilterIdentifier();
        $this->addItem($breadCrumb, $breadcrumbIdentifier);
    }



    /**
     * @see PunktDe_PtExtbase_State_IdentifiableInterface
     */
    public function getObjectNamespace()
    {
        return $this->configurationBuilder->getListIdentifier() . '.' . 'breadcrumbs';
    }



    /**
     * 
     *
     */
    public function resetFilters()
    {
        $breadCrumbIdentifier = $this->gpVarData['filterboxIdentifier'] . '.' . $this->gpVarData['filterIdentifier'];
        if ($this->hasItem($breadCrumbIdentifier)) {
            $this->getItemById($breadCrumbIdentifier)->getFilter()->reset();
        }
    }
}
