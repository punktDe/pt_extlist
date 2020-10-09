<?php

namespace PunktDe\PtExtlist\Domain\Configuration\Filters;

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

use PunktDe\PtExtlist\Domain\Configuration\AbstractExtlistConfiguration;
use PunktDe\PtExtlist\Domain\Configuration\Data\Fields\FieldConfigCollection;
use PunktDe\PtExtlist\Domain\Configuration\RenderConfigInterface;
use PunktDe\PtExtlist\Utility\RenderValue;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class implementing configuration for filter
 *
 * @package Domain
 * @subpackage Configuration\Filters
 * @author Daniel Lienert
 * @author Michael Knoll
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Filters_FilterConfigTest
 */
class FilterConfig extends AbstractExtlistConfiguration
    implements RenderConfigInterface
{
    /**
     * Identifier of filterbox to which this filter belongs to
     *
     * @var string
     */
    protected $filterboxIdentifier;


    /**
     * Name of class implementing this filter
     *
     * @var string
     */
    protected $filterClassName;


    /**
     * Identifier of this filter
     *
     * @var string
     */
    protected $filterIdentifier;


    /**
     * default value for this filter
     *
     * @var string
     */
    protected $defaultValue;


    /**
     * if set to true, the filter will not be appended to the where clause of the query
     *
     * @var boolean
     */
    protected $disableFilterQuery = false;


    /**
     * Label to be shown for this filter
     *
     * @var string
     */
    protected $label;


    /**
     * If set, it is the label for an inactive option added to the filter
     * @var string
     */
    protected $inactiveOption = '';


    /**
     * If set, the filter sets itself inactive when this value is submitted
     * @var string
     */
    protected $inactiveValue = '';


    /**
     * @var string
     */
    protected $fieldIdentifier;


    /**
     * Comma seperated list of gids that have access to this filter
     *
     * @var array
     */
    protected $accessGroups = [];


    /**
     * If true, sorting state is reset if filter is submitted
     *
     * @var bool
     */
    protected $resetListSortingStateOnSubmit = false;


    /**
     * List of filters to be reset if this filter is submitted
     *
     * @var array
     */
    protected $resetFilters = [];


    /**
     * Submit filter by javascript on change
     *
     * @var boolean
     */
    protected $submitOnChange = false;


    /**
     * @var string
     */
    protected $dependsOn;


    /**
     * @var array
     */
    protected $onValidated = [];


    /**
     * If this is set to true, the query will be inverted
     *
     * @var boolean
     */
    protected $invert = false;


    /**
     * If this is set to true, the filter has the ability to be inverted
     *
     * @var bool
     */
    protected $invertable = false;


    /**
     * If set to true, this filter is active
     *
     * @var bool
     */
    protected $isActive = 1;


    /**
     * Holds all settings passed by TS
     *
     * @var array
     */
    protected $settings = [];


    /**
     * Holds path to partial for filter template
     *
     * @var string
     */
    protected $partialPath;


    /**
     * Holds a partial tha could be used to exchange parts of the filter via ajax
     *
     * @var string
     */
    protected $ajaxPartialPath;


    /**
     * cObj config array to render every filter option
     * in typoscript object array notation
     * @var array
     */
    protected $renderObj = null;


    /**
     * renderuserFunction configuration to render everey filteroption
     * @var array
     */
    protected $renderUserFunctions = null;


    /**
     * Path to fluid template
     * @var string
     */
    protected $renderTemplate;


    /**
     * This flag indicates if this filter is accessable for the current user.
     * Will be injected from the factory.
     * @var bool
     */
    protected $accessable = false;


    /**
     * Holds string to be shown as bread
     * crumb message for this filter
     *
     * @var string
     */
    protected $breadCrumbString;


    /**
     * If set to true, filter will set default value given in TS after resetting
     *
     * @var bool
     */
    protected $resetToDefaultValue = false;


    /**
     * @var boolean If the hidden flag is true, the filter is not shown in the filterBox
     */
    protected $hidden = false;


    /**
     * If set to true, row count of filter values will be shown
     *
     * @var boolean
     */
    protected $showRowCount = false;


    /**
     * If set to true, we want to cache rendering
     *
     * @var bool
     */
    protected $cacheRendering;


    /**
     * A optional filter description
     *
     * @var string
     */
    protected $description;


    /**
     * Build the filterconfig object
     *
     * @param \PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder
     * @param array $settings
     * @param string $filterBoxIdentifier
     */
    public function __construct(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder, $settings, $filterBoxIdentifier)
    {
        $settings['filterboxIdentifier'] = $filterBoxIdentifier;
        parent::__construct($configurationBuilder, $settings);
    }


    /**
     * (non-PHPdoc)
     * @see \PunktDe\PtExtbase\Configuration\AbstractConfiguration::init()
     */
    public function init()
    {

        // required
        $this->setRequiredValue('filterboxIdentifier', 'Filterbox identifier must not be empty. 1277889652');
        $this->setRequiredValue('filterIdentifier', 'No filterIdentifier specified in config. 1277889452');
        $this->setRequiredValue('filterClassName', 'No filterClassName specified for filter ' . $this->filterIdentifier . '. 1277889552');
        $this->setRequiredValue('fieldIdentifier', 'No fieldIdentifier set in TS config for filter ' . $this->fieldIdentifier . ' 1280762513');
        $this->setRequiredValue('partialPath', 'No partial path is configured for ' . $this->filterIdentifier . ' (TS key partialPath). 1281013746');

        $this->processAndSetFieldIdentifier($this->settings['fieldIdentifier']);

        // optional
        $this->setBooleanIfExistsAndNotNothing('disableFilterQuery');
        $this->setBooleanIfExistsAndNotNothing('invert');
        $this->setBooleanIfExistsAndNotNothing('invertable');
        $this->setBooleanIfExistsAndNotNothing('submitOnChange');
        $this->setBooleanIfExistsAndNotNothing('resetToDefaultValue');
        $this->setBooleanIfExistsAndNotNothing('hidden');
        $this->setBooleanIfExistsAndNotNothing('showRowCount');
        $this->setBooleanIfExistsAndNotNothing('cacheRendering');

        $this->setValueIfExists('inactiveValue');
        $this->setValueIfExists('breadCrumbString');
        $this->setValueIfExists('label');
        $this->setValueIfExists('description');
        $this->setValueIfExists('ajaxPartialPath');

        $this->setValueIfExistsAndNotNothing('defaultValue');
        if ($this->defaultValue) {
            $this->renderDefaultValue();
        }

        $this->setValueIfExistsAndNotNothing('renderUserFunctions');
        $this->setValueIfExistsAndNotNothing('renderTemplate');

        if ($this->configValueExistsAndNotNothing('inactiveOption')) {
            $this->inactiveOption = RenderValue::stdWrapIfPlainArray($this->settings['inactiveOption']);
            if (GeneralUtility::isFirstPartOfStr($this->inactiveOption, 'LLL:')) {
                $this->inactiveOption = LocalizationUtility::translate($this->inactiveOption, '');
            }
        }

        if ($this->configValueExistsAndNotNothing('label')) {
            $this->label = \PunktDe\PtExtlist\Utility\RenderValue::stdWrapIfPlainArray($this->settings['label']);
            if (GeneralUtility::isFirstPartOfStr($this->label, 'LLL:')) {
                $this->label = LocalizationUtility::translate($this->label, '');
            }
        }

        if (array_key_exists('renderObj', $this->settings)) {
            $this->renderObj = GeneralUtility::makeInstance(TypoScriptService::class)->convertPlainArrayToTypoScriptArray(['renderObj' => $this->settings['renderObj']]);
        }

        if (array_key_exists('accessGroups', $this->settings)) {
            $this->accessGroups = GeneralUtility::trimExplode(',', $this->settings['accessGroups']);
        }
    }


    /**
     * Convert a single field identifier or comma separated list of fieldIdentifier in fieldIdentifier collection
     *
     * @param $fieldIdentifier
     * @return void
     */
    protected function processAndSetFieldIdentifier($fieldIdentifier)
    {
        $fieldIdentifierList = [];

        if (is_array($fieldIdentifier)) {
            foreach ($fieldIdentifier as $firstLevel) {
                if (is_array($firstLevel)) {
                    $fieldIdentifierList = array_merge($fieldIdentifierList, array_values($firstLevel));
                } else {
                    $fieldIdentifierList[] = $firstLevel;
                }
                $fieldIdentifierList = array_unique($fieldIdentifierList);
            }
        } else {
            $fieldIdentifierList = GeneralUtility::trimExplode(',', $fieldIdentifier);
        }

        $this->fieldIdentifier = $this->configurationBuilder->buildFieldsConfiguration()->extractCollectionByIdentifierList($fieldIdentifierList);
    }


    /**
     * Render defaultValue with stdWrap
     */
    protected function renderDefaultValue()
    {

        // no array - nothing to do
        if (!is_array($this->defaultValue)) {
            return;
        }

        // array but no cOBJ - defines multivalue default
        if (!$this->defaultValue['cObject']) {
            foreach ($this->defaultValue as $key => $defaultValue) {
                if (is_array($defaultValue)) {
                    $this->defaultValue[$key] = RenderValue::renderCObjectWithPlainArray($defaultValue);
                }
            }

            if (array_key_exists('_typoScriptNodeValue', $this->defaultValue)) {
                unset($this->defaultValue['_typoScriptNodeValue']);
            }

            return;
        }


        // array and cObject - render
        $this->defaultValue = RenderValue::renderCObjectWithPlainArray($this->defaultValue);
    }


    /**
     * Returns partial path for filter template
     *
     * @return string
     */
    public function getPartialPath()
    {
        return $this->partialPath;
    }


    /**
     * Returns identifier of filterbox to which this filter belongs to
     *
     * @return string Identifier of filterbox to which this filter belongs to
     */
    public function getFilterboxIdentifier()
    {
        return $this->filterboxIdentifier;
    }


    /**
     * Returns class name of class implementing this filter
     *
     * @return string Class name of class implementing this filter
     */
    public function getFilterClassName()
    {
        return $this->filterClassName;
    }


    /**
     * @return array of group IDs
     */
    public function getAccessGroups()
    {
        return $this->accessGroups;
    }


    /**
     * @return unknown
     */
    public function getDependsOn()
    {
        return $this->dependsOn;
    }

    /**
     * @return boolean
     */
    public function getDisableFilterQuery()
    {
        return $this->disableFilterQuery;
    }

    /**
     * @return FieldConfigCollection
     */
    public function getFieldIdentifier()
    {
        return $this->fieldIdentifier;
    }


    /**
     * @return unknown
     */
    public function getFilterIdentifier()
    {
        return $this->filterIdentifier;
    }


    /**
     * Get the default value
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }


    /**
     * @return boolean
     */
    public function getInvert()
    {
        return $this->invert;
    }


    /**
     * @return boolean
     */
    public function getInvertable()
    {
        return $this->invertable;
    }


    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }


    /**
     * @return unknown
     */
    public function getOnValidated()
    {
        return $this->onValidated;
    }


    /**
     * @return boolean
     */
    public function getResetFilters()
    {
        return $this->resetFilters;
    }


    /**
     * cObj configuration
     * @return array
     */
    public function getRenderObj()
    {
        return $this->renderObj;
    }


    /**
     * @return string renderTemplate
     */
    public function getRenderTemplate()
    {
        return $this->renderTemplate;
    }


    /**
     * @return boolean
     */
    public function getResetListSortingStateOnSubmit()
    {
        return $this->resetListSortingStateOnSubmit;
    }


    /**
     * @return string
     */
    public function getInactiveOption()
    {
        return $this->inactiveOption;
    }


    /**
     * @return string
     */
    public function getInactiveValue()
    {
        return $this->inactiveValue;
    }


    /**
     * @return boolean
     */
    public function getSubmitOnChange()
    {
        return $this->submitOnChange;
    }


    /**
     * @return array
     */
    public function getRenderUserFunctions()
    {
        return $this->renderUserFunctions;
    }


    /**
     * Returns bread crumb string to be shown as
     * message for this filter
     *
     * @return string
     */
    public function getBreadCrumbString()
    {
        return $this->breadCrumbString;
    }


    /**
     * Returns true, if filter should reset to default TS value after resetting
     *
     * @return bool
     */
    public function getResetToDefaultValue()
    {
        return $this->resetToDefaultValue;
    }


    /**
     * @param bool $accessable
     */
    public function setAccessable($accessable)
    {
        $this->accessable = (bool)$accessable;
    }


    /**
     * Returns the accessable flag.
     *
     * @return bool
     */
    public function isAccessable()
    {
        return $this->accessable;
    }


    /**
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }


    /**
     * @return bool
     */
    public function getVisible()
    {
        return !$this->hidden;
    }


    /**
     * Returns true, if row count should be shown for filter options
     *
     * @return boolean
     */
    public function getShowRowCount()
    {
        return $this->showRowCount;
    }


    /**
     * @return string
     */
    public function getAjaxPartialPath()
    {
        return $this->ajaxPartialPath;
    }


    /**
     * Returns true, if rendering should be cached
     *
     * @return bool True, if rendering should be cached
     */
    public function getCacheRendering()
    {
        return $this->cacheRendering;
    }


    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
