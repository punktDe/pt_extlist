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

use PunktDe\PtExtbase\State\IdentifiableInterface;
use PunktDe\PtExtlist\Domain\Configuration\BreadCrumbs\BreadCrumbsConfig;
use PunktDe\PtExtlist\Domain\Model\Filter\FilterInterface;

/**
 * Class implements a filter breadcrumb
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Model_BreadCrumbs_BreadCrumbTest
 */
class BreadCrumb implements IdentifiableInterface
{
    /**
     * The listIdentifier for which this pager is active.
     *
     * @var string
     */
    protected $listIdentifier;

    

    /**
     * Associated filter object
     *
     * @var FilterInterface
     */
    protected $filter;

    

    /**
     * Message to be shown as breadcrumb
     *
     * @var string
     */
    protected $message;


    
    /**
     * True, if filter can be resetted via breadcrumb
     *
     * @var bool
     */
    protected $isResettable = true;
    
    
    
    /**
     * Holds an instance of breadcrumbs configuration
     *
     * @var BreadCrumbsConfig
     */
    protected $breadCrumbsConfiguration;
    


    /**
     * Constructor for breadcrumb. Takes filter object to show breadcrumb for as parameter
     *
     * @param FilterInterface $filter
     */
    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
        $this->listIdentifier = $filter->getListIdentifier();
    }
    
    
    
    /**
     * Injects breadcrumb configuration
     *
     * @param BreadCrumbsConfig $breadCrumbsConfig
     */
    public function injectBreadCrumbsConfiguration(BreadCrumbsConfig $breadCrumbsConfig)
    {
        $this->breadCrumbsConfiguration = $breadCrumbsConfig;
    }



    /**
     * @see PunktDe_PtExtbase_State_IdentifiableInterface::getObjectNamespace()
     *
     * @return String
     */
    public function getObjectNamespace()
    {
        return $this->listIdentifier . '.demolist.breadcrumb';
    }



    /**
     * Getter for filter object
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }



    /**
     * Setter for breadcrumb message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }



    /**
     * Getter for breadcrumb message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }



    /**
     * Setter for is resettable (true, if filter can be resetted via breadcrumb)
     *
     * @param bool $isResettable
     */
    public function setIsResettable($isResettable)
    {
        $this->isResettable = $isResettable;
    }



    /**
     * Getter for is resettable (true, if filter can be resetted via breadcrumb)
     *
     * @return bool
     */
    public function getIsResettable()
    {
        return $this->isResettable;
    }
    
    
    
    /**
     * Returns true, if reset links should be shown
     *
     * @return bool
     */
    public function getShowResetLinks()
    {
        return $this->breadCrumbsConfiguration->getShowResetLinks();
    }
}
