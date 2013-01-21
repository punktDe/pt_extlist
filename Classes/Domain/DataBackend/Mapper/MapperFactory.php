<?php
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
 * Class implements a factory for a data mapper
 * 
 * @package Domain
 * @subpackage DataBackend\Mapper
 * @author Michael Knoll 
 */
class Tx_PtExtlist_Domain_DataBackend_Mapper_MapperFactory {
	
	/**
	 * Returns an instance of a data mapper for a given data mapper class name.
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @return mixed
	 */
	public static function createDataMapper(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder) {
		$dataBackendConfiguration = $configurationBuilder->buildDataBackendConfiguration();
		$dataMapperClassName = $dataBackendConfiguration->getDataMapperClass();

		$dataMapper = new $dataMapperClassName($configurationBuilder);
		$mapperConfiguration = $configurationBuilder->buildFieldsConfiguration();

		// Check whether mapper implements interface
		Tx_PtExtbase_Assertions_Assert::isTrue($dataMapper instanceof Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface, array('message' => 'Data mapper must implement data mapper interface! 1280415471'));

		$dataMapper->injectMapperConfiguration($mapperConfiguration);
		$dataMapper->init();

		return $dataMapper;
	}
	
}

?>