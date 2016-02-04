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
 * ViewHelpers renders link for given sorting fields.
 *
 * Sorting via list headers can either happen via sortingDirection or via list of fields and directions.
 * This viewhelper renders fields and directions link.
 *
 * @package ViewHelpers
 * @subpackage Link
 * @author Michael Knoll
 */
class  Tx_PtExtlist_ViewHelpers_Link_SortingFieldsViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Link\ActionViewHelper
{
    /**
     * Holds instance of session persistence manager builder
     *
     * @var Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder
     */
    protected $sessionPersistenceManagerBuilder;



    /**
     * Injects session persistence manager factory (used by DI)
     *
     * @param Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder
     */
    public function injectSessionPersistenceManagerBuilder(Tx_PtExtbase_State_Session_SessionPersistenceManagerBuilder $sessionPersistenceManagerBuilder)
    {
        $this->sessionPersistenceManagerBuilder = $sessionPersistenceManagerBuilder;
    }



    /**
     * Renders a link for given header and sortingFields
     *
     * @param Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header
     * @param array $fieldAndDirection List of fields and direction for which we want to generate sorting link {field: fieldName, direction: sortingDirection}
     * @param string $action Rendered link for sorting action
     * @param int $pageUid target page. See TypoLink destination
     * @param int $pageType type of the target page. See typolink.parameter
     * @param bool $noCache set this to disable caching for the target page. You should not need this.
     * @param bool $noCacheHash set this to supress the cHash query parameter created by TypoLink. You should not need this.
     * @param string $section the anchor to be added to the URI
     * @param string $format The requested format, e.g. ".html
     * @param bool $linkAccessRestrictedPages If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
     * @param array $additionalParams additional query parameters that won't be prefixed like $arguments (overrule $arguments)
     * @param bool $absolute If set, the URI of the rendered link is absolute
     * @param bool $addQueryString If set, the current query parameters will be kept in the URI
     * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
     * @param string $addQueryStringMethod Set which parameters will be kept. Only active if $addQueryString = TRUE
     * @return string Rendered link
     */
    public function render(Tx_PtExtlist_Domain_Model_List_Header_HeaderColumn $header, array $fieldAndDirection, $action='sort', $pageUid = null, $pageType = 0, $noCache = false, $noCacheHash = false, $section = '', $format = '', $linkAccessRestrictedPages = false, array $additionalParams = array(), $absolute = false, $addQueryString = false, array $argumentsToBeExcludedFromQueryString = array(), $addQueryStringMethod = null)
    {
        $sortingFieldParams = array();

        $sortingDirection = Tx_PtExtlist_Domain_QueryObject_Query::invertSortingState($fieldAndDirection['currentDirection']);
        $sortingFieldParams[] = $fieldAndDirection['field'] . ':' . $sortingDirection;

        # echo "current direction for field " . $fieldAndDirection['field'] . " = " . $fieldAndDirection['currentDirection'] . " link direction = " . $sortingDirection;

        $sortingFieldParam = implode(';', $sortingFieldParams);

        $gpArrayViewHelper = new Tx_PtExtlist_ViewHelpers_Namespace_GPArrayViewHelper();
        $argumentArray = $gpArrayViewHelper->buildObjectValueArray($header, 'sortingFields', $sortingFieldParam);

        $this->sessionPersistenceManagerBuilder->getInstance()->addSessionRelatedArguments($argumentArray);

        return parent::render($action, $argumentArray, null, null, null, $pageUid, $pageType, $noCache, $noCacheHash, $section, $format, $linkAccessRestrictedPages, $additionalParams, $absolute, $addQueryString, $argumentsToBeExcludedFromQueryString, $addQueryStringMethod);
    }
}
