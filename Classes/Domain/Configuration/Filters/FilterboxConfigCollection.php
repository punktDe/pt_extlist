<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Filters;

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
use PunktDe\PtExtbase\Exception\InternalException;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Class FilterboxConfig Collection
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Configuration\Filters
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterboxConfigCollectionTest
 */
class FilterboxConfigCollection extends ObjectCollection
{
    protected $listIdentifier;



    protected $restrictedClassName = FilterboxConfig::class;



    /**
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function __construct(ConfigurationBuilder $configurationBuilder)
    {
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
    }



    /**
     * Add a filterbox config
     * @param FilterboxConfig $filterBox
     * @param string $filterBoxIdentifier
     * @param $filterBoxIdentifier
     * @throws InternalException
     */
    public function addFilterBoxConfig(FilterboxConfig $filterBox, $filterBoxIdentifier)
    {
        $this->addItem($filterBox, $filterBoxIdentifier);
    }


    /**
     * @param $filterBoxIdentifier
     * @return FilterboxConfig
     * @throws InternalException
     */
    public function getFilterBoxConfig($filterBoxIdentifier)
    {
        return $this->getItemById($filterBoxIdentifier);
    }
}
