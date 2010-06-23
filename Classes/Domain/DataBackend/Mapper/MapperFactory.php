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



// TODO implement tests!



/**
 * Class implements a factory for a data mapper
 * 
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory {
	
	/**
	 * Returns an instance of a data mapper for a given data mapper class name.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return mixed
	 */
	public static function createDataMapper(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendSettings = $configurationBuilder->getBackendConfiguration();
		tx_pttools_assert::isNotEmptyString($dataBackendSettings['dataMapperClass'], array("message" => 'No dataMapperClass given in settings'));	
		$dataMapperClassName = $dataBackendSettings['dataMapperClass'];
		
		// Check whether dataMapper class exists
		if (!class_exists($dataMapperClassName)) {
			throw new Exception('Data Mapper class ' . $dataMapperClassName . ' does not exist!');
		}
		$dataMapper = new $dataMapperClassName($configurationBuilder);
		
		return $dataMapper;
	}
	
}

?>