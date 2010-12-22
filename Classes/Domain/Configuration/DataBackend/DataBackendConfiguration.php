<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>
*  All rights reserved
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
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_DataBackend_DataBackendConfiguration {
	
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
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$this->dataBackendSettings = $configurationBuilder->getSettingsForConfigObject('dataBackend');

		$this->checkAndSetDataBackendClass($this->dataBackendSettings['dataBackendClass']);
		$this->checkAndSetDataMapperClass($this->dataBackendSettings['dataMapperClass']);
		$this->checkAndSetQueryInterpreterClass($this->dataBackendSettings['queryInterpreterClass']);
	}
	
	
	
	/**
	 * Check existance of given class and set the property
	 * @param string $dataBackendClassName
	 */
	protected function checkAndSetDataBackendClass($dataBackendClassName) {
		tx_pttools_assert::isNotEmptyString($dataBackendClassName, array('message' => 'dataBackendClass must not be empty! 1281178473'));   
		tx_pttools_assert::isTrue(class_exists($dataBackendClassName), array('message' =>' Data Backend class ' . $dataBackendClassName . ' does not exist! 1281178474'));
		$this->dataBackendClass = $dataBackendClassName;
	}
	
	
	
	/**
	 * Check existance of given class and set the property
	 * @param string $dataMapperClassName
	 */
	protected function checkAndSetDataMapperClass($dataMapperClassName) {
		tx_pttools_assert::isNotEmptyString($dataMapperClassName, array('message' => 'dataMaperClass must not be empty! 1281178475'));   
		tx_pttools_assert::isTrue(class_exists($dataMapperClassName), array('message' =>' Datamapper class ' . $dataMapperClassName . ' does not exist! 1281178476'));
		$this->dataMapperClass = $dataMapperClassName;
	}
	
	
	
	/**
	 * Check existance of given class and set the property
	 * @param string $queryInterpreterClassname
	 */
	protected function checkAndSetQueryInterpreterClass($queryInterpreterClassname) {
		tx_pttools_assert::isNotEmptyString($queryInterpreterClassname, array('message' => 'queryInterpreterClass must not be empty! 1281178538'));   
		tx_pttools_assert::isTrue(class_exists($queryInterpreterClassname), array('message' =>' QueryInterpreter class ' . $queryInterpreterClassname . ' does not exist! 1281178539'));
		$this->queryInterpreterClass = $queryInterpreterClassname;
	}
	
	
	
	/**
	 * @return string classname;
	 */
	public function getDataBackendClass() {
		return $this->dataBackendClass;
	}
	
	
	
	/**
	 * @return string classname;
	 */
	public function getDataMapperClass() {
		return $this->dataMapperClass;
	}
	
	
	/**
	 * @return string classname;
	 */
	public function getQueryInterpreterClass() {
		return $this->queryInterpreterClass;
	}
	
	
	
	/**
	 * @key if given, return the setting of this key
	 * @return mixed databackend settings
	 */
	public function getDataBackendSettings($key = '') {
		if($key != '') {
			if(array_key_exists($key, $this->dataBackendSettings)) {
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
	public function getDataSourceSettings() {
		if(is_array($this->dataBackendSettings['dataSource'])) {
			return $this->dataBackendSettings['dataSource'];	
		} else {
			return array();
		}
	}
}
?>