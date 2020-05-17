<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Bookmark;

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

use PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder;

/**
 * Class implements factory for export configuration
 *
 * @package Domain
 * @subpackage Configuration\Bookmarks
 * @author Daniel Lienert 
 */
class BookmarkConfigFactory
{
    /**
     * Returns a instance of a bookmark configuration.
     *  
     * @param ConfigurationBuilder $configurationBuilder
     * @return BookmarkConfig
     */
    public static function getInstance(\PunktDe\PtExtlist\Domain\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $bookmarksSettings = $configurationBuilder->getSettingsForConfigObject('bookmarks');
        $bookmarkConfig = new BookmarkConfig($configurationBuilder, $bookmarksSettings);
        return $bookmarkConfig;
    }
}
