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
 * Maps data to a domain object
 * 
 * @package Domain
 * @subpackage Renderer\Default
 * @author Daniel Lienert
 */
class Tx_PtExtlist_Domain_Renderer_Default_ObjectMapper implements t3lib_Singleton {


	/**
	 * @var Tx_Extbase_Property_Mapper
	 */
	protected $mapper;



	/**
	 * @param Tx_Extbase_Property_Mapper $mapper
	 */
	public function injectMapper(Tx_Extbase_Property_Mapper $mapper) {
		$this->mapper = $mapper;
	}


	/**
	 * @param $data
	 * @param Tx_PtExtlist_Domain_Configuration_Columns_ObjectMapper_ObjectMapperConfig $configuration
	 * @return object
	 */
	public function convert($data, $configuration) {

		if($configuration->getMapping()) {
			$this->applyKeyMapping($configuration->getMapping(), $data);
		}

		$mappedObject = $this->mapper->map(array(), $data, $configuration->getClass());

		if($mappedObject === NULL) {
			throw new Exception('The data could mot be mapped to the object of class' . $configuration->getClass() .
					  '. Reason: '. implode(', ', $this->mapper->getMappingResults()->getErrors()));
		}

		return $mappedObject;
	}



	/**
	 * @param array $mapping
	 * @param array $data
	 */
	protected function applyKeyMapping(array $mapping, array &$data) {
		
		foreach($mapping as $oldKey => $newKey) {
			if(array_key_exists($oldKey, $data)) {
				$data[$newKey] = $data[$oldKey];
				unset($data[$oldKey]);
			}
		}
	}


}
?>