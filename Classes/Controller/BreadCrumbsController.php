<?php
namespace PunktDe\PtExtlist\Controller;

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
 * Controller for filter breadcrumbs widget
 *
 * @package Controller
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Controller_BreadCrumbsControllerTest
 */
class BreadCrumbsController extends AbstractController
{
    /**
     * Holds an instance of filterbox collection processed by this controller
     *
     * @var \PunktDe\PtExtlist\Domain\Model\Filter\FilterboxCollection
     */
    protected $filterboxCollection;
    
    
    
    /**
     * Holds a pagerCollection.
     *  
     * @var Tx_PtExtlist_Domain_Model_Pager_PagerCollection
     */
    protected $pagerCollection = null;



    /**
     * @var Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory
     */
    protected $breadCrumbsCollectionFactory;



    /**
     * @param Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory $breadCrumbsCollectionFactory
     */
    public function injectBreadCrumbsCollectionFactory(Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumbCollectionFactory $breadCrumbsCollectionFactory)
    {
        $this->breadCrumbsCollectionFactory = $breadCrumbsCollectionFactory;
    }



    /**
     * Overwrites initAction for setting properties
     * and enabling easy testing
     */
    public function initializeAction()
    {
        parent::initializeAction();
        $this->filterboxCollection = $this->dataBackend->getFilterboxCollection();
    }



    /**
     * Renders index action for breadcrumbs controller
     *
     * @return string The rendered index action
     */
    public function indexAction()
    {
        $breadcrumbs = $this->breadCrumbsCollectionFactory->getInstanceByFilterboxCollection(
            $this->configurationBuilder,
            $this->filterboxCollection
        );
        
        // Ugly hack, to check whether there really exists a breadcrumb 
        $breadcrumbsHaveMessage = false;
        foreach ($breadcrumbs as $breadcrumb) { /* @var $breadcrumb Tx_PtExtlist_Domain_Model_BreadCrumbs_BreadCrumb */
            if ($breadcrumb->getMessage() != '') {
                $breadcrumbsHaveMessage = true;
            }
        }
        
        if ($breadcrumbsHaveMessage) {
            $this->view->assign('breadcrumbs', $breadcrumbs);
        }
    }



    /**
     * Resets given filter and forwards to index action
     *
     * @return string The rendered reset filter action
     */
    public function resetFilterAction()
    {
        $breadcrumbs = $this->breadCrumbsCollectionFactory->getInstanceByFilterboxCollection(
            $this->configurationBuilder,
            $this->filterboxCollection
        );

        $breadcrumbs->resetFilters();
        $this->resetPagers();

        $this->redirect('index');
    }
    
    
    
    /**
     * Reset all pagers for this list.
     */
    protected function resetPagers()
    {
        // Reset pagers
        if ($this->pagerCollection === null) {
            // Only get pagerCollection if it's not set already. Important for testing. 
            $this->pagerCollection = $this->dataBackend->getPagerCollection();
        }
        $this->pagerCollection->reset();
    }
}
