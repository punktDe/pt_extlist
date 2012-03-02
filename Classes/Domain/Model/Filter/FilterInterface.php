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
 * Interface for all filter classes
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Michael Knoll 
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
	 * @param Tx_PtExtbase_State_GpVars_GpVarsAdapter $gpVarAdapter
	 */
	public function injectGpVarAdapter(Tx_PtExtbase_State_GpVars_GpVarsAdapter $gpVarAdapter);

	
	
	/**
     * Injector for associated data backend
     *
     * @param Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend
     */
	public function injectDataBackend(Tx_PtExtlist_Domain_DataBackend_DataBackendInterface $dataBackend);



	/**
	 * Injects filterbox to which this filter is associated to
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox
	 */
	public function injectFilterbox(Tx_PtExtlist_Domain_Model_Filter_Filterbox $filterbox);

	
	
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
     * @return Tx_PtExtlist_Domain_QueryObject_Query Query object that describes criterias for this filter
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
	 * Returns validation error for this filter
	 * 
	 * @return string Error message for filter
	 */
	public function getErrorMessage();
	
	
	
	/**
     * Returns filter configuration of this filter
     *
     * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterConfiguration
     */
    public function getFilterConfig();
    
    
    
    /**
     * Returns filter breadcrumb for this filter
     * 
     * @return Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb
     */
    public function getFilterBreadCrumb();
    
    
    
    /**
     * Returns true, if filter is active
     * 
     * @return bool True, if filter is active
     */
    public function isActive();
	
    
    
    /**
     * Returns the current filtervalues of this filter
     * 
     * @return variant
     */
    public function getValue();
}

?>