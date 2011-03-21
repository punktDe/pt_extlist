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
 * Abstract controller for all pt_extlist controllers
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Controller
 */
abstract class Tx_PtExtlist_Controller_AbstractController extends Tx_PtExtbase_Controller_AbstractActionController  {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder = NULL;
	
	
	
	/**
	 * 
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend;
	
	
	
	/**
	 * A string which identifies a group of list elements eg. List, Filter, Pager etc.
	 * Since all plugins of this extensions need an unique list identifier, it is set in the controller.
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Constructor for all plugin controllers
	 */
	public function __construct() {
		parent::__construct();
		$this->lifecycleManager->registerAndUpdateStateOnRegisteredObject(Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance());
	}
	
	
	
	/**
	 * Creates configuration builder after getting extension configuration injected
	 * @see Classes/MVC/Controller/Tx_Extbase_MVC_Controller_AbstractController::injectConfigurationManager()
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) { // , $initConfigurationBuilder = TRUE, $initDataBackend = TRUE
		parent::injectConfigurationManager($configurationManager);	
		
		if ($this->settings['listIdentifier'] != '') {
		    $this->listIdentifier = $this->settings['listIdentifier'];
		} else {
			throw new Exception('No list identifier set! List controller cannot be initialized without a list identifier. Most likely you have not set a list identifier in flexform');
		}
		
		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($this->settings);
		$this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->listIdentifier);
		
		$this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder);
	}

    
    
	/**
	 * Initializes the view before invoking an action method.
	 *
	 * Override this method to solve assign variables common for all actions
	 * or prepare the view in another way before the action is called.
	 *
	 * @param Tx_Extbase_View_ViewInterface $view The view to be initialized
	 * @return void
	 * @api
	 */
	protected function initializeView(Tx_Extbase_MVC_View_ViewInterface $view) {
        $this->objectManager->get('Tx_PtExtlist_Extbase_ExtbaseContext')->setControllerContext($this->controllerContext);
        if (method_exists($view, 'setConfigurationBuilder')) {
            $view->setConfigurationBuilder($this->configurationBuilder);
        }
  	        
        $this->view->assign('config', $this->configurationBuilder);
	}
    
    
    
    /**
     * Template method for getting template path and filename from
     * TypoScript settings.
     * 
     * Overwrite this method in extending controllers to add further namespace conventions etc.
     *
     * @return string Template path and filename
     */
    protected function getTsTemplatePathAndFilename() {
        return $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
    }

}
?>