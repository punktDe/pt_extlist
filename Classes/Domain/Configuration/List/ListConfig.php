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
 * Class implements configuration for list defaults
 *
 * @package Domain
 * @subpackage Configuration\List
 * @author Daniel Lienert 
 */
class Tx_PtExtlist_Domain_Configuration_List_ListConfig extends Tx_PtExtlist_Domain_Configuration_AbstractExtlistConfiguration {
	
	/**
	 * @var string 
	 */
	protected $headerPartial;
	
	
	/**
	 * @var string
	 */
	protected $bodyPartial;
	
	
	/**
	 * @var string headerPartial
	 */
	protected $aggregateRowsPartial;


	/**
	 * @var boolean
	 */
	protected $useIterationListData;


	/**
	 * Is set to true, if rendering (e.g. of cells) should be cached
	 *
	 * @var bool
	 */
	protected $cacheRendering;

	
	/**
	 * Set the properties
	 */
	protected function init() {
		$this->setBooleanIfExistsAndNotNothing('cacheRendering');
		$this->setValueIfExistsAndNotNothing('headerPartial');
		$this->setValueIfExistsAndNotNothing('bodyPartial');
		$this->setValueIfExistsAndNotNothing('aggregateRowsPartial');
		$this->setBooleanIfExistsAndNotNothing('useIterationListData');
	}


	/**
	 * @return string
	 */
	public function getHeaderPartial() {
		return $this->headerPartial;
	}
	
	
	
	/**
	 * @return string
	 */
	public function getBodyPartial() {
		return $this->bodyPartial;		
	}
	
	
	
	/**
	 * @return string
	 */
	public function getAggregateRowsPartial() {
		return $this->aggregateRowsPartial;
	}


	/**
	 * @return boolean
	 */
	public function getUseIterationListData() {
		return $this->useIterationListData;
	}



	/**
	 * @return integer
	 */
	public function getSortingDirection() {
		return $this->sortingDirection;
	}



	/**
	 * @return bool
	 */
	public function getCacheRendering() {
		return $this->cacheRendering;
	}
}
?>