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
 * Interface for all filter classes
 *
 * @package Typo3
 * @subpackage pt_extlist
 * @author Michael Knoll <knoll@punkt.de>
 */
interface Tx_PtExtlist_Domain_Model_Filter_FilterInterface {

	/**
	 * Injector for filter configuration
	 *
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 */
	public function injectFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig);
	
	
	
	/**
	 * Injector for get / post vars adapter
	 *
	 * @param Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $gpVarAdapter
	 */
	public function injectGpVarAdapter(Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapter $gpVarAdapter);
	
	
	
	/**
     * Injector for session persistence manager
     *
     * @param Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager
     */
    public function injectSessionPersistenceManager(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManager $sessionPersistenceManager);
	
	
	
	/**
     * Injector for associated data backend
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend);
	
	
	
	/**
	 * Returns identifier of filter
	 * 
	 * @return string Identifier of filter
	 */
	public function getFilterIdentifier();
	
	
	
	/**
	 * Returns identifier of associated list
	 * 
	 * return @string Identifier of associated list
	 */
	public function getListIdentifier();
	
	
	
	/**
	 * Returns Identifier of filterbox to which this filter belongs
	 *
	 * @return string Identifier of filterbox to which this filter belongs
	 */
	public function getFilterBoxIdentifier();
	
	
	
	/**
	 * Returns query object for this filter
	 * 
     * @return Tx_PtExtlist_Domain_QueryObject_QueryObject Query object that describes criterias for this filter
	 */
	public function getFilterQuery();
	
	
	
	/**
	 * Initializes filter settings
	 * 
	 * @return void
	 */
	public function init();
	
	
	
	/**
	 * Resets filter
	 *
	 * @return void
	 */
	public function reset();
	
	
	
	/**
	 * Checks whether a filter validates. 
	 * 
	 * @return bool True, if filter validates
	 */
	public function validate();
	
	
	
	/**
	 * Returns validation errors for this filter
	 * 
	 * @return array Error messages for filter
	 */
	public function getErrorMessages();
	
}

?>