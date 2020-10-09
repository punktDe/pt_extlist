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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use PunktDe\PtExtlist\Domain\Configuration\DataBackend\DataSource\DatabaseDataSourceConfiguration;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
/**
 * Class implements data source for TYPO3 databases
 *  
 * @author Michael Knoll 
 * @package Domain
 * @subpackage DataBackend\DataSource
 * @see Tx_PtExtlist_Tests_Domain_DataBackend_DataSource_Typo3DataSourceTest
 */
class Typo3DataSource extends MySqlDataSource
{
}

