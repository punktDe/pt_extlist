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
 * Class implements a filter breadcrumb
 *
 * @package Domain
 * @subpackage Model\BreadCrumbs
 * @author Michael Knoll <knoll@punkt.de>
 */
class Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb {
	
	/**
	 * Associated filter object
	 *
	 * @var Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	protected $filter;
	
	
	
	/**
	 * Message to be shown as breadcrumb
	 *
	 * @var string
	 */
	protected $message;
	
	
	
	/**
	 * True, if filter can be resetted via breadcrumb
	 *
	 * @var bool
	 */
	protected $isResettable = true;
	
	
	
	/**
	 * Constructor for breadcrumb. Takes filter object to show breadcrumb for as parameter
	 *
	 * @param Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter
	 */
	public function __construct(Tx_PtExtlist_Domain_Model_Filter_FilterInterface $filter) {
		$this->filter = $filter;
	}
	
	
	
	/**
	 * Getter for filter object
	 *
	 * @return Tx_PtExtlist_Domain_Model_Filter_FilterInterface
	 */
	public function getFilter() {
		return $this->filter;
	}
	
	
	
	/**
	 * Setter for breadcrumb message
	 *
	 * @param string $message
	 */
	public function setMessage($message) {
		$this->message = $message;
	}
	
	
	
	/**
	 * Getter for breadcrumb message
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
	
	
	/**
	 * Setter for is resettable (true, if filter can be resetted via breadcrumb)
	 *
	 * @param bool $isResettable
	 */
	public function setIsResettable($isResettable) {
		$this->isResettable = $isResettable;
	}
	
	
	
	/**
	 * Getter for is resettable (true, if filter can be resetted via breadcrumb)
	 *
	 * @return bool
	 */
	public function getIsResettable() {
		return $this->isResettable;
	}
	
}
 
?>