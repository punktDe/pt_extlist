<?php
namespace PunktDe\PtExtlist\Domain\StateAdapter;

use PunktDe\PtExtbase\State\GpVars\GpVarsAdapter;
use PunktDe\PtExtbase\State\GpVars\GpVarsAdapterFactory;
use TYPO3\CMS\Core\SingletonInterface;

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
 * Class implements a factory for GET/POST Var Adapter.
 *  
 * Class is an adapter for pt_extbase's gpvarsAdapter. Sets extension namespace of pt_extlist on generic factory method.
 *
 * @package Domain
 * @subpackage StateAdapter
 */
class GetPostVarAdapterFactory implements SingletonInterface
{
    /**
     * @var GpVarsAdapterFactory
     */
    protected $gpVarsAdapterFactory;



    /**
     * @var \PunktDe\PtExtlist\Extbase\ExtbaseContext
     */
    protected $extbaseContext;


    /**
     * @param GpVarsAdapterFactory $gpVarsAdapterFactory
     */
    public function injectGpVarsAdapterFactory(GpVarsAdapterFactory $gpVarsAdapterFactory)
    {
        $this->gpVarsAdapterFactory = $gpVarsAdapterFactory;
    }



    /**
     * @param \PunktDe\PtExtlist\Extbase\ExtbaseContext $extbaseContext
     */
    public function injectExtbaseContext(\PunktDe\PtExtlist\Extbase\ExtbaseContext $extbaseContext)
    {
        $this->extbaseContext = $extbaseContext;
    }


    /**
     * Factory method for GET/POST Var Adapter.
     *  
     * @return GpVarsAdapter Singleton instance of GET/POST Var Adapter.
     */
    public function getInstance()
    {
        return  $this->gpVarsAdapterFactory->getInstance($this->extbaseContext->getExtensionNamespace());
    }
}
