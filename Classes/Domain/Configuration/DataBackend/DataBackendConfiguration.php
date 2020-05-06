<?php


namespace PunktDe\PtExtlist\Domain\Configuration\DataBackend;

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
 * Databackend configuration class. Holds configuration parameters for the dataBackend
 *
 * @package Domain
 * @subpackage Configuration\DataBackend
 * @author Daniel Lienert 
 */
class DataBackendConfiguration extends \PunktDe\PtExtlist\Domain\Configuration\AbstractExtlistConfiguration
{
    /**
     * Holds th classname of the databackend class
     * @var string
     */
    protected $dataBackendClass;
    
    
    
    /**
     * Holds th classname of the datamapper class
     * @var string
     */
    protected $dataMapperClass;
    

    /**
     * Holds the className of the datasource class
     * @var string
     */
    protected $dataSourceClass;

    
    /**
     * Holds th classname of the queryinterpreter class
     * @var string
     */
    protected $queryInterpreterClass;
    
    
    
    /**
     *  
     * Array of databackendspecific settings
     * @var array
     */
    protected $dataBackendSettings;
    
    
    
    /**
     * Constructor for data backend configuration
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        parent::__construct($configurationBuilder, $configurationBuilder->getSettingsForConfigObject('dataBackend'));

        $this->dataBackendSettings = $configurationBuilder->getSettingsForConfigObject('dataBackend');

        $this->checkAndSetDataBackendClass($this->dataBackendSettings['dataBackendClass']);
        $this->checkAndSetDataMapperClass($this->dataBackendSettings['dataMapperClass']);
        $this->checkAndSetDataSourceClass($this->dataBackendSettings['dataSourceClass']);
        $this->checkAndSetQueryInterpreterClass($this->dataBackendSettings['queryInterpreterClass']);
    }
    
    
    
    /**
     * Check existance of given class and set the property
     * @param string $dataBackendClassName
     */
    protected function checkAndSetDataBackendClass($dataBackendClassName)
    {
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($dataBackendClassName, ['message' => 'dataBackendClass must not be empty! 1281178473']);
        PunktDe_PtExtbase_Assertions_Assert::isTrue(class_exists($dataBackendClassName), ['message' =>' Data Backend class ' . $dataBackendClassName . ' does not exist! 1281178474']);
        $this->dataBackendClass = $dataBackendClassName;
    }
    
    
    
    /**
     * Check existance of given class and set the property
     * @param string $dataMapperClassName
     */
    protected function checkAndSetDataMapperClass($dataMapperClassName)
    {
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($dataMapperClassName, ['message' => 'dataMapperClass must not be empty! 1281178475']);
        PunktDe_PtExtbase_Assertions_Assert::isTrue(class_exists($dataMapperClassName), ['message' =>' Datamapper class ' . $dataMapperClassName . ' does not exist! 1281178476']);
        $this->dataMapperClass = $dataMapperClassName;
    }


    /**
     * Check existance of given class and set the property
     * @param string $dataSourceClassName
     */
    protected function checkAndSetDataSourceClass($dataSourceClassName)
    {
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($dataSourceClassName, ['message' => 'dataSourceClassName must not be empty! 1337589668']);
        PunktDe_PtExtbase_Assertions_Assert::isTrue(class_exists($dataSourceClassName), ['message' =>' DataSourceClass ' . $dataSourceClassName . ' does not exist! 1337589669']);
        $this->dataSourceClass = $dataSourceClassName;
    }

    
    
    /**
     * Check existance of given class and set the property
     * @param string $queryInterpreterClassName
     */
    protected function checkAndSetQueryInterpreterClass($queryInterpreterClassName)
    {
        PunktDe_PtExtbase_Assertions_Assert::isNotEmptyString($queryInterpreterClassName, ['message' => 'queryInterpreterClass must not be empty! 1281178538']);
        PunktDe_PtExtbase_Assertions_Assert::isTrue(class_exists($queryInterpreterClassName), ['message' =>' QueryInterpreter class ' . $queryInterpreterClassName . ' does not exist! 1281178539']);
        $this->queryInterpreterClass = $queryInterpreterClassName;
    }
    
    
    
    /**
     * @return string className;
     */
    public function getDataBackendClass()
    {
        return $this->dataBackendClass;
    }
    
    
    
    /**
     * @return string className;
     */
    public function getDataMapperClass()
    {
        return $this->dataMapperClass;
    }


    /**
     * @return string className;
     */
    public function getDataSourceClass()
    {
        return $this->dataSourceClass;
    }

    
    /**
     * @return string classname;
     */
    public function getQueryInterpreterClass()
    {
        return $this->queryInterpreterClass;
    }
    
    
    
    /**
     * @key if given, return the setting of this key
     * @return mixed databackend settings
     */
    public function getDataBackendSettings($key = '')
    {
        if ($key != '') {
            if (array_key_exists($key, $this->dataBackendSettings)) {
                return $this->dataBackendSettings[$key];
            } else {
                return '';
            }
        }
        
        return $this->dataBackendSettings;
    }
    
    
    
    /**
     * return array datasource settings
     */
    public function getDataSourceSettings()
    {
        if (is_array($this->dataBackendSettings['dataSource'])) {
            return $this->dataBackendSettings['dataSource'];
        } else {
            return [];
        }
    }
}
