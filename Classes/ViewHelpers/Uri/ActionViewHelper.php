<?php
namespace PunktDe\PtExtlist\ViewHelpers\Uri;


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

use PunktDe\PtExtbase\State\Session\SessionPersistenceManagerBuilder;

/**
 * ActionViewHelper, adds state hash to every link
 *  
 * @author Daniel Lienert 
 * @package ViewHelpers
 * @subpackage Uri
 */
class ActionViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Uri\ActionViewHelper
{
    /**
     * Holds instance of session persistence manager builder
     *
     * @var SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * Injects session persistence manager factory (used by DI)
     *
     * @param SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }

    /**
     * @return string Rendered link
     * @author Sebastian KurfÃ¼rst <sebastian@typo3.org>
     * @author Bastian Waidelich <bastian@typo3.org>
     */
    public function render()
    {
        $this->sessionPersistenceManagerBuilder->getInstance()->addSessionRelatedArguments($this->arguments['arguments']);
        
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uri = $uriBuilder
            ->reset()
            ->setTargetPageUid($this->arguments['pageUid'])
            ->setTargetPageType($this->arguments['pageType'])
            ->setNoCache($this->arguments['noCache'])
            ->setUseCacheHash(!$this->arguments['noCacheHash'])
            ->setSection($this->arguments['section'])
            ->setFormat($this->arguments['format'])
            ->setLinkAccessRestrictedPages($this->arguments['linkAccessRestrictedPages'])
            ->setArguments($this->arguments['additionalParams'])
            ->setCreateAbsoluteUri($this->arguments['absolute'])
            ->setAddQueryString($this->arguments['addQueryString'])
            ->setArgumentsToBeExcludedFromQueryString($this->arguments['argumentsToBeExcludedFromQueryString'])
            ->uriFor($this->arguments['action'], $this->arguments['arguments'], $this->arguments['controller'], $this->arguments['extensionName'], $this->arguments['pluginName']);

        return $uri;
    }
}
