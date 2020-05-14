<?php


namespace PunktDe\PtExtlist\Domain;

use PunktDe\PtExtbase\State\Session\SessionPersistenceManagerBuilder;
use PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory;

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Michael Knoll <knoll@punkt.de>, punkt.de GmbH
*
*
*  All rights reserved
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
 * Abstact base class for component factories that use injected object manager.
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package Domain
 */
abstract class AbstractComponentFactoryWithState extends \PunktDe\PtExtlist\Domain\AbstractComponentFactory
{
    /**
     * @var SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * @var \PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory
     */
    protected $getPostVarsAdapterFactory;



    /**
     * @param SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }



    /**
     * @param GetPostVarAdapterFactory $getPostVarAdapterFactory
     */
    public function injectGetPostVarAdapterFactory(GetPostVarAdapterFactory $getPostVarAdapterFactory)
    {
        $this->getPostVarsAdapterFactory = $getPostVarAdapterFactory;
    }
}
