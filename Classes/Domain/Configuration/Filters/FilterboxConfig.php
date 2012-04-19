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
 * Class Filterbox Config 
 * 
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage Configuration\Filters
 */
class Tx_PtExtlist_Domain_Configuration_Filters_FilterboxConfig extends Tx_PtExtbase_Collection_ObjectCollection {

	
	/**
	 * Hash map between filter identifier and numeric filter index
	 * 
	 * @var array
	 */
	protected $filterIdentifierToFilterIndex;
	
	
	
	/**
	 * Identifier of current list
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * @var string
	 */
	protected $restrictedClassName = 'Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig';
	
	
	
	/**
	 * Identifier of this filterbox
	 * @var string
	 */
	protected $filterboxIdentifier;

	
	
	/**
	 * Show a reset link / button
	 * @var boolean
	 */
	protected $showReset = true;

	
	
	/**
	 * Show a submit link / button
	 * @var boolean
	 */
	protected $showSubmit = true;
	
	
	
	/**
	 * Holds ID of page to which should be redirected after filterbox submits
	 *
	 * @var int
	 */
	protected $redirectOnSubmitPageId = null;
	
	
	
	/**
	 * Holds name of controller used for redirect after filterbox submits
	 *
	 * @var string
	 */
	protected $redirectOnSubmitControllerName = null;
	
	
	
	/**
	 * Holds name of action used for redirect after filterbox submits
	 *
	 * @var string
	 */
	protected $redirectOnSubmitActionName = null;



    /**
     * Holds an array of filters that are excluded from where
     * part if this filterbox is submitted.
     *
     * @var array
     */
    protected $excludeFilters = array();



    /**
     * If set to true, this filterbox has been submitted in current request
     * 
     * @var bool
     */
    protected $isSubmittedFilterbox = false;



    /**
     * Target PID to submit filterbox to
     * 
     * @var string
     */
    protected $submitToPage;



	/**
	 * If set to true, sorting should be reset to default if filterbox is submit
	 *
	 * @var boolean
	 */
	protected $resetToDefaultSortingOnSubmit = TRUE;
	
	
	
	/**
	 * Holds an instance of configuration builder
	 *
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	
	
	/**
	 * @param Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder
	 * @param string $filterboxIdentifier
	 * @param array $filterBoxSettings
	 */
	public function __construct(Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder $configurationBuilder, $filterboxIdentifier, $filterBoxSettings) {
		
		Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterboxIdentifier, array('message' => 'FilterboxIdentifier must not be empty! 1277889451'));
		
		$this->configurationBuilder = $configurationBuilder;
		$this->listIdentifier = $configurationBuilder->getListIdentifier();
		$this->filterboxIdentifier = $filterboxIdentifier;
		
