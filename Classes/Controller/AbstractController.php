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
	 * Since all plugins of this extensions need an unique list identifier, it is set in the controller.
	 * @var string
	 */
	protected $listIdentifier;
	
	
	
	/**
	 * Constructor for all plugin controllers
	 * @author Michael Knoll <knoll@punkt.de>
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	
	/**
	 * Injects the settings of the extension.
	 *
	 * @param array $settings Settings container of the current extension
	 * @return void
	 */
	public function injectSettings(array $settings) {
		parent::injectSettings($settings);
		
		if ($this->settings['listIdentifier'] != '') {
		    $this->listIdentifier = $this->settings['listIdentifier'];
		} else {
			throw new Exception('No list identifier set!');
		}
		
		$this->configurationBuilder = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilder::getInstance($this->settings);
		$this->dataBackend = Tx_PtExtlist_Domain_DataBackend_DataBackendFactory::createDataBackend($this->configurationBuilder);
	}
	
}

?>