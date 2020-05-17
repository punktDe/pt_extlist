<?php


namespace PunktDe\PtExtlist\Domain\Configuration\Pager;

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
 * Class implements configuration for pager
 *
 * @package Domain
 * @subpackage Configuration\Pager
 * @author Michael Knoll 
 * @author Daniel Lienert 
 * @author Christoph Ehscheidt
 * @see Tx_PtExtlist_Tests_Domain_Configuration_Pager_PagerConfigTest
 */
class PagerConfig extends \PunktDe\PtExtlist\Domain\Configuration\AbstractExtlistConfiguration
{
    /**
     * The pager identifier.
     *  
     * @var string
     */
    protected $pagerIdentifier;
    
    
    /**
     * Holds class name for pager
     *
     * @var string
     */
    protected $pagerClassName = '';
    
    
    /**
     * If true, pager is enabled
     *
     * @var boolean
     */
    protected $enabled;
    
    
    /**
     * Holds the templatePath to override default extbase settings.
     *  
     * @var string
     */
    protected $templatePath = null;
    
    
    /**
     * Global value for all filters
     *  
     * @var integer
     */
    protected $itemsPerPage;
    


    protected function init()
    {
        $this->setRequiredValue('pagerIdentifier', 'No Pager Identifier given');

        $this->setRequiredValue('pagerClassName', 'No class name given for pager "' . $this->pagerIdentifier . '" 1280408323');
        if (!class_exists($this->pagerClassName)) {
            throw new \Exception(sprintf('Given pager class %s does not exist or is not loaded!', $this->pagerClassName), 1279541306);
        }

        $this->setBooleanIfExistsAndNotNothing('enabled');
        $this->setValueIfExistsAndNotNothing('templatePath');
        $this->setValueIfExistsAndNotNothing('itemsPerPage');
    }


    /**
     * @param ConfigurationBuilder $configurationBuilder
     * @param string $pagerIdentifier
     * @param array $settings
     */
    public function __construct(ConfigurationBuilder $configurationBuilder, string $pagerIdentifier,  array $settings)
    {
        $settings['pagerIdentifier'] = $pagerIdentifier;
        parent::__construct($configurationBuilder, $settings);
    }
    
    

    /**
     * Returns the pager identifier.
     *  
     * @return string
     */
    public function getPagerIdentifier()
    {
        return $this->pagerIdentifier;
    }
    
    
    
    public function getTemplatePath()
    {
        return $this->templatePath;
    }
    
    /**
     * Returns class name of pager
     *
     * @return string Class name of pager
     */
    public function getPagerClassName()
    {
        return $this->pagerClassName;
    }
    
    
    
    /**
     * Returns settings array of pager
     *
     * @return array Array with settings for pager
     */
    public function getPagerSettings()
    {
        return $this->settings;
    }
    

    
    /**
     * Returns true, if pager is enabled
     *
     * @return bool True, if pager is enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    
    
    /**
     * @return integer itemsPerPage
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
}
