<?php


namespace PunktDe\PtExtlist\Domain\Link;


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


use PunktDe\PtExtbase\State\GpVars\GpVarsAdapter;
use PunktDe\PtExtbase\State\IdentifiableInterface;
use PunktDe\PtExtlist\Domain\Configuration\Lists\ListConfig;

/**
 * Link manager provides all links used in this plugin
 * TODO: implement shortlinks
 *
 * @package Domain
 * @subpackage Link
 * @author Daniel Lienert
 */
class LinkManager
{
    /**
     * @var GpVarsAdapter
     */
    protected $getPostVarAdapter;



    /**
     * @var ListConfig
     */
    protected $listConfiguration;



    /**
     * Inject the get post var adapter
     * @param GpVarsAdapter $getPostVarAdapter
     */
    public function injectGetPostVarAdapter(GpVarsAdapter $getPostVarAdapter)
    {
        $this->getPostVarAdapter = $getPostVarAdapter;
    }



    /**
     * Inject the List configuration
     * @param $listConfig
     */
    public function injectListConfiguration(ListConfig $listConfig)
    {
        $this->listConfiguration = $listConfig;
    }



    /**
     * Build and return the argument array for the given object
     *
     * @param IdentifiableInterface $object
     * @param array $properties
     */
    public function buildArgumentArrayForObject(IdentifiableInterface $object, array $properties)
    {
    }
}
