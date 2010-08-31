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
 * Factory for creating instances of pt_extlist controllers to be used as subcontrollers
 * 
 * @author Michael Knoll <knoll@punkt.de>
 * @author Daniel Lienert <lienert@punkt.de>
 * @package TYPO3
 * @subpackage pt_extlist
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
	
	
	
	public function createListController($config = array()) {
		// TODO merge $config over generic config
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
		
		// Remind setting list identifier in TS! plugin.tx_ptextlist.listIdentifier = <listIdentifier>
		
        $this->initializeConfigurationManagerAndFrameworkConfiguration($configuration);
        
        // TODO fake request!
        $requestBuilder = t3lib_div::makeInstance('Tx_Extbase_MVC_Web_RequestBuilder');
        $request = $requestBuilder->initialize(self::$extbaseFrameworkConfiguration);
        $request = $requestBuilder->build();
        
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

        $controller = $this->getPreparedController($request);
        #try {
            $controller->processRequest($request, $response);
        #} catch (Tx_Extbase_MVC_Exception_StopAction $ignoredException) {
        #}
        
        if (count($response->getAdditionalHeaderData()) > 0) {
            $GLOBALS['TSFE']->additionalHeaderData[$request->getControllerExtensionName()] = implode("\n", $response->getAdditionalHeaderData());
        }
        
        print_r($response->getContent());
        
        return $controller;
	}
	
}