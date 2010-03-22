<?php

abstract class Tx_PtExtlist_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder
	 */
	protected $configurationBuilder;
	
	/**
	 * 
	 * @var Tx_PtExtlist_Domain_DataBackend_DataBackendInterface
	 */
	protected $dataBackend;
	
	/**
	 * A string which identifies a group of list elements eg. List, Filter, Pager etc.
	 * @var string
	 */
	protected $listIdentifier;
	
	public function __construct() {
		parent::__construct();
		
		$this->listIdentifier = 'test';
		
		
	}
	
	/**
	 * Injects the settings of the extension.
	 *
	 * @param array $settings Settings container of the current extension
	 * @return void
	 */
	public function injectSettings(array $settings) {
		parent::injectSettings($settings);
		
		
		$this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		
		$this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend(
								$this->configurationBuilder->buildDataConfiguration($this->listIdentifier));
		
	}
	
	public function indexAction() {
		
	}
	
}

?>