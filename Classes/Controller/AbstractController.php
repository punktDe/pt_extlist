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
	 * This flag is set to true, the configurationBuilder is reset
	 *
	 * @var bool
	 */
	protected $resetConfigurationBuilder = false;



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
		$this->lifecycleManager = Tx_PtExtbase_Lifecycle_ManagerFactory::getInstance();
		parent::__construct();
		$this->lifecycleManager->registerAndUpdateStateOnRegisteredObject(Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance());
	}
	
	
	/**
	 * Creates configuration builder after getting extension configuration injected
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManager $configurationManager
	 * @throws Exception
	 */
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManager $configurationManager) { // , $initConfigurationBuilder = TRUE, $initDataBackend = TRUE
		parent::injectConfigurationManager($configurationManager);

		$this->configurationBuilder = $this->buildConfigurationBuilder();
		$sessionPersistenceManager = $this->buildSessionPersistenceManager();
		$this->lifecycleManager->registerAndUpdateStateOnRegisteredObject($sessionPersistenceManager);

		// We reset session data, if we want to have a reset on empty submit
		if ($this->configurationBuilder->buildBaseConfiguration()->getResetOnEmptySubmit()) {
			$sessionPersistenceManager->resetSessionDataOnEmptyGpVars(Tx_PtExtlist_Domain_StateAdapter_GetPostVarAdapterFactory::getInstance());
		}

		$this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder);
	}



	/**
	 * @return Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 * @throws Exception
	 */
	protected function buildConfigurationBuilder() {

		if ($this->settings['listIdentifier'] != '') {
			$this->listIdentifier = $this->settings['listIdentifier'];
		} else {
			throw new Exception('No list identifier set! List controller cannot be initialized without a list identifier. Most likely you have not set a list identifier in flexform');
		}

		Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::injectSettings($this->settings);
		return  Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->listIdentifier, $this->resetConfigurationBuilder);
	}



	/**
	 * @return Tx_PtExtbase_State_Session_SessionPersistenceManager
	 */
	protected function buildSessionPersistenceManager() {
		// Determine class name of session storage class to use for session persistence
		if (TYPO3_MODE === 'FE') {
			$sessionStorageClassName = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get('Tx_PtExtlist_Extbase_ExtbaseContext')->isInCachedMode()
					  ? $this->configurationBuilder->buildBaseConfiguration()->getCachedSessionStorageAdapter()
					  : $this->configurationBuilder->buildBaseConfiguration()->getUncachedSessionStorageAdapter();
		} else {
			$sessionStorageClassName = Tx_PtExtbase_State_Session_SessionPersistenceManager::STORAGE_ADAPTER_BROWSER_SESSION;
		}

		// Instantiate session storage for determined class name
		$sessionStorageClass = call_user_func($sessionStorageClassName . '::getInstance');
		$sessionPersistenceManager = Tx_PtExtbase_State_Session_SessionPersistenceManagerFactory::getInstance($sessionStorageClass);

		return $sessionPersistenceManager;
	}


		
	/**
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @return void
	 */
	protected function setViewConfiguration(Tx_Extbase_MVC_View_ViewInterface $view) {
		parent::setViewConfiguration($view);
		$this->setCustomPathsInView($view);  
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
       if($this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template']) {
			 return $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
		 }

		 return $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
    }


	/**
	 * Template method for getting class name for view to be used in this controller from
	 * TypoScript.
	 *
	 * Overwrite this method in your extending controller to enable adding
	 * further namespace settings etc.
	 *
	 * @return string View class name to be used in this controller
	 */
	protected function getTsViewClassName() {
		if($this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view']) {
			return $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];
		}

		if($this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view']) {
			return $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];
		}
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/MVC/Controller/Tx_Extbase_MVC_Controller_ActionController::processRequest()
	 */
	public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
		parent::processRequest($request, $response);
		
		if(TYPO3_MODE === 'BE') {
			// if we are in BE mode, this ist the last line called
			Tx_PtExtbase_Lifecycle_ManagerFactory::getInstance()->updateState(Tx_PtExtbase_Lifecycle_Manager::END);
		}
	}
	
	
	
	/**
	 * Set the TS defined custom paths in view
	 * 
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @throws Exception
	 */
	protected function setCustomPathsInView(Tx_Extbase_MVC_View_ViewInterface $view) {
        // TODO we do not get global settings from pt_extlist merged into list_identifier settings here. fix this.

        // Get template for current action from settings for list identifier
		$templatePathAndFilename = $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];

        // Get template for current action from global settings (e.g. flexform)
        if (!$templatePathAndFilename) {
            $templatePathAndFilename = $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
        }

        // If no template is given before, take default one
		if(!$templatePathAndFilename) {
			$templatePathAndFilename = $this->templatePathAndFileName;
		}
		
		if (isset($templatePathAndFilename) && strlen($templatePathAndFilename) > 0) {
			
			if (file_exists(t3lib_div::getFileAbsFileName($templatePathAndFilename))) { 
                $view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($templatePathAndFilename));
			} else {
				throw new Exception('Given template path and filename could not be found or resolved: ' . t3lib_div::getFileAbsFileName($templatePathAndFilename), 1284655110);
			}
        }		
	}
	
	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/MVC/Controller/Tx_Extbase_MVC_Controller_AbstractController::redirect()
	 */
    protected function redirect($actionName, $controllerName = NULL, $extensionName = NULL, array $arguments = NULL, $pageUid = NULL, $delay = 0, $statusCode = 303) {
    	$this->lifecycleManager->updateState(Tx_PtExtbase_Lifecycle_Manager::END);
        parent::redirect($actionName, $controllerName, $extensionName, $arguments, $pageUid, $delay, $statusCode);
    }
    
}

?>