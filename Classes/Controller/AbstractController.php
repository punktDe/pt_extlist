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
 * Abstract controller for all pt_extlist controllers
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @package Controller
 */
abstract class Tx_PtExtlist_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
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
	 * Constructor for all plugin controllers
	 * @author Michael Knoll <knoll@punkt.de>
	 */
	public function __construct() {
		$this->lifecycleManager = Tx_PtExtlist_Domain_Lifecycle_LifecycleManagerFactory::getInstance();
		$this->lifecycleManager->register(Tx_PtExtlist_Domain_StateAdapter_SessionPersistenceManagerFactory::getInstance());
		// SET LIFECYCLE TO START -> read session data into cache
		$this->lifecycleManager->updateState(Tx_PtExtlist_Domain_Lifecycle_LifecycleManager::START);
		
		parent::__construct();
	}
	
	
	
	/**
	 * Injects the settings of the extension.
	 *
	 * @param array $settings Settings container of the current extension
	 * @param bool $initConfigurationBuilder If set to true, a configuration builder is set in controller
	 * @param bool $initDataBackend If set to true, a data backend is set in controller
	 * @return void
	 */
	public function injectSettings(array $settings, $initConfigurationBuilder = TRUE, $initDataBackend = TRUE) {
		parent::injectSettings($settings);
		
		if ($this->settings['listIdentifier'] != '') {
		    $this->listIdentifier = $this->settings['listIdentifier'];
		} else {
			throw new Exception('No list identifier set! List controller cannot be initialized without a list identifier. Most likely you have not set a list identifier in flexform');
		}
		
		$this->configurationBuilder = $initConfigurationBuilder ? Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance($this->settings) : null;
		$this->dataBackend = $initDataBackend ? Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder) : null;
	}
	
	
	
    /**
     * Prepares a view for the current action and stores it in $this->view.
     * By default, this method tries to locate a view with a name matching
     * the current action.
     *
     * Configuration for view in TS:
     * 
     * controller.<ControllerName>.<controllerActionName>.view = <viewClassName>
     * 
     * @return void
     */
    protected function resolveView() {
    	$view = $this->resolveViewObject();
        
        $controllerContext = $this->buildControllerContext();
        $view->setControllerContext($controllerContext);

		// Setting the controllerContext for the FLUID template renderer         
        Tx_PtExtlist_Utility_RenderValue::setControllerContext($controllerContext);
        
        // Template Path Override
        $extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
        if (isset($extbaseFrameworkConfiguration['view']['templateRootPath']) && strlen($extbaseFrameworkConfiguration['view']['templateRootPath']) > 0) {
            $view->setTemplateRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']));
        }
        if (isset($extbaseFrameworkConfiguration['view']['layoutRootPath']) && strlen($extbaseFrameworkConfiguration['view']['layoutRootPath']) > 0) {
            $view->setLayoutRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['layoutRootPath']));
        }
        if (isset($extbaseFrameworkConfiguration['view']['partialRootPath']) && strlen($extbaseFrameworkConfiguration['view']['partialRootPath']) > 0) {
            $view->setPartialRootPath(t3lib_div::getFileAbsFileName($extbaseFrameworkConfiguration['view']['partialRootPath']));
        }

        if ($view->hasTemplate() === FALSE) {
            $viewObjectName = $this->resolveViewObjectName();
            if (class_exists($viewObjectName) === FALSE) $viewObjectName = 'Tx_Extbase_MVC_View_EmptyView';
            $view = $this->objectManager->getObject($viewObjectName);
            $view->setControllerContext($controllerContext);
        }
        if (method_exists($view, 'injectConfigurationBuilder')) {
            $view->injectConfigurationBuilder($this->configurationBuilder);
        }
        $view->initializeView(); // In FLOW3, solved through Object Lifecycle methods, we need to call it explicitely
        $view->assign('settings', $this->settings); // same with settings injection.
        
        return $view;
    }
    
    
    
    /**
     * These lines have been added by Michael Knoll to make view configurable via TS
     * Added TS-Key redirect by Daniel Lienert. If the tsPath points to a TS Configuration with child key viewClassName, it uses this as view class
     * 
     * @throws Exception
     */
    protected function resolveViewObject() {
   
        $viewClassName = $this->settings['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['view'];

        if ($viewClassName != '') {

        	if (class_exists($viewClassName)) {
        		return $this->objectManager->getObject($viewClassName);
        	} 

        	$viewClassName .= '.viewClassName';
        	$tsRedirectPath = explode('.', $viewClassName);
        	$viewClassName = Tx_Extbase_Utility_Arrays::getValueByPath($this->settings, $tsRedirectPath);
        	
        	if (class_exists($viewClassName)) {
        		return $this->objectManager->getObject($viewClassName);
        	}
        	
        	throw new Exception('View class does not exist! ' . $viewClassName . ' 1281369758');
        } else {
        	
        	// We replace Tx_Fluid_View_TemplateView by Tx_PtExtlist_View_BaseView here to use our own view base class
        	return $this->objectManager->getObject('Tx_PtExtlist_View_BaseView');	
        }
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
        $templatePath = $this->settings['listConfig'][$this->listIdentifier]['controller'][$this->request->getControllerName()][$this->request->getControllerActionName()]['template'];
		if (isset($templatePath) && strlen($templatePath) > 0) {
            $view->setTemplatePathAndFilename($templatePath);
        }
		
	}
    
    /**
     * Sets template to be used by current action.
     * 
     * BaseView reads this parameter for resolving template path and filename
     * 
     * Can be given absolute, in Extbase way or as templatePath = EXT:pt_extlist/Resources/Private/Templates/Filter/StringFilter.html
     *
     * @param string $templatePathAndFilename
     */
    public function setTemplatePathAndFilename($templatePathAndFilename) {
    	$this->view->setTemplatePathAndFilename($templatePathAndFilename);
    }
	
}

?>