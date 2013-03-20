<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * Class implements a listData that iterates through a data source
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\List
 */
class Tx_PtExtlist_Domain_Model_List_IterationListData implements Tx_PtExtlist_Domain_Model_List_IterationListDataInterface {

	/**
	 * @var Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDatasourceInterface
	 */
	protected $dataSource;


	/**
	 * @var Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected $currentRow = NULL;


	/**
	 * @var int
	 */
	protected $index = 0;


	/**
	 * @var Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface
	 */
	protected $dataMapper;


	/**
	 * @var Tx_PtExtlist_Domain_Renderer_RendererChain
	 */
	protected $renderChain;


	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDatasourceInterface $dataSource
	 */
	public function _injectDataSource(Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDataSourceInterface $dataSource) {
		$this->dataSource = $dataSource;
	}


	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper
	 */
	public function _injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $dataMapper) {
		$this->dataMapper = $dataMapper;
	}


	/**
	 * @param Tx_PtExtlist_Domain_Renderer_RendererChain $renderChain
	 */
	public function _injectRenderChain(Tx_PtExtlist_Domain_Renderer_RendererChain $renderChain) {
		$this->renderChain = $renderChain;
	}


	/**
	 * Alias function for count to be accessible in fluid
	 *
	 * @return int
	 */
	public function getCount() {
		return $this->count();
	}



	/**
	 * @return int
	 */
	public function count() {
		return $this->dataSource->count();
	}



	/**
	 * Processes the current row (array data structure):
	 * 	1. build a row data structure
	 *  2. render this data structure using the row renderer of the default renderer
	 *
	 * @return Tx_PtExtlist_Domain_Model_List_Row
	 */
	protected function getProcessedCurrentRow() {
		if($this->currentRow !== FALSE) {
			return $this->renderChain->renderSingleRow($this->dataMapper->getMappedRow($this->currentRow), $this->index);
		} else {
			return FALSE;
		}
	}


	/**
	 * Iterator Interface
	 ***********************************************************************/


	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current() {
		if($this->currentRow === NULL) {
			$this->currentRow = $this->dataSource->fetchRow();
		}

		return $this->getProcessedCurrentRow();
	}



	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		$this->currentRow = $this->dataSource->fetchRow();
		$this->index++;
	}



	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return $this->index;
	}



	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid() {
		if($this->index < $this->count()) {
			return true;
		} else {
			return false;
		}
	}



	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		$this->dataSource->rewind();
		$this->index = 0;
		$this->currentRow = NULL;
	}
}
?>