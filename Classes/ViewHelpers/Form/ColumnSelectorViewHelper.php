<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010-2011 punkt.de GmbH - Karlsruhe, Germany - http://www.punkt.de
 *  Authors: Daniel Lienert, Michael Knoll
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
 * GPValueViewHelper
 *
 * @author Daniel Lienert
 * @package ViewHelpers
 * @subpackage NameSpace
 */
class Tx_PtExtlist_ViewHelpers_Form_ColumnSelectorViewHelper extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {


	/**
	 * @var Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer
	 */
	protected $captionRenderer;


	/**
	 * @var Tx_PtExtlist_Domain_Configuration_ColumnSelector_ColumnSelectorConfig
	 */
	protected $columnSelectorConfig;

	

	/**
	 * Initialize the viewHelper
	 */
	public function initialize() {
		parent::initialize();
		$this->captionRenderer = t3lib_div::makeInstance('Tx_PtExtlist_Domain_Renderer_Default_CaptionRenderer');
		$this->columnSelectorConfig = Tx_PtExtlist_Domain_Configuration_ConfigurationBuilderFactory::getInstance()->buildColumnSelectorConfiguration();
	}
	
	
	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function initializeArguments() {
		$this->registerArgument('name', 'string', 'Name of input tag');
		$this->registerArgument('value', 'mixed', 'Value of input tag');
		$this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');

		$this->registerUniversalTagAttributes();

		$this->registerTagAttribute('multiple', 'string', 'if set, multiple select field');
		$this->arguments['multiple'] = '1';

		$this->registerTagAttribute('size', 'string', 'Size of input field');
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');

		$this->registerArgument('columns', 'Tx_PtExtlist_Domain_Model_List_Header_ListHeader', 'The columns', TRUE);
		$this->registerArgument('optionValueField', 'string', 'If specified, will call the appropriate getter on each object to determine the value.');
		$this->registerArgument('optionLabelField', 'string', 'If specified, will call the appropriate getter on each object to determine the label.');
		$this->registerArgument('sortByOptionLabel', 'boolean', 'If true, List will be sorted by label.', FALSE, FALSE);
		$this->registerArgument('selectAllByDefault', 'boolean', 'If specified options are selected if none was set before.', FALSE, FALSE);
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
	}



	/**
	 * @param Tx_PtExtlist_Domain_Model_List_Header_ListHeader $headers
	 */
	public function render() {
		$columns = $this->arguments['columns'];

		$options = array();
		$selectedOptions = array();

		foreach($columns as $columnIdentifier => $column) { /** @var $column Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn */

			if(!($this->columnSelectorConfig->getHideDefaultVisibleInSelector() && $column->getColumnConfig()->getIsVisible())) {
				$options[$columnIdentifier] = $this->captionRenderer->renderColumnLabel($column);
			}

			if($column->getIsVisible()) {
				$selectedOptions[] = $columnIdentifier;
			}
			
		}


		// This hack is needed to be backwards compatible to Fluid 1.3.0 where arguments was an object
		if(is_a($this->arguments, 'Tx_Fluid_Core_ViewHelper_Arguments')) {
			$arg = (array) $this->arguments;
			$arg['multiple'] = 1;
			$arg['name'] = $this->arguments['name'];
			$arg['options'] = $options;
			$arg['value'] = $selectedOptions;
			$this->arguments = new Tx_Fluid_Core_ViewHelper_Arguments($arg);

		} else {
			$this->arguments['options'] = $options;
			$this->arguments['value'] = $selectedOptions;
		}

		return parent::render();
	}

}

?>