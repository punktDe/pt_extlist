<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\Mapper;

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;

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
 * Abstract class for mapper classes
 *
 * TODO we need custom factory to create data mapper here and be able to create data mapper automatically with DI
 *
 * @author Michael Knoll
 * @package Domain
 * @subpackage DataBackend\Mapper
 */
abstract class AbstractMapper implements MapperInterface
{
    /**
     * Holds mapping configurations
     *
     * @var FieldConfigCollection
     */
    protected $fieldConfigurationCollection = null;



    /**
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;



    public function init()
    {
    }



    /**
     * Sets the mapper configuration
     *
     * @param FieldConfigCollection $mapperConfiguration
     */
    public function _injectMapperConfiguration(FieldConfigCollection $mapperConfiguration)
    {
        $this->fieldConfigurationCollection = $mapperConfiguration;
    }



    /**
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function _injectConfigurationBuilder(ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }
}
