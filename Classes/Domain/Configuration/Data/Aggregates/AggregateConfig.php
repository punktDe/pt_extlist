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
 * Class aggregateField config
 *
 * @package pt_extlist
 * @subpackage Domain\Configuration\Data\Aggregates 
 * @author Daniel Lienert <lienert@punkt.de>
 */
class Tx_PtExtlist_Domain_Configuration_Data_Aggregates_AggregateConfig {
	
	/**
	 * aggregate field Identifier
	 * @var string
	 */
	protected $identifier;
	
	
	/**
	 * field identifier
	 * @var string
	 */
	protected $fieldIdentifier;
	
	
	/**
	 * aggregate method
	 * @var string
	 */
	protected $method;
	
	
	
	public function __construct($identifier, $aggregateSettings) {
		tx_pttools_assert::isNotEmptyString($identifier, array('message' => 'No aggregate identifier specified. 1282891490'));
		$this->identifier = $identifier;
		
		if(!trim($aggregateSettings['fieldIdentifier'])) {
			throw new Exception('No fieldIdentifier for aggregate given! 1282891630');
		}
		
		$this->fieldIdentifier = $aggregateSettings['fieldIdentifier'];
		
		if(!$aggregateSettings['method']) {
			throw new Exception('No aggregate method given! 1282891831');
		} else {
			// TODO check if method is valid / implemented
			$this->method = trim($aggregateSettings['method']);
		}
	}
		
	
	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}
	
	
	/**
	 * @return array
	 */
	public function getFieldIdentifier() {
		return $this->fieldIdentifier;
	}
	
	
	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}
}
?>