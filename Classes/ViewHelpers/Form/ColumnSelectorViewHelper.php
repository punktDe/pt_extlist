<?php
namespace PunktDe\PtExtlist\ViewHelpers\Form;
use PunktDe\PtExtlist\Domain\Configuration\ColumnSelector\ColumnSelectorConfig;
use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilderFactory;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\HeaderColumn;
use PunktDe\PtExtlist\Domain\Model\Lists\Header\ListHeader;
use PunktDe\PtExtlist\Domain\Renderer\Defaults\CaptionRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper;

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
class ColumnSelectorViewHelper extends SelectViewHelper
{
    /**
     * @var CaptionRenderer
     */
    protected $captionRenderer;


    /**
     * @var ColumnSelectorConfig
     */
    protected $columnSelectorConfig;

    

    /**
     * Initialize the viewHelper
     */
    public function initialize()
    {
        parent::initialize();
        $this->captionRenderer = GeneralUtility::makeInstance(CaptionRenderer::class);

        // TODO Remove this, once we have DI
        $configurationBuilderFactory = GeneralUtility::makeInstance(ObjectManager::class)->get(ConfigurationBuilderFactory::class); /* @var $configurationBuilderFactory \ConfigurationBuilderFactory */
        $configurationBuilder = $configurationBuilderFactory->getInstance();

        $this->columnSelectorConfig = $configurationBuilder->buildColumnSelectorConfiguration();
    }
    
    
    /**
     * Initialize arguments.
     *
     * @return void
     * @author Sebastian Kurfürst <sebastian@typo3.org>
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('name', 'string', 'Name of input tag');
        $this->registerArgument('value', 'mixed', 'Value of input tag');
        $this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');

        $this->registerUniversalTagAttributes();

        $this->registerTagAttribute('multiple', 'string', 'if set, multiple select field');
        $this->arguments['multiple'] = '1';

        $this->registerTagAttribute('size', 'string', 'Size of input field');
        $this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');

        $this->registerArgument('columns', 'ListHeader', 'The columns', true);
        $this->registerArgument('optionValueField', 'string', 'If specified, will call the appropriate getter on each object to determine the value.');
        $this->registerArgument('optionLabelField', 'string', 'If specified, will call the appropriate getter on each object to determine the label.');
        $this->registerArgument('sortByOptionLabel', 'boolean', 'If true, List will be sorted by label.', false, false);
        $this->registerArgument('selectAllByDefault', 'boolean', 'If specified options are selected if none was set before.', false, false);
        $this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', false, 'f3-form-error');
    }



    /**
     * @param ListHeader $headers
     */
    public function render()
    {
        $columns = $this->arguments['columns'];

        $options = [];
        $selectedOptions = [];

        foreach ($columns as $columnIdentifier => $column) { /** @var $column HeaderColumn */

            if (!($this->columnSelectorConfig->getHideDefaultVisibleInSelector() && $column->getColumnConfig()->getIsVisible())) {
                $options[$columnIdentifier] = $this->captionRenderer->renderColumnLabel($column);
            }

            if ($column->getIsVisible()) {
                $selectedOptions[] = $columnIdentifier;
            }
        }

        $this->arguments['options'] = $options;
        $this->arguments['value'] = $selectedOptions;

        return parent::render();
    }
}
