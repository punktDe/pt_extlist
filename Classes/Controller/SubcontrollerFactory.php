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
 * Factory for creating instances of pt_extlist controllers to be used as subcontrollers
 * 
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @package Controller
 */
class Tx_PtExtlist_Controller_SubcontrollerFactory extends Tx_Extbase_Dispatcher {

	/**
	 * Holds associative array of instances listIdentifier => factoryInstance
	 *
	 * @var array
	 */
	protected static $instancesArray = array();
	
	
	
	/**
	 * Holds identifier of list, this factory creates controllers for
	 *
	 * @var string
	 */
	protected $listIdentifier;
	

	
	
	/**
	 * Factory method for subcontrollerFactory
	 *
	 * @param string $listIdentifier
	 * @return Tx_PtExtlist_Controller_SubcontrollerFactory
	 */
	public static function getInstanceByListIdentifier($listIdentifier) {
	    tx_pttools_assert::isNotEmptyString($listIdentifier, array('message' => 'Cannot instantiate subcontroller factory with empty list identifier! 1283254996'));
	    
		if(self::$instancesArray[$listIdentifier] === NULL) {
	   	   	self::$instancesArray[$listIdentifier] = new Tx_PtExtlist_Controller_SubcontrollerFactory($listIdentifier);
	    }
	    
	    return self::$instancesArray[$listIdentifier];
	}
	
	
	
	/**
	 * Constructor for subcontroller factory
	 *
	 * @param string $listIdentifier Identifier of list, factory should create subcontrollers for
	 */
	public function __construct($listIdentifier) {
		$this->cObj = $GLOBALS['TSFE']->cObj;
		parent::__construct();
		$this->listIdentifier = $listIdentifier;
	}
	
	
	
	/**
	 * Creates a controller object for list controller
	 * 
	 * // TODO make this generic for all controllers (--> how to get dynamic TS config for plugin?)
	 *
	 * @param unknown_type $config
	 * @return unknown
	 */
	public function createListController($config = array()) {
		$configuration = array(
			"userFunc"=>		 "tx_extbase_dispatcher->dispatch",
			"pluginName"=>			 "pi1",
			"extensionName"=>			 "PtExtlist",
			"controller"=>			 "List",
			"action"=>			 "list",
			"switchableControllerActions."=> array (
			  "1."=>   array (
			    "controller"=>			     "List",
			    "actions"=> 			     "list,sort"
			  ),
			  "2."=>			 array (
			    "controller"=>			     "Filterbox",
			    "actions"=>			     "show,submit,reset"
			  ),
			  "3."=>			  array (
			    "controller"=>			     "Pager",
			    "actions"=>			     "show,submit"
			  ),
			  "4."=>			  array (
			    "controller"=>			     "Bookmarks",
			    "actions"=>			     "show,edit,update,delete,create"
			  ),
			), 
			"settings" => "< plugin.tx_ptextlist.settings",
			"persistence"=>			   "< plugin.tx_ptextlist.persistence",
			"view"=>			   "< plugin.tx_ptextlist.view",
			"_LOCAL_LANG"=>			  "< plugin.tx_ptextlist._LOCAL_LANG"
		);
		
        $this->initializeConfigurationManagerAndFrameworkConfiguration($configuration);
        
        // TODO fake request!
        try {
	        $requestBuilder = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_RequestBuilder'); /* @var $requestBuilder Tx_Extbase_MVC_Web_RequestBuilder */
	        $request = $requestBuilder->initialize(self::$extbaseFrameworkConfiguration);	        
	        $request = $requestBuilder->build();

	        // The controller configuration ist fixed. It should be a ListController.
	        $request->setPluginName($configuration['pluginName']);
	        $request->setControllerExtensionName($configuration['extensionName']);
	        $request->setControllerName($configuration['controller']);
//	        $request->setControllerActionName($configuration['action']);
        } catch(Exception $e) {
        	/* TODO this is done for being testable in CLI environment! */
        	$actionNames = $configuration['switchableControllerActions.']['1.']['actions'];
        	$actions = explode(',', $actionNames);
        	
        	$request = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Request'); /* @var $request Tx_Extbase_MVC_Web_Request */
            $request->setPluginName($configuration['pluginName']);
	        $request->setControllerExtensionName($configuration['extensionName']);
	        $request->setControllerName($configuration['controller']);
	        $request->setControllerActionName($actions[0]);
	        $request->setRequestURI('http://fakeuri.com');
	        $request->setBaseURI('http://fakeuri.com');
	        $request->setMethod('HTTP');
        }
        
		// Remind setting list identifier in TS! plugin.tx_ptextlist.settings.listIdentifier = <listIdentifier>
		self::$extbaseFrameworkConfiguration = t3lib_div::array_merge_recursive_overrule(
            self::$extbaseFrameworkConfiguration,
            $config
        );
        
        if (isset($this->cObj->data) && is_array($this->cObj->data)) {
            // we need to check the above conditions as cObj is not available in Backend.
            $request->setContentObjectData($this->cObj->data);
            $request->setIsCached($this->cObj->getUserObjectType() == tslib_cObj::OBJECTTYPE_USER);
        }
        
        $response = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_Response');

        // Request hash service
        $requestHashService = t3lib_div::makeInstance('Tx_Extbase_Security_Channel_RequestHashService'); // singleton
        $requestHashService->verifyRequest($request);

        $persistenceManager = self::getPersistenceManager();

        $subcontroller = $this->getPreparedController($request);
        
        $subcontrollerWrapper = new Tx_PtExtlist_Controller_SubcontrollerWrapper();
        $subcontrollerWrapper->injectSubcontroller($subcontroller);
        $subcontrollerWrapper->injectSubcontrollerFactory($this);
        $subcontrollerWrapper->injectRequest($request);
        $subcontrollerWrapper->injectResponse($response);
        
        return $subcontrollerWrapper;
	}
	
	
	
	
	/**
	 * Returns current subcontroller
	 *
	 * @param Tx_Extbase_MVC_Web_Request $request
	 * @return Tx_Extbase_MVC_Controller_ControllerInterface
	 */
	public function getPreparedSubController(Tx_Extbase_MVC_Web_Request $request) {
		return $this->getPreparedController($request);
	}
	
}