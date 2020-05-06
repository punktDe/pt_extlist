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
/**
 * Class FilterboxConfig Collection
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Configuration\Filters
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterboxConfigCollectionTest
 */
class FilterboxConfigCollection extends \PunktDe\PtExtbase\Collection\ObjectCollection
{
    protected $listIdentifier;



    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig';



    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
    }



    /**
     * Add a filterbox config
     * @param \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig $filterBox
     * @param string $filterBoxIdentifier
     */
    public function addFilterBoxConfig(\PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig $filterBox, $filterBoxIdentifier)
    {
        $this->addItem($filterBox, $filterBoxIdentifier);
    }



    /**
     * @param $filterBoxIdentifier
     * @return \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterboxConfig
     */
    public function getFilterBoxConfig($filterBoxIdentifier)
    {
        return $this->getItemById($filterBoxIdentifier);
    }
}
