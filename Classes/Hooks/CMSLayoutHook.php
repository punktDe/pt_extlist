<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Daniel Lienert <daniel@lienert.cc>, Michael Knoll <knoll@punkt.de>
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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class implements hook for tx_cms_layout
 *
 * @package Hooks
 * @author Daniel Lienert <daniel@lienert.cc>
 */
class user_Tx_PtExtlist_Hooks_CMSLayoutHook
{
    /**
     * Plugin mode determined from switchableControllerAction
     * @var string
     */
    protected $pluginMode;
    
    
    /**
     * @var unknown_type
     */
    protected $fluidRenderer;
    
    
    
    /**
     * Render the Plugin Info
     * 
     * @param unknown_type $params
     * @param unknown_type $pObj
     */
    public function getExtensionSummary($params, &$pObj)
    {
        $data = GeneralUtility::xml2array($params['row']['pi_flexform']);
        $this->init($data);

        if (is_array($data)) {
            // PluginMode
            $firstControllerAction = array_shift(explode(';', $data['data']['sDefault']['lDEF']['switchableControllerActions']['vDEF']));
            $this->pluginMode = str_replace('->', '_', $firstControllerAction);
            
            // List
            $listIdentifier = $data['data']['sDefault']['lDEF']['settings.listIdentifier']['vDEF'];
            
            // Filter
            $filterBoxIdentifier = $data['data']['sDefault']['lDEF']['settings.filterboxIdentifier']['vDEF'];
            
            // Export
            $exportType = array_pop(explode('.', $data['data']['sDefault']['lDEF']['settings.controller.List.export.view']['vDEF']));
            $exportFileName = $data['data']['sDefault']['lDEF']['settings.prototype.export.fileName']['vDEF'];
            $exportFileName .= $data['data']['sDefault']['lDEF']['settings.prototype.export.addDateToFilename']['vDEF'] ? '[DATE]' : '';
            $exportFileName .= '.' . $data['data']['sDefault']['lDEF']['settings.prototype.export.fileExtension']['vDEF'];
            $exportDownloadType = 'tx_ptextlist_flexform_export.downloadtype.'.$data['data']['sDefault']['lDEF']['settings.prototype.export.downloadType']['vDEF'];
            $exportListIdentifier = $data['data']['sDefault']['lDEF']['settings.exportListIdentifier']['vDEF'];
        }
        
        $this->fluidRenderer->assign($this->pluginMode, true);
        $this->fluidRenderer->assign('listIdentifier', $listIdentifier);
        
        $this->fluidRenderer->assign('filterBoxIdentifier', $filterBoxIdentifier);

        $this->fluidRenderer->assign('exportType', $exportType);
        $this->fluidRenderer->assign('exportFileName', $exportFileName);
        $this->fluidRenderer->assign('exportDownloadType', $exportDownloadType);
        $this->fluidRenderer->assign('exportListIdentifier', $exportListIdentifier);
        $this->fluidRenderer->assign('caLabel', 'LLL:EXT:pt_extlist/Resources/Private/Language/locallang.xml:tx_ptextlist_flexform_controllerAction.' . $this->pluginMode);
        return $this->fluidRenderer->render();
    }
    
    
    
    /**
     * Init some values
     * 
     * @param array $data
     */
    protected function init($data)
    {
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName('EXT:pt_extlist/Resources/Private/Templates/Backend/PluginInfo.html');
                
        // Fluid
        $objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $this->fluidRenderer = $objectManager->get('TYPO3\CMS\Fluid\View\StandaloneView');
        $this->fluidRenderer->setTemplatePathAndFilename($templatePathAndFilename);
    }
}
