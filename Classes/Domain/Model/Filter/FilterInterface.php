<?php

namespace PunktDe\PtExtlist\Domain\Model\Filter;

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

use PunktDe\PtExtbase\State\GpVars\GpVarsAdapter;
use PunktDe\PtExtbase\State\Session\SessionPersistableInterface;
use PunktDe\PtExtbase\State\GpVars\GpVarsInjectableInterface;
use PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig;
use PunktDe\PtExtlist\Domain\DataBackend\DataBackendInterface;
use PunktDe\PtExtlist\Domain\QueryObject\Query;

/**
 * Interface for all filter classes
 *
 * @package Domain
 * @subpackage Model\Filter
 * @author Michael Knoll
 */
interface FilterInterface extends SessionPersistableInterface, GpVarsInjectableInterface
{
    /**
     * Injector for filter configuration
     *
     * @param FilterConfig $filterConfig
     */
    public function _injectFilterConfig(FilterConfig $filterConfig);



    /**
     * Injector for get / post vars adapter
     *
     * @param GpVarsAdapter $gpVarAdapter
     */
    public function _injectGpVarsAdapter(GpVarsAdapter $gpVarAdapter);



    /**
     * Injector for associated data backend
     *
     * @param DataBackendInterface $dataBackend
     */
    public function _injectDataBackend(DataBackendInterface $dataBackend);



    /**
     * Injects filterbox to which this filter is associated to
     *
     * @param Filterbox $filterbox
     */
    public function _injectFilterbox(Filterbox $filterbox);



    /**
     * Returns identifier of filter
     *
     * @return string Identifier of filter
     */
    public function getFilterIdentifier();



    /**
     * Returns identifier of associated list
     *
     * @return string Identifier of associated list
     */
    public function getListIdentifier();



    /**
     * Returns Identifier of filterbox to which this filter belongs
     *
     * @return string Identifier of filterbox to which this filter belongs
     */
    public function getFilterBoxIdentifier();



    /**
     * Returns query object for this filter
     *
     * @return Query Query object that describes criterias for this filter
     */
    public function getFilterQuery();



    /**
     * Initializes filter settings
     *
     * @return void
     */
    public function init();



    /**
     * Resets filter
     *
     * @return void
     */
    public function reset();



    /**
     * Checks whether a filter validates.
     *
     * @return bool True, if filter validates
     */
    public function validate();



    /**
     * Returns validation error for this filter
     *
     * @return string Error message for filter
     */
    public function getErrorMessage();



    /**
     * Returns filter configuration of this filter
     *
     * @return FilterConfig
     */
    public function getFilterConfig();



    /**
     * Returns filter breadcrumb for this filter
     *
     * @return BreadCrumb
     */
    public function getFilterBreadCrumb();



    /**
     * @return array
     */
    public function getGPVarFilterData();



    /**
     * Returns true, if filter is active
     *
     * @return bool True, if filter is active
     */
    public function isActive();



    /**
     * Returns the current filter values of this filter
     *
     * @return mixed
     */
    public function getValue();
}