		$this->setOptionalSettings($filterBoxSettings);
	}
	
	
	
	/**
	 * Getter for configuration builder
	 *
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	public function getConfigurationBuilder() {
		return $this->configurationBuilder;
	}
	
	
	
	/**
	 * Add FilterConfig to the FilterboxConfig
	 * 
	 * @param Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig
	 * @param string $filterIdentifier
	 */
	public function addFilterConfig(Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig $filterConfig, $filterIndex)  {
		$this->addItem($filterConfig, $filterIndex);
		$this->filterIdentifierToFilterIndex[$filterConfig->getFilterIdentifier()] = $filterIndex;
	}
	
	
	
	/**
	 * Get the filterconfig by filterIdentifier
	 * 
	 * @param string $filterIdentifier
	 * @return Tx_PtExtlist_Domain_Configuration_Filters_FilterConfig
	 */
	public function getFilterConfigByFilterIdentifier($filterIdentifier) {
		return $this->getItemById($this->filterIdentifierToFilterIndex[$filterIdentifier]);
	}
	
	
	
	/**
	 * Set the optional settings
	 * 
	 * @param array $filterBoxSettings
	 */
	protected function setOptionalSettings($filterBoxSettings) {

        if (array_key_exists('submitToPage', $filterBoxSettings)) {
            $this->submitToPage = $filterBoxSettings['submitToPage'];
        }

		if (array_key_exists('showReset', $filterBoxSettings)) {
			$this->showReset = $filterBoxSettings['showReset'] == 1 ? true : false;
		}
		
		if (array_key_exists('showSubmit', $filterBoxSettings)) {
			$this->showSubmit = $filterBoxSettings['showSubmit'] == 1 ? true : false;
		}

		if (array_key_exists('resetToDefaultSortingOnSubmit', $filterBoxSettings)) {
			$this->resetToDefaultSortingOnSubmit = $filterBoxSettings['resetToDefaultSortingOnSubmit'] == 1 ? true : false;
		}
		
		if (array_key_exists('redirectOnSubmit', $filterBoxSettings)) {
			$redirectSettings = $filterBoxSettings['redirectOnSubmit'];
			if (array_key_exists('action', $redirectSettings)) {
				$this->redirectOnSubmitActionName = $redirectSettings['action'];
			} else {
				throw new Exception('You have redirect on submit configured for your filterbox ' . $this->getFilterboxIdentifier() . ' but have set no action to redirect to. You always have to set an action, even if it is nonesense! 1313610240');
			}
			if (array_key_exists('pageId', $redirectSettings)) {
				$this->redirectOnSubmitPageId = $redirectSettings['pageId'];
			}
			if (array_key_exists('controller', $redirectSettings)) {
				$this->redirectOnSubmitControllerName = $redirectSettings['controller'];
			}
		}

        if (array_key_exists('excludeFilters', $filterBoxSettings)) {
            $this->setExcludeFilters($filterBoxSettings['excludeFilters']);
        }
		
	} 



    /**
     * Setter for exclude filters for this filterbox.
     *
     * Set excludeFilters = filterboxIdentifier1.filterIdentifier1, filterboxIdentifier1.filterIdentifier2, ...
     *
     * @param $excludeFiltersString
     * @return void
     */
    protected function setExcludeFilters($excludeFiltersString) {
        $excludeFilters = t3lib_div::trimExplode(',',$excludeFiltersString);
        foreach($excludeFilters as $excludedFilter) {
            list($filterboxIdentifier, $filterIdentifier) = explode('.', $excludedFilter);
            Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterboxIdentifier, array('message' => 'You have not set a filterboxIdentifier in your excludeFilter configuration for filterbox ' . $this->getFilterboxIdentifier() . ' 1315845416'));
            Tx_PtExtbase_Assertions_Assert::isNotEmptyString($filterIdentifier, array('message' => 'You have not set a filterIdentifier in your excludeFilter configuration for filterbox ' . $this->getFilterboxIdentifier() . ' 1315845417'));
            if (!is_array($this->excludeFilters[$filterboxIdentifier]) || !in_array($filterIdentifier, $this->excludeFilters[$filterboxIdentifier])) {
                $this->excludeFilters[$filterboxIdentifier][] = $filterIdentifier;
            }
        }
    }

	
	
	/**
	 * @return string filterboxIdentifier
	 */
	public function getFilterboxIdentifier() {
		return $this->filterboxIdentifier;
	}
	
	
	
	/**
	 * @return string listIdentifier
	 */
	public function getListIdentifier() {
		return $this->listIdentifier;
	}
	
	
	
	/**
	 * Show Reset button / link in filterbox
	 * @return boolean showReset
	 */
	public function getShowReset() {
		return $this->showReset;
	}
	
	
	
	/**
	 * Show Submit button / link in filterbox
	 * @return boolean showSubmit
	 */
	public function getShowSubmit() {
		return $this->showSubmit;
	}
	
	
	
	/**
	 * Returns action name used for redirect after filterbox submits
	 * 
	 * @return string
	 */
	public function getRedirectOnSubmitActionName() {
		return $this->redirectOnSubmitActionName;
	}
	
	
	
	/**
	 * Returns controller name used for redirect after filterbox submits
	 * 
	 * @return string
	 */
	public function getRedirectOnSubmitControllerName() {
		return $this->redirectOnSubmitControllerName;
	}
	
	
	
	/**
	 * Returns page id of page to which should be redirected after filterbox submits
	 * 
	 * @return int
	 */
	public function getRedirectOnSubmitPageId() {
		return $this->redirectOnSubmitPageId;
	}
	
	
	
	/**
	 * Returns true, if we do a redirect after submit
	 *
	 * @return bool
	 */
	public function doRedirectOnSubmit() {
		return ($this->redirectOnSubmitPageId > 0 || $this->redirectOnSubmitActionName !== null);
	}



    /**
     * Returns an array of 'filterboxIdentifier' => array(filterbox
     * @return array
     */
    public function getExcludeFilters() {
        return $this->excludeFilters;
    }



    /**
     * Getter for target PID to send submit request for this filterbox
     * 
     * @return Target PID to send submit request of this filterbox
     */
    public function getSubmitToPage() {
        return $this->submitToPage;
    }



	/**
	 * Setter for resetToDefaultSortingOnSubmit
	 *
	 * If set to TRUE (which is default), sorting will be reset to default values if filterbox is submitted.
	 * If set to FALSE, no sorting will be reset if filter is submitted.
	 *
	 * @param boolean $resetToDefaultSortingOnSubmit
	 */
	public function setResetToDefaultSortingOnSubmit($resetToDefaultSortingOnSubmit) {
		$this->resetToDefaultSortingOnSubmit = $resetToDefaultSortingOnSubmit;
	}



	/**
	 * Getter for resetToDefaultSortingOnSubmit
	 *
	 * @return boolean
	 */
	public function getResetToDefaultSortingOnSubmit() {
		return $this->resetToDefaultSortingOnSubmit;
	}

}
?>