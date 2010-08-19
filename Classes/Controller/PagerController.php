<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <lienert@punkt.de>, Michael Knoll <knoll@punkt.de>, Christoph Ehscheidt <ehscheidt@punkt.de>
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
 * Class Pager Controller
 *
 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
 */
class Tx_PtExtlist_Controller_PagerController extends Tx_PtExtlist_Controller_AbstractController {

	/**
	 * Holds the pager collection.
	 * 
	 * @var Tx_PtExtlist_Domain_Model_Pager_PagerCollection
	 */
	protected $pagerCollection;
	
	/**
	 * The pager identifier of the pager configured for this view.
	 * 
	 * @var string
	 */
	protected $pagerIdentifier;
	
	/**
	 * Injects the settings of the extension.
	 *
	 * @param array $settings Settings container of the current extension
	 * @return void
	 */
	public function injectSettings(array $settings) {
		parent::injectSettings($settings);
		$this->pagerCollection = $this->getPagerCollectionInstance();
		$this->pagerIdentifier = (empty($this->settings['pagerIdentifier']) ? 'default' : $this->settings['pagerIdentifier']);
	}
	
	/**
	 * Shows a pager as a frontend plugin
	 *
	 * @return string Rendered pager action HTML source
	 */
	public function showAction() {
		// Do not show pager when nothing to page.
		if($this->pagerCollection->getItemCount() <= 0) return '';
		echo $this->pagerCollection->count();
		tx_pttools_assert::isTrue($this->pagerCollection->hasItem($this->pagerIdentifier), array(message => 'No pager configuration with id '.$this->pagerIdentifier.' found. 1282216891'));
		
		$this->view->assign('pager', $this->pagerCollection->getItemById($this->pagerIdentifier));
		
	}
	
	
	
	/**
	 * Updates the pager model.
	 * 
	 * @author Christoph Ehscheidt <ehscheidt@punkt.de>
	 * @param int $page
	 * @param string list
	 * @return string Rendered pager action HTML source
	 */
	public function submitAction($page, $list) {
		
		// Only update pager if the listIdentifier equals this list.
		if ($this->listIdentifier != $list) {
			$this->forward('show');
			return;
		}
		
		
		$this->pagerCollection->setCurrentPage($page);
		
		$this->forward('show');
	}
	
	
	
	/**
	 * Returns an initialized pager object
	 * 
	 * @return Tx_PtExtlist_Domain_Model_Pager_PagerInterface
	 */
	protected function getPagerCollectionInstance() {
		$pagerCollection = Tx_PtExtlist_Domain_Model_Pager_PagerCollectionFactory::getInstance($this->configurationBuilder);
		$pagerCollection->setItemCount($this->dataBackend->getTotalItemsCount());
		
        return $pagerCollection;
	}
}

?>