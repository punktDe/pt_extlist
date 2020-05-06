<?php


namespace PunktDe\PtExtlist\View;

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
 * Class implements base view for all pt_extlist views. 
 *  
 * Class type for base view can be changed by changing pt_extlist AbstractController::resolveView()
 *
 * @author Daniel Lienert 
 * @author Michael Knoll
 * @package View
 */
class BaseView
    extends PunktDe_PtExtbase_View_BaseView
    implements \PunktDe\PtExtlist\View\ConfigurableViewInterface
{
    /**
     * @var \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder
     */
    protected $configurationBuilder;
    
    
    /**
     * Inject the configurationBuilder
     *  
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return mixed|void
     */
    public function setConfigurationBuilder(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }
    

    /**
     * Called by setConfiguration
     *
     * @return void
     */
    protected function initConfiguration()
    {
    }
}
