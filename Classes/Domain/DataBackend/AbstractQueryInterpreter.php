<?php


namespace PunktDe\PtExtlist\Domain\DataBackend;

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
 * TODO why is this an abstract class and not an interface?
 * TODO this should be an interface!
 *  
 * Interface for query interpreters
 *  
 * @package Domain
 * @subpackage DataBackend
 * @author Michael Knoll 
 * @author Daniel Lienert 
 */
abstract class AbstractQueryInterpreter
{
    /**
     * Returns translated criteria(s)
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    abstract public static function getCriterias(\PunktDe\PtExtlist\Domain\QueryObject\Query $query);
    
    
    
    /**
     * Returns translated sortings
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    abstract public static function getSorting(\PunktDe\PtExtlist\Domain\QueryObject\Query $query);
    
    
    
    /**
     * Returns translated limit
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    abstract public static function getLimit(\PunktDe\PtExtlist\Domain\QueryObject\Query $query);
    
    
    
     /**
     * Returns translated group by
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query
     */
    abstract public static function getGroupBy(\PunktDe\PtExtlist\Domain\QueryObject\Query $query);
    
    
    
    /**
     * Translates whole query with all keywords etc.
     *
     * @param \PunktDe\PtExtlist\Domain\QueryObject\Query $query Query to be translated
     */
    abstract public static function interpretQuery(\PunktDe\PtExtlist\Domain\QueryObject\Query $query);
}
