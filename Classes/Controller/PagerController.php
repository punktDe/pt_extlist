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


use PunktDe\PtExtlist\Domain\Model\Pager\PagerCollection;

/**
 * Class Pager Controller
 *
 * @author Christoph Ehscheidt 
 * @package Controller
 */
class PagerController extends AbstractController
{
    /**
     * Holds the pager collection.
     *  
     * @var PagerCollection
     */
    protected $pagerCollection;
    
    
    
    /**
     * The pager identifier of the pager configured for this view.
     *  
     * @var string
     */
    protected $pagerIdentifier;
    
    
    
    /**
     * (non-PHPdoc)
     * @see Classes/Controller/\PunktDe\PtExtlist\Controller\AbstractController::initializeAction()
     * @throws \Exception
     */
    public function initializeAction()
    {
        parent::initializeAction();
        $this->pagerIdentifier = (empty($this->settings['pagerIdentifier']) ? 'default' : $this->settings['pagerIdentifier']);
        $this->templatePathAndFileName = $this->configurationBuilder->buildPagerConfiguration()->getPagerConfig($this->pagerIdentifier)->getTemplatePath();
        $this->pagerCollection = $this->getPagerCollectionInstance();
    }
        
    
    
    /**
     * Shows a pager as a frontend plugin
     *
     * @return string Rendered pager action HTML source
     */
    public function showAction()
    {
        // Do not show pager when nothing to page.
        if ($this->pagerCollection->getItemCount() <= 0) {
            return '';
        }

        $pager = $this->pagerCollection->getPagerByIdentifier($this->pagerIdentifier);
        
        $this->view->assign('pagerCollection', $this->pagerCollection);
        $this->view->assign('pager', $pager);
    }

    
    
    /**
     * Returns an initialized pager object
     *  
     * @returm PagerInterface
     */
    protected function getPagerCollectionInstance()
    {
        $pagerCollection = $this->dataBackend->getPagerCollection();
        $pagerCollection->setItemCount($this->dataBackend->getTotalItemsCount());
        return $pagerCollection;
    }
}
