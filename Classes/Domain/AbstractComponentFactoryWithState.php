<?php


namespace PunktDe\PtExtlist\Domain;

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
     * @var PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * @var \PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory
     */
    protected $getPostVarsAdapterFactory;



    /**
     * @param PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(PunktDe_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }



    /**
     * @param \PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory $getPostVarAdapterFactory
     */
    public function injectGetPostVarAdapterFactory(\PunktDe\PtExtlist\Domain\StateAdapter\GetPostVarAdapterFactory $getPostVarAdapterFactory)
    {
        $this->getPostVarsAdapterFactory = $getPostVarAdapterFactory;
    }
}
