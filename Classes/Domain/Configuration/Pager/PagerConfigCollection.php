<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Pager;

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
 * Class implements pager configuration collection
 *
 * @package Domain
 * @subpackage Configuration\Pager
 * @author Daniel Lienert
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Pager_PagerConfigCollectionTest
 */
class PagerConfigCollection extends \PunktDe\PtExtbase\Collection\ObjectCollection
{
    protected $listIdentifier;
    protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Pager_PagerConfig';
    
    
    
    /**
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->listIdentifier = $configurationBuilder->getListIdentifier();
    }
    
    
    
    /**
     * Add pagerconig to list
     *  
     * @param \PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfig $pagerConfig
     * @param string $pagerIdentifier
     */
    public function addPagerConfig(\PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfig $pagerConfig, $pagerIdentifier)
    {
        $this->addItem($pagerConfig, $pagerIdentifier);
    }
    
    
    
    /**
     * @param string $pagerIdentifier
     * @return \PunktDe\PtExtlist\Domain\Configuration\Pager\PagerConfig
     */
    public function getPagerConfig($pagerIdentifier)
    {
        return $this->getItemById($pagerIdentifier);
    }
}
