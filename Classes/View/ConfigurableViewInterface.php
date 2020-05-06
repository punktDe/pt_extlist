<?php
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
 * Interface for configurable views that allow injection of configuration builder
 *
 * @author Michael Knoll <knoll@punkt.de>
 * @package 
 * @subpackage 
 * @see 
 */
interface Tx_PtExtlist_View_ConfigurableViewInterface extends \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
{
    /**
     * Injects configuration builder via setter.
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @return mixed
     */
    public function setConfigurationBuilder(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder);
}
