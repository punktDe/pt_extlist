<?php


namespace PunktDe\PtExtlist\Domain\DataBackend\Mapper;


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

use PunktDe\PtExtbase\Assertions\Assert;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;

/**
 * Class implements a factory for a data mapper
 *  
 * @package Domain
 * @subpackage DataBackend\Mapper
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_Mapper_MapperFactoryTest
 */
class MapperFactory implements SingletonInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;



    /**
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function injectObjectManager(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }


    
    /**
     * Returns an instance of a data mapper for a given data mapper class name.
     *
     * @param ConfigurationBuilder $configurationBuilder
     * @return mixed
     */
    public function createDataMapper(ConfigurationBuilder $configurationBuilder)
    {
        $dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
        $dataMapperClassName = $dataBackendConfiguration->getDataMapperClass();

        $dataMapper = $this->objectManager->get($dataMapperClassName, $configurationBuilder); /** @var AbstractMapper $dataMapper */
        $mapperConfiguration = $configurationBuilder->buildFieldsConfiguration();

        // Check whether mapper implements interface
        Assert::isTrue($dataMapper instanceof MapperInterface, ['message' => 'Data mapper must implement data mapper interface! 1280415471']);

        $dataMapper->_injectConfigurationBuilder($configurationBuilder);
        $dataMapper->_injectMapperConfiguration($mapperConfiguration);
        $dataMapper->init();

        return $dataMapper;
    }
}
