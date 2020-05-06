<?php

namespace PunktDe\PtExtlist\Domain\DataBackend\DataSource;

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

use TYPO3\CMS\Core\Database\DatabaseConnection;

/**
 * Class implements data source for typo3 databases
 *  
 * @author Daniel Lienert 
 * @package Domain
 * @subpackage DataBackend\DataSource
 */
class Typo3DataSourceFactory
{
    /**
     * Create instance of typo3 data source
     *
     * @static
     * @param string $dataSourceClassName
     * @param \PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration $dataSourceConfiguration
     * @return \PunktDe\PtExtlist\Domain\DataBackend\DataSource\Typo3DataSource
     */
    public static function createInstance($dataSourceClassName, \PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration $dataSourceConfiguration)
    {
        $dataSource = new $dataSourceClassName($dataSourceConfiguration); /** @var \PunktDe\PtExtlist\Domain\DataBackend\DataSource\AbstractDataSource $dataSource */
        $dataSource->injectDbObject(self::createDataObject());
        $dataSource->initialize();
        return $dataSource;
    }


    /**
     * @static
     * @return DatabaseConnection
     */
    protected static function createDataObject()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
