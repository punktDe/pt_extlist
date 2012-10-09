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
 * Interface for listData structure
 *
 * @author Daniel Lienert
 * @package Domain
 * @subpackage Model\List
 */
interface Tx_PtExtlist_Domain_Model_List_IterationListDataInterface extends Iterator {

	/**
	 * Set the datasource
	 *
	 * @param Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDatasourceInterface $dataSource
	 */
	public function _injectDataSource(Tx_PtExtlist_Domain_DataBackend_DataSource_IterationDatasourceInterface $dataSource);


	/**
	 * @param Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $mapper
	 */
	public function _injectDataMapper(Tx_PtExtlist_Domain_DataBackend_Mapper_MapperInterface $mapper);


	/**
	 * @abstract
	 * @param Tx_PtExtlist_Domain_Renderer_RendererChain $renderChain
	 */
	public function _injectRenderChain(Tx_PtExtlist_Domain_Renderer_RendererChain $renderChain);


	/**
	 * Alias function for count to be accessible in fluid
	 *
	 * @return int
	 */
	public function getCount();


	/**
	 * @return int
	 */
	public function count();

}
