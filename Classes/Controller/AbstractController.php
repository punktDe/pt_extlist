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
abstract class Tx_PtExtlist_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder = NULL;
	
	
	
	/**
	 * @var Tx_PtExtlist_Domain_Lifecycle_LifecycleManager
	 */
	protected $lifecycleManager;
	
	
	
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
	 * Custom template Path and Filename
	 * Has to be set before resolveView is called!
	 * 
	 * @var string
	 */
	protected $templatePathAndFileName;
	
	
	
	/**
	 * Constructor for all plugin controllers
	 */
	public function __construct() {
		$this->lifecycleManager = Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory::getInstance();
		$this->lifecycleManager->registerAndUpdateStateOnRegisteredObject(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance());
		
		parent::__construct();
	}
	
	
	
	/**
	 * (non-PHPdoc)
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
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @return void
	 */
	protected function setViewConfiguration(Tx_Extbase_MVC_View_ViewInterface $view) {
		parent::setViewConfiguration($view);
		$this->setCustomPathsInView($view);  
	}
	
	
    
    /**
     * Resolve the viewObjectname in the following order
     * 
     * 1. TS-defined
     * 2. Determined by Controller/Action/Format
     * 3. Extlist BaseView 
     * 
     * @throws Exception
     * @return string
     */
    protected function resolveViewObjectName() {
   	
    	$viewClassName = $this->resolveTsDefinedViewClassName();
    	if($viewClassName) {
			return $viewClassName;
		} 
		
		$viewClassName = parent::resolveViewObjectName();
  		if($viewClassName) {
			return $viewClassName;
		}
		
		else {
			return 'Tx_PtExtlist_View_BaseView';
		}
    }
    
    
    
    /**
     * Resolve the viewClassname defined via typoscript
     * 
     * @return string
     */
    protected function resolveTsDefinedViewClassName() {
    	
    	$viewClassName = $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];

    	if($viewClassName != '') {
    		if (!class_exists($viewClassName)) {
		    	
	    		// Use the viewClassName as redirect path to a typoscript value holding the viewClassName
		    	$viewClassName .= '.viewClassName';
		    	$tsRedirectPath = explode('.', $viewClassName);
		    	$viewClassName = Tx_Extbase_Utility_Arrays::getValueByPath($this->settings, $tsRedirectPath);
		    	
    		}	
    	}
    	
    	if($viewClassName && !class_exists($viewClassName)) {
    		throw new Exception('View class does not exist! ' . $viewClassName . ' 1281369758');
    	}
    	
		return $viewClassName;
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
		
        if (method_exists($view, 'injectConfigurationBuilder')) {
            $view->setConfigurationBuilder($this->configurationBuilder);
        }
  	        
        $this->view->assign('config', $this->configurationBuilder);
	}

	
	
	/**
	 * (non-PHPdoc)
	 * @see Classes/MVC/Controller/Tx_Extbase_MVC_Controller_ActionController::processRequest()
	 */
	public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
		parent::processRequest($request, $response);
		
		if(TYPO3_MODE === 'BE') {
			// if we are in BE mode, this ist the last line called
			Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory::getInstance()->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::END);
		}
	}
	
	
	
	/**
	 * Set the TS defined custom paths in view
	 * 
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @throws Exception
	 */
	protected function setCustomPathsInView(Tx_Extbase_MVC_View_ViewInterface $view) {
		
		$templatePathAndFilename = $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
		
		if(!$templatePathAndFileName) {
			$templatePathAndFilename = $this->templatePathAndFileName;
		}
		
		if (isset($templatePathAndFilename) && strlen($templatePathAndFilename) > 0) {
			if (file_exists(t3lib_div::getFileAbsFileName($templatePathAndFilename))) { 
                $view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($templatePathAndFilename));
			} else {
				throw new Exception('Given template path and filename could not be found or resolved: ' . $templatePathAndFilename . ' 1284655109');
			}
        }		
	}
}
?>