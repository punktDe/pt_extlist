<?php
namespace PunktDe\PtExtlist\Controller;

use PunktDe\PtExtlist\Domain\Model\Filter\FilterFactory;

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
 * Class implementing ajax filterData controller.
 *  
 * @package Controller
 * @author Daniel Lienert
 * @author Michael Knoll
 */
class AjaxFilterController extends AbstractController
{
    /**
     * @param string $fullQualifiedFilterIdentifier
     * @return string
     */
    public function getFilterElementAction($fullQualifiedFilterIdentifier)
    {
        $filterConfig = $this->getFilterConfigByFullQualifiedFilterIdentifier($fullQualifiedFilterIdentifier);
        $filter = $this->objectManager->get(FilterFactory::class)->createInstance($filterConfig);
        
        $this->view->assign('filter', $filter);
    }


    /**
     * @param $fullQualifiedFilterIdentifier
     * @return \PunktDe\PtExtlist\Domain\Configuration\Filters\FilterConfig
     */
    protected function getFilterConfigByFullQualifiedFilterIdentifier($fullQualifiedFilterIdentifier)
    {
        $parts = explode('.', $fullQualifiedFilterIdentifier);
        $filterBoxIdentifier = $parts[0];
        $filterIdentifier = $parts[1];

        return $this->configurationBuilder
            ->buildFilterConfiguration()
            ->getFilterBoxConfig($filterBoxIdentifier)
            ->getFilterConfigByFilterIdentifier($filterIdentifier);
    }
}
